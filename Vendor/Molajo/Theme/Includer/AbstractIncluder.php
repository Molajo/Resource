<?php
/**
 * @package   Abstract Includer
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Theme\Includer;

use Molajo\Theme\Api\IncluderInterface;

/**
 * Abstract Includer
 *
 * The Includer acts as the base class for a set of classes which gather the input parameters needed
 * to generate a specific <include type=value name=statement/>, passing on the parameters to the Mvc for rendering
 * and then returning the rendered results to the Theme Service.
 *
 * The Theme Service Includer schedules onBeforeInclude and onAfterInclude Events
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
abstract class AbstractIncluder implements IncluderInterface
{
    /**
     * Include Name
     *
     * Values include Head, Message, Page, Profiler, Tag, Template, Theme, and Wrap
     *
     * <include type=head/>
     * <include type=template name=template-name/>
     * <include type=message/>
     *
     * @var    string
     * @since  1.0
     */
    protected $include_name = null;

    /**
     * Include Handler
     *
     * Handler is only different than name in type:name pairs where type is an alias of name
     * Asset and metadata types are an alias of template; defer type is an alias of head
     *
     * @var    string
     * @since  1.0
     */
    protected $include_type = null;

    /**
     * Name - from attributes
     *
     * <include type=template name=this-value/>
     *
     * @var    string
     * @since  1.0
     */
    protected $name = null;

    /**
     * Attributes - extracted from include statement and placed into an array by parsing process
     *
     * <include type=template name=this-value all=the rest=of-this goes=into-attributes as=named-pairs/>
     *
     * @var    string
     * @since  1.0
     */
    protected $attributes = array();

    /**
     * $tag used to extract a set of views for rendering
     *
     * @var    array
     * @since  1.0
     */
    protected $tag = array();

    /**
     * Parameters to pass on to the Mvc for rendering the include statement
     *
     * @var    string
     * @since  1.0
     */
    protected $parameters = array();

    /**
     * Parameters to pass on to the Mvc for rendering the include statement
     *
     * @var    string
     * @since  1.0
     */
    protected $parameter_property_array = array();

    /**
     * Name of Model Registry used to generate input for the include
     *
     * @var    string
     * @since  1.0
     */
    protected $model_registry_name = null;

    /**
     * Model used to generate input for the include
     *
     * @var    string
     * @since  1.0
     */
    protected $model_registry = array();

    /**
     * Rendered by the Views and passed back through the Theme Includers to the Theme Service
     *
     * @var    string
     * @since  1.0
     */
    protected $rendered_output = null;

    /**
     * Used in editing get and set values
     *
     * @var    string
     * @since  1.0
     */
    protected $property_array = array(
        'include_name',
        'include_type',
        'name',
        'attributes',
        'tag',
        'parameters',
        'model_registry_name',
        'model_registry',
        'rendered_output'
    );

    /**
     * Content Helper
     *
     * @var    object
     * @since  1.0
     */
    protected $content_helper;

    /**
     * Extension Helper
     *
     * @var    object
     * @since  1.0
     */
    protected $extension_helper;

    /**
     * Theme Helper
     *
     * @var    object
     * @since  1.0
     */
    protected $theme_helper;

    /**
     * View Helper
     *
     * @var    object
     * @since  1.0
     */
    protected $view_helper;

    /**
     * Profiler Instance
     *
     * @var    object
     * @since  1.0
     */
    protected $profiler_instance;

    /**
     * Registry Instance
     *
     * @var    object
     * @since  1.0
     */
    protected $registry;

    /**
     * Event Instance
     *
     * @var    object
     * @since  1.0
     */
    protected $event;

    /**
     * User Instance
     *
     * @var    object
     * @since  1.0
     */
    protected $user;

    /**
     * class Constructor
     *
     * @param   string $include_name
     * @param   string $include_type
     * @param   string $tag
     * @param   string $parameters
     *
     * @return  object  Includer
     * @since   1.0
     */
    public function __construct(
        $include_name = null,
        $include_type = null,
        $tag = null,
        $parameters = array(),
        $content_helper,
        $extension_helper,
        $theme_helper,
        $view_helper
    ) {
        $this->set('name', null);
        $this->set('attributes', array());
        $this->set('model_registry_name', null);
        $this->set('model_registry', null);
        $this->set('rendered_output', null);

        $this->content_helper   = $content_helper;
        $this->extension_helper = new ExtensionHelper();
        $this->theme_helper     = new ThemeHelper();
        $this->view_helper      = new ViewHelper();
    }

    /**
     * Get the value (or default) of the specified property and key
     *
     * @param   string $property
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  IncluderException
     */
    public function get($property = '', $key, $default = null)
    {
//        echo 'GET $key ' . $key . ' ' . ' Property ' . $property . '<br />';

        if (in_array($key, $this->property_array) && $property == '') {
            $value = $this->$key;

            return $value;
        }

        if ($property == 'parameters') {
            if (isset($this->parameters->$key)) {
                return $this->parameters->$key;
            }
            $this->parameters->$key = $default;

            return $this->parameters->$key;
        }

        if ($property == 'model_registry') {
            if (isset($this->model_registry->$key)) {
                return $this->model_registry->$key;
            }
            $this->model_registry->$key = $default;

            return $this->model_registry->$key;
        }

        if ($property == 'attributes') {
            if (isset($this->attributes[$key])) {
                return $this->attributes[$key];
            }
            $this->attributes[$key] = $default;

            return $this->attributes[$key];
        }

        throw new IncluderException
        ('Includer: get for unknown property: ' . $property . ' and key: ' . $key);
    }

    /**
     * Set the value of the specified property and key
     *
     * @param   string $include_name
     * @param   string $property
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  mixed
     * @since   1.0
     */
    public function set($property = '', $key, $value = null)
    {
//echo 'SET $key ' . $key . ' ' . ' Property ' . $property . '<br />';

        if (in_array($key, $this->property_array) && $property == '') {
            $this->$key = $value;

            return $this->$key;
        }

        if ($property == 'parameters') {
            $this->parameters->$key = $value;

            return $this->parameters->$key;
        }

        if ($property == 'model_registry') {
            $this->model_registry->$key = $value;

            return $this->model_registry->$key;
        }

        if ($property == 'attributes') {
            $this->attributes[$key] = $value;

            return $this->attributes[$key];
        }

        throw new IncluderException
        ('Includer: set for unknown key: ' . $key . ' and property: ' . $property);
    }

    /**
     * Includer controller executes steps in sequence needed:
     *
     * - getAttributes - extracts extension name and other parameters defined on the <include type=value/> statement
     * - setExtensionParameters - for the specific type of includer, retrieve parameters needed for rendering
     * - loadPlugins - load Plugin Overrides in Extension folder
     * - onBeforeIncludeEvent - Schedule on Before Include Event
     * - renderOutput - passes parameters to Mvc and receives rendered output
     * - loadAssets - loads CSS and JS files for rendered output
     * - onAfterIncludeEvent - Schedule After Include Event
     * - Returns Rendered Output to the Theme Service which will parse output for additional <include type=value />
     *
     * @param   array $attributes <include type=value name=x the=rest are=attributes/>
     *
     * @return  mixed
     * @since   1.0
     */
    public function process($attributes)
    {
        $this->getAttributes($attributes);

        $results = $this->setExtensionParameters();
        if ($results === false) {
            return false;
        }

        $this->onBeforeIncludeEvent();

        $this->loadPlugins();

        $this->renderOutput();

        $this->onAfterIncludeEvent();

        return $this->rendered_output;
    }

    /**
     * Use the view and/or wrap criteria ife specified on the <include statement
     *
     * @param   $attributes
     *
     * @return  void
     * @since   1.0
     */
    protected function getAttributes($attributes)
    {
        $this->attributes = array();
        $this->name       = null;

        if (count($attributes) > 0) {
        } else {
            return;
        }

        foreach ($attributes as $key => $value) {

            if (strtolower($key) == 'name') {
                $this->name = strtolower(trim($value));
            } else {
                $this->attributes[$key] = $value;
            }
        }

        return;
    }

    /**
     * Uses Include Request and Attributes (overrides) to set Parameters for Rendering
     *
     * @return  bool
     * @since   1.0
     */
    protected function setRenderCriteria()
    {
        $template_id    = 0;
        $template_title = '';

        $saveTemplate = array();
        $temp         = $this->registry->get('parameters', 'template*');

        if (is_array($temp) && count($temp) > 0) {
            foreach ($temp as $key => $value) {

                if ($key == 'template_view_id'
                    || $key == 'template_view_path_node'
                    || $key == 'template_view_title'
                ) {

                } elseif (is_array($value)) {
                    $saveTemplate[$key] = $value;

                } elseif ($value === 0
                    || trim($value) == ''
                    || $value === null
                ) {

                } else {
                    $saveTemplate[$key] = $value;
                }
            }
        }

        $saveWrap = array();
        $temp     = $this->registry->get('parameters', 'wrap*');
        $temp2    = $this->registry->get('parameters', 'model*');
        $temp3    = array_merge($temp, $temp2);
        $temp2    = $this->registry->get('parameters', 'data*');
        $temp     = array_merge($temp2, $temp3);

        if (is_array($temp) && count($temp) > 0) {
            foreach ($temp as $key => $value) {

                if (is_array($value)) {
                    $saveWrap[$key] = $value;

                } elseif ($value === 0 || trim($value) == '' || $value === null) {

                } else {
                    $saveWrap[$key] = $value;
                }
            }
        }

        if ($this->type == CATALOG_TYPE_WRAP_VIEW_LITERAL) {

        } else {
            $results = $this->setTemplateRenderCriteria($saveTemplate);
            if ($results === false) {
                return false;
            }
        }

        $results = $this->setWrapRenderCriteria($saveWrap);
        if ($results === false) {
            return false;
        }

        $this->registry->delete('parameters', 'item*');
        $this->registry->delete('parameters', 'list*');
        $this->registry->delete('parameters', 'form*');
        $this->registry->delete('parameters', 'menuitem*');

        $this->registry->sort('parameters');

        $fields = $this->application->get('application*');
        if (count($fields) === 0 || $fields === false) {
        } else {
            foreach ($fields as $key => $value) {
                $this->registry->set('include', $key, $value);
            }
        }

        $fields = $this->registry->getArray('Tempattributes');
        if (count($fields) === 0 || $fields === false) {
        } else {
            foreach ($fields as $key => $value) {
                $this->registry->set('include', $key, $value);
            }
        }

        $message = 'Includer: Render Criteria '
            . 'Name ' . strtolower($this->name)
            . ' Handler ' . $this->type
            . ' Template ' . $this->registry->get('parameters', 'template_view_title')
            . ' Model Handler ' . $this->registry->get('parameters', 'model_type')
            . ' Model Name ' . $this->registry->get('parameters', 'model_name');

        $this->profiler_instance->set('message', $message, 'Rendering', 1);

        return true;
    }

    /**
     * Retrieve extension information
     *
     * @return  bool
     * @since   1.0
     */
    protected function getExtension()
    {
        return;
    }

    /**
     * Process Template Options
     *
     * @param   string $saveTemplate
     *
     * @return  bool
     * @since   1.0
     */
    protected function setTemplateRenderCriteria($saveTemplate)
    {
        $template_id = (int)$this->registry->get('parameters', 'template_view_id');

        if ((int)$template_id == 0) {
            $template_title = $this->registry->get('parameters', 'template_view_path_node');
            if (trim($template_title) == '') {
            } else {
                $template_id = $this->extension_helper
                    ->getId(CATALOG_TYPE_TEMPLATE_VIEW, $template_title);
                $this->registry->set('include', 'template_view_id', $template_id);
            }
        }

        if ((int)$template_id == 0) {
            $template_id = $this->view_helper->getDefault(CATALOG_TYPE_TEMPLATE_VIEW_LITERAL);
            $this->registry->set('include', 'template_view_id', $template_id);
        }

        if ((int)$template_id == 0) {
            return false;
        }

        $this->view_helper->get($template_id, CATALOG_TYPE_TEMPLATE_VIEW_LITERAL);

        if (is_array($saveTemplate) && count($saveTemplate) > 0) {
            foreach ($saveTemplate as $key => $value) {
                $this->registry->set('include', $key, $value);
            }
        }

        return true;
    }

    /**
     * Process Wrap Options
     *
     * @param   string @saveWrap
     *
     * @return  bool
     * @since   1.0
     */
    protected function setWrapRenderCriteria($saveWrap)
    {
        if (is_array($saveWrap) && count($saveWrap) > 0) {
            foreach ($saveWrap as $key => $value) {
                if (is_array($value)) {
                    $saveWrap[$key] = $value;

                } elseif ($value === 0 || trim($value) == '' || $value === null) {

                } else {
                    $this->registry->set('include', $key, $value);
                }
            }
        }

        $wrap_id    = 0;
        $wrap_title = '';

        $wrap_id = (int)$this->registry->get('parameters', 'wrap_view_id');

        if ((int)$wrap_id == 0) {
            $wrap_title = $this->registry->get('parameters', 'wrap_view_path_node', '');
            if (trim($wrap_title) == '') {
                $wrap_title = 'None';
            }
            $wrap_id = $this->extension_helper
                ->getId(CATALOG_TYPE_WRAP_VIEW, $wrap_title);
            $this->registry->set('include', 'wrap_view_id', $wrap_id);
        }

        if (is_array($saveWrap) && count($saveWrap) > 0) {
            foreach ($saveWrap as $key => $value) {
                if ($key == 'wrap_view_id' || $key == 'wrap_view_path_node' || $key == 'wrap_view_title') {
                } else {
                    $this->registry->set('include', $key, $value);
                }
            }
        }

        $saveWrap = array();
        $temp     = $this->registry->get('parameters', 'wrap*');
        $temp2    = $this->registry->get('parameters', 'model*');
        $temp3    = array_merge($temp, $temp2);
        $temp2    = $this->registry->get('parameters', 'data*');
        $temp     = array_merge($temp2, $temp3);

        if (is_array($temp) && count($temp) > 0) {
            foreach ($temp as $key => $value) {

                if (is_array($value)) {
                    $saveWrap[$key] = $value;

                } elseif ($value === 0 || trim($value) == '' || $value === null) {

                } else {
                    $saveWrap[$key] = $value;
                }
            }
        }

        $this->view_helper->get($wrap_id, CATALOG_TYPE_WRAP_VIEW_LITERAL);

        if (is_array($saveWrap) && count($saveWrap) > 0) {
            foreach ($saveWrap as $key => $value) {
                if ($key == 'wrap_view_id' || $key == 'wrap_view_path_node' || $key == 'wrap_view_title') {
                } else {
                    $this->registry->set('include', $key, $value);
                }
            }
        }

        if ($this->registry->exists('parameters', 'wrap_view_role')) {
        } else {
            $this->registry->set('include', 'wrap_view_role', '');
        }
        if ($this->registry->exists('parameters', 'wrap_view_property')) {
        } else {
            $this->registry->set('include', 'wrap_view_property', '');
        }
        if ($this->registry->exists('parameters', 'wrap_view_header_level')) {
        } else {
            $this->registry->set('include', 'wrap_view_header_level', '');
        }
        if ($this->registry->exists('parameters', 'wrap_view_show_title')) {
        } else {
            $this->registry->set('include', 'wrap_view_show_title', '');
        }
        if ($this->registry->exists('parameters', 'wrap_view_show_subtitle')) {
        } else {
            $this->registry->set('include', 'wrap_view_show_subtitle', '');
        }

        $this->registry->sort('parameters');

        return true;
    }

    /**
     * Load Plugins Overrides from the Template and/or Wrap View folders
     *
     * @return  void
     * @since   1.0
     */
    protected function loadPlugins()
    {
        $node = $this->registry->get('parameters', 'extension_name_path_node');

        $this->event->registerPlugins(
            $this->extension_helper->getPath(CATALOG_TYPE_RESOURCE, $node),
            $this->extension_helper->getNamespace(CATALOG_TYPE_RESOURCE, $node)
        );

        $node = $this->registry->get('parameters', 'template_view_path_node');

        $this->event->registerPlugins(
            $this->extension_helper->getPath(CATALOG_TYPE_TEMPLATE_VIEW, $node),
            $this->extension_helper->getNamespace(CATALOG_TYPE_TEMPLATE_VIEW, $node)
        );

        $node = $this->registry->get('parameters', 'wrap_view_path_node');

        $this->event->registerPlugins(
            $this->extension_helper->getPath(CATALOG_TYPE_WRAP_VIEW, $node),
            $this->extension_helper->getNamespace(CATALOG_TYPE_WRAP_VIEW, $node)
        );

        return;
    }

    /**
     * Instantiate Controller class and pass in Parameters, Model Registry and Name and
     * Include Name and Handler. The Mvc will render the output, and send it back to this method.
     *
     * @return  void
     * @since   1.0
     */
    protected function renderOutput()
    {
        $model_registry_name = ucfirst(strtolower($this->registry->get('parameters', 'model_name')))
            . ucfirst(strtolower($this->registry->get('parameters', 'model_type')));

        $controller = new DisplayController();

        $controller->set(
            'primary_key_value',
            (int)$this->registry->get('parameters', 'source_id'),
            'model_registry'
        );

        $controller->set('include', $this->registry->getArray('parameters'));
        $controller->set('model_registry', $this->registry->get($model_registry_name));
        $controller->set('model_registry_name', $model_registry_name);

        $cache_key     = implode('', $controller->set('include', $this->registry->getArray('parameters')));
        $cached_output = $this->cache->get(CATALOG_TYPE_TEMPLATE_VIEW_LITERAL, $cache_key);

//@todo check parameter to see if individual item should be cached
        if ($cached_output === false) {

            $this->rendered_output = $controller->execute();

            $model_registry_name = $controller->get('model_registry_name');

            $this->registry->delete($model_registry_name);
            $this->registry->createRegistry($model_registry_name);
            $this->registry->loadArray($model_registry_name, $controller->get('model_registry'));

            $this->registry->delete('parameters');
            $this->registry->createRegistry('parameters');
            $this->registry->loadArray('parameters', $controller->get('parameters'));
            $this->registry->sort('parameters');

            $this->cache->set(CATALOG_TYPE_TEMPLATE_VIEW_LITERAL, $cache_key, $this->rendered_output);

        } else {
            $this->rendered_output = $cached_output;
        }

        if ($this->rendered_output == ''
            && $this->registry->get('parameters', 'criteria_display_view_on_no_results') == 0
        ) {
        } else {
            $this->loadMedia();
            $this->loadViewMedia();
        }

        return;
    }

    /**
     * Loads Media CSS and JS files for extension and related content
     *
     * @return  null
     * @since   1.0
     */
    protected function loadMedia()
    {
        return $this;
    }

    /**
     * Loads Media CSS and JS files for Template and Wrap Views
     *
     * @return  null
     * @since   1.0
     */
    protected function loadViewMedia()
    {
        $priority  = $this->registry->get('parameters', 'criteria_media_priority_other_extension', 400);
        $file_path = $this->registry->get('parameters', 'template_view_path');
        $url_path  = $this->registry->get('parameters', 'template_view_path_url');

        $css   = $this->document_css->setFolder($file_path, $url_path, $priority);
        $js    = $this->document_js->setFolder($file_path, $url_path, $priority, 0);
        $defer = $this->document_js->setFolder($file_path, $url_path, $priority, 1);

        $file_path = $this->registry->get('parameters', 'wrap_view_path');
        $url_path  = $this->registry->get('parameters', 'wrap_view_path_url');

        $css   = $this->document_css->setFolder($file_path, $url_path, $priority);
        $js    = $this->document_js->setFolder($file_path, $url_path, $priority, 0);
        $defer = $this->document_js->setFolder($file_path, $url_path, $priority, 1);

        return $this;
    }

    /**
     * Schedule Event onBeforeIncludeEvent
     *
     * @return  boolean
     * @since   1.0
     */
    protected function onBeforeIncludeEvent()
    {
        return $this->triggerEvent('onBeforeInclude');
    }

    /**
     * Schedule Event onAfterParseEvent Event
     *
     * @return  boolean
     * @since   1.0
     */
    protected function onAfterIncludeEvent()
    {
        return $this->triggerEvent('onAfterInclude');
    }

    /**
     * Common Method for Includer Events
     *
     * @param   string $event_name
     *
     * @return  string  void
     * @since   1.0
     */
    protected function triggerEvent($event_name)
    {
        $model_registry_name
            = ucfirst(strtolower($this->registry->get('parameters', 'model_name')))
            . ucfirst(strtolower($this->registry->get('parameters', 'model_type')));

        $model_registry
            = $this->registry->get($model_registry_name);

        $arguments = array(
            'model'                             => null,
            'model_registry'                    => $model_registry,
            'model_registry_name'               => $this->model_registry_name,
            'parameters'                        => $this->parameters,
            'parameter_property_array'          => $this->parameter_property_array,
            'query_results'                     => array(),
            'row'                               => array(),
            'rendered_output'                   => $this->rendered_output,
            'view_path'                         => null,
            'view_path_url'                     => null,
            'plugins'                           => null,
            'include_parse_sequence'            => array(),
            'include_parse_exclude_until_final' => array()
        );

        $arguments = $this->event->scheduleEvent($event_name, $arguments, array());

        if (isset($arguments['model_registry'])) {
            $this->registry->delete($model_registry_name);
            $this->registry->createRegistry($model_registry_name);
            $this->registry->loadArray($model_registry_name, $arguments['model_registry']);
        }

        if (isset($this->parameters->model_registry_name)) {

            $model_registry_name = $this->parameters->model_registry_name;

            if (isset($arguments['model_registry'])) {
                $this->registry->delete($model_registry_name);
                $this->registry->createRegistry($this->get('model_registry_name'));
                $this->registry->loadArray($this->get('model_registry_name'), $arguments['model_registry']);
            }
        }

        if (isset($arguments['parameters'])) {
            $this->parameters = $arguments['parameters'];
        }

        if (isset($arguments['parameter_property_array'])) {
            $this->parameter_property_array = $arguments['parameter_property_array'];
        }

        if (isset($arguments['rendered_output'])) {
            $this->rendered_output = $arguments['rendered_output'];
        }

        return;
    }
}
