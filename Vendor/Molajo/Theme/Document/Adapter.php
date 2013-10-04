<?php
/**
 * Adapter for Document
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Document;


use Molajo\Theme\Api\DocumentInterface;

use Molajo\Theme\Exception\DocumentException;

/**
 * Adapter for Document
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class Adapter implements DocumentInterface
{
    /**
     * Document Handler
     *
     * @var     object
     * @since   1.0
     */
    public $dt;

    /**
     * Construct
     *
     * @param   string $document_type
     *
     * @since   1.0
     * @throws  DocumentException
     */
    public function __construct($document_type = 'Css')
    {
        if ($document_type == '') {
            $document_type = 'Css';
        }

        $this->getDocumentHandler($document_type);

        return $this->dt;
    }

    /**
     * Get the Document Handler (Css, Js, Links, Metadata)
     *
     * @param   string $document_type
     *
     * @return  $this
     * @since   1.0
     * @throws  DocumentException
     */
    protected function getDocumentHandler($document_type)
    {
        $class = 'Molajo\\Document\\Handler\\' . $document_type;

        try {

            $this->dt = new $class();

        } catch (Exception $e) {

            throw new DocumentException
            ('Document: getDocumentHandler instance ' . $class
            . ' failed.' . $e->getMessage());
        }

        return $this;
    }

    /**
     * Return results for document
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  DocumentException
     */
    public function get($key, $default)
    {
        $this->dt->get($key, $default);
    }

    /**
     * Set the value of a key
     *
     * @param   string $key
     * @param   array  $options
     *
     * @return  object
     * @since   1.0
     * @throws  DocumentException
     */
    public function set($key, $options = array())
    {
        $this->dt->set($key, $options);
    }

    /**
     * Remove item
     *
     * @param   string $key
     *
     * @return  mixed
     * @since   1.0
     * @throws  DocumentException
     */
    public function remove($key)
    {
        $this->dt->remove($key);
    }
}
