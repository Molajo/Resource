<?php
/**
 * Pagination Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Pagination\Api;

/**
 * Pagination Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface PaginationInterface
{
    /**
     * Get the first page number (always page=1)
     *
     * @return  int
     * @since   1.0
     */
    public function getFirstPage();

    /**
     * Get the page number previous to the current page
     *
     * @return  int
     * @since   1.0
     */
    public function getPrevPage();

    /**
     * Get the first page number to use when looping through the display page number buttons
     *
     * @return  int
     * @since   1.0
     */
    public function getStartDisplayPage();

    /**
     * Get the current page number
     *
     * @return  int
     * @since   1.0
     */
    public function getCurrentPage();

    /**
     * Get the last page number to use when looping through the display page number buttons
     *
     * @return  int
     * @since   1.0
     */
    public function getStopDisplayPage();

    /**
     * Get the page number following the current page
     *
     * @return  int
     * @since   1.0
     */
    public function getNextPage();

    /**
     * Get the final page number
     *
     * @return  int
     * @since   1.0
     */
    public function getLastPage();

    /**
     * Get data paginated
     *
     * @return  array
     * @since   1.0
     */
    public function getData();

    /**
     * Get the total number of items in the recordset (not just those displayed on the page)
     *
     * @return  int
     * @since   1.0
     */
    public function getTotalItems();

    /**
     * Get the URL for the specified key
     *
     *  - Use a numeric page number
     *  - Or, use a specific key value: first, previous, current, next, or last
     *
     * @param   mixed $page
     *
     * @return  string
     * @since   1.0
     */
    public function getPageUrl($page);
}
