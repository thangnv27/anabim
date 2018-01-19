<?php
/*
  Template Name: About
 */
get_header(); ?>

<section>
    <div class="bg_about" style="background-image: url(<?php _e(get_option(SHORT_NAME . "_bg_about")); ?>);"></div>
    
    <div class="container-fluid">
        <div id="content" class="site-content" role="main">
            <?php
            while (have_posts()) : the_post();

                // Include the page content template.
                get_template_part('content', 'page');

            endwhile;
            ?>
        </div><!-- #content -->
    </div>
</section>

<?php get_footer(); ?>