<?php
/**
 * Class Map
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\ClassMap;

use ReflectionClass;

/**
 * Class Map
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class Events extends Base
{
    /**
     * Events
     *
     * @var    array
     * @since  1.0
     */
    protected $events = array();

    /**
     * For each Interface, determine Concrete Classes which Implement the Interface and
     *  Requirements for a Concrete Class expressed by the Interface as a Type Hint in the Method Parameters
     *
     * @since   1.0
     * @return  $this
     */
    protected function setEvents()
    {
        $this->events = array();

        foreach ($this->concretes as $concrete) {

                if (count($concrete->method) > 0) {

                    foreach ($concrete->method as $method) {

                        if ($this->testMethodForPlugin($concrete, $method) === true) {

                            $class_instance = new \ReflectionClass($concrete->qns);
                            $abstract       = $class_instance->isAbstract();

                            $reflectionMethod = new \ReflectionMethod(new $concrete->qns, $method);
                            $results          = $reflectionMethod->getDeclaringClass();

                            if ($results->name === $concrete->qns) {
                                if (isset($this->events[$method])) {
                                    $classes = $this->events[$method];
                                } else {
                                    $classes = array();
                                }
                                $classes[]             = $concrete->qns;
                                $this->events[$method] = array_unique($classes);
                            }
                        }
                    }
                }

        }

        return $this;
    }

    /**
     * Test Method to determine if it is a Plugin
     *
     * @param   string $concrete
     * @param   string $method
     *
     * @return  boolean
     * @since   1.0
     */
    protected function testMethodForPlugin($concrete, $method)
    {
        if (substr($method, 0, 2) == 'on') {
        } else {
            return false;
        }

        if (strpos($concrete->qns, 'Plugins') > 0) {
        } else {
            return false;
        }

        $class_instance = new ReflectionClass($concrete->qns);

        if ($class_instance->isAbstract() === true) {
            return false;
        }

        return true;
    }
}
