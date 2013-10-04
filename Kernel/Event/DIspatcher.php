<?php
/**
 * Dispatcher
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Event;

use Molajo\Event\Api\EventInterface;
use Molajo\Event\Api\DispatcherInterface;
use Molajo\Event\Api\EventDispatcherInterface;
use Molajo\Event\Exception\DispatcherException;

/**
 * Dispatcher
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Dispatcher implements DispatcherInterface
{
    /**
     * Event Dispatcher
     *
     * @var    object  Molajo\Event\Api\EventDispatcherInterface
     * @since  1.0
     */
    protected $event_dispatcher = null;

    /**
     * Registered Listeners by Event
     *
     * @var    array
     * @since  1.0
     */
    protected $listeners_by_event = array();

    /**
     * Registered Listeners by Callback
     *
     * @var    array
     * @since  1.0
     */
    protected $callback_events = array();

    /**
     * Class Constructor
     *
     * @param  EventDispatcherInterface $event_dispatcher
     *
     * @since 1.0
     */
    public function __construct(EventDispatcherInterface $eventdispatcher)
    {
        $this->event_dispatcher = $eventdispatcher;
    }

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
    public function scheduleEvent($event_name, $parameters)
    {
        if (isset($this->listeners_by_event[$event_name])) {
            $listeners  = $this->listeners_by_event[$event_name];
            $event      = new Event();
            $parameters = $this->event_dispatcher->delegateEvent($event, $listeners, $parameters);
        }

        return $parameters;
    }

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
    public function registerForEvent($event_name, callable $callback, $priority = 50)
    {
        if (isset($this->listeners_by_event[$event_name])) {
            $listeners = $this->listeners_by_event[$event_name];
        } else {
            $listeners = array();
        }

        if (isset($listeners[$priority])) {
            $priority_listeners = $listeners[$priority];
        } else {
            $priority_listeners = array();
        }

        $priority_listeners[] = $callback;
        $listeners[$priority] = $priority_listeners;
        krsort($listeners);
        $this->listeners_by_event[$event_name] = $listeners;
/**
        if (isset($this->callback_events[$callback])) {
            $callback_events = $this->callback_events[$callback];
        } else {
            $callback_events = array();
        }

        $callback_events[]                = $event_name;
        $this->callback_events[$callback] = $callback_events;
*/
        return $this;
    }

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
    public function unregisterForEvent(callable $callback, $event_name = null)
    {
/**        if (isset($this->callback_events[$callback])) {
        } else {
            return $this;
        }

        if ($event_name === null) {
            unset($this->callback_events[$callback]);
            return $this;
        }

        $events = $this->callback_events[$callback];
*/
        if (count($events) === 0) {
            return $this;
        }

        $save_events = array();

        foreach ($events as $event) {
            if ($event === $event_name) {
            } else {
                $save_events[] = $event;
            }
        }

//        $this->callback_events[$callback] = $save_events;

        return $this;
    }
}
