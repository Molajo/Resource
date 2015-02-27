<?php
/**
 * Search Resource Map for Namespace
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Adapter;

/**
 * Namespace Handler
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
abstract class SearchResourceMap extends Base
{
    /**
     * Search compiled namespace map for resource namespace
     *
     * @return  mixed|bool|string
     * @since   1.0.0
     */
    protected function searchResourceMap()
    {
        if ($this->searchResourceMapInstance() === false) {
            return false;
        }

        $paths = $this->setResourceMapPaths();

        if (count($paths) > 0) {
            return $this->setResourceMapPaths($paths);
        }

        return '';
    }

    /**
     * Search compiled namespace map for resource namespace
     *
     * @return  mixed|bool|string
     * @since   1.0.0
     */
    protected function searchResourceMapInstance()
    {
        if (isset($this->resource_map[strtolower($this->resource_namespace)])) {
            return true;
        }

        return false;
    }

    /**
     * Set Path for Resource Map Search
     *
     * @return  array
     * @since   1.0.0
     */
    protected function setResourceMapPaths()
    {
        $paths = $this->resource_map[strtolower($this->resource_namespace)];

        if (is_array($paths)) {
        } else {
            $paths = array($paths);
        }

        return $paths;
    }

    /**
     * Set Path for Resource Map Search
     *
     * @param   array $paths
     *
     * @return  array
     * @since   1.0.0
     */
    protected function searchResourceMapPaths(array $paths = array())
    {
        foreach ($paths as $path) {

            if (count($this->valid_file_extensions) > 0) {
                return $this->searchResourceMapFileExtensions($path);
            }

            return $path;
        }

        return '';
    }

    /**
     * Search Resource Map Valid file extensions
     *
     * @param   string $path
     *
     * @return  string
     * @since   1.0.0
     */
    protected function searchResourceMapFileExtensions($path)
    {
        $file_extension = '.' . pathinfo($path, PATHINFO_EXTENSION);

        foreach ($this->valid_file_extensions as $rule_extension) {
            if ($file_extension === $rule_extension) {
                return $path;
            }
        }

        return '';
    }
}
