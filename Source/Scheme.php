<?php
/**
 * Scheme
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource;

use stdClass;
use CommonApi\Exception\RuntimeException;
use CommonApi\Resource\ResourceInterface;
use CommonApi\Resource\SchemeInterface;

/**
 * Scheme
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class Scheme implements SchemeInterface
{
    /**
     * Scheme array =>
     *    Scheme Name =>
     *      Extensions list
     *      Adapter
     *
     * @var    array
     * @since  1.0.0
     */
    protected $scheme_array = array();

    /**
     * Get Scheme
     *
     * @param   string $scheme_name
     *
     * @return  null|object
     * @since   1.0.0
     */
    public function getScheme($scheme_name)
    {
        if ($scheme_name === 'all') {
            return $this->scheme_array;
        }

        $scheme_name = strtolower($scheme_name);

        if (isset($this->scheme_array[$scheme_name])) {
            return $this->scheme_array[$scheme_name];
        }

        return null;
    }

    /**
     * Define scheme, allowable file extensions and adapter instance
     *
     * @param   string            $scheme_name
     * @param   ResourceInterface $adapter
     * @param   array             $extensions
     *
     * @return  $this
     * @since   1.0.0
     */
    public function setScheme($scheme_name, ResourceInterface $adapter, array $extensions = array())
    {
        $scheme = new stdClass();

        $this->setSchemeName($scheme, $scheme_name);
        $this->setSchemeAdapter($scheme, $adapter);
        $this->setFileExtensions($scheme, $extensions);

        $this->scheme_array[$scheme->name] = $scheme;

        return $this;
    }

    /**
     * Set Scheme Name
     *
     * @param   object $scheme
     * @param   string $scheme_name
     *
     * @return  object
     * @since   1.0.0
     */
    protected function setSchemeName($scheme, $scheme_name)
    {
        $scheme->name = strtolower(trim($scheme_name));

        if ($scheme_name === '') {
            throw new RuntimeException('Resource File ' . $scheme_name . ' must provide Name for each Scheme.');
        }

        return $scheme;
    }

    /**
     * Set Scheme Adapter
     *
     * @param   object            $scheme
     * @param   ResourceInterface $adapter
     *
     * @return  object
     * @since   1.0.0
     */
    protected function setSchemeAdapter($scheme, ResourceInterface $adapter)
    {
        $scheme->adapter = $adapter;

        return $scheme;
    }

    /**
     * Set File Extensions
     *
     * @param   object $scheme
     * @param   array  $extensions
     *
     * @return  object
     * @since   1.0.0
     */
    protected function setFileExtensions($scheme, array $extensions = array())
    {
        $scheme->include_file_extensions = $extensions;

        return $scheme;
    }
}
