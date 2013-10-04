<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Login;

use Molajo\Plugin\AbstractPlugin;
use Molajo\Plugin\Api\AuthenticateEventInterface;
use Molajo\Plugin\Exception\AuthenticateEventException;

/**
 * login
 *
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class LoginPlugin extends AbstractPlugin implements AuthenticateEventInterface
{
    /**
     * Before Authenticating the Login Process
     *
     * @return  $this
     * @since   1.0
     * @throws  AuthenticateEventException
     */
    public function onBeforeLogin()
    {
        return false;
    }

    /**
     * After Authenticating the Login Process
     *
     * @return  $this
     * @since   1.0
     * @throws  AuthenticateEventException
     */
    public function onAfterLogin()
    {
        return false;
    }
}
