<?php
/**
 * Autoload
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */

if (file_exists(BASE_FOLDER . '/defines.php')) {
    include_once BASE_FOLDER . '/defines.php';
}

define('GROUP_ADMINISTRATOR', 1);
define('GROUP_PUBLIC', 2);
define('GROUP_GUEST', 3);
define('GROUP_REGISTERED', 4);

$IoCBase = BASE_FOLDER . '/Kernel/IoC';

$IoCClassMap = array(
    'Molajo\\IoC\\Api\\IoCContainerInterface'          => $IoCBase . '/Api/IoCContainerInterface.php',
    'Molajo\\IoC\\Api\\IoCControllerInterface'         => $IoCBase . '/Api/IoCControllerInterface.php',
    'Molajo\\IoC\\Api\\ExceptionInterface'             => $IoCBase . '/Api/ExceptionInterface.php',
    'Molajo\\IoC\\Api\\ServiceItemInterface'           => $IoCBase . '/Api/ServiceItemInterface.php',
    'Molajo\\IoC\\Api\\ServiceHandlerInterface'        => $IoCBase . '/Api/ServiceHandlerInterface.php',
    'Molajo\\IoC\\Exception\\AdapterException'         => $IoCBase . '/Exception/AdapterException.php',
    'Molajo\\IoC\\Exception\\ContainerException'       => $IoCBase . '/Exception/ContainerException.php',
    'Molajo\\IoC\\Exception\\ControllerException'      => $IoCBase . '/Exception/ControllerException.php',
    'Molajo\\IoC\\Exception\\ErrorThrownAsException'   => $IoCBase . '/Exception/ErrorThrownAsException.php',
    'Molajo\\IoC\\Exception\\FrontcontrollerException' => $IoCBase . '/Exception/FrontcontrollerException.php',
    'Molajo\\IoC\\Exception\\InjectorException'        => $IoCBase . '/Exception/InjectorException.php',
    'Molajo\\IoC\\Exception\\ServiceItemException'     => $IoCBase . '/Exception/ServiceItemException.php',
    'Molajo\\IoC\\Exception\\ServiceHandlerException'  => $IoCBase . '/Exception/ServiceHandlerException.php',
    'Molajo\\IoC\\Handler\\AbstractInjector'           => $IoCBase . '/Handler/AbstractInjector.php',
    'Molajo\\IoC\\Handler\\StandardInjector'           => $IoCBase . '/Handler/StandardInjector.php',
    'Molajo\\IoC\\IoCController'                       => $IoCBase . '/IoCController.php',
    'Molajo\\IoC\\IoCContainer'                        => $IoCBase . '/IoCContainer.php',
    'Molajo\\IoC\\ServiceItemAdapter'                  => $IoCBase . '/ServiceItemAdapter.php'
);

spl_autoload_register(
    function ($class) use ($IoCClassMap) {
        if (array_key_exists($class, $IoCClassMap)) {
            require_once $IoCClassMap[$class];
        }
    }
);

$ResourcesBase = BASE_FOLDER . '/Kernel/Resources';

$ResourcesMap = array(
    'Molajo\\Controller\\Api\\FrontControllerInterface'   => BASE_FOLDER . '/Kernel/Controller/Api' . '/FrontControllerInterface.php',
    'Molajo\\Controller\\FrontController'                 => BASE_FOLDER . '/Kernel/Controller' . '/FrontController.php',
    'Molajo\\Service\\Resources\\ResourcesInjector'       => BASE_FOLDER . '/Kernel/Service/Resources' . '/ResourcesInjector.php',
    'Molajo\\Resources\\Adapter'                          => $ResourcesBase . '/Adapter.php',
    'Molajo\\Resources\\InterfaceMap'                     => $ResourcesBase . '/InterfaceMap.php',
    'Molajo\\Resources\\ResourceMap'                      => $ResourcesBase . '/ResourceMap.php',
    'Molajo\\Resources\\Scheme'                           => $ResourcesBase . '/Scheme.php',
    'Molajo\\Resources\\Api\\ClassHandlerInterface'       => $ResourcesBase . '/Api/ClassHandlerInterface.php',
    'Molajo\\Resources\\Api\\ConfigurationDataInterface'  => $ResourcesBase . '/Api/ConfigurationDataInterface.php',
    'Molajo\\Resources\\Api\\ConfigurationInterface'      => $ResourcesBase . '/Api/ConfigurationInterface.php',
    'Molajo\\Resources\\Api\\ExceptionInterface'          => $ResourcesBase . '/Api/ExceptionInterface.php',
    'Molajo\\Resources\\Api\\ResourceHandlerInterface'    => $ResourcesBase . '/Api/ResourceHandlerInterface.php',
    'Molajo\\Resources\\Api\\ResourceLocatorInterface'    => $ResourcesBase . '/Api/ResourceLocatorInterface.php',
    'Molajo\\Resources\\Api\\ResourceMapInterface'        => $ResourcesBase . '/Api/ResourceMapInterface.php',
    'Molajo\\Resources\\Api\\ResourceNamespaceInterface'  => $ResourcesBase . '/Api/ResourceNamespaceInterface.php',
    'Molajo\\Resources\\Api\\ResourceTagInterface'        => $ResourcesBase . '/Api/ResourceTagInterface.php',
    'Molajo\\Resources\\Api\\ResourceAdapterInterface'    => $ResourcesBase . '/Api/ResourceAdapterInterface.php',
    'Molajo\\Resources\\Api\\SchemeInterface'             => $ResourcesBase . '/Api/SchemeInterface.php',
    'Molajo\\Resources\\Configuration\\AbstractHandler'   => $ResourcesBase . '/Configuration/AbstractHandler.php',
    'Molajo\\Resources\\Configuration\\Data'              => $ResourcesBase . '/Configuration/Data.php',
    'Molajo\\Resources\\Configuration\\DataobjectHandler' => $ResourcesBase . '/Configuration/DataobjectHandler.php',
    'Molajo\\Resources\\Configuration\\ModelHandler'      => $ResourcesBase . '/Configuration/ModelHandler.php',
    'Molajo\\Resources\\Exception\\ResourcesException'    => $ResourcesBase . '/Exception/ResourcesException.php',
    'Molajo\\Resources\\Handler\\ClassHandler'            => $ResourcesBase . '/Handler/ClassHandler.php',
    'Molajo\\Resources\\Handler\\ConstantHandler'         => $ResourcesBase . '/Handler/ConstantHandler.php',
    'Molajo\\Resources\\Handler\\CssdeclarationsHandler'  => $ResourcesBase . '/Handler/CssdeclarationsHandler.php',
    'Molajo\\Resources\\Handler\\CssHandler'              => $ResourcesBase . '/Handler/CssHandler.php',
    'Molajo\\Resources\\Handler\\FileHandler'             => $ResourcesBase . '/Handler/FileHandler.php',
    'Molajo\\Resources\\Handler\\FolderHandler'           => $ResourcesBase . '/Handler/FolderHandler.php',
    'Molajo\\Resources\\Handler\\FunctionHandler'         => $ResourcesBase . '/Handler/FunctionHandler.php',
    'Molajo\\Resources\\Handler\\InterfaceHandler'        => $ResourcesBase . '/Handler/InterfaceHandler.php',
    'Molajo\\Resources\\Handler\\JsdeclarationsHandler'   => $ResourcesBase . '/Handler/JsdeclarationsHandler.php',
    'Molajo\\Resources\\Handler\\JsHandler'               => $ResourcesBase . '/Handler/JsHandler.php',
    'Molajo\\Resources\\Handler\\PageviewHandler'         => $ResourcesBase . '/Handler/PageviewHandler.php',
    'Molajo\\Resources\\Handler\\QueryHandler'            => $ResourcesBase . '/Handler/QueryHandler.php',
    'Molajo\\Resources\\Handler\\TemplateviewHandler'     => $ResourcesBase . '/Handler/TemplateviewHandler.php',
    'Molajo\\Resources\\Handler\\ThemeHandler'            => $ResourcesBase . '/Handler/ThemeHandler.php',
    'Molajo\\Resources\\Handler\\WrapviewHandler'         => $ResourcesBase . '/Handler/WrapviewHandler.php',
    'Molajo\\Resources\\Handler\\XmlHandler'              => $ResourcesBase . '/Handler/XmlHandler.php'
);

spl_autoload_register(
    function ($class) use ($ResourcesMap) {
        if (array_key_exists($class, $ResourcesMap)) {
            require_once $ResourcesMap[$class];
        }
    }
);

require_once BASE_FOLDER . '/Vendor/Autoload.php';
