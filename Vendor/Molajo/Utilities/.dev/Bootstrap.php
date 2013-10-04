<?php
/**
 * Utilities
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */

$base = substr(__DIR__, 0, strlen(__DIR__) - 5);

define('BASE_FOLDER', $base);

$classMap = array(
    'Molajo\\Utilities\\Api\\DateInterface'       => BASE_FOLDER . '/Api/DateInterface.php',
    'Molajo\\Utilities\\Api\\ExceptionInterface'  => BASE_FOLDER . '/Api/ExceptionInterface.php',
    'Molajo\\Utilities\\Api\\TextInterface'       => BASE_FOLDER . '/Api/TextInterface.php',
    'Molajo\\Utilities\\Api\\UrlInterface'        => BASE_FOLDER . '/Api/UrlInterface.php',
    'Molajo\\Utilities\\Exception\\DateException' => BASE_FOLDER . '/Exception/DateException.php',
    'Molajo\\Utilities\\Date'                     => BASE_FOLDER . '/Date.php',
    'Molajo\\Utilities\\Image'                    => BASE_FOLDER . '/Image.php',
    'Molajo\\Utilities\\Text'                     => BASE_FOLDER . '/Text.php',
    'Molajo\\Utilities\\Url'                      => BASE_FOLDER . '/Url.php'
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);
