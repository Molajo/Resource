<?php
/**
 * Language Model Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Language\Api;

use Molajo\Language\Exception\LanguageModelException;

/**
 * Language Model Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface DatabaseModelInterface
{
    /**
     * Save untranslated strings for use by translators
     *
     * @param   string
     *
     * @return  bool
     * @since   1.0
     * @throws  \Molajo\Language\Exception\LanguageModelException
     */
    public function setUntranslatedString($string);
}
