<?php
/**
 * Read Controller Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Controller\Api;

use Molajo\Controller\Exception\ReadControllerException;

/**
 * Read Controller Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface ReadControllerInterface
{
    /**
     * Method to get data from model
     *
     * @return  mixed
     * @since   1.0
     * @throws  ReadControllerException
     */
    public function getData();

    /**
     * Schedule onBeforeRead Event
     *
     * - Model Query has been developed and is passed into the event, along with parameters and registry data
     *
     * - Good event for modifying selection criteria, like adding tag selectivity, or setting publishing criteria
     *
     * - Examples: Publishedstatus
     *
     * @return  $this
     * @since   1.0
     */
    public function onBeforeReadEvent();

    /**
     * Schedule Event onAfterRead Event
     *
     * - After the Query executes, the results of the query are sent through the plugins, one at a time
     *  (this event -- and each of the associated plugins -- run one time for each record returned)
     *
     * - Good time to schedule content modifying plugins, like smilies or image placement.
     *      Examples: Smilies, Images, Linebreaks
     *
     * - Additional data elements can be added to the row -- codes can be expanded into textual descriptions
     *  or profile data added for author, etc.
     *      Examples: Author, CSSclassandids, Gravatar, Dateformats, Email
     *
     * - Use Event carefully as it has perhaps the most potential to negatively impact performance.
     *
     * @return  $this
     * @since   1.0
     */
    public function onAfterReadEvent();

    /**
     * Schedule Event onAfterRead Event
     *
     *  - entire query results passed in as an array
     *
     *  - Good event for inserting an include statement based on the results (maybe a begin and end form)
     *      or when the entire resultset must be handled, like generating a Feed, or JSON output,
     *
     *  - Examples: CssclassandidsPlugin, Pagination, Paging, Useractivity
     *
     * @return  $this
     * @since   1.0
     */
    public function onAfterReadallEvent();
}
