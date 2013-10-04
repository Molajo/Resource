<?php
/**
 * Resourcesquery Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Service\Resourcesquery;

use Molajo\IoC\Handler\AbstractInjector;
use Molajo\IoC\Api\ServiceHandlerInterface;
use Molajo\IoC\Exception\ServiceHandlerException;

/**
 * Resourcesquery Service Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class ResourcesqueryInjector extends AbstractInjector implements ServiceHandlerInterface
{
    /**
     * Constructor
     *
     * @param  array $options
     *
     * @since  1.0
     */
    public function __construct(array $options = array())
    {
        $options['service_namespace'] = 'Molajo\\Resources\\Handler\\QueryHandler';
        $options['service_name']      = basename(__DIR__);

        parent::__construct($options);

        $this->options['resources_array'] = $options['resources_array'];
    }

    /**
     * Instantiate a new handler and inject it into the Adapter for the ServiceHandlerInterface
     * Retrieve a list of Interface dependencies and return the data ot the controller.
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    public function setDependencies(array $reflection = null)
    {
        parent::setDependencies($reflection);

        $this->dependencies['Resources'] = array();

        return $this->dependencies;
    }

    /**
     * Set Dependencies for Instantiation
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    public function processFulfilledDependencies(array $dependency_instances = null)
    {
        parent::processFulfilledDependencies($dependency_instances);

        $this->dependencies['query']        = $this->dependencies['Database']->getQueryObject();
        $this->dependencies['null_date']    = $this->dependencies['Database']->getNullDate();
        $this->dependencies['current_date'] = $this->dependencies['Database']->getDate();

        $resources = $this->options['resources_array'];
        if (count($resources) > 0) {
            foreach ($resources as $key => $value) {
                $this->dependencies[$key] = $value;
                unset($this->options[$key]);
            }
        }
        $this->dependencies['resources_array'] = $resources;

        return $this->dependencies;
    }

    /**
     * Follows the completion of the instantiate service method
     *
     * @return  $this
     * @since   1.0
     */
    public function performAfterInstantiationLogic()
    {
        $this->dependencies['Resources']->setHandlerInstance('QueryHandler', $this->service_instance);

        return $this;
    }
}
