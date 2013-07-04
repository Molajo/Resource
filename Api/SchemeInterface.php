<?php
/**
 * Scheme Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resources\Api;

/**
 * Resources Scheme Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
interface SchemeInterface
{
    /**
     * Get Scheme (or all schemes)
     *
     * @param   string $scheme
     *
     * @return  object|array
     * @since   1.0
     */
    public function getScheme($scheme = '');

    /**
     * Define Scheme, associated Handler and allowable file extensions (empty array means any extension allowed)
     *
     * @param   string $scheme
     * @param   string $handler
     * @param   array  $extensions
     * @param   bool   $replace
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function setScheme($scheme_name, $handler = 'File', array $extensions = array(), $replace = false);
}
