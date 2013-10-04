<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Paging;

use Molajo\Plugin\AbstractPlugin;


/**
 * Paging
 *
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class PagingPlugin extends AbstractPlugin
{
    /**
     * After reading, calculate paging data
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeInclude()
    {
        return;
        if (strtolower($this->get('template_view_path_node', '', 'parameters')) == 'paging') {
        } else {
            return true;
        }

        /** initialise */
        $url = $this->registry->get('Page', 'page_url');

        /** current_page */
        $current_page = ($this->get('model_offset') / $this->get('model_count', 0, 'parameters')) + 1;
        if ($this->get('model_offset') % $this->get('model_count', 0, 'parameters')) {
            $current_page ++;
        }

        /** previous page */
        if ((int)$current_page > 1) {
            $previous_page = (int)$current_page - 1;
            $prev_link     = $url . '/page/' . (int)$previous_page;
        } else {
            $previous_page = 0;
            $prev_link     = '';
        }

        /** next page */
        if ((int)$total_pages > (int)$current_page) {
            $next_page = $current_page + 1;
            $next_link = $url . '/page/' . $next_page;
        } else {
            $next_page = 0;
            $next_link = '';
        }

        /** Paging */
        $temp_row = new \stdClass();

        $temp_row->total_items          = (int)$this->get('pagination_total');
        $temp_row->total_items_per_page = (int)$this->get('model_count', 0, 'parameters');

        $temp_row->first_page = $first_page;
        $temp_row->first_link = $first_link;

        $temp_row->previous_page = $previous_page;
        $temp_row->prev_link     = $prev_link;

        $temp_row->next_page = $next_page;
        $temp_row->next_link = $next_link;

        $temp_row->last_page = $last_page;
        $temp_row->last_link = $last_link;

        $temp_query_results[] = $temp_row;

        $this->registry->set('Primary', 'Paging', $temp_query_results);
    }

    /**
     * Prev and Next Paging for Item Pages
     *
     * @return bool
     */
    protected function itemPaging()
    {
        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();

        $results = $controller->getModelRegistry(
            $this->get('model_type', 'datasource'),
            $this->get('model_name', '', 'parameters'),
            1
        );

        $controller->setDataobject();
        $controller->connectDatabase();

        $controller->set('get_customfields', 0, 'model_registry');
        $controller->set('use_special_joins', 0, 'model_registry');
        $controller->set('process_plugins', 0, 'model_registry');
        $controller->set('get_item_children', 0, 'model_registry');

        $controller->model->query->select(
            $controller->model->database->qn('a')
            . '.' . $controller->model->database->qn($controller->get('primary_key', 'id', 'model_registry'))
        );

        $controller->model->query->select(
            $controller->model->database->qn('a')
            . '.' . $controller->model->database->qn($controller->get('name_key', 'title'))
        );

        $controller->model->query->where(
            $controller->model->database->qn('a')
            . '.' . $controller->model->database->qn(
                $controller->get('primary_key', 'id', 'model_registry')
                . ' = ' . (int)$this->parameters->catalog->source_id
            )
        );

//@todo ordering
        $item = $controller->getData('item');

        $this->model_registry_name = ucfirst(strtolower($this->get('model_name', '', 'parameters')))
            . ucfirst(strtolower($this->get('model_type', 'datasource')));

        if ($item === false || count($item) == 0) {
            return false;
        }
    }
}
