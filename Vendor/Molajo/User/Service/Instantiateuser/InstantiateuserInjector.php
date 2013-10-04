<?php
/**
 * User User Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\User\Service\Instantiateuser;

use Molajo\IoC\Handler\AbstractInjector;
use Molajo\IoC\Api\ServiceHandlerInterface;

/**
 * User User Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class InstantiateuserInjector extends AbstractInjector implements ServiceHandlerInterface
{
    /**
     * Constructor
     *
     * @param  $options
     *
     * @since  1.0
     */
    public function __construct(array $options = array())
    {
        $options['service_name']      = basename(__DIR__);
        $options['service_namespace'] = 'Molajo\\User\\User';

        parent::__construct($options);
    }

    /**
     * Following Class creation, DI Handler requests the IoC Controller set Services in the Container
     *
     * @return  string
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceItemException
     */
    public function setService()
    {
        $set         = array();
        $set['User'] = $this->service_instance;
        return $set;
    }

    /**
     * Schedule the Next Service
     *
     * @return  $this
     * @since   1.0
     */
    public function scheduleNextService()
    {
        $options = array();

        $options['User']                         = $this->service_instance;
        $this->schedule_service['Language']      = $options;
        $this->schedule_service['Authorisation'] = $options;

        return $this->schedule_service;
    }
}
