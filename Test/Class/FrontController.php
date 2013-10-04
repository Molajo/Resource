<?php
/**
 * Front Controller
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo;

/**
 * Front Controller
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class FrontController
{
    /**
     * Injected Value to be displayed by the Function
     *
     * @since   1.0
     */
    public $injected_value;

    /**
     * Constructor
     *
     * @param  array $options
     *
     * @since  1.0
     */
    public function __construct(
        $injected_value = 0
    ) {
        $this->injected_value = $injected_value;
    }

    /**
     * Method to pass in Function 1 -
     *
     *  Function 1 works
     *
     * @return  $this
     * @since   1.0
     */
    public function test1($function1)
    {
        $function1();

        return $this;
    }

    /**
     * Method to pass in Function 2 -
     *
     *  Function 2 also works but it generates a PHP Notice for an undefined variable
     *
     * @return  $this
     * @since   1.0
     */
    public function test2($function2)
    {
        $function2();

        return $this;
    }
}
