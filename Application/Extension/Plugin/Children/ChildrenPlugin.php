<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Children;

use Molajo\Plugin\AbstractPlugin;

/**
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class ChildrenPlugin extends AbstractPlugin
{
//moved from read model
    /**
     * Append additional rows of child data as defined by the Model Registry
     *
     * @param   bool    $children
     * @param   integer $id
     * @param   array   $query_results
     *
     * @return  mixed
     * @since   1.0
     *
     * @return  object
     */

    //todo -- move this to onafterquery
    public function addItemChildren($children, $id, $query_results)
    {
        if ((int)$this->getModelRegistry('get_item_children', 1) == 1) {

            $children = $this->getModelRegistry('children', array());

            if (count($children) > 0) {
                $row = $this->model->addItemChildren(
                    $children,
                    $this->model_registry['primary_key_value'],
                    $row
                );
            }
        }


        foreach ($children as $child) {

            $model_name = (string)$child['name'];
            $model_name = ucfirst(strtolower($model_name));

            $model_type = (string)$child['type'];
            $model_type = ucfirst(strtolower($model_type));

            $options               = array();
            $options['model_name'] = $model_name;
            $options['model_type'] = $model_type;

//            $getService = $this->getService;
//            $controller = $this->dependencies['Controllerread', $options];

            $this->setModelRegistry('primary_key_value', (int)$id);
            $this->setModelRegistry('get_customfields', 2);
            $this->setModelRegistry('use_special_joins', 1);
            $this->setModelRegistry('process_plugins', 1);
            $this->setModelRegistry('query_object', 'list');

            $join              = (string)$child['join'];
            $joinPrimaryPrefix = $controller->getModelRegistry('primary_prefix', 'a');

            $controller->model->query->where(
                $controller->model->database->qn($joinPrimaryPrefix . '.' . $join)
                . ' = ' . (int)$id
            );

            $results = $controller->getData();

            $query_results->$model_name = $results;

            unset ($controller);
        }

        return $query_results;
    }
}
