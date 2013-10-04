<?php
/**
 * Resource Controller Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Controller\Api;

/**
 * Resource Controller Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface ResourceControllerInterface
{
    /**
     * Get Resource, Theme and View Data for Page Type and other Route Data
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ResourceControllerException
     */
    public function getResources();

    /**
     * Retrieve Theme Metadata
     *
     * @param   int $theme_id
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ResourceControllerException
     */
    public function getTheme($theme_id);

    /**
     * Retrieve Page View Metadata
     *
     * @param   int $page_view_id
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ResourceControllerException
     */
    public function getPageView($page_view_id);

    /**
     * Retrieve Template View Metadata
     *
     * @param   int $template_view_id
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ResourceControllerException
     */
    public function getTemplateView($template_view_id);

    /**
     * Retrieve Wrap View Metadata
     *
     * @param   int $wrap_view_id
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ResourceControllerException
     */
    public function getWrapView($wrap_view_id);
}
