<?php
/**
 * User Http Redirect
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\User\Utilities;

use Exception;
use Molajo\FieldHandler\Api\FieldHandlerInterface;
use Molajo\User\Api\MessagesInterface;
use Molajo\User\Exception\RedirectException;
use Molajo\Http\Api\RedirectInterface;

/**
 * User Http Redirect
 *
 * Copy of Molajo\Http\Redirect
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Class Redirect implements RedirectInterface
{
    /**
     * Field Handler
     *
     * @var    object
     * @since  1.0
     */
    protected $fieldhandler = null;

    /**
     * Messages
     *
     * @var    object
     * @since  1.0
     */
    protected $messages = null;

    /**
     * Default Exception
     *
     * @var    string
     * @since  1.0
     */
    protected $default_exception = 'Molajo\\User\\Exception\\RedirectException';

    /**
     * Url to use in Redirect
     *
     * @var    string
     * @since  1.0
     */
    protected $redirect_url = null;

    /**
     * Http Status Code
     *
     * @var    integer
     * @since  1.0
     */
    protected $status_code = 0;

    /**
     * Fallback URL can be injected as default (ex. home)
     *
     * @var    string
     * @since  1.0
     */
    protected $fallback_url = null;

    /**
     * List of Properties
     *
     * @var    object
     * @since  1.0
     */
    protected $property_array = array(
        'fieldhandler',
        'messages',
        'redirect_url',
        'status_code',
        'fallback_url'
    );

    /**
     * Constructor
     *
     * @param  FieldHandlerInterface $fieldhandler
     * @param  MessagesInterface     $messages
     * @param  null|string           $default_exception
     * @param  null|string           $redirect_url
     * @param  null|string           $status_code
     * @param  null|string           $fallback_url
     *
     * @since  1.0
     */
    public function __construct(
        FieldHandlerInterface $fieldhandler,
        MessagesInterface $messages,
        $default_exception = null,
        $redirect_url = null,
        $status_code = null,
        $fallback_url = null
    ) {
        $this->fieldhandler = $fieldhandler;
        $this->messages     = $messages;

        if ($redirect_url === null) {
        } else {
            $this->redirect_url = $redirect_url;
        }

        if ($status_code === null) {
        } else {
            $this->status_code = $status_code;
        }

        if ($fallback_url === null) {
        } else {
            $this->fallback_url = $fallback_url;
        }
    }

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
    public function get($key = null, $default = null)
    {
        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            $values        = array();
            $values['key'] = $key;
            $this->messages->throwException(4000, $values, $this->default_exception);
        }

        $this->$key = $default;

        return $this->$key;
    }

    /**
     * Set the value of the specified key
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  mixed
     * @since   1.0
     * @throws  RedirectException
     */
    public function set($key, $value = null)
    {
        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            $values        = array();
            $values['key'] = $key;
            $this->messages->throwException(4100, $values, $this->default_exception);
        }

        $this->$key = $value;

        return $this->$key;
    }

    /**
     * Redirect to the specified url using the given status code
     *
     * @param   int    $status_code
     * @param   string $redirect_url
     *
     * @return  string
     * @since   1.0
     * @throws  RedirectException
     */
    public function redirect($status_code = 301, $redirect_url = '')
    {
        if ($redirect_url == '') {
        } else {
            $this->redirect_url = $redirect_url;
        }
        if ((string)$this->redirect_url == '') {
            $this->redirect_url = $this->fallback_url;
        }
        if ((string)$this->redirect_url == '') {
            $this->messages->throwException(4200, array(), $this->default_exception);
        }

        try {
            $this->fieldhandler->validate('url', $this->redirect_url, 'url');

        } catch (Exception $e) {
            $values        = array();
            $values['url'] = $this->redirect_url;
            $this->messages->throwException(4300, $values, $this->default_exception);
        }

        if ((int)$status_code == 0) {
        } else {
            $this->status_code = $status_code;
        }

        if ((int)$this->status_code == 0) {
            $this->status_code = 301;
        }

        if ($this->status_code == 301) {
            header('HTTP/1.1 301 Moved Permanently');

        } elseif ($this->status_code == 302) {
            header('HTTP/1.1 302 Moved Temporarily');

        } elseif ($this->status_code == 401) {
            header('HTTP/1.1 401 Unauthorized');

        } elseif ($this->status_code == 403) {
            header('HTTP/1.1 403 Forbidden');

        } elseif ($this->status_code == 404) {
            header('HTTP/1.1 404 Not Found');

        } else {
            header('HTTP/1.1 ' . $this->status_code);
        }

        header('Location: ' . htmlspecialchars($this->redirect_url, ENT_QUOTES, 'UTF-8'));

        exit();
    }
}
