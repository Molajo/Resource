<?php
/**
 *  Set Namespace Test
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo\Resource;

use CommonApi\Resource\ResourceInterface;
use Molajo\Resource\Adapter\NamespaceHandler;
use Molajo\Resource\Proxy;

/**
 * Set Namespace Test
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class NamespaceHandlerNoMapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Proxy Instance
     *
     * @var    object
     * @since  1.0.0
     */
    protected $proxy_instance;

    /**
     * Adapter Instance
     *
     * @var    object
     * @since  1.0.0
     */
    protected $adapter_instance;

    /**
     * @return  $this
     * @since   1.0.0
     */
    protected function setUp()
    {
        $class  = 'Molajo\\Resource\\Scheme';
        $scheme = new $class();

        $class                = 'Molajo\\Resource\\Proxy';
        $this->proxy_instance = new $class($scheme);

        return $this;
    }

    /**
     *
     * @return  $this
     * @since   1.0.0
     */
    public function setAdapter()
    {
        $this->adapter_instance = new Xyz(
            __DIR__,
            array(),
            array(),
            array(),
            array()
        );

        $this->proxy_instance->setScheme('Xyz', $this->adapter_instance, array());

        return $this;
    }

    /**
     *
     * @return  $this
     * @since   1.0.0
     */
    public function setAdapterValidExtensions()
    {
        $this->adapter_instance = new Xyz(
            __DIR__,
            array(),
            array(),
            array('.php'),
            array()
        );

        $this->proxy_instance->setScheme('Xyz', $this->adapter_instance, array());

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
     * @return  $this
     * @since   1.0.0
     */
    public function testSetNSBefore()
    {
        $this->setNs();

        $this->setAdapter();

        $expected_ns = array();

        $expected_ns['Molajo\\A\\']       = array('Source/A/');
        $expected_ns['Molajo\\B\\']       = array('Source/B/');
        $expected_ns['Molajo\\C\\']       = array('Source/C/');
        $expected_ns['Molajo\\Plugins\\'] = array('Source/Plugins/');

        $this->assertEquals($expected_ns, $this->adapter_instance->getData('namespace_prefixes'));

        return $this;
    }

    /**
     * @return  $this
     * @since   1.0.0
     */
    public function testSetNSAfter()
    {
        $this->setAdapter();

        $this->setNs();

        $expected_ns = array();

        $expected_ns['Molajo\\A\\']       = array('Source/A/');
        $expected_ns['Molajo\\B\\']       = array('Source/B/');
        $expected_ns['Molajo\\C\\']       = array('Source/C/');
        $expected_ns['Molajo\\Plugins\\'] = array('Source/Plugins/');

        $this->assertEquals($expected_ns, $this->adapter_instance->getData('namespace_prefixes'));

        return $this;
    }

    /**
     *
     * @return  $this
     * @since   1.0.0
     */
    public function testExistsNoMapNoValidExtensions()
    {
        $this->setAdapter();

        $this->setNs();

        $this->assertEquals(true, $this->proxy_instance->exists('xyz:\\\molajo\\c\\customize.css'));

        return $this;
    }

    /**
     *
     * @return  $this
     * @since   1.0.0
     */
    public function testExistsNoMapValidExtensions()
    {
        $this->setAdapterValidExtensions();

        $this->setNs();

        $this->assertEquals(true, $this->proxy_instance->exists('xyz:\\\molajo\\b\\banana'));

        return $this;
    }

    /**
     *
     * @return  $this
     * @since   1.0.0
     */
    public function testNotExistsNoMapNoValidExtensions()
    {
        $this->setAdapter();

        $this->setNs();

        $this->assertEquals(false, $this->proxy_instance->exists('xyz:\\\molajo\\c\\ccccccustomize.css'));

        return $this;
    }

    /**
     *
     * @return  $this
     * @since   1.0.0
     */
    public function testNotExistsNoMapValidExtensions()
    {
        $this->setAdapterValidExtensions();

        $this->setNs();

        $this->assertEquals(false, $this->proxy_instance->exists('xyz:\\\molajo\\b\\bananarana'));

        return $this;
    }
}

class Xyz extends NamespaceHandler implements ResourceInterface
{
    public function getData($key)
    {
        return $this->$key;
    }
}
