<?php
/**
 * Content Helper
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Helper;

use Molajo\Helper\ExtensionHelper;
use Molajo\Helper\ThemeHelper;
use Molajo\Helper\ViewHelper;

/**
 * Retrieves Item, List, and Menu Item Parameters for Route and other useful data
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class ContentHelper
{
    /**
     * Extension Helper
     *
     * @var    object  Molajo\Helper\Extension
     * @since  1.0
     */
    protected $extension_helper;

    /**
     * Theme Helper
     *
     * @var    object  Molajo\Helper\Theme
     * @since  1.0
     */
    protected $theme_helper;

    /**
     * View Helper
     *
     * @var    object  Molajo\Helper\View
     * @since  1.0
     */
    protected $view_helper;

    /**
     * Parameters for rendering the page
     *
     * @var    $parameters
     * @since  1.0
     */
    protected $parameters;

    /**
     * List of valid properties for parameters
     *
     * @var    array
     * @since  1.0
     */
    protected $parameter_property_array = array();

    /**
     * Profiler Instance
     *
     * @var    object    Molajo\Profiler\Profiler
     * @since  1.0
     */
    protected $profiler_instance;

    /**
     * List of Property Array
     *
     * @var    array
     * @since  1.0
     */
    protected $property_array = array(
        'extension_helper',
        'theme_helper',
        'view_helper',
        'parameters',
        'parameter_property_array',
        'profiler_instance'
    );

    /**
     * Constructor
     *
     * @param  ExtensionHelperInterface $extension_helper
     * @param  ThemeHelperInterface     $theme_helper
     * @param  ViewHelperInterface      $view_helper
     * @param  array                    $parameters
     * @param  array                    $parameter_property_array
     * @param  ProfilerInterface        $profiler_instance
     *
     * @since  1.0
     */
    public function __construct(
        ExtensionHelperInterface $extension_helper,
        ThemeHelperInterface $theme_helper,
        ViewHelperInterface $view_helper,
        array $parameters = array(),
        array $parameter_property_array = array(),
        ProfilerInterface $profiler_instance
    ) {
        foreach ($this->property_array as $property) {
            $this->$property = $property;
        }
    }

    /**
     * Get the current value (or default) of the specified key
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     */
    protected function get($key = null, $default = null)
    {
        $key = strtolower($key);

        if (isset($this->parameters->$key)) {
            return $this->parameters->$key;
        }

        $this->parameters->$key = $default;

        return $this->parameters->$key;
    }

    /**
     * Set the value of a specified key
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  mixed
     * @since   1.0
     */
    protected function set($key, $value = null)
    {
        $key = strtolower($key);

        $this->parameters->$key = $value;

        return $this->parameters->$key;
    }

    /**
     * Set Parameters for Item Page Handler
     *
     * @return  array
     * @since   1.0
     */
    public function getRouteItem()
    {
        $id         = (int)$this->get('catalog_source_id');
        $model_type = $this->get('catalog_model_type');
        $model_name = $this->get('catalog_model_name');

        $item = $this->getData($id, $model_type, $model_name);

        if (count($item) == 0) {
            return $this->set('route_found', false);
        }

        if (isset($item->extension_instance_id)) {
            $extension_instance_id              = (int)$item->extension_instance_id;
            $extension_instance_catalog_type_id = (int)$item->catalog_catalog_type_id;
        } else {
            $extension_instance_id              = (int)$item->catalog_extension_instance_id;
            $extension_instance_catalog_type_id = (int)$item->catalog_catalog_type_id;
        }

        $this->set('extension_instance_id', $extension_instance_id);
        $this->set('extension_catalog_type_id', $extension_instance_catalog_type_id);
        $this->set('criteria_extension_instance_id', (int)$extension_instance_id);
        $this->set('criteria_source_id', (int)$item->id);
        $this->set('page_type', 'item');
        $this->set('criteria_catalog_type_id', (int)$item->catalog_type_id);

        $this->getResourceExtensionParameters((int)$extension_instance_id);

        if (strtolower($this->get('request_action')) == 'read') {
            $page_type_namespace = 'item';
        } else {
            $page_type_namespace = 'form';
        }

        if ($this->get('catalog_model_type') == 'Resource') {
            $resource_or_system = 'Resource';
        } else {
            $resource_or_system = 'System';
        }

        $this->set('extension_name_path_node', $this->get('catalog_model_name'));
        $this->set('model_registry_name', $item->model_registry_name);

        $this->setParameters(
            $page_type_namespace,
            $item->model_registry_name . 'Parameters',
            $item->model_registry_name . 'Metadata',
            'ResourcesSystem',
            $resource_or_system
        );

        $customfields = $this->registry->get($item->model_registry_name . 'Customfields');

        if (is_array($customfields) && count($customfields) > 0) {
            foreach ($customfields as $key => $value) {
                if ($value === 0 || trim($value) == '' || $value === null) {
                } else {
                    $item->$key = $value;
                }
            }
        }

        $parent_menu_id = $this->registry->get(
            'ResourcesSystemParameters',
            $page_type_namespace . '_parent_menu_id'
        );

        $this->registry->set('Primary', 'Data', array($item));

        $this->set('parent_menu_id', $parent_menu_id);

        if ($page_type_namespace == 'form') {
            $this->set('page_type', PAGE_TYPE_EDIT);
        }

        $parameters = $this->parameters;
        ksort($parameters);

        $this->parameter_property_array = array();
        foreach ($parameters as $key => $value) {
            $this->parameter_property_array[] = $key;
        }
        $parameter_property_array = $this->parameter_property_array;

        return array($parameters, $parameter_property_array);
    }

    /**
     * Set Parameters for List Page Handler
     *
     * @return  array
     * @since   1.0
     */
    public function getRouteList()
    {
        $id         = (int)$this->get('catalog_id');
        $model_type = $this->get('catalog_model_type');
        $model_name = $this->get('catalog_model_name');

        $item = $this->getData($id, $model_type, $model_name, 'item');

        if (count($item) == 0) {
            return $this->set('route_found', false);
        }

        $this->set('extension_instance_id', (int)$item->id);
        $this->set('extension_title', $item->title);
        $this->set('extension_translation_of_id', (int)$item->translation_of_id);
        $this->set('extension_language', $item->language);
        $this->set('extension_catalog_type_id', (int)$item->catalog_type_id);
        $this->set('extension_modified_datetime', $item->modified_datetime);
        $this->set('extension_catalog_type_title', $item->catalog_types_title);
        $this->set('catalog_type_id', $item->catalog_type_id);
        $this->set('page_type', 'list');
        $this->set('primary_category_id', $item->catalog_primary_category_id);
        $this->set('source_id', (int)$item->id);

        if ($this->get('catalog_model_type') == 'Resource') {
            $resource_or_system = 'Resource';
        } else {
            $resource_or_system = 'System';
        }

        $this->set('extension_name_path_node', $this->get('catalog_model_name'));

        $this->setParameters(
            'list',
            $item->model_registry_name . 'Parameters',
            $item->model_registry_name . 'Metadata',
            null,
            $resource_or_system
        );

        $parameters = $this->parameters;
        ksort($parameters);

        $parameter_property_array = $this->parameter_property_array;
        ksort($parameter_property_array);

        return array($parameters, $parameter_property_array);
    }

    /**
     * Set Parameters for Menu Item Page Handler
     *
     * @return  array
     * @since   1.0
     */
    public function getRouteMenuitem()
    {
        $id         = (int)$this->get('catalog_source_id');
        $model_type = CATALOG_TYPE_MENUITEM_LITERAL;
        $model_name = $this->get('catalog_page_type');

        $item = $this->getData($id, $model_type, $model_name);

        if (count($item) == 0) {
            return $this->set('route_found', false);
        }

        $this->set('menuitem_lvl', (int)$item->lvl);
        $this->set('menuitem_title', $item->title);
        $this->set('menuitem_parent_id', $item->parent_id);
        $this->set('menuitem_translation_of_id', (int)$item->translation_of_id);
        $this->set('menuitem_language', $item->language);
        $this->set('menuitem_catalog_type_id', (int)$item->catalog_type_id);
        $this->set('menuitem_catalog_type_title', $item->catalog_types_title);
        $this->set('menuitem_modified_datetime', $item->modified_datetime);
        $this->set('menu_id', (int)$item->extension_id);
        $this->set('menu_title', $item->extensions_name);
        $this->set('menu_extension_id', (int)$item->extensions_id);
        $this->set('menu_path_node', $item->extensions_name);

        $page_type = $this->get('catalog_page_type');
        $this->set('page_type', $page_type);

        $registry = $page_type . CATALOG_TYPE_MENUITEM_LITERAL;

        $parameters = $this->registry->copy($registry . 'Parameters');
        if (count($parameters) > 0) {
            foreach ($parameters as $key => $value) {
                if (in_array($key, $this->parameter_property_array)) {
                } else {
                    $this->parameter_property_array[] = $key;
                }
                $this->set($key, $value);
            }
        }

        $metadata = $this->registry->get($registry . 'Metadata');
        if (count($metadata) > 0) {
            foreach ($metadata as $key => $value) {
                $this->document_metadata->set($key, array($value));
            }
        }

        if ($this->get('catalog_model_type') == 'Resource') {
            $resource_or_system = 'Resource';
        } else {
            $resource_or_system = 'System';
        }

        $this->set('extension_name_path_node', $this->get('catalog_model_name'));

        $this->setParameters(
            strtolower(CATALOG_TYPE_MENUITEM_LITERAL),
            $registry . 'Parameters',
            $registry . 'Metadata',
            null,
            $resource_or_system
        );

        /** Must be after parameter set so as to not strip off menuitem */
        $this->set('menuitem_id', (int)$item->id);
        $this->set('page_type', $this->get('catalog_page_type'));

        /** Retrieve Model Registry for Resource */
        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry(
            $this->get('catalog_model_type'),
            $this->get('catalog_model_name'),
            0
        );

        $parameters = $this->parameters;
        ksort($parameters);
        $parameter_property_array = $this->parameter_property_array;
        ksort($parameter_property_array);

        return array($parameters, $parameter_property_array);
    }

    /**
     * Get data for Menu Item, Item or List
     *
     * @param   $id
     * @param   $model_type
     * @param   $model_name
     *
     * @return  array
     * @since   1.0
     */
    public function getData($id = 0, $model_type = 'datasource', $model_name = 'Content')
    {
        $this->profiler_instance->set(
            'message',
            'ContentHelper get ' . ' ID: ' . $id . ' Model Handler: ' . $model_type
            . ' Model Name: ' . $model_name,
            'Routing',
            1
        );

        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry($model_type, $model_name, 1);

        $controller->set('primary_key_value', (int)$id, 'model_registry');
        $controller->set('process_plugins', 1, 'model_registry');
        $controller->set('get_customfields', 1, 'model_registry');

        $item = $controller->getData('item');

        if ($item === false || $item === null || count($item) == 0) {
            return array();
        }

        $item->model_registry_name = $controller->get('model_registry_name');

        return $item;
    }

    /**
     * Determines parameter values from primary item (form, item, list, or menuitem)
     *  Extension and Application defaults applied following item values
     *
     * @param string $page_type_namespace (ex. item, list, menuitem)
     * @param string $parameter_namespace (ex. $item->model_registry_name . 'Parameters')
     * @param string $metadata_namespace  (ex. $item->model_registry_name . 'Metadata')
     * @param string $resource_namespace  for extension (ex. ResourcesSystem)
     * @param string $resource_or_system  for extension (values 'resource' or 'system')
     *
     * @return  boolean
     * @since   1.0
     */
    public function setParameters(
        $page_type_namespace,
        $parameter_namespace,
        $metadata_namespace,
        $resource_namespace = null,
        $resource_or_system = 'resource'
    ) {
        $this->set('page_type', $page_type_namespace);

        /** I. Priority 1 - Item level (Item, List, Menu Item) */
        $parameter_set = $this->registry->get($parameter_namespace, $page_type_namespace . '*');
        if (is_array($parameter_set) && count($parameter_set) > 0) {
            $this->processParameterSet($parameter_set, $page_type_namespace);
        }

        $parameter_set = $this->registry->get($parameter_namespace, 'criteria*');
        if (is_array($parameter_set) && count($parameter_set) > 0) {
            $this->processParameterSet($parameter_set, $page_type_namespace);
        }

        $parameter_set = $this->registry->get($parameter_namespace, 'enable*');
        if (is_array($parameter_set) && count($parameter_set) > 0) {
            $this->processParameterSet($parameter_set, $page_type_namespace);
        }

        /** II. Priority 2 - Extension level defaults */
        if ($resource_namespace === null) {
        } else {

            $parameter_set = $this->registry->get(
                $resource_namespace . 'Parameters',
                $page_type_namespace . '*'
            );

            if (is_array($parameter_set) && count($parameter_set) > 0) {
                $this->processParameterSet($parameter_set, $page_type_namespace);
            }

            $parameter_set = $this->registry->get($resource_namespace . 'Parameters', 'criteria*');
            if (is_array($parameter_set) && count($parameter_set) > 0) {
                $this->processParameterSet($parameter_set, $page_type_namespace);
            }

            $parameter_set = $this->registry->get($resource_namespace . 'Parameters', 'enable*');
            if (is_array($parameter_set) && count($parameter_set) > 0) {
                $this->processParameterSet($parameter_set, $page_type_namespace);
            }
        }

        /** III. Finally, Application level defaults */
        $applicationDefaults = $this->application->get($page_type_namespace . '*');
        if (count($applicationDefaults) > 0) {
            $this->processParameterSet($applicationDefaults, $page_type_namespace);
        }

        /** Merge in the rest */
        $random = 'r' . mt_rand(10000, 60000000);
        $this->registry->createRegistry($random);
        $this->registry->loadArray($random, $this->parameters);
        $this->registry->merge($parameter_namespace, $random);

        /** Set Theme and View values */
        $this->theme_helper->get((int)$this->get('theme_id'), $random);
        $this->view_helper->get((int)$this->get('page_view_id'), CATALOG_TYPE_PAGE_VIEW_LITERAL, $random);
        $this->view_helper->get((int)$this->get('template_view_id'), CATALOG_TYPE_TEMPLATE_VIEW_LITERAL, $random);
        $this->view_helper->get((int)$this->get('wrap_view_id'), CATALOG_TYPE_WRAP_VIEW_LITERAL, $random);

        $this->set(
            'extension_path',
            $this->extension_helper->getPath(
                $resource_or_system,
                $this->get('extension_name_path_node'),
                $random
            )
        );

        $this->set(
            'extension_path_url',
            $this->extension_helper->getPathURL(
                $resource_or_system,
                $this->get('extension_name_path_node'),
                $random
            )
        );

        $this->set(
            'extension_namespace',
            $this->extension_helper->getNamespace(
                $resource_or_system,
                $this->get('extension_name_path_node'),
                $random
            )
        );

        /** Metadata defaulting */
        $this->registry->merge($metadata_namespace, 'Metadata');
        if ($resource_namespace == '') {
        } else {
            $this->registry->merge($resource_namespace . 'Metadata', 'Metadata', true);
        }

        /** Remove standard patterns no longer needed  */
        $this->registry->delete($random, 'list*');

        $this->registry->delete($random, 'form*');
        $this->registry->delete($random, 'menuitem*');
        $this->registry->delete($random, 'item*');

        /** Copy some configuration data */
        $fields = $this->application->get('application*');
        if (count($fields) === 0 || $fields === false) {
        } else {
            foreach ($fields as $key => $value) {
                $this->registry->set($random, $key, $value);
            }
        }

        $this->registry->sort($random);
        $this->parameters = $this->registry->get($random);
        $this->registry->deleteRegistry($random);

        $property = $this->parameter_property_array;
        sort($property);
        $this->parameter_property_array = $property;

        return true;
    }

    /**
     * Iterates parameter set to determine whether or not value should be applied
     *
     * @param   $parameter_set
     * @param   $page_type_namespace
     *
     * @return void
     * @since   1.0
     */
    protected function processParameterSet($parameter_set, $page_type_namespace)
    {
        foreach ($parameter_set as $key => $value) {

            $copy_from = $key;

            if (substr($key, 0, strlen($page_type_namespace)) == $page_type_namespace) {
                $copy_to = substr($key, strlen($page_type_namespace) + 1, 9999);
            } else {
                $copy_to = $key;
            }

            $existing = $this->get($copy_to);

            if ($existing === 0 || trim($existing) == '' || $existing === null || $existing === false) {
                if ($value === 0 || trim($value) == '' || $value === null) {
                } else {
                    $this->set($copy_to, $value);
                }
            }
        }
    }

    /**
     * Get Category Handler information for Resource
     *
     * @param   $id
     *
     * @return array An object containing an array of basic resource info, parameters in registry
     * @since   1.0
     */
    public function getResourceCatalogHandler($id = 0)
    {
        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry('datasource', 'CatalogHandlers', 1);

        $controller->set('process_plugins', 0, 'model_registry');
        $controller->set('get_customfields', 0, 'model_registry');
        $prefix = $controller->get('primary_prefix', 'a', 'model_registry');

        $controller->model->query->where(
            $controller->model->database->qn($prefix)
            . '.'
            . $controller->model->database->qn('extension_instance_id')
            . ' = '
            . (int)$id
        );

        $item = $controller->getData('item');

        if (count($item) == 0) {
            return array();
        }

        return $item;
    }

    /**
     * Get Parameter and Custom Fields for Resource Content (no data, just field definitions)
     *
     * Populates these registries (ex. Model Handler Resource and Model Name Articles):
     *      Model => $this->registry->get('ArticlesResource', '*');
     *      Parameter Fields => $this->registry->get('ArticlesResource', 'Parameters')
     *
     * @param string $model_type
     * @param string $model_name
     *
     * @return array An object containing an array of basic resource info, parameters in registry
     * @since   1.0
     */
    public function getResourceContentParameters($model_type = 'Resource', $model_name)
    {
        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry($model_type, $model_name, 0);

        $controller->set('process_plugins', 0, 'model_registry');
        $controller->set('get_customfields', 1, 'model_registry');

        return $controller->setDataobject();
    }

    /**
     * Get Parameters for Resource
     *
     * Usage:
     *  $this->content_helper->getResourceExtensionParameters($extension_instance_id);
     *
     * Populates these registries:
     *      Model => $this->registry->get('ResourcesSystem', '*');
     *      Parameters => $this->registry->get('ResourcesSystemParameters', '*');
     *
     * @param   $id Resource Extension
     *
     * @return array An object containing an array of basic resource info, parameters in registry
     * @since   1.0
     */
    public function getResourceExtensionParameters($id = 0)
    {
        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry('System', 'Resources', 1);

        $controller->set('primary_key_value', (int)$id, 'model_registry');
        $controller->set('process_plugins', 0, 'model_registry');
        $controller->set('get_customfields', 1, 'model_registry');
        $controller->set('check_view_level_access', 0, 'model_registry');

        return $controller->getData('item');
    }

    /**
     * Get Menuitem Content Parameters for Resource
     *
     * Usage:
     *  $this->content_helper->getResourceMenuitemParameters(PAGE_TYPE_GRID, $extension_instance_id);
     *
     * Populates this registry:
     * If the menuitem is found, parameters can be accessed using the 'Menuitemtype' + 'MenuitemParameters' registry
     *      Parameters => $this->registry->get('GridMenuitemParameters', '*');
     *
     * @param string $page_type
     * @param string $extension_instance_id
     *
     * @return mixed false, or an object containing an array of basic resource info, parameters in registry
     * @since   1.0
     */
    public function getResourceMenuitemParameters($page_type, $extension_instance_id)
    {
        $page_type = ucfirst(strtolower($page_type));

        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry(CATALOG_TYPE_MENUITEM_LITERAL, $page_type, 1);

        $prefix = $controller->get('primary_prefix', 'a', 'model_registry');

        $controller->model->query->where(
            $controller->model->database->qn($prefix)
            . '.'
            . $controller->model->database->qn('page_type')
            . ' = '
            . $controller->model->database->q($page_type)
        );

        $controller->model->query->where(
            $controller->model->database->qn($prefix)
            . '.'
            . $controller->model->database->qn('catalog_type_id')
            . ' = '
            . (int)CATALOG_TYPE_MENUITEM
        );

        $value = '"criteria_extension_instance_id":"' . $extension_instance_id . '"';
        $controller->model->query->where(
            $controller->model->database->qn($prefix)
            . '.'
            . $controller->model->database->qn('Parameters')
            . ' = '
            . $controller->model->database->q('%' . $value . '%')
        );

        $menuitem = $controller->getData('item');
        if ($menuitem === false || $menuitem === null || count($menuitem) == 0) {
            return false;
        }

        $menuitem->table_registry = $page_type . CATALOG_TYPE_MENUITEM_LITERAL;

        return $menuitem;
    }
}
