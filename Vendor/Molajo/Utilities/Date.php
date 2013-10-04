<?php
/**
 * Date Service
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Utilities;

use stdClass;
use Datetime;
use Molajo\Utilities\Api\DateInterface;
use Molajo\Utilities\Exception\DateException;

/**
 * Date Service
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Date extends DateTime implements DateInterface
{
    /** minute in seconds */
    const MINUTE = 60;

    /** hour in seconds */
    const HOUR = 3600;

    /** day in seconds */
    const DAY = 86400;

    /** week in seconds */
    const WEEK = 604800;

    /** average month in seconds */
    const MONTH = 2629800;

    /** average year in seconds */
    const YEAR = 31557600;

    /**
     * Locale
     *
     * @var    string
     * @since  1.0
     */
    protected $locale;

    /**
     * Offset for User Time zone
     *
     * @var    string
     * @since  1.0
     */
    protected $offset_user = 'UTC';

    /**
     * Offset for Server Time zone
     *
     * @var    string
     * @since  1.0
     */
    protected $offset_server = 'UTC';

    /**
     * Translation Strings for Dates
     *
     * @var    array
     * @since  1.0
     */
    protected $date_translate_array = array
    (
        'AGO'                  => 'ago',
        'DATE_MINUTE_SINGULAR' => 'minute',
        'DATE_MINUTE_PLURAL'   => 'minutes',
        'DATE_HOUR_SINGULAR'   => 'hour',
        'DATE_HOUR_PLURAL'     => 'hours',
        'DATE_DAY_SINGULAR'    => 'day',
        'DATE_DAY_PLURAL'      => 'days',
        'DATE_WEEK_SINGULAR'   => 'week',
        'DATE_WEEK_PLURAL'     => 'weeks',
        'DATE_MONTH_SINGULAR'  => 'month',
        'DATE_MONTH_PLURAL'    => 'months',
        'DATE_YEAR_SINGULAR'   => 'year',
        'DATE_YEAR_PLURAL'     => 'years',
        'DATE_MON'             => 'Mon',
        'DATE_MONDAY'          => 'Monday',
        'DATE_TUE'             => 'Tue',
        'DATE_TUESDAY'         => 'Tuesday',
        'DATE_WED'             => 'Wed',
        'DATE_WEDNESDAY'       => 'Wednesday',
        'DATE_THU'             => 'Thu',
        'DATE_THURSDAY'        => 'Thursday',
        'DATE_FRI'             => 'Fri',
        'DATE_FRIDAY'          => 'Friday',
        'DATE_SAT'             => 'Sat',
        'DATE_SATURDAY'        => 'Saturday',
        'DATE_SUN'             => 'Sun',
        'DATE_SUNDAY'          => 'Sunday',
        'DATE_JAN'             => 'Jan',
        'DATE_JANUARY'         => 'January',
        'DATE_FEB'             => 'Feb',
        'DATE_FEBRUARY'        => 'February',
        'DATE_MAR'             => 'Mar',
        'DATE_MARCH'           => 'March',
        'DATE_APR'             => 'Apr',
        'DATE_APRIL'           => 'April',
        'DATE_MAY'             => 'May',
        'DATE_JUN'             => 'Jun',
        'DATE_JUNE'            => 'June',
        'DATE_JUL'             => 'Jul',
        'DATE_JULY'            => 'July',
        'DATE_AUG'             => 'Aug',
        'DATE_AUGUST'          => 'August',
        'DATE_SEP'             => 'Sep',
        'DATE_SEPTEMBER'       => 'September',
        'DATE_OCT'             => 'Oct',
        'DATE_OCTOBER'         => 'October',
        'DATE_NOV'             => 'Nov',
        'DATE_NOVEMBER'        => 'November',
        'DATE_DEC'             => 'Dec',
        'DATE_DECEMBER'        => 'December',
        'YESTERDAY'            => 'Yesterday'
    );

    /**
     * List of Properties
     *
     * @var    object
     * @since  1.0
     */
    protected $property_array = array(
        'date_translate_array',
        'locale',
        'offset_user',
        'offset_server'
    );

    /**
     * Constructor
     *
     * @param  array $options
     *
     * @since  1.0
     */
    public function __construct(array $options = array())
    {
        if (is_array($options)) {
        } else {
            $options = array();
        }

        if (count($options) > 0) {
            foreach ($this->property_array as $property) {
                if (isset($options[$property])) {
                    $this->$property = $options[$property];
                }

            }
        }

        $timezone = '';

        if (is_array($options)) {
        } else {
            $options = array();
        }

        if (isset($options['timezone'])) {
            $timezone = $options['timezone'];
        }

        if ($timezone === '') {
            if (ini_get('date.timezone')) {
                $timezone = ini_get('date.timezone');
            }
        }

        if ($timezone === '') {
            $timezone = 'UTC';
        }

        ini_set('date.timezone', $timezone);
        $options['timezone'] = $timezone;

        return $options;
    }

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
    public function get($key = null, $default = null)
    {
        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            throw new DateException
            ('Date Service: attempting to get value for unknown property: ' . $key);
        }

        $this->$key = $default;

        return $this->$key;
    }

    /**
     * Set the value of the specified key
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  mixed
     * @since   1.0
     * @throws  DateException
     */
    public function set($key, $value = null)
    {
        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            throw new DateException
            ('Date Service: attempting to set value for unknown key: ' . $key);
        }

        $this->$key = $value;

        return $this->$key;
    }

    /**
     * Prepares Date object
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
    ) {
        if ($time == '') {
            $time = 'now';
        }

        if ($time_zone_offset === null) {
            if ($server_or_user_utc == 'server') {
                $time_zone_offset = $this->get('offset_server');
            } else {
                $time_zone_offset = $this->get('offset_user');
            }
        }

        $date_time = new \DateTime($time, $time_zone_offset);

        if ($date_format == null) {
            $date_format = 'Y-m-d H:i:s';
        }
        if ($date_format == 'd-m-YY') {
            $date = strtotime($date_time->format('d-m-YY'));
        } else {
            $date = $date_time->format($date_format);
        }

        return $date;
    }

    /**
     * Convert Date to String
     *
     * @return  string
     * @since   1.0
     */
    public function __toString()
    {
        return (string)parent::format('Y-m-d H:i:s');
    }

    /**
     * Converts standard MYSQL date (ex. 2011-11-11 11:11:11) to CCYY-MM-DD format (ex. 2011-11-11)
     *
     * @param   string $date
     *
     * @return  string CCYY-MM-DD
     * @since   1.0
     */
    public function convertCCYYMMDD($date = null)
    {
        return substr($date, 0, 4) . '-' . substr($date, 5, 2) . '-' . substr($date, 8, 2);
    }

    /**
     * Get the number of days between two dates
     *
     * @param   string $date1 expressed as CCYY-MM-DD
     * @param   string $date2 expressed as CCYY-MM-DD
     *
     * @since   1.0
     * @return  integer
     */
    public function getNumberofDaysAgo($date1, $date2 = null)
    {
        if ($date2 === null) {
            $date2 = $this->convertCCYYMMDD($this->getDate());
        }

        $date1mm   = substr($date1, 5, 2);
        $date1dd   = substr($date1, 8, 2);
        $date1ccyy = substr($date1, 0, 4);

        $date1 = new \DateTime($date1mm . '/' . $date1dd . '/' . $date1ccyy);

        $date2mm   = substr($date2, 5, 2);
        $date2dd   = substr($date2, 8, 2);
        $date2ccyy = substr($date2, 0, 4);

        $date2 = new \DateTime($date2mm . '/' . $date2dd . '/' . $date2ccyy);

        $day_object = $date1->diff($date2);

        return $day_object->days;
    }

    /**
     * Get Pretty Date
     *
     * @param   string $date
     * @param   string $compare_to_date
     *
     * @return  string formatted pretty date
     * @since   1.0
     */
    public function getPrettyDate($date, $compare_to_date = null)
    {
        $source_date = new \Datetime ($date);

        if ($compare_to_date === null) {
            $compare_to_date = $this->getDate('now', null, 'user', 0);
        } else {
            $compare_to_date = new \Datetime ($compare_to_date);
        }

        $difference     = $compare_to_date->format('U') - $source_date->format('U');
        $day_difference = floor($difference / 86400);

        $pretty_date = '';

        if (is_nan($day_difference) || $day_difference < 0) {
            return '';
        }

        if ($day_difference == 0) {

            if ($difference < 60) {
                $pretty_date = $this->translate('JUST_NOW');

            } elseif ($difference < 3600) {
                $number      = floor($difference / 60);
                $pretty_date = $number . ' '
                    . $this->translatePrettyDate($number, 'DATE_MINUTE_SINGULAR', 'DATE_MINUTE_PLURAL')
                    . ' ' . $this->translate('AGO');

            } elseif ($difference < 86400) {
                $number      = floor($difference / 3660);
                $pretty_date = $number . ' '
                    . $this->translatePrettyDate($number, 'DATE_HOUR_SINGULAR', 'DATE_HOUR_PLURAL')
                    . ' ' . $this->translate('AGO');
            }

        } elseif ($day_difference == 1) {
            $pretty_date = $this->translate('YESTERDAY');

        } elseif ($day_difference < 7) {
            $number      = $day_difference;
            $pretty_date = $number . ' '
                . $this->translatePrettyDate($number, 'DATE_DAY_SINGULAR', 'DATE_DAY_PLURAL')
                . ' ' . $this->translate('AGO');

        } elseif ($day_difference < (7 * 6)) {
            $number      = ceil($day_difference / 7);
            $pretty_date = $number . ' '
                . $this->translatePrettyDate($number, 'DATE_WEEK_SINGULAR', 'DATE_WEEK_PLURAL')
                . ' ' . $this->translate('AGO');

        } elseif ($day_difference < 365) {
            $number      = ceil($day_difference / (365 / 12));
            $pretty_date = $number . ' '
                . $this->translatePrettyDate($number, 'DATE_MONTH_SINGULAR', 'DATE_MONTH_PLURAL')
                . ' ' . $this->translate('AGO');

        } else {
            $number      = ceil($day_difference / (365));
            $pretty_date = $number . ' '
                . $this->translatePrettyDate($number, 'DATE_YEAR_SINGULAR', 'DATE_YEAR_PLURAL')
                . ' ' . $this->translate('AGO');
        }

        return $pretty_date;
    }

    /**
     * Provides translated name of day in abbreviated or full format, given day number
     *
     * @param string $day_number
     * @param bool   $abbreviation
     *
     * @return string
     * @since   1.0
     */
    public function getDayName($day_number, $abbreviation = false)
    {
        switch ((int)$day_number) {
            case 1:
                if ($abbreviation === true) {
                    return $this->translate('DATE_MON');
                } else {
                    return $this->translate('DATE_MONDAY');
                }
                break;
            case 2:
                if ($abbreviation === true) {
                    return $this->translate('DATE_TUE');
                } else {
                    return $this->translate('DATE_TUESDAY');
                }
                break;
            case 3:
                if ($abbreviation === true) {
                    return $this->translate('DATE_WED');
                } else {
                    return $this->translate('DATE_WEDNESDAY');
                }
                break;
            case 4:
                if ($abbreviation === true) {
                    return $this->translate('DATE_THU');
                } else {
                    return $this->translate('DATE_THURSDAY');
                }
                break;
            case 5:
                if ($abbreviation === true) {
                    return $this->translate('DATE_FRI');
                } else {
                    return $this->translate('DATE_FRIDAY');
                }
                break;
            case 6:
                if ($abbreviation === true) {
                    return $this->translate('DATE_SAT');
                } else {
                    return $this->translate('DATE_SATURDAY');
                }
                break;
            default:
                if ($abbreviation === true) {
                    return $this->translate('DATE_SUN');
                } else {
                    return $this->translate('DATE_SUNDAY');
                }
                break;
        }
    }

    /**
     * Provides translated name of month in abbreviated or full format, given month number
     *
     * @param   string $month_number
     * @param   bool   $abbreviation
     *
     * @return  string
     * @since   1.0
     */
    public function getMonthName($month_number, $abbreviation = false)
    {
        switch ((int)$month_number) {
            case 1:
                if ($abbreviation === true) {
                    return $this->translate('DATE_JAN');
                } else {
                    return $this->translate('DATE_JANUARY');
                }
            case 2:
                if ($abbreviation === true) {
                    return $this->translate('DATE_FEB');
                } else {
                    return $this->translate('DATE_FEBRUARY');
                }
            case 3:
                if ($abbreviation === true) {
                    return $this->translate('DATE_MAR');
                } else {
                    return $this->translate('DATE_MARCH');
                }
            case 4:
                if ($abbreviation === true) {
                    return $this->translate('DATE_APR');
                } else {
                    return $this->translate('DATE_APRIL');
                }
            case 5:
                if ($abbreviation === true) {
                    return $this->translate('DATE_MAY');
                } else {
                    return $this->translate('DATE_MAY');
                }
            case 6:
                if ($abbreviation === true) {
                    return $this->translate('DATE_JUN');
                } else {
                    return $this->translate('DATE_JUNE');
                }
            case 7:
                if ($abbreviation === true) {
                    return $this->translate('DATE_JUL');
                } else {
                    return $this->translate('DATE_JULY');
                }
            case 8:
                if ($abbreviation === true) {
                    return $this->translate('DATE_AUG');
                } else {
                    return $this->translate('DATE_AUGUST');
                }
            case 9:
                if ($abbreviation === true) {
                    return $this->translate('DATE_SEP');
                } else {
                    return $this->translate('DATE_SEPTEMBER');
                }
            case 10:
                if ($abbreviation === true) {
                    return $this->translate('DATE_OCT');
                } else {
                    return $this->translate('DATE_OCTOBER');
                }
            case 11:
                if ($abbreviation === true) {
                    return $this->translate('DATE_NOV');
                } else {
                    return $this->translate('DATE_NOVEMBER');
                }
            default:
                if ($abbreviation === true) {
                    return $this->translate('DATE_DEC');
                } else {
                    return $this->translate('DATE_DECEMBER');
                }
        }
    }

    /**
     * buildCalendar
     *
     * $d = getDate();
     * $month = $d['mon'];
     * $year = $d['year'];
     *
     * $calendar = Services::Date()->buildCalendar ($month, $year);
     *
     * @param   string $month
     * @param   string $year
     *
     * @return  string CCYY-MM-DD
     * @since   1.0
     */
    public function buildCalendar($month, $year)
    {
        $temp_row                     = new stdClass();
        $temp_row->days_of_week       = array('S', 'M', 'T', 'W', 'T', 'F', 'S');
        $temp_row->first_day_of_month = mktime(0, 0, 0, $month, 1, $year);
        $temp_row->number_of_days     = date('t', $temp_row->first_day_of_month);
        $temp_row->date_resources     = getdate($temp_row->first_day_of_month);
        $temp_row->month_name         = $temp_row->date_resources['month'];
        $temp_row->day_of_week_number = $temp_row->date_resources['wday'];
        $temp_row->day_of_week_name   = $temp_row->date_resources['weekday'];

        return array($temp_row);
    }

    /**
     * translate Pretty Date
     *
     * @param   integer $numeric_value
     * @param   string  $singular_literal
     * @param   string  $plural_literal
     *
     * @return  mixed
     * @since   1.0
     */
    protected function translatePrettyDate($numeric_value, $singular_literal, $plural_literal)
    {
        if ($numeric_value == 0) {
            return '';
        }

        if ($numeric_value == 1) {
            return strtolower($this->translate($singular_literal));
        }

        return strtolower($this->translate($plural_literal));
    }

    /**
     * Translate the string
     *
     * @param   string $string
     *
     * @return  string
     * @since   1.0
     */
    protected function translate($string)
    {
        if (isset($this->date_translate_array[$string])) {
            return $this->date_translate_array[$string];
        }

        return $string;
    }
}
