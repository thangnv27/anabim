<?php
/*
  Template Name: Thành viên ANABIM
 */

get_header();

?>

<section id="staff_main">
    <div class="bg_about" style="background-image: url(<?php _e(get_option(SHORT_NAME . "_bg_staff")); ?>);"></div>
    
    <div class="container-fluid">
        <h1 class="page-title"><?php _e('THÀNH VIÊN ANABIM', SHORT_NAME) ?></h1>
        <div class="row staffs">
            <?php
            $loop = new WP_Query(array(
                'post_type' => 'staff',
                'posts_per_page' => -1,
                'meta_key' => 'sort_num',
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
            ));
            while ($loop->have_posts()) : $loop->the_post();
                $link = get_permalink();
            ?>
            <div class="col-sm-2">
                <div class="item">
                    <div class="thumbnail">
                        <a href="<?php echo $link; ?>" title="<?php the_title(); ?>">
                            <img alt="<?php echo $link; ?>" src="<?php get_image_url(); ?>" />
                        </a>
                    </div>
                    <a href="<?php echo $link; ?>" title="<?php the_title(); ?>" class="title">
                        <h3><?php the_title(); ?></h3>
                    </a>
                    <div class="regency"><?php echo get_post_meta(get_the_ID(), 'regency', true); ?></div>
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