<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Pagetypedashboard;

use Molajo\Plugin\AbstractPlugin;


/**
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class PagetypedashboardPlugin extends AbstractPlugin
{
    /**
     * Prepares data for Pagetypedashboard
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeParse()
    {
        if (strtolower($this->get('page_type', '', 'parameters')) == 'dashboard') {
        } else {
            return true;
        }

        $portletOptions = $this->registry->get('parameters', 'dashboard_portlet');
        if (trim($portletOptions) == '') {
            return true;
        }

        $portletOptionsArray = explode(',', $portletOptions);

        if (count($portletOptionsArray) == 0
            || $portletOptionsArray === false
        ) {
        } else {
            $this->portlets($portletOptionsArray);
        }

        /** Create Tabs */
        $namespace = 'Pagetypedashboard';

        $page_array = $this->get('dashboard_page_array');

        $tabs = Services::Form()->setPageArray(
            $this->get('model_type', '', 'parameters'),
            $this->get('model_name', '', 'parameters'),
            $namespace,
            $page_array,
            'dahboard_page_',
            'Pagetypedashboard',
            'Pagetypedashboardtab',
            null,
            null
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
            $tabs
        );

        return true;
    }

    public function portlets($portletOptionsArray)
    {
        $i               = 1;
        $portletIncludes = '';
        foreach ($portletOptionsArray as $portlet) {

            $portletIncludes .= '<include type=template name='
                . ucfirst(strtolower(trim($portlet)))
                . ' wrap=Portlet wrap_id=portlet'
                . $i
                . ' wrap_class=portlet/>'
                . chr(13);

            $i ++;
        }

        $this->registry->set('xxxx', 'PortletOptions', $portletIncludes);

        if ($this->get('model_type', '', 'parameters') == '' || $this->get('model_name', '', 'parameters') == '') {
            return true;
        }

        $this->setOptions();
    }

    /**
     * Create Toolbar Registry based on Authorized Access
     *
     * @return boolean
     * @since  1.0
     */
    protected function setDashboardPermissions()
    {
    }

    /**
     * Options: creates a list of Portlets available for this Dashboard
     *
     * @return boolean
     * @since   1.0
     */
    protected function setOptions()
    {
        $results = Services::Text()->getDatalist('Portlets', 'Datalist', $this->parameters);
        if ($results === false) {
            return true;
        }

        if (isset($this->parameters->selected)) {
            $selected = $this->parameters->selected;
        } else {
            $selected = null;
        }

        $list = Services::Text()->buildSelectlist(
            'Portlets',
            $results[0]->listitems,
            $results[0]->multiple,
            $results[0]->size,
            $selected
        );

        if (count($list) == 0 || $list === false) {
            //throw exception
        }

        $temp_query_results = array();

        foreach ($list as $item) {

            $temp_row           = new \stdClass();
            $temp_row->id       = $item->id;
            $temp_row->value    = $this->language->translate(
                ucfirst(strtolower(substr($item->value, 7, strlen($item->value))))
            );
            $temp_row->selected = '';
            $temp_row->multiple = '';
            $temp_row->listname = 'Portlets';

            $temp_query_results[] = $temp_row;
        }
        $this->registry->set('Datalist', 'Portlets', $temp_query_results);

        return true;
    }
}
