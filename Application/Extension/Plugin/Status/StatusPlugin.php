<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Status;

use Molajo\Plugin\AbstractPlugin;


/**
 * Status Url
 *
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class StatusPlugin extends AbstractPlugin
{
    /**
     * Provides Text for Status ID
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterRead()
    {
        if (defined('ROUTE')) {
        } else {
            return true;
        }

        $statusField = $this->getField('status');

        if ($statusField === false) {
            return true;
        }

        $status = $this->getFieldValue($statusField);

        if ($status == '2') {
            $status_name = $this->language->translate('Archived');
        } elseif ($status == '1') {
            $status_name = $this->language->translate('Published');
        } elseif ($status == '0') {
            $status_name = $this->language->translate('Unpublished');
        } elseif ($status == '-1') {
            $status_name = $this->language->translate('Trashed');
        } elseif ($status == '-2') {
            $status_name = $this->language->translate('Spammed');
        } elseif ($status == '-5') {
            $status_name = $this->language->translate('Draft');
        } elseif ($status == '-10') {
            $status_name = $this->language->translate('Version');
        } else {
            return true;
        }

        $this->saveField(null, 'status_name', $status_name);

        return true;
    }
}
