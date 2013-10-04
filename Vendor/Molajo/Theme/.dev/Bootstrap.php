<?php
/**
 * Asset
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
    'Molajo\\Asset\\Exception\\AssetException'     => BASE_FOLDER . '/Exception/AssetException.php',
    'Molajo\\Asset\\Exception\\ExceptionInterface' => BASE_FOLDER . '/Exception/ExceptionInterface.php',
    'Molajo\\Asset\\Api\\AssetInterface'           => BASE_FOLDER . '/Api/AssetInterface.php',
    'Molajo\\Asset\\Handler\\FileAsset'            => BASE_FOLDER . '/Handler/FileAsset.php',
    'Molajo\\Asset\\Adapter'                       => BASE_FOLDER . '/Adapter.php',
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);

/*
include BASE_FOLDER . '/' . 'ClassLoader.php';
$loader = new ClassLoader();
$loader->add('Molajo', BASE_FOLDER . '/src/');
$loader->add('Testcase1', BASE_FOLDER . '/Tests/');
$loader->register();
*/
