<?php
/**
 * Folder Resources
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resources\Handler;

use Molajo\Resources\Api\ResourceHandlerInterface;

/**
 * Folder Resources
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class FolderHandler implements ResourceHandlerInterface
{
    /**
     * Handle located folder/file associated with URI Namespace for Resource
     *
     * @param   string $scheme
     * @param   string $located_path
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function handlePath($scheme, $located_path, array $options = array())
    {
        if (file_exists($located_path)) {
            return $located_path;
        }

        return false;
    }

    /**
     * Retrieve a collection of a specific handler
     *
     * @param   string $scheme
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function getCollection($scheme, array $options = array())
    {
        return;
    }
}
