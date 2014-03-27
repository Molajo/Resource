<?php
/**
 * Folder Resource Adapter
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Adapter;

use CommonApi\Resource\AdapterInterface;

//todo: add scoping overrides, etc. and multiple folders returned when needed

/**
 * Folder Resource Adapter
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class Folder extends AbstractAdapter implements AdapterInterface
{
    /**
     * Locates folder/file associated with Namespace for Resource
     *
     * @param   string $resource_namespace
     * @param   bool   $multiple
     *
     * @return  void|mixed|string|array
     * @since   1.0
     */
    public function get($resource_namespace, $multiple = false)
    {
        $located_path           = false;
        $this->located_multiple = array();

        $temp_resource            = ltrim($resource_namespace, '//');
        $temp_resource            = str_replace('//', '\\', $temp_resource);
        $temp_resource            = str_replace('/', '\\', $temp_resource);
        $resource_namespace       = $temp_resource;
        $this->resource_namespace = $resource_namespace;

        if (count($this->namespace_prefixes) > 0) {

            foreach ($this->namespace_prefixes as $namespace_prefix => $base_directories) {

                $namespace_prefix = htmlspecialchars(strtolower($namespace_prefix));

                if (stripos(strtolower($resource_namespace), $namespace_prefix) === false) {
                } else {

                    $located_path = $this->searchNamespacePrefix(
                        $resource_namespace,
                        $namespace_prefix,
                        $base_directories
                    );

                    if ($located_path === false) {
                    } else {
                        if ($multiple === false) {
                            break;
                        } else {
                            $this->located_multiple[] = $located_path;
                        }
                    }
                }
            }
        }

        if ($located_path === false || $multiple === true) {
            $located_path = $this->searchResourceMap($resource_namespace, $multiple);
            if ($multiple === true) {
                if (is_array($located_path) && count($located_path) > 0) {
                    foreach ($located_path as $item) {
                        $this->located_multiple[] = $item;
                    }
                }
            }
        }

        if ($multiple === true) {
            return $this->located_multiple;
        }

        return $located_path;
    }

    /**
     * Set a namespace prefix by mapping to the filesystem path
     *
     * @param   string $resource_namespace
     * @param   string $namespace_prefix
     * @param   array  $base_directories
     *
     * @return  $this
     * @since   1.0
     */
    protected function searchNamespacePrefix(
        $resource_namespace,
        $namespace_prefix,
        $base_directories
    ) {
        foreach ($base_directories as $namespace_base_directory) {

            // Part 1: Base Directory
            $base = $namespace_base_directory;

            // Part 2: Remove Namespace Prefix from Resource Namespace
            if (substr($resource_namespace, strlen($namespace_prefix), 999) == '') {
                $namespace_path = '';
            } else {
                $namespace_path = substr($resource_namespace, strlen($namespace_prefix) + 1, 999);
            }

            // Part 3: Assemble include path for filename, returning matches
            $file_name = $this->base_path . $base . str_replace('\\', '/', $namespace_path);

            if (is_dir($file_name)) {
                return $file_name;
            }
        }

        return false;
    }

    /**
     * Search compiled namespace map for resource namespace
     *
     * @param   string $resource_namespace
     * @param   bool   $multiple
     *
     * @return  mixed|bool|string
     * @since   1.0
     */
    protected function searchResourceMap($resource_namespace, $multiple = false)
    {
        if (isset($this->resource_map[strtolower($resource_namespace)])) {
        } else {
            return false;
        }

        $paths = $this->resource_map[strtolower($resource_namespace)];

        if (is_array($paths)) {
        } else {
            $paths = array($paths);
        }

        foreach ($paths as $path) {

            if (is_dir($path)) {
                if ($multiple === false) {
                    return $path;
                } else {
                    $this->located_multiple[] = $path;
                }
            }
        }

        return false;
    }

    /**
     * Handle located folder/file associated with URI Namespace for Resource
     *
     * @param   string       $scheme
     * @param   string|array $located_path
     * @param   array        $options
     *
     * @return  void|mixed
     * @since   1.0
     */
    public function handlePath($scheme, $located_path, array $options = array())
    {
        return $located_path;
    }
}
