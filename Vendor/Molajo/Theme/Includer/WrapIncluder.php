<?php
/**
 * @package   Wrap Includer
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Theme\Includer;

use Molajo\Theme\Api\IncluderInterface;

/**
 * Wrap Includer
 *
 * @package     Molajo
 * @subpackage  Includer
 * @since       1.0
 */
class WrapIncluder extends AbstractIncluder implements IncluderInterface
{
    /**
     * @param string $name
     * @param string $type
     *
     * @return null
     * @since   1.0
     */
    public function __construct($include_name = null, $include_type = null)
    {
        $this->registry->set('include', 'extension_catalog_type_id', 0);
        parent::__construct($include_name, $include_type);
        $this->registry->set('include', 'criteria_html_display_filter', false);

        return $this;
    }

    /**
     * Loads Media CSS and JS files for Template and Wrap Views
     *
     * @return null
     * @since   1.0
     */
    protected function loadViewMedia()
    {
        $priority = $this->registry->get('include', 'criteria_media_priority_other_extension', 400);

        $file_path = $this->registry->get('include', 'wrap_view_path');
        $url_path  = $this->registry->get('include', 'wrap_view_path_url');

        $this->document_css->setFolder($file_path, $url_path, $priority);
        $this->document_js->setFolder($file_path, $url_path, $priority, 0);
        $this->document_js->setFolder($file_path, $url_path, $priority, 1);

        return $this;
    }
}
