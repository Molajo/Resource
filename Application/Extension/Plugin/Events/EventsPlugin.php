<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Event;

use Molajo\Plugin\AbstractPlugin;


/**
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class EventsPlugin extends AbstractPlugin
{
    /**
     * Generates list of Events for use in Datalists
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterInitialise()
    {
        if ($this->parameters->application->id == 2) {
        } else {
            return true;
        }

        $events = array(
            'onAfterInitialise',
            'onAfterRoute',
            'onAfterAuthorise',
            'onBeforeParse',
            'onBeforeParseHead',
            'onBeforeInclude',
            'onBeforeRead',
            'onAfterRead',
            'onAfterReadall',
            'onBeforeRenderview',
            'onAfterRenderview',
            'onAfterInclude',
            'onAfterParse',
            'onAfterExecute',
            'onAfterResponse',
            'onBeforeCreate',
            'onAfterCreate',
            'onBeforeUpdate',
            'onAfterUpdate',
            'onBeforDdelete',
            'onAfterDelete',
            'onAfterLogin',
            'onBeforeLogin',
            'onAfterLogout',
            'onBeforeLogout'
        );

        foreach ($this->events->get('Events') as $e) {
            if (in_array(strtolower($e), array_map('strtolower', $events))) {
            } else {
                $events[] = $e;
            }
        }

        $eventArray = array();
        foreach ($events as $key) {

            $temp_row = new \stdClass();

            $temp_row->id    = $key;
            $temp_row->value = trim($key);

            $eventArray[] = $temp_row;
        }

        $this->registry->set('Datalist', 'EventsList', $eventArray);

        return true;
    }
}
