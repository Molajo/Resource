<?php
/**
 * Controller
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Controller;

use Molajo\Authorisation\Api\AuthorisationInterface;
use Molajo\Controller\Api\CustomfieldsControllerInterface;
use Molajo\User\Api\UserInterface;
use Molajo\Model\Api\ModelInterface;
use Molajo\Language\Api\LanguageInterface;
use Molajo\Controller\Api\ControllerInterface;
use Molajo\Fieldhandler\Api\FieldHandlerInterface;
use Molajo\Event\Api\EventInterface;
use Molajo\Controller\Exception\ControllerException;
use Molajo\Cache\Api\CacheInterface;
use Molajo\Http\Api\RedirectInterface;

/**
 * Primary controller responsible to retrieve configuration for model registries, interact with models,
 * data objects, and perform event scheduling for data object connectivity and before and after read.
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Controller implements ControllerInterface
{
    /**
     * Authorisation Instance
     *
     * @var    object  Molajo\Authorisation\Api\AuthorisationInterface
     * @since  1.0
     */
    protected $authorisation;

    /**
     * User Instance  Molajo\User\Api\UserInterface
     *
     * @var    object
     * @since  1.0
     */
    protected $user;

    /**
     * Language Instance Molajo\Language\Api\LanguageInterface
     *
     * @var    object
     * @since  1.0
     */
    protected $language;

    /**
     * Model Instance  Molajo\Model\Api\ModelInterface
     *
     * @var    object
     * @since  1.0
     */
    public $model;

    /**
     * Stores an array of key/value parameters settings
     *
     * @var    object
     * @since  1.0
     */
    protected $parameters = null;

    /**
     * Page Type
     *
     * @var    string
     * @since  1.0
     */
    protected $page_type;

    /**
     * Fieldhandler Instance (Validation, Filtering, Formatting/Escaping)
     *
     * @var    object
     * @since  1.0
     */
    protected $fieldhandler;

    /**
     * Customfields Controller
     *
     * @var    object  Molajo\Controller\Api\CustomfieldsControllerInterface
     * @since  1.0
     */
    protected $customfields;

    /**
     * Event Instance
     *
     * @var    object
     * @since  1.0
     */
    protected $event;

    /**
     * Cache Instance
     *
     * @var    object
     * @since  1.0
     */
    protected $cache;

    /**
     * Plugins specified in the table registry for the model registry
     *
     * @var    array
     * @since  1.0
     */
    protected $plugins = array();

    /**
     * Redirect Instance
     *
     * @var    object  Molajo\Http\Api\RedirectInterface
     * @since  1.0
     */
    protected $redirect;

    /**
     * Set of rows returned from a query
     *
     * @var    array
     * @since  1.0
     */
    protected $query_results = array();

    /**
     * Single set of $query_results and used in create, update, delete operations
     *
     * @var    object
     * @since  1.0
     */
    protected $row;

    /**
     * Rendered Output
     *
     * @var    string
     * @since  1.0
     */
    protected $rendered_output;

    /**
     * List of Controller Properties
     *
     * @var    object
     * @since  1.0
     */
    protected $property_array = array(
        'authorisation',
        'user',
        'language',
        'model',
        'parameters',
        'page_type',
        'fieldhandler',
        'customfields',
        'event',
        'cache',
        'plugins',
        'redirect',
        'query_results',
        'row',
        'rendered_output',

    );

    /**
     * Class Constructor
     *
     * @param   array $options
     *
     * @since   1.0
     */
    public function __construct(
        AuthorisationInterface $authorisation,
        UserInterface $user = null,
        LanguageInterface $language = null,
        ModelInterface $model,
        $parameters,
        $page_type = null,
        FieldHandlerInterface $fieldhandler,
        CustomfieldsControllerInterface $customfields,
        EventInterface $event,
        CacheInterface $cache,
        $plugins = null,
        RedirectInterface $redirect
    ) {
        $this->authorisation = $authorisation;
        $this->user          = $user;
        $this->language      = $language;
        $this->model         = $model;
        $this->parameters    = $parameters;
        $this->page_type     = $page_type;
        $this->fieldhandler  = $fieldhandler;
        $this->customfields  = $customfields;
        $this->event         = $event;
        $this->cache         = $cache;
        $this->plugins       = $plugins;
        $this->redirect      = $redirect;

        if ($this->model->getModelRegistry('query_object')) {
            $query_object = $this->model->getModelRegistry('query_object');
        } else {
            $query_object = '';
        }

        if (in_array($query_object, array('result', 'item', 'list', 'distinct'))) {
        } else {
            $this->model->setModelRegistry('query_object', 'list');
        }

        $this->setPluginList();
    }

    /**
     * Get the current value (or default) of the specified property
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ControllerException
     */
    public function get($key, $default = null)
    {
        if (in_array($key, $this->property_array)) {
        } else {
            throw new ControllerException('Controller Get: Unknown key: ' . $key);
        }

        if ($this->$key === null) {
            $this->$key = $default;
        }

        return $this->$key;
    }

    /**
     * Set the value of the specified property
     *
     * @param   string $key
     * @param   string $value
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ControllerException
     */
    public function set($key, $value = null)
    {
        if (in_array($key, $this->property_array)) {
        } else {
            throw new ControllerException('Controller Set: Unknown key: ' . $key);
        }

        $this->$key = $value;

        return $this;
    }

    /**
     * Get a specified Model key value
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ControllerException
     */
    public function getModel($key, $default = null)
    {
        if ($this->model->$key === null) {
            $this->model->$key = $default;
        }

        return $this->model->$key;
    }

    /**
     * Set a specified Model key value
     *
     * @param   string $key
     * @param   string $value
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ControllerException
     */
    public function setModel($key, $value = null)
    {
        $this->model->$key = $value;

        return $this;
    }

    /**
     * Get the current value (or default) of the Model Registry
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ControllerException
     */
    public function getModelRegistry($key, $default = null)
    {
        return $this->model->getModelRegistry($key, $default);
    }

    /**
     * Set the value of the specified Model Registry
     *
     * @param   string $key
     * @param   string $value
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ControllerException
     */
    public function setModelRegistry($key, $value = null)
    {
        $this->model->setModelRegistry($key, $value);

        return $this;
    }

    /**
     * Get the list of potential plugins identified with this model registry
     *
     * @return  void
     * @since   1.0
     */
    public function setPluginList()
    {
        $this->plugins = array();

        if (defined('ROUTE')) {
        } else {
            return;
        }

        $model_registry = $this->model_registry;
        $parameters     = $this->parameters;

        if ($model_registry === null) {
            return;
        }

        if ($this->model->getModelRegistry('query_object') == 'result') {
            return;
        }

        $model_plugins = array();

        if ((int)$this->model->getModelRegistry('process_plugins') > 0) {

            $model_plugins = $this->model->getModelRegistry('plugins');

            if (is_array($model_plugins) & count($model_plugins) > 0) {
            } else {
                $model_plugins = array();
            }
        }

        $template_plugins = array();

        if ((int)$this->model->getModelRegistry('process_template_plugins') > 0) {
            if ($this->parameters->template_view_path_node == '') {
            } else {
                $template_plugins = $this->parameters->template_plugins;

                if (is_array($template_plugins) & count($template_plugins) > 0) {
                } else {
                    $template_plugins = array();
                }
            }
        }

        $plugins = array_merge($model_plugins, $template_plugins);

        if (is_array($plugins)) {
        } else {
            $plugins = array();
        }

        if ($this->parameters->catalog->page_type == '') {
        } else {
            $plugins[] = 'Pagetype' . strtolower($this->parameters->catalog->page_type);
        }

        if ($this->parameters->template_view_path_node == '') {
        } else {
            $plugins[] = $this->parameters->template_view_path_node;
        }

        if ((int)$this->model->getModelRegistry('process_plugins') == 0 && count($plugins) == 0) {
            $this->plugins = array();
            return;
        }

        $plugins[] = 'Application';

        $this->plugins = $plugins;

        return;
    }

    /**
     * Common code for setting the controller properties, given various events
     *
     * @param   $arguments
     *
     * @return  bool
     * @since   1.0
     */
    protected function setPluginResultProperties($arguments)
    {
        if (isset($arguments['model'])) {
            $this->model = $arguments['model'];
        } else {
            $this->model = array();
        }

        if (isset($arguments['model_registry'])) {
            $this->model_registry = $arguments['model_registry'];
        } else {
            $this->model_registry = array();
        }

        if (isset($arguments['model_registry_name'])) {
            $this->model->setModelRegistry('model_registry_name', $arguments['model_registry_name']);
        }

        if (isset($arguments['parameters'])) {
            $this->parameters = $arguments['parameters'];
        } else {
            $this->parameters = array();
        }

        if (isset($arguments['row']) && $arguments['row'] !== null) {
            $this->query_results[] = $arguments['row'];
            $this->row             = $arguments['row'];
        } elseif (isset($arguments['query_results'])) {
            $this->row = null;
        } else {
            $this->query_results = array();
            $this->row           = null;
        }

        if (isset($arguments['rendered_output'])) {
            $this->rendered_output = $arguments['rendered_output'];
        } else {
            $this->rendered_output = array();
        }

        if (isset($arguments['plugins'])) {
            $this->plugins = $arguments['plugins'];
        } else {
            $this->plugins = array();
        }

        return true;
    }

    /**
     * Set Profiler Messages
     *
     * @param   array $attributes
     *
     * @return  $this
     * @since   1.0
     */
    public function setProfilerMessage($attributes = array())
    {
    }
}
