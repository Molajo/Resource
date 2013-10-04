<?php
/**
 * Pagination
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
$base = substr(__DIR__, 0, strlen(__DIR__) - 5);

define('BASE_FOLDER', $base);

$classMap = array(
    'Molajo\\Pagination\\Api\\PaginationInterface'       => BASE_FOLDER . '/Api/PaginationInterface.php',
    'Molajo\\Pagination\\Api\\ExceptionInterface'        => BASE_FOLDER . '/Api/ExceptionInterface.php',
    'Molajo\\Pagination\\Exception\\PaginationException' => BASE_FOLDER . '/Exception/PaginationException.php',
    'Molajo\\Pagination\\Adapter'                        => BASE_FOLDER . '/Adapter.php',
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);
