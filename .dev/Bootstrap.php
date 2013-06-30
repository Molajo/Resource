<?php
/**
 * Locator
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
$LocatorBase = substr(__DIR__, 0, strlen(__DIR__) - 5);
define('BASE_FOLDER', $LocatorBase);

$classMap = array(
    'Molajo\\Locator\\Adapter'                             => $LocatorBase . '/Adapter.php',
    'Molajo\\Locator\\Handler\\AbstractLocator'            => $LocatorBase . '/Handler/AbstractLocator.php',
    'Molajo\\Locator\\Handler\\ClassLoader'                => $LocatorBase . '/Handler/ClassLoader.php',
    'Molajo\\Locator\\Handler\\ConfigurationFileLocator'   => $LocatorBase . '/Handler/ConfigurationFileLocator.php',
    'Molajo\\Locator\\Handler\\ConfigurationFolderLocator' => $LocatorBase . '/Handler/ConfigurationFolderLocator.php',
    'Molajo\\Locator\\Handler\\ConfigurationLocator'       => $LocatorBase . '/Handler/ConfigurationLocator.php',
    'Molajo\\Locator\\Handler\\ConfigurationViewLocator'   => $LocatorBase . '/Handler/ConfigurationViewLocator.php',
    'Molajo\\Locator\\Handler\\CssLocator'                 => $LocatorBase . '/Handler/CssLocator.php',
    'Molajo\\Locator\\Handler\\JsLocator'                  => $LocatorBase . '/Handler/JsLocator.php',
    'Molajo\\Locator\\Exception\\LocatorException'         => $LocatorBase . '/Exception/LocatorException.php',
    'Molajo\\Locator\\Api\\ExceptionInterface'             => $LocatorBase . '/Api/ExceptionInterface.php',
    'Molajo\\Locator\\Api\\LocatorInterface'               => $LocatorBase . '/Api/LocatorInterface.php',
    'Molajo\\Locator\\Api\\ClassLoaderInterface'           => $LocatorBase . '/Api/ClassLoaderInterface.php',
    'Molajo\\Locator\\Api\\ResourceMapInterface'           => $LocatorBase . '/Api/ResourceMapInterface.php',
    'Molajo\\Locator\\Utilities\\ResourceMap'              => $LocatorBase . '/Utilities/ResourceMap.php',
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);
