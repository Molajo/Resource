<?php
/**
 * Authorisation Test
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Tests;

/**
 * Test User Authorisation
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class AuthorisationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var $PermissionClass
     */
    protected $PermissionClass;

    /**
     * @var $AuthorisationClass
     */
    protected $AuthorisationClass;

    /**
     * Set up
     */
    public function setUp()
    {
        $class                 = 'Molajo\\Permissions\\Permissions';
        $this->Permissionclass = new $class;

        $class                    = 'Molajo\\User\\Authorisation\\Authorisation';
        $this->Authorisationclass = new $class($this->PermissionClass);

        return;
    }

    /**
     * @covers Molajo\User\Authorisation\Authorisation::verifyLogin
     */
    public function testVerifyLogin()
    {
        $id                 = 1;
        $request            = array();
        $request['user_id'] = $id;
        $request['method']  = 'login';

        $results = $this->AuthorisationClass->isAuthorised($request);

        $this->assertEquals(true, $results);
    }

    /**
     * @covers Molajo\User\Authorisation\Authorisation::verifyActionPermissions
     */
    public function testVerifyTask()
    {
        $request               = array();
        $request['method']     = 'task';
        $request['action']     = 'view';
        $request['catalog_id'] = 1;

        $results = $this->AuthorisationClass->isAuthorised($request);

        $this->assertEquals(true, $results);
    }

    /**
     * @covers Molajo\User\Authorisation\Authorisation::verifyActionPermissions
     */
    public function testVerifyActionTest()
    {
        $request                   = array();
        $request['method']         = 'action';
        $request['view_group_id']  = 1;
        $request['request_action'] = 'read';
        $request['catalog_id']     = 1;

        $results = $this->AuthorisationClass->isAuthorised($request);

        $this->assertEquals(true, $results);
    }

    /**
     * @covers Molajo\User\Authorisation\Authorisation::isUserAuthorisedNoFiltering
     */
    public function testSetHTMLFilter()
    {
        $request           = array();
        $request['method'] = 'htmlfilter';
        $request['key']    = 'MolajoAuthorisation';

        $results = $this->AuthorisationClass->isAuthorised($request);

        $this->assertEquals(true, $results);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

}
