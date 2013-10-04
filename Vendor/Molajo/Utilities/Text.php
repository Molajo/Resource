<?php
/**
 * Text Utilities
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Utilities;

use stdClass;
use Molajo\Utilities\Api\TextInterface;
use Molajo\Utilities\Exception\TextException;

/**
 * Text Utilities
 *
 * @package     Molajo
 * @subpackage  Utilities
 * @since       1.0
 */
class Text // implements TextInterface
{
    protected $lorem_set = array(
        'lorem',
        'ipsum',
        'dolor',
        'sit',
        'amet',
        'consectetur',
        'adipisicing',
        'elit',
        'sed',
        'do',
        'eiusmod',
        'tempor',
        'incididunt',
        'ut',
        'labore',
        'etdolore',
        'magna',
        'aliqua',
        'enim',
        'ad',
        'minim',
        'veniam',
        'quis',
        'nostrud',
        'exercitation',
        'ullamco',
        'laboris',
        'nisi',
        'aliquip',
        'ex',
        'ea',
        'commodo',
        'consequatduis',
        'aute',
        'irure',
        'in',
        'reprehenderit',
        'voluptate',
        'velit',
        'esse',
        'cillum',
        'dolore',
        'eu',
        'fugiat',
        'nulla',
        'pariatur',
        'excepteur',
        'sint',
        'occaecatcupidatat',
        'non',
        'proident',
        'sunt',
        'culpa',
        'qui',
        'officia',
        'deserunt',
        'mollit',
        'anim',
        'id',
        'est',
        'laborumcurabitur',
        'pretium',
        'tincidunt',
        'lacus',
        'gravida',
        'orci',
        'a',
        'odio',
        'nullam',
        'varius',
        'turpis',
        'etcommodo',
        'pharetra',
        'eros',
        'bibendum',
        'nec',
        'luctus',
        'felis',
        'sollicitudin',
        'mauris',
        'integerin',
        'nibh',
        'euismod',
        'duis',
        'ac',
        'tellus',
        'et',
        'risus',
        'vulputate',
        'vehicula',
        'donec',
        'lobortisrisus',
        'etiam',
        'ullamcorper',
        'ligula',
        'congue',
        'turpisid',
        'sapien',
        'quam',
        'maecenas',
        'fermentum',
        'consequat',
        'mi',
        'pellentesquemalesuada',
        'sem',
        'aliquet',
        'eget',
        'neque',
        'aliquam',
        'faucibuselit',
        'dictum',
        'nisl',
        'adipiscing',
        'malesuada',
        'diam',
        'erat',
        'cras',
        'mollisscelerisque',
        'nunc',
        'arcu',
        'curabitur',
        'php',
        'augue',
        'dapibus',
        'laoreet',
        'etpretium',
        'aenean',
        'mollis',
        'molestie',
        'feugiat',
        'hac',
        'habitasse',
        'platea',
        'dictumstfusce',
        'convallis',
        'imperdiet',
        'suscipit',
        'placeratipsum',
        'urna',
        'libero',
        'tristique',
        'sodalesmauris',
        'mattis',
        'semper',
        'leo',
        'dictumst',
        'vivamus',
        'facilisis',
        'at',
        'odiomauris',
        'elementum',
        'metus',
        'nonfeugiat',
        'vitae',
        'morbi',
        'maurisquisque',
        'proin',
        'scelerisque',
        'lobortisac',
        'eleifend',
        'diamsuspendisse',
        'suspendisse',
        'nonummy',
        'pulvinar',
        'laciniapede',
        'dignissim',
        'ornare',
        'praesent',
        'liguladapibus',
        'nam',
        'sam',
        'lobortisquam',
        'vestibulum',
        'massa',
        'lectus',
        'nullacras',
        'pellentesque',
        'habitant',
        'senectus',
        'netuset',
        'fames',
        'egestas',
        'lobortiselit',
        'dapibusaliquam',
        'pede',
        'purus',
        'consectetuerluctus',
        'nebraska',
        'feugiatpraesent',
        'hendrerit',
        'iaculis',
        'tellusa',
        'justo',
        'eratpraesent',
        'ligulaquis',
        'tortor',
        'posuere',
        'justonullam',
        'integer',
        'rutrum',
        'facilisiquisque',
        'vel',
        'egetsemper',
        'viverra',
        'quisque',
        'dolorduis',
        'volutpat',
        'condimentum',
        'lacusnunc',
        'orcietiam',
        'mialiquam',
        'porttitor',
        'variusenim',
        'lacinia',
        'gemma',
        'ultricies',
        'fusce',
        'porttitorhendrerit',
        'ante',
        'cursus',
        'tempus',
        'felissed',
        'rhoncus',
        'idlaoreet',
        'auctor',
        'sempernisi',
        'integersem',
        'fringilla',
        'praesentet',
        'pellentesqueleo',
        'venenatis',
        'interdum',
        'semut',
        'condimentumaenean',
        'accumsan',
        'porta',
        'egetaugue',
        'faucibus',
        'consectetuerquis',
        'ultrices',
        'nontristique',
        'netus',
        'molajo',
        'turpisegestas',
        'suscipitblandit',
        'sodales',
        'blandit',
        'massaarcu',
        'famesac',
        'ligulapraesent',
        'anteipsum',
        'primis',
        'cubilia',
        'curae',
        'ipsumdonec',
        'nuncfermentum',
        'consectetuer',
        'nullainteger',
        'sapiendonec',
        'commodomauris',
        'ametultrices',
        'proinlibero',
        'adipiscingnec'
    );

    /**
     * Translation Strings for Dates
     *
     * @var    array
     * @since  1.0
     */
    protected $date_translate_array = array
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
    }

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
    public function get($key = null, $default = null)
    {
        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            throw new TextException ('Text Utility Get: Unknown key: ' . $key);
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
     * @throws  TextException
     */
    public function set($key, $value = null)
    {
        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            throw new TextException ('Text Utility Set: Unknown key: ' . $key);
        }

        $this->$key = $value;

        return $this->$key;
    }

    /**
     * getDatalist creates named pair lists
     *
     * @param   string $model_name ex. Articles or Templateviews
     * @param   string $model_type ex. Datalist, ResourceLists, Database, etc.
     * @param   string $parameters
     *
     * @return  array|bool|object
     * @since   1.0
     */
    public function getDatalist($model_registry, $parameters)
    {
        $multiple = (int)$model_registry->multiple;
        $size     = (int)$model_registry->size;

        if ((int)$multiple == 1) {
            if ((int)$size == 0) {
                $size = 5;
            }
        } else {
            $multiple = 0;
            $size     = 0;
        }

        $values = $model_registry->values;

        if (is_array($values) && count($values) === 0) {

            $query_results = array();

            $temp_row = new stdClass();

            $temp_row->listitems = $values;
            $temp_row->multiple  = $multiple;
            $temp_row->size      = $size;

            $query_results[] = $temp_row;

            return $query_results;
        }
//amy-query
        $values = $this->getQueryResults($controller, $model_type, $parameters);

        $query_results = array();

        $temp_row = new stdClass();

        $temp_row->listitems = $values;
        $temp_row->multiple  = $multiple;
        $temp_row->size      = $size;

        $query_results[] = $temp_row;

        return $query_results;
    }

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
    public function getQueryResults($controller, $model_type, $parameters)
    {
        $registry_entry = $controller->get('registry_entry');

        if ($registry_entry == '') {
            $results = array();
        } else {
            $results = $this->registry->get('Datalist', $registry_entry, array());
            if (count($results) > 0) {
                return $results;
            }
        }

        $primary_prefix = $controller->get('primary_prefix', 'a', 'model_registry');
        $primary_key    = $controller->get('primary_key', 'id', 'model_registry');
        $name_key       = $controller->get('name_key', '', 'data_registry');

        $model_registry = $controller->get('model_registry');

        $controller->model->set('model_offset', 0, 'data_registry');
        $controller->model->set('model_count', 999999, 'data_registry');

        $fields = $this->registry->get($model_registry, 'Fields', 'data_registry');

        $first = true;
        if (count($fields) < 2) {

            $controller->model->query->select(
                'DISTINCT '
                . $controller->model->database->qn($primary_prefix . '.' . $primary_key) . ' as id'
            );

            $controller->model->query->select(
                $controller->model->database->qn(
                    $primary_prefix
                    . '.' . $name_key
                ) . ' as value'
            );

            $controller->model->query->order(
                $controller->model->database->qn(
                    $primary_prefix
                    . '.' . $name_key
                ) . ' ASC'
            );

        } else {

            $ordering = '';
            foreach ($fields as $field) {

                if (isset($field['alias'])) {
                    $alias = $field['alias'];
                } else {
                    $alias = $primary_prefix;
                }

                $name = $field['name'];

                if ($first) {
                    $first    = false;
                    $as       = 'id';
                    $distinct = 'distinct';

                } else {
                    $as       = 'value';
                    $distinct = '';
                    $ordering = $alias . '.' . $name;
                }

                $controller->model->query->select(
                    $distinct . ' '
                    . $controller->model->database->qn($alias . '.' . $name) . ' as ' . $as
                );
            }

            $controller->model->query->order($controller->model->database->qn($ordering) . ' ASC');
        }

        if ($controller->get('extension_instance_id', 0) == 0) {
        } else {
            $this->setWhereCriteria(
                'extension_instance_id',
                $controller->get('extension_instance_id'),
                $primary_prefix,
                $controller
            );
        }

        if ($controller->get('catalog_type_id', 0) == 0) {
        } else {
            $this->setWhereCriteria(
                'catalog_type_id',
                $controller->get('catalog_type_id'),
                $primary_prefix,
                $controller
            );
        }

        $query_object = 'distinct';

        $offset = $controller->set('model_offset', 0, 'model_registry');
        $count  = $controller->set('model_count', 9999999);

        return $controller->getData($query_object);
    }

    /**
     * setWhereCriteria
     *
     * @param   $field
     * @param   $value
     * @param   $alias
     * @param   $connection
     *
     * @return  Void
     * @since   1.0
     */
    protected function setWhereCriteria($field, $value, $alias, $connection)
    {
        if (strrpos($value, ',') > 0) {
            $connection->model->query->where(
                $connection->model->database->qn($alias . '.' . $field)
                . ' IN (' . $value . ')'
            );

        } elseif ((int)$value == 0) {

        } else {
            $connection->model->query->where(
                $connection->model->database->qn($alias . '.' . $field) . ' = ' . (int)$value
            );
        }

        return;
    }

    /**
     * add publishedStatus information to list query
     *
     * @return void
     * @since   1.0
     */
    protected function publishedStatus($controller)
    {
        $primary_prefix = $this->registry->get($controller->model_registry, 'primary_prefix', 'a');

        $controller->model->query->where(
            $controller->model->database->qn($primary_prefix)
            . '.' . $controller->model->database->qn('status')
            . ' > ' . 0
        );

        $controller->model->query->where(
            '(' . $controller->model->database->qn($primary_prefix)
            . '.' . $controller->model->database->qn('start_publishing_datetime')
            . ' = ' . $controller->model->database->q($controller->model->null_date)
            . ' OR ' . $controller->model->database->qn($primary_prefix)
            . '.' . $controller->model->database->qn('start_publishing_datetime')
            . ' <= ' . $controller->model->database->q($controller->model->now) . ')'
        );

        $controller->model->query->where(
            '(' . $controller->model->database->qn($primary_prefix)
            . '.' . $controller->model->database->qn('stop_publishing_datetime')
            . ' = ' . $controller->model->database->q($controller->model->null_date)
            . ' OR ' . $controller->model->database->qn($primary_prefix)
            . '.' . $controller->model->database->qn('stop_publishing_datetime')
            . ' >= ' . $controller->model->database->q($controller->model->now) . ')'
        );

        return;
    }

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
    public function buildSelectlist($listname, $items, $multiple = 0, $size = 5, $selected = null)
    {
        $query_results = array();

        if (count($items) == 0) {
            return false;
        }

        foreach ($items as $item) {

            $temp_row = new stdClass();

            $temp_row->listname = $listname;
            $temp_row->id       = $item->id;
            $temp_row->value    = $item->value;

            if ($temp_row->id == $selected) {
                $temp_row->selected = ' selected ';
            } else {
                $temp_row->selected = '';
            }

            $temp_row->multiple = '';

            if ($multiple == 1) {
                $temp_row->multiple = ' multiple ';
                if ((int)$size == 0) {
                    $temp_row->multiple .= 'size=5 ';
                } else {
                    $temp_row->multiple .= 'size=' . (int)$size;
                }
            }
            $query_results[] = $temp_row;
        }

        return $query_results;
    }

    /** tests
     *
     * $results = Services::Text()->convertNumberToText(0);
     * if ($results == 'zero') {
     * } else {
     * echo 'This should be zero but is: ' . $results;
     * die;
     * }
     *
     * $results = Services::Text()->convertNumberToText(5);
     * if ($results == 'five') {
     * } else {
     * echo 'This should be five but is: ' . $results;
     * die;
     * }
     *
     * $results = Services::Text()->convertNumberToText(15);
     * if ($results == 'fifteen') {
     * } else {
     * echo 'This should be fifteen but is: ' . $results;
     * die;
     * }
     *
     * $results = Services::Text()->convertNumberToText(20);
     * if ($results == 'twenty') {
     * } else {
     * echo 'This should be twenty but is: ' . $results;
     * die;
     * }
     *
     * $results = Services::Text()->convertNumberToText(23, 0, 1);
     * if ($results == 'twentythree') {
     * } else {
     * echo 'This should be twentythree but is: ' . $results;
     * die;
     * }
     *
     * $results = Services::Text()->convertNumberToText(100);
     * if ($results == 'one hundred') {
     * } else {
     * echo 'This should be one hundred but is: ' . $results;
     * die;
     * }
     *
     * $results = Services::Text()->convertNumberToText(123);
     * if ($results == 'one hundred and twenty three') {
     * } else {
     * echo 'This should be one hundred and twenty three but is: ' . $results;
     * die;
     * }
     *
     * $results = Services::Text()->convertNumberToText(103);
     * if ($results == 'one hundred three') {
     * } else {
     * echo 'This should be one hundred three but is: ' . $results;
     * die;
     * }
     *
     * $results = Services::Text()->convertNumberToText(1000);
     * if ($results == 'one thousand') {
     * } else {
     * echo 'This should be one thousand but is: ' . $results;
     * die;
     * }
     *
     * $results = Services::Text()->convertNumberToText(923403123);
     * if ($results == 'nine hundred and twenty three million, four hundred three thousand, one hundred and twenty three') {
     * } else {
     * echo 'This should be nine hundred and twenty three million, four hundred three thousand, one hundred and twenty three but is: ' . $results;
     * die;
     * }
     **/

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
     * @param   int    $number_of_paragraphs
     * @param   int    $lines_per_paragraph
     * @param   int    $words_per_line
     * @param   string $markup_type ('p', 'h1', 'h2', 'h3', 'h4', 'h5', 'ul', 'ol', 'blockquote')
     * @param   bool   $start_with_lorem_ipsum
     *
     * @return  string
     * @since   1.0
     * @throws  TextException
     */
    public function getPlaceHolderText(
        $number_of_paragraphs = 3,
        $lines_per_paragraphs = 3,
        $words_per_line = 7,
        $markup_type = 'p',
        $start_with_lorem_ipsum = true
    ) {
        $count_lorem_set = count($this->lorem_set) - 1;
        if ($count_lorem_set < 10) {
            throw new TextException ('Text Utility: getPlaceHolderText requires more than 10 lorem_set words.');
        }

        if ((int)$number_of_paragraphs === 0) {
            $number_of_paragraphs = 3;
        }

        if ((int)$lines_per_paragraphs === 0) {
            $lines_per_paragraphs = 3;
        }

        if ((int)$words_per_line === 0) {
            $words_per_line = 7;
        }

        $valid = array('p', 'h1', 'h2', 'h3', 'h4', 'h5', 'ul', 'ol', 'blockquote');
        if (in_array($markup_type, $valid)) {
        } else {
            $markup_type = 'p';
        }

        if ($start_with_lorem_ipsum === false) {
        } else {
            $start_with_lorem_ipsum = true;
        }

        $output = '';

        if ($markup_type == 'ul' || $markup_type == 'ol') {
            $output = '<' . $markup_type . '>';
            $begin  = '<li>';
            $end    = '</li>';
        } else {
            $begin = '<' . $markup_type . '>';
            $end   = '</' . $markup_type . '>';
        }

        for ($paragraph_count = 0; $paragraph_count < $number_of_paragraphs; $paragraph_count ++) {
            $output .= $begin;

            for ($line_count = 0; $line_count < $lines_per_paragraphs; $line_count ++) {

                for ($word_count = 0; $word_count < $words_per_line; $word_count ++) {

                    if ($word_count === 0 && $start_with_lorem_ipsum === true) {
                        $word = 'Lorem';

                    } elseif ($word_count === 1 && $start_with_lorem_ipsum === true) {
                        $word = 'ipsum';

                    } else {
                        $word = $this->lorem_set[rand(0, $count_lorem_set)];
                    }

                    if ($word_count === 0) {
                        $word = ucfirst(strtolower($word));
                    }

                    $output .= ' ' . $word;

                    if ($word_count < $words_per_line) {
                    } else {
                        $output .= $end;
                    }
                }
                $output .= '.';
            }
            $output .= $end;
        }

        if ($markup_type == 'ul' || $markup_type == 'ol') {
            $output .= '</' . $markup_type . '>';
        }

        return $output;
    }

    /**
     * Converts a numeric value, with or without a decimal, up to a 999 quattuordecillion to words
     *
     * @param   string $number
     * @param   bool   $remove_spaces
     *
     * @return  string
     * @since   1.0
     * @throws  TextException
     */
    public function convertNumberToText($number, $remove_spaces = false)
    {
        $results = '';

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
            $sign     = $this->translate('number_negative') . ' ';
        }

        if ((int)$number == 0) {
            return $this->translate('number_zero');
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
            } else {
                $word_value = trim($temp_word_value) . ', ' . $word_value;
            }
            $temp_word_value = '';
            $i ++;
        }

        if ($decimal === null) {
        } else {
            $word_value .= ' ' . $this->translate('number_point') . ' ' . $decimal;
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
                return $this->translate('number_one');
            case '2':
                return $this->translate('number_two');
            case '3':
                return $this->translate('number_three');
            case '4':
                return $this->translate('number_four');
            case '5':
                return $this->translate('number_five');
            case '6':
                return $this->translate('number_six');
            case '7':
                return $this->translate('number_seven');
            case '8':
                return $this->translate('number_eight');
            case '9':
                return $this->translate('number_nine');

        }

        return false;
    }

    /**
     * Convert the tens placeholder to a word, combining with the ones placeholder word
     *
     * @param   string $tensDigit
     * @param   string $onesDigit
     * @param   string $translate
     *
     * @return  string
     * @since   1.0
     * @throws  TextException
     */
    protected function convertPlaceValueTens($tensDigit, $onesDigit, $onesWord)
    {
        if ($onesDigit == 0) {

            switch ($tensDigit) {

                case 0:
                    return '';
                case 1:
                    return $this->translate('number_ten');
                case 2:
                    return $this->translate('number_twenty');
                case 3:
                    return $this->translate('number_thirty');
                case 4:
                    return $this->translate('number_forty');
                case 5:
                    return $this->translate('number_fifty');
                case 6:
                    return $this->translate('number_sixty');
                case 7:
                    return $this->translate('number_seventy');
                case 8:
                    return $this->translate('number_eighty');
                case 9:
                    return $this->translate('number_ninety');

            }

        } elseif ($tensDigit == 0) {
            return $onesWord;

        } elseif ($tensDigit == 1) {

            switch ($onesDigit) {

                case 1:
                    return $this->translate('number_eleven');
                case 2:
                    return $this->translate('number_twelve');
                case 3:
                    return $this->translate('number_thirteen');
                case 4:
                    return $this->translate('number_fourteen');
                case 5:
                    return $this->translate('number_fifteen');
                case 6:
                    return $this->translate('number_sixteen');
                case 7:
                    return $this->translate('number_seventeen');
                case 8:
                    return $this->translate('number_eighteen');
                case 9:
                    return $this->translate('number_nineteen');
            }

        } else {

            switch ($tensDigit) {

                case 2:
                    return $this->translate('number_twenty') . ' ' . $onesWord;
                case 3:
                    return $this->translate('number_thirty') . ' ' . $onesWord;
                case 4:
                    return $this->translate('number_forty') . ' ' . $onesWord;
                case 5:
                    return $this->translate('number_fifty') . ' ' . $onesWord;
                case 6:
                    return $this->translate('number_sixty') . ' ' . $onesWord;
                case 7:
                    return $this->translate('number_seventy') . ' ' . $onesWord;
                case 8:
                    return $this->translate('number_eighty') . ' ' . $onesWord;
                case 9:
                    return $this->translate('number_ninety') . ' ' . $onesWord;
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
     * @throws  TextException
     */
    protected function convertPlaceValueHundreds($hundredsDigit, $tensDigit, $tensWord, $onesDigit)
    {
        $temp = '';

        switch ($hundredsDigit) {

            case 0:
                return $tensWord;
                break;
            case 1:
                $temp = $this->translate('number_one');
                break;
            case 2:
                $temp = $this->translate('number_two');
                break;
            case 3:
                $temp = $this->translate('number_three');
                break;
            case 4:
                $temp = $this->translate('number_four');
                break;
            case 5:
                $temp = $this->translate('number_five');
                break;
            case 6:
                $temp = $this->translate('number_six');
                break;
            case 7:
                $temp = $this->translate('number_seven');
                break;
            case 8:
                $temp = $this->translate('number_eight');
                break;
            case 9:
                $temp = $this->translate('number_nine');
                break;
        }

        $temp .= ' ' . $this->translate('number_hundred');

        if ($tensDigit == 0 && $onesDigit == 0) {
            return $temp;

        } elseif ($tensDigit == 0) {
            return $temp . ' ' . $tensWord;
        }

        return $temp . ' ' . $this->translate('number_and') . ' ' . $tensWord;
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
     * @throws  TextException
     */
    protected function convertGrouping($number)
    {
        switch ($number) {

            case 0:
                return '';
            case 1:
                return $this->translate('number_thousand');
            case 2:
                return $this->translate('number_million');
            case 3:
                return $this->translate('number_billion');
            case 4:
                return $this->translate('number_trillion');
            case 5:
                return $this->translate('number_quadrillion');
            case 6:
                return $this->translate('number_quintillion');
            case 7:
                return $this->translate('number_sextillion');
            case 8:
                return $this->translate('number_septillion');
            case 9:
                return $this->translate('number_octillion');
            case 10:
                return $this->translate('number_nonillion');
            case 11:
                return $this->translate('number_decillion');
            case 12:
                return $this->translate('number_undecillion');
            case 13:
                return $this->translate('number_duodecillion');
            case 14:
                return $this->translate('number_tredecillion');
            case 15:
                return $this->translate('number_quattuordecillion');
        }

        return $this->translate('number_quinquadecillion');
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
