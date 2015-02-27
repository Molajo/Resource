<?php
/**
 *  Proxy
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo\Resource;

use CommonApi\Resource\ResourceInterface;
use Molajo\Resource\Proxy;
use stdClass;

/**
 * Scheme Test
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class ProxyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Map Instance
     *
     * @var    object
     * @since  1.0.0
     */
    protected $proxy_instance;

    /**
     * @covers  Molajo\Resource\Proxy::setNamespace
     * @covers  Molajo\Resource\Proxy::exists
     * @covers  Molajo\Resource\Proxy::get
     * @covers  Molajo\Resource\Proxy::getCollection
     * @covers  Molajo\Resource\Proxy\ClassLoader::register
     * @covers  Molajo\Resource\Proxy\ClassLoader::unregister
     * @covers  Molajo\Resource\Proxy\Scheme::__construct
     * @covers  Molajo\Resource\Proxy\Scheme::setScheme
     * @covers  Molajo\Resource\Proxy\Scheme::getScheme
     * @covers  Molajo\Resource\Proxy\Scheme::setAdapterNamespaces
     * @covers  Molajo\Resource\Proxy\Scheme::saveNamespaceArray
     * @covers  Molajo\Resource\Proxy\Scheme::locateScheme
     * @covers  Molajo\Resource\Proxy\Scheme::getUriScheme
     * @covers  Molajo\Resource\Proxy\Scheme::removeUriScheme
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setUp()
    {
        $class  = 'Molajo\\Resource\\Scheme';
        $scheme = new $class();

        $class  = 'Molajo\\Resource\\MockProxy';
        $this->proxy_instance = new $class($scheme);

        return $this;
    }

    /**
     * @covers  Molajo\Resource\Proxy::setNamespace
     * @covers  Molajo\Resource\Proxy::exists
     * @covers  Molajo\Resource\Proxy::get
     * @covers  Molajo\Resource\Proxy::getCollection
     * @covers  Molajo\Resource\Proxy\ClassLoader::register
     * @covers  Molajo\Resource\Proxy\ClassLoader::unregister
     * @covers  Molajo\Resource\Proxy\Scheme::__construct
     * @covers  Molajo\Resource\Proxy\Scheme::setScheme
     * @covers  Molajo\Resource\Proxy\Scheme::getScheme
     * @covers  Molajo\Resource\Proxy\Scheme::setAdapterNamespaces
     * @covers  Molajo\Resource\Proxy\Scheme::saveNamespaceArray
     * @covers  Molajo\Resource\Proxy\Scheme::locateScheme
     * @covers  Molajo\Resource\Proxy\Scheme::getUriScheme
     * @covers  Molajo\Resource\Proxy\Scheme::removeUriScheme
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setNs()
    {
        $this->proxy_instance->setNamespace('Molajo\\A\\', 'Source/A/');
        $this->proxy_instance->setNamespace('Molajo\\B\\', 'Source/B/');
        $this->proxy_instance->setNamespace('Molajo\\C\\', 'Source/C/');
        $this->proxy_instance->setNamespace('Molajo\\Plugins\\', 'Source/Plugins/');

        return $this;
    }

    /**
     * @covers  Molajo\Resource\Proxy::setNamespace
     * @covers  Molajo\Resource\Proxy::exists
     * @covers  Molajo\Resource\Proxy::get
     * @covers  Molajo\Resource\Proxy::getCollection
     * @covers  Molajo\Resource\Proxy\ClassLoader::register
     * @covers  Molajo\Resource\Proxy\ClassLoader::unregister
     * @covers  Molajo\Resource\Proxy\Scheme::__construct
     * @covers  Molajo\Resource\Proxy\Scheme::setScheme
     * @covers  Molajo\Resource\Proxy\Scheme::getScheme
     * @covers  Molajo\Resource\Proxy\Scheme::setAdapterNamespaces
     * @covers  Molajo\Resource\Proxy\Scheme::saveNamespaceArray
     * @covers  Molajo\Resource\Proxy\Scheme::locateScheme
     * @covers  Molajo\Resource\Proxy\Scheme::getUriScheme
     * @covers  Molajo\Resource\Proxy\Scheme::removeUriScheme
     *
     * @return  $this
     * @since   1.0.0
     */
    public function testSetSchemeAdapter()
    {
        $adapter = new MockResourceAdapter();
        $this->proxy_instance->setScheme('MockResourceAdapter', $adapter, array());
        $this->proxy_instance->getScheme('MockResourceAdapter');
        $this->assertTrue(is_object($this->proxy_instance->getData('requested_adapter')));

        return $this;
    }

    /**
     * @covers  Molajo\Resource\Proxy::setNamespace
     * @covers  Molajo\Resource\Proxy::exists
     * @covers  Molajo\Resource\Proxy::get
     * @covers  Molajo\Resource\Proxy::getCollection
     * @covers  Molajo\Resource\Proxy\ClassLoader::register
     * @covers  Molajo\Resource\Proxy\ClassLoader::unregister
     * @covers  Molajo\Resource\Proxy\Scheme::__construct
     * @covers  Molajo\Resource\Proxy\Scheme::setScheme
     * @covers  Molajo\Resource\Proxy\Scheme::getScheme
     * @covers  Molajo\Resource\Proxy\Scheme::setAdapterNamespaces
     * @covers  Molajo\Resource\Proxy\Scheme::saveNamespaceArray
     * @covers  Molajo\Resource\Proxy\Scheme::locateScheme
     * @covers  Molajo\Resource\Proxy\Scheme::getUriScheme
     * @covers  Molajo\Resource\Proxy\Scheme::removeUriScheme
     *
     * @return  $this
     * @since   1.0.0
     */
    public function testSetGetScheme()
    {
        $adapter = new MockResourceAdapter();
        $this->proxy_instance->setScheme('MockResourceAdapter', $adapter, array());
        $this->proxy_instance->getScheme('MockResourceAdapter');
        $this->assertTrue(is_object($this->proxy_instance->getData('requested_adapter')));

        return $this;
    }

    /**
     * @covers  Molajo\Resource\Proxy::setNamespace
     * @covers  Molajo\Resource\Proxy::exists
     * @covers  Molajo\Resource\Proxy::get
     * @covers  Molajo\Resource\Proxy::getCollection
     * @covers  Molajo\Resource\Proxy\ClassLoader::register
     * @covers  Molajo\Resource\Proxy\ClassLoader::unregister
     * @covers  Molajo\Resource\Proxy\Scheme::__construct
     * @covers  Molajo\Resource\Proxy\Scheme::setScheme
     * @covers  Molajo\Resource\Proxy\Scheme::getScheme
     * @covers  Molajo\Resource\Proxy\Scheme::setAdapterNamespaces
     * @covers  Molajo\Resource\Proxy\Scheme::saveNamespaceArray
     * @covers  Molajo\Resource\Proxy\Scheme::locateScheme
     * @covers  Molajo\Resource\Proxy\Scheme::getUriScheme
     * @covers  Molajo\Resource\Proxy\Scheme::removeUriScheme
     *
     * @return  $this
     * @since   1.0.0
     */
    public function testSetNamespaces()
    {
        $this->setNs();

        $expected_ns = array();

        $row = new stdClass();
        $row->namespace_prefix = 'Molajo\\A\\';
        $row->base_directory = 'Source/A/';
        $row->prepend = true;
        $expected_ns[] = $row;

        $row = new stdClass();
        $row->namespace_prefix = 'Molajo\\B\\';
        $row->base_directory = 'Source/B/';
        $row->prepend = true;
        $expected_ns[] = $row;

        $row = new stdClass();
        $row->namespace_prefix = 'Molajo\\C\\';
        $row->base_directory = 'Source/C/';
        $row->prepend = true;
        $expected_ns[] = $row;


        $row = new stdClass();
        $row->namespace_prefix = 'Molajo\\Plugins\\';
        $row->base_directory = 'Source/Plugins/';
        $row->prepend = true;
        $expected_ns[] = $row;

        $actual_ns = $this->proxy_instance->getData('namespace_array');

        $this->assertEquals($expected_ns, $actual_ns);

        return $this;
    }
}

class MockProxy extends Proxy
{
    public function getData($key)
    {
        return $this->$key;
    }
}

class MockResourceAdapter implements ResourceInterface
{
    /**
     * Set a namespace prefix by mapping to the filesystem path
     *
     * @param   string  $namespace_prefix
     * @param   string  $namespace_base_directory
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0.0
     */
    public function setNamespace($namespace_prefix, $namespace_base_directory, $prepend = false)
    {

    }

    /**
     * Verify if resource namespace is defined
     *
     * @param   string $resource_namespace
     * @param   array  $options
     *
     * @return  boolean
     * @since   1.0.0
     */
    public function exists($resource_namespace, array $options = array())
    {

    }

    /**
     * Locates folder/file associated with Namespace
     *
     * @param   string $resource_namespace
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0.0
     */
    public function get($uri_namespace, array $options = array())
    {

    }

    /**
     * Retrieve a collection of a specific handler
     *
     * @param   string $scheme
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0.0
     */
    public function getCollection($scheme, array $options = array())
    {

    }
}
