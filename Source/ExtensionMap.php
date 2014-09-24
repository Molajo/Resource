<?php
/**
 * Extension Map
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
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
 * @copyright  2014 Amy Stephen. All rights reserved.
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
     * @since   1.0
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
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function getCatalogTypes()
    {
        $catalog_types = $this->resource->get(
            'query:///Molajo//Model//Datasource//CatalogTypes.xml',
            array('Parameters' => $this->runtime_data)
        );

        $catalog_types->setModelRegistry('check_view_level_access', 0);
        $catalog_types->setModelRegistry('process_events', 0);
        $catalog_types->setModelRegistry('query_object', 'list');
        $catalog_types->setModelRegistry('use_pagination', 0);
        $catalog_types->setModelRegistry('process_events', 0);

        $prefix = $catalog_types->getModelRegistry('prefix', 'a');

        try {
            $catalog_types->select('*');
            $catalog_types->from('#__catalog_types', 'a');
            $catalog_types->where(
                'column',
                $prefix . '.id',
                'IN',
                'integer',
                (int)$this->runtime_data->reference_data->catalog_type_plugin_id . ', '
                . (int)$this->runtime_data->reference_data->catalog_type_theme_id . ', '
                . (int)$this->runtime_data->reference_data->catalog_type_page_view_id . ', '
                . (int)$this->runtime_data->reference_data->catalog_type_template_view_id . ', '
                . (int)$this->runtime_data->reference_data->catalog_type_wrap_view_id . ', '
                . (int)$this->runtime_data->reference_data->catalog_type_menuitem_id . ', '
                . (int)$this->runtime_data->reference_data->catalog_type_resource_id,
                'OR'
            );
            $catalog_types->where(
                'column',
                $prefix . '.model_type',
                '=',
                'string',
                'Resource',
                'OR'
            );

            $results = $catalog_types->getData();
        } catch (Exception $e) {
            throw new RuntimeException(
                'ExtensionMap:getCatalogTypes Query Failed' . $e->getMessage()
            );
        }

        $names       = array();
        $ids         = array();
        $model_names = array();
        $extensions  = array();

        foreach ($results as $catalog_item) {

            $ids[$catalog_item->id]         = $catalog_item->title;
            $names[$catalog_item->title]    = $catalog_item->id;
            $model_names[$catalog_item->id] = $catalog_item->model_name;

            $id    = $catalog_item->id;
            $model = $model_names[$id];

            $extensions[$id] = $this->getCatalogExtensions($id, $model, $catalog_item->model_type);
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
     * @param   string $model_type
     *
     * @return  array|stdClass
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function getCatalogExtensions($catalog_type_id, $catalog_type_model_name, $model_type)
    {
        $items = $this->getCatalogSystemExtensions($catalog_type_id);

        if (is_array($items) && count($items) > 0) {
        } else {
            return array();
        }

        $ids        = array();
        $names      = array();
        $extensions = array();
        $menus      = array();
        $pagetypes  = array();

        foreach ($items as $item) {

            if ($catalog_type_id == $this->runtime_data->reference_data->catalog_type_menuitem_id) {

                $menus[] = $item->menu;

                if ($item->path === '') {
                    $name = $item->alias;
                } else {
                    $name = $item->path . '/' . $item->alias;
                }

                $pagetypes[$item->id] = $item->page_type;
            } else {
                $name = $item->alias;
            }

            $ids[$item->id] = $name;

            $names[$name] = $item->id;
        }

        $x     = array_unique($menus);
        $menus = $x;
        ksort($ids);

        $catalog_type_model_name = ucfirst(strtolower($catalog_type_model_name));

        if ($catalog_type_model_name === 'Views//pages') {
            $catalog_type_model_name = 'Views//Pages';
        } elseif ($catalog_type_model_name === 'Views//templates') {
            $catalog_type_model_name = 'Views//Templates';
        } elseif ($catalog_type_model_name === 'Views//wraps') {
            $catalog_type_model_name = 'Views//Wraps';
        }

        foreach ($ids as $id => $alias) {
            $alias = ucfirst(strtolower($alias));

            if ($catalog_type_model_name === 'Resources' || $catalog_type_model_name === 'System') {

                $model_name         = 'Molajo//' . $alias . '//Extension.xml';
                $resource_indicator = true;

                if ($alias === 'Groups') {
                    $extensions[$id] = array();
                } else {
                    $extensions[$id] = $this->getExtension($id, $model_name, $resource_indicator);
                }
            } else {

                if ($catalog_type_id == $this->runtime_data->reference_data->catalog_type_menuitem_id) {
                    $pagetype   = $pagetypes[$id];
                    $pagetype   = ucfirst(strtolower($pagetype));
                    $model_name = 'Molajo//Model//Menuitem//' . $pagetype . '//Configuration.xml';
                } else {
                    $model_name = 'Molajo//' . $catalog_type_model_name . '//' . $alias . '//Configuration.xml';
                }

                $resource_indicator = false;

                $extensions[$id] = $this->getExtension($id, $model_name, $resource_indicator);
            }
        }

        $temp             = new stdClass();
        $temp->ids        = $ids;
        $temp->names      = $names;
        $temp->extensions = $extensions;
        $temp->menus      = $menus;

        return $temp;
    }

    /**
     * Retrieve Resource Extensions for a specific Catalog Type
     *
     * @param   int $catalog_type_id
     *
     * @return  array|stdClass
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function getCatalogResourceExtensions($catalog_type_id)
    {
        $extensions_controller
            = $this->resource->get(
            'query:///Molajo//Model//Datasource//ExtensionInstances.xml',
            array('Runtimedata' => $this->runtime_data)
        );

        $extensions_controller->setModelRegistry('check_view_level_access', 0);
        $extensions_controller->setModelRegistry('process_events', 0);
        $extensions_controller->setModelRegistry('get_customfields', 0);
        $extensions_controller->setModelRegistry('use_special_joins', 0);
        $extensions_controller->setModelRegistry('query_object', 'list');
        $extensions_controller->setModelRegistry('use_pagination', 0);

        $extensions_controller->select(
            $extensions_controller->getModelRegistry('primary_prefix', 'a')
            . '.'
            . 'id'
        );

        $extensions_controller->select(
            $extensions_controller->getModelRegistry('primary_prefix', 'a')
            . '.'
            . 'alias'
        );

        $extensions_controller->where(
            'column',
            $extensions_controller->getModelRegistry('primary_prefix', 'a') . '.' . 'id',
            ' = ',
            'integer',
            (int)$catalog_type_id
        );

        $extensions_controller->where(
            $extensions_controller->getModelRegistry('primary_prefix', 'a') . '.' . 'status',
            ' > ',
            'integer',
            ' 0 '
        );

        $extensions_controller->order(
            $extensions_controller->getModelRegistry('primary_prefix', 'a') . '.' . 'alias',
            'ASC'
        );

        try {
            return $extensions_controller->getData();
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Retrieve System Extensions for a specific Catalog Type
     *
     * @param   int $catalog_type_id
     *
     * @return  array|stdClass
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function getCatalogSystemExtensions($catalog_type_id)
    {
        $extensions_controller
            = $this->resource->get(
            'query:///Molajo//Model//Datasource//ExtensionInstances.xml',
            array('Runtimedata' => $this->runtime_data)
        );

        $application_id = $this->runtime_data->application->id;
        $extensions_controller->setModelRegistry('application_id', $application_id);
        $site_id = $this->runtime_data->site->id;
        $extensions_controller->setModelRegistry('site_id', $site_id);

        $extensions_controller->setModelRegistry('check_view_level_access', 0);
        $extensions_controller->setModelRegistry('process_events', 0);
        $extensions_controller->setModelRegistry('get_customfields', 0);
        $extensions_controller->setModelRegistry('use_special_joins', 1);
        $extensions_controller->setModelRegistry('query_object', 'list');
        $extensions_controller->setModelRegistry('use_pagination', 0);

        $extensions_controller->select(
            $extensions_controller->getModelRegistry('primary_prefix', 'a')
            . '.'
            . 'id'
        );

        $extensions_controller->select(
            $extensions_controller->getModelRegistry('primary_prefix', 'a')
            . '.'
            . 'alias'
        );

        $extensions_controller->select(
            $extensions_controller->getModelRegistry('primary_prefix', 'a')
            . '.'
            . 'menu'
        );

        $extensions_controller->select(
            $extensions_controller->getModelRegistry('primary_prefix', 'a')
            . '.'
            . 'path'
        );

        $extensions_controller->select(
            $extensions_controller->getModelRegistry('primary_prefix', 'a')
            . '.'
            . 'page_type'
        );

        $extensions_controller->where(
            'column',
            $extensions_controller->getModelRegistry('primary_prefix', 'a') . '.' . 'catalog_type_id',
            '=',
            'integer',
            (int)$catalog_type_id
        );

        $extensions_controller->where(
            'column',
            $extensions_controller->getModelRegistry('primary_prefix', 'a') . '.' . 'id',
            '<>',
            'column',
            $extensions_controller->getModelRegistry('primary_prefix', 'a') . '.' . 'catalog_type_id'
        );

        $extensions_controller->where(
            'column',
            $extensions_controller->getModelRegistry('primary_prefix', 'a') . '.' . 'status',
            '>',
            'integer',
            ' 0 '
        );

        $extensions_controller->orderBy(
            $extensions_controller->getModelRegistry('primary_prefix', 'a')
            . '.'
            . 'alias'
        );

        try {
            return $extensions_controller->getData();
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Retrieve specific Extension Information
     *
     * @param   int    $id
     * @param   string $model_name
     * @param   bool   $resource_indicator
     *
     * @return  object
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function getExtension($id, $model_name, $resource_indicator = false)
    {
        $item_resource = $this->resource->get(
            'query:///' . $model_name,
            array('Runtimedata' => $this->runtime_data)
        );

        $item_resource->setModelRegistry('check_view_level_access', 0);
        $item_resource->setModelRegistry('process_events', 0);
        $item_resource->setModelRegistry('get_customfields', 1);
        $item_resource->setModelRegistry('primary_key_value', $id);
        $item_resource->setModelRegistry('query_object', 'item');

        $application_id = $this->runtime_data->application->id;
        $item_resource->setModelRegistry('application_id', $application_id);
        $site_id = $this->runtime_data->site->id;
        $item_resource->setModelRegistry('site_id', $site_id);

        $item_resource->where(
            'column',
            $item_resource->getModelRegistry('primary_prefix', 'a') . '.' . 'id',
            '=',
            'integer',
            (int)$id
        );

        try {
            $data = $item_resource->getData();
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }

        $model_registry = $item_resource->getModelRegistry('*');

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
     * @since   1.0
     */
    protected function processCustomfieldGroup($group, $data, $model_registry)
    {
        if (isset($data->$group)) {
            $group_data = json_decode($data->$group);
        } else {
            $group_data = new stdClass();
        }

        if (isset($this->runtime_data->application->id)) {
            $application_id = $this->runtime_data->application->id;
            if (isset($group_data->$application_id)) {
                $group_data = $group_data->$application_id;
            }
        }

        $temp = array();

        foreach ($model_registry[$group] as $customfields) {

            $key = $customfields['name'];

            $value = null;

            if (isset($group_data->$key)) {
                $value = $group_data->$key;
            }

            if ($value === null || $value === '' || $value === ' ') {

                if (isset($customfields['default'])) {
                    $default = $customfields['default'];
                } else {
                    $default = false;
                }

                $value = $default;
            }

            $temp[$key] = $value;
        }

        ksort($temp);

        $group_name = new stdClass();
        foreach ($temp as $key => $value) {
            $group_name->$key = $value;
        }

        return $group_name;
    }
}
