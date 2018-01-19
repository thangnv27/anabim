<?php
/* ----------------------------------------------------------------------------------- */
# Create post_type
/* ----------------------------------------------------------------------------------- */
add_action('init', 'create_photo_post_type');

function create_photo_post_type(){
    register_post_type('photo', array(
        'labels' => array(
            'name' => __('Hình ảnh'),
            'singular_name' => __('Hình ảnh'),
            'add_new' => __('Thêm mới'),
            'add_new_item' => __('Add new Photo'),
            'new_item' => __('New Photo'),
            'edit' => __('Edit'),
            'edit_item' => __('Edit Photo'),
            'view' => __('View Photo'),
            'view_item' => __('View Photo'),
            'search_items' => __('Search Photos'),
            'not_found' => __('No Photo found'),
            'not_found_in_trash' => __('No Photo found in trash'),
        ),
        'public' => true,
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
        'rewrite' => array('slug' => 'hinh-anh', 'with_front' => false),
        'can_export' => true,
        'description' => __('Photo description here.'),
        //'taxonomies' => array('post_tag'),
    ));
}

/* ----------------------------------------------------------------------------------- */
# Create taxonomy
/* ----------------------------------------------------------------------------------- */
add_action('init', 'create_photo_taxonomies');

function create_photo_taxonomies(){
    register_taxonomy('photo_category', 'photo', array(
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'query_var' => true,
        'labels' => array(
            'name' => __('Chuyên mục'),
            'singular_name' => __('Chuyên mục'),
            'add_new' => __('Thêm mới'),
            'add_new_item' => __('Add New Category'),
            'new_item' => __('New Category'),
            'search_items' => __('Search Categories'),
        ),
        'rewrite' => array('slug' => 'chuyen-muc-anh', 'with_front' => false),
    ));
}

/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://metabox.io/docs/registering-meta-boxes/
 */
add_filter('rwmb_meta_boxes', 'photo_register_meta_boxes');

/**
 * Register meta boxes
 *
 * Remember to change "your_prefix" to actual prefix in your photo
 *
 * @param array $meta_boxes List of meta boxes
 *
 * @return array
 */
function photo_register_meta_boxes($meta_boxes) {
    /**
     * prefix of meta keys (optional)
     * Use underscore (_) at the beginning to make keys hidden
     * Alt.: You also can make prefix empty to disable it
     */
    // Better has an underscore as last sign
    $prefix = 'photo_';

    // 1st meta box
    $meta_boxes[] = array(
        'id' => 'standard',
        'title' => "Information",
        'post_types' => array('photo'),
        'context' => 'normal',
        'priority' => 'high',
        'autosave' => true,
        'fields' => array(
            array(
                'id' => "{$prefix}featured_image",
                'name' => "Ảnh đại diện (1600x460 px)",
                'type' => 'image_advanced',
                // Delete image from Media Library when remove it from post meta?
                // Note: it might affect other posts if you use same image for multiple posts
                'force_delete' => false,
                // Maximum image uploads
                'max_file_uploads' => 1,
            ),
            array(
                'id' => "{$prefix}images",
                'name' => "Ảnh albums (1600x460 px)",
                'type' => 'image_advanced',
                // Delete image from Media Library when remove it from post meta?
                // Note: it might affect other posts if you use same image for multiple posts
                'force_delete' => false,
                // Maximum image uploads
//                'max_file_uploads' => 2,
            )
        )
    );

    return $meta_boxes;
}
