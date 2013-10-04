<?php
/**
 * @package   Page Includer
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Theme\Includer;

use Molajo\Theme\Api\IncluderInterface;

/**
 * Page Includer
 *
 * @package     Molajo
 * @subpackage  Includer
 * @since       1.0
 */
class PageIncluder extends AbstractIncluder implements IncluderInterface
{
    /**
     * @return null
     * @since   1.0
     */
    public function __construct($include_name = null, $include_type = null)
    {
        $this->name = $include_name;
        $this->type = $include_type;

        $this->registry->createRegistry('Include');

        $this->registry->set('include', 'includer_name', $this->name);
        $this->registry->set('include', 'includer_type', $this->type);

        return $this;
    }

    /**
     * For Item, List, or Menu Item, retrieve Parameter data needed to generate page.
     *
     * Once parameters are available, page cache is returned, if avaiable.
     *
     * @return mixed | false or string (page cache)
     * @since    1.0
     * @throws   /Exception
     */
    public function setPrimaryData()
    {
        $catalog_id                    = $this->registry->get('include', 'catalog_id');
        $id                            = $this->registry->get('include', 'catalog_source_id');
        $catalog_extension_instance_id = $this->registry->get('include', 'catalog_extension_instance_id');
        $catalog_page_type             = $this->registry->get('include', 'catalog_page_type');
        $model_type                    = ucfirst(
            strtolower($this->registry->get('include', 'catalog_model_type'))
        );
        $model_name                    = ucfirst(
            strtolower($this->registry->get('include', 'catalog_model_name'))
        );

        if (strtolower(trim($catalog_page_type)) == 'list') {
            $response = $this->content_helper->getRouteList($id, $model_type, $model_name);

        } elseif (strtolower(trim($catalog_page_type)) == 'item') {
            $response = $this->content_helper->getRouteItem($id, $model_type, $model_name);

        } else {
            $response = $this->content_helper->getRouteMenuitem();
        }

        if ($response === false) {
            throw new Exception('Page Parameter Data for Catalog ID ' . $catalog_id);
        }

        $this->registry->set('include', 'extension_catalog_type_id', CATALOG_TYPE_RESOURCE);

        $this->getPageCache();

        return $this->rendered_output;
    }

    /**
     * See if page exists in Page Cache
     *
     * @return mixed | false or string
     * @since   1.0
     */
    protected function getPageCache()
    {
        if (file_exists($this->registry->get('include', 'Page_path_include'))) {
        } else {
            $this->error_instance->set(500, 'Page Not found');
            throw new Exception('Page not found '
            . $this->registry->get('include', 'Page_path_include'));
        }

        $parameters = $this->registry->getArray('include');

        $this->rendered_output = $this->cache->get('Page', implode('', $parameters));

        return;
    }

    /**
     * Render and return output
     *
     * @param   $attributes
     *
     * @return mixed
     * @since   1.0
     */
    public function process($attributes = array())
    {
        $this->loadPlugins();

        $this->renderOutput();

        return $this->rendered_output;
    }

    /**
     * Load Plugins Overrides from the Page and/or Page View folders
     *
     * @return void
     * @since   1.0
     */
    protected function loadPlugins()
    {
        $this->event->registerPlugins(
            $this->registry->get('include', 'Page_path'),
            $this->registry->get('include', 'Page_namespace')
        );

        return;
        $this->event->registerPlugins(
            $this->registry->get('include', 'page_view_path'),
            $this->registry->get('include', 'page_view_namespace')
        );

        $this->event->registerPlugins(
            $this->registry->get('include', 'extension_path'),
            $this->registry->get('include', 'extension_namespace')
        );

        return;
    }

    /**
     * The Page Includer renders the Page include file and feeds in the Page Name Value
     *  The rendered output from that process provides the initial data to be parsed for Include statements
     */
    protected function renderOutput()
    {
        $controller = new DisplayController();
        $controller->set('include', $this->registry->getArray('include'));

        $this->rendered_output = $controller->execute();

        $this->registry->delete('include');
        $this->registry->createRegistry('include');
        $this->registry->loadArray('include', $controller->get('include'));
        $this->registry->sort('include');

        $this->loadMedia();

        $this->loadViewMedia();

        return;
    }

    /**
     * loadMedia
     *
     * Loads Media Files for Site, Application, User, and Page
     *
     * @return bool
     * @since   1.0
     */
    protected function loadMedia()
    {
        $this->loadMediaPlus(
            '',
            $this->registry->get('include', 'asset_priority_site', 100)
        );

        $this->loadMediaPlus(
            '/application' . APPLICATION,
            $this->registry->get('include', 'asset_priority_application', 200)
        );

        $this->loadMediaPlus(
            '/user' . $this->user->get('id'),
            $this->registry->get('include', 'asset_priority_user', 300)
        );

        $this->loadMediaPlus(
            '/category' . $this->registry->get('include', 'catalog_category_id'),
            $this->registry->get('include', 'asset_priority_primary_category', 700)
        );

        $this->loadMediaPlus(
            '/menuitem' . $this->registry->get('include', 'menu_item_id'),
            $this->registry->get('include', 'asset_priority_menuitem', 800)
        );

        $this->loadMediaPlus(
            '/source/' . $this->registry->get('include', 'extension_title')
            . $this->registry->get('include', 'criteria_source_id'),
            $this->registry->get('include', 'asset_priority_item', 900)
        );

        $this->loadMediaPlus(
            '/resource/' . $this->registry->get('include', 'extension_title'),
            $this->registry->get('include', 'asset_priority_extension', 900)
        );

        $priority  = $this->registry->get('include', 'asset_priority_Page', 600);
        $file_path = $this->registry->get('include', 'Page_path');
        $url_path  = $this->registry->get('include', 'Page_path_url');

        $this->document_css->setFolder($file_path, $url_path, $priority);
        $this->document_js->setFolder($file_path, $url_path, $priority, 0);
        $this->document_js->setFolder($file_path, $url_path, $priority, 1);

        $priority  = $this->registry->get('include', 'asset_priority_Page', 600);
        $file_path = $this->registry->get('include', 'page_view_path');
        $url_path  = $this->registry->get('include', 'page_view_path_url');

        $this->document_css->setFolder($file_path, $url_path, $priority);
        $this->document_js->setFolder($file_path, $url_path, $priority, 0);
        $this->document_js->setFolder($file_path, $url_path, $priority, 1);

        $this->document_links->set(
            $url = $this->registry->get('include', 'Page_favicon'),
            $relation = 'shortcut icon',
            $relation_type = 'image/x-icon',
            $attributes = array()
        );

        $this->loadMediaPlus('', $this->registry->get('include', 'asset_priority_site', 100));

        return true;
    }

    /**
     * loadMediaPlus
     *
     * Loads Media Files for Site, Application, User, and Page
     *
     * @return bool
     * @since   1.0
     */
    protected function loadMediaPlus($plus = '', $priority = 500)
    {
        /** Page */
        $file_path = $this->registry->get('include', 'Page_path');
        $url_path  = $this->registry->get('include', 'Page_path_url');
        $css       = $this->document_css->setFolder($file_path, $url_path, $priority);
        $js        = $this->document_js->setFolder($file_path, $url_path, $priority, 0);
        $defer     = $this->document_js->setFolder($file_path, $url_path, $priority, 1);
        if ($css === true || $js === true || $defer === true) {
            return true;
        }

        /** Site Specific: Application */
        $file_path = SITE_MEDIA_FOLDER . '/' . APPLICATION . $plus;
        $url_path  = SITE_MEDIA_URL . '/' . APPLICATION . $plus;
        $css       = $this->document_css->setFolder($file_path, $url_path, $priority);
        $js        = $this->document_js->setFolder($file_path, $url_path, $priority, 0);
        $defer     = $this->document_js->setFolder($file_path, $url_path, $priority, 1);
        if ($css === true || $js === true || $defer === true) {
            return true;
        }

        /** Site Specific: Site-wide */
        $file_path = SITE_MEDIA_FOLDER . $plus;
        $url_path  = SITE_MEDIA_URL . $plus;
        $css       = $this->document_css->setFolder($file_path, $url_path, $priority);
        $js        = $this->document_js->setFolder($file_path, $url_path, $priority, false);
        $defer     = $this->document_js->setFolder($file_path, $url_path, $priority, 1);
        if ($css === true || $js === true || $defer === true) {
            return true;
        }

        /** All Sites: Application */
        $file_path = SITES_MEDIA_FOLDER . '/' . APPLICATION . $plus;
        $url_path  = SITES_MEDIA_URL . '/' . APPLICATION . $plus;
        $css       = $this->document_css->setFolder($file_path, $url_path, $priority);
        $js        = $this->document_js->setFolder($file_path, $url_path, $priority, 0);
        $defer     = $this->document_js->setFolder($file_path, $url_path, $priority, 1);
        if ($css === true || $js === true || $defer === true) {
            return true;
        }

        /** All Sites: Site Wide */
        $file_path = SITES_MEDIA_FOLDER . $plus;
        $url_path  = SITES_MEDIA_URL . $plus;
        $css       = $this->document_css->setFolder($file_path, $url_path, $priority);
        $js        = $this->document_js->setFolder($file_path, $url_path, $priority, 0);
        $defer     = $this->document_js->setFolder($file_path, $url_path, $priority, 1);
        if ($css === true || $js === true || $defer === true) {
            return true;
        }

        /** nothing was loaded */

        return true;
    }
}
