<?php
/**
 * Class Resources
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resources\Handler;

use Molajo\Resources\Api\ResourceHandlerInterface;

/**
 * Class Resources
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class ClassLoader implements ResourceHandlerInterface
{
    /**
     * Locates folder/file associated with URI Namespace for Resource
     *
     * @param   string $resource
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function handlePath($located_path, array $options = array())
    {
        if (file_exists($located_path)) {
            require $located_path;

            return;
        }

        return;
    }

    /**
     * Retrieve a collection of a specific resource type (ex., all CSS files registered)
     *
     * @param   array $options
     *
     * @return  null
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function getCollection(array $options = array())
    {
        return null;
    }
}
