<?php
/**
 * Namespace Prefixes for Resource Map
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\ResourceMap;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use stdClass;

/**
 * Namespace Prefixes for Resource Map
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
abstract class Prefixes extends Folders
{
    /**
     * Folders
     *
     * @var    array
     * @since  1.0.0
     */
    protected $folders = array();

    /**
     * Files
     *
     * @var    array
     * @since  1.0.0
     */
    protected $files = array();

    /**
     * Process Array of Namespaces/Folder Mapping Pairs
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function processNamespacePrefixes()
    {
        foreach ($this->namespace_prefixes as $namespace_prefix => $namespace_base_folders) {
            $this->processNamespaceFolders($namespace_base_folders, $namespace_prefix);
        }

        return $this;
    }

    /**
     * Process Folders for Namespace
     *
     * @param   array  $namespace_base_folders
     * @param   string $namespace_prefix
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function processNamespaceFolders($namespace_base_folders, $namespace_prefix)
    {
        foreach ($namespace_base_folders as $namespace_base_folder) {

            $this->php_class = 0;

            if (trim($namespace_base_folder) === '') {

            } elseif (is_dir($this->base_path . '/' . $namespace_base_folder)) {

                $this->processNamespaceFolder($namespace_base_folder, $namespace_prefix);
            }
        }

        return $this;
    }

    /**
     * Process Single Directory
     *
     * @param   string $namespace_base_folder
     * @param   string $namespace_prefix
     *
     * @return  object
     * @since   1.0.0
     */
    protected function processNamespaceFolder($namespace_base_folder, $namespace_prefix)
    {
        $paths                                             = array();
        $paths[]                                           = $this->base_path . '/' . $namespace_base_folder;
        $this->resource_map[strtolower($namespace_prefix)] = array_unique($paths);

        $objects = new RecursiveIteratorIterator
        (
            new RecursiveDirectoryIterator($this->base_path . '/' . $namespace_base_folder),
            RecursiveIteratorIterator::SELF_FIRST
        );

        $this->processFilePathObjects($objects, $namespace_prefix, $namespace_base_folder);

        return $this;
    }

    /**
     * Process File Objects
     *
     * @param   object $objects
     * @param   string $namespace_prefix
     * @param   string $namespace_base_folder
     *
     * @return  object
     * @since   1.0.0
     */
    protected function processFilePathObjects($objects, $namespace_prefix, $namespace_base_folder)
    {
        foreach ($objects as $file_path => $file_object) {

            $file_name      = $file_object->getFileName();
            $file_extension = $file_object->getExtension();
            $is_directory   = $file_object->isDir();

            $this->testFileForNamespaceRules(
                $namespace_prefix,
                $namespace_base_folder,
                $is_directory,
                $file_path,
                $file_name,
                $file_extension
            );
        }

        return $this;
    }

    /**
     * Test Path for this Namespace Prefix
     *
     * @param   string $namespace_prefix
     * @param   string $base_directory
     * @param   int    $is_directory
     * @param   string $file_path
     * @param   string $file_name
     * @param   string $file_extension
     *
     * @return  int|object
     * @since   1.0.0
     */
    protected function testFileForNamespaceRules(
        $namespace_prefix,
        $base_directory,
        $is_directory,
        $file_path,
        $file_name,
        $file_extension
    ) {
        $this->setBase($is_directory, $file_path, $file_name, $file_extension);

        $skip = $this->setFileInclusion($is_directory, $file_name, $file_extension);
        if ($skip === 1) {
            return $this;
        }

        $file_path = substr($file_path, strlen($this->base_path . '/'), 9999);

        $skip = $this->testExcludeFolders($file_path, $this->base_name, $skip);
        if ($skip === 1) {
            return $this;
        }

        $path                 = $this->setPath($is_directory, $file_path, $file_name);
        $class_namespace_path = substr($path, strlen($base_directory), 9999);
        $qns                  = $this->setQNS($class_namespace_path, $namespace_prefix);
        $nspath               = $path;

        if ($is_directory === true) {
        } else {
            list($qns, $nspath) = $this->setClassfileArrayEntry($file_name, $file_extension, $qns, $nspath);
        }

        if ($qns === false) {
        } else {
            $this->mergeFQNSPaths($nspath, $qns);
        }

        return $this;
    }

    /**
     * Set base for file or folder
     *
     * @param   int    $is_directory
     * @param   string $file_path
     * @param   string $file_name
     * @param   string $file_extension
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setBase($is_directory, $file_path, $file_name, $file_extension)
    {
        $this->base_name = $file_name;

        if ($is_directory === 1) {
            $pathinfo        = pathinfo($file_path);
            $this->base_name = $pathinfo['basename'];

        } elseif ($file_extension === 'php') {
            $this->base_name = substr($file_name, 0, strlen($file_name) - strlen($file_extension) - 1);
        }

        return $this;
    }

    /**
     * Test to determine if the file or folder should be used
     *
     * @param   int    $is_directory
     * @param   string $file_name
     * @param   string $file_extension
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function setFileInclusion($is_directory, $file_name, $file_extension)
    {
        $skip = 0;

        if ($is_directory === 1) {
            return $skip;
        }

        if ($file_extension === 'php') {

            if ($this->testPHPClassExceptions($file_name) === false) {
                $skip = 1;
            }
        }

        return $skip;
    }

    /**
     * Set PHP Class
     *
     * @param   string $file_name
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function testPHPClassExceptions($file_name)
    {
        if (strtolower(substr($file_name, 0, 4)) === 'hold') {
        } elseif (strtolower(substr($file_name, 0, 3)) === 'xxx') {
        } elseif (strtolower($this->base_name) === 'index') {
        } else {
            return true;
        }

        return false;
    }

    /**
     * Test Exclude Folders Definitions
     *
     * @param   string $file_path
     * @param   int    $skip
     *
     * @return  int
     * @since   1.0.0
     */
    protected function testExcludeFolders($file_path, $skip = 1)
    {
        if ($skip === 1) {
            return $skip;
        }

        if (substr($this->base_name, 0, 1) === '.') {
            return 1;
        }

        if (count($this->exclude_folders) === 0) {
            return $skip;
        }

        $skip = 0;

        foreach ($this->exclude_folders as $exclude) {

            if ($this->base_name === $exclude) {
                $skip = 1;
                break;
            } elseif (strpos($file_path, '/' . $exclude) === false) {
            } else {
                $skip = 1;
                break;
            }
        }

        return $skip;
    }

    /**
     * Set Path
     *
     * @param   int    $is_directory
     * @param   string $file_path
     * @param   string $file_name
     *
     * @return  string
     * @since   1.0.0
     */
    protected function setPath($is_directory, $file_path, $file_name)
    {
        if ($is_directory === true) {
            return $file_path;
        }

        return substr($file_path, 0, strlen($file_path) - strlen($file_name) - 1);
    }

    /**
     * Set Qualified Namespace
     *
     * @param   string $class_namespace_path
     * @param   string $namespace_prefix
     *
     * @return  string
     * @since   1.0.0
     */
    protected function setQNS($class_namespace_path, $namespace_prefix)
    {
        if ($class_namespace_path === '') {
            return $namespace_prefix;
        }

        $namespace_prefix = $this->addSlash($namespace_prefix);

        return $namespace_prefix . str_replace('/', '\\', $class_namespace_path);
    }

    /**
     * Get Resource Map Tags
     *
     * @param   string $nspath
     * @param   string $qns
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function mergeFQNSPaths($nspath, $qns)
    {
        if ($nspath === '') {
            return $this;
        }

        $qns = strtolower($qns);

        if (isset($this->resource_map[$qns])) {
            $paths = $this->mergeExistingFQNSPath($qns);
        } else {
            $paths = array();
        }

        $paths[] = $this->base_path . '/' . $nspath;

        $this->resource_map[$qns] = array_unique($paths);

        return $this;
    }

    /**
     * Merge Existing FQNS Path
     *
     * @param   string $nspath
     * @param   string $qns
     *
     * @return  array
     * @since   1.0.0
     */
    protected function mergeExistingFQNSPath($qns)
    {
        $paths    = array();
        $existing = $this->resource_map[$qns];

        if (is_array($existing)) {
            $paths = $existing;
            if (count($paths) === 0) {
                $paths = array();
                return $paths;
            }
        }
        return $paths;
    }

    /**
     * Set Class File Array Entry
     *
     * @param   string $file_name
     * @param   string $file_extension
     * @param   string $nspath
     *
     * @return  array
     * @since   1.0.0
     */
    protected function setClassfileArrayEntry($file_name, $file_extension, $qns, $nspath)
    {
        $qns    = $this->addSlash($qns);
        $qns    = $qns . $this->base_name;
        $nspath = $nspath . '/' . $file_name;

        if ($file_extension === 'php') {
            $this->class_files[$nspath] = $this->setNamespaceObject($file_name, $nspath, $qns);
        }

        return array($qns, $nspath);
    }

    /**
     * Set Namespace Object
     *
     * @param   string $file_name
     * @param   string $nspath
     * @param   string $qns
     *
     * @return  object
     * @since   1.0.0
     */
    protected function setNamespaceObject($file_name, $nspath, $qns)
    {
        $temp            = new stdClass();
        $temp->file_name = $file_name;
        $temp->base_name = $this->base_name;
        $temp->path      = $nspath;
        $temp->qns       = $qns;

        return $temp;
    }
}
