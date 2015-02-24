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

        if ($this->scheme_value === 'Field') {
            return $this->adapter_instance_array[$this->adapter_value]->get(substr($uri_namespace, 9, 999));
        }

        return $this->locateNamespace(str_replace('\\', '/', $this->path), $this->scheme_value, $options);
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
        $this->parseUri($uri_namespace);

        $this->scheme_value = 'file';

        $this->getScheme($this->scheme_value);

        return $this->adapter_instance_array[$this->adapter_value]->get($uri_namespace);
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

        $multiple = false;

        if (isset($options['multiple']) && $options['multiple'] === true) {
            $multiple = true;
        }

        $located_path = $this->adapter_instance_array[$this->adapter_value]->get($namespace, $multiple);

        $options['namespace'] = $namespace;

        return $this->handlePath($this->scheme_value, $located_path, $options);
    }

    /**
     * Handle located folder/file associated with URI Namespace for Resource
     *
     * @param   string $scheme_value
     * @param   string $located_path
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0.0
     */
    protected function handlePath($scheme_value, $located_path, array $options = array())
    {
        $this->getScheme($scheme_value);

        if (strtolower($scheme_value) == 'query') {
            $xml            = $this->adapter_instance_array['Xml']->handlePath(
                $scheme_value,
                $located_path,
                $options
            );
            $options['xml'] = $xml;

            $this->adapter_value = 'Query';
        }

        return $this->adapter_instance_array[$this->adapter_value]->handlePath($scheme_value, $located_path, $options);
    }

    /**
     * Parse the URL
     *
     * @param   string $uri
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function parseUri($uri)
    {
        $uri = $this->setUriScheme($uri);

        $this->setUriHost($uri);
        $this->setUriUser($uri);
        $this->setUriPassword($uri);
        $this->setUriPath($uri);
        $this->setUriQuery($uri);
        $this->setUriFragment($uri);

        return $this;
    }

    /**
     * Set Uri Scheme
     *
     * @param   string $uri
     *
     * @return  string
     * @since   1.0.0
     */
    protected function setUriScheme($uri)
    {
        $scheme = parse_url($uri, PHP_URL_SCHEME);

        if ($scheme === false) {
            if (strpos($uri, ':///') === false) {
            } else {
                $scheme = substr($uri, 0, strpos($uri, ':///'));
                $uri    = substr($uri, strpos($uri, ':///') + 4, 9999);
            }
        }

        if ($scheme === false) {
            if (strpos($uri, ':/') === false) {
            } else {
                $scheme = substr($uri, 0, strpos($uri, ':/'));
                $uri    = substr($uri, strpos($uri, ':/') + 2, 9999);
            }
        }

        $this->getScheme($scheme);

        return $uri;
    }

    /**
     * Set Uri Host
     *
     * @param   string $uri
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setUriHost($uri)
    {
        $this->host = parse_url($uri, PHP_URL_HOST);

        return $this;
    }

    /**
     * Set Uri User
     *
     * @param   string $uri
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setUriUser($uri)
    {
        $this->user = parse_url($uri, PHP_URL_USER);

        return $this;
    }

    /**
     * Set Uri Password
     *
     * @param   string $uri
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setUriPassword($uri)
    {
        $this->password = parse_url($uri, PHP_URL_PASS);

        return $this;
    }

    /**
     * Set Uri Path
     *
     * @param   string $uri
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setUriPath($uri)
    {
        $this->path = parse_url($uri, PHP_URL_PATH);

        return $this;
    }

    /**
     * Set Uri Query
     *
     * @param   string $uri
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setUriQuery($uri)
    {
        $this->query = array();

        $query = parse_url($uri, PHP_URL_QUERY);
        if ($query === null || $query === false) {
            $query = '';
        }

        $temp = explode(',', $query);

        if (count($temp) > 0) {
            foreach ($temp as $item) {
                $pair = explode('=', $item);
                if (count($pair) == 2) {
                    $this->query[$pair[0]] = $pair[1];
                }
            }
        }

        return $this;
    }

    /**
     * Set Uri Fragment
     *
     * @param   string $uri
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setUriFragment($uri)
    {
        $this->fragment = parse_url($uri, PHP_URL_FRAGMENT);

        return $this;
    }
}
