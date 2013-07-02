<?php
/**
 * Resource Map Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resources\Api;

interface ResourceMapInterface
{
    /**
     * Get the resource map
     *
     * @return  $this
     * @since   1.0
     */
    public function getMap();

    /**
     * Create resource map of folder/file locations and Fully Qualified Namespaces
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function createMap();

    /**
     * Verify the correctness of the resource map
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function editMap();
}
