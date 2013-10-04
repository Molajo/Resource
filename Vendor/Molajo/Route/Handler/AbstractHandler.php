<?php
/**
 * Abstract Route Handler
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Route\Handler;

use Molajo\Route\Api\RouteInterface;
use Molajo\Route\Exception\RouteException;

/**
 * Abstract Route Handler
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
abstract class AbstractHandler implements RouteInterface
{
    /**
     * Request Object
     *
     * @var    object
     * @since  1.0
     */
    protected $request;

    /**
     * Parameters
     *
     * @var    object $parameters
     * @since  1.0
     */
    protected $parameters;

    /**
     * Filters
     *
     * @var    array
     * @since  1.0
     */
    protected $filters;

    /**
     * Constructor
     *
     * @param  object $request
     * @param  object $parameters
     * @param  array  $filters
     *
     * @since   1.0
     */
    public function __construct(
        $request,
        $parameters,
        array $filters = array()
    ) {
        $this->request    = $request;
        $this->parameters = $parameters;
        $this->filters    = $filters;
    }

    /**
     * Determine if secure protocol required and in use
     *
     * @return  object
     * @throws  \Molajo\Route\Exception\RouteException
     * @since   1.0
     */
    public function verifySecureProtocol()
    {
        if ((int)$this->parameters->application->parameters->url_force_ssl == 0) {
            return $this->parameters;
        }

        if ((int)$this->request->is_secure == 1) {
            return $this->parameters;
        }

        $this->parameters->error_code      = 301;
        $this->parameters->redirect_to_url = $this->parameters->application->parameters->application_home_catalog_id;

        return $this->parameters;
    }

    /**
     * Determine if request is for home page
     *
     * @return  object
     * @throws  \Molajo\Route\Exception\RouteException
     * @since   1.0
     */
    public function verifyHome()
    {
        $this->parameters->route->home = 0;

        if (strlen($this->parameters->application->path) == 0
            || trim($this->parameters->application->path) == ''
        ) {
            $this->parameters->route->catalog_id
                                           = $this->parameters->application->parameters->application_home_catalog_id;
            $this->parameters->route->home = 1;

            return $this->parameters;
        }

        if ($this->parameters->application->path == '/') {
            $this->parameters->error_code = 301;
            $this->parameters->redirect_to_id
                                          = $this->parameters->application->parameters->application_home_catalog_id;
            return $this->parameters;
        }

        if ($this->parameters->application->path == 'index.php'
            || $this->parameters->application->path == 'index.php/'
            || $this->parameters->application->path == 'index.php?'
            || $this->parameters->application->path == '/index.php/'
        ) {
            $this->parameters->error_code = 301;
            $this->parameters->redirect_to_id
                                          = $this->parameters->application->parameters->application_home_catalog_id;
            return $this->parameters;
        }

        return $this->parameters;
    }

    /**
     * Set Request
     *
     * @return  $this
     * @throws  \Molajo\Route\Exception\RouteException
     * @since   1.0
     */
    public function setRequest()
    {
        $this->setAction();
        $this->setBaseUrl();
        $this->setPath();
        $this->setRequestVariables();

        return $this->parameters;
    }

    /**
     * Set Action from HTTP Method
     *
     * @return  $this
     * @throws  \Molajo\Route\Exception\RouteException
     * @since   1.0
     */
    protected function setAction()
    {
        $method = $this->request->method;
        $method = strtoupper($method);

        if ($method == 'POST') {
            $action = 'create';

        } elseif ($method == 'PUT') {
            $action = 'update';

        } elseif ($method == 'DELETE') {
            $action = 'delete';

        } else {
            $method = 'GET';
            $action = 'read';
        }

        $this->parameters->route->action = $action;
        $this->parameters->route->method = $method;

        return $this;
    }

    /**
     * Set Path
     *
     * @return  $this
     * @throws  \Molajo\Route\Exception\RouteException
     * @since   1.0
     */
    protected function setBaseUrl()
    {
        $this->parameters->route->base_url = $this->request->application_base_url;

        return $this;
    }

    /**
     * Set Path
     *
     * @return  $this
     * @throws  \Molajo\Route\Exception\RouteException
     * @since   1.0
     */
    protected function setPath()
    {
        $this->parameters->route->path = $this->parameters->application->path;

        return $this;
    }

    /**
     * Set Request Variables
     *
     * @return  $this
     * @throws  \Molajo\Route\Exception\RouteException
     * @since   1.0
     */
    protected function setRequestVariables()
    {
        $post_variables = array();

        if ($this->parameters->route->action == 'read') {
            $this->setReadVariables();
        } else {
            $post_variables = $this->parameters->route->post_variable_array;
            $this->setTaskVariables();
        }

        $this->parameters->route->post_variable_array = $post_variables;

        if ($this->parameters->route->catalog_id > 0) {
        } else {
            $value = (int)$this->parameters->route->source_id;
            if ($value == 0) {
            } else {
                $this->parameters->route->catalog_id = $value;
            }
        }

        return $this;
    }

    /**
     * Retrieve non-route values for SEF URLs:
     *
     * @return  $this
     * @since   1.0
     */
    protected function setReadVariables()
    {
        $urlParts = explode(' / ', $this->parameters->route->path);

        if (count($urlParts) == 0) {
            return $this;
        }

        $path        = '';
        $filterArray = '';
        $filter      = '';
        $i           = 0;

        foreach ($urlParts as $slug) {

            if ($filter == '') {
                if (in_array($slug, $this->filters)) {
                    $filter = $slug;
                } else {
                    if (trim($path) == '') {
                    } else {
                        $path .= '/';
                    }
                    $path .= $slug;
                }
            } else {
                if ($filterArray == '') {
                } else {
                    $filterArray .= ';';
                }
                $filterArray .= $filter . ':' . $slug;
                $filter = '';
            }
        }

        $this->parameters->route->filters_array = $filterArray;

        return $this;
    }

    /**
     * For non-read actions, retrieve task and values
     *
     * @return  $this
     * @since   1.0
     */
    protected function setTaskVariables()
    {
        $urlParts = explode('/', $this->parameters->route->path);
        if (count($urlParts) == 0) {
            return $this;
        }

        $tasks = $this->parameters->permission_tasks;

        $path          = '';
        $task          = '';
        $action_target = '';

        foreach ($urlParts as $slug) {
            if ($task == '') {
                if (in_array($slug, $tasks)) {
                    $task = $slug;
                } else {
                    if (trim($path) == '') {
                    } else {
                        $path .= ' / ';
                    }
                    $path .= $slug;
                }
            } else {
                $action_target = $slug;
                break;
            }
        }

        /** Map Action Verb (Tag, Favorite, etc.) to Permission Action (Update, Delete, etc.) */
        $this->parameters->route->request_task        = $task;
        $this->parameters->route->request_task_values = $action_target;

        return $this;
    }


    /**
     * Set Route
     *
     * @return  object
     * @throws  \Molajo\Route\Exception\RouteException
     * @since   1.0
     */
    public function setRoute()
    {
        return $this->parameters();
    }

    /**
     *
     */
    public function setRedirect()
    {
        $this->parameters->request_non_route_parameters = '';

        /**
         * @todo test with non-sef URLs
         * $sef = $this->parameters->configuration_sef_url', 1);
         * if ($sef == 1) {
         *
         * $this->getResourceSEF();
         *
         * } else {
         *
         * $this->getResourceExtensionParameters();
         *
         * }
         */

        return;
    }
}
