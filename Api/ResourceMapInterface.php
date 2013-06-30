<?php
/**
 * Resource Map Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Locator\Api;

interface ResourceMapInterface
{
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
    public function addNamespace($namespace_prefix, $base_directory, $prepend = false);

    /**
     * Verify the correctness of the resource map
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function editMap();

    /**
     * Create resource map of folder/file locations and Fully Qualified Namespaces
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function createMap();

    /**
     * Create resource item hash
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function createResourceItemHash();
}
