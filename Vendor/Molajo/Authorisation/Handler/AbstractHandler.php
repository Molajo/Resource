<?php
/**
 * Abstract Authorisation Handler
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Authorisation\Handler;

use Molajo\Authorisation\Api\AuthorisationInterface;
use Molajo\Authorisation\Exception\AuthorisationException;

/**
 * Abstract Authorisation Handler
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
abstract class AbstractHandler implements AuthorisationInterface
{
    /**
     * Site ID
     *
     * @var    int
     * @since  1.0
     */
    protected $site_id;

    /**
     * Application ID
     *
     * @var    int
     * @since  1.0
     */
    protected $application_id;

    /**
     * Actions used to establish authorisation
     *
     *  0 => 'none',
     *  1 => 'login',
     *  2 => 'create',
     *  3 => 'read',
     *  4 => 'update',
     *  5 => 'publish',
     *  6 => 'delete',
     *  7 => 'administer'
     *
     * @var    array
     * @since  1.0
     */
    protected $actions = array();

    /**
     * Actions used to establish authorisation
     *
     * 'none'       => 0,
     * 'login'      => 1,
     * 'create'     => 2,
     * 'read'       => 3,
     * 'update'     => 4,
     * 'publish'    => 5,
     * 'delete'     => 6,
     * 'administer' => 7
     *
     * @var    array
     * @since  1.0
     */
    protected $action_ids = array();

    /**
     * Action to Authorisation ID
     *
     * @var    array
     * @since  1.0
     */
    protected $task_to_action_id = array();

    /**
     * Action to Authorisation
     *
     * @var    array
     * @since  1.0
     */
    protected $task_to_action = array();

    /**
     * Action to Controller
     *
     * @var    array
     * @since  1.0
     */
    protected $task_to_controller = array();

    /**
     * User Permissions
     *
     *  $this->permissions->id
     *  $this->permissions->username
     *  $this->permissions->email
     *  $this->permissions->administrator
     *  $this->permissions->authorised_for_offline_access
     *  $this->permissions->public
     *  $this->permissions->guest
     *  $this->permissions->registered
     *  $this->permissions->sites
     *  $this->permissions->applications
     *  $this->permissions->groups
     *  $this->permissions->view_groups
     *  $this->permissions->html_filtering
     *
     * @var    object $permissions
     * @since  1.0
     */
    protected $permissions = null;

    /**
     * Default Exception
     *
     * @var    string
     * @since  1.0
     */
    protected $default_exception = 'Molajo\\Authorisation\\Exception\\AuthorisationException';

    /**
     * Construct
     *
     * @param  int    $site_id
     * @param  int    $application_id
     * @param  array  $task_to_action
     * @param  array  $task_to_controller
     * @param  object $permissions
     * @param  string $default_exception
     *
     * @since  1.0
     */
    public function __construct(
        $site_id = null,
        $application_id = null,
        array $task_to_action = array(),
        array $task_to_controller = array(),
        $permissions,
        $default_exception = null
    ) {
        $this->site_id            = $site_id;
        $this->application_id     = $application_id;
        $this->task_to_action     = $task_to_action;
        $this->task_to_controller = $task_to_controller;
        $this->permissions        = $permissions;

        $this->default_exception = $default_exception;
    }

    /**
     * Retrieve Action List
     *
     * Example usage:
     *  $authorisation = $this->authorisation->getActions();
     *
     * @param   string $type (id or null)
     *
     * @return  array|$this
     * @since   1.0
     * @throws  \Molajo\Authorisation\Exception\AuthorisationException
     */
    public function getActions($type = null)
    {
        if ($type === null || $type === '') {
            return $this->actions;
        }

        $type = ucfirst(strtolower($type));

        if (isset($this->actions[$type])) {
            return $this->actions[$type];
        }

        return null;
    }

    /**
     * Using the Request Task (Verb Action, like Tag, or Order Up), retrieve the Authorisation Action (ex. Update)
     *
     * Example usage:
     *  $results = $this->authorisation->getTaskAction($task);
     *
     * @param   string $task
     *
     * @return  string
     * @since   1.0
     * @throws  \Molajo\Authorisation\Exception\AuthorisationException
     */
    public function getTaskAction($task = null)
    {
        if ($task === '' || $task === null) {
            return $this->task_to_action;
        }

        if (isset($this->task_to_action[$task])) {
            return $this->task_to_action[$task];
        }

        throw new AuthorisationException
        ('Authorisation getTaskAction: Invalid Task: ' . $task);
    }

    /**
     * Use Task (Verb Action, like Tag, or Order Up) from Request to retrieve Controller (ex. ReadController)
     *
     * Example usage:
     *  $results = $this->authorisation->getTaskController($action);
     *
     * @return  array|string|null
     *
     * @return  string
     * @since   1.0
     * @throws  \Molajo\Authorisation\Exception\AuthorisationException
     */
    public function getTaskController($action = null)
    {
        if ($action == '') {
            return $this->task_to_controller;
        }

        if (isset($this->task_to_controller[$action])) {
            return $this->task_to_controller[$action];
        }

        throw new AuthorisationException
        ('Authorisation getTaskAction: Invalid Action: ' . $action);
    }

    /**
     * Check if Application has been set to "offline" and, if so, verify if user has offline access
     *
     * @return  bool
     * @throws  \Molajo\Authorisation\Exception\AuthorisationException
     * @since   1.0
     */
    public function isUserAuthorisedOfflineMode()
    {
        return true;

        if ((int)$this->parameters->application->parameters->offline_switch == 1) {
            if ($this->parameters->user->parameters->user_authorised_for_offline_access == 1) {

            } else {
                $this->parameters->error_code = 503;
                $this->parameters->redirect_to_id
                                              = $this->parameters->application->parameters->application_home_catalog_id;
                return $this->parameters;

                return false;
            }
        }

        return $this;
    }

    /**
     * Verify User Authorisation to take Action on Resource
     *
     * @param   int    $action_id
     * @param   int    $resource_id
     * @param   int    $group_id
     * @param   string $type
     *
     * @return  bool
     * @since   1.0
     * @throws  \Molajo\User\Exception\AuthorisationException
     */
    public function isUserAuthorised($action_id = null, $resource_id = null, $group_id = null, $type = 'Catalog')
    {
        $type = ucfirst(strtolower($type));

        if ($type == 'Site') {
            return $this->isUserAuthorisedSite();

        } elseif ($type == 'Application') {
            return $this->isUserAuthorisedApplication($resource_id);

        } elseif ($action_id == 3) {
            return $this->isUserAuthorisedViewAccess($resource_id);

        } else {
            return $this->isUserAuthorisedTask($action_id, $resource_id);
        }

        throw new AuthorisationException
        ('Authorisation isUserAuthorised: Invalid Type: ' . $type);
    }

    /**
     * Is User Authorised for this Site
     *
     * @return  bool
     * @since   1.0
     * @throws  \Molajo\User\Exception\AuthorisationException
     */
    protected function isUserAuthorisedSite()
    {
        if (in_array($this->site_id, $this->permissions->sites)) {
            return true;
        }

        return false;
    }

    /**
     * Is User Authorised for this Application
     *
     * @param   int $application_id
     *
     * @return  bool
     * @since   1.0
     * @throws  \Molajo\User\Exception\AuthorisationException
     */
    protected function isUserAuthorisedApplication($application_id)
    {
        if (in_array($this->application_id, $this->permissions->applications)) {
            return true;
        }

        return false;
    }

    /**
     * Is User Authorised to View this Catalog ID which has this View Group ID
     *
     * @param   int $group_id
     *
     * @return  bool
     * @since   1.0
     * @throws  \Molajo\User\Exception\AuthorisationException
     */
    protected function isUserAuthorisedViewAccess($group_id)
    {
        if (in_array($group_id, $this->permissions->view_groups)) {
            return true;
        }

        return false;
    }

    /**
     * Is User Authorised to View this Catalog ID
     *
     * @param   int $action_id
     * @param   int $resource_id
     *
     * @return  bool
     * @since   1.0
     * @throws  \Molajo\User\Exception\AuthorisationException
     */
    protected function isUserAuthorisedTask($action_id, $resource_id)
    {
        return true;
    }

    /**
     * Is User Authorised for no HTML Editing
     *
     * @param   int $action_id
     * @param   int $resource_id
     *
     * @return  bool
     * @since   1.0
     * @throws  \Molajo\User\Exception\AuthorisationException
     */
    protected function isUserAuthorisedNoHTMLFiltering($action_id, $resource_id)
    {
        return true;
    }

    /**
     * Appends View Access criteria to Query when Model check_view_level_access is set to 1
     *
     * Example usage:
     *  $this->authorisation->setQueryAuthorisation(
     *     $this->query,
     *     $this->db,
     *     array('join_to_prefix' => $this->primary_prefix,
     *         'join_to_primary_key' => $this->registry->get($this->model_registry, 'primary_key'),
     *         'catalog_prefix' => $this->primary_prefix . '_catalog',
     *         'select' => true
     *     )
     * );
     *
     * @param   object $query
     * @param   object $db
     * @param   object $model_registry
     * @param   array  $user_view_groups
     *
     * @return  array
     * @since   1.0
     */
    public function setQueryAuthorisation(
        $query,
        $db,
        $model_registry,
        array $user_view_groups = array()
    ) {
        return $query;
    }
}
