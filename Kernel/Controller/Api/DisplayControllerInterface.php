<?php
/**
 * Display Controller Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Controller\Api;

use Molajo\Controller\Exception\DisplayControllerException;

/**
 * Display Controller Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface DisplayControllerInterface
{
    /**
     * Display Controller
     *
     * Interact with the model to connect to a data object, execute a query, schedule events, render theme
     *  output, push data into the views and returned rendered output to Theme Includer.
     *
     * @return  string  Rendered output
     * @since   1.0
     * @throws  DisplayControllerException
     */
    public function execute();

    /**
     * RenderTheme is first output rendered, driven by Theme Includer, and the source of
     *  include statements during parsing. All rendered output is recursively scanned for include statements.
     *  For that reason, <include type=value values can be embedded into Views and content.
     *
     * @return  $this
     * @since   1.0
     * @throws  DisplayControllerException
     */
    public function renderTheme();

    /**
     * Wrap Template View Rendered Output using specified Wrap View
     *
     * @return  $this
     * @since   1.0
     * @throws  DisplayControllerException
     */
    public function renderWrapView();

    /**
     * Two ways Template Views are rendered:
     *
     * 1. If there is a Custom.php file in the Template View folder, then all query
     *      results are pushed into the View using the $this->query_results array/object.
     *      The Custom.php View must handle it's own loop iteration, if necessary, and
     *      reference the results set via an index , ex. $this->query_results[0]->name
     *
     *      Note: neither onBeforeRenderView or onAfterRenderView are scheduled for Custom.php Views.
     *      The View can schedule this Event prior to the rendering for each row using:
     *          <?php $this->onBeforeRenderView(); ?>
     *      And following the rendering of the View for the row, using:
     *          <?php $this->onBeforeRenderView(); ?>
     *
     * 2. Otherwise, the Header.php, and/or Body.php, and/or Footer.php Template View(s)
     *      are used, with data injected into the View, one row at a time. within the views,
     *      data is referenced using the $this->row object, ex. $this->row->name
     *      Header.php (if existing) - used one time for the first row in the resultset
     *      Body.php (if existing) - once for each row within the query results
     *      Footer.php (if existing) - used one time for the last row in the resultset
     *
     * @return  $this
     * @since   1.0
     * @throws  DisplayControllerException
     */
    public function renderView();

    /**
     * Schedule Event onBeforeRenderView Event
     *
     * Useful for preprocessing of input prior to rendering or evaluation of content for
     *  possible inclusion of related information. Include statements could be added to
     *  the input, images resized, links to keywords added, blockquotes, and so on.
     *
     *  Method runs one time for each input row to View.
     *
     *  Not available to custom.php file Views since the Controller does not manage the looping
     *  in that case.
     *
     * @return  $this
     * @since   1.0
     * @throws  DisplayControllerException
     */
    public function onBeforeRenderView();

    /**
     * Schedule Event onAfterRenderView Event
     *
     * Processing follows completion of a single row rendering. Can be used to add
     *  include statement or additional information.
     *
     *  Method runs one time for each input row to View.
     *
     *  Not available to custom.php file Views since the Controller does not manage the looping
     *  in that case.
     *
     * @return  $this
     * @since   1.0
     * @throws  DisplayControllerException
     */
    public function onAfterRenderView();
}
