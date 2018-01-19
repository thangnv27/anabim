<?php
/* ----------------------------------------------------------------------------------- */
# Create post_type
/* ----------------------------------------------------------------------------------- */
add_action('init', 'create_product_post_type');

function create_product_post_type(){
    register_post_type('product', array(
        'labels' => array(
            'name' => __('Sản phẩm', SHORT_NAME),
            'singular_name' => __('Sản phẩm', SHORT_NAME),
            'add_new' => __('Thêm mới', SHORT_NAME),
            'add_new_item' => __('Add new Product', SHORT_NAME),
            'new_item' => __('New Product', SHORT_NAME),
            'edit' => __('Edit', SHORT_NAME),
            'edit_item' => __('Edit Product', SHORT_NAME),
            'view' => __('View Product', SHORT_NAME),
            'view_item' => __('View Product', SHORT_NAME),
            'search_items' => __('Search Products', SHORT_NAME),
            'not_found' => __('No Product found', SHORT_NAME),
            'not_found_in_trash' => __('No Product found in trash', SHORT_NAME),
        ),
        'public' => true,
        'show_ui' => true,
        'publicy_queryable' => true,
        'exclude_from_search' => false,
        'menu_position' => 20,
        'hierarchical' => false,
        'query_var' => true,
        'supports' => array(
            'title', 'editor', 'author', 'thumbnail', 'excerpt', 
            //'custom-fields', 'comments',
        ),
        'rewrite' => array('slug' => 'khoa-hoc', 'with_front' => false),
        'can_export' => true,
        'description' => __('Product description here.', SHORT_NAME),
        //'taxonomies' => array('post_tag'),
    ));
}
/* ----------------------------------------------------------------------------------- */
# Create taxonomy
/* ----------------------------------------------------------------------------------- */
add_action('init', 'create_product_taxonomies');

function create_product_taxonomies(){
    register_taxonomy('product_cat', 'product', array(
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'query_var' => true,
        'labels' => array(
            'name' => __('Chuyên mục sản phẩm', SHORT_NAME),
            'singular_name' => __('Chuyên mục sản phẩm', SHORT_NAME),
            'add_new' => __('Thêm mới', SHORT_NAME),
            'add_new_item' => __('Add New Category', SHORT_NAME),
            'new_item' => __('New Category', SHORT_NAME),
            'search_items' => __('Search Categories', SHORT_NAME),
        ),
        'rewrite' => array('slug' => 'product-category', 'with_front' => false),
    ));
}

// Show filter
add_action('restrict_manage_posts','restrict_product_by_product_category');
function restrict_product_by_product_category() {
    global $wp_query, $typenow;
    if ($typenow=='product') {
        $taxonomies = array('product_cat');
        foreach ($taxonomies as $taxonomy) {
            $category = get_taxonomy($taxonomy);
            wp_dropdown_categories(array(
                'show_option_all' =>  __("$category->label"),
                'taxonomy'        =>  $taxonomy,
                'name'            =>  $taxonomy,
                'orderby'         =>  'name',
                'selected'        =>  $wp_query->query['term'],
                'hierarchical'    =>  true,
                'depth'           =>  3,
                'show_count'      =>  true, // Show # listings in parens
                'hide_empty'      =>  true, // Don't show businesses w/o listings
            ));
        }
    }
}

// Get post where filter condition

add_filter( 'posts_where' , 'products_where' );
function products_where($where) {
    if (is_admin()) {
        global $wpdb;
        
        $wp_posts = $wpdb->posts;
        $term_relationships = $wpdb->term_relationships;
        $term_taxonomy = $wpdb->term_taxonomy;

        $product_category = intval(getRequest('product_cat'));
        if ($product_category > 0) {
            $where .= " AND $wp_posts.ID IN (SELECT DISTINCT {$term_relationships}.object_id FROM {$term_relationships} 
                WHERE {$term_relationships}.term_taxonomy_id IN (
                    SELECT DISTINCT {$term_taxonomy}.term_taxonomy_id FROM {$term_taxonomy} ";
            
            if ($product_category > 0) {
                $where .= " WHERE {$term_taxonomy}.term_id = $product_category 
                                AND {$term_taxonomy}.taxonomy = 'product_cat') )";
            }
                            
//            $where = str_replace("AND 0 = 1", "", $where);
            $where = str_replace("0 = 1", "1 = 1", $where);
        }
    }
    return $where;
}

function product_get_levels() {
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
$product_meta_box = array(
    'id' => 'product-meta-box',
    'title' => 'Thông tin sản phẩm',
    'page' => 'product',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Cấp độ',
            'desc' => '',
            'id' => 'level',
            'type' => 'radio',
            'std' => '1',
            'options' => product_get_levels(),
        ),
        array(
            'name' => 'Đối tượng',
            'desc' => '',
            'id' => 'object',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => 'Phần mềm',
            'desc' => '',
            'id' => 'software',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => 'Price',
            'desc' => 'Ví dụ: 100000',
            'id' => 'price',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => 'Sale price',
            'desc' => 'Ví dụ: 80000',
            'id' => 'sale_price',
            'type' => 'text',
            'std' => '',
        ),
));

// Add product meta box
if(is_admin()){
    add_action('admin_menu', 'product_add_box');
    add_action('save_post', 'product_add_box');
    add_action('save_post', 'product_save_data');
//    add_action('publish_product', 'product_publish_data');
}

function product_add_box(){
    global $product_meta_box;
    add_meta_box($product_meta_box['id'], $product_meta_box['title'], 'product_show_box', $product_meta_box['page'], $product_meta_box['context'], $product_meta_box['priority']);
}
/**
 * Callback function to show fields in product meta box
 * @global array $product_meta_box
 * @global Object $post
 * @global array $area_fields
 */
function product_show_box() {
    global $product_meta_box, $post;
    custom_output_meta_box($product_meta_box, $post);
    
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
            <input type="text" style="width:99%" size="30" name="pdc_attrs[' . $_k . ']" id=pdc_attrs_' . $_k . ' value="' . htmlentities($v, ENT_QUOTES | ENT_IGNORE, 'UTF-8', FALSE) . '" placeholder="Thời hạn (số ngày)" />
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
        <td><input type="text" style="width:99%" size="30" id="pdc_att_ip_name" placeholder="Thời hạn (số ngày)" /></td>
        <td><input type="text" style="width:99%" size="30" id="pdc_att_ip_value" placeholder="Đơn giá (số tiền)" /></td>
        <td><input type="button" style="width:99%" class="button tr_remove" value="Xoá"></td>
        </tr>
        </tbody>
    </table>
    <input id="tr_add_btn" style="margin-left:4px;" type="button" class="button btnAddfield" value="Thêm mới" />
HTML;
}
/**
 * Save data from product meta box
 * @global array $product_meta_box
 * @param Object $post_id
 * @return 
 */
function product_save_data($post_id) {
    global $product_meta_box;
    
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
    custom_save_meta_box($product_meta_box, $post_id);
}
/*
function product_publish_data($post_id){
    $purchases = get_post_meta($post_id, "purchases", true);
    
    if(!$purchases or $purchases == ""){
        if( ( $_POST['post_status'] == 'publish' ) && ( $_POST['original_post_status'] != 'publish' ) ) {
            update_post_meta($post_id, 'purchases', 0);
        }
    }
    
    return $post_id;
}

/***************************************************************************/

/*
// ADD NEW COLUMN  
function product_columns_head($defaults) {
    $defaults['orders'] = 'Orders';
    return $defaults;
}

// SHOW THE COLUMN
function product_columns_content($column_name, $post_id) {
    switch ($column_name) {
        case 'orders':
            echo '<a href="admin.php?page=nvt_orders&product_id=' . $post_id . '" target="_blank">View</a>';
            break;
        default:
            break;
    }
}

add_filter('manage_product_posts_columns', 'product_columns_head');  
add_action('manage_product_posts_custom_column', 'product_columns_content', 10, 2);  

# Add custom field to quick edit
//add_action( 'bulk_edit_custom_box', 'quickedit_products_custom_box', 10, 2 );
add_action('quick_edit_custom_box', 'quickedit_products_custom_box', 10, 2);

function quickedit_products_custom_box( $col, $type ) {
    if( $col != 'orders' || $type != 'product' ) {
        return;
    }
?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col product-custom-fields">
            <div class="inline-edit-group">
                <label class="alignleft">
                    <span class="title">Price</span>
                    <input type="text" name="price" id="price" value="" />
                    <span class="spinner" style="display: none;"></span>
                </label>
            </div>
            <div class="inline-edit-group">
                <label class="alignleft">
                    <span class="title">Sale price</span>
                    <input type="text" name="sale_price" id="sale_price" value="" />
                    <span class="spinner" style="display: none;"></span>
                </label>
            </div>
        </div>
    </fieldset>
<?php
}

add_action('save_post', 'product_save_quick_edit_data');
 
function product_save_quick_edit_data($post_id) {
    // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
    // to do anything
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return $post_id;   
    // Check permissions
    if ( 'page' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
            return $post_id;
    } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
        return $post_id;
    }
    // OK, we're authenticated: we need to find and save the data
    $post = get_post($post_id);
    $fields = array('price', 'sale_price');
    foreach ($fields as $field) {
        if (isset($_POST[$field]) && ($post->post_type != 'revision')) {
            $meta = esc_attr($_POST[$field]);
            if ($meta)
                update_post_meta( $post_id, $field, $meta);
        }
    }
    
    return $post_id;
}

add_action( 'admin_print_scripts-edit.php', 'product_enqueue_edit_scripts' );
function product_enqueue_edit_scripts() {
   wp_enqueue_script( 'product-admin-edit', get_bloginfo( 'stylesheet_directory' ) . '/libs/js/quick_edit.js', array( 'jquery', 'inline-edit-post' ), '', true );
}
*/

function product_add_custom_js() {
    ?>
    <script type="text/javascript">/* <![CDATA[ */
        jQuery(function ($) {
            var $tr_id = $('#tbl_pdc_att').find("tr").length - 1;
            $('#tr_add_btn').on('click', function (e) {
                ++$tr_id;

                e.preventDefault();
                var $cloned_tr = $('#tr_clone').clone(true);
                $cloned_tr.attr({
                    id: 'pdc_attrs_' + $tr_id
                }).removeAttr('style').find('#pdc_att_ip_name').attr({
                    id: "pdc_att_ip_name_" + $tr_id,
                    name: "pdc_attrs[" + $tr_id + "]"
                });

                $cloned_tr.attr({
                    id: 'pdc_attrs_' + $tr_id
                }).find('#pdc_att_ip_value').attr({
                    id: "pdc_attr_value_" + $tr_id,
                    name: "pdc_attr_value[" + $tr_id + "]"
                }).css('display', 'block');

                $cloned_tr.insertBefore($('#tr_clone'));
            });

            $('.tr_remove').on('click', function (e) {
                e.preventDefault();
                $(this).closest('tr').remove();
            });
        });
        /* ]]> */
    </script>
    <?php
}

add_action('admin_print_footer_scripts', 'product_add_custom_js', 99);