<?php
/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Plugin\Alias;

use Molajo\Plugin\CreateEventPlugin;
use Molajo\Plugin\Api\CreateEventInterface;
use Molajo\Plugin\Api\UpdateEventInterface;

/**
 * Alias
 *
 * @package     Molajo
 * @license     MIT
 * @since       1.0
 */
class AliasPlugin extends CreateEventPlugin implements CreateEventInterface, UpdateEventInterface
{
    /**
     * Pre-create processing
     *
     * @return  $this
     * @since   1.0
     */
    public function onBeforeCreate()
    {
        //unique
        return true;
    }

    /**
     * Pre-update processing
     *
     * @return  $this
     * @since   1.0
     */
    public function onBeforeUpdate()
    {
        //reserved words - /edit
        return true;
    }
}
