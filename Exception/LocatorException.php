<?php
/**
 * LocatorException
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Kernel\Locator\Exception;

use RuntimeException;
use Molajo\Kernel\Locator\Api\ExceptionInterface;

/**
 * Locator Exception
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class LocatorException extends RuntimeException implements ExceptionInterface
{

}
