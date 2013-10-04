<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Application;

use Molajo\Plugin\AbstractPlugin;

/**
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class ApplicationPlugin extends AbstractPlugin
{
    /**
     * Override Page Metadata prior to parsing document head
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeParseHead()
    {

    }

    /**
     * Prepares Page Information, such as document metadata, page and home URLs, breadcrumbs, and menus/menu items
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeParse()
    {
        $current_menuitem_id = (int)$this->get('menuitem_id', 0, 'parameters');

        $item_indicator = 0;
        if ((int)$current_menuitem_id == 0) {
            $item_indicator      = 1;
            $current_menuitem_id = (int)$this->get('parent_menu_id', 0, 'parameters');
        }

        if ((int)$current_menuitem_id == 0) {
            return true;
        }

        $this->urls();

        $this->setBreadcrumbs($current_menuitem_id);

        $this->setMenu($current_menuitem_id);

        $this->setPageTitle($item_indicator);

        $this->setPageEligibleActions();

        $this->setPageMeta();

        return true;
    }

    /**
     * Build the home and page url to be used in links
     *
     * @return boolean
     * @since   1.0
     */
    protected function urls()
    {
        $url = $this->application->get('application_base_url');
        $this->registry->set('Page', 'home_url', $url);

        $url = $this->get('request_base_url_path', '', 'parameters') . $this->get('request_url', '', 'parameters');
        $this->registry->set('Page', 'page_url', $url);
        $this->document_links->set($url, 'canonical', 'rel', array(), 1);

        $resource = $this->get('extension_name_path_node', '', 'parameters');
        $url      = $this->registry->get('Page', 'home_url') . '/' . strtolower($resource);
        $this->registry->set('Page', 'resource_url', $url);

        //@todo add links for prev and next
        return true;
    }

    /**
     * Set Breadcrumbs for the page
     *
     * @return boolean
     * @since   1.0
     */
    protected function setBreadcrumbs($current_menuitem_id)
    {
        $bread_crumbs = Services::Menu()->getMenuBreadcrumbIds($current_menuitem_id);

        $this->registry->set('Page', 'Breadcrumbs', $bread_crumbs);

        return true;
    }

    /**
     * Retrieve an array of values that represent the active menuitem ids for a specific menu
     *
     * @return boolean
     * @since   1.0
     */
    protected function setMenu($current_menu_item = 0)
    {
        $bread_crumbs = $this->registry->get('Page', 'Breadcrumbs');

        $menuname           = '';
        $temp_query_results = array();

        if ($bread_crumbs == false || count($bread_crumbs) == 0) {
            return true;
        }

        $menu_id = $bread_crumbs[0]->extension_id;

        $temp_query_results = Services::Menu()->get($menu_id, $current_menu_item, $bread_crumbs);
        if ($temp_query_results == false || count($temp_query_results) == 0) {
            $menuname = '';
        } else {
            $menuname = $temp_query_results[0]->extensions_name;
        }

        if ($menuname == '') {
            return true;
        }

        $this->registry->set('Page', $menuname, $temp_query_results);

        return true;
    }

    /**
     * Set the Header Title
     *
     * @return boolean
     * @since   1.0
     */
    protected function setPageTitle($item_indicator = 0)
    {
        $title = $this->application->get('application_name');
        if ($title == '') {
            $title = 'Molajo ' . $this->parameters->application->name;
        }
        $this->registry->set('Page', 'HeaderTitle', $title);

        $this->registry->set('Page', 'page_type', $this->get('page_type', '', 'parameters'));

        $heading1  = $this->get('criteria_title', '', 'parameters');
        $page_type = $this->get('page_type', '', 'parameters');
        if ($page_type == 'Grid') {
            $page_type = 'list';
        }

        $list_current          = 0;
        $configuration_current = 0;
        $new_current           = 0;
        if (strtolower($page_type) == 'item') {
            $new_current = 1;
        } elseif (strtolower($page_type) == PAGE_TYPE_CONFIGURATION) {
            $configuration_current = 1;
        } else {
            $list_current = 1;
        }

        $display_page_type = $this->language->translate(strtoupper($page_type));
//		$action_id = $this->get('request_action');
        $heading2 = ucfirst(strtolower($page_type));

        $this->registry->set('Page', 'heading1', $heading1);
        $this->registry->set('Page', 'heading2', $heading2);

        $resource_menu_item = array();

        $this->registry->get('Page', 'resource_url');

        $temp_row             = new \stdClass();
        $temp_row->link_text  = $this->language->translate('GRID');
        $temp_row->link       = $this->registry->get('Page', 'resource_url');
        $temp_row->current    = $list_current;
        $temp_query_results[] = $temp_row;

        $temp_row             = new \stdClass();
        $temp_row->link_text  = $this->language->translate('Configuration');
        $temp_row->link       = $this->registry->get('Page', 'resource_url') . '/' . 'Configuration';
        $temp_row->current    = $configuration_current;
        $temp_query_results[] = $temp_row;

        $temp_row             = new \stdClass();
        $temp_row->link_text  = $this->language->translate('NEW');
        $temp_row->link       = $this->registry->get('Page', 'resource_url') . '/' . 'new';
        $temp_row->current    = $new_current;
        $temp_query_results[] = $temp_row;

        $this->registry->set('Page', 'PageSubmenu', $temp_query_results);

        return true;
    }

    /**
     * Prepares Page Title and Actions for Rendering
     *
     * @return boolean
     * @since   1.0
     */
    protected function setPageEligibleActions()
    {
        if ($this->get('page_type', '', 'parameters') == 'item') {

            if (strtolower($this->registry->get('parameters', 'request_action', 'read', 'parameters'))
                == 'read'
            ) {
                $actions = $this->setItemActions();
            } else {
                $actions = $this->setEditActions();
            }

        } elseif ($this->get('page_type', '', 'parameters') == 'list') {
            $actions = $this->setListActions();

        } else {
            $actions = $this->setMenuitemActions();
        }

        if ($actions === false) {
            $actionCount = 0;
        } else {
            $actionCount = count($actions);
        }

        $temp_query_results = array();

        $temp_row               = new \stdClass();
        $temp_row->action_count = $actionCount;
        $temp_row->action_array = '';

        if ($actionCount === 0) {
            $temp_row->action_array = null;
        } else {
            foreach ($actions as $action) {
                $temp_row->action_array .= trim($action);
            }
        }

        $temp_query_results[] = $temp_row;

        $this->registry->set('Page', 'PageEligibleActions', $temp_query_results);

        return true;
    }

    /**
     * Create Item Actions
     *
     * @return array
     * @since   1.0
     */
    protected function setItemActions()
    {
        // Currently display

        $actions   = array();
        $actions[] = 'create';
        $actions[] = 'copy';
        $actions[] = 'read';
        $actions[] = 'edit';

        // editing item
        $actions[] = 'read';
        $actions[] = 'copy';
        $actions[] = 'draft';
        $actions[] = 'save';
        $actions[] = 'restore';
        $actions[] = 'cancel';

        // either
        $actions[] = 'tag';
        $actions[] = 'categorize';
        $actions[] = 'status'; // archive, publish, unpublish, trash, spam, version
        $actions[] = 'sticky';
        $actions[] = 'feature';
        $actions[] = 'delete';

        // list
        $actions[] = 'orderup';
        $actions[] = 'orderdown';
        $actions[] = 'reorder';
        $actions[] = 'status';

        return $actions;
    }

    /**
     * Create Edit Actions
     *
     * @return array
     * @since   1.0
     */
    protected function setEditActions()
    {
        $actions = array();

        return $actions;
    }

    /**
     * Create List Actions
     *
     * @return array
     * @since   1.0
     */
    protected function setListActions()
    {
        $actions = array();

        $actions[] = 'create';
        $actions[] = 'copy';
        $actions[] = 'edit';

        $actions[] = 'tag';
        $actions[] = 'categorize';
        $actions[] = 'status'; // archive, publish, unpublish, trash, spam, version
        $actions[] = 'sticky';
        $actions[] = 'feature';
        $actions[] = 'delete';

        $actions[] = 'orderup';
        $actions[] = 'orderdown';
        $actions[] = 'reorder';
        $actions[] = 'status';

        return $actions;
    }

    /**
     * Menu Item Actions
     *
     * @return array
     * @since   1.0
     */
    protected function setMenuitemActions()
    {
        $actions = array();

        return $actions;
    }

    /**
     * Set Page Meta Data during onBeforeParse, can be modified at any point during the document body rendering
     *
     * @return boolean
     * @since   1.0
     */
    protected function setPageMeta()
    {
        $title       = $this->metadata_service->get('title', '');
        $description = $this->metadata_service->get('description', '');
        $author      = $this->metadata_service->get('author', '');
        $robots      = $this->metadata_service->get('robots', '');

        if ($title == '' || $description == '' || $author == '' || $robots == '') {
        } else {
            return true;
        }

        $type = strtolower($this->registry->get('Page', 'page_type'));
        $type = strtolower($type);

        if (trim($title) == '') {
            if ($type == 'item') {
                if (isset($this->query_results[0]->title)) {
                    $title = $this->query_results[0]->title;
                }
            }

            if ($title == '') {
                $title = $this->registry->set('Page', 'HeaderTitle', '');
            }

            if ($title == '') {
            } else {
                $title .= ': ';
            }

            $title .= $parameters->site->name;

            $this->document_metadata->set('title', $title);
        }

        if (trim($description) == '') {

            if ($type == 'item') {

                if (isset($this->query_results[0]->description)) {
                    $description = $this->query_results[0]->description;

                } elseif (isset($this->query_results[0]->content_text_snippet)) {
                    $description = $this->query_results[0]->content_text_snippet;
                }
            }

            $this->document_metadata->set('description', $description);
        }

        if (trim($author) == '') {

            if ($type == 'item') {

                if (isset($this->query_results[0]->author_full_name)) {
                    $author = $this->query_results[0]->author_full_name;
                    $this->document_metadata->set('author', $author);
                }
            }
        }

        if (trim($robots) == '') {
            $this->document_metadata->set('robots', 'follow,index');
        }

        return true;
    }
}
