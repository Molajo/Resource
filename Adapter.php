<?php
/**
 * Resource Locator Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Kernel\Locator;

use Molajo\Kernel\Locator\Exception\LocatorException;
use Molajo\Kernel\Locator\Api\LocatorInterface;
use Molajo\Kernel\Locator\Api\ClassLocatorInterface;

/**
 * Resource Locator Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class Adapter implements LocatorInterface, ClassLocatorInterface
{
    /**
     * Handler Instances
     *
     * @var    object  Molajo\Kernel\Locator\Api\LocatorInterface
     * @since  1.0
     */
    protected $handler_instance;

    /**
     * Constructor
     *
     * @param   LocatorInterface $handler_instance
     * @param   string          $handler
     *
     * @since   1.0
     */
    public function __construct(LocatorInterface $handler_instance, $handler = 'Class')
    {
        $this->handler_instance = $handler_instance;

        if ($handler == 'Class') {
            $this->register();
        }
    }

    /**
     * Registers Class Autoloader
     *
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0
     */
    public function register($prepend = true)
    {
        spl_autoload_register(array($this, 'findResource'), true, $prepend);

        return $this;
    }

    /**
     * Unregister Class Autoloader
     *
     * @return  $this
     * @since   1.0
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'findResource'));

        return $this;
    }

    /**
     * Registers a namespace prefix with filesystem path, appending the filesystem path to existing paths
     *
     * @param   string   $namespace_prefix
     * @param   string   $base_directory
     * @param   boolean  $replace
     *
     * @return  $this
     * @since   1.0
     */
    public function addNamespace($namespace_prefix, $base_directory, $replace = false)
    {
        $this->handler_instance->addNamespace($namespace_prefix, $base_directory, $replace);

        return $this;
    }

    /**
     * Add resource map which maps folder/file locations to Fully Qualified Namespaces
     *
     * @return  $this
     * @since   1.0
     */
    public function createResourceMap()
    {
        $this->handler_instance->createResourceMap();

        return $this;
    }

    /**
     * Locates folder/file associated with Fully Qualified Namespace for Resource and passes
     * the path to a handler for that type of resource (ex. a Class Locator includes the file)
     *
     * @param   string $resource
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Kernel\Locator\Exception\LocatorException
     */
    public function findResource($resource, array $options = array())
    {
        return $this->handler_instance->findResource($resource, $options);
    }

    /**
     * Retrieve a collection of a specific resource type (ex., all CSS files registered)
     *
     * @param   array $options
     *
     * @return  mixed
     * @since   1.0
     */
    public function getCollection(array $options = array())
    {
        return $this->handler_instance->getCollection($options);
    }
}
