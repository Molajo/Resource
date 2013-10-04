<?php
/**
 * Weather Plugin
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Plugin\Weather\WeatherPlugin;

/**
 * Weather Plugin
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class WeatherPlugin
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
