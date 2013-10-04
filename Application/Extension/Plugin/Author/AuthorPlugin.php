<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Author;

use Molajo\Plugin\AbstractPlugin;


/**
 * Author
 *
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class AuthorPlugin extends AbstractPlugin
{
    /**
     * After-read processing
     *
     * @todo    move to it's own include
     *          Retrieves Author Information for Item
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterRead()
    {
        $field = $this->getField('created_by');
        if ($field === false) {
            return true;
        }
        $append = 'created_by_';

        $fieldValue = $this->getFieldValue($field);
        if ((int)$fieldValue == 0) {
            return true;
        }

        $registry_name = $append . $fieldValue;

        if ($this->registry->exists('Template', $registry_name)) {

            $authorArray = $this->registry->get('Template', $registry_name);

            foreach ($authorArray as $key => $value) {
                $this->saveField(null, $key, $value);
            }

            return true;
        }

        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry('System', 'Users', 1);

        $controller->set('primary_key_value', (int)$fieldValue, 'model_registry');
        $controller->set('get_item_children', 0, 'model_registry');

        $item = $controller->getData('item');

        if ($item === false || count($item) == 0) {
            return false;
        }

        $authorArray = array();
        foreach (get_object_vars($item) as $key => $value) {

            if (substr($key, 0, strlen('item_')) == 'item_'
                || substr($key, 0, strlen('form_')) == 'form_'
                || substr($key, 0, strlen('list_')) == 'list_'
                || substr($key, 0, strlen('password')) == 'password'
            ) {

            } else {

                $new_field_name = $append . $key;
                $this->saveField(null, $new_field_name, $value);
                $authorArray[$new_field_name] = $value;
            }
        }

        $this->registry->set('Template', $registry_name, $authorArray);

        return true;
    }
}
