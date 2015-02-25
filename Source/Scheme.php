<?php
/**
 * Scheme
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource;

use stdClass;
use CommonApi\Resource\SchemeInterface;
use CommonApi\Exception\RuntimeException;

/**
 * Scheme
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class Scheme implements SchemeInterface
{
    /**
     * Scheme array =>
     *    Scheme Name =>
     *      Extensions list
     *      Adapter
     *
     * @var    array
     * @since  1.0.0
     */
    protected $scheme_array = array();

    /**
     * Constructor
     *
     * @param  string $scheme_filename
     *
     * @since  1.0.0
     */
    public function __construct($scheme_filename)
    {
        $this->readSchemes($scheme_filename);
    }

    /**
     * Get Scheme (or all schemes)
     *
     * @param   string $scheme
     *
     * @return  array
     * @since   1.0.0
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
     * @param   string $filename
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function readSchemes($filename)
    {
        $this->scheme_array = array();

        $schemes = $this->getSchemeData($filename);

        foreach ($schemes as $values) {
            $this->processScheme($filename, $values);
        }

        return $this;
    }

    /**
     * Get Scheme Data
     *
     * @param   string $filename
     *
     * @return  array
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function getSchemeData($filename)
    {
        if (file_exists($filename)) {
        } else {
            throw new RuntimeException('Scheme Class: filename not found - ' . $filename);
        }

        $input = file_get_contents($filename);

        return json_decode($input);
    }

    /**
     * Process Scheme
     *
     * @param   string $filename
     * @param   array  $values
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function processScheme($filename, $values)
    {
        list($scheme_name, $adapter, $extensions) = $this->processSchemeValues($filename, $values);

        $this->setScheme($scheme_name, $adapter, $extensions, false);

        return $this;
    }

    /**
     * Process Scheme Values
     *
     * @param   string $filename
     * @param   array  $values
     *
     * @return  $this
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function processSchemeValues($filename, array $values = array())
    {
        $scheme_name = '';
        $adapter     = '';
        $extensions  = '';

        foreach ($values as $key => $value) {

            if ($key === 'Name') {
                $scheme_name = $value;

            } elseif ($key === 'Adapter') {
                $adapter = $value;

            } elseif ($key === 'RequireFileExtensions') {
                $extensions = $value;

            } else {
                throw new RuntimeException('Resource File ' . $filename . ' unknown key: ' . $key);
            }
        }

        return array($scheme_name, $adapter, $extensions);
    }

    /**
     * Define Scheme, associated Adapter and allowable file extensions (empty array means any extension allowed)
     *
     * @param   string $scheme_name
     * @param   string $adapter
     * @param   array  $extensions
     * @param   bool   $replace
     *
     * @return  $this
     * @since   1.0.0
     */
    public function setScheme($scheme_name, $adapter = 'File', array $extensions = array(), $replace = false)
    {
        $scheme = new stdClass();

        $this->setSchemeName($scheme, $scheme_name);
        $this->setFileExtensions($scheme, $extensions);
        $this->setSchemeAdapter($scheme, $adapter);

        $this->scheme_array[$scheme->name] = $scheme;

        return $this;
    }

    /**
     * Set Scheme Name
     *
     * @param   object $scheme
     * @param   string $scheme_name
     *
     * @return  object
     * @since   1.0.0
     */
    protected function setSchemeName($scheme, $scheme_name)
    {
        $scheme->name = strtolower(trim($scheme_name));

        if ($scheme_name === '') {
            throw new RuntimeException('Resource File ' . $scheme_name . ' must provide Name for each Scheme.');
        }

        return $scheme;
    }

    /**
     * Set File Extensions
     *
     * @param   object $scheme
     * @param   mixed  $extensions
     *
     * @return  object
     * @since   1.0.0
     */
    protected function setFileExtensions($scheme, $extensions)
    {
        if (is_array($extensions)) {

        } elseif (trim($extensions) === '') {
            $extensions = array();

        } else {
            $temp         = $extensions;
            $extensions   = array();
            $extensions[] = $temp;
        }

        $scheme->include_file_extensions = $extensions;

        return $scheme;
    }


    /**
     * Set Scheme Adapter
     *
     * @param   object $scheme
     * @param   string $adapter
     *
     * @return  object
     * @since   1.0.0
     */
    protected function setSchemeAdapter($scheme, $adapter)
    {
        if ($adapter === '') {
            $adapter = $scheme->name;
        }

        $scheme->adapter       = trim($adapter);
        $scheme->adapter_class = 'Molajo\\Resource\\Adapter\\' . $scheme->adapter;

        if (class_exists($scheme->adapter_class)) {
        } else {
            throw new RuntimeException('Resource Scheme Adapter Class: ' . $scheme->adapter_class);
        }

        return $scheme;
    }
}
