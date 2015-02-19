<?php
/**
 * Resource Factory Method
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo\Factories\Resource;

use Exception;
use CommonApi\Exception\RuntimeException;
use CommonApi\IoC\FactoryInterface;
use CommonApi\IoC\FactoryBatchInterface;
use Molajo\IoC\FactoryMethod\Base as FactoryMethodBase;

/**
 * Resource Factory Method
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class ResourceFactoryMethod extends FactoryMethodBase implements FactoryInterface, FactoryBatchInterface
{
    /**
     * Constructor
     *
     * @param  $options
     *
     * @since  1.0.0
     */
    public function __construct(array $options = array())
    {
        $options['product_namespace']        = 'Molajo\\Resource\\Driver';
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
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function setDependencies(array $reflection = array())
    {
        parent::setDependencies(array());

        $this->dependencies['Getcachecallback']    = array();
        $this->dependencies['Setcachecallback']    = array();
        $this->dependencies['Deletecachecallback'] = array();

        return $this->dependencies;
    }

    /**
     * Fulfill Dependencies
     *
     * @param   array $dependency_values (ignored in Service Item Adapter, based in from handler)
     *
     * @return  $this
     * @since   1.0.0
     */
    public function onBeforeInstantiation(array $dependency_values = null)
    {
        $this->dependencies['Scheme'] = $this->createScheme();

        $adapter_instance = array();

        $resource_map = $this->readFile(
            $this->base_path . '/Bootstrap/Files/Output/ResourceMap.json'
        );

        $fields = $this->readFile(
            $this->base_path . '/Bootstrap/Files/Model/Fields.json'
        );

        $cache_callbacks = array(
            'get_cache_callback'    => $this->dependencies['Getcachecallback'],
            'set_cache_callback'    => $this->dependencies['Setcachecallback'],
            'delete_cache_callback' => $this->dependencies['Deletecachecallback']
        );

        $empty_handler_options = array();

        /**
         * NOTE:
         *  Css, CssDeclarations, JsDeclarations, and Js loaded in Application Factory
         *  QueryHandler loaded following Database Factory Method
         */
        $adapter_instance['Asset']
            = $this->createAdapter(
            'Asset',
            $this->base_path,
            $resource_map,
            array(),
            $this->dependencies['Scheme']->getScheme('Asset')->include_file_extensions,
            $cache_callbacks,
            $empty_handler_options
        );
        $adapter_instance['ClassLoader']
            = $this->createAdapter(
            'ClassLoader',
            $this->base_path,
            $resource_map,
            array(),
            $this->dependencies['Scheme']->getScheme('ClassLoader')->include_file_extensions,
            $cache_callbacks,
            $empty_handler_options
        );
        $adapter_instance['Field']
            = $this->createAdapter(
            'Field',
            $this->base_path,
            $resource_map,
            array(),
            $this->dependencies['Scheme']->getScheme('Field')->include_file_extensions,
            $cache_callbacks,
            array('fields' => $fields)
        );
        $adapter_instance['File']
            = $this->createAdapter(
            'File',
            $this->base_path,
            $resource_map,
            array(),
            $this->dependencies['Scheme']->getScheme('File')->include_file_extensions,
            $cache_callbacks,
            $empty_handler_options
        );
        $adapter_instance['Folder']
            = $this->createAdapter(
            'Folder',
            $this->base_path,
            $resource_map,
            array(),
            $this->dependencies['Scheme']->getScheme('Folder')->include_file_extensions,
            $cache_callbacks,
            $empty_handler_options
        );
        $adapter_instance['Head']
            = $this->createAdapter(
            'Head',
            $this->base_path,
            $resource_map,
            array(),
            $this->dependencies['Scheme']->getScheme('Head')->include_file_extensions,
            $cache_callbacks,
            $empty_handler_options
        );
        $adapter_instance['Xml']
            = $this->createAdapter(
            'Xml',
            $this->base_path,
            $resource_map,
            array(),
            $this->dependencies['Scheme']->getScheme('Xml')->include_file_extensions,
            $cache_callbacks,
            $empty_handler_options
        );

        $this->dependencies['adapter_instance_array'] = $adapter_instance;

        return $this->dependencies;
    }

    /**
     * Factory Method Controller triggers the Factory Method to create the Class for the Service
     *
     * @return  $this
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function instantiateClass()
    {
        $class = 'Molajo\\Resource\\Driver';

        $this->product_result = new $class(
            $this->dependencies['Scheme'],
            $this->dependencies['adapter_instance_array']
        );

        return $this;
    }

    /**
     * Request for array of Factory Methods to be Scheduled
     *
     * @return  object
     * @since   1.0.0
     */
    public function scheduleFactories()
    {
        $options                                        = array();
        $options['store_instance_indicator']            = true;
        $options['product_name']                        = 'Fieldhandler';
        $this->schedule_factory_methods['Fieldhandler'] = $options;

        $options                                        = array();
        $options['Resource']                            = $this->product_result;
        $this->schedule_factory_methods['Resourcedata'] = $options;

        $options                                             = array();
        $options['store_instance_indicator']                 = true;
        $options['product_name']                             = 'Exceptionhandling';
        $this->schedule_factory_methods['Exceptionhandling'] = $options;

        return $this->schedule_factory_methods;
    }

    /**
     * Create Scheme Instance
     *
     * @return  object
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function createScheme()
    {
        $class = 'Molajo\\Resource\\Scheme';

        $input = $this->base_path . '/Bootstrap/Files/Input/SchemeArray.json';

        try {
            $scheme = new $class ($input);
        } catch (Exception $e) {
            throw new RuntimeException(
                'Resource Scheme ' . $class
                . ' Exception during Instantiation: ' . $e->getMessage()
            );
        }

        return $scheme;
    }

    /**
     * Create Handler Instance
     *
     * @param  string $adapter
     * @param  string $base_path
     * @param  array  $resource_map
     * @param  array  $namespace_prefixes
     * @param  array  $valid_file_extensions
     * @param  array  $cache_callbacks
     * @param  array  $handler_options
     *
     * @return  mixed
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function createAdapter(
        $adapter,
        $base_path,
        $resource_map,
        $namespace_prefixes,
        $valid_file_extensions,
        array $cache_callbacks = array(),
        array $handler_options = array()
    ) {
        $class = 'Molajo\\Resource\\Adapter\\' . $adapter;

        try {
            $adapter_instance = new $class (
                $base_path,
                $resource_map,
                $namespace_prefixes,
                $valid_file_extensions,
                $cache_callbacks,
                $handler_options
            );
        } catch (Exception $e) {
            throw new RuntimeException(
                'Resource Adapter ' . $adapter
                . ' Exception during Instantiation: ' . $e->getMessage()
            );
        }

        return $adapter_instance;
    }
}
