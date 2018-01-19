<?php

/* ----------------------------------------------------------------------------------- */
# Quick edit
/* ----------------------------------------------------------------------------------- */
function get_product_meta(){
    $product_id = getRequest('product_id');
    $response = array(
        'price' => get_post_meta( $product_id, 'price', true ),
        'sale_price' => get_post_meta( $product_id, 'sale', true ),
    );
    
    Response(json_encode($response));
    
    exit();
}

/* ----------------------------------------------------------------------------------- */
# Membership - add to cart
/* ----------------------------------------------------------------------------------- */
function membershipAddToCart(){
    $locale = getRequest("locale");
    $lang['login'] = ($locale == "vn") ? "Vui lòng đăng nhập vào tài khoản của bạn trước" : "Please login to your account before upgrade";
    $lang['already_reg'] = ($locale == "vn") ? "Bạn đang ở cấp độ này và chưa hết hạn" : "This is your current level and not expired";

    global $current_user;
    get_currentuserinfo();
    $membership = esc_attr(get_the_author_meta('user_membership', $current_user->ID));
    $id = intval(getRequest('id'));
    
    if(!is_user_logged_in()){
        Response(json_encode(array(
            'status' => 'error',
            'message' => $lang['login'],
        )));
    }elseif($membership == $id){
        Response(json_encode(array(
            'status' => 'error',
            'message' => $lang['already_reg'],
        )));
    } else {
        $price = floatval(get_post_meta($id, "price", true));
        $sale_price = floatval(get_post_meta($id, "sale_price", true));
        $member_price = $sale_price;
        if($price == $sale_price or $sale_price == 0){
            $member_price = $price;
        }
        $time = intval(getRequest('time'));
        $_price = 0;
        $pdc_attr_name = get_post_meta($id, 'pdc_attr_name', true);
        $pdc_attr_value = get_post_meta($id, 'pdc_attr_value', true);
        foreach ($pdc_attr_name as $_key => $_time) {
            if($time == $_time){
                $_price = $pdc_attr_value[$_key];
                break;
            }
        }
        if($_price > 0){
            $member_price = $_price;
        }
        $package = array(
            'id' => $id,
            'title' => get_the_title($id),
            'price' => $member_price,
            'time' => $time,
            'amount' => $member_price,
        );

        if(isset($_SESSION['membershipCart']) and !empty($_SESSION['membershipCart'])){
            unset($_SESSION['membershipCart']);
        }
        
        $_SESSION['membershipCart'] = $package;

        // Response message
        Response(json_encode(array(
            'status' => 'success',
        )));
    }
    exit();
}

/* ----------------------------------------------------------------------------------- */
# Membership - Complete order
/* ----------------------------------------------------------------------------------- */
function membershipOrderComplete() {
    $locale = getRequest("locale");
    $lang['login'] = ($locale == "vn") ? "Bạn đã bị thoát ra do thời gian chờ quá lâu, vui lòng đăng nhập lại!" : "You've been log out for the timeout, please login again!";
    $lang['cart_empty'] = ($locale == "vn") ? "Bạn chưa chọn khoá học nào, vui lòng chọn đăng ký một khoá học." : "Your cart is empty, please add product into your cart.";
    $lang['cEmail_invalid'] = ($locale == "vn") ? "<p>Địa chỉ email khách hàng không hợp lệ</p>" : "Customer email invalid!";
    $lang['order_success'] = ($locale == "vn") ? "Đăng ký thành công! Chúng tôi sẽ liên lạc với bạn trong thời gian sớm nhất!" : "Order Success! We will contact you soon!";
    $lang['order_failure'] = ($locale == "vn") ? "Đăng ký không thành công! Hãy liên lạc với chúng tôi ngay để được trợ giúp!" : "Order Failure! Please contact us for assistance!";
    
    if(!is_user_logged_in()){
        Response(json_encode(array(
                    'status' => 'error',
                    'message' => $lang['login'],
                    'login' => 1
                )));
    } else if(isset($_SESSION['membershipCart']) and !empty($_SESSION['membershipCart'])) {
        $errorMsg = "";

        if(!is_valid_email(getRequest("cEmail"))){
            $errorMsg .= $lang['cEmail_invalid'];
        }
        
        if($errorMsg == ""){
            $customer_info = array(
                'fullname' => getRequest('cName'),
                'email' => getRequest('cEmail'),
                'phone' => getRequest('cPhone'),
                'address' => getRequest('cAddress'),
                'workplace' => getRequest('cWorkplace'),
                'city' => getRequest('cCity'),
            );
            $payment_method = getRequest('payment_method');
            $cart = $_SESSION['membershipCart'];
            $expire_date = date('Y-m-d', strtotime("+{$cart['time']} months"));

            global $wpdb, $current_user;
            get_currentuserinfo();

            $tblMemberships = $wpdb->prefix . 'memberships';
            $result = $wpdb->query($wpdb->prepare("INSERT INTO $tblMemberships SET user_id = %d, user_info = '%s', 
                payment_method = '%s', package_id = %d, package_name = '%s', 
                price = '%s', time = %d, expire_date='%s', total_amount = '%s'", 
                $current_user->ID, json_encode($customer_info), $payment_method, $cart['id'], $cart['title'], 
                $cart['price'], $cart['time'], $expire_date, $cart['amount']));
            if($result){
                update_usermeta($current_user->ID, 'membership_expire', $expire_date);
                sendMembershipInvoiceToEmail($customer_info, $payment_method, $expire_date);
                unset($_SESSION['membershipCart']);

                Response(json_encode(array(
                    'status' => 'success',
                    'message' => $lang['order_success'],
                )));
            } else {
                Response(json_encode(array(
                        'status' => 'failure',
                        'message' => $lang['order_failure'],
                    )));
            }
        }else{
            Response(json_encode(array(
                'status' => 'error',
                'message' => $errorMsg,
            )));
        }
    } else {
        Response(json_encode(array(
                    'status' => 'error',
                    'message' => $lang['cart_empty'],
                )));
    }
    
    exit();
}
function membershipOrderNganLuong(){
    $locale = getRequest("locale");
    $lang['login'] = ($locale == "vn") ? "Bạn đã bị thoát ra do thời gian chờ quá lâu, vui lòng đăng nhập lại!" : "You've been log out for the timeout, please login again!";
    $lang['coupon_wrong'] = ($locale == "vn") ? "Sai mã giá" : "Coupon code is wrong!";
    $lang['cart_empty'] = ($locale == "vn") ? "Bạn chưa chọn khoá học nào, vui lòng chọn đăng ký một khoá học." : "Your cart is empty, please add product into your cart.";
    $lang['cEmail_invalid'] = ($locale == "vn") ? "<p>Địa chỉ email khách hàng không hợp lệ</p>" : "Customer email invalid!";

    if(!is_user_logged_in()){
        Response(json_encode(array(
                    'status' => 'error',
                    'message' => $lang['login'],
                    'login' => 1
                )));
    } else if(isset($_SESSION['membershipCart']) and !empty($_SESSION['membershipCart'])) {
        $errorMsg = "";

        if(!is_valid_email(getRequest("cEmail"))){
            $errorMsg .= $lang['cEmail_invalid'];
        }

        if($errorMsg == ""){
            $customer_info = array(
                'fullname' => getRequest('cName'),
                'email' => getRequest('cEmail'),
                'phone' => getRequest('cPhone'),
                'address' => getRequest('cAddress'),
                'workplace' => getRequest('cWorkplace'),
                'city' => getRequest('cCity'),
            );
            $_SESSION['CUSTOMER_INFO'] = $customer_info;
            $cart = $_SESSION['membershipCart'];
            
            $receiver = stripslashes(get_option("nl_email"));
            $return_url = get_page_link(get_option(SHORT_NAME . "_membershipNLComplete"));
            $order_code = random_string(6);
            
            global $nl_checkout;
            $url = $nl_checkout->buildCheckoutUrl($return_url, $receiver, '', $order_code, $cart['amount']);
            
            Response(json_encode(array(
                    'status' => 'success',
                    'message' => "Kiểm tra hợp lệ, chúng tôi sẽ chuyển sang cổng thanh toán Ngân Lượng ngay bây giờ.",
                    'nganluongUrl' => $url,
                )));
        }else{
            Response(json_encode(array(
                    'status' => 'error',
                    'message' => $errorMsg,
                )));
        }
    } else {
        Response(json_encode(array(
                    'status' => 'error',
                    'message' => $lang['cart_empty'],
                )));
    }
    
    exit();
}

/* ----------------------------------------------------------------------------------- */
# Add product to Cart
/* ----------------------------------------------------------------------------------- */
function addToCart(){
    $locale = getRequest("locale");
    $lang['added'] = ($locale == "vn") ? "Đã thêm vào giỏ hàng" : "Added to your cart";
    $lang['login'] = ($locale == "vn") ? "Vui lòng đăng nhập vào tài khoản của bạn trước" : "Please login to your account before register this course";
    $lang['already_reg'] = ($locale == "vn") ? "Khoá học này bạn đã đăng ký rồi và chưa hết hạn" : "This course you have registered and not expired";
    
    $id = intval(getRequest('id'));
    if(!is_user_logged_in()){
        Response(json_encode(array(
            'status' => 'error',
            'message' => $lang['login'],
        )));
    }elseif(check_product_in_order($id)){
        Response(json_encode(array(
            'status' => 'error',
            'message' => $lang['already_reg'],
        )));
    } else {
        $price = getRequest('price');
        $quantity = 1;
        $time = 45;
        $amount = $price * $quantity;
        $product = array(
            'id' => $id,
            'title' => getRequest('title'),
            'price' => $price,
            'quantity' => $quantity,
            'amount' => $amount,
            'time' => $time,
        );

        if(isset($_SESSION['cart']) and !empty($_SESSION['cart'])){
            $addToCart = TRUE;
            $cart = $_SESSION['cart'];
            foreach ($cart as $k => $v) {
                if(getRequest('id') == $v['id']){
                    if($v['quantity'] == $quantity and $v['price'] == $price){
                        $addToCart = FALSE;
                    }else{
                        unset($cart[$k]);
                    }
                    break;
                }
            }
            if($addToCart == TRUE){
                array_push($cart, $product);
                $_SESSION['cart'] = $cart;
            }
        }else{
            $cart = array();
            array_push($cart, $product);
            $_SESSION['cart'] = $cart;
        }

        $cart = $_SESSION['cart'];

        // Response message
        Response(json_encode(array(
            'status' => 'success',
            'message' => $lang['added'],
        )));
    }
    exit();
}
/* ----------------------------------------------------------------------------------- */
# Remove a product in Cart
/* ----------------------------------------------------------------------------------- */
function deleteCartItem(){
    $locale = getRequest("locale");
    $lang['cart_empty'] = ($locale == "vn") ? "Bạn chưa chọn khoá học nào" : "Your cart is empty";
    $lang['removed'] = ($locale == "vn") ? "Đã xóa khoá học khỏi danh sách" : "Product removed from cart";
    
    if (isset($_SESSION['cart']) and !empty($_SESSION['cart'])) {
        $product_id = intval(getRequest('id'));
        if($product_id > 0){
            $cart = $_SESSION['cart'];
            $totalAmount = 0;
            foreach ($cart as $key => $product) {
                if($product['id'] == $product_id){
                    unset($cart[$key]);
                }else{
                    $totalAmount += $product['amount'];
                }
            }
            array_values($cart);
            $_SESSION['cart'] = $cart;

            Response(json_encode(array(
                'status' => 'success',
                'totalAmount' => number_format($totalAmount,0,',','.') . " đ",
                'message' => $lang['removed'],
            )));
        }
    }else{
        Response(json_encode(array(
            'status' => 'error',
            'message' => $lang['cart_empty'],
        )));
    }
    exit();
}
/* ----------------------------------------------------------------------------------- */
# Update Cart
/* ----------------------------------------------------------------------------------- */
function updateCartItem(){
    $locale = getRequest("locale");
    $lang['cart_empty'] = ($locale == "vn") ? "Bạn không có sản phẩm nào trong giỏ hàng" : "Your cart is empty";
    $lang['cart_updated'] = ($locale == "vn") ? "Đã cập nhật giỏ hàng" : "Your cart has been updated";
    
    if (isset($_SESSION['cart']) and !empty($_SESSION['cart'])) {
        $product_id = intval(getRequest('id'));
        $time = intval(getRequest('time'));
        $price = 0;
        $pdc_attr_name = get_post_meta($product_id, 'pdc_attr_name', true);
        $pdc_attr_value = get_post_meta($product_id, 'pdc_attr_value', true);
        foreach ($pdc_attr_name as $_key => $_time) {
            if($time == $_time){
                $price = $pdc_attr_value[$_key];
                break;
            }
        }
        if($product_id > 0 and $price > 0){
            $cart = $_SESSION['cart'];
            $totalAmount = 0;
            $item_amount = 0;
            foreach ($cart as $key => $product) {
                if($product['id'] == $product_id){
                    $new_product = $product;
                    $new_product['price'] = $price;
                    $new_product['amount'] = $price;
                    $new_product['time'] = $time;
                    unset($cart[$key]);
                    array_push($cart, $new_product);
                    $item_amount = $price;
                    $totalAmount += $price;
                }else{
                    $totalAmount += $product['amount'];
                }
            }
            array_values($cart);
            $_SESSION['cart'] = $cart;

            Response(json_encode(array(
                'status' => 'success',
                'countCart' => count($cart),
                'item_amount' => number_format($item_amount,0,',','.') . " đ",
                'totalAmount' => number_format($totalAmount,0,',','.') . " đ",
                'message' => $lang['cart_updated'],
            )));
        }
    }else{
        Response(json_encode(array(
            'status' => 'error',
            'message' => $lang['cart_empty'],
        )));
    }
    exit();
}
/* ----------------------------------------------------------------------------------- */
# Check cart before redirect to checkout page
/* ----------------------------------------------------------------------------------- */
function preCheckout(){
    $locale = getRequest("locale");
    $lang['cart_empty'] = ($locale == "vn") ? "Bạn chưa chọn khoá học nào, vui lòng chọn đăng ký một khoá học." : "Your cart is empty, please add product into your cart.";
    
    if (isset($_SESSION['cart']) and !empty($_SESSION['cart'])) {        
        Response(json_encode(array(
                    'status' => 'success',
                    'message' => "",
                )));
    }else{
        Response(json_encode(array(
                    'status' => 'error',
                    'message' => $lang['cart_empty'],
                )));
    }
    exit();
}
/* ----------------------------------------------------------------------------------- */
# Complete order
/* ----------------------------------------------------------------------------------- */
function orderComplete() {
    $locale = getRequest("locale");
    $lang['login'] = ($locale == "vn") ? "Bạn đã bị thoát ra do thời gian chờ quá lâu, vui lòng đăng nhập lại!" : "You've been log out for the timeout, please login again!";
    $lang['coupon_wrong'] = ($locale == "vn") ? "Sai mã giảm giá" : "Coupon code is wrong!";
    $lang['cart_empty'] = ($locale == "vn") ? "Bạn chưa chọn khoá học nào, vui lòng chọn đăng ký một khoá học." : "Your cart is empty, please add product into your cart.";
    $lang['cEmail_invalid'] = ($locale == "vn") ? "<p>Địa chỉ email khách hàng không hợp lệ</p>" : "Customer email invalid!";
    $lang['order_success'] = ($locale == "vn") ? "Đăng ký thành công! Chúng tôi sẽ liên lạc với bạn trong thời gian sớm nhất!" : "Order Success! We will contact you soon!";
    $lang['order_failure'] = ($locale == "vn") ? "Đăng ký không thành công! Hãy liên lạc với chúng tôi ngay để được trợ giúp!" : "Order Failure! Please contact us for assistance!";
    
    if(!is_user_logged_in()){
        Response(json_encode(array(
                    'status' => 'error',
                    'message' => $lang['login'],
                    'login' => 1
                )));
    } else if(isset($_SESSION['cart']) and !empty($_SESSION['cart'])) {
        $errorMsg = "";
        $coupon_amount = 0;
        $coupon_code = getRequest('coupon_code');
        $total_amount_before_cp = intval(getRequest('total_amount'));

        ## BEGIN Check Coupon
        if ($coupon_code != '') {
            $args = array(
                'post_type' => 'coupon',
                'meta_query' => array(
                    array(
                        'key' => 'coupon_code',
                        'value' => $coupon_code,
                    )
                )
            );
            $coupons = new WP_Query($args);
            if($coupons->post_count == 1) {
                while ($coupons->have_posts()) : $coupons->the_post();
                    $coupon_type = get_post_meta(get_the_ID(), "coupon_type", true);
                    $coupon_usage = intval(get_post_meta(get_the_ID(), "coupon_usage", true));
                    $coupon_expiry_date = intval(get_post_meta(get_the_ID(), "coupon_expiry_date", true));
                    $coupon_minimum_amount = intval(get_post_meta(get_the_ID(), "coupon_minimum_amount", true));
                    $coupon_amount2 = intval(get_post_meta(get_the_ID(), "coupon_amount", true));
                    $date = new DateTime(date("Y-m-d H:i:s", get_the_time('U')));
                    $currentDate = new DateTime(date("Y-m-d H:i:s"));
                    $diff = $date->diff($currentDate);
                    $day = $diff->format('%d');
                    if($day > $coupon_expiry_date){
                        $errorMsg .= 'Mã giảm giá đã hết hạn';
                    }elseif ($coupon_usage <= 0) {
                        $errorMsg .= 'Mã giảm giá đã quá số lượng sử dụng';
                    }elseif($total_amount_before_cp <= $coupon_minimum_amount){
                        $errorMsg .= 'Giá trị đơn hàng không đạt yêu cầu để sử dụng mã giảm giá';
                    }else{
                        if($coupon_type == 'cp_percent_order') {
                            $coupon_amount = intval(getRequest('total_amount')) * $coupon_amount2 / 100;
                        }elseif($coupon_type=='cp_order'){
                            $coupon_amount = $coupon_amount2;
                        }
                        update_post_meta(get_the_ID(), 'coupon_usage', $coupon_usage-1);
                    }
                endwhile;
            }else{
                $errorMsg .= $lang['coupon_wrong'];
            }
        }
        ## END Check Coupon

        if(!is_valid_email(getRequest("cEmail"))){
            $errorMsg .= $lang['cEmail_invalid'];
        }
        
        if($errorMsg == ""){
            $cart = $_SESSION['cart'];
            $name = getRequest('cName');
            $email = getRequest('cEmail');
            $phone = getRequest('cPhone');
            $address = getRequest('cAddress');
            $workplace = getRequest('cWorkplace');
            $city = getRequest('cCity');
            $notes = getRequest('cNotes');
            $customer_id = 0;

            if(is_user_logged_in()){
                global $current_user;
                get_currentuserinfo();
                $customer_id = $current_user->ID;
            } elseif(email_exists($email)){
                $user = get_user_by_email($email);
                $customer_id = $user->ID;
            }

            $customer_info = json_encode(array(
                'fullname' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'workplace' => $workplace,
                'city' => $city,
                'notes' => $notes,
            ));
            $payment_method = getRequest('payment_method');
//            $products = json_encode($cart);
            $total_amount = $total_amount_before_cp - $coupon_amount;
            $referrer = isset($_COOKIE['ap_id']) ? $_COOKIE['ap_id'] : "";

            global $wpdb;
            $tblOrders = $wpdb->prefix . 'orders';
            foreach ($cart as $product) {
                $products = json_encode($product);
                $expire_date = date('Y-m-d', strtotime("+{$product['time']} days"));
                $result = $wpdb->query($wpdb->prepare("INSERT INTO $tblOrders SET customer_id = %d, customer_info = '%s', 
                    payment_method = '%s', products = '%s', discount = '%s', total_amount = '%s', affiliate_id = '%s', 
                    expire_date='%s', coupon_code='%s'", 
                    $customer_id, $customer_info, $payment_method, $products, $coupon_amount, $total_amount, 
                    $referrer, $expire_date, $coupon_code));
            }

            if($result){
                Response(json_encode(array(
                        'status' => 'success',
                        'message' => $lang['order_success'],
                    )));
                // Send invoice to email
                sendInvoiceToEmail($customer_info, $coupon_amount);
                // Remove Cart
                unset($_SESSION['cart']);
            }else{
                Response(json_encode(array(
                        'status' => 'failure',
                        'message' => $lang['order_failure'],
                    )));
            }
        }else{
            Response(json_encode(array(
                    'status' => 'error',
                    'message' => $errorMsg,
                )));
        }
    } else {
        Response(json_encode(array(
                    'status' => 'error',
                    'message' => $lang['cart_empty'],
                )));
    }
    
    exit();
}
function orderNganLuong(){
    $locale = getRequest("locale");
    $lang['login'] = ($locale == "vn") ? "Bạn đã bị thoát ra do thời gian chờ quá lâu, vui lòng đăng nhập lại!" : "You've been log out for the timeout, please login again!";
    $lang['coupon_wrong'] = ($locale == "vn") ? "Sai mã giá" : "Coupon code is wrong!";
    $lang['cart_empty'] = ($locale == "vn") ? "Bạn chưa chọn khoá học nào, vui lòng chọn đăng ký một khoá học." : "Your cart is empty, please add product into your cart.";
    $lang['cEmail_invalid'] = ($locale == "vn") ? "<p>Địa chỉ email khách hàng không hợp lệ</p>" : "Customer email invalid!";

    if(!is_user_logged_in()){
        Response(json_encode(array(
                    'status' => 'error',
                    'message' => $lang['login'],
                    'login' => 1
                )));
    } else if(isset($_SESSION['cart']) and !empty($_SESSION['cart'])) {
        $errorMsg = "";
        $coupon_amount = 0;
        $coupon_code = getRequest('coupon_code');
        $total_amount_before_cp = intval(getRequest('total_amount'));

        ## BEGIN Check Coupon
        if ($coupon_code != '') {
            $args = array(
                'post_type' => 'coupon',
                'meta_query' => array(
                    array(
                        'key' => 'coupon_code',
                        'value' => $coupon_code,
                    )
                )
            );
            $coupons = new WP_Query($args);
            if($coupons->post_count == 1) {
                while ($coupons->have_posts()) : $coupons->the_post();
                    $coupon_type = get_post_meta(get_the_ID(), "coupon_type", true);
                    $coupon_usage = intval(get_post_meta(get_the_ID(), "coupon_usage", true));
                    $coupon_expiry_date = intval(get_post_meta(get_the_ID(), "coupon_expiry_date", true));
                    $coupon_minimum_amount = intval(get_post_meta(get_the_ID(), "coupon_minimum_amount", true));
                    $coupon_amount2 = intval(get_post_meta(get_the_ID(), "coupon_amount", true));
                    $date = new DateTime(date("Y-m-d H:i:s", get_the_time('U')));
                    $currentDate = new DateTime(date("Y-m-d H:i:s"));
                    $diff = $date->diff($currentDate);
                    $day = $diff->format('%d');
                    if($day > $coupon_expiry_date){
                        $errorMsg .= 'Mã giảm giá đã hết hạn';
                    }elseif ($coupon_usage <= 0) {
                        $errorMsg .= 'Mã giảm giá đã quá số lượng sử dụng';
                    }elseif($total_amount_before_cp <= $coupon_minimum_amount){
                        $errorMsg .= 'Giá trị đơn hàng không đạt yêu cầu để sử dụng mã giảm giá';
                    }else{
                        if($coupon_type == 'cp_percent_order') {
                            $coupon_amount = intval(getRequest('total_amount')) * $coupon_amount2 / 100;
                        }elseif($coupon_type=='cp_order'){
                            $coupon_amount = $coupon_amount2;
                        }
                        update_post_meta(get_the_ID(), 'coupon_usage', $coupon_usage-1);
                    }
                endwhile;
            }else{
                $errorMsg .= $lang['coupon_wrong'];
            }
        }
        ## END Check Coupon

        if(!is_valid_email(getRequest("cEmail"))){
            $errorMsg .= $lang['cEmail_invalid'];
        }

        if($errorMsg == ""){
//            $cart = $_SESSION['cart'];
            $name = getRequest('cName');
            $email = getRequest('cEmail');
            $phone = getRequest('cPhone');
            $address = getRequest('cAddress');
            $workplace = getRequest('cWorkplace');
            $city = getRequest('cCity');
            $notes = getRequest('cNotes');
            $customer_id = 0;

            if(is_user_logged_in()){
                global $current_user;
                get_currentuserinfo();
                $customer_id = $current_user->ID;
            } elseif(email_exists($email)){
                $user = get_user_by_email($email);
                $customer_id = $user->ID;
            }

            $customer_info = json_encode(array(
                'fullname' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'workplace' => $workplace,
                'city' => $city,
                'notes' => $notes,
            ));
//            $products = json_encode($cart);
            $_SESSION['CUSTOMER_ID'] = $customer_id;
            $_SESSION['CUSTOMER_INFO'] = $customer_info;
//            $_SESSION['PRODUCTS_CART'] = $products;
            $_SESSION['coupon_amount'] = $coupon_amount;
            $_SESSION['coupon_code'] = $coupon_code;
            $totalAmount = $total_amount_before_cp - $coupon_amount;
            
            $receiver = stripslashes(get_option("nl_email"));
            $return_url = get_page_link(get_option(SHORT_NAME . "_pageNLComplete"));
            $order_code = random_string(6);
            
            global $nl_checkout;
            $url = $nl_checkout->buildCheckoutUrl($return_url, $receiver, '', $order_code, $totalAmount);
            
            Response(json_encode(array(
                    'status' => 'success',
                    'message' => "Kiểm tra hợp lệ, chúng tôi sẽ chuyển sang cổng thanh toán Ngân Lượng ngay bây giờ.",
                    'nganluongUrl' => $url,
                )));
        }else{
            Response(json_encode(array(
                    'status' => 'error',
                    'message' => $errorMsg,
                )));
        }
    } else {
        Response(json_encode(array(
                    'status' => 'error',
                    'message' => $lang['cart_empty'],
                )));
    }
    
    exit();
}

function usingCoupon() {
    $locale = getRequest("locale");
    $lang['coupon_wrong'] = ($locale == "vn") ? "Sai mã giá" : "Coupon code is wrong!";
    $lang['coupon_complete'] = ($locale == "vn") ? "Sử dụng mã giảm giá thành công" : "Coupon using success fully!";
    $lang['cart_empty'] = ($locale == "vn") ? "Đơn hàng không có sản phẩm, vui lòng thêm sản phẩm vào giỏ hàng trước." : "Your cart is empty, please add product into your cart.";
    $lang['label_discount'] = ($locale == "vn") ? "Số tiền được giảm: ." : "Discount: ";

    if (isset($_SESSION['cart']) and ! empty($_SESSION['cart'])) {
        $errorMsg = "";
        $coupon_amount = 0;
        $coupon_code = getRequest('coupon_code');
        $total_amount_before_cp = intval(getRequest('total_amount'));

        if (!empty($coupon_code) && $total_amount_before_cp > 0) {
            $args = array(
                'post_type' => 'coupon',
                'meta_query' => array(
                    array(
                        'key' => 'coupon_code',
                        'value' => $coupon_code,
                    )
                )
            );
            $coupons = new WP_Query($args);
            if($coupons->post_count == 1) {
                while ($coupons->have_posts()) : $coupons->the_post();
                    $coupon_type = get_post_meta(get_the_ID(), "coupon_type", true);
                    $coupon_usage = intval(get_post_meta(get_the_ID(), "coupon_usage", true));
                    $coupon_expiry_date = intval(get_post_meta(get_the_ID(), "coupon_expiry_date", true));
                    $coupon_minimum_amount = intval(get_post_meta(get_the_ID(), "coupon_minimum_amount", true));
                    $coupon_amount2 = intval(get_post_meta(get_the_ID(), "coupon_amount", true));
                    $date = new DateTime(date("Y-m-d H:i:s", get_the_time('U')));
                    $currentDate = new DateTime(date("Y-m-d H:i:s"));
                    $diff = $date->diff($currentDate);
                    $day = $diff->format('%d');
                    if($day > $coupon_expiry_date){
                        $errorMsg .= 'Mã giảm giá đã hết hạn';
                    }elseif ($coupon_usage <= 0) {
                        $errorMsg .= 'Mã giảm giá đã quá số lượng sử dụng';
                    }elseif($total_amount_before_cp <= $coupon_minimum_amount){
                        $errorMsg .= 'Giá trị đơn hàng không đạt yêu cầu để sử dụng mã giảm giá';
                    }else{
                        if($coupon_type == 'cp_percent_order') {
                            $coupon_amount = intval(getRequest('total_amount')) * $coupon_amount2 / 100;
                        }elseif($coupon_type=='cp_order'){
                            $coupon_amount = $coupon_amount2;
                        }
//                        update_post_meta(get_the_ID(), 'coupon_usage', $coupon_usage-1);
                    }
                endwhile;
            }else{
                $errorMsg .= $lang['coupon_wrong'];
            }
        }
        if (empty($errorMsg)) {
            $total_amount = $total_amount_before_cp - $coupon_amount;
            Response(json_encode(array(
                'status' => 'success',
                'totalAmount' => number_format($total_amount,0,',','.') . " đ",
                'couponAmount' => $lang['label_discount'] . number_format($coupon_amount,0,',','.') . " đ",
            )));
        } else {
            Response(json_encode(array(
                'status' => 'error',
                'message' => $errorMsg,
            )));
        }
    } else {
        Response(json_encode(array(
            'status' => 'error',
            'message' => $lang['cart_empty'],
        )));
    }

    exit();
}