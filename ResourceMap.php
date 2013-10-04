<?php
/**
 * Resource Map
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resources;

use stdClass;
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
     *
     *  levels
     *  include_folders
     *  exclude_folders
     *  include_file_extensions
     *  exclude_file_extensions
     *  tags
     *
     * @var    array
     * @since  1.0
     */
    protected $primary_array = array();

    /**
     * Sort Order Array
     *
     *
     * @var    array
     * @since  1.0
     */
    protected $priority_order_array = array();

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
     * Interface Map Filename
     *
     * @var    string
     * @since  1.0
     */
    protected $interface_map_filename = 'Files/InterfaceMap.json';

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
     * Namespace Rules: Tags
     *
     * @var    array
     * @since  1.0
     */
    protected $namespace_rules_tags = null;

    /**
     * Namespace Rules: Tag Namespace
     *
     * @var    array
     * @since  1.0
     */
    protected $namespace_rules_tag_namespace = null;

    /**
     * Temporary Work File to accumulate Resource Map
     *
     * @var    array
     * @since  1.0
     */
    protected $temp_resource_map = array();

    /**
     * Temporary Work File to accumulate Tag Resources
     *
     * @var    array
     * @since  1.0
     */
    protected $temp_tag = array();

    /**
     * Temporary Work File to accumulate Tag Resources by Namespace
     *
     * @var    array
     * @since  1.0
     */
    protected $temp_tag_ns = array();

    /**
     * Temporary Work File to accumulate PHP Class Files
     *
     * @var    array
     * @since  1.0
     */
    protected $php_files = array();

    /**
     * Constructor
     *
     * @param  string $base_path
     * @param  bool   $rebuild_resource_map
     * @param  string $primary_array_filename
     * @param  string $priority_order_array_filename
     * @param  string $resource_map_filename
     * @param  string $interface_map_filename
     *
     * @since  1.0
     */
    public function __construct(
        $rebuild_resource_map = true,
        $base_path = '/',
        $primary_array_filename = 'Files/PrimaryArray.json',
        $priority_order_array_filename = 'Files/SortArray.json',
        $resource_map_filename = 'Files/ResourceMap.json',
        $interface_map_filename = 'Files/InterfaceMap.json'
    ) {
        $this->base_path = $base_path;

        $property_name_array = 'primary_array';
        $filename            = $primary_array_filename;
        $this->readFile($filename, $property_name_array);

        $property_name_array = 'priority_order';
        $filename            = $priority_order_array_filename;
        $this->readFile($filename, $property_name_array);

        $property_name_array = 'resource_map';
        $filename            = $resource_map_filename;
        $this->readFile($filename, $property_name_array);

        $this->resource_map_filename = __DIR__ . '/' . $resource_map_filename;

        $this->interface_map_filename = __DIR__ . '/' . $interface_map_filename;

        if ($rebuild_resource_map === true) {
            $this->createMap();
        }
    }

    /**
     * Locates a resource using only the namespace
     *
     * @param   string $namespace
     * @param   string $scheme
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function locateNamespace($namespace, $scheme = 'Class')
    {
        $this->get($namespace, '.php');
    }

    /**
     * Locates folder/file associated with Namespace for Resource
     *
     * @param   string $resource_namespace
     * @param   array  $valid_file_extensions
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function get($resource_namespace, array $valid_file_extensions = array('.php'))
    {
        $located_path = false;

        $temp_resource      = strtolower(ltrim($resource_namespace, '/'));
        $temp_resource      = str_replace('//', '\\', $temp_resource);
        $resource_namespace = $temp_resource;

        if (count($this->namespace_prefixes) > 0) {
            foreach ($this->namespace_prefixes as $namespace_prefix => $base_directories) {
                $located_path = $this->searchNamespacePrefix(
                    $resource_namespace,
                    $valid_file_extensions,
                    $namespace_prefix,
                    $base_directories
                );
            }
        }

        if ($located_path === false) {
            $located_path = $this->searchResourceMap($resource_namespace, $valid_file_extensions);
        }

        return $located_path;
    }

    /**
     * Set a namespace prefix by mapping to the filesystem path
     *
     * @param   string $resource_namespace
     * @param   array  $valid_file_extensions
     * @param   string $namespace_prefix
     * @param   array  $base_directories
     *
     * @return  $this
     * @since   1.0
     */
    protected function searchNamespacePrefix(
        $resource_namespace,
        $valid_file_extensions,
        $namespace_prefix,
        $base_directories
    ) {
        $namespace_prefix = strtolower($namespace_prefix);

        if (stripos($resource_namespace, $namespace_prefix) === false) {
        } else {

            foreach ($base_directories as $namespace_base_directory) {

                // Part 1: Base Directory
                $base = $namespace_base_directory;

                // Part 2: Remove Namespace Prefix from Resource Namespace
                if (substr($resource_namespace, strlen($namespace_prefix), 999) == '') {
                    $namespace_path = '';
                } else {
                    $namespace_path = substr($resource_namespace, strlen($namespace_prefix), 999);
                }

                // Part 3: Process each Extension valid for this Resource
                foreach ($valid_file_extensions as $valid_extension) {

                    // Part 4: Assemble include path for filename
                    $file_name = $base . str_replace('\\', '/', $namespace_path);

                    // Part 5: Validate File Extension
                    $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                    if ('.' . $extension == strtolower($valid_extension)) {

                        // Part 6: If exists, match found and return filename
                        if (file_exists($file_name)) {
                            return $file_name;

                            break;
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * Search compiled namespace map for resource namespace
     *
     * @param   string $resource_namespace
     * @param   array  $valid_file_extensions
     *
     * @return  mixed|bool|string
     * @since   1.0
     */
    protected function searchResourceMap($resource_namespace, $valid_file_extensions)
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

            if (count($valid_file_extensions) > 0) {

                $file_extension = '.' . pathinfo($path, PATHINFO_EXTENSION);

                foreach ($valid_file_extensions as $rule_extension) {
                    if ($file_extension == $rule_extension) {
                        return $path;
                    }
                }
            } else {
                return $path;
            }
        }

        return false;
    }

    /**
     * Set a namespace prefix by mapping to the filesystem path
     *
     * @param   string  $namespace_prefix
     * @param   string  $namespace_base_directory
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0
     */
    public function setNamespace($namespace_prefix, $namespace_base_directory, $prepend = false)
    {
        if (isset($this->namespace_prefixes[$namespace_prefix])) {

            $hold = $this->namespace_prefixes[$namespace_prefix];

            if ($prepend === false) {
                $hold[]                                      = $namespace_base_directory;
                $this->namespace_prefixes[$namespace_prefix] = $hold;
            } else {
                $new   = array();
                $new[] = $namespace_base_directory;
                foreach ($hold as $h) {
                    $new[] = $h;
                }
                $this->namespace_prefixes[$namespace_prefix] = $new;
            }
        } else {
            $this->namespace_prefixes[$namespace_prefix] = array($namespace_base_directory);
        }

        return $this;
    }

    /**
     * Get Resource Map
     *
     * @return  array
     * @since   1.0
     */
    public function getMap()
    {
        return $this->resource_map;
    }

    /**
     * Create resource map of folder/file locations and Fully Qualified Namespaces
     *
     * @return  object
     * @since   1.0
     */
    public function createMap()
    {
        $this->getResourceMapTags();

        $this->temp_resource_map = array();
        $this->php_files         = array();
        $this->temp_tag_ns       = array();
        $this->temp_tag          = array();

        foreach ($this->primary_array as $namespace_prefix => $namespace_prefix_object) {

            foreach ($namespace_prefix_object->include_folders as $namespace_base_directory) {

                if (trim($namespace_base_directory) == '') {
                } else {
                    if (is_dir($this->base_path . '/' . $namespace_base_directory)
                        && $namespace_base_directory !== ''
                    ) {

                        $paths   = array();
                        $paths[] = $this->base_path . '/' . $namespace_base_directory;
                        $this->temp_resource_map[strtolower($namespace_prefix)]
                                 = array_unique($paths);

                        $objects = new RecursiveIteratorIterator
                        (new RecursiveDirectoryIterator($this->base_path . '/' . $namespace_base_directory),
                            RecursiveIteratorIterator::SELF_FIRST);
                    } else {

                        if ($namespace_base_directory == '') {
                        } else {
                            echo 'createResourceMap: Not a folder ' . $this->base_path . '/' . $namespace_base_directory . '<br />';
                        }

                        break;
                    }

                    foreach ($objects as $file_path => $file_object) {

                        $file_name      = $file_object->getFileName();
                        $file_extension = $file_object->getExtension();
                        $is_directory   = $file_object->isDir();
                        $php_class      = 0;

                        /** Test Namespace Rules */
                        $skip = $this->testFileForNamespaceRules(
                            $namespace_prefix,
                            $namespace_prefix_object,
                            $namespace_base_directory,
                            $is_directory,
                            $file_path,
                            $file_name,
                            $file_extension,
                            $php_class
                        );

                        if ($skip == 0 && count($this->namespace_rules_tags) > 0) {
                            $this->testPathAgainstTags($file_path);
                        }
                    }
                }
            }
        }

        /** Add Tagged Resource Map Items */
        foreach ($this->primary_array as $namespace_prefix => $namespace_prefix_object) {

            $tags = $namespace_prefix_object->tags;

            if (count($tags) > 0) {

                if (isset($this->temp_tag_ns[$namespace_prefix])) {
                    $folders = $this->temp_tag_ns[$namespace_prefix];
                } else {
                    $folders = array();
                }

                foreach ($folders as $file_path) {

                    $tag_found = false;
                    foreach ($tags as $tag) {
                        if (strpos($file_path, '/' . $tag) == false) {
                        } else {
                            $tag_found = $tag;
                            break;
                        }
                    }

                    if ($tag_found === false) {
                    } else {

                        $pathinfo       = pathinfo($file_path);
                        $file_extension = $pathinfo['extension'];
                        $is_directory   = is_dir($file_path);

                        if ($is_directory === true) {
                            $file_name      = '';
                            $file_extension = '';
                        } else {
                            $file_name = $pathinfo['filename'] . '.' . $pathinfo['extension'];
                        }

                        $php_class = 0;

                        $base_directory = substr($file_path, 0, strpos($file_path, '/' . $tag_found) + 1);

                        $this->testFileForNamespaceRules(
                            $namespace_prefix,
                            $namespace_prefix_object,
                            $base_directory,
                            $is_directory,
                            $file_path,
                            $file_name,
                            $file_extension,
                            $php_class
                        );
                    }
                }
            }
        }

        ksort($this->temp_resource_map);
        ksort($this->php_files);

        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            file_put_contents($this->resource_map_filename, json_encode($this->temp_resource_map, JSON_PRETTY_PRINT));
            file_put_contents($this->interface_map_filename, json_encode($this->php_files, JSON_PRETTY_PRINT));
        } else {
            file_put_contents($this->resource_map_filename, json_encode($this->temp_resource_map));
            file_put_contents($this->interface_map_filename, json_encode($this->php_files));
        }

        $this->resource_map = $this->temp_resource_map;

        return $this->resource_map;
    }

    /**
     * Process all Namespace Prefixes for Resource Map to identify Tags and Tag Namespaces
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    protected function getResourceMapTags()
    {
        $this->namespace_rules_tags          = array();
        $this->namespace_rules_tag_namespace = array();

        foreach ($this->primary_array as $namespace_prefix => $namespace_prefix_object) {

            $temp_tags = $namespace_prefix_object->tags;

            if (count($temp_tags) === 0) {
            } else {

                foreach ($temp_tags as $temp) {
                    if (trim($temp) === '') {
                    } else {
                        $this->namespace_rules_tags[$temp]                      = $namespace_prefix;
                        $this->namespace_rules_tag_namespace[$namespace_prefix] = $temp;
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Test Path for this Namespace Prefix
     *
     * @param   string $namespace_prefix
     * @param   object $namespace_prefix_object
     * @param   string $base_directory
     * @param   int    $is_directory
     * @param   string $file_path
     * @param   string $file_name
     * @param   string $file_extension
     * @param   string $base_name
     * @param   string $php_class
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    protected function testFileForNamespaceRules(
        $namespace_prefix,
        $namespace_prefix_object,
        $base_directory,
        $is_directory,
        $file_path,
        $file_name,
        $file_extension,
        $php_class
    ) {
        $skip = 0;

        if ($is_directory == 1) {
            $pathinfo  = pathinfo($file_path);
            $base_name = $pathinfo['basename'];

        } else {

            if ($file_extension == 'php') {

                $base_name = substr($file_name, 0, strlen($file_name) - strlen($file_extension) - 1);

                if (strpos(strtolower($file_name), 'template.php')) {
                } elseif (strtolower(substr($file_name, 0, 4)) == 'hold') {
                } elseif (strtolower(substr($file_name, 0, 3)) == 'xxx') {
                } elseif (strtolower($base_name) == 'index') {
                    $skip = 1;
                } else {
                    $php_class = 1;
                }

            } else {
                $base_name = $file_name;
            }
        }

        if ($skip == 1) {
            return $skip;
        }

        /** Namespace Rules */
        $exclude_folders = $namespace_prefix_object->exclude_folders;

        $found                   = 0;
        $include_file_extensions = explode(',', $namespace_prefix_object->include_file_extensions);
        foreach ($include_file_extensions as $test) {
            if ($test === '') {
            } else {
                $found = 1;
            }
        }
        if ($found == 1) {
        } else {
            $include_file_extensions = array();
        }

        $found                   = 0;
        $exclude_file_extensions = explode(',', $namespace_prefix_object->exclude_file_extensions);
        foreach ($exclude_file_extensions as $test) {
            if ($test === '') {
            } else {
                $found = 1;
            }
        }
        if ($found == 1) {
        } else {
            $exclude_file_extensions = array();
        }

        $file_path = substr($file_path, strlen($this->base_path . '/'), 9999);

        $skip = $this->processExcludeFolders($file_path, $base_name, $exclude_folders, $skip);
        if ($skip == 1) {
            return $skip;
        }

        if ($is_directory === true) {
            $path = $file_path;
        } else {
            $path = substr($file_path, 0, strlen($file_path) - strlen($file_name) - 1);
        }

        $class_namespace_path = substr($path, strlen($base_directory), 9999);

        if ($class_namespace_path == '') {
            $fqns = $namespace_prefix;
        } else {
            $fqns = $namespace_prefix . '\\' . str_replace('/', '\\', $class_namespace_path);
        }

        $nspath = $path;

        if ($is_directory === true) {
        } else {
            $fqns .= '\\' . $base_name;
            $nspath .= '/' . $file_name;
            $skip = $this->processIncludeExtensions($file_extension, $include_file_extensions, $skip);
            if ($skip == 1) {
                return $skip;
            }
        }

        $skip = $this->processExcludeExtensions($file_extension, $nspath, $fqns, $exclude_file_extensions, $skip);
        if ($skip == 1) {
            return $skip;
        }

        if ($php_class === 1) {
            $temp = new stdClass();

            $temp->file_name = $file_name;
            $temp->base_name = $base_name;
            $temp->path      = $nspath;
            $temp->fqns      = $fqns;

            $this->php_files[$nspath] = $temp;
        }

        $this->mergeFQNSPaths($nspath, $fqns);

        return 0;
    }

    /**
     * Process Exclude Folders Definitions
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    protected function processExcludeFolders($file_path, $base_name, $exclude_folders, $skip = 1)
    {
        if ($skip === 1) {
            return $skip;
        }

        if (count($exclude_folders) === 0) {
            return $skip;
        }

        $skip = 0;

        foreach ($exclude_folders as $exclude) {

            if ($base_name == $exclude) {
                $skip = 1;
                break;
            } elseif (strpos($file_path, '/' . $exclude) == false) {
            } else {
                $skip = 1;
                break;
            }
        }

        return $skip;
    }

    /**
     * Process Require Extension Definitions
     *
     * @param  string $file_extension
     * @param  array  $include_file_extensions
     * @param  int    $skip
     *
     * @return  int
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    protected function processIncludeExtensions($file_extension, $include_file_extensions, $skip)
    {
        if ($skip === 1) {
            return $skip;
        }

        $skip = 0;

        if (count($include_file_extensions) === 0) {
            return $skip;
        }

        $found = 0;
        foreach ($include_file_extensions as $extension) {
            if ($file_extension == '' || $extension == '') {
            } elseif ($file_extension == $extension) {
            } else {
                $found = 1;
                break;
            }
        }

        if ($found == 0) {
            $skip = 1;
        }

        return $skip;
    }

    /**
     * Process Exclude Extension Definitions
     *
     * @param  string $file_extension
     * @param  string $nspath
     * @param  string $fqns
     * @param  array  $exclude_file_extensions
     * @param  int    $skip
     *
     * @return  int
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    protected function processExcludeExtensions($file_extension, $nspath, $fqns, $exclude_file_extensions, $skip = 0)
    {
        if ($skip === 1) {
            return $skip;
        }

        $skip = 0;

        if (count($exclude_file_extensions) === 0) {
            return $skip;
        }

        foreach ($exclude_file_extensions as $exclude_path => $exclude_ns) {

            if ($file_extension == '' || $exclude_ns == '') {
            } elseif (substr($nspath, 0, strlen($exclude_path)) === $exclude_path
                && substr($fqns, 0, strlen($exclude_ns)) === $exclude_ns
            ) {
                $skip = 1;
                break;
            }
        }

        return $skip;
    }

    /**
     * Get Resource Map Tags
     *
     * @param   string $nspath
     * @param   string $fqns
     *
     * @return  int
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    protected function mergeFQNSPaths($nspath, $fqns)
    {
        if ($nspath === '') {
            return $this;
        }

        $paths = array();

        $fqns = strtolower($fqns);

        if (isset($this->temp_resource_map[$fqns])) {

            $existing = $this->temp_resource_map[$fqns];

            if (is_array($existing)) {
                $paths = $existing;
                if (count($paths) == 0) {
                    $paths = array();
                }
            } else {
                $paths = array();
            }
        } else {
            $paths = array();
        }

        $paths[]                        = $this->base_path . '/' . $nspath;
        $this->temp_resource_map[$fqns] = array_unique($paths);

        return $this;
    }

    /**
     * Process Tags for Namespace to see if a folder in this file_path matches
     *
     * @param  string $file_path
     * @param  int    $skip
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    protected function testPathAgainstTags($file_path)
    {
        foreach ($this->namespace_rules_tags as $tag => $ns) {

            if (strpos($file_path . '/', '/' . $tag . '/') === false) {
            } else {

                /** Organized by File path */
                if (isset($this->temp_tag[$file_path])) {

                    $x = $this->temp_tag[$file_path];

                    if (count($x) == 0) {
                        $x = array();
                    }
                } else {
                    $x = array();
                }

                $x[] = $ns;
                sort($x);
                $this->temp_tag[$file_path] = $x;

                /** Organized by Namespace */
                if (isset($this->temp_tag_ns[$ns])) {

                    $x = $this->temp_tag_ns[$ns];

                    if (count($x) == 0) {
                        $x = array();
                    }
                } else {
                    $x = array();
                }

                $x[] = $file_path;
                sort($x);
                $this->temp_tag_ns[$ns] = $x;
            }
        }

        return $this;
    }

    /**
     * Read File
     *
     * @param  string $file_name
     * @param  string $property_name_array
     *
     * @since  1.0
     */
    protected function readFile($file_name, $property_name_array)
    {
        $temp_array = array();

        $file_name = __DIR__ . '/' . $file_name;

        if (file_exists($file_name)) {
        } else {
            return;
        }

        $input = file_get_contents($file_name);
        $temp  = json_decode($input);

        if (count($temp) > 0) {
            $temp_array = array();
            foreach ($temp as $key => $value) {
                $temp_array[$key] = $value;
            }
        }

        $this->$property_name_array = $temp_array;
    }

    /**
     * Verify the correctness of the resource map, returning error messages
     *
     * @return  array
     * @since   1.0
     */
    public function editMap()
    {
        // TODO: Implement editMap() method.
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
}
