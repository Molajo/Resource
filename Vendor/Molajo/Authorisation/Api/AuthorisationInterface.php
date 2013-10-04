<?php
/**
 * Authorisation Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Authorisation\Api;

use Molajo\Authorisation\Exception\AuthorisationException;

/**
 * Authorisation Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface AuthorisationInterface
{
    /**
     * Retrieve list of Authorisation Actions
     *
     * Example usage:
     *  $authorisation = $this->authorisation->getActions();
     *
     * @param   int|null $type
     *
     * @return  array|string|null
     * @since   1.0
     */
    public function getActions($type = null);

    /**
     * Use Task (Verb Action, like Tag, or Order Up) from Request to Action (ex. Update)
     *
     * Example usage:
     *  $results = $this->authorisation->getTaskAction($task);
     *
     * @return  array|string|null
     *
     * @return  string
     * @since   1.0
     * @throws  \Molajo\Authorisation\Exception\AuthorisationException
     */
    public function getTaskAction($task = null);

    /**
     * Use Task (Verb Action, like Tag, or Order Up) from Request to retrieve Controller (ex. ReadController)
     *
     * Example usage:
     *  $results = $this->authorisation->getTaskController($action);
     *
     * @return  array|string|null
     *
     * @return  string
     * @since   1.0
     * @throws  \Molajo\Authorisation\Exception\AuthorisationException
     */
    public function getTaskController($action = null);

    /**
     * Check if Application has been set to "offline" and, if so, verify if user has offline access
     *
     * @return  bool
     * @throws  \Molajo\Authorisation\Exception\AuthorisationException
     * @since   1.0
     */
    public function isUserAuthorisedOfflineMode();

    /**
     * Verify User Authorisation to take Action on Resource
     *
     * @param   int    $action_id
     * @param   int    $resource_id
     * @param   int    $group_id
     * @param   string $type
     *
     * @return  bool
     * @since   1.0
     * @throws  \Molajo\User\Exception\AuthorisationException
     */
    public function isUserAuthorised($action_id = null, $resource_id = null, $group_id = null, $type = 'Catalog');

    /**
     * Appends View Access criteria to Query when Model check_view_level_access is set to 1
     *
     * Example usage:
     *  $this->authorisation->setQueryAuthorisation(
     *     $this->query,
     *     $this->db,
     *     array('join_to_prefix' => $this->primary_prefix,
     *         'join_to_primary_key' => $this->registry->get($this->model_registry, 'primary_key'),
     *         'catalog_prefix' => $this->primary_prefix . '_catalog',
     *         'select' => true
     *     )
     * );
     *
     * @param   object $query
     * @param   object $db
     * @param   object $model_registry
     *
     * @return  array
     * @since   1.0
     */
    public function setQueryAuthorisation(
        $query,
        $db,
        $model_registry
    );
}
