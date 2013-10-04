<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Defer;

use Molajo\Plugin\AbstractPlugin;


/**
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class DeferPlugin extends AbstractPlugin
{
    /**
     * Prepares data for the JS links and Declarations for the Head
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeRead()
    {
        if (strtolower($this->get('template_view_path_node', '', 'parameters')) == 'defer') {
        } else {
            return true;
        }

        /** JS */
        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry('Assets', JS_DEFER_LITERAL);
        $controller->setDataobject();
        $controller->connectDatabase();

        $temp_query_results = $controller->getData('list');

        $this->registry->set('Assets', JS_DEFER_LITERAL, $temp_query_results);

        /** JS Declarations */
        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry('Assets', JS_DECLARATIONS_DEFER_LITERAL);
        $controller->set('model_parameter', JS_DECLARATIONS_DEFER_LITERAL, 'parameters');
        $controller->connectDatabase();

        $temp_query_results = $controller->getData('list');

        $this->registry->set('Assets', JS_DECLARATIONS_DEFER_LITERAL, $temp_query_results);

        return true;
    }
}
