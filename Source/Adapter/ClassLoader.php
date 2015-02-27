<?php
/**
 * Class Loader Resource Adapter
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Adapter;

use CommonApi\Exception\RuntimeException;
use CommonApi\Resource\ResourceInterface;

/**
 * Class Loader Resource Adapter
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class ClassLoader extends NamespaceHandler implements ResourceInterface
{
    /**
     * Handle located folder/file associated with URI Namespace for Resource
     *
     * @param   string|array $located_path
     * @param   array        $options
     *
     * @return  void|mixed
     * @since   1.0.0
     */
    public function handlePath($located_path, array $options = array())
    {
        if (is_file($located_path)
            && file_exists($located_path)
        ) {
            require_once $located_path;

            return;
        }

        $this->testNotFoundException($options);

        return;
    }

    /**
     * Test if file should be read and contents returned
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function testNotFoundException(array $options = array())
    {
        if (isset($options['throw_exception']) && $options['throw_exception'] === 1) {
            throw new RuntimeException('Resource Classloader could not locate: ' . $options['resource_namespace']);
        }

        return $this;
    }
}
