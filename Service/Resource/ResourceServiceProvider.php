<?php
/**
 * Resource Service Provider
 *
 * @package    Molajo
 * @license    http:/www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Service\Resource;

use Exception;
use CommonApi\Exception\RuntimeException;
use Molajo\IoC\AbstractServiceProvider;
use CommonApi\IoC\ServiceProviderInterface;

/**
 * Resource Service Provider
 *
 * @author     Amy Stephen
 * @license    http:/www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class ResourceServiceProvider extends AbstractServiceProvider implements ServiceProviderInterface
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
        $options['service_namespace']        = 'Molajo\\Resource\\Adapter';
        $options['store_instance_indicator'] = true;
        $options['service_name']             = basename(__DIR__);

        parent::__construct($options);
    }

    /**
     * Set Dependencies for Service
     *
     * @param   array $reflection
     *
     * @return  array|bool
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException;
     */
    public function setDependencies(array $reflection = null)
    {
        if ($reflection === null) {
            $this->reflection = array();
        } else {
            $this->reflection = $reflection;
        }

        $this->options['Scheme'] = $this->createScheme();

        $handler_instance = array();

        $resource_map = $this->readFile(BASE_FOLDER . '/vendor/molajo/resource/Source/Files/Output/ResourceMap.json');

        /**
         * NOTE:
         *  Css, Cssdeclarations, Jsdeclarations, and JsHandler loaded in Application Service Provider
         *  QueryHandler loaded following Database Service Provider
         */
        $handler_instance['AssetHandler']
            = $this->createHandler(
            'AssetHandler',
            BASE_FOLDER,
            $resource_map,
            array(),
            $this->options['Scheme']->getScheme('Asset')->include_file_extensions
        );
        $handler_instance['ClassHandler']
            = $this->createHandler(
            'ClassHandler',
            BASE_FOLDER,
            $resource_map,
            array(),
            $this->options['Scheme']->getScheme('Class')->include_file_extensions
        );
        $handler_instance['FileHandler']
            = $this->createHandler(
            'FileHandler',
            BASE_FOLDER,
            $resource_map,
            array(),
            $this->options['Scheme']->getScheme('File')->include_file_extensions
        );
        $handler_instance['FolderHandler']
            = $this->createHandler(
            'FolderHandler',
            BASE_FOLDER,
            $resource_map,
            array(),
            $this->options['Scheme']->getScheme('Folder')->include_file_extensions
        );
        $handler_instance['HeadHandler']
            = $this->createHandler(
            'HeadHandler',
            BASE_FOLDER,
            $resource_map,
            array(),
            $this->options['Scheme']->getScheme('Head')->include_file_extensions
        );
        $handler_instance['XmlHandler']
            = $this->createHandler(
            'XmlHandler',
            BASE_FOLDER,
            $resource_map,
            array(),
            $this->options['Scheme']->getScheme('Xml')->include_file_extensions
        );

        $this->options['handler_instance_array'] = $handler_instance;

        $this->dependencies = array();

        return $this->dependencies;
    }

    /**
     * Fulfill Dependencies
     *
     * @param   array $dependency_instances (ignored in Service Item Adapter, based in from handler)
     *
     * @return  $this
     * @since   1.0
     */
    public function onBeforeInstantiation(array $dependency_instances = null)
    {
        $this->dependencies['Scheme']                 = $this->options['Scheme'];
        $this->dependencies['handler_instance_array'] = $this->options['handler_instance_array'];

        return $this->dependencies;
    }

    /**
     * Service Provider Controller triggers the Service Provider to create the Class for the Service
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException;
     */
    public function instantiateService()
    {
        $class = 'Molajo\\Resource\\Adapter';

        $this->service_instance = new $class(
            $this->dependencies['Scheme'],
            $this->dependencies['handler_instance_array']
        );

        return $this;
    }

    /**
     * Follows the completion of the instantiate service method
     *
     * @return  object
     * @since   1.0
     */
    public function onAfterInstantiation()
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
    public function scheduleServices()
    {
        $this->schedule_service = array();

        $options                             = array();
        $options['service_namespace']        = 'Molajo\\Resource\\Configuration\\Registry';
        $options['store_instance_indicator'] = true;
        $options['service_name']             = 'Registry';
        $this->schedule_service['Registry']  = $options;

        $options                                = array();
        $options['service_namespace']           = 'Molajo\\Fieldhandler\\Adapter';
        $options['store_instance_indicator']    = true;
        $options['service_name']                = 'Fieldhandler';
        $this->schedule_service['Fieldhandler'] = $options;

        $options                                 = array();
        $options['Resource']                    = $this->service_instance;
        $this->schedule_service['Resourcedata'] = $options;

        $options                                     = array();
        $options['service_namespace']                = 'Exception\\ControllerHandlingController';
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
     * @throws  \CommonApi\Exception\RuntimeException;
     */
    protected function createScheme()
    {
        $class = 'Molajo\\Resource\\Scheme';

        $input = BASE_FOLDER . '/vendor/molajo/resource/Source/Files/Input/SchemeArray.json';

        try {
            $scheme = new $class ($input);
        } catch (Exception $e) {
            throw new RuntimeException ('Resource Scheme ' . $class
            . ' Exception during Instantiation: ' . $e->getMessage());
        }

        return $scheme;
    }

    /**
     * Create Handler Instance
     *
     * @param   string $handler
     * @param   string $base_path
     * @param   array  $resource_map
     * @param   array  $namespace_prefixes
     * @param   array  $valid_file_extensions
     *
     * @return  mixed
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException;
     */
    protected function createHandler($handler, $base_path, $resource_map, $namespace_prefixes, $valid_file_extensions)
    {
        $class = 'Molajo\\Resource\\Handler\\' . $handler;

        try {
            $handler_instance = new $class (
                $base_path,
                $resource_map,
                $namespace_prefixes,
                $valid_file_extensions);
        } catch (Exception $e) {
            throw new RuntimeException ('Resource Handler ' . $handler
            . ' Exception during Instantiation: ' . $e->getMessage());
        }

        return $handler_instance;
    }
}
