<?php
/*
  Template Name: Contact
 */
get_header(); ?>

<section>
    <div class="bg_about" style="background-image: url(<?php _e(get_option(SHORT_NAME . "_bg_contact")); ?>);"></div>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-7">
                <div class="post-content">
                    <?php 
                    while (have_posts()) : the_post(); 
                        the_content();
                    endwhile;
                    ?>
                </div>
            </div>
            <div class="col-sm-5"><?php echo stripslashes(get_option(SHORT_NAME . "_gmaps")); ?></div>
            <div class="clearfix"></div>
        </div>
    </div>
</section>

<?php get_footer(); ?>