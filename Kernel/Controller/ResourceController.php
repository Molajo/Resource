<?php
/**
 * Resource Controller
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Controller;

use stdClass;
use Exception;
use Molajo\Controller\Api\ReadControllerInterface;
use Molajo\Controller\Api\ResourceControllerInterface;
use Molajo\Controller\Exception\ResourceControllerException;

/**
 * Resource Controller
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class ResourceController implements ResourceControllerInterface
{
    /**
     * Page Type
     *
     * @var    int
     * @since  1.0
     */
    protected $page_type;

    /**
     * Resources
     *
     * @var    object  Molajo\Controller\Api\ReadControllerInterface
     * @since  1.0
     */
    protected $resources = null;

    /**
     * Parameters
     *
     * @var    object $parameters
     * @since  1.0
     */
    protected $parameters;

    /**
     * Resource Query
     *
     * @var    object  Molajo\Controller\Api\ReadControllerInterface
     * @since  1.0
     */
    protected $resource_query = null;

    /**
     * Rendering Extensions Query Results
     *
     * @var    object
     * @since  1.0
     */
    protected $rendering_extensions = null;

    /**
     * Constructor
     *
     * @param  object         $resources
     * @param  object         $parameters
     * @param  ReadController $resource_query
     * @param  object         $rendering_extensions
     *
     * @since  1.0
     */
    public function __construct(
        $resources,
        $parameters,
        ReadController $resource_query,
        $rendering_extensions
    ) {
        $this->resources            = $resources;
        $this->parameters           = $parameters;
        $this->resource_query       = $resource_query;
        $this->rendering_extensions = $rendering_extensions;
    }

    /**
     * Get Resource, Theme and View Data for Page Type and other Route Data
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ResourceControllerException
     */
    public function getResources()
    {
        $this->page_type = strtolower($this->parameters->route->page_type);

        if ($this->page_type == 'item') {
            return $this->getResourceItem();
        } elseif ($this->page_type == 'form') {
            return $this->getResourceForm();
        } elseif ($this->page_type == 'list') {
            return $this->getResourceList();
        } elseif ($this->page_type == 'menuitem') {
            return $this->getResourceMenuitem();
        }

        throw new ResourceControllerException
        ('Invalid Page Type: ' . $this->page_type);
    }

    /**
     * Retrieve Resource Item
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ResourceControllerException
     */
    protected function getResourceForm()
    {
        $this->getResourceItem();

        return $this;
    }

    /**
     * Retrieve Resource Item
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ResourceControllerException
     */
    protected function getResourceItem()
    {
        $this->resource_query->setModelRegistry(
            'primary_key_value',
            (int)$this->parameters->route->source_id
        );
        $this->resource_query->setModelRegistry('query_object', 'item');

        try {
            $item = $this->resource_query->getData();
        } catch (Exception $e) {
            throw new ResourceControllerException ($e->getMessage());
        }

        $resource = new stdClass();

        if (count($item) == 0) {
            throw new ResourceControllerException ('Resource Item not found.');
        }

        foreach (\get_object_vars($item) as $key => $value) {
            $resource->$key = $value;
        }

        if ((int)$resource->parameters->theme_id === 0) {
            $resource->parameters->theme_id = $this->parameters->application->parameters->application_default_theme_id;
        }

        return $resource;
    }

    /**
     * Retrieve Resource List
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ResourceControllerException
     */
    protected function getResourceList()
    {
        $this->resource_query->setModelRegistry(
            'primary_key_value',
            (int)$this->parameters->route->source_id
        );
        $this->resource_query->setModelRegistry('query_object', 'list');

        try {
            $item = $this->resource_query->getData();
        } catch (Exception $e) {
            throw new ResourceControllerException ($e->getMessage());
        }

        $resource = new stdClass();

        if (count($item) == 0) {
            throw new ResourceControllerException ('Resource Data not found.');
        }

        foreach (\get_object_vars($item) as $key => $value) {
            $resource->$key = $value;
        }

        return $resource;
    }

    /**
     * Retrieve Resource Menuitem
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ResourceControllerException
     */
    protected function getResourceMenuitem()
    {
        $id         = (int)$this->get('catalog_source_id');
        $model_type = CATALOG_TYPE_MENUITEM_LITERAL;
        $model_name = $this->get('catalog_page_type');

        $item = $this->getData($id, $model_type, $model_name);

        if (count($item) == 0) {
            return $this->set('route_found', false);
        }

        $this->set('menuitem_lvl', (int)$item->lvl);
        $this->set('menuitem_title', $item->title);
        $this->set('menuitem_parent_id', $item->parent_id);
        $this->set('menuitem_translation_of_id', (int)$item->translation_of_id);
        $this->set('menuitem_language', $item->language);
        $this->set('menuitem_catalog_type_id', (int)$item->catalog_type_id);
        $this->set('menuitem_catalog_type_title', $item->catalog_types_title);
        $this->set('menuitem_modified_datetime', $item->modified_datetime);
        $this->set('menu_id', (int)$item->extension_id);
        $this->set('menu_title', $item->extensions_name);
        $this->set('menu_extension_id', (int)$item->extensions_id);
        $this->set('menu_path_node', $item->extensions_name);

        $page_type = $this->get('catalog_page_type');
        $this->set('page_type', $page_type);

        $registry = $page_type . CATALOG_TYPE_MENUITEM_LITERAL;

        $parameters = $this->registry->copy($registry . 'Parameters');
        if (count($parameters) > 0) {
            foreach ($parameters as $key => $value) {
                if (in_array($key, $this->parameter_property_array)) {
                } else {
                    $this->parameter_property_array[] = $key;
                }
                $this->set($key, $value);
            }
        }

        $metadata = $this->registry->get($registry . 'Metadata');
        if (count($metadata) > 0) {
            foreach ($metadata as $key => $value) {
                $this->document_metadata->set($key, array($value));
            }
        }

        if ($this->get('catalog_model_type') == 'Resource') {
            $resource_or_system = 'Resource';
        } else {
            $resource_or_system = 'System';
        }

        $this->set('extension_name_path_node', $this->get('catalog_model_name'));

        $this->setParameters(
            strtolower(CATALOG_TYPE_MENUITEM_LITERAL),
            $registry . 'Parameters',
            $registry . 'Metadata',
            null,
            $resource_or_system
        );

        /** Must be after parameter set so as to not strip off menuitem */
        $this->set('menuitem_id', (int)$item->id);
        $this->set('page_type', $this->get('catalog_page_type'));

        /** Retrieve Model Registry for Resource */
        $controller_class_namespace = $this->controller_namespace;
        $controller                 = new $controller_class_namespace();
        $controller->getModelRegistry(
            $this->get('catalog_model_type'),
            $this->get('catalog_model_name'),
            0
        );

        $parameters = $this->parameters;
        ksort($parameters);
        $parameter_property_array = $this->parameter_property_array;
        ksort($parameter_property_array);

        return array($parameters, $parameter_property_array);
    }

    /**
     * Retrieve Theme Metadata
     *
     * @param   int $theme_id
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ResourceControllerException
     */
    public function getTheme($theme_id)
    {
        $catalog_type_id = $this->parameters->reference_data->catalog_type_theme_id;

        if (isset($this->rendering_extensions->extensions[$catalog_type_id]->ids[$theme_id])) {
            return $this->rendering_extensions->extensions[$catalog_type_id]->ids[$theme_id];
        }

        throw new ResourceControllerException ('ResourceController: Theme not found ' . $theme_id);
    }

    /**
     * Retrieve Page View Metadata
     *
     * @param   int $page_view_id
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ResourceControllerException
     */
    public function getPageView($page_view_id)
    {
        $catalog_type_id = $this->parameters->reference_data->catalog_type_page_view_id;

        if (isset($this->rendering_extensions->extensions[$catalog_type_id]->ids[$page_view_id])) {
            return $this->rendering_extensions->extensions[$catalog_type_id]->ids[$page_view_id];
        }

        throw new ResourceControllerException ('ResourceController: Page View not found ' . $page_view_id);
    }

    /**
     * Retrieve Template View Metadata
     *
     * @param   int $template_view_id
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ResourceControllerException
     */
    public function getTemplateView($template_view_id)
    {
        $this->parameters->resource->template_view = new stdClass();

        $catalog_type_id = $this->parameters->reference_data->catalog_type_template_view_id;

        if (isset($this->rendering_extensions->extensions[$catalog_type_id]->ids[$template_view_id])) {
            return $this->rendering_extensions->extensions[$catalog_type_id]->ids[$template_view_id];
        }

        throw new ResourceControllerException ('ResourceController: Page View not found ' . $template_view_id);
    }

    /**
     * Retrieve Wrap View Metadata
     *
     * @param   int $wrap_view_id
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ResourceControllerException
     */
    public function getWrapView($wrap_view_id)
    {
        $this->parameters->resource->wrap_view = new stdClass();

        $catalog_type_id = $this->parameters->reference_data->catalog_type_wrap_view_id;

        if (isset($this->rendering_extensions->extensions[$catalog_type_id]->ids[$wrap_view_id])) {
            return $this->rendering_extensions->extensions[$catalog_type_id]->ids[$wrap_view_id];
        }

        throw new ResourceControllerException ('ResourceController: Wrap View not found ' . $wrap_view_id);
    }
}
