<?php
/**
 * Delete Event Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Plugin\Api;

use Molajo\Plugin\Exception\DeleteEventException;

/**
 * Delete Event Interface
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Interface DeleteEventInterface
{
    /**
     * Before delete processing
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\DeleteEventException
     */
    public function onBeforeDelete();

    /**
     * After delete processing
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\DeleteEventException
     */
    public function onAfterDelete();
}
