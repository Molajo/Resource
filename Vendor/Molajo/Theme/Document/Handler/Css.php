<?php
/**
 * Document CSS
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Theme\Handler;

use stdClass;

use Exception;
use Molajo\Theme\Exception\DocumentException;

use Molajo\Theme\Api\DocumentInterface;

/**
 * The Asset Service is used within the Theme Service, Plugins, and Mvc classes to indicate
 * Asset files, such as CSS, JS, and Links to loaded during the Head and Defer Include Template
 * Rendering Process.
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Css extends AbstractHandler
{
    /**
     * Return results for document
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  DocumentException
     */
    public function get($key, $default)
    {
        return parent::get($key, $default);
    }

    /**
     * Set the value of a key
     *
     * @param   string $key
     * @param   array  $options
     *
     * @return  object
     * @since   1.0
     * @throws  DocumentException
     */
    public function set($key, $options = array())
    {
        return parent::set($key, $options);
    }

    /**
     * Remove item
     *
     * @param   string $key
     *
     * @return  mixed
     * @since   1.0
     * @throws  DocumentException
     */
    public function remove($key)
    {
        return parent::remove($key);
    }
}
