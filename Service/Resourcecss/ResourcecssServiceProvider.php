<?php
/**
 * Resourcecss Service Provider
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Service\Resourcecss;

use Exception;
use CommonApi\Exception\RuntimeException;
use CommonApi\IoC\ServiceProviderInterface;
use Molajo\IoC\AbstractServiceProvider;

/**
 * Resourcecss Service Provider
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class ResourcecssServiceProvider extends AbstractServiceProvider implements ServiceProviderInterface
{
    /**
     * Constructor
     *
     * @param  array $options
     *
     * @since  1.0
     */
    public function __construct(array $options = array())
    {
        $options['service_name']             = basename(__DIR__);
        $options['store_instance_indicator'] = true;
        $options['service_namespace']        = 'Molajo\\Resource\\Handler\\CssHandler';

        parent::__construct($options);
    }

    /**
     * Instantiate a new handler and inject it into the Adapter for the ServiceProviderInterface
     * Retrieve a list of Interface dependencies and return the data ot the controller.
     *
     * @return  array
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException;
     */
    public function setDependencies(array $reflection = null)
    {
        parent::setDependencies($reflection);

        $options                           = array();
        $this->dependencies['Resource']    = $options;
        $this->dependencies['Runtimedata'] = $options;

        return $this->dependencies;
    }

    /**
     * Set Dependencies for Instantiation
     *
     * @return  array
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException;
     */
    public function onBeforeInstantiation(array $dependency_values = null)
    {
        parent::onBeforeInstantiation($dependency_values);

        $this->dependencies['base_path']             = BASE_FOLDER;
        $this->dependencies['resource_map']          = $this->readFile(
            BASE_FOLDER . '/vendor/molajo/resource/Source/Files/Output/ResourceMap.json'
        );
        $this->options['Scheme']                     = $this->createScheme();
        $this->dependencies['namespace_prefixes']    = array();
        $scheme                                      = $this->options['Scheme']->getScheme('Css');
        $this->dependencies['valid_file_extensions'] = $scheme->include_file_extensions;

        $this->dependencies['language_direction']
            = $this->dependencies['Runtimedata']->application->parameters->language_direction;
        $this->dependencies['html5']
            = $this->dependencies['Runtimedata']->application->parameters->application_html5;
        $this->dependencies['line_end']
            = $this->dependencies['Runtimedata']->application->parameters->application_line_end;
        //todo: mime type

        return $this->dependencies;
    }

    /**
     * Follows the completion of the instantiate service method
     *
     * @return  $this
     * @since   1.0
     */
    public function onAfterInstantiation()
    {
        $this->dependencies['Resource']->setHandlerInstance('CssHandler', $this->service_instance);

        return $this;
    }

    /**
     * Create Scheme Instance
     *
     * @return  object
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException;
     */
    protected function createScheme()
    {
        $class = 'Molajo\\Resource\\Scheme';

        $input = BASE_FOLDER . '/vendor/molajo/resource/Source/Files/Input/SchemeArray.json';

        try {
            $scheme = new $class ($input);
        } catch (Exception $e) {
            throw new RuntimeException ('Resource Scheme ' . $class
            . ' Exception during Instantiation: ' . $e->getMessage());
        }

        return $scheme;
    }
}
