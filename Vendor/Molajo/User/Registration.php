<?php
/**
 * User Registration
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\User;

use Molajo\User\Api\RegistrationInterface;
use Molajo\User\Exception\RegistrationException;

/**
 * User Registration
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Registration implements RegistrationInterface
{
    /** Exception error code */
    const AUTHENTICATE_STATUS_SUCCESS = 1,
        AUTHENTICATE_STATUS_CANCEL    = 2,
        AUTHENTICATE_STATUS_FAILURE   = 3;

    /**
     * Activation Code
     *
     * @var    string
     * @since  1.0
     */
    protected $activation_code;

    /**
     * Register User and email activation code
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0
     * @throws  RegistrationException
     */
    public function register($options)
    {
        //edit and filter
        //getActivationCode and send activation code
        $this->activation_code = $this->getRandomString();

        $this->save();

        //email user the activation code
        return $this->activation_code;
    }

    /**
     * Determine if this user is registered
     *
     * @return  boolean
     * @since   1.0
     * @throws  RegistrationException
     */
    public function isRegistered()
    {

    }

    /**
     * Activate Registration using Activation Code
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0
     * @throws  RegistrationException
     */
    public function activateRegistration($options)
    {

    }

    /**
     * Determine if this user registration has been activated
     *
     * @return  boolean
     * @since   1.0
     * @throws  RegistrationException
     */
    public function isActivated()
    {

    }

    /**
     * Deactivate Registration
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0
     * @throws  RegistrationException
     */
    public function deactivateRegistration($options = array())
    {

    }

    /**
     * Suspend User
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0
     * @throws  RegistrationException
     */
    public function suspendUser($options)
    {

    }

    /**
     * Determine if this user was suspended
     *
     * @return  $this
     * @since   1.0
     * @throws  RegistrationException
     */
    public function isSuspended()
    {

    }

    /**
     * Remove Suspension on this User
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0
     * @throws  RegistrationException
     */
    public function removeSuspension($options)
    {

    }
}
