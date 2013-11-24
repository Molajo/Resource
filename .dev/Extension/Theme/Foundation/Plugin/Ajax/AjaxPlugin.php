<?php
/**
 * Ajax Plugin
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Plugin\Ajax\AjaxPlugin;

/**
 * Ajax Plugin
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class AjaxPlugin
{
    /**
     * onBeforeParse
     *
     * @return void
     * @since   1.0
     */
    public function onBeforeParse()
    {
        return true;
    }
}
