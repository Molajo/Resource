<?php
/**
 * File Resource Adapter
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Adapter;

use CommonApi\Resource\ResourceInterface;

/**
 * File Resource Adapter
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class File extends NamespaceHandler implements ResourceInterface
{
    /**
     * Handle located folder/file associated with URI Namespace for Resource
     *
     * @param   string $located_path
     * @param   array  $options
     *
     * @return  string
     * @since   1.0.0
     */
    public function handlePath($located_path, array $options = array())
    {
        if (file_exists($located_path)) {
        } else {
            return '';
        }

        if ($this->testReturnFileContents($options) === true) {
            return $this->returnFileContents($located_path);
        }

        return $located_path;
    }

    /**
     * Test if file should be read and contents returned
     *
     * @param   array $options
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function testReturnFileContents(array $options = array())
    {
        if (isset($options['return_contents']) && $options['return_contents'] === 1) {
            return true;
        }

        return false;
    }

    /**
     * Return file contents
     *
     * @param   string $located_path
     *
     * @return  string
     * @since   1.0.0
     */
    protected function returnFileContents($located_path)
    {
        return file_get_contents($located_path);
    }
}
