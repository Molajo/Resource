<?php
/**
 * Render Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Render\Api;

use Molajo\Render\Exception\RenderException;

/**
 * Render Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface RenderInterface
{
    /**
     * Render Theme which includes Page
     *
     * @since   1.0
     * @return  string
     */
    public function renderTheme();

    /**
     * Parse rendered output looking for tags
     *
     * @param   string $rendered_output
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\Render\Exception\RenderException
     */
    public function parse($rendered_output);

    /**
     * Process tag discovered during parsing and capture rendered output
     *
     * @param   object $data
     * @param   string $template
     * @param   array  $options
     *
     * @return  string
     * @since   1.0
     * @throws  \Molajo\Render\Exception\RenderException
     */
    public function render($data, $options = array());

    /**
     * Replace the tag discovered during parsing with the associated rendered output
     *
     * @param   string $tag
     * @param   string $rendered_output
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Render\Exception\RenderException
     */
    public function inject($tag, $rendered_output);
}
