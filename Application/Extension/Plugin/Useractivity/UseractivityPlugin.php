<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Useractivity;

use Molajo\Plugin\AbstractPlugin;


/**
 * Useractivity
 *
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class UseractivityPlugin extends AbstractPlugin
{

    /**
     * onAfterRead
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
        if ($this->get('criteria_log_user_view_activity', 0) == 1) {
            return $this->setUserActivityLog();
        }

        return;
    }

    /**
     * onAfterCreate
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterCreate()
    {
        if ($this->get('criteria_log_user_activity_create', 0) == 1) {
            return $this->setUserActivityLog();
        }

        return true;
    }

    /**
     * onAfterUpdate
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterUpdate()
    {
        if ($this->get('criteria_log_user_update_activity', 0) == 1) {
            return $this->setUserActivityLog();
        }

        return true;
    }

    /**
     * onAfterDelete
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterDelete()
    {
        if ($this->get('criteria_log_user_activity_delete', 0) == 1) {
            return $this->setUserActivityLog();
        }

        return true;
    }

    /**
     * onAfterRead
     *
     * User Activity
     *
     * @return boolean
     * @since   1.0
     */
    public function setUserActivityLog()
    {
        /** Retrieve Key for Action  */
        $action_id = $this->registry->get(
            'Actions',
            $this->get('action', 'read')
        );

        /** Retrieve User Data  */
        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry('datasource', 'UserActivity', 1);

        $controller->set('user_id', $this->registry->set('User', 'id'));
        $controller->set('action_id', $action_id, 'parameters');
        $controller->set('catalog_id', $this->row->catalog_id, 'parameters');
        $controller->set('activity_datetime', null, 'parameters');

        $results = $controller->getData('create');

        return true;
    }
}
