<?php
/**
 * Session Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\User\Api;

use Molajo\User\Exception\SessionException;

/**
 * Session Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface SessionInterface
{
    /**
     * Gets the value for a key
     *
     * @param   string $key
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\User\Exception\SessionException
     */
    public function getSession($key);

    /**
     * Sets the value for key
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\User\Exception\SessionException
     */
    public function setSession($key, $value);

    /**
     * Delete a single or all session keys
     *
     * @param   null|string $key
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\User\Exception\SessionException
     */
    public function deleteSession($key);
}
