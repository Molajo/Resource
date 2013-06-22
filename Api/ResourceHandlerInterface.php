<?php
/**
 * Resource Handler Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Locator\Api;

interface ResourceHandlerInterface
{
    /**
     * Locates folder/file associated with URI Namespace for Resource
     *
     * @param   string $resource
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function handlePath($path, $options);

    /**
     * Retrieve a collection of a specific resource type (ex., all CSS files registered)
     *
     * @param   array $options
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function getCollection(array $options = array());
}
