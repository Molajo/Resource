<?php
/**
 * User Authorisation Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\User\Api;

use Molajo\User\Exception\AuthorisationException;

/**
 * User Authorisation Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface AuthorisationInterface
{
    /**
     * Verify User Permission to take Action on Resource
     *
     * @param   int    $action_id
     * @param   int    $resource_id
     * @param   string $type
     *
     * @return  bool
     * @since   1.0
     * @throws  \Molajo\User\Exception\AuthorisationException
     */
    public function isUserAuthorised($action_id, $resource_id, $type = 'Catalog');
}
