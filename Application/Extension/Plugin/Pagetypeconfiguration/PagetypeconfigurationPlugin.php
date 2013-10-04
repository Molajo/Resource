<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Pagetypeconfiguration;

use Molajo\Plugin\AbstractPlugin;


/**
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class PagetypeconfigurationPlugin extends AbstractPlugin
{
    /**
     * Prepares Configuration Data
     *
     * @return boolean
     * @since    1.0
     */
    public function onBeforeParse()
    {
        if (strtolower($this->get('page_type', '', 'parameters')) == PAGE_TYPE_CONFIGURATION) {
        } else {
            return true;
        }

        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry(
            $this->get('model_type', '', 'parameters'),
            $this->get('model_name', '', 'parameters'),
            1
        );
        $controller->set('get_customfields', 2, 'model_registry');
        $controller->set('use_special_joins', 1, 'model_registry');
        $controller->set('process_plugins', 1, 'model_registry');

        /** Array - All Pages in Set
         * 2, {{Access,noformfields}}{{Editor,editor}}{{Grid,grid}}{{Form,form}}{{Item,item}}{{List,list}}
         */
        $temp  = $this->get('configuration_array', '', 'parameters');
        $pages = explode('{{', $temp);

        /** Determine Current Page of Set */
        $temp    = $this->get('request_filters', array(), 'parameters');
        $filters = explode(',', $temp);

        $page = 1;
        if ($filters == '' || count($filters) == 0) {
            $page = 1;
        } else {
            foreach ($filters as $x) {
                if (trim($x) == '') {
                } else {
                    $pair = explode(':', $x);
                    if (strtolower($pair[0]) == 'page') {
                        $page = (int)$pair[1];
                        break;
                    }
                }
            }
        }

        if ($page < count($pages)) {
        } else {
            $page = 1;
        }
        $page_number = $page;

        /** Resource Submenu: Links to various Form Pages (Tabs) - ex. Basic, Metadata, Fields, etc. */
        $pageArray = array();
        $i         = 0;
        foreach ($pages as $item) {

            if ($item == '') {
            } else {
                $i ++;
                $temp_row     = new \stdClass();
                $temp_row->id = $i;
                if ($i == $page_number) {
                    $temp_row->current = 1;
                } else {
                    $temp_row->current = 0;
                }

                $temp_row->id    = $i;
                $temp_row->title = substr($item, 0, strpos($item, ','));
                $temp_row->url   = $this->registry->get('Page', 'page_url') . '/page/' . $i;

                $pageArray[] = $temp_row;
            }
        }
        $this->registry->set('Page', 'SectionSubmenu', $pageArray);

        /** Even tho links are created to each form page, generate Form for the current page, only */
        $current_page = '{{' . $pages[$page_number];

        /** Build Fieldsets and Fields */
        $form = Services::Form();

        /** Resource
         * 1. {{Basic,basic}}
         * 3. {{Fields,customfields,Customfields}}
         * 4. {{Editor,editor}}
         * 5. {{Grid,grid}}
         * 6. {{Form,form}}
         * 7. {{Item,item}}
         * 8. {{List,list}}
         */
        if ($page_number == 1 || $page_number == 3 || $page_number == 4
            || $page_number == 5 || $page_number == 6 || $page_number == 7
            || $page_number == 8
        ) {

            $pageFieldsets = $this->getResourceConfiguration($form, $current_page);
        }

        /** Set the View Model Parameters and Populate the Registry used as the Model */
        $current_page = $form->getPages(
            $pageFieldsets[0]->page_array,
            $pageFieldsets[0]->page_count
        );

        $controller->set('request_model_type', $this->get('model_type', '', 'parameters'), 'model_registry');
        $controller->set('request_model_name', $this->get('model_name', '', 'parameters'), 'model_registry');

        $controller->set('model_type', 'Dataobject', 'model_registry');
        $controller->set('model_name', 'Primary', 'model_registry');
        $controller->set('model_query_object', 'list', 'model_registry');

        $controller->set('model_type', 'list', 'model_registry');
        $controller->set('model_name', 'Primary', 'model_registry');

        $this->registry->set(
            'Primary',
            'Data',
            $current_page
        );

        return true;
    }

    /**
     * Prepares Configuration Data
     *
     * @return boolean
     * @since    1.0
     */
    protected function getResourceConfiguration($form, $current_page)
    {
        $this->content_helper->getResourceExtensionParameters(
            (int)$this->parameters->criteria_extension_instance_id
        );

        /** Set Input */
        $form->set('namespace', strtolower($this->get('page_type', '', 'parameters')));

        $form->set('model_type', $this->get('model_type', '', 'parameters'));
        $form->set('model_name', $this->get('model_name', '', 'parameters'));
        $form->set(
            'model_registry_name',
            ucfirst(strtolower($this->get('model_name', '', 'parameters'))) . ucfirst(
                strtolower($this->get('model_type', '', 'parameters'))
            )
        );

        $form->set('extension_instance_id', $this->get('criteria_extension_instance_id'));

        $form->set('data', array());

        /** Parameters */
        $parameters = $this->registry->getArray('ResourcesSystemParameters');
        $array2     = $this->registry->getArray('Parameters');

        foreach ($array2 as $key => $value) {
            if (substr($key, 0, strlen('Configuration')) == 'Configuration') {
                $parameters[$key] = $value;
            }
        }

        $form->set('Parameters', $parameters);
        $form->set('parameter_fields', $this->registry->get('ResourcesSystem', 'Parameters'));

        /** Metadata */
        $form->set('Metadata', $this->registry->getArray('ResourcesSystemMetadata'));
        $form->set('metadata_fields', $this->registry->get('ResourcesSystem', 'Metadata'));

        /** Customfields */
        $form->set('Customfields', $this->registry->getArray('ResourcesSystemCustomfields'));
        $form->set('customfields_fields', $this->registry->get('ResourcesSystem', 'Customfields'));

        /** Build Fieldsets and Fields */

        return $form->execute($current_page);
    }
}
