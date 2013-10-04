<?php
/**
 * Update Event Plugin
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Plugin;

use Molajo\Plugin\Api\UpdateEventInterface;

/**
 * Create Event Plugin
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class UpdateEventPlugin extends AbstractPlugin implements UpdateEventInterface
{
    /**
     * Before update processing
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\UpdateEventException
     */
    public function onBeforeUpdate()
    {

    }

    /**
     * After update processing
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Plugin\Exception\UpdateEventException
     */
    public function onAfterUpdate()
    {

    }
}
