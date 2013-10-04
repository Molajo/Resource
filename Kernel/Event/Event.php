<?php
/**
 * Event Schedule
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Event;

use Molajo\Event\Api\EventInterface;
use Molajo\Event\Api\ScheduleInterface;
use Molajo\Event\Exception\ScheduleException;

/**
 * Event Schedule
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Event implements EventInterface
{
    /**
     * Indicator Event Service has been activated
     *
     * @var    array
     * @since  1.0
     */
    protected $on;

    /**
     * Constructor
     *
     * @param   array $options
     *
     * @since   1.0
     */
    public function __construct()
    {

    }

    /**
     * Event Schedule triggers Schedules in order of priority
     *
     * @param    EventInterface $event
     *
     * @return   mixed
     * @since    1.0
     * @throws   \Molajo\Event\Exception\ScheduleException
     */
    public function schedule()
    {
        return $this;
    }
}
