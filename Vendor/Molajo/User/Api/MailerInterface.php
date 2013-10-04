<?php
/**
 * Mailer Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\User\Api;

use Molajo\User\Api\TemplateInterface;
use Molajo\User\Exception\MailerException;

/**
 * Mailer Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface MailerInterface
{
    /**
     * Set the Option Values, Initiate Rendering, Send
     *
     * @param   array                  $options
     * @param   null|TemplateInterface $template
     *
     * @return  $this
     * @since   1.0
     * @throws  MailerException
     */
    public function render(
        $options = array(),
        TemplateInterface $template = null
    );

    /**
     * Send Email
     *
     * @return  $this
     * @since   1.0
     * @throws  MailerException
     */
    public function send();
}
