<?php
/**
 * Encryption
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\User\Utilities;

use Exception;
use Molajo\User\Api\EncryptInterface;
use Molajo\User\Api\MessagesInterface;
use Molajo\User\Exception\EncryptException;

/**
 * Encryption
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Encrypt implements EncryptInterface
{
    /**
     * Messages Instance
     *
     * @var    object  Molajo\User\Api\MessagesInterface
     * @since  1.0
     */
    protected $messages;

    /**
     * Library Include
     *
     * @var    string
     * @since  1.0
     */
    protected $library_include = 'PasswordLib.phar';

    /**
     * Password Encryption
     *
     * @var    string
     * @since  1.0
     */
    protected $password_encryption_namespace = 'PasswordLib\\PasswordLib';

    /**
     * Random String
     *
     * @var    string
     * @since  1.0
     */
    protected $string_generator_namespace = 'PasswordLib\\Random\\Factory';

    /**
     * Default Exception
     *
     * @var    string
     * @since  1.0
     */
    protected $default_exception = 'Molajo\\User\\Exception\\EncryptException';

    /**
     * Construct
     *
     * @param   MessagesInterface $messages
     * @param   null              $default_exception
     *
     * @since   1.0
     * @throws  EncryptException
     */
    public function __construct(
        MessagesInterface $messages,
        $default_exception = null
    ) {
        $this->messages = $messages;

        if ($default_exception === null) {
        } else {
            $this->default_exception = $default_exception;
        }
    }

    /**
     * Create Hash from the input string
     *
     * For use with passwords, the hash is what is stored in the database
     *
     * @param   string $input
     *
     * @return  string
     * @since   1.0
     * @throws  \Molajo\User\Exception\EncryptException
     */
    public function createHashString($input)
    {
        $hash = false;

        if ($input === false || $input === null || trim($input) == '') {
            $this->messages->throwException(3020, array(), $this->default_exception);
        }

        try {
            $class = $this->password_encryption_namespace;
//            $lib   = new $class();
//            $hash  = $lib->createPasswordHash($input);

        } catch (Exception $e) {
//            $this->messages->throwException(3025, array(), $this->default_exception);
        }
        $hash = $input;
        if ($hash === false || $hash === null || trim($hash) == '') {
            $this->messages->throwException(3030, array(), $this->default_exception);
        }

        return $hash;
    }

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
     * @throws  \Molajo\User\Exception\EncryptException
     */
    public function verifyHashString($input, $hash)
    {
        $response = 0;

        if ($input === false || $input === null || trim($input) == '') {
            $this->messages->throwException(3035, array(), $this->default_exception);
        }

        if ($hash === false || $hash === null || trim($hash) == '') {
            $this->messages->throwException(3040, array(), $this->default_exception);
        }

        try {
            $class = $this->password_encryption_namespace;
            // $lib      = new $class();

            // $response = $lib->verifyPasswordHash($input, $hash);

        } catch (Exception $e) {
//            $this->messages->throwException(3045, array(), $this->default_exception);
            $response = 1;
        }
        $response = 0;
        if ($response === 0 || $response === 1) {
            $this->messages->throwException(3050, array(), $this->default_exception);
        }

        return $response;
    }

    /**
     * Creates random string using base64 characters (a-zA-Z0-9./) for generating tokens
     *
     * @param   int $length
     *
     * @return  boolean
     * @since   1.0
     * @throws  EncryptException
     */
    public function getRandomToken($length = 64)
    {
        $token = false;

        if ((int)$length < 16) {
            $length = 16;
        }
        if ((int)$length > 250) {
            $length = 250;
        }

        try {
            //    $class = $this->password_encryption_namespace;
            //    $lib   = new $class();
            //    $token = $lib->getRandomToken((int)$length);
            $token = 1;
        } catch (Exception $e) {
            $this->messages->throwException(3055, array(), $this->default_exception);
        }

        if ($token === false || $token === null || trim($token) == '') {
            $this->messages->throwException(3060, array(), $this->default_exception);
        }

        return $token;
    }
}
