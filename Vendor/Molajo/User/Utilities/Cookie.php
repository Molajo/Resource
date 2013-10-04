<?php
/**
 * Cookie
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\User\Utilities;

use stdClass;
use Exception;
use Molajo\User\Exception\CookieException;
use Molajo\User\Api\CookieInterface;

/**
 * Cookie Class
 *
 * @see       setCookie()
 * @link      http://www.faqs.org/rfcs/rfc6265.html
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Cookie implements CookieInterface
{
    /**
     * Request Cookies
     *
     * @var    array
     * @since  1.0
     */
    protected $request_cookies;

    /**
     * Response Cookies
     *
     * @var    array
     * @since  1.0
     */
    protected $response_cookies;

    /**
     * Expire
     *
     * @var    string
     * @since  1.0
     */
    protected $expire = 2628000;

    /**
     * Path
     *
     * @var    string
     * @since  1.0
     */
    protected $path = null;

    /**
     * Domain
     *
     * @var    string
     * @since  1.0
     */
    protected $domain = '';

    /**
     * Secure
     *
     * @var    bool
     * @since  1.0
     */
    protected $secure = false;

    /**
     * Http Only
     *
     * @var    bool
     * @since  1.0
     */
    protected $http_only = false;

    /**
     * Property Array
     *
     * @var    array
     * @since  1.0
     */
    protected $property_array = array(
        'expire',
        'path',
        'domain',
        'secure',
        'http_only'
    );

    /**
     * Construct
     *
     * @param   array $options
     *
     * @since   1.0
     */
    public function __construct(
        $request_cookies,
        $response_cookies,
        $expire = 2628000,
        $path = null,
        $domain = null,
        $secure = 0,
        $http_only = false
    ) {
        if (is_array($request_cookies)) {
            $this->request_cookies = $request_cookies;
        } else {
            $this->request_cookies = array();
        }

        if (is_array($response_cookies)) {
            $this->response_cookies = $response_cookies;
        } else {
            $this->response_cookies = array();
        }

        if ($expire === null) {
            $expire = 2628000;
        }
        $this->expire = $expire;

        if ($path === null) {
            $path = '';
        }
        $this->path = $path;

        if ($domain === null) {
            $domain = '';
        }
        $this->domain = $domain;

        if ($secure === null) {
            $secure = '';
        }
        $this->secure = $secure;

        if ($http_only === true) {
        } else {
            $this->http_only = false;
        }

        if (is_array($this->request_cookies)
            && count($this->request_cookies) > 0
        ) {
        } else {
            $this->request_cookies = $_COOKIE;
        }
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
        if (isset($this->request_cookies[$name])) {
            return $this->request_cookies[$name];
        }

        return false;
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
        if ($minutes == 0) {
            $minutes = $this->$minutes;
        }
        $expire = time() + $minutes;

        if ($path == '') {
            $path = $this->path;
        }

        if ($domain == '') {
            $domain = $this->domain;
        }

        $cookie            = new stdClass();
        $cookie->name      = $name;
        $cookie->value     = $value;
        $cookie->expire    = $expire;
        $cookie->path      = $path;
        $cookie->domain    = $domain;
        $cookie->secure    = $secure;
        $cookie->http_only = $http_only;

        $this->response_cookies[$name] = $cookie;

        return $this;
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
        $name = (string)$name;

        if (isset($this->response_cookies[$name])) {
            unset($this->response_cookies[$name]);
        }

        if (isset($_COOKIE[$name])) {
            unset($_COOKIE[$name]);
        }

        return $this;
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
        if (count($this->response_cookies) === 0) {
            return $this;
        }

        foreach ($this->response_cookies as $cookie) {
            $this->sendCookie($cookie);
        }

        return $this;
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
        try {
            setcookie(
                $cookie->name,
                $cookie->value,
                $cookie->expire,
                $cookie->path,
                $cookie->domain,
                $cookie->secure,
                $cookie->http_only
            );

        } catch (Exception $e) {
            throw new CookieException
            ('Cookie Response: sendCookie failed for : ' . $cookie->name . ' ' . $e->getMessage());
        }

        return $this;
    }
}
