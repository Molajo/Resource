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
     * Scheme Properties
     *
     * @var    object
     * @since  1.0
     */
    protected $scheme_properties;

    /**
     * Scheme Handler
     *
     * @var    string
     * @since  1.0
     */
    protected $handler;

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

        foreach ($handler_instance_array as $key => $value) {
            if ($value instanceof ResourceHandlerInterface) {
                $this->handler_instance[$key] = $value;
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
        spl_autoload_register(array($this, 'locateNamespace'), true, $prepend);

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
        spl_autoload_unregister(array($this, 'locateNamespace'));

        return $this;
    }

    /**
     * Get Scheme (or all schemes)
     *
     * @param   string $scheme
     *
     * @return  object|array
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function getScheme($scheme = '')
    {
        if ($scheme == '') {
            return $this->scheme_instance->getScheme();
        }

        $this->scheme = $scheme;

        $this->scheme_properties = $this->scheme_instance->getScheme($this->scheme);

        if ($this->scheme_properties === false) {
            throw new ResourcesException ('Resources getScheme Scheme not found: ' . $this->scheme);
        }

        $this->handler = $this->scheme_properties->handler;

        if (isset($this->handler_instance[$this->handler])) {
        } else {
            throw new ResourcesException ('Resources getScheme Handler not found: ' . $this->handler);
        }

        return $this->scheme_properties;
    }

    /**
     * Define Scheme, associated Handler and allowable file extensions (empty array means any extension allowed)
     *
     * @param   string $scheme_name
     * @param   string $handler
     * @param   array  $extensions
     * @param   bool   $replace
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function setScheme($scheme_name, $handler = 'File', array $extensions = array(), $replace = false)
    {
        $this->scheme_instance->setScheme($scheme_name, $handler, $extensions, $replace);

        return $this;
    }

    /**
     * Locates folder/file associated with URI Namespace for Resource
     *
     * @param   string $uri_namespace
     * @param   array  $valid_file_extensions
     *
     * @return  mixed
     * @since   1.0
     */
    public function locate($uri_namespace, array $valid_file_extensions = array('.php'))
    {
        $this->parseUrl($uri_namespace);

        return $this->locateNamespace(str_replace('\\', '/', $this->path), $this->scheme, $this->query);
    }

    /**
     * Locates a resource using only the namespace
     *
     * @param   string $namespace
     * @param   string $scheme
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function locateNamespace($namespace, $scheme = 'Class', array $options = array())
    {
        $this->getScheme($scheme);

        $located_path = $this->resource_map_instance->locate(
            $namespace,
            $this->scheme_properties->require_file_extensions
        );

        return $this->handlePath($this->scheme, $located_path, $options);
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
        $this->getScheme($scheme);

        return $this->handler_instance[$this->handler]->handlePath($scheme, $located_path, $options);
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
        $this->getScheme($scheme);

        return $this->handler_instance[$this->handler]->getCollection($scheme, $options);
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
     */
    public function getMap()
    {
        return $this->resource_map_instance->getMap();
    }

    /**
     * Create resource map of folder/file locations and Fully Qualified Namespaces
     *
     * @return  object
     * @since   1.0
     */
    public function createMap()
    {
        return $this->resource_map_instance->createMap();
    }

    /**
     * Verify the correctness of the resource map, returning error messages
     *
     * @return  array
     * @since   1.0
     */
    public function editMap()
    {
        return $this->resource_map_instance->editMap();
    }

    /**
     * Parse the URL
     *
     * @param   string $url
     *
     * @return  $this
     * @since   1.0
     */
    protected function parseUrl($url)
    {
        $scheme = parse_url($url, PHP_URL_SCHEME);
        $this->getScheme($scheme);

        $this->host     = parse_url($url, PHP_URL_HOST);
        $this->user     = parse_url($url, PHP_URL_USER);
        $this->password = parse_url($url, PHP_URL_PASS);
        $this->path     = parse_url($url, PHP_URL_PATH);
        $this->query    = parse_url($url, PHP_URL_QUERY);
        if ($this->query === null) {
            $this->query = array();
        }
        $this->fragment = parse_url($url, PHP_URL_FRAGMENT);

        return $this;
    }
}
