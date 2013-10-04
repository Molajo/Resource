<?php
/**
 * Encrypt Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\User\Api;

use Molajo\User\Exception\EncryptException;

/**
 * Encrypt Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface EncryptInterface
{
    /**
     * Create Hash from the input string
     *
     * For use with passwords, the hash is what is stored in the database
     *
     * @param   string $input
     *
     * @return  string
     * @since   1.0
     * @throws  EncryptException
     */
    public function createHashString($input);

    /**
     * Verify Input String to Hash
     *
     * For use with passwords, the input is the real password, but the hash is from the database
     *
     * @param   string $input
     * @param   string $hash
     *
     * @return  boolean
     * @since   1.0
     * @throws  EncryptException
     */
    public function verifyHashString($input, $hash);

    /**
     * Creates random string using base64 characters (a-zA-Z0-9./) for generating tokens
     *
     * @param   int $length
     *
     * @return  boolean
     * @since   1.0
     * @throws  EncryptException
     */
    public function getRandomToken($length = 64);
}
