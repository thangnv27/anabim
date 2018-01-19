<?php
/*
  Template Name: Ngan Luong Complete
 */
?>
<?php
//Lấy thông tin giao dịch
$transaction_info = $_GET["transaction_info"];
//Lấy mã đơn hàng 
$order_code = $_GET["order_code"];
//Lấy tổng số tiền thanh toán tại ngân lượng 
$price = $_GET["price"];
//Lấy mã giao dịch thanh toán tại ngân lượng
$payment_id = $_GET["payment_id"];
//Lấy loại giao dịch tại ngân lượng (1=thanh toán ngay ,2=thanh toán tạm giữ)
$payment_type = $_GET["payment_type"];
//Lấy thông tin chi tiết về lỗi trong quá trình giao dịch
$error_text = $_GET["error_text"];
//Lấy mã kiểm tra tính hợp lệ của đầu vào 
$secure_code = $_GET["secure_code"];
?>
<?php get_header(); ?>
<div class="container-fluid">
    <div class="row pdt20">
        <!--BEGIN SINGLE POST-->
        <div class="col-sm-9">
            <?php while (have_posts()) : the_post(); ?>
            <h1 class="mt0"><?php _e('Cảm ơn bạn đã đăng ký!', SHORT_NAME) ?></h1>
            <div class="single-content">
                <?php
                $html = "";
                global $nl_checkout;
                $check = $nl_checkout->verifyPaymentUrl($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code);
                if ($check){
                    global $wpdb;
                    $tblOrders = $wpdb->prefix . 'orders';
                    $nl_payment_id = $wpdb->get_var("SELECT nl_payment_id FROM $tblOrders WHERE nl_payment_id = $payment_id");
                    if(!$nl_payment_id){
                        $html .= '<div align="center">' . __('Cám ơn quý khách, quá trình thanh toán đã được hoàn tất. Bạn có thể học ngay bây giờ!', SHORT_NAME) . '</div>';
                        $coupon_amount = isset($_SESSION['coupon_amount']) ? $_SESSION['coupon_amount'] : 0;
                        $coupon_code = isset($_SESSION['coupon_code']) ? $_SESSION['coupon_code'] : 0;
                        $customer_id = isset($_SESSION['CUSTOMER_ID']) ? $_SESSION['CUSTOMER_ID'] : 0;
                        $customer_info = isset($_SESSION['CUSTOMER_INFO']) ? $_SESSION['CUSTOMER_INFO'] : json_encode(array());
                        $products = isset($_SESSION['cart']) ? $_SESSION['cart'] : json_encode(array());
                        $referrer = $_COOKIE['ap_id'];
                        $payment_method = "Thanh toán khi nhận hàng qua Ngân Lượng";
                        switch ($payment_type) {
                            case 1:
                                $payment_method = "Thanh toán ngay qua Ngân Lượng";
                                break;
                            case 2:
                                $payment_method = "Thanh toán tạm giữ qua Ngân Lượng";
                                break;
                            default:
                                break;
                        }

                        foreach ($cart as $product) {
                            $products = json_encode($product);
                            $expire_date = date('Y-m-d', strtotime("+{$product['time']} days"));
                            $result = $wpdb->query($wpdb->prepare("INSERT INTO $tblOrders SET customer_id = %d, customer_info = '%s', 
                                payment_method = '%s', products = '%s', discount = '%s', total_amount = '%s', status = 1, 
                                nl_payment_id = '%s', nl_secure_code = '%s', affiliate_id = '%s', expire_date='%s', coupon_code='%s'",
                                $customer_id, $customer_info, $payment_method, $products, $coupon_amount, $price, $payment_id, $secure_code, 
                                $referrer, $expire_date, $coupon_code));
                        }
                        
                        if($result){
                            // Send invoice to email
                            sendInvoiceToEmail($customer_info, $coupon_amount);
                            // Remove Cart
                            unset($_SESSION['cart']);
                        } else {
                            $html = __("Quá trình thanh toán không thành công bạn vui lòng thực hiện lại", SHORT_NAME);
                        }
                    }
                }else{
                    $html .= __("Quá trình thanh toán không thành công bạn vui lòng thực hiện lại", SHORT_NAME);
                }
                echo $html;
                ?>
            </div>
            <?php endwhile; ?>
        </div>
        
        <?php get_sidebar(); ?>
    </div>
</div>
<?php get_footer(); ?>