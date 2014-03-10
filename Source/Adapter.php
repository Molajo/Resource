<?php
/**
 * Resource Adapter
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource;

use Exception;
use CommonApi\Exception\RuntimeException;
use CommonApi\Resource\AdapterInterface;
use CommonApi\Resource\HandlerInterface;
use CommonApi\Resource\SchemeInterface;

/**
 * Resource Adapter
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class Adapter implements AdapterInterface
{
    /**
     * Scheme Instance
     *
     * @var    object  CommonApi\Resource\SchemeInterface
     * @since  1.0
     */
    protected $scheme;

    /**
     * Handler Instances
     *
     * @var    object  CommonApi\Resource\HandlerInterface
     * @since  1.0
     */
    protected $handler_instance_array = array();

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
     * Handler Value
     *
     * @var    string
     * @since  1.0
     */
    protected $handler_value;

    /**
     * Constructor
     *
     * @param  SchemeInterface $scheme
     * @param  array           $handler_instance_array
     *
     * @since  1.0
     */
    public function __construct(
        SchemeInterface $scheme,
        array $handler_instance_array = array()
    ) {
        $this->scheme                 = $scheme;
        $this->handler_instance_array = array();

        foreach ($handler_instance_array as $key => $value) {
            $this->setHandlerInstance($key, $value);
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
        foreach ($this->handler_instance_array as $key => $value) {
            $this->handler_instance_array[$key]->setNamespace(
                $namespace_prefix,
                $base_directory,
                $prepend
            );
        }

        return $this;
    }

    /**
     * Pass in the Handler Instance for a Scheme Handler
     * => For class construction or adding a new scheme/handler after instantiation
     *
     * @param   string $handler
     * @param   object $handler_instance
     *
     * @return  $this
     * @since   1.0
     */
    public function setHandlerInstance($handler = 'File', $handler_instance)
    {
        if ($handler_instance instanceof HandlerInterface) {
            $this->handler_instance_array[$handler] = $handler_instance;
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

        $this->scheme_value = $scheme;

        $this->scheme_properties = $this->scheme->getScheme($this->scheme_value);

        if ($this->scheme_properties === false) {
            throw new RuntimeException ('Resource getScheme Scheme not found: ' . $this->scheme_value);
        }

        $this->handler_value = $this->scheme_properties->handler;

        if (isset($this->handler_instance_array[$this->handler_value])) {
        } else {
            echo 'in Resource Adapter ' . $this->handler_value . ' <br />';
            echo '<pre>';
            foreach ($this->handler_instance_array as $key => $value) {
                echo $key . '<br />';
            }
            var_dump($this->handler_instance_array);
            echo '</pre>';
            throw new RuntimeException ('Resource getScheme Handler not found: ' . $this->handler_value);
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
     */
    public function setScheme($scheme_name, $handler = 'File', array $extensions = array(), $replace = false)
    {
        $this->scheme->setScheme($scheme_name, $handler, $extensions, $replace);

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

            $located_path = $this->handler_instance_array[$this->handler_value]->get($uri_namespace);
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
    public function locateNamespace($namespace, $scheme = 'Class', array $options = array())
    {
        $this->getScheme($scheme);

        $multiple = false;
        if (isset($options['multiple']) && $options['multiple'] === true) {
            $multiple = true;
        }

        $located_path = $this->handler_instance_array[$this->handler_value]->get($namespace, $multiple);

        if (strtolower($scheme) == 'head') {
            echo $this->handler_value;
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

        if ($scheme_value == 'query') {

            $options['xml'] = $this->handler_instance_array['XmlHandler']->handlePath(
                $scheme_value,
                $located_path,
                $options
            );

            $this->handler_value = 'QueryHandler';
        }

        return $this->handler_instance_array[$this->handler_value]->handlePath($scheme_value, $located_path, $options);
    }

    /**
     * Retrieve a collection of a specific handler
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

        return $this->handler_instance_array[$this->handler_value]->getCollection($scheme_value, $options);
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
