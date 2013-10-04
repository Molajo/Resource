<?php
/**
 * Authentication Anonymous
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Service\Api;


use Molajo\User\Api\AuthenticationInterface;
use Molajo\User\Exception\AuthenticationException;

/**
 * Authentication Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Http implements AuthenticationInterface
{
    /**
     * Username
     *
     * @var     string
     * @since   1.0
     */
    public $username;

    /**
     * Password
     *
     * @var     string
     * @since   1.0
     */
    public $password;

    /**
     * Realm
     *
     * @var     string
     * @since   1.0
     */
    public $realm;

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
        $username    = $req->headers('PHP_AUTH_USER');
        $password    = $req->headers('PHP_AUTH_PW');
        $this->realm = $realm;

        if ($authUser && $authPass && $authUser === $this->username && $authPass === $this->password) {
        } else {
            $res->status(401);
            $res->header('WWW-Authenticate', sprintf('Basic realm="%s"', $this->realm));
            exit();
        }
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
        return $this;
    }
}
