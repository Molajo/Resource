<?php
/**
 *  ClassMap
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo\Resource;

use Exception;

/**
 * Render Test
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class ClassMapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Map Instance
     *
     * @var    object
     * @since  1.0.0
     */
    protected $map_instance;

    /**
     * @covers  Molajo\Resource\ClassMap::createMap
     * @covers  Molajo\Resource\ClassMap::saveOutput
     * @covers  Molajo\Resource\ClassMap\Items::processItems
     * @covers  Molajo\Resource\ClassMap\Items::initialiseObject
     * @covers  Molajo\Resource\ClassMap\Items::setInterfaceClass
     * @covers  Molajo\Resource\ClassMap\Items::setConcreteClass
     * @covers  Molajo\Resource\ClassMap\Items::setConcreteInterfaces
     * @covers  Molajo\Resource\ClassMap\Items::setConcreteConstructorParameters
     * @covers  Molajo\Resource\ClassMap\Items::processDependencies
     * @covers  Molajo\Resource\ClassMap\Items::setConcreteDependencyInterfaces
     * @covers  Molajo\Resource\ClassMap\Items::setConcreteInterfaceRelationship
     * @covers  Molajo\Resource\ClassMap\Aggregate::finalizeItems
     * @covers  Molajo\Resource\ClassMap\Aggregate::setInterfaces
     * @covers  Molajo\Resource\ClassMap\Aggregate::setInterfaceValues
     * @covers  Molajo\Resource\ClassMap\Aggregate::setConcretes
     * @covers  Molajo\Resource\ClassMap\Aggregate::setConcreteConstructorValues
     * @covers  Molajo\Resource\ClassMap\Events::setEvents
     * @covers  Molajo\Resource\ClassMap\Events::testMethodForPlugin
     * @covers  Molajo\Resource\ClassMap\Base::__construct
     * @covers  Molajo\Resource\ClassMap\Base::getReflectionObject
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setUp()
    {
        $this->createResourceMap();

        $classmap_input = readJsonFile(__DIR__ . '/Source/Output/ClassMap.json');
        $base_path      = __DIR__;
        $class          = 'Molajo\\Resource\\MockClassMap';

        try {
            $this->map_instance = new $class (
            // Input
                $classmap_input,
                // Output
                __DIR__ . '/Source/Output/Interfaces.json',
                __DIR__ . '/Source/Output/ClassDependencies.json',
                __DIR__ . '/Source/Output/Events.json',
                $base_path
            );
        } catch (Exception $e) {
            throw new Exception('Interface Map ' . $class . ' Exception during Instantiation: ' . $e->getMessage());
        }

        return $this;
    }

    /**
     * Setup
     *
     * @covers  Molajo\Resource\ResourceMap::setNamespace
     * @covers  Molajo\Resource\ResourceMap::createMap
     * @covers  Molajo\Resource\ResourceMap::saveOutput
     * @covers  Molajo\Resource\ResourceMap::getResourceMap
     * @covers  Molajo\Resource\ResourceMap\Prefixes::processNamespacePrefixes
     * @covers  Molajo\Resource\ResourceMap\Prefixes::processNamespaceFolders
     * @covers  Molajo\Resource\ResourceMap\Prefixes::processNamespaceFolder
     * @covers  Molajo\Resource\ResourceMap\Prefixes::processFilePathObjects
     * @covers  Molajo\Resource\ResourceMap\Prefixes::testFileForNamespaceRules
     * @covers  Molajo\Resource\ResourceMap\Prefixes::setBase
     * @covers  Molajo\Resource\ResourceMap\Prefixes::setFileInclusion
     * @covers  Molajo\Resource\ResourceMap\Prefixes::testPHPClassExceptions
     * @covers  Molajo\Resource\ResourceMap\Prefixes::testExcludeFolders
     * @covers  Molajo\Resource\ResourceMap\Prefixes::setPath
     * @covers  Molajo\Resource\ResourceMap\Prefixes::setQNS
     * @covers  Molajo\Resource\ResourceMap\Prefixes::setNamespaceObject
     * @covers  Molajo\Resource\ResourceMap\Prefixes::useFilesWithNamespace
     * @covers  Molajo\Resource\ResourceMap\Prefixes::setClassfileArrayEntry
     * @covers  Molajo\Resource\ResourceMap\Prefixes::mergeFQNSPaths
     * @covers  Molajo\Resource\ResourceMap\Prefixes::mergeExistingFQNSPath
     * @covers  Molajo\Resource\ResourceMap\Prefixes::setClassfileArrayEntry
     * @covers  Molajo\Resource\ResourceMap\Prefixes::setFileDirectoryNamespace
     * @covers  Molajo\Resource\ResourceMap\Folders::setMultipleNamespaceFolder
     * @covers  Molajo\Resource\ResourceMap\Folders::appendNamespaceFolder
     * @covers  Molajo\Resource\ResourceMap\Folders::prependNamespaceFolder
     * @covers  Molajo\Resource\ResourceMap\Base::__construct
     * @covers  Molajo\Resource\ResourceMap\Base::getReflectionObject
     * @covers  Molajo\Resource\ResourceMap\Base::addSlash
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function createResourceMap()
    {
        $base_path             = __DIR__;
        $exclude_folders_array = readJsonFile(__DIR__ . '/Source/ExcludeFolders.json');
        $classmap_filename     = __DIR__ . '/Source/Output/ClassMap.json';
        $resource_map_filename = __DIR__ . '/Source/Output/ResourceMap.json';

        $class = 'Molajo\\Resource\\ResourceMap';

        $resource_map = new $class (
        // Input
            $base_path,
            $exclude_folders_array,
            // Output
            $classmap_filename,
            $resource_map_filename
        );

        $resource_map->setNamespace('Molajo\\A\\', 'Source/A/');
        $resource_map->setNamespace('Molajo\\B\\', 'Source/B/');
        $resource_map->setNamespace('Molajo\\C\\', 'Source/C/');
        $resource_map->setNamespace('Molajo\\Plugins\\', 'Source/Plugins/');

        $resource_map->createMap();

        return $this;
    }

    /**
     * @covers  Molajo\Resource\ClassMap::createMap
     * @covers  Molajo\Resource\ClassMap::saveOutput
     * @covers  Molajo\Resource\ClassMap\Items::processItems
     * @covers  Molajo\Resource\ClassMap\Items::initialiseObject
     * @covers  Molajo\Resource\ClassMap\Items::setInterfaceClass
     * @covers  Molajo\Resource\ClassMap\Items::setConcreteClass
     * @covers  Molajo\Resource\ClassMap\Items::setConcreteInterfaces
     * @covers  Molajo\Resource\ClassMap\Items::setConcreteConstructorParameters
     * @covers  Molajo\Resource\ClassMap\Items::processDependencies
     * @covers  Molajo\Resource\ClassMap\Items::setConcreteDependencyInterfaces
     * @covers  Molajo\Resource\ClassMap\Items::setConcreteInterfaceRelationship
     * @covers  Molajo\Resource\ClassMap\Aggregate::finalizeItems
     * @covers  Molajo\Resource\ClassMap\Aggregate::setInterfaces
     * @covers  Molajo\Resource\ClassMap\Aggregate::setInterfaceValues
     * @covers  Molajo\Resource\ClassMap\Aggregate::setConcretes
     * @covers  Molajo\Resource\ClassMap\Aggregate::setConcreteConstructorValues
     * @covers  Molajo\Resource\ClassMap\Events::setEvents
     * @covers  Molajo\Resource\ClassMap\Events::testMethodForPlugin
     * @covers  Molajo\Resource\ClassMap\Base::__construct
     * @covers  Molajo\Resource\ClassMap\Base::getReflectionObject
     *
     * @return  $this
     * @since   1.0.0
     */
    public function testEvents()
    {
        $this->map_instance->createMap();

        $events = array();

        $events['onAfterRead'] = array(
            'Molajo\Plugins\BasePlugin',
            'Molajo\Plugins\CatPlugin'
        );

        $events['onAfterPurr'] = array(
            'Molajo\Plugins\CatPlugin'
        );

        $events['onBeforeWag'] = array(
            'Molajo\Plugins\DogPlugin'
        );

        $events['onBeforeSqueak'] = array(
            'Molajo\Plugins\MousePlugin'
        );

        $this->assertEquals($events, $this->map_instance->get('events'));

        return $this;
    }

    /**
     * @covers  Molajo\Resource\ClassMap::createMap
     * @covers  Molajo\Resource\ClassMap::saveOutput
     * @covers  Molajo\Resource\ClassMap\Items::processItems
     * @covers  Molajo\Resource\ClassMap\Items::initialiseObject
     * @covers  Molajo\Resource\ClassMap\Items::setInterfaceClass
     * @covers  Molajo\Resource\ClassMap\Items::setConcreteClass
     * @covers  Molajo\Resource\ClassMap\Items::setConcreteInterfaces
     * @covers  Molajo\Resource\ClassMap\Items::setConcreteConstructorParameters
     * @covers  Molajo\Resource\ClassMap\Items::processDependencies
     * @covers  Molajo\Resource\ClassMap\Items::setConcreteDependencyInterfaces
     * @covers  Molajo\Resource\ClassMap\Items::setConcreteInterfaceRelationship
     * @covers  Molajo\Resource\ClassMap\Aggregate::finalizeItems
     * @covers  Molajo\Resource\ClassMap\Aggregate::setInterfaces
     * @covers  Molajo\Resource\ClassMap\Aggregate::setInterfaceValues
     * @covers  Molajo\Resource\ClassMap\Aggregate::setConcretes
     * @covers  Molajo\Resource\ClassMap\Aggregate::setConcreteConstructorValues
     * @covers  Molajo\Resource\ClassMap\Events::setEvents
     * @covers  Molajo\Resource\ClassMap\Events::testMethodForPlugin
     * @covers  Molajo\Resource\ClassMap\Base::__construct
     * @covers  Molajo\Resource\ClassMap\Base::getReflectionObject
     *
     * @return  $this
     * @since   1.0.0
     */
    public function testInterfaces()
    {
        $this->map_instance->createMap();

        $interfaces = array(
            'CatInterface',
            'DogInterface',
            'MouseInterface',
            'ZebraInterface'

        );

        $hold_implemented_by = array();
        $results = array();
        foreach ($this->map_instance->get('interfaces') as $interface) {
            $results[] = $interface->name;
            if ($interface->name === 'DogInterface') {
                $hold_implemented_by = $interface->implemented_by;
            }
        }

        $implemented_by = array(
            'Molajo\A\Dog',
            'Molajo\A\Mouse',
            'Molajo\Plugins\DogPlugin',
            'Molajo\Plugins\MousePlugin'
        );

        $this->assertEquals($implemented_by, $hold_implemented_by);

        return $this;
    }
}

class MockClassMap extends ClassMap
{
    public function get($key)
    {
        return $this->$key;
    }
}
