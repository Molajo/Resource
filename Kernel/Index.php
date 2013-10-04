<?php
/**
 * Boot Strap System
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
if (substr($_SERVER['DOCUMENT_ROOT'], - 1) == '/') {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT']);
} else {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT'] . '/');
}

define('BASE_FOLDER', substr(__DIR__, 0, strlen(__DIR__) - 7));

/** PHP Settings */
ini_set('display_errors', 'On');
ini_set('error_reporting', E_ALL);
ini_set('short_open_tag', 'On');
if (function_exists('date_default_timezone_set') && function_exists('date_default_timezone_get')) {
    date_default_timezone_set(@date_default_timezone_get());
}

/**
 *  Defined before OverrideAutoload.php where it could be changed
 *
 *  1. IoC Container, Controller and Service Folders
 *  2. Resource Definitions
 *  3. FrontController
 */
$IoCContainerNamespace  = 'Molajo\\IoC\\IoCContainer';
$IoCContainerFile       = __DIR__ . '/IoC/IoCContainer.php';
$IoCControllerNamespace = 'Molajo\\IoC\\IoCController';
$IoCControllerFile      = __DIR__ . '/IoC/IoCController.php';

$handler_folders                                              = array();
$handler_folders[BASE_FOLDER . '/Kernel/Service']             = 'Molajo\\Service';
$handler_folders[BASE_FOLDER . '/Kernel/Event/Service']       = 'Molajo\\Event\\Service';
$handler_folders[BASE_FOLDER . '/Vendor/Molajo/User/Service'] = 'Molajo\\User\\Service';

$classDependencies = __DIR__ . '/Kernel/Resources/Files/ClassDependencies.json';

$FCNamespace = 'Molajo\\Controller\\FrontController';
$FCFile      = __DIR__ . '/Controller/FrontController.php';

/**
 *  Autoload and OverrideAutoload (if needed to override previous)
 */
if (file_exists(BASE_FOLDER . '/OverrideAutoload.php')) {
    require_once BASE_FOLDER . '/OverrideAutoload.php';
} else {
    require_once __DIR__ . '/Autoload.php';
}

/**
 *  Instantiate IoC Container, IoC Controller and FrontController
 */
require_once $IoCContainerFile;
$container = new $IoCContainerNamespace();

require_once $IoCControllerFile;
$IoC = new $IoCControllerNamespace ($container, $handler_folders, $classDependencies);

require_once $FCFile;
$frontController = new $FCNamespace ($IoC);
$frontController->driver();
