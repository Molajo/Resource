<?php
/**
 * Resource Data Factory Method
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo\Factories\Resourcedata;

use Exception;
use CommonApi\Exception\RuntimeException;
use CommonApi\IoC\FactoryInterface;
use CommonApi\IoC\FactoryBatchInterface;
use Molajo\IoC\FactoryMethod\Base as FactoryMethodBase;

/**
 * Resource Data Factory Method
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class ResourcedataFactoryMethod extends FactoryMethodBase implements FactoryInterface, FactoryBatchInterface
{
    /**
     * Valid Data Object Types
     *
     * @var    array
     * @since  1.0
     */
    protected $valid_data_object_types;

    /**
     * Valid Data Object Attributes
     *
     * @var    array
     * @since  1.0
     */
    protected $valid_data_object_attributes;

    /**
     * Valid Model Types
     *
     * @var    array
     * @since  1.0
     */
    protected $valid_model_types;

    /**
     * Valid Model Attributes
     *
     * @var    array
     * @since  1.0
     */
    protected $valid_model_attributes;

    /**
     * Valid Data Types
     *
     * @var    array
     * @since  1.0
     */
    protected $valid_data_types;

    /**
     * Valid Query Attributes
     *
     * @var    array
     * @since  1.0
     */
    protected $valid_queryelements_attributes;

    /**
     * Valid Field Attributes
     *
     * @var    array
     * @since  1.0
     */
    protected $valid_field_attributes;

    /**
     * Valid Join Attributes
     *
     * @var    array
     * @since  1.0
     */
    protected $valid_join_attributes;

    /**
     * Valid Foreignkey Attributes
     *
     * @var    array
     * @since  1.0
     */
    protected $valid_foreignkey_attributes;

    /**
     * Valid Criteria Attributes
     *
     * @var    array
     * @since  1.0
     */
    protected $valid_criteria_attributes;

    /**
     * Valid Children Attributes
     *
     * @var    array
     * @since  1.0
     */
    protected $valid_children_attributes;

    /**
     * Valid Plugin Attributes
     *
     * @var    array
     * @since  1.0
     */
    protected $valid_plugin_attributes;

    /**
     * Valid Value Attributes
     *
     * @var    array
     * @since  1.0
     */
    protected $valid_value_attributes;

    /**
     * Valid Field Attribute Defaults
     *
     * @var    array
     * @since  1.0
     */
    protected $valid_field_attributes_default;

    /**
     * Datalists
     *
     * @var    array
     * @since  1.0
     */
    protected $valid_datalists;

    /**
     * Constructor
     *
     * @param  array $options
     *
     * @since  1.0
     */
    public function __construct(array $options = array())
    {
        $options['product_namespace']        = 'Molajo\\Resource\\Configuration\\Data';
        $options['store_instance_indicator'] = true;
        $options['product_name']             = basename(__DIR__);

        parent::__construct($options);

        $this->options['Resource'] = $options['Resource'];
    }

    /**
     * Retrieve and load valid properties for fields, data models and data objects
     *
     * @param   array $reflection
     *
     * @return  object
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function setDependencies(array $reflection = array())
    {
        parent::setDependencies($reflection);

        $options                        = array();
        $options['product_namespace']   = 'Molajo\\Resource\\Configuration\\Registry';
        $this->dependencies['Registry'] = $options;

        /** Fields Configuration */
        $f = $this->options['Resource']->get('xml:///Molajo//Model//Application//Fields.xml');

        /** Data Objects */
        $this->loadFieldProperties($f, 'dataobjecttypes', 'dataobjecttype', 'valid_data_object_types');
        $this->loadFieldPropertiesWithAttributes($f, 'dataobjectattributes', 'dataobjectattribute',
            'valid_data_object_attributes');

        /** Models */
        $this->loadFieldProperties($f, 'modeltypes', 'modeltype', 'valid_model_types');
        $this->loadFieldPropertiesWithAttributes($f, 'modelattributes', 'modelattribute', 'valid_model_attributes');

        /** Data Types */
        $this->loadFieldPropertiesWithAttributes($f, 'datatypes', 'datatypes', 'valid_data_types');
        $this->loadFieldProperties($f, 'queryelements', 'queryelement', 'valid_queryelements_attributes');

        $list = $this->valid_queryelements_attributes;

        foreach ($list as $item) {
            $field = explode(',', $item);
            $this->loadFieldProperties($f, $field[0], $field[1], $field[2]);
        }

        $data_lists_array = array();
        $path             = $this->base_path . '/vendor/molajo/application/Source/Model/Datalist';
        $data_lists_array = $this->loadDatalists($data_lists_array, $path);
        $data_lists_array = array_unique($data_lists_array);

        $this->valid_datalists = $data_lists_array;

        $this->setValidArray();

        $this->dependencies['Getcachecallback']    = array();
        $this->dependencies['Setcachecallback']    = array();
        $this->dependencies['Deletecachecallback'] = array();

        return $this->dependencies;
    }

    /**
     * Instantiate Class
     *
     * @return  object
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function instantiateClass()
    {
        try {
            $class                = $this->product_namespace;
            $this->product_result = new $class($this->options['valid_array']);

        } catch (Exception $e) {

            throw new RuntimeException(
                'IoC Factory Method Adapter Instance Failed for ' . $this->product_namespace
                . ' failed.' . $e->getMessage()
            );
        }

        return $this;
    }

    /**
     * Follows the completion of the instantiate method
     *
     * @return  object
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function onAfterInstantiation()
    {
        $xml = $this->createXml();

        $this->options['Resource']->setAdapterInstance('Xml', $xml);

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
        $this->set_container_entries['Resource'] = $this->options['Resource'];

        return $this->set_container_entries;
    }

    /**
     * loadFieldProperties
     *
     * @param   string $xml
     * @param   string $plural
     * @param   string $singular
     * @param   string $parameter_name
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function loadFieldProperties($xml, $plural, $singular, $parameter_name)
    {
        if (isset($xml->$plural->$singular)) {
        } else {
            return false;
        }

        $types = $xml->$plural->$singular;

        if (count($types) === 0) {
            return false;
        }

        $type_array = array();
        foreach ($types as $type) {
            $type_array[] = (string)$type;
        }

        $this->$parameter_name = $type_array;

        return $this;
    }

    /**
     * loadFieldPropertiesWithAttributes
     *
     * @param   string $xml
     * @param   string $plural
     * @param   string $singular
     * @param   string $parameter_name
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function loadFieldPropertiesWithAttributes($xml, $plural, $singular, $parameter_name)
    {
        if (isset($xml->$plural->$singular)) {
        } else {
            return $this;
        }

        $type_array         = array();
        $type_default_array = array();
        foreach ($xml->$plural->$singular as $type) {
            $type_array[]                              = (string)$type['name'];
            $type_default_array[(string)$type['name']] = (string)$type['default'];
        }

        $this->$parameter_name = $type_array;
        $temp                  = $parameter_name . '_defaults';
        $this->$temp           = $type_default_array;

        return $this;
    }

    /**
     * loadDatalists
     *
     * @param   string $data_lists_array
     * @param   string $folder
     *
     * @return  array
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function loadDatalists($data_lists_array, $folder)
    {
        try {
            $directory_read = dir($folder);
            $path           = $directory_read->path;

            while (false !== ($entry = $directory_read->read())) {
                if (is_dir($path . '/' . $entry)) {
                } else {
                    $data_lists_array[] = substr($entry, 0, strlen($entry) - 4);
                }
            }

            $directory_read->close();

        } catch (RuntimeException $e) {
            throw new RuntimeException(
                'IoC Factory Method Configuration: loadDatalists cannot find Datalists file for folder: ' . $folder
            );
        }

        return $data_lists_array;
    }

    /**
     * Create Resource Xml Adapter dependent upon configuration information
     *
     * @return  object
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function createXml()
    {
        $scheme = $this->createScheme();

        $resource_map = $this->readFile(
            $this->base_path
            . '/Bootstrap/Files/Output/ResourceMap.json'
        );

        $class = 'Molajo\\Resource\\Adapter\\Xml';

        try {
            $xml = new $class (
                $this->base_path,
                $resource_map,
                array(),
                $scheme->getScheme('Xml')->include_file_extensions,
                array(
                    'get_cache_callback'    => $this->dependencies['Getcachecallback'],
                    'set_cache_callback'    => $this->dependencies['Setcachecallback'],
                    'delete_cache_callback' => $this->dependencies['Deletecachecallback']
                ),
                array(
                    'resource' => $this->options['Resource'],
                    'registry' => $this->dependencies['Registry'],
                    'data'     => $this->product_result
                )
            );
        } catch (Exception $e) {
            throw new RuntimeException(
                'Resource Data Factory Method createXml failed: '
                . $e->getMessage()
            );
        }

        return $xml;
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
     * Set Valid Array
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setValidArray()
    {
        $valid_array = array();

        $valid_array['valid_data_object_types']        = $this->valid_data_object_types;
        $valid_array['valid_data_object_attributes']   = $this->valid_data_object_attributes;
        $valid_array['valid_model_types']              = $this->valid_model_types;
        $valid_array['valid_model_attributes']         = $this->valid_model_attributes;
        $valid_array['valid_data_types']               = $this->valid_data_types;
        $valid_array['valid_queryelements_attributes'] = $this->valid_queryelements_attributes;
        $valid_array['valid_field_attributes']         = $this->valid_field_attributes;
        $valid_array['valid_join_attributes']          = $this->valid_join_attributes;
        $valid_array['valid_foreignkey_attributes']    = $this->valid_foreignkey_attributes;
        $valid_array['valid_criteria_attributes']      = $this->valid_criteria_attributes;
        $valid_array['valid_children_attributes']      = $this->valid_children_attributes;
        $valid_array['valid_plugin_attributes']        = $this->valid_plugin_attributes;
        $valid_array['valid_value_attributes']         = $this->valid_value_attributes;
        $valid_array['valid_field_attributes_default'] = $this->valid_field_attributes_default;
        $valid_array['valid_datalists']                = $this->valid_datalists;

        $this->options['valid_array'] = $valid_array;

        return $this;
    }
}
