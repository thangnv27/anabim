<?php

# Meta box
$post_meta_box = array(
    'id' => 'post-meta-box',
    'title' => 'Thông tin',
    'page' => 'post',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
//        array(
//            'name' => 'Phụ đề tiếng anh',
//            'desc' => 'File có định dạng .vtt',
//            'id' => 'caption_en',
//            'type' => 'text',
//            'std' => '',
//            'btn' => 'caption_en',
//        ),
        array(
            'name' => 'Loại video',
            'desc' => 'Nếu chọn Regular thì thành viên chỉ cần đăng nhập là xem được.',
            'id' => 'video_type',
            'type' => 'radio',
            'std' => '',
            'options' => array(
                'regular' => 'Regular',
                'premium' => 'Premium',
            ),
        ),
));

// Add post meta box
if(is_admin()){
    add_action('admin_menu', 'post_add_box');
    add_action('save_post', 'post_add_box');
    add_action('save_post', 'post_save_data');
}

function post_add_box(){
    global $post_meta_box;
    add_meta_box($post_meta_box['id'], $post_meta_box['title'], 'post_show_box', $post_meta_box['page'], $post_meta_box['context'], $post_meta_box['priority']);
}

// Callback function to show fields in post meta box
function post_show_box() {
    // Use nonce for verification
    global $post_meta_box, $post;
    custom_output_meta_box($post_meta_box, $post);
}

// Save data from post meta box
function post_save_data($post_id) {
    global $post_meta_box;
    custom_save_meta_box($post_meta_box, $post_id);
}