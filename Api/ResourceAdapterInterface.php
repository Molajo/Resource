<?php
/**
 * Resource Adapter Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resources\Api;

interface ResourceAdapterInterface
    extends ClassHandlerInterface,
    ResourceHandlerInterface,
    ResourceLocatorInterface,
    ResourceMapInterface,
    ResourceNamespaceInterface,
    SchemeInterface
{
}
