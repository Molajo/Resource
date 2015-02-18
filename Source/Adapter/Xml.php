<?php
/**
 * Xml Handler
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Adapter;

use CommonApi\Resource\AdapterInterface;
use CommonApi\Exception\RuntimeException;

/**
 * Xml Handler
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class Xml extends ConfigurationFactory implements AdapterInterface
{
    /**
     * Set a namespace prefix by mapping to the filesystem path
     *
     * @param   string  $namespace_prefix
     * @param   string  $namespace_base_directory
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0.0
     */
    public function setNamespace($namespace_prefix, $namespace_base_directory, $prepend = false)
    {
        return parent::setNamespace($namespace_prefix, $namespace_base_directory, $prepend);
    }

    /**
     * Locates folder/file associated with Namespace for Resource
     *
     * @param   string $resource_namespace
     * @param   bool   $multiple
     *
     * @return  array|mixed|string|void
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function get($resource_namespace, $multiple = false)
    {
        return parent::get($resource_namespace);
    }

    /**
     * Xml file is located, read, loaded using Simplexml into a string and then sent back
     *  or processed by the Configuration data_object or Model utility
     *
     * @param   string $scheme
     * @param   string $located_path
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function handlePath($scheme, $located_path, array $options = array())
    {
        $this->verifyNamespace($options);

        $segments = $this->handlePathSegments($options);

        list($model_type, $model_name) = $this->setModelTypeName($segments);

        $this->verifyFileExists($located_path);

        $contents   = file_get_contents($located_path);
        $scheme     = strtolower(trim($scheme));
        $model_type = ucfirst(strtolower(trim($model_type)));

        return $this->handlePathResults($scheme, $model_type, $model_name, $located_path, $contents);
    }

    /**
     * Break Namespace into Segments
     *
     * @param   array $options
     *
     * @return  array
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function handlePathSegments(array $options = array())
    {
        $segments = explode('//', $options['namespace']);

        if (count($segments) > 2) {
        } else {
            echo '<pre>';
            var_dump($segments);
            throw new RuntimeException(
                'Resource XmlHandler Failure namespace must have at least 3 segments:  '
                . $options['namespace']
            );
        }

        return $segments;
    }

    /**
     * Derive Model Type and Name from NS Segments
     *
     * @param   array $segments
     *
     * @return  array
     * @since   1.0.0
     */
    public function setModelTypeName(array $segments = array())
    {
        if (ucfirst(strtolower($segments[1])) === 'Resources') {
            $model_type = ucfirst(strtolower($segments[1]));
            $model_name = ucfirst(strtolower($segments[2] . $segments[3]));

        } elseif (count($segments) === 3) {
            $model_type = ucfirst(strtolower($segments[1]));
            $model_name = ucfirst(strtolower($segments[2]));

        } else {
            $model_type = ucfirst(strtolower($segments[2]));
            $model_name = ucfirst(strtolower($segments[3]));
        }

        if (substr($model_name, strlen($model_name) - 4, 4) === '.xml') {
            $model_name = substr($model_name, 0, strlen($model_name) - 4); //remove .xml
        }
        return array($model_type, $model_name);
    }

    /**
     * Process Request given path
     *
     * @param   string $scheme
     * @param   string $model_type
     * @param   string $model_name
     * @param   string $located_path
     * @param   string $contents
     *
     * @return  object
     * @since   1.0.0
     */
    protected function handlePathResults(
        $scheme,
        $model_type,
        $model_name,
        $located_path,
        $contents
    ) {
        if ($scheme === 'query') {
            return $this->handlePathQuery($model_type, $model_name, $contents);
        }

        if ($model_type === 'Application') {
            return $this->handlePathApplication($contents);
        }

        if ($model_type === 'Include') {
            return $this->handlePathInclude($contents);
        }

        if ($model_type === 'Dataobject') {
            return $this->handlePathDataObject($model_name, $contents);
        }

        if ($model_type === 'Datasource') {
            return $this->handlePathDatasource($model_name, $contents);
        }

        if ($model_type === 'Resources') {
            return $this->handlePathResources($model_type, $model_name, $contents);
        }

        $message = ' SCHEME: ' . $scheme
            . ' MODEL TYPE: ' . $model_type
            . ' MODEL NAME: ' . $model_name
            . ' PATH: ' . $located_path;

        throw new RuntimeException('Resource XmlHandler Failure: ' . $message);
    }

    /**
     * Process Application Request
     *
     * @param   string $contents
     *
     * @return  object
     * @since   1.0.0
     */
    protected function handlePathApplication($contents)
    {
        $xml = simplexml_load_string($contents);

        return $xml;
    }

    /**
     * Process data_object Request
     *
     * @param   string $model_name
     * @param   string $contents
     *
     * @return  object
     * @since   1.0.0
     */
    protected function handlePathDataObject($model_name, $contents)
    {
        $data_object_configuration = $this->instantiateDataObjectConfiguration();

        $xml = simplexml_load_string($contents);

        return $data_object_configuration->getConfiguration('Dataobject', $model_name, $xml);
    }

    /**
     * Process Datasource Request
     *
     * @param   string $model_name
     * @param   string $contents
     *
     * @return  object
     * @since   1.0.0
     */
    protected function handlePathDatasource($model_name, $contents)
    {
        $model_configuration = $this->instantiateModelConfiguration();

        $xml = simplexml_load_string($contents);

        return $model_configuration->getConfiguration('Datasource', $model_name, $xml);
    }

    /**
     * Process Resources Request
     *
     * @param   string $model_type
     * @param   string $model_name
     * @param   string $contents
     *
     * @return  object
     * @since   1.0.0
     */
    protected function handlePathResources($model_type, $model_name, $contents)
    {
        $model_configuration = $this->instantiateModelConfiguration();

        $xml = simplexml_load_string($contents);

        return $model_configuration->getConfiguration($model_type, $model_name, $xml);
    }

    /**
     * Process Include Request
     *
     * @param   string $contents
     *
     * @return  object
     * @since   1.0.0
     */
    protected function handlePathInclude($contents)
    {
        return $contents;
    }

    /**
     * Process Query Request
     *
     * @param   string $model_type
     * @param   string $model_name
     * @param   string $contents
     *
     * @return  object
     * @since   1.0.0
     */
    protected function handlePathQuery($model_type, $model_name, $contents)
    {
        $model_configuration = $this->instantiateModelConfiguration();

        $xml = simplexml_load_string($contents);

        return $model_configuration->getConfiguration($model_type, $model_name, $xml);
    }

    /**
     * Retrieve a collection of a specific handler
     *
     * @param   string $scheme
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function getCollection($scheme, array $options = array())
    {
        return null;
    }
}
