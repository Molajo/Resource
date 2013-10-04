<?php
/**
 * Flash Message Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\User\Api;

use Molajo\User\Exception\FlashMessageException;

/**
 * Flash Message Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface FlashMessageInterface
{
    /**
     * Get Flash Messages for User, all or by Type
     *
     * @param   null|string $type (Success, Notice, Warning, Error)
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\User\Exception\FlashMessageException
     */
    public function getFlashMessage($type = null);

    /**
     * Save a Flash Message (User Message)
     *
     * @param   string $type (Success, Notice, Warning, Error)
     * @param   string $message
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\User\Exception\FlashMessageException
     */
    public function setFlashMessage($type, $message);

    /**
     * Delete Flash Messages, all or by type
     *
     * @param   null|string $type
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\User\Exception\FlashMessageException
     */
    public function deleteFlashMessage($type = null);
}
