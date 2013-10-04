<?php
/**
 * Renderingextensions Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Service\Renderingextensions;

use stdClass;
use Exception;
use Molajo\IoC\Handler\AbstractInjector;
use Molajo\IoC\Api\ServiceHandlerInterface;
use Molajo\IoC\Exception\ServiceHandlerException;

/**
 * Rendering Extensions Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class RenderingextensionsInjector extends AbstractInjector implements ServiceHandlerInterface
{
    /**
     * Controller
     *
     * @var    object  Molajo\Controller\Api\ReadControllerInterface
     * @since  1.0
     */
    protected $controller = null;

    /**
     * Constructor
     *
     * @param  $options
     *
     * @since  1.0
     */
    public function __construct(array $options = array())
    {
        $options['service_name']             = basename(__DIR__);
        $options['store_instance_indicator'] = true;

        parent::__construct($options);
    }

    /**
     * Define Dependencies for the Service
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    public function setDependencies(array $reflection = null)
    {
        $options                         = array();
        $this->dependencies['Resources'] = $options;
        $this->dependencies['Parameters'] = $options;

        return $this->dependencies;
    }

    /**
     * IoC Controller triggers the DI Handler to create the Class for the Service
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    public function instantiateService()
    {
        $this->service_instance = $this->getCatalogTypes();

        return $this;
    }

    /**
     * Catalog Types
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    protected function getCatalogTypes()
    {
        $controller = $this->dependencies['Resources']->get(
            'query:///Molajo//Datasource//CatalogTypes.xml',
            array('Parameters' => $this->dependencies['Parameters'])
        );

        $controller->setModelRegistry('check_view_level_access', 0);
        $controller->setModelRegistry('process_plugins', 0);
        $controller->setModelRegistry('query_object', 'list');

        $controller->model->query->where(
            $controller->model->database->qn($controller->getModelRegistry('prefix', 'a'))
            . ' . '
            . $controller->model->database->qn('id')
            . ' IN ('
            . (int)$this->dependencies['Parameters']->reference_data->catalog_type_plugin_id . ', '
            . (int)$this->dependencies['Parameters']->reference_data->catalog_type_theme_id . ', '
            . (int)$this->dependencies['Parameters']->reference_data->catalog_type_page_view_id . ', '
            . (int)$this->dependencies['Parameters']->reference_data->catalog_type_template_view_id . ', '
            . (int)$this->dependencies['Parameters']->reference_data->catalog_type_wrap_view_id . ', '
//. (int)$this->dependencies['Parameters']->reference_data->catalog_type_menuitem_id . ', '
            . (int)$this->dependencies['Parameters']->reference_data->catalog_type_resource_id
            . ')'
        );

        try {
            $results = $controller->getData();

        } catch (Exception $e) {
            throw new ServiceHandlerException ($e->getMessage());
        }

        $catalog_type             = new stdClass();
        $catalog_type->names      = array();
        $catalog_type->ids        = array();
        $catalog_type->extensions = array();

        foreach ($results as $item) {
            $catalog_type->ids[$item->id]        = $item;
            $catalog_type->names[$item->title]   = $item->id;
            $catalog_type->extensions[$item->id] = $this->getExtensions($item->id, $item->model_name);
        }

        return $catalog_type;
    }

    /**
     * Retrieve Extension information for Catalog Type
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    protected function getExtensions($catalog_type_id, $catalog_type_model_name)
    {
        $controller
            = $this->dependencies['Resources']->get(
            'query:///Molajo//Datasource//ExtensionInstances.xml',
            array('Parameters' => $this->dependencies['Parameters'])
        );

        $controller->setModelRegistry('check_view_level_access', 0);
        $controller->setModelRegistry('process_plugins', 0);
        $controller->setModelRegistry('id', $catalog_type_id);
        $controller->setModelRegistry('get_customfields', 1);
        $controller->setModelRegistry('query_object', 'list');

        $controller->model->query->where(
            $controller->model->database->qn($controller->getModelRegistry('primary_prefix', 'a'))
            . ' . '
            . $controller->model->database->qn('id')
            . ' <> '
            . $controller->model->database->qn($controller->getModelRegistry('primary_prefix', 'a'))
            . ' . '
            . $controller->model->database->qn('catalog_type_id')
        );

        $controller->model->query->where(
            $controller->model->database->qn($controller->getModelRegistry('primary_prefix', 'a'))
            . ' . '
            . $controller->model->database->qn('catalog_type_id')
            . ' = '
            . (int)$catalog_type_id
        );

        $controller->model->query->where(
            $controller->model->database->qn($controller->getModelRegistry('primary_prefix', 'a'))
            . ' . '
            . $controller->model->database->qn('status')
            . ' > '
            . ' 0 '
        );

        $controller->model->query->order(
            $controller->model->database->qn($controller->getModelRegistry('primary_prefix', 'a'))
            . ' . '
            . $controller->model->database->qn('title')
        );

        try {
            $extensions = $controller->getData();

        } catch (Exception $e) {
            throw new ServiceHandlerException ($e->getMessage());
        }

        if (is_array($extensions) && count($extensions) > 0) {
        } else {
            return array();
        }

        $temp             = new stdClass();
        $temp->ids        = array();
        $temp->names      = array();
        $temp->extensions = array();

        foreach ($extensions as $item) {
            $temp->ids[$item->id]        = $item;
            $temp->names[$item->title]   = $item->id;
            $temp->extensions[$item->id] = $this->getExtension($item->id, $item->title, $catalog_type_model_name);
        }

        return $temp;
    }

    /**
     * Retrieve specific Extension Information
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    protected function getExtension($id, $title, $catalog_type_model_name)
    {
        $catalog_type_model_name = ucfirst(strtolower($catalog_type_model_name));
        $title                   = ucfirst(strtolower($title));
        $model                   = 'Molajo'
            . '//' . $catalog_type_model_name
            . '//' . $title
            . '//Configuration.xml';

        $controller = $this->dependencies['Resources']->get(
            'query:///' . $model,
            array('Parameters' => $this->dependencies['Parameters'])
        );

        $controller->setModelRegistry('check_view_level_access', 0);
        $controller->setModelRegistry('process_plugins', 1);
        $controller->setModelRegistry('get_customfields', 1);
        $controller->setModelRegistry('id', $id);
        $controller->setModelRegistry('query_object', 'item');

        try {
            $extension = $controller->getData();

        } catch (Exception $e) {
            echo 'RenderingExtensionsInjector: Extension not found: ' . $title;
            throw new ServiceHandlerException ($e->getMessage());
        }

        return $extension;
    }
}
