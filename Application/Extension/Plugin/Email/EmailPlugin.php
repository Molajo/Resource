<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Email;

use Molajo\Plugin\AbstractPlugin;


/**
 * Email
 *
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class EmailPlugin extends AbstractPlugin
{
    /**
     * After-read processing
     *
     * Retrieves Author Information for Item
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterRead()
    {
        $fields = $this->retrieveFieldsByType('email');

        if (is_array($fields) && count($fields) > 0) {

            foreach ($fields as $field) {

                $name     = $field['name'];
                $new_name = $name . '_' . 'obfuscated';

                /** Retrieves the actual field value from the 'normal' or special field */
                $fieldValue = $this->getFieldValue($field);

                if ($fieldValue === false) {
                } else {

                    $newFieldValue = Services::Url()->obfuscateEmail($fieldValue);

                    if ($newFieldValue === false) {
                    } else {

                        if (strtolower($this->get('model_query_object', '', 'parameters')) == 'item') {
                        } else {
                            return true;
                        }
                        /** Creates the new 'normal' or special field and populates the value */
                        $this->saveField(null, $new_name, $newFieldValue);
                    }
                }
            }
        }

        return true;
    }

    /**
     * Pre-update processing
     *
     * @param   $this ->row
     * @param   $model
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeUpdate()
    {
        return false;
    }
}
