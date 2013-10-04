<?php
/**
 * Adapter for Render
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Render;

use Molajo\Render\Api\RenderInterface;
use Molajo\Render\Exception\RenderException;

/**
 * Adapter for Render
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class Adapter implements RenderInterface
{
    /**
     * Render Adapter
     *
     * @var     object  Molajo\Render\Api\RenderInterface
     * @since  1.0
     */
    protected $adapter = null;

    /**
     * Class Constructor
     *
     * @param   RenderInterface $adapter
     *
     * @since   1.0
     */
    public function __construct(
        RenderInterface $adapter
    ) {
        $this->adapter = $adapter;
    }

    /**
     * Render Theme which includes Page
     *
     * @since   1.0
     * @return  string
     */
    public function renderTheme()
    {
        return $this->adapter->renderTheme();
    }

    /**
     * Parse rendered output looking for tags
     *
     * @param   string $rendered_output
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\Render\Exception\RenderException
     */
    public function parse($rendered_output)
    {
        return $this->adapter->parse($rendered_output);
    }

    /**
     * Process tag discovered during parsing and capture rendered output
     *
     * @param   object $data
     * @param   array  $options
     *
     * @return  string
     * @since   1.0
     * @throws  \Molajo\Render\Exception\RenderException
     */
    public function render($data, $options = array())
    {
        return $this->adapter->render($data, $options);
    }

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
    public function inject($tag, $rendered_output)
    {
        return $this->adapter->inject($tag, $rendered_output);
    }
}
