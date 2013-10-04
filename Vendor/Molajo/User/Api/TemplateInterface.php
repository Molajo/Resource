<?php
/**
 * Template Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\User\Api;

use stdClass;
use Molajo\User\Exception\TemplateException;

/**
 * Template Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface TemplateInterface
{
    /**
     * Get the current value (or default) of the specified key
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  TemplateException
     */
    public function get($key, $default = null);

    /**
     * Set the value of a specified key
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  $this
     * @since   1.0
     * @throws  TemplateException
     */
    public function set($key, $value = null);

    /**
     * Set the Option Values, Initiate Rendering, Send
     *
     * @param   stdClass $data
     *
     * @return  string
     * @since   1.0
     */
    public function render(stdClass $data);
}

