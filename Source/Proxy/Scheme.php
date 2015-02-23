<?php
/**
 * Resource Scheme Class
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Proxy;

use CommonApi\Exception\RuntimeException;
use CommonApi\Resource\SchemeInterface;

/**
 * Resource Scheme Class
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class Scheme extends ClassLoader implements SchemeInterface
{
    /**
     * Define Scheme, Adapter and allowable file extensions (empty array means all file extensions allowed)
     *
     * @param   string $scheme_name
     * @param   string $adapter_name
     * @param   array  $extensions
     * @param   bool   $replace
     *
     * @return  $this
     * @since   1.0.0
     */
    public function setScheme($scheme_name, $adapter_name = 'File', array $extensions = array(), $replace = false)
    {
        $this->scheme->setScheme($scheme_name, $adapter_name, $extensions, $replace);

        return $this;
    }

    /**
     * Get Scheme (or all schemes)
     *
     * @param   string $scheme
     *
     * @return  object|array
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function getScheme($scheme = '')
    {
        if ($scheme == '') {
            return $this->scheme->getScheme();
        }

        $scheme = ucfirst(strtolower($scheme));

        $this->scheme_value = $scheme;

        $this->scheme_properties = $this->scheme->getScheme($this->scheme_value);

        if ($this->scheme_properties === false) {
            throw new RuntimeException('Resource getScheme Scheme not found: ' . $this->scheme_value);
        }

        $this->adapter_value = $this->scheme_properties->adapter;

        if (isset($this->adapter_instance_array[$this->adapter_value])) {
        } else {
            echo 'in Resource Adapter ' . $this->adapter_value . ' <br />';
            echo '<pre>';
            foreach ($this->adapter_instance_array as $key => $value) {
                echo $key . '<br />';
            }
            throw new RuntimeException('Resource getScheme Adapter not found: ' . $this->adapter_value);
        }

        return $this->scheme_properties;
    }
}
