<?php
/*
  Template Name: Sign Up / Sign In
 */
if (is_user_logged_in()) {
    header("location: " . home_url());
    exit;
}

get_header();
$msg = array(
    'warning' => array(),
    'success' => array()
);
// Create an Account
if (getRequestMethod() == 'POST' and $_POST['action_type'] == '7d2abf2d0fa7c3a0c13236910f30bc43') {
    $fname = getRequest('first_name');
    $lname = getRequest('last_name');
    $email = getRequest('email');
    $confirm_email = getRequest('confirm_email');
    $password = getRequest('password');
    $confirm_password = getRequest('confirm_password');
    $dob_month = getRequest('dob_month');
    $dob_day = getRequest('dob_day');
    $dob_year = getRequest('dob_year');
    $user_phone = getRequest('user_phone');
    $user_address1 = getRequest('user_address1');
    $workplace = getRequest('workplace');
    $user_membership = intval(get_option(SHORT_NAME . "_membershipID"));
    
    if (!is_email($email)) {
        array_push($msg['warning'], __("<p>Địa chỉ email không hợp lệ!</p>", SHORT_NAME));
    } elseif (email_exists($email)) {
        array_push($msg['warning'], __("<p>Địa chỉ email này đã tồn tại!</p>", SHORT_NAME));
    } elseif ($confirm_email != $email) {
        array_push($msg['warning'], __("<p>Xác nhận email không chính xác!</p>", SHORT_NAME));
    } elseif (empty ($password)) {
        array_push($msg['warning'], __("<p>Vui lòng nhập mật khẩu!</p>", SHORT_NAME));
    } elseif ($confirm_password != $password) {
        array_push($msg['warning'], __("<p>Xác nhận mật khẩu không chính xác!</p>", SHORT_NAME));
    } elseif (!in_array($dob_month, range(1, 12))) {
        array_push($msg['warning'], __("<p>Tháng sinh không chính xác!</p>", SHORT_NAME));
    } elseif (!in_array($dob_day, range(1, 31))) {
        array_push($msg['warning'], __("<p>Ngày sinh không chính xác!</p>", SHORT_NAME));
    } elseif (!in_array($dob_year, range(1940, date('Y') - 5))) {
        array_push($msg['warning'], __("<p>Năm sinh không chính xác!</p>", SHORT_NAME));
    } else {
        $login = explode("@", $email);
        $sanitized_user_login = sanitize_user($login[0]);
        if (username_exists($sanitized_user_login)) {
            array_push($msg['warning'], __("<p>Tài khoản đã tồn tại, vui lòng nhập email khác!</p>", SHORT_NAME));
        } else {
            $user_id = wp_create_user($sanitized_user_login, $password, $email);
            if (!$user_id || is_wp_error($user_id)) {
                array_push($msg['warning'], "Đăng ký lỗi. Vui lòng liên hệ <a href='mailto:" . get_option('admin_email') . "'>quản trị website</a>!");
            } else {
                array_push($msg['success'], __("Đăng ký thành công!", SHORT_NAME));
                //Set up the Password change nag.
                update_user_option($user_id, 'default_password_nag', true, true);
                update_usermeta($user_id, 'first_name', $fname);
                update_usermeta($user_id, 'last_name', $lname);
                update_usermeta($user_id, 'dob_month', $dob_month);
                update_usermeta($user_id, 'dob_day', $dob_day);
                update_usermeta($user_id, 'dob_year', $dob_year);
                update_usermeta($user_id, 'user_phone', $user_phone);
                update_usermeta($user_id, 'user_address1', $user_address1);
                update_usermeta($user_id, 'workplace', $workplace);
                update_usermeta($user_id, 'user_country', 'Vietnam');
                update_usermeta($user_id, 'user_membership', $user_membership);
                // notification for user
                //wp_new_user_notification($user_id, $password);
                custom_wp_new_user_notification($user_id, $password);
            }
        }
    }
}
// Sign In
if (getRequestMethod() == 'GET') {
    $login = getRequest('login');
    
    if ($login == 'failed') {
        array_push($msg['warning'], __("<p>Đăng nhập lỗi, vui lòng kiểm tra thông tin chính xác và đăng nhập lại!</p>", SHORT_NAME));
    } elseif ($login == 'empty') {
        array_push($msg['warning'], __("<p>Đăng nhập lỗi, vui lòng kiểm tra thông tin chính xác và đăng nhập lại!</p>", SHORT_NAME));
        //array_push($msg['warning'], "<p>Sai tài khoản hoặc mật khẩu!</p>");
    }
}

$login_page = get_page_link(get_option(SHORT_NAME . "_pageLoginID"));
?>
<div class="container-fluid">
    <div id="message" class="mt10 pdl15 pdr15 t_center font14 <?php
        if (!empty($msg['warning'])) {
            echo 'alert alert-warning';
        } elseif (!empty($msg['success'])) {
            echo 'alert alert-success';
        }
        ?>">
        <?php
        if (!empty($msg['warning'])) {
            foreach ($msg['warning'] as $m) {
                echo $m;
            }
        }
        if (!empty($msg['success'])) {
            foreach ($msg['success'] as $m) {
                echo $m;
            }
        }
        ?>
    </div>
    <div class="row pdb30">
        <div class="col-sm-6 user-signup">
            <h2><?php _e('ĐĂNG KÝ THÀNH VIÊN', SHORT_NAME); ?></h2>
            <form action="<?php echo $login_page; ?>" method="post" id="frmSignup">
                <div class="form-group fl mr10" style="min-width: calc(50% - 10px)">
                    <label for="first_name" class="control-label"><?php _e('Tên', SHORT_NAME); ?></label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo (isset($_POST['first_name'])) ? trim($_POST['first_name']) : "" ?>" class="form-control" />
                </div>
                <div class="form-group fl" style="min-width: 50%">
                    <label for="last_name" class="control-label"><?php _e('Họ và đệm', SHORT_NAME); ?></label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo (isset($_POST['last_name'])) ? trim($_POST['last_name']) : "" ?>" class="form-control" />
                </div>
                <div class="clearfix mb10"></div>
                <div class="form-group fl mr10" style="min-width: calc(50% - 10px)">
                    <label for="email" class="control-label"><?php _e('Địa chỉ E-mail', SHORT_NAME); ?></label>
                    <input type="email" id="email" name="email" value="<?php echo (isset($_POST['email'])) ? trim($_POST['email']) : "" ?>" class="form-control" />
                </div>
                <div class="form-group fl" style="min-width: 50%">
                    <label for="confirm_email" class="control-label"><?php _e('Xác nhận địa chỉ E-mail', SHORT_NAME); ?></label>
                    <input type="email" id="confirm_email" name="confirm_email" value="<?php echo (isset($_POST['confirm_email'])) ? trim($_POST['confirm_email']) : "" ?>" class="form-control" />
                </div>
                <div class="clearfix mb10"></div>
                <div class="form-group fl mr10" style="min-width: calc(50% - 10px)">
                    <label for="password" class="control-label"><?php _e('Mật khẩu', SHORT_NAME); ?></label>
                    <input type="password" id="password" name="password" value="" class="form-control" />
                </div>
                <div class="form-group fl" style="min-width: 50%">
                    <label for="confirm_password" class="control-label"><?php _e('Xác nhận mật khẩu', SHORT_NAME); ?></label>
                    <input type="password" id="confirm_password" name="confirm_password" value="" class="form-control" />
                </div>
                <div class="clearfix mb10"></div>
                <div class="form-group">
                    <label class="control-label"><?php _e('Ngày sinh', SHORT_NAME); ?></label>
                    <div>
                        <select name="dob_month" class="form-control fl mr10" style="width: calc(45% - 10px)">
                            <?php
                            $months = month_list();
                            foreach ($months as $key => $value) {
                                if((isset($_POST['dob_month'])) and $_POST['dob_month'] == $i){
                                    echo "<option value=\"$key\" selected>$value</option>";
                                } else {
                                    echo "<option value=\"$key\">$value</option>";
                                }
                            }
                            ?>
                        </select>
                        <select name="dob_day" class="form-control fl mr10" style="width: calc(37% - 10px)">
                            <?php
                            for($i = 1; $i <= 31; $i++) {
                                if((isset($_POST['dob_day'])) and $_POST['dob_day'] == $i){
                                    echo "<option value=\"$i\" selected>$i</option>";
                                } else {
                                    echo "<option value=\"$i\">$i</option>";
                                }
                            }
                            ?>
                        </select>
                        <select name="dob_year" class="form-control fl" style="width: 18%">
                            <?php
                            $year_max = date('Y') - 5;
                            $year_min = $year_max - 71;
                            for($i = $year_max; $i >= $year_min; $i--) {
                                if((isset($_POST['dob_year'])) and $_POST['dob_year'] == $i){
                                    echo "<option value=\"$i\" selected>$i</option>";
                                } else {
                                    echo "<option value=\"$i\">$i</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="clearfix mb10"></div>
                <div class="form-group">
                    <label for="user_address1" class="control-label"><?php _e('Địa chỉ', SHORT_NAME); ?></label>
                    <input type="text" id="user_address1" name="user_address1" value="<?php echo (isset($_POST['user_address1'])) ? trim($_POST['user_address1']) : "" ?>" class="form-control" />
                </div>
                <div class="clearfix mb10"></div>
                <div class="form-group fl mr10" style="min-width: calc(50% - 10px)">
                    <label for="user_phone" class="control-label"><?php _e('Số điện thoại', SHORT_NAME); ?></label>
                    <input type="text" id="user_phone" name="user_phone" value="<?php echo (isset($_POST['user_phone'])) ? trim($_POST['user_phone']) : "" ?>" class="form-control" />
                </div>
                <div class="form-group fl" style="min-width: 50%">
                    <label for="workplace" class="control-label"><?php _e('Nơi công tác', SHORT_NAME); ?></label>
                    <input type="text" id="workplace" name="workplace" value="<?php echo (isset($_POST['workplace'])) ? trim($_POST['workplace']) : "" ?>" class="form-control" />
                </div>
                <div class="clearfix mb10"></div>
                <div class="form-group">
                    <input type="submit" value="<?php _e('Đăng ký', SHORT_NAME); ?>" class="btn btn-warning" />
                    <input type="hidden" name="action_type" value="7d2abf2d0fa7c3a0c13236910f30bc43" />
                </div>
            </form>
        </div>
        <div class="col-sm-6 user-signin">
            <h2><?php _e('ĐĂNG NHẬP TÀI KHOẢN', SHORT_NAME); ?></h2>
            <form action="<?php bloginfo('siteurl'); ?>/wp-login.php" method="post" name="loginform" id="loginform">
                <div class="form-group mb17" style="min-width: calc(100% - 10px)">
                    <label for="user_login" class="control-label"><?php _e('Địa chỉ E-mail', SHORT_NAME); ?></label>
                    <input type="text" id="user_login" name="log" value="<?php echo (isset($_POST['log'])) ? trim($_POST['log']) : "" ?>" class="form-control" />
                </div>
                <div class="clearfix mb10"></div>
                <div class="form-group" style="min-width: calc(100% - 10px)">
                    <label for="user_pass" class="control-label"><?php _e('Mật khẩu', SHORT_NAME); ?></label>
                    <input type="password" id="user_pass" name="pwd" value="" class="form-control" />
                </div>
                <div class="clearfix mb10"></div>
                <div class="form-group">
                    <label for="rememberme"><?php _e("Ghi nhớ", SHORT_NAME); ?>
                        <input name="rememberme" type="checkbox" id="rememberme" value="forever" checked />
                    </label>
                </div>
                <div class="form-group mb10">
                    <input type="submit" value="<?php _e('Đăng nhập', SHORT_NAME); ?>" class="btn btn-warning" />
                    <?php
                    $redirect_to = getRequest('redirect_to');
                    if(empty($redirect_to)){
                        if(isset($_SESSION['redirect_to'])){
                            $redirect_to = $_SESSION['redirect_to'];
                        } else {
                            $redirect_to = home_url();
                        }
                    }
                    ?>
                    <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
                </div>
                <div class="form-group">
                    <a class="open-lostpassword-form" href="<?php echo wp_lostpassword_url(); ?>" title="<?php _e('Bạn quên mật khẩu?', SHORT_NAME); ?>" target="_blank"><?php _e('Bạn quên mật khẩu?', SHORT_NAME); ?></a>
                </div>
            </form>
            
            <?php /* ?>
            <div id="user-lostpassword" style="display: <?php echo (getRequest('login') == "lostpassword") ? "block":"none"; ?>">
                <?php echo do_shortcode("[custom-lost-password-form]"); ?>
            </div>
            <?php */ ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<?php get_footer(); ?>
