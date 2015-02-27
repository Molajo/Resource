<?php
/**
 *  Uri
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo\Resource;

use CommonApi\Resource\ResourceInterface;
use Molajo\Resource\Uri;
use stdClass;

/**
 * Scheme Test
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class UriTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Map Instance
     *
     * @var    object
     * @since  1.0.0
     */
    protected $proxy_instance;

    /**
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setUp()
    {
        $class  = 'Molajo\\Resource\\Scheme';
        $scheme = new $class();

        $class  = 'Molajo\\Resource\\MockUri';
        $this->proxy_instance = new $class($scheme);

        $this->setAdapter();

        $this->setNs();

        return $this;
    }

    /**
     *
     * @return  $this
     * @since   1.0.0
     */
    public function setAdapter()
    {
        $adapter = new MockResourceAdapter();
        $this->proxy_instance->setScheme('MockResourceAdapter', $adapter, array());

        return $this;
    }

    /**
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
     *
     * @return  $this
     * @since   1.0.0
     */
    public function testSetGetScheme()
    {
        $this->assertEquals(1, 1);
        return $this;
    }
}

class MockUri extends Proxy
{
    public function getData($key)
    {
        return $this->$key;
    }
}

class MockResourceUriAdapter implements ResourceInterface
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
