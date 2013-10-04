<?php
/**
 * Data Class
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Controller;

use Molajo\Controller\Api\DataInterface;
use Molajo\Controller\Exception\DataException;

/**
 * Data Class
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Data implements DataInterface
{
    /**
     * Site Instance
     *
     * @var    array    Molajo\Controller\Api\SiteInterface
     * @since  1.0
     */
    protected $site = null;

    /**
     * Application Instance
     *
     * @var    array    Molajo\Controller\Api\ApplicationInterface
     * @since  1.0
     */
    protected $application = null;

    /**
     * User Object
     *
     * @var    object
     * @since  1.0
     */
    protected $user = null;

    /**
     * Language Instance
     *
     * @var    object  Molajo\Event\Api\ListenerInterface
     * @since  1.0
     */
    protected $language = null;

    /**
     * Authorisation Instance
     *
     * @var    object  Molajo\Authorisation\Api\AuthorisationInterface
     * @since  1.0
     */
    protected $authorisation;
    /**
     * Request Instance
     *
     * @var    array    Molajo\Http\Request\Api\RequestInterface
     * @since  1.0
     */
    protected $request = null;

    /**
     * Response Instance
     *
     * @var    array    Molajo\Http\Request\Api\RequestInterface
     * @since  1.0
     */
    protected $response = null;

    /**
     * Resources
     *
     * @var    object  Molajo\Resources\Api\ResourcesInterface
     * @since  1.0
     */
    protected $resources = null;

    /**
     * Error Code
     *
     * @var    string
     * @since  1.0
     */
    protected $error_code = null;

    /**
     * Error Message
     *
     * @var    string
     * @since  1.0
     */
    protected $error_message = null;

    /**
     * Date Instance
     *
     * @var    object   Molajo\Utilities\Api\DateInterface
     * @since  1.0
     */
    protected $date = null;

    /**
     * Log Instance
     *
     * @var    object  Psr\Log\LoggerInterface
     * @since  1.0
     */
    protected $log = null;

    // remove? get from resources?

    /**
     * Controller
     *
     * @var    object  Molajo\Controller\Api\ControllerInterface
     * @since  1.0
     */
    protected $controller = null;

    /**
     * Today's CCYY-MM-DD 00:00:00 formatted for query
     *
     * @var    string
     * @since  1.0
     */
    protected $current_date;

    /**
     * Null Date 0000-00-00 00:00:00 formatted for query
     *
     * @var    string
     * @since  1.0
     */
    protected $null_date;

    /**
     * Database Instance
     *
     * @var    object   Molajo\Database\Api\DatabaseInterface
     * @since  1.0
     */
    protected $database = null;

    // should these be accessible via resources?

    /**
     * Email Instance
     *
     * @var    object   Molajo\Email\Api\EmailInterface
     * @since  1.0
     */
    protected $email = null;

    /**
     * File Upload Instance
     *
     * @var    object  Molajo\FileUpload\Api\FileUploadInterface
     * @since  1.0
     */
    protected $fileupload = null;

    /**
     * Dispatcher Instance
     *
     * @var    object  Molajo\Event\Api\DispatcherInterface
     * @since  1.0
     */
    protected $dispatcher = null;

    /**
     * Event Dispatcher Instance
     *
     * @var    object  Molajo\Event\Api\EventDispatcherInterface
     * @since  1.0
     */
    protected $eventdispatcher = null;

    /**
     * Event Instance
     *
     * @var    object  Molajo\Event\Api\EventInterface
     * @since  1.0
     */
    protected $event = null;

    /**
     * Event Name
     *
     * @var    string
     * @since  1.0
     */
    protected $event_name = null;

    /**
     * Listener Instance
     *
     * @var    object  Molajo\Event\Api\ListenerInterface
     * @since  1.0
     */
    protected $listener = null;

    /**
     * Listeners
     *
     * @var    array
     * @since  1.0
     */
    protected $listeners;

    /**
     * Model Instance
     *
     * @var    object  Molajo\Controller\Api\ModelInterface
     * @since  1.0
     */
    protected $model = null;

    /**
     * Model Registry Name
     *
     * @var    string
     * @since  1.0
     */
    protected $model_registry_name = null;

    /**
     * Model Registry
     *
     * @var    object
     * @since  1.0
     */
    protected $model_registry = null;

    /**
     * Parameters
     *
     * @var    array
     * @since  1.0
     */
    protected $parameters = array();

    /**
     * Includes statements excluded until final run (empty during final run)
     *
     * Available in: onBeforeParseEvent and onBeforeParseHead
     *
     * @var    array
     * @since  1.0
     */
    protected $include_parse_exclude_until_final = array();

    /**
     * Include statements to be processed by parser in order of sequence processed
     *
     * Available in: onBeforeParseEvent and onBeforeParseHead
     *
     * @var    array
     * @since  1.0
     */
    protected $include_parse_sequence = array();

    /**
     * Query Object
     *
     * @var    object   Molajo\Database\Api\QueryObjectInterface
     * @since  1.0
     */
    protected $query = null;

    /**
     * Results from queries
     *
     * @var    array
     * @since  1.0
     */
    protected $query_results = array();

    /**
     * Redirect To
     *
     * @var    string
     * @since  1.0
     */
    protected $redirect_to_url = null;

    /**
     * Rendered Output
     *
     * @var    string
     * @since  1.0
     */
    protected $rendered_output = array();

    /**
     * Single Row from Query Results
     *
     * @var    string
     * @since  1.0
     */
    protected $row = null;

    /**
     * View Path
     *
     * @var    string
     * @since  1.0
     */
    protected $view_path = null;

    /**
     * View Path Url
     *
     * @var    string
     * @since  1.0
     */
    protected $view_path_url = null;

    /**
     * Constructor
     *
     * @since  1.0
     */
    public function __construct()
    {
    }

    /**
     * Get the current value of the specified key, or get all values
     *
     * @param   string $key
     *
     * @return  mixed
     * @since   1.0
     */
    public function get($key = null, $default = null)
    {
        $key = strtolower($key);

        if ($this->$key === null) {
            $this->$key = $default;
        }

        return $this->$key;
    }

    /**
     * Set the current value (or default) of the specified key
     *
     * @param   string     $key
     * @param   null|mixed $value
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\DataException
     */
    public function set($key, $value = null)
    {
        $key = strtolower($key);

        $this->$key = $value;

        return $this;
    }
}
