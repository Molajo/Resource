<?php
/**
 * @package   Head Includer
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Theme\Includer;

use Molajo\Theme\Api\IncluderInterface;

/**
 * Head Includer
 *
 * @package     Molajo
 * @subpackage  Includer
 * @since       1.0
 */
class HeadIncluder extends AbstractIncluder implements IncluderInterface
{


    /**
     * @return null
     * @since   1.0
     */
    public function __construct($include_name = null, $include_type = null)
    {
        $this->registry->set('include', 'extension_catalog_type_id', 0);
        parent::__construct($include_name, $include_type);
        $this->registry->set('include', 'criteria_html_display_filter', false);

        return;
    }

    /**
     *  Retrieve default values for Rendering, if not provided by extension
     *
     * @return bool
     * @since   1.0
     */
    protected function setRenderCriteria()
    {
        $this->registry->set('include', 'criteria_display_view_on_no_results', 1);

        $this->registry->set('include', 'model_type', 'Assets');

        if ($this->type == 'defer') {

            if ((int)$this->registry->get('include', 'template_view_id', 0) == 0) {
                $this->registry->set(
                    'include',
                    'template_view_id',
                    $this->application->get('defer_template_view_id')
                );
            }

            if ((int)$this->registry->get('include', 'wrap_view_id', 0) == 0) {
                $this->registry->set(
                    'include',
                    'wrap_view_id',
                    $this->application->get('defer_wrap_view_id')
                );
            }

        } else {
            if ((int)$this->registry->get('include', 'template_view_id', 0) == 0) {
                $this->registry->set(
                    'include',
                    'template_view_id',
                    $this->application->get('head_template_view_id')
                );
            }
            if ((int)$this->registry->get('include', 'wrap_view_id', 0) == 0) {
                $this->registry->set(
                    'include',
                    'wrap_view_id',
                    $this->application->get('head_wrap_view_id')
                );
            }
        }

        /** Save existing parameters */
        $savedParameters = array();
        $temp            = $this->registry->getArray('include');

        if (is_array($temp) && count($temp) > 0) {
            foreach ($temp as $key => $value) {
                if (is_array($value)) {
                    $savedParameters[$key] = $value;

                } elseif ($value === 0 || trim($value) == '' || $value === null) {

                } else {
                    $savedParameters[$key] = $value;
                }
            }
        }

        /** Template  */
        $this->view_helper->get(
            $this->registry->get('include', 'template_view_id'),
            CATALOG_TYPE_TEMPLATE_VIEW_LITERAL
        );

        /** Merge Parameters in (Pre-wrap) */
        if (is_array($savedParameters) && count($savedParameters) > 0) {
            foreach ($savedParameters as $key => $value) {
                $this->registry->set('include', $key, $value);
            }
        }
        /** Default Wrap if needed */
        $wrap_view_id = $this->registry->get('include', 'wrap_view_id');
        $this->registry->set(
            'include',
            'wrap_view_path_node',
            $this->extension_helper->getExtensionNode((int)$wrap_view_id)
        );
        $wrap_view_title = $this->registry->get('include', 'wrap_view_path_node');

        $this->registry->set('include', 'wrap_view_title', $wrap_view_title);
        $this->registry->set(
            'include',
            'wrap_view_path',
            $this->extension_helper->getPath($wrap_view_title, CATALOG_TYPE_WRAP_VIEW_LITERAL)
        );
        $this->registry->set(
            'include',
            'wrap_view_path_url',
            $this->extension_helper->getPathURL($wrap_view_title, CATALOG_TYPE_WRAP_VIEW_LITERAL)
        );
        $this->registry->set(
            'include',
            'wrap_view_namespace',
            $this->extension_helper->getNamespace($wrap_view_title, CATALOG_TYPE_WRAP_VIEW_LITERAL)
        );

        if ($this->registry->exists('include', 'wrap_view_role')) {
        } else {
            $this->registry->set('include', 'wrap_view_role', '');
        }
        if ($this->registry->exists('include', 'wrap_view_property')) {
        } else {
            $this->registry->set('include', 'wrap_view_property', '');
        }
        if ($this->registry->exists('include', 'wrap_view_header_level')) {
        } else {
            $this->registry->set('include', 'wrap_view_header_level', '');
        }
        if ($this->registry->exists('include', 'wrap_view_show_title')) {
        } else {
            $this->registry->set('include', 'wrap_view_show_title', '');
        }
        if ($this->registry->exists('include', 'wrap_view_show_subtitle')) {
        } else {
            $this->registry->set('include', 'wrap_view_show_subtitle', '');
        }
        $this->registry->delete('include', 'item*');
        $this->registry->delete('include', 'list*');
        $this->registry->delete('include', 'form*');
        $this->registry->delete('include', 'menuitem');

        $this->registry->sort('include');

        return true;
    }
}
