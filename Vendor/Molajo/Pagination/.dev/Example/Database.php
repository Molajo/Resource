<?php
/**
 * Database Simulation - reads posts, skipping offset and capturing only those to be presented
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */

/** Simulates the database read */
include_once __DIR__ . '/' . 'Posts.php';

/** Calculate total */
$total_items = count($posts);

/** Edit $page from Request */
$first_on_last_page = ($page * $per_page) - ($per_page + 1);
if ($first_on_last_page > $total_items) {
    $page = 1;
}

/** Edit $read_until to ensure not more than total rows */
$offset     = ($page - 1) * $per_page;
$read_until = ($per_page * $page) - $offset;
if ($read_until > $total_items) {
    $read_until = $total_items;
}

/** Capture $data array which will be displayed on page */
$i    = 0;
$data = array();

foreach ($posts as $item) {
    if ($i < $offset) {

    } else {
        if (count($data) < $read_until) {
            $item->row_number = $i + 1;
            $data[]           = $item;
        } else {
            break;
        }
    }
    $i ++;
}
