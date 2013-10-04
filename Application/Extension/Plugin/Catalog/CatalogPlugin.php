<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Catalog;

use Molajo\Plugin\AbstractPlugin;
use Molajo\Controller\CreateController;


/**
 * Catalog
 *
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class CatalogPlugin extends AbstractPlugin
{
    /**
     * Generates Catalog Datalist
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeInclude()
    {
        if ($this->parameters->application->id == 2) {
        } else {
            return true;
        }

        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry('datasource', 'Catalog', 1);

        $controller->set('get_customfields', 0, 'model_registry');
        $controller->set('use_special_joins', 0, 'model_registry');
        $controller->set('process_plugins', 0, 'model_registry');

        $controller->model->query->select(
            $controller->model->database->qn($controller->get('primary_prefix', 'a', 'model_registry'))
            . '.' . $controller->model->database->qn('id')
        );
        $controller->model->query->select(
            $controller->model->database->qn($controller->get('primary_prefix', 'a', 'model_registry'))
            . '.' . $controller->model->database->qn('sef_request')
            . ' AS value '
        );

        $controller->model->query->where(
            $controller->model->database->qn($controller->get('primary_prefix', 'a', 'model_registry'))
            . '.' . $controller->model->database->qn('redirect_to_id')
            . ' = 0'
        );
        $controller->model->query->where(
            $controller->model->database->qn($controller->get('primary_prefix', 'a', 'model_registry'))
            . '.' . $controller->model->database->qn('enabled')
            . ' = 1'
        );
        $controller->model->query->where(
            $controller->model->database->qn($controller->get('primary_prefix', 'a', 'model_registry'))
            . '.' . $controller->model->database->qn('page_type')
            . ' <> '
            . $controller->model->database->q('Link')
        );
        $controller->model->query->where(
            $controller->model->database->qn($controller->get('primary_prefix', 'a', 'model_registry'))
            . '.' . $controller->model->database->qn('catalog_type_id')
            . ' = ' . CATALOG_TYPE_MENUITEM
            . ' OR ' .
            $controller->model->database->qn($controller->get('primary_prefix', 'a', 'model_registry'))
            . '.' . $controller->model->database->qn('catalog_type_id')
            . ' > ' . CATALOG_TYPE_TAG
        );
        $controller->model->query->where(
            $controller->model->database->qn($controller->get('primary_prefix', 'a', 'model_registry'))
            . '.' . $controller->model->database->qn('application_id')
            . ' = ' . (int)$this->parameters->application->id
        );

        $controller->model->query->order(
            $controller->model->database->qn($controller->get('primary_prefix', 'a', 'model_registry'))
            . '.' . $controller->model->database->qn('sef_request')
        );

        $controller->set('model_offset', 0, 'model_registry');
        $controller->set('model_count', 99999, 'model_registry');

        $temp_query_results = $controller->getData(distinct);

        $catalogArray = array();

        $application_home_catalog_id =
            (int)$this->application->get('application_home_catalog_id');

        if ($application_home_catalog_id === 0) {
        } else {
            if (count($temp_query_results) == 0 || $temp_query_results === false) {
            } else {

                foreach ($temp_query_results as $item) {
                    if ($item->id == $application_home_catalog_id) {
                        $item->value    = trim($item->value . ' ' . $this->language->translate('Home'));
                        $catalogArray[] = $item;
                    } elseif (trim($item->value) == '' || $item->value === null) {
                        unset ($item);
                    } else {
                        $catalogArray[] = $item;
                    }
                }
            }
        }

        $this->registry->set('Datalist', 'Catalog', $catalogArray);

        return true;
    }

    /**
     * Post-create processing
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterCreate()
    {
        $id = $this->row->id;
        if ((int)$id == 0) {
            return false;
        }

        /** Catalog Activity: fields populated by Catalog Activity plugins */
        if ($this->application->get('log_user_update_activity', 1) == 1) {
            $results = $this->logUserActivity($id, $this->registry->get('Actions', 'create'));
            if ($results === false) {
                return false;
            }
        }

        if ($this->application->get('log_catalog_update_activity', 1) == 1) {
            $results = $this->logCatalogActivity($id, $this->registry->get('Actions', 'create'));
            if ($results === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Post-update processing
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterUpdate()
    {
        if ($this->application->get('log_user_update_activity', 1) == 1) {
            $results = $this->logUserActivity(
                $this->row->id,
                $this->registry->get('Actions', 'delete')
            );
            if ($results === false) {
                return false;
            }
        }

        if ($this->application->get('log_catalog_update_activity', 1) == 1) {
            $results = $this->logCatalogActivity(
                $this->row->id,
                $this->registry->get('Actions', 'delete')
            );
            if ($results === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Pre-update processing
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeUpdate()
    {
        return true; // only redirect id
    }

    /**
     * Pre-delete processing
     *
     * @param   $this ->row
     * @param   $model
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeDelete()
    {
        /** @todo - fix empty setModelRegistry */
        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry('x', 'y', 1);

        $sql = 'DELETE FROM ' . $controller->model->database->qn('#__catalog_categories');
        $sql .= ' WHERE ' . $controller->model->database->qn('catalog_id') . ' = ' . (int)$this->row->id;
        $controller->model->database->setQueryPermissions($sql);
        $controller->model->database->execute();

        $sql = 'DELETE FROM ' . $controller->model->database->qn('#__catalog_activity');
        $sql .= ' WHERE ' . $controller->model->database->qn('catalog_id') . ' = ' . (int)$this->row->id;
        $controller->model->database->setQueryPermissions($sql);
        $controller->model->database->execute();

        return true;
    }

    /**
     * Post-delete processing
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterDelete()
    {
        //how to get id - referential integrity?
        /**
         * if ($this->application->get('log_user_update_activity', 1) == 1) {
         * $this->logUserActivity($id, $this->registry->get('Actions', 'delete'));
         * }
         * if ($this->application->get('log_catalog_update_activity', 1) == 1) {
         * $this->logCatalogActivity($id, $this->registry->get('Actions', 'delete'));
         * }
         */

        return true;
    }

    /**
     * Log user updates
     *
     * @return boolean
     * @since   1.0
     */
    public function logUserActivity($id, $action_id)
    {
        $data              = new \stdClass();
        $data->model_name  = 'UserActivity';
        $data->model_table = 'datasource';
        $data->catalog_id  = $id;
        $data->action_id   = $action_id;

        $controller       = new CreateController();
        $controller->data = $data;
        $user_activity_id = $controller->execute();
        if ($user_activity_id === false) {
            //install failed
            return false;
        }

        return true; // only redirect id
    }

    /**
     * Pre-update processing
     *
     * @return boolean
     * @since   1.0
     */
    public function logCatalogActivity($id, $action_id)
    {
        $data              = new \stdClass();
        $data->model_name  = 'CatalogActivity';
        $data->model_table = 'datasource';
        $data->catalog_id  = $id;
        $data->action_id   = $action_id;

        $controller          = new CreateController();
        $controller->data    = $data;
        $catalog_activity_id = $controller->execute();
        if ($catalog_activity_id === false) {
            //install failed
            return false;
        }

        return true; // only redirect id
    }
}
