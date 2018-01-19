<?php
/* ----------------------------------------------------------------------------------- */
# Create post_type
/* ----------------------------------------------------------------------------------- */
add_action('init', 'create_staff_post_type');

function create_staff_post_type(){
    register_post_type('staff', array(
        'labels' => array(
            'name' => __('Thành viên ANABIM'),
            'singular_name' => __('Thành viên ANABIM'),
            'add_new' => __('Thêm mới'),
            'add_new_item' => __('Add new Staff'),
            'new_item' => __('New Staff'),
            'edit' => __('Edit'),
            'edit_item' => __('Edit Staff'),
            'view' => __('View Staff'),
            'view_item' => __('View Staff'),
            'search_items' => __('Search Staffs'),
            'not_found' => __('No Staff found'),
            'not_found_in_trash' => __('No Staff found in trash'),
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
        'rewrite' => array('slug' => 'staff', 'with_front' => false),
        'can_export' => true,
        'description' => __('Staff description here.'),
        //'taxonomies' => array('post_tag'),
    ));
}

/* ----------------------------------------------------------------------------------- */
# Meta box
/* ----------------------------------------------------------------------------------- */
$staff_meta_box = array(
    'id' => 'staff-meta-box',
    'title' => __('Staff Information', SHORT_NAME),
    'page' => 'staff',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => __('Regency', SHORT_NAME),
            'desc' => '',
            'id' => 'regency',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => __('Sort number', SHORT_NAME),
            'desc' => '',
            'id' => 'sort_num',
            'type' => 'text',
            'std' => '1',
        ),
));

// Add staff meta box
if(is_admin()){
    add_action('admin_menu', 'staff_add_box');
    add_action('save_post', 'staff_add_box');
    add_action('save_post', 'staff_save_data');
}

function staff_add_box(){
    global $staff_meta_box;
    add_meta_box($staff_meta_box['id'], $staff_meta_box['title'], 'staff_show_box', $staff_meta_box['page'], $staff_meta_box['context'], $staff_meta_box['priority']);
}
/**
 * Callback function to show fields in staff meta box
 * @global array $staff_meta_box
 * @global Object $post
 * @global array $area_fields
 */
function staff_show_box() {
    global $staff_meta_box, $post;
    custom_output_meta_box($staff_meta_box, $post);    
}
/**
 * Save data from staff meta box
 * @global array $staff_meta_box
 * @param Object $post_id
 * @return 
 */
function staff_save_data($post_id) {
    global $staff_meta_box;
    custom_save_meta_box($staff_meta_box, $post_id);
}