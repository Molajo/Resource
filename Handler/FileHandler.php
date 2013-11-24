<?php
/**
 * File Resource Handler
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Handler;

use CommonApi\Resource\HandlerInterface;

/**
 * File Resource Handler
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class FileHandler extends AbstractHandler implements HandlerInterface
{
    /**
     * Constructor
     *
     * @param  string $base_path
     * @param  array  $resource_map
     * @param  array  $namespace_prefixes
     * @param  array  $valid_file_extensions
     *
     * @since  1.0
     */
    public function __construct(
        $base_path = null,
        array $resource_map = array(),
        array $namespace_prefixes = array(),
        array $valid_file_extensions = array()
    ) {
        parent::__construct(
            $base_path,
            $resource_map,
            $namespace_prefixes,
            $valid_file_extensions
        );
    }

    /**
     * Locates folder/file associated with Namespace for Resource
     *
     * @param   string $resource_namespace
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Exception\Resources\ResourcesException
     */
    public function get($resource_namespace, $multiple = false)
    {
        return parent::get($resource_namespace);
    }

    /**
     * Set a namespace prefix by mapping to the filesystem path
     *
     * @param   string  $namespace_prefix
     * @param   string  $namespace_base_directory
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0
     */
    public function setNamespace($namespace_prefix, $namespace_base_directory, $prepend = false)
    {
        return parent::setNamespace($namespace_prefix, $namespace_base_directory, $prepend);
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
     * @throws  \Exception\Resources\ResourcesException
     */
    public function handlePath($scheme, $located_path, array $options = array())
    {
        if (file_exists($located_path)) {
            return $located_path;
        }

        return false;
    }

    /**
     * Retrieve a collection of a specific handler
     *
     * @param   string $scheme
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Exception\Resources\ResourcesException
     */
    public function getCollection($scheme, array $options = array())
    {
        return;
    }
}
