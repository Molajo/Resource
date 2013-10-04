<?php
/**
 * User Fieldhandler Utility
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\User\Utilities;

use Exception;
use Molajo\FieldHandler\Api\FieldHandlerInterface as UserFieldHandlerInterface;
use Molajo\User\Api\MessagesInterface;
use Molajo\Fieldhandler\Api\FieldHandlerInterface;

/**
 * User Fieldhandler Utility
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Fieldhandler implements UserFieldHandlerInterface
{
    /**
     * Fieldhandler Instance
     *
     * @var    object  Molajo\Fieldhandler\Api\FieldHandlerInterface
     * @since  1.0
     */
    protected $fieldhandler;

    /**
     * Message Instance
     *
     * @var    object  Molajo\Fieldhandler\Api\MessagesInterface
     * @since  1.0
     */
    protected $messages;

    /**
     * Construct
     *
     * @param FieldHandlerInterface $fieldhandler
     * @param MessagesInterface     $messages
     *
     * @since  1.0
     */
    public function __construct(
        FieldHandlerInterface $fieldhandler,
        MessagesInterface $messages
    ) {
        $this->fieldhandler = $fieldhandler;
        $this->messages     = $messages;
    }

    /**
     * Validate Input
     *
     * @param   string      $key
     * @param   null|string $value
     * @param   string      $fieldhandler_type_chain
     * @param   array       $options
     *
     * @return  $this|mixed
     * @since   1.0
     */
    public function validate($key, $value = null, $fieldhandler_type_chain, $options = array())
    {
        return $this->fieldhandler->validate($key, $value, $fieldhandler_type_chain, $options);
    }

    /**
     * Filter Input
     *
     * @param   string      $key
     * @param   null|string $value
     * @param   string      $fieldhandler_type_chain
     * @param   array       $options
     *
     * @return  $this|mixed
     * @since   1.0
     */
    public function filter($key, $value = null, $fieldhandler_type_chain, $options = array())
    {
        return $this->fieldhandler->filter($key, $value, $fieldhandler_type_chain, $options);
    }

    /**
     * Escape Input
     *
     * @param   string      $key
     * @param   null|string $value
     * @param   string      $fieldhandler_type_chain
     * @param   array       $options
     *
     * @return  $this|mixed
     * @since   1.0
     */
    public function escape($key, $value = null, $fieldhandler_type_chain, $options = array())
    {
        return $this->fieldhandler->filter($key, $value, $fieldhandler_type_chain, $options);
    }
}
