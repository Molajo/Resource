<?php

/**
 * Locator Class Injector
 *
 * @package   Molajo
 * @license   http:/www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Service\Locator;

/**
 * Locator Class Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http:/www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Test
{
    /**
     * Include Primary Array
     *
     * @var    array
     * @since  1.0
     */
    protected $primary_array = array();

    /**
     * Scheme Array
     *
     * @var    array
     * @since  1.0
     */
    protected $scheme_array = array();

    /**
     * Order of precedence for searching
     *
     * @var    array
     * @since  1.0
     */
    protected $sort_order = array();

    /**
     * Handler Instances
     *
     * @var    object  Molajo\Locator\Api\LocatorInterface
     * @since  1.0
     */
    protected $tags_array = array();

    /**
     * Handler Instances
     *
     * @var    object  Molajo\Locator\Api\LocatorInterface
     * @since  1.0
     */
    protected $handler_instance;

    /**
     * Constructor
     *
     * @param  $options
     *
     * @since  1.0
     */
    public function __construct(
        $resource_primary_array_filename = 'resource_primary_array.json',
        $resource_scheme_array_filename = 'resource_scheme_array.json',
        $resource_sort_order_filename = 'resource_sort_order.json',
        $resource_tags_filename = 'resource_tags_array.json',
        array $options = array())
    {
        $class_array = 'primary_array';
        $filename = $resource_primary_array_filename;
        $this->readFile($filename, $class_array);

        $class_array = 'scheme_array';
        $filename = $resource_scheme_array_filename;
        $this->readFile($filename, $class_array);

        $class_array = 'sort_order';
        $filename = $resource_sort_order_filename;
        $this->readFile($filename, $class_array);

        $class_array = 'tags_array';
        $filename = $resource_tags_filename;
        $this->readFile($filename, $class_array);
    }

    /**
     * Read File
     *
     * @param  string $name
     * @param  string $class_array
     *
     * @since  1.0
     */
    public function readFile($filename, $class_array)
    {
        $temp_array = array();

        $filename = __DIR__ . '/' . $filename;

        if (file_exists($filename)) {
            $input = file_get_contents($filename);
            $temp  = json_decode($input);

            if (count($temp) > 0) {
                $temp_array = array();
                foreach ($temp as $key => $value) {
                    $temp_array[$key] = $value;
                }
            }
        } else {

            file_put_contents($filename, json_encode($this->$class_array, JSON_PRETTY_PRINT));
            $temp_array = $this->$class_array;
        }

        $this->$class_array = $temp_array;
    }

    /**
     * Rebuild Resource Map
     *
     * @return  $this|object
     * @since   1.0
     */
    public function rebuildResourceMap()
    {
        $namespace_prefixes = array();

        $namespace_prefixes = $this->buildNameSpacePrefixes(
            $this->tags_array,
            $this->sort_order,
            array()
        );

        /** Append in with Canonical Namespace Prefixes */
        foreach ($this->include_canonical_array as $namespace => $paths) {
            foreach ($paths as $path) {
                $namespace_prefixes = $this->mergeMultidimensionalArray(
                    $namespace,
                    $namespace_prefixes,
                    $path
                );
            }
        }

        return $namespace_prefixes;
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

                        foreach ($this->exclude_in_path_array as $exclude) {

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
}

$x = new test();
