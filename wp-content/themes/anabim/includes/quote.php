<?php
/* ----------------------------------------------------------------------------------- */
# Create post_type
/* ----------------------------------------------------------------------------------- */
add_action('init', 'create_quote_post_type');

function create_quote_post_type(){
    register_post_type('quote', array(
        'labels' => array(
            'name' => __('Trích dẫn'),
            'singular_name' => __('Trích dẫn'),
            'add_new' => __('Thêm mới'),
            'add_new_item' => __('Add new Quote'),
            'new_item' => __('New Quote'),
            'edit' => __('Edit'),
            'edit_item' => __('Edit Quote'),
            'view' => __('View Quote'),
            'view_item' => __('View Quote'),
            'search_items' => __('Search Quotes'),
            'not_found' => __('No Quote found'),
            'not_found_in_trash' => __('No Quote found in trash'),
        ),
        'public' => false,
        'show_ui' => true,
        'publicy_queryable' => true,
        'exclude_from_search' => false,
        'menu_position' => 20,
        'hierarchical' => false,
        'query_var' => true,
        'supports' => array(
            'title', 'editor', 
            //'custom-fields', 'author', 'thumbnail', 'comments', 'excerpt', 
        ),
        'rewrite' => array('slug' => 'quote', 'with_front' => false),
        'can_export' => true,
        'description' => __('Quote description here.'),
        //'taxonomies' => array('post_tag'),
    ));
}