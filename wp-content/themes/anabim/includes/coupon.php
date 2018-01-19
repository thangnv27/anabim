<?php
/* ----------------------------------------------------------------------------------- */
# Create post_type
/* ----------------------------------------------------------------------------------- */
add_action('init', 'create_coupon_post_type');

function create_coupon_post_type(){
    register_post_type('coupon', array(
        'labels' => array(
            'name' => __('Mã giảm giá'),
            'singular_name' => __('Mã giảm giá'),
            'add_new' => __('Add new coupon'),
            'add_new_item' => __('Add new Coupon'),
            'new_item' => __('New Coupon'),
            'edit' => __('Edit'),
            'edit_item' => __('Edit Coupon'),
            'view' => __('View Coupon'),
            'view_item' => __('View Coupon'),
            'search_items' => __('Search Coupons'),
            'not_found' => __('No Coupon found'),
            'not_found_in_trash' => __('No Coupon found in trash'),
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu'=> PPOCART_MENU_NAME,
        'publicy_queryable' => true,
        'exclude_from_search' => false,
//        'menu_position' => 20,
        'hierarchical' => false,
        'query_var' => true,
        'supports' => array( 'title' ),
        'rewrite' => false,
        'has_archive' => false,
        'can_export' => true,
        'description' => __('Coupon description here.')
    ));
}
/* ----------------------------------------------------------------------------------- */
# Meta box
/* ----------------------------------------------------------------------------------- */
$coupon_meta_box = array(
    'id' => 'coupon-meta-box',
    'title' => 'Thông tin mã giảm giá',
    'page' => 'coupon',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Mã Code',
            'desc' => '',
            'id' => 'coupon_code',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => 'Mô tả',
            'desc' => '',
            'id' => 'mo_ta',
            'type' => 'textarea',
            'std' => '',
        ),
        array(
            'name' => 'Loại mã giảm giá',
            'desc' => '',
            'id' => 'coupon_type',
            'type' => 'select',
            'std' => '',
            'options' => array(
                'cp_product' => 'Giảm giá sản phẩm',
                'cp_percent_product' => 'Giảm giá % sản phẩm',
                'cp_order' => 'Giảm giá đơn hàng',
                'cp_percent_order' => 'Giảm giá % đơn hàng',
            )
        ),
          array(
            'name' => 'Dãy ID của sản phẩm',
            'desc' => 'Nhập vào ID của 1 hoặc nhiều sản phẩm. các nhau bởi dấu , (Bỏ trống sẽ áp dụng giảm giá cho đơn hàng).',
            'id' => 'coupon_id_product',
            'type' => 'text',
            'std' => '',
        ),
          array(
            'name' => 'Giá trị',
            'desc' => 'Nhập vào giá trị của phiếu giảm giá. VD: Giảm 15% nhập là 15, giảm 150.000đ nhập là 150000',
            'id' => 'coupon_amount',
            'type' => 'text',
            'std' => '',
        ),
          array(
            'name' => 'Số lượng',
            'desc' => 'Nếu bỏ trống giá trị sẽ là 0',
            'id' => 'coupon_usage',
            'type' => 'text',
            'std' => '0',
        ),
          array(
            'name' => 'Tổng giá để được sử dụng',
            'desc' => 'Nhập vào giá tối thiểu của đơn hàng hoặc sản phẩm để được áp dụng mã giảm giá',
            'id' => 'coupon_minimum_amount',
            'type' => 'text',
            'std' => '',
        ),
          array(
            'name' => 'Giới hạn thời gian',
            'desc' => 'Nhập vào thời gian áp dụng cho phiếu giảm giá',
            'id' => 'coupon_expiry_date',
            'type' => 'text',
            'std' => '',
        ),
));

// Add coupon meta box
if(is_admin()){
    add_action('admin_menu', 'coupon_add_box');
    add_action('save_post', 'coupon_add_box');
    add_action('save_post', 'coupon_save_data');
}

function coupon_add_box(){
    global $coupon_meta_box;
    add_meta_box($coupon_meta_box['id'], $coupon_meta_box['title'], 'coupon_show_box', $coupon_meta_box['page'], $coupon_meta_box['context'], $coupon_meta_box['priority']);
}

// Callback function to show fields in coupon meta box
function coupon_show_box() {
    // Use nonce for verification
    global $coupon_meta_box, $post;
    custom_output_meta_box($coupon_meta_box, $post);
?>
<script type="text/javascript">/* <![CDATA[ */
    jQuery(function($){
        $.extend({
            password: function (length, special) {
                var iteration = 0;
                var password = "";
                var randomNumber;
                if(special == undefined){
                    var special = false;
                }
                while(iteration < length){
                    randomNumber = (Math.floor((Math.random() * 100)) % 94) + 33;
                    if(!special){
                        if ((randomNumber >=33) && (randomNumber <=47)) { continue; }
                        if ((randomNumber >=58) && (randomNumber <=64)) { continue; }
                        if ((randomNumber >=91) && (randomNumber <=96)) { continue; }
                        if ((randomNumber >=123) && (randomNumber <=126)) { continue; }
                    }
                    iteration++;
                    password += String.fromCharCode(randomNumber);
                }
                return password;
            }
        });
        $("input#coupon_code").css('width', '87%')
        .after('<input type="button" name="btnGenerateCoupon" id="btnGenerateCoupon" value="Generate" class="button" />')
        .next("input#btnGenerateCoupon").click(function(){
            var strMD5 = $.md5($.password(12,true));
            $("input#coupon_code").val(strMD5.substr(0, 10));
        });
    });
    /* ]]> */
</script>
<?php
}

// Save data from coupon meta box
function coupon_save_data($post_id) {
    global $coupon_meta_box;
    custom_save_meta_box($coupon_meta_box, $post_id);
}
