<?php
/**
 * Test User Session
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Tests;


use Molajo\User\Persist\Session;

/**
 * Test User Session
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class SessionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var $SessionClass
     */
    protected $SessionClass;

    /**
     * Set up
     */
    public function setUp()
    {
        $class              = 'Molajo\\User\\Persist\\Session';
        $this->Sessionclass = new $class;

        return;
    }

    /**
     * @covers Molajo\User\Persist\Session::start
     */
    public function testStart()
    {
        $started = $this->SessionClass->start();

        $this->assertEquals(session_id(), $started);
    }

    /**
     * @covers Molajo\User\Persist\Session::testGetSessionId
     */
    public function testGetSessionId()
    {
        $id = $this->SessionClass->getSessionId();

        $this->assertEquals(session_id(), $id);
    }

    /**
     * @covers Molajo\User\Persist\Session::get
     */
    public function testNoToken()
    {
        $key            = 'MolajoSessionDoesNotExist';
        $request_method = 'token';
        $request_key    = '';

        $session = $this->SessionClass->start();

        $token = $this->SessionClass->token($key, $request_method, $request_key);

        $this->assertEquals(40, strlen($token));
    }

    /**
     * @covers Molajo\User\Persist\Session::get
     * @expectedException  Molajo\User\Exception\SessionException
     */
    public function testSaveToken()
    {
        $key            = 'MolajoSessionDoesNotExist';
        $request_method = 'POST';
        $request_key    = 'willnotmatch';

        $this->SessionClass->token($key, $request_method, $request_key);
    }

    /**
     * @covers Molajo\User\Persist\Session::exists
     */
    public function testExists()
    {
        $key   = 'MolajoSession';
        $value = 'dogfood';

        $session = $this->SessionClass->start();
        $session = $this->SessionClass->set($key, $value);

        $this->assertTrue($this->SessionClass->exists($key));
    }

    /**
     * @covers Molajo\User\Persist\Session::exists
     */
    public function testExistsFalse()
    {
        $key = 'MolajoSession';

        $this->assertFalse($this->SessionClass->exists($key));
    }

    /**
     * @covers Molajo\User\Persist\Session::set
     */
    public function testSet()
    {
        $key   = 'MolajoSession';
        $value = 'Toothpick';

        $this->SessionClass->set($key, $value);

        $value2 = htmlspecialchars_decode($_SESSION[$key]);
        $new    = @unserialize($value2);
        $this->assertEquals($value, $new);
    }

    /**
     * @covers Molajo\User\Persist\Session::get
     */
    public function testGet()
    {
        $key   = 'MolajoSession';
        $value = 'Toothpick';

        $this->SessionClass->set($key, $value);

        $get = $this->SessionClass->get($key);

        $value2 = htmlspecialchars_decode($_SESSION[$key]);
        $new    = @unserialize($value2);
        $this->assertEquals($new, $get);
    }

    /**
     * @covers Molajo\User\Persist\Session::get
     * @expectedException  Molajo\User\Exception\SessionException
     */
    public function testGetFail()
    {
        $key = 'MolajoSessionDoesNotExist';

        $this->SessionClass->get($key);
    }

    /**
     * @covers Molajo\User\Persist\Session::delete
     * @expectedException  Molajo\User\Exception\SessionException
     */
    public function testDelete()
    {
        $this->SessionClass->set('MolajoSession1', 'Toothpick 1');
        $this->SessionClass->set('MolajoSession2', 'Toothpick 2');
        $this->SessionClass->set('MolajoSession3', 'Toothpick 3');

        $this->SessionClass->delete('MolajoSession3');
        $this->SessionClass->get('MolajoSession3');
    }

    /**
     * @covers Molajo\User\Persist\Session::destroy
     */
    public function testDestroy()
    {
        $this->SessionClass->destroy();

        $this->assertEquals('', session_id());
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

}
