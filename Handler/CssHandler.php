<?php
/**
 * Css Resources
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resources\Handler;

use stdClass;
use Molajo\Resources\Api\ResourceHandlerInterface;

//todo: CSS $url_path
/**
 * Css Resources
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class CssHandler implements ResourceHandlerInterface
{
    /**
     * Collect list of CSS Files
     *
     * @var    array
     * @since  1.0
     */
    protected $css_files = array();

    /**
     * Css
     *
     * @var    array
     * @since  1.0
     */
    protected $css = array();

    /**
     * CSS Priorities
     *
     * @var    array
     * @since  1.0
     */
    protected $css_priorities = array();

    /**
     * Language Direction
     *
     * @var    string
     * @since  1.0
     */
    protected $language_direction;

    /**
     * HTML5
     *
     * @var    string
     * @since  1.0
     */
    protected $html5;

    /**
     * Line End
     *
     * @var    string
     * @since  1.0
     */
    protected $line_end;

    /**
     * Mimetype
     *
     * @var    string
     * @since  1.0
     */
    protected $mimetype;

    /**
     * Constructor
     *
     * @param  string $language_direction
     * @param  string $html5
     * @param  string $line_end
     * @param  string $mimetype
     *
     * @since  1.0
     */
    public function __construct(
        $language_direction,
        $html5,
        $line_end,
        $mimetype
    ) {
        $this->language_direction = $language_direction;
        $this->html5              = $html5;
        $this->line_end           = $line_end;
        $this->mimetype           = $mimetype;
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
        $type = '';
        if (isset($options['type'])) {
            $type = $options['type'];
        }
        $priority = '';
        if (isset($options['priority'])) {
            $priority = $options['priority'];
        }
        $mimetype = '';
        if (isset($options['mimetype'])) {
            $mimetype = $options['mimetype'];
        }
        $media = '';
        if (isset($options['media'])) {
            $media = $options['media'];
        }
        $conditional = '';
        if (isset($options['conditional'])) {
            $conditional = $options['conditional'];
        }
        $attributes = array();
        if (isset($options['attributes'])) {
            $attributes = $options['attributes'];
        }

        if ($type == 'folder') {
            $this->addCssFolder(
                $located_path,
                $priority
            );

        } else {
            $this->addCss(
                $located_path,
                $priority,
                $mimetype,
                $media,
                $conditional,
                $attributes
            );
        }

        if ($located_path === false) {
            return;
        }

        if (file_exists($located_path)) {
            require $located_path;

            return;
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
        $temp = $this->css;

        if (is_array($temp) && count($temp) > 0) {
        } else {
            return array();
        }

        $priorities = $this->css_priorities;
        sort($priorities);

        $query_results = array();

        foreach ($priorities as $priority) {

            foreach ($temp as $temp_row) {

                $include = false;

                if (isset($temp_row->priority)) {
                    if ($temp_row->priority == $priority) {
                        $include = true;
                    }
                }

                if ($include === false) {
                } else {
                    $temp_row->application_html5 = $this->html5;
                    $temp_row->end               = $this->line_end;
                    $temp_row->page_mimetype     = $this->mimetype;
                    $query_results[]             = $temp_row;
                }
            }
        }

        return $query_results;
    }

    /**
     * addCssFolder - Loads the CS located within the folder
     *
     * @param   string  $file_path
     * @param   integer $priority
     *
     * @return  $this
     * @since   1.0
     */
    public function addCssFolder($file_path, $priority = 500)
    {
        if (is_dir($file_path . '/css')) {
        } else {
            return $this;
        }

        $url_path = $this->getUrlPath($file_path);

        $files = scandir($file_path);

        if (count($files) > 0) {

            foreach ($files as $file) {

                if ($file == 1) {
                    $add = 0;
                }

                if (substr($file, 0, 4) == 'ltr_') {
                    if ($this->language_direction == 'rtl') {
                    } else {
                        $add = 1;
                    }

                } elseif (substr($file, 0, 4) == 'rtl_') {

                    if ($this->language_direction == 'rtl') {
                        $add = 1;
                    }

                } elseif (strtolower(substr($file, 0, 4)) == 'hold') {

                } else {
                    $add = 1;
                }

                if ($add == 1) {
                    $this->addCss($url_path . '/css/' . $file, $priority);
                }
            }
        }

        return $this;
    }

    /**
     * addCss - Adds a linked stylesheet to the page
     *
     * @param   string $file_path
     * @param   int    $priority
     * @param   string $mimetype
     * @param   string $media
     * @param   string $conditional
     * @param   array  $attributes
     *
     * @return  mixed
     * @since   1.0
     */
    public function addCss(
        $file_path,
        $priority = 500,
        $mimetype = 'text/css',
        $media = '',
        $conditional = '',
        $attributes = array()
    ) {
        $css = $this->css;

        $url_path = $this->getUrlPath($file_path);

        foreach ($css as $item) {

            if ($item->url == $url_path
                && $item->mimetype == $mimetype
                && $item->media == $media
                && $item->conditional == $conditional
            ) {
                return $this;
            }
        }

        $temp_row = new stdClass();

        $temp_row->url         = $url_path;
        $temp_row->priority    = $priority;
        $temp_row->mimetype    = $mimetype;
        $temp_row->media       = $media;
        $temp_row->conditional = $conditional;
        $temp_row->attributes  = trim(implode(' ', $attributes));

        $css[] = $temp_row;

        $this->css = $css;

        $priorities = $this->css_priorities;

        if (in_array($priority, $priorities)) {
        } else {
            $priorities[] = $priority;
        }

        sort($priorities);

        $this->css_priorities = $priorities;

        return $this;
    }

    /**
     * getUrlPath
     *
     * @param   $file_path
     *
     * @return  $this
     * @since   1.0
     */
    public function getUrlPath($file_path)
    {
        $url_path = $file_path;

        return $url_path;
    }
}
