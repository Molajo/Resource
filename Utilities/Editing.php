<?php
/**
 * Abstract Locator
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Kernel\Locator\Utilities;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Molajo\Kernel\Locator\Api\LocatorInterface;
use Molajo\Kernel\Locator\Exception\LocatorException;

/**
 * Verifies correctness for Namespace pairs and map
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class Edits
{
    /**
     * Associative Array [Namespace Prefix] => Array of Base Directories
     *  for this Namespace Prefix
     *
     * @var    array
     * @since  1.0
     */
    protected $namespace_prefixes = array();

    /**
     * Associative Array [Fully Qualified Namespace] => Folder-File Name
     *
     * @var    array
     * @since  1.0
     */
    protected $resource_map = array();

    /**
     * Base Path - root of the website from which paths are defined
     *
     * @var    string
     * @since  1.0
     */
    protected $base_path = null;

    /**
     * Array of Handler File Extensions
     *
     * @var    array
     * @since  1.0
     */
    protected $handler_file_extensions = array();

    /**
     * Resource Map Filename
     *
     * @var    string
     * @since  1.0
     */
    protected $resource_map_filename = 'ResourceMap.json';

    /**
     * Handler Instances
     *
     * @var    object  Molajo\Kernel\Locator\Api\LocatorInterface
     * @since  1.0
     */
    protected $exclude_in_path_array = array(
        '.dev',
        '.travis.yml',
        'Service',
        '.DS_Store',
        '.git',
        '.',
        '..',
        '.gitattributes',
        '.gitignore'
    );

    /**
     * Constructor
     *
     * @param   array       $namespace_prefixes
     * @param   null|string $base_path
     * @param   bool        $rebuild_map
     * @param   null|string $resource_map_filename
     * @param   array       $exclude_in_path_array
     *
     * @since   1.0
     */
    public function __construct(
        array $namespace_prefixes = array(),
        $base_path = null,
        $rebuild_map = false,
        $resource_map_filename = null,
        $exclude_in_path_array = array()
    ) {
        $this->base_path             = $base_path;
        $this->namespace_prefixes    = $namespace_prefixes;
        $this->resource_map_filename = $resource_map_filename;

        if (array($exclude_in_path_array)
            && count($exclude_in_path_array) > 0
        ) {
            $this->exclude_in_path_array = $exclude_in_path_array;
        }

        if ($this->resource_map_filename === null) {
            $rebuild_map = false;
        } else {
            if (file_exists($this->resource_map_filename)) {
                $input        = file_get_contents($this->resource_map_filename);
                $resource_map = json_decode($input);
                if (count($resource_map) > 0) {
                    $this->resource_map = array();
                    foreach ($resource_map as $key => $value) {
                        $this->resource_map[$key] = $value;
                    }
                }
            }
        }

        if ($rebuild_map === true) {
            $this->createResourceMap($namespace_prefixes, $resource_map_filename);
        }
    }

    /**
     * Add resource map which maps folder/file locations to Fully Qualified Namespaces
     *
     * @return  $this
     * @since   1.0
     */
    public function createResourceMap()
    {
        $resource_map = array();

        foreach ($this->namespace_prefixes as $namespace_prefix => $base_directories) {

            foreach ($base_directories as $base_directory) {

                $objects = array();

                if (is_dir($this->base_path . '/' . $base_directory)) {
                    $objects = new RecursiveIteratorIterator
                    (new RecursiveDirectoryIterator($this->base_path . '/' . $base_directory),
                        RecursiveIteratorIterator::SELF_FIRST);
                }

                foreach ($objects as $path => $fileObject) {

                    $path = substr($path, strlen($this->base_path . '/'), 9999);

                    $file_name = '';
                    $base_name = '';

                    $skip = 0;
                    foreach ($this->exclude_in_path_array as $exclude) {
                        if (strpos($path, '/' . $exclude) === false) {
                        } else {
                            $skip = 1;
                            break;
                        }
                    }

                    if ($skip == 0) {

                        /** Names and path */
                        if (is_dir($fileObject)) {

                        } else {
                            $file_name      = $fileObject->getFileName();
                            $file_extension = $fileObject->getExtension();
                            if ($file_extension == '') {
                                $base_name = $file_name;
                            } else {
                                $base_name = substr($file_name, 0, strlen($file_name) - strlen($file_extension) - 1);
                            }
                            $path = substr($path, 0, strlen($path) - strlen($file_name) - 1);
                        }

                        $class_namespace_path = substr($path, strlen($base_directory) + 1, 9999);

                        if ($class_namespace_path == '') {
                            $fqns = $namespace_prefix;
                        } else {
                            $fqns = $namespace_prefix . '\\' . str_replace('/', '\\', $class_namespace_path);
                        }

                        $nspath = $path;

                        if ($fileObject->isDir()) {
                        } else {
                            $fqns .= '\\' . $base_name;
                            $nspath .= '/' . $file_name;
                        }

                        // Merge paths with existing paths for fqns
                        $paths = array();

                        if (isset($resource_map[$fqns])) {

                            $existing = $resource_map[$fqns];

                            if (is_array($existing)) {
                                $paths = $existing;
                            } else {
                                $paths[] = array();
                                $paths[] = $existing;
                            }

                        }

                        $paths[]             = $nspath;
                        $resource_map[$fqns] = array_unique($paths);
                    }
                }
            }
        }

        ksort($resource_map);

        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            file_put_contents($this->resource_map_filename, json_encode($resource_map, JSON_PRETTY_PRINT));
        } else {
            file_put_contents($this->resource_map_filename, json_encode($resource_map));
        }

        return $this;
    }

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
    public function locate($resource, array $options = array())
    {
        if (isset($options['handler_type'])) {
            $handler_type = $options['handler_type'];
        } else {
            $handler_type = 'Class';
        }

        if (isset($this->handler_file_extensions[$handler_type])) {
        } else {
            throw new LocatorException ('Locator Locate-Handler Type not found: ' . $handler_type);
        }

        $located_path = false;

        $temp_resource = strtolower(ltrim($resource, '\\'));

        if (isset($this->resource_map[$temp_resource])) {

            $paths = $this->resource_map[$temp_resource];

            if (is_array($paths)) {
            } else {
                $paths = array($paths);
            }

            foreach ($paths as $path) {
                foreach ($this->handler_file_extensions[$handler_type] as $rule_extension) {
                    $file_extension = '.' . pathinfo($path, PATHINFO_EXTENSION);
                    if ($file_extension == $rule_extension) {
                        $located_path = $path;
                        break;
                    }
                }
            }
        }

        if ($located_path === false) {

            foreach ($this->namespace_prefixes as $namespace_prefix => $base_directories) {

                $temp_prefix = strtolower($namespace_prefix) . '\\';

                if (stripos($resource, $temp_prefix) === false) {
                } else {

                    foreach ($base_directories as $base_directory) {

                        // Part 1: the "path to packages" -- base (ex. .dev)
                        $base = $this->base_path . '/' . $base_directory;

                        // Part 2: Remove Namespace Prefix from Class leaving namespace path
                        if (substr($resource, strlen($temp_prefix), 999) == '') {
                            $namespace_path = '';
                        } else {
                            $namespace_path = '\\' . substr($resource, strlen($temp_prefix), 999);
                        }

                        foreach ($this->handler_file_extensions[$handler_type] as $extension) {

                            // Part 3: Assemble include path
                            $temp = str_replace('\\', '/', $base)
                                . str_replace('\\', '/', $namespace_path)
                                . $extension;

                            // Part 4: If exists, match found
                            if (file_exists($temp)) {
                                $located_path = $temp;

                                break;
                            }
                        }
                    }
                }
            }
        }

        if ($located_path === false) {
            return false;
        }

        return $located_path;
    }

    /**
     * Retrieves the collection of resources
     *
     * @param   array $options
     *
     * @return  array
     * @since   1.0
     */
    public function getCollection(array $options = array())
    {
        return $this;
    }
}
