<?php
/**
 * Custom Fields Controller
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Controller;

use stdClass;
use Exception;
use Molajo\Fieldhandler\Api\FieldHandlerInterface;
use Molajo\Controller\Exception\CustomfieldsException;
use Molajo\Controller\Api\CustomfieldsControllerInterface;

/**
 * Custom Fields Controller
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class CustomfieldsController implements CustomfieldsControllerInterface
{
    /**
     * Fieldhandler Instance
     *
     * @var    object  Molajo\Fieldhandler\Api\FieldHandlerInterface
     * @since  1.0
     */
    protected $fieldhandler;

    /**
     * Model Registry
     *
     * @var    object
     * @since  1.0
     */
    protected $model_registry = null;

    /**
     * Source Data containing the custom fields
     *
     * @var    object
     * @since  1.0
     */
    protected $data = null;

    /**
     * Parameters
     *
     * @var    object
     * @since  1.0
     */
    protected $parameters = null;

    /**
     * Type
     *
     * @var    string
     * @since  1.0
     */
    protected $page_type = 'list';

    /**
     * Constructor
     *
     * @param  FieldHandlerInterface $fieldhandler
     *
     * @since  1.0
     */
    public function __construct(
        FieldHandlerInterface $fieldhandler
    ) {
        $this->fieldhandler = $fieldhandler;
    }

    /**
     * Get Custom Fields and Data for each field
     *
     * @param   object      $model_registry
     * @param   null|object $data
     * @param   null|object $parameters
     * @param   null|string $page_type
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\CustomfieldsException
     */
    public function getCustomfields(
        $model_registry,
        $data = null,
        $parameters = null,
        $page_type = null
    ) {
        $this->model_registry = $model_registry;

        $this->data = $data;
        if ($this->data === null) {
            return array();
        }

        $this->parameters = $parameters;

        $this->page_type = strtolower($page_type);

        $customfieldgroups = $this->model_registry['customfieldgroups'];
        if (is_array($customfieldgroups) && count($customfieldgroups) > 0) {
        } else {
            return array();
        }

        $customfields = array();
        foreach ($customfieldgroups as $group) {
            $customfields[$group] = $this->processCustomfieldGroup($group);
        }

        $this->model_registry = null;
        $this->data           = null;

        return $customfields;
    }

    /**
     * Process Customfield Group
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\CustomfieldsException
     */
    protected function processCustomfieldGroup($group)
    {
        /** Standard Data */
        $standard_custom_field_data = json_decode($this->data->$group);

        if (is_array($standard_custom_field_data) > 0
            && isset($this->parameters->application->id)
        ) {

            foreach ($standard_custom_field_data as $key => $value) {
                if ($key == $this->parameters->application->id) {
                    $standard_custom_field_data = $value;
                    break;
                }
            }
        }

        /** Extension Instances Data */
        $x = 'extension_instances_' . $group;

        if (isset($this->data->$x)) {
            $extension_instances_field_data = json_decode($this->data->$x);

            if (is_array($extension_instances_field_data)
                && isset($this->parameters->application->id)
            ) {

                foreach ($extension_instances_field_data as $key => $value) {
                    if ($key == $this->parameters->application->id) {
                        $extension_instances_field_data = $value;
                        break;
                    }
                }
            }
        } else {
            $extension_instances_field_data = null;
        }

        /** Application Data */
        $application = $this->parameters->application->$group;

        $temp = array();

        foreach ($this->model_registry[$group] as $customfields) {

            $key        = $customfields['name'];
            $target_key = $key;
            $test       = substr($key, 0, strlen($this->page_type));
            $use        = true;

            if ((strlen($this->page_type) > 0)
                && (substr($key, 0, strlen('item_')) == 'item_'
                    || substr($key, 0, strlen('form_')) == 'form_'
                    || substr($key, 0, strlen('list_')) == 'list_'
                    || substr($key, 0, strlen('menuitem_')) == 'menuitem_')
            ) {
                if ($test == $this->page_type) {
                    $target_key = substr($key, strlen($this->page_type) + 1, 9999);
                } else {
                    $use = false;
                }
            }

            if ($use === true) {

                $value = null;

                if (isset($standard_custom_field_data->$key)) {
                    $value = $standard_custom_field_data->$key;
                }

                if (($value === null || $value == '' || $value == ' ')
                    && isset($extension_instances_field_data->$key)
                ) {
                    $value = $extension_instances_field_data->$key;
                }

                if (($value === null || $value == '' || $value == ' ')
                    && isset($application->$key)
                ) {
                    $value = $application->$key;
                }

                if (($value === null || $value == '' || $value == ' ')
                    && isset($application->$target_key)
                ) {

                    $value = $application->$target_key;
                }

                if ($value === null || $value == '' || $value == ' ') {

                    if (isset($customfields['default'])) {
                        $default = $customfields['default'];
                    } else {
                        $default = false;
                    }

                    $value = $default;
                }

                $page_type = $customfields['type'];

                $temp[$target_key] = $this->filter($key, $value, $page_type);
            }
        }

        ksort($temp);

        $group_name = new stdClass();
        foreach ($temp as $key => $value) {
            $group_name->$key = $value;
        }

        return $group_name;
    }

    /**
     * Filter Input
     *
     * @param          $key
     * @param   null   $value
     * @param          $page_type
     * @param   array  $filter_options
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\CustomfieldsException
     */
    protected function filter($key, $value = null, $page_type, $filter_options = array())
    {
        return $value;

        if ($page_type == 'text') {
            $filter = 'Html';
        } elseif ($page_type == 'char') {
            $filter = 'String';
        } elseif ($page_type == 'image') {
            $filter = 'Url';
        } elseif (substr($page_type, strlen($page_type) - 3, 3) == '_id'
            || $key == 'id'
            || $page_type == 'integer'
        ) {
            $filter = 'Int';
        } elseif ($page_type == 'char') {
            $filter = 'String';
        } else {
            $filter = $page_type;
        }

        try {
            $value = $this->fieldhandler->filter($key, $value, $filter, $filter_options);
        } catch (Exception $e) {
            throw new CustomfieldsException
            ('Request: Filter class Failed for Key: ' . $key . ' Filter: ' . $filter . ' ' . $e->getMessage());
        }

        return $value;
    }
}
