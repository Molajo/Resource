<?php
/**
 * Query Handler - Instantiates Model and Controller
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resources\Handler;

use stdClass;
use Exception;
use Molajo\Resources\Api\ResourceHandlerInterface;
use Molajo\Resources\Exception\ResourcesException;
use Molajo\Authorisation\Api\AuthorisationInterface;
use Molajo\Controller\Api\CustomfieldsControllerInterface;
use Molajo\Fieldhandler\Api\FieldHandlerInterface;
use Molajo\Event\Api\EventInterface;
use Molajo\Http\Api\RedirectInterface;
use Molajo\Database\Api\DatabaseInterface;
use Molajo\Registry\Api\RegistryInterface;
use Molajo\User\Api\UserInterface;
use Molajo\Language\Api\LanguageInterface;
use Molajo\Cache\Api\CacheInterface;

/**
 * Query Handler - Instantiates Model and Controller
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class QueryHandler extends XmlHandler implements ResourceHandlerInterface
{
    /**
     * Database Instance
     *
     * @var    object
     * @since  1.0
     */
    protected $database;

    /**
     * Query Object
     *
     * @var    object
     * @since  1.0
     */
    protected $query = null;

    /**
     * Used in queries to determine date validity
     *
     * @var    string
     * @since  1.0
     */
    protected $null_date;

    /**
     * Today's CCYY-MM-DD 00:00:00 formatted for query
     *
     * @var    string
     * @since  1.0
     */
    protected $current_date;

    /**
     * AuthorisationInterface Instance
     *
     * @var    object  Molajo\Authorisation\Api\AuthorisationInterface
     * @since  1.0
     */
    protected $authorisation;

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
     * Cache
     *
     * @var    object  Molajo\Cache\Api\CacheInterface
     * @since  1.0
     */
    protected $cache;

    /**
     * Event Instance
     *
     * @var    object
     * @since  1.0
     */
    protected $event;

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
     * Model Instance  Molajo\Model\Api\ReadModelInterface
     *
     * @var    object
     * @since  1.0
     */
    protected $model;

    /**
     * Model Registry - data source/object fields and definitions
     * type, name, model_registry_name, query_object
     *
     * @var    object
     * @since  1.0
     */
    protected $model_registry;

    /**
     * Stores an array of key/value parameters settings
     *
     * @var    object
     * @since  1.0
     */
    protected $parameters = null;

    /**
     * Plugins specified in the table registry for the model registry
     *
     * @var    array
     * @since  1.0
     */
    protected $plugins = array();

    /**
     * Resources Instance
     *
     * @var    object
     * @since  1.0
     */
    protected $resources;

    /**
     * Registry Instance  Molajo\Registry\Api\RegistryInterface
     *
     * @var    object
     * @since  1.0
     */
    protected $registry;

    /**
     * Controller Instance  Molajo\Controller\Api\ReadControllerInterface
     *
     * @var    object
     * @since  1.0
     */
    protected $controller;

    /**
     * Redirect Instance  Molajo\Http\Api\RedirectInterface
     *
     * @var    object
     * @since  1.0
     */
    protected $redirect;

    /**
     * List of Properties
     *
     * @var    array
     * @since  1.0
     */
    protected $property_array = array(
        'database',
        'query',
        'null_date',
        'current_date',
        'authorisation',
        'fieldhandler',
        'event',
        'resources',
        'registry',
        'cache',
        'user',
        'language',
        'controller',
        'model',
        'model_registry',
        'parameters',
        'view_path_url',
        'view_path',
        'plugins',
        'redirect'
    );

    /**
     * Constructor
     *
     * @param DatabaseInterface               $database
     * @param                                 $query
     * @param string                          $null_date
     * @param string                          $current_date
     * @param AuthorisationInterface          $authorisation
     * @param FieldHandlerInterface           $fieldhandler
     * @param CustomfieldsControllerInterface $customfields
     * @param EventInterface                  $event
     * @param Object                          $resources
     * @param RegistryInterface               $registry
     * @param CacheInterface                  $cache
     * @param UserInterface                   $user
     * @param LanguageInterface               $language
     * @param RedirectInterface               $redirect
     *
     * @since  1.0
     */
    public function __construct(
        DatabaseInterface $database,
        $query,
        $null_date,
        $current_date,
        AuthorisationInterface $authorisation,
        FieldHandlerInterface $fieldhandler,
        CustomfieldsControllerInterface $customfields,
        EventInterface $event,
        $resources,
        RegistryInterface $registry,
        CacheInterface $cache = null,
        UserInterface $user,
        LanguageInterface $language,
        RedirectInterface $redirect
    ) {
        $this->database      = $database;
        $this->query         = $query;
        $this->null_date     = $null_date;
        $this->current_date  = $current_date;
        $this->authorisation = $authorisation;
        $this->fieldhandler  = $fieldhandler;
        $this->customfields  = $customfields;
        $this->event         = $event;
        $this->resources     = $resources;
        $this->registry      = $registry;
        $this->cache         = $cache;
        $this->user          = $user;
        $this->language      = $language;
        $this->redirect      = $redirect;
        $this->parameters    = new stdClass();
    }

    /**
     * Handle requires located file
     *
     * @param   string $scheme
     * @param   string $located_path
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function handlePath($scheme, $located_path, array $options = array())
    {
        $this->query = $this->database->getQueryObject(true);

        if (isset($options['namespace'])) {
        } else {
            throw new ResourcesException
            ('Resources XmlHandler handlePath options array must have namespace entry.');
        }

        $segments = explode('//', $options['namespace']);
        if (count($segments > 2)) {
        } else {
            throw new ResourcesException
            ('Resources XmlHandler Failure namespace must have at least 3 segments:  ' . $options['namespace']);
        }

        $this->model_registry = $options['xml'];

        if (isset($options['parameters'])) {
            $this->parameters = $options['parameters'];
        }

        if (isset($this->model_registry['plugins'])) {
            $this->plugins = $this->model_registry['plugins'];
        } else {
            $this->plugins                   = array();
            $this->model_registry['plugins'] = array();
        }

        if (isset($this->model_registry['query_object'])) {
        } else {
            $this->model_registry['query_object'] = 'list';
        }

        if (isset($this->model_registry['name_key'])) {
        } else {
            $this->model_registry['name_key'] = null;
        }

        if (isset($this->model_registry['name_key_value'])) {
        } else {
            $this->model_registry['name_key_value'] = null;
        }

        if (isset($this->model_registry['name_key'])) {
        } else {
            $this->model_registry['name_key'] = null;
        }

        if (isset($this->model_registry['model_offset'])) {
        } else {
            $this->model_registry['model_offset'] = 0;
        }

        if (isset($this->model_registry['model_count'])) {
        } else {
            $this->model_registry['model_count'] = 20;
        }

        if (isset($this->model_registry['use_pagination'])) {
        } else {
            $this->model_registry['use_pagination'] = 1;
        }

        $this->createReadModel();

        $this->createReadController();

        return $this->controller;
    }

    /**
     * Create Model Instance
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function createReadModel()
    {
        $class = 'Molajo\\Model\\ReadModel';

        $this->query = $this->database->getQueryObject();

        $site_id = 2;
        try {
            $this->model = new $class (
                $this->model_registry,
                $this->database,
                $this->null_date,
                $this->current_date,
                $this->query,
                $this->authorisation,
                $this->fieldhandler,
                $this->cache,
                $site_id,
                $this->parameters->application->id
            );

        } catch (Exception $e) {
            throw new ResourcesException ('Resources Query Handler Failed Instantiating Model: '
            . $e->getMessage());
        }

        return $this;
    }

    /**
     * Create Controller Instance
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function createReadController()
    {
        $class = 'Molajo\\Controller\\ReadController';

        if (isset($this->parameters->page_type)) {
            $page_type = $this->parameters->page_type;
        } else {
            $page_type = null;
        }

        try {
            $this->controller = new $class (
                $this->authorisation,
                $this->user,
                $this->language,
                $this->model,
                $this->parameters,
                $page_type,
                $this->fieldhandler,
                $this->customfields,
                $this->event,
                $this->cache,
                $this->plugins,
                $this->redirect
            );

        } catch (Exception $e) {
            throw new ResourcesException ('Resources Query Handler Failed Instantiating Controller: '
            . $e->getMessage());
        }

        return $this;
    }

    /**
     * Retrieve a collection of a specific handler
     *
     * @param   string $scheme
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function getCollection($scheme, array $options = array())
    {
        return null;
    }
}
