<?php
/**
 * Resourceadapter Factory Method
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo\Factories\Resourceadapter;

use CommonApi\IoC\FactoryInterface;
use CommonApi\IoC\FactoryBatchInterface;
use Molajo\IoC\FactoryMethod\Base as FactoryMethodBase;

/**
 * Resourceadapter Factory Method
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class ResourceadapterFactoryMethod extends FactoryMethodBase implements FactoryInterface, FactoryBatchInterface
{
    /**
     * Constructor
     *
     * @param  array $options
     *
     * @since  1.0.0
     */
    public function __construct(array $options = array())
    {
        /**
         * $options['product_name'] and $options['product_namespace'] sent in by resource adapter factory
         */

        $options['store_instance_indicator'] = false;

        $this->options['scheme_name'] = $options['scheme_name'];

        if (isset($options['valid_file_extensions'])) {
            $this->options['valid_file_extensions'] = $options['valid_file_extensions'];
        } else {
            $this->options['valid_file_extensions'] = array();
        }

        if (isset($options['handler_options'])) {
            $this->options['handler_options'] = $options['handler_options'];
        } else {
            $this->options['handler_options'] = array();
        }

        parent::__construct($options);
    }

    /**
     * Retrieve a list of Interface dependencies and return the data ot the controller.
     *
     * @return  array
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function setDependencies(array $reflection = array())
    {
        parent::setDependencies($reflection);

        $this->dependencies['Resource']            = array();
        $this->dependencies['ResourceMap']         = array();
        $this->dependencies['Getcachecallback']    = array();
        $this->dependencies['Setcachecallback']    = array();
        $this->dependencies['Deletecachecallback'] = array();

        return $this->dependencies;
    }

    /**
     * Set Dependencies for Instantiation
     *
     * @return  array
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function onBeforeInstantiation(array $dependency_values = null)
    {
        parent::onBeforeInstantiation($dependency_values);

        $cache_callbacks                          = array();
        $cache_callbacks['get_cache_callback']    = $this->dependencies['Getcachecallback'];
        $cache_callbacks['set_cache_callback']    = $this->dependencies['Setcachecallback'];
        $cache_callbacks['delete_cache_callback'] = $this->dependencies['Deletecachecallback'];

        $this->dependencies['cache_callbacks'] = $cache_callbacks;

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
        $this->product_result = new $this->product_namespace(
            $this->base_path,
            $this->dependencies['ResourceMap'],
            array(),
            $this->options['valid_file_extensions'],
            $this->dependencies['cache_callbacks'],
            $this->options['handler_options']
        );

        return $this;
    }

    /**
     * Follows the completion of the instantiate method
     *
     * @return  $this
     * @since   1.0.0
     */
    public function onAfterInstantiation()
    {
        $this->dependencies['Resource']->setScheme(
            $this->options['scheme_name'],
            $this->product_result,
            $this->options['valid_file_extensions']
        );

        if (strtolower($this->options['scheme_name']) === 'classloader') {
            $this->dependencies['Resource']->register(true);
        }

        return $this;
    }

    /**
     * Factory Method Controller requests any Products (other than the current product) to be saved
     *
     * @return  array
     * @since   1.0.0
     */
    public function setContainerEntries()
    {
        $this->set_container_entries['Resource'] = $this->dependencies['Resource'];

        return $this->set_container_entries;
    }
}
