<?php
/**
 * @package   Theme Includer
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Theme\Includer;

use Molajo\Theme\Api\IncluderInterface;

/**
 * Theme Includer
 *
 * Theme Includer sets parameter values needed to render the Theme Index.php file, the results
 * of which are feed into the parsing rendered output for <include type=value/> statements process.
 *
 * In addition, the Theme Includer loads media and Plugins for the Theme.
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class ThemeIncluder extends AbstractIncluder implements IncluderInterface
{
    /**
     * The Theme Includer establishes values needed to render the Theme Index.php file and
     *  Plugins overriding core and extension plugins are loaded, along with Theme Assets.
     *
     * @param array $attributes
     *
     * @return mixed|null|string
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
     * Set Item, List, or Menu Item Parameter data needed to generate page.
     *
     * @return void
     * @since    1.0
     * @throws   /Exception
     */
    public function setThemeParameters()
    {
        $catalog_id        = $this->get('catalog_id');
        $catalog_page_type = $this->get('catalog_page_type');

        $content_helper = new $class();
        $content_helper->initialise($this->parameters);

        if (strtolower(trim($catalog_page_type)) == strtolower('list')) {
            $response = $content_helper->getRouteList();

        } elseif (strtolower(trim($catalog_page_type)) == strtolower('item')) {
            $response = $content_helper->getRouteItem();

        } else {
            $response = $content_helper->getRouteMenuitem();
        }

        if ($response === false) {
            throw new Exception('Theme Service: Could not identify Primary Data for Catalog ID ' . $catalog_id);
        }

        $this->parameters     = $response[0];
        $this->property_array = $response[1];

        return;
    }

    /**
     * Load Plugins Overrides from the Theme and/or Page View folders
     *
     * @return void
     * @since   1.0
     */
    protected function loadPlugins()
    {
        $this->event->registerPlugins(
            $this->registry->get('include', 'theme_path'),
            $this->registry->get('include', 'theme_namespace')
        );

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
     * The Theme Includer renders the Theme include file and feeds in the Page Name Value
     *  The rendered output from that process provides the initial data to be parsed for Include statements
     */
    protected function renderOutput()
    {
        if (file_exists($this->get('theme_path_include'))) {
        } else {
            $this->error_instance->set(500, 'Theme Not found');
            throw new Exception('Theme not found ' . $this->get('theme_path_include'));
        }

        $controller = new DisplayController();
        $controller->set('include', $this->registry->getArray('include'));
        $this->set(
            $this->get('extension_catalog_type_id', '', 'parameters'),
            CATALOG_TYPE_RESOURCE,
            'parameters'
        );

        $this->rendered_output = $controller->execute();
        echo $this->rendered_output;
        $this->loadMedia();

        $this->loadViewMedia();

        return;
    }

    /**
     * loadMedia
     *
     * Loads Media Files for Site, Application, User, and Theme
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

        $priority  = $this->registry->get('include', 'asset_priority_theme', 600);
        $file_path = $this->registry->get('include', 'theme_path');
        $url_path  = $this->registry->get('include', 'theme_path_url');

        $this->document_css->setFolder($file_path, $url_path, $priority);
        $this->document_js->setFolder($file_path, $url_path, $priority, 0);
        $this->document_js->setFolder($file_path, $url_path, $priority, 1);

        $priority  = $this->registry->get('include', 'asset_priority_theme', 600);
        $file_path = $this->registry->get('include', 'page_view_path');
        $url_path  = $this->registry->get('include', 'page_view_path_url');

        $this->document_css->setFolder($file_path, $url_path, $priority);
        $this->document_js->setFolder($file_path, $url_path, $priority, 0);
        $this->document_js->setFolder($file_path, $url_path, $priority, 1);

        $this->document_links->set(
            $url = $this->registry->get('include', 'theme_favicon'),
            $relation = 'shortcut icon',
            $relation_type = 'image/x-icon',
            $attributes = array()
        );

        $this->loadMediaPlus('', $this->registry->get('include', 'asset_priority_site', 100));

        return true;
    }

    /**
     * Loads Media Files for Site, Application, User, and Theme
     *
     * @param string $plus
     * @param int    $priority
     *
     * @return bool
     * @since   1.0
     */
    protected function loadMediaPlus($plus = '', $priority = 500)
    {
        /** Theme */
        $file_path = $this->registry->get('include', 'theme_path');
        $url_path  = $this->registry->get('include', 'theme_path_url');
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
