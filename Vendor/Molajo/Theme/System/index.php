<?php
/**
 * System Theme
 *
 * Theme Index.php file that is included in the DisplayController, providing the source for
 * parsing for <include type=value/> statements.
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
?>
<include type=head/>
    <include type=page name=<?php echo $this->row->page_name; ?>/>
    <include type=defer/>
