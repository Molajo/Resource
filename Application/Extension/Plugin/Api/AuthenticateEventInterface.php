<?php
/**
 * Authenticate Plugin Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Plugin\Api;

use Molajo\Plugin\Exception\AuthenticateEventException;

/**
 * Authenticate Plugin Interface
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Interface AuthenticateEventInterface
{
    /**
     * Before logging in processing
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\AuthenticateEventException
     */
    public function onBeforeLogin();

    /**
     * After Logging in event
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\AuthenticateEventException
     */
    public function onAfterLogin();

    /**
     * Before logging out processing
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\AuthenticateEventException
     */
    public function onBeforeLogout();

    /**
     * After Logging out event
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\AuthenticateEventException
     */
    public function onAfterLogout();
}
