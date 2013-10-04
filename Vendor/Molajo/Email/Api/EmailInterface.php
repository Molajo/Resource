<?php
/**
 * Email Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Email\Api;

use Molajo\Email\Exception\EmailException;

/**
 * Email Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface EmailInterface
{
    /**
     * Return parameter value or default
     *
     * @param   string      $key
     * @param   null|string $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  EmailException
     */
    public function get($key, $default = null);

    /**
     * Set parameter value
     *
     * @param   string     $key
     * @param   null|mixed $value
     *
     * @return  mixed
     * @since   1.0
     * @throws  EmailException
     */
    public function set($key, $value = null);

    /**
     * Send Email
     *
     * @return  mixed
     * @since   1.0
     * @throws  EmailException
     */
    public function send();
}
