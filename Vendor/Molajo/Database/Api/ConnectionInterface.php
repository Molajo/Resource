<?php
/**
 * Connection Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Database\Api;

use Molajo\Database\Exception\DatabaseException;

/**
 * Connection Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface ConnectionInterface
{
    /**
     * Connect to the Database, passing through credentials and other data needed to secure a connection
     *
     *  $options                    = array();
     *  $options['db_type']         = $db_type;
     *  $options['db_host']         = $db_host;
     *  $options['db_user']         = $db_user;
     *  $options['db_password']     = $db_password;
     *  $options['db_name']         = $db;
     *  $options['db_prefix']       = $db_prefix;
     *  $options['process_plugins'] = $process_plugins;
     *  $options['select']          = true;
     *
     *  try {
     *          $class = 'Molajo\Database\Handler\Joomla';
     *          $handler = $class($options);
     *
     *          $class = 'Molajo\Database\Adapter';
     *          $adapter = $this->Adapter($handler);
     *
     *  } catch (Exception $e) {
     *      throw new DatabaseException ('IoC: Injector Instance failed.' . $e->getMessage());
     *  }
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0
     * @throws  DatabaseException
     */
    public function connect($options = array());

    /**
     * Disconnects from Database and removes the database connection, freeing resources
     *
     * $adapter->disconnect();
     *
     * @return  $this
     * @since   1.0
     * @throws  DatabaseException
     */
    public function disconnect();
}
