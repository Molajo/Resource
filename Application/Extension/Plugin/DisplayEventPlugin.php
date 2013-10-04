<?php
/**
 * Plugin
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Plugin;

use OutOfRangeException;

use Molajo\Plugin\Api\DisplayEventInterface;

/**
 * Base Plugin Class
 *
 * At various points in the Application, Events are Scheduled and data passed to the Event Service.
 *
 * The Event Service triggers all Plugins registered for the Event, one at a time, passing the
 * data into this Plugin.
 *
 * As each triggered Plugin finishes, the Event Service retrieves the class properties, returning
 * the values to the scheduling process.
 *
 * The base plugin takes care of getting and setting values and providing connectivity to Helper
 * functions and other commonly used data and connections.
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class DisplayEventPlugin extends AbstractPlugin implements DisplayEventInterface
{
    /**
     * After Route and Authorisation, the Theme/Page are parsed
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\DisplayEventException
     */
    public function onBeforeParse()
    {

    }

    /**
     * After the body render is complete and before the document head rendering starts
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\DisplayEventException
     */
    public function onBeforeParseHead()
    {

    }

    /**
     * After the Read Query has executed but Before Query results are injected into the View
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\DisplayEventException
     */
    public function onBeforeRenderView()
    {

    }

    /**
     * After the View has been rendered but before the output has been passed back to the Includer
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\DisplayEventException
     */
    public function onAfterRenderView()
    {

    }

    /**
     * On after parsing and rendering is complete
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\DisplayEventException
     */
    public function onAfterParse()
    {

    }
}
