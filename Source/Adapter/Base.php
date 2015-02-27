<?php
/**
 * Base
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Adapter;

use CommonApi\Exception\RuntimeException;

/**
 * Base
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
abstract class Base extends Cache
{
    /**
     * Scheme Name
     *
     * @var    string
     * @since  1.0.0
     */
    protected $scheme_name = null;

    /**
     * Resource Namespace
     *
     * @var    string
     * @since  1.0.0
     */
    protected $resource_namespace = null;

    /**
     * Base Path - root of the website from which paths are defined
     *
     * @var    string
     * @since  1.0.0
     */
    protected $base_path = null;

    /**
     * Resource Map
     *
     * @var    array
     * @since  1.0.0
     */
    protected $resource_map = array();

    /**
     * Namespace Prefixes + Path
     *
     * @var    array
     * @since  1.0.0
     */
    protected $namespace_prefixes = array();

    /**
     * Namespace Prefixes + Path
     *
     * @var    array
     * @since  1.0.0
     */
    protected $valid_file_extensions = array();

    /**
     * Constructor
     *
     * @param  string $base_path
     * @param  array  $resource_map
     * @param  array  $namespace_prefixes
     * @param  array  $valid_file_extensions
     * @param  array  $cache_callbacks
     *
     * @since  1.0.0
     */
    public function __construct(
        $base_path = null,
        array $resource_map = array(),
        array $namespace_prefixes = array(),
        array $valid_file_extensions = array(),
        array $cache_callbacks = array()
    ) {
        $this->base_path             = $base_path . '/';
        $this->resource_map          = $resource_map;
        $this->namespace_prefixes    = $namespace_prefixes;
        $this->valid_file_extensions = $valid_file_extensions;

        $this->instantiateCache($cache_callbacks);
    }

    /**
     * Instantiate Cache Class
     *
     * @param   array $cache_callbacks
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function instantiateCache(array $cache_callbacks = array())
    {
        if (isset($cache_callbacks['get_cache_callback'])) {
            $get_cache_callback = $cache_callbacks['get_cache_callback'];
        } else {
            $get_cache_callback = null;
        }

        if (isset($cache_callbacks['set_cache_callback'])) {
            $set_cache_callback = $cache_callbacks['set_cache_callback'];
        } else {
            $set_cache_callback = null;
        }

        if (isset($cache_callbacks['delete_cache_callback'])) {
            $delete_cache_callback = $cache_callbacks['delete_cache_callback'];
        } else {
            $delete_cache_callback = null;
        }

        parent::__construct($get_cache_callback, $set_cache_callback, $delete_cache_callback);

        return $this;
    }

    /**
     * Verify Options Entry
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function verifyNamespace(array $options = array())
    {
        if (isset($options['namespace'])) {
        } else {
            throw new RuntimeException('Resource options array must have namespace entry.');
        }

        return $this;
    }

    /**
     * Verify File Exists
     *
     * @param   string $located_path
     *
     * @return  $this
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function verifyFileExists($located_path)
    {
        if (file_exists($located_path)) {
        } else {
            throw new RuntimeException('Resource located_path not found: ' . $this->resource_namespace);
        }

        return $this;
    }

    /**
     * Verify Options Entry
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setScheme(array $options = array())
    {
        if (isset($options['scheme_name'])) {
        } else {
            throw new RuntimeException('Resource options array must have scheme_name entry set in Proxy.');
        }

        $this->scheme_name = $options['scheme_name'];

        return $this;
    }

    /**
     * x
     * Prepare Resource Namespace for Search
     *
     * @param   string $resource_namespace
     *
     * @return  string
     * @since   1.0.0
     */
    protected function setResourceNamespace($resource_namespace)
    {
        $temp_resource      = ltrim($resource_namespace, '//');
        $temp_resource      = str_replace('//', '\\', $temp_resource);
        $temp_resource      = str_replace('/', '\\', $temp_resource);
        $resource_namespace = $temp_resource;

        $this->resource_namespace = $resource_namespace;

        return $this;
    }
}
