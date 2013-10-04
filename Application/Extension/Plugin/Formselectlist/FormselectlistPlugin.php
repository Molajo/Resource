<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Formselectlist;

use Molajo\Plugin\AbstractPlugin;


/**
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class FormselectlistPlugin extends AbstractPlugin
{
    /**
     * Prepares listbox contents
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeInclude()
    {
        $results = $this->registry->get('Template', $this->get('template_view_path_node', '', 'parameters'));
        if (count($results) > 0) {
            return true;
        }

        $datalist = $this->get('datalist', '', 'parameters');
        if ($datalist == '') {
            return true;
        }

        $temp_query_results = $this->registry->get('Datalist', $datalist);

        if (count($temp_query_results) > 0) {

        } else {
            if ($datalist === false || trim($datalist) == '') {
                return true;
            }

            $results = Services::Text()->getDatalist($datalist, 'Datalist', $this->get('parameters'));
            if ($results === false) {
                return true;
            }

            $selected = $this->get('selected', null, 'parameters');

            $temp_query_results = Services::Text()->buildSelectlist(
                $datalist,
                $results[0]->listitems,
                $results[0]->multiple,
                $results[0]->size,
                $this->get('selected', null, 'parameters')
            );
        }

        $this->registry->set(
            'Template',
            $this->get('template_view_path_node', '', 'parameters'),
            $temp_query_results
        );

        return true;
    }

    /**
     * Remove Registry just rendered
     *
     * @return  object
     * @since   1.0
     */
    public function onAfterInclude()
    {
        $this->registry->delete('Template', $this->get('template_view_path_node', '', 'parameters'));

        return $this;
    }
}
