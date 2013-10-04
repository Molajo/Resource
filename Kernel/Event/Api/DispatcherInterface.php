<?php
/**
 * Event Dispatcher Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Event\Api;

use Molajo\Event\Api\EventInterface;
use Molajo\Event\Exception\DispatcherException;
use Molajo\Controller\Api\DataInterface;

/**
 * Event Dispatcher Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface DispatcherInterface
{
    /**
     * Requester schedules an Event with Dispatcher
     *
     * @param   string $event_name
     * @param   object $parameters
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Event\Exception\DispatcherException
     */
    public function scheduleEvent($event_name, $parameters);

    /**
     * Listener registers for an Event with the Dispatcher
     *
     * @param   string   $event_name
     * @param   callable $callback
     * @param   int      $priority 0 (lowest) to 100 (highest)
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Event\Exception\DispatcherException
     */
    public function registerForEvent($event_name, callable $callback, $priority = 50);

    /**
     * Listener unregisters for an Event with the Dispatcher
     *
     * @param  callable    $callback
     * @param  null|string $event_name
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Event\Exception\DispatcherException
     */
    public function unregisterForEvent(callable $callback, $event_name = null);
}
