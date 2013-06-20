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
    'Molajo\\Kernel\\Locator\\Adapter'                             => $LocatorBase . '/Adapter.php',
    'Molajo\\Kernel\\Locator\\Handler\\AbstractLocator'            => $LocatorBase . '/Handler/AbstractLocator.php',
    'Molajo\\Kernel\\Locator\\Handler\\ClassLocator'               => $LocatorBase . '/Handler/ClassLocator.php',
    'Molajo\\Kernel\\Locator\\Handler\\ConfigurationFileLocator'   => $LocatorBase . '/Handler/ConfigurationFileLocator.php',
    'Molajo\\Kernel\\Locator\\Handler\\ConfigurationFolderLocator' => $LocatorBase . '/Handler/ConfigurationFolderLocator.php',
    'Molajo\\Kernel\\Locator\\Handler\\ConfigurationLocator'       => $LocatorBase . '/Handler/ConfigurationLocator.php',
    'Molajo\\Kernel\\Locator\\Handler\\ConfigurationViewLocator'   => $LocatorBase . '/Handler/ConfigurationViewLocator.php',
    'Molajo\\Kernel\\Locator\\Handler\\CssLocator'                 => $LocatorBase . '/Handler/CssLocator.php',
    'Molajo\\Kernel\\Locator\\Handler\\JsLocator'                  => $LocatorBase . '/Handler/JsLocator.php',
    'Molajo\\Kernel\\Locator\\Exception\\LocatorException'         => $LocatorBase . '/Exception/LocatorException.php',
    'Molajo\\Kernel\\Locator\\Api\\ExceptionInterface'             => $LocatorBase . '/Api/ExceptionInterface.php',
    'Molajo\\Kernel\\Locator\\Api\\LocatorInterface'               => $LocatorBase . '/Api/LocatorInterface.php',
    'Molajo\\Kernel\\Locator\\Api\\ClassLocatorInterface'          => $LocatorBase . '/Api/ClassLocatorInterface.php',
    'Molajo\\Kernel\\Locator\\Api\\ResourceMapInterface'           => $LocatorBase . '/Api/ResourceMapInterface.php',
    'Molajo\\Kernel\\Locator\\Utilities\\ResourceMap'              => $LocatorBase . '/Utilities/ResourceMap.php',
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);
