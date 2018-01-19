<?php
/*
  Template Name: Membership - Ngan Luong Complete
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
                global $wpdb, $nl_checkout, $current_user;
                $check = $nl_checkout->verifyPaymentUrl($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code);
                if ($check){
                    get_currentuserinfo();
                    $cart = $_SESSION['membershipCart'];
                    $customer_info = $_SESSION['CUSTOMER_INFO'];
                    $expire_date = date('Y-m-d', strtotime("+{$cart['time']} months"));
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
                    
                    $tblMemberships = $wpdb->prefix . 'memberships';
                    $result = $wpdb->query($wpdb->prepare("INSERT INTO $tblMemberships SET user_id = %d, user_info = '%s', 
                        payment_method = '%s', package_id = %d, package_name = '%s', price = '%s', time = %d, 
                        expire_date='%s', total_amount = '%s', nl_payment_id = '%s', nl_secure_code = '%s'", 
                        $current_user->ID, json_encode($customer_info), $payment_method, $cart['id'], $cart['title'], 
                        $cart['price'], $cart['time'], $expire_date, $cart['amount'], $payment_id, $secure_code));
                    if($result){
                        update_usermeta($current_user->ID, 'user_membership', $cart['id']);
                        update_usermeta($current_user->ID, 'membership_expire', $expire_date);
                        sendMembershipInvoiceToEmail($customer_info, $payment_method, $expire_date);
                        $html .= '<div align="center">' . __('Cám ơn quý khách, quá trình thanh toán đã được hoàn tất. Bạn có thể học ngay bây giờ!', SHORT_NAME) . '</div>';
                        unset($_SESSION['membershipCart']);
                    } else {
                        $html = __("Quá trình thanh toán không thành công bạn vui lòng thực hiện lại", SHORT_NAME);
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