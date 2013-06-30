<?php
/**
 * Css Declarations Locator
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Locator\Handler;

use Molajo\Locator\Api\ResourceMapInterface;
use Molajo\Locator\Api\LocatorInterface;
use Molajo\Locator\Handler\AbstractLocator;

/**
 * Css Declarations Locator
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class CssDeclarationsHandler implements ResourceHandlerInterface
{
    /**
     * Css Declarations
     *
     * @var    array
     * @since  1.0
     */
    protected $css_declarations = array();

    /**
     * CSS Declarations Priorities
     *
     * @var    array
     * @since  1.0
     */
    protected $css_declarations_priorities = array();

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
     * @param   string  $namespace_prefix
     * @param   string  $base_directory
     * @param   boolean $replace
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
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function findResource($resource, array $options = array())
    {
        $located_path = parent::findResource($resource, $options);

        if ($located_path === false) {
            return;
        }

        if (file_exists($located_path)) {
        } else {
            return;
        }

        $css = $this->get('css_declarations');

        if (is_array($css) && count($css) > 0) {
            foreach ($css as $item) {
                if ($item->content == $content) {
                    return $this;
                }
            }
        }
        //$content, $mimetype = 'text/css', $priority = 500
        $temp_row = new stdClass();

        $temp_row->mimetype = $mimetype;
        $temp_row->content  = $content;
        $temp_row->priority = $priority;

        $css[] = $temp_row;

        $this->set('css_declarations', $css);

        $priorities = $this->get('css_declarations_priorities', array());

        if (in_array($priority, $priorities)) {
        } else {
            $priorities[] = $priority;
        }

        sort($priorities);

        $this->set('css_declarations_priorities', $priorities);

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
}
