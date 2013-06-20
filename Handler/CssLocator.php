<?php
/**
 * Css Locator
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Kernel\Locator\Handler;

use Molajo\Kernel\Locator\Api\LocatorInterface;
use Molajo\Kernel\Locator\Handler\AbstractLocator;

/**
 * Css Locator
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class CssLocator extends AbstractLocator implements LocatorInterface
{
    /**
     * Collect list of CSS Files
     *
     * @var    array
     * @since  1.0
     */
    protected $css_files = array();

    /**
     * Css
     *
     * @var    array
     * @since  1.0
     */
    protected $css = array();

    /**
     * CSS Priorities
     *
     * @var    array
     * @since  1.0
     */
    protected $css_priorities = array();

    /**
     * Constructor
     *
     * @param   array                $file_extensions
     * @param   array                $namespace_prefixes
     * @param   null|string          $base_path
     * @param   bool                 $rebuild_map
     * @param   null|string          $resource_map_filename
     * @param   array                $exclude_in_path_array
     * @param   array                $exclude_path_array
     * @param   array                $valid_extensions_array
     * @param   ResourceMapInterface $resource_map_instance
     *
     * @since   1.0
     */
    public function __construct(
        array $file_extensions = array('Class' => '.php,.inc'),
        array $namespace_prefixes = array(),
        $base_path = null,
        $rebuild_map = false,
        $resource_map_filename = null,
        $exclude_in_path_array = array(),
        $exclude_path_array = array(),
        $valid_extensions_array = array(),
        ResourceMapInterface $resource_map_instance
    ) {
        parent::__construct(
            $file_extensions,
            $namespace_prefixes,
            $base_path,
            $rebuild_map,
            $resource_map_filename,
            $exclude_in_path_array,
            $exclude_path_array,
            $valid_extensions_array,
            $resource_map_instance
        );
    }

    /**
     * Registers a namespace prefix with filesystem path, appending the filesystem path to existing paths
     *
     * @param   string   $namespace_prefix
     * @param   string   $base_directory
     * @param   boolean  $replace
     *
     * @return  $this
     * @since   1.0
     */
    public function addNamespace($namespace_prefix, $base_directory, $replace = false)
    {
        parent::addNamespace($namespace_prefix, $base_directory, $replace);

        return $this;
    }

    /**
     * Add resource map which maps folder/file locations to Fully Qualified Namespaces
     *
     * @return  $this
     * @since   1.0
     */
    public function createResourceMap()
    {
        parent::createResourceMap();

        return $this;
    }

    /**
     * Locates folder/file associated with Fully Qualified Namespace for Resource and passes
     * the path to a handler for that type of resource (ex. a Css Locator includes the file)
     *
     * @param   string $resource
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Kernel\Locator\Exception\LocatorException
     */
    public function findResource($resource, array $options = array())
    {
        $located_path = parent::findResource($resource, $options);

        if ($located_path === false) {
            return;
        }

        if (file_exists($located_path)) {
            require $located_path;

            return;
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
     */
    public function getCollection(array $options = array())
    {
        return $this->resource_map;
    }

    /**
     * addCssFolder - Loads the CS located within the folder, as specified by the file path
     *
     * Usage:
     * $this->assets->addCssFolder($file_path, $url_path, $priority);
     *
     * @param string  $file_path
     * @param string  $url_path
     * @param integer $priority
     *
     * @return  object
     * @since   1.0
     */
    public function addCssFolder($file_path, $url_path, $priority = 500)
    {
        if (is_dir($file_path . '/css')) {
        } else {
            return $this;
        }

        $files = files($file_path);

        if (count($files) > 0) {

            foreach ($files as $file) {
                $add = 0;
                if (substr($file, 0, 4) == 'ltr_') {
                    if ($this->get('language', 'direction') == 'rtl') {
                    } else {
                        $add = 1;
                    }

                } elseif (substr($file, 0, 4) == 'rtl_') {

                    if ($this->get('language', 'direction') == 'rtl') {
                        $add = 1;
                    }

                } elseif (strtolower(substr($file, 0, 4)) == 'hold') {

                } else {
                    $add = 1;
                }

                if ($add == 1) {
                    $this->addCss($url_path . '/css/' . $file, $priority);
                }
            }
        }

        return $this;
    }

    /**
     * addCss - Adds a linked stylesheet to the page
     *
     * Usage:
     * $this->assets->addCss($url_path . '/template.css');
     *
     * @param string $url
     * @param int    $priority
     * @param string $mimetype
     * @param string $media
     * @param string $conditional
     * @param array  $attributes
     *
     * @return mixed
     * @since   1.0
     */
    public function addCss(
        $url,
        $priority = 500,
        $mimetype = 'text/css',
        $media = '',
        $conditional = '',
        $attributes = array()
    ) {
        $css = $this->get('css', array());

        foreach ($css as $item) {

            if ($item->url == $url
                && $item->mimetype == $mimetype
                && $item->media == $media
                && $item->conditional == $conditional
            ) {
                return $this;
            }
        }

        $temp_row = new stdClass();

        $temp_row->url         = $url;
        $temp_row->priority    = $priority;
        $temp_row->mimetype    = $mimetype;
        $temp_row->media       = $media;
        $temp_row->conditional = $conditional;
        $temp_row->attributes  = trim(implode(' ', $attributes));

        $css[] = $temp_row;

        $this->set('css', $css);

        $priorities = $this->get('css_priorities', array());

        if (in_array($priority, $priorities)) {
        } else {
            $priorities[] = $priority;
        }

        sort($priorities);

        $this->set('css_priorities', $priorities);

        return $this;
    }
}
