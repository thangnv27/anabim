<?php
/**
 * The template for displaying Search Results pages
 */
get_header();
?>

<section id="main" class="content-area">
        <div class="container-fluid">
            <div class="row">
                <div id="main-content" class="col-sm-9">
                    <?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumbs">','</div>'); } ?>
                    
                    <div id="content" class="site-content" role="main">

                    <?php if (have_posts()) : ?>

                        <header>
                            <h1 class="page-title mt0 mb20"><?php printf(__('Kết quả cho: %s', SHORT_NAME), get_search_query()); ?></h1>
                        </header><!-- .page-header -->

                        <?php
                        // Start the Loop.
                        while (have_posts()) : the_post();

                            /*
                             * Include the post format-specific template for the content. If you want to
                             * use this in a child theme, then include a file called called content-___.php
                             * (where ___ is the post format) and that will be used instead.
                             */
                            get_template_part('content', get_post_format());

                        endwhile;

                        // Previous/next post navigation.
                        getpagenavi();

                    else :
                        // If no content, include the "No posts found" template.
                        get_template_part('content', 'none');

                    endif;
                    ?>
                </div><!-- #content -->
            </div><!-- #main-content -->
                
            <?php get_sidebar(); ?>
        </div>
    </div>
</section><!-- #main -->

<?php get_footer(); ?>