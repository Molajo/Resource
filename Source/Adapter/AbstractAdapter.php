<?php
/**
 * Abstract Resource Adapter
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Adapter;

use CommonApi\Resource\AdapterInterface;
use CommonApi\Exception\RuntimeException;
use Exception;

/**
 * Abstract Resource Adapter
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class AbstractAdapter implements AdapterInterface
{
    /**
     * Resource Namespace
     *
     * @var    string
     * @since  1.0.0
     */
    protected $resource_namespace = null;

    /**
     * Base Path - root of the website from which paths are defined
     *
     * @var    string
     * @since  1.0.0
     */
    protected $base_path = null;

    /**
     * Resource Map
     *
     * @var    array
     * @since  1.0.0
     */
    protected $resource_map = array();

    /**
     * Namespace Prefixes + Path
     *
     * @var    array
     * @since  1.0.0
     */
    protected $namespace_prefixes = array();

    /**
     * Namespace Prefixes + Path
     *
     * @var    array
     * @since  1.0.0
     */
    protected $valid_file_extensions = array();

    /**
     * Located for Multiple = true
     *
     * @var    array
     * @since  1.0.0
     */
    protected $located_multiple = array();

    /**
     * Constructor
     *
     * @param  string $base_path
     * @param  array  $resource_map
     * @param  array  $namespace_prefixes
     * @param  array  $valid_file_extensions
     *
     * @since  1.0.0
     */
    public function __construct(
        $base_path = null,
        array $resource_map = array(),
        array $namespace_prefixes = array(),
        array $valid_file_extensions = array()
    ) {
        $this->base_path             = $base_path . '/';
        $this->resource_map          = $resource_map;
        $this->namespace_prefixes    = $namespace_prefixes;
        $this->valid_file_extensions = $valid_file_extensions;
    }

    /**
     * Set a namespace prefix by mapping to the filesystem path
     *
     * @param   string  $namespace_prefix
     * @param   string  $namespace_base_directory
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0.0
     */
    public function setNamespace($namespace_prefix, $namespace_base_directory, $prepend = true)
    {
        if (isset($this->namespace_prefixes[$namespace_prefix])) {
            $this->setNamespaceExists($namespace_prefix, $namespace_base_directory, $prepend);
        } else {
            $this->namespace_prefixes[$namespace_prefix] = array($namespace_base_directory);
        }

        return $this;
    }

    /**
     * Set Namespace for secondary location
     *
     * @param   string  $namespace_prefix
     * @param   string  $namespace_base_directory
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setNamespaceExists($namespace_prefix, $namespace_base_directory, $prepend)
    {
        if ($prepend === false) {
            return $this->appendNamespace($namespace_prefix, $namespace_base_directory);
        }

        return $this->prependNamespace($namespace_prefix, $namespace_base_directory);
    }

    /**
     * Append Namespace for secondary location
     *
     * @param   string  $namespace_prefix
     * @param   string  $namespace_base_directory
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function appendNamespace($namespace_prefix, $namespace_base_directory)
    {
        $hold = $this->namespace_prefixes[$namespace_prefix];

        $hold[]                                      = $namespace_base_directory;
        $this->namespace_prefixes[$namespace_prefix] = $hold;

        return $this;
    }

    /**
     * Append Namespace for secondary location
     *
     * @param   string  $namespace_prefix
     * @param   string  $namespace_base_directory
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function prependNamespace($namespace_prefix, $namespace_base_directory)
    {
        $hold = $this->namespace_prefixes[$namespace_prefix];

        $new   = array();
        $new[] = $namespace_base_directory;
        foreach ($hold as $h) {
            $new[] = $h;
        }

        $this->namespace_prefixes[$namespace_prefix] = $new;

        return $this;
    }

    /**
     * Locates folder/file associated with Namespace for Resource
     *
     * @param   string $resource_namespace
     * @param   bool   $multiple
     *
     * @return  void|mixed|string|array
     * @since   1.0.0
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
            $located_path = $this->searchNamespacePrefixes($resource_namespace, $multiple);
        }

        if ($located_path === false || $multiple === true) {
            $located_path = $this->searchResourceMap(strtolower($resource_namespace), $multiple);
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
     * @param $resource_namespace
     * @param $multiple
     *
     * @return AbstractAdapter
     */
    protected function searchNamespacePrefixes($resource_namespace, $multiple)
    {
        $located_path           = false;

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
     * @since   1.0.0
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

            if (file_exists($file_name) && count($this->valid_file_extensions) == 0) {
                return $file_name;
            }

            // Part 4: Process each Extension valid for this Resource
            foreach ($this->valid_file_extensions as $valid_extension) {

                // Part 5: If exists, match found and return filename
                if (file_exists($file_name . $valid_extension)) {
                    return $file_name . $valid_extension;

                    break;
                }
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
     * @since   1.0.0
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

            if (count($this->valid_file_extensions) > 0) {

                $file_extension = '.' . pathinfo($path, PATHINFO_EXTENSION);

                foreach ($this->valid_file_extensions as $rule_extension) {
                    if ($file_extension == $rule_extension) {
                        if ($multiple === false) {
                            return $path;
                        } else {
                            $this->located_multiple[] = $path;
                        }
                    }
                }
            } else {

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
     * Handle requires located file
     *
     * @param   string $scheme
     * @param   string $located_path
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0.0
     */
    public function handlePath($scheme, $located_path, array $options = array())
    {
        return;
    }

    /**
     * Retrieve a collection of a specific handler
     *
     * @param   string $scheme
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0.0
     */
    public function getCollection($scheme, array $options = array())
    {
        return null;
    }

    /**
     * Verify Options Entry
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0.0
     * @throws  RuntimeException
     */
    protected function verifyNamespace(array $options = array())
    {
        if (isset($options['namespace'])) {
        } else {
            throw new RuntimeException('Resource handlePath options array must have namespace entry.');
        }

        return $this;
    }

    /**
     * Verify File Exists
     *
     * @param   string $located_path
     *
     * @return  $this
     * @since   1.0.0
     * @throws  RuntimeException
     */
    protected function verifyFileExists($located_path)
    {
        if (file_exists($located_path)) {
        } else {
            throw new RuntimeException('Resource located_path not found: ' . $this->resource_namespace);
        }

        return $this;
    }
}
