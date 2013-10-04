<?php
/**
 * Date Service Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Date;

use Molajo\IoC\Handler\AbstractInjector;


/**
 * Date Service Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class UtilitiesServicePlugin extends AbstractInjector implements ServiceHandlerInterface
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
        $this->service_instance->set(
            'locale',
            $this->language->translate('language')
        );

        $this->service_instance->set(
            'date_translate_array',
            $this->language->translate('date_', 1)
        );

//        $this->service_instance->set(
//            'offset_user',
//            Services::User()->get('parameters_timezone', '')
//        );

        $this->service_instance->set(
            'offset_server',
            $this->application->get('language_utc_offset', 'UTC')
        );

        return;
    }
}
