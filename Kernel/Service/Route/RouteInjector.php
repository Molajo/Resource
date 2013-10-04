<?php
/**
 * Route Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Service\Route;

use stdClass;
use Exception;
use Molajo\IoC\Handler\AbstractInjector;
use Molajo\IoC\Api\ServiceHandlerInterface;
use Molajo\IoC\Exception\ServiceHandlerException;

/**
 * Route Service Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class RouteInjector extends AbstractInjector implements ServiceHandlerInterface
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
        $options['service_name']      = basename(__DIR__);
        $options['service_namespace'] = null;

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
        $options = array();

        $this->dependencies               = array();
        $this->dependencies['Resources']  = $options;
        $this->dependencies['Request']    = $options;
        $this->dependencies['Parameters'] = $options;

        return $this->dependencies;
    }

    /**
     * Set Dependency values
     *
     * @param   array $dependency_instances (ignored in Service Item Adapter, based in from handler)
     *
     * @return  $this
     * @since   1.0
     */
    public function processFulfilledDependencies(array $dependency_instances = null)
    {
        parent::processFulfilledDependencies($dependency_instances);

        $this->dependencies['Filters'] = $this->getApplicationFilters();

        return $this;
    }

    /**
     * Instantiate Class
     *
     * @return  $this
     * @since   1.0
     * @throws  ServiceHandlerException
     */
    public function instantiateService()
    {
        $handler = $this->getAdapterHandler();

        $this->service_instance = $this->getAdapter($handler);

        return $this;
    }

    /**
     * Get the Route Adapter Handler
     *
     * @param   string $adapter_handler
     *
     * @return  object
     * @since   1.0
     * @throws  ServiceHandlerInterface
     */
    protected function getAdapterHandler()
    {
        $route              = new stdClass();
        $route->route_found = null;

        if ((int)$this->options['override_route_catalog_id'] === 0) {
            $route->catalog_id = 0;
        } else {
            $route->catalog_id = (int)$this->options['override_route_catalog_id'];
        }

        if (trim($this->options['override_route_path']) === '') {
            $route->path = '';
        } else {
            $route->path = trim($this->options['override_route_path']);
        }

        if ((int)$this->options['override_route_source_id'] === 0) {
            $route->source_id = 0;
        } else {
            $route->source_id = (int)$this->options['override_route_source_id'];
        }

        if ((int)$this->options['override_route_catalog_type_id'] === 0) {
            $route->catalog_type_id = 0;
        } else {
            $route->catalog_type_id = (int)$this->options['override_route_catalog_type_id'];
        }

        $this->dependencies['Parameters']->route = $route;

        $query = $this->dependencies['Resources']->get(
            'query:///Molajo//Datasource//Catalog.xml',
            array('parameters' => $this->dependencies['Parameters'])
        );

        $class = 'Molajo\\Route\\Handler\\Database';

        try {
            return new $class(
                $this->dependencies['Request'],
                $this->dependencies['Parameters'],
                $this->dependencies['Filters'],
                $query
            );

        } catch (Exception $e) {
            throw new ServiceHandlerException
            ('Route: Could not instantiate Handler: ' . $class);
        }
    }

    /**
     * Get Filesystem Adapter, inject with specific Filesystem Adapter Handler
     *
     * @param   object $handler
     *
     * @return  object
     * @since   1.0
     * @throws  ServiceHandlerInterface
     */
    protected function getAdapter($handler)
    {
        $class = 'Molajo\\Route\\Adapter';

        try {
            return new $class($handler);

        } catch (Exception $e) {

            throw new ServiceHandlerException
            ('Route: Could not instantiate Adapter');
        }
    }

    /**
     * Set Application Filters (For URLs)
     *
     * @return  array
     * @since   1.0
     * @throws  ServiceHandlerException
     */
    public function getApplicationFilters()
    {
        $f = $this->dependencies['Resources']->get('xml:///Molajo//Application//Filters.xml');

        $filters = array();
        foreach ($f->filter as $t) {
            $filters[] = (string)$t['name'];
        }

        return $filters;
    }
}
