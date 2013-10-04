<?php
/**
 * Inversion of Control Container
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\IoC\Api;

use Molajo\IoC\Exception\ContainerException;

/**
 * Inversion of Control Container
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface IoCContainerInterface
{
    /**
     * Get Instance from Container or return false
     *
     * @param   string $container_key
     *
     * @return bool|null|object
     * @since   1.0
     */
    public function getService($container_key);

    /**
     * Set the existing service instance with the passed in object
     *
     * @param   string      $container_key
     * @param   object      $instance
     * @param   null|string $service_namespace
     *
     * @return  $this
     * @since   1.0
     */
    public function setService($container_key, $instance, $alias = null);

    /**
     * Clone the existing service instance and return the cloned instance
     *
     * @param   string $container_key
     *
     * @return  null|object
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ContainerException
     */
    public function cloneService($container_key);

    /**
     * Remove the existing service instance
     *
     * @param   string $container_key
     *
     * @return  $this
     * @since   1.0
     */
    public function removeService($container_key);
}
