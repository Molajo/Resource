<?php
/**
 * User Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\User\Service\User;

use Molajo\IoC\Handler\AbstractInjector;
use Molajo\IoC\Api\ServiceHandlerInterface;
use Molajo\IoC\Exception\ServiceHandlerException;

/**
 * User Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class UserInjector extends AbstractInjector implements ServiceHandlerInterface
{
    /**
     * Actions Array
     *
     * @var    array
     * @since  1.0
     */
    protected $actions
        = array(
            'register',
            'confirmRegistration',
            'login',
            'requestPasswordReset',
            'changePassword',
            'logout',
            'isGuest',
            'isLoggedOn'
        );

    /**
     * Request Variables
     *
     * @var    array
     * @since  1.0
     */
    protected $request_variables
        = array(
            'action',
            'username',
            'password',
            'remember',
            'return',
            'new_password',
            'reset_password_code',
            'reset_password_code',
            'registration_confirmation_code'
        );

    /**
     * Actions Array
     *
     * @var    array
     * @since  1.0
     */
    protected $registration_actions
        = array(
            'register',
            'confirmRegistration'
        );

    /**
     * Session Variables
     *
     * @var    array
     * @since  1.0
     */
    protected $session_variables
        = array('session_id');

    /**
     * Constructor
     *
     * @param  $options
     *
     * @since  1.0
     */
    public function __construct(array $options = array())
    {
        $options['service_name']             = basename(__DIR__);
        $options['store_instance_indicator'] = false;
        $options['service_namespace']        = 'Molajo\\User\\User';

        parent::__construct($options);
    }

    /**
     * Define Service Dependencies
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    public function setDependencies(array $reflection = null)
    {
        $this->dependencies                 = array();
        $options                            = array();
        $this->dependencies['Request']      = $options;
        $this->dependencies['Cookie']       = $options;
        $this->dependencies['Session']      = $options;
        $this->dependencies['Flashmessage'] = $options;
        $this->dependencies['Parameters']   = $options;
        $this->dependencies['Resources']    = $options;

        return $this->dependencies;
    }

    /**
     * Set Dependencies
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    public function processFulfilledDependencies(array $dependency_instances = null)
    {
        foreach ($dependency_instances as $service => $instance) {
            $this->dependencies[$service] = $instance;
        }

        $query_string = $this->dependencies['Request']->query_string;

        foreach ($this->request_variables as $variable) {
            if (isset($query_string[$variable])) {
                $this->options[$variable] = $query_string[$variable];
            } else {
                $this->options[$variable] = '';
            }
        }

        // TEST DATA BEGIN
        $this->dependencies['Flashmessage']->deleteFlashMessage();
        $this->dependencies['Session']->setSession(session_id(), 'admin');
        $this->dependencies['Session']->setSession('session_id', session_id());
        $this->dependencies['Session']->setSession('admin', '12345');

        // TEST DATA END
        $this->getSessionInput();

        // TEST DATA BEGIN

        $this->options['username']     = 'admin';
        $this->options['password']     = 'Gemma*2013';
        $this->options['new_password'] = '';
        $this->options['remember']     = '';
        $this->options['action']       = 'login';

        return $this;
    }

    /**
     * IoC Controller triggers the DI Handler to create the Class for the Service
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    public function instantiateService()
    {
        $this->service_instance = null;
    }

    /**
     * Get Session Data
     *
     * @return  $this
     * @since   1.0
     * @throws  ServiceHandlerException
     */
    public function getSessionInput()
    {
        $session_id                  = $this->dependencies['Session']->getSession('session_id');
        $this->options['session_id'] = $session_id;
        if ($this->options['action'] == 'login'
            || $this->options['action'] == 'changePassword'
            || $this->options['action'] == 'requestPasswordReset'
            || $this->options['action'] == 'logout'
            || $this->options['action'] == 'register'
            || $this->options['action'] == 'confirmRegistration'
        ) {

        } else {

            $this->options['password']                       = '';
            $this->options['remember']                       = '';
            $this->options['return']                         = '';
            $this->options['reset_password_code']            = '';
            $this->options['registration_confirmation_code'] = '';
            $this->options['username']                       = $this->dependencies['Session']->getSession($session_id);
            if ($this->options['username'] == false) {
                $this->options['action']   = 'isGuest';
                $this->options['username'] = '';
            } else {
                $this->options['action'] = 'isLoggedOn';
            }
        }
    }

    /**
     * Schedule the Next Service
     *
     * @return  $this
     * @since   1.0
     */
    public function scheduleNextService()
    {
        $options = array();
        foreach ($this->options as $key => $value) {
            $options[$key] = $value;
        }
        $options['registration_actions']    = $this->registration_actions;
        $this->schedule_service['Userdata'] = $options;

        return $this->schedule_service;
    }
}
