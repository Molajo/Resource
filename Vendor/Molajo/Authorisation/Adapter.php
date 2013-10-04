<?php
/**
 * Adapter for Authorisation
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Authorisation;

use Molajo\Authorisation\Api\AuthorisationInterface;

/**
 * Adapter for Authorisation
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class Adapter implements AuthorisationInterface
{
    /**
     * Authorisation Handler
     *
     * @var     object  Molajo\Authorisation\Api\AuthorisationInterface
     * @since   1.0
     */
    protected $authorisation;

    /**
     * Constructor
     *
     * @param AuthorisationInterface $authorisation
     * @param                        $parameters
     *
     * @since   1.0
     */
    public function __construct(
        AuthorisationInterface $authorisation
    ) {
        $this->authorisation = $authorisation;
    }

    /**
     * Retrieve Action List
     *
     * Example usage:
     *  $authorisation = $this->authorisation->getActions();
     *
     * @param   string $type (id or null)
     *
     * @return  array|$this
     * @since   1.0
     * @throws  \Molajo\Authorisation\Exception\AuthorisationException
     */
    public function getActions($type = null)
    {
        return $this->authorisation->getActions($type);
    }

    /**
     * Using the Request Task (Verb Action, like Tag, or Order Up), retrieve the Authorisation Action (ex. Update)
     *
     * Example usage:
     *  $results = $this->authorisation->getTaskAction($task);
     *
     * @param   string $task
     *
     * @return  string
     * @since   1.0
     * @throws  \Molajo\Authorisation\Exception\AuthorisationException
     */
    public function getTaskAction($task = null)
    {
        return $this->authorisation->getTaskAction($task);
    }

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
    public function getTaskController($action = null)
    {
        return $this->authorisation->getTaskAction($action);
    }

    /**
     * Check if Application has been set to "offline" and, if so, verify if user has offline access
     *
     * @return  bool
     * @throws  \Molajo\Authorisation\Exception\AuthorisationException
     * @since   1.0
     */
    public function isUserAuthorisedOfflineMode()
    {
        return $this->authorisation->isUserAuthorisedOfflineMode();
    }

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
    public function isUserAuthorised($action_id = null, $resource_id = null, $group_id = null, $type = 'Catalog')
    {
        return $this->authorisation->isUserAuthorised($action_id, $resource_id, $group_id, $type);
    }

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
    ) {
        return $this->authorisation->setQueryAuthorisation(
            $query,
            $db,
            $model_registry
        );
    }
}
