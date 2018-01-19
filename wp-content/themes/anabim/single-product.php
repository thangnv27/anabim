<?php
while (have_posts()) : the_post();
    $course_cat = get_post_meta(get_the_ID(), "course_cat", true);
    $link = get_category_link($course_cat);
    if(empty($course_cat)) $link = get_category_link (8);
    
    wp_redirect($link);
    exit;
endwhile;