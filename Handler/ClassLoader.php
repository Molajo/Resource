<?php
/**
 * Class Locator
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Locator\Handler;

use Molajo\Locator\Api\ResourceLocatorInterface;
use Molajo\Locator\Api\LocatorInterface;
use Molajo\Locator\Handler\AbstractLocator;

/**
 * Class Locator
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class ClassLoader implements ResourceLocatorInterface
{
    /**
     * Constructor
     *
     * @since   1.0
     */
    public function __construct()
    {

    }

    /**
     * Locates folder/file associated with URI Namespace for Resource
     *
     * @param   string $resource
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
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
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function getCollection(array $options = array())
    {

    }
}
