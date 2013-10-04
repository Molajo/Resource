<?php
/**
 * Css Declarations Resources
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resources\Handler;

use stdClass;
use Molajo\Resources\Api\ResourceHandlerInterface;

/**
 * Css Declarations Resources
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class CssdeclarationsHandler implements ResourceHandlerInterface
{
    /**
     * Css Declarations
     *
     * @var    array
     * @since  1.0
     */
    protected $css = array();

    /**
     * CSS Declarations Priorities
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
     * @param   string $scheme
     * @param   string $located_path
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function handlePath($scheme, $located_path, array $options = array())
    {
        $css = '';
        if (isset($options['css'])) {
            $css = $options['css'];
        }
        $priority = 500;
        if (isset($options['priority'])) {
            $priority = $options['priority'];
        }
        $mimetype = 'text/css';
        if (isset($options['mimetype'])) {
            $mimetype = $options['mimetype'];
        }

        $temp_row = new stdClass();

        $temp_row->mimetype = $mimetype;
        $temp_row->content  = $css;
        $temp_row->priority = $priority;

        $this->css[] = $temp_row;

        $this->css_priorities[] = $priority;
        sort($priorities);

        return;
    }

    /**
     * Retrieve a collection of a specific handler
     *
     * @param   string $scheme
     * @param   array  $options
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
}
