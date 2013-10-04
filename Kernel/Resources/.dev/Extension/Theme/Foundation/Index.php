<?php

/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 *
 * <include type=message wrap=Modal/>
 */

///Services::Message()->set('Test message for information', 'Information');
//Services::Message()->set('Test message for success', 'Success');
//Services::Message()->set('Test message for warning', 'Warning');
//Services::Message()->set('Test message for error', 'Error');
?>
<include type=head/>
    <a id="top"></a>
    <include type=template name=Navbar/>
        <include type=template name=Header/>
            <div id="wrapper" class="row">
                <div id="main" class="twelve columns">
                    <include type=page name=<?php echo $this->row->page_name; ?>/>
                </div>
            </div>
            <include type=template name=Footer/>
                <include type=profiler/>
                    <include type=defer/>
