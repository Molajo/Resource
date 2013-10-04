<?php
/**
 * en-GB Translation
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\NumberToText\Translations;

use Molajo\NumberToText\Api\LoadTranslationInterface;

/**
 * en-GB Translation
 *
 * @package     Molajo
 * @subpackage  NumberToText
 * @since       1.0
 */
class enGB implements LoadTranslationInterface
{
    /**
     * Translation Strings for Numbers
     *
     * @var    array
     * @since  1.0
     */
    protected $number_translate_array = array
    (
        'number_negative'          => '-',
        'number_point'             => '.',
        'number_zero'              => 'zero',
        'number_one'               => 'one',
        'number_two'               => 'two',
        'number_three'             => 'three',
        'number_four'              => 'four',
        'number_five'              => 'five',
        'number_six'               => 'six',
        'number_seven'             => 'seven',
        'number_eight'             => 'eight',
        'number_nine'              => 'nine',
        'number_ten'               => 'ten',
        'number_eleven'            => 'eleven',
        'number_twelve'            => 'twelve',
        'number_thirteen'          => 'thirteen',
        'number_fourteen'          => 'fourteen',
        'number_fifteen'           => 'fifteen',
        'number_sixteen'           => 'sixteen',
        'number_seventeen'         => 'seventeen',
        'number_eighteen'          => 'eighteen',
        'number_nineteen'          => 'nineteen',
        'number_twenty'            => 'twenty',
        'number_thirty'            => 'thirty',
        'number_forty'             => 'forty',
        'number_fifty'             => 'fifty',
        'number_sixty'             => 'sixty',
        'number_seventy'           => 'seventy',
        'number_eighty'            => 'eighty',
        'number_ninety'            => 'ninety',
        'number_hundred'           => 'hundred',
        'number_thousand'          => 'thousand',
        'number_million'           => 'million',
        'number_billion'           => 'billion',
        'number_trillion'          => 'trillion',
        'number_quadrillion'       => 'quadrillion',
        'number_quintillion'       => 'quintillion',
        'number_sextillion'        => 'sextillion',
        'number_septillion'        => 'septillion',
        'number_octillion'         => 'octillion',
        'number_nonillion'         => 'nonillion',
        'number_decillion'         => 'decillion',
        'number_undecillion'       => 'undecillion',
        'number_duodecillion'      => 'duodecillion',
        'number_tredecillion'      => 'tredecillion',
        'number_quattuordecillion' => 'quattuordecillion',
        'number_quinquadecillion'  => 'quinquadecillion',
        'number_and'               => 'and'
    );

    /**
     * Returns the translation array
     *
     * @return  array
     * @since   1.0
     */
    public function loadTranslation()
    {
        return $this->number_translate_array;
    }
}
