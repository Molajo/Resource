<?php
/**
 * Resource Class Loader Class
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Proxy;

use CommonApi\Resource\ClassLoaderInterface;

/**
 * Resource Class Loader
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class ClassLoader extends Scheme implements ClassLoaderInterface
{
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
        spl_autoload_register(array($this, 'locateNamespace'), true, $prepend);

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
        spl_autoload_unregister(array($this, 'locateNamespace'));

        return $this;
    }
}
