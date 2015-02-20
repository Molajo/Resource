<?php
/**
 * Class Map
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\ClassMap;

use ReflectionParameter;
use stdClass;

/**
 * Class Map
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class Items extends Aggregate
{
    /**
     * Loop through Class Map files and process as Interfaces or Concretes
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function processItems()
    {
        foreach ($this->classmap_files as $file) {

            $reflection = $this->getReflectionObject($file->qns);

            if ($reflection === false) {

            } else {

                $class_object = $this->initialiseObject($file->path, $reflection);

                if ($reflection->isInterface() === true) {
                    $this->setInterfaceClass($file->qns, $class_object);
                } else {
                    $this->setConcreteClass($file->qns, $reflection, $class_object);
                }
            }
        }

        return $this;
    }

    /**
     * Initialise Class Object for Interface or Concrete
     *
     * @param   string $path
     * @param   object $reflection
     *
     * @return  $class_object
     * @since   1.0.0
     */
    protected function initialiseObject($path, $reflection)
    {
        $class_object = new stdClass();

        $class_object->name      = $reflection->getShortName();
        $class_object->namespace = $reflection->getNamespaceName();
        $class_object->qns       = $reflection->getName();
        $class_object->file_name = $reflection->getFileName();
        $class_object->path      = $path;

        $parent = $reflection->getParentClass();

        if ($parent === false) {
            $class_object->parent = false;
        } else {
            $class_object->parent = $parent->name;
        }

        return $class_object;
    }

    /**
     * Set Interface Class Array with Interface Object
     *
     * @param   string $qns
     * @param   object $class_object
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setInterfaceClass($qns, $class_object)
    {
        $this->interfaces[$qns] = $class_object;

        return $this;
    }

    /**
     * Process Concrete Class for Interfaces and Constructor Parameters
     *
     * @param   string $qns
     * @param   object $reflection
     * @param   object $class_object
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setConcreteClass($qns, $reflection, $class_object)
    {
        $this->setConcreteInterfaces($reflection, $class_object, $qns);

        $this->setConcreteConstructorParameters($reflection, $class_object, $qns);

        $this->concretes[$qns] = $class_object;

        return $this;
    }

    /**
     * Set Interfaces for Concrete
     *
     * @param   object $reflection
     * @param   object $class_object
     * @param   string $qns
     *
     * @return  $this;
     * @since   1.0.0
     */
    protected function setConcreteInterfaces($reflection, $class_object, $qns)
    {
        $class_object->implemented_interfaces = $reflection->getInterfaceNames();

        if (count($class_object->implemented_interfaces) > 0) {
            foreach ($class_object->implemented_interfaces as $interface) {
                $this->setConcreteInterfaceRelationship($interface, 0, $qns);
            }
        }

        return $this;
    }

    /**
     * Set array for multiple use of interfaces
     *
     * @param   object $reflection
     * @param   object $class_object
     * @param   string $qns
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setConcreteConstructorParameters($reflection, $class_object, $qns)
    {
        $class_object->constructor_parameters = array();

        if (method_exists($qns, '__construct')) {
        } else {
            return $this;
        }

        $construct                            = $reflection->getMethod('__construct');
        $class_object->constructor_docComment = $construct->getDocComment();
        $parameters                           = $construct->getParameters();

        if (count($parameters) > 0) {

            $temp = array();
            foreach ($parameters as $parameter) {
                $temp[] = $this->processDependencies(array($qns, '__construct'), $parameter);
            }

            $class_object->constructor_parameters = $temp;

            $this->setConcreteDependencyInterfaces(
                $class_object->qns,
                $class_object->constructor_parameters
            );
        }

        return $this;
    }

    /**
     * Process Dependencies for the Class
     *
     * @param   array  $class_method_array
     * @param   object $parameter
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function processDependencies($class_method_array, $parameter)
    {
        $parameters_object = new stdClass();
        $param             = new ReflectionParameter($class_method_array, $parameter->name);

        $parameters_object->name = $param->getName();

        if ($param->isDefaultValueAvailable() === true) {
            $parameters_object->default_available = true;
            $parameters_object->default_value     = $param->getDefaultValue();
        } else {
            $parameters_object->default_available = false;
            $parameters_object->default_value     = null;
        }

        $instance_dependency = $param->getClass();

        if ($instance_dependency === null) {
            $parameters_object->instance_of     = null;
            $parameters_object->is_instantiable = false;
        } else {
            $parameters_object->instance_of     = $instance_dependency->name;
            $parameters_object->is_instantiable = $instance_dependency->isInstantiable();
        }

        return $parameters_object;
    }

    /**
     * Add to Dependency Interfaces List
     *
     * @param   string $qns
     * @param   array  $concretes
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setConcreteDependencyInterfaces($qns, array $dependencies)
    {
        if (count($dependencies) > 0) {

            foreach ($dependencies as $interface) {
                if ($interface->instance_of === null) {
                } else {
                    $this->setConcreteInterfaceRelationship($interface->instance_of, 1, $qns);
                }
            }
        }

        return $this;
    }

    /**
     * Process Implemented Interfaces for the Class
     *
     * @param   string  $interface
     * @param   integer $type
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setConcreteInterfaceRelationship($interface, $type = 0, $qns = '')
    {
        $implemented_by = array();
        $dependency_for = array();

        if (isset($this->interface_usage[$interface])) {
            $interface_object = $this->interface_usage[$interface];
        } else {
            $interface_object = new stdClass();
        }

        if (isset($interface_object->implemented_by)) {
            $implemented_by = $interface_object->implemented_by;
        } else {
            $interface_object->implemented_by = array();
        }

        if (isset($interface_object->dependency_for)) {
            $dependency_for = $interface_object->dependency_for;
        } else {
            $interface_object->dependency_for = array();
        }

        if ($type === 0) {
            $implemented_by[] = $qns;
        } else {
            $dependency_for[] = $qns;
        }

        sort($implemented_by);
        $interface_object->implemented_by = array_unique($implemented_by);

        sort($dependency_for);
        $interface_object->dependency_for = array_unique($dependency_for);

        $this->interface_usage[$interface] = $interface_object;

        return $this;
    }
}
