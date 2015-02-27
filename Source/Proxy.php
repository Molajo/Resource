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

        if (count($adapters) > 0) {
            foreach ($adapters as $scheme_name => $scheme_object) {
                $scheme_object->adapter->setNamespace($namespace_prefix, $base_directory, $prepend);
            }
        }

        $this->saveNamespaceArray($namespace_prefix, $base_directory, $prepend);

        return $this;
    }

    /**
     * Verify if resource namespace is defined
     *
     * @param   string $resource_namespace
     * @param   array  $options
     *
     * @return  boolean
     * @since   1.0.0
     */
    public function exists($resource_namespace, array $options = array())
    {
        $results = $this->locateScheme($resource_namespace, $options);

        return $this->requested_adapter->exists($results['resource_namespace'], $results['options']);
    }

    /**
     * Get resource associated with namespace
     *
     * @param   string $resource_namespace
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0.0
     */
    public function get($resource_namespace, array $options = array())
    {
        $results = $this->locateScheme($resource_namespace, $options);

        return $this->requested_adapter->get($results['resource_namespace'], $results['options']);
    }

    /**
     * Retrieve collection for scheme
     *
     * @param   string $scheme
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0.0
     */
    public function getCollection($scheme, array $options = array())
    {
        $this->getScheme($scheme);

        return $this->requested_adapter->getCollection($scheme, $options);
    }
}
