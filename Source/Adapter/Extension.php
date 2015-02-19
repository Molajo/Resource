<?php
/**
 * Extension Resources
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Adapter;

use CommonApi\Resource\AdapterInterface;
use CommonApi\Exception\RuntimeException;

/**
 * Extension Resources
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class Extension extends AbstractAdapter implements AdapterInterface
{
    /**
     * Rendering Extensions
     *
     * @var    object
     * @since  1.0.0
     */
    protected $extensions = null;

    /**
     * Extension Object
     *
     * @var    object
     * @since  1.0.0
     */
    protected $extension = null;

    /**
     * Extension Path
     *
     * @var    string
     * @since  1.0.0
     */
    protected $extension_path = null;

    /**
     * Parameters
     *
     * @var    object
     * @since  1.0.0
     */
    protected $parameters = null;

    /**
     * Catalog Type ID
     *
     * @var    int
     * @since  1.0.0
     */
    protected $catalog_type_id;

    /**
     * Catalog Type Priority
     *
     * @var    int
     * @since  1.0.0
     */
    protected $catalog_type_priority;

    /**
     * Resource Instance to retrieve CSS and JS files
     *
     * @var    object
     * @since  1.0.0
     */
    protected $resource;

    /**
     * Constructor
     *
     * @param  string $base_path
     * @param  array  $resource_map
     * @param  array  $namespace_prefixes
     * @param  array  $valid_file_extensions
     * @param  array  $cache_callbacks
     * @param  array  $handler_options
     *
     * @since  1.0.0
     */
    public function __construct(
        $base_path = null,
        array $resource_map = array(),
        array $namespace_prefixes = array(),
        array $valid_file_extensions = array(),
        array $cache_callbacks = array(),
        array $handler_options = array()
    ) {
        parent::__construct(
            $base_path,
            $resource_map,
            $namespace_prefixes,
            $valid_file_extensions,
            $cache_callbacks
        );

        $this->extensions = $handler_options['extensions'];
        $this->resource   = $handler_options['resource'];
    }

    /**
     * Set a namespace prefix by mapping to the filesystem path
     *
     * @param   string  $namespace_prefix
     * @param   string  $namespace_base_directory
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0.0
     */
    public function setNamespace($namespace_prefix, $namespace_base_directory, $prepend = false)
    {
        return parent::setNamespace($namespace_prefix, $namespace_base_directory, $prepend);
    }

    /**
     * Locates folder/file associated with Namespace for Resource
     *
     * @param   string $resource_namespace
     * @param   bool   $multiple
     *
     * @return  void|mixed
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function get($resource_namespace, $multiple = false)
    {
        $extension = substr($resource_namespace, strrpos($resource_namespace, '/') + 1, 9999);

        $test = $extension;
        if (is_numeric($test) && (int)$test == $extension) {
        } else {
            $test = strtolower($extension);
            if (isset($this->extensions->extensions[$this->catalog_type_id]->names[$test])) {
                $extension = $this->extensions->extensions[$this->catalog_type_id]->names[$test];
            }
        }

        $temp = substr($resource_namespace, 0, strrpos($resource_namespace, '/') - 1);

        if (isset($this->extensions->extensions[$this->catalog_type_id]->ids[$extension])) {
            $alias = $this->extensions->extensions[$this->catalog_type_id]->ids[$extension];
        } else {
            $alias = $extension;
        }

        $namespace = $temp . '//' . $alias;

        $this->extension = $this->extensions->extensions[$this->catalog_type_id]->extensions[$extension];

        return parent::get($namespace);
    }

    /**
     * Handle located folder/file associated with URI Namespace for Resource
     *
     * @param   string $scheme
     * @param   string $located_path
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function handlePath($scheme, $located_path, array $options = array())
    {
        if (file_exists($located_path)) {
        } else {
            throw new RuntimeException('Resource Extension Adapter: File not found: ' . $located_path);
        }

        $this->extension->path = $located_path;

        return $this->extension;
    }

    /**
     * Retrieve a collection of a specific handler
     *
     * @param   string $scheme
     * @param   array  $options
     *
     * @return  Extension
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function getCollection($scheme, array $options = array())
    {
        return $this;
    }
}
