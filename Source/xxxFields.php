<?php
/**
 * Fields
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource;

use stdClass;

/**
 * Fields
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class Fields
{
    /**
     * Query Instance
     *
     * @var    object
     * @since  1.0.0
     */
    protected $query;

    /**
     * Resource
     *
     * @var    object
     * @since  1.0.0
     */
    protected $resource;

    /**
     * Runtime Data
     *
     * @var    object
     * @since  1.0.0
     */
    protected $runtime_data = null;

    /**
     * Fields
     *
     * @var    array
     * @since  1.0.0
     */
    protected $fields = array();

    /**
     * Field Path
     *
     * @var    string
     * @since  1.0.0
     */
    protected $field_path;

    /**
     * Catalog Type ID
     *
     * @var    string
     * @since  1.0.0
     */
    protected $field_catalog_type_id = 500;

    /**
     * Constructor
     *
     * @param  object $resource
     * @param  object $runtime_data
     * @param  string $field_path
     *
     * @since  1.0.0
     */
    public function __construct(
        $resource,
        $runtime_data,
        $field_path
    ) {
        $this->resource     = $resource;
        $this->runtime_data = $runtime_data;
        $this->field_path   = $field_path;
    }

    /**
     * Process Fields
     *
     * @return  $this
     * @since   1.0.0
     */
    public function processFields()
    {
        $files = scandir($this->field_path);

        if (count($files) === 0) {
            return $this;
        }

        foreach ($files as $file_name) {
            if ($file_name === '.' || $file_name === '..') {
            } else {
                $this->readField($file_name);
            }
        }

        $id = 1;
        foreach ($this->fields as $field_name => $field) {
            $this->insertField($field, $id++);
        }

        return $this;
    }

    /**
     * Read Field File
     *
     * @param   string $file_name
     *
     * @return  $this
     * @since   1.0.0
     */
    public function readField($file_name)
    {
        if (file_exists(($this->field_path . $file_name))) {
        } else {
            return $this;
        }

        $string = file_get_contents($this->field_path . $file_name);
        $xml    = simplexml_load_string($string);

        if (count($xml->attributes()) > 0) {
        } else {
            return $this;
        }

        $row = new stdClass();
        foreach ($xml->attributes() as $key => $value) {
            $key_name       = (string)$key;
            $value_string   = (string)$value;
            $row->$key_name = $value_string;
        }

        $this->fields[substr($file_name, 0, strlen($file_name) - 4)] = $row;

        return $this;
    }

    /**
     * Insert Field
     *
     * @param   object  $field
     * @param   integer $id
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function insertField($field, $id)
    {
        $this->query = null;

        $this->connect('create');

        $this->query->setModelRegistry('process_events', 1);
        $this->query->setModelRegistry('check_view_level_access', 0);
        $this->query->setModelRegistry('get_customfields', 0);
        $this->query->setModelRegistry('use_special_joins', 0);

        $row = $this->query->initialiseRow();
        $row = $this->setInsertValues($row, $field, $id);
        $this->query->setInsertStatement($row);
        $this->query->setSql();
        $this->query->insertData();

        return $this;
    }

    /**
     * Set Language Field Values for Insert
     *
     * @param   object  $row
     * @param   object  $field
     * @param   integer $id
     *
     * @return  object
     * @since   1.0.0
     */
    protected function setInsertValues($row, $field, $id)
    {
        unset($row->id);
        $row->extension_id      = $this->field_catalog_type_id;
        $row->catalog_type_id   = $this->field_catalog_type_id;
        $row->title             = ucfirst(strtolower($field->name));
        $row->path              = 'fields';
        $row->parent_id         = 0;
        $row->translation_of_id = 0;
        $row->protected         = 1;
        $row->content_text      = '';
        $row->language          = 'en-GB';
        $row->ordering          = $id;
        $row->status            = 1;
        $row->customfields      = $this->setCustomFields($field);

        return $row;
    }

    /**
     * Set Customfield Elements
     *
     * @return  object
     * @since   1.0.0
     */
    protected function setCustomFields($field)
    {
        $custom_fields = $this->query->getModelRegistry('customfields');

        $row = new stdClass();

        foreach ($custom_fields as $custom_field) {

            $key   = $custom_field['name'];
            $value = null;

            if (isset($field->$key)) {
                $value = $field->$key;

            } elseif (isset($custom_field['default'])) {
                $value = $custom_field['default'];
            }

            $row->$key = $value;
        }

        return json_encode($row, JSON_PRETTY_PRINT);
    }

    /**
     * Get Query Connection
     *
     * @param   string $crud_type
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function connect($crud_type = 'Create')
    {
        $options   = array();
        $crud_type = ucfirst(strtolower($crud_type));
        if ($crud_type === 'Create'
            || $crud_type === 'Read'
            || $crud_type === 'Update'
            || $crud_type === 'Delete'
        ) {
        } else {
            $crud_type = 'Read';
        }

        $options['crud_type']    = $crud_type;
        $options['runtime_data'] = $this->runtime_data;

        $this->query = $this->resource->get(
            'query:///Molajo//Model//Datasource//Fields.xml',
            $options
        );

        $this->query->clearQuery();

        return $this;
    }
}
