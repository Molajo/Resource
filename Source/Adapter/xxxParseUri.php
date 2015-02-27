<?php
/**
 * ParseUri
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Adapter;

use CommonApi\Exception\RuntimeException;

/**
 * ParseUri
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
abstract class ParseUri extends Base
{

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
        $this->setUriHost($uri);
        $this->setUriUser($uri);
        $this->setUriPassword($uri);
        $this->setUriPath($uri);
        $this->setUriQuery($uri);
        $this->setUriFragment($uri);

        return $this;
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
                if (count($pair) === 2) {
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
