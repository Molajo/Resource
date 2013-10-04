<?php
/**
 * Number To Text
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */

$base = substr(__DIR__, 0, strlen(__DIR__) - 5);

define('BASE_FOLDER', $base);

$classMap = array(
    'Molajo\\NumberToText\\Api\\LoadTranslationInterface'    => BASE_FOLDER . '/Api/LoadTranslationInterface.php',
    'Molajo\\NumberToText\\Api\\ExceptionInterface'          => BASE_FOLDER . '/Api/ExceptionInterface.php',
    'Molajo\\NumberToText\\Api\\NumberToTextInterface'       => BASE_FOLDER . '/Api/NumberToTextInterface.php',
    'Molajo\\NumberToText\\Api\\TranslateInterface'          => BASE_FOLDER . '/Api/TranslateInterface.php',
    'Molajo\\NumberToText\\Exception\\NumberToTextException' => BASE_FOLDER . '/Exception/NumberToTextException.php',
    'Molajo\\NumberToText\\Translations\\Translate'          => BASE_FOLDER . '/Translations/Translate.php',
    'Molajo\\NumberToText\\Translations\\enGB'               => BASE_FOLDER . '/Translations/enGB.php',
    'Molajo\\NumberToText\\Utility'                          => BASE_FOLDER . '/Utility.php',
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);
