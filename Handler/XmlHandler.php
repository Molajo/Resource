<?php
/**
 * Configuration Handler
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resources\Handler;

use Exception;
use Molajo\Resources\Exception\ResourcesException;
use Molajo\Resources\Api\ResourceHandlerInterface;

/**
 * Configuration Handler
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
class XmlHandler implements ResourceHandlerInterface
{
    /**
     * File Extension
     *
     * @var    string
     * @since  1.0
     */
    protected $file_extension = '.xml';

    /**
     * Handler Instance
     *
     * @var    string
     * @since  1.0
     */
    protected $handler_instance;

    /**
     * Model Type
     *
     * @var    string
     * @since  1.0
     */
    protected $model_type;

    /**
     * Model Name
     *
     * @var    string
     * @since  1.0
     */
    protected $model_name;

    /**
     * Extension Path
     *
     * @var    string
     * @since  1.0
     */
    protected $primary_extension_path;

    /**
     * Extension Path
     *
     * @var    string
     * @since  1.0
     */
    protected $extension_path;

    /**
     * Theme Path
     *
     * @var    string
     * @since  1.0
     */
    protected $theme_path;

    /**
     * Page View Path
     *
     * @var    string
     * @since  1.0
     */
    protected $page_view_path;

    /**
     * Template View Path
     *
     * @var    string
     * @since  1.0
     */
    protected $template_view_path;

    /**
     * Wrap View Path
     *
     * @var    string
     * @since  1.0
     */
    protected $wrap_view_path;

    /**
     * Override Path
     *
     * @var    string
     * @since  1.0
     */
    protected $override_path;

    /**
     * Model Name is a File
     *
     * Model Type = Folder
     * Model Name = File . file_extension
     *
     * @var    string
     * @since  1.0
     */
    private $file_type = array(
        'Appconfiguration',
        'Datalist',
        'Dataobject',
        'Datasource',
        'Field',
        'Include'
    );

    /**
     * Model Name is a Folder
     *
     * Model Type = Folder
     * Model Name = Folder
     * Configuration.xml
     *
     * @var    string
     * @since  1.0
     */
    private $folder_type = array(
        'Menuitem',
        'Plugin',
        'Resource',
        'Service',
        'System',
        'Theme'
    );

    /**
     * View followed by Folder Type
     *
     * View = Folder
     * Model Type = Folder
     * Model Name = Folder
     * Configuration.xml
     *
     * @var    string
     * @since  1.0
     */
    private $view_type = array(
        'Page',
        'Template',
        'Wrap'
    );

    /**
     * Constructor
     *
     * @param  string      $model_type
     * @param  string      $model_name
     * @param  null|string $file_extension
     * @param  null|string $primary_extension_path
     * @param  null|string $extension_path
     * @param  null|string $theme_path
     * @param  null|string $page_view_path
     * @param  null|string $template_view_path
     * @param  null|string $wrap_view_path
     * @param  null|string $override_path
     *
     * @since  1.0
     */
    public function __construct(
        $model_type,
        $model_name,
        $file_extension = null,
        $primary_extension_path = null,
        $extension_path = null,
        $theme_path = null,
        $page_view_path = null,
        $template_view_path = null,
        $wrap_view_path = null,
        $override_path = null
    ) {
        $this->model_type = trim(ucfirst(strtolower($this->model_type)));
        $this->model_name = trim(ucfirst(strtolower($this->model_name)));

        if ($file_extension === null) {
            $file_extension = '.xml';
        }

        $this->model_type             = $model_type;
        $this->model_name             = $model_name;
        $this->file_extension         = $file_extension;
        $this->primary_extension_path = $primary_extension_path;
        $this->extension_path         = $extension_path;
        $this->theme_path             = $theme_path;
        $this->page_view_path         = $page_view_path;
        $this->template_view_path     = $template_view_path;
        $this->wrap_view_path         = $wrap_view_path;
        $this->override_path          = $override_path;

        $this->search();
    }

    /**
     * Handle located folder/file associated with URI Namespace for Resource
     *
     * @param   string  $scheme
     * @param   string  $located_path
     * @param   array   $options
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function handlePath($scheme, $located_path, array $options = array())
    {
        if (file_exists($located_path)) {
            return $located_path;
        }

        return;
    }

    /**
     * Retrieve a collection of a specific handler
     *
     * @param   string  $scheme
     * @param   array   $options
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function getCollection($scheme, array $options = array())
    {
        return $this->resource_map;
    }

    /**
     * Search for the file, first looking in default locations, and finally in the primary location
     *
     * @param   null|string $remainder
     *
     * @return  string
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function search($remainder = null)
    {
        $path = SITE_BASE_PATH . '/' . $remainder;
        if (file_exists($path)) {
            return $path;
        }

        $path = SITES . '/' . $remainder;
        if (file_exists($path)) {
            return $path;
        }

        if ($this->theme_path === null) {
        } else {
            $path = $this->theme_path . '/' . $remainder;
            if (file_exists($path)) {
                return $path;
            }
        }

        if ($this->template_view_path === null) {
        } else {
            $path = $this->template_view_path . '/' . $remainder;
            if (file_exists($path)) {
                return $path;
            }
        }

        if ($this->wrap_view_path === null) {
        } else {
            $path = $this->wrap_view_path . '/' . $remainder;
            if (file_exists($path)) {
                return $path;
            }
        }

        if ($this->page_view_path === null) {
        } else {
            $path = $this->page_view_path . '/' . $remainder;
            if (file_exists($path)) {
                return $path;
            }
        }

        if ($this->extension_path === null) {
        } else {
            $path = $this->extension_path . '/' . $remainder;
            if (file_exists($path)) {
                return $path;
            }
        }

        if ($this->primary_extension_path === null) {
        } else {
            $path = $this->primary_extension_path . '/' . $remainder;
            if (file_exists($path)) {
                return $path;
            }
        }

        $path = BASE_FOLDER . '/Application/Extension' . '/' . $remainder;
        if (file_exists($path)) {
            return $path;
        }

        $path = BASE_FOLDER . '/Application/Extension' . '/Model/' . $remainder;
        if (file_exists($path)) {
            return $path;
        }

        $path = APPLICATION . '/Model/' . $remainder;
        if (file_exists($path)) {
            return $path;
        }

        $path = APPLICATION . '/System/' . $remainder;
        if (file_exists($path)) {
            return $path;
        }

        if (substr($remainder, 0, strlen('/View/')) == '/View/') {
            $path = VENDOR_MOLAJO_FOLDER . '/Mvc/' . $remainder;
            if (file_exists($path)) {
                return $path;
            }
        }

        throw new ResourcesException
        ('Configuration: locateFile() Cannot find Model Type '
        . $this->model_type . ' Model Name ' . $this->model_name);
    }
}
