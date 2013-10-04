<?php
/**
 * Data Object Plugin
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Plugin\Dataobject;

use Molajo\Plugin\AbstractPlugin;


/**
 * Data Object Plugin - connects to Database
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */

class DataobjectPlugin extends AbstractPlugin
{
    /**
     * Prepares list of Dataobject Types
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterRoute()
    {
        if ($this->parameters->application->id == 2) {
        } else {
            return true;
        }

        $files = glob(BASE_FOLDER . '/Vendor' . '/Molajo' . '/Dataobject/*');

        if (count($files) === 0 || $files === false) {
            $dataobjectLists = array();
        } else {
            $dataobjectLists = $this->processFiles($files);
        }

        $resourceFiles = glob($this->get('extension_path', '', 'parameters') . '/Dataobject/*');

        if (count($resourceFiles) == 0 || $resourceFiles === false) {
            $resourceLists = array();
        } else {
            $resourceLists = $this->processFiles($resourceFiles);
        }

        $new   = array_merge($dataobjectLists, $resourceLists);
        $newer = array_unique($new);
        sort($newer);

        $dataobject = array();

        foreach ($newer as $file) {
            $temp_row        = new \stdClass();
            $temp_row->value = $file;
            $temp_row->id    = $file;
            $dataobject[]    = $temp_row;
        }

        $this->registry->set('Datalist', 'Dataobject', $dataobject);

        return true;
    }

    /**
     * Prepares list of Dataobject Lists
     *
     * @param array $files
     *
     * @return array
     * @since   1.0
     */
    protected function processFiles($files)
    {
        $fileList = array();

        foreach ($files as $file) {

            $length = strlen($file) - strlen('.xml');
            $value  = substr($file, 0, $length);

            $fileList[] = $value;
        }

        return $fileList;
    }
}
