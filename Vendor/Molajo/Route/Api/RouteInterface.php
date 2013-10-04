<?php
/**
 * Route Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Route\Api;

use Molajo\Route\Exception\RouteException;

/**
 * Route Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface RouteInterface
{
    /**
     * Determine if secure protocol required and in use
     *
     * @return  object
     * @throws  \Molajo\Route\Exception\RouteException
     * @since   1.0
     */
    public function verifySecureProtocol();

    /**
     * Determine if request is for home page
     *
     * @return  object
     * @throws  \Molajo\Route\Exception\RouteException
     * @since   1.0
     */
    public function verifyHome();

    /**
     * Set Action from HTTP Method
     *
     * @return  object
     * @throws  \Molajo\Route\Exception\RouteException
     * @since   1.0
     */
    public function setRequest();

    /**
     * Set Route
     *
     * @return  object
     * @throws  \Molajo\Route\Exception\RouteException
     * @since   1.0
     */
    public function setRoute();
}
