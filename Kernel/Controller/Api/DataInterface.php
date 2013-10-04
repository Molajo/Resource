<?php
/**
 * Data Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Controller\Api;

use Molajo\Controller\Exception\DataException;

/**
 * Data Interface
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface DataInterface
{
    /**
     * Get the current value of the specified key, or get all values
     *
     * @param   string $key
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\DataException
     */
    public function get($key = null);

    /**
     * Set the current value (or default) of the specified key
     *
     * @param   string     $key
     * @param   null|mixed $value
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\DataException
     */
    public function set($key, $value = null);
}
