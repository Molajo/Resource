<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Publishedstatus;

use Molajo\Plugin\AbstractPlugin;


/**
 * Published Status
 *
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class PublishedstatusPlugin extends AbstractPlugin
{
    /**
     * Pre-read processing
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeRead()
    {
        if ($this->get('data_object', '', 'model_registry') == 'Database') {
        } else {
            return true;;
        }

        $field = $this->getField('start_publishing_datetime');
        if ($field === false) {
            return true;
        }

        $field = $this->getField('stop_publishing_datetime');
        if ($field === false) {
            return true;
        }

        $field = $this->getField('status');
        if ($field === false) {
            return true;
        }

        $primary_prefix = $this->get('primary_prefix', 0, 'model_registry');

        $this->model->query->where(
            $this->model->database->qn($primary_prefix)
            . '.' . $this->model->database->qn('status')
            . ' > ' . STATUS_UNPUBLISHED
        );

        $this->model->query->where(
            '(' . $this->model->database->qn($primary_prefix)
            . '.' . $this->model->database->qn('start_publishing_datetime')
            . ' = ' . $this->model->database->q($this->model->null_date)
            . ' OR ' . $this->model->database->qn($primary_prefix) . '.' . $this->model->database->qn(
                'start_publishing_datetime'
            )
            . ' <= ' . $this->model->database->q($this->model->now) . ')'
        );

        $this->model->query->where(
            '(' . $this->model->database->qn($primary_prefix)
            . '.' . $this->model->database->qn('stop_publishing_datetime')
            . ' = ' . $this->model->database->q($this->model->null_date)
            . ' OR ' . $this->model->database->qn($primary_prefix) . '.' . $this->model->database->qn(
                'stop_publishing_datetime'
            )
            . ' >= ' . $this->model->database->q($this->model->now) . ')'
        );

        return true;
    }

    /**
     * Post-create processing
     *
     * @param $this ->row, $model
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterCreate()
    {
        // if it is published, notify
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
        // hold status
        // if it is published (or greater) make certain published dates are ok
        // if status changes -- it should unpublished below
        return true;
    }

    /**
     * Post-update processing
     *
     * @param   $this ->row
     * @param   $model
     *
     * @return boolean
     * @since   1.0
     */
    public function onAfterUpdate()
    {
        // if it wasn't published and now is

        // is email notification enabled? are people subscribed?
        // tweets
        // pings
        return true;
    }

    public function notify()
    {
        // is email notification enabled? are people subscribed?
        // tweets
        // pings
    }
}
