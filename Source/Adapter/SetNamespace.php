<?php
/**
 * Set Namespace Methods
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Adapter;

/**
 * Set Namespace Methods
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
abstract class SetNamespace extends HandleNamespacePrefixes
{
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
     * @param   string $namespace_prefix
     * @param   string $namespace_base_directory
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
     * Prepend Namespace for secondary location
     *
     * @param   string $namespace_prefix
     * @param   string $namespace_base_directory
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
}
