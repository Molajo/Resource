<?php
/**
 * AbstractHandler Handler for XML Configuration
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Resources\Configuration;

use stdClass;
use Molajo\Registry\Api\RegistryInterface;
use Molajo\Resources\Api\ConfigurationInterface;
use Molajo\Resources\Api\ConfigurationDataInterface;
use Molajo\Resources\Api\ResourceAdapterInterface;
use Molajo\Resources\Exception\ConfigurationException;

/**
 * AbstractHandler Handler for XML Configuration
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
abstract class AbstractHandler implements ConfigurationInterface
{
    /**
     * Data Object Instance
     *
     * @var    object Molajo\Resources\Api\ConfigurationDataInterface
     * @since  1.0
     */
    protected $dataobject;

    /**
     * Registry
     *
     * @var    object Molajo\Profiler\Api\RegistryInterface
     * @since  1.0
     */
    protected $registry;

    /**
     * Resource Instance
     *
     * @var    object Molajo\Resources\Api\ConfigurationDataInterface
     * @since  1.0
     */
    protected $resource;

    /**
     * Constructor
     *
     * @param ConfigurationDataInterface $dataobject
     * @param RegistryInterface          $registry
     * @param ResourceAdapterInterface   $resource
     *
     * @since  1.0
     */
    public function __construct(
        ConfigurationDataInterface $dataobject,
        RegistryInterface $registry,
        ResourceAdapterInterface $resource
    ) {
        $this->dataobject = $dataobject;
        $this->registry   = $registry;
        $this->resource   = $resource;
    }

    /**
     * Load registry for requested model resource, returning name of registry collection
     *
     * @param   string $model_type
     * @param   string $model_name
     * @param   object $xml
     *
     * @return  string  Name of registry model
     * @since   1.0
     * @throws  ConfigurationException
     */
    public function getConfiguration($model_type, $model_name, $xml)
    {
        throw new ConfigurationException
        ('Configuration Xml Abstract Handler - use subclass getConfiguration');
    }

    /**
     * Store Configuration Data in Registry
     *
     * @param   string $model_registry
     * @param   object $xml
     *
     * @return  array
     * @since   1.0
     * @throws  ConfigurationException
     */
    public function setModelRegistry($model_registry, $xml)
    {
        throw new ConfigurationException
        ('Configuration Xml Abstract Handler - use subclass setModelRegistry');
    }

    /**
     * Parse xml recursively, processing all include statements
     *
     * @param   string $xml
     *
     * @return  mixed
     * @since   1.0
     * @throws  ConfigurationException
     */
    protected function getIncludeCode($xml)
    {
        $xml_string = $xml->asXML();

        $pattern = '/<include (.*)="(.*)"\/>/';

        $done = false;
        while ($done === false) {

            preg_match_all($pattern, $xml_string, $matches);
            if (count($matches[1]) == 0) {
                break;
            }

            $i = 0;
            foreach ($matches[1] as $match) {

                $replaceThis = $matches[0][$i];

                $include = ucfirst(strtolower($matches[2][$i]));

                if (trim(strtolower($matches[1][$i])) == 'field') {
                    $withThis = $this->resource->get('xml:///Molajo//Field//' . $include . '.xml');
                } else {
                    $withThis = $this->resource->get('xml:///Molajo//Include//' . $include . '.xml');
                }

                $xml_string = str_replace($replaceThis, $withThis, $xml_string);

                $i ++;
            }
        }

        return simplexml_load_string($xml_string);
    }

    /**
     * Define elements for Data Model to Registry
     *
     * @param   string $model_registry
     * @param   object $xml
     * @param   string $plural
     * @param   string $singular
     * @param   string $valid_attributes
     *
     * @return  bool
     * @since   1.0
     * @throws  ConfigurationException
     */
    protected function setElementsRegistry($model_registry, $xml, $plural, $singular, $valid_attributes)
    {
        if (isset($xml->table->$plural->$singular)) {
        } else {
            return true;
        }

        $set = $xml->table->$plural;

        $itemArray = array();

        if (count($set->$singular) > 0) {

            foreach ($set->$singular as $item) {

                $attributes = get_object_vars($item);

                $itemAttributes      = ($attributes["@attributes"]);
                $itemAttributesArray = array();

                if (count($itemAttributes) > 0) {

                    foreach ($itemAttributes as $key => $value) {

                        if (in_array($key, $valid_attributes)) {
                        } else {
                            throw new ConfigurationException
                            ('Configuration: setElementsRegistry encountered Invalid Model Attribute '
                            . $key . ' for ' . $model_registry);
                        }

                        $itemAttributesArray[$key] = $value;
                    }
                }

                if ($plural == 'plugins') {
                    if (count($itemAttributesArray) > 0) {
                        foreach ($itemAttributesArray as $plugin) {
                            $itemArray[] = $plugin;
                        }
                    }
                } else {
                    $itemArray[] = $itemAttributesArray;
                }
            }
        }

        if ($plural == 'joins') {
            $joins   = array();
            $selects = array();

            for ($i = 0; $i < count($itemArray); $i ++) {
                $temp      = $this->setJoinFields($itemArray[$i]);
                $joins[]   = $temp[0];
                $selects[] = $temp[1];
            }

            $this->registry->set($model_registry, $plural, $joins);

            $this->registry->set($model_registry, 'JoinFields', $selects);

        } elseif ($plural == 'values') {

            $valuesArray = array();

            if (count($itemArray) > 0) {

                foreach ($itemArray as $value) {

                    if (is_array($value)) {
                        $temp_row = $value;
                    } else {
                        $valueVars = get_object_vars($value);
                        $temp_row  = ($valueVars["@attributes"]);
                    }

                    $temp = new stdClass();

                    $temp->id    = $temp_row['id'];
                    $temp->value = $temp_row['value'];

                    $valuesArray[] = $temp;
                }

                $this->registry->set($model_registry, 'values', $valuesArray);
            }

        } else {
            $this->registry->set($model_registry, $plural, $itemArray);
        }

        return true;
    }

    /**
     * Process join field definitions for registry
     *
     * @param   array $modelJoinArray
     *
     * @return  array
     * @since   1.0
     * @throws  ConfigurationException
     */
    protected function setJoinFields($modelJoinArray)
    {
        $joinArray       = array();
        $joinSelectArray = array();

        $joinModel    = ucfirst(strtolower($modelJoinArray['model']));
        $joinRegistry = $joinModel . 'Datasource';

        if ($this->registry->exists($joinRegistry) === false) {
            ;
            $this->resource->get('xml:///Molajo//Datasource//' . $joinModel . '.xml');
        }

        $fields = $this->registry->get($joinRegistry, 'Fields');

        $table = $this->registry->get($joinRegistry, 'table_name');

        $joinArray['table_name'] = $table;

        $alias = (string)$modelJoinArray['alias'];
        if (trim($alias) == '') {
            $alias = substr($table, 3, strlen($table));
        }
        $joinArray['alias'] = trim($alias);

        $select              = (string)$modelJoinArray['select'];
        $joinArray['select'] = $select;

        $selectArray = explode(',', $select);

        if ((int)count($selectArray) > 0) {

            foreach ($selectArray as $s) {

                foreach ($fields as $joinSelectArray) {
                    if ($joinSelectArray['name'] == $s) {
                        $joinSelectArray['as_name']    = trim($alias) . '_' . trim($s);
                        $joinSelectArray['alias']      = $alias;
                        $joinSelectArray['table_name'] = $table;
                    }
                }
            }
        }

        $joinArray['jointo']   = (string)$modelJoinArray['jointo'];
        $joinArray['joinwith'] = (string)$modelJoinArray['joinwith'];

        return array($joinArray, $joinSelectArray);
    }

    /**
     * getCustomFields extracts field information for all customfield groups
     *
     * @param   string $model_registry
     * @param   object $xml
     *
     * @return  object
     * @since   1.0
     * @throws  ConfigurationException
     */
    protected function getCustomFields($model_registry, $xml)
    {
        $customFieldsArray = array();

        if (count($xml->customfields->customfield) > 0) {

            foreach ($xml->customfields->customfield as $custom_field) {

                $name    = (string)$custom_field['name'];
                $results = $this->getCustomFieldsSpecificGroup($model_registry, $custom_field);
                if ($results === false) {
                } else {

                    $fieldArray = $results[0];
                    $fieldNames = $results[1];

                    $this->inheritCustomFieldsSpecificGroup(
                        $model_registry,
                        $name,
                        $fieldArray,
                        $fieldNames
                    );

                    $customFieldsArray[] = $name;
                }
            }
        }

        /** Include Inherited Groups not matching existing groups */
        $exists = $this->registry->exists($model_registry, 'Customfieldgroups');

        if ($exists === true) {
            $inherited = $this->registry->get($model_registry, 'Customfieldgroups');

            if (is_array($inherited) && count($inherited) > 0) {
                foreach ($inherited as $name) {

                    if (in_array($name, $customFieldsArray)) {
                    } else {
                        $results = $this->inheritCustomFieldsSpecificGroup($model_registry, $name);
                        if ($results === false) {
                        } else {
                            $customFieldsArray[] = $name;
                        }
                    }
                }
            }
        }

        $this->registry->set($model_registry, 'Customfieldgroups', array_unique($customFieldsArray));

        return;
    }

    /**
     * Load Custom Fields for a specific Group -- this is called once for each custom field type for a Model
     *
     * @param   $model_registry
     * @param   $customfield
     *
     * @return  array|bool
     * @since   1.0
     * @throws  ConfigurationException
     */
    protected function getCustomFieldsSpecificGroup($model_registry, $customfield)
    {
        $fieldArray = array();
        $fieldNames = array();

        if (count($customfield) > 0) {

            foreach ($customfield as $key1 => $value1) {

                $attributes           = get_object_vars($value1);
                $fieldAttributes      = ($attributes["@attributes"]);
                $fieldAttributesArray = array();

                if (count($fieldAttributes) > 0) {
                    foreach ($fieldAttributes as $key2 => $value2) {

                        if ($key2 == 'fieldset') {
                        } elseif (in_array($key2, $this->dataobject->get('valid_field_attributes'))) {
                        } else {
                            throw new ConfigurationException
                            ('Configuration: getCustomFieldsSpecificGroup Invalid Field attribute '
                            . $key2 . ':' . $value2 . ' for ' . $model_registry);
                        }

                        if ($key2 == 'name') {
                        } else {
                            $fieldNames[] = $value2;
                        }

                        $fieldAttributesArray[$key2] = $value2;
                    }
                }

                $fieldAttributesArray['field_inherited'] = 0;

                $fieldArray[] = $fieldAttributesArray;
            }
        }

        if (is_array($fieldArray) && count($fieldArray) > 0) {
        } else {
            return false;
        }

        return array($fieldArray, $fieldNames);
    }

    /**
     * Inherited fields are merged in with those specifically defined in model
     *
     * @param   $model_registry
     * @param   $name
     * @param   $fieldArray
     * @param   $fieldNames
     *
     * @return array
     * @since   1.0
     */
    protected function inheritCustomFieldsSpecificGroup(
        $model_registry,
        $name,
        $fieldArray = array(),
        $fieldNames = array()
    ) {

        $available = $this->registry->get($model_registry, $name, array());

        if (count($available) > 0) {

            foreach ($available as $temp_row) {

                foreach ($temp_row as $field => $fieldvalue) {

                    if ($field == 'name') {

                        if (in_array($fieldvalue, $fieldNames)) {
                        } else {
                            $temp_row['field_inherited'] = 1;
                            $fieldArray[]                = $temp_row;
                            $fieldNames[]                = $fieldvalue;
                        }
                    }
                }
            }
        }

        if (is_array($fieldArray) && count($fieldArray) == 0) {
            $this->registry->set($model_registry, $name, array());

            return false;
        }

        $this->registry->set($model_registry, $name, $fieldArray);

        return $name;
    }

    /**
     * Inheritance checking and setup  <model name="XYZ" extends="ThisTable"/>
     *
     * @param   $model_registry
     * @param   $xml
     *
     * @return  $this
     * @since   1.0
     * @throws  ConfigurationException
     */
    protected function inheritDefinition($model_registry, $xml)
    {
        $extends = false;

        if (count($xml->attributes()) > 0) {
            foreach ($xml->attributes() as $key => $value) {
                if ($key == 'extends') {
                    $extends = (string)$value;
                }
            }
        }
        if ($extends === false) {
            return $this;
        }

        $modelArray = $this->dataobject->get('valid_model_types');

        $extends_model_name = '';
        $extends_model_type = '';
        if (count($modelArray) > 0) {

            foreach ($modelArray as $modeltype) {
                if (ucfirst(
                        strtolower(substr($extends, strlen($extends) - strlen($modeltype), strlen($modeltype)))
                    ) == $modeltype
                ) {
                    $extends_model_name = ucfirst(
                        strtolower(substr($extends, 0, strlen($extends) - strlen($modeltype)))
                    );
                    $extends_model_type = $modeltype;
                    break;
                }
            }
        }

        if ($extends_model_name == '') {
            $extends_model_name = ucfirst(strtolower($extends));
            $extends_model_type = 'Datasource';
        }

        $inheritModelRegistry = $extends_model_name . $extends_model_type;

        if ($this->registry->exists($inheritModelRegistry) === true) {

        } else {
            $this->resource->get('xml:///Molajo//Datasource//' . $extends_model_name . '.xml');
        }

        $this->registry->copy($inheritModelRegistry, $model_registry);

        return $this;
    }
}
