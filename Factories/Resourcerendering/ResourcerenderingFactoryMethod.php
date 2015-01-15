<?php
/**
 * Resources Rendering Factory Method
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo\Factories\Resourcerendering;

use Exception;
use CommonApi\Exception\RuntimeException;
use CommonApi\IoC\FactoryInterface;
use CommonApi\IoC\FactoryBatchInterface;
use Molajo\IoC\FactoryMethod\Base as FactoryMethodBase;

/**
 * Resources Rendering Factory Method
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class ResourcerenderingFactoryMethod extends FactoryMethodBase implements FactoryInterface, FactoryBatchInterface
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
        $options['product_name'] = basename(__DIR__);

        parent::__construct($options);
    }

    /**
     * Retrieve a list of Interface dependencies and return the data ot the controller.
     *
     * @return  array
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function setDependencies(array $reflection = array())
    {
        parent::setDependencies($reflection);

        $options                           = array();
        $this->dependencies['Resource']    = $options;
        $this->dependencies['Runtimedata'] = $options;

        return $this->dependencies;
    }

    /**
     * Set Dependencies for Instantiation
     *
     * @return  array
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function onBeforeInstantiation(array $dependency_values = null)
    {
        parent::onBeforeInstantiation($dependency_values);

        $filename     = $this->base_path . '/Bootstrap/Files/Output/ResourceMap.json';
        $resource_map = $this->readFile($filename);
        $scheme       = $this->createScheme();

        $adapter_instance['Theme']
            = $this->createHandler(
            'Theme',
            $this->base_path,
            $resource_map,
            array(),
            $scheme->getScheme('Theme')->include_file_extensions
        );
        $adapter_instance['Page']
            = $this->createHandler(
            'Page',
            $this->base_path,
            $resource_map,
            array(),
            $scheme->getScheme('Page')->include_file_extensions
        );
        $adapter_instance['Template']
            = $this->createHandler(
            'Template',
            $this->base_path,
            $resource_map,
            array(),
            $scheme->getScheme('Template')->include_file_extensions
        );
        $adapter_instance['Wrap']
            = $this->createHandler(
            'Wrap',
            $this->base_path,
            $resource_map,
            array(),
            $scheme->getScheme('Wrap')->include_file_extensions
        );
        $adapter_instance['Menuitem']
            = $this->createHandler(
            'Menuitem',
            $this->base_path,
            $resource_map,
            array(),
            $scheme->getScheme('Menuitem')->include_file_extensions
        );

        return $this->dependencies;
    }

    /**
     * Create Handler Instance
     *
     * @param   string $adapter
     * @param   string $base_path
     * @param   array  $resource_map
     * @param   array  $namespace_prefixes
     * @param   array  $valid_file_extensions
     * @param   bool   $extensions
     *
     * @return  mixed
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function createHandler(
        $adapter,
        $base_path,
        $resource_map,
        $namespace_prefixes,
        $valid_file_extensions
    ) {
        $class = 'Molajo\\Resource\\Adapter\\' . $adapter;

        try {
            $adapter_instance = new $class (
                $base_path,
                $resource_map,
                $namespace_prefixes,
                $valid_file_extensions,
                $this->dependencies['Runtimedata']->reference_data->extensions,
                $this->dependencies['Resource']
            );
        } catch (Exception $e) {
            throw new RuntimeException(
                'Resources Handler ' . $adapter
                . ' Exception during Instantiation: ' . $e->getMessage()
            );
        }

        $this->dependencies['Resource']->setAdapterInstance($adapter, $adapter_instance);

        return $adapter_instance;
    }

    /**
     * Create Scheme Instance
     *
     * @return  object
     * @since   1.0
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
                'Resources Scheme ' . $class
                . ' Exception during Instantiation: ' . $e->getMessage()
            );
        }

        return $scheme;
    }

    /**
     * Request for array of Factory Methods to be Scheduled
     *
     * @return  $this
     * @since   1.0
     */
    public function scheduleFactories()
    {
        $options              = array();
        $options['base_path'] = $this->base_path;

        $this->schedule_factory_methods['Resourcecss']             = $options;
        $this->schedule_factory_methods['Resourcecssdeclarations'] = $options;
        $this->schedule_factory_methods['Resourcejs']              = $options;
        $this->schedule_factory_methods['Resourcejsdeclarations']  = $options;

        return $this->schedule_factory_methods;
    }
}
