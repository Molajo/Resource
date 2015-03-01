<?php
/**
 * Resource Scheme Class
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Proxy;

use CommonApi\Exception\RuntimeException;
use CommonApi\Resource\SchemeInterface;
use CommonApi\Resource\ResourceInterface;
use stdClass;

/**
 * Resource Scheme Class
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
abstract class Scheme implements SchemeInterface
{
    /**
     * Saved Namespace Array for yet to be defined Resource Adapters
     *
     * @var    array
     * @since  1.0.0
     */
    protected $namespace_array = array();

    /**
     * Requested Scheme
     *
     * @var    string
     * @since  1.0.0
     */
    protected $requested_scheme;

    /**
     * Name of requested resource adapter
     *
     * @var    object  CommonApi\Resource\ResourceInterface
     * @since  1.0.0
     */
    protected $requested_adapter;

    /**
     * Scheme Instance
     *
     * @var    object  CommonApi\Resource\SchemeInterface
     * @since  1.0.0
     */
    protected $scheme;

    /**
     * Constructor
     *
     * @param  SchemeInterface $scheme
     *
     * @since  1.0.0
     */
    public function __construct(
        SchemeInterface $scheme
    ) {
        $this->scheme = $scheme;
    }

    /**
     * Define scheme, allowable file extensions and adapter instance
     *
     * @param   string            $scheme_name
     * @param   ResourceInterface $adapter
     * @param   array             $extensions
     *
     * @return  $this
     * @since   1.0.0
     */
    public function setScheme($scheme_name, ResourceInterface $adapter, array $extensions = array())
    {
        $this->scheme->setScheme($scheme_name, $adapter, $extensions);

        $this->getScheme($scheme_name);

        $this->setAdapterNamespaces();

        return $this;
    }

    /**
     * Get Scheme
     *
     * @param   string $scheme_name
     *
     * @return  null|object
     * @since   1.0.0
     */
    public function getScheme($scheme_name)
    {
        $this->requested_scheme = ucfirst(strtolower($scheme_name));

        $response = $this->scheme->getScheme($this->requested_scheme);

        if ($response === null) {
            throw new RuntimeException('Resource getScheme Scheme not found for request: ' . $this->requested_scheme);
        }

        $this->requested_adapter = $response->adapter;

        return $response;
    }

    /**
     * Set namespace prefixes for Adapter
     *
     * @return  $this
     * @since   1.0.0
     */
    public function setAdapterNamespaces()
    {
        if (count($this->namespace_array) === 0) {
            return $this;
        }

        foreach ($this->namespace_array as $row) {
            $this->requested_adapter->setNamespace($row->namespace_prefix, $row->base_directory, $row->prepend);
        }

        return $this;
    }

    /**
     * Map a namespace prefix to a filesystem path
     *
     * @param   string  $namespace_prefix
     * @param   string  $base_directory
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function saveNamespaceArray($namespace_prefix, $base_directory, $prepend = true)
    {
        $row                   = new stdClass();
        $row->namespace_prefix = $namespace_prefix;
        $row->base_directory   = $base_directory;
        $row->prepend          = $prepend;

        $this->namespace_array[] = $row;

        return $this;
    }

    /**
     * Verify if resource namespace is defined
     *
     * @param   string $resource_namespace
     * @param   array  $options
     *
     * @return  array
     * @since   1.0.0
     */
    protected function locateScheme($resource_namespace, array $options = array())
    {
        $this->getUriScheme($resource_namespace);

        $resource_namespace = $this->removeUriScheme($resource_namespace);

        $this->getScheme($this->requested_scheme);

        $options['scheme_name'] = $this->requested_scheme;

        return array('resource_namespace' => $resource_namespace, 'options' => $options);
    }

    /**
     * Set Uri Scheme
     *
     * @param   string $uri
     *
     * @return  string
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function getUriScheme($uri)
    {
        $scheme = parse_url($uri, PHP_URL_SCHEME);

        if ($scheme === false) {
            throw new RuntimeException('Resource Scheme must define scheme:// with resource requests');
        }

        return $scheme;
    }

    /**
     * Set Uri Scheme removeUriScheme
     *
     * @param   string $uri
     * @param   string $scheme
     *
     * @return  string
     * @since   1.0.0
     */
    protected function removeUriScheme($uri)
    {
        return substr($uri, strpos($uri, $this->requested_scheme) + strlen($this->requested_scheme) + 3, 9999);
    }
}
