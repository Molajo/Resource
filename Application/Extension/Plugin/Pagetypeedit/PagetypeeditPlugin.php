<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Pagetypeedit;

use Molajo\Plugin\AbstractPlugin;


/**
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class PagetypeeditPlugin extends AbstractPlugin
{
    /**
     * Prepares Configuration Data
     *
     * @return boolean
     * @since    1.0
     */
    public function onBeforeParse()
    {
        if (strtolower($this->get('page_type', '', 'parameters')) == 'edit') {
        } else {
            return true;
        }

        $resource_model_type = $this->get('model_type', '', 'parameters');
        $resource_model_name = $this->get('model_name', '', 'parameters');

        //@todo - submenu
        $this->registry->set('Page', 'SectionSubmenu', array());

        /** Form Service */
        $form = Services::Form();

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

        $form->set('data', $this->registry->get('Dataobject', 'Primary'));

        /** Parameters */
        $form->set('Parameters', $this->registry->getArray('ResourcesSystemParameters'));
        $form->set('parameter_fields', $this->registry->get('ResourcesSystem', 'Parameters'));

        /** Metadata */
        $form->set('Metadata', $this->registry->getArray('ResourcesSystemMetadata'));
        $form->set('metadata_fields', $this->registry->get('ResourcesSystem', 'Metadata'));

        /** Customfields */
        $form->set('Customfields', $this->registry->getArray('ResourcesSystemCustomfields'));
        $form->set('customfields_fields', $this->registry->get('ResourcesSystem', 'Customfields'));
        echo $this->registry->get('ResourcesSystemParameters', 'edit_array');

        /** Build Fieldsets and Fields */
        $pageFieldsets = $form->execute($this->registry->get('ResourcesSystemParameters', 'edit_array'));

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
}
