<?php
/**
 * Render Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Service\Render;

use stdClass;
use Exception;
use Molajo\IoC\Handler\AbstractInjector;
use Molajo\IoC\Api\ServiceHandlerInterface;
use Molajo\IoC\Exception\ServiceHandlerException;

/**
 * Render Service Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class RenderInjector extends AbstractInjector implements ServiceHandlerInterface
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
        $options['service_namespace'] = null;

        parent::__construct($options);
    }

    /**
     * Define Dependencies for the Service
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\IoC\Exception\ServiceHandlerException
     */
    public function setDependencies(array $reflection = null)
    {
        parent::setDependencies($reflection);

        $this->dependencies['Resources']  = array();
        $this->dependencies['Parameters'] = array();
        $this->dependencies['Renderingextensions'] = array();

        return $this->dependencies;
    }

    /**
     * Instantiate Class
     *
     * @return  $this
     * @since   1.0
     * @throws  ServiceHandlerException
     */
    public function instantiateService()
    {
        $handler = $this->getAdapterHandler();

        $class = 'Molajo\\Render\\Adapter';

        try {
            $this->service_instance = new $class(
                $handler
            );
        } catch (Exception $e) {
            throw new ServiceHandlerException
            ('Render: Could not instantiate Handler: ' . $class);
        }

        return $this;
    }

    /**
     * Get Filesystem Adapter, inject with specific Filesystem Adapter Handler
     *
     * @param   object $handler
     *
     * @return  object
     * @since   1.0
     * @throws  ServiceHandlerInterface
     */
    protected function getAdapterHandler()
    {
        $class = 'Molajo\\Render\\Handler\\Molajito';

        try {

            return new $class(
                $this->dependencies['Resources']->get('xml:///Molajo//Application//Parse_sequence.xml'),
                $this->dependencies['Resources']->get('xml:///Molajo//Application//Parse_final.xml'),
                $this->dependencies['Resources'],
                $this->dependencies['Parameters'],
                $this->dependencies['Renderingextensions']
            );

        } catch (Exception $e) {
            throw new ServiceHandlerException
            ('Render: Could not instantiate Handler: ' . $class);
        }
    }
}
