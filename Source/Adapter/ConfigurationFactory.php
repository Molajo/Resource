<?php
/**
 * Configuration Factory
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Adapter;

use CommonApi\Exception\RuntimeException;
use Exception;

/**
 * XML Handler
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class ConfigurationFactory extends AbstractAdapter
{
    /**
     * Data Object
     *
     * @var    object  CommonApi\Resource\DataInterface
     * @since  1.0.0
     */
    protected $data;

    /**
     * Resource
     *
     * @var    object  CommonApi\Resource\ResourceInterface
     * @since  1.0.0
     */
    protected $resource;

    /**
     * Registry
     *
     * @var    object  Molajo\Resource\Api\RegistryInterface
     * @since  1.0.0
     */
    protected $registry;

    /**
     * Constructor
     *
     * @param  string $base_path
     * @param  array  $resource_map
     * @param  array  $namespace_prefixes
     * @param  array  $valid_file_extensions
     * @param  array  $configuration_dependency_array
     *
     * @since  1.0.0
     */
    public function __construct(
        $base_path,
        array $resource_map = array(),
        array $namespace_prefixes = array(),
        array $valid_file_extensions = array(),
        array $configuration_dependency_array = array()
    ) {
        parent::__construct(
            $base_path,
            $resource_map,
            $namespace_prefixes,
            $valid_file_extensions
        );

        $this->saveConfigurationDependencies($configuration_dependency_array);
    }

    /**
     * Save Configuration Dependencies
     *
     * @param   array $configuration_dependency_array
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function saveConfigurationDependencies(array $configuration_dependency_array = array())
    {
        if (count($configuration_dependency_array) === 0) {
            return $this;
        }

        $this->data     = $configuration_dependency_array['data'];
        $this->resource = $configuration_dependency_array['resource'];
        $this->registry = $configuration_dependency_array['registry'];

        return $this;
    }

    /**
     * Create Model Configuration Class
     *
     * @return  object
     * @since   1.0.0
     */
    public function instantiateModelConfiguration()
    {
        $class = 'Molajo\\Resource\\Configuration\\Model';

        $data     = clone $this->data;
        $registry = clone $this->registry;
        $resource = clone $this->resource;

        try {
            return new $class ($data, $registry, $resource);

        } catch (Exception $e) {
            throw new RuntimeException(
                'Resource ConfigurationFactory instantiateModelConfiguration failed: '
                . $e->getMessage()
            );
        }
    }

    /**
     * Create data_object Configuration Class
     *
     * @return  object  CommonApi\Resource\DataInterface
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function instantiateDataObjectConfiguration()
    {
        $class = 'Molajo\\Resource\\Configuration\\Dataobject';

        $data     = clone $this->data;
        $registry = clone $this->registry;
        $resource = clone $this->resource;

        try {
            return new $class ($data, $registry, $resource);

        } catch (Exception $e) {
            throw new RuntimeException(
                'Resource ConfigurationFactory instantiateDataObjectConfiguration failed: '
                . $e->getMessage()
            );
        }
    }
}
