<?php
/**
 * Customfields Controller Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Controller\Api;

use Molajo\Controller\Exception\CustomfieldsException;

/**
 * Customfields Controller Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface CustomfieldsControllerInterface
{
    /**
     * Get Custom Fields and Data for each field
     *
     * @param   object      $model_registry
     * @param   null|object $data
     * @param   null|object $parameters
     * @param   null|string $page_type
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\CustomfieldsException
     */
    public function getCustomfields(
        $model_registry,
        $data = null,
        $parameters = null,
        $page_type = null
    );
}
