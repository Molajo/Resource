<?php
/**
 * User Data
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\User;

//todo: abstract out to be create, read, update, delete, list

use stdClass;
use Exception;
use Molajo\User\Exception\DataException;
use Molajo\User\Api\UserDataInterface;
use Molajo\FieldHandler\Api\FieldHandlerInterface;
use Molajo\User\Api\MessagesInterface;
use Molajo\Database\Api\DatabaseInterface;

/**
 * User Data
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class UserData implements UserDataInterface
{
    /**
     * Database Instance
     *
     * @var    object  Molajo\Database\Api\DatabaseInterface
     * @since  1.0
     */
    protected $database;

    /**
     * Model Registry
     *
     * @var    array
     * @since  1.0
     */
    protected $model_registry;

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
    protected $default_exception = 'Molajo\\User\\Exception\\DataException';

    /**
     * Session Key
     *
     * @var    string
     * @since  1.0
     */
    protected $session_key;

    /**
     * User Object
     *
     * @var    string
     * @since  1.0
     */
    protected $login_attempts;

    /**
     * Customfield Groups
     *
     * @var    array
     * @since  1.0
     */
    protected $customfieldgroups = array();

    /**
     * Children
     *
     * @var    array
     * @since  1.0
     */
    protected $children = array();

    /**
     * Model Registries for Children
     *
     * @var    array
     * @since  1.0
     */
    protected $child_model_registries = array();

    /**
     * Updates for fields
     *
     * @var    array
     * @since  1.0
     */
    protected $updates;

    /**
     * Construct
     *
     * @param  DatabaseInterface     $database
     * @param                        $model_registry
     * @param                        $child_model_registries
     * @param  FieldHandlerInterface $fieldhandler
     * @param  MessagesInterface     $messages
     * @param  null|string           $default_exception
     * @param  null|int              $id
     * @param  null|string           $username
     * @param  null|string           $email
     *
     * @since  1.0
     */
    public function __construct(
        DatabaseInterface $database,
        $model_registry,
        $child_model_registries,
        FieldHandlerInterface $fieldhandler,
        MessagesInterface $messages,
        $default_exception = null,
        $id = null,
        $username = null,
        $email = null
    ) {
        $this->user = new stdClass();

        $this->database               = $database;
        $this->model_registry         = $model_registry;
        $this->child_model_registries = $child_model_registries;
        $this->fieldhandler           = $fieldhandler;
        $this->messages               = $messages;

        if ($default_exception === null) {
        } else {
            $this->default_exception = $default_exception;
        }

        $this->guest = 0;

        $this->user->id       = null;
        $this->user->username = null;
        $this->user->email    = null;

        if ($id !== null) {
            $this->user->id = $id;
            $this->getUser($this->user->id);

        } elseif ($username !== null) {
            $this->user->username = $username;
            $this->getUser($this->user->username, 'username');

        } elseif ($email !== null) {
            $this->user->email = $email;
            $this->getUser($this->user->email, 'email');

        } else {
            $this->guest = 1;
            $this->getUser(0, 'id');
        }
    }

    /**
     * GetDate
     *
     * @return  object  DateTime
     * @since   1.0
     * @throws  \Molajo\User\Exception\DataException
     */
    public function getDate()
    {
        return $this->database->getDate();
    }

    /**
     * Get the current value (or default) of the specified key or all User Data for null key
     * The secondary key can be used to designate a customfield group or child object
     *
     * @param   null|string $key
     * @param   null|string $secondary_key
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\User\Exception\DataException
     */
    public function getUserData($key = null, $secondary_key = null)
    {
        if (($key === null || $key == '*')
            && ($secondary_key === null)
        ) {
            return $this->user;
        }

        $key = strtolower($key);

        if ($secondary_key === null) {

        } elseif (in_array($secondary_key, $this->customfieldgroups)) {
            return $this->getDataCustomfield($secondary_key, $key);

        } elseif (in_array($secondary_key, $this->children)) {
            return $this->getChildObject($secondary_key, $key);
        }

        if ($key === null || $key == '*') {
            return $this->user;
        }

        if (isset($this->user->$key)) {
        } else {
            $options        = array();
            $options['key'] = $key;
            $this->messages->throwException(5010, $options, $this->default_exception);
        }

        return $this->user->$key;
    }

    /**
     * Get the current value (or default) of the specified key or all User Data for null key
     *
     * @param   string      $customfieldgroup
     * @param   null|string $key
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\User\Exception\DataException
     */
    protected function getDataCustomfield($customfieldgroup, $key = null)
    {
        $customfieldgroup = strtolower($customfieldgroup);

        if (in_array($customfieldgroup, $this->customfieldgroups)) {

        } else {
            $options = array();
            $this->messages->throwException(5010, $options, $this->default_exception);
        }

        if ($key === null || $key == '*') {
            return $this->user->$customfieldgroup;
        }

        if (isset($this->user->$customfieldgroup->$key)) {
        } else {
            $options        = array();
            $options['key'] = $key;
            $this->messages->throwException(5010, $options, $this->default_exception);
        }

        return $this->user->customfieldgroup->$key;
    }

    /**
     * Get child object
     *
     * @param   string      $child
     * @param   null|string $key
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\User\Exception\DataException
     */
    protected function getChildObject($child, $key = null)
    {
        $child = strtolower($child);

        if (in_array($child, $this->children)) {

        } else {
            $options = array();
            $this->messages->throwException(5010, $options, $this->default_exception);
        }

        if ($key === null || $key == '*') {
            return $this->user->$child;
        }

        if (isset($this->user->$child->$key)) {
        } else {
            $options        = array();
            $options['key'] = $key;
            $this->messages->throwException(5010, $options, $this->default_exception);
        }

        return $this->user->child->$key;
    }

    /**
     * Set the value of a specified key
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  $this
     * @since   1.0
     * @throws  DataException
     */
    public function setUserData($key, $value = null)
    {

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
    protected function getUser($input, $type = 'username')
    {
        /** Lookup */
        $this->user->id       = null;
        $this->user->username = null;
        $this->user->email    = null;

        if ($type == 'username') {
            $input = $this->fieldhandler->filter('username', $input, 'Alphanumeric');

        } elseif ($type == 'id') {
            $input = $this->fieldhandler->filter('id', $input, 'Numeric');

        } elseif ($type == 'email') {
            $input = $this->fieldhandler->filter('email', $input, 'Email');
        }

        /** Query (if not guest) */
        $data = array();

        if ($this->guest === 0) {

            $query = $this->database->getQueryObject();

            $query->select('*');
            $query->from($this->database->qn('#__users'));
            $query->where(
                $this->database->qn($type)
                . ' = ' . $this->database->q($input)
            );

            $results = $this->database->loadObjectList();

            if (is_array($results) && count($results) > 0) {
                $data = $results[0];
            }
        }

        if (is_array($data) && count($data) === 0) {
            $this->guest = 1;
        }

        /** Standard Fields */
        if (count($this->model_registry['fields']) > 0) {
            foreach ($this->model_registry['fields'] as $field) {
                $name              = $field['name'];
                $this->user->$name = $this->setFieldValue($field, $data);
            }
        }

        if ($this->user->reset_password_code === null) {
            $this->user->reset_password_code = '';
        }

        /** Custom fields */
        $this->customfieldgroups = $this->model_registry['customfieldgroups'];

        if (count($this->customfieldgroups) > 0) {

            foreach ($this->customfieldgroups as $group) {

                if ($this->user->id === 0) {
                    $data = null;
                } else {
                    $data = json_decode($this->user->$group);
                }

                $this->user->$group = $this->loadCustomfields(
                    $type = $group,
                    $data,
                    $this->model_registry [$group]
                );
            }
        }

        /** Children */
        $this->children = $this->model_registry['children'];

        if (count($this->children) > 0) {

            foreach ($this->children as $child) {

                $name = (string)$child['name'];
                $join = (string)$child['join'];

                if (isset($this->child_model_registries[$name])) {

                    $model_registry = $this->child_model_registries[$name];

                    if ($this->user->id === 0) {
                        $data = null;
                    } else {
                        $entry = substr((string)$model_registry['table_name'], 8, 9999);
                        $this->user->$entry
                               = $this->getChildData($name, $this->user->id, $join, $model_registry);
                    }
                }
            }
        }

        $this->user->guest  = $this->guest;
        $this->user->public = 1;

        if (in_array(GROUP_ADMINISTRATOR, $this->user->groups)) {
            $this->user->administrator = 1;
        } else {
            $this->user->administrator = 0;
        }

        if ($this->user->guest === 1) {
            $this->user->registered = 0;
        } else {
            $this->user->registered = 1;
        }

        if ($this->user->administrator === 1) {
            $this->user->html_filtering_required       = 0;
            $this->user->authorised_for_offline_access = 1;
        } else {
            $this->user->html_filtering_required       = 1;
            $this->user->authorised_for_offline_access = 0;
        }

        return $this->user;
    }

    /**
     * Get Child Data
     *
     * @param   string $child
     * @param   int    $id
     * @param   string $join
     * @param   object $model_registry
     *
     * @return  $this
     * @since   1.0
     */
    public function getChildData($child, $id, $join, $model_registry)
    {
        $query = $this->database->getQueryObject();

        $count  = 0;
        $fields = $model_registry['fields'];

        if (is_array($fields) && count($fields) > 0) {
            foreach ($fields as $field) {
                if ($field['name'] == $join) {
                } else {
                    $count ++;
                    $hold_field = $field['name'];
                    $query->select($this->database->qn($field['name']));
                }
            }
        }

        $query->from($this->database->qn($model_registry['table_name']));
        $query->where(
            $this->database->qn($join)
            . ' = ' . $this->database->q((int)$id)
        );

        $results = $this->database->loadObjectList();

        $value = array();
        if ($count == 1) {
            if (count($results) > 0) {
                foreach ($results as $result) {
                    $value[] = $result->$hold_field;
                }
            }
        } else {
            $value = $results;
        }

        return $value;
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
    protected function loadCustomfields($type = 'customfields', $data = array(), $fields = array())
    {
        $typeObject = new stdClass();

        if (is_array($fields) && count($fields) > 0) {

            foreach ($fields as $field) {
                $name              = $field['name'];
                $typeObject->$name = $this->setFieldValue($field, $data);
            }
        }

        return $typeObject;
    }

    /**
     * Retrieve value (or default value) for field
     *
     * @param   object      $field
     * @param   null|object $data
     *
     * @return  null|mixed
     */
    protected function setFieldValue($field, $data)
    {
        $name = $field['name'];

        if (isset($data->$name)) {
            $value = $data->$name;

        } elseif (isset($field['default'])) {
            $value = $field['default'];

        } else {
            $value = null;
        }

        return $value;
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
    protected function insertUser()
    {
        return $this;
    }

    /**
     * Update User
     *
     * @param   array $updates
     *
     * @return  $this
     * @since   1.0
     */
    public function updateUser(array $updates = array())
    {
        if (count($updates) > 0) {
            $this->updates = $updates;
        } else {
            return $this;
        }

        $query = $this->database->getQueryObject();

        $query->update($this->database->qn('#__users'));

        foreach ($this->updates as $field => $value) {
            $query->set(
                $this->database->qn($field)
                . ' = ' . $this->database->q($value)
            );
        }

        $query->where(
            $this->database->qn('id')
            . ' = ' . $this->database->q($this->user->id)
        );

//echo $this->database->getQueryString();

        $results = $this->database->execute();

        if ($results === false) {
            $this->messages->throwException(1700, array(), $this->default_exception);
        }

        $this->getUser($this->user->id, 'id');

        $this->updates = array();

        return $this;
    }

    /**
     * Delete the User
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\User\Exception\DataException
     */
    public function deleteUser()
    {
        $query = $this->database->getQueryObject();
//children first
        $query->delete($this->database->qn('#__users'));

        $query->where(
            $this->database->qn('id')
            . ' = ' . $this->database->q($this->user->id)
        );

        $results = $this->database->execute();

        if ($results === false) {
            $this->messages->throwException(1800, array(), $this->default_exception);
        }

        $this->guest = 1;
        $this->getUser(0, 'id');

        $this->updates = array();

        return $this;
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
