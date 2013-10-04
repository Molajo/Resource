<?php
/**
 * Display Event Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Plugin\Api;

use Molajo\Plugin\Exception\DisplayEventException;

/**
 * Display Event Interface
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Interface DisplayEventInterface
{
    /**
     * After Route and Authorisation, the Theme/Page are parsed
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\DisplayEventException
     */
    public function onBeforeParse();

    /**
     * After the body render is complete and before the document head rendering starts
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\DisplayEventException
     */
    public function onBeforeParseHead();

    /**
     * After the Read Query has executed but Before Query results are injected into the View
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\DisplayEventException
     */
    public function onBeforeRenderView();

    /**
     * After the View has been rendered but before the output has been passed back to the Includer
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\DisplayEventException
     */
    public function onAfterRenderView();

    /**
     * On after parsing and rendering is complete
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\DisplayEventException
     */
    public function onAfterParse();
}
