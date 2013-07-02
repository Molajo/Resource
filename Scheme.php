<?php
/**
 * Scheme
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resources;

use Molajo\Resources\Api\SchemeInterface;
use Molajo\Resources\Exception\ResourcesException;

/**
 * Scheme
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class Scheme implements SchemeInterface
{
    /**
     * Scheme array =>
     *    Scheme Name =>
     *      Extensions list
     *      Handler
     *
     * @var    array
     * @since  1.0
     */
    protected $scheme_array = array();

    /**
     * Constructor
     *
     * @param  string $scheme_array
     *
     * @since  1.0
     */
    public function __construct(
        $scheme_array = 'files/SchemeArray.json'
    ) {
        $class_array = 'scheme_array';
        $filename    = $scheme_array;
        $this->readFile($filename, $class_array);
    }

    /**
     * Get Scheme
     *
     * @param   string $scheme
     *
     * @return  bool|object
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function getScheme($scheme)
    {
        if (isset($this->scheme[$scheme])) {
            return $this->scheme[$scheme];
        }

        return false;
    }

    /**
     * Add Scheme to Associate with Resource
     *
     * @param   string $scheme
     * @param   string $handler
     * @param   array  $extensions
     * @param   bool   $replace
     *
     * @return  $this|void
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function setScheme($scheme, $handler = 'File', array $extensions = array(), $replace = false)
    {
        $scheme_row          = new \stdClass();
        $scheme_row->scheme  = $scheme;
        $scheme_row->handler = $handler;

        foreach ($extensions as $extension) {
            $scheme->extensions[] = $extension;
        }

        if (isset($this->scheme[$scheme])) {
        } else {
            $this->scheme[$scheme] = $scheme_row;
        }

        return $this;
    }

    /**
     * Read File
     *
     * @param  string $name
     * @param  string $class_array
     *
     * @since  1.0
     */
    public function readFile($filename, $class_array)
    {
        $temp_array = array();

        $filename = __DIR__ . '/' . $filename;

        if (file_exists($filename)) {
        } else {
            return;
        }

        $input = file_get_contents($filename);
        $temp  = json_decode($input);

        if (count($temp) > 0) {
            $temp_array = array();
            foreach ($temp as $key => $value) {
                $temp_array[$key] = $value;
            }
        }

        $this->$class_array = $temp_array;
    }
}
