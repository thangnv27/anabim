<?php
/**
 * The template for displaying all pages
 */
get_header();
?>

<section id="main">
    <div class="container-fluid">
        <?php if(is_bbpress()): ?>
            <div id="main-content">
                <?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumbs">','</div>'); } ?>
                
                <div id="content" class="site-content" role="main">
                    <?php
                    // Start the Loop.
                    while (have_posts()) : the_post();

                        // Include the page content template.
                        get_template_part('content', 'page');
                    endwhile;
                    ?>
                </div><!-- #content -->
            </div><!-- #main-content -->
        <?php else: ?>
        <div class="row">
            <div id="main-content" class="col-sm-9">
                <?php
                if (is_front_page() && ppo_has_featured_posts()) {
                    // Include the featured content template.
                    get_template_part('featured-content');
                } else {
                    if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumbs">','</div>'); }
                }
                ?>
                <div id="content" class="site-content" role="main">
                    <?php
                    // Start the Loop.
                    while (have_posts()) : the_post();

                        // Include the page content template.
                        get_template_part('content', 'page');

                        // If comments are open or we have at least one comment, load up the comment template.
                        if (comments_open() || get_comments_number()) {
                            comments_template();
                        }
                    endwhile;
                    ?>
                </div><!-- #content -->
            </div><!-- #main-content -->

            <?php get_sidebar(); ?>
        </div>
        <?php endif; ?>
    </div>
</section><!-- #main -->

<?php
get_footer();
