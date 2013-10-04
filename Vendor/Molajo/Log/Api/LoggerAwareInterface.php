<?php
/**
 * Logger Aware Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Log\Api;

use Molajo\Log\Exception\LogException;
use Molajo\Log\Api\LoggerInterface;

/**
 * Logger Aware Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface LoggerAwareInterface
{
    /**
     * Sets a Log Instance on the object
     *
     * @param   $logger \Molajo\Log\Api\LoggerInterface
     *
     * @return  $this
     * @since   1.0
     * @throws  LogException
     */
    public function setLogger(LoggerInterface $logger);
}
