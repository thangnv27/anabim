<?php get_header(); ?>

<section id="main">
    <div class="container-fluid">
        <div class="row">
            <div id="main-content" class="col-sm-9">
                <?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumbs">','</div>'); } ?>

                <div id="content" class="site-content" role="main">
                    <?php
                    // Start the Loop.
                    while (have_posts()) : the_post();

                        /*
                         * Include the post format-specific template for the content. If you want to
                         * use this in a child theme, then include a file called called content-___.php
                         * (where ___ is the post format) and that will be used instead.
                         */
                        get_template_part('content', 'staff');
                        
                    endwhile;
                    ?>
                </div><!-- #content -->
            </div><!-- #main-content -->

            <?php get_sidebar(); ?>
        </div>
    </div>
</section><!-- #main -->

<?php get_footer(); ?>