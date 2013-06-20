<?php
/**
 * Js Locator
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Kernel\Locator\Handler;

use Molajo\Kernel\Locator\Api\LocatorInterface;

/**
 * Js Locator
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class JsLocator extends AbstractLocator implements LocatorInterface
{

    /**
     * Js
     *
     * @var    array
     * @since  1.0
     */
    protected $js = array();

    /**
     * Js Priorities
     *
     * @var    array
     * @since  1.0
     */
    protected $js_priorities = array();

    /**
     * Js Defer
     *
     * @var    array
     * @since  1.0
     */
    protected $js_defer = array();

    /**
     * Js Defer Priorities
     *
     * @var    array
     * @since  1.0
     */
    protected $js_defer_priorities = array();

    /**
     * JS Declarations
     *
     * @var    array
     * @since  1.0
     */
    protected $js_declarations = array();

    /**
     * JS Declarations Priorities
     *
     * @var    array
     * @since  1.0
     */
    protected $js_declarations_priorities = array();

    /**
     * JS Declarations Defer
     *
     * @var    array
     * @since  1.0
     */
    protected $js_declarations_defer = array();

    /**
     * JS Declarations Defer Priorities
     *
     * @var    array
     * @since  1.0
     */
    protected $js_declarations_defer_priorities = array();


    /**
     * Handles the located resource in a manner that varies from resource to resource
     *  (include the file, return the path, consolidate, etc)
     *
     * @param   string $located_path
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0
     */
    public function handle($located_path, array $options = array())
    {
        if (file_exists($located_path)) {
            $this->js_files[] = $located_path;
        } elseif (is_dir($located_path)) {
            // get the js files by type
        }

        return;
    }


    /**
     * Retrieves the collection of resources
     *
     * @param   array $options
     *
     * @return  array
     * @since   1.0
     */
    public function getCollection(array $options = array())
    {

    }

    /**
     * addJsFolder - Loads the JS Files located within the folder specified by the filepath
     *
     * Usage:
     * $this->assets->addJsFolder($file_path, $url_path, $priority, 0);
     *
     * @param string $file_path
     * @param string $url_path
     * @param int    $priority
     * @param int    $defer
     *
     * @return void
     * @since   1.0
     */
    public function addJsFolder($file_path, $url_path, $priority = 500, $defer = 0)
    {
        if ($defer == 1) {
            $extra = '/js/defer';
        } else {
            $extra = '/js';
            $defer = 0;
        }

        if (is_dir($file_path . $extra)) {
        } else {
            return;
        }
        // .js
        $files = files($file_path . $extra);

        if (count($files) > 0) {
            foreach ($files as $file) {
                if (strtolower(substr($file, 0, 4)) == 'hold') {
                } else {
                    $this->addJs(
                        $url_path . $extra . '/' . $file,
                        $priority,
                        $defer,
                        'text/javascript',
                        0
                    );
                }
            }
        }

        return;
    }

    /**
     * addJs - Adds a linked script to the page
     *
     * Usage:
     * $this->assets->addJs('http://example.com/test.js', 1000, 1);
     *
     * @param string $url
     * @param int    $priority
     * @param int    $defer
     * @param string $mimetype
     * @param bool   $async
     *
     * @return  object Asset
     * @since   1.0
     */
    public function addJs($url, $priority = 500, $defer = 0, $mimetype = "text/javascript", $async = false)
    {
        if ($defer == 1) {
            $js = $this->get('js_defer', array());
        } else {
            $js = $this->get('js', array());
        }

        foreach ($js as $item) {
            if ($item->url == $url) {
                return $this;
            }
        }

        $temp_row = new stdClass();

        $temp_row->url      = $url;
        $temp_row->priority = $priority;
        $temp_row->mimetype = $mimetype;
        $temp_row->async    = $async;
        $temp_row->defer    = $defer;

        $js[] = $temp_row;

        if ($defer == 1) {
            $this->set('js_defer', $js);
        } else {
            $this->set('js', $js);
        }

        if ($defer == 1) {
            $priorities = $this->get('js_defer_priorities', $js);
        } else {
            $priorities = $this->get('js_priorities', $js);
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
            $js = $this->get('js_declarations_defer', array());
        } else {
            $js = $this->get('js_declarations', array());
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
            $this->set('js_declarations_defer', $js);
        } else {
            $this->set('js_declarations', $js);
        }

        if ($defer == 1) {
            $priorities = $this->get('js_declarations_defer_priorities', array());
        } else {
            $priorities = $this->get('js_declarations_priorities', array());
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
            $this->set('js_declarations_defer_priorities', $priorities);
        } else {
            $this->set('js_declarations_priorities', $priorities);
        }

        return $this;
    }
}
