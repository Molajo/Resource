<?php
/**
 * Database Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Database;

use Exception;
use Molajo\Database\Exception\AdapterException;
use Molajo\Database\Api\DatabaseInterface;

/**
 * Database Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class Adapter implements DatabaseInterface
{
    /**
     * Database Adapter Handler
     *
     * @var     object  Molajo\Database\Api\DatabaseInterface
     * @since   1.0
     */
    protected $handler;

    /**
     * Constructor
     *
     * @param  DatabaseInterface $database
     *
     * @since  1.0
     */
    public function __construct(DatabaseInterface $database)
    {
        $this->handler = $database;
    }

    /**
     * Set the current query object for the current database connection
     *
     * @return  object
     * @since   1.0
     * @throws  AdapterException
     */
    public function getQueryObject()
    {
        try {
            return $this->handler->getQueryObject();

        } catch (Exception $e) {

            throw new AdapterException
            ('Database Adapter getQueryObject Exception: ' . $e->getMessage());
        }
    }

    /**
     * Returns a database driver compliant date format for PHP date()
     *
     * @return  string
     * @since   1.0
     * @throws  AdapterException
     */
    public function getDateFormat()
    {
        try {
            return $this->handler->getDateFormat();

        } catch (Exception $e) {

            throw new AdapterException
            ('Database Adapter getDateFormat Exception: ' . $e->getMessage());
        }
    }

    /**
     * Retrieves the current date and time formatted compliant with the database driver
     *
     * @return  string
     * @since   1.0
     * @throws  AdapterException
     */
    public function getDate()
    {
        try {
            return $this->handler->getDate();

        } catch (Exception $e) {

            throw new AdapterException
            ('Database Adapter getDate Exception: ' . $e->getMessage());
        }
    }

    /**
     * Returns a database driver compliant value for null date
     *
     * @return  string
     * @since   1.0
     * @throws  AdapterException
     */
    public function getNullDate()
    {
        try {
            return $this->handler->getNullDate();

        } catch (Exception $e) {

            throw new AdapterException
            ('Database Adapter getNullDate Exception: ' . $e->getMessage());
        }
    }

    /**
     * Quote Value
     *
     * @param   string $value
     *
     * @return  mixed
     * @since   1.0
     * @throws  AdapterException
     */
    public function quote($value)
    {
        try {
            return $this->handler->quote($value);

        } catch (Exception $e) {

            throw new AdapterException
            ('Database Adapter quote Exception: ' . $e->getMessage());
        }
    }

    /**
     * Quote and return name
     *
     * @param   string $name
     *
     * @return  string
     * @since   1.0
     * @throws  AdapterException
     */
    public function quoteName($name)
    {
        try {
            return $this->handler->quoteName($name);

        } catch (Exception $e) {

            throw new AdapterException
            ('Database Adapter quoteName Exception: ' . $e->getMessage());
        }
    }

    /**
     * Method to escape a string for usage in an SQL statement.
     *
     * @param   string $text
     *
     * @return  string
     * @since   1.0
     * @throws  AdapterException
     */
    public function escape($text)
    {
        try {
            return $this->handler->escape($text);

        } catch (Exception $e) {

            throw new AdapterException
            ('Database Adapter escape Exception: ' . $e->getMessage());
        }
    }

    /**
     * Returns a string containing the query, resolved from the query object
     *
     * @return  string
     * @since   1.0
     * @throws  AdapterException
     */
    public function getQueryString()
    {
        try {
            return $this->handler->getQueryString();

        } catch (Exception $e) {

            throw new AdapterException
            ('Database Adapter getQueryString Exception: ' . $e->getMessage());
        }
    }

    /**
     * Single Value Query Result returned
     *
     * @return  string
     * @since   1.0
     * @throws  AdapterException
     */
    public function loadResult()
    {
        try {
            return $this->handler->loadResult();

        } catch (Exception $e) {

            throw new AdapterException
            ('Database Adapter loadResult Exception: ' . $e->getMessage());
        }
    }

    /**
     * Array of object values returned from query
     *
     * @param   null|int $offset
     * @param   null|int $limit
     *
     * @return  object
     * @since   1.0
     * @throws  AdapterException
     */
    public function loadObjectList($offset = null, $limit = null)
    {
        try {
            return $this->handler->loadObjectList($offset, $limit);

        } catch (Exception $e) {

            throw new AdapterException
            ('Database Adapter loadObjectList Exception: ' . $e->getMessage());
        }
    }

    /**
     * Execute the Database Query (SQL can be sent in or derived from Query Object)
     *
     * @param   string $sql
     *
     * @return  object
     * @since   1.0
     * @throws  AdapterException
     */
    public function execute($sql = null)
    {
        try {
            return $this->handler->execute($sql = null);

        } catch (Exception $e) {

            throw new AdapterException
            ('Database Adapter execute Exception: ' . $e->getMessage());
        }
    }

    /**
     * Returns the primary key following insert
     *
     * @return  integer
     * @since   1.0
     * @throws  AdapterException
     */
    public function getInsertId()
    {
        try {
            return $this->handler->getInsertId();

        } catch (Exception $e) {

            throw new AdapterException
            ('Database Adapter getInsertId Exception: ' . $e->getMessage());
        }
    }

    /**
     * Alias of Quote
     *
     * @param   string $value
     *
     * @return  string
     * @since   1.0
     * @throws  AdapterException
     */
    public function q($value)
    {
        try {
            return $this->handler->quote($value);

        } catch (Exception $e) {

            throw new AdapterException
            ('Database Adapter q Exception: ' . $e->getMessage());
        }
    }

    /**
     * Alias of quoteName
     *
     * @param   string $name
     *
     * @return  string
     * @since   1.0
     * @throws  AdapterException
     */
    public function qn($name)
    {
        try {
            return $this->handler->quoteName($name);

        } catch (Exception $e) {

            throw new AdapterException
            ('Database Adapter qn Exception: ' . $e->getMessage());
        }
    }

    /**
     * Alias of Escape
     *
     * @param   string $text
     * @param   bool   $extra
     *
     * @return  string
     * @since   1.0
     * @throws  AdapterException
     */
    public function e($text, $extra)
    {
        try {
            return $this->handler->escape($text, $extra);

        } catch (Exception $e) {

            throw new AdapterException
            ('Database Adapter escape Exception: ' . $e->getMessage());
        }
    }
}
