<?php
/**
 * Create Event Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Plugin\Api;

use Molajo\Plugin\Exception\CreateEventException;

/**
 * Create Event Interface
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Interface CreateEventInterface
{
    /**
     * Pre-create processing
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\CreateEventException
     */
    public function onBeforeCreate();

    /**
     * Post-create processing
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\CreateEventException
     */
    public function onAfterCreate();
}
