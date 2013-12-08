<?php
/**
 * Asset Handler
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Handler;

use CommonApi\Resource\HandlerInterface;

/**
 * Asset Handler
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class AssetHandler extends AbstractHandler implements HandlerInterface
{
    /**
     * Handle requires located file
     *
     * @param   string $scheme
     * @param   string $located_path
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0
     */
    public function handlePath($scheme, $located_path, array $options = array())
    {
        if (is_file($located_path)
            && file_exists($located_path)
        ) {
            return $located_path;
        }

        return false;
    }
}
