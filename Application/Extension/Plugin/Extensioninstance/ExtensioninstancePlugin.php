<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Extensioninstance;

use Molajo\Plugin\AbstractPlugin;
use Molajo\Controller;
use Molajo\Controller\CreateController;
use Molajo\Controller\DeleteController;
use Molajo\Service;


/**
 * Extension Instances
 *
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class ExtensioninstancePlugin extends AbstractPlugin
{
    /**
     * onBeforeCreate processing
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeCreate()
    {
        if ($this->row->catalog_type_id >= CATALOG_TYPE_BEGIN
            AND $this->row->catalog_type_id <= CATALOG_TYPE_END
        ) {
        } else {
            return true;
        }

        /** Check Permissions */

        /** Check if the Extension Instance already exists */
        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry('datasource', 'ExtensionInstances');
        $controller->setDataobject();
        $controller->connectDatabase();

        $primary_prefix = $controller->get('primary_prefix', 'a', 'model_registry');

        $controller->set('get_customfields', 0, 'model_registry');
        $controller->set('get_item_children', 0, 'model_registry');
        $controller->set('use_special_joins', 0, 'model_registry');
        $controller->set('check_view_level_access', 0, 'model_registry');

        $controller->model->query->select(
            $controller->model->database->qn($primary_prefix) . '.' . $controller->model->database->qn('id')
        );
        $controller->model->query->where(
            $controller->model->database->qn($primary_prefix) . '.' . $controller->model->database->qn('title')
            . ' = ' . $controller->model->database->q($this->row->title)
        );
        $controller->model->query->where(
            $controller->model->database->qn($primary_prefix) . '.' . $controller->model->database->qn(
                'catalog_type_id'
            )
            . ' = ' . (int)$this->row->catalog_type_id
        );

        $id = $controller->getData('result');

        if ((int)$id > 0) {
            //name already exists
            return false;
        }

        /** Next, see if the Extension node exists */
        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry('datasource', 'Extensions');
        $controller->setDataobject();
        $controller->connectDatabase();

        $controller->model->query->select($controller->model->database->qn('a.id'));
        $controller->model->query->where(
            $controller->model->database->qn('a.name')
            . ' = ' . $controller->model->database->q($this->row->title)
        );
        $controller->model->query->where(
            $controller->model->database->qn('a.catalog_type_id')
            . ' = ' . (int)$this->row->catalog_type_id
        );

        $item = $controller->getData('item');

        if ($item === false) {
        } else {
            $this->row->extension_id    = $item->id;
            $this->row->catalog_type_id = $item->catalog_type_id;

            return;
        }

        /** If Extension Node does not exist */

        //@todo decide if another query is warranted for verifying existence of catalog type

        /** Create a new Catalog Type */
        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry('x', 'y');
        $controller->setDataobject();

        /** Catalog Types */
        $sql = 'INSERT INTO ' . $controller->model->database->qn('#__catalog_types');
        $sql .= ' VALUES ( null, '
            . $controller->model->database->q($this->row->title)
            . ', 0, '
            . $controller->model->database->q($this->row->title)
            . ', ' . $controller->model->database->q('#__content') . ')';

        $controller->model->database->setQueryPermissions($sql);
        $controller->model->database->execute();

        $this->parameters->criteria_catalog_type_id = $controller->model->database->insertid();

        /** Create a new Extension Node */
        $data                  = new \stdClass();
        $data->name            = $this->row->title;
        $data->catalog_type_id = $this->row->catalog_type_id;
        $data->model_name      = 'Extensions';

        $controller       = new CreateController();
        $controller->data = $data;

        $this->row->extension_id = $controller->execute();

        if ($this->row->extension_id === false) {
            //error
            return false;
        }

        return true;
    }

    /**
     * onAfterCreate processing
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterCreate()
    {
        echo 'Catalog ID ' . $this->row->catalog_type_id . '<br />';

        if ($this->row->catalog_type_id >= CATALOG_TYPE_BEGIN
            AND $this->row->catalog_type_id <= CATALOG_TYPE_END
        ) {
        } else {
            return true;
        }

        echo 'ID ' . $this->row->id . '<br />';
        /** Extension Instance ID */
        $id = $this->row->id;
        if ((int)$id == 0) {
            return false;
        }

        /** Site Extension Instances */
        $controller = new CreateController();

        $data                        = new \stdClass();
        $data->site_id               = $parameters->site->id;
        $data->extension_instance_id = $id;
        $data->model_name            = 'SiteExtensionInstances';

        $controller->data = $data;

        $results = $controller->execute();
        if ($results === false) {
            //install failed
            return false;
        }
        echo 'results are true for site ' . '<br />';

        /** Application Extension Instances */
        $controller = new CreateController();

        $data                        = new \stdClass();
        $data->application_id        = $this->parameters->application->id;
        $data->extension_instance_id = $id;
        $data->model_name            = 'Applicationextensioninstances';

        $controller->data = $data;

        $results = $controller->execute();
        if ($results === false) {
            //install failed
            return false;
        }
        echo 'results are true for app ' . '<br />';
        /** Catalog */
        $controller = new CreateController();

        $data                        = new \stdClass();
        $data->catalog_type_id       = $this->registry->get($this->model_registry_name, 'catalog_type_id');
        $data->source_id             = $id;
        $data->view_group_id         = 1;
        $data->extension_instance_id = $id;
        $data->model_name            = 'Catalog';

        $controller->data = $data;

        $this->row->catalog_id = $controller->execute();
        if ($results === false) {
            //install failed
            return false;
        }
        echo 'results are true for catalog ' . '<br />';

        return true;
    }

    /**
     * onBeforeDelete -
     *
     * Returns false and does not delete if there is content for this extension
     *
     * Deletes ACL and catalog data for this extension to be deleted
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeDelete()
    {

        /** Only Extension Instances */
        if (isset($this->row->catalog_type_id)
            && ($this->row->catalog_type_id == CATALOG_TYPE_RESOURCE)
        ) {
        } else {
            return true;
        }

        /** Do not allow delete if there is content for this resource */
        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();

        $controller->getModelRegistry('datasource', $this->row->title);

        $results = $controller->setDataobject();
        if ($results === false) {
            return false;
        }

        $primary_prefix = $controller->get('primary_prefix', 'a', 'model_registry');

        $controller->set('get_customfields', 0, 'model_registry');
        $controller->set('get_item_children', 0, 'model_registry');
        $controller->set('use_special_joins', 0, 'model_registry');
        $controller->set('check_view_level_access', 0, 'model_registry');

        if (isset($this->parameters->criteria_catalog_type_id)) {
            $temp = (int)$this->parameters->criteria_catalog_type_id;

            $controller->model->query->where(
                $controller->model->database->qn($primary_prefix)
                . '.' . $controller->model->database->qn('catalog_type_id')
                . ' = ' . $temp
            );

            $item = $controller->getData('item');

            if ($item === false) {
            } else {
                //content exists - cannot delete
                return false;
            }
        }

        /** Delete allowed - get rid of ACL info */
        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry('x', 'y');

        $results = $controller->setDataobject();
        if ($results === false) {
            return false;
        }
        $sql = 'DELETE FROM ' . $controller->model->database->qn('#__application_extension_instances');
        $sql .= ' WHERE ' . $controller->model->database->qn('extension_instance_id') . ' = ' . (int)$this->row->id;
        $controller->model->database->setQueryPermissions($sql);
        $controller->model->database->execute();

        $sql = 'DELETE FROM ' . $controller->model->database->qn('#__site_extension_instances');
        $sql .= ' WHERE ' . $controller->model->database->qn('extension_instance_id') . ' = ' . (int)$this->row->id;
        $controller->model->database->setQueryPermissions($sql);
        $controller->model->database->execute();

        $sql = 'DELETE FROM ' . $controller->model->database->qn('#__group_permissions');
        $sql .= ' WHERE ' . $controller->model->database->qn('catalog_id') . ' = ' . (int)$this->row->catalog_id;
        $controller->model->database->setQueryPermissions($sql);
        $controller->model->database->execute();

        $sql = 'DELETE FROM ' . $controller->model->database->qn('#__view_group_permissions');
        $sql .= ' WHERE ' . $controller->model->database->qn('catalog_id') . ' = ' . (int)$this->row->catalog_id;
        $controller->model->database->setQueryPermissions($sql);
        $controller->model->database->execute();

        /** Catalog has plugins for more deletions */
        $controller = new DeleteController();
        echo 'Passing this catalog id in to Delete Controller ' . $this->row->catalog_id . '<br />';

        $data             = new \stdClass();
        $data->model_name = ucfirst(strtolower('Catalog'));
        $data->id         = $this->row->catalog_id;
        $controller->data = $data;
        $controller->set('action', 'delete');

        $id = $controller->execute();

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
        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();

        $results = $controller->getModelRegistry('datasource', 'ExtensionInstances');
        if ($results === false) {
            return false;
        }

        $results = $controller->setDataobject();
        if ($results === false) {
            return false;
        }

        $controller->set('get_customfields', 0, 'model_registry');
        $controller->set('get_item_children', 0, 'model_registry');
        $controller->set('use_special_joins', 0, 'model_registry');
        $controller->set('check_view_level_access', 0, 'model_registry');
        $controller->set('process_plugins', 0, 'model_registry');

        $controller->model->query->select('COUNT(*)');
        $controller->model->query->from($controller->model->database->qn('#__extension_instances'));
        $controller->model->query->where(
            $controller->model->database->qn('extension_id')
            . ' = ' . (int)$this->row->extension_id
        );

        $value = $controller->getData('result');

        if (empty($value) || (int)$value == 0) {
        } else {
            /** do not delete - more instances remain */

            return true;
        }

        /** Delete orphan node */
        $controller = new DeleteController();

        $data             = new \stdClass();
        $data->model_name = ucfirst(strtolower('Extensions'));
        $data->id         = $this->row->extension_id;
        $controller->data = $data;
        $controller->set('action', 'delete');

        $controller->execute();

        return true;
    }
}
