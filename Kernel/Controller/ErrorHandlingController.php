<?php
/**
 * Error Handling Controller
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Controller;

use stdClass;
use Molajo\Controller\Api\ErrorHandlingControllerInterface;
use Molajo\Controller\Exception\ErrorThrownAsException;

/**
 * Error Handling Controller
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class ErrorHandlingController implements ErrorHandlingControllerInterface
{
    /**
     * Not Authorised Message
     *
     * @var    string
     * @since  1.0
     */
    protected $error_message_not_authorised = 'Not Authorised';

    /**
     * Not Found Message
     *
     * @var    string
     * @since  1.0
     */
    protected $error_message_not_found = 'Not Found';

    /**
     * Internal Server Error Message
     *
     * @var    string
     * @since  1.0
     */
    protected $error_message_internal_server_error = 'Internal Server Error';

    /**
     * Site is Offline Error Message
     *
     * @var    string
     * @since  1.0
     */
    protected $error_message_offline_mode = 'Site is Offline.';

    /**
     * Error Theme Namespace
     *
     * @var    string
     * @since  1.0
     */
    protected $error_theme = 'Molajo\\Theme\\System';

    /**
     * Page View for Offline Error
     *
     * @var    string
     * @since  1.0
     */
    protected $error_page_offline_view = 'Molajo\\View\\Page\\Offline';

    /**
     * Page View for Standard Error
     *
     * @var    string
     * @since  1.0
     */
    protected $error_page_view = 'Molajo\\View\\Page\\Error';

    /**
     * Class Constructor
     *
     * @param  string $error_theme
     * @param  string $error_page_view
     * @param  string $error_message_not_authorised
     * @param  string $error_message_not_found
     * @param  string $error_message_internal_server_error
     * @param  string $error_offline_theme
     * @param  string $error_page_offline_view
     * @param  string $error_message_offline_mode
     *
     * @since  1.0
     */
    public function __construct(
        $error_theme = 'Molajo\\Theme\\System',
        $error_page_view = 'Molajo\\View\\Page\\Error',
        $error_message_not_authorised = 'Not Authorised',
        $error_message_not_found = 'Not Found',
        $error_message_internal_server_error = 'Internal Server Error',
        $error_offline_theme = 'Molajo\\Theme\\System',
        $error_page_offline_view = 'Molajo\\View\\Page\\Offline',
        $error_message_offline_mode = 'This site is not available.\<\br /\>\ Please check back again soon.'
    ) {
        set_error_handler(array($this, 'setError'), 0);
    }

    /**
     * Set 403, 404, 500 and 503 Error. Throw exception for any other errors.
     * Set rendering parameters for theme, page and template.
     *
     * @param   int    $error_code
     * @param   string $error_message
     * @param   string $file
     * @param   string $line
     *
     * @return  object|stdClass
     * @throws  \Molajo\Controller\Exception\ErrorThrownAsException
     * @since   1.0
     */
    public function setError($error_code = 0, $error_message = '', $file = '', $line = '')
    {
        $error_object = new stdClass();

        if ($error_code == 403) {
            $error_message = $this->error_message_not_authorised;
        } elseif ($error_code == 404) {
            $error_message = $this->error_message_not_found;
        } elseif ($error_code == 500) {
            $error_message = $this->error_message_internal_server_error;
        } elseif ($error_code == 503) {
            $error_message = $this->error_message_offline_mode;
        } else {
            throw new ErrorThrownAsException ($error_message, 0, $error_code, $file, $line);
        }

        $error_object->error_code    = $error_code;
        $error_object->error_message = $error_message;

        if ($error_code == 503) {
            $error_object->theme_namespace = $this->error_theme;
            $error_object->page_namespace  = $this->error_page_offline_view;
        } else {
            $error_object->theme_namespace = $this->error_theme;
            $error_object->page_namespace  = $this->error_page_view;
        }

        return $error_object;
    }
}
