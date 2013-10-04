<?php
/**
 * Text Template Class
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\User\Utilities;

use stdClass;
use Molajo\FieldHandler\Api\FieldHandlerInterface;
use Molajo\User\Api\MessagesInterface;
use Molajo\User\Api\TemplateInterface;

/**
 * Text Template Class
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class TextTemplate implements TemplateInterface
{
    /**
     * Fieldhandler Instance
     *
     * @var    object  Molajo\FieldHandler\Api\FieldHandlerInterface
     * @since  1.0
     */
    protected $fieldhandler;

    /**
     * Messages Instance
     *
     * @var    object  Molajo\User\Api\MessagesInterface
     * @since  1.0
     */
    protected $messages_interface;

    /**
     * Default Exception
     *
     * @var    string
     * @since  1.0
     */
    protected $default_exception = 'Molajo\\User\\Exception\\TemplateException';

    /**
     * Input
     *
     * @var    array
     * @since  1.0
     */
    protected $userdata = array();

    /**
     * Rendered Output
     *
     * @var    object
     * @since  1.0
     */
    protected $rendered_output;

    /**
     * Tokens
     *
     * @var    array
     * @since  1.0
     */
    protected $tokens;

    /**
     * Templates
     *
     * @var    object
     * @since  1.0
     */
    protected $templates = array();

    /**
     * List of Properties
     *
     * @var    object
     * @since  1.0
     */
    protected $property_array = array(
        'fieldhandler',
        'messages',
        'data',
        'rendered_output',
        'tokens',
        'templates'
    );

    /**
     * Construct
     *
     * @param   FieldHandlerInterface $fieldhandler
     * @param   MessagesInterface     $messages
     * @param   string                $default_exception
     * @param   array                 $templates
     * @param   array                 $data
     *
     * @since   1.0
     */
    public function __construct(
        FieldHandlerInterface $fieldhandler,
        MessagesInterface $messages,
        $default_exception = null,
        array $templates = array(),
        array $data = array()
    ) {
        $this->fieldhandler = $fieldhandler;
        $this->messages     = $messages;
        $this->templates    = $templates;
        $this->data         = $data;

        if ($default_exception === null) {
        } else {
            $this->default_exception = $default_exception;
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
     * @throws  \Molajo\User\Exception\TemplateException
     */
    public function get($key, $default = null)
    {
        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            $this->messages->throwException(6000, array('key' => $key), $this->default_exception);
        }

        if ($this->$key === null) {
            $this->$key = $default;
        }

        return $this->$key;
    }

    /**
     * Set the value of a specified key
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\User\Exception\TemplateException
     */
    public function set($key, $value = null)
    {
        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            $this->messages->throwException(6010, array('key' => $key), $this->default_exception);
        }

        $this->$key = $value;

        return $this;
    }

    /**
     * Set the Option Values, Initiate Rendering, Send
     *
     * @param   stdClass $data
     *
     * @return  string
     * @since   1.0
     */
    public function render(stdClass $data)
    {
        $this->data = $data;
        $type       = $data->type;

        $this->rendered_output = '';
        $rendered              = new stdClass();

        /** Body */
        if (isset($this->templates[$type])) {
            $this->rendered_output = $this->templates[$type]->body;
        } else {
            if (isset($this->data['body'])) {
                $this->rendered_output = $data['body'];
            }
        }

        $this->renderLoop();

        $rendered->body = $this->fieldhandler
            ->filter($type . ' Body', $this->rendered_output, 'Fullspecialchars');

        /** Head */
        if (isset($this->templates[$type])) {
            $this->rendered_output = $this->templates[$type]->subject;
        } else {
            if (isset($this->data['subject'])) {
                $this->rendered_output = $data['subject'];
            }
        }

        $this->renderLoop();

        $rendered->subject = $this->fieldhandler
            ->filter($type . ' Body', $this->rendered_output, 'Fullspecialchars');

        return $rendered;
    }

    /**
     * Iteratively process content, replacing tokens with data values
     *
     * @return  $this
     * @since   1.0
     */
    protected function renderLoop()
    {
        $complete = false;
        $loop     = 0;

        while ($complete === false) {

            $loop ++;

            $this->parseTokens();

            if (count($this->tokens) == 0) {
                break;
            }

            $this->replaceTokens();

            if ($loop > 100) {
                break;
            }
            continue;
        }

        return $this;
    }

    /**
     * Parses the rendered output, looking for {tokens}
     *
     * @return  $this
     * @since   1.0
     */
    protected function parseTokens()
    {
        $this->tokens = array();
        preg_match_all('#{(.*)}#iU', $this->rendered_output, $this->tokens);

        return $this;
    }

    /**
     * Locate all tags and replace with data
     *
     * @return  $this
     * @since   1.0
     */
    protected function replaceTokens()
    {
        $get_rid_of_array = array();

        foreach ($this->tokens[0] as $get_rid_of_item) {
            $get_rid_of_array[] = $get_rid_of_item;
        }

        $replace_with_array = array();

        foreach ($this->tokens[1] as $use_as_field_name) {
            if (isset($this->data->$use_as_field_name)) {
                $replace_with_array[] = $this->data->$use_as_field_name;
            } else {
                $replace_with_array[] = '';
            }
        }

        $this->rendered_output = str_replace($get_rid_of_array, $replace_with_array, $this->rendered_output);

        return $this;
    }
}
