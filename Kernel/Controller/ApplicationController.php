<?php
/**
 * Application Controller
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Controller;

use stdClass;
use Molajo\Database\Api\DatabaseInterface;
use Molajo\Controller\Api\CustomfieldsControllerInterface;
use Molajo\Controller\Exception\ApplicationException;
use Molajo\Controller\Api\ApplicationControllerInterface;

/**
 * Application Controller
 *
 * 1. Identifies Current Application
 * 2. Loads Application
 * 3. Defines Site Paths for Application
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class ApplicationController implements ApplicationControllerInterface
{
    /**
     * Catalog Type Application ID
     *
     * @var    int
     * @since  1.0
     */
    protected $catalog_type_application_id = 2000;

    /**
     * Database Instance
     *
     * @var    object
     * @since  1.0
     */
    protected $database;

    /**
     * Customfields Controller
     *
     * @var    object  Molajo\Controller\Api\CustomfieldsControllerInterface
     * @since  1.0
     */
    protected $customfields;

    /**
     * Applications Instances XML
     *
     * @var    object
     * @since  1.0
     */
    protected $applications = null;

    /**
     * Application Model Registry
     *
     * @var    object
     * @since  1.0
     */
    protected $model_registry = null;

    /**
     * Base URL Path with Scheme
     *
     * @var    string
     * @since  1.0
     */
    protected $request_uri = null;

    /**
     * Application Data
     *
     * @var    array
     * @since  1.0
     */
    protected $data = null;

    /**
     * Application Name
     *
     * @var    string
     * @since  1.0
     */
    protected $name = null;

    /**
     * Application ID
     *
     * @var    int
     * @since  1.0
     */
    protected $id = null;

    /**
     * Application Base Path
     *
     * @var    string
     * @since  1.0
     */
    protected $base_path = null;

    /**
     * Application Base Path
     *
     * @var    string
     * @since  1.0
     */
    protected $path = null;

    /**
     * Constructor
     *
     * @param                                 $applications
     * @param                                 $model_registry
     * @param DatabaseInterface               $database
     * @param CustomfieldsControllerInterface $customfields
     * @param                                 $request_uri
     * @param                                 $request_base_url
     *
     * @since  1.0
     */
    public function __construct(
        $applications,
        $model_registry,
        DatabaseInterface $database,
        CustomfieldsControllerInterface $customfields,
        $request_uri,
        $request_base_url
    ) {
        $this->applications     = $applications;
        $this->model_registry   = $model_registry;
        $this->database         = $database;
        $this->customfields     = $customfields;
        $this->request_uri      = $request_uri;
        $this->request_base_url = $request_base_url;
    }

    /**
     * Using Request URI, identify current application and page request
     *
     * @return  $this
     * @since   1.0
     */
    public function setApplication()
    {
        if (strpos($this->request_uri, '/') == 0) {
            $this->request_uri = substr($this->request_uri, 1, 99999);
        }

        if (strpos($this->request_uri, '/')) {
            $applicationTest = substr($this->request_uri, 0, strpos($this->request_uri, '/'));
        } else {
            $applicationTest = $this->request_uri;
        }

        foreach ($this->applications as $app) {

            $xml_name = (string)$app->name;

            if (strtolower(trim($xml_name)) == strtolower(trim($applicationTest))) {

                $this->name      = $app->name;
                $this->id        = $app->id;
                $this->base_path = $app->name . '/';
                $this->path      = substr($this->request_uri, strlen($this->base_path), 999);

                break;
            }
        }

        if ($this->name === null) {

            $this->name      = $this->applications->default->name;
            $this->id        = $this->applications->default->id;
            $this->base_path = '';
            $this->path      = $this->request_uri;
        }

        return $this;
    }

    /**
     * Retrieve Application Data
     *
     * @return  $this
     * @since   1.0
     * @throws  ApplicationException
     */
    public function getConfiguration()
    {
        $this->data = new stdClass();

        if ($this->name == 'installation') {
            $this->data->id          = 0;
            $this->data->name        = $this->name;
            $this->data->description = $this->name;

            return $this;
        }

        $query = $this->database->getQueryObject();

        $query->select('a.*');
        $query->select('b.id' . ' as catalog_id');
        $query->from($this->database->qn('#__applications') . ' ' . $this->database->qn('a'));
        $query->from($this->database->qn('#__catalog') . ' ' . $this->database->qn('b'));
        $query->where(
            $this->database->qn('a.name')
            . ' = ' . $this->database->q($this->name)
        );
        $query->where(
            $this->database->qn('b.extension_instance_id')
            . ' = ' . $this->database->q($this->catalog_type_application_id)
        );
        $query->where(
            $this->database->qn('b.source_id')
            . ' = ' . $this->database->qn('a.id')
        );
        $query->where(
            $this->database->qn('b.application_id')
            . ' = ' . $this->database->qn('a.id')
        );

        $query->where(
            $this->database->qn('b.enabled')
            . ' = ' . $this->database->q(1)
        );

        $x = $this->database->loadObjectList();

        if ($x === false) {
            throw new ApplicationException ('Application: Error executing getApplication Query');
        } else {
            $data = $x[0];
        }

        if ($this->model_registry === null) {
            throw new ApplicationException ('Application: Model Registry for Application Configuration missing');
        }

        $this->data->id              = (int)$data->id;
        $this->data->base_path       = $this->base_path;
        $this->data->path            = $this->path;
        $this->data->name            = $this->name;
        $this->data->description     = $data->description;
        $this->data->catalog_id      = (int)$data->catalog_id;
        $this->data->catalog_type_id = (int)$data->catalog_type_id;

        $custom_field_types = $this->model_registry['customfieldgroups'];

        if (is_array($custom_field_types)) {
        } else {
            $custom_field_types = array();
        }

        if (count($custom_field_types) > 0) {

            $groups = $this->customfields->getCustomfields($this->model_registry, $data);

            if (is_array($groups) && count($groups) > 0) {
                foreach ($groups as $name => $object) {
                    unset($this->data->$name);
                    $this->data->$name = $object;
                }
            }
        }

        if (isset($this->data->parameters->application_html5)
            && $this->data->parameters->application_html5 == 1
        ) {
            $this->data->parameters->application_line_end = '>' . chr(10);
        } else {
            $this->data->parameters->application_html5    = 0;
            $this->data->parameters->application_line_end = '/>' . chr(10);
        }

        return $this->data;
    }

    /**
     * Check if the Site has permission to utilise this Application
     *
     * @param   int $site_id
     *
     * @return  $this
     * @since   1.0
     */
    public function verifySiteApplication($site_id)
    {
        $query = $this->database->getQueryObject();

        $query->select('*');
        $query->from($this->database->qn('#__site_applications'));
        $query->where(
            $this->database->qn('application_id')
            . ' = ' . $this->database->q($this->id)
        );
        $query->where(
            $this->database->qn('site_id')
            . ' = ' . $this->database->q($site_id)
        );

        $valid = $this->database->loadObjectList();

        if ($valid === false) {
        } else {
            return $this;
        }

        die('Site accessing invalid application.');
    }
}
