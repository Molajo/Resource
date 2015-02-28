<?php
/**
 * Handle Namespace Prefixes
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Adapter;

/**
 * Handle Namespace Prefixes
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
abstract class HandleNamespacePrefixes extends HandleResourceMap
{
    /**
     * Search Namespace Prefixes
     *
     * @return  string
     * @since   1.0.0
     */
    protected function searchNamespacePrefixes()
    {
        $located_path = '';

        foreach ($this->namespace_prefixes as $namespace_prefix => $base_directories) {

            $namespace_prefix = htmlspecialchars(strtolower($namespace_prefix));

            if (stripos(strtolower($this->resource_namespace), $namespace_prefix) === false) {
            } else {

                $located_path = $this->searchNamespacePrefix($namespace_prefix, $base_directories);

                if ($located_path === '') {
                } else {
                    break;
                }
            }
        }

        return $located_path;
    }

    /**
     * Set a namespace prefix by mapping to the filesystem path
     *
     * @param   string $namespace_prefix
     * @param   array  $base_directories
     *
     * @return  string
     * @since   1.0.0
     */
    protected function searchNamespacePrefix($namespace_prefix, array $base_directories = array())
    {
        foreach ($base_directories as $namespace_base_directory) {

            $results = $this->searchNamespacePrefixDirectory($namespace_prefix, $namespace_base_directory);

            if ($results === '') {
            } else {
                return $results;
            }
        }

        return '';
    }

    /**
     * Set a namespace prefix by mapping to the filesystem path
     *
     * @param   string $namespace_prefix
     * @param   string $namespace_base_directory
     *
     * @return  string
     * @since   1.0.0
     */
    protected function searchNamespacePrefixDirectory($namespace_prefix, $namespace_base_directory)
    {
        $base           = $namespace_base_directory;
        $namespace_path = $this->searchNamespacePrepareNamespacePath($namespace_prefix);
        $file_name      = $this->base_path . $base . str_replace('\\', '/', $namespace_path);

        $results = $this->searchNamespaceFilename($file_name);
        if ($results === '') {
        } else {
            return $results;
        }

        return $this->searchNamespacePrefixFileExtensions($file_name);
    }

    /**
     * Remove Namespace Prefix from Resource Namespace
     *
     * @param   string $namespace_prefix
     *
     * @return  string
     * @since   1.0.0
     */
    protected function searchNamespacePrepareNamespacePath($namespace_prefix)
    {
        if (trim(substr($this->resource_namespace, strlen($namespace_prefix), 999)) === '') {
            $namespace_path = '';
        } else {
            $namespace_path = substr($this->resource_namespace, strlen($namespace_prefix), 999);
        }

        return $namespace_path;
    }

    /**
     * Assemble include path for filename, returning matches
     *
     * @param   string $file_name
     *
     * @return  string
     * @since   1.0.0
     */
    protected function searchNamespaceFilename($file_name)
    {
        if ((file_exists($file_name) || is_dir($file_name))
            && count($this->valid_file_extensions) === 0) {
            return $file_name;
        }

        return '';
    }

    /**
     * Search for file for scheme with valid file extensions
     *
     * @param   string $file_name
     *
     * @return  string
     * @since   1.0.0
     */
    protected function searchNamespacePrefixFileExtensions($file_name)
    {
        if (count($this->valid_file_extensions) === 0) {
            return '';
        }

        foreach ($this->valid_file_extensions as $valid_extension) {

            if (file_exists($file_name . $valid_extension)) {
                return $file_name . $valid_extension;
            }
        }

        return '';
    }
}
