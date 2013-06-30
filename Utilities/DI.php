<?php
/**
 * Dependency Injection
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Locator\Utilities;

use Molajo\Locator\Api\ClassLoaderInterface;
use Molajo\Locator\Api\ExceptionInterface;
use Molajo\Locator\Api\ResourceHandlerInterface;
use Molajo\Locator\Api\ResourceLocatorInterface;
use Molajo\Locator\Api\ResourceMapInterface;
use Molajo\Locator\Api\ResourceSchemeInterface;
use Molajo\Locator\Api\ResourceTagInterface;
use Molajo\Locator\Exception\LocatorException;

/**
 * Dependency Injection
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class DI
{
    /**
     * Scheme and associated extensions and handler
     *
     * @var    array
     * @since  1.0
     */
    protected $scheme_array = array();

    /**
     * Associative Array [Namespace Prefix] => Array of Base Directories
     *  for this Namespace Prefix
     *
     * @var    array
     * @since  1.0
     */
    protected $namespace_prefixes = array();

    /**
     * Base Path - root of the website from which paths are defined
     *
     * @var    string
     * @since  1.0
     */
    protected $base_path = null;

    /**
     * Handler Instances
     *
     * @var    object  Molajo\Locator\Api\LocatorInterface
     * @since  1.0
     */
    protected $exclude_in_path_array = array(
        '.dev',
        '.travis.yml',
        '.DS_Store',
        '.git',
        '.',
        '..',
        '.gitattributes',
        '.gitignore'
    );

    /**
     * Exclude these pairs during build
     *
     * @var    array
     * @since  1.0
     */
    protected $exclude_path_array = array();

    /**
     * Resource Map Filename
     *
     * @var    string
     * @since  1.0
     */
    protected $resource_map_filename = 'ResourceMap.json';

    /**
     * Associative Array [Fully Qualified Namespace] => Folder-File Name
     *
     * @var    array
     * @since  1.0
     */
    protected $resource_map = array();

    /**
     * Valid extensions to use during map build (shared for all handlers)
     *
     * @var    array
     * @since  1.0
     */
    protected $valid_extensions_array = array();

    /**
     * Constructor
     *
     * @param   array       $scheme_array
     * @param   array       $namespace_prefixes
     * @param   null|string $base_path
     * @param   bool        $rebuild_map
     * @param   array       $exclude_in_path_array
     * @param   array       $exclude_path_array
     * @param   array       $valid_extensions_array
     * @param   null|string $resource_map_filename
     * @param   array       $sort_order_array
     *
     * @since   1.0
     */
    public function __construct(
        array $scheme_array = array(),
        array $namespace_prefixes = array(),
        $base_path = null,
        $rebuild_map = false,
        $exclude_in_path_array = array(),
        $exclude_path_array = array(),
        $valid_extensions_array = array(),
        $resource_map_filename = null,
        array $sort_order_array = array()
    ) {
        $this->base_path             = $base_path;
        $this->namespace_prefixes    = $namespace_prefixes;
        $this->resource_map_filename = $resource_map_filename;

        if (array($exclude_in_path_array)
            && count($exclude_in_path_array) > 0
        ) {
            $this->exclude_in_path_array = $exclude_in_path_array;
        }

        if (array($exclude_path_array)
            && count($exclude_path_array) > 0
        ) {
            $this->exclude_path_array = $exclude_path_array;
        }

        if (array($valid_extensions_array)
            && count($valid_extensions_array) > 0
        ) {
            $this->valid_extensions_array = $valid_extensions_array;
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
            $this->create($namespace_prefixes, $resource_map_filename);
        }
    }
public function load($filename)
{

    if (file_exists($filename)) {
        $input        = file_get_contents($filename);
        $temp = json_decode($input);
        if (count($temp) > 0) {
            $temp_array = array();
            foreach ($temp_array as $key => $value) {
                $temp_array[$key] = $value;
            }
        }
    }

    return $temp_array;
}
    /**
     * Add Scheme to Associate with Resource
     *
     * @param   string $scheme
     * @param   string $handler
     * @param   array  $extensions
     * @param   bool   $replace
     *
     * @return  $this|void
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function addScheme($scheme, $handler = 'File', array $extensions = array(), $replace = false)
    {
        $scheme_row          = new \stdClass();
        $scheme_row->scheme  = $scheme;
        $scheme_row->handler = $handler;

        foreach ($extensions as $extension) {
            $scheme->extensions[] = $extension;
        }

        if (isset($this->scheme[$scheme])) {
        } else {
            $this->scheme[$scheme] = $scheme_row;
        }

        return $this;
    }

    /**
     * Remove Scheme
     *
     * @param   string $scheme
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function removeScheme($scheme)
    {

    }

    /**
     * Registers a namespace prefix with filesystem path, appending the filesystem path to existing paths
     *
     * @param   string  $namespace_prefix
     * @param   string  $base_directory
     * @param   boolean $replace
     *
     * @return  $this
     * @since   1.0
     */
    public function addNamespace($namespace_prefix, $base_directory, $replace = false)
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
     * Build Namespace Prefixes
     *
     * @param   array $include_array
     * @param   array $sort_order
     * @param   array $path_array (merge into existing)
     *
     * @return  array
     * @since   1.0
     */
    protected function buildNameSpacePrefixes(
        array $include_array = array(),
        array $sort_order = array(),
        array $path_array = array()
    ) {
        $namespace_prefixes = array();

        $objects = new RecursiveIteratorIterator
        (new RecursiveDirectoryIterator(BASE_FOLDER),
            RecursiveIteratorIterator::SELF_FIRST);

        foreach ($objects as $path => $fileObject) {

            $use       = true;
            $file_name = '';
            $base_name = '';

            if (is_dir($fileObject)) {

                if ($fileObject->getFileName() == '.' || $fileObject->getFileName() == '..') {

                } elseif (in_array($fileObject->getFileName(), $include_array)) {

                    $path = substr($fileObject->getPathName(), strlen(BASE_FOLDER) + 1, 9999);

                    foreach ($include_array as $namespace => $key) {

                        $skip = 0;

                        foreach ($exclude_in_path_array as $exclude) {

                            if (strpos($path, $exclude) === false) {
                            } else {
                                if ($key !== $exclude) {
                                    $skip = 1;
                                }
                            }
                        }

                        if (substr($path, - strlen($key)) == $key && $skip == 0) {
                            $path_array = $this->mergeMultidimensionalArray(
                                $namespace,
                                $path_array,
                                $path
                            );
                        }
                    }
                }
            }
        }

        ksort($path_array);

        return $path_array;
    }

    /**
     * Merge Array for Namespace
     *
     * @param   $namespace
     * @param   $path_array
     * @param   $path
     *
     * @return  $this|object
     * @since   1.0
     */
    protected function mergeMultidimensionalArray($namespace, $path_array, $path)
    {
        $paths = array();

        if (isset($path_array[$namespace])) {

            $existing = $path_array[$namespace];

            if (is_array($existing)) {
                $paths = $existing;
            } else {
                $paths[] = array();
                $paths[] = $existing;
            }

        } else {
            $paths = array();
        }

        $paths[]                = $path;
        $path_array[$namespace] = $paths;

        return $path_array;
    }

    /**
     * Create resource map of folder/file locations and Fully Qualified Namespaces
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function createMap()
    {
        $resource_map = array();

        foreach ($this->namespace_prefixes as $namespace_prefix => $base_directories) {

            foreach ($base_directories as $base_directory) {
                $objects = array();

                if (is_dir($this->base_path . '/' . $base_directory)) {

                    $objects = new RecursiveIteratorIterator
                    (new RecursiveDirectoryIterator($this->base_path . '/' . $base_directory),
                        RecursiveIteratorIterator::SELF_FIRST);

                } else {
                    break;
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
                            $file_extension = '';
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

                        $skip = 0;
                        if (count($this->exclude_path_array) > 0) {
                            foreach ($this->exclude_path_array as $exclude_path => $exclude_ns) {

                                if (substr($nspath, 0, strlen($exclude_path)) === $exclude_path
                                    && substr($fqns, 0, strlen($exclude_ns)) === $exclude_ns
                                ) {
                                    $skip = 1;
                                    break;
                                }
                            }
                        }

                        if (count($this->valid_extensions_array) > 0) {
                            foreach ($this->valid_extensions_array as $extension) {
                                if ($file_extension == '') {
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

        return $resource_map;
    }

    /**
     * Create resource item hash
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function createResourceItemHash()
    {

    }

    /**
     * Verify the correctness of the resource map
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function editMap()
    {

    }
}
