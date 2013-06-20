<?php
/**
 * Resource Map Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Kernel\Locator\Api;

interface ResourceMapInterface
{
    /**
     * Create resource map of folder/file locations linking to Fully Qualified Namespaces
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Kernel\Locator\Exception\LocatorException
     */
    public function create();

    /**
     * Verify the correctness of the resource map
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\Kernel\Locator\Exception\LocatorException
     */
    public function edit();
}
