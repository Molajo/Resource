<?php
/**
 * Resource Namespace Mapping
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\ResourceMap;

/**
 * Resource Namespace
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
abstract class Folders extends Base
{
    /**
     * Base Name
     *
     * @var    string
     * @since  1.0.0
     */
    protected $base_name = '';

    /**
     * PHP Class Indicator
     *
     * @var    boolean
     * @since  1.0.0
     */
    protected $php_class = false;

    /**
     * Temporary Work File to accumulate Resource Map
     *
     * @var    array
     * @since  1.0.0
     */
    protected $resource_map = array();

    /**
     * Temporary Work File to accumulate Class Files
     *
     * @var    array
     * @since  1.0.0
     */
    protected $class_files = array();

    /**
     * Add another Namespace Folder
     *
     * @param   string  $namespace_prefix
     * @param   string  $namespace_base_directory
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setMultipleNamespaceFolder($namespace_prefix, $namespace_base_directory, $prepend)
    {
        $hold = $this->namespace_prefixes[$namespace_prefix];

        if ($prepend === false) {
            $this->appendNamespaceFolder($namespace_prefix, $namespace_base_directory, $hold);
        }

        $this->prependNamespaceFolder($namespace_prefix, $namespace_base_directory, $hold);

        return $this;
    }

    /**
     * Append Namespace Folder
     *
     * @param   string $namespace_prefix
     * @param   string $namespace_base_directory
     * @param   array  $hold
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function appendNamespaceFolder($namespace_prefix, $namespace_base_directory, $hold)
    {
        $hold[]                                      = $namespace_base_directory;
        $this->namespace_prefixes[$namespace_prefix] = $hold;

        return $this;
    }

    /**
     * Prepend Namespace Folder
     *
     * @param   string $namespace_prefix
     * @param   string $namespace_base_directory
     * @param   array  $hold
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function prependNamespaceFolder($namespace_prefix, $namespace_base_directory, $hold)
    {
        $new = array();

        $new[] = $namespace_base_directory;

        foreach ($hold as $h) {
            $new[] = $h;
        }

        $this->namespace_prefixes[$namespace_prefix] = $new;
    }
}
