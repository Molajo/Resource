<?php
/**
 * Resource Class Loader
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Proxy;

use CommonApi\Resource\ClassLoaderInterface;
use CommonApi\Resource\SchemeInterface;

/**
 * Resource Class Loader
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class ClassLoader extends Base implements ClassLoaderInterface
{
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
        parent::__construct($scheme, $adapter_instance_array);

        $this->register(true);
    }

    /**
     * Registers Class Autoloader
     *
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0.0
     */
    public function register($prepend = true)
    {
        spl_autoload_register(array('Molajo\Resource\Proxy\Uri', 'locateNamespace'), true, $prepend);

        return $this;
    }

    /**
     * Unregister Class Autoloader
     *
     * @return  $this
     * @since   1.0.0
     */
    public function unregister()
    {
        spl_autoload_unregister(array('Molajo\Resource\Proxy\Uri', 'locateNamespace'));

        return $this;
    }
}
