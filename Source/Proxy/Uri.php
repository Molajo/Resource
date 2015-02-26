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
     * Host
     *
     * @var    string
     * @since  1.0.0
     */
    protected $host;

    /**
     * User
     *
     * @var    string
     * @since  1.0.0
     */
    protected $user;

    /**
     * Password
     *
     * @var    string
     * @since  1.0.0
     */
    protected $password;

    /**
     * Path
     *
     * @var    string
     * @since  1.0.0
     */
    protected $path;

    /**
     * Query
     *
     * @var    string
     * @since  1.0.0
     */
    protected $query;

    /**
     * Fragment
     *
     * @var    string
     * @since  1.0.0
     */
    protected $fragment;

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
        $this->parseUri($uri_namespace);

        $this->requested_scheme = 'file';

        $this->getScheme($this->requested_scheme);

        return $this->adapter_instance_array[$this->requested_adapter]->get($uri_namespace);
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

        $located_path = $this->adapter_instance_array[$this->requested_adapter]->get($namespace, $multiple);

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
