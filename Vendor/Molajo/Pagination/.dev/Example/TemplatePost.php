<?php
/**
 * Example Post Template
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
?>
<article class="post">
    <header>
        <h2><?php echo $item->title; ?></h2>

        <p>Posted on <?php echo $item->postdate; ?> by <a href="#"><?php echo $item->author; ?></a></p>
    </header>
    <p><?php echo $item->article; ?></p>
</article>
