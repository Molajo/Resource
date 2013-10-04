<?php
/**
 * Event Dispatcher Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Event\Api;

use Molajo\Event\Api\EventInterface;

/**
 * Event Dispatcher Interface
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Interface EventDispatcherInterface
{
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
    public function delegateEvent(EventInterface $event, array $listeners = array());

    /**
     * Event Dispatcher triggers Listeners in order of priority
     *
     * @param    callable $listener
     *
     * @return   mixed
     * @since    1.0
     * @throws   \Molajo\Event\Exception\DispatcherException
     */
    public function triggerListener(callable $listener);
}
