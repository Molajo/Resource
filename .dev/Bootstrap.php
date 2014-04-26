<?php
/**
 * Bootstrap for Testing
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
$base = substr(__DIR__, 0, strlen(__DIR__) - 5);
include $base . '/vendor/autoload.php';

include_once __DIR__ . '/CreateClassMap.php';

$classmap = array();
$classmap['Molajo\\Resource\\ClassMap']    = $base . '/Source/ClassMap.php';
$classmap['Molajo\\Resource\\ResourceMap'] = $base . '/Source/ResourceMap.php';
$classmap['Molajo\\Resource\\Driver']      = $base . '/Source/Driver.php';
$classmap['Molajo\\Resource\\Scheme']      = $base . '/Source/Scheme.php';
$results  = createClassMap($base . '/Source/Adapter/', 'Molajo\\Render\\Adapter\\');
$classmap = array_merge($classmap, $results);

spl_autoload_register(
    function ($class) use ($classmap) {
        if (array_key_exists($class, $classmap)) {
            require_once $classmap[$class];
        }
    }
);
