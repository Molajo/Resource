<?php
/**
 * Translation
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\NumberToText\Translations;

use Molajo\NumberToText\Api\TranslateInterface;
use Molajo\NumberToText\Exception\NumberToTextException;

/**
 * Translate requested string for specified locale
 *
 * @package     Molajo
 * @subpackage  NumberToText
 * @since       1.0
 */
class Translate implements TranslateInterface
{
    /**
     * Translation Strings for Numbers
     *
     * @var    array
     * @since  1.0
     */
    protected $number_translate_array = array();

    /**
     * Constructor
     *
     * @param  string $locale
     *
     * @throws \Molajo\NumberToText\Exception\NumberToTextException
     * @since  1.0
     */
    public function __construct($locale = 'en-GB')
    {
        if ($locale === '') {
            $locale = 'enGB';
        }

        $locale = str_replace('-', '', $locale);

        $class = 'Molajo\\NumberToText\\Translations\\' . $locale;

        try {
            $translate                    = new $class();
            $this->number_translate_array = $translate->loadTranslation();

        } catch (NumberToTextException $e) {
            throw new NumberToTextException
            ('NumberToText Translate Error Loading ' . $class . ' ' . $e->getMessage());
        }
    }

    /**
     * Translate the string
     *
     * @param   string $string
     *
     * @return  string
     * @since   1.0
     */
    public function translate($string)
    {
        if (isset($this->number_translate_array[$string])) {
            return $this->number_translate_array[$string];
        }

        return $string;
    }
}
