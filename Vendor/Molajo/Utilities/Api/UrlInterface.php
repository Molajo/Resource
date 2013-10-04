<?php
/**
 * Url Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Utilities\Api;

/**
 * Session Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface UrlInterface
{
    /**
     * Register the Service
     *
     * @return void
     * @since   1.0
     */
    public function create();

    /**
     * Instantiate Service Class
     *
     * @return void
     * @since   1.0
     */
    public function read();

    /**
     * Instantiate Service Class
     *
     * @return void
     * @since   1.0
     */
    public function write();

}
