<?php
/**
 * File Locator
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Locator\Handler;

use Molajo\Locator\Api\ResourceMapInterface;
use Molajo\Locator\Api\ResourceLocatorInterface;
use Molajo\Locator\Handler\AbstractLocator;

/**
 * File Locator
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class FileLocator implements ResourceLocatorInterface
{
    /**
     * Constructor
     *
     * @param   array                $file_extensions
     * @param   array                $namespace_prefixes
     * @param   null|string          $base_path
     * @param   bool                 $rebuild_map
     * @param   null|string          $resource_map_filename
     * @param   array                $exclude_in_path_array
     * @param   array                $exclude_path_array
     * @param   array                $valid_extensions_array
     * @param   ResourceMapInterface $resource_map_instance
     *
     * @since   1.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Locates folder/file associated with URI Namespace for Resource
     *
     * @param   string $located_path
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function handlePath($located_path, array $options = array())
    {
        if (file_exists($located_path)) {
            return $located_path;
        }

        return;
    }

    /**
     * Retrieve a collection of a specific resource type (ex., all CSS files registered)
     *
     * @param   array $options
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function getCollection(array $options = array())
    {
        return $this->resource_map;
    }
}
