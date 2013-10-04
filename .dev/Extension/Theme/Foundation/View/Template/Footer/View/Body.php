<?php

/**
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */

$pageURL = $this->registry->get('Page', 'page_url');
$homeURL = $this->registry->get('Page', 'home_url'); ?>
<footer id="pagefooter" class="contain-to-grid not-small-device" role="contentinfo">
    <div class="row">
        <div class="footer-links">
            <ul class="link-list">
                <li><a href="pages/about-us">About Us</a></li>
                <li><a href="pages/contact-us">Contact Us</a></li>
                <li><a href="pages/privacy-policy">Privacy Policy</a></li>
                <li><a href="pages/site-map">Site Map</a></li>
                <li><a href="<?php echo $pageURL; ?>#top">&uarr; Back to Top</a></li>
            </ul>
        </div>
        <div class="footer-copyright">
            <p><?php echo $this->row->copyright_statement ?> <a
                    href="<?php echo $this->row->link; ?>"><?php echo $this->row->linked_text; ?> </a><?php echo ' ' . $this->row->remaining_text; ?>
            </p>
        </div>
    </div>
</footer>
<footer id="footer-small" class="row small-device" role="contentinfo">
    <div class="twelve columns">
        <ul class="link-list">
            <li><a href="pages/about-us">About Us</a></li>
            <li><a href="pages/contact-us">Contact Us</a></li>
            <li><a href="pages/privacy-policy">Privacy Policy</a></li>
            <li><a href="pages/site-map">Site Map</a></li>
            <li><a href="<?php echo $pageURL; ?>#top">&uarr; Back to Top</a></li>
        </ul>
        <p class="footer-copyright-small">
            <?php echo $this->row->copyright_statement; ?> <a
                href="<?php echo $this->row->link; ?>"><?php echo $this->row->linked_text; ?> </a><?php echo ' ' . $this->row->remaining_text; ?>
        </p>
    </div>
</footer>
