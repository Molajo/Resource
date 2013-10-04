<?php
/**
 * Service Item Interface - handles interaction between IoC and DI Handler to resolve dependencies and create class
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\IoC\Api;

use Molajo\IoC\Exception\ServiceItemException;

/**
 * Service Item Interface - handles interaction between IoC and DI Handler to resolve dependencies and create class
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface ServiceItemInterface
{
    /**
     * IoC Controller requests Service Name from DI Handler
     *
     * @return  string
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceItemException
     */
    public function getServiceName();

    /**
     * IoC Controller requests Service Namespace from DI Handler
     *
     * @return  string
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceItemException
     */
    public function getServiceNamespace();

    /**
     * IoC Controller requests Service Options from DI Handler
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceItemException
     */
    public function getServiceOptions();

    /**
     * IoC Controller retrieves "store instance indicator" from DI Handler
     *
     * @return  string
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceItemException
     */
    public function getStoreInstanceIndicator();

    /**
     * IoC Controller provides reflection values which the DI Handler can use to set Dependencies
     *  Dependencies are returned to the IoC Controller.
     *
     * @param   array $reflection
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceItemException
     */
    public function setDependencies(array $reflection = null);

    /**
     * IoC Controller removes Dependency (Either itself or for if_exists)
     *
     * @param   string $dependency
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceItemException
     */
    public function removeDependency($dependency);

    /**
     * IoC Controller provides an Instance for Dependency, not sent to the
     *  DI Handler until all Dependencies in place. At that time, the IoC Controller
     *  uses processFulfilledDependencies to send satisfied Dependencies to the DI Handler
     *
     * @param   string $dependency
     * @param   object $dependency_instance
     *
     * @return  $this
     * @since   1.0
     */
    public function setDependencyInstance($dependency, $dependency_instance);

    /**
     * IoC Controller requests count of Dependencies not yet satisfied
     *
     * @return  int
     * @since   1.0
     */
    public function getRemainingDependencyCount();

    /**
     * IoC Controller shares Dependency Instances with DI Handler for final processing before creating class
     *
     * @return  $this
     * @since   1.0
     */
    public function processFulfilledDependencies();

    /**
     * IoC Controller triggers the DI Handler to Create the Class for the Service
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceItemException
     */
    public function instantiateService();

    /**
     * IoC Controller triggers the DI Handler to execute logic that follows class instantiation,
     *  This is an ideal place to add Setter Dependencies or any other actions that must follow
     *   creating the Class
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceItemException
     */
    public function performAfterInstantiationLogic();

    /**
     * IoC Controller requests Service Instance from DI Handler
     *
     * @return  object
     * @since   1.0
     * @throws  ServiceItemException
     */
    public function getServiceInstance();

    /**
     * IoC Controller requests any other Services that the DI Handler wants to save in Container
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceItemException
     */
    public function setService();

    /**
     * IoC Controller requests any Services that the DI Handler wants removed from Container
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceItemException
     */
    public function removeService();

    /**
     * IoC Controller requests any Services that the DI Handler wants scheduled now that this Service
     *    has been created
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceItemException
     */
    public function scheduleNextService();
}
