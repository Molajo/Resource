<?php
/**
 * @package   Template Includer
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Theme\Includer;

use Molajo\Theme\Api\IncluderInterface;

/**
 * Template Includer
 *
 * The Template Includer prepares parameter values needed by the Mvc to render the requested
 * Template and Wrap for the specific <include type=value name=statement/> parsed by the Theme Service.
 * Once all parameter values have been determined, the data is passed to the Mvc for rendering and
 * the rendered result is passed back through the Template Includer to the Theme Service.
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class TemplateIncluder extends AbstractIncluder implements IncluderInterface
{
    /**
     * Uses Attributes and Extension Definitions to:
     *
     * 1. Determine which Template has been requested
     * 2. Set Parameter Values for the Template, Wrap, and Model
     *
     * @return bool
     * @since   1.0
     */
    protected function setRenderCriteria()
    {
        // get id for name - or name for id
        if ($this->get('name', null) === null) {
            throw new Exception ('TemplateIncluder: No Name provided for Template Include');
        }

        if (is_numeric($this->get('name'))) {
            $template_id = $this->extension_helper->getId(CATALOG_TYPE_TEMPLATE_VIEW, $this->get('name'));
        }


        $template_id = (int)$this->registry->get('parameters', 'template_view_id');

        if ((int)$template_id == 0) {
            $template_title = $this->registry->get('parameters', 'template_view_path_node');
            if (trim($template_title) == '') {
            } else {
                $template_id = $this->extension_helper
                    ->getId(CATALOG_TYPE_TEMPLATE_VIEW, $template_title);
                $this->registry->set('include', 'template_view_id', $template_id);
            }
        }

        if ((int)$template_id == 0) {
            $template_id = $this->view_helper->getDefault(CATALOG_TYPE_TEMPLATE_VIEW_LITERAL);
            $this->registry->set('include', 'template_view_id', $template_id);
        }

        if ((int)$template_id == 0) {
            return false;
        }

        $this->view_helper->get($template_id, CATALOG_TYPE_TEMPLATE_VIEW_LITERAL);

        if (is_array($saveTemplate) && count($saveTemplate) > 0) {
            foreach ($saveTemplate as $key => $value) {
                $this->registry->set('include', $key, $value);
            }
        }

        // extract parameters and populate $this->set('thing', value, 'parameters);
        // loop thru parameter names and overaly with matching attributes

        // get model
        $fields = $this->application->get('application*');
        if (count($fields) === 0 || $fields === false) {
        } else {
            foreach ($fields as $key => $value) {
                $this->registry->set('include', $key, $value);
            }
        }

        $fields = $this->registry->getArray('Tempattributes');
        if (count($fields) === 0 || $fields === false) {
        } else {
            foreach ($fields as $key => $value) {
                $this->registry->set('include', $key, $value);
            }
        }

        $message = 'Includer: Render Criteria '
            . 'Name ' . strtolower($this->name)
            . ' Handler ' . $this->type
            . ' Template ' . $this->registry->get('include', 'template_view_title')
            . ' Model Handler ' . $this->registry->get('include', 'model_type')
            . ' Model Name ' . $this->registry->get('include', 'model_name');

        $this->profiler_instance->set('message', $message, 'Rendering', 1);

        return true;
    }

    /**
     * Loads Media CSS and JS files for Template and Template Views
     *
     * @return  object
     * @since   1.0
     */
    protected function loadViewMedia()
    {
        if ($this->type == 'asset' || $this->type == 'metadata') {
            return $this;
        }

        $priority = $this->registry->get('include', 'criteria_media_priority_other_extension', 400);

        $file_path = $this->registry->get('include', 'template_view_path');
        $url_path  = $this->registry->get('include', 'template_view_path_url');

        $this->document_css->setFolder($file_path, $url_path, $priority);
        $this->document_js->setFolder($file_path, $url_path, $priority, 0);
        $this->document_js->setFolder($file_path, $url_path, $priority, 1);

        return $this;
    }
}
