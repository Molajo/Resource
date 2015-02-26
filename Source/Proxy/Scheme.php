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
use CommonApi\Resource\ResourceInterface;

/**
 * Resource Scheme Class
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
abstract class Scheme implements SchemeInterface
{
    /**
     * Requested Scheme
     *
     * @var    string
     * @since  1.0.0
     */
    protected $requested_scheme;

    /**
     * Name of requested resource adapter
     *
     * @var    object  CommonApi\Resource\ResourceInterface
     * @since  1.0.0
     */
    protected $requested_adapter;

    /**
     * Scheme Instance
     *
     * @var    object  CommonApi\Resource\SchemeInterface
     * @since  1.0.0
     */
    protected $scheme;

    /**
     * Constructor
     *
     * @param  SchemeInterface $scheme
     * @param  array           $adapter_instance_array
     *
     * @since  1.0.0
     */
    public function __construct(
        SchemeInterface $scheme
    ) {
        $this->scheme = $scheme;
    }

    /**
     * Define scheme, allowable file extensions and adapter instance
     *
     * @param   string             $scheme_name
     * @param   ResourceInterface  $adapter
     * @param   array              $extensions
     *
     * @return  $this
     * @since   1.0.0
     */
    public function setScheme($scheme_name, ResourceInterface $adapter, array $extensions = array())
    {
        $this->scheme->setScheme($scheme_name, $adapter, $extensions);

        return $this;
    }

    /**
     * Get Scheme
     *
     * @param   string $scheme_name
     *
     * @return  object
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function getScheme($scheme_name)
    {
        $this->requested_scheme = ucfirst(strtolower($scheme_name));

        $response = $this->scheme->getScheme($this->requested_scheme);

        if ($response === null) {
            throw new RuntimeException('Resource getScheme Scheme not found for request: ' . $this->requested_scheme);
        }

        $this->requested_adapter = $response->adapter;

        return $this;
    }
}
