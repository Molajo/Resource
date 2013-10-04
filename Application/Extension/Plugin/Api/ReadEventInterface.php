<?php
/**
 * Read Event Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Plugin\Api;

use Molajo\Plugin\Exception\ReadEventException;

/**
 * Read Event Interface
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Interface ReadEventInterface
{
    /**
     * Pre-read processing
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\ReadEventException
     */
    public function onBeforeRead();

    /**
     * Post-read processing - one row at a time
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\ReadEventException
     */
    public function onAfterRead();

    /**
     * Post-read processing - all rows at one time from query_results
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\ReadEventException
     */
    public function onAfterReadall();
}
