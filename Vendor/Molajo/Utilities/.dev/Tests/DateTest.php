<?php
/**
 * Date Test
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Utilities\Test;


use Molajo\Utilities\Date;

/**
 * Utilities Test
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class DateTest extends \PHPUnit_Framework_TestCase
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
    public function testGetDate()
    {
        //date_default_timezone_set('America/Chicago');

        $date = new Date();
        $test = $date->getDate();

        $this->assertEquals(true, is_string($test));

        return $this;
    }

    /**
     * Tests the Getter and Setter
     *
     * @return  $this
     * @since   1.0
     */
    public function testGet()
    {
        //date_default_timezone_set('America/Chicago');

        $date = new Date();
        $date->set('offset_server', - 5);

        $test = $date->get('offset_server', - 5);


        $this->assertEquals(- 5, $test);

        return $this;
    }

    /**
     * Test Utilities Connection
     *
     * @return  $this
     * @since   1.0
     */
    public function testGetDate2()
    {
        $date = new Date();
        $test = $date->getDate('1961-09-17');

        $value = '1961-09-17 00:00:00';
        $this->assertEquals($value, $test);

        return $this;
    }

    /**
     * Test Utilities Connection
     *
     * @return  $this
     * @since   1.0
     */
    public function testConvertCCYYMMDD()
    {
        $date = new Date();
        $test = $date->getDate('1961-09-17');

        $results = $date->convertCCYYMMDD($test);

        $value = '1961-09-17';
        $this->assertEquals($value, $results);

        return $this;
    }

    /**
     *  Get the number of days between two dates
     *
     * @return  $this
     * @since   1.0
     */
    public function testGetNumberofDaysAgo()
    {
        $date  = new Date();
        $date1 = $date->getDate('1961-09-17');
        $date2 = $date->getDate('1962-09-17');

        $days = $date->getNumberofDaysAgo($date1, $date2);

        $value = 365;
        $this->assertEquals($value, $days);

        return $this;
    }

    /**
     *  Get the number of days between two dates
     *
     * @return  $this
     * @since   1.0
     */
    public function testGetPrettyDate()
    {
        $date  = new Date();
        $date1 = $date->getDate('1961-09-17');
        $date2 = $date->getDate('1962-09-17');

        $days = $date->getPrettyDate($date1, $date2);

        $value = '1 year ago';
        $this->assertEquals($value, $days);

        $date  = new Date();
        $date1 = $date->getDate('1961-09-17 00:00:00');
        $date2 = $date->getDate('1961-09-17 00:05:00');

        $days = $date->getPrettyDate($date1, $date2);

        $value = '5 minutes ago';
        $this->assertEquals($value, $days);


        $date  = new Date();
        $date1 = $date->getDate('1961-09-16 00:00:00');
        $date2 = $date->getDate('1961-09-17 00:00:00');

        $days = $date->getPrettyDate($date1, $date2);

        $value = 'Yesterday';
        $this->assertEquals($value, $days);

        return $this;
    }

    /**
     *  Get the name of the day
     *
     *  Tests translate and getDayName
     *
     * @return  $this
     * @since   1.0
     */
    public function testGetDayName()
    {
        $date    = new Date();
        $dayname = $date->getDayName(1);

        $value = 'Monday';
        $this->assertEquals($value, $dayname);

        $date    = new Date();
        $dayname = $date->getDayName(7, true);

        $value = 'Sun';
        $this->assertEquals($value, $dayname);

        return $this;
    }

    /**
     *  Get the number of days between two dates
     *
     * Tests translate and getMonthName
     *
     * @return  $this
     * @since   1.0
     */
    public function testGetMonthName()
    {
        $date      = new Date();
        $monthname = $date->getMonthName(1);

        $value = 'January';
        $this->assertEquals($value, $monthname);

        $date      = new Date();
        $monthname = $date->getMonthName(7, true);

        $value = 'Jul';
        $this->assertEquals($value, $monthname);

        return $this;
    }

    /**
     *  Get the number of days between two dates
     *
     * Tests translate and getMonthName
     *
     * @return  $this
     * @since   1.0
     */
    public function testBuildCalendar()
    {
        $date     = new Date();
        $calendar = $date->buildCalendar(12, 2013);

        $this->assertEquals('S', $calendar[0]->days_of_week[0]);
        $this->assertEquals('31', $calendar[0]->number_of_days);
        $this->assertEquals('December', $calendar[0]->month_name);
        $this->assertEquals(0, $calendar[0]->day_of_week_number);
        $this->assertEquals('Sunday', $calendar[0]->day_of_week_name);

        return $this;
    }
}
