<?php
/**
 * Asset Resource Adapter
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Adapter;

use CommonApi\Resource\AdapterInterface;

/**
 * Asset Resource Adapter
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class Asset extends AbstractAdapter implements AdapterInterface
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
