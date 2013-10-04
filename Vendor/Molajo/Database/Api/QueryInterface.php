<?php
/**
 * Query Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Database\Api;

use Molajo\Database\Exception\QueryException;

/**
 * Query Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface QueryInterface
{
    /**
     * Retrieves a new Query Object from the database, clearing the contents of the object, if necessary
     *
     *  $query = $adapter->getQueryObject();
     *
     *  $query->select('*');
     *  $query->from('#__actions');
     *  $query->where('status = 1');
     *  $query->order('id');
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\Database\Exception\QueryException
     */
    public function getQueryObject();

    /**
     * Returns a string containing the query, resolved from the query object
     *
     * @return  string
     * @since   1.0
     * @throws  \Molajo\Database\Exception\QueryException
     */
    public function getQueryString();
}
