<?php
/**
 * Abstract Render Handler
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Render\Handler;

use Molajo\Render\Api\RenderInterface;
use Molajo\Render\Exception\RenderException;

/**
 * Abstract Render Handler
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
abstract class AbstractHandler implements RenderInterface
{
    /**
     * System defined order for processing includes
     *
     * @var    array
     * @since  1.0
     */
    protected $sequence = array();

    /**
     * Final include types
     *
     * @var    array
     * @since  1.0
     */
    protected $final = array();

    /**
     * Exclude from parsing
     *
     * @var    array
     * @since  1.0
     */
    protected $exclude_until_final = array();

    /**
     * Resources
     *
     * @var    object
     * @since  1.0
     */
    protected $resources;

    /**
     * Parameters
     *
     * @var    array
     * @since  1.0
     */
    protected $parameters;

    /**
     * Final processing for includes
     *
     * @var    boolean
     * @since  1.0
     */
    protected $final_indicator = false;

    /**
     * Include Statements discovered during parsing
     *
     * @var    array
     * @since  1.0
     */
    protected $include_statements = array();

    /**
     * Accumulated rendered output
     *
     * @var    array
     * @since  1.0
     */
    protected $rendered_output = null;

    /**
     * Constructor
     *
     * @param  array  $sequence
     * @param  array  $final
     * @param  object $resources
     * @param  object $parameters
     * @param  object $rendering_extensions
     *
     * @since   1.0
     */
    public function __construct(
        array $sequence,
        array $final,
        $resources,
        $parameters,
        $rendering_extensions
    ) {
        $this->sequence             = $sequence;
        $this->final                = $final;
        $this->resources            = $resources;
        $this->parameters           = $parameters;
        $this->rendering_extensions = $rendering_extensions;
    }

    /**
     * Render Theme which includes Page
     *
     * @since   1.0
     * @return  string
     */
    public function renderTheme()
    {
        $model = 'Molajo//Theme//'
            . $this->parameters->resource->theme->title
            . '//Index.phtml';

        ob_start();

        $theme = $this->resources->get(
            'theme:///' . $model,
            array('Parameters' => $this->parameters)
        );

        ob_get_clean();

        return $theme;
    }
}
