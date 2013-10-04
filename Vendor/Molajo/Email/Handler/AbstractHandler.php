<?php
/**
 * Abstract Email Class
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Email\Handler;

use Molajo\Fieldhandler\Api\FieldHandlerInterface;
use Molajo\Email\Api\EmailInterface;
use Molajo\Email\Exception\ConnectionException;
use Molajo\Email\Exception\EmailException;

/**
 * Adapter for Email
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
abstract class AbstractHandler implements EmailInterface
{
    /**
     * Field Handler
     *
     * @var    object
     * @since  1.0
     */
    protected $fieldhandler = null;

    /**
     * Mailer Transport - smtp, sendmail, ismail
     *
     * @var     string
     * @since   1.0
     */
    protected $mailer_transport;

    /**
     * Site Name
     *
     * @var     string
     * @since   1.0
     */
    protected $site_name;

    /**
     * SMTP Authorisation
     *
     * @var     EmailInterface
     * @since   1.0
     */
    protected $smtpauth;

    /**
     * SMTP Host
     *
     * @var     EmailInterface
     * @since   1.0
     */
    protected $smtphost;

    /**
     * SMTP User
     *
     * @var     EmailInterface
     * @since   1.0
     */
    protected $smtpuser;

    /**
     * SMTP Password
     *
     * @var     EmailInterface
     * @since   1.0
     */
    protected $smtppass;

    /**
     * SMTP Secure
     *
     * @var     EmailInterface
     * @since   1.0
     */
    protected $smtpsecure;

    /**
     * SMTP Port
     *
     * @var     EmailInterface
     * @since   1.0
     */
    protected $smtpport;

    /**
     * Sendmail Path
     *
     * @var     bool
     * @since   1.0
     */
    protected $sendmail_path = 0;

    /**
     * Disable Sending
     *
     * @var     bool
     * @since   1.0
     */
    protected $mailer_disable_sending = 0;

    /**
     * Only Deliver To
     *
     * @var     string
     * @since   1.0
     */
    protected $mailer_only_deliver_to = '';

    /**
     * To
     *
     * @var     array
     * @since   1.0
     */
    protected $to = array();

    /**
     * From
     *
     * @var     array
     * @since   1.0
     */
    protected $from = array();

    /**
     * Reply To
     *
     * @var     array
     * @since   1.0
     */
    protected $reply_to = array();

    /**
     * Copy
     *
     * @var     array
     * @since   1.0
     */
    protected $cc = array();

    /**
     * Blind Copy
     *
     * @var     array
     * @since   1.0
     */
    protected $bcc = array();

    /**
     * Subject
     *
     * @var     string
     * @since   1.0
     */
    protected $subject = '';

    /**
     * Body
     *
     * @var     string
     * @since   1.0
     */
    protected $body = '';

    /**
     * HTML or Text
     *
     * @var     string
     * @since   1.0
     */
    protected $mailer_html_or_text = '';

    /**
     * Attachment
     *
     * @var     string
     * @since   1.0
     */
    protected $attachment = '';

    /**
     * List of Properties
     *
     * @var    object
     * @since  1.0
     */
    protected $property_array = array(
        'Fieldhandler',
        'mailer_transport',
        'site_name',
        'smtpauth',
        'smtphost',
        'smtpuser',
        'smtppass',
        'smtpsecure',
        'smtpport',
        'sendmail_path',
        'mailer_disable_sending',
        'mailer_only_deliver_to',
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
     * @param   array $options
     *
     * @since   1.0
     * @throws  EmailException
     */
    public function __construct(
        FieldHandlerInterface $fieldhandler,
        $mailer_transport,
        $site_name,
        $smtpauth,
        $smtphost,
        $smtpuser,
        $smtppass,
        $smtpsecure,
        $smtpport,
        $sendmail_path,
        $mailer_disable_sending,
        $to,
        $from,
        $reply_to,
        $cc,
        $bcc,
        $subject,
        $body,
        $mailer_html_or_text,
        $attachment
    ) {
        $this->fieldhandler           = $fieldhandler;
        $this->mailer_transport       = $mailer_transport;
        $this->site_name              = $site_name;
        $this->smtpauth               = $smtpauth;
        $this->smtphost               = $smtphost;
        $this->smtpuser               = $smtpuser;
        $this->smtppass               = $smtppass;
        $this->smtpsecure             = $smtpsecure;
        $this->smtpport               = $smtpport;
        $this->sendmail_path          = $sendmail_path;
        $this->mailer_disable_sending = $mailer_disable_sending;
        $this->to                     = $to;
        $this->from                   = $from;
        $this->reply_to               = $reply_to;
        $this->cc                     = $cc;
        $this->bcc                    = $bcc;
        $this->subject                = $subject;
        $this->body                   = $body;
        $this->mailer_html_or_text    = $mailer_html_or_text;
        $this->attachment             = $attachment;
    }

    /**
     * Set parameter value
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  $this
     * @since   1.0
     * @throws  EmailException
     */
    public function set($key, $value = null)
    {
        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            throw new EmailException ('Email Service Set: Unknown key: ' . $key);
        }

        $this->$key = $value;

        return $this;
    }

    /**
     * Return value for a key
     *
     * @param   null|string $key
     * @param   mixed       $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  EmailException
     */
    public function get($key, $default = null)
    {
        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            throw new EmailException
            ('Email Service: attempting to get value for unknown property: ' . $key);
        }

        if ($this->$key === null) {
            $this->$key = $default;
        }

        return $this->$key;
    }

    /**
     * Send email
     *
     * @return  mixed
     * @since   1.0
     * @throws  EmailException
     */
    abstract public function send();

    /**
     * Close the Connection
     *
     * @return  $this
     * @since   1.0
     * @throws  ConnectionException
     */
    abstract public function close();
}
