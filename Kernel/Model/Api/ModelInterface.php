<?php
/**
 * Model Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Model\Api;

use Molajo\Model\Exception\ModelException;

/**
 * Model Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface ModelInterface
{
    /**
     * Get the current value (or default) of the specified property
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Model\Exception\ModelException
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
     * @throws  \Molajo\Model\Exception\ModelException
     */
    public function set($key, $value = null);

    /**
     * Return Query Object
     *
     * @return  string
     * @since   1.0
     */
    public function getQueryString();
}
