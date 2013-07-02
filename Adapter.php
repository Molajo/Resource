<?php
/**
 * Resource Locator
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resources;

use Molajo\Resources\Api\ClassHandlerInterface;
use Molajo\Resources\Api\SchemeInterface;
use Molajo\Resources\Api\ResourceLocatorInterface;
use Molajo\Resources\Api\ResourceNamespaceInterface;
use Molajo\Resources\Api\ResourceMapInterface;
use Molajo\Resources\Api\ResourceHandlerInterface;
use Molajo\Resources\Exception\ResourcesException;

/**
 * Resource Resources Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class Adapter implements ClassHandlerInterface, SchemeInterface,
    ResourceLocatorInterface, ResourceNamespaceInterface, ResourceMapInterface,
    ResourceHandlerInterface
{
    /**
     * Scheme Instance
     *
     * @var    object  Molajo\Resources\Api\SchemeInterface
     * @since  1.0
     */
    protected $scheme_instance;

    /**
     * Resources Map Instance
     *
     * @var    object  Molajo\Resources\Api\ResourceMapInterface
     * @since  1.0
     */
    protected $resource_map_instance;

    /**
     * Handler Instances
     *
     * @var    object  Molajo\Resources\Api\ResourceHandlerInterface
     * @since  1.0
     */
    protected $handler_instance = array();

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
     * Password
     *
     * @var    string
     * @since  1.0
     */
    protected $password;

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
     * @param   SchemeInterface      $scheme_instance
     * @param   ResourceMapInterface $resource_map_instance
     * @param   array                $handler_instance_array
     *
     * @since   1.0
     */
    public function __construct(
        ResourceMapInterface $resource_map_instance,
        SchemeInterface $scheme_instance,
        array $handler_instance_array = array()
    ) {
        $this->scheme_instance        = $scheme_instance;
        $this->resource_map_instance  = $resource_map_instance;
        $this->handler_instance_array = array();

        foreach ($handler_instance_array as $item) {
            if ($item instanceof ResourceHandlerInterface) {
                $this->handler_instance_array[] = $item;
            }
        }

        $this->register();
    }

    /**
     * Registers Class Autoloader
     *
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0
     */
    public function register($prepend = true)
    {
        spl_autoload_register(array($this, 'loadClass'), true, $prepend);

        return $this;
    }

    /**
     * Unregisters Class Autoloader
     *
     * @return  $this
     * @since   1.0
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));

        return $this;
    }

    /**
     * Get Scheme
     *
     * @param   string $scheme
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function getScheme($scheme)
    {
        return $this->scheme_instance->getScheme($scheme);
    }

    /**
     * Add (or replace) Scheme
     *
     * @param   string $scheme
     * @param   string $handler
     * @param   array  $extensions
     * @param   bool   $replace
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function setScheme($scheme, $handler = 'File', array $extensions = array(), $replace = false)
    {
        $this->scheme_instance->setScheme($scheme, $handler, $extensions, $replace);

        return $this;
    }

    /**
     * Loads a class file
     *
     * @param   string $namespace
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function loadClass($namespace)
    {
        $located_path = $this->locateResource($namespace);
echo $located_path;
        die;
        return $this->handlePath($this->scheme, $located_path, $options);
    }

    /**
     * Locates folder/file associated with URI Namespace for Resource
     *
     * @param   string $uri_namespace
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function locate($uri_namespace, array $options = array())
    {
        $this->parseUrl($uri_namespace);
echo '<pre>';
var_dump(
    array($this->scheme,
        $this->host,
        $this->user,
        $this->password,
        $this->path,
        $this->query,
        $this->fragment)
    );

        $handler = $this->scheme . 'Handler';
        if (isset($this->handler_instance[$handler])) {
        } else {
            throw new ResourcesException ('Resources locaate Handler not found for Scheme: : ' . $this->scheme);
        }

        $located_path = $this->locateResource(str_replace('/', '\\', $this->path));

        return $this->handlePath($this->scheme, $located_path, $options);
    }

    /**
     * Locates folder/file associated with URI Namespace for Resource
     *
     * @param   string $resource
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function locateResource($resource)
    {
        return $this->resource_map_instance->locateResource($resource);
    }

    /**
     * Handle located folder/file associated with URI Namespace for Resource
     *
     * @param   string $scheme
     * @param   string $located_path
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function handlePath($scheme, $located_path, array $options = array())
    {
        if (isset($this->handler_instance[$this->scheme])) {
        } else {
            throw new ResourcesException ('Resources handlePath Handler not found for Scheme: ' . $this->scheme);
        }

        return $this->handler_instance[$scheme]->handlePath($scheme, $located_path, $options);
    }

    /**
     * Retrieve a collection of a specific handler
     *
     * @param   string $scheme
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function getCollection($scheme, array $options = array())
    {
        if (isset($this->handler_instance[$this->scheme])) {
        } else {
            throw new ResourcesException ('Resources getCollection Handler not found for Scheme: ' . $this->scheme);
        }

        return $this->handler_instance[$scheme]->getCollection($scheme, $options);
    }

    /**
     * Get Namespace (or all namespaces)
     *
     * @param   string $namespace_prefix
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function getNamespace($namespace_prefix = '')
    {
        return $this->resource_map_instance->getNamespace($namespace_prefix);
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
    public function setNamespace($namespace_prefix, $base_directory, $prepend = false)
    {
        $this->resource_map_instance->setNamespace($namespace_prefix, $base_directory, $prepend);

        return $this;
    }

    /**
     * Get Resource Map
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function getMap()
    {
        return $this->resource_map_instance->getMap('resource_map');
    }

    /**
     * Create resource map of folder/file locations and Fully Qualified Namespaces
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function createMap()
    {

    }

    /**
     * Verify the correctness of the resource map
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function editMap()
    {

    }

    /**
     * Parse the URL
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    protected function parseUrl($url)
    {
        $this->scheme   = parse_url($url, PHP_URL_SCHEME);
        $this->host     = parse_url($url, PHP_URL_HOST);
        $this->user     = parse_url($url, PHP_URL_USER);
        $this->password = parse_url($url, PHP_URL_PASS);
        $this->path     = parse_url($url, PHP_URL_PATH);
        $this->query    = parse_url($url, PHP_URL_QUERY);
        $this->fragment = parse_url($url, PHP_URL_FRAGMENT);

        return $this;
    }
}
