<?php
/**
 * Ajax Plugin
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Plugin\Ajax;

use Molajo\Plugin\DisplayEventPlugin;
use Molajo\Plugin\Api\DisplayEventInterface;

/**
 * Ajax Plugin
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Ajax extends DisplayEventPlugin implements DisplayEventInterface
{
    /**
     * Identify Ajax Request (run last in onBeforeParse):
     *    Adapt the Parse Include File Parameters to only generate the Request
     *     Adapt the Template and Wrap Parameters to generate consumable output
     *
     * @return  $this
     * @since   1.0
     */
    public function onBeforeParse()
    {
        if ($this->parameters->application->id == 2) {
        } else {
            return $this;
        }

        $view = $this->view_helper->get(0, CATALOG_TYPE_TEMPLATE_VIEW_LITERAL);

        if ((int)Services::Client()->get('ajax') == 0) {
            return true;
        }

        $this->set('template_view_id', 1342);
        $this->set('wrap_view_id', 2090);

        $this->view_helper->get(2090, CATALOG_TYPE_WRAP_VIEW_LITERAL);

        $this->registry->set(OVERRIDE_LITERAL, 'parse_sequence', 'Ajax_sequence');
        $this->registry->set(OVERRIDE_LITERAL, 'parse_final', 'Ajax_final');

        return true;
    }
}
