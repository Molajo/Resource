<?php
/**
 * Database
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
$base = substr(__DIR__, 0, strlen(__DIR__) - 5);

define('BASEDB_FOLDER', $base);

$classMap = array(
    'Molajo\\Database\\Api\\ConnectionInterface'          => BASEDB_FOLDER . '/Api/ConnectionInterface.php',
    'Molajo\\Database\\Api\\DatabaseAwareInterface'       => BASEDB_FOLDER . '/Api/DatabaseAwareInterface.php',
    'Molajo\\Database\\Api\\DatabaseInterface'            => BASEDB_FOLDER . '/Api/DatabaseInterface.php',
    'Molajo\\Database\\Api\\ExceptionInterface'           => BASEDB_FOLDER . '/Api/ExceptionInterface.php',
    'Molajo\\Database\\DatabaseDriver'                    => BASEDB_FOLDER . '/DatabaseDriver.php',
    'Molajo\\Database\\Exception\\AdapterException'       => BASEDB_FOLDER . '/Exception/AdapterException.php',
    'Molajo\\Database\\Exception\\ConnectionException'    => BASEDB_FOLDER . '/Exception/ConnectionException.php',
    'Molajo\\Database\\Exception\\DatabaseException'      => BASEDB_FOLDER . '/Exception/DatabaseException.php',
    'Molajo\\Database\\Exception\\DummyHandlerException'  => BASEDB_FOLDER . '/Exception/DummyHandlerException.php',
    'Molajo\\Database\\Exception\\JoomlaHandlerException' => BASEDB_FOLDER . '/Exception/JoomlaHandlerException.php',
    'Molajo\\Database\\Handler\\AbstractHandler'          => BASEDB_FOLDER . '/Handler/AbstractHandler.php',
    'Molajo\\Database\\Handler\\Joomla'                   => BASEDB_FOLDER . '/Handler/Joomla.php',
    'Molajo\\Database\\Adapter'                           => BASEDB_FOLDER . '/Adapter.php',
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);

define ('JOOMLA_FOLDER', '/Users/amystephen/Sites/Standard/Vendor/Joomla/Database');

$classMap = array(
    'Joomla\\Database\\Mysqli\\MysqliDriver'       => JOOMLA_FOLDER . '/Mysqli/MysqliDriver.php',
    'Joomla\\Database\\Mysqli\\MysqliExporter'     => JOOMLA_FOLDER . '/Mysqli/MysqliExporter.php',
    'Joomla\\Database\\Mysqli\\MysqliImporter'     => JOOMLA_FOLDER . '/Mysqli/MysqliImporter.php',
    'Joomla\\Database\\Mysqli\\MysqliIterator'     => JOOMLA_FOLDER . '/Mysqli/MysqliIterator.php',
    'Joomla\\Database\\Mysqli\\MysqliQuery'        => JOOMLA_FOLDER . '/Mysqli/MysqliQuery.php',
    'Joomla\\Database\\Query\\LimitableInterface'  => JOOMLA_FOLDER . '/Query/LimitableInterface.php',
    'Joomla\\Database\\Query\\PreparableInterface' => JOOMLA_FOLDER . '/Query/PreparableInterface.php',
    'Joomla\\Database\\Query\\QueryElement'        => JOOMLA_FOLDER . '/Query/QueryElement.php',
    'Joomla\\Database\\DatabaseDriver'             => JOOMLA_FOLDER . '/DatabaseDriver.php',
    'Joomla\\Database\\DatabaseExporter'           => JOOMLA_FOLDER . '/DatabaseExporter.php',
    'Joomla\\Database\\DatabaseFactory'            => JOOMLA_FOLDER . '/DatabaseFactory.php',
    'Joomla\\Database\\DatabaseImporter'           => JOOMLA_FOLDER . '/DatabaseImporter.php',
    'Joomla\\Database\\DatabaseInterface'          => JOOMLA_FOLDER . '/DatabaseInterface.php',
    'Joomla\\Database\\DatabaseIterator'           => JOOMLA_FOLDER . '/DatabaseIterator.php',
    'Joomla\\Database\\DatabaseQuery'              => JOOMLA_FOLDER . '/DatabaseQuery.php',
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);


define ('PSR_FOLDER', '/Users/amystephen/Sites/Standard/Vendor/psr/log/Psr/Log');

$classMap = array(
    'Psr\\Log\\AbstractLogger'           => PSR_FOLDER . '/AbstractLogger.php',
    'Psr\\Log\\InvalidArgumentException' => PSR_FOLDER . '/InvalidArgumentException.php',
    'Psr\\Log\\LoggerAwareInterface'     => PSR_FOLDER . '/LoggerAwareInterface.php',
    'Psr\\Log\\LoggerAwareTrait'         => PSR_FOLDER . '/LoggerAwareTrait.php',
    'Psr\\Log\\LoggerInterface'          => PSR_FOLDER . '/LoggerInterface.php',
    'Psr\\Log\\LoggerTrait'              => PSR_FOLDER . '/LoggerTrait.php',
    'Psr\\Log\\LogLevel'                 => PSR_FOLDER . '/LogLevel.php',
    'Psr\\Log\\NullLogger'               => PSR_FOLDER . '/NullLogger.php'
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);
