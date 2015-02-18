<?php
/**
 * Extensions Factory Method
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo\Factories\Extensions;

use Exception;
use CommonApi\Exception\RuntimeException;
use CommonApi\IoC\FactoryInterface;
use CommonApi\IoC\FactoryBatchInterface;
use Molajo\IoC\FactoryMethod\Base as FactoryMethodBase;
use stdClass;

/**
 * Extensions Factory Method
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class ExtensionsFactoryMethod extends FactoryMethodBase implements FactoryInterface, FactoryBatchInterface
{
    /**
     * Controller
     *
     * @var    object  CommonApi\Controller\ReadInterface
     * @since  1.0.0
     */
    protected $controller = null;

    /**
     * Constructor
     *
     * @param  $options
     *
     * @since  1.0.0
     */
    public function __construct(array $options = array())
    {
        $options['product_name']             = basename(__DIR__);
        $options['store_instance_indicator'] = false;
        $options['product_namespace']        = 'Molajo\\Resource\\ExtensionMap';

        parent::__construct($options);
    }

    /**
     * Define dependencies or use dependencies automatically defined by base class using Reflection
     *
     * @return  array
     * @since   1.0.0
     */
    public function setDependencies(array $reflection = array())
    {
        parent::setDependencies($reflection);

        $options                           = array();
        $this->dependencies['Resource']    = $options;
        $this->dependencies['Runtimedata'] = $options;
        $this->dependencies['Cache']       = $options;

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

        $this->dependencies['extensions_filename']
            = $this->base_path . '/Bootstrap/Files/Output/Extensions.json';

        return $this->dependencies;
    }

    /**
     * Instantiate Class
     *
     * @return  $this
     * @since   1.0.0
     */
    public function instantiateClass()
    {
        $cache_results = $this->dependencies['Cache']->get('Extensions');

        if ($cache_results === false || $cache_results->value === null) {
        } else {
            $this->product_result = $cache_results->value;

            return $this;
        }

        if (is_file($this->dependencies['extensions_filename'])) {
            $this->product_result = $this->readFile($this->dependencies['extensions_filename']);
        } else {
            $this->createMap();
        }

        $this->dependencies['Cache']->set('Extensions', $this->product_result);

        return $this;
    }

    /**
     * Create Extensions Map
     *
     * @return  $this
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function createMap()
    {
        try {
            $extension_map = new $this->product_namespace(
                $this->dependencies['Resource'],
                $this->dependencies['Runtimedata'],
                $this->dependencies['extensions_filename']
            );
        } catch (Exception $e) {
            throw new RuntimeException(
                'Render: Could not instantiate Handler: ' . $this->product_namespace
            );
        }

        $this->product_result = $extension_map->createMap();

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
        $this->dependencies['Runtimedata']->reference_data->extensions = $this->product_result;
        $this->set_container_entries['Runtimedata']                    = $this->dependencies['Runtimedata'];

        return $this->set_container_entries;
    }
}
