<?php
/**
 * Text Utility Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Utilities\Api;

use Molajo\Utilities\Exception\TextException;

/**
 * Text Utility Interface
 *
 * @package     Molajo
 * @subpackage  Utilities
 * @since       1.0
 */
interface TextInterface
{
    /**
     * Get the current value (or default) of the specified key
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  TextException
     */
    public function get($key = null, $default = null);

    /**
     * Set the value of the specified key
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  mixed
     * @since   1.0
     * @throws  TextException
     */
    public function set($key, $value = null);

    /**
     * getDatalist creates named pair lists
     *
     * @param string $model_name ex. Articles or Templateviews
     * @param string $model_type ex. Datalist, ResourceLists, Database, etc.
     * @param string $parameters
     *
     * @return array|bool|object
     * @since   1.0
     */
    public function getDatalist($model_name, $model_type, $parameters);

    /**
     * getQueryResults for list
     *
     * @param   $controller
     * @param   $model_type
     * @param   $parameters
     *
     * @return  object
     * @since   1.0
     */
    public function getQueryResults($controller, $model_type, $parameters);

    /**
     * buildSelectlist - build select list for insertion into webpage
     *
     * @param string $listname
     * @param array  $items
     * @param int    $multiple
     * @param int    $size
     * @param string $selected
     *
     * @return array
     * @since   1.0
     */
    public function buildSelectlist($listname, $items, $multiple = 0, $size = 5, $selected = null);

    /**
     * Generates Lorem Ipsum Placeholder Text
     *
     * Usage:
     * $text->getPlaceHolderText(2, 3, 7, 'p', true);
     *  Generates 2 paragraphs, each with 3 lines of 7 random words each, each paragraph starting with 'Lorem ipsum'
     *
     * $text->getPlaceHolderText(1, 1, 3, 'h1', false);
     *  Generates 1 <h1> line using 3 random words
     *
     * $text->getPlaceHolderText(1, 10, 3, 'li', false);
     *  Generates 1 <ul> list with 10 items each with 3 random words
     *
     * @param int    $number_of_paragraphs
     * @param int    $lines_per_paragraphs
     * @param int    $words_per_line
     * @param string $markup_type ('p', 'h1', 'h2', 'h3', 'h4', 'h5', 'ul', 'ol', 'blockquote')
     * @param bool   $start_with_lorem_ipsum
     *
     * @return string
     * @since   1.0
     */
    public function getPlaceHolderText(
        $number_of_paragraphs = 3,
        $lines_per_paragraphs = 3,
        $words_per_line = 7,
        $markup_type = 'p',
        $start_with_lorem_ipsum = true
    );
}
