<?php
/**
 * Resource Uri Class
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Proxy;

/**
 * Resource Uri Class
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class Uri extends Scheme
{
    /**
     * Get Resource for Uri
     *
     * @param   string $uri_namespace
     * @param   array  $options
     *
     * @return  mixed|void
     * @since   1.0.0
     */
    protected function getUriResource($uri_namespace, array $options = array())
    {
        $located_path = $this->getUriPath($uri_namespace);

        if ($this->requested_scheme === 'Field') {
            return $this->adapter_instance_array[$this->requested_adapter]->get(substr($uri_namespace, 9, 999));
        }

        return $this->locateNamespace(str_replace('\\', '/', $this->path), $this->requested_scheme, $options);
    }

    /**
     * Get Path for URI
     *
     * @param   string $uri_namespace
     *
     * @return  string
     * @since   1.0.0
     */
    protected function getUriPath($uri_namespace)
    {
        $this->requested_scheme = $this->getUriScheme($uri_namespace);

        $scheme = $this->getScheme($this->requested_scheme);

        $this->requested_adapter = $this->requested_scheme->adapter;

        return $scheme->adapter->exists($uri_namespace);
    }


    /**
     * Locates a resource using only the namespace
     *
     * @param   string $namespace
     * @param   string $scheme
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0.0
     */
    protected function locateNamespace($namespace, $scheme = 'ClassLoader', array $options = array())
    {
        $this->getScheme($scheme);

        $located_path = $this->adapter_instance_array[$this->requested_adapter]->get($namespace);

        $options['namespace'] = $namespace;

        return $this->handlePath($this->requested_scheme, $located_path, $options);
    }

    /**
     * Handle located folder/file associated with URI Namespace for Resource
     *
     * @param   string $requested_scheme
     * @param   string $located_path
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0.0
     */
    protected function handlePath($requested_scheme, $located_path, array $options = array())
    {
        $this->getScheme($requested_scheme);

        if (strtolower($requested_scheme) == 'query') {
            $xml            = $this->adapter_instance_array['Xml']->handlePath(
                $requested_scheme,
                $located_path,
                $options
            );
            $options['xml'] = $xml;

            $this->requested_adapter = 'Query';
        }

        return $this->adapter_instance_array[$this->requested_adapter]->handlePath($requested_scheme, $located_path, $options);
    }

}
