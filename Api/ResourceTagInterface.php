<?php
/**
 * Resource Tag Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resources\Api;

/**
 * Resource Tag Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
interface ResourceTagInterface
{
    /**
     * Add Namespace and Tag(s)
     *
     * @param   string   $namespace
     * @param   array    $tag
     * @param   boolean  $replace
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function addTag($namespace, array $tag = array(), $replace = true);
}
