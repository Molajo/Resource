<?php
/**
 * Class Map
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\ClassMap;

use Exception;
use ReflectionClass;

/**
 * Class Map Base
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
abstract class Base
{
    /**
     * Class Map Array
     *
     * @var    array
     * @since  1.0
     */
    protected $classmap_files = array();

    /**
     * Interfaces Filename
     *
     * @var    string
     * @since  1.0
     */
    protected $interface_classes_filename;

    /**
     * Class Dependencies
     *
     * @var    string
     * @since  1.0
     */
    protected $concrete_classes_filename;

    /**
     * Events
     *
     * @var    string
     * @since  1.0
     */
    protected $events_filename;

    /**
     * Interfaces
     *
     * @var    array
     * @since  1.0
     */
    protected $interfaces = array();

    /**
     * Interface Usage
     *
     * @var    array
     * @since  1.0
     */
    protected $interface_usage = array();

    /**
     * Concretes
     *
     * @var    array
     * @since  1.0
     */
    protected $concretes = array();

    /**
     * Events
     *
     * @var    array
     * @since  1.0
     */
    protected $events = array();

    /**
     * Base Path - root of the website from which paths are defined
     *
     * @var    string
     * @since  1.0
     */
    protected $base_path;

    /**
     * Constructor
     *
     * @param  array   $class_maps
     * @param  string  $interface_classes_filename
     * @param  string  $concrete_classes_filename
     * @param  string  $events_filename
     *
     * @since  1.0
     */
    public function __construct(
        array $classmap_files = array(),
        $interface_classes_filename = '',
        $concrete_classes_filename = '',
        $events_filename = '',
        $base_path
    ) {
        $this->classmap_files             = $classmap_files;
        $this->interface_classes_filename = $interface_classes_filename;
        $this->concrete_classes_filename  = $concrete_classes_filename;
        $this->events_filename            = $events_filename;
        $this->base_path                  = $base_path;
    }

    /**
     * Get Reflection Object from PHP
     *
     * @param  string $qns
     *
     * @since  1.0
     * @return object
     */
    protected function getReflectionObject($qns)
    {
        try {
            return new ReflectionClass($qns);
        } catch (Exception $e) {
            return false;
        }
    }
}
