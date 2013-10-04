<?php
/**
 * Event Service
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Event;

use Exception;
use Molajo\Event\Exception\EventException;
use Molajo\Event\Api\EventInterface;

/**
 * Event
 *
 * List All Events:
 *      $event_array = $this->events->get('Events');
 *
 * List All Plugins:
 *      $plugin_array = $this->events->get('Plugins');
 *
 * List Plugins for a Specific Event:
 *      $plugin_array = $this->events->get('Plugins', 'onBeforeRead');
 *
 * Schedule an Event:
 *      Services::Event()->scheduleEvent('onAfterDelete', $arguments, $selections);
 *
 * Override a Plugin:
 *      Copy the Plugin folder into an Extension (i.e., Plugin, Resource, View, Theme, etc.) and make changes,
 *      When that extension is in use, Molajo will locate the override and register it with this command:
 *
 *      Services::Event()->registerPlugin(BASE_FOLDER . '/Vendor' . '/Molajo' . '/' . 'Plugin', 'Molajo\\Plugin\\');
 *      Services::Event()->registerPlugin('Extension', 'Extension\\Resource\\Articles\\AliasPlugin');
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Event implements EventInterface
{
    /**
     * Indicator Event Service has been activated
     *
     * @var    array
     * @since  1.0
     */
    protected $on;

    /**
     * Current Date
     *
     * @var    object
     * @since  1.0
     */
    protected $current_date;

    /**
     * Event Array
     *
     * @var    array
     * @since  1.0
     */
    protected $event_array = array();

    /**
     * Events Plugin Array
     *
     * @var    array
     * @since  1.0
     */
    protected $event_plugin_array = array();

    /**
     * getService Closure
     *
     * @var     string
     * @since   1.0
     */
    public $getService;

    /**
     * setService Closure
     *
     * @var     string
     * @since   1.0
     */
    public $setService;

    /**
     * cloneService Closure
     *
     * @var     string
     * @since   1.0
     */
    public $cloneService;

    /**
     * removeService Closure
     *
     * @var     string
     * @since   1.0
     */
    public $removeService;

    /**
     * Include Parse Exclude Until Final
     *
     * @var    string
     * @since  1.0
     */
    protected $include_parse_exclude_until_final;

    /**
     * Include Parse Sequence
     *
     * @var    string
     * @since  1.0
     */
    protected $include_parse_sequence;

    /**
     * Model Registry Name
     *
     * @var    string
     * @since  1.0
     */
    protected $model_registry_name;

    /**
     * Model Registry
     *
     * @var    object
     * @since  1.0
     */
    protected $model_registry;

    /**
     * Model
     *
     * @var    object
     * @since  1.0
     */
    protected $model;

    /**
     * Options
     *
     * @var    array
     * @since  1.0
     */
    protected $options;

    /**
     * Parameters
     *
     * @var    array
     * @since  1.0
     */
    protected $parameters;

    /**
     * Plugins
     *
     * @var    array
     * @since  1.0
     */
    protected $plugins;

    /**
     * Plugin Array
     *
     * @var    array
     * @since  1.0
     */
    protected $plugin_array = array();

    /**
     * Profiler Instance
     *
     * @var    object
     * @since  1.0
     */
    protected $profiler_instance;

    /**
     * Query Results
     *
     * @var    string
     * @since  1.0
     */
    protected $query_results;

    /**
     * Rendered Output
     *
     * @var    array
     * @since  1.0
     */
    protected $rendered_output;

    /**
     * Row
     *
     * @var    object
     * @since  1.0
     */
    protected $row;

    /**
     * View Path
     *
     * @var    string
     * @since  1.0
     */
    protected $view_path;

    /**
     * View Path URL
     *
     * @var    array
     * @since  1.0
     */
    protected $view_path_url;

    /**
     * List of Plugin Property Array
     *
     * Must match Plugin class $property_array Property
     *
     * @var    object
     * @since  1.0
     */
    protected $property_array = array(
        'current_date',
        'event_array',
        'event_plugin_array',
        'getService',
        'setService',
        'cloneService',
        'removeService',
        'include_parse_exclude_until_final',
        'include_parse_sequence',
        'model_registry_name',
        'model_registry',
        'model',
        'on',
        'options',
        'property_array',
        'parameters',
        'plugin_array',
        'profiler_instance',
        'plugins',
        'query_results',
        'rendered_output',
        'row',
        'view_path',
        'view_path_url'
    );

    /**
     * Constructor
     *
     * @param   array $options
     *
     * @since   1.0
     * @api
     */
    public function __construct(array $options = array())
    {
        $this->options = $options;

        $this->frontcontroller = null;
        if (isset($options['frontcontroller'])) {
            $this->frontcontroller = $options['frontcontroller'];
        }

        $this->profiler_instance = null;
        if (isset($options['profiler'])) {
            $this->profiler_instance = $options['profiler'];
        }

        $this->current_date = null;
        if (isset($options['current_date'])) {
            $this->current_date = $options['current_date'];
        }

        $this->on = false;
        if (isset($options['on'])) {
            $this->on = $options['on'];
        }

        $this->event_array = array();
        if (isset($options['event_array'])) {
            $this->event_array = $options['event_array'];
        }

        $this->plugins = array();
        if (isset($options['plugins'])) {
            $this->plugins = $options['plugins'];
        }

        $this->plugin_array = array();
        if (isset($options['plugin_array'])) {
            $this->plugin_array = $options['plugin_array'];
        }

        $this->event_plugin_array = array();
        if (isset($options['event_plugin_array'])) {
            $this->event_plugin_array = $options['event_plugin_array'];
        }
    }

    /**
     * get property
     *
     * @param  string $key
     * @param  string $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  EventException
     */
    public function get($key, $default = '')
    {
        $key = strtolower($key);

        if ($key == 'events') {
            $key = 'event_array';
        }

        if ($key == 'plugins') {
            $plugins = array();
            foreach ($this->event_plugin_array as $x) {
                if ($x->event == $default || $default == '') {
                    $plugin           = $this->plugin_array[$x->plugin];
                    $plugins[$plugin] = $x->plugin;
                }
            }

            return $plugins;
        }

        if (in_array($key, $this->property_array)) {
        } else {
            throw new EventException
            ('Event Service: attempting to set value for unknown key: ' . $key);
        }

        if (isset($this->$key)) {
        } else {
            $this->$key = $default;
        }

        return $this->$key;
    }

    /**
     * set property
     *
     * @param   string $key
     * @param   string $value
     *
     * @return  $this
     * @since   1.0
     * @throws  EventException
     */
    public function set($key, $value)
    {
        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            throw new EventException
            ('Event Service: attempting to set value for unknown key: ' . $key);
        }

        $this->$key = $value;

        return;
    }

    /**
     * The application schedules events at various points within the system.
     *
     * Usage:
     * $event->scheduleEvent('onAfterDelete', $arguments, $selections);
     *
     * In response, the Event Service fires off plugins meeting this criteria:
     *
     * - published (or archived);
     * - registered for the scheduled event;
     * - associated with the current extension;
     * - authorised for use by the user.
     *
     * @param   string $event
     * @param   array  $arguments
     * @param   array  $selections
     *
     * @return  boolean
     *
     * @since   1.0
     */
    public function scheduleEvent($event, $arguments = array(), $selections = array())
    {
        if (defined('PROFILER_ON') && PROFILER_ON === true) {
            $this->profiler_instance->set(
                'message',
                'Event Service: Initiated Scheduling of Event ' . $event,
                'Plugins',
                1
            );
        }

        echo ' Event Schedule: ' . $event . '<br />';

        if (in_array(strtolower($event), $this->event_array)
            || count($this->event_plugin_array) > 0
        ) {
        } else {

            if (defined('PROFILER_ON') && PROFILER_ON === true) {
                $this->profiler_instance->set(
                    'message',
                    'Event Service: ' . $event . ' has no registrations',
                    'Plugins',
                    1
                );
            }

            return $arguments;
        }

        $compareSelection = array();

        if (count($selections) > 0 && is_array($selections)) {
            foreach ($selections as $s) {
                $compareSelection[] = strtolower($s . 'Plugin');
            }
        }

        $scheduledEventPlugins = array();

        foreach ($this->event_plugin_array as $x) {

            if ($x->event == strtolower($event)) {

                if (count($compareSelection) == 0
                    || in_array(strtolower($x->plugin), $compareSelection)
                ) {
                    $temp_row = $x;

                    $temp_row->plugin_class_name = $this->plugin_array[$x->plugin];
                    $temp_row->model_name        = $x->model_name;
                    $temp_row->model_type        = $x->model_type;

                    $scheduledEventPlugins[] = $temp_row;
                }
            }
        }

        if (count($scheduledEventPlugins) == 0) {

            if (defined('PROFILER_ON') && PROFILER_ON === true) {
                $this->profiler_instance->set('message', 'Event: ' . $event . ' has no registrations', 'Plugins', 1);
            }

            return $arguments;
        }

        foreach ($scheduledEventPlugins as $selection) {

            $plugin_class_name = $selection->plugin_class_name;

            if (method_exists($plugin_class_name, $event)) {
                $results = $this->processPluginClass($plugin_class_name, $event, $arguments);
                if ($results === false) {
                    return false;
                }
                $arguments = $results;
            }
        }

        if (defined('PROFILER_ON') && PROFILER_ON === true) {
            $this->profiler_instance->set(
                'message',
                'Event Service: Finished EventSchedule for Event: ' . $event,
                'Plugins',
                1
            );
        }

        return $arguments;
    }

    /**
     * Instantiate the Plugin Class.
     *
     * Establish initial property values given arguments passed in (could include changes other plugins made).
     * Load Fields for Model Registry, if in the arguments, for Plugin use.
     * Execute each qualified plugin, one at a time, until all have been processed.
     * Return arguments, which could contain changed data, to the calling class.
     *
     * @param   string $plugin_class_name includes namespace
     * @param   string $event
     * @param   array  $arguments
     *
     * @return  array|bool
     * @since   1.0
     * @throws  EventException
     */
    public function processPluginClass($plugin_class_name, $event, $arguments = array())
    {
        try {
            $plugin = new $plugin_class_name();

        } catch (Exception $e) {
            throw new Exception('Event Service: ' . $event
            . ' processPluginclass failure instantiating: ' . $plugin_class_name);
        }

        if (defined('PROFILER_ON') && PROFILER_ON === true) {

            $this->profiler_instance->set(
                'message',
                'Event:' . $event . ' firing Plugin: ' . $plugin_class_name,
                'Plugins',
                1
            );
        }

        $plugin->set('frontcontroller', $this->frontcontroller);

        $plugin->set('plugin_class_name', $plugin_class_name);

        $plugin->set('plugin_event', $event);

        $plugin->set('current_date', $this->current_date);

        if (count($arguments) > 0) {

            foreach ($arguments as $key => $value) {

                if (in_array($key, $this->property_array)) {
                    $plugin->set($key, $value, '');

                } else {
                    throw new EventException('Event Service: ' . $event .
                    ' Plugin ' . $plugin_class_name .
                    ' attempting to set value for unknown property: ' . $key);
                }
            }
        }

        $plugin->initialise();

        $results = $plugin->$event();

        if ($results === false) {
            // plugin will throw Exception if warranted, otherwise, a false means "don't update data"
        } else {

            if (count($arguments) > 0) {

                foreach ($arguments as $key => $value) {

                    if (in_array($key, $this->property_array)) {
                        $arguments[$key] = $plugin->get($key);

                    } else {
                        throw new EventException('Event Service: ' . $event .
                        ' Plugin ' . $plugin_class_name .
                        ' attempting to set value for unknown property: ' . $key);
                    }
                }
            }
        }

        return $arguments;
    }

    /**
     * Registers all Plugins in the folder
     *
     * Extensions can override Plugins by including a like-named folder in a Plugin directory within the extension
     *
     * The application will find and register overrides at the point in time the extension is used in rendering.
     *
     * Usage:
     * $event->registerPlugin('ExamplePlugin', 'Molajo\\Plugin\\Example');
     *
     * @param   string $plugin_name
     * @param   string $plugin_class_name
     *
     * @return  bool|Event
     * @throws  Exception
     */
    public function registerPlugin($plugin_name = '', $plugin_class_name = '')
    {
        $events = get_class_methods($plugin_class_name);

        if (count($events) > 0) {

            foreach ($events as $event) {

                if (substr($event, 0, 2) == 'on') {
                    $reflectionMethod = new \ReflectionMethod(new $plugin_class_name, $event);
                    $results          = $reflectionMethod->getDeclaringClass();

                    if ($results->name == $plugin_class_name) {
                        $this->registerPluginEvent($plugin_name, $plugin_class_name, $event);
                    }
                }
            }
        }

        sort($this->event_array);
        ksort($this->plugin_array);
        sort($this->event_plugin_array);

        return $this;
    }

    /**
     * Plugins register for events. When the event is scheduled, the plugin will be executed.
     *
     * The last plugin to register is the one that will be invoked.
     *
     * Installed plugins are registered during Application startup process.
     * Other plugins can be created and dynamically registered using this method.
     * Plugins can be overridden by registering after the installed plugins.
     *
     * @param   string $plugin_name
     * @param   string $plugin_class_name
     * @param   string $event
     *
     * @return  void
     * @since   1.0
     */
    public function registerPluginEvent($plugin_name, $plugin_class_name, $event)
    {
        $event = strtolower($event);

        // $this->plugin_array['AssetPlugin'] = 'Molajo\\Asset';
        $this->plugin_array[$plugin_name] = $plugin_class_name;

        // $this->event_array = 'onBeforeRegisterPlugins';
        if (in_array($event, $this->event_array)) {
        } else {
            $this->event_array[] = $event;
        }

        $list                     = $this->event_plugin_array;
        $this->event_plugin_array = array();

        $found = false;
        if (count($list) > 0) {
            foreach ($list as $single) {
                if ($event == $single->event) {
                    if ($plugin_name == $single->plugin) {
                        $found = true;
                    }
                }

                $this->event_plugin_array[] = $single;
            }
        }

        if ($found === true) {
        } else {

            $temp_row = new \stdClass();

            // $this->event_plugin_array = array (
            //      event => 'onBeforeRegisterPlugin',
            //      plugin => 'EventPlugin',
            //      model_name => 'Event',
            //      model_type => 'Plugin'
            //  )

            $temp_row->event      = $event;
            $temp_row->plugin     = $plugin_name;
            $temp_row->model_name = strtolower(substr($plugin_name, 0, strlen($plugin_name) - strlen('Plugin')));
            $temp_row->model_type = 'Plugin';

            $this->event_plugin_array[] = $temp_row;
        }

        if (defined('PROFILER_ON') && PROFILER_ON === true) {
            $this->profiler_instance->set(
                'message',
                'Event Service: Plugin ' . $plugin_name
                . ' scheduled for Event: ' . $event
                . ' will execute from namespace ' . $plugin_class_name,
                'Plugins',
                1
            );
        }

        return;
    }
}
