<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Ordering;

use Molajo\Plugin\AbstractPlugin;


/**
 * Date Formats
 *
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class OrderingPlugin extends AbstractPlugin
{
    //@todo reorder on delete, too

    /**
     * Pre-create processing
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeCreate()
    {

        $field = $this->getField('ordering');

        if ($field === false) {
            $fieldValue = false;
        } else {
            $fieldValue = $this->getFieldValue($field);
        }

        if ((int)$fieldValue > 0) {
            return true;
        }

        $newFieldValue = '';

        if ($fieldValue === false
            || (int)$fieldValue == 0
        ) {

            $controller_class_namespace = $this->controller_namespace;
            $controller                 = new $controller_class_namespace();
            $controller->getModelRegistry(
                $this->get('model_type', '', 'parameters'),
                $this->get('model_name', '', 'parameters'),
                1
            );

            $primary_prefix = $controller->set('primary_prefix', 0, 'model_registry');

            $catalog_type_idField = $this->getField('catalog_type_id');
            $catalog_type_id      = $this->getFieldValue($catalog_type_idField);

            $controller->model->query->select(
                'max(' . $this->db->qn($primary_prefix) . '.' . $this->db->qn('ordering') . ')'
            );
            $controller->model->query->where(
                $this->db->qn($primary_prefix) . '.' . $this->db->qn('catalog_type_id')
                . ' = ' . (int)$catalog_type_id
            );

            $controller->set('use_special_joins', 0, 'model_registry');
            $controller->set('check_view_level_access', 0, 'model_registry');
            $controller->set('process_plugins', 0, 'model_registry');
            $controller->set('get_customfields', 0, 'model_registry');

            $ordering = $controller->getData('result');

            $newFieldValue = (int)$ordering + 1;

            $this->saveField($field, 'ordering', $newFieldValue);

        }

        return true;
    }
}
