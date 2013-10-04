<?php
/**
 * Asset Test
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Asset\Test;


/**
 * Asset Test
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class AssetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Asset Object
     */
    protected $Asset;

    /**
     * @var Asset Object
     */
    protected $Asset_folder;

    /**
     * Initialises Adapter
     */
    protected function setUp()
    {
        $class = 'Molajo\\Asset\\Adapter';

        $Asset_service      = 1;
        $this->Asset_folder = BASE_FOLDER . '/.dev/Data';
        $Asset_time         = 9;
        $Asset_type         = 'File';

        $this->Asset = new $class($Asset_service, $this->Asset_folder, $Asset_time, $Asset_type);

        return;
    }

    /**
     * Create a Asset entry or set a parameter value
     *
     * @covers Molajo\Asset\Handler\FileAsset::set
     */
    public function testSet()
    {
        $value = 'Stuff';
        $key   = serialize($value);

        $this->Asset->set($key, $value, $ttl = null);

        $this->assertTrue(file_exists($this->Asset_folder . '/' . $key));
    }

    /**
     * Create a Asset entry or set a parameter value
     *
     * @covers Molajo\Asset\Handler\FileAsset::get
     */
    public function testGet()
    {
        $value = 'Stuff';
        $key   = serialize($value);

        $this->Asset->set($key, $value, $ttl = null);

        $value = 'Stuff';
        $key   = serialize($value);

        $results = $this->Asset->get($key);

        $this->assertEquals($value, $results);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        foreach (new \DirectoryIterator($this->Asset_folder) as $file) {
            if ($file->isDot()) {
            } else {
                unlink($file->getPathname());
            }
        }
        rmdir($this->Asset_folder);
    }
}
