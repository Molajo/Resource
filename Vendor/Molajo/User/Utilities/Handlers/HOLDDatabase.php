<?php
//include __DIR__ . '/' . 'PasswordLib.phar';

/**
 * Authentication
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\User\Authentication;

use Molajo\User\Api\AuthenticationInterface;
use Molajo\User\Exception\AuthenticationException;

/**
 * Anonymous Authentication Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Database implements AuthenticationInterface
{
    /**
     * Login
     *
     * @param   array $credentials
     *
     * @return  $this
     * @since   1.0
     * @throws  AuthenticationException
     */
    public function login($credentials = array())
    {
        return $this;


        $password = null;
        if (isset($credentials['password'])) {
            $password = $credentials['password'];
        }

        $username = null;
        if (isset($credentials['username'])) {
            $username = $credentials['username'];
        }

        $lib      = new PasswordLib / PasswordLib();
        $verified = $lib->verifyPasswordHash($password);

        if ($verified === true) {
        } else {
            throw new AuthenticationException
            ('Authentication Password is incorrect.', self::INVALID_CREDENTIAL);
        }

        $actual_password = null;
        if (isset($credentials['actual_password'])) {
            $actual_password = $credentials['actual_password'];
        }

        $results = $this->calculateHash($password, $actual_password);
        if ($results === true) {
        } else {
            throw new AuthenticationException
            ('The password is incorrect.', self::INVALID_CREDENTIAL);
        }

        return $this;
    }

    /**
     * Log out
     *
     * @return  $this
     * @since   1.0
     * @throws  AuthenticationException
     */
    public function logout()
    {
        return;

        $this->getSession()->clear();

        $this->redirect_to_idRoute('Homepage');

        return $this;
    }
}
