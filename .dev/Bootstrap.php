<?php
/**
 * Resource
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
$ResourceBase = substr(__DIR__, 0, strlen(__DIR__) - 5);
define('BASE_FOLDER', $ResourceBase);

$classMap = array(
    'Molajo\\Resource\\Adapter'                               => $ResourceBase . '/Adapter.php',
    'Molajo\\Resource\\Handler\\AbstractResource'            => $ResourceBase . '/Handler/AbstractResource.php',
    'Molajo\\Resource\\Handler\\ClassHandler'                 => $ResourceBase . '/Handler/ClassHandler.php',
    'Molajo\\Resource\\Handler\\ConfigurationfileResource'   => $ResourceBase . '/Handler/ConfigurationfileResource.php',
    'Molajo\\Resource\\Handler\\ConfigurationFolderResource' => $ResourceBase . '/Handler/ConfigurationFolderResource.php',
    'Molajo\\Resource\\Handler\\ConfigurationResource'       => $ResourceBase . '/Handler/ConfigurationResource.php',
    'Molajo\\Resource\\Handler\\ConfigurationViewResource'   => $ResourceBase . '/Handler/ConfigurationViewResource.php',
    'Molajo\\Resource\\Handler\\CssResource'                 => $ResourceBase . '/Handler/CssResource.php',
    'Molajo\\Resource\\Handler\\JsResource'                  => $ResourceBase . '/Handler/JsResource.php',
    'Molajo\\Resource\\Exception\\ResourceException'         => $ResourceBase . '/Exception/ResourceException.php',
    'Molajo\\Resource\\CommonApi\\ExceptionInterface'               => $ResourceBase . '/Api/ExceptionInterface.php',
    'Molajo\\Resource\\CommonApi\\ResourceInterface'               => $ResourceBase . '/Api/ResourceInterface.php',
    'Molajo\\Resource\\CommonApi\\ClassHandlerInterface'            => $ResourceBase . '/Api/ClassHandlerInterface.php',
    'Molajo\\Resource\\CommonApi\\MapInterface'             => $ResourceBase . '/Api/MapInterface.php',
    'Molajo\\Resource\\Utilities\\ResourceMap'                => $ResourceBase . '/Utilities/ResourceMap.php',
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);
