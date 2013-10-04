<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Pagetypeitem;

use Molajo\Plugin\AbstractPlugin;


/**
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class PagetypeitemPlugin extends AbstractPlugin
{
    /**
     * Switches the model registry for an item since the Content Query already retrieved the data
     *  and saved it into the registry
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeParse()
    {
        if (strtolower($this->get('catalog_page_type', '', 'parameters')) == strtolower(PAGE_TYPE_ITEM)) {
        } else {
            return true;
        }

        return true;
    }
}
