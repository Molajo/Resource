<?php
/**
 * Site Service
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Controller;

use stdClass;
use Molajo\Controller\Exception\SiteException;
use Molajo\Controller\Api\SiteInterface;

/**
 * Site Services
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class SiteController implements SiteInterface
{
    /**
     * Sites XML containing defines information
     *
     * @var    object
     * @since  1.0
     */
    protected $reference_data_xml = array();

    /**
     * Sites XML identifying sites on this implementation
     *
     * @var    object
     * @since  1.0
     */
    protected $sites = null;

    /**
     * Host
     *
     * @var    string
     * @since  1.0
     */
    protected $host = null;

    /**
     * Site ID
     *
     * @var    string
     * @since  1.0
     */
    protected $id = null;

    /**
     * Site Name
     *
     * @var    string
     * @since  1.0
     */
    protected $name = null;

    /**
     * Site Base URL
     *
     * @var    string
     * @since  1.0
     */
    protected $base_url = null;

    /**
     * Site Base Folder
     *
     * @var    string
     * @since  1.0
     */
    protected $base_folder = null;

    /**
     * Site Base Path
     *
     * @var    string
     * @since  1.0
     */
    protected $base_path = null;

    /**
     * Sites Base Folder
     *
     * @var    string
     * @since  1.0
     */
    protected $sites_base_folder = null;

    /**
     * Sites Media Folder
     *
     * @var    string
     * @since  1.0
     */
    protected $sites_media_folder = null;

    /**
     * Sites Media URL
     *
     * @var    string
     * @since  1.0
     */
    protected $sites_media_url = null;

    /**
     * List of Properties
     *
     * @var    object
     * @since  1.0
     */
    protected $property_array = array(
        'sites_base_folder',
        'sites_media_folder',
        'sites_media_url',
        'id',
        'name',
        'base_url',
        'base_folder'
    );

    /**
     * Constructor
     *
     * @param  string $host
     * @param  string $base_url
     * @param  string $path
     * @param  array  $reference_data_xml
     * @param  array  $sites
     *
     * @since  1.0
     */
    public function __construct(
        $host,
        $base_url,
        $path,
        $reference_data_xml,
        $sites
    ) {
        $this->host               = $host;
        $this->base_url           = $base_url;
        $this->path               = $path;
        $this->reference_data_xml = $reference_data_xml;
        $this->sites              = $sites;
    }

    /**
     * Get the current value (or default) of the specified key
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\SiteException
     */
    public function get($key = null, $default = null)
    {
        if ($key == '*') {
            $site = new stdClass();
            foreach ($this->property_array as $key) {
                $site->$key = $this->$key;
            }
            return $site;
        }

        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            throw new SiteException
            ('Site Service: attempting to get value for unknown property: ' . $key);
        }

        if ($this->$key === null) {
            $this->$key = $default;
        }

        return $this->$key;
    }

    /**
     * Define Site URL and Folder using scheme, host, and base URL
     *
     * @return  $this
     * @since   1.0
     */
    public function setBaseURL()
    {
        if (defined('BASE_URL')) {
        } else {
            /**
             * BASE_URL - root of the website with a trailing slash
             */
            define('BASE_URL', $this->base_url . '/');
        }

        $this->sites_base_folder  = BASE_FOLDER . '/Sites';
        $this->sites_media_folder = $this->sites_base_folder . '/Media';
        $this->sites_media_url    = BASE_URL . 'Sites/Media';

        return $this;
    }

    /**
     * Identifies the specific site and sets site paths for use in the application
     *
     * @return  $this
     * @since   1.0
     * @throws  SiteException
     */
    public function identifySite()
    {
        foreach ($this->sites as $single) {

            if (strtolower((string)$single->site_base_url) == strtolower($this->host)) {

                $this->id          = $single->id;
                $this->name        = $single->name;
                $this->base_folder = $single->site_base_folder;
                $this->base_path   = BASE_FOLDER . (string)$single->site_base_folder;

                if (substr($this->path, 0, strlen('installation')) == 'installation') {
                } else {
                    $this->installCheck();
                }

                break;
            }
        }

        if ($this->base_folder === null) {
            throw new SiteException
            ('Sites Service: Cannot identify site for: ' . $this->base_url);
        }

        return $this;
    }

    /**
     * Custom set of reference data loaded for consistency in Application
     *
     * @return  $this
     * @since   1.0
     */
    public function setReferenceData()
    {
        if (count($this->reference_data_xml) > 0) {
        } else {
            return $this;
        }

        $reference_data = new stdClass();
        foreach ($this->reference_data_xml->define as $item) {
            if (defined((string)$item['name'])) {
            } else {
                $value                         = (string)$item['value'];
                $reference_data->$item['name'] = $value;
            }
        }

        return $reference_data;
    }

    /**
     * Determine if the site has already been installed
     *
     * return  $this
     *
     * @since  1.0
     */
    public function installCheck()
    {
        if (defined('SKIP_INSTALL_CHECK')) {
            return $this;
        }

        if (file_exists($this->base_path . '/Temp/Index.html')) {
            return $this;
        }

        $redirect = $this->host . 'installation/';
        header('Location: ' . $redirect);
        exit();
    }
}
