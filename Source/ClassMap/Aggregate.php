<?php
/**
 * Class Map Item Aggregation
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\ClassMap;

/**
 * Class Map Item Aggregation
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
abstract class Aggregate extends Events
{
    /**
     * Determine Concrete Classes implement the Interface
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function finalizeItems()
    {
        $this->setInterfaces();

        $this->setConcretes();

        return $this;
    }

    /**
     * Determine Concrete Classes implement the Interface
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setInterfaces()
    {
        if (count($this->interfaces) === 0) {
            return $this->interfaces;
        }

        ksort($this->interface_usage);
        ksort($this->interfaces);

        foreach ($this->interfaces as $interface) {

            $interface->implemented_by = array();
            $interface->dependency_for = array();

            if (isset($this->interface_usage[$interface->qns])) {
                $this->setInterfaceValues($interface, $interface->qns);
            }
        }

        return $this->interfaces;
    }

    /**
     * Determine Concrete Classes implement the Interface
     *
     * @param   object $interface
     * @param   string $qns
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setInterfaceValues($interface, $qns)
    {
        if (isset($this->interface_usage[$qns]->implemented_by)) {
            $interface->implemented_by = $this->interface_usage[$qns]->implemented_by;
        }

        if (isset($this->interface_usage[$qns]->dependency_for)) {
            $interface->dependency_for = $this->interface_usage[$qns]->dependency_for;
        }

        return $this;
    }

    /**
     * Determine Concrete Classes implement the Interface
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setConcretes()
    {
        foreach ($this->concretes as $concrete) {

            $concrete->method = get_class_methods($concrete->qns);

            if (count($concrete->constructor_parameters) > 0) {
                foreach ($concrete->constructor_parameters as $parameter) {
                    $this->setConcreteConstructorValues($parameter);
                }
            }
        }

        return $this;
    }

    /**
     * Determine Concrete Classes implement the Interface
     *
     * @param   object $parameter
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setConcreteConstructorValues($parameter)
    {
        $instance_of = $parameter->instance_of;

        if (isset($this->interfaces[$instance_of]->implemented_by)) {
            $parameter->implemented_by = $this->interfaces[$instance_of]->implemented_by;
            $parameter->concrete       = false;
        } else {
            $parameter->implemented_by = null;
            $parameter->concrete       = true;
        }

        return $this;
    }
}
