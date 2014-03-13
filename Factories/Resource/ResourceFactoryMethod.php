<?php
/**
 * Resource Factory Method
 *
 * @package    Molajo
 * @license    http:/www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Factories\Resource;

use Exception;
use CommonApi\Exception\RuntimeException;
use CommonApi\IoC\FactoryMethodInterface;
use CommonApi\IoC\FactoryMethodBatchSchedulingInterface;
use Molajo\IoC\FactoryBase;

/**
 * Resource Factory Method
 *
 * @author     Amy Stephen
 * @license    http:/www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class ResourceFactoryMethod extends FactoryBase implements FactoryMethodInterface, FactoryMethodBatchSchedulingInterface
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
        $options['product_namespace']        = 'Molajo\\Resource\\Adapter';
        $options['store_instance_indicator'] = true;
        $options['product_name']             = basename(__DIR__);

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

        $resource_map = $this->readFile(
            $this->options['base_path'] . '/Bootstrap/Files/Output/ResourceMap.json'
        );

        /**
         * NOTE:
         *  Css, Cssdeclarations, Jsdeclarations, and JsHandler loaded in Application Factory Method
         *  QueryHandler loaded following Database Factory Method
         */
        $handler_instance['AssetHandler']
            = $this->createHandler(
            'AssetHandler',
            $this->options['base_path'],
            $resource_map,
            array(),
            $this->options['Scheme']->getScheme('Asset')->include_file_extensions
        );
        $handler_instance['ClassHandler']
            = $this->createHandler(
            'ClassHandler',
            $this->options['base_path'],
            $resource_map,
            array(),
            $this->options['Scheme']->getScheme('Class')->include_file_extensions
        );
        $handler_instance['FileHandler']
            = $this->createHandler(
            'FileHandler',
            $this->options['base_path'],
            $resource_map,
            array(),
            $this->options['Scheme']->getScheme('File')->include_file_extensions
        );
        $handler_instance['FolderHandler']
            = $this->createHandler(
            'FolderHandler',
            $this->options['base_path'],
            $resource_map,
            array(),
            $this->options['Scheme']->getScheme('Folder')->include_file_extensions
        );
        $handler_instance['HeadHandler']
            = $this->createHandler(
            'HeadHandler',
            $this->options['base_path'],
            $resource_map,
            array(),
            $this->options['Scheme']->getScheme('Head')->include_file_extensions
        );
        $handler_instance['XmlHandler']
            = $this->createHandler(
            'XmlHandler',
            $this->options['base_path'],
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
     * @param   array $dependency_values (ignored in Service Item Adapter, based in from handler)
     *
     * @return  $this
     * @since   1.0
     */
    public function onBeforeInstantiation(array $dependency_values = null)
    {
        $this->dependencies['Scheme']                 = $this->options['Scheme'];
        $this->dependencies['handler_instance_array'] = $this->options['handler_instance_array'];

        return $this->dependencies;
    }

    /**
     * Factory Method Controller triggers the Factory Method to create the Class for the Service
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException;
     */
    public function instantiateClass()
    {
        $class = 'Molajo\\Resource\\Adapter';

        $this->product_result = new $class(
            $this->dependencies['Scheme'],
            $this->dependencies['handler_instance_array']
        );

        return $this;
    }

    /**
     * Follows the completion of the instantiate method
     *
     * @return  object
     * @since   1.0
     */
    public function onAfterInstantiation()
    {
        $this->product_result->setNamespace(
            'PasswordLib\\PasswordLib',
            $this->options['base_path'] . '/Vendor' . '/Molajo' . '/User/Encrypt/PasswordLib.phar'
        );

        return $this;
    }

    /**
     * Request for array of Factory Methods to be Scheduled
     *
     * @return  object
     * @since   1.0
     */
    public function scheduleFactories()
    {
        $options                                 = array();
        $options['store_instance_indicator']     = true;
        $options['product_name']                 = 'Fieldhandler';
        $options['base_path']                    = $this->options['base_path'];
        $this->schedule_factory_methods['Fieldhandler'] = $options;

        $options                                 = array();
        $options['Resource']                     = $this->product_result;
        $options['base_path']                    = $this->options['base_path'];
        $this->schedule_factory_methods['Resourcedata'] = $options;

        $options                                      = array();
        $options['store_instance_indicator']          = true;
        $options['product_name']                      = 'Exceptionhandling';
        $options['base_path']                         = $this->options['base_path'];
        $this->schedule_factory_methods['Exceptionhandling'] = $options;

        return $this->schedule_factory_methods;
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

        $input = $this->options['base_path'] . '/Bootstrap/Files/Input/SchemeArray.json';

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
