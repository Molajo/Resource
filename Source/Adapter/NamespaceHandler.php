<?php
/**
 * Namespace Handler
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Adapter;

use CommonApi\Resource\ResourceInterface;

/**
 * Namespace Handler
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class NamespaceHandler extends SetNamespace implements ResourceInterface
{
    /**
     * Set namespace prefix by mapping to the filesystem path
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
        if ($this->setNamespaceExists($namespace_prefix, $namespace_base_directory, $prepend) === true) {
            return $this;
        }

        $this->namespace_prefixes[$namespace_prefix] = array($namespace_base_directory);

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
        $located_path = $this->locateResourceNamespace($resource_namespace, $options);

        if ($located_path === '') {
            return false;
        }

        return true;
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
        return $this->locateResourceNamespace($resource_namespace, $options);
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
        return null;
    }

    /**
     * Locates resource associated with Namespace
     *
     * @param   string $resource_namespace
     * @param   array  $options
     *
     * @return  string
     * @since   1.0.0
     */
    protected function locateResourceNamespace($resource_namespace, array $options = array())
    {
        $located_path = '';

        $this->setScheme($options);

        $this->setResourceNamespace($resource_namespace);

        if (count($this->namespace_prefixes) > 0) {
            $located_path = $this->searchNamespacePrefixes();
        }

        if (trim($located_path) === '') {
            $located_path = $this->searchResourceMap();
        }

        return trim($located_path);
    }
}
