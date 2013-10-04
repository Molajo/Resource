<?php
/**
 * Email Test
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Email\Test;

/**
 * Email Test
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Fieldhandler
{
    /**
     * Facade: to mock up filtering and validation services
     *
     * @param   string $name
     * @param   array  $arguments
     */
    public function __call($name, $arguments)
    {
        return $arguments[1];
    }
}

class EmailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Email Object
     */
    protected $adapter;

    /**
     * @var Email Object
     */
    protected $Email_folder;

    /**
     * Initialises Adapter
     */
    protected function setUp()
    {

        $options                     = array();
        $options['mailer_transport'] = 'mail';
        $options['site_name']        = 'Sitename';
        $options['Fieldhandler']     = new Fieldhandler();

        $class   = 'Molajo\\Email\\Handler\\PhpMailer';
        $handler = new $class($options);

        $class         = 'Molajo\\Email\\Adapter';
        $this->adapter = new $class($handler);

        return;
    }

    /**
     * Create a Email entry or set a parameter value
     *
     * @covers Molajo\Email\Handler\FileEmail::set
     */
    public function testSet()
    {
        $this->adapter->set('to', 'AmyStephen@gmail.com,Fname Lname');
        $this->adapter->set('from', 'AmyStephen@gmail.com,Fname Lname');
        $this->adapter->set('reply_to', 'AmyStephen@gmail.com,FName LName');
        $this->adapter->set('cc', 'AmyStephen@gmail.com,FName LName');
        $this->adapter->set('bcc', 'AmyStephen@gmail.com,FName LName');
        $this->adapter->set('subject', 'Welcome to our Site');
        $this->adapter->set('body', 'Stuff goes here');
        $this->adapter->set('mailer_html_or_text', 'html');

        $this->adapter->send();

    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }
}
