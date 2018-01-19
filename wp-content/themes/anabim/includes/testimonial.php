<?php
/* ----------------------------------------------------------------------------------- */
# Create post_type
/* ----------------------------------------------------------------------------------- */
add_action('init', 'create_testimonial_post_type');

function create_testimonial_post_type(){
    register_post_type('testimonial', array(
        'labels' => array(
            'name' => __('Cảm nhận học viên'),
            'singular_name' => __('Cảm nhận học viên'),
            'add_new' => __('Thêm mới'),
            'add_new_item' => __('Add new Testimonial'),
            'new_item' => __('New Testimonial'),
            'edit' => __('Edit'),
            'edit_item' => __('Edit Testimonial'),
            'view' => __('View Testimonial'),
            'view_item' => __('View Testimonial'),
            'search_items' => __('Search Testimonials'),
            'not_found' => __('No Testimonial found'),
            'not_found_in_trash' => __('No Testimonial found in trash'),
        ),
        'public' => false,
        'show_ui' => true,
        'publicy_queryable' => true,
        'exclude_from_search' => false,
        'menu_position' => 20,
        'hierarchical' => false,
        'query_var' => true,
        'supports' => array(
            'title', 'editor', 'thumbnail',
            //'custom-fields', 'author', 'comments', 'excerpt', 
        ),
        'rewrite' => array('slug' => 'testimonial', 'with_front' => false),
        'can_export' => true,
        'description' => __('Testimonial description here.'),
        //'taxonomies' => array('post_tag'),
    ));
}