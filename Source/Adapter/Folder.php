<?php
/**
 * Folder Resource Adapter
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Adapter;

use CommonApi\Resource\ResourceInterface;

/**
 * Folder Resource Adapter
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class Folder extends NamespaceHandler implements ResourceInterface
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
        if (file_exists($located_path)) {
        } else {
            return '';
        }

        if ($this->testReturnFileList($options) === true) {
            return $this->returnFileList($located_path);
        }

        return $located_path;
    }

    /**
     * Test if list of folder files should be returned
     *
     * @param   array $options
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function testReturnFileList(array $options = array())
    {
        if (isset($options['return_file_list']) && $options['return_file_list'] === 1) {
            return true;
        }

        return false;
    }

    /**
     * Return file list
     *
     * @param   string $located_path
     *
     * @return  string
     * @since   1.0.0
     */
    protected function returnFileList($located_path)
    {
        $objects = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($located_path),
            RecursiveIteratorIterator::SELF_FIRST
        );

        $list = array();

        foreach ($objects as $file_path => $file_object) {

            $file_name      = $file_object->getFileName();
            $file_extension = $file_object->getExtension();
            $is_directory   = $file_object->isDir();

            if ($is_directory === true) {
                $list[] = $file_path;
            } elseif ($file_name === '.' || $file_name === '..') {
            } elseif ($file_extension === '') {
                $list[] = $file_path . '/' . $file_name;
            } else {
                $list[] = $file_path . '/' . $file_name . '.' . $file_extension;
            }
        }

        return $list;
    }
}
