<?php
/*
  Template Name: Projects
 */

get_header();

?>

<section id="project_main">
    <div class="bg_about" style="background-image: url(<?php echo get_option(SHORT_NAME . "_bg_project"); ?>);"></div>
    
    <div class="container-fluid">
        <h1 class="page-title"><?php _e('DỰ ÁN', SHORT_NAME) ?></h1>
        <div class="projects">
            <?php
            $loop = new WP_Query(array(
                'post_type' => 'project',
                'posts_per_page' => -1,
            ));
            while ($loop->have_posts()) : $loop->the_post();
                $link = get_permalink();
            ?>
            <div class="item">
                <a href="<?php echo $link; ?>" title="<?php the_title(); ?>">
                    <img alt="<?php echo $link; ?>" src="<?php get_image_url(); ?>" />
                </a>
                <div class="photo-caption">
                    <a href="<?php echo $link; ?>" title="<?php the_title(); ?>">
                        <span class="title"><?php the_title(); ?></span>
                        <span class="desc"><?php echo get_short_content(get_the_content(), 300); ?></span>
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
            <?php wp_reset_query(); ?>
            <div class="clearfix"></div>
        </div>
        
        <?php // getpagenavi(array('query' => $loop)); ?>
    </div>
</section>

<?php get_footer(); ?>