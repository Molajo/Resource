<?php
/**
 * Extension Helper
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Helper;

//todo fix THEME_URL and VIEW_URL

/**
 * Extension Helper provides an interface to different types of extension information, like:
 *
 * - generating a list of extensions for which the site visitor is authorised to view;
 * - returning a list of language strings to be for site interface translation;
 * - query for a specific extension, be it a resource, view, language, or theme, etc.
 * - determine the path, URL path, or namespace for an extension
 * - return the path to the Favicon
 * - translate the catalog type id to text, and visa versa
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class ExtensionHelper
{
    /**
     * Get Service
     *
     * @var     string
     * @since   1.0
     */
    protected $getService;

    /**
     * Registry
     *
     * @var     array
     * @since   1.0
     */
    protected $registry = array();

    /**
     * List of Properties
     *
     * @var    object
     * @since  1.0
     */
    protected $property_array = array(
        'getService',
        'registry'
    );

    /**
     * Constructor
     *
     * @param  array $options
     *
     * @since  1.0
     */
    public function __construct(array $options = array())
    {
        if (is_array($options)) {
        } else {
            $options = array();
        }

        if (count($options) > 0) {
            foreach ($this->property_array as $property) {
                if (isset($options[$property])) {
                    $this->$property = $options[$property];
                }

            }
        }
    }

    /**
     * Primary interface to extension information enabling queries for a single extension,
     * all extensions the site visitor is authorised to view, or all extensions of a specific catalog
     * type, etc.
     *
     * Usage:
     *
     * To retrieve a specific Extension Instance
     *  $this->extension_helper = new ExtensionHelper();
     *  $object = $this->extension_helper->get($extension_instance_id);
     *
     * To retrieve a list of Extension Instances for a specific Catalog Handler
     *  $this->extension_helper = new ExtensionHelper();
     *  $object = $this->extension_helper->get(0, $catalog_type);
     *
     * To override the default Extension Instance Model Registry
     *  $this->extension_helper = new ExtensionHelper();
     *  $object = $this->extension_helper->get($extension_instance_id, null, $model_type, $model_name);
     *
     * @param   string $extension_instance_id
     * @param   string $catalog_type
     * @param   string $model_type
     * @param   string $model_name
     * @param   string $check_permissions
     *
     * @return  bool
     * @since   1.0
     */
    public function get(
        $extension_instance_id = 0,
        $catalog_type = null,
        $model_type = null,
        $model_name = null,
        $check_permissions = null
    ) {
        if ((int)$catalog_type === 0 && (trim($catalog_type) == '' || is_null($catalog_type))) {
            $catalog_type_id = 0;

        } elseif (is_numeric($catalog_type)) {
            $catalog_type_id = $catalog_type;

        } else {
            $catalog_type_id = $this->getHandler(0, $catalog_type);
        }

        if (is_null($model_type)) {
            $model_type = 'Datasource';
        }
        if (is_null($model_name)) {
            $model_name = 'Extensioninstances';
        }

        $model_type = ucfirst(strtolower(trim($model_type)));
        $model_name = ucfirst(strtolower(trim($model_name)));

        $options               = array();
        $options['model_name'] = $model_name;
        $options['model_type'] = $model_type;

        $getService = $this->getService;
        $controller = $this->dependencies['Controllerread', $options];

        $primary_prefix = $controller->getModelRegistry('primary_prefix', 'a');
        $primary_key    = $controller->getModelRegistry('primary_key', 'id');
        $query_object   = 'list';

        if ((int)$extension_instance_id == 0) {
            $controller->setModelRegistry('query_object', 'list');

        } else {
            $getService = $this->getService;
            $user       = $getService('User');

            $authorised = $user->isAuthorisedExtension($extension_instance_id);

            if (is_object($authorised)) {
                $controller->setModelRegistry('get_customfields', 1);
                $temp                    = $controller->getCustomFields(array($authorised), 'item', 2);
                $temp[0]->model_registry = ucfirst(strtolower($model_name)) . ucfirst(strtolower($model_type));
                $query_results           = $temp[0];

                return $query_results;

            } else {

                $controller->model->query->where(
                    $controller->model->database->qn($primary_prefix)
                    . '.'
                    . $controller->model->database->qn($primary_key)
                    . ' = '
                    . (int)$extension_instance_id
                );

                $query_object = 'item';
            }
        }

        if ((int)$catalog_type_id == 0) {

        } else {
            $controller->model->query->where(
                $controller->model->database->qn($primary_prefix)
                . '.'
                . $controller->model->database->qn('catalog_type_id')
                . ' = '
                . (int)$catalog_type_id
            );
        }

        if (strtolower($query_object) == strtolower('list')
            && strtolower($model_type) == 'datasource'
            && strtolower($model_name) == 'extensioninstances'
        ) {

            $controller->setModelRegistry('model_offset', 0);
            $controller->setModelRegistry('model_count', 999999);
            $controller->setModelRegistry('use_pagination', 0);
            $controller->setModelRegistry('use_special_joins', 1);
            $controller->setModelRegistry('get_customfields', 1);
        }

        /** Do not return base row for extension catalog type */
        if (strtolower($query_object) == strtolower('list')) {

            $controller->model->query->where(
                $controller->model->database->qn($primary_prefix)
                . '.'
                . $controller->model->database->qn('catalog_type_id')
                . ' <> '
                . $controller->model->database->qn($primary_prefix)
                . '.'
                . $controller->model->database->qn($primary_key)
            );
        }

        if ($check_permissions === null) {
        } else {
            $controller->setModelRegistry('check_view_level_access', $check_permissions);
        }

        if (strtolower($model_type) == 'datasource') {

        } else {
            $controller->model->query->where(
                $controller->model->database->qn('catalog')
                . '.'
                . $controller->model->database->qn('enabled')
                . ' = '
                . (int)1
            );
        }

        $query_results = $controller->getData();

        if ($query_object == 'item') {
            $query_results->model_registry = $model_name . $model_type;
        }

        return $query_results;
    }

    /**
     * Retrieves Extension ID for specified Extension Instance Title and Catalog Handler values
     *
     * Note: All Extension queries first check the Registry of all Extensions for which the user is authorised.
     *
     * @param   $catalog_type Numeric or textual key for View Catalog Handler
     * @param   $title        Title of the Extension Instance
     *
     * @return bool|mixed
     * @since   1.0
     */
    public function getId($catalog_type, $title)
    {
        if (is_numeric($catalog_type)) {
            $catalog_type_id = $catalog_type;
        } else {
            $catalog_type_id = $this->getHandler(0, $catalog_type);
        }

        if ($this->registry->exists('AuthorisedExtensionsByInstanceTitle') === true) {
            $key = trim($title) . $catalog_type_id;
            $id  = $this->registry->get('AuthorisedExtensionsByInstanceTitle', $key, 0);
            if ((int)$id == 0) {
            } else {
                return $id;
            }
        }

        $options               = array();
        $options['model_name'] = 'Extensioninstances';
        $options['model_type'] = 'Datasource';

        $getService = $this->getService;
        $controller = $this->dependencies['Controllerread', $options];

        $primary_prefix = $controller->getModelRegistry('primary_prefix', 'a');
        $primary_key    = $controller->getModelRegistry('primary_key', 'id');
        $query_object   = 'list';

        $controller->set('process_plugins', 0);
        $prefix = $controller->getModelRegistry('primary_prefix', 'a');

        $controller->model->query->select(
            $controller->model->database->qn($prefix)
            . '.'
            . $controller->model->database->qn('id')
        );

        $controller->model->query->select(
            $controller->model->database->qn($prefix)
            . '.'
            . $controller->model->database->qn('title')
            . ' = '
            . $controller->model->database->q($title)
        );

        $controller->model->query->select(
            $controller->model->database->qn($prefix)
            . '.'
            . $controller->model->database->qn('catalog_type_id')
            . ' = '
            . (int)$catalog_type_id
        );

        return $controller->getData('result');
    }

    /**
     * Retrieves Extension Title given the Extension Instance ID value
     *
     * Note: All Extension queries first check the Registry of all Extensions for which the user is authorised.
     *
     * @param   $extension_instance_id Primary key for the Extension Instance
     *
     * @return bool|mixed
     * @since   1.0
     */
    public function getInstanceTitle($extension_instance_id)
    {
        if ($this->registry->exists('AuthorisedExtensions') === true) {
            $object = $this->registry->get('AuthorisedExtensions', $extension_instance_id, '');
            if ($object === false) {
                $title = '';
            } else {
                $title = $object->title;
            }

            if ($title == '') {
            } else {
                return $title;
            }
        }

        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry('datasource', 'Extensioninstances');
        $controller->setDataobject();
        $controller->connectDatabase();

        $controller->setModelRegistry('process_plugins', 0);
        $prefix = $controller->getModelRegistry('primary_prefix', 'a');

        $controller->model->query->select(
            $controller->model->database->qn($prefix)
            . '.'
            . $controller->model->database->qn('title')
        );

        $controller->model->query->where(
            $controller->model->database->qn($prefix)
            . '.'
            . $controller->model->database->qn('id')
            . ' = '
            . (int)$extension_instance_id
        );

        return $controller->getData('result');
    }

    /**
     * Retrieves the Node (which is the Extension Name and Folder Name) for the specified Extension Instance
     *
     * Note: The Extension Instance Title might be different than the Node since each Extension can be used
     *  multiple times as instances.
     *
     * @param   $extension_instance_id Primary key for the Extension Instance
     *
     * @return bool|mixed
     * @since   1.0
     */
    public function getExtensionNode($extension_instance_id)
    {
        if ($this->registry->exists('AuthorisedExtensions') === true) {
            $object = $this->registry->get('AuthorisedExtensions', $extension_instance_id, '');
            if (is_object($object)) {
                return $object->extensions_name;
            } else {
                return false;
            }
        }

        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry('datasource', 'Extensions');

        $controller->setModelRegistry('process_plugins', 0);

        $controller->model->query->select(
            $controller->model->database->qn('a')
            . '.'
            . $controller->model->database->qn('name')
        );

        $controller->model->query->from(
            $controller->model->database->qn('#__extensions')
            . ' as '
            . $controller->model->database->qn('a')
        );

        $controller->model->query->from(
            $controller->model->database->qn('#__extension_instances')
            . ' as '
            . $controller->model->database->qn('b')
        );

        $controller->model->query->where(
            $controller->model->database->qn('a')
            . '.'
            . $controller->model->database->qn('id')
            . ' = '
            . $controller->model->database->qn('b')
            . '.'
            . $controller->model->database->qn('extension_id')
        );

        $controller->model->query->where(
            $controller->model->database->qn('b')
            . '.'
            . $controller->model->database->qn('id')
            . ' = '
            . (int)$extension_instance_id
        );

        return $controller->getData('result');
    }

    /**
     * Returns path for Extension - make certain to send in extension name, not instance title.
     *
     * @param string $catalog_type Numeric or textual key for View Catalog Handler
     * @param string $node         Extension Name (folder name) for Extension Instance ID
     * @param string $registry     Registry for storing results
     *
     * @return string
     * @since   1.0
     */
    public function getPath($catalog_type, $node, $registry = null)
    {
        $this->registry = $registry;

        if (is_numeric($catalog_type)) {
            $catalog_type = $this->getHandler(0, $catalog_type);
        }

        if ($catalog_type === false) {
            throw new RuntimeException
            ('ExtensionHelper: Invalid Catalog Handler Value: ' . $catalog_type . ' sent in to getPath');
        }

        $catalog_type = ucfirst(strtolower($catalog_type));

        if ($this->registry === null) {
            $this->registry = 'parameters';
        }

        if ($catalog_type == CATALOG_TYPE_RESOURCE_LITERAL) {
            if (file_exists(
                BASE_FOLDER . '/Application/Extension' . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                ) . '/Configuration.xml'
            )
            ) {
                return BASE_FOLDER . '/Application/Extension' . '/' . $catalog_type . '/' . ucfirst(strtolower($node));
            }

            if (file_exists(
                BASE_FOLDER . '/Vendor' . '/Molajo' . '/' . 'System' . '/' . ucfirst(strtolower($node)) . '/Configuration.xml'
            )
            ) {
                return BASE_FOLDER . '/Vendor' . '/Molajo' . '/' . 'System' . '/' . ucfirst(strtolower($node));
            }

        } elseif ($catalog_type == CATALOG_TYPE_MENUITEM_LITERAL) {
            if (file_exists(
                BASE_FOLDER . '/Application/Extension' . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                ) . '/Configuration.xml'
            )
            ) {
                return BASE_FOLDER . '/Application/Extension' . '/' . $catalog_type . '/' . ucfirst(strtolower($node));
            }

        } elseif ($catalog_type == CATALOG_TYPE_LANGUAGE_LITERAL) {
            if (file_exists(
                BASE_FOLDER . '/Application/Extension' . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                ) . '/Configuration.xml'
            )
            ) {
                return BASE_FOLDER . '/Application/Extension' . '/' . $catalog_type . '/' . ucfirst(strtolower($node));
            }

        } elseif ($catalog_type == CATALOG_TYPE_THEME_LITERAL) {
            if (file_exists(
                BASE_FOLDER . '/Application/Extension' . '/Theme' . '/' . ucfirst(strtolower($node)) . '/' . 'index.php'
            )
            ) {
                return BASE_FOLDER . '/Application/Extension' . '/Theme' . '/' . ucfirst(strtolower($node));
            }

            if (file_exists(
                BASE_FOLDER . '/Vendor' . '/Molajo' . '/Molajo/Theme' . '/' . ucfirst(strtolower($node)) . '/' . 'index.php'
            )
            ) {
                return BASE_FOLDER . '/Vendor' . '/Molajo' . '/Molajo/Theme' . '/' . ucfirst(strtolower($node));
            }

        } elseif ($catalog_type == CATALOG_TYPE_PAGE_VIEW_LITERAL
            || $catalog_type == CATALOG_TYPE_TEMPLATE_VIEW_LITERAL
            || $catalog_type == CATALOG_TYPE_WRAP_VIEW_LITERAL
        ) {

            $plus = '/View/' . $catalog_type . '/' . ucfirst(strtolower($node));

            if (file_exists($this->registry->get($this->registry, 'theme_path') . $plus . '/Configuration.xml')) {
                return $this->registry->get($this->registry, 'theme_path') . $plus;
            }

            if (file_exists($this->registry->get($this->registry, 'extension_path') . $plus . '/Configuration.xml')) {
                return $this->registry->get($this->registry, 'extension_path') . $plus;
            }

            if (file_exists(
                BASE_FOLDER . '/Application/Extension' . '/View' . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                ) . '/Configuration.xml'
            )
            ) {
                return BASE_FOLDER . '/Application/Extension' . '/View' . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                );
            }

            if (file_exists(
                BASE_FOLDER . '/Vendor' . '/Molajo' . '/Molajo/View' . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                ) . '/Configuration.xml'
            )
            ) {
                return BASE_FOLDER . '/Vendor' . '/Molajo' . '/Molajo/View' . '/' . $catalog_type . '/' . ucfirst(strtolower($node));
            }

            if (file_exists(
                BASE_FOLDER . '/Vendor' . '/Molajo' . '/' . 'System' . '/' . ucfirst(strtolower($node)) . '/Configuration.xml'
            )
            ) {
                return BASE_FOLDER . '/Vendor' . '/Molajo' . '/' . 'System' . '/' . ucfirst(strtolower($node));
            }
        }

        throw new Exception('ExtensionHelper: getPath not found for Catalog Handler: '
        . $catalog_type . ' and Node: ' . $node);
    }

    /**
     * Return URL path for Extension
     *
     * @param string $catalog_type Numeric or textual key for View Catalog Handler
     * @param string $node         Folder name of extension
     * @param string $this         ->registry     Registry for storing results
     *
     * @return mixed
     * @since   1.0
     */
    public function getPathURL($catalog_type, $node, $registry)
    {
        $this->registry = $registry;

        if (is_numeric($catalog_type)) {
            $catalog_type = $this->getHandler(0, $catalog_type);
        }

        if ($catalog_type === false) {
            throw new \RuntimeException
            ('ExtensionHelper: Invalid Catalog Handler Value: ' . $catalog_type . ' sent in to getPath');
        }

        $catalog_type = ucfirst(strtolower($catalog_type));

        if ($this->registry === null) {
            $this->registry = 'parameters';
        }

        if ($catalog_type == CATALOG_TYPE_RESOURCE_LITERAL) {
            if (file_exists(
                BASE_FOLDER . '/Application/Extension' . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                ) . '/Configuration.xml'
            )
            ) {
                return BASE_FOLDER . '/Application/Extension'_URL . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                );
            }

            if (file_exists(
                BASE_FOLDER . '/Vendor' . '/Molajo' . '/' . 'System' . '/' . ucfirst(strtolower($node)) . '/Configuration.xml'
            )
            ) {
                return CORE_SYSTEM_URL . '/' . ucfirst(strtolower($node));
            }

        } elseif ($catalog_type == CATALOG_TYPE_MENUITEM_LITERAL) {
            if (file_exists(
                BASE_FOLDER . '/Application/Extension' . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                ) . '/Configuration.xml'
            )
            ) {
                return BASE_FOLDER . '/Application/Extension'_URL . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                );
            }

        } elseif ($catalog_type == CATALOG_TYPE_LANGUAGE_LITERAL) {
            if (file_exists(
                BASE_FOLDER . '/Application/Extension' . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                ) . '/Configuration.xml'
            )
            ) {
                return BASE_FOLDER . '/Application/Extension'_URL . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                );
            }

        } elseif ($catalog_type == CATALOG_TYPE_THEME_LITERAL) {

            if (file_exists(
                BASE_FOLDER . '/Application/Extension' . '/Theme' . '/' . ucfirst(strtolower($node)) . '/' . 'index.php'
            )
            ) {
                return BASE_FOLDER . '/Application/Extension' . '/' . THEME_URL . '/' . ucfirst(strtolower($node));
            }

            if (file_exists(
                BASE_FOLDER . '/Vendor' . '/Molajo' . '/Molajo/Theme' . '/' . ucfirst(strtolower($node)) . '/' . 'index.php'
            )
            ) {
                return BASE_FOLDER . '/Vendor' . '/Molajo' . '/Molajo/' . THEME_URL . '/' . ucfirst(strtolower($node));
            }

        } elseif ($catalog_type == CATALOG_TYPE_PAGE_VIEW_LITERAL
            || $catalog_type == CATALOG_TYPE_TEMPLATE_VIEW_LITERAL
            || $catalog_type == CATALOG_TYPE_WRAP_VIEW_LITERAL
        ) {

            $plus = '/View/' . $catalog_type . '/' . ucfirst(strtolower($node));

            if (file_exists($this->registry->get($this->registry, 'theme_path') . $plus . '/Configuration.xml')) {
                return $this->registry->get($this->registry, 'theme_path_url') . $plus;
            }

            if (file_exists($this->registry->get($this->registry, 'extension_path') . $plus . '/Configuration.xml')) {
                return $this->registry->get($this->registry, 'extension_path_url') . $plus;
            }

            if (file_exists(
                BASE_FOLDER . '/Application/Extension' . '/View' . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                ) . '/Configuration.xml'
            )
            ) {
                return BASE_FOLDER . '/Application/Extension' . '/' . VIEW_URL . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                );
            }

            if (file_exists(
                BASE_FOLDER . '/Vendor' . '/Molajo' . '/Molajo/View' . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                ) . '/Configuration.xml'
            )
            ) {
                return BASE_FOLDER . '/Vendor' . '/Molajo' . '/Molajo/' . VIEW_URL . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                );
            }
        }

        throw new Exception('ExtensionHelper: getPathURL not found for Catalog Handler: '
        . $catalog_type . ' and Node: ' . $node);
    }

    /**
     * Return namespace for extension
     *
     * @param string $catalog_type Numeric or textual key for View Catalog Handler
     * @param string $node         Folder name of extension
     * @param string $this         ->registry     Registry for storing results
     *
     * @return bool|string
     * @since   1.0
     */
    public function getNamespace($catalog_type, $node, $registry = null)
    {
        $this->registry = $registry;

        if (is_numeric($catalog_type)) {
            $catalog_type = $this->getHandler(0, $catalog_type);
        }

        if ($catalog_type === false) {
            throw new \RuntimeException
            ('ExtensionHelper: Invalid Catalog Handler Value: ' . $catalog_type . ' sent in to getPath');
        }

        if ($this->registry === null) {
            $this->registry = 'parameters';
        }

        $catalog_type = ucfirst(strtolower($catalog_type));

        if ($catalog_type == CATALOG_TYPE_RESOURCE_LITERAL) {
            if (file_exists(
                BASE_FOLDER . '/Application/Extension' . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                ) . '/Configuration.xml'
            )
            ) {
                return 'Extension\\Resource\\' . ucfirst(strtolower($node));
            }

            if (file_exists(
                BASE_FOLDER . '/Vendor' . '/Molajo' . '/' . 'System' . '/' . ucfirst(strtolower($node)) . '/Configuration.xml'
            )
            ) {
                return 'Vendor\\Molajo\\System\\' . ucfirst(strtolower($node));
            }

        } elseif ($catalog_type == CATALOG_TYPE_MENUITEM_LITERAL) {
            if (file_exists(
                BASE_FOLDER . '/Application/Extension' . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                ) . '/Configuration.xml'
            )
            ) {
                return 'Extension\\Menuitem\\' . ucfirst(strtolower($node));

            } elseif ($catalog_type == CATALOG_TYPE_LANGUAGE_LITERAL) {
                if (file_exists(
                    BASE_FOLDER . '/Application/Extension' . '/' . $catalog_type . '/' . ucfirst(
                        strtolower($node)
                    ) . '/Configuration.xml'
                )
                ) {
                    return 'Extension\\Language\\' . $catalog_type . '\\' . ucfirst(strtolower($node));
                }
            }

        } elseif ($catalog_type == CATALOG_TYPE_THEME_LITERAL) {

            if (file_exists(
                BASE_FOLDER . '/Application/Extension' . '/Theme' . '/' . ucfirst(strtolower($node)) . '/' . 'index.php'
            )
            ) {
                return 'Extension\\Theme\\' . ucfirst(strtolower($node));
            }

            if (file_exists(
                BASE_FOLDER . '/Vendor' . '/Molajo' . '/Molajo/Theme' . '/' . ucfirst(strtolower($node)) . '/' . 'index.php'
            )
            ) {
                return 'Molajo\\Theme\\' . ucfirst(strtolower($node));
            }

        } elseif ($catalog_type == CATALOG_TYPE_PAGE_VIEW_LITERAL
            || $catalog_type == CATALOG_TYPE_TEMPLATE_VIEW_LITERAL
            || $catalog_type == CATALOG_TYPE_WRAP_VIEW_LITERAL
        ) {

            $plus   = '/View/' . $catalog_type . '/' . ucfirst(strtolower($node));
            $plusNS = 'View\\' . $catalog_type . '\\' . ucfirst(strtolower($node));

            if (file_exists($this->registry->get($this->registry, 'theme_path') . $plus . '/Configuration.xml')) {
                return 'Extension\\Theme\\' . $this->registry->get($this->registry, 'theme_path_node') . '\\' . $plusNS;
            }

            if (file_exists($this->registry->get($this->registry, 'extension_path') . $plus . '/Configuration.xml')) {
                return 'Extension\\Resource\\' . $this->registry->get(
                    $this->registry,
                    'extension_title'
                ) . '\\' . $plusNS;
            }

            if (file_exists(
                BASE_FOLDER . '/Application/Extension' . '/View' . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                ) . '/Configuration.xml'
            )
            ) {
                return 'Extension\\' . $plusNS;
            }

            if (file_exists(
                BASE_FOLDER . '/Vendor' . '/Molajo' . '/Molajo/View' . '/' . $catalog_type . '/' . ucfirst(
                    strtolower($node)
                ) . '/Configuration.xml'
            )
            ) {
                return 'Molajo\\' . $plusNS;
            }

        }

        throw new Exception('ExtensionHelper: getPathNamespace not found for Catalog Handler: '
        . $catalog_type . ' and Node: ' . $node);
    }

    /**
     * Retrieve Favicon Path from Theme Folder
     *
     * Note: Expects theme_path to already be set in the $this->registry
     *
     * @param string $this ->registry Registry for storing results
     *
     * @return mixed
     * @since   1.0
     */
    public function getFavicon($registry)
    {
        $this->registry = $registry;

        $path = $this->registry->get($this->registry, 'theme_path') . '/images/';

        if (file_exists($path . 'favicon.ico')) {
            $this->registry->get($this->registry, 'theme_path_url') . '/images/favicon.ico';
        }

        $path = BASE_FOLDER;
        if (file_exists($path . 'favicon.ico')) {
            return BASE_URL . '/favicon.ico';
        }

        return false;
    }

    /**
     * Retrieve the path node for a specified catalog type or
     * it retrieves the catalog id value for the requested type
     *
     * @param   int  $catalog_type_id
     * @param   null $catalog_type
     *
     * @return  string
     * @since   1.0
     */
    public function getHandler($catalog_type_id = 0, $catalog_type = null)
    {
        if ((int)$catalog_type_id == 0) {

            if ($catalog_type == $this->parameters->reference_data->catalog_type_application_literal) {
                return $this->parameters->reference_data->catalog_type_application_id;

            } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_field_literal) {
                return $this->parameters->reference_data->catalog_type_field_id;

            } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_language_literal) {
                return $this->parameters->reference_data->catalog_type_language_id;

            } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_language_string_literal) {
                return $this->parameters->reference_data->catalog_type_language_string_id;

            } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_menuitem_literal) {
                return $this->parameters->reference_data->catalog_type_menuitem_id;

            } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_message_literal) {
                return $this->parameters->reference_data->catalog_type_message_id;

            } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_page_view_literal) {
                return $this->parameters->reference_data->catalog_type_page_view_id;

            } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_plugin_literal) {
                return $this->parameters->reference_data->catalog_type_plugin_id;

            } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_resource_literal) {
                return $this->parameters->reference_data->catalog_type_resource_id;

            } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_service_literal) {
                return $this->parameters->reference_data->catalog_type_service_id;

            } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_site_literal) {
                return $this->parameters->reference_data->catalog_type_site_id;

            } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_template_view_literal) {
                return $this->parameters->reference_data->catalog_type_template_view_id;

            } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_theme_literal) {
                return $this->parameters->reference_data->catalog_type_theme_id;

            } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_user_literal) {
                return $this->parameters->reference_data->catalog_type_user_id;

            } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_wrap_view_literal) {
                return $this->parameters->reference_data->catalog_type_wrap_view_id;
            }

            return $this->parameters->reference_data->catalog_type_resource_id;
        }

        if ($catalog_type == $this->parameters->reference_data->catalog_type_application_id) {
            return $this->parameters->reference_data->catalog_type_application_literal;

        } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_field_id) {
            return $this->parameters->reference_data->catalog_type_field_literal;

        } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_language_id) {
            return $this->parameters->reference_data->catalog_type_language_literal;

        } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_language_string_id) {
            return $this->parameters->reference_data->catalog_type_language_string_literal;

        } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_menuitem_id) {
            return $this->parameters->reference_data->catalog_type_menuitem_literal;

        } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_message_id) {
            return $this->parameters->reference_data->catalog_type_message_literal;

        } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_page_view_id) {
            return $this->parameters->reference_data->catalog_type_page_view_literal;

        } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_plugin_id) {
            return $this->parameters->reference_data->catalog_type_plugin_literal;

        } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_resource_id) {
            return $this->parameters->reference_data->catalog_type_resource_literal;

        } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_service_id) {
            return $this->parameters->reference_data->catalog_type_service_literal;

        } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_site_id) {
            return $this->parameters->reference_data->catalog_type_site_literal;

        } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_template_view_id) {
            return $this->parameters->reference_data->catalog_type_template_view_literal;

        } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_theme_id) {
            return $this->parameters->reference_data->catalog_type_theme_literal;

        } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_user_id) {
            return $this->parameters->reference_data->catalog_type_user_literal;

        } elseif ($catalog_type == $this->parameters->reference_data->catalog_type_wrap_view_id) {
            return $this->parameters->reference_data->catalog_type_wrap_view_literal;
        }

        return $this->parameters->reference_data->catalog_type_resource_literal;
    }
}
