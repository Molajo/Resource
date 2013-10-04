<?php
/**
 * Abstract Document Class
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Theme\Handler;

use Molajo\Theme\Exception\DocumentException;

/**
 * Adapter for Document
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class AbstractHandler
{
    /**
     * Items
     *
     * @var     array
     * @since   1.0
     */
    protected $items = array();

    /**
     * Items Sorted in Priority order
     *
     * @var     array
     * @since   1.0
     */
    protected $sorted = array();

    /**
     * Priorities associated with items
     *
     * @var     array
     * @since   1.0
     */
    protected $priorities = array();

    /**
     * Item Content
     *
     * @var     object
     * @since   1.0
     */
    protected $content;

    /**
     * Return results for document
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  DocumentException
     */
    public function get($key, $default)
    {
        if (isset($this->items[$key])) {
            return $this->items[$key];
        }

        return $default;
    }

    /**
     * Set the value of a key
     *
     * @param   string $key
     * @param   array  $options
     *
     * @return  object
     * @since   1.0
     * @throws  DocumentException
     */
    public function set($key, $options = array())
    {
        $this->items[$key] = $options;

        return $this;
    }

    /**
     * Remove item
     *
     * @param   string $key
     *
     * @return  mixed
     * @since   1.0
     * @throws  DocumentException
     */
    public function remove($key)
    {
        if (isset($this->items[$key])) {
            unset($this->items[$key]);
        }

        return $this;
    }
}
