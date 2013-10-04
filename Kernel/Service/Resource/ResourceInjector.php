<?php
/**
 * Resource Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Service\Resource;

use Molajo\IoC\Handler\AbstractInjector;
use Molajo\IoC\Api\ServiceHandlerInterface;

/**
 * Resource Service Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class ResourceInjector extends AbstractInjector implements ServiceHandlerInterface
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
        $options['service_name'] = basename(__DIR__);
        parent::__construct($options);
    }

    /**
     * Define Dependencies for the Service
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    public function setDependencies(array $reflection = null)
    {
        parent::setDependencies($reflection);

        $options = array();

        $this->dependencies                        = array();
        $this->dependencies['Resources']           = $options;
        $this->dependencies['Parameters']          = $options;
        $this->dependencies['Renderingextensions'] = $options;

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

        $model = 'Molajo//Datasource//'
            . $this->dependencies['Parameters']->route->model_name
            . '.xml';

        $this->dependencies['resource_query']
            = $this->dependencies['Resources']->get(
            'query:///' . $model,
            array('Parameters', $this->dependencies['Parameters']));

        $this->dependencies['rendering_extensions'] = $this->dependencies['Renderingextensions'];

        return $this->dependencies;
    }

    /**
     * IoC Controller triggers the DI Handler to create the Class for the Service
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    public function instantiateService()
    {
        $class_name = 'Molajo\\Controller\\ResourceController';

        $this->service_instance = new $class_name
        (
            $this->dependencies['Resources'],
            $this->dependencies['Parameters'],
            $this->dependencies['resource_query'],
            $this->dependencies['Renderingextensions']
        );

        return $this;
    }

}
