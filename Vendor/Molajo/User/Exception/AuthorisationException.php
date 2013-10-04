<?php
/**
 * User Authorisation Exception
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\User\Exception;

use RuntimeException;
use Molajo\User\Api\ExceptionInterface;

/**
 * User Authorisation Exception
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class AuthorisationException extends RuntimeException implements ExceptionInterface
{
}
