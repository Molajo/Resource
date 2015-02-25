<?php
/**
 * Resource Map
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource;

use CommonApi\Resource\MapInterface;
use Molajo\Resource\ResourceMap\Prefixes;

/**
 * Resource Map
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class ResourceMap extends Prefixes implements MapInterface
{
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
    public function setNamespace($namespace_prefix, $namespace_base_directory, $prepend = false)
    {
        $namespace_prefix = $this->addSlash($namespace_prefix);

        if (isset($this->namespace_prefixes[$namespace_prefix])) {
            $this->setMultipleNamespaceFolder($namespace_prefix, $namespace_base_directory, $prepend);
        } else {
            $this->namespace_prefixes[$namespace_prefix] = array($namespace_base_directory);
        }

        return $this;
    }

    /**
     * Create resource map of folder/file locations and Qualified Namespaces
     *
     * @return  $this
     * @since   1.0.0
     */
    public function createMap()
    {
        $this->resource_map = array();
        $this->class_files  = array();

        $this->processNamespacePrefixes();
        $this->saveOutput();

        return $this;
    }

    /**
     * Save output to json file
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function saveOutput()
    {
        ksort($this->resource_map);
        ksort($this->class_files);

        file_put_contents($this->resource_map_filename, json_encode($this->resource_map, JSON_PRETTY_PRINT));
        file_put_contents($this->classmap_filename, json_encode($this->class_files, JSON_PRETTY_PRINT));

        return $this;
    }

    /**
     * Return Resource Map Data
     *
     * @return  object
     * @since   1.0.0
     */
    public function getResourceMap()
    {
        return $this->resource_map;
    }
}
