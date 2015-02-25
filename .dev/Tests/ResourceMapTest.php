<?php
/**
 *  Test ResourceMap Class
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo\Resource;

use stdClass;

/**
 *  Test ResourceMap Class
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class ResourceMapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Resource Adapter
     *
     * @var    object
     * @since  1.0.0
     */
    protected $resource_adapter;

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
    protected function setUp()
    {
        $base_path             = __DIR__;
        $exclude_folders_array = readJsonFile(__DIR__ . '/Source/ExcludeFolders.json');

        $classmap_filename     = __DIR__ . '/Source/Output/ClassMap.json';
        $resource_map_filename = __DIR__ . '/Source/Output/ResourceMap.json';

        $class = 'Molajo\\Resource\\MockResourceMap';

        $this->resource_adapter = new $class (
        // Input
            $base_path,
            $exclude_folders_array,
            // Output
            $classmap_filename,
            $resource_map_filename
        );

        return $this;
    }

    /**
     * Set Namespace
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
    public function setNamespace()
    {
        $this->resource_adapter->setNamespace('Molajo\\A\\', 'Source/A/');
        $this->resource_adapter->setNamespace('Molajo\\B\\', 'Source/B/');
        $this->resource_adapter->setNamespace('Molajo\\C\\', 'Source/C/');
        $this->resource_adapter->setNamespace('Molajo\\Plugins\\', 'Source/Plugins/');

        return $this;
    }

    /**
     * Set Namespace
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
    public function createMap()
    {
        $this->resource_adapter->createMap();

        return $this;
    }

    /**
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
    public function testSetNamespace()
    {
        $this->resource_adapter->setNamespace('Molajo\\A\\', 'Source/A/');
        $this->resource_adapter->setNamespace('Molajo\\B\\', 'Source/B/');
        $this->resource_adapter->setNamespace('Molajo\\C\\', 'Source/C/');
        $this->resource_adapter->setNamespace('Molajo\\Plugins\\', 'Source/Plugins/');

        $expected = array();
        $expected['Molajo\\A\\'] = array('Source/A/');
        $expected['Molajo\\B\\'] = array('Source/B/');
        $expected['Molajo\\C\\'] = array('Source/C/');
        $expected['Molajo\\Plugins\\'] = array('Source/Plugins/');

        $this->assertEquals($expected, $this->resource_adapter->get('namespace_prefixes'));

        return $this;
    }

    /**
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
    public function testClassFiles()
    {
        $this->setNamespace();
        $this->resource_adapter->createMap();

        $class_files = array();

        /** Row 1 */
        $row = new stdClass();

        $row->file_name = 'Cat.php';
        $row->base_name = 'Cat';
        $row->path      = 'Source/A/Cat.php';
        $row->file_name = 'Cat.php';
        $row->qns       = 'Molajo\\A\\Cat';

        $class_files[$row->path] = $row;

        /** Row 2 */
        $row = new stdClass();

        $row->file_name = 'CatInterface.php';
        $row->base_name = 'CatInterface';
        $row->path      = 'Source/A/CatInterface.php';
        $row->file_name = 'CatInterface.php';
        $row->qns       = 'Molajo\\A\\CatInterface';

        $class_files[$row->path] = $row;

        /** Row 3 */
        $row = new stdClass();

        $row->file_name = 'Dog.php';
        $row->base_name = 'Dog';
        $row->path      = 'Source/A/Dog.php';
        $row->file_name = 'Dog.php';
        $row->qns       = 'Molajo\\A\\Dog';

        $class_files[$row->path] = $row;

        /** Row 4 */
        $row = new stdClass();

        $row->file_name = 'DogInterface.php';
        $row->base_name = 'DogInterface';
        $row->path      = 'Source/A/DogInterface.php';
        $row->file_name = 'DogInterface.php';
        $row->qns       = 'Molajo\\A\\DogInterface';

        $class_files[$row->path] = $row;

        /** Row 5 */
        $row = new stdClass();

        $row->file_name = 'Mouse.php';
        $row->base_name = 'Mouse';
        $row->path      = 'Source/A/Mouse.php';
        $row->file_name = 'Mouse.php';
        $row->qns       = 'Molajo\\A\\Mouse';

        $class_files[$row->path] = $row;

        /** Row 6 */
        $row = new stdClass();

        $row->file_name = 'MouseInterface.php';
        $row->base_name = 'MouseInterface';
        $row->path      = 'Source/A/MouseInterface.php';
        $row->file_name = 'MouseInterface.php';
        $row->qns       = 'Molajo\\A\\MouseInterface';

        $class_files[$row->path] = $row;

        /** Row 7 */
        $row = new stdClass();

        $row->file_name = 'Zebra.php';
        $row->base_name = 'Zebra';
        $row->path      = 'Source/A/Z/Zebra.php';
        $row->file_name = 'Zebra.php';
        $row->qns       = 'Molajo\\A\\Z\\Zebra';

        $class_files[$row->path] = $row;

        /** Row 8 */
        $row = new stdClass();

        $row->file_name = 'ZebraInterface.php';
        $row->base_name = 'ZebraInterface';
        $row->path      = 'Source/A/Z/ZebraInterface.php';
        $row->file_name = 'ZebraInterface.php';
        $row->qns       = 'Molajo\\A\\Z\\ZebraInterface';

        $class_files[$row->path] = $row;

        /** Row 9 */
        $row = new stdClass();

        $row->file_name = 'Banana.php';
        $row->base_name = 'Banana';
        $row->path      = 'Source/B/Banana.php';
        $row->file_name = 'Banana.php';
        $row->qns       = 'Molajo\\B\\Banana';

        $class_files[$row->path] = $row;

        /** Row 10 */
        $row = new stdClass();

        $row->file_name = 'Bat.php';
        $row->base_name = 'Bat';
        $row->path      = 'Source/B/Bat.php';
        $row->file_name = 'Bat.php';
        $row->qns       = 'Molajo\\B\\Bat';

        $class_files[$row->path] = $row;

        /** Row 11 */
        $row = new stdClass();

        $row->file_name = 'Candy.php';
        $row->base_name = 'Candy';
        $row->path      = 'Source/C/Candy.php';
        $row->file_name = 'Candy.php';
        $row->qns       = 'Molajo\\C\\Candy';

        $class_files[$row->path] = $row;

        /** Row 12 */
        $row = new stdClass();

        $row->file_name = 'BasePlugin.php';
        $row->base_name = 'BasePlugin';
        $row->path      = 'Source/Plugins/BasePlugin.php';
        $row->file_name = 'BasePlugin.php';
        $row->qns       = 'Molajo\\Plugins\\BasePlugin';

        $class_files[$row->path] = $row;

        /** Row 13 */
        $row = new stdClass();

        $row->file_name = 'CatPlugin.php';
        $row->base_name = 'CatPlugin';
        $row->path      = 'Source/Plugins/CatPlugin.php';
        $row->file_name = 'CatPlugin.php';
        $row->qns       = 'Molajo\\Plugins\\CatPlugin';

        $class_files[$row->path] = $row;

        /** Row 14 */
        $row = new stdClass();

        $row->file_name = 'DogPlugin.php';
        $row->base_name = 'DogPlugin';
        $row->path      = 'Source/Plugins/DogPlugin.php';
        $row->file_name = 'DogPlugin.php';
        $row->qns       = 'Molajo\\Plugins\\DogPlugin';

        $class_files[$row->path] = $row;

        /** Row 15 */
        $row = new stdClass();

        $row->file_name = 'MousePlugin.php';
        $row->base_name = 'MousePlugin';
        $row->path      = 'Source/Plugins/MousePlugin.php';
        $row->file_name = 'MousePlugin.php';
        $row->qns       = 'Molajo\\Plugins\\MousePlugin';

        $class_files[$row->path] = $row;

        $this->assertEquals($class_files, $this->resource_adapter->get('class_files'));

        return $this;
    }


    /**
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
    public function testResourceFiles()
    {
        $base = __DIR__ . '/';

        $this->setNamespace();

        $this->resource_adapter->createMap();

        $resource_files = array();
        $resource_files['molajo\\a\\'] = array($base . 'Source/A/');
        $resource_files['molajo\\a\\cat'] = array($base . 'Source/A/Cat.php');
        $resource_files['molajo\\a\\catinterface'] = array($base . 'Source/A/CatInterface.php');
        $resource_files['molajo\\a\\dog'] = array($base . 'Source/A/Dog.php');
        $resource_files['molajo\\a\\doginterface'] = array($base . 'Source/A/DogInterface.php');
        $resource_files['molajo\\a\\mouse'] = array($base . 'Source/A/Mouse.php');
        $resource_files['molajo\\a\\mouseinterface'] = array($base . 'Source/A/MouseInterface.php');
        $resource_files['molajo\\a\\z'] = array($base . 'Source/A/Z');
        $resource_files['molajo\\a\\z\\stripes.txt'] = array($base . 'Source/A/Z/Stripes.txt');
        $resource_files['molajo\\a\\z\\zebra'] = array($base . 'Source/A/Z/Zebra.php');
        $resource_files['molajo\\a\\z\\zebrainterface'] = array($base . 'Source/A/Z/ZebraInterface.php');
        $resource_files['molajo\\b\\'] = array($base . 'Source/B/');
        $resource_files['molajo\\b\\100x100.gif'] = array($base . 'Source/B/100x100.gif');
        $resource_files['molajo\\b\\150x150.gif'] = array($base . 'Source/B/150x150.gif');
        $resource_files['molajo\\b\\50x50.gif'] = array($base . 'Source/B/50x50.gif');
        $resource_files['molajo\\b\\banana'] = array($base . 'Source/B/Banana.php');
        $resource_files['molajo\\b\\bat'] = array($base . 'Source/B/Bat.php');
        $resource_files['molajo\\c\\'] = array($base . 'Source/C/');
        $resource_files['molajo\\c\\candy'] = array($base . 'Source/C/Candy.php');
        $resource_files['molajo\\c\\content.xml'] = array($base . 'Source/C/Content.xml');
        $resource_files['molajo\\c\\customize.css'] = array($base . 'Source/C/Customize.css');
        $resource_files['molajo\\c\\js'] = array($base . 'Source/C/Js');
        $resource_files['molajo\\c\\js\\foundation.min.js'] = array($base . 'Source/C/Js/foundation.min.js');
        $resource_files['molajo\\plugins\\'] = array($base . 'Source/Plugins/');
        $resource_files['molajo\\plugins\\baseplugin'] = array($base . 'Source/Plugins/BasePlugin.php');
        $resource_files['molajo\\plugins\\catplugin'] = array($base . 'Source/Plugins/CatPlugin.php');
        $resource_files['molajo\\plugins\\dogplugin'] = array($base . 'Source/Plugins/DogPlugin.php');
        $resource_files['molajo\\plugins\\mouseplugin'] = array($base . 'Source/Plugins/MousePlugin.php');

        $resource_output = $this->resource_adapter->getResourceMap();

        foreach ($resource_output as $key => $row) {
            $this->assertEquals($row, $resource_files[$key]);
        }

        return $this;
    }
}

class MockResourceMap extends ResourceMap
{
    public function get($key)
    {
        return $this->$key;
    }
}
