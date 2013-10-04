<?php
/**
 * Http Redirect Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\Http\Api;

use Molajo\Http\Exception\RedirectException;

/**
 * Http Redirect Interface
 *
 * http://tools.ietf.org/html/rfc2616#section-10.3
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Interface RedirectInterface
{
    /**
     * Get the current value (or default) of the specified key
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  RedirectException
     */
    public function get($key = null, $default = null);

    /**
     * Set the value of the specified key
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  $this
     * @since   1.0
     * @throws  RedirectException
     */
    public function set($key, $value = null);

    /**
     * Redirect to the specified Url using the given Status Code
     *
     * @return  string
     * @since   1.0
     * @throws  RedirectException
     */
    public function redirect();
}
