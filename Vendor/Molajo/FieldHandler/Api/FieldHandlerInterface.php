<?php
/**
 * Interface for Fieldhandler Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Fieldhandler\Api;

use Molajo\Fieldhandler\Exception\FieldhandlerException;

/**
 * Interface for Fieldhandler Adapter
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface FieldHandlerInterface
{
    /**
     * Validate
     *
     * @param   string     $field_name
     * @param   null|mixed $field_value
     * @param   string     $fieldhandler_type_chain
     * @param   array      $options
     *
     * @return  mixed
     * @since   1.0
     * @throws  FieldhandlerException
     */
    public function validate(
        $field_name,
        $field_value = null,
        $fieldhandler_type_chain,
        $options = array()
    );

    /**
     * Filter
     *
     * @param   string     $field_name
     * @param   null|mixed $field_value
     * @param   string     $fieldhandler_type_chain
     * @param   array      $options
     *
     * @return  mixed
     * @since   1.0
     * @throws  FieldhandlerException
     */
    public function filter(
        $field_name,
        $field_value = null,
        $fieldhandler_type_chain,
        $options = array()
    );

    /**
     * Escape
     *
     * @param   string     $field_name
     * @param   null|mixed $field_value
     * @param   string     $fieldhandler_type_chain
     * @param   array      $options
     *
     * @return  mixed
     * @since   1.0
     * @throws  FieldhandlerException
     */
    public function escape(
        $field_name,
        $field_value = null,
        $fieldhandler_type_chain,
        $options = array()
    );
}
