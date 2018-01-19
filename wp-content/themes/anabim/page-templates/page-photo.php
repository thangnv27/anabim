<?php
/*
  Template Name: Photos
 */

get_header();

?>

<section id="project_main">
    <div class="container-fluid pdt20">
        <?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumbs">','</div>'); } ?>
        
        <h1 class="page-title mt0"><?php _e('THƯ VIỆN HÌNH ẢNH', SHORT_NAME) ?></h1>
        <?php
        $taxonomy = 'photo_category';
        $terms = get_terms($taxonomy, array(
            'hide_empty' => 0,
        ));
        foreach ($terms as $term) :
        ?>
        <h2><?php echo $term->name; ?></h2>
        <div class="projects">
            <?php
            $loop = new WP_Query(array(
                'post_type' => 'photo',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'id',
                        'terms' => $term->term_id,
                    ),
                ),
            ));
            while ($loop->have_posts()) : $loop->the_post();
                $link = get_permalink();
            ?>
            <div class="item">
                <a href="<?php echo $link; ?>">
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
        <?php endforeach; ?>
        
        <?php // getpagenavi(array('query' => $loop)); ?>
    </div>
</section>

<?php get_footer(); ?>