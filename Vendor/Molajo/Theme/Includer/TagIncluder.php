<?php
/**
 * @package   Tag Includer
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Theme\Includer;

use Molajo\Theme\Api\IncluderInterface;

/**
 * Tag Includer
 *
 * @package     Molajo
 * @subpackage  Includer
 * @since       1.0
 */
class TagIncluder extends AbstractIncluder implements IncluderInterface
{
    /**
     * process
     *
     * @return mixed
     * @since   1.0
     */
    public function __construct($include_name = null, $include_type = null)
    {
        return 'still need to do Tag Includer';
    }

    public function render($tag)
    {

        foreach (x::y($tag) as $item) {
            $buffer = $includer->etc($thing);
        }

        return $buffer;
    }
}
