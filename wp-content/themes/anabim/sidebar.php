<?php
/**
 * The Sidebar containing the main widget area
 */

if ( ! is_active_sidebar( 'sidebar' ) ) {
    return;
}
?>
<div id="sidebar" class="col-sm-3 hidden-xs">
    <?php if ( is_active_sidebar( 'sidebar' ) ) { dynamic_sidebar( 'sidebar' ); } ?>
</div><!-- #sidebar -->