<?php
/**
 * Locator Resource Map Injector
 *
 * @package   Molajo
 * @license   http:/www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Service\LocatorResourceMap;

use Molajo\IoC\Exception\ServiceItemException;
use Molajo\IoC\Handler\CustomInjector;
use Molajo\IoC\Api\ServiceItemInterface;

/**
 * Locator Resource Map Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http:/www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class LocatorResourceMapInjector extends CustomInjector implements ServiceItemInterface
{
    /**
     * Constructor
     *
     * @param  $options
     *
     * @since  1.0
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->service                  = basename(__DIR__);
        $this->service_namespace        = 'Molajo\\Locator\\Utilities\\ResourceMap';
        $this->store_instance_indicator = true;
    }

    /**
     * Identify Class Dependencies for Constructor Injection
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceItemException
     */
    public function getDependencies()
    {
        $this->dependencies = array();

        return $this->dependencies;
    }

    /**
     * Set Dependency Values
     *
     * @param   array $dependency_instances
     *
     * @return  $this|object
     * @since   1.0
     */
    public function setDependencies(array $dependency_instances = array())
    {
        $this->dependencies['namespace_prefixes']     = $this->options['namespace_prefixes'];
        $this->dependencies['base_path']              = $this->options['base_path'];
        $this->dependencies['rebuild_map']            = $this->options['rebuild_map'];
        $this->dependencies['resource_map_filename']  = $this->options['resource_map_filename'];
        $this->dependencies['exclude_in_path_array']  = $this->options['exclude_in_path_array'];
        $this->dependencies['exclude_path_array']     = $this->options['exclude_path_array'];
        $this->dependencies['valid_extensions_array'] = $this->options['valid_extensions_array'];

        parent::setDependencies();
    }
}
