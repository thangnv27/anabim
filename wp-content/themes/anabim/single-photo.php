<?php get_header(); ?>

<section>
    <?php while (have_posts()) : the_post(); ?>
    
    <div class="project-slider">
        <ul>
            <?php
            $args = array(
                'type' => 'image_advanced',
                //'size' => 'full_url'
            );
            $images = rwmb_meta( 'photo_images', $args );
            foreach ($images as $image) {
                echo <<<HTML
                <li><img src="{$image['url']}" /></li>
HTML;
            }
            ?>
        </ul>
    </div>
    
    <div class="container-fluid">
        <div id="content" class="site-content" role="main">
            <?php get_template_part('content', 'project'); ?>
        </div><!-- #content -->
    </div>
    <?php endwhile; ?>
</section><!-- #main -->

<?php get_footer(); ?>