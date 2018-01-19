<?php
/* ----------------------------------------------------------------------------------- */
# Create post_type
/* ----------------------------------------------------------------------------------- */
add_action('init', 'create_project_post_type');

function create_project_post_type(){
    register_post_type('project', array(
        'labels' => array(
            'name' => __('Dự án'),
            'singular_name' => __('Dự án'),
            'add_new' => __('Thêm mới'),
            'add_new_item' => __('Add new Project'),
            'new_item' => __('New Project'),
            'edit' => __('Edit'),
            'edit_item' => __('Edit Project'),
            'view' => __('View Project'),
            'view_item' => __('View Project'),
            'search_items' => __('Search Projects'),
            'not_found' => __('No Project found'),
            'not_found_in_trash' => __('No Project found in trash'),
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
        'rewrite' => array('slug' => 'du-an', 'with_front' => false),
        'can_export' => true,
        'description' => __('Project description here.'),
        //'taxonomies' => array('post_tag'),
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
add_filter('rwmb_meta_boxes', 'project_register_meta_boxes');

/**
 * Register meta boxes
 *
 * Remember to change "your_prefix" to actual prefix in your project
 *
 * @param array $meta_boxes List of meta boxes
 *
 * @return array
 */
function project_register_meta_boxes($meta_boxes) {
    /**
     * prefix of meta keys (optional)
     * Use underscore (_) at the beginning to make keys hidden
     * Alt.: You also can make prefix empty to disable it
     */
    // Better has an underscore as last sign
    $prefix = 'project_';

    // 1st meta box
    $meta_boxes[] = array(
        'id' => 'standard',
        'title' => "Information",
        'post_types' => array('project'),
        'context' => 'normal',
        'priority' => 'high',
        'autosave' => true,
        'fields' => array(
            array(
                'id' => "{$prefix}images",
                'name' => "Images",
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
