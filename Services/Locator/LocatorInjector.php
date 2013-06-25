<?php
/**
 * Locator Class Injector
 *
 * @package   Molajo
 * @license   http:/www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Service\Locator;

use Exception;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Molajo\IoC\Exception\ServiceItemException;
use Molajo\IoC\Handler\CustomInjector;
use Molajo\IoC\Api\ServiceItemInterface;

/**
 * Locator Class Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http:/www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class LocatorInjector extends CustomInjector implements ServiceItemInterface
{
    /**
     * Order of precedence for searching
     *
     * @var    array
     * @since  1.0
     */
    protected $sort_order = array(
        'Site',
        'Sites',
        'Theme',
        'Page',
        'Menuitem',
        'Resource',
        'Plugin',
        'Template',
        'Wrap',
        'System'
    );

    /**
     * Canonical Namespace Pairs
     *
     * @var    array
     * @since  1.0
     */
    protected $include_canonical_array = array(
        'Molajo\\Administration'   => array('Levels' => 1, 'IncludeFolder' => array('Application/Administration'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Appconfiguration' => array('Levels' => 1, 'IncludeFolder' => array('Application/Model/Appconfiguration'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Cache'            => array('Levels' => 1, 'IncludeFolder' => array('Vendor/Molajo/Cache'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Configuration'    => array('Levels' => 1, 'IncludeFolder' => array('Kernel/Configuration'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Controller'       => array('Levels' => 1, 'IncludeFolder' => array('Kernel/Controller'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Database'         => array('Levels' => 1, 'IncludeFolder' => array('Vendor/Molajo/Database'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Datalist'         => array('Levels' => 1, 'IncludeFolder' => array('Application/Model/Datalist'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Dataobject'       => array('Levels' => 1, 'IncludeFolder' => array('Application/Model/Dataobject'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Datasource'       => array('Levels' => 1, 'IncludeFolder' => array('Application/Model/Datasource'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Email'            => array('Levels' => 1, 'IncludeFolder' => array('Vendor/Molajo/Email'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Event'            => array('Levels' => 1, 'IncludeFolder' => array('Kernel/Event', 'Molajo/Event/Service', 'Molajo/User/Service'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Field'            => array('Levels' => 1, 'IncludeFolder' => array('Application/Model/Field'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\FieldHandler'     => array('Levels' => 1, 'IncludeFolder' => array('Vendor/Molajo/FieldHandler'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Filesystem'       => array('Levels' => 1, 'IncludeFolder' => array('Vendor/Molajo/Filesystem'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\FileUpload'       => array('Levels' => 1, 'IncludeFolder' => array('Vendor/Molajo/FileUpload'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Http'             => array('Levels' => 1, 'IncludeFolder' => array('Kernel/Http'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Include'          => array('Levels' => 1, 'IncludeFolder' => array('Application/Model/Include'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\IoC'              => array('Levels' => 1, 'IncludeFolder' => array('Kernel/IoC'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Language'         => array('Levels' => 1, 'IncludeFolder' => array('Vendor/Molajo/Language'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Locator'          => array('Levels' => 1, 'IncludeFolder' => array('Kernel/Locator'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Log'              => array('Levels' => 1, 'IncludeFolder' => array('Vendor/Molajo/Log'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Menuitem'         => array('Levels' => 1, 'IncludeFolder' => array('Application/Extension/Menuitem'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Mvc'              => array('Levels' => 1, 'IncludeFolder' => array('Vendor/Molajo/Mvc'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\NumberToText'     => array('Levels' => 1, 'IncludeFolder' => array('Vendor/Molajo/NumberToText'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Pagination'       => array('Levels' => 1, 'IncludeFolder' => array('Vendor/Molajo/Pagination'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Plugin'           => array('Levels' => 1, 'IncludeFolder' => array('Application/Extension/Plugin'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Registry'         => array('Levels' => 1, 'IncludeFolder' => array('Vendor/Molajo/Registry'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Resource'         => array('Levels' => 1, 'IncludeFolder' => array('Application/Extension/Resource'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Search'           => array('Levels' => 1, 'IncludeFolder' => array('Vendor/Molajo/Search'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Service'          => array('Levels' => 1, 'IncludeFolder' => array('Service', 'User/Service', 'Kernel/Event/Service'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Sites'            => array('Levels' => 1, 'IncludeFolder' => array('Sites'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Theme'            => array('Levels' => 1, 'IncludeFolder' => array('Application/Extension/Theme'), 'ExcludeFolder' => array('Vendor/Molajo/Theme/View')),
        'Molajo\\User'             => array('Levels' => 1, 'IncludeFolder' => array('User'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\Utilities'        => array('Levels' => 1, 'IncludeFolder' => array('Vendor/Molajo/Utilities'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\View\\Page'       => array('Levels' => 1, 'IncludeFolder' => array('Application/Extension/View/Page', 'Vendor/Molajo/Theme/View/Page'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\View\\Template'   => array('Levels' => 1, 'IncludeFolder' => array('Application/Extension/View/Template', 'Vendor/Molajo/Theme/View/Template'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Molajo\\View\\Wrap'       => array('Levels' => 1, 'IncludeFolder' => array('Application/Extension/View/Wrap', 'Vendor/Molajo/Theme/View/Wrap'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'PhpMailer'                => array('Levels' => 1, 'IncludeFolder' => array('Vendor/PhpMailer'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Psr\\Log'                 => array('Levels' => 1, 'IncludeFolder' => array('Vendor/psr/log/Psr/Log'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Whoops'                   => array('Levels' => 1, 'IncludeFolder' => array('Vendor/filp/whoops/src'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
        'Joomla\\Database'         => array('Levels' => 1, 'IncludeFolder' => array('Vendor/Joomla/Database'), 'ExcludeFolder' => array('.dev','.travis.yml', '.DS_Store', '.git', '.', '..', '.gitattributes', '.gitignore'),  'RequireFileExtensions' => '', 'ProhibitFileExtensions' => ''),
    );

    /**
     * Handler Instances
     *
     * @var    object  Molajo\Locator\Api\LocatorInterface
     * @since  1.0
     */
    protected $include_file_extensions_array = array(
        'Molajo\\View'   => '.html.php'
    );

    /**
     * Handler Instances
     *
     * @var    object  Molajo\Locator\Api\LocatorInterface
     * @since  1.0
     */
    protected $include_tags_array = array(
        'Molajo\\Appconfiguration' => 'Appconfiguration',
        'Molajo\\Datalist'         => 'Datalist',
        'Molajo\\Dataobject'       => 'Dataobject',
        'Molajo\\Datasource'       => 'Datasource',
        'Molajo\\Field'            => 'Field',
        'Molajo\\Include'          => 'Include',
        'Molajo\\Public\\Css'   => 'Css',
        'Molajo\\Public\\Js'    => 'Js',
        'Molajo\\Public\\Media' => 'Media,Images',
        'Molajo\\View\\Page'     => 'Page',
        'Molajo\\View\\Template' => 'Template',
        'Molajo\\View\\Wrap'     => 'Wrap',
        'Molajo\\Administration' => 'Administration',
        'Molajo\\Menuitem'       => 'Menuitem',
        'Molajo\\Plugin'         => 'Plugin',
        'Molajo\\Resource'       => 'Resource',
        'Molajo\\Service'        => 'Service',
        'Molajo\\Theme'          => 'Theme',
        'Molajo\\User'           => 'User',
    );

    /**
     * Valid extensions
     *
     * @var    array
     * @since  1.0
     */
    protected $valid_extensions_array = array();

    /**
     * Handler Instances
     *
     * @var    object  Molajo\Locator\Api\LocatorInterface
     * @since  1.0
     */
    protected $handler_instance;

    /**
     * Constructor
     *
     * @param  $options
     *
     * @since  1.0
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->service                  = basename(__DIR__);
        $this->service_namespace        = 'Molajo\\Locator\\Handler\\ClassLocator';
        $this->store_instance_indicator = true;
    }

    /**
     * Identify Class Dependencies for Constructor Injection
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceItemException
     */
    public function getDependencies()
    {
        $this->dependencies = array();

        $options['file_extensions']          = array();
        $options['file_extensions']['Class'] = '.php';
        $options['base_path']                = BASE_FOLDER;
        $options['rebuild_map']              = true;
        $options['resource_map_filename']    = BASE_FOLDER . '/' . 'Kernel/Locator/ClassMap.json';
        $options['exclude_in_path_array']    = $this->exclude_in_path_array;
        $options['exclude_path_array']       = $this->exclude_path_array;
        $options['valid_extensions_array']   = $this->valid_extensions_array;
        $options['namespace_prefixes']       = $this->rebuildResourceMap();

        $this->dependencies['LocatorResourceMap'] = $options;

        $this->options['file_extensions']          = array();
        $this->options['file_extensions']['Class'] = '.php';
        $this->options['base_path']                = BASE_FOLDER;
        $this->options['rebuild_map']              = true;
        $this->options['resource_map_filename']    = BASE_FOLDER . '/' . 'Kernel/Locator/ClassMap.json';
        $this->options['exclude_in_path_array']    = $this->exclude_in_path_array;
        $this->options['exclude_path_array']       = $this->exclude_path_array;
        $this->options['valid_extensions_array']   = $this->valid_extensions_array;
        $this->options['namespace_prefixes']       = $this->rebuildResourceMap();

        return $this->dependencies;
    }

    /**
     * Set Dependency Values
     *
     * @param   array $dependencies
     *
     * @return  $this|object
     * @since   1.0
     */
    public function setDependencies(array $dependency_instances = array())
    {
        $this->dependencies['file_extensions']        = $this->options['file_extensions'];
        $this->dependencies['namespace_prefixes']     = $this->options['namespace_prefixes'];
        $this->dependencies['base_path']              = $this->options['base_path'];
        $this->dependencies['rebuild_map']            = $this->options['rebuild_map'];
        $this->dependencies['resource_map_filename']  = $this->options['resource_map_filename'];
        $this->dependencies['exclude_in_path_array']  = $this->options['exclude_in_path_array'];
        $this->dependencies['exclude_path_array']     = $this->options['exclude_path_array'];
        $this->dependencies['valid_extensions_array'] = $this->options['valid_extensions_array'];
        $this->dependencies['resource_map_instance']  = $dependency_instances['LocatorResourceMap'];

        parent::setDependencies(array());

        return $this;
    }

    /**
     * Follows the completion of the instantiate service method
     *
     * @return  object
     * @since   1.0
     * @throws  ServiceItemException
     */
    public function performAfterInstantiationLogic()
    {
        $class = 'Molajo\\Locator\\Adapter';
        try {
            $adapter = new $class (
                $this->service_instance,
                'Class'
            );

        } catch (Exception $e) {
            throw new ServiceItemException ('Locator Adapter ' . $class
            . ' Exception during Instantiation: ' . $e->getMessage());
        }

        $this->service_instance = $adapter;

        $this->service_instance->addNamespace(
            'PasswordLib\\PasswordLib',
            BASE_FOLDER . '/User/Encrypt/PasswordLib.phar'
        );

        return $this;
    }

    /**
     * Rebuild Resource Map
     *
     * @return  $this|object
     * @since   1.0
     */
    public function rebuildResourceMap()
    {
        $namespace_prefixes = array();

        $namespace_prefixes = $this->buildNameSpacePrefixes(
            $this->include_extensions_array,
            $this->sort_order,
            array()
        );

        $namespace_prefixes = $this->buildNameSpacePrefixes(
            $this->include_items_array,
            $this->sort_order,
            $namespace_prefixes
        );

        $namespace_prefixes = $this->buildNameSpacePrefixes(
            $this->include_views_array,
            $this->sort_order,
            $namespace_prefixes
        );

        $namespace_prefixes = $this->buildNameSpacePrefixes(
            $this->include_assets_array,
            $this->sort_order,
            $namespace_prefixes
        );

        /** Append in with Canonical Namespace Prefixes */
        foreach ($this->include_canonical_array as $namespace => $paths) {
            foreach ($paths as $path) {
                $namespace_prefixes = $this->mergeMultidimensionalArray(
                    $namespace,
                    $namespace_prefixes,
                    $path
                );
            }
        }

        return $namespace_prefixes;
    }

    /**
     * Build Namespace Prefixes
     *
     * @param   array $include_array
     * @param   array $sort_order
     * @param   array $path_array (merge into existing)
     *
     * @return  array
     * @since   1.0
     */
    protected function buildNameSpacePrefixes(
        array $include_array = array(),
        array $sort_order = array(),
        array $path_array = array()
    ) {
        $namespace_prefixes = array();

        $objects = new RecursiveIteratorIterator
        (new RecursiveDirectoryIterator(BASE_FOLDER),
            RecursiveIteratorIterator::SELF_FIRST);

        foreach ($objects as $path => $fileObject) {

            $use       = true;
            $file_name = '';
            $base_name = '';

            if (is_dir($fileObject)) {

                if ($fileObject->getFileName() == '.' || $fileObject->getFileName() == '..') {

                } elseif (in_array($fileObject->getFileName(), $include_array)) {

                    $path = substr($fileObject->getPathName(), strlen(BASE_FOLDER) + 1, 9999);

                    foreach ($include_array as $namespace => $key) {

                        $skip = 0;

                        foreach ($this->exclude_in_path_array as $exclude) {

                            if (strpos($path, $exclude) === false) {
                            } else {
                                if ($key !== $exclude) {
                                    $skip = 1;
                                }
                            }
                        }

                        if (substr($path, - strlen($key)) == $key && $skip == 0) {
                            $path_array = $this->mergeMultidimensionalArray(
                                $namespace,
                                $path_array,
                                $path
                            );
                        }
                    }
                }
            }
        }

        ksort($path_array);

        return $path_array;
    }

    /**
     * Merge Array for Namespace
     *
     * @param   $namespace
     * @param   $path_array
     * @param   $path
     *
     * @return  $this|object
     * @since   1.0
     */
    protected function mergeMultidimensionalArray($namespace, $path_array, $path)
    {
        $paths = array();

        if (isset($path_array[$namespace])) {

            $existing = $path_array[$namespace];

            if (is_array($existing)) {
                $paths = $existing;
            } else {
                $paths[] = array();
                $paths[] = $existing;
            }

        } else {
            $paths = array();
        }

        $paths[]                = $path;
        $path_array[$namespace] = $paths;

        return $path_array;
    }
}
