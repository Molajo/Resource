<?php

/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 *
 * <include:message wrap=Modal/>
 */

///Services::Message()->set('Test message for information', 'Information');
//Services::Message()->set('Test message for success', 'Success');
//Services::Message()->set('Test message for warning', 'Warning');
//Services::Message()->set('Test message for error', 'Error');
?>
<include:head/>
<a id="top"></a>
<include:template name=Navbar/>
    <include:template name=Header/>
        <div id="wrapper" class="row">
            <div id="main" class="twelve columns">
                <include:page name=<?php echo $this->row->page_name; ?>/>
            </div>
        </div>
        <include:template name=Footer/>
            <include:profiler/>
            <include:defer/>
