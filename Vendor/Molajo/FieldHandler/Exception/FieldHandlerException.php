<?php
/**
 * FieldhandlerException
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Fieldhandler\Exception;


use RuntimeException;
use Molajo\Fieldhandler\Api\ExceptionInterface;

/**
 * FieldhandlerException Exception
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class FieldhandlerException extends RuntimeException implements ExceptionInterface
{

}
