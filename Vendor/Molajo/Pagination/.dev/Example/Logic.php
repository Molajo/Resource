<?php
/**
 * Pagination Example
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
namespace Molajo;

/** Initialize */
$page_url = ''; // Http Request
$query_parameters = array(); //
$page = 1; //
$data = array(); // Database Query
$total_items = 0; //
$per_page = 3; // Application settings
$display_links = 3; //

/** Autoload */
include_once __DIR__ . '/../' . 'Bootstrap.php';

/** Request: $page_url and $page */
include_once __DIR__ . '/' . 'Request.php';

/** Database: skips offset, includes $per_page # in $data, calculates $total_items */
include_once __DIR__ . '/' . 'Database.php';

/** Pagination: inject with dependencies */
use Molajo\Pagination\Adapter as Pagination;

$pagination = new Pagination(
    $data, $page_url, $query_parameters, $total_items, $per_page, $display_links, $page);
?>

<section class="posts">
    <?php
    /** Posts: can use (not required) Pagination as ArrayIterator */
    foreach ($pagination as $item) {
        include __DIR__ . '/' . 'TemplatePost.php';
    }
    ?>
</section>
<aside>
    <?php
    include __DIR__ . '/' . 'TemplateSidebar.php';
    ?>
</aside>
<?php
/**
 * Pagination URL
 *
 *  - Send in a Page Number, or a literal: first, prev, current, next, and last to get a URL
 *
 *  echo $pagination->getPageUrl('first');
 *  echo $pagination->getPageUrl(1);
 *
 */
?>
<footer class="pagination">
    <a href="<?php echo $pagination->getPageUrl('first'); ?>">First</a>
    &nbsp;<a href="<?php echo $pagination->getPageUrl('prev'); ?>">«</a>
    <?php
    /**
     * Pagination Display Links
     *
     *  Loops $display_links times
     *  From $pagination->getStartDisplayPage()
     *  To $pagination->getStopDisplayPage();
     */
    for ($i = $pagination->getStartDisplayPage(); $i < $pagination->getStopDisplayPage(); $i ++) {
        ?>
        <a href="<?php echo $pagination->getPageUrl($i); ?>"><?php echo $i; ?></a>
    <?php
    } ?>
    <a href="<?php echo $pagination->getPageUrl('next'); ?>">»</a>
    &nbsp;<a href="<?php echo $pagination->getPageUrl('last'); ?>">Last</a>
</footer>
