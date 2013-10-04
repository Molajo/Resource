<?php
/**
 * Number To Text Utility
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\NumberToText;

use Molajo\NumberToText\Api\NumberToTextInterface;
use Molajo\NumberToText\Api\TranslateInterface;

/**
 * Number to Text Utility: Converts a numeric value up to a 999 quattuordecillion to translatable term.
 *
 * @package     Molajo
 * @subpackage  NumberToText
 * @since       1.0
 */
class Utility implements NumberToTextInterface
{
    /**
     * Translation Strings for Numbers
     *
     * @var    array
     * @since  1.0
     */
    protected $locale_instance;

    /**
     * Constructor
     *
     * @param  TranslateInterface $locale_instance
     *
     * @since  1.0
     */
    public function __construct(TranslateInterface $locale_instance)
    {
        $this->locale_instance = $locale_instance;
    }

    /**
     * Converts a numeric value, with or without a decimal, up to a 999 quattuordecillion to words
     *
     * @param   string $number
     * @param   bool   $remove_spaces default false
     *
     * @return  string
     * @since   1.0
     */
    public function convert($number, $remove_spaces = false)
    {
        $split = explode('.', $number);
        if (count($split) > 1) {
            $decimal = $split[1];
        } else {
            $decimal = null;
        }

        $sign = '';
        if (substr($split[0], 0, 1) == '+') {
            $split[0] = substr($split[0], 1, strlen($split[0]) - 1);
        }
        if (substr($split[0], 0, 1) == '-') {
            $split[0] = substr($split[0], 1, strlen($split[0]) - 1);
            $sign     = $this->locale_instance->translate('number_negative') . ' ';
        }

        if ((int)$number == 0) {
            return $this->locale_instance->translate('number_zero');
        }

        $word_value = $sign;

        $reverseDigits = str_split($number, 1);
        $number        = implode(array_reverse($reverseDigits));

        if ((strlen($number) % 3) == 0) {
            $padToSetsOfThree = strlen($number);
        } else {
            $padToSetsOfThree = 3 - (strlen($number) % 3) + strlen($number);
        }

        $number = str_pad($number, $padToSetsOfThree, 0, STR_PAD_RIGHT);
        $groups = str_split($number, 3);

        $onesDigit     = null;
        $tensDigit     = null;
        $hundredsDigit = null;

        $temp_word_value = '';

        $i = 0;
        foreach ($groups as $digits) {

            $digit = str_split($digits, 1);

            $onesDigit     = $digit[0];
            $tensDigit     = $digit[1];
            $hundredsDigit = $digit[2];

            if ($onesDigit == 0 && $tensDigit == 0 && $hundredsDigit == 0) {
            } else {

                $temp_word_value = $this->convertPlaceValueOnes($onesDigit);
                $temp_word_value = $this->convertPlaceValueTens($tensDigit, $onesDigit, $temp_word_value);
                $temp_word_value = $this->convertPlaceValueHundreds(
                    $hundredsDigit,
                    $tensDigit,
                    $temp_word_value,
                    $onesDigit
                );

                $temp_word_value .= ' ' . $this->convertGrouping($i);
            }

            $onesDigit     = null;
            $tensDigit     = null;
            $hundredsDigit = null;

            if (trim($word_value) == '') {
                $word_value = trim($temp_word_value);
            } elseif (trim($temp_word_value) == '') {
            } else {
                $word_value = trim($temp_word_value) . ', ' . $word_value;
            }
            $temp_word_value = '';
            $i ++;
        }

        if ($decimal === null) {
        } else {
            $word_value .= ' ' . $this->locale_instance->translate('number_point') . ' ' . $decimal;
        }

        if ((int)$remove_spaces == 1) {
            $word_value = str_replace(' ', '', $word_value);
        }

        return trim($word_value);
    }

    /**
     * Convert the ones place value to a word
     *
     * @param   string $digit
     *
     * @return  bool
     * @since   1.0
     */
    protected function convertPlaceValueOnes($digit)
    {
        switch ($digit) {

            case   '0':
                return '';
            case '1':
                return $this->locale_instance->translate('number_one');
            case '2':
                return $this->locale_instance->translate('number_two');
            case '3':
                return $this->locale_instance->translate('number_three');
            case '4':
                return $this->locale_instance->translate('number_four');
            case '5':
                return $this->locale_instance->translate('number_five');
            case '6':
                return $this->locale_instance->translate('number_six');
            case '7':
                return $this->locale_instance->translate('number_seven');
            case '8':
                return $this->locale_instance->translate('number_eight');
            case '9':
                return $this->locale_instance->translate('number_nine');

        }

        return false;
    }

    /**
     * Convert the tens placeholder to a word, combining with the ones placeholder word
     *
     * @param   string $tensDigit
     * @param   string $onesDigit
     * @param   string $onesWord
     *
     * @return  string
     * @since   1.0
     */
    protected function convertPlaceValueTens($tensDigit, $onesDigit, $onesWord)
    {
        if ($onesDigit == 0) {

            switch ($tensDigit) {

                case 0:
                    return '';
                case 1:
                    return $this->locale_instance->translate('number_ten');
                case 2:
                    return $this->locale_instance->translate('number_twenty');
                case 3:
                    return $this->locale_instance->translate('number_thirty');
                case 4:
                    return $this->locale_instance->translate('number_forty');
                case 5:
                    return $this->locale_instance->translate('number_fifty');
                case 6:
                    return $this->locale_instance->translate('number_sixty');
                case 7:
                    return $this->locale_instance->translate('number_seventy');
                case 8:
                    return $this->locale_instance->translate('number_eighty');
                case 9:
                    return $this->locale_instance->translate('number_ninety');

            }

        } elseif ($tensDigit == 0) {
            return $onesWord;

        } elseif ($tensDigit == 1) {

            switch ($onesDigit) {

                case 1:
                    return $this->locale_instance->translate('number_eleven');
                case 2:
                    return $this->locale_instance->translate('number_twelve');
                case 3:
                    return $this->locale_instance->translate('number_thirteen');
                case 4:
                    return $this->locale_instance->translate('number_fourteen');
                case 5:
                    return $this->locale_instance->translate('number_fifteen');
                case 6:
                    return $this->locale_instance->translate('number_sixteen');
                case 7:
                    return $this->locale_instance->translate('number_seventeen');
                case 8:
                    return $this->locale_instance->translate('number_eighteen');
                case 9:
                    return $this->locale_instance->translate('number_nineteen');
            }

        } else {

            switch ($tensDigit) {

                case 2:
                    return $this->locale_instance->translate('number_twenty') . ' ' . $onesWord;
                case 3:
                    return $this->locale_instance->translate('number_thirty') . ' ' . $onesWord;
                case 4:
                    return $this->locale_instance->translate('number_forty') . ' ' . $onesWord;
                case 5:
                    return $this->locale_instance->translate('number_fifty') . ' ' . $onesWord;
                case 6:
                    return $this->locale_instance->translate('number_sixty') . ' ' . $onesWord;
                case 7:
                    return $this->locale_instance->translate('number_seventy') . ' ' . $onesWord;
                case 8:
                    return $this->locale_instance->translate('number_eighty') . ' ' . $onesWord;
                case 9:
                    return $this->locale_instance->translate('number_ninety') . ' ' . $onesWord;
            }
        }

        return '';
    }

    /**
     * Creates words for Hundreds Digit, combining previously determined tens digit word
     *
     * @param   string $hundredsDigit
     * @param   string $tensDigit
     * @param   string $tensWord
     * @param   string $onesDigit
     *
     * @return  string
     * @since   1.0
     */
    protected function convertPlaceValueHundreds($hundredsDigit, $tensDigit, $tensWord, $onesDigit)
    {
        $temp = '';

        switch ($hundredsDigit) {

            case 0:
                return $tensWord;
                break;
            case 1:
                $temp = $this->locale_instance->translate('number_one');
                break;
            case 2:
                $temp = $this->locale_instance->translate('number_two');
                break;
            case 3:
                $temp = $this->locale_instance->translate('number_three');
                break;
            case 4:
                $temp = $this->locale_instance->translate('number_four');
                break;
            case 5:
                $temp = $this->locale_instance->translate('number_five');
                break;
            case 6:
                $temp = $this->locale_instance->translate('number_six');
                break;
            case 7:
                $temp = $this->locale_instance->translate('number_seven');
                break;
            case 8:
                $temp = $this->locale_instance->translate('number_eight');
                break;
            case 9:
                $temp = $this->locale_instance->translate('number_nine');
                break;
        }

        $temp .= ' ' . $this->locale_instance->translate('number_hundred');

        if ($tensDigit == 0 && $onesDigit == 0) {
            return $temp;

        } elseif ($tensDigit == 0) {
            return $temp . ' ' . $tensWord;
        }

        return $temp . ' ' . $this->locale_instance->translate('number_and') . ' ' . $tensWord;
    }

    /**
     * Creates the high-level word associated with the numeric group
     *
     * ex. for 300,000: we want 'thousand' to combine with 'three hundred' to make 'three hundred thousand'
     *
     * Called once for each set of (up to) three numbers over one hundred.
     *
     * Ex. for 3,000,000 it will be called for the middle "000" and the first digit, 3
     *
     * Source: http://en.wikipedia.org/wiki/Names_of_large_numbers
     *
     * @param   string $number
     *
     * @return  string
     * @since   1.0
     */
    protected function convertGrouping($number)
    {
        switch ($number) {

            case 0:
                return '';
            case 1:
                return $this->locale_instance->translate('number_thousand');
            case 2:
                return $this->locale_instance->translate('number_million');
            case 3:
                return $this->locale_instance->translate('number_billion');
            case 4:
                return $this->locale_instance->translate('number_trillion');
            case 5:
                return $this->locale_instance->translate('number_quadrillion');
            case 6:
                return $this->locale_instance->translate('number_quintillion');
            case 7:
                return $this->locale_instance->translate('number_sextillion');
            case 8:
                return $this->locale_instance->translate('number_septillion');
            case 9:
                return $this->locale_instance->translate('number_octillion');
            case 10:
                return $this->locale_instance->translate('number_nonillion');
            case 11:
                return $this->locale_instance->translate('number_decillion');
            case 12:
                return $this->locale_instance->translate('number_undecillion');
            case 13:
                return $this->locale_instance->translate('number_duodecillion');
            case 14:
                return $this->locale_instance->translate('number_tredecillion');
            case 15:
                return $this->locale_instance->translate('number_quattuordecillion');
        }

        return $this->locale_instance->translate('number_quinquadecillion');
    }
}
