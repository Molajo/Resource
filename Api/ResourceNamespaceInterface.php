<?php
/**
 * Namespace Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resources\Api;

interface ResourceNamespaceInterface
{
    /**
     * Locates a resource using only the namespace
     *
     * @param   string  $namespace
     * @param   string  $scheme
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function locateNamespace($namespace, $scheme = 'Class');

    /**
     * Locates folder/file associated with URI Namespace for Resource
     *
     * @param   string $uri_namespace
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function locate($uri_namespace);

    /**
     * Get a namespace (or all namespaces)
     *
     * @param   string $namespace_prefix
     *
     * @return  $this
     * @since   1.0
     */
    public function getNamespace($namespace_prefix);

    /**
     * Set a namespace prefix by mapping to the filesystem path
     *
     * @param   string  $namespace_prefix
     * @param   string  $base_directory
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0
     */
    public function setNamespace($namespace_prefix, $base_directory, $prepend = false);
}
