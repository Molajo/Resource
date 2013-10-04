<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Head;

use Molajo\Plugin\AbstractPlugin;


/**
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class HeadPlugin extends AbstractPlugin
{
    /**
     * Prepares data for the JS links and Declarations for the Head
     *
     * @return boolean
     * @since   1.0
     */
    public function onBeforeRead()
    {
        if (strtolower($this->get('template_view_path_node', '', 'parameters')) == 'head') {
        } else {
            return true;
        }

        /** JS */
        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry('dbo', 'Assets');
        $controller->setDataobject();
        $controller->connectDatabase();
        $controller->set('model_parameter', 'Js', 'model_registry');

        $temp_query_results = $controller->getData('getAssets');

        $this->registry->set('Assets', 'js', $temp_query_results);

        /** JS Declarations */
        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry('dbo', 'Assets');
        $controller->setDataobject();
        $controller->connectDatabase();

        $controller->set('model_parameter', 'JsDeclarations', 'model_registry');
        $temp_query_results = $controller->getData('getAssets');

        $this->registry->set('Assets', 'jsdeclarations', $temp_query_results);

        return true;
    }
}
