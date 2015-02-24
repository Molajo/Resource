<?php
/**
 * Base
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Proxy;

use CommonApi\Resource\SchemeInterface;
use CommonApi\Resource\ResourceInterface;

/**
 * Base
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class Base
{
    /**
     * Scheme from Request
     *
     * @var    string
     * @since  1.0.0
     */
    protected $scheme_value;

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
     * Scheme Properties
     *
     * @var    object
     * @since  1.0.0
     */
    protected $scheme_properties;

    /**
     * Scheme Instance
     *
     * @var    object  CommonApi\Resource\SchemeInterface
     * @since  1.0.0
     */
    protected $scheme;

    /**
     * Adapter Instances
     *
     * @var    array  Contains set of CommonApi\Resource\ResourceInterface instances
     * @since  1.0.0
     */
    protected $adapter_instance_array = array();

    /**
     * Adapter Value
     *
     * @var
     * @since  1.0.0
     */
    protected $adapter_value;

    /**
     * Constructor
     *
     * @param  SchemeInterface $scheme
     * @param  array           $adapter_instance_array
     *
     * @since  1.0.0
     */
    public function __construct(
        SchemeInterface $scheme,
        array $adapter_instance_array = array()

    ) {
        $this->scheme                 = $scheme;

        foreach ($adapter_instance_array as $adapter => $adapter_instance) {
            $this->setAdapterInstance($adapter, $adapter_instance);
        }
    }

    /**
     * Set Adapter Instance
     *
     * @param   string  $adapter_name
     * @param   object  /CommonApi/Resource/ResourceInterface
     *
     * @return  $this
     * @since   1.0.0
     */
    public function setAdapterInstance($adapter_name, ResourceInterface $adapter_instance)
    {
        if ($adapter_instance instanceof ResourceInterface) {
            $this->adapter_instance_array[$adapter_name] = $adapter_instance;
        }

        return $this;
    }
}
