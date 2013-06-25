<?php
/**
 * Locator
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Locator\Utilities;

use Molajo\Locator\Api\ResourceLocatorInterface;
use Molajo\Locator\Api\ResourceMapInterface;
use Molajo\Locator\Exception\LocatorException;

/**
 * Locator
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class Locator implements ResourceLocatorInterface
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
     * Exclude when these values are found within the path
     *
     * @var    array
     * @since  1.0
     */
    protected $exclude_in_path_array = array(
        '.dev',
        '.travis.yml',
        'README',
        'LICENSE',
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
     * Valid extensions to use during map build (shared for all handlers)
     *
     * @var    array
     * @since  1.0
     */
    protected $valid_extensions_array = array();

    /**
     * Resource Map Instance
     *
     * @var    object Molajo\Locator\Api\ResourceMapInterface
     * @since  1.0
     */
    protected $resource_map_instance;

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
    public function __construct(
        array $file_extensions = array('Class' => '.php,.inc'),
        array $namespace_prefixes = array(),
        $base_path = null,
        $rebuild_map = false,
        $resource_map_filename = null,
        $exclude_in_path_array = array(),
        $exclude_path_array = array(),
        $valid_extensions_array = array(),
        ResourceMapInterface $resource_map_instance
    ) {
        $this->base_path             = $base_path;
        $this->namespace_prefixes    = $namespace_prefixes;
        $this->resource_map_filename = $resource_map_filename;
        $this->resource_map_instance = $resource_map_instance;

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
            $this->createResourceMap($namespace_prefixes, $resource_map_filename);
        }

        $this->handler_file_extensions = array();
        foreach ($file_extensions as $handler => $extension_list) {
            $this->handler_file_extensions[$handler] = explode(',', $extension_list);
        }
    }

    /**
     * Locates folder/file associated with URI Namespace for Resource
     *
     * @param   string $resource
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function get($uri_namespace)
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
}
