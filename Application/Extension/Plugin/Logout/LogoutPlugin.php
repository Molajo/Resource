<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Logout;

use Molajo\Plugin\Api\AuthenticateEventInterface;
use Molajo\Plugin\AuthenticateEventPlugin;
use Molajo\Plugin\Exception\AuthenticateEventException;

/**
 * login
 *
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class LogoutPlugin extends AuthenticateEventPlugin implements AuthenticateEventInterface
{
    /**
     * Before Authenticating the Logout Process
     *
     * @return  $this
     * @since   1.0
     * @throws  AuthenticateEventException
     */
    public function onBeforeLogout()
    {
        return false;
    }

    /**
     * After Authenticating the Logout Process
     *
     * @return  $this
     * @since   1.0
     * @throws  AuthenticateEventException
     */
    public function onAfterLogout()
    {
        return false;
    }
}
