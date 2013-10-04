<?php
/**
 * Upload Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\FileUpload\Api;

use Molajo\FileUpload\Exception\UploadException;

/**
 * Upload Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface UploadInterface
{
    /**
     * Get the list of File Extensions associated with the Mime Type
     *
     * @param   string $mime_type
     *
     * @return  string
     * @since   1.0
     * @throws  UploadException
     */
    public function getType($mime_type);

    /**
     * Add Valid Mime Type and File Extension Entry to List
     *
     * @param   string $mime_type
     * @param   string $extension
     *
     * @return  $this
     * @since   1.0
     * @throws  UploadException
     */
    public function addType($mime_type, $extension);

    /**
     * Remove Mime Type from List of Valid Values
     *
     * @param   string $mime_type
     *
     * @return  $this
     * @since   1.0
     * @throws  UploadException
     */
    public function removeType($mime_type);

    /**
     * Set the maximum file size for upload
     *
     * @param   string $maximum_file_size
     *
     * @return  integer
     * @since   1.0
     * @throws  UploadException
     */
    public function setMaxFileSize($maximum_file_size);

    /**
     * Set the target folder
     *
     * @param   string $target_folder
     *
     * @return  $this
     * @since   1.0
     */
    public function setTargetFolder($target_folder);

    /**
     * Set Input Field Name
     *
     * @param   string $input_field_name
     *
     * @return  $this
     * @since   1.0
     * @throws  UploadException
     */
    public function setInputFieldName($input_field_name);

    /**
     * Set Overwrite Existing File
     *
     * @param   boolean $overwrite_existing_file
     *
     * @return  $this
     * @since   1.0
     * @throws  UploadException
     */
    public function setOverwriteExistingFile($overwrite_existing_file);

    /**
     * Upload File
     *
     * @param   null|string|array $target_filename
     *
     * @return  $this
     * @since   1.0
     * @throws  UploadException
     */
    public function upload($target_filename = null);
}
