<?php
/**
 * Dispatcher Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Api;

use Molajo\Plugin\Exception\DispatcherException;

/**
 * Dispatcher Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface EventDataInterface
{
    /**
     * Requester schedules an Event with Dispatcher
     *
     * @param   EventInterface $event
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\DispatcherException
     */
    public function scheduleEvent(EventInterface $event);

    /**
     * Listener registers for an Event with the Dispatcher
     *
     * @param   string   $event_name
     * @param   callable $callback
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\DispatcherException
     */
    public function registerForEvent($event_name, callable $callback);

    /**
     * Listener unregisters for an Event with the Dispatcher
     *
     * @param  string   $event_name
     * @param  callable $callback
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\DispatcherException
     */
    public function unregisterForEvent($event_name, callable $callback);
}
