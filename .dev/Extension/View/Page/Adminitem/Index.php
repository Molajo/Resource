<?php

/**
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
?>
<include article wrap=article role=main/>
<?php if ((int)$this->registry->get('parameters', 'enable_response_comments') == 1) { ?>
    <include Author wrap=aside wrap_class=author-profile/>
        <section>
            <include Comment/>
            <include Comments/>
            <include Commentform/>
        </section>
<?php }
