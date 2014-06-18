<?php
/**
 * Resource Driver
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource;

use Exception;
use CommonApi\Exception\RuntimeException;
use CommonApi\Resource\AdapterInterface;
use CommonApi\Resource\ResourceInterface;
use CommonApi\Resource\SchemeInterface;

/**
 * Resource Driver
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class Driver implements ResourceInterface
{
    /**
     * Scheme Instance
     *
     * @var    object  CommonApi\Resource\SchemeInterface
     * @since  1.0
     */
    protected $scheme;

    /**
     * Adapter Instances
     *
     * @var    array  Contains set of CommonApi\Resource\AdapterInterface instances
     * @since  1.0
     */
    protected $adapter_instance_array = array();

    /**
     * Scheme from Request
     *
     * @var    string
     * @since  1.0
     */
    protected $scheme_value;

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
     * Adapter Value
     *
     * @var    string
     * @since  1.0
     */
    protected $adapter_value;

    /**
     * Constructor
     *
     * @param  SchemeInterface $scheme
     * @param  array           $adapter_instance_array
     *
     * @since  1.0
     */
    public function __construct(
        SchemeInterface $scheme,
        array $adapter_instance_array = array()
    ) {
        $this->scheme                 = $scheme;
        $this->adapter_instance_array = array();

        foreach ($adapter_instance_array as $key => $value) {
            $this->setAdapterInstance($key, $value);
        }

        $this->register();
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
    public function setNamespace($namespace_prefix, $base_directory, $prepend = true)
    {
        foreach ($this->adapter_instance_array as $key => $value) {
            $this->adapter_instance_array[$key]->setNamespace(
                $namespace_prefix,
                $base_directory,
                $prepend
            );
        }

        return $this;
    }

    /**
     * Pass in the Adapter Instance for a Scheme Adapter
     * => For class construction or adding a new scheme/adapter after instantiation
     *
     * @param   string $adapter
     * @param   object $adapter_instance
     *
     * @return  $this
     * @since   1.0
     */
    public function setAdapterInstance($adapter = 'File', $adapter_instance)
    {
        if ($adapter_instance instanceof AdapterInterface) {
            $this->adapter_instance_array[$adapter] = $adapter_instance;
        }

        return $this;
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
     * Unregister Class Autoloader
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
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function getScheme($scheme = '')
    {
        if ($scheme == '') {
            return $this->scheme->getScheme();
        }

        $scheme = ucfirst(strtolower($scheme));

        $this->scheme_value = $scheme;

        $this->scheme_properties = $this->scheme->getScheme($this->scheme_value);

        if ($this->scheme_properties === false) {
            throw new RuntimeException('Resource getScheme Scheme not found: ' . $this->scheme_value);
        }

        $this->adapter_value = $this->scheme_properties->adapter;

        if (isset($this->adapter_instance_array[$this->adapter_value])) {
        } else {
            echo 'in Resource Adapter ' . $this->adapter_value . ' <br />';
            echo '<pre>';
            foreach ($this->adapter_instance_array as $key => $value) {
                echo $key . '<br />';
            }
            die;
            var_dump($this->adapter_instance_array);
            echo '</pre>';
            throw new RuntimeException('Resource getScheme Adapter not found: ' . $this->adapter_value);
        }

        return $this->scheme_properties;
    }

    /**
     * Define Scheme, associated Adapter and allowable file extensions (empty array means any extension allowed)
     *
     * @param   string $scheme_name
     * @param   string $adapter
     * @param   array  $extensions
     * @param   bool   $replace
     *
     * @return  $this
     * @since   1.0
     */
    public function setScheme($scheme_name, $adapter = 'File', array $extensions = array(), $replace = false)
    {
        $this->scheme->setScheme($scheme_name, $adapter, $extensions, $replace);

        return $this;
    }

    /**
     * Verify if the resource namespace has been defined or not
     *
     * @param   string $uri_namespace
     *
     * @return  boolean
     * @since   1.0
     */
    public function exists($uri_namespace)
    {
        try {
            $this->parseUri($uri_namespace);
            $this->scheme_value = 'file';
            $this->getScheme($this->scheme_value);

            $located_path = $this->adapter_instance_array[$this->adapter_value]->get($uri_namespace);
            if ($located_path === false) {
                return false;
            }

            return true;

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Locates folder/file associated with URI Namespace for Resource
     *
     * @param   string $uri_namespace
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     */
    public function get($uri_namespace, array $options = array())
    {
        $this->parseUri($uri_namespace);

        return $this->locateNamespace(str_replace('\\', '/', $this->path), $this->scheme_value, $options);
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
     */
    public function locateNamespace($namespace, $scheme = 'ClassLoader', array $options = array())
    {
        $this->getScheme($scheme);

        $multiple = false;

        if (isset($options['multiple']) && $options['multiple'] === true) {
            $multiple = true;
        }

        $located_path = $this->adapter_instance_array[$this->adapter_value]->get($namespace, $multiple);

        if (strtolower($scheme) == 'head') {
            echo $this->adapter_value;
            echo 'Path' . $located_path;
            die;
        }

        $options['namespace'] = $namespace;

        return $this->handlePath($this->scheme_value, $located_path, $options);
    }

    /**
     * Handle located folder/file associated with URI Namespace for Resource
     *
     * @param   string $scheme_value
     * @param   string $located_path
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     */
    public function handlePath($scheme_value, $located_path, array $options = array())
    {
        $this->getScheme($scheme_value);

        if (strtolower($scheme_value) == 'query') {
            $xml = $this->adapter_instance_array['Xml']->handlePath(
                $scheme_value,
                $located_path,
                $options
            );;
            $options['xml'] = $xml;

            $this->adapter_value = 'Query';
        }

        return $this->adapter_instance_array[$this->adapter_value]->handlePath($scheme_value, $located_path, $options);
    }

    /**
     * Retrieve a collection of a specific adapter
     *
     * @param   string $scheme_value
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0
     */
    public function getCollection($scheme_value, array $options = array())
    {
        $this->getScheme($scheme_value);

        return $this->adapter_instance_array[$this->adapter_value]->getCollection($scheme_value, $options);
    }

    /**
     * Parse the URL
     *
     * @param   string $uri
     *
     * @return  $this
     * @since   1.0
     */
    protected function parseUri($uri)
    {
        $scheme = parse_url($uri, PHP_URL_SCHEME);

        if ($scheme === false) {
            if (strpos($uri, ':///') === false) {
            } else {
                $scheme = substr($uri, 0, strpos($uri, ':///'));
                $uri    = substr($uri, strpos($uri, ':///') + 4, 9999);
            }
        }

        if ($scheme === false) {
            if (strpos($uri, ':/') === false) {
            } else {
                $scheme = substr($uri, 0, strpos($uri, ':/'));
                $uri    = substr($uri, strpos($uri, ':/') + 2, 9999);
            }
        }

        $this->getScheme($scheme);

        $this->host     = parse_url($uri, PHP_URL_HOST);
        $this->user     = parse_url($uri, PHP_URL_USER);
        $this->password = parse_url($uri, PHP_URL_PASS);
        $this->path     = parse_url($uri, PHP_URL_PATH);
        $this->query    = array();
        $query          = parse_url($uri, PHP_URL_QUERY);
        if ($query === null || $query === false) {
            $query = '';
        }
        $temp = explode(',', $query);
        if (count($temp) > 0) {
            foreach ($temp as $item) {
                $pair = explode('=', $item);
                if (count($pair) == 2) {
                    $this->query[$pair[0]] = $pair[1];
                }
            }
        }
        $this->fragment = parse_url($uri, PHP_URL_FRAGMENT);

        return $this;
    }
}
