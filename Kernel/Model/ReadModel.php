<?php
/**
 * Read Model
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Model;

use Molajo\Model\Exception\ReadModelException;
use Molajo\Model\Api\ReadModelInterface;
use Molajo\Database\Api\DatabaseInterface;
use Molajo\Fieldhandler\Api\FieldHandlerInterface;
use Molajo\Authorisation\Api\AuthorisationInterface;
use Molajo\Cache\Api\CacheInterface;

/**
 * Read Model
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class ReadModel extends Model implements ReadModelInterface
{
    /**
     * Constructor
     *
     * @param  string                          $model_registry
     * @param  DatabaseInterface               $database
     * @param  string                          $null_date
     * @param  string                          $current_date
     * @param                                  $query
     * @param  AuthorisationInterface          $authorisation
     * @param  FieldHandlerInterface           $fieldhandler
     * @param  CacheInterface                  $cache
     * @param  null|int                        $site_id
     * @param  null|int                        $application_id
     *
     * @since  1.0
     */
    public function __construct(
        $model_registry,
        DatabaseInterface $database,
        $null_date,
        $current_date,
        $query,
        AuthorisationInterface $authorisation,
        FieldHandlerInterface $fieldhandler,
        CacheInterface $cache = null,
        $site_id = null,
        $application_id = null
    ) {
        parent::__construct(
            $model_registry,
            $database,
            $null_date,
            $current_date,
            $query,
            $authorisation,
            $fieldhandler,
            $cache,
            $site_id,
            $application_id
        );
    }

    /**
     * Based on Model Registry, set default SELECT, FROM and WHERE clauses for query
     *
     * @return $this
     * @since  1.0
     * @throws ReadModelException
     */
    public function setBaseQuery()
    {
        $columns        = $this->model_registry['fields'];
        $table_name     = $this->model_registry['table_name'];
        $primary_prefix = $this->model_registry['primary_prefix'];
        $primary_key    = $this->model_registry['primary_key'];
        $id             = $this->model_registry['primary_key_value'];
        $name_key       = $this->model_registry['name_key'];
        $name_key_value = $this->model_registry['name_key_value'];
        $query_object   = $this->model_registry['query_object'];
        $criteria_array = $this->model_registry['model_registry_name'];

        if ($this->query->select == null) {

            if ($query_object == 'result') {

                if ((int)$id > 0) {

                    $this->query->select(
                        ' ' . $this->database->qn($primary_prefix . '.' . $name_key . ' ')
                    );

                    $this->query->where(
                        $this->database->qn($primary_prefix . '.' . $primary_key)
                        . ' = ' . $this->database->q($id)
                    );
                } else {

                    $this->query->select($this->database->qn($primary_prefix . '.' . $primary_key));

                    $this->query->where(
                        $this->database->qn($primary_prefix . '.' . $name_key)
                        . ' = ' . $this->database->q($name_key_value)
                    );
                }
            } else {

                $first = true;

                if (count($columns) == 0) {

                    $this->query->select($this->database->qn($primary_prefix) . '.' . '*');
                } else {
                    foreach ($columns as $column) {

                        if ($first === true && strtolower(trim($query_object)) == 'distinct') {

                            $first = false;
                            $this->query->select(
                                'DISTINCT ' . $this->database->qn($primary_prefix . '.' . $column['name'])
                            );
                        } else {
                            $this->query->select(
                                $this->database->qn($primary_prefix . '.' . $column['name'])
                            );
                        }
                    }
                }
            }
        }

        if ($this->query->from == null) {
            $this->query->from(
                $this->database->qn($table_name)
                . ' as '
                . $this->database->qn($primary_prefix)
            );
        }

        if ($this->query->where == null) {
            if ((int)$id > 0) {
                $this->query->where(
                    $this->database->qn($primary_prefix . '.' . $primary_key)
                    . ' = ' . $this->database->q($id)
                );
            } elseif (trim($name_key_value) == '') {
            } else {
                $this->query->where(
                    $this->database->qn($primary_prefix . '.' . $name_key)
                    . ' = ' . $this->database->q($name_key_value)
                );
            }
        }

        if (is_array($criteria_array) && count($criteria_array) > 0) {

            foreach ($criteria_array as $item) {
                if (isset($item['value'])) {
                    $this->query->where(
                        $this->database->qn($item['name'])
                        . ' ' . $item['connector'] . ' '
                        . $this->database->q($item['value'])
                    );
                } elseif (isset($item['name2'])) {
                    $this->query->where(
                        $this->database->qn($item['name'])
                        . ' ' . $item['connector'] . ' '
                        . $this->database->qn($item['name2'])
                    );
                }
            }
        }

        return $this;
    }

    /**
     * Add View Permission Verification to the Query
     *
     * Note: When Language query runs, Permissions Service is not yet available.
     *
     * @return $this
     * @since  1.0
     */
    public function checkPermissions()
    {
        if ((int)$this->getModelRegistry('check_view_level_access') === 0) {
            return $this;
        }

        $this->authorisation
            ->setQueryAuthorisation(
                $this->query,
                $this->database,
                $this->model_registry
            );

        return $this;
    }

    /**
     * Uses joins defined in model registry to build SQL statements
     *
     * @return  $this
     * @since   1.0
     * @throws  ReadModelException
     */
    public function useSpecialJoins()
    {
        if ((int)$this->model_registry['use_special_joins'] === 0) {
            return $this;
        }

        if (count($this->model_registry['joins']) === 0) {
            return $this;
        }

        $joins           = $this->model_registry['joins'];
        $primary_prefix  = $this->model_registry['primary_prefix'];
        $query_object    = $this->model_registry['query_object'];
        $menu_id         = $this->model_registry['menu_id'];
        $catalog_type_id = $this->model_registry['catalog_type_id'];

        foreach ($joins as $join) {

            $join_table = $join['table_name'];
            $alias      = $join['alias'];
            $select     = $join['select'];
            $join_to    = $join['jointo'];
            $join_with  = $join['joinwith'];

            $this->query->from(
                $this->database->qn($join_table)
                . ' as '
                . $this->database->qn($alias)
            );

            /* Select fields */
            if (trim($select) == '') {
                $select_array = array();
            } else {
                $select_array = explode(',', $select);
            }

            if ($query_object == 'result') {
            } else {

                if (count($select_array) > 0) {

                    foreach ($select_array as $select_item) {

                        $this->query->select(
                            $this->database->qn(trim($alias) . '.' . trim($select_item))
                            . ' as ' .
                            $this->database->qn(trim($alias) . '_' . trim($select_item))
                        );
                    }
                }
            }

            /* Join Fields */
            $join_to_array   = explode(',', $join_to);
            $join_with_array = explode(',', $join_with);
            $where_left      = null;
            $where_right     = null;

            if (count($join_to_array) > 0) {

                $i = 0;
                foreach ($join_to_array as $join_to_item) {

                    /** join THIS to that */
                    $to = $join_to_item;

                    if ($to == 'APPLICATION_ID') {

                        if ((int)$this->application_id === 0) {
                            $where_left = null;
                            $to         = null;
                        } else {
                            $where_left = $this->application_id;
                        }
                    } elseif ($to == 'SITE_ID') {
                        $where_left = $this->site_id;
                    } elseif ($to == 'MENU_ID') {
                        $where_left = (int)$menu_id;
                    } elseif ($to == 'CATALOG_TYPE_ID') {
                        $where_left = (int)$catalog_type_id;
                    } elseif (is_numeric($to)) {
                        $where_left = (int)$to;
                    } else {

                        $has_alias = explode('.', $to);

                        if (count($has_alias) > 1) {
                            $to_join = trim($has_alias[0]) . '.' . trim($has_alias[1]);
                        } else {
                            $to_join = trim($alias) . '.' . trim($to);
                        }

                        $where_left = $this->database->qn($to_join);
                    }

                    /** join this to THAT */
                    $with = $join_with_array[$i];

                    $operator = '=';
                    if (substr($with, 0, 2) == '>=') {
                        $operator = '>=';
                        $with     = substr($with, 2, strlen($with) - 2);
                    } elseif (substr($with, 0, 1) == '>') {
                        $operator = '>';
                        $with     = substr($with, 0, strlen($with) - 1);
                    } elseif (substr($with, 0, 2) == '<=') {
                        $operator = '<=';
                        $with     = substr($with, 2, strlen($with) - 2);
                    } elseif (substr($with, 0, 1) == '<') {
                        $operator = '<';
                        $with     = substr($with, 0, strlen($with) - 1);
                    }

                    if ($with == 'APPLICATION_ID') {

                        if ((int)$this->application_id === 0) {
                            $where_right = null;
                            $to          = null;
                        } else {
                            $where_right = $this->application_id;
                        }
                    } elseif ($with == 'SITE_ID') {
                        $where_right = $this->site_id;
                    } elseif ($with == 'MENU_ID') {
                        $where_right = (int)$menu_id;
                    } elseif ($with == 'CATALOG_TYPE_ID') {
                        $where_right = (int)$catalog_type_id;
                    } elseif (is_numeric($with)) {
                        $where_right = (int)$with;
                    } else {

                        $has_alias = explode('.', $with);

                        if (count($has_alias) > 1) {
                            $with_join = trim($has_alias[0]) . '.' . trim($has_alias[1]);
                        } else {
                            $with_join = trim($primary_prefix) . '.' . trim($with);
                        }

                        $where_right = $this->database->qn($with_join);
                    }

                    /** put the where together */
                    if ($where_left === null || $where_right === null) {
                    } else {
                        $this->query->where($where_left . $operator . $where_right);
                    }

                    $i ++;
                }
            }
        }

        return $this;
    }

    /**
     * Add Model Registry Criteria to Query
     *
     * @param   object $parameters
     *
     * @return  $this
     * @since   1.0
     */
    public function setModelCriteria($parameters)
    {
        $primary_prefix = $this->model_registry['primary_prefix'];

        if (isset($parameters->criteria_catalog_type_id)) {
            if ((int)$parameters->criteria_catalog_type_id === 0) {
            } else {
                $this->query->where(
                    $this->database->qn($primary_prefix . '.' . 'catalog_type_id')
                    . ' = ' . (int)$parameters->criteria_catalog_type_id
                );
            }
        }

        if (isset($parameters->criteria_extension_instance_id)) {
            if ((int)$parameters->criteria_catalog_type_id === 0) {
            } else {
                $this->query->where(
                    $this->database->qn($primary_prefix . '.' . 'extension_instance_id')
                    . ' = ' . (int)$parameters->criteria_extension_instance_id
                );
            }
        }

        if (isset($parameters->criteria_menu_id)) {
            if ((int)$parameters->criteria_catalog_type_id === 0) {
            } else {
                $this->query->where(
                    $this->database->qn($primary_prefix . '.' . 'menu_id')
                    . ' = ' . (int)$parameters->menu_id
                );
            }
        }

        if (isset($parameters->criteria_status)) {
            if ((int)$parameters->criteria_status === null) {
            } else {
                $this->query->where(
                    $this->database->qn($primary_prefix . '.' . 'status')
                    . ' IN (' . (string)$parameters->criteria_status . ')'
                );
            }
        }

        return $this;
    }

    /**
     * getQueryResults - Execute query and returns an associative array of data elements
     *
     * @return  int     count of total rows for pagination
     * @since   1.0
     */
    public function getQueryResults()
    {
        $query_object   = $this->model_registry['query_object'];
        $offset         = $this->model_registry['model_offset'];
        $count          = $this->model_registry['model_count'];
        $use_pagination = $this->model_registry['use_pagination'];

        $this->query_results = array();

        if ($query_object == 'result'
            || $query_object == 'item'
            || $query_object == 'list'
            || $query_object == 'distinct'
        ) {
        } else {
            $query_object = 'list';
        }

        if ($offset == 0 && $count == 0) {

            if ($query_object == 'result') {
                $offset         = 0;
                $count          = 1;
                $use_pagination = 0;
            } elseif ($query_object == 'distinct') {
                $offset         = 0;
                $count          = 999999;
                $use_pagination = 0;
            } else {
                $offset         = 0;
                $count          = 15;
                $use_pagination = 1;
            }
        }

        /**
         * echo '<br /><br />';
         * echo $this->query->__toString();
         * echo '<br /><br />';
         */

        if (is_object($this->cache)) {
            $cache_key = $this->query->__toString();
            $results   = $this->cache->get(serialize($cache_key));
            if ($results->isHit() === true) {
                $cached_output = $results->value;
            } else {
                $cached_output = false;
            }
        } else {
            $cached_output = false;
        }

        if ($query_object == 'list') {
        } else {
            $use_pagination = 0;
        }

        if ($cached_output === false) {

            if ((int)$use_pagination === 0) {
                $query_offset = $offset;
                $query_count  = $count;
            } else {
                $query_offset = 0;
                $query_count  = 99999999;
            }

            if ($query_object == 'result') {
                $query_results = $this->database->loadResult();
            } else {
                $query_results = $this->database->loadObjectList($query_offset, $query_count);
            }

            if (is_object($this->cache)) {
                $this->cache->set('Query', $cache_key, $query_results);
            }
        } else {

            $query_results = $cached_output;
        }

        $total = count($query_results);

        if ($offset > $total) {
            $offset = 0;
        }

        if ($use_pagination === 0
            || (int)$total === 0
        ) {
            $this->query_results = $query_results;

            return $total;
        }

        $offset_count  = 0;
        $results_count = 0;

        foreach ($results as $item) {

            /** Read past offset */
            if ($offset_count < $offset) {
                $offset_count ++;
                /** Collect next set for pagination */
            } elseif ($results_count < $count) {
                $this->query_results[] = $item;
                $results_count ++;
                /** Offset and Results set collected. Exit. */
            } else {
                break;
            }
        }

        $this->query_results = $query_results;

        return $results_count;
    }
}
