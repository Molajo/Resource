<?php
/**
 * Test
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo\Plugins;

use Molajo\A\CatInterface;
use Molajo\A\DogInterface;
use Molajo\A\Z\ZebraInterface;

/**
 * Mouse Plugin
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class MousePlugin implements CatInterface, DogInterface
{
    /**
     * Class Constructor
     *
     * @param  object  CatInterface
     * @param  object  DogInterface
     * @param  object  ZebraInterface
     *
     * @since  1.0
     */
    public function __construct(
        CatInterface $cat = null,
        DogInterface $dog = null,
        ZebraInterface $zebra = null
    ) {

    }

    /**
     * Event
     *
     * @return  $this
     * @since   1.0.0
     */
    public function onBeforeSqueak()
    {

    }

    /**
     * Furry
     *
     * @return  $this
     * @since   1.0.0
     */
    public function furry()
    {

    }

    /**
     * Spotted
     *
     * @return  $this
     * @since   1.0.0
     */
    public function squeak()
    {

    }

    /**
     * Spotted
     *
     * @return  $this
     * @since   1.0.0
     */
    public function spotted()
    {

    }

    /**
     * Tail
     *
     * @return  $this
     * @since   1.0.0
     */
    public function tail()
    {

    }
}
