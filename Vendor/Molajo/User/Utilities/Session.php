<?php
/**
 * Session Class
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\User\Utilities;

use Molajo\User\Exception\SessionException;
use Molajo\User\Api\SessionInterface;

/**
 * Session Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Session implements SessionInterface
{
    /**
     * Default Messages Exception
     *
     * @var    string
     * @since  1.0
     */
    protected $session_exception = 'Molajo\\User\Exception\\SessionException';

    /**
     * Constructor
     *
     * @since  1.0
     */
    public function __construct(
        $session_exception = null
    ) {
        if ($session_exception === null) {
        } else {
            $this->session_exception = $session_exception;
        }

        $this->startSession();
    }

    /**
     * Start the Session
     *
     * @return  bool
     * @since   1.0
     */
    protected function startSession()
    {
        if (session_id()) {
        } else {
            session_start();
        }

        return session_id();
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
        if (session_id()) {
        } else {
            $this->startSession();
        }

        $key = (string)$key;

        if ($key == 'session_id') {
            return session_id();
        }

        if (isset($_SESSION[$key])) {
            return unserialize($_SESSION[$key]);
        }

        return false;
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
        if (session_id()) {
        } else {
            $this->startSession();
        }

        $key   = (string)$key;
        $value = serialize($value);

        $_SESSION[$key] = $value;

        return $this;
    }

    /**
     * Delete a session key
     *
     * @param   null|string $key
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\User\Exception\SessionException
     */
    public function deleteSession($key = null)
    {
        if ($key == null) {
            $this->destroySession();
            return $this;
        }

        if (session_id()) {
        } else {
            $this->startSession();
        }

        $key = (string)$key;

        unset($_SESSION[$key]);

        return $this;
    }

    /**
     * Destroy Session, including all key values and session id
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\User\Exception\SessionException
     */
    public function destroySession()
    {
        if (session_id()) {
            session_unset();
            session_destroy();
            $_SESSION = null;
            session_write_close();
        }
    }
}
