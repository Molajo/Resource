<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Datalist;

use Molajo\Plugin\AbstractPlugin;


/**
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class DatalistPlugin extends AbstractPlugin
{
    /**
     * Prepares list of Datalist Lists
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

        if ($this->registry->exists('Datalist', 'Datalists')) {
            return true;
        }

        $files = glob(BASE_FOLDER . '/Vendor' . '/Molajo' . '/Mvc' . '/Model/Datalist' . '/*');

        if (count($files) === 0 || $files === false) {
            $dataLists = array();
        } else {
            $dataLists = $this->processFiles($files);
        }

        $resourceFiles = glob($this->get('extension_path', '', 'parameters') . '/Model/Datalist/*');

        if (count($resourceFiles) == 0 || $resourceFiles === false) {
            $resourceLists = array();
        } else {
            $resourceLists = $this->processFiles($resourceFiles);
        }

        $new   = array_merge($dataLists, $resourceLists);
        $newer = array_unique($new);
        sort($newer);

        $datalist = array();

        foreach ($newer as $file) {
            $temp_row        = new \stdClass();
            $temp_row->value = $file;
            $temp_row->id    = $file;
            $datalist[]      = $temp_row;
        }

        $this->registry->set('Datalist', 'Datalists', $datalist);

        return true;
    }

    /**
     * Prepares list of Datalist Lists
     *
     * @return boolean
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
