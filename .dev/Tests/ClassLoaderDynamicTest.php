<?php
/**
 * Class Resources Tests
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Tests;

use Molajo\Resource\Adapter;
use PHPUnit_Framework_TestCase;

/**
 * Class Resources Tests
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class ClassHandlerDynamicTest extends PHPUnit_Framework_TestCase
{
    /**
     * Resources Adapter
     *
     * @var    object  Molajo/Application/Resources/Adapter
     * @since  1.0
     */
    protected $resources;

    /**
     * Exclude when these values are found in the path
     *
     * @var    array
     * @since  1.0
     */
    protected $exclude_in_path_array = array(
        '.dev',
        '.travis.yml',
        '.DS_Store',
        '.git',
        '.',
        '..',
        '.gitattributes',
        '.gitignore'
    );

    /**
     * Exclude these pairs during build
     *
     * @var    array
     * @since  1.0
     */
    protected $exclude_path_array = array(
        'User/Service' => 'Molajo\\User\\Service'
    );

    /**
     * Valid extensions
     *
     * @var    array
     * @since  1.0
     */
    protected $valid_extensions_array = array();

    /**
     * Set up
     *
     * @return  void
     * @since   1.0
     */
    protected function setUp()
    {
        $file_extensions          = array();
        $file_extensions['Class'] = '.php';
        $base_path                = BASE_FOLDER;
        $rebuild_map              = false;
        $resource_map_filename    = null;
        $namespace_prefixes       = array();

        $class       = 'Molajo\\Resources\\Utilities\\ResourceMap';
        $resourcemap = new $class (
            $namespace_prefixes,
            $base_path,
            $rebuild_map,
            $resource_map_filename,
            $this->exclude_in_path_array,
            $this->exclude_path_array,
            $this->valid_extensions_array
        );

        $class   = 'Molajo\\Resources\\Handler\\ClassHandler';
        $handler = new $class (
            $file_extensions,
            $namespace_prefixes,
            $base_path,
            $rebuild_map,
            $resource_map_filename,
            $this->exclude_in_path_array,
            $this->exclude_path_array,
            $this->valid_extensions_array,
            $resourcemap
        );

        $class           = 'Molajo\\Resources\\Adapter';
        $this->resources = new $class (
            $handler,
            'Class'
        );

        $this->resources->addNamespace('Molajo\\Test', '.dev/Classes');
        $this->resources->addNamespace('Molajo\\Resources', '.dev/Psr0/Molajo/Resources');
        $this->resources->addNamespace('Molajo', '.dev/Extension');
    }

    /**
     * Retrieve path from namespace resource map
     *
     * @covers  Molajo\Resource\Handler\Default::validate
     * @return  void
     * @since   1.0
     */
    public function testValidateNamespace1()
    {
        $class = 'Molajo\Test\System\CacheMock';
        $cache = new $class();

        $this->assertEquals(1, $cache->get('foo'));
        $this->assertEquals(2, $cache->get('bar'));

        return;
    }

    /**
     * Class reloads from previous, lower case class name
     *
     * @covers  Molajo\Resource\Handler\Default::validate
     * @return  void
     * @since   1.0
     */
    public function testValidateNamespace2()
    {
        $class = 'molajo\test\system\cachemock';
        $cache = new $class();

        $this->assertEquals(3, $cache->get('baz'));

        return;
    }

    /**
     * Class is not found - with class exists
     *
     * @covers  Molajo\Resource\Handler\Default::validate
     * @return  void
     * @since   1.0
     */
    public function testValidateNamespaceNotFound()
    {
        $class = 'Molajo\Test\System\CacheMock\XYZ';
        $found = false;
        if (class_exists($class)) {
            $found = true;
        }
        $this->assertEquals(false, $found);

        return;
    }

    /**
     * Class is found - with class exists
     *
     * @covers  Molajo\Resource\Handler\Default::validate
     * @return  void
     * @since   1.0
     */
    public function testValidateNamespaceExists()
    {
        $class = 'Molajo\Test\System\StandardMock';
        $found = false;
        if (class_exists($class)) {
            $found = true;
        }
        $this->assertEquals(true, $found);

        return;
    }

    /**
     * Retrieve path from namespace resource map
     *
     * @covers  Molajo\Resource\Handler\Default::validate
     * @return  void
     * @since   1.0
     */
    public function testFindLowercaseClass()
    {
        $class = 'molajo\test\system\configurationmock';
        $cache = new $class();

        $this->assertEquals(3, $cache->get('baz'));

        return;
    }

    /**
     * Matching file - but does not match file extension
     *
     * @covers  Molajo\Resource\Handler\Default::validate
     * @return  void
     * @since   1.0
     */
    public function testDoNotFindXMLFileWithName()
    {
        $class = 'Molajo\Test\phpunit';
        $found = false;
        if (class_exists($class)) {
            $found = true;
        }
        $this->assertEquals(false, $found);

        return;
    }

    /**
     * Tear down
     *
     * @return void
     * @since   1.0
     */
    protected function tearDown()
    {
        parent::tearDown();
    }
}
