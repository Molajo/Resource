<?php
/**
 * User Mailer Class
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\User\Utilities;

use Molajo\User\Api\MailerInterface;
use Molajo\User\Api\TemplateInterface;
use Molajo\Email\Api\EmailInterface;

/**
 * User Mailer Class
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Mailer implements MailerInterface
{
    /**
     * Email Instance
     *
     * @var    object  Molajo\Email\Api\EmailInterface
     * @since  1.0
     */
    protected $email;

    /**
     * Template Instance
     *
     * @var    object  Molajo\User\Api\TemplateInterface
     * @since  1.0
     */
    protected $template;

    /**
     * Default Exception
     *
     * @var    string
     * @since  1.0
     */
    protected $default_exception = 'Molajo\\User\\Exception\\MailerException';

    /**
     * Email To (Email address, Name)
     *
     * @var     string
     * @since   1.0
     */
    protected $to;

    /**
     * From
     *
     * @var     string
     * @since   1.0
     */
    protected $from;

    /**
     * Reply to
     *
     * @var     string
     * @since   1.0
     */
    protected $reply_to;

    /**
     * CC
     *
     * @var     string
     * @since   1.0
     */
    protected $cc;

    /**
     * Bcc
     *
     * @var     string
     * @since   1.0
     */
    protected $bcc;

    /**
     * Subject
     *
     * @var     string
     * @since   1.0
     */
    protected $subject;

    /**
     * HTML or Text
     *
     * @var     string
     * @since   1.0
     */
    protected $mailer_html_or_text;

    /**
     * Attachment location
     *
     * @var     string
     * @since   1.0
     */
    protected $attachment;

    /**
     * Data
     *
     * @var     object
     * @since   1.0
     */
    protected $userdata;

    /**
     * List of Properties that will be sent via email
     *
     * @var    object
     * @since  1.0
     */
    protected $property_array = array(
        'to',
        'from',
        'reply_to',
        'cc',
        'bcc',
        'subject',
        'body',
        'mailer_html_or_text',
        'attachment'
    );

    /**
     * Construct
     *
     * @param EmailInterface         $email
     * @param null|TemplateInterface $template
     * @param null|string            $default_exception
     * @param array                  $options
     *
     * @since  1.0
     */
    public function __construct(
        EmailInterface $email,
        TemplateInterface $template = null,
        $default_exception = null,
        $options = array()
    ) {
        $this->email = $email;

        if ($template === null) {
        } else {
            $this->template = $template;
        }

        if ($default_exception === null) {
        } else {
            $this->default_exception = $default_exception;
        }

        if (is_array($options)) {
        } else {
            $options = array();
        }

        if (count($options) > 0) {
            foreach ($this->property_array as $property) {
                if (isset($options[$property])) {
                    $this->$property = $options[$property];
                } else {
                    $this->$property = '';
                }
            }
        }
    }

    /**
     * Set the Option Values, Initiate Rendering, Send
     *
     * @param   array                  $options
     * @param   null|TemplateInterface $template
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\User\Exception\MailerException
     */
    public function render(
        $options = array(),
        TemplateInterface $template = null
    ) {
        if (is_array($options)) {
        } else {
            $options = array();
        }

        if (count($options) > 0) {
            $this->data = new \stdClass();
            foreach ($this->property_array as $property) {
                if (isset($options[$property])) {
                    $this->$property = $options[$property];
                }
            }
            foreach ($options as $key => $value) {
                $this->data->$key = $value;
            }
        }

        if ($template === null) {
        } else {
            $this->template = $template;
        }

        $results = $this->template->render($this->data);

        $this->subject = $results->subject;
        $this->body    = $results->body;

        return $this;
    }

    /**
     * Send Email
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\User\Exception\MailerException
     */
    public function send()
    {
        foreach ($this->property_array as $property) {
            if ($this->$property == '') {
            } else {
                $this->email->set($property, $this->$property);
            }
        }

        $this->email->send();

        return $this;
    }
}
