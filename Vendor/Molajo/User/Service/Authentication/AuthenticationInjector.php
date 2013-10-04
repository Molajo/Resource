<?php
/**
 * Authentication Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\User\Service\Authentication;

use stdClass;
use Exception;
use Molajo\IoC\Handler\AbstractInjector;
use Molajo\IoC\Api\ServiceHandlerInterface;
use Molajo\IoC\Exception\ServiceHandlerException;

/**
 * Authentication Services
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class AuthenticationInjector extends AbstractInjector implements ServiceHandlerInterface
{
    /**
     * Constructor
     *
     * @param  array $options
     *
     * @since  1.0
     */
    public function __construct(array $options = array())
    {
        $options['service_name']             = basename(__DIR__);
        $options['store_instance_indicator'] = true;
        $options['service_namespace']        = 'Molajo\\User\\Authentication';

        parent::__construct($options);
    }

    /**
     * Instantiate a new handler and inject it into the Adapter for the ServiceHandlerInterface
     * Retrieve a list of Interface dependencies and return the data ot the controller.
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    public function setDependencies(array $reflection = null)
    {
        parent::setDependencies($reflection);

        $options                          = array();
        $this->dependencies['Activity']   = $options;
        $this->dependencies['Parameters'] = $options;
        $this->dependencies['Request']    = $options;
        $this->dependencies['Resources']  = $options;

        return $this->dependencies;
    }

    /**
     * Set Dependencies for Instantiation
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    public function processFulfilledDependencies(array $dependency_instances = null)
    {
        parent::processFulfilledDependencies($dependency_instances);

        $this->dependencies['default_exception'] = 'Molajo\\User\\Exception\\AuthenticationException';
        $this->dependencies['configuration']     = $this->getConfiguration();

        return $this->dependencies;
    }

    /**
     * Get Configuration
     *
     * @return  object
     * @since   1.0
     */
    public function getConfiguration()
    {
        $configuration                                          = new stdClass();
        $configuration->authentication_types                    = array('database');
        $configuration->from_email_address                      = true;
        $configuration->from_email_name                         = true;
        $configuration->max_login_attempts                      = 10;
        $configuration->password_alpha_character_required       = false;
        $configuration->password_expiration_days                = 0;
        $configuration->password_lock_out_days                  = 1;
        $configuration->password_minimum_password_length        = 5;
        $configuration->password_maximum_password_length        = 50;
        $configuration->password_mixed_case_required            = false;
        $configuration->password_must_not_match_last_password   = true;
        $configuration->password_must_not_match_username        = false;
        $configuration->password_numeric_character_required     = false;
        $configuration->password_send_email_for_password_blocks = false;
        $configuration->password_special_character_required     = false;
        $configuration->password_email_address_as_username      = false;
        $configuration->session_expires_minutes                 = 30;
        $configuration->site_is_offline                         = false;

        $configuration->url_for_home
            = $this->dependencies['Request']->application_base_url;
        $configuration->url_to_change_password
            = $this->dependencies['Request']->application_base_url . '/' . 'password';
        $configuration->url_to_login
            = $this->dependencies['Request']->application_base_url . '/' . 'login';
        $configuration->url_to_registration
            = $this->dependencies['Request']->application_base_url . '/' . 'registration';

        return $configuration;
    }

    /**
     * Process Authenticate: isGuest, login, isLoggedOn, changePassword,
     *   requestPasswordReset, logout, register, confirmRegistration
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    public function performAfterInstantiationLogic()
    {
        $action = $this->options['action'];

        switch ($action) {

            case 'isGuest':

                try {
                    $id = $this->service_instance->$action($this->options['session_id']);

                } catch (Exception $e) {
                    throw new ServiceHandlerException
                    ('User Authentication Injector isGuest Failed. Exception: ' . $e->getMessage());
                }
                break;

            case 'login':

                try {
                    $id = $this->service_instance->$action(
                        $this->options['session_id'],
                        $this->options['username'],
                        $this->options['password'],
                        $this->options['remember']
                    );

                } catch (Exception $e) {

                    throw new ServiceHandlerException
                    ('User Authentication Injector login Failed. Exception: ' . $e->getMessage());
                }
                break;

            case 'isLoggedOn':

                try {

                    $id = $this->service_instance->$action(
                        $this->options['session_id'],
                        $this->options['username']
                    );

                } catch (Exception $e) {

                    throw new ServiceHandlerException
                    ('User Authentication Injector login Failed. Exception: ' . $e->getMessage());
                }
                break;

            case 'changePassword':

                try {

                    $id = $this->service_instance->$action(
                        $this->options['session_id'],
                        $this->options['username'],
                        $this->options['password'],
                        $this->options['new_password'],
                        $this->options['reset_password_code'],
                        $this->options['remember']
                    );

                } catch (Exception $e) {

                    throw new ServiceHandlerException
                    ('User Authentication Injector changePassword Failed. Exception: ' . $e->getMessage());
                }
                break;

            case 'requestPasswordReset':

                try {

                    $id = $this->service_instance->$action(
                        $this->options['session_id'],
                        $this->options['username']
                    );

                } catch (Exception $e) {

                    throw new ServiceHandlerException
                    ('User Authentication Injector requestPasswordReset Failed. Exception: ' . $e->getMessage());
                }
                break;

            case 'logout':

                try {

                    $id = $this->service_instance->$action(
                        $this->options['session_id'],
                        $this->options['username']
                    );

                } catch (Exception $e) {

                    throw new ServiceHandlerException
                    ('User Authentication Injector logout Failed. Exception: ' . $e->getMessage());
                }
                break;

            /**
             * case 'register':
             * echo "i equals 1";
             * break;
             * case 'confirmRegistration':
             * echo "i equals 2";
             * break;
             */
            default:
                throw new ServiceHandlerException
                ('User Authentication Injector: Invalid action: ' . $action);
        }

        $this->options['id'] = $id;

        return $this;
    }

    /**
     * Schedule the Next Service
     *
     * @return  $this
     * @since   1.0
     */
    public function scheduleNextService()
    {
        $options                                   = array();
        $options['id']                             = $this->options['id'];
        $this->schedule_service['Instantiateuser'] = $options;

        return $this->schedule_service;
    }
}
