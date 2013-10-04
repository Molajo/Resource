<?php
/**
 * Database Handler for Route
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Route\Handler;

use stdClass;
use Exception;
use Molajo\Controller\ReadController;
use Molajo\Route\Api\RouteInterface;
use Molajo\Route\Exception\RouteException;

/**
 * Database Handler for Route
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Database extends AbstractHandler implements RouteInterface
{
    /**
     * Resource Query
     *
     * @var    object  Molajo\Controller\ReadController
     * @since  1.0
     */
    protected $resource_query = null;

    /**
     * Constructor
     *
     * @param  object         $request
     * @param  object         $parameters
     * @param  array          $filters
     * @param  ReadController $resource_query
     *
     * @since   1.0
     */
    public function __construct(
        $request,
        $parameters,
        array $filters = array(),
        ReadController $resource_query
    ) {
        parent::__construct(
            $request,
            $parameters,
            $filters
        );

        $this->resource_query = $resource_query;
    }

    /**
     * For Route, retrieve Catalog Item, either for the SEF URL or the Catalog ID
     *
     * 404 Error when no Catalog Item is found
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\Route\Exception\RouteException
     */
    public function setRoute()
    {

        /* test 1: Application 2, Site 1

            Retrieve Catalog ID: 831 using Source ID: 1 and Catalog Type ID: 1000

                     $catalog_id = 0;
                     $url_sef_request = '';
                     $source_id = 1;
                     $catalog_type_id = 1000;
        */

        /* test 2: Application 2, Site 1

            Retrieve Catalog ID: 1075 using $url_sef_request = 'articles'

                $catalog_id = 0;
                $url_sef_request = 'articles';
                $source_id = 0;
                $catalog_type_id = 0;
        */

        /* test 3: Application 2, Site 1

            Retrieve Item: for Catalog ID 1075

                $catalog_id = 1075;
                $url_sef_request = '';
                $source_id = 0;
                $catalog_type_id = 0;
         */
        $this->resource_query->setModelRegistry('use_special_joins', 1);
        $this->resource_query->setModelRegistry('process_plugins', 0);
        $this->resource_query->setModelRegistry('query_object', 'item');

        $prefix = $this->resource_query->getModelRegistry('primary_prefix', 'a');
        $key    = $this->resource_query->getModelRegistry('primary_key', 'id');

        /** Catalog ID  */
        if ((int)$this->parameters->route->catalog_id > 0) {

            $this->resource_query->model->query->where(
                $this->resource_query->model->database->qn($prefix)
                . ' . '
                . $this->resource_query->model->database->qn($key)
                . ' = '
                . (int)$this->parameters->route->catalog_id
            );

            /** Source ID and Catalog Type ID */
        } elseif ((int)$this->parameters->route->source_id > 0
            && (int)$this->parameters->route->catalog_type_id > 0
        ) {

            $this->resource_query->model->query->where(
                $this->resource_query->model->database->qn($prefix)
                . ' . '
                . $this->resource_query->model->database->qn('catalog_type_id')
                . ' = '
                . (int)$this->parameters->route->catalog_type_id
            );

            $this->resource_query->model->query->where(
                $this->resource_query->model->database->qn($prefix)
                . ' . '
                . $this->resource_query->model->database->qn('source_id')
                . ' = '
                . (int)$this->parameters->route->source_id
            );

            /** SEF Request  */
        } else {
            $this->resource_query->model->query->where(
                $this->resource_query->model->database->qn($prefix)
                . ' . '
                . $this->resource_query->model->database->qn('sef_request')
                . ' = '
                . $this->resource_query->model->database->q($this->parameters->application->path)
            );
        }

        /** Extension Join */

        /** Standard Query Values */
        $this->resource_query->model->query->where(
            $this->resource_query->model->database->qn($prefix)
            . ' . '
            . $this->resource_query->model->database->qn('application_id')
            . ' = '
            . $this->resource_query->model->database->q($this->parameters->application->id)
        );

        $this->resource_query->model->query->where(
            $this->resource_query->model->database->qn($prefix)
            . ' . '
            . $this->resource_query->model->database->qn('page_type')
            . ' <> '
            . $this->resource_query->model->database->q($this->parameters->reference_data->page_type_link)
        );

        $this->resource_query->model->query->where(
            $this->resource_query->model->database->qn($prefix)
            . ' . '
            . $this->resource_query->model->database->qn('enabled')
            . ' = 1 '
        );

        /** Run the Query */
        try {
            $item = $this->resource_query->getData();

        } catch (Exception $e) {
            throw new RouteException ($e->getMessage());
        }

        /** 404 */
        if (count($item) == 0 || $item === false) {
            $this->parameters->route->route_found = 0;

            return $this->parameters;
        }

        /** Redirect */
        if ((int)$item->redirect_to_id == 0) {
        } else {
            $this->parameters->redirect_to_id = (int)$item->redirect_to_id;

            return $this->parameters;
        }

        /** Found */
        $this->parameters->route->route_found = 1;

        if ((int)$this->parameters->route->catalog_id
            == (int)$this->parameters->application->parameters->application_home_catalog_id
        ) {
            $this->parameters->route->home = 1;
            $item->path                    = '';
        } else {
            $this->parameters->route->home = 0;
        }

        foreach (\get_object_vars($item) as $key => $value) {

            $this->parameters->route->$key = $value;

            if ($key == 'b_model_name') {
                $this->parameters->route->model_name = ucfirst(strtolower($item->b_model_name));
                $this->parameters->route->model_type = ucfirst(strtolower($item->b_model_type));
                $this->parameters->route->model_registry_name
                                                     = $this->parameters->route->model_name . $this->parameters->route->model_type;
            }
        }

        $this->parameters->route->catalog_id      = $this->parameters->route->id;
        $this->parameters->route->source_id       = $this->parameters->route->source_id;
        $this->parameters->route->catalog_type_id = $this->parameters->route->catalog_type_id;

        return $this->parameters;
    }
}
