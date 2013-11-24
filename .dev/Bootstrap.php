<?php
/**
 * Resources
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
$ResourcesBase = substr(__DIR__, 0, strlen(__DIR__) - 5);
define('BASE_FOLDER', $ResourcesBase);

$classMap = array(
    'Molajo\\Resources\\Adapter'                               => $ResourcesBase . '/Adapter.php',
    'Molajo\\Resources\\Handler\\AbstractResources'            => $ResourcesBase . '/Handler/AbstractResources.php',
    'Molajo\\Resources\\Handler\\ClassHandler'                 => $ResourcesBase . '/Handler/ClassHandler.php',
    'Molajo\\Resources\\Handler\\ConfigurationfileResources'   => $ResourcesBase . '/Handler/ConfigurationfileResources.php',
    'Molajo\\Resources\\Handler\\ConfigurationFolderResources' => $ResourcesBase . '/Handler/ConfigurationFolderResources.php',
    'Molajo\\Resources\\Handler\\ConfigurationResources'       => $ResourcesBase . '/Handler/ConfigurationResources.php',
    'Molajo\\Resources\\Handler\\ConfigurationViewResources'   => $ResourcesBase . '/Handler/ConfigurationViewResources.php',
    'Molajo\\Resources\\Handler\\CssResources'                 => $ResourcesBase . '/Handler/CssResources.php',
    'Molajo\\Resources\\Handler\\JsResources'                  => $ResourcesBase . '/Handler/JsResources.php',
    'Molajo\\Resources\\Exception\\ResourcesException'         => $ResourcesBase . '/Exception/ResourcesException.php',
    'Molajo\\Resources\\CommonApi\\ExceptionInterface'               => $ResourcesBase . '/Api/ExceptionInterface.php',
    'Molajo\\Resources\\CommonApi\\ResourcesInterface'               => $ResourcesBase . '/Api/ResourcesInterface.php',
    'Molajo\\Resources\\CommonApi\\ClassHandlerInterface'            => $ResourcesBase . '/Api/ClassHandlerInterface.php',
    'Molajo\\Resources\\CommonApi\\MapInterface'             => $ResourcesBase . '/Api/MapInterface.php',
    'Molajo\\Resources\\Utilities\\ResourceMap'                => $ResourcesBase . '/Utilities/ResourceMap.php',
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);
