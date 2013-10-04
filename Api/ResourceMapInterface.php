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
     * Get Resource Map
     *
     * @return  array
     * @since   1.0
     */
    public function getMap();

    /**
     * Create resource map of folder/file locations and Fully Qualified Namespaces
     *
     * @return  object
     * @since   1.0
     */
    public function createMap();

    /**
     * Verify the correctness of the resource map, returning error messages
     *
     * @return  array
     * @since   1.0
     */
    public function editMap();
}
