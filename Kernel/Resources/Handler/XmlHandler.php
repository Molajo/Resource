<?php
/**
 * XML Handler
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resources\Handler;

use Exception;
use Molajo\Resources\Api\ResourceHandlerInterface;
use Molajo\Resources\Exception\ResourcesException;
use Molajo\Resources\Api\ConfigurationInterface;

/**
 * XMK Handler
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class XmlHandler implements ResourceHandlerInterface
{
    /**
     * Model Handler
     *
     * @var    object Molajo\Resources\Configuration\ModelHandler
     * @since  1.0
     */
    protected $model_handler;

    /**
     * Data Object Handler
     *
     * @var    object Molajo\Resources\Configuration\DataobjectHandler
     * @since  1.0
     */
    protected $dataobject_handler;

    /**
     * Model Name is a File
     *
     * Model Type = Folder
     * Model Name = File . file_extension
     *
     * @var    string
     * @since  1.0
     */
    private $file_type = array(
        'Application',
        'Datalist',
        'Dataobject',
        'Datasource',
        'Field',
        'Include'
    );

    /**
     * Model Name is a Folder
     *
     * Model Type = Folder
     * Model Name = Folder
     * Configuration.xml
     *
     * @var    string
     * @since  1.0
     */
    private $folder_type = array(
        'Menuitem',
        'Plugin',
        'Resource',
        'Service',
        'System',
        'Theme'
    );

    /**
     * View followed by Folder Type
     *
     * View = Folder
     * Model Type = Folder
     * Model Name = Folder
     * Configuration.xml
     *
     * @var    string
     * @since  1.0
     */
    private $view_type = array(
        'Page',
        'Template',
        'Wrap'
    );

    /**
     * Constructor
     *
     * @param  array $option
     *
     * @since  1.0
     */
    public function __construct(
        ConfigurationInterface $model_handler = null,
        ConfigurationInterface $dataobject_handler = null
    ) {
        $this->model_handler      = $model_handler;
        $this->dataobject_handler = $dataobject_handler;
    }

    /**
     * Xml file is located, read, loaded using Simplxml into a string and then sent back
     *  or processed by the Configuration Dataobject or Model utility
     *
     * @param   string $scheme
     * @param   string $located_path
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function handlePath($scheme, $located_path, array $options = array())
    {
        ;
        if (isset($options['namespace'])) {
        } else {
            throw new ResourcesException
            ('Resources XmlHandler handlePath options array must have namespace entry.');
        }

        $segments = explode('//', $options['namespace']);
        if (count($segments > 2)) {
        } else {
            throw new ResourcesException
            ('Resources XmlHandler Failure namespace must have at least 3 segments:  ' . $options['namespace']);
        }

        $model_type = ucfirst(strtolower($segments[1]));
        $model_name = ucfirst(strtolower($segments[2]));
        $model_name = substr($model_name, 0, strlen($model_name) - 4); //remove .xml

        if (file_exists($located_path)) {
        } else {
            throw new ResourcesException
            ('Resources XmlHandler located_path not found: ' . $located_path);
        }

        try {

            $contents = file_get_contents($located_path);

            if ($scheme == 'query') {
                $xml = simplexml_load_string($contents);
                return $this->model_handler->getConfiguration($model_type, $model_name, $xml);

            } elseif ($model_type == 'Application') {
                $xml = simplexml_load_string($contents);
                return $xml;

            } elseif ($model_type == 'Field' || $model_type == 'Include') {
                return $contents;

            } elseif ($model_type == 'Dataobject') {
                $xml = simplexml_load_string($contents);
                return $this->dataobject_handler->getConfiguration($model_type, $model_name, $xml);

            } else {
                $xml = simplexml_load_string($contents);
                return $this->model_handler->getConfiguration($model_type, $model_name, $xml);
            }

        } catch (Exception $e) {

            throw new ResourcesException
            ('Resources XmlHandler Failure:  ' . $located_path . ' ' . $e->getMessage());
        }
    }

    /**
     * Retrieve a collection of a specific handler
     *
     * @param   string $scheme
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function getCollection($scheme, array $options = array())
    {
        return null;
    }
}
