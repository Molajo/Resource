<?php
/**
 * Controller Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Controller\Api;

use Molajo\Controller\Exception\ControllerException;

/**
 * Controller Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface ControllerInterface
{
    /**
     * Get the current value (or default) of the specified property
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ControllerException
     */
    public function get($key, $default = null);

    /**
     * Set the value of the specified property
     *
     * @param   string $key
     * @param   string $value
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ControllerException
     */
    public function set($key, $value = null);

    /**
     * Get a specified Model key value
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ControllerException
     */
    public function getModel($key, $default = null);

    /**
     * Set a specified Model key value
     *
     * @param   string $key
     * @param   string $value
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ControllerException
     */
    public function setModel($key, $value = null);

    /**
     * Get the current value (or default) of the Model Registry
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ControllerException
     */
    public function getModelRegistry($key, $default = null);

    /**
     * Set the value of the specified Model Registry
     *
     * @param   string $key
     * @param   string $value
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ControllerException
     */
    public function setModelRegistry($key, $value = null);

    /**
     * Get the list of potential plugins identified with this model registry
     *
     * @return  void
     * @since   1.0
     */
    public function setPluginList();

    /**
     * Set Profiler Messages
     *
     * @param   array $attributes
     *
     * @return  $this
     * @since   1.0
     */
    public function setProfilerMessage($attributes = array());
}
