<?php
/**
 * Locator Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Kernel\Locator\Api;

interface LocatorInterface
{
    /**
     * Registers a namespace prefix with filesystem path, appending the filesystem path to existing paths
     *
     * @param   string       $namespace_prefix
     * @param   array|string $base_directory
     * @param   boolean      $replace
     *
     * @return  $this
     * @since   1.0
     */
    public function addNamespace($namespace_prefix, $base_directory, $replace = false);

    /**
     * Add resource map which maps folder/file locations to Fully Qualified Namespaces
     *
     * @return  $this
     * @since   1.0
     */
    public function createResourceMap();

    /**
     * Locates folder/file associated with Fully Qualified Namespace for Resource and passes
     * the path to a handler for that type of resource (ex. a Class Locator includes the file)
     *
     * @param   string $resource
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Kernel\Locator\Exception\LocatorException
     */
    public function findResource($resource, array $options = array());

    /**
     * Retrieve a collection of a specific resource type (ex., all CSS files registered)
     *
     * @param   array $options
     *
     * @return  mixed
     * @since   1.0
     */
    public function getCollection(array $options = array());
}
