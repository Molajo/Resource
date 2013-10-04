<?php
/**
 * Scheme
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resources;

use stdClass;
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
     * @param  string $scheme_filename
     *
     * @since  1.0
     */
    public function __construct(
        $scheme_filename = 'files/SchemeArray.json'
    ) {
        $filename = $scheme_filename;
        $this->readSchemes($filename);
    }

    /**
     * Get Scheme (or all schemes)
     *
     * @param   string $scheme
     *
     * @return  object|array
     * @since   1.0
     */
    public function getScheme($scheme = '')
    {
        $scheme = strtolower($scheme);

        if (isset($this->scheme_array[$scheme])) {
            return $this->scheme_array[$scheme];
        }

        return $this->scheme_array;
    }

    /**
     * Read File and populate scheme array
     *
     * @param  string $filename
     *
     * @return  void
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    protected function readSchemes($filename)
    {
        $this->scheme_array = array();

        $filename = __DIR__ . '/' . $filename;

        if (file_exists($filename)) {
        } else {
            return;
        }

        $input = file_get_contents($filename);

        $schemes = json_decode($input);

        if (count($schemes) == 0) {
            return;
        }

        foreach ($schemes as $values) {

            $scheme_name = '';
            $handler     = '';
            $extensions  = array();

            foreach ($values as $key => $value) {

                if ($key == 'Name') {
                    $scheme_name = $value;

                } elseif ($key == 'Handler') {

                    $handler = $value;

                } elseif ($key == 'RequireFileExtensions') {
                    $extensions = $value;

                } else {
                    throw new ResourcesException ('Resources File ' . $filename . ' unknown key: ' . $key);
                }
            }

            if ($scheme_name == '') {
                throw new ResourcesException ('Resources File ' . $filename . ' must provide Name for each Scheme.');
            }

            if ($handler == '') {
                $handler = $scheme_name;
            }

            if (is_array($extensions)) {
            } elseif (trim($extensions) == '') {
                $extensions = array();
            } else {
                $temp         = $extensions;
                $extensions   = array();
                $extensions[] = $temp;
            }

            $this->setScheme($scheme_name, $handler, $extensions, false);
        }

        return;
    }

    /**
     * Define Scheme, associated Handler and allowable file extensions (empty array means any extension allowed)
     *
     * @param   string $scheme
     * @param   string $handler
     * @param   array  $extensions
     * @param   bool   $replace
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Resources\Exception\ResourcesException
     */
    public function setScheme($scheme_name, $handler = 'File', array $extensions = array(), $replace = false)
    {
        $scheme = new stdClass();

        $scheme->name = strtolower(trim($scheme_name));
        if ($scheme->name == '') {
            throw new ResourcesException ('Resources File ' . $scheme_name . ' must provide Name for each Scheme.');
        }

        $scheme->handler = ucfirst(strtolower(trim($handler))) . 'Handler';

        $scheme->handler_class = 'Molajo\\Resources\\Handler\\' . $scheme->handler;

        if (class_exists($scheme->handler_class)) {
        } else {
            throw new ResourcesException ('Resources Scheme Handler Class: ' . $scheme->handler_class);
        }

        $scheme->include_file_extensions = $extensions;

        $this->scheme_array[$scheme->name] = $scheme;

        return $this;
    }
}
