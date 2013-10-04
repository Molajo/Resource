<?php
/**
 * Execute Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Controller\Api;

use Molajo\Controller\Exception\ExecuteException;

/**
 * Execute Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface ExecuteInterface
{
    /**
     * Process Step
     *
     * @return  object
     * @since   1.0
     * @throws  \Molajo\Controller\Exception\ExecuteException
     */
    public function processStep();
}
