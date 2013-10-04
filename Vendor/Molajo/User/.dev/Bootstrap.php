<?php
/**
 * User
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */

if (substr($_SERVER['DOCUMENT_ROOT'], - 1) == '/') {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT']);
} else {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT'] . '/');
}

$base = substr(__DIR__, 0, strlen(__DIR__) - 5);
define('BASE_FOLDER', $base);

$classMap = array(
    'Molajo\\User\\Api\\ActivityInterface'                           => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Api/ActivityInterface.php',
    'Molajo\\User\\Api\\AuthenticationInterface'                     => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Api/AuthenticationInterface.php',
    'Molajo\\User\\Api\\AuthorisationInterface'                      => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Api/AuthorisationInterface.php',
    'Molajo\\User\\Api\\CookieInterface'                             => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Api/CookieInterface.php',
    'Molajo\\User\\Api\\UserDataInterface'                           => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Api/UserDataInterface.php',
    'Molajo\\User\\Api\\ExceptionInterface'                          => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Api/ExceptionInterface.php',
    'Molajo\\User\\Api\\FieldHandlerInterface'                       => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Api/FieldHandlerInterface.php',
    'Molajo\\User\\Api\\FlashMessageInterface'                       => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Api/FlashMessageInterface.php',
    'Molajo\\User\\Api\\MailerInterface'                             => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Api/MailerInterface.php',
    'Molajo\\User\\Api\\MessagesInterface'                           => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Api/MessagesInterface.php',
    'Molajo\\User\\Api\\RegistrationInterface'                       => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Api/RegistrationInterface.php',
    'Molajo\\User\\Api\\SessionInterface'                            => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Api/SessionInterface.php',
    'Molajo\\User\\Api\\TemplateInterface'                           => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Api/TemplateInterface.php',
    'PasswordLib\\PasswordLib'                                       => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Encrypt/PasswordLib.phar',
    'Molajo\\User\\Exception\\ActivityException'                     => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Exception/ActivityException.php',
    'Molajo\\User\\Exception\\AuthenticationException'               => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Exception/AuthenticationException.php',
    'Molajo\\User\\Exception\\AuthenticationUserException'           => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Exception/AuthenticationUserException.php',
    'Molajo\\User\\Exception\\AuthorisationException'                => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Exception/AuthorisationException.php',
    'Molajo\\User\\Exception\\CookieException'                       => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Exception/CookieException.php',
    'Molajo\\User\\Exception\\DataException'                         => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Exception/DataException.php',
    'Molajo\\User\\Exception\\EncryptException'                      => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Exception/EncryptException.php',
    'Molajo\\User\\Exception\\FieldhandlerException'                 => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Exception/FieldhandlerException.php',
    'Molajo\\User\\Exception\\FlashMessageException'                 => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Exception/FlashMessageException.php',
    'Molajo\\User\\Exception\\MailerException'                       => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Exception/MailerException.php',
    'Molajo\\User\\Exception\\MessagesException'                     => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Exception/MessagesException.php',
    'Molajo\\User\\Exception\\RedirectException'                     => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Exception/RedirectException.php',
    'Molajo\\User\\Exception\\RegistrationException'                 => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Exception/RegistrationException.php',
    'Molajo\\User\\Exception\\SessionException'                      => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Exception/SessionException.php',
    'Molajo\\User\\Exception\\SystemException'                       => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Exception/SystemException.php',
    'Molajo\\User\\Exception\\TemplateException'                     => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Exception/TemplateException.php',
    'Molajo\\User\\Exception\\UserException'                         => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Exception/UserException.php',
    'Molajo\\User\\Service\\Activity\\ActivityInjection'             => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Service/Activity/ActivityInjection.php',
    'Molajo\\User\\Service\\Authentication\\AuthenticationInjection' => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Service/Authentication/AuthenticationInjection.php',
    'Molajo\\User\\Service\\Authorisation\\AuthorisationInjection'   => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Service/Authorisation/AuthorisationInjection.php',
    'Molajo\\User\\Service\\Cookie\\CookieInjection'                 => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Service/Cookie/CookieInjection.php',
    'Molajo\\User\\Service\\Data\\DataInjection'                     => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Service/Data/DataInjection.php',
    'Molajo\\User\\Service\\Encrypt\\EncryptInjection'               => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Service/Encrypt/EncryptInjection.php',
    'Molajo\\User\\Service\\Fieldhandler\\FieldhandlerInjection'     => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Service/Fieldhandler/FieldhandlerInjection.php',
    'Molajo\\User\\Service\\Flashmessage\\FlashmessageInjection'     => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Service/Flashmessage/FlashmessageInjection.php',
    'Molajo\\User\\Service\\Mailer\\MailerInjection'                 => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Service/Mailer/MailerInjection.php',
    'Molajo\\User\\Service\\Messages\\MessagesInjection'             => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Service/Messages/MessagesInjection.php',
    'Molajo\\User\\Service\\Redirect\\RedirectInjection'             => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Service/Activity/ActivityInjection.php',
    'Molajo\\User\\Service\\Registration\\RegistrationInjection'     => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Service/Registration/RegistrationInjection.php',
    'Molajo\\User\\Service\\Session\\SessionInjection'               => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Service/Session/SessionInjection.php',
    'Molajo\\User\\Service\\Startup\\StartupInjection'               => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Service/Startup/StartupInjection.php',
    'Molajo\\User\\Service\\Template\\TemplateInjection'             => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Service/Template/TemplateInjection.php',
    'Molajo\\User\\Service\\User\\UserInjection'                     => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Service/User/UserInjection.php',
    'Molajo\\User\\Utilities\\Handlers\\Database'                    => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Utilities/Handlers/Database.php',
    'Molajo\\User\\Utilities\\Handlers\\Http'                        => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Utilities/Handlers/Http.php',
    'Molajo\\User\\Utilities\\Cookie'                                => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Utilities/Cookie.php',
    'Molajo\\User\\Utilities\\Encrypt'                               => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Utilities/Encrypt.php',
    'Molajo\\User\\Utilities\\Fieldhandler'                          => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Utilities/Fieldhandler.php',
    'Molajo\\User\\Utilities\\FlashMessage'                          => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Utilities/FlashMessage.php',
    'Molajo\\User\\Utilities\\Mailer'                                => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Utilities/Mailer.php',
    'Molajo\\User\\Utilities\\Messages'                              => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Utilities/Messages.php',
    'Molajo\\User\\Utilities\\Redirect'                              => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Utilities/Redirect.php',
    'Molajo\\User\\Utilities\\Session'                               => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Utilities/Session.php',
    'Molajo\\User\\Utilities\\TextTemplate'                          => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Utilities/TextTemplate.php',
    'Molajo\\User\\Activity'                                         => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Activity.php',
    'Molajo\\User\\Authentication'                                   => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Authentication.php',
    'Molajo\\User\\Authorisation'                                    => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Authorisation.php',
    'Molajo\\User\\Data'                                             => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Data.php',
    'Molajo\\User\\Registration'                                     => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Registration.php',
    'Molajo\\User\\User'                                             => MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User.php'
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);

/*
include MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/' . 'ClassLoader.php';
$loader = new ClassLoader();
$loader->add('Molajo', MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/src/');
$loader->add('Testcase1', MOLAJO_BASE_FOLDER . '/Vendor'_FOLDER . '/User/Tests/');
$loader->register();
*/
