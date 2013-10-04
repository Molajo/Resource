<?php
/**
 * File Upload Exception
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\FileUpload\Exception;

use Exception;
use Molajo\FileUpload\Api\ExceptionInterface;

/**
 * File Upload Exception
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class UploadException extends Exception implements ExceptionInterface
{
}
