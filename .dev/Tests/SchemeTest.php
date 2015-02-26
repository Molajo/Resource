<?php
/**
 *  Scheme
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo\Resource;

use CommonApi\Resource\ResourceInterface;

/**
 * Scheme Test
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class SchemeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Map Instance
     *
     * @var    object
     * @since  1.0.0
     */
    protected $scheme_instance;

    /**
     * @covers  Molajo\Resource\Scheme::getScheme
     * @covers  Molajo\Resource\Scheme::setScheme
     * @covers  Molajo\Resource\Scheme::setSchemeName
     * @covers  Molajo\Resource\Scheme::setSchemeAdapter
     * @covers  Molajo\Resource\Scheme::setFileExtensions
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setUp()
    {
        $class                 = 'Molajo\\Resource\\Scheme';
        $this->scheme_instance = new $class();

        return $this;
    }

    /**
     * @covers  Molajo\Resource\Scheme::getScheme
     * @covers  Molajo\Resource\Scheme::setScheme
     * @covers  Molajo\Resource\Scheme::setSchemeName
     * @covers  Molajo\Resource\Scheme::setSchemeAdapter
     * @covers  Molajo\Resource\Scheme::setFileExtensions
     *
     * @return  $this
     * @since   1.0.0
     */
    public function testSetAdapter()
    {
        $adapter = new MockAdapter();
        $this->scheme_instance->setScheme('MockAdapter', $adapter, array());
        $actual_results = $this->scheme_instance->getScheme('all');
        $this->assertEquals('mockadapter', $actual_results['mockadapter']->name);
        $this->assertEquals(array(), $actual_results['mockadapter']->include_file_extensions);

        return $this;
    }

    /**
     * @covers  Molajo\Resource\Scheme::getScheme
     * @covers  Molajo\Resource\Scheme::setScheme
     * @covers  Molajo\Resource\Scheme::setSchemeName
     * @covers  Molajo\Resource\Scheme::setSchemeAdapter
     * @covers  Molajo\Resource\Scheme::setFileExtensions
     *
     * @return  $this
     * @since   1.0.0
     */
    public function testGetAdapter()
    {
        $adapter = new MockAdapter();
        $this->scheme_instance->setScheme('MockAdapter', $adapter, array());
        $actual_results = $this->scheme_instance->getScheme('MockAdapter');
        $this->assertEquals('mockadapter', $actual_results->name);
        $this->assertEquals(array(), $actual_results->include_file_extensions);

        return $this;
    }
}

class MockAdapter implements ResourceInterface
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
