<?php
/**
 * Data Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\User\Api;

use Molajo\User\Exception\DataException;

/**
 * Data Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface UserDataInterface
{
    /**
     * Get the current value (or default) of the specified key or all User Data for null key
     * The secondary key can be used to designate a customfield group or child object
     *
     * @param   null|string $key
     * @param   null|string $secondary_key
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\User\Exception\DataException
     */
    public function getUserData($key = null, $secondary_key = null);

    /**
     * Set the value of a specified key
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  $this
     * @since   1.0
     * @throws  DataException
     */
    public function setUserData($key, $value = null);

    /**
     * Save the User
     *
     * @return  $this
     * @since   1.0
     * @throws  DataException
     */
    public function updateUser();

    /**
     * Delete the User
     *
     * @return  $this
     * @since   1.0
     * @throws  DataException
     */
    public function deleteUser();
}
