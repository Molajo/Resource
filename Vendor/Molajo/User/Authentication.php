<?php
/**
 * Authentication Class
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\User;

use stdClass;
use DateTime;
use Exception;
use Molajo\User\Api\AuthenticationInterface;
use Molajo\User\Api\UserDataInterface;
use Molajo\User\Api\SessionInterface;
use Molajo\User\Api\CookieInterface;
use Molajo\User\Api\EncryptInterface;
use Molajo\User\Api\MailerInterface;
use Molajo\User\Api\MessagesInterface;
use Molajo\Http\Api\RedirectInterface;
use Molajo\FieldHandler\Api\FieldHandlerInterface;

/**
 * Authentication Class
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Authentication implements AuthenticationInterface
{
    /**
     * User Data Instance
     *
     * @var    object  Molajo\User\Api\UserDataInterface
     * @since  1.0
     */
    protected $userdata;

    /**
     * Session Instance
     *
     * @var    object  Molajo\User\Api\SessionInterface
     * @since  1.0
     */
    protected $session;

    /**
     * Cookie Instance
     *
     * @var    object  Molajo\User\Api\CookieInterface
     * @since  1.0
     */
    protected $cookie;

    /**
     * Mailer Instance
     *
     * @var    object  Molajo\User\Api\MailerInterface
     * @since  1.0
     */
    protected $mailer;

    /**
     * Messages Instance
     *
     * @var    object  Molajo\User\Api\MessagesInterface
     * @since  1.0
     */
    protected $messages;

    /**
     * Encrypt Instance
     *
     * @var    object  Molajo\User\Api\EncryptInterface
     * @since  1.0
     */
    protected $encrypt;

    /**
     * Redirect Instance
     *
     * @var    object  Molajo\Http\Api\RedirectInterface
     * @since  1.0
     */
    protected $redirect;

    /**
     * Fieldhandler Instance
     *
     * @var    object  Molajo\FieldHandler\Api\FieldHandlerInterface
     * @since  1.0
     */
    protected $fieldhandler;

    /**
     * Configuration Settings
     *
     * @var    object
     * @since  1.0
     */
    protected $configuration;

    /**
     * Default Exception
     *
     * @var    string
     * @since  1.0
     */
    protected $default_exception = 'Molajo\\User\\Exception\\AuthenticationException';

    /**
     * Today
     *
     * @var    datetime
     * @since  1.0
     */
    protected $today;

    /**
     * Guest
     *
     * @var    boolean
     * @since  1.0
     */
    protected $guest;

    /**
     * Remember
     *
     * @var    boolean
     * @since  1.0
     */
    protected $remember;

    /**
     * Updates
     *
     * @var    array
     * @since  1.0
     */
    protected $updates = array();

    /**
     * Error
     *
     * @var    boolean
     * @since  1.0
     */
    protected $error = false;

    /**
     * Session ID
     *
     * @var    string
     * @since  1.0
     */
    protected $session_id;

    /**
     * Construct
     *
     * @param UserDataInterface     $userdata
     * @param SessionInterface      $session
     * @param CookieInterface       $cookie
     * @param MailerInterface       $mailer
     * @param MessagesInterface     $messages
     * @param EncryptInterface      $encrypt
     * @param RedirectInterface     $redirect
     * @param FieldHandlerInterface $fieldhandler
     * @param stdClass              $configuration
     * @param null|string           $default_exception
     *
     * @since  1.0
     */
    public function __construct(
        UserDataInterface $userdata,
        SessionInterface $session,
        CookieInterface $cookie,
        MailerInterface $mailer,
        MessagesInterface $messages,
        EncryptInterface $encrypt,
        RedirectInterface $redirect,
        FieldHandlerInterface $fieldhandler,
        $configuration,
        $default_exception = null
    ) {
        $this->userdata      = $userdata;
        $this->today         = $this->userdata->getDate();
        $this->session       = $session;
        $this->cookie        = $cookie;
        $this->mailer        = $mailer;
        $this->messages      = $messages;
        $this->encrypt       = $encrypt;
        $this->redirect      = $redirect;
        $this->fieldhandler  = $fieldhandler;
        $this->configuration = $configuration;

        if ($default_exception === null) {
        } else {
            $this->default_exception = $default_exception;
        }
    }

    /**
     * Guest - verify the Session
     *
     * @param   string $session_id
     *
     * @return  int   $id
     * @since   1.0
     */
    public function isGuest($session_id)
    {
        $this->verifySession($session_id, 'isGuest');
        if ($this->session->getSession('last_activity_datetime') === false) {
            $this->setFormToken();
        }
        $this->verifyFormToken('isGuest');
        $this->session->setSession('last_activity_datetime', $this->today);

        return 0;
    }

    /**
     * Login - verify username and password, handle remember request
     *
     * @param   string $session_id
     * @param   string $username
     * @param   string $password
     * @param   bool   $remember
     *
     * @return  int          $id
     * @since   1.0
     */
    public function login($session_id, $username, $password, $remember = false)
    {
        $this->verifySession($session_id, 'login');
        $this->verifyUser('login');
        $this->verifyFormToken('login');
        $this->verifyCredentials($username, $password);
        $this->error = false;
        if ($this->error === true) {
            $this->redirect->redirect('401', $this->configuration->url_to_login);
        }

        $this->setSessionLogin();
        $this->setFormToken();

        if (isset($options['remember'])) {
            $this->remember = $options['remember'];
        }

        if ((int)$this->remember == 1) {

        }

        $this->clearLoginAttempts();
        $this->clearResetPasswordCode();
        $this->updates['last_visit_datetime']    = $this->today;
        $this->updates['last_activity_datetime'] = $this->today;

        $this->updateUser();

        return $this->userdata->getUserData('id');
    }

    /**
     * Verify if the User is Logged On
     *
     * @param   string $session_id
     * @param   string $username
     *
     * @return  int
     * @since   1.0
     */
    public function isLoggedOn($session_id, $username)
    {
        $this->verifySession($session_id, 'isLoggedOn');
        $this->verifyUser('isLoggedOn');
        $this->verifySessionNotTimedOut();
        $this->verifyFormToken('isLoggedOn');

        if ($this->error === true) {
            $this->redirect->redirect('401', $this->configuration->url_to_login);
        }

        $this->updates['last_activity_datetime'] = $this->today;
        $this->updateUser();

        return $this->userdata->getUserData('id');
    }

    /**
     * Change the password for a user
     *
     * @param   string $session_id
     * @param   string $username
     * @param   string $password
     * @param   string $new_password
     * @param   string $reset_password_code
     * @param   bool   $remember $remember
     *
     * @return  $this
     * @since   1.0
     */
    public function changePassword(
        $session_id = '',
        $username,
        $password = '',
        $new_password = '',
        $reset_password_code = '',
        $remember = false
    ) {
        $this->verifySession($session_id, 'login');
        $this->verifyUser('login');
        $this->verifyFormToken('login');
        $this->verifyPasswordChange($new_password);
        $this->verifyCredentials($username, $password, $reset_password_code);

        if ($this->error === true) {
            $this->redirect->redirect('401', $this->configuration->url_to_login);
        }

        //$hash                                       = $this->encrypt->createHashString($new_password);
        $this->updates['password']                  = $new_password; //$hash;
        $this->updates['password_changed_datetime'] = $this->today;
        $this->updates['last_visit_datetime']       = $this->today;
        $this->updates['last_activity_datetime']    = $this->today;

        $this->clearLoginAttempts();
        $this->clearResetPasswordCode();

        $this->updateUser();

        if ((int)$this->remember == true) {
            //$this->saveRememberMeCookie();
        }

        return $this->userdata->getUserData('id');
    }

    /**
     * Generate a token and email a temporary link to change password and sends to user
     *
     * @param   string $session_id
     * @param   string $username
     *
     * @return  $this|void
     * @since   1.0
     */
    public function requestPasswordReset($session_id, $username)
    {
        $this->verifySession($session_id, 'requestPasswordReset');
        $this->verifyUser('requestPasswordReset');
        $this->verifyFormToken('requestPasswordReset');

        if ($this->error === true) {
            $this->redirect->redirect('401', $this->configuration->url_to_login);
        }

        if ($this->userdata->getUserData('reset_password_code') == '') {
            $this->setResetPasswordCode();
        }
        $this->updates['last_activity_datetime'] = $this->today;
        $this->updateUser();

        $options          = array();
        $options['type']  = 'password_reset_request';
        $options['link']  = $this->configuration->url_to_change_password
            . '?reset_password_code=' . $this->userdata->getUserData('reset_password_code');
        $options['name']  = $this->userdata->getUserData('full_name');
        $options['today'] = $this->today;
        $options['to']    = $this->userdata->getUserData('email')
            . ', ' . $this->userdata->getUserData('full_name');

        $this->mailer->render($options)->send();

        return 0;
    }

    /**
     * Log out and Redirect
     *
     * @param   string $session_id
     * @param   string $username
     *
     * @return  null|void
     * @since   1.0
     */
    public function logout($session_id, $username)
    {
        $this->verifySession($session_id, 'logout');
        $this->verifyUser('logout');
        $this->verifySessionNotTimedOut();
        $this->verifyFormToken('logout');

        if ($this->error === true) {
            $this->redirect->redirect('401', $this->configuration->url_to_login);
        }

        $this->updates['last_activity_datetime'] = $this->today;
        $this->updateUser();
        $this->session->destroySession();
        //$this->cookie->forget();

        $this->redirect->redirect('401', $this->configuration->url_for_home);

        return;
    }

    /**
     * Verifies the session given the action
     *
     * @param   string $session_id
     * @param   string $action
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\User\Exception\AuthenticationException
     */
    protected function verifySession($session_id, $action)
    {
        if ($session_id == '') {
            $this->messages->throwException(300);
        }

        if ($action == 'isGuest') {

            if (session_id() == $session_id
                && $this->userdata->getUserData('username') == ''
            ) {
                $this->session_id = $session_id;
                return $this;

            } else {
                $this->messages->throwException(1800, array(), $this->default_exception);
            }

        } elseif ($action == 'login' || $action == 'changePassword' || $action == 'requestPasswordReset') {

            if (session_id() == $session_id) {
                $this->session_id = $session_id;
                return $this;

            } else {
                $values           = array();
                $values['action'] = $action;
                $this->messages->throwException(1805, $values, $this->default_exception);
            }

        } else {

            if (session_id()
                == $session_id
                && $this->session->getSession($session_id)
                == $this->userdata->getUserData('username')
                && $this->session->getSession($this->userdata->getUserData('username'))
                == $this->userdata->getUserData('session_key')
            ) {

                $this->session_id = $session_id;
                return $this;
            };
        }

        $this->messages->throwException(1900, array(), $this->default_exception);
        return $this;
    }

    /**
     * Is the user permitted to logon?
     *
     * @param   string $action
     *
     * @return  int
     * @since   1.0
     * @throws  \Molajo\User\Exception\AuthenticationException
     */
    protected function verifyUser($action)
    {
        $today_datetime = new DateTime();

        if ($this->userdata->getUserData('activation_datetime') == '0000-00-00 00:00:00') {
            $this->error = true;
            $this->messages->setFlashMessage(600);
        }

        if ($action == 'login' || $action == 'changePassword' || 'requestPasswordReset') {
            if ($this->userdata->getUserData('login_attempts') > $this->configuration->max_login_attempts) {

                $last_activity_date = new DateTime($this->userdata->getUserData('last_activity_datetime'));
                $day_object         = $last_activity_date->diff($today_datetime);

                if ($day_object->days > $this->configuration->password_lock_out_days) {

                    $this->updates['login_attempts']         = 0;
                    $this->updates['block']                  = 0;
                    $this->updates['last_activity_datetime'] = $this->today;

                    $this->updateUser();

                } else {
                    $this->error = true;
                    $this->messages->setFlashMessage(800);
                }
            }
        }

        if ($this->userdata->getUserData('block') == 1) {
            $this->error = true;
            $this->messages->setFlashMessage(1100);
        }

        $password_changed_datetime = new DateTime($this->userdata->getUserData('password_changed_datetime'));
        $day_object                = $password_changed_datetime->diff($today_datetime);

        if ($day_object->days > (int)$this->configuration->password_expiration_days
            && (int)$this->configuration->password_expiration_days > 0
        ) {

            $this->updates['block']                  = 1;
            $this->updates['last_activity_datetime'] = $this->today;
            $this->updateUser();

            $this->error = true;
            $this->messages->setFlashMessage(1200);
        }

//todo: move this to route
        if ($this->configuration->site_is_offline == 1) {
            $this->error = true;
            $this->messages->setFlashMessage(450);
        }

        return true;
    }

    /**
     * Verifies the token for the user (guest or logged on)
     *
     * @param   string $action
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\User\Exception\AuthenticationException
     */
    protected function verifyFormToken($action)
    {

        if ($action == 'isLoggedon' || $action == 'isGuest') {
        } else {
            /**
             * if (in_array($_SERVER['REQUEST_METHOD'], array('POST', 'PUT', 'DELETE'))) {
             * } else {
             * $values           = array();
             * $values['action'] = $action;
             * $this->messages->throwException(205, $values);
             * }
             */
        }

        if (in_array($_SERVER['REQUEST_METHOD'], array('POST', 'PUT', 'DELETE'))) {

            if (isset($_POST[$this->session->getSession('form_token')])) {
            } else {
                $this->session->destroy();
                //$this->cookie->forget();
                $this->messages->throwException(2000);
            }
        }

        return $this;
    }

    /**
     * Used during login and other times username and password must be verified
     *
     * @param  string $username
     * @param  string $password
     * @param  string $reset_password_code
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\User\Exception\AuthenticationException
     */
    protected function verifyCredentials(
        $username = '',
        $password = '',
        $reset_password_code = ''
    ) {

        if ($this->configuration->password_email_address_as_username == true) {
            if ($username == $this->userdata->getUserData('email')) {
            } else {
                $this->error = true;
                $this->messages->setFlashMessage(1310);
            }

        } else {
            if ($username == $this->userdata->getUserData('username')) {
            } else {
                $this->error = true;
                $this->messages->setFlashMessage(1300);
            }
        }

        if ($password == '' && $reset_password_code == '') {
            $this->error = true;
            $this->messages->setFlashMessage(1400);
        }

        if ($password == '') {
            if ($reset_password_code == $this->userdata->getUserData('reset_password_code')) {
            } else {
                $this->setFailedLoginAttempt();
                $this->error = true;
                $this->messages->setFlashMessage(1500);
            }

        } else {

            //$test = $this->encrypt->verifyHashString(
            //    $password,
            //    $this->userdata->getUserData('password')
            //);
            $test = true;
            if ($test === true) {
            } else {
                $this->setFailedLoginAttempt();

                $this->error = true;
                $this->messages->setFlashMessage(1600);
            }
        }

        return $this;
    }

    /**
     * Check to see if the Session has timed out
     *
     * @return  $this
     * @since   1.0
     */
    protected function verifySessionNotTimedOut()
    {
        if (session_id() == $this->session_id
            && $this->session->getSession('last_activity_datetime')
            + ($this->configuration->session_expires_minutes * 60)
            < time()
        ) {
            $this->error = true;
            session_regenerate_id(true);
//todo $this->session->setSession('request', $this->request);
            $this->session->setSession('last_activity_datetime', time());
        } else {
            $this->session->setSession('last_activity_datetime', time());
        }

        return $this;
    }

    /**
     * Password requirements
     *
     * @param   string $new_password
     *
     * @return  $this
     * @since   1.0
     */
    protected function verifyPasswordChange($new_password)
    {
        if ($new_password == '' || $new_password === null) {
            $this->error = true;
            $this->messages->setFlashMessage(2130);
        }

        if ($this->configuration->password_minimum_password_length == 0) {
            $this->configuration->password_minimum_password_length = 5;
        }
        if ($this->configuration->password_maximum_password_length
            < $this->configuration->password_minimum_password_length
        ) {
            $this->configuration->password_maximum_password_length = 10;
        }

        $options = array();

        try {
            $options['from'] = $this->configuration->password_minimum_password_length;
            $options['to']   = $this->configuration->password_maximum_password_length;

            $this->fieldhandler->validate('Password', $new_password, 'Stringlength', $options);

        } catch (Exception $e) {

            $values         = array();
            $values['from'] = $this->configuration->password_minimum_password_length;
            $values['to']   = $this->configuration->password_maximum_password_length;

            $this->error = true;
            $this->messages->setFlashMessage(2100, $values);
        }

        if ($this->configuration->password_must_not_match_username === true) {

            try {
                $options              = array();
                $options['not_equal'] = $this->userdata->getUserData('username');

                $this->fieldhandler->validate('Password', $new_password, 'Notequal', $options);

            } catch (Exception $e) {

                $this->error = true;
                $this->messages->setFlashMessage(2200);
            }
        }

        if ($this->configuration->password_must_not_match_last_password === true) {

            $test = $this->encrypt->verifyHashString(
                $new_password,
                $this->userdata->getUserData('password')
            );

            if ($test == 1) {
                $this->error = true;
                $this->messages->setFlashMessage(2300);
            }
        }

        if ($this->configuration->password_alpha_character_required === true) {

            try {
                $options          = array();
                $options['regex'] = '/[A-Za-z]/';
                $this->fieldhandler->validate('Password', $new_password, 'Regex', $options);

            } catch (Exception $e) {

                $this->error = true;
                $this->messages->setFlashMessage(2400);
            }
        }

        if ($this->configuration->password_numeric_character_required === true) {

            try {
                $options          = array();
                $options['regex'] = '/[0-9]/';
                $this->fieldhandler->validate('Password', $new_password, 'Regex', $options);

            } catch (Exception $e) {

                $this->error = true;
                $this->messages->setFlashMessage(2500);
            }
        }

        if ($this->configuration->password_special_character_required === true) {

            try {
                $options          = array();
                $options['regex'] = '/[!*@#$%]/';
                $this->fieldhandler->validate('Password', $new_password, 'Regex', $options);

            } catch (Exception $e) {

                $this->error = true;
                $this->messages->setFlashMessage(2600);
            }
        }

        if ($this->configuration->password_mixed_case_required === true) {

            try {
                $options          = array();
                $options['regex'] = '`[A-Z]`';
                $this->fieldhandler->validate('Password', $new_password, 'Regex', $options);

            } catch (Exception $e) {

                $this->error = true;
                $this->messages->setFlashMessage(2700);

            }

            try {
                $options          = array();
                $options['regex'] = '`[a-z]`';
                $this->fieldhandler->validate('Password', $new_password, 'Regex', $options);

            } catch (Exception $e) {

                $this->error = true;
                $this->messages->setFlashMessage(2800);
            }

        }

        return $this;
    }

    /**
     * Update the session for log in information
     *
     * @return  $this
     * @since   1.0
     */
    protected function setSessionLogin()
    {
        $this->session->destroySession();
        $this->session_id = $this->session->getSession('session_id');
        $this->session->setSession(
            session_id(),
            $this->userdata->getUserData('username')
        );
        $this->updates['session_key']
            = $this->userdata->getUserData('session_key');
        $this->session->setSession(
            $this->userdata->getUserData('username'),
            $this->userdata->getUserData('session_key')
        );
        $this->session->setSession(
            'login_datetime',
            time()
        );
        $this->session->setSession(
            'last_activity_datetime',
            time()
        );

        return $this;
    }

    /**
     * Set a failed login attempt
     *
     * @return  $this
     * @since   1.0
     */
    protected function setFailedLoginAttempt()
    {
        $this->updates['login_attempts'] = $this->userdata->getUserData('login_attempts') + 1;
        if ((int)$this->userdata->getUserData('login_attempts')
            > (int)$this->configuration->max_login_attempts
        ) {
            $this->updates['block'] = 1;
        }
        $this->updates['last_activity_datetime'] = $this->today;

        $this->updateUser();
        return $this;
    }

    /**
     * Set the Form Token
     *
     * @return  $this
     * @since   1.0
     */
    protected function setFormToken()
    {
        if ($this->session->getSession('form_token') === false) {
            $this->session->setSession(
                'form_token',
                $this->encrypt->getRandomToken(64)
            );
        }

        return $this;
    }

    /**
     * Set the Reset Password Code
     *
     * @return  $this
     * @since   1.0
     */
    protected function setResetPasswordCode()
    {
        $this->updates['reset_password_code'] = $this->encrypt->getRandomToken(16);

        return $this;
    }

    /**
     * Clear the number of login attempts
     *
     * @return  $this
     * @since   1.0
     */
    protected function clearLoginAttempts()
    {
        if ($this->userdata->getUserData('login_attempts') === 0) {
            return $this;
        }

        $this->updates['login_attempts'] = 0;

        return $this;
    }

    /**
     * Clear the Reset Password Code
     *
     * @return  $this
     * @since   1.0
     */
    protected function clearResetPasswordCode()
    {
        if ($this->userdata->getUserData('reset_password_code') == '') {
            return $this;
        }

        $this->updates['reset_password_code'] = null;

        return $this;
    }

    /**
     * Update User
     *
     * @return  $this
     * @since   1.0
     */
    protected function updateUser()
    {
        $this->userdata->updateUser($this->updates);
    }


    /**
     * Save Remember Me Cookie
     *
     * @return  object
     * @since   1.0
     */
    protected function saveRememberMeCookie()
    {
        return;


        /**
         * if ($remember
         * {
         * $this->cookie->forever($toPersist);
         * }/
         * 'remember_me_time',
         * 'remember_me_cookie_name',
         * 'remember_me_cookie_domain',
         * 'remember_me_secure',
         * 'remember_me_path',
         * 'encryption_method',
         */
    }
}
