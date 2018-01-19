<?php
/* ----------------------------------------------------------------------------------- */
# Create post_type
/* ----------------------------------------------------------------------------------- */
add_action('init', 'create_membership_post_type');

function create_membership_post_type(){
    register_post_type('membership', array(
        'labels' => array(
            'name' => __('Memberships', SHORT_NAME),
            'singular_name' => __('Memberships', SHORT_NAME),
            'add_new' => __('Thêm mới', SHORT_NAME),
            'add_new_item' => __('Add new Membership', SHORT_NAME),
            'new_item' => __('New Membership', SHORT_NAME),
            'edit' => __('Edit', SHORT_NAME),
            'edit_item' => __('Edit Membership', SHORT_NAME),
            'view' => __('View Membership', SHORT_NAME),
            'view_item' => __('View Membership', SHORT_NAME),
            'search_items' => __('Search Memberships', SHORT_NAME),
            'not_found' => __('No Membership found', SHORT_NAME),
            'not_found_in_trash' => __('No Membership found in trash', SHORT_NAME),
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
            //'custom-fields', 'comments', 'excerpt', 'thumbnail', 'author', 
        ),
        'rewrite' => array('slug' => 'membership', 'with_front' => false),
        'can_export' => true,
        'description' => __('Membership description here.', SHORT_NAME),
        //'taxonomies' => array('post_tag'),
    ));
}

function membership_get_levels() {
    $result = array();
    $loop = new WP_Query(array(
        'post_type' => 'membership',
        'posts_per_page' => -1,
        'meta_key' => 'mem_order',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
    ));
    while ($loop->have_posts()) : $loop->the_post();
        $result[get_the_ID()] = get_the_title();
    endwhile;
    wp_reset_query();
    return $result;
}

/* ----------------------------------------------------------------------------------- */
# Meta box
/* ----------------------------------------------------------------------------------- */
$membership_meta_box = array(
    'id' => 'membership-meta-box',
    'title' => 'Membership information',
    'page' => 'membership',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Cấp độ',
            'desc' => 'Xem được những video cấp độ',
            'id' => 'level',
            'type' => 'checkbox_list',
            'std' => '1',
            'options' => membership_get_levels(),
        ),
        array(
            'name' => 'Price',
            'desc' => 'Ví dụ: 100000',
            'id' => 'price',
            'type' => 'text',
            'std' => '0',
        ),
        array(
            'name' => 'Sale price',
            'desc' => 'Ví dụ: 80000',
            'id' => 'sale_price',
            'type' => 'text',
            'std' => '0',
        ),
        array(
            'name' => 'STT',
            'desc' => '',
            'id' => 'mem_order',
            'type' => 'text',
            'std' => '1',
        ),
));

// Add membership meta box
if(is_admin()){
    add_action('admin_menu', 'membership_add_box');
    add_action('save_post', 'membership_add_box');
    add_action('save_post', 'membership_save_data');
}

function membership_add_box(){
    global $membership_meta_box;
    add_meta_box($membership_meta_box['id'], $membership_meta_box['title'], 'membership_show_box', $membership_meta_box['page'], $membership_meta_box['context'], $membership_meta_box['priority']);
}
/**
 * Callback function to show fields in membership meta box
 * @global array $membership_meta_box
 * @global Object $post
 * @global array $area_fields
 */
function membership_show_box() {
    global $membership_meta_box, $post;
    custom_output_meta_box($membership_meta_box, $post);
    
    $pdc_attr_name = get_post_meta($post->ID, 'pdc_attr_name', true);
    $pdc_attr_value = get_post_meta($post->ID, 'pdc_attr_value', true);
    echo <<<HTML
    <div style="font-weight: bold">Đơn giá theo thời hạn (Ngày - Giá):</div>
    <table width="100%" id="tbl_pdc_att">
       <tbody>
HTML;
    $_k = 1;
    foreach ($pdc_attr_name as $k => $v) {
        echo '<tr id="pdc_att_ip_name_' . $_k . '">
        <td>
            <input type="text" style="width:99%" size="30" name="pdc_attrs[' . $_k . ']" id=pdc_attrs_' . $_k . ' value="' . htmlentities($v, ENT_QUOTES | ENT_IGNORE, 'UTF-8', FALSE) . '" placeholder="Thời hạn (số tháng)" />
        </td>
        <td>
            <input type="text" style="width:99%" size="30" name="pdc_attr_value[' . $_k . ']" value="' . htmlentities($pdc_attr_value[$k], ENT_QUOTES | ENT_IGNORE, 'UTF-8', FALSE) . '" placeholder="Đơn giá (số tiền)" />
        </td>
        <td><input type="button" style="width:98%" class="button tr_remove" value="Xoá"/></td>
        </tr>';
        $_k++;
    }
    echo <<<HTML
       <tr id="tr_clone" style="display:none">
        <td><input type="text" style="width:99%" size="30" id="pdc_att_ip_name" placeholder="Thời hạn (số tháng)" /></td>
        <td><input type="text" style="width:99%" size="30" id="pdc_att_ip_value" placeholder="Đơn giá (số tiền)" /></td>
        <td><input type="button" style="width:99%" class="button tr_remove" value="Xoá"></td>
        </tr>
        </tbody>
    </table>
    <input id="tr_add_btn" style="margin-left:4px;" type="button" class="button btnAddfield" value="Thêm mới" />
HTML;
}
/**
 * Save data from membership meta box
 * @global array $membership_meta_box
 * @param Object $post_id
 * @return 
 */
function membership_save_data($post_id) {
    global $membership_meta_box;

    $name = getRequest('pdc_attrs');
    $value = getRequest('pdc_attr_value');
    if(is_array($name)){
        foreach ($name as $k => $v) {
            if (empty($v)) {
                unset($name[$k]);
                unset($value[$k]);
            }
        }
    }

    update_post_meta($post_id, 'pdc_attr_name', $name);
    update_post_meta($post_id, 'pdc_attr_value', $value);
    custom_save_meta_box($membership_meta_box, $post_id);
}