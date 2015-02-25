<?php
include_once __DIR__ . '/ReadJsonFile.php';
/**
 * Bootstrap for Testing
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
$base      = substr(__DIR__, 0, strlen(__DIR__) - 5);
$test_base = __DIR__;

if (function_exists('CreateClassMap')) {
} else {
    include_once __DIR__ . '/CreateClassMap.php';
}
include_once $base . '/vendor/autoload.php';

$classmap                                  = array();
$classmap['Molajo\\Resource\\ClassMap']    = $base . '/Source/ClassMap.php';
$classmap['Molajo\\Resource\\ResourceMap'] = $base . '/Source/ResourceMap.php';
$classmap['Molajo\\Resource\\Proxy']       = $base . '/Source/Proxy.php';
$classmap['Molajo\\Resource\\Scheme']      = $base . '/Source/Scheme.php';

$results  = createClassMap($test_base . '/Tests/Source/A/', 'Molajo\\A\\');
$classmap = array_merge($classmap, $results);
$results  = createClassMap($test_base . '/Tests/Source/A/Z/', 'Molajo\\A\\Z\\');
$classmap = array_merge($classmap, $results);
$results  = createClassMap($test_base . '/Tests/Source/B/', 'Molajo\\B\\');
$classmap = array_merge($classmap, $results);
$results  = createClassMap($test_base . '/Tests/Source/C/', 'Molajo\\C\\');
$classmap = array_merge($classmap, $results);
$results  = createClassMap($test_base . '/Tests/Source/Plugins/', 'Molajo\\Plugins\\');
$classmap = array_merge($classmap, $results);

spl_autoload_register(
    function ($class) use ($classmap) {
        if (array_key_exists($class, $classmap)) {
            require_once $classmap[$class];
        }
    }
);

//include_once __DIR__ . '/Reflection.php';
