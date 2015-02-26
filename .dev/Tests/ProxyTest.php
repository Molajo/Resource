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
     * @covers  Molajo\Resource\Proxy\Uri::getUriResource
     * @covers  Molajo\Resource\Proxy\Uri::getUriPath
     * @covers  Molajo\Resource\Proxy\Uri::locateNamespace
     * @covers  Molajo\Resource\Proxy\Uri::handlePath
     * @covers  Molajo\Resource\Proxy\Uri::parseUri
     * @covers  Molajo\Resource\Proxy\Uri::setUriScheme
     * @covers  Molajo\Resource\Proxy\Uri::setUriHost
     * @covers  Molajo\Resource\Proxy\Uri::setUriUser
     * @covers  Molajo\Resource\Proxy\Uri::setUriPassword
     * @covers  Molajo\Resource\Proxy\Uri::setUriPath
     * @covers  Molajo\Resource\Proxy\Uri::setUriQuery
     * @covers  Molajo\Resource\Proxy\Uri::setUriFragment
     * @covers  Molajo\Resource\Proxy\Scheme::__construct
     * @covers  Molajo\Resource\Proxy\Scheme::setScheme
     * @covers  Molajo\Resource\Proxy\Scheme::getScheme
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
     * @covers  Molajo\Resource\Proxy\Uri::getUriResource
     * @covers  Molajo\Resource\Proxy\Uri::getUriPath
     * @covers  Molajo\Resource\Proxy\Uri::locateNamespace
     * @covers  Molajo\Resource\Proxy\Uri::handlePath
     * @covers  Molajo\Resource\Proxy\Uri::parseUri
     * @covers  Molajo\Resource\Proxy\Uri::setUriScheme
     * @covers  Molajo\Resource\Proxy\Uri::setUriHost
     * @covers  Molajo\Resource\Proxy\Uri::setUriUser
     * @covers  Molajo\Resource\Proxy\Uri::setUriPassword
     * @covers  Molajo\Resource\Proxy\Uri::setUriPath
     * @covers  Molajo\Resource\Proxy\Uri::setUriQuery
     * @covers  Molajo\Resource\Proxy\Uri::setUriFragment
     * @covers  Molajo\Resource\Proxy\Scheme::__construct
     * @covers  Molajo\Resource\Proxy\Scheme::setScheme
     * @covers  Molajo\Resource\Proxy\Scheme::getScheme
     *
     * @return  $this
     * @since   1.0.0
     */
    public function testSetSchemeAdapter()
    {
        // $adapter = new MockResourceAdapter();
        //$this->proxy_instance->setScheme('MockResourceAdapter', $adapter, array());
       // $this->proxy_instance->getScheme('MockResourceAdapter');
       // $this->assertTrue(is_object($this->proxy_instance->getData('requested_adapter')));

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
     * Verify if the resource namespace has been defined or not
     *
     * @param   string $resource_namespace
     *
     * @return  boolean
     * @since   1.0.0
     */
    public function exists($resource_namespace)
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
