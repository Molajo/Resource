<?php
/**
 * User Facade to access to all user functions and data
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\User;

use Molajo\User\Exception\DataException;
use Molajo\User\Exception\AuthorisationException;
use Molajo\User\Exception\CookieException;
use Molajo\User\Exception\SessionException;
use Molajo\User\Api\UserInterface;
use Molajo\User\Api\UserDataInterface;
use Molajo\User\Api\AuthorisationInterface;
use Molajo\User\Api\SessionInterface;
use Molajo\User\Api\FlashMessageInterface;
use Molajo\User\Api\ActivityInterface;
use Molajo\User\Api\CookieInterface;

/**
 * User Facade to access to all user functions and data
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class User implements UserInterface
{
    /**
     * User Data
     *
     * @var     object  Molajo\User\Api\UserDataInterface
     * @since   1.0
     */
    protected $userdata;

    /**
     * Session
     *
     * @var     object  Molajo\User\Api\SessionInterface
     * @since   1.0
     */
    protected $session;

    /**
     * Flash Message
     *
     * @var     object  Molajo\User\Api\FlashMessageInterface
     * @since   1.0
     */
    protected $flashmessage;

    /**
     * Cookie
     *
     * @var     object  Molajo\User\Api\CookieInterface
     * @since   1.0
     */
    protected $cookie;

    /**
     * Activity
     *
     * @var     object  Molajo\User\Api\ActivityInterface
     * @since   1.0
     */
    protected $activity;

    /**
     * Constructor
     *
     * @param  UserDataInterface     $userdata
     * @param  SessionInterface      $session
     * @param  FlashMessageInterface $flashmessage
     * @param  CookieInterface       $cookie
     * @param  ActivityInterface     $activity
     *
     * @since  1.0
     */
    public function __construct(
        UserDataInterface $userdata,
        SessionInterface $session,
        FlashMessageInterface $flashmessage,
        CookieInterface $cookie = null,
        ActivityInterface $activity = null
    ) {
        $this->data         = $userdata;
        $this->session      = $session;
        $this->flashmessage = $flashmessage;
        $this->cookie       = $cookie;
        $this->activity     = $activity;
    }

    /**
     * GetDate
     *
     * @return  object  DateTime
     * @since   1.0
     * @throws  \Molajo\User\Exception\DataException
     */
    public function getDate()
    {
        $this->data->getDate();
    }

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
    public function getUserData($key = null, $secondary_key = null)
    {
        return $this->data->getUserData($key, $secondary_key);
    }

    /**
     * Set the value of a specified key
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\User\Exception\DataException
     */
    public function setUserData($key, $value = null)
    {
        return $this->data->setUserData($key, $value);
    }

    /**
     * Get User Customfields
     *
     * @param    string $key
     * @param    mixed  $default
     *
     * @returns  $this
     * @since    1.0
     * @throws   \Molajo\User\Exception\DataException
     */
    public function getUserCustomfields($key, $default = null)
    {
        return $this->data->getUserCustomfields($key, $default);
    }

    /**
     * Set User Customfields
     *
     * @param    string $key
     * @param    mixed  $value
     *
     * @return   $this
     * @since    1.0
     * @throws   \Molajo\User\Exception\DataException
     */
    public function setUserCustomfields($key, $value = null)
    {
        return $this->data->setUserCustomfields($key, $value);
    }

    /**
     * Get User Parameters
     *
     * @param    string $key
     * @param    mixed  $default
     *
     * @returns  $this
     * @since    1.0
     * @throws   \Molajo\User\Exception\DataException
     */
    public function getUserParameters($key, $default = null)
    {
        return $this->data->getUserParameters($key, $default);
    }

    /**
     * Set User Parameters
     *
     * @param    string $key
     * @param    mixed  $value
     *
     * @return   $this
     * @since    1.0
     * @throws   \Molajo\User\Exception\DataException
     */
    public function setUserParameters($key, $value = null)
    {
        return $this->data->setUserParameters($key, $value);
    }

    /**
     * Get User Metadata
     *
     * @param    string $key
     * @param    mixed  $default
     *
     * @return   $this
     * @since    1.0
     * @throws   \Molajo\User\Exception\DataException
     */
    public function getUserMetadata($key, $default = null)
    {
        return $this->data->getUserMetadata($key, $default);
    }

    /**
     * Set User Metadata
     *
     * @param    string $key
     * @param    mixed  $value
     *
     * @return   $this
     * @since    1.0
     * @throws   \Molajo\User\Exception\DataException
     */
    public function setUserMetadata($key, $value = null)
    {
        return $this->data->setUserMetadata($key, $value);
    }

    /**
     * Save the User
     *
     * @return  $this
     * @since   1.0
     * @throws  DataException
     */
    public function updateUser()
    {
        return $this->data->updateUser();
    }

    /**
     * Delete the User
     *
     * @return  $this
     * @since   1.0
     * @throws  DataException
     */
    public function deleteUser()
    {
        return $this->data->deleteUser();
    }

    /**
     * Gets the value for a key
     *
     * @param   string $key
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\User\Exception\SessionException
     */
    public function getSession($key)
    {
        return $this->session->getSession($key);
    }

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
    public function setSession($key, $value)
    {
        return $this->session->setSession($key, $value);
    }

    /**
     * Delete a single or all session keys
     *
     * @param   null|string $key
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\User\Exception\SessionException
     */
    public function deleteSession($key)
    {
        return $this->session->deleteSession($key);
    }

    /**
     * Get Flash Messages for User, all or by Type
     *
     * @param   null|string $type (Success, Notice, Warning, Error)
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\User\Exception\FlashMessageException
     */
    public function getFlashMessage($type = null)
    {
        return $this->flashmessage->getFlashMessage($type);
    }

    /**
     * Save a Flash Message (User Message)
     *
     * @param   string $type (Success, Notice, Warning, Error)
     * @param   string $message
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\User\Exception\FlashMessageException
     */
    public function setFlashMessage($type, $message)
    {
        return $this->flashmessage->setFlashMessage($type, $message);
    }

    /**
     * Delete Flash Messages, all or by type
     *
     * @param   null|string $type
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\User\Exception\FlashMessageException
     */
    public function deleteFlashMessage($type = null)
    {
        return $this->flashmessage->deleteFlashMessage($type);
    }

    /**
     * Get an HTTP Cookie
     *
     * @param           $name
     *
     * @link    http://www.faqs.org/rfcs/rfc6265.html
     * @return  $this
     * @since   1.0
     */
    public function getCookie($name)
    {
        return $this->cookie->getCookie($name);
    }

    /**
     * Sets an HTTP Cookie to be sent with the HTTP response
     *
     * @param           $name
     * @param   null    $value
     * @param   int     $minutes
     * @param   string  $path
     * @param   string  $domain
     * @param   bool    $secure
     * @param   bool    $http_only
     *
     * @return  $this
     * @since   1.0
     */
    public function setCookie(
        $name,
        $value = null,
        $minutes = 0,
        $path = '/',
        $domain = '',
        $secure = false,
        $http_only = false
    ) {
        return $this->cookie->setCookie
            (
                $name,
                $value,
                $minutes,
                $path,
                $domain,
                $secure,
                $http_only
            );
    }

    /**
     * Delete a cookie
     *
     * @param   string $name
     *
     * @return  $this
     * @since   1.0
     * @throws  CookieException
     */
    public function deleteCookie($name)
    {
        return $this->cookie->deleteCookie($name);
    }

    /**
     * sendCookies
     *
     * @return  $this
     * @since   1.0
     * @throws  CookieException
     */
    public function sendCookies()
    {
        return $this->cookie->sendCookies();
    }

    /**
     * sendCookie
     *
     * @param   object $cookie
     *
     * @return  $this
     * @since   1.0
     * @throws  CookieException
     */
    protected function sendCookie($cookie)
    {
        return $this->cookie->sendCookie($cookie);
    }
}
