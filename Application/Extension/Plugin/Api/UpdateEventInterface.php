<?php
/**
 * Update Event Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Plugin\Api;

use Molajo\Plugin\Exception\UpdateEventException;

/**
 * Update Event Interface
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
Interface UpdateEventInterface
{
    /**
     * Before update processing
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\UpdateEventException
     */
    public function onBeforeUpdate();

    /**
     * After update processing
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\UpdateEventException
     */
    public function onAfterUpdate();
}
