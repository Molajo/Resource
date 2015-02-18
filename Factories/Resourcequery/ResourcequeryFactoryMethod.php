<?php
/**
 * Resource Query Factory Method
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo\Factories\Resourcequery;

use Exception;
use CommonApi\Exception\RuntimeException;
use CommonApi\IoC\FactoryInterface;
use CommonApi\IoC\FactoryBatchInterface;
use Molajo\IoC\FactoryMethod\Base as FactoryMethodBase;

/**
 * Resource Query Factory Method
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class ResourcequeryFactoryMethod extends FactoryMethodBase implements FactoryInterface, FactoryBatchInterface
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
        $options['product_namespace'] = 'Molajo\\Resource\\Adapter\\Query';
        $options['product_name']      = basename(__DIR__);

        parent::__construct($options);
    }

    /**
     * Instantiate a new handler and inject it into the Adapter for the FactoryInterface
     *
     * @return  array
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function setDependencies(array $reflection = array())
    {
        parent::setDependencies($reflection);

        $this->dependencies['Resource']            = array();
        $this->dependencies['Database']            = array();
        $this->dependencies['Query']               = array();
        $this->dependencies['Runtimedata']         = array();
        $this->dependencies['Eventcallback']       = array();
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

        $this->dependencies['base_path']          = $this->base_path;
        $this->dependencies['resource_map']       = $this->readFile(
            $this->base_path . '/Bootstrap/Files/Output/ResourceMap.json'
        );
        $this->options['Scheme']                  = $this->createScheme();
        $this->dependencies['namespace_prefixes'] = array();
        $this->dependencies['valid_file_extensions']
                                                  = $this->options['Scheme']->getScheme(
            'Query'
        )->include_file_extensions;

        $this->dependencies['query']                 = $this->dependencies['Query'];
        $this->dependencies['schedule_event']        = $this->dependencies['Eventcallback'];
        $this->dependencies['get_cache_callback']    = $this->dependencies['Getcachecallback'];
        $this->dependencies['set_cache_callback']    = $this->dependencies['Setcachecallback'];
        $this->dependencies['delete_cache_callback'] = $this->dependencies['Deletecachecallback'];

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
        $class = $this->product_namespace;

        $database = clone $this->dependencies['Database'];
        $query    = clone $this->dependencies['Query'];

        $this->product_result = new $class(
            $this->dependencies['base_path'],
            $this->dependencies['resource_map'],
            $this->dependencies['namespace_prefixes'],
            $this->dependencies['valid_file_extensions'],
            $database,
            $query,
            $this->dependencies['schedule_event'],
            $this->dependencies['get_cache_callback'],
            $this->dependencies['set_cache_callback'],
            $this->dependencies['delete_cache_callback']

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
        $this->dependencies['Resource']->setAdapterInstance('Query', $this->product_result);

        return $this;
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
}
