<?php
/**
 * Resource Proxy
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource;

use CommonApi\Resource\ResourceInterface;
use Molajo\Resource\Proxy\ClassLoader;

/**
 * Resource Proxy
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class Proxy extends ClassLoader implements ResourceInterface
{
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
    public function setNamespace($namespace_prefix, $base_directory, $prepend = true)
    {
        $adapters = $this->scheme->getScheme('all');

        foreach ($adapters as $scheme_name => $scheme_object) {
            $scheme_object->adapter->setNamespace($namespace_prefix, $base_directory, $prepend);
        }

        return $this;
    }

    /**
     * Verify if the resource namespace has been defined
     *
     * @param   string $uri_namespace
     *
     * @return  boolean
     * @since   1.0.0
     */
    public function exists($uri_namespace)
    {
        $located_path = $this->getUriPath($uri_namespace);

        if ($located_path === false) {
            return false;
        }

        return true;
    }

    /**
     * Locates folder/file associated with URI Namespace for Resource
     *
     * @param   string $uri_namespace
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0.0
     */
    public function get($uri_namespace, array $options = array())
    {
        return $this->getUriResource($uri_namespace, $options);
    }

    /**
     * Retrieve a collection of a specific adapter
     *
     * @param   string $scheme_value
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0.0
     */
    public function getCollection($scheme_value, array $options = array())
    {
        $scheme = $this->getScheme($scheme_value);

        return $scheme->adapter->getCollection($scheme_value, $options);
    }
}
