<?php
/**
 * Error Handling Controller Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Controller\Api;

use Molajo\Controller\Exception\ErrorThrownAsException;

/**
 * Error Handling Controller Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface ErrorHandlingControllerInterface
{
    /**
     * Set 403, 404, 500 and 503 Error. Throw exception for any other errors.
     * Set rendering parameters for theme, page and template.
     *
     * @param   int    $error_code
     * @param   string $error_message
     * @param   string $file
     * @param   string $line
     *
     * @return  object
     * @throws  \Molajo\Controller\Exception\ErrorThrownAsException
     * @since   1.0
     */
    public function setError($error_code = 0, $error_message = '', $file = '', $line = '');
}
