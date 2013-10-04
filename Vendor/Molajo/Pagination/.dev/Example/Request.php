<?php
/**
 * Http Request Simulation - Builds URL and extracts Page parameter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */

if (isset($_REQUEST['page'])) {
    $page = (int)$_REQUEST['page'];
} else {
    $page = 1;
}

$current_url      = explode("?", $_SERVER['REQUEST_URI']);
$page_url         = $current_url[0];
$query_parameters = array();
