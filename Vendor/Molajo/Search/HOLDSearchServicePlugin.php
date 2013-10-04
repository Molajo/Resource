<?php
/**
 * Search Service Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Search;

use Molajo\IoC\Handler\AbstractInjector;


/**
 * Search Service Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class SearchServicePlugin extends AbstractInjector implements ServiceHandlerInterface
{
    /**
     * on Before Startup Event
     *
     * Follows instantiation of the service class and before the method identified as the "start" method
     *
     * @return void
     * @since   1.0
     */
    public function processFulfilledDependencies()
    {

    }

    /**
     * On After Startup Event
     *
     * Follows the completion of the start method defined in the configuration
     *
     * @return void
     * @since   1.0
     */
    public function performAfterInstantiationLogic()
    {

    }
}
