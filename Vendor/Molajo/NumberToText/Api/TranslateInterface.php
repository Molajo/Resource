<?php
/**
 * Translate Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo\NumberToText\Api;

/**
 * Translate Interface
 *
 * @package   Molajo
 * @license   MIT
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 * @api
 */
interface TranslateInterface
{
    /**
     * Translate the requested string
     *
     * @param   string $string
     *
     * @return  string
     * @since   1.0
     */
    public function translate($string);
}
