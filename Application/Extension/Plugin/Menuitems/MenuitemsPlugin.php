<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Menuitems;

use Molajo\Plugin\AbstractPlugin;


/**
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class MenuitemsPlugin extends AbstractPlugin
{
    /**
     * Generates list of Menus and Menuitems for use in Datalists
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

        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry('System', 'Menuitems', 1);

        $controller->set('check_view_level_access', 1, 'model_registry');
        $controller->set('model_offset', 0, 'model_registry');
        $controller->set('model_count', 999999, 'model_registry');
        $controller->set('get_customfields', 2, 'model_registry');
        $controller->set('use_special_joins', 1, 'model_registry');
        $controller->set('process_plugins', 0, 'model_registry');
        $controller->set('status', 1, 'model_registry');

        $controller->model->query->select(
            $controller->model->database->qn($controller->get('primary_prefix', 'a', 'model_registry'))
            . '.' . $controller->model->database->qn('title')
        );
        $controller->model->query->select(
            $controller->model->database->qn($controller->get('primary_prefix', 'a', 'model_registry'))
            . '.' . $controller->model->database->qn('id')
        );
        $controller->model->query->select(
            $controller->model->database->qn($controller->get('primary_prefix', 'a', 'model_registry'))
            . '.' . $controller->model->database->qn('lvl')
        );

        $controller->model->query->where(
            $controller->model->database->qn($controller->get('primary_prefix', 'a', 'model_registry'))
            . '.' . $controller->model->database->qn('status')
            . ' IN (0,1,2)'
        );
        $controller->model->query->where(
            $controller->model->database->qn($controller->get('primary_prefix', 'a', 'model_registry'))
            . '.' . $controller->model->database->qn('catalog_type_id')
            . ' = ' . CATALOG_TYPE_MENUITEM
        );

        $controller->model->query->order(
            $controller->model->database->qn($controller->get('primary_prefix', 'a', 'model_registry'))
            . '.' . $controller->model->database->qn('root') . ', '
            . $controller->model->database->qn($controller->get('primary_prefix', 'a', 'model_registry'))
            . '.' . $controller->model->database->qn('lft')
        );

        $controller->set('model_offset', 0, 'model_registry');
        $controller->set('model_count', 99999, 'model_registry');

        $temp_query_results = $controller->getData('list');

        $menuitems = array();
        foreach ($temp_query_results as $item) {
            $temp_row = new \stdClass();

            $name = $item->title;
            $lvl  = (int)$item->lvl - 1;

            if ($lvl > 0) {
                for ($i = 0; $i < $lvl; $i ++) {
                    $name = ' ..' . $name;
                }
            }

            $temp_row->id    = $item->id;
            $temp_row->value = trim($name);

            $menuitems[] = $temp_row;
        }

        $this->registry->set('Datalist', 'Menuitems', $menuitems);

        return true;
    }
}
