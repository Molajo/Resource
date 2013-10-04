<?php
/**
 * Template Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Theme\Api;

use Molajo\Theme\Exception\TemplateException;

/**
 * Template Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface TemplateInterface
{
    /**
     * Define the variables that identified Variables
     *
     * @param   array $tags
     *
     * @return  $this
     * @since   1.0
     * @throws  TemplateException
     */
    public function defineTags($tags = array());

    /**
     * Parse rendered output for tags
     *
     * @param   string $rendered_output
     *
     * @return  array
     * @since   1.0
     * @throws  TemplateException
     */
    public function parseTags($rendered_output);

    /**
     * Push the input into the Template and capture rendered output
     *
     * Includes escaping rendered output and formatting requirements
     *
     * @param   object $data
     * @param   string $template
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0
     * @throws  TemplateException
     */
    public function renderOutput($data, $template, $options = array());

    /**
     * Locate the tag that was used as a basic for retrieving input data and the template
     *  and replace it with the rendered output for that tag
     *
     * @param   string $tag
     * @param   string $rendered_output
     *
     * @return  $this
     * @since   1.0
     * @throws  TemplateException
     */
    public function replaceTags($tag, $rendered_output);

    /**
     * Remove Comments found within the rendered output
     *
     * @return  $this
     * @since   1.0
     * @throws  TemplateException
     */
    public function removeComments();
}
