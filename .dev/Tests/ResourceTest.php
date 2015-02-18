<?php
/**
 * Render Test
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo\Render\Test;

use CommonApi\Render\RenderInterface;
use Molajo\Render\Adapter\Resource;
use Molajo\Render\Adapter\Mustache;
use Molajo\Render\Adapter\Twig;
use Molajo\Render\Driver;

/**
 * Render Test
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class ResourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Resource Renderer
     *
     * @return  $this
     * @since   1.0.0
     */
    public function testResource()
    {
        $instance = new Driver(new Resource(new MockRender));

        $file  = __DIR__ . '/RenderTest.php';
        $data  = array();
        $stuff = $instance->render($file, $data);
        $this->assertEquals('stuff', $stuff);

        return $this;
    }

    /**
     * Test Mustache Renderer
     *
     * @return  $this
     * @since   1.0.0
     */
    public function testMustache()
    {
        $instance = new Driver(new Mustache(new MockRender));

        $file  = __DIR__ . '/RenderTest.php';
        $data  = array();
        $stuff = $instance->render($file, $data);
        $this->assertEquals('stuff', $stuff);

        return $this;
    }

    /**
     * Test Twig Renderer
     *
     * @return  $this
     * @since   1.0.0
     */
    public function testTwig()
    {
        $instance = new Driver(new Twig(new MockRender));

        $file  = __DIR__ . '/RenderTest.php';
        $data  = array();
        $stuff = $instance->render($file, $data);
        $this->assertEquals('stuff', $stuff);

        return $this;
    }
}

class MockRender implements RenderInterface
{
    /**
     * Render output for specified file and data
     *
     * @param   string $include_file
     * @param   array  $data
     *
     * @return  string
     * @since   1.0.0
     */
    public function render($include_file, array $data = array())
    {
        return 'stuff';
    }
}
