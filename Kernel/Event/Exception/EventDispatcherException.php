<?php
/**
 * Authenticate Event Exception
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Event\Exception;

use Exception;
use Molajo\Event\Api\ExceptionInterface;

/**
 * Authenticate Event Exception
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class EventDispatcherException extends Exception implements ExceptionInterface
{

}
