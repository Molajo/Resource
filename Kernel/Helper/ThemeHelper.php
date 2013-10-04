<?php
/**
 * Theme Service Theme Helper
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Helper;

use Molajo\Helper\ExtensionHelper;


/**
 * Theme Helper retrieves values needed to render the selected Theme index.php file, load plugins
 * in the Theme folder and load assets defined by the Theme.
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class ThemeHelper
{
    /**
     * Extension Helper
     *
     * @var    object
     * @since  1.0
     */
    protected $extension_helper;

    /**
     * class Constructor
     *
     * @return void
     * @since   1.0
     */
    public function __construct()
    {
        $this->extension_helper = new ExtensionHelper();

        return;
    }

    /**
     * Get information for rendering the specified Theme index.php file. Calling process sends in the
     * name of the Registry to use when storing results. Defaults to "Parameters" registry.
     *
     * @param int    $theme_id
     * @param string $registry
     *
     * @return boolean
     * @since   1.0
     */
    public function get($theme_id = 0, $registry = null)
    {
        if ((int)$theme_id == 0) {
            $theme_id = $this->application->get('application_default_theme_id');
        }

        if ($registry === null) {
            $registry = strtolower('Parameters');
        }

        $this->registry->set($registry, 'theme_id', (int)$theme_id);

        $node = $this->extension_helper->getExtensionNode((int)$theme_id);

        $this->registry->set($registry, 'theme_path_node', $node);

        $this->registry->set(
            $registry,
            'theme_path',
            $this->extension_helper->getPath(CATALOG_TYPE_THEME_LITERAL, $node, $registry)
        );

        $this->registry->set(
            $registry,
            'theme_namespace',
            $this->extension_helper->getNamespace(CATALOG_TYPE_THEME_LITERAL, $node, $registry)
        );

        $this->registry->set(
            $registry,
            'theme_path_include',
            $this->extension_helper->getPath(CATALOG_TYPE_THEME_LITERAL, $node, $registry) . '/index.php'
        );

        $this->registry->set(
            $registry,
            'theme_path_url',
            $this->extension_helper->getPathURL(CATALOG_TYPE_THEME_LITERAL, $node, $registry)
        );

        $this->registry->set(
            $registry,
            'theme_favicon',
            $this->extension_helper->getFavicon($registry)
        );

        $item = $this->extension_helper->get($theme_id, CATALOG_TYPE_THEME, null, null, 1);
        if (count($item) == 0) {

            if ($theme_id == $this->extension_helper->getId(CATALOG_TYPE_THEME, 'System')) {
                $this->error_instance->set(500, 'System Theme not found');
                throw new Exception('ThemeIncluder: Not found ' . $theme_id);
            }

            $theme_id = $this->extension_helper->getId(CATALOG_TYPE_THEME, 'System');
            $this->registry->set($registry, 'theme_id', (int)$theme_id);

            $node = $this->extension_helper->getExtensionNode((int)$theme_id);
            $this->registry->set($registry, 'theme_path_node', $node);

            $this->registry->set(
                $registry,
                'theme_path',
                $this->extension_helper->getPath(CATALOG_TYPE_THEME, $node, $registry)
            );
            $this->registry->set(
                $registry,
                'theme_namespace',
                $this->extension_helper->getNamespace(CATALOG_TYPE_THEME, $node, $registry)
            );
            $this->registry->set(
                $registry,
                'theme_path_include',
                $this->extension_helper->getPath(CATALOG_TYPE_THEME, $node, $registry) . '/index.php'
            );
            $this->registry->set(
                $registry,
                'theme_path_url',
                $this->extension_helper->getPathURL(CATALOG_TYPE_THEME, $node, $registry)
            );
            $this->registry->set(
                $registry,
                'theme_favicon',
                $this->extension_helper->getFavicon($registry)
            );

            $item = $this->extension_helper->get($theme_id, CATALOG_TYPE_THEME_LITERAL, $node, 1);
            if (count($item) == 0) {
                $this->error_instance->set(500, 'System Theme not found');
                //throw error
                die();
            }
        }

        $this->registry->set($registry, 'theme_title', $item->title);
        $this->registry->set($registry, 'theme_translation_of_id', (int)$item->translation_of_id);
        $this->registry->set($registry, 'theme_language', $item->language);
        $this->registry->set($registry, 'theme_view_group_id', $item->catalog_view_group_id);
        $this->registry->set($registry, 'theme_catalog_id', $item->catalog_id);
        $this->registry->set($registry, 'theme_catalog_type_id', (int)$item->catalog_view_group_id);
        $this->registry->set($registry, 'theme_catalog_type_title', $item->catalog_types_title);
        $this->registry->set($registry, 'theme_model_registry', $item->model_registry);

        return true;
    }
}
