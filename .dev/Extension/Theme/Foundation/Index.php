<?php

/**
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
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
    <include Navbar/>
    <include Header/>
    <div id="wrapper" class="row">
        <div id="main" class="twelve columns">
            <include page=<?php echo $row->page_name; ?>/>
        </div>
    </div>
    <include Footer/>
    <include defer/>
