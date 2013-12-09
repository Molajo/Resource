<?php
/**
 * Js Resource
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Handler;

use stdClass;
use CommonApi\Resource\HandlerInterface;

/**
 * Js Resource
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class JsHandler extends AbstractHandler implements HandlerInterface
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
     * Constructor
     *
     * @param  string $base_path
     * @param  array  $resource_map
     * @param  array  $namespace_prefixes
     * @param  array  $valid_file_extensions
     *
     * @since  1.0
     */
    public function __construct(
        $base_path = null,
        array $resource_map = array(),
        array $namespace_prefixes = array(),
        array $valid_file_extensions = array()
    ) {
        parent::__construct(
            $base_path,
            $resource_map,
            $namespace_prefixes,
            $valid_file_extensions
        );
    }

    /**
     * Set a namespace prefix by mapping to the filesystem path
     *
     * @param   string  $namespace_prefix
     * @param   string  $namespace_base_directory
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0
     */
    public function setNamespace($namespace_prefix, $namespace_base_directory, $prepend = false)
    {
        return parent::setNamespace($namespace_prefix, $namespace_base_directory, $prepend);
    }

    /**
     * Locates folder/file associated with Namespace for Resource
     *
     * @param   string $resource_namespace
     *
     * @return  void|mixed
     * @since   1.0
     */
    public function get($resource_namespace, $multiple = false)
    {
        return $resource_namespace;
    }

    /**
     * Handle located folder/file associated with URI Namespace for Resource
     *
     * @param   string $scheme
     * @param   string $located_path
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0
     */
    public function handlePath($scheme, $located_path, array $options = array())
    {
        $located_path = $options['located_path'];

        if (is_dir($located_path)) {
            $type = 'folder';
        } elseif (file_exists($located_path)) {
            $type = 'file';
        } else {
            return null;
        }

        $priority = '';
        if (isset($options['priority'])) {
            $priority = $options['priority'];
        }

        $defer = '';
        if (isset($options['defer'])) {
            $defer = $options['defer'];
        }

        $mimetype = '';
        if (isset($options['mimetype'])) {
            $mimetype = $options['mimetype'];
        }

        $async = '';
        if (isset($options['async'])) {
            $async = $options['async'];
        }

        if ($type == 'folder') {
            $this->addJsFolder(
                $located_path,
                $priority,
                false
            );
            $located_path .= '/Defer';
            if (is_dir($located_path)) {
                $this->addJsFolder(
                    $located_path,
                    $priority,
                    true
                );
            }
        } else {
            $this->addJs(
                $located_path,
                $priority,
                $defer,
                $mimetype,
                $async
            );
        }

        return $this;
    }

    /**
     * addJsFolder - Loads the JS located within the folder
     *
     * @param   string  $file_path
     * @param   integer $priority
     * @param   bool    $defer
     *
     * @return  $this
     * @since   1.0
     */
    protected function addJsFolder($file_path, $priority = 500, $defer)
    {
        $files = scandir($file_path);

        if (count($files) > 0) {

            foreach ($files as $file) {

                $add = 1;

                if ($file == 1 || $file == '.' || $file == '..') {
                    $add = 0;
                }

                if (substr($file, 0, 4) == 'ltr_') {
                    if ($this->language_direction == 'rtl') {
                        $add = 0;
                    }
                } elseif (substr($file, 0, 4) == 'rtl_') {
                    if ($this->language_direction == 'rtl') {
                    } else {
                        $add = 0;
                    }
                } elseif (strtolower(substr($file, 0, 4)) == 'hold') {
                    $add = 0;
                }

                if (is_file($file)) {
                } else {
                    $add = 0;
                }

                if ($add == 1) {
                    $pathinfo = pathinfo($file);
                    if (strtolower($pathinfo->extension) == 'js') {
                    } else {
                        $add = 0;
                    }
                }

                if ($add == 1) {
                    $this->addJs($file_path . '/' . $file, $priority, $defer);
                }
            }
        }

        return $this;
    }

    /**
     * addJs - Adds a linked script to the page
     *
     * Usage:
     * $this->assets->addJs('http://example.com/test.js', 1000, 1);
     *
     * @param   string $file_path
     * @param   int    $priority
     * @param   int    $defer
     * @param   string $mimetype
     * @param   bool   $async
     *
     * @return $this
     */
    public function addJs($file_path, $priority = 500, $defer = 0, $mimetype = 'text/javascript', $async = false)
    {
        if ($defer == 1) {
            $js = $this->js_defer;
        } else {
            $js = $this->js;
        }

        foreach ($js as $item) {
            if ($item->file_path == $file_path) {
                return $this;
            }
        }

        $temp_row = new stdClass();

        $temp_row->file_path = $file_path;
        $temp_row->priority  = $priority;
        $temp_row->mimetype  = $mimetype;
        $temp_row->async     = $async;
        $temp_row->defer     = $defer;

        $js[] = $temp_row;

        if ($defer == 1) {
            $this->js_defer = $js;
        } else {
            $this->js = $js;
        }

        if ($defer == 1) {
            $priorities = $this->js_defer_priorities;
        } else {
            $priorities = $this->js_priorities;
        }

        if (in_array($priority, $priorities)) {
        } else {
            $priorities[] = $priority;
        }

        sort($priorities);

        if ($defer == 1) {
            $this->js_defer_priorities = $priorities;
        } else {
            $this->js_priorities = $priorities;
        }

        return $this;
    }
}
