<?php
/*
  Template Name: Membership Checkout
 */
if (!is_user_logged_in()) {
    wp_redirect(get_page_link(get_option(SHORT_NAME . "_pageLoginID")) . '?redirect_to=' . urlencode(getCurrentRquestUrl()));
    exit;
} elseif($_SESSION['membershipCart'] == null or empty($_SESSION['membershipCart'])){
    wp_redirect(get_page_link(get_option(SHORT_NAME . "_membershipUpgradeID")));
    exit;
}

get_header();
global $current_user;
get_currentuserinfo();
$cities = vn_city_list();
?>
<section id="main" class="content-area">
    <div class="container-fluid">
        <?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumbs">','</div>'); } ?>

        <div id="content" role="main" class="site-content">
            <!--BEGIN PAGE CHECKOUT-->
            <form action="" method="post" id="frmOrder">
                <input type="hidden" name="action" value="membershipOrderComplete" />
                <input type="hidden" name="locale" value="<?php echo getLocale(); ?>" />
                <div class="row">
                    <div class="col-md-7">
                        <div class="row cart_info">
                            <div class="col-md-6">
                                <div class="customer">
                                    <div class="title"><?php _e('Thông tin khách hàng', SHORT_NAME) ?></div>
                                    <div class="form-group">
                                        <input name="cName" type="text" placeholder="<?php _e('Họ và tên', SHORT_NAME) ?>" class="form-control" value="<?php echo (is_user_logged_in()) ? $current_user->display_name : ""; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <input name="cEmail" type="text" placeholder="<?php _e('Địa chỉ email', SHORT_NAME) ?>" class="form-control" value="<?php echo (is_user_logged_in()) ? $current_user->user_email : ""; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <input name="cPhone" type="text" placeholder="<?php _e('Số điện thoại', SHORT_NAME) ?>" class="form-control" value="<?php echo (is_user_logged_in()) ? esc_attr(get_the_author_meta('user_phone', $current_user->ID)) : ""; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <input name="cAddress" type="text" placeholder="<?php _e('Địa chỉ', SHORT_NAME) ?>" class="form-control" value="<?php echo (is_user_logged_in()) ? esc_attr(get_the_author_meta('user_address1', $current_user->ID)) : ""; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <input name="cWorkplace" type="text" placeholder="<?php _e('Nơi công tác', SHORT_NAME) ?>" class="form-control" value="<?php echo (is_user_logged_in()) ? esc_attr(get_the_author_meta('workplace', $current_user->ID)) : ""; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <select name="cCity" class="form-control">
                                            <?php
                                            foreach ($cities as $city) {
                                                if (esc_attr(get_the_author_meta('user_city', $current_user->ID)) == $city) {
                                                    echo '<option value="' .$city .'" selected="selected">' . $city . '</option>';
                                                } else {
                                                    echo "<option value=\"$city\">$city</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="payWay">
                                    <div class="title"><?php _e('Hình thức thanh toán', SHORT_NAME) ?></div>
                                    <div class="PaymentMethod">
                                        <div class="PaymentMethod_Name">
                                            <input type="radio" name="payment_method" value="Thanh toán trực tiếp" id="ck1" checked>
                                            <label for="ck1"><?php _e('Thanh toán trực tiếp', SHORT_NAME) ?></label>
                                        </div>
                                        <div class="PaymentMethod_Info" id="method1">
                                            <?php echo nl2br(stripslashes(get_option('payment_atOffice'))); ?>
                                        </div>
                                    </div>
                                    <div class="PaymentMethod">
                                        <div class="PaymentMethod_Name">
                                            <input type="radio" name="payment_method" value="Chuyển khoản qua ATM/Internet Banking" id="ck2">
                                            <label for="ck2"><?php _e('Chuyển khoản qua ATM/Internet Banking', SHORT_NAME) ?></label>
                                        </div>
                                        <div class="PaymentMethod_Info" id="method2" style="display: none;">
                                            <?php echo nl2br(stripslashes(get_option('payment_atm'))); ?>
                                        </div>
                                    </div>
                                    <div class="PaymentMethod">
                                        <div class="PaymentMethod_Name">
                                            <input type="radio" name="payment_method" value="Thanh toán qua Ngân Lượng" id="ck3">
                                            <label for="ck3"><?php _e('Thanh toán qua Ngân Lượng', SHORT_NAME) ?></label>
                                        </div>
                                        <div class="PaymentMethod_Info" id="method2" style="display: none;">
                                            <?php echo nl2br(stripslashes(get_option('payment_atNganLuong'))); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr style="height: 38px;">
                                        <th><?php _e('Gói tài khoản', SHORT_NAME) ?></th>
                                        <th><?php _e('Thời hạn', SHORT_NAME) ?></th>
                                        <th><?php _e('Thành tiền', SHORT_NAME) ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(isset($_SESSION['membershipCart']) and !empty($_SESSION['membershipCart'])): 
                                    $cart = $_SESSION['membershipCart'];
                                    ?>
                                    <tr>
                                        <td><?php echo $cart['title']; ?></td>
                                        <td><?php echo $cart['time'] . __(' tháng', SHORT_NAME); ?></td>
                                        <td><?php echo number_format($cart['amount'],0,',','.'); ?> đ</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="cart-price">
                                <span><?php _e('Tổng tiền', SHORT_NAME) ?>:</span>
                                <span class="total_price"><?php echo number_format($cart['amount'], 0, ',', '.'); ?> đ
                            </div>
                        </div>
                        <div class="btnCart">
                            <a href="javascript://" id="btnNganLuong" style="display: none"><?php _e('Thanh toán ngân lượng', SHORT_NAME) ?></a>
                            <a href="javascript://" id="btnMuaHang"><?php _e('Hoàn tất', SHORT_NAME) ?></a>
                        </div>
                    </div>
                </div>
            </form>
            <!--END PAGE CHECKOUT-->
            <script type="text/javascript">
                jQuery(document).ready(function($){
                    if($("#ck2").is(":checked")){
                        $("#method1").hide();
                        $("#method2").show();
                        $("#method3").hide();
                        $("#btnNganLuong").hide();
                        $("#btnMuaHang").show();
                    }
                    if($("#ck3").is(":checked")){
                        $("#method1").hide();
                        $("#method2").hide();
                        $("#method3").show();
                        $("#btnNganLuong").show();
                        $("#btnMuaHang").hide();
                    }

                    /* switch payment method */
                    $("#ck1").click(function(){
                        $("#method1").show();
                        $("#method2").hide();
                        $("#method3").hide();
                        $("#btnNganLuong").hide();
                        $("#btnMuaHang").show();
                    });
                    $("#ck2").click(function(){
                        $("#method1").hide();
                        $("#method2").show();
                        $("#method3").hide();
                        $("#btnNganLuong").hide();
                        $("#btnMuaHang").show();
                    });
                    $("#ck3").click(function(){
                        $("#method1").hide();
                        $("#method2").hide();
                        $("#method3").show();
                        $("#btnNganLuong").show();
                        $("#btnMuaHang").hide();
                    });

                    // Complete order
                    $("#btnMuaHang").click(function(){
                        if(validate_info() && $("#btnMuaHang").is(":visible")){
                            $("#frmOrder input[name=action]").val('membershipOrderComplete');
                            AjaxCart.membershipOrderComplete($("#frmOrder").serialize());
                        }else{
                            displayBarNotification(true, "nWarning", "<?php _e('Vui lòng nhập đầy đủ thông tin.', SHORT_NAME) ?>");
                        }
                    });
                    $("#btnNganLuong").click(function(){
                        if(validate_info() && $("#btnNganLuong").is(":visible")){
                            $("#frmOrder input[name=action]").val('membershipOrderNganLuong');
                            AjaxCart.membershipOrderNganLuong($("#frmOrder").serialize());
                        }else{
                            displayBarNotification(true, "nWarning", "<?php _e('Vui lòng nhập đầy đủ thông tin.', SHORT_NAME) ?>");
                        }
                        return false;
                    });

                    function validate_info(){
                        var valid = true;
                        $(".customer input[type=text], .customer select").each(function(){
                            if($(this).val().length == 0){
                                $(this).parent().addClass('has-error');
                                valid = false;
                            }else{
                                $(this).parent().removeClass('has-error');
                            }
                        });
                        return valid;
                    }
                });
            </script>
        </div>
    </div>
</section><!-- #main -->
<?php get_footer(); ?>