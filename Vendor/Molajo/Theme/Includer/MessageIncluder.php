<?php
/**
 * @package   Message Includer
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Theme\Includer;

use Molajo\Theme\Api\IncluderInterface;

/**
 * Message Includer
 *
 * @package     Molajo
 * @subpackage  Includer
 * @since       1.0
 */
class MessageIncluder extends AbstractIncluder implements IncluderInterface
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
     * setRenderCriteria
     *
     * Retrieve default values, if not provided by extension
     *
     * @return bool
     * @since   1.0
     */
    protected function setRenderCriteria()
    {
        $this->registry->set(
            'include',
            'template_view_id',
            $this->application->get('message_template_view_id')
        );
        $this->registry->set(
            'include',
            'wrap_view_id',
            $this->application->get('message_wrap_view_id')
        );

        $this->registry->set('include', 'criteria_display_view_on_no_results', 0);

        /** Template  */
        $this->view_helper->get(
            $this->registry->get('include', 'template_view_id'),
            CATALOG_TYPE_TEMPLATE_VIEW_LITERAL
        );

        /** Wrap  */
        $this->view_helper->get($this->registry->get('include', 'wrap_view_id'), CATALOG_TYPE_WRAP_VIEW_LITERAL);

        /** Merge Configuration in */
        $this->registry->merge('Configuration', 'include', true);

        /** DBO  */
        $this->registry->set('include', 'model_type', 'Dataobject');
        $this->registry->set('include', 'model_name', 'Messages');
        $this->registry->set('include', 'model_query_object', 'list');

        /** Cleanup */
        $this->registry->delete('include', 'item*');
        $this->registry->delete('include', 'list*');
        $this->registry->delete('include', 'form*');

        /** Sort */
        $this->registry->sort('include');

        return true;
    }
}
