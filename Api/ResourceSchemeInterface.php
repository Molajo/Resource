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
interface ResourceSchemeInterface
{
    /**
     * Add Scheme to Associate with Resource
     *
     * @param   string $scheme
     * @param   string $handler
     * @param   array  $extensions
     * @param   bool   $replace
     *
     * @return  $this|void
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function addScheme($scheme, $handler = 'File', array $extensions = array(), $replace = false);
}
