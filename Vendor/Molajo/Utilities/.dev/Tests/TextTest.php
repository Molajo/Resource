<?php
/**
 * Text Test
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Utilities\Test;


use Molajo\Utilities\Text;

/**
 * Utilities Test
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class TextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Options
     *
     * @var    array
     * @since  1.0
     */
    protected $options;

    /**
     * Setup testing
     *
     * @return  $this
     * @since   1.0
     */
    protected function setUp()
    {
        return $this;
    }

    /**
     * Test Utilities Connection
     *
     * @return  $this
     * @since   1.0
     */
    public function testGetText()
    {
        //date_default_timezone_set('America/Chicago');

        $date = new Text();
        $test = $date->getPlaceHolderText(
            $number_of_paragraphs = 3,
            $words_per_paragraph = 7,
            $markup_type = 'p',
            $start_with_lorem_ipsum = true
        );

        echo '<pre>';
        var_dump($test);

        // $this->assertEquals(true, is_string($test));

        return $this;
    }

}
