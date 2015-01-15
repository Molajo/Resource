<?php
/**
 * Read Controller Factory
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Factory;

use Exception;
use CommonApi\Exception\RuntimeException;
use CommonApi\Model\ModelInterface;
use CommonApi\Query\QueryInterface;
use Molajo\Resource\Api\FactoryInterface;

/**
 * Read Controller Factory
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class ReadControllerFactory implements FactoryInterface
{
    /**
     * Query Instance
     *
     * @var    object  CommonApi\Query\QueryInterface
     * @since  1.0
     */
    protected $query = null;

    /**
     * Model Instance
     *
     * @var    object  CommonApi\Model\ModelInterface
     * @since  1.0
     */
    protected $model;

    /**
     * Model Registry
     *
     * @var    array
     * @since  1.0
     */
    protected $model_registry = null;

    /**
     * Runtime Data
     *
     * @var    object
     * @since  1.0
     */
    protected $runtime_data = null;

    /**
     * Schedule Event - anonymous function to FrontController schedule_event method
     *
     * @var    callable
     * @since  1.0
     */
    protected $schedule_event;

    /**
     * SQL
     *
     * @var    string
     * @since  1.0
     */
    protected $sql;

    /**
     * Constructor
     *
     * @param  QueryInterface $query
     * @param  ModelInterface $model
     * @param  array          $model_registry
     * @param  object         $runtime_data
     * @param  callback       $schedule_event
     * @param  string         $sql
     *
     * @since  1.0
     */
    public function __construct(
        QueryInterface $query,
        ModelInterface $model,
        $model_registry,
        $runtime_data,
        callable $schedule_event,
        $sql = ''
    ) {
        $this->query          = $query;
        $this->model          = $model;
        $this->model_registry = $model_registry;
        $this->runtime_data   = $runtime_data;
        $this->schedule_event = $schedule_event;
        $this->sql            = $sql;
    }

    /**
     * Instantiate Class, load the Model Registry and SQL
     *
     * @return  object
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function instantiateClass()
    {
        $class = 'Molajo\\Controller\\ReadController';

        try {
            $instance = new $class (
                $this->model,
                $this->runtime_data,
                $this->schedule_event,
                $this->query
            );
        } catch (Exception $e) {
            throw new RuntimeException(
                'Resource Factory ReadControllerFactory failed in instantiateClass Method.' . $e->getMessage()
            );
        }

        $instance->clearQuery();

        $instance->setModelRegistry('*', $this->model_registry);

        if (trim($this->sql) === '') {
        } else {
            $instance->set('sql', $this->sql);
        }

        return $instance;
    }
}
