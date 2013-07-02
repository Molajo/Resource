<?php
/**
 * Resource Map
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resources;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Molajo\Resources\Api\ResourceNamespaceInterface;
use Molajo\Resources\Api\ResourceMapInterface;
use Molajo\Resources\Exception\ResourcesException;

/**
 * Resource Map
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class ResourceMap implements ResourceNamespaceInterface, ResourceMapInterface
{
    /**
     * Primary Array => object
     *  Levels
     *  IncludeFolders
     *  ExcludeFolders
     *  RequireFileExtensions
     *  ProhibitFileExtensions
     *  Tags
     *
     * @var    array
     * @since  1.0
     */
    protected $primary_array = array();

    /**
     * Sort Order Array
     *
     * @var    array
     * @since  1.0
     */
    protected $sort_array = array();

    /**
     * Resource Map
     *
     * @var    array
     * @since  1.0
     */
    protected $resource_map = array();

    /**
     * Resource Map Filename
     *
     * @var    string
     * @since  1.0
     */
    protected $resource_map_filename = 'Files/ResourceMap.json';

    /**
     * Base Path - root of the website from which paths are defined
     *
     * @var    string
     * @since  1.0
     */
    protected $base_path = null;

    /**
     * Namespace Prefixes + Path
     *
     * @var    string
     * @since  1.0
     */
    protected $namespace_prefixes = null;

    /**
     * Constructor
     *
     * @param  string $base_path
     * @param  bool   $rebuild_map
     * @param  string $primary_array_filename
     * @param  string $sort_array_filename
     * @param  string $resource_map_filename
     *
     * @since  1.0
     */
    public function __construct(
        $base_path = '/',
        $rebuild_map = false,
        $primary_array_filename = 'Files/PrimaryArray.json',
        $sort_array_filename = 'Files/SortArray.json',
        $resource_map_filename = 'Files/ResourceMap.json'
    ) {
        $this->base_path = $base_path;

        $class_array = 'primary_array';
        $filename    = $primary_array_filename;
        $this->readFile($filename, $class_array);

        $class_array = 'sort_array';
        $filename    = $sort_array_filename;
        $this->readFile($filename, $class_array);

        $class_array = 'resource_map';
        $filename    = $resource_map_filename;
        $this->readFile($filename, $class_array);

        $this->resource_map_filename = __DIR__ . '/' . $resource_map_filename;

        //if ($rebuild_map === true) {
            $this->createMap();
        //}
    }

    /**
     * Locates folder/file associated with URI Namespace for Resource
     *
     * @param   string $resource
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function locateResource($resource)
    {
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
            return $this->handler_instance[$this->scheme]->handlePath(false, $options);

            return false;
        }

        return $this->handler_instance[$this->scheme]->handlePath($located_path, $options);
    }

    /**
     * Get a namespace (or all namespaces)
     *
     * @param   string $namespace_prefix
     *
     * @return  $this
     * @since   1.0
     */
    public function getNamespace($namespace_prefix)
    {

    }

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
    public function setNamespace($namespace_prefix, $base_directory, $prepend = false)
    {
        //todo: add edit to prevent multiple namespaces to a single php file, always a replace

        if (isset($this->namespace_prefixes[$namespace_prefix])) {
            if ($replace === true) {
                $this->namespace_prefixes[$namespace_prefix] = array();
            }
        }

        if (isset($this->namespace_prefixes[$namespace_prefix])) {

            $hold = $this->namespace_prefixes[$namespace_prefix];

            if (in_array($base_directory, $hold)) {
            } else {
                $hold[]                                      = $base_directory;
                $this->namespace_prefixes[$namespace_prefix] = $hold;
            }

        } else {
            $this->namespace_prefixes[$namespace_prefix] = array($base_directory);
        }

        return $this;
    }

    /**
     * Get the resource map
     *
     * @return  $this
     * @since   1.0
     */
    public function getMap()
    {

    }

    /**
     * Create resource map of folder/file locations and Fully Qualified Namespaces
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function createMap()
    {
        $resource_map = array();

        foreach ($this->primary_array as $namespace_prefix => $prefix_object) {

            $levels                 = $prefix_object->Levels;
            $includeFolders         = $prefix_object->IncludeFolders;
            $excludeFolders         = $prefix_object->ExcludeFolders;
            $requireFileExtensions  = explode(',', $prefix_object->RequireFileExtensions);
            $prohibitFileExtensions = explode(',', $prefix_object->ProhibitFileExtensions);
            $tags                   = explode(',', $prefix_object->Tags);
            /**
            echo '<br />';
            echo $namespace_prefix;
            echo '<br />';
            echo '<pre>';
            var_dump($levels);
            echo '</pre>';
            echo '<pre>';
            var_dump($includeFolders);
            echo '</pre>';
            echo '<pre>';
            var_dump($excludeFolders);
            echo '</pre>';
            echo '<pre>';
            var_dump($requireFileExtensions);
            echo '</pre>';
            echo '<pre>';
            var_dump($prohibitFileExtensions);
            echo '</pre>';
            echo '<pre>';
            var_dump($tags);
            echo '</pre>';
             */

            foreach ($includeFolders as $base_directory) {

                $objects = array();

                if (is_dir($this->base_path . '/' . $base_directory) && $base_directory !== '') {
                    $objects = new RecursiveIteratorIterator
                    (new RecursiveDirectoryIterator($this->base_path . '/' . $base_directory),
                        RecursiveIteratorIterator::SELF_FIRST);

                } else {
                    if ($base_directory == '') {
                    } else {
                        echo 'createMap: Not a folder ' . $this->base_path . '/' . $base_directory . '<br />';
                    }
                    break;
                }

                $count_levels = 1;
                foreach ($objects as $path => $fileObject) {

                    $path = substr($path, strlen($this->base_path . '/'), 9999);

                    $file_name = '';
                    $base_name = '';

                    $skip = 0;
                    foreach ($excludeFolders as $exclude) {
                        if (strpos($path, '/' . $exclude) === false) {
                        } else {
                            $skip = 1;
                            break;
                        }
                    }

                    if ($skip == 0) {

                        /** Names and path */
                        if (is_dir($fileObject)) {
                            $file_extension = '';
                        } else {
                            $file_name      = $fileObject->getFileName();
                            $file_extension = $fileObject->getExtension();
                            if ($file_extension == 'php') {
                                $base_name = substr($file_name, 0, strlen($file_name) - strlen($file_extension) - 1);
                            } else {
                                $base_name = $file_name;
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

                        $skip = 0;
                        if (count($prohibitFileExtensions) > 0) {
                            foreach ($prohibitFileExtensions as $exclude_path => $exclude_ns) {
                                if ($file_extension == '' || $exclude_ns == '') {
                                } elseif (substr($nspath, 0, strlen($exclude_path)) === $exclude_path
                                    && substr($fqns, 0, strlen($exclude_ns)) === $exclude_ns
                                ) {
                                    $skip = 1;
                                    break;
                                }
                            }
                        }

                        if (count($requireFileExtensions) > 0) {
                            foreach ($requireFileExtensions as $extension) {
                                if ($file_extension == '' || $extension == '') {
                                } elseif ($file_extension == $extension) {
                                } else {
                                    $skip = 1;
                                    break;
                                }
                            }
                        }

                        // Merge paths with existing paths for fqns
                        if ($skip == 0) {
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
        }

        ksort($resource_map);

        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            file_put_contents($this->resource_map_filename, json_encode($resource_map, JSON_PRETTY_PRINT));
        } else {
            file_put_contents($this->resource_map_filename, json_encode($resource_map));
        }

        $this->resource_map = $resource_map;

        return $this->resource_map;
    }

    /**
     * Verify the correctness of the resource map
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function editMap()
    {

    }

    /**
     * Create resource item hash
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    protected function createResourceItemHash()
    {

    }

    /**
     * Parse the URL
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    protected function parseUrl($url)
    {
        $this->scheme   = parse_url($url, PHP_URL_SCHEME);
        $this->host     = parse_url($url, PHP_URL_HOST);
        $this->user     = parse_url($url, PHP_URL_USER);
        $this->password = parse_url($url, PHP_URL_PASS);
        $this->path     = parse_url($url, PHP_URL_PATH);
        $this->query    = parse_url($url, PHP_URL_QUERY);
        $this->fragment = parse_url($url, PHP_URL_FRAGMENT);

        return $this;
    }

    /**
     * Read File
     *
     * @param  string $name
     * @param  string $class_array
     *
     * @since  1.0
     */
    protected function readFile($filename, $class_array)
    {
        $temp_array = array();

        $filename = __DIR__ . '/' . $filename;

        if (file_exists($filename)) {
        } else {
            return;
        }

        $input = file_get_contents($filename);
        $temp  = json_decode($input);

        if (count($temp) > 0) {
            $temp_array = array();
            foreach ($temp as $key => $value) {
                $temp_array[$key] = $value;
            }
        }

        $this->$class_array = $temp_array;
    }
}
