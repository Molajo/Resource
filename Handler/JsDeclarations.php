<?php
/**
 * Js Locator
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Locator\Handler;

use Molajo\Locator\Api\ResourceHandlerInterface;

/**
 * Js Locator
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class JsDeclarationHandler implements ResourceHandlerInterface
{
    /**
     * JS Declarations
     *
     * @var    array
     * @since  1.0
     */
    protected $js = array();

    /**
     * JS Declarations Priorities
     *
     * @var    array
     * @since  1.0
     */
    protected $js_priorities = array();

    /**
     * JS Declarations Defer
     *
     * @var    array
     * @since  1.0
     */
    protected $js_defer = array();

    /**
     * JS Declarations Defer Priorities
     *
     * @var    array
     * @since  1.0
     */
    protected $js_defer_priorities = array();

    /**
     * Locates folder/file associated with URI Namespace for Resource
     *
     * @param   string $located_path
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function handlePath($located_path, array $options = array())
    {
        if (file_exists($located_path)) {
            return $located_path;
        }

        return;
    }

    /**
     * Retrieve a collection of a specific resource type (ex., all CSS files registered)
     *
     * @param   array $options
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function getCollection(array $options = array())
    {
        return $this->resource_map;
    }

    /**
     * addJSDeclarations - Adds a js declaration to an array for later rendering
     *
     * Usage:
     * $this->assets->addJSDeclarations($fallback, 'text/javascript', 1000);
     *
     * @param string $content
     * @param int    $priority
     * @param int    $defer
     * @param string $mimetype
     *
     * @return  object Asset
     * @since   1.0
     */
    public function addJSDeclarations($content, $priority = 500, $defer = 0, $mimetype = 'text/javascript')
    {
        if ($defer == 1) {
            $js = $this->get('js_defer', array());
        } else {
            $js = $this->get('js', array());
        }

        foreach ($js as $item) {
            if ($item->content == $content) {
                return $this;
            }
        }

        $temp_row = new stdClass();

        $temp_row->content  = $content;
        $temp_row->mimetype = $mimetype;
        $temp_row->defer    = $defer;
        $temp_row->priority = $priority;

        $js[] = $temp_row;

        if ($defer == 1) {
            $this->set('js_defer', $js);
        } else {
            $this->set('js', $js);
        }

        if ($defer == 1) {
            $priorities = $this->get('js_defer_priorities', array());
        } else {
            $priorities = $this->get('js_priorities', array());
        }

        if (is_array($priorities)) {
        } else {
            $priorities = array();
        }

        if (in_array($priority, $priorities)) {
        } else {
            $priorities[] = $priority;
        }

        sort($priorities);

        if ($defer == 1) {
            $this->set('js_defer_priorities', $priorities);
        } else {
            $this->set('js_priorities', $priorities);
        }

        return $this;
    }
}
