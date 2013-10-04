<?php
/**
 * Utilities Connection Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Utilities\Api;

use DateTime;
use Molajo\Utilities\Exception\DateException;

/**
 * Date Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 * @api
 */
interface DateInterface
{
    /**
     * Get the current value (or default) of the specified key
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  DateException
     */
    public function get($key = null, $default = null);

    /**
     * Set the value of the specified key
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  $this
     * @since   1.0
     * @throws  DateException
     */
    public function set($key, $value = null);

    /**
     * Returns a PHP Date object with TZ for user or server, if set, or defaults to UTC
     *
     * @param   string $time
     * @param   null   $time_zone_offset
     * @param   string $server_or_user_utc
     * @param   string $date_format
     *
     * @return  DateTime
     * @since   1.0
     */
    public function getDate(
        $time = 'now',
        $time_zone_offset = null,
        $server_or_user_utc = 'user',
        $date_format = 'Y-m-d H:i:s'
    );

    /**
     * Convert Date to String
     *
     * @return  string
     * @since   1.0
     */
    public function __toString();

    /**
     * Converts standard MYSQL date (ex. 2011-11-11 11:11:11) to CCYY-MM-DD format (ex. 2011-11-11)
     *
     * @param   string $date
     *
     * @return  string CCYY-MM-DD
     * @since   1.0
     */
    public function convertCCYYMMDD($date = null);

    /**
     * Get the number of days between two dates
     *
     * @param string $date1 expressed as CCYY-MM-DD
     * @param string $date2 expressed as CCYY-MM-DD
     *
     * @since   1.0
     * @return integer
     */
    public function getNumberofDaysAgo($date1, $date2 = null);

    /**
     * getPrettyDate
     *
     * @param  string $source
     * @param  string $compare
     *
     * @return string formatted pretty date
     * @since   1.0
     */
    public function getPrettyDate($source, $compare = null);

    /**
     * Provides translated name of day in abbreviated or full format, given day number
     *
     * @param   string $day_number
     * @param   bool   $abbreviation
     *
     * @return  string
     * @since   1.0
     */
    public function getDayName($day_number, $abbreviation = false);

    /**
     * Provides translated name of month in abbreviated or full format, given month number
     *
     * @param string $month_number
     * @param bool   $abbreviation
     *
     * @return string
     * @since   1.0
     */
    public function getMonthName($month_number, $abbreviation = false);

    /**
     * buildCalendar
     *
     * $d = getdate();
     * $month = $d['mon'];
     * $year = $d['year'];
     *
     * $calendar = Services::Date()->buildCalendar ($month, $year);
     *
     * @param string $month
     * @param string $year
     *
     * @return string CCYY-MM-DD
     * @since   1.0
     */
    public function buildCalendar($month, $year);
}
