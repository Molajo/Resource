<?php
/**
 * Read Controller
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Controller;

use Molajo\Event\Api\EventInterface;
use Molajo\User\Api\UserInterface;
use Molajo\Model\Api\ModelInterface;
use Molajo\Language\Api\LanguageInterface;
use Molajo\Fieldhandler\Api\FieldHandlerInterface;
use Molajo\Controller\Api\ReadControllerInterface;
use Molajo\Controller\Api\CustomfieldsControllerInterface;
use Molajo\Controller\Exception\ReadControllerException;
use Molajo\Authorisation\Api\AuthorisationInterface;
use Molajo\Cache\Api\CacheInterface;
use Molajo\Http\Api\RedirectInterface;

/**
 * Read Controller
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class ReadController extends Controller implements ReadControllerInterface
{
    /**
     * Class Constructor
     *
     * @param   array $options
     *
     * @since   1.0
     */
    public function __construct(
        AuthorisationInterface $authorisation,
        UserInterface $user = null,
        LanguageInterface $language = null,
        ModelInterface $model,
        $parameters,
        $page_type = null,
        FieldHandlerInterface $fieldhandler,
        CustomfieldsControllerInterface $customfields,
        EventInterface $event,
        CacheInterface $cache,
        $plugins = null,
        RedirectInterface $redirect
    ) {

        parent::__construct(
            $authorisation,
            $user,
            $language,
            $model,
            $parameters,
            $page_type,
            $fieldhandler,
            $customfields,
            $event,
            $cache,
            $plugins,
            $redirect
        );
    }

    /**
     * Method to get data from model
     *
     * @return  mixed
     * @since   1.0
     * @throws  ReadControllerException
     */
    public function getData()
    {
        if ($this->model->getModelRegistry('data_object') == 'Database') {
            $this->getDataDatabase();
        } else {
            $this->getDataNonDatabase();
        }

        $this->onAfterReadEvent(
            $this->model->getModelRegistry('use_pagination'),
            $this->model->getModelRegistry('model_offset'),
            $this->model->getModelRegistry('model_count')
        );

        if ($this->model->getModelRegistry('data_object') == 'Database') {
        } else {
            return $this->query_results;
        }

        if ($this->model->getModelRegistry('query_object') == 'result'
            || $this->model->getModelRegistry('query_object') == 'distinct'
        ) {
            return $this->query_results;
        }

        if (count($this->query_results) === 0
            || $this->query_results === false
        ) {
            return array();
        }

        if (is_array($this->query_results)) {
        } else {
            $this->query_results = array();
        }

        if ($this->model->getModelRegistry('query_object') == 'item') {
            $result              = $this->query_results[0];
            $this->query_results = $result;
        }

        return $this->query_results;
    }

    /**
     * Retrieve data from a database
     *
     * @return  $this
     * @since   1.0
     * @throws  ReadControllerException
     */
    protected function getDataDatabase()
    {
        $this->prepareQuery();

        if (count($this->model->getModelRegistry('plugins')) > 0) {
            $this->onBeforeReadEvent();
        }

//echo '<br /><br />';
//echo $this->model->query->__toString();
//echo '<br /><br />';

        $this->runQuery();

        return $this;
    }

    /**
     * Prepare query object for standard dbo queries
     *
     * @return  $this
     * @since   1.0
     */
    protected function prepareQuery()
    {
        $key = 0;

        if ($this->model->getModelRegistry('primary_key_value')) {
            $key = (int)$this->model->getModelRegistry('primary_key_value');
        }

        if ($key === 0) {
            if (isset($this->parameters->criteria_source_id)) {
                $key = (int)$this->parameters->criteria_source_id;
            }
        }

        if ($key === 0) {
            $this->model->setModelRegistry('primary_key_value', null);
        } else {
            $this->model->setModelRegistry('primary_key_value', $key);
        }

        $this->model->setBaseQuery();
        $this->model->checkPermissions();
        $this->model->useSpecialJoins();
        $this->model->setModelCriteria($this->parameters);

        return $this;
    }

    /**
     * Execute data retrieval query for standard requests
     *
     * @return  $this
     * @since   1.0
     */
    protected function runQuery()
    {
        $this->parameters->pagination_total = (int)$this->model->getQueryResults();

        $this->query_results = $this->model->get('query_results');

        if ($this->model->getModelRegistry('query_object') == 'result'
            || $this->model->getModelRegistry('query_object') == 'distinct'
        ) {
            return $this;
        }

        if ($this->model->getModelRegistry('get_customfields') == 1) {
            $this->getCustomFields();
        }

        return $this;
    }

    /**
     * Adds Custom Fields and Children to Query Results
     *
     * @return  $this
     * @since   1.0
     */
    protected function getCustomFields()
    {
        $q = array();

        foreach ($this->query_results as $row) {

            $groups = $this->customfields->getCustomFields(
                $this->model->model_registry,
                $row,
                $this->parameters,
                $this->page_type
            );

            if (is_array($groups) && count($groups) > 0) {

                foreach ($groups as $name => $object) {

                    unset($row->$name);
                    $extensions = 'extension_instances_' . $name;

                    if (isset($row->$extensions)) {
                        unset($row->$extensions);
                    }

                    $row->$name = $object;
                }
            }

            $q[] = $row;
        }

        $this->query_results = $q;

        return $this;
    }

    /**
     * Retrieve Data from a Non-database datasource
     *
     * @return  $this
     * @since   1.0
     * @throws  ReadControllerException
     */
    protected function getDataNonDatabase()
    {
        if (strtolower($this->model->getModelRegistry('model_name')) == 'dummy') {
            $this->query_results = array();
            return $this;
        }

        $service_class              = $this->model->getModelRegistry('service_class');
        $service_class_query_method = $this->model->getModelRegistry('service_class_query_method');

        if ($this->model->getModelRegistry('model_name') == 'Primary') {
            $method_parameter = 'Data';
        } elseif ($this->model->getModelRegistry('service_class_query_method_parameter') == 'Template') {
            $method_parameter = $this->parameters->template_view_path_node;
        } elseif ($this->model->getModelRegistry('service_class_query_method_parameter') == 'Model') {
            $method_parameter = $this->model->getModelRegistry('model_name');
        } else {
            $method_parameter = $this->model->getModelRegistry('service_class_query_method_parameter');
        }

        if (count($this->plugins) > 0) {
            $this->onBeforeReadEvent();
        }
//callback ?

// Call the $foo->bar() method with 2 arguments
        /**
         * $foo = new foo;
         * call_user_func_array(array($foo, "bar"), array("three", "four"));
         * $this->query_results = Services::$service_class()
         * ->$service_class_query_method(
         * $this->get('model_name'),
         * $method_parameter,
         * $this->get('query_object')
         * );
         */
        return $this;
    }

    /**
     * Schedule onBeforeRead Event
     *
     * - Model Query has been developed and is passed into the event, along with parameters and registry data
     *
     * - Good event for modifying selection criteria, like adding tag selectivity, or setting publishing criteria
     *
     * - Examples: Publishedstatus
     *
     * @return  $this
     * @since   1.0
     */
    public function onBeforeReadEvent()
    {
        return;

        if (defined('ROUTE')) {
        } else {
            return;
        }

        if (count($this->model->getModelRegistry('plugins')) == 0
            || (int)$this->model->getModelRegistry('process_plugins') == 0
        ) {
            return;
        }

        $arguments = array(
            'model'                             => $this->get('model'),
            'model_registry'                    => $this->get('model_registry'),
            'model_registry_name'               => $this->model->getModelRegistry('model_registry_name'),
            'parameters'                        => $this->get('parameters'),
            'query_results'                     => array(),
            'row'                               => null,
            'rendered_output'                   => null,
            'view_path'                         => null,
            'view_path_url'                     => null,
            'plugins'                           => $this->model->getModelRegistry('plugins'),
            'class_array'                       => array(),
            'include_parse_sequence'            => array(),
            'include_parse_exclude_until_final' => array()
        );

        $arguments = $this->event
            ->scheduleEvent('onBeforeRead', $arguments, $this->get('plugins'));

        $this->setPluginResultProperties($arguments);

        return;
    }

    /**
     * Schedule Event onAfterRead Event
     *
     * - After the Query executes, the results of the query are sent through the plugins, one at a time
     *  (this event -- and each of the associated plugins -- run one time for each record returned)
     *
     * - Good time to schedule content modifying plugins, like smilies or image placement.
     *      Examples: Smilies, Images, Linebreaks
     *
     * - Additional data elements can be added to the row -- codes can be expanded into textual descriptions
     *  or profile data added for author, etc.
     *      Examples: Author, CSSclassandids, Gravatar, Dateformats, Email
     *
     * - Use Event carefully as it has perhaps the most potential to negatively impact performance.
     *
     * @return  $this
     * @since   1.0
     */
    public function onAfterReadEvent()
    {
        return;

        if (count($this->plugins) == 0
            || (int)$this->model->getModelRegistry('process_plugins') == 0
        ) {
            return $this;
        }

        $rows                = $this->query_results;
        $this->query_results = array();

        $first = true;

        if (count($rows) == 0) {
        } else {
            foreach ($rows as $row) {

                $this->set('first', $first, 'parameters');

                $arguments = array(
                    'model'                             => $this->get('model'),
                    'model_registry'                    => $this->get('model_registry'),
                    'model_registry_name'               => $this->model->getModelRegistry('model_registry_name'),
                    'parameters'                        => $this->get('parameters'),
                    'query_results'                     => array(),
                    'row'                               => $row,
                    'rendered_output'                   => null,
                    'view_path'                         => null,
                    'view_path_url'                     => null,
                    'plugins'                           => $this->get('plugins'),
                    'class_array'                       => array(),
                    'include_parse_sequence'            => array(),
                    'include_parse_exclude_until_final' => array()
                );

                $arguments = $this->event
                    ->scheduleEvent('onAfterRead', $arguments, $this->get('plugins'));

                $this->setPluginResultProperties($arguments);

                $first = false;
            }
        }

        return $this;
    }

    /**
     * Schedule Event onAfterRead Event
     *
     *  - entire query results passed in as an array
     *
     *  - Good event for inserting an include statement based on the results (maybe a begin and end form)
     *      or when the entire resultset must be handled, like generating a Feed, or JSON output,
     *
     *  - Examples: CssclassandidsPlugin, Pagination, Paging, Useractivity
     *
     * @return  $this
     * @since   1.0
     */
    public function onAfterReadallEvent()
    {
        return;

        if (defined('ROUTE')) {
        } else {
            return $this;
        }

        $arguments = array(
            'model'                             => $this->get('model'),
            'model_registry'                    => $this->get('model_registry'),
            'model_registry_name'               => $this->model->getModelRegistry('model_registry_name'),
            'parameters'                        => $this->get('parameters'),
            'query_results'                     => $this->get('query_results'),
            'row'                               => null,
            'rendered_output'                   => null,
            'view_path'                         => null,
            'view_path_url'                     => null,
            'plugins'                           => $this->get('plugins'),
            'class_array'                       => array(),
            'include_parse_sequence'            => array(),
            'include_parse_exclude_until_final' => array()
        );

        $arguments = $this->event
            ->scheduleEvent('onAfterReadall', $arguments, $this->get('plugins'));

        $this->setPluginResultProperties($arguments);

        return $this;
    }
}
