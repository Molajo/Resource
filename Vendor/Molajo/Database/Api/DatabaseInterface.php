<?php
/**
 * Database Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Database\Api;

use Molajo\Database\Exception\DatabaseException;

/**
 * Database Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface DatabaseInterface
{
    /**
     * Retrieves the PHP date format that is compliant with the database driver
     *
     *  $date_format = $adapter->getDateFormat();
     *
     * @return  string
     * @since   1.0
     */
    public function getDateFormat();

    /**
     * Retrieves the current date and time formatted compliant with the database driver
     *
     *  $date_format = $adapter->getDate();
     *
     * @return  string
     * @since   1.0
     */
    public function getDate();

    /**
     * Returns a value for null date that is compliant with the database driver
     *
     *  $null_date = $adapter->getNullDate();
     *
     * @return  string
     * @since   1.0
     * @throws  DatabaseException
     */
    public function getNullDate();

    /**
     * Returns the value sent in quoted for use in a database query
     *
     *  $adapter->quote($string);
     *
     * @param   string $value
     *
     * @return  mixed
     * @since   1.0
     * @throws  DatabaseException
     */
    public function quote($value);

    /**
     * Returns the name sent in (ex. table name or column name) having quoted it for use in a database query
     *
     *  $adapter->quoteName($field_name);
     *
     * @param   string $name
     *
     * @return  string
     * @since   1.0
     * @throws  DatabaseException
     */
    public function quoteName($name);

    /**
     * Escapes value sent in for use in a database query
     *
     *  $adapter->escape($text);
     *
     * @param   string $text
     *
     * @return  string
     * @since   1.0
     * @throws  DatabaseException
     */
    public function escape($text);

    /**
     * Using the Query Object already specified, to build the SQL, sends the SQL as a request to the database,
     *  returning a single data value as the result
     *
     * $results = $adapter->loadResult($offset, $limit);
     *
     * echo $results->title;
     *
     * @return  object
     * @since   1.0
     */
    public function loadResult();

    /**
     * Using the Query Object already specified, sends the SQL request to the database, returning an array of rows
     *  each row of which is an object. Offset represents the row number in the result set from which to start.
     *  Limit specifies the maximum number of rows to be returned.
     *
     * $results = $adapter->loadObjectList($offset, $limit);
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
     * @throws  DatabaseException
     */
    public function loadObjectList($offset = null, $limit = null);

    /**
     * Execute the Database Query. If $sql is not sent in, the Execute method will create the SQL from
     *  the query object. Execute can be used to create tables, insert, update, delete data, and also to
     *  select data.
     *
     * $results = $adapter->execute($sql);
     *
     * @param   null|string $sql
     *
     * @return  object
     * @since   1.0
     * @throws  DatabaseException
     */
    public function execute($sql = null);

    /**
     * Returns the primary key following insert
     *
     * $sql = 'INSERT (x, y, z) INTO $__table VALUES (1, 2, 3)';
     * $results = $adapter->execute($sql);
     *
     * echo $adapter->getInsertId();
     *
     * @return  integer
     * @since   1.0
     * @throws  DatabaseException
     */
    public function getInsertId();
}
