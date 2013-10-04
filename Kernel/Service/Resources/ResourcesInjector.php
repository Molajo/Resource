<?php
/**
 * Resources Injector
 *
 * @package   Molajo
 * @license   http:/www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Service\Resources;

use Exception;
use Molajo\IoC\Exception\ServiceHandlerException;
use Molajo\IoC\Handler\AbstractInjector;
use Molajo\IoC\Api\ServiceHandlerInterface;

/**
 * Resources Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http:/www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class ResourcesInjector extends AbstractInjector implements ServiceHandlerInterface
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
        $options['service_namespace']        = 'Molajo\\Resources\\Adapter';
        $options['store_instance_indicator'] = true;
        $options['service_name']             = basename(__DIR__);

        parent::__construct($options);

        if (isset($options['new_map']) && $options['new_map'] === true) {
            $this->options['new_map'] = true;
        } else {
            $this->options['new_map'] = true;
        }
    }

    /**
     * Set Dependencies for Service
     *
     * @param   object $reflection
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    public function setDependencies(array $reflection = null)
    {
        if ($reflection === null) {
            $this->reflection = array();
        } else {
            $this->reflection = $reflection;
        }

        $this->options['Resourcemap'] = $this->createResourceMap(BASE_FOLDER, $this->options['new_map']);

        $this->options['Scheme'] = $this->createScheme();

        $handler_instance                        = array();
        $handler_instance['ClassHandler']        = $this->createHandler('ClassHandler');
        $handler_instance['ConstantHandler']     = $this->createHandler('ConstantHandler');
        $handler_instance['FileHandler']         = $this->createHandler('FileHandler');
        $handler_instance['FolderHandler']       = $this->createHandler('FolderHandler');
        $handler_instance['FunctionHandler']     = $this->createHandler('FunctionHandler');
        $handler_instance['InterfaceHandler']    = $this->createHandler('InterfaceHandler');
        $handler_instance['PageviewHandler']     = $this->createHandler('PageviewHandler');
        $handler_instance['TemplateviewHandler'] = $this->createHandler('TemplateviewHandler');
        $handler_instance['ThemeHandler']        = $this->createHandler('ThemeHandler');
        $handler_instance['WrapviewHandler']     = $this->createHandler('WrapviewHandler');
        $handler_instance['XmlHandler']          = $this->createHandler('XmlHandler');

        $this->options['handler_instance_array'] = $handler_instance;

        $this->dependencies = array();

        return $this->dependencies;

        /**
         *  Css, Cssdeclarations, Jsdeclarations, and JsHandler loaded in ApplicationInjector
         *  QueryHandler loaded following DatabaseInjector
         */
    }

    /**
     * Fulfill Dependencies
     *
     * @param   array $dependency_instances (ignored in Service Item Adapter, based in from handler)
     *
     * @return  $this
     * @since   1.0
     */
    public function processFulfilledDependencies(array $dependency_instances = null)
    {
        $this->dependencies['Resourcemap']            = $this->options['Resourcemap'];
        $this->dependencies['Scheme']                 = $this->options['Scheme'];
        $this->dependencies['handler_instance_array'] = $this->options['handler_instance_array'];

        $this->dependency_instances = $this->dependencies;

        return $this;

        /**
         *  Css, Cssdeclarations, Jsdeclarations, and JsHandler loaded in ApplicationInjector
         *  QueryHandler loaded following DatabaseInjector
         */
    }

    /**
     * Follows the completion of the instantiate service method
     *
     * @return  object
     * @since   1.0
     */
    public function performAfterInstantiationLogic()
    {
        $this->service_instance->setNamespace(
            'PasswordLib\\PasswordLib',
            BASE_FOLDER . '/Vendor' . '/Molajo' . '/User/Encrypt/PasswordLib.phar'
        );

        return $this;
    }

    /**
     * Schedule the Next Service
     *
     * @return  object
     * @since   1.0
     */
    public function scheduleNextService()
    {
        echo 'before';
        $this->createInterfaceMap();
echo 'after';
        $this->schedule_service = array();

        $options                             = array();
        $options['service_namespace']        = 'Molajo\\Registry\\Registry';
        $options['store_instance_indicator'] = true;
        $options['service_name']             = 'Registry';
        $this->schedule_service['Registry']  = $options;

        $options                                = array();
        $options['service_namespace']           = 'Molajo\\Fieldhandler\\Adapter';
        $options['store_instance_indicator']    = true;
        $options['service_name']                = 'Fieldhandler';
        $this->schedule_service['Fieldhandler'] = $options;

        $options                                 = array();
        $options['Resources']                    = $this->service_instance;
        $this->schedule_service['Resourcesdata'] = $options;

        $options                                     = array();
        $options['service_namespace']                = 'Molajo\\Controller\\ExceptionHandlingController';
        $options['store_instance_indicator']         = true;
        $options['service_name']                     = 'Exceptionhandling';
        $this->schedule_service['Exceptionhandling'] = $options;

        return $this->schedule_service;
    }

    /**
     * Create Scheme Instance
     *
     * @return  object
     * @since   1.0
     * @throws  ServiceHandlerException
     */
    protected function createScheme()
    {
        $class = 'Molajo\\Resources\\Scheme';
        try {
            $scheme = new $class ();

        } catch (Exception $e) {
            throw new ServiceHandlerException ('Resources Scheme ' . $class
            . ' Exception during Instantiation: ' . $e->getMessage());
        }

        return $scheme;
    }

    /**
     * Create Handler Instance
     *
     * @param   $handler
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    protected function createHandler($handler)
    {
        $class = 'Molajo\\Resources\\Handler\\' . $handler;
        try {
            $handler_instance = new $class ();

        } catch (Exception $e) {
            throw new ServiceHandlerException ('Resources Handler ' . $handler
            . ' Exception during Instantiation: ' . $e->getMessage());
        }

        return $handler_instance;
    }

    /**
     * Create Resource Map
     *
     * @param   string $base_path
     * @param   bool   $new_map
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    protected function createResourceMap($base_path, $new_map = true)
    {
        $class = 'Molajo\\Resources\\ResourceMap';
        try {
            $map_instance = new $class (
                $new_map = true,
                $base_path,
                $primary_array_filename = 'Files/PrimaryArray.json',
                $sort_array_filename = 'Files/SortArray.json',
                $resource_map_filename = 'Files/ResourceMap.json',
                $interface_map_filename = 'Files/InterfaceMap.json'
            );

        } catch (Exception $e) {
            throw new ServiceHandlerException ('Resources Handler ' . $class
            . ' Exception during Instantiation: ' . $e->getMessage());
        }

        return $map_instance;
    }

    /**
     * Create Interface Map
     *
     * @param   string $base_path
     * @param   bool   $new_map
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    protected function createInterfaceMap()
    {
        $class = 'Molajo\\Resources\\InterfaceMap';
        try {
            $map_instance = new $class ('Files/InterfaceMap.json');

        } catch (Exception $e) {
            throw new ServiceHandlerException ('Interface Map ' . $class
            . ' Exception during Instantiation: ' . $e->getMessage());
        }

        $map_instance->createMap();

        return $this;
    }
}
