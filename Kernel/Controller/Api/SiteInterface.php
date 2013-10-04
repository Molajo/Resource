<?php
/**
 * Site Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Controller\Api;

use Molajo\Controller\Exception\SiteException;

/**
 * Site Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface SiteInterface
{
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
    public function get($key = null, $default = null);

    /**
     * Define Site URL and Folder using scheme, host, and base URL
     *
     * @return  $this
     * @since   1.0
     */
    public function setBaseURL();

    /**
     * Identifies the specific site and sets site paths for use in the application
     *
     * @return  $this
     * @since   1.0
     * @throws  SiteException
     */
    public function identifySite();

    /**
     * Custom set of reference data loaded for consistency in Application
     *
     * @return  $this
     * @since   1.0
     */
    public function setReferenceData();

    /**
     * Determine if the site has already been installed
     *
     * return  $this
     *
     * @since  1.0
     */
    public function installCheck();
}
