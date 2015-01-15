<?php
/**
 * Create Resource Map
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\ResourceMap;

use Exception;
use ReflectionClass;

/**
 * Resource Map Base
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
abstract class Base
{
    /**
     * Resource Map Filename
     *
     * @var    string
     * @since  1.0
     */
    protected $resource_map_filename;

    /**
     * Interface Map Filename
     *
     * @var    string
     * @since  1.0
     */
    protected $classmap_filename;

    /**
     * Base Path - root of the website from which paths are defined
     *
     * @var    string
     * @since  1.0
     */
    protected $base_path;

    /**
     * Namespace Prefixes + Path
     *
     * @var    array
     * @since  1.0
     */
    protected $namespace_prefixes = array();

    /**
     * List of folders to exclude from resource mapping
     *
     * @var    array
     * @since  1.0
     */
    protected $exclude_folders = array();

    /**
     * Constructor
     *
     * @param  string  $base_path
     * @param  array   $exclude_folders
     * @param  string  $classmap_filename
     * @param  string  $resource_map_filename
     *
     * @since  1.0
     */
    public function __construct(
        $base_path,
        array $exclude_folders = array(),
        $classmap_filename = '',
        $resource_map_filename = ''
    ) {
        $this->base_path       = $base_path;
        $this->exclude_folders = $exclude_folders;

        if ($classmap_filename === '') {
            $this->classmap_filename = $this->base_path . '/Files/Output/ClassMap.json';
        } else {
            $this->classmap_filename = $classmap_filename;
        }

        if ($resource_map_filename === '') {
            $this->resource_map_filename = $this->base_path . '/Files/Output/ResourceMap.json';
        } else {
            $this->resource_map_filename = $resource_map_filename;
        }
    }
    /**
     * Get Reflection Object from PHP
     *
     * @param  string $qns
     *
     * @since  1.0
     * @return object
     */
    protected function getReflectionObject($qns)
    {
        try {
            return new ReflectionClass($qns);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Set a namespace prefix by mapping to the filesystem path
     *
     * @param   string  $namespace
     *
     * @return  string
     * @since   1.0
     */
    protected function addSlash($namespace)
    {
        if (substr($namespace, - 1) === '\\') {
        } else {
            $namespace = $namespace . '\\';
        }

        return $namespace;
    }
}
