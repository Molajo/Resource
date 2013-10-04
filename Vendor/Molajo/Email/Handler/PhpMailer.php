<?php
/**
 * Abstract Email Class
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Email\Handler;

use Exception;
use PhpMailer\PhpMailer as mailer;
use Molajo\Email\Exception\ConnectionException;
use Molajo\Fieldhandler\Api\FieldHandlerInterface;
use Molajo\Email\Api\EmailInterface;
use Molajo\Email\Exception\EmailException;

/**
 * Adapter for Email
 *
 * Edits, filters input, and sends email
 *
 * Example usage:
 *
 * $adapter->set('to', 'person@example.com,Fname Lname');
 * $adapter->set('from', 'person@example.com,Fname Lname');
 * $adapter->set('reply_to', 'person@example.com,FName LName');
 * $adapter->set('cc', 'person@example.com,FName LName');
 * $adapter->set('bcc', 'person@example.com,FName LName');
 * $adapter->set('subject', 'Welcome to our Site');
 * $adapter->set('body', '<h2>Stuff goes here</h2>') ;
 * $adapter->set('mailer_html_or_text', 'html') ;
 * $adapter->set('attachment', SITE_MEDIA_FOLDER.'/molajo.sql') ;
 *
 * $adapter->send();
 *
 * @package     Molajo
 * @subpackage  Service
 * @since       1.0
 */
class PhpMailer extends AbstractHandler implements EmailInterface
{
    /**
     * Email Instance
     *
     * @var     object
     * @since   1.0
     */
    protected $email;

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
        $this->email                  = new mailer();
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
        return parent::set($key, $value);
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
        return parent::get($key, $default);
    }

    /**
     * Send email
     *
     * @return  $this
     * @since   1.0
     * @throws  EmailException
     */
    public function send()
    {
        if ($this->get('mailer_disable_sending', 0) == 1) {
            return $this;
        }

        if (trim($this->get('mailer_only_deliver_to', '') == '')) {
        } else {
            $this->mailer_only_deliver_to = 'AmyStephen@gmail.com';

            $this->set(
                'reply_to',
                $this->fieldhandler->validate('reply_to', $this->mailer_only_deliver_to, 'email')
            );
            $this->set('from', $this->fieldhandler->validate('from', $this->mailer_only_deliver_to, 'email'));
            $this->set('to', $this->fieldhandler->validate('to', $this->mailer_only_deliver_to, 'email'));
            $this->set('cc', '');
            $this->set('bcc', '');
        }

        $this->setSubject();

        $this->processRecipient('reply_to');
        $this->processRecipient('from');
        $this->processRecipient('to');
        $this->processRecipient('cc');
        $this->processRecipient('bcc');

        if ($this->get('mailer_html_or_text', 'text') == 'html') {
            $mailer_html_or_text = 'text';
        } else {
            $mailer_html_or_text = 'char';
        }
        if ($mailer_html_or_text == 'html') {
            $this->email->IsHTML(true);
        }

        $body = $this->get('body');
//todo amy - $mailer_html_or_text
        $this->email->set('Body', $this->fieldhandler->filter('subject', $body, 'string'));

        $attachment = $this->get('attachment', '');
        if ($attachment == '') {
        } else {
            $this->email->set(
                'attachment',
                $this->fieldhandler->filter('subject', $attachment, 'file')
            );
        }
        if ($attachment === false || $attachment == '') {
        } else {
            $this->email->AddAttachment(
                $attachment,
                $name = 'Attachment',
                $encoding = 'base64',
                $type = 'application/octet-stream'
            );
        }

        try {

            $this->email->Send();

        } catch (Exception $e) {

            throw new EmailException
            ('Email PhpMailer Handler: Caught Exception: ' . $e->getMessage());
        }
        return $this;
    }

    /**
     * Set Subject
     *
     * @return  object
     * @since   1.0
     * @throws  EmailException
     */
    protected function setSubject()
    {
        $value = (string)$this->get('subject', '');

        if ($value == '') {
            $value = $this->site_name;
        }

        $this->email->set('Subject', $this->fieldhandler->validate('subject', $value, 'string'));

        return $this;
    }

    /**
     * Filter and send to phpMail email address and name values
     *
     * @param   string $field_name
     *
     * @return  null
     * @since   1.0
     */
    protected function processRecipient($field_name)
    {
        $x = explode(';', $this->get($field_name));

        if (is_array($x)) {
            $y = $x;
        } else {
            $y = array($x);
        }

        if (count($y) == 0) {
            return;
        }

        foreach ($y as $z) {

            $extract = explode(',', $z);
            if (count($extract) == 0) {
                break;
            }

            if ($z === false || $z == '') {
                break;
            }
            $z = $this->fieldhandler->filter('email', $extract[0], 'email');
            if ($z === false || $z == '') {
                break;
            }
            $useEmail = $z;

            $useName = '';
            if (count($extract) > 1) {
                $z = $this->fieldhandler->filter($field_name, $extract[1], 'string');
                if ($z === false || $z == '') {
                } else {
                    $useName = $z;
                }
            }

            if ($field_name == 'reply_to') {
                $this->email->AddReplyTo(
                    $this->fieldhandler->filter('reply to email', $useEmail, 'email'),
                    $this->fieldhandler->filter('reply to name', $useName, 'string')
                );

            } elseif ($field_name == 'from') {
                $this->email->SetFrom(
                    $this->fieldhandler->filter('from email', $useEmail, 'email'),
                    $this->fieldhandler->filter('from name', $useName, 'string')
                );

            } elseif ($field_name == 'cc') {
                $this->email->AddCC(
                    $this->fieldhandler->filter('cc email', $useEmail, 'email'),
                    $this->fieldhandler->filter('cc name', $useName, 'string')
                );

            } elseif ($field_name == 'bcc') {
                $this->email->AddBCC(
                    $this->fieldhandler->filter('bcc email', $useEmail, 'email'),
                    $this->fieldhandler->filter('bcc name', $useName, 'string')
                );

            } else {
                $this->email->AddAddress(
                    $this->fieldhandler->filter('bcc email', $useEmail, 'email'),
                    $this->fieldhandler->filter('bcc name', $useName, 'string')
                );
            }
        }
    }

    /**
     * Close the Connection
     *
     * @return  $this
     * @since   1.0
     * @throws  ConnectionException
     */
    public function close()
    {
        return $this;
    }
}
