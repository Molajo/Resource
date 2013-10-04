<?php
/**
 * Number to Text Test
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\NumberToText\Text;

use Molajo\NumberToText\Translations\Translate;
use Molajo\NumberToText\Utility as NumberToText;

/**
 * Number to Text Testing
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class TextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Locale Instance
     *
     * @var    array
     * @since  1.0
     */
    protected $locale_instance;

    /**
     * Number To Text Instance
     *
     * @var    array
     * @since  1.0
     */
    protected $numberToText_instance;

    /**
     * Setup testing
     *
     * @return  $this
     * @since   1.0
     */
    protected function setUp()
    {
        $this->locale_instance       = new Translate('en-GB');
        $this->numberToText_instance = new NumberToText($this->locale_instance);
    }

    /**
     * Test Number to Text Conversion for 1
     *
     * @return  $this
     * @since   1.0
     */
    public function testTranslate1()
    {
        $results = $this->numberToText_instance->convert(1);
        $this->assertEquals('one', $results);
        return $this;
    }

    /**
     * Test Number to Text Conversion for 10
     *
     * @return  $this
     * @since   1.0
     */
    public function testTranslate10()
    {
        $results = $this->numberToText_instance->convert(10);
        $this->assertEquals('ten', $results);
        return $this;
    }

    /**
     * Test Number to Text Conversion for 11
     *
     * @return  $this
     * @since   1.0
     */
    public function testTranslate11()
    {
        $results = $this->numberToText_instance->convert(11);
        $this->assertEquals('eleven', $results);
        return $this;
    }

    /**
     * Test Number to Text Conversion for 30
     *
     * @return  $this
     * @since   1.0
     */
    public function testTranslate30()
    {
        $results = $this->numberToText_instance->convert(30);
        $this->assertEquals('thirty', $results);
        return $this;
    }

    /**
     * Test Number to Text Conversion for 31
     *
     * @return  $this
     * @since   1.0
     */
    public function testTranslate31()
    {
        $results = $this->numberToText_instance->convert(31);
        $this->assertEquals('thirty one', $results);
        return $this;
    }

    /**
     * Test Number to Text Conversion for 900
     *
     * @return  $this
     * @since   1.0
     */
    public function testTranslate900()
    {
        $results = $this->numberToText_instance->convert(900);
        $this->assertEquals('nine hundred', $results);
        return $this;
    }

    /**
     * Test Number to Text Conversion for 10,000
     *
     * @return  $this
     * @since   1.0
     */
    public function testTranslate10000()
    {
        $results = $this->numberToText_instance->convert(10000);
        $this->assertEquals('ten thousand', $results);
        return $this;
    }

    /**
     * Test Number to Text Conversion for 4,000,000,000.02
     *
     * @return  $this
     * @since   1.0
     */
    public function testTranslate400000002()
    {
        $results = $this->numberToText_instance->convert(400000002);
        $this->assertEquals('four hundred million, two', $results);
        return $this;
    }

    /**
     * Test Number to Text Conversion for 4,000,000,000.02
     *
     * @return  $this
     * @since   1.0
     */
    public function testTranslateTrillion()
    {
        $results = $this->numberToText_instance->convert(400000000000000);
        $this->assertEquals('four hundred trillion', $results);
        return $this;
    }
}
