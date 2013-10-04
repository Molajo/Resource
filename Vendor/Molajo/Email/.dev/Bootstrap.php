<?php
/**
 * Email
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

//include BASE_FOLDER . '/Tests/Testcase1/Data.php';

$classMap = array(
    'Molajo\\Email\\Api\\EmailAwareInterface'            => BASE_FOLDER . '/Api/EmailAwareInterface.php',
    'Molajo\\Email\\Api\\EmailInterface'                 => BASE_FOLDER . '/Api/EmailInterface.php',
    'Molajo\\Email\\Api\\ExceptionInterface'             => BASE_FOLDER . '/Api/ExceptionInterface.php',
    'Molajo\\Email\\Exception\\AbstractHandlerException' => BASE_FOLDER . '/Exception/AbstractHandlerException.php',
    'Molajo\\Email\\Exception\\AdapterException'         => BASE_FOLDER . '/Exception/AdapterException.php',
    'Molajo\\Email\\Exception\\ConnectionException'      => BASE_FOLDER . '/Exception/ConnectionException.php',
    'Molajo\\Email\\Exception\\DummyHandlerException'    => BASE_FOLDER . '/Exception/DummyHandlerException.php',
    'Molajo\\Email\\Exception\\EmailException'           => BASE_FOLDER . '/Exception/EmailException.php',
    'Molajo\\Email\\Handler\\AbstractHandler'            => BASE_FOLDER . '/Handler/AbstractHandler.php',
    'Molajo\\Email\\Handler\\PhpMailer'                  => BASE_FOLDER . '/Handler/PhpMailer.php',
    'Molajo\\Email\\Adapter'                             => BASE_FOLDER . '/Adapter.php',
    'PhpMailer\\PhpMailer'
                                                         => '/Users/amystephen/Sites/Standard/Vendor/PhpMailer/PhpMailer.php',
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);
