<?php
/**
 * Adapter for Route
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Route;

use Molajo\Route\Api\RouteInterface;
use Molajo\Route\Exception\RouteException;

/**
 * Adapter for Route
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class Adapter implements RouteInterface
{
    /**
     * Route Handler
     *
     * @var     object  Molajo\Route\Api\RouteInterface
     * @since   1.0
     */
    protected $route;

    /**
     * Constructor
     *
     * @param  RouteInterface $route
     *
     * @since   1.0
     */
    public function __construct(
        RouteInterface $route = null
    ) {
        $this->route = $route;
    }

    /**
     * Determine if secure protocol required and in use
     *
     * @return  object
     * @throws  \Molajo\Route\Exception\RouteException
     * @since   1.0
     */
    public function verifySecureProtocol()
    {
        return $this->route->verifySecureProtocol();
    }

    /**
     * Determine if request is for home page
     *
     * @return  object
     * @throws  \Molajo\Route\Exception\RouteException
     * @since   1.0
     */
    public function verifyHome()
    {
        return $this->route->verifyHome();
    }

    /**
     * Set Action from HTTP Method
     *
     * @return  object
     * @throws  \Molajo\Route\Exception\RouteException
     * @since   1.0
     */
    public function setRequest()
    {
        return $this->route->setRequest();
    }

    /**
     * Set Route
     *
     * @return  object
     * @throws  \Molajo\Route\Exception\RouteException
     * @since   1.0
     */
    public function setRoute()
    {
        return $this->route->setRoute();
    }
}
