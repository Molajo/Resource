<?php
/**
 * Number to Text Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\NumberToText\Api;

/**
 * Number to Text Interface
 *
 * @package     Molajo
 * @subpackage  Utilities
 * @since       1.0
 */
interface NumberToTextInterface
{
    /**
     * Converts a numeric value, with or without a decimal, up to a 999 quattuordecillion into words
     * Translations can by injecting the $number_translate_array property using the constructor
     *
     * @param   string $number
     * @param   bool   $remove_spaces default false
     *
     * @return  string
     * @since   1.0
     */
    public function convert($number, $remove_spaces = false);
}
