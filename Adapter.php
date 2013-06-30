<?php
/**
 * Resource Locator Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Locator;

use Molajo\Locator\Exception\LocatorException;
use Molajo\Locator\Api\ResourceLocatorInterface;
use Molajo\Locator\Api\ResourceHandlerInterface;
use Molajo\Locator\Api\ClassLoaderInterface;
use Molajo\Locator\Api\ResourceMapInterface;

/**
 * Resource Locator Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class Adapter implements ResourceLocatorInterface, ClassLoaderInterface
{
    /**
     * Locator Instances
     *
     * @var    object  Molajo\Locator\Api\ResourceLocatorInterface
     * @since  1.0
     */
    protected $locator_instance;

    /**
     * Handler Instances
     *
     * @var    object  Molajo\Locator\Api\ResourceHandlerInterface
     * @since  1.0
     */
    protected $handler_instance = array();

    /**
     * Scheme Type
     *
     * @var    array
     * @since  1.0
     */
    protected $scheme_type = array();

    /**
     * Scheme
     *
     * @var    string
     * @since  1.0
     */
    protected $scheme;

    /**
     * Host
     *
     * @var    string
     * @since  1.0
     */
    protected $host;

    /**
     * User
     *
     * @var    string
     * @since  1.0
     */
    protected $user;

    /**
     * Pass
     *
     * @var    string
     * @since  1.0
     */
    protected $pass;

    /**
     * Path
     *
     * @var    string
     * @since  1.0
     */
    protected $path;

    /**
     * Query
     *
     * @var    string
     * @since  1.0
     */
    protected $query;

    /**
     * Fragment
     *
     * @var    string
     * @since  1.0
     */
    protected $fragment;

    /**
     * Constructor
     *
     * @param   ResourceLocatorInterface $locator_instance
     * @param   ResourceMapInterface     $resource_map_instance
     * @param   array                    $scheme_type
     * @param   array                    $handler_instance_array
     *
     * @since   1.0
     */
    public function __construct(
        ResourceLocatorInterface $locator_instance,
        ResourceMapInterface $resource_map_instance,
        array $scheme_type = array(),
        array $handler_instance_array = array()
    ) {
        $this->locator_instance      = $locator_instance;
        $this->resource_map_instance = $resource_map_instance;
        $this->scheme_type           = $scheme_type;

        foreach ($this->handler_instance_array as $item) {
            if ($item instanceof ResourceHandlerInterface) {
                $this->handler_instance_array[] = $item;
            }
        }

        $this->register();
    }

    /**
     * Register Class as Autoloader
     *
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0
     */
    public function register($prepend = true)
    {
        spl_autoload_register(array($this, 'get'), true, $prepend);

        return $this;
    }

    /**
     * Cancel Class Registration as Autoloader
     *
     * @return  $this
     * @since   1.0
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'get'));

        return $this;
    }

    /**
     * Locates folder/file associated with URI Namespace for Resource
     *
     * @param   string $uri_namespace
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function get($uri_namespace)
    {
        $this->parseUrl($uri_namespace);

        $resource = $this->locator_instance->get($uri_namespace);

        return $this->handler_instance[$this->scheme]->getCollection($resource);
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
        return $this->handler_instance[$this->scheme]->getCollection($options);
    }

    /**
     * Locates folder/file associated with Fully Qualified Namespace
     *
     * @param   string $resource
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    protected function getResource($resource, array $options = array())
    {
        return $this->handler_instance[$this->scheme]->getResource($resource, $options);
    }

    /**
     * Special file or folder handling for resource type
     *
     * @param   string $resource
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    protected function handleResource($resource, array $options = array())
    {
        return $this->handler_instance[$this->scheme]->handleResource($resource, $options);
    }

    /**
     * Define Schemes for Resource location
     *
     * @param   string $scheme
     * @param   string $handler
     * @param   array  $extensions
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function addScheme($scheme, array $handler = 'File', $extensions = array())
    {

    }

    /**
     * Map a namespace prefix to a filesystem path
     *
     * @param   string  $namespace_prefix
     * @param   string  $base_directory
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0
     */
    public function addNamespace($namespace_prefix, $base_directory, $prepend = false)
    {
        $this->handler_instance[$this->scheme]->addNamespace($namespace_prefix, $base_directory, $prepend);

        return $this;
    }

    /**
     * Create resource map of folder/file locations and Fully Qualified Namespaces
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function createMap()
    {

    }

    /**
     * Verify the correctness of the resource map
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function editMap()
    {

    }

    /**
     * Parse the URL
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function parseUrl($url)
    {
        $this->scheme   = parse_url($url, PHP_URL_SCHEME);
        $this->host     = parse_url($url, PHP_URL_HOST);
        $this->user     = parse_url($url, PHP_URL_USER);
        $this->pass     = parse_url($url, PHP_URL_PASS);
        $this->path     = parse_url($url, PHP_URL_PATH);
        $this->query    = parse_url($url, PHP_URL_QUERY);
        $this->fragment = parse_url($url, PHP_URL_FRAGMENT);

        return $this;
    }
}
