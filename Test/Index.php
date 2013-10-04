<?php
/**
 * Why does the variable defined in this script generate an "Undefined variable" notice
 *  when the function is included within a folder -- and no notice when the file is
 *  contained within the same directory?
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */

/** Autoload */
$classMap = array(
    'Molajo\\FrontController' => __DIR__ . '/Class/FrontController.php'
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);

/** Injected value used during testing */
$injected_value = 'Hello World!';

/** Class File */
require_once __DIR__ . '/Class/FrontController.php';
$class_namespace = 'Molajo\\FrontController';
$frontController = new $class_namespace($injected_value);

/**
 *  Anonymous Function from same directory stored as $function1 variable
 *
 *  Note: No PHP Notice for Undefined Variable.
 */
require_once __DIR__ . '/Function1.php';
$frontController->test1($function1);

/**
 *  Anonymous Function stored in Subfolder stored as $function2 variable
 *
 *  Note: A PHP Notice for Undefined Variable is generated.
 */
require_once __DIR__ . '/Include/Function2.php';
$frontController->test2($function2);
