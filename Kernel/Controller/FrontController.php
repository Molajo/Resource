<?php
/**
 * Front Controller
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Controller;

use stdClass;
use Exception;
use Molajo\Controller\Api\FrontControllerInterface;
use Molajo\IoC\Api\IoCControllerInterface;
use Molajo\Controller\Exception\FrontControllerException;

/**
 * Front Controller
 *
 * 1. Identifies Current Application
 * 2. Loads Application
 * 3. Defines Site Paths for Application
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class FrontController implements FrontControllerInterface
{
    /**
     * Version Constant
     *
     * @const  string
     * @since  1.0
     */
    const VERSION = '0.1.0';

    /**
     * IoC Controller
     *
     * @var     object  Molajo\IoC\Api\IoCControllerInterface
     * @since   1.0
     */
    protected $ioc_controller;

    /**
     * Override Route Catalog ID
     *
     * @var     int
     * @since   1.0
     */
    protected $override_route_catalog_id = 0;

    /**
     * Override Route Path
     *
     * @var     string
     * @since   1.0
     */
    protected $override_route_path;

    /**
     * Override Route Source ID (requires
     *
     * @var     int
     * @since   1.0
     */
    protected $override_route_source_id;

    /**
     * Override Route Catalog Type ID
     *
     * @var     int
     * @since   1.0
     */
    protected $override_route_catalog_type_id;

    /**
     * Constructor
     *
     * @param IoCControllerInterface $ioc_controller
     * @param int                    $override_route_catalog_id
     * @param string                 $override_route_path
     * @param int                    $override_route_source_id
     * @param int                    $override_route_catalog_type_id
     *
     * @since  1.0
     */
    public function __construct(
        IoCControllerInterface $ioc_controller,
        $override_route_catalog_id = 0,
        $override_route_path = '',
        $override_route_source_id = 0,
        $override_route_catalog_type_id = 0
    ) {
        $this->ioc_controller                 = $ioc_controller;
        $this->override_route_catalog_id      = $override_route_catalog_id;
        $this->override_route_path            = $override_route_path;
        $this->override_route_source_id       = $override_route_source_id;
        $this->override_route_catalog_type_id = $override_route_catalog_type_id;
    }

    /**
     * Initialise the Application
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\FrontControllerException
     */
    public function driver()
    {
        set_error_handler(array($this, 'handleErrors'));
        register_shutdown_function(array($this, 'shutdown'));

        $this->processStep('initialise');
        $this->processStep('route');
        $this->processStep('authorise');
        $this->processStep('resource');
        $this->processStep('execute');
        $this->processStep('render');

        define('NormalEnding', true);

        restore_error_handler();

        return $this;
    }

    /**
     * Process each Step and run the scheduleEvent processing
     *
     * @param   string $step
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\FrontControllerException
     */
    public function processStep($step)
    {
        echo 'STEP:   ' . $step . '<br />';

        /** Step 1: Run the Step */
        try {
            $this->$step();
            /** Step 2: Catch and rethrow Exception */
        } catch (Exception $e) {
            throw new FrontControllerException ($e->getMessage());
        }

        /** Step 3: After Event Processing */
        $this->scheduleEvent('onAfter' . ucfirst(strtolower($step)));

        /** Step 4: Complete */
        return $this;
    }

    /**
     * Execute methods within steps and handle errors
     *
     * @param  object $class
     * @param  string $method
     * @param  array  $options
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\FrontControllerException
     */
    public function processStepMethod($class, $method, $options = array())
    {
        echo 'METHOD: ' . $method . '<br />';

        try {
            $results = call_user_func_array(array($class, $method), $options);
        } catch (Exception $e) {
            throw new FrontControllerException ($e->getMessage());
        }

        if (isset($results->error_code) && (int)$results->error_code > 0) {
            $this->handleErrors();
        }

        return $results;
    }

    /**
     * Event Processing
     *
     * @param   $event_name
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\FrontControllerException
     */
    public function scheduleEvent($event_name)
    {
        /** Step 1: Get Dispatcher */
        $dispatcher = $this->getService('Dispatcher');

        /** Step 2: Create Event */
        $event = $this->getService('Event', array('event_name' => $event_name));

        /** Step 3: Parameters */
        $parameters = $this->getService('Parameters');

        /** Step 4: Schedule Event */
        $parameters = $dispatcher->scheduleEvent($event, $parameters);

        /** Step 5: Save Dispatcher */
        $this->setService('Dispatcher', $dispatcher);

        /** Step 6: Save Data */
        $this->setService('Parameters', $parameters);

        /** Step 7: Complete */
        return $this;
    }

    /**
     * Error Handling
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\FrontControllerException
     */
    public function handleErrors()
    {
        //$this->getService('ErrorHandling');
        /**
         *
         * $this->redirect->set('url', $parameters->redirect_to_id);
         * $this->redirect->set('status_code', 301);
         * $this->redirect->redirect();
         *
         * echo 'In Frontcontroller->processStepMethod for step ' . $method . '<br />';
         * echo 'Error Code: ' . $parameters->error_code;
         * echo 'Redirect To ID: ' . $parameters->redirect_to_id;
         * die;
         *
         *
         * ob_start();
         * if ( is_callable($this->error) ) {
         * call_user_func_array($this->error, array($argument));
         * } else {
         * call_user_func_array(array($this, 'defaultError'), array($argument));
         * }
         *
         * return ob_get_clean();
         */
    }

    /**
     * Exception Handling
     * http://ralphschindler.com/2010/09/15/exception-best-practices-in-php-5-3
     */

    /**
     * Shutdown the application
     *
     * @return  void
     * @since   1.0
     */
    public function shutdown()
    {
        if (defined('NormalEnding')) {
            echo 'Normal Shutdown';
        } else {
            echo 'Failed Run';
        }

        exit(0);
    }

    /**
     * Process a Set of Service Requests
     *
     * @param   array $batch_services (array [$service_name] => $options)
     *
     * @return  array (array ['service_name'] => $application)
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ControllerException
     */
    protected function getServices(array $batch_services = array())
    {
        return $this->ioc_controller->getServices($batch_services);
    }

    /**
     * Create a Class Instance (Service) and its dependencies (and those services and their dependencies, etc.)
     *
     * @param   string $service_name
     * @param   array  $options
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ControllerException
     */
    protected function getService($service_name, array $options = array())
    {
        return $this->ioc_controller->getService($service_name, $options);
    }

    /**
     * Store Instance in the Inversion of Control Container
     *
     * @param   string      $service_name
     * @param   object      $instance
     * @param   null|string $alias
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ControllerException
     */
    protected function setService($service_name, $instance, $alias = null)
    {
        return $this->ioc_controller->setService($service_name, $instance, $alias);
    }

    /**
     * Clone Instance in the Inversion of Control Container
     *
     * @param   string $container_key
     *
     * @return  null|object
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ControllerException
     */
    protected function cloneService($container_key)
    {
        return $this->ioc_controller->cloneService($container_key);
    }

    /**
     * Remove Instance in the Inversion of Control Container
     *
     * @param   string $container_key
     *
     * @return  $this
     * @since   1.0
     */
    protected function removeService($container_key)
    {
        return $this->ioc_controller->removeService($container_key);
    }

    /**
     * Initialise the Application
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\FrontControllerException
     */
    protected function initialise()
    {
        /** Step 1: Error codes */
        $parameters                 = new stdClass();
        $parameters->error_code     = 0;
        $parameters->redirect_to_id = 0;

        $this->setService('Parameters', $parameters, 'parameters');

        /** Step 2: Resources, includes Autoloader */
        $this->getService('Resources');

        /** Step 3: Site */
        $site = $this->getService('Site');

        $site->setBaseURL();
        $parameters->reference_data = $site->setReferenceData();
        $this->setService('Parameters', $parameters);
        $this->sortParameters('reference_data');

        $site->identifySite();

        $parameters->site = $site->get('*');
        $this->setService('Parameters', $parameters);

        /** Step 4: Request */
        $parameters->request = $this->getService('Request');

        /** Step 5: Application */
        $application = $this->getService('Application');
        $application->setApplication();

        $application->verifySiteApplication($parameters->site->id);

        $configuration = $application->getConfiguration();
        $this->setService('Application', $configuration);
        $parameters->application = $configuration;

        $parameters->site->cache_folder =
            BASE_FOLDER
                . $parameters->site->base_folder
                . '/'
                . $parameters->application->parameters->system_cache_folder;

        $parameters->site->logs_folder =
            BASE_FOLDER
            . $parameters->site->base_folder
            . '/'
            . $parameters->application->parameters->system_logs_folder;

        $parameters->site->media_folder =
            BASE_FOLDER
            . $parameters->site->base_folder
            . '/'
            . $parameters->application->parameters->system_media_folder;

        $parameters->site->temp_folder =
            BASE_FOLDER
            . $parameters->site->base_folder
            . '/'
            . $parameters->application->parameters->system_temp_folder;

        $parameters->site->temp_url =
            $parameters->site_base_url
            . '/'
            . $configuration->parameters->system_temp_url;

        $this->setService('Parameters', $parameters);
        $this->sortParameters('Application');
        $this->sortParameters('Request');
        $this->sortParameters('Site');

        $schedule_services          = array();
        $schedule_services['Cache'] = array();
        $schedule_services['Email'] = array();
        $schedule_services['User']  = array();

        //$this->schedule_service['Log'] = array();
        $this->getServices($schedule_services);

        /** Step 6: User */
        $user             = $this->getService('User');
        $parameters->user = $user->getUserData('*');
        $this->setService('Parameters', $parameters);
        $this->sortParameters('User');

        /** Step 7: Rendering Extensions */
        $cache = $this->getService('Cache');
        $cache_results = $cache->get(serialize('Renderingextensions'));

        if ($cache_results->isHit === false) {
            $item = $this->getService('Renderingextensions');
            $cache->set(serialize('Renderingextensions'), serialize($item));
        } else {
            $this->setService('Renderingextensions', $cache_results->value);
        }

        return $this;
    }

    /**
     * Route the Application
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\FrontControllerException
     */
    protected function route()
    {
        /** Step 1. Initiate Route Controller */
        $options                                   = array();
        $options['override_route_catalog_id']      = $this->override_route_catalog_id;
        $options['override_route_path']            = $this->override_route_path;
        $options['override_route_source_id']       = $this->override_route_source_id;
        $options['override_route_catalog_type_id'] = $this->override_route_catalog_type_id;
        $options['parameters']                     = $this->getService('Parameters');

        $route = $this->getService('Route', $options);

        /** Step 2. Verify Route and Reroute, if necessary */
        $this->processStepMethod($route, 'verifySecureProtocol');
        $this->processStepMethod($route, 'verifyHome');

        /** Step 3. Extract Action, Task, Filters from Request */
        $this->processStepMethod($route, 'setRequest');

        /** Step 4. Get Route Information: Catalog  */
        $parameters = $this->processStepMethod($route, 'setRoute');

        /** Step 5. Set Page Type for Resource Query */
        $parameters->page_type = $parameters->route->page_type;

        /** Step 6. Save and Sort Parameters */
        $this->setService('Parameters', $parameters);
        $this->sortParameters('Route');

        /** Step 7. Complete */
        return $this;
    }

    /**
     * Authorise User
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\FrontControllerException
     */
    protected function authorise()
    {
        /** Step 1. Initiate Authorisation Controller */
        $authorisation = $this->getService('Authorisation');

        /** Step 2.  Authorised to Access Site */
        $options    = array(
            'action_id'  => null,
            'catalog_id' => null,
            'type'       => 'Site'
        );
        $authorised = $this->processStepMethod($authorisation, 'isUserAuthorised', $options);
        if ($authorised === false) {
            // 301 redirect
        }

        /** Step 3. Authorised to access Application */
        $parameters = $this->getService('Parameters');
        $options    = array(
            'action_id'  => null,
            'catalog_id' => $parameters->application->catalog_id,
            'type'       => 'Application'
        );
        $authorised = $this->processStepMethod($authorisation, 'isUserAuthorised', $options);
        if ($authorised === false) {
            // 301 redirect
        }

        /** Step 4. Authorised for Catalog */
        $action_id  = $authorisation->getActions($parameters->route->action);
        $options    = array(
            'action_id'     => $action_id,
            'catalog_id'    => $parameters->route->catalog_id,
            'view_group_id' => $parameters->route->view_group_id,
            'type'          => 'Catalog'
        );
        $authorised = $this->processStepMethod($authorisation, 'isUserAuthorised', $options);
        if ($authorised === false) {
            // 301 redirect
        }

        /** Step 5. Validate if site is set to offline mode that user has access */
        $authorised = $this->processStepMethod($authorisation, 'isUserAuthorisedOfflineMode');
        if ($authorised === false) {
            // 301 redirect
        }

        /** Step 3. Thresholds: Lockout */
        // IP address
        // Hits
        // Time of day
        // Visits
        // Login Attempts
        // Upload Limits
        // CSFR
        // Captcha Failure

        /** Step 3. Complete */
        return $this;
    }

    /**
     * Get Resource
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\FrontControllerException
     */
    protected function resource()
    {
        /** Step 1. Instantiate Resource Controller */
        $resource = $this->getService('Resource');

        /** Step 2. Get Resource Data */
        $parameters                 = $this->getService('Parameters');
        $parameters->resource->data = new stdClass();
        $parameters->resource->data = $this->processStepMethod($resource, 'getResources');

        /** Step 3. Theme */
        $parameters->resource->theme = $this->processStepMethod(
            $resource,
            'getTheme',
            array('theme_id' => $parameters->resource->data->parameters->theme_id)
        );

        /** Step 4. Page View */
        $parameters->resource->page_view = $this->processStepMethod(
            $resource,
            'getPageView',
            array('page_view_id' => $parameters->resource->data->parameters->page_view_id)
        );

        /** Step 5. Template View */
        $parameters->resource->template_view = $this->processStepMethod(
            $resource,
            'getTemplateView',
            array('template_view_id' => $parameters->resource->data->parameters->template_view_id)
        );

        /** Step 6. Wrap View */
        $parameters->resource->wrap_view = $this->processStepMethod(
            $resource,
            'getWrapView',
            array('wrap_view_id' => $parameters->resource->data->parameters->wrap_view_id)
        );

        /** Step 7. Save and sort parameters */
        $this->setService('Parameters', $parameters);
        $this->sortParameters('Resource');

        /** Step 8. Complete */
        return $this;
    }

    /**
     * Execute Route
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\FrontControllerException
     */
    protected function execute()
    {
        /** Step 1. Get Parameter Data */
        $parameters = $this->getService('Parameters');

        /** Step 2. Rendering */
        if ($parameters->route->method == 'GET') {
            return $this->render();
        }

        // do create, update, delete, etc.

        return $this;
    }

    /**
     * Render Application
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\FrontControllerException
     */
    protected function render()
    {
        /** Step 1. Instantiate Resource Controller */
        $render = $this->getService('Render');

        /** Step 1. Instantiate Resource Controller */
        $theme = $render->renderTheme();

//        $render->onBeforeParseEvent();

        $complete = false;
        $loop     = 0;
        while ($complete === false) {

            $loop ++;

            $include_statements = array();

            $render->getIncludeRequests();

            if (count($include_statements) == 0) {
                break;
            }

            $render->processIncludeRequests();

            if ($loop > $this->parameters->reference_data->stop_loop_count) {
                break;
            }
            continue;
        }

        /** Head */
        $this->final_indicator     = true;
        $this->sequence            = $this->final;
        $this->exclude_until_final = array();

        $this->onBeforeParseHeadEvent();

        $complete = false;
        $loop     = 0;
        while ($complete === false) {

            $loop ++;

            $include_statements = array();

            $render->getIncludeRequests();

            if (count($include_statements) == 0) {
                break;
            }

            $render->processIncludeRequests();

            if ($loop > $this->parameters->reference_data->stop_loop_count) {
                break;
            }
            continue;
        }

        /** Rendering is complete */
        $this->onAfterParseEvent();

        /** Step 2. Get Resource Data */
        $parameters                 = $this->getService('Parameters');
        $parameters->render->output = new stdClass();
        $parameters->render->output = $this->processStepMethod($render, 'parseTheme');

        /** Step 7. Save and sort parameters */
        $this->setService('Parameters', $parameters);
        $this->sortParameters('Resource');

        /** Step 8. Complete */
        return $this;

        // echo $body;
        // </body>
        // </html>

        return $this;
    }

    /**
     * Schedule Event onBeforeParseEvent
     *
     * Event runs before any output is rendered (including the Theme file),
     *
     *  The include and exclude values that will be processed by the parsing/rendering process are available
     *  to the plugin, as are the parameters for the primary resource, theme, page, template, etc., and
     *  and the Primary Data Registry, all of which can be modified or used by plugins.
     *
     * Page Handler Plugins are scheduled for this event (List, Item, Edit, and the Menu Item Page Handlers)
     *
     * In general, this event is good for building data that is relevant to the entire page,
     *  like the Application Plugin which sets Page Registry data (ex. current and home URL, menu, metadata, etc.)
     *
     * @return  $this
     * @since   1.0
     */
    public function onBeforeParseEvent()
    {
        $query_results = $this->triggerEvent('onBeforeParse', $this->registry->get('Primary', 'Data'));

        $this->registry->delete('Primary', 'Data');
        $this->registry->createRegistry('Primary');
        $this->registry->set('Primary', 'Data', $query_results);

        $data = $this->registry->get('Primary', 'Data');

        return $data;
    }

    /**
     * Schedule Event onBeforeParseHeadEvent
     *
     * Event runs after the body of the document is fully developed.
     *
     * The include and exclude values that will be processed by the parsing/rendering process are available
     *  to the plugin. All metadata, document links, and assets have been defined and can be modified by plugins.
     *  Rendered content for the document body is available to event plugins.
     *
     * @return  $this
     * @since   1.0
     */
    public function onBeforeParseHeadEvent()
    {
        $this->triggerEvent('onBeforeParseHead', array());

        return $this;
    }

    /**
     * Schedule Event onAfterParseEvent Event
     *
     * Event runs after the entire document has been rendered. The rendered content is available to event plugins.
     *
     * @return  $this
     * @since   1.0
     */
    public function onAfterParseEvent()
    {
        $this->triggerEvent('onAfterParse', array());

        return $this;
    }

    /**
     * Common Method for all Theme Service Events
     *
     * @param   string $event_name
     * @param   string $query_results
     *
     * @return  array|null
     * @since   1.0
     */
    protected function triggerEvent($event_name, $query_results = null)
    {
        if ($query_results === null) {
            $query_results = array();
        }

        if (isset($this->parameters->model_registry_name)) {
            $model_registry_name = $this->parameters->model_registry_name;
            $model_registry      = $this->registry->get($model_registry_name);
        } else {
            $model_registry = array();
        }

        $arguments = array(
            'model'                             => null,
            'model_registry'                    => $model_registry,
            'model_registry_name'               => $this->get('model_registry_name'),
            'parameters'                        => $this->parameters,
            'parameter_property_array'          => $this->parameter_property_array,
            'query_results'                     => $query_results,
            'row'                               => array(),
            'rendered_output'                   => $this->rendered_output,
            'view_path'                         => null,
            'view_path_url'                     => null,
            'plugins'                           => null,
            'include_parse_sequence'            => $this->sequence,
            'include_parse_exclude_until_final' => $this->exclude_until_final
        );

        $arguments = $this->event->scheduleEvent(
            $event_name,
            $arguments,
            $this->getPluginList()
        );

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

        if (isset($arguments['property_array'])) {
            $this->parameters = $arguments['property_array'];
        }

        if (isset($arguments['query_results'])) {
            $query_results = $arguments['query_results'];
        }

        if (isset($arguments['row'])) {
            $query_results = $arguments['row'];
        }

        if (isset($arguments['rendered_output'])) {
            $query_results = $arguments['rendered_output'];
        }

        if (isset($arguments['class_array'])) {
            $query_results = $arguments['class_array'];
        }

        if (isset($arguments['include_parse_sequence'])) {
            $this->sequence = $arguments['include_parse_sequence'];
        }

        if (isset($arguments['include_parse_exclude_until_final'])) {
            $this->exclude_until_final = $arguments['include_parse_exclude_until_final'];
        } else {
            $this->exclude_until_final = array();
        }

        return $query_results;
    }

    /**
     * Get the list of potential plugins identified with this model registry
     *
     * @return  array
     * @since   1.0
     */
    protected function getPluginList()
    {
        $model_registry_name = $this->get('model_registry_name');

        $modelPlugins = array();

        if ((int)$this->registry->get($model_registry_name, 'process_plugins') > 0) {
            $modelPlugins = $this->registry->get($model_registry_name, 'plugins');

            if (is_array($modelPlugins)) {
            } else {
                $modelPlugins = array();
            }
        }

        $templatePlugins = array();

        if ((int)$this->registry->get($model_registry_name, 'process_template_plugins') > 0) {
            $name = $this->registry->get($model_registry_name, 'template_view_path_node');
            if ($name == '') {
            } else {
                $templatePlugins = $this->registry->get(ucfirst(strtolower($name)) . 'Templates', 'plugins');

                if (is_array($templatePlugins)) {
                } else {
                    $templatePlugins = array();
                }
            }
        }

        $plugins = array_merge($modelPlugins, $templatePlugins);
        if (is_array($plugins)) {
        } else {
            $plugins = array();
        }

        $page_type = $this->get('catalog_page_type');
        if ($page_type == '') {
        } else {
            $plugins[] = 'Pagetype' . strtolower($page_type);
        }

        $template = $this->get('template_view_path_node');
        if ($template == '') {
        } else {
            $plugins[] = $template;
        }

        if ((int)$this->registry->get($model_registry_name, 'process_plugins') == 0
            && count($plugins) == 0
        ) {
            $this->plugins;

            return array();
        }

        $plugins[] = 'Application';

        return $plugins;
    }

    /**
     * Sort Parameters
     *
     * @param   $step
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\FrontControllerException
     */
    private function sortParameters($step)
    {
        /** Step 1. Get Parameters  */
        $parameters = $this->getService('Parameters');

        /** Step 2. Sort and Replace Step Object */
        $step              = strtolower($step);
        $input_object      = $parameters->$step;
        $parameters->$step = $this->sortParametersObject($input_object);

        /** Step 3. Sort and Replace Base Object Values */
        $input_object = $parameters;
        $parameters   = $this->sortParametersObject($input_object);

        /** Step 3. Save Parameters */
        $this->setService('Parameters', $parameters);

        return $this;
    }

    /**
     * Sort Object
     *
     * @param   object $input_object
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\FrontControllerException
     */
    private function sortParametersObject($input_object)
    {
        /** Step 1. Load Array with Fields */
        $hold_array = array();

        foreach (\get_object_vars($input_object) as $key => $value) {
            $hold_array[$key] = $value;
        }

        /** Step 2. Sort Array by Key */
        ksort($hold_array);

        /** Step 3. Create New Object */
        $new_object = new stdClass();

        foreach ($hold_array as $key => $value) {
            $new_object->$key = $value;
        }

        /** Step 4. Return Object */
        return $new_object;
    }
}
