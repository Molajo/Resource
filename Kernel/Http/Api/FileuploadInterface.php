<?php
/**
 * Fileupload Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Http\Api;

use Molajo\Http\Exception\FileuploadException;

/**
 * Client Interface
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Interface FileuploadInterface
{
    /**
     * Get Client Data
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  int
     * @since   1.0
     * @throws  FileuploadException
     */
    public function get($key = null, $default = null);
}
