<?php
/**
 * Class Handler - Class Loader
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Handler;

use CommonApi\Resource\HandlerInterface;

/**
 * Class Handler
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class ClassHandler extends AbstractHandler implements HandlerInterface
{
    /**
     * Constructor
     *
     * @param  string $base_path
     * @param  array  $resource_map
     * @param  array  $namespace_prefixes
     * @param  array  $valid_file_extensions
     *
     * @since  1.0
     */
    public function __construct(
        $base_path = null,
        array $resource_map = array(),
        array $namespace_prefixes = array(),
        array $valid_file_extensions = array()
    ) {
        parent::__construct(
            $base_path,
            $resource_map,
            $namespace_prefixes,
            $valid_file_extensions
        );
    }

    /**
     * Handle requires located file
     *
     * @param   string $scheme
     * @param   string $located_path
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     */
    public function handlePath($scheme, $located_path, array $options = array())
    {
        if (is_file($located_path)
            && file_exists($located_path)
        ) {
            require_once $located_path;
        }

        return;
    }
}
