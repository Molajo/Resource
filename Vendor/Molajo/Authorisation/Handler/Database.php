<?php
/**
 * Database Handler for Authorisation
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Authorisation\Handler;

use Exception;
use Molajo\Database\Api\DatabaseInterface;
use Molajo\FieldHandler\Api\FieldHandlerInterface;
use Molajo\Authorisation\Api\AuthorisationInterface;

/**
 * Database Handler for Authorisation
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Database extends AbstractHandler implements AuthorisationInterface
{
    /**
     * Database Instance
     *
     * @var    object  Molajo\User\Api\DatabaseInterface
     * @since  1.0
     */
    protected $database;

    /**
     * Fieldhandler Instance
     *
     * @var    object  Molajo\FieldHandler\Api\FieldHandlerInterface
     * @since  1.0
     */
    protected $fieldhandler;

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
     * @param  int                   $application_id
     * @param  array                 $task_to_action
     * @param  array                 $task_to_controller
     * @param  object                $permissions
     * @param  string                $default_exception
     * @param  DatabaseInterface     $database
     * @param  FieldHandlerInterface $fieldhandler
     *
     * @since  1.0
     */
    public function __construct(
        $application_id = null,
        array $task_to_action = array(),
        array $task_to_controller = array(),
        $permissions,
        $default_exception = null,
        DatabaseInterface $database,
        FieldHandlerInterface $fieldhandler
    ) {
        parent::__construct(
            $application_id,
            $task_to_action,
            $task_to_controller,
            $permissions,
            $default_exception,
            $database,
            $fieldhandler
        );

        if ($default_exception === null) {
        } else {
            $this->default_exception = $default_exception;
        }

        $this->database     = $database;
        $this->fieldhandler = $fieldhandler;

        $this->actions = array();

        $this->getActions();
    }

    /**
     * Retrieve list of Authorisation Actions
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
        if (count($this->actions) > 0 && count($this->actions) > 0) {
            return parent::getActions($type);
        }

        $query = $this->database->getQueryObject();

        $query->select($this->database->qn('id'));
        $query->select($this->database->qn('title'));
        $query->from($this->database->qn('#__actions'));

        $list = $this->database->loadObjectList();

        $this->actions    = array();
        $this->action_ids = array();

        if (count($list) > 0) {
            foreach ($list as $item) {
                $this->action_ids[strtolower($item->title)] = $item->id;
                $this->actions[$item->id]                   = strtolower($item->title);
            }
        }

        $this->task_to_action_id = array();
        if (count($this->task_to_action) > 0) {
            foreach ($this->task_to_action as $key => $value) {
                if ($value == 'none') {
                    $this->task_to_action_id[$key] = 'none';
                } else {
                    $this->task_to_action_id[$key] = $this->action_ids[$value];
                }
            }
        }

        return $this;
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
        return parent::getTaskAction($task);
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
        return parent::getTaskController($action);
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
//@todo
        return true;

        $action    = $this->task_to_action[$action];
        $action_id = $this->task_to_action_id[$action];

        $action_id = 3;

        $query = $this->database->getQueryObject();

        $query->select('count(*)');
        $query->from($this->database->qn('#_view_group_permissions'));
        $query->where(
            $this->database->qn('catalog_id')
            . ' = ' . (int)$resource_id
        );
        $query->where(
            $this->database->qn('action_id')
            . ' = ' . (int)$action_id
        );
        $query->where(
            $this->database->qn('group_id')
            . ' IN (' . implode(',', $this->user_groups) . ')'
        );


        try {
            $count = $this->database->loadResult();

            if ($count > 0) {
                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            $this->messages->throwException(3098, array(), $this->default_exception);
        }
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
     *
     * @return  array
     * @since   1.0
     */
    public function setQueryAuthorisation(
        $query,
        $db,
        $model_registry
    ) {
        if (is_array($this->permissions->view_groups)) {
        } else {
            return $query;
        }

        if ($model_registry['select'] === true) {
            $query->select(
                $db->qn('authorisation') .
                '.' .
                $db->qn('view_group_id')
            );

            $query->select(
                $db->qn('authorisation') .
                '.' .
                $db->qn('id') .
                ' as ' .
                $db->qn('catalog_id')
            );
        }

        $query->from(
            $db->qn('#__catalog') .
            ' as ' .
            $db->qn('authorisation')
        );

        $query->where(
            $db->qn('authorisation') .
            '.' .
            $db->qn('source_id') .
            ' = ' .
            $db->qn($model_registry['primary_prefix']) .
            '.' .
            $db->qn($model_registry['primary_key'])
        );

        $query->where(
            $db->qn('authorisation') .
            '.' . $db->qn('catalog_type_id') .
            ' = ' .
            $db->qn($model_registry['primary_prefix']) .
            '.' .
            $db->qn('catalog_type_id')
        );

        $query->where(
            $db->qn('authorisation') .
            '.' . $db->qn('application_id') .
            ' = ' .
            $this->application_id
        );

        $vg = implode(',', array_unique($this->permissions->view_groups));

        $query->where(
            $db->qn('authorisation') .
            '.' .
            $db->qn('view_group_id') . ' IN (' . $vg . ')'
        );

        $query->where(
            $db->qn('authorisation') .
            '.' .
            $db->qn('redirect_to_id') .
            ' = 0'
        );

        return $query;
    }

    /**
     * Validate, Filter and Escape Field data
     *
     * @param   string      $key
     * @param   null|string $value
     * @param   string      $fieldhandler_type_chain
     * @param   array       $options
     *
     * @return  $this|mixed
     * @since   1.0
     */
    public function handleField($key, $value = null, $fieldhandler_type_chain, $options = array())
    {
        try {
            return $this->fieldhandler
                ->filter($key, $value, $fieldhandler_type_chain, $options);

        } catch (Exception $e) {
            $this->messages->throwException(1800, array(), $this->default_exception);
        }
    }
}
