<?php
/**
 * Joomla Database Class
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Database\Handler;

use Joomla\Database;

use DateTime;
use Exception;
use Molajo\Database\Exception\JoomlaHandlerException;
use Molajo\Database\Api\ConnectionInterface;
use Molajo\Database\Api\DatabaseInterface;
use Molajo\Database\Api\QueryInterface;

use Joomla\Database\DatabaseFactory;

/**
 * Database Connection
 *
 * @package     Molajo
 * @subpackage  Service
 * @since       1.0
 */
class Joomla extends AbstractHandler
    implements ConnectionInterface, DatabaseInterface, QueryInterface
{
    /**
     * Database Factory
     *
     * @var    string
     * @since  1.0
     */
    protected $factory;

    /**
     * Query String
     *
     * @var    string
     * @since  1.0
     */
    protected $query_string;

    /**
     * Constructor
     *
     * @param   array $options
     *
     * @return  mixed
     * @since   1.0
     */
    public function __construct(array $options = array())
    {
        $this->database_type = 'Joomla';
        $this->connect($options);
    }

    /**
     * Set the Database Object
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0
     * @throws  JoomlaHandlerException
     */
    public function connect($options = array())
    {
        $this->options = $options;

        $db_type = 'mysqli';
        if (isset($options['db_type'])) {
            $db_type = $options['db_type'];
        }

        $db_host = '';
        if (isset($options['db_host'])) {
            $db_host = $options['db_host'];
        }

        $db_user = '';
        if (isset($options['db_user'])) {
            $db_user = $options['db_user'];
        }

        $db_password = '';
        if (isset($options['db_password'])) {
            $db_password = $options['db_password'];
        }

        $db_name = '';
        if (isset($options['db_name'])) {
            $db_name = $options['db_name'];
        }

        $db_prefix = '';
        if (isset($options['db_prefix'])) {
            $db_prefix = $options['db_prefix'];
        }

        $db_options = array(
            'host'     => $db_host,
            'user'     => $db_user,
            'password' => $db_password,
            'database' => $db_name,
            'prefix'   => $db_prefix,
            'select'   => true
        );

        try {
            $this->factory = new DatabaseFactory();

        } catch (Exception $e) {
            throw new JoomlaHandlerException
            ('Unable to connect to the Database: Joomla DatabaseFactory ' . $e->getMessage());
        }

        try {
            $this->database = $this->factory->getDriver($db_type, $db_options);

        } catch (Exception $e) {
            throw new JoomlaHandlerException
            ('Unable to connect to the Joomla Database Factory Driver: ' . $db_type . ' ' . $e->getMessage());
        }

        $this->query = null;

        $this->date_format = $this->database->getDateFormat();

        $this->null_date = $this->database->getNullDate();

        return $this;
    }

    /**
     * Get a new query object for the current database connection
     *
     * @return  object
     * @since   1.0
     * @throws  JoomlaHandlerException
     */
    public function getQueryObject()
    {
        try {
            $this->query = $this->database->getQuery(true);

            $this->query->clear();

        } catch (Exception $e) {
            throw new JoomlaHandlerException
            ('Unable to get Query Connection for Joomla Database Type: ' . $this->database_type);
        }

        return $this->query;
    }

    /**
     * Returns a database driver compliant date format for PHP date()
     *
     * @return  string The format string.
     * @since   1.0
     */
    public function getDateFormat()
    {
        return $this->date_format;
    }

    /**
     * Returns the current date in a database driver compliant format
     *
     * @return  string The format string.
     * @since   1.0
     */
    public function getDate()
    {
        $date = new DateTime();

        return $date->format($this->getDateFormat());
    }

    /**
     * Returns a database driver compliant value for null date
     *
     * @return  string
     * @since   1.0
     * @throws  JoomlaHandlerException
     */
    public function getNullDate()
    {
        return $this->null_date;
    }

    /**
     * Quote Value
     *
     * @param   string $value
     *
     * @return  mixed
     * @since   1.0
     * @throws  JoomlaHandlerException
     */
    public function quote($value)
    {
        return $this->database->quote($value);
    }

    /**
     * Quote and return name
     *
     * @param   string $name
     *
     * @return  string
     * @since   1.0
     * @throws  JoomlaHandlerException
     */
    public function quoteName($name)
    {
        return $this->database->quoteName($name);
    }

    /**
     * Method to escape a string for usage in an SQL statement.
     *
     * @param   string $text
     *
     * @return  string
     * @since   1.0
     * @throws  JoomlaHandlerException
     */
    public function escape($text)
    {
        return $this->database->escape($text);
    }

    /**
     * Using the Query Object already specified, to build the SQL, sends the SQL as a request to the database,
     *  returning a single data value as the result
     *
     * $results = $this->adapter->loadResult();
     *
     * echo $results->title;
     *
     * @return  object
     * @since   1.0
     */
    public function loadResult()
    {
        $this->database->setQuery($this->query);

        return $this->database->loadResult();
    }

    /**
     * Using the Query Object already specified, sends the SQL request to the database, returning an array of rows
     *  each row of which is an object. Offset represents the row number in the result set from which to start.
     *  Limit specifies the maximum number of rows to be returned.
     *
     * $results = $this->adapter->loadObjectList($offset, $limit);
     *
     * if (count($results) > 0) {
     *      foreach ($results as $row) {
     *          $title = $results->title;
     *          $author = $results->author;
     *      }
     * }
     *
     * @param   null|int $offset
     * @param   null|int $limit
     *
     * @return  object
     * @since   1.0
     * @throws  JoomlaHandlerException
     */
    public function loadObjectList($offset = null, $limit = null)
    {
        $this->database->setQuery($this->getQueryString(), $offset, $limit);

        return $this->database->loadObjectList();
    }

    /**
     * Execute the Database Query (SQL can be sent in or derived from Query Object)
     *
     * @param   null|string $sql
     *
     * @return  object
     * @since   1.0
     * @throws  JoomlaHandlerException
     */
    public function execute($sql = null)
    {
        if ($sql === null) {
            $this->database->setQuery($this->query);
        }

        $this->database->execute($sql);

        $this->query->clear();
    }

    /**
     * Returns the primary key following insert
     *
     * @return  integer
     * @since   1.0
     * @throws  JoomlaHandlerException
     */
    public function getInsertId()
    {
        return $this->database->insertid();
    }

    /**
     * Disconnect from Database
     *
     * @return  $this
     * @since   1.0
     * @throws  JoomlaHandlerException
     */
    public function disconnect()
    {
        $this->database->disconnect();
    }

    /**
     * Returns a string containing the query, resolved from the query object
     *
     * @return  string
     * @since   1.0
     * @throws  JoomlaHandlerException
     */
    public function getQueryString()
    {
        $this->database->setQuery($this->query);

        $db_prefix = null;
        if (isset($this->options['db_prefix'])) {
            $db_prefix = $this->options['db_prefix'];
        }

        $qs = $this->query->__toString();

        return str_replace('#__', $db_prefix, $qs);
    }
}
