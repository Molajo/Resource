<?php
/**
 * Class Map Driver
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource;

use CommonApi\Resource\MapInterface;
use Molajo\Resource\ClassMap\Items;

/**
 * Class Map Driver
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class ClassMap extends Items implements MapInterface
{
    /**
     * Create interface to concrete references
     *
     * @return  $this
     * @since   1.0
     */
    public function createMap()
    {
        if (count($this->classmap_files) > 0) {
        } else {
            return array();
        }

        $this->processItems();
        $this->finalizeItems();
        $this->setEvents();
        $this->saveOutput();

        return $this;
    }

    /**
     * Save results of processing to files
     *
     * @return  $this
     * @since   1.0
     */
    protected function saveOutput()
    {
        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            file_put_contents($this->interface_classes_filename, json_encode($this->interfaces, JSON_PRETTY_PRINT));
            file_put_contents($this->concrete_classes_filename, json_encode($this->concretes, JSON_PRETTY_PRINT));
            file_put_contents($this->events_filename, json_encode($this->events, JSON_PRETTY_PRINT));

            return $this;
        }

        file_put_contents($this->interface_classes_filename, json_encode($this->interfaces));
        file_put_contents($this->concrete_classes_filename, json_encode($this->concretes));
        file_put_contents($this->events_filename, json_encode($this->events));

        return $this;
    }
}
