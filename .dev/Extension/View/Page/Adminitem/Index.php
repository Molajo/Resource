<?php

/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
?>
<include:template name=article wrap=article role=main/>
<?php if ((int)$this->registry->get('parameters', 'enable_response_comments') == 1) { ?>
    <include:template name=Author wrap=aside wrap_class=author-profile/>
        <section>
            <include:template name=Comment wrap=none/>
                <include:template name=Comments wrap=none/>
                    <include:template name=Commentform wrap=none/>
        </section>
<?php }
