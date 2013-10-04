<?php
/**
 * Event Dispatcher
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Event;

use stdClass;
use Exception;
use Molajo\Event\Api\EventInterface;
use Molajo\Event\Api\EventDispatcherInterface;
use Molajo\Event\Exception\EventDispatcherException;

/**
 * Event Dispatcher
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * Event
     *
     * @var    object  Molajo\Event\Api\EventInterface
     * @since  1.0
     */
    protected $event = null;

    /**
     * Listeners
     *
     * @var    array
     * @since  1.0
     */
    protected $listeners = array();

    /**
     * Parameters
     *
     * @var    object
     * @since  1.0
     */
    protected $parameters = null;

    /**
     * Dispatcher delegates Event Management to the Event Dispatcher
     *
     * @param   EventInterface $event
     * @param   array          $listeners
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Event\Exception\EventDispatcherException
     */
    public function delegateEvent(EventInterface $event, array $listeners = array(), $parameters = null)
    {
        $this->event     = $event;
        $this->listeners = $listeners;

        if ($parameters === null) {
            $this->parameters = new stdClass();
        } else {
            $this->parameters = $parameters;
        }

        if (count($this->listeners) > 0) {
            return $this->parameters;
        }

        foreach ($this->listeners as $listener) {
            $this->triggerListener($listener);
        }

        return $this->parameters;
    }

    /**
     * Event Dispatcher triggers Listeners in order of priority
     *
     * @param    callable $listener
     *
     * @return   $this
     * @since    1.0
     * @throws   \Molajo\Event\Exception\DispatcherException
     */
    public function triggerListener(callable $listener)
    {
        //$this->event
        //$this->listener

        // listener = class -- event == method verify exists

        try {
            $this->parameters = call_user_func_array(
                array($listener, $this->event->event_name),
                array(
                    'event'      => $this->event,
                    'parameters' => $this->parameters
                )
            );

        } catch (Exception $e) {

        }

        return $this;
    }
}
