<?php
/**
 * User Activity Class
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\User;

use Exception;
use Molajo\User\Exception\ActivityException;
use Molajo\User\Api\ActivityInterface;
use Molajo\FieldHandler\Api\FieldHandlerInterface;
use Molajo\User\Api\ExceptionMessagesInterface;
use Molajo\User\Api\MessagesInterface;
use Molajo\Database\Api\DatabaseInterface;

/**
 * User Activity Class
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Activity implements ActivityInterface
{
    /**
     * Guest
     *
     * @var    boolean
     * @since  1.0
     */
    protected $guest;

    /**
     * Database Instance
     *
     * @var    object  Molajo\Database\Api\DatabaseInterface
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
     * Messages Instance
     *
     * @var    object  Molajo\User\Api\MessagesInterface
     * @since  1.0
     */
    protected $messages;

    /**
     * Default Exception
     *
     * @var    string
     * @since  1.0
     */
    protected $default_exception = 'Molajo\\User\\Exception\\ActivityException';

    /**
     * activity_id
     *
     * @var    int
     * @since  1.0
     */
    protected $activity_id;

    /**
     * ID for visitor
     *
     * @var    int
     * @since  1.0
     */
    protected $id;

    /**
     * User ID
     *
     * @var    int
     * @since  1.0
     */
    protected $user_id;

    /**
     * Action ID
     *
     * @var    int
     * @since  1.0
     */
    protected $action_id;

    /**
     * Catalog ID
     *
     * @var    int
     * @since  1.0
     */
    protected $catalog_id;

    /**
     * Session ID
     *
     * @var    int
     * @since  1.0
     */
    protected $session_id;

    /**
     * Activity Date Time
     *
     * @var    string
     * @since  1.0
     */
    protected $activity_datetime;

    /**
     * IP Address
     *
     * @var    string
     * @since  1.0
     */
    protected $ip_address;

    /**
     * Activity
     *
     * @var    array
     * @since  1.0
     */
    protected $activity;

    /**
     * Updates
     *
     * @var    array
     * @since  1.0
     */
    protected $updates;

    /**
     * List of Properties
     *
     * @var    object
     * @since  1.0
     */
    protected $property_array = array(
        'activity_id',
        'id',
        'user_id',
        'action_id',
        'catalog_id',
        'session_id',
        'activity_datetime',
        'ip_address',
        'activity'
    );

    /**
     * Construct
     *
     * @param  DatabaseInterface     $database
     * @param  FieldHandlerInterface $fieldhandler
     * @param  MessagesInterface     $messages
     * @param  null                  $default_exception
     * @param  null                  $id
     *
     * @since  1.0
     */
    public function __construct(
        DatabaseInterface $database,
        FieldHandlerInterface $fieldhandler,
        MessagesInterface $messages,
        $default_exception = null,
        $id = 0
    ) {
        $this->database     = $database;
        $this->fieldhandler = $fieldhandler;
        $this->messages     = $messages;

        if ($default_exception === null) {
        } else {
            $this->default_exception = $default_exception;
        }

        if ($id === 0) {
            $this->guest = true;

        } else {
            $this->guest = false;
        }

        $this->getUser($id);
    }

    /**
     * GetDate
     *
     * @return  object  DateTime
     * @since   1.0
     */
    public function getDate()
    {
        return $this->database->getDate();
    }

    /**
     * Get the current value (or default) of the specified key or all User Data for null key
     *
     * @param   null|string $key
     * @param   null|mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\User\Exception\ActivityException
     */
    public function get($key = null, $default = null)
    {
        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            $options        = array();
            $options['key'] = $key;
            $this->messages->throwException(5010, $options, $this->default_exception);
        }

        if ($this->$key === null) {
            $this->$key = $default;
        }

        return $this->$key;
    }

    /**
     * Set the value of a specified key
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\User\Exception\ActivityException
     */
    public function set($key, $value = null)
    {
        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            $options        = array();
            $options['key'] = $key;
            $this->messages->throwException(5015, $options, $this->default_exception);
        }

        $this->$key = $value;

        return $this;
    }

    /**
     * Lookup User using the Id, Username or email address
     * automatically executed during class instantiation
     *
     * @param  string $input
     * @param  string $type : id, username (default), email
     *
     * @return  $this
     * @since   1.0
     */
    protected function getUser($id)
    {
        $this->getUserActivity($id);

        return $this;
    }

    /**
     * Retrieve User View Groups
     * echo $this->database->getQueryString();
     *
     * @param   string $id
     *
     * @return  $this
     * @since   1.0
     */
    protected function getUserActivity($id)
    {
        $data = array();

        if ($this->guest === false) {

            $query = $this->database->getQueryObject();

            $query->select('*');
            $query->from($this->database->qn('#__user_activity'));
            $query->where(
                $this->database->qn('user_id')
                . ' = ' . $this->database->q($id)
            );
            $query->order('activity_datetime');

            $data = $this->database->loadObjectList();
        }

        if (is_array($data) && count($data) > 0) {
            $this->activity = $data;
        }

        return $this;
    }

    /**
     * Update User Activity
     *
     * @param   array $updates
     *
     * @return  $this
     * @since   1.0
     */
    public function updateUserActivity(array $updates = array())
    {
        if (count($updates) > 0) {
            $this->updates = $updates;
        } else {
            return $this;
        }

        $query = $this->database->getQueryObject();

        $query->update($this->database->qn('#__user_activity'));

        foreach ($this->updates as $field => $value) {
            $query->set(
                $this->database->qn($field)
                . ' = ' . $this->database->q($value)
            );
        }

        $query->where(
            $this->database->qn('id')
            . ' = ' . $this->database->q($this->activity_id)
        );

//echo $this->database->getQueryString();

        $results = $this->database->execute();

        if ($results === false) {
            $this->messages->throwException(1700, array(), $this->default_exception);
        }

        $this->getUserActivity($this->id);

        $this->updates = array();

        return $this;
    }

    /**
     * Delete User Activity
     *
     * @return  $this
     * @since   1.0
     * @throws  DataException
     */
    public function deleteUser()
    {
        $query = $this->database->getQueryObject();

        $query->delete($this->database->qn('#__user_activity'));

        $query->where(
            $this->database->qn('id')
            . ' = ' . $this->database->q($this->activity_id)
        );

        $results = $this->database->execute();

        if ($results === false) {
            $this->messages->throwException(1800, array(), $this->default_exception);
        }

        $this->getUser($this->id);

        $this->updates = array();

        return $this;
    }
}
