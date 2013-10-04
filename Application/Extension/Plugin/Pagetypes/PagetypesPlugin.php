<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Pagetypes;

use Molajo\Plugin\AbstractPlugin;


/**
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class PagetypesPlugin extends AbstractPlugin
{
    /**
     * Generates list of Pagetypes
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterRoute()
    {
        $folders = glob(BASE_FOLDER . '/Application/Extension' . '/Menuitem/*');
        if (count($folders) === 0 || $folders === false) {
            $page_type_list = array();
        } else {
            $page_type_list = $folders;
        }

        $folders = glob(BASE_FOLDER . '/Vendor' . '/Molajo' . '/Menuitem/*');
        if (count($folders) === 0 || $folders === false) {
        } else {
            $new            = array_merge($page_type_list, $folders);
            $page_type_list = $new;
        }

        $newer = array_unique($page_type_list);
        sort($newer);

        $page_types = array();
        foreach ($newer as $item) {
            $temp_row        = new \stdClass();
            $temp_row->value = $item;
            $temp_row->id    = $item;
            $page_types[]    = $temp_row;
        }

        $this->registry->set('Datalist', 'Pagetypes', $page_types);

        return true;
    }
}
