<?php
/**
 * Extension Map
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource;

use Exception;
use CommonApi\Exception\RuntimeException;
use CommonApi\Resource\ExtensionsInterface;
use stdClass;

/**
 * Extension Map
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class ExtensionMap implements ExtensionsInterface
{
    /**
     * Stores an array of key/value runtime_data settings
     *
     * @var    object
     * @since  1.0
     */
    protected $runtime_data = null;

    /**
     * Resource Instance
     *
     * @var    object
     * @since  1.0
     */
    protected $resource;

    /**
     * Extensions Filename
     *
     * @var    string
     * @since  1.0
     */
    protected $extensions_filename;

    /**
     * Extensions Filename
     *
     * @var    array
     * @since  1.0
     */
    protected $temp_ids = array();

    /**
     * Extensions Filename
     *
     * @var    array
     * @since  1.0
     */
    protected $temp_names = array();

    /**
     * Extensions Filename
     *
     * @var    array
     * @since  1.0
     */
    protected $temp_extensions = array();

    /**
     * Extensions Filename
     *
     * @var    array
     * @since  1.0
     */
    protected $temp_menus = array();

    /**
     * Extensions Filename
     *
     * @var    array
     * @since  1.0
     */
    protected $temp_page_types = array();

    /**
     * Constructor
     *
     * @param  object $resource
     * @param  object $runtime_data
     * @param  string $extensions_filename
     *
     * @since  1.0
     */
    public function __construct(
        $resource,
        $runtime_data,
        $extensions_filename = null
    ) {
        $this->resource            = $resource;
        $this->runtime_data        = $runtime_data;
        $this->extensions_filename = $extensions_filename;
    }

    /**
     * Catalog Types
     *
     * @return  stdClass
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function createMap()
    {
        $map = $this->getCatalogTypes();

        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            $x = json_encode($map, JSON_PRETTY_PRINT);
        } else {
            $x = json_encode($map);
        }

        file_put_contents($this->extensions_filename, $x);

        return $map;
    }

    /**
     * Get Catalog Types
     *
     * @return  stdClass
     * @since   1.0.0.0
     */
    public function getCatalogTypes()
    {
        $controller = $this->setCatalogTypesQuery();
        $results    = $this->runQuery($controller);

        return $this->processCatalogTypes($results);
    }

    /**
     * Process Catalog Types
     *
     * @param   $catalog_types  array
     *
     * @return  stdClass
     * @since   1.0.0
     */
    protected function processCatalogTypes(array $catalog_types)
    {
        $names       = array();
        $ids         = array();
        $model_names = array();
        $extensions  = array();

        foreach ($catalog_types as $type) {

            $ids[$type->id]         = $type->title;
            $names[$type->title]    = $type->id;
            $model_names[$type->id] = $type->model_name;
            $id                     = $type->id;

            $extensions[$id] = $this->getExtensions($id, $model_names[$id]);
        }

        unset($catalog_types);

        $catalog_type             = new stdClass();
        $catalog_type->ids        = $ids;
        $catalog_type->names      = $names;
        $catalog_type->extensions = $extensions;

        return $catalog_type;
    }

    /**
     * Retrieve Extensions for a specific Catalog Type
     *
     * @param   int    $catalog_type_id
     * @param   string $catalog_type_model_name
     *
     * @return  array|stdClass
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function getExtensions($catalog_type_id, $catalog_type_model_name)
    {
        $controller = $this->setExtensionsQuery($catalog_type_id);
        $items      = $this->runQuery($controller);

        if (is_array($items) && count($items) > 0) {
        } else {
            return array();
        }

        return $this->processExtensions($items, $catalog_type_id, $catalog_type_model_name);
    }

    /**
     * Process Extensions
     *
     * @param   array   $items
     * @param   integer $catalog_type_id
     * @param   string  $catalog_type_model_name
     *
     * @return  array|stdClass
     * @since   1.0.0
     */
    protected function processExtensions($items, $catalog_type_id, $catalog_type_model_name)
    {
        $this->initialiseExtensions($items, $catalog_type_id);

        $catalog_type_model_name = $this->setCatalogTypeModelName($catalog_type_model_name);

        foreach ($this->temp_ids as $id => $alias) {

            $resource_indicator = false;
            $alias              = ucfirst(strtolower($alias));

            if (in_array($catalog_type_model_name, array('Resources', 'System'))) {
                $resource_indicator = true;
                $model_name         = $this->setExtensionModelNameResource($alias, $this->temp_extensions, $id);

            } elseif ($catalog_type_id == $this->runtime_data->reference_data->catalog_type_menuitem_id) {
                $model_name = $this->setExtensionModelNameMenuitem($this->temp_page_types, $id);
            } else {
                $model_name = $this->setExtensionModelNameDefault($catalog_type_model_name, $alias);
            }

            if ($alias === 'Groups') {
                $this->temp_extensions[$id] = array();
            } else {
                $this->temp_extensions[$id] = $this->getExtension($id, $model_name, $resource_indicator);
            }
        }

        return $this->setExtensions();
    }

    /**
     * Set Extensions Results
     *
     * @return  stdClass
     * @since   1.0.0
     */
    protected function setExtensions()
    {
        $temp             = new stdClass();
        $temp->ids        = $this->temp_ids;
        $temp->names      = $this->temp_names;
        $temp->extensions = $this->temp_extensions;
        $temp->menus      = $this->temp_menus;

        return $temp;
    }

    /**
     * Initialise Extensions
     *
     * @param   array   $items
     * @param   integer $catalog_type_id
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function initialiseExtensions($items, $catalog_type_id)
    {
        $this->temp_ids        = array();
        $this->temp_names      = array();
        $this->temp_extensions = array();
        $this->temp_menus      = array();
        $this->temp_page_types = array();

        foreach ($items as $item) {

            if ($catalog_type_id == $this->runtime_data->reference_data->catalog_type_menuitem_id) {
                $name = $this->initialiseExtensionsMenuItem($item);
            } else {
                $name = $item->alias;
            }

            $this->temp_ids[$item->id] = $name;
            $this->temp_names[$name]   = $item->id;
        }

        $x                = array_unique($this->temp_menus);
        $this->temp_menus = $x;
        ksort($this->temp_ids);

        return $this;
    }

    /**
     * Initialize Extension Name for Menu Item
     *
     * @param   object $item
     *
     * @return  string
     * @since   1.0.0
     */
    protected function initialiseExtensionsMenuItem($item)
    {
        $this->temp_menus[] = $item->menu;

        if ($item->path === '') {
            $name = $item->alias;
        } else {
            $name = $item->path . '/' . $item->alias;
        }

        $this->temp_page_types[$item->id] = $item->page_type;

        return $name;
    }

    /**
     * Retrieve specific Extension Information
     *
     * @param   string $catalog_type_model_name
     *
     * @return  string
     * @since   1.0.0
     */
    protected function setCatalogTypeModelName($catalog_type_model_name)
    {
        $catalog_type_model_name = ucfirst(strtolower($catalog_type_model_name));

        if ($catalog_type_model_name === 'Views//pages') {
            $catalog_type_model_name = 'Views//Pages';
        } elseif ($catalog_type_model_name === 'Views//templates') {
            $catalog_type_model_name = 'Views//Templates';
        } elseif ($catalog_type_model_name === 'Views//wraps') {
            $catalog_type_model_name = 'Views//Wraps';
        }

        return $catalog_type_model_name;
    }

    /**
     * Set Extension Model Name for Menu Item
     *
     * @param   string  $alias
     * @param   array   $extensions
     * @param   integer $id
     *
     * @return  array
     * @since   1.0.0
     */
    protected function setExtensionModelNameResource($alias, $extensions, $id)
    {
        return 'Molajo//' . $alias . '//Extension.xml';
    }

    /**
     * Set Extension Model Name for Menu Item
     *
     * @param   array   $page_types
     * @param   integer $id
     *
     * @return  string
     * @since   1.0.0
     */
    protected function setExtensionModelNameMenuitem($page_types, $id)
    {
        $pagetype = $page_types[$id];
        $pagetype = ucfirst(strtolower($pagetype));

        return 'Molajo//Model//Menuitem//' . $pagetype . '//Configuration.xml';
    }

    /**
     * Set Extension Model Name (Not Resource or Menuitem)
     *
     * @param   string $catalog_type_model_name
     * @param   string $alias
     *
     * @return  string
     * @since   1.0.0
     */
    protected function setExtensionModelNameDefault($catalog_type_model_name, $alias)
    {
        return 'Molajo//' . $catalog_type_model_name . '//' . $alias . '//Configuration.xml';
    }

    /**
     * Retrieve specific Extension Information
     *
     * @param   int    $id
     * @param   string $model_name
     *
     * @return  object
     * @since   1.0.0
     */
    protected function getExtension($id, $model_name)
    {
        $controller     = $this->setExtensionQuery($id, $model_name);
        $data           = $this->runQuery($controller);
        $model_registry = $controller->getModelRegistry('*');

        return $this->processExtension($data, $model_registry);
    }

    /**
     * Retrieve specific Extension Information
     *
     * @param   object $data
     * @param   array  $model_registry
     *
     * @return  object
     * @since   1.0.0
     */
    protected function processExtension($data, array $model_registry)
    {
        $custom_field_types = $model_registry['customfieldgroups'];

        if (is_array($custom_field_types)) {
        } else {
            $custom_field_types = array();
        }

        if (count($custom_field_types) > 0) {
            if (is_array($custom_field_types) && count($custom_field_types) > 0) {
                foreach ($custom_field_types as $group) {
                    $data->$group = $this->processCustomfieldGroup($group, $data, $model_registry);
                }
            }
        }

        return $data;
    }

    /**
     * Process Customfield Group
     *
     * @param   string $group
     * @param   object $data
     * @param   object $model_registry
     *
     * @return  stdClass
     * @since   1.0.0
     */
    protected function processCustomfieldGroup($group, $data, $model_registry)
    {
        $fields = $this->getCustomfields($group, $data, $model_registry);

        $group_fields = new stdClass();

        foreach ($fields as $key => $value) {
            $group_fields->$key = $value;
        }

        return $group_fields;
    }

    /**
     * Get Custom Group Data
     *
     * @param   string $group
     * @param   object $data
     * @param   object $model_registry
     *
     * @return  stdClass
     * @since   1.0.0
     */
    protected function getCustomfields($group, $data, $model_registry)
    {
        $group_data = $this->getCustomfieldGroupData($group, $data);

        $fields = array();

        foreach ($model_registry[$group] as $customfields) {
            $key          = $customfields['name'];
            $fields[$key] = $this->setCustomfieldValue($key, $group_data, $customfields);
        }
        ksort($fields);

        return $fields;
    }

    /**
     * Get Custom Group Data
     *
     * @param   string $group
     * @param   object $data
     *
     * @return  stdClass
     * @since   1.0.0
     */
    protected function getCustomfieldGroupData($group, $data)
    {
        if (isset($data->$group)) {
        } else {
            $group_data = new stdClass();
            return $group_data;
        }

        $group_data = json_decode($data->$group);

        $application_id = $this->runtime_data->application->id;

        if (isset($group_data->$application_id)) {
            $group_data = $group_data->$application_id;
        }

        return $group_data;
    }

    /**
     * Set Custom Field Value
     *
     * @param   string $key
     * @param   object $group_data
     * @param   array  $customfields
     *
     * @return  null|mixed
     * @since   1.0.0
     */
    protected function setCustomfieldValue($key, $group_data, $customfields)
    {
        if (isset($group_data->$key)) {
            return $group_data->$key;
        }

        if (isset($customfields['default'])) {
            return $customfields['default'];
        }

        return null;
    }

    /**
     * Set Catalog Types Query
     *
     * @return  object
     * @since   1.0.0
     */
    public function setCatalogTypesQuery()
    {
        $controller = $this->resource->get(
            'query:///Molajo//Model//Datasource//CatalogTypes.xml',
            array('Runtime_data' => $this->runtime_data)
        );

        $controller->setModelRegistry('check_view_level_access', 0);
        $controller->setModelRegistry('process_events', 0);
        $controller->setModelRegistry('query_object', 'list');
        $controller->setModelRegistry('use_pagination', 0);
        $controller->setModelRegistry('process_events', 0);

        $prefix = $controller->getModelRegistry('prefix', 'a');

        $catalog_id_list = (int)$this->runtime_data->reference_data->catalog_type_plugin_id . ', '
            . (int)$this->runtime_data->reference_data->catalog_type_theme_id . ', '
            . (int)$this->runtime_data->reference_data->catalog_type_page_view_id . ', '
            . (int)$this->runtime_data->reference_data->catalog_type_template_view_id . ', '
            . (int)$this->runtime_data->reference_data->catalog_type_wrap_view_id . ', '
            . (int)$this->runtime_data->reference_data->catalog_type_menuitem_id . ', '
            . (int)$this->runtime_data->reference_data->catalog_type_resource_id;

        $controller->select('*');
        $controller->from('#__catalog_types', 'a');
        $controller->where('column', $prefix . '.id', 'IN', 'integer', $catalog_id_list, 'OR');
        $controller->where('column', $prefix . '.model_type', '=', 'string', 'Resource', 'OR');

        return $controller;
    }

    /**
     * Retrieve System Extensions for a specific Catalog Type
     *
     * @param   int $catalog_type_id
     *
     * @return  array|stdClass
     * @since   1.0.0
     */
    protected function setExtensionsQuery($catalog_type_id)
    {
        $controller
            = $this->resource->get(
            'query:///Molajo//Model//Datasource//ExtensionInstances.xml',
            array('Runtimedata' => $this->runtime_data)
        );

        $application_id = $this->runtime_data->application->id;
        $site_id        = $this->runtime_data->site->id;

        $controller->setModelRegistry('application_id', $application_id);
        $controller->setModelRegistry('site_id', $site_id);
        $controller->setModelRegistry('check_view_level_access', 0);
        $controller->setModelRegistry('process_events', 0);
        $controller->setModelRegistry('get_customfields', 0);
        $controller->setModelRegistry('use_special_joins', 1);
        $controller->setModelRegistry('query_object', 'list');
        $controller->setModelRegistry('use_pagination', 0);

        $prefix = $controller->getModelRegistry('primary_prefix', 'a');
        $cat_id = $prefix . '.' . 'catalog_type_id';

        $controller->select($prefix . '.' . 'id');
        $controller->select($prefix . '.' . 'alias');
        $controller->select($prefix . '.' . 'menu');
        $controller->select($prefix . '.' . 'path');
        $controller->select($prefix . '.' . 'page_type');

        $controller->where('column', $cat_id, '=', 'integer', $catalog_type_id);
        $controller->where('column', $prefix . '.' . 'id', '<>', 'column', $cat_id);
        $controller->where('column', $prefix . '.' . 'status', '>', 'integer', ' 0 ');

        $controller->orderBy($prefix . '.' . 'alias');

        return $controller;
    }

    /**
     * Set Extension Query
     *
     * @param   int    $id
     * @param   string $model_name
     *
     * @return  object
     * @since   1.0.0
     */
    protected function setExtensionQuery($id, $model_name)
    {
        $controller = $this->resource->get(
            'query:///' . $model_name,
            array('Runtimedata' => $this->runtime_data)
        );

        $controller->setModelRegistry('check_view_level_access', 0);
        $controller->setModelRegistry('process_events', 0);
        $controller->setModelRegistry('get_customfields', 1);
        $controller->setModelRegistry('primary_key_value', $id);
        $controller->setModelRegistry('query_object', 'item');

        $application_id = $this->runtime_data->application->id;
        $site_id        = $this->runtime_data->site->id;

        $controller->setModelRegistry('application_id', $application_id);
        $controller->setModelRegistry('site_id', $site_id);
        $prefix = $controller->getModelRegistry('primary_prefix', 'a');

        $controller->where('column', $prefix . '.' . 'id', '=', 'integer', (int)$id);

        return $controller;
    }

    /**
     * Run Query
     *
     * @param   object $controller
     *
     * @return  mixed
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     *
     */
    protected function runQuery($controller)
    {
        try {
            return $controller->getData();

        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}
