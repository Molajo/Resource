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
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::setNamespace
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::exists
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::get
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::getCollection
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::locateResourceNamespace
     * @covers  Molajo\Resource\Adapter\SetNamespace::setNamespaceExists
     * @covers  Molajo\Resource\Adapter\SetNamespace::appendNamespace
     * @covers  Molajo\Resource\Adapter\SetNamespace::prependNamespace
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixes
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefix
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixDirectory
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrepareNamespacePath
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespaceFilename
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixFileExtensions
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMap
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapInstance
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::setResourceMapPaths
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapPaths
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapFileExtensions
     * @covers  Molajo\Resource\Adapter\Base::__construct
     * @covers  Molajo\Resource\Adapter\Base::initialiseCacheVariables
     * @covers  Molajo\Resource\Adapter\Base::setScheme
     * @covers  Molajo\Resource\Adapter\Base::setResourceNamespace
     * @covers  Molajo\Resource\Adapter\Cache::getConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::setConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::deleteConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::useConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::getCache
     * @covers  Molajo\Resource\Adapter\Cache::setCache
     * @covers  Molajo\Resource\Adapter\Cache::deleteCache
     * @covers  Molajo\Resource\Adapter\Cache::clearCache
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
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::setNamespace
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::exists
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::get
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::getCollection
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::locateResourceNamespace
     * @covers  Molajo\Resource\Adapter\SetNamespace::setNamespaceExists
     * @covers  Molajo\Resource\Adapter\SetNamespace::appendNamespace
     * @covers  Molajo\Resource\Adapter\SetNamespace::prependNamespace
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixes
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefix
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixDirectory
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrepareNamespacePath
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespaceFilename
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixFileExtensions
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMap
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapInstance
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::setResourceMapPaths
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapPaths
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapFileExtensions
     * @covers  Molajo\Resource\Adapter\Base::__construct
     * @covers  Molajo\Resource\Adapter\Base::initialiseCacheVariables
     * @covers  Molajo\Resource\Adapter\Base::setScheme
     * @covers  Molajo\Resource\Adapter\Base::setResourceNamespace
     * @covers  Molajo\Resource\Adapter\Cache::getConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::setConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::deleteConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::useConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::getCache
     * @covers  Molajo\Resource\Adapter\Cache::setCache
     * @covers  Molajo\Resource\Adapter\Cache::deleteCache
     * @covers  Molajo\Resource\Adapter\Cache::clearCache
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
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::setNamespace
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::exists
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::get
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::getCollection
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::locateResourceNamespace
     * @covers  Molajo\Resource\Adapter\SetNamespace::setNamespaceExists
     * @covers  Molajo\Resource\Adapter\SetNamespace::appendNamespace
     * @covers  Molajo\Resource\Adapter\SetNamespace::prependNamespace
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixes
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefix
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixDirectory
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrepareNamespacePath
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespaceFilename
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixFileExtensions
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMap
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapInstance
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::setResourceMapPaths
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapPaths
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapFileExtensions
     * @covers  Molajo\Resource\Adapter\Base::__construct
     * @covers  Molajo\Resource\Adapter\Base::initialiseCacheVariables
     * @covers  Molajo\Resource\Adapter\Base::setScheme
     * @covers  Molajo\Resource\Adapter\Base::setResourceNamespace
     * @covers  Molajo\Resource\Adapter\Cache::getConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::setConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::deleteConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::useConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::getCache
     * @covers  Molajo\Resource\Adapter\Cache::setCache
     * @covers  Molajo\Resource\Adapter\Cache::deleteCache
     * @covers  Molajo\Resource\Adapter\Cache::clearCache
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
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::setNamespace
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::exists
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::get
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::getCollection
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::locateResourceNamespace
     * @covers  Molajo\Resource\Adapter\SetNamespace::setNamespaceExists
     * @covers  Molajo\Resource\Adapter\SetNamespace::appendNamespace
     * @covers  Molajo\Resource\Adapter\SetNamespace::prependNamespace
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixes
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefix
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixDirectory
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrepareNamespacePath
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespaceFilename
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixFileExtensions
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMap
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapInstance
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::setResourceMapPaths
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapPaths
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapFileExtensions
     * @covers  Molajo\Resource\Adapter\Base::__construct
     * @covers  Molajo\Resource\Adapter\Base::initialiseCacheVariables
     * @covers  Molajo\Resource\Adapter\Base::setScheme
     * @covers  Molajo\Resource\Adapter\Base::setResourceNamespace
     * @covers  Molajo\Resource\Adapter\Cache::getConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::setConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::deleteConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::useConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::getCache
     * @covers  Molajo\Resource\Adapter\Cache::setCache
     * @covers  Molajo\Resource\Adapter\Cache::deleteCache
     * @covers  Molajo\Resource\Adapter\Cache::clearCache
     *
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
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::setNamespace
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::exists
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::get
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::getCollection
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::locateResourceNamespace
     * @covers  Molajo\Resource\Adapter\SetNamespace::setNamespaceExists
     * @covers  Molajo\Resource\Adapter\SetNamespace::appendNamespace
     * @covers  Molajo\Resource\Adapter\SetNamespace::prependNamespace
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixes
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefix
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixDirectory
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrepareNamespacePath
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespaceFilename
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixFileExtensions
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMap
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapInstance
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::setResourceMapPaths
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapPaths
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapFileExtensions
     * @covers  Molajo\Resource\Adapter\Base::__construct
     * @covers  Molajo\Resource\Adapter\Base::initialiseCacheVariables
     * @covers  Molajo\Resource\Adapter\Base::setScheme
     * @covers  Molajo\Resource\Adapter\Base::setResourceNamespace
     * @covers  Molajo\Resource\Adapter\Cache::getConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::setConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::deleteConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::useConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::getCache
     * @covers  Molajo\Resource\Adapter\Cache::setCache
     * @covers  Molajo\Resource\Adapter\Cache::deleteCache
     * @covers  Molajo\Resource\Adapter\Cache::clearCache
     *
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
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::setNamespace
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::exists
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::get
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::getCollection
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::locateResourceNamespace
     * @covers  Molajo\Resource\Adapter\SetNamespace::setNamespaceExists
     * @covers  Molajo\Resource\Adapter\SetNamespace::appendNamespace
     * @covers  Molajo\Resource\Adapter\SetNamespace::prependNamespace
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixes
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefix
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixDirectory
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrepareNamespacePath
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespaceFilename
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixFileExtensions
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMap
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapInstance
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::setResourceMapPaths
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapPaths
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapFileExtensions
     * @covers  Molajo\Resource\Adapter\Base::__construct
     * @covers  Molajo\Resource\Adapter\Base::initialiseCacheVariables
     * @covers  Molajo\Resource\Adapter\Base::setScheme
     * @covers  Molajo\Resource\Adapter\Base::setResourceNamespace
     * @covers  Molajo\Resource\Adapter\Cache::getConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::setConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::deleteConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::useConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::getCache
     * @covers  Molajo\Resource\Adapter\Cache::setCache
     * @covers  Molajo\Resource\Adapter\Cache::deleteCache
     * @covers  Molajo\Resource\Adapter\Cache::clearCache
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
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::setNamespace
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::exists
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::get
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::getCollection
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::locateResourceNamespace
     * @covers  Molajo\Resource\Adapter\SetNamespace::setNamespaceExists
     * @covers  Molajo\Resource\Adapter\SetNamespace::appendNamespace
     * @covers  Molajo\Resource\Adapter\SetNamespace::prependNamespace
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixes
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefix
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixDirectory
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrepareNamespacePath
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespaceFilename
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixFileExtensions
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMap
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapInstance
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::setResourceMapPaths
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapPaths
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapFileExtensions
     * @covers  Molajo\Resource\Adapter\Base::__construct
     * @covers  Molajo\Resource\Adapter\Base::initialiseCacheVariables
     * @covers  Molajo\Resource\Adapter\Base::setScheme
     * @covers  Molajo\Resource\Adapter\Base::setResourceNamespace
     * @covers  Molajo\Resource\Adapter\Cache::getConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::setConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::deleteConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::useConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::getCache
     * @covers  Molajo\Resource\Adapter\Cache::setCache
     * @covers  Molajo\Resource\Adapter\Cache::deleteCache
     * @covers  Molajo\Resource\Adapter\Cache::clearCache
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
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::setNamespace
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::exists
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::get
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::getCollection
     * @covers  Molajo\Resource\Adapter\NamespaceHandler::locateResourceNamespace
     * @covers  Molajo\Resource\Adapter\SetNamespace::setNamespaceExists
     * @covers  Molajo\Resource\Adapter\SetNamespace::appendNamespace
     * @covers  Molajo\Resource\Adapter\SetNamespace::prependNamespace
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixes
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefix
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixDirectory
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrepareNamespacePath
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespaceFilename
     * @covers  Molajo\Resource\Adapter\HandleNamespacePrefixes::searchNamespacePrefixFileExtensions
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMap
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapInstance
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::setResourceMapPaths
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapPaths
     * @covers  Molajo\Resource\Adapter\HandleResourceMap::searchResourceMapFileExtensions
     * @covers  Molajo\Resource\Adapter\Base::__construct
     * @covers  Molajo\Resource\Adapter\Base::initialiseCacheVariables
     * @covers  Molajo\Resource\Adapter\Base::setScheme
     * @covers  Molajo\Resource\Adapter\Base::setResourceNamespace
     * @covers  Molajo\Resource\Adapter\Cache::getConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::setConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::deleteConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::useConfigurationCache
     * @covers  Molajo\Resource\Adapter\Cache::getCache
     * @covers  Molajo\Resource\Adapter\Cache::setCache
     * @covers  Molajo\Resource\Adapter\Cache::deleteCache
     * @covers  Molajo\Resource\Adapter\Cache::clearCache
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
