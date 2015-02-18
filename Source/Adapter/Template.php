<?php
/**
 * Template View Resources
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Adapter;

use Exception;
use CommonApi\Resource\AdapterInterface;
use CommonApi\Exception\RuntimeException;

/**
 * Template View Resources
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class Template extends Extension implements AdapterInterface
{
    /**
     * Constructor
     *
     * @param  string $base_path
     * @param  array  $resource_map
     * @param  array  $namespace_prefixes
     * @param  array  $valid_file_extensions
     * @param  object $extensions
     * @param  object $resource
     *
     * @since  1.0.0
     */
    public function __construct(
        $base_path = null,
        array $resource_map = array(),
        array $namespace_prefixes = array(),
        array $valid_file_extensions = array(),
        $extensions,
        $resource
    ) {
        parent::__construct(
            $base_path,
            $resource_map,
            $namespace_prefixes,
            $valid_file_extensions,
            $extensions,
            $resource
        );

        $this->catalog_type_id       = 9000;
        $this->catalog_type_priority = 200;
    }

    /**
     * Search compiled namespace map for resource namespace
     *
     * @param   string $resource_namespace
     *
     * @return  mixed|bool|string
     * @since   1.0.0
     */
    protected function searchResourceMap($resource_namespace, $multiple = false)
    {
        if (isset($this->resource_map[strtolower($resource_namespace)])) {
        } else {

            /** Default location */
            $path                 = $this->base_path . 'Source/Views/Templates'
                . ucfirst(strtolower($this->extension->alias));
            $this->extension_path = $path;
            $include_path         = $path;

            return $include_path;
        }

        $paths = $this->resource_map[strtolower($resource_namespace)];

        if (is_array($paths)) {
        } else {
            $paths = array($paths);
        }

        foreach ($paths as $path) {
            $include_path         = $path;
            $this->extension_path = $path;
            return $include_path;
        }

        return false;
    }

    /**
     * Retrieve a collection of a specific handler
     *
     * @param   string $scheme
     * @param   array  $options
     *
     * @return  Template
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function getCollection($scheme, array $options = array())
    {
        return $this;
    }
}
