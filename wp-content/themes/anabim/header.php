<?php 
include_once 'libs/bbit-compress.php';
//$IP = $_SERVER['REMOTE_ADDR'];
//if(!in_array($IP, array('117.1.184.191','66.249.82.170','66.249.82.166'))){
//    wp_redirect("http://edu.anabim.com/coming-soon/");
//    exit;
//}
?><!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
    <!--<meta http-equiv="Cache-control" content="no-store; no-cache"/>-->
    <!--<meta http-equiv="Pragma" content="no-cache"/>-->
    <!--<meta http-equiv="Expires" content="0"/>-->
    <meta http-equiv="Cache-control" content="max-age=86400"/>
    <meta http-equiv="Pragma" content="max-age"/>
    <meta http-equiv="Expires" content="86400"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
    <title><?php wp_title('|', true, 'right'); ?></title>
    <meta name="author" content="anabim.com" />
    <meta name="robots" content="index, follow" /> 
    <meta name="googlebot" content="index, follow" />
    <meta name="bingbot" content="index, follow" />
    <meta name="geo.region" content="VN" />
    <meta name="geo.position" content="14.058324;108.277199" />
    <meta name="ICBM" content="14.058324, 108.277199" />
    <meta property="fb:app_id" content="<?php echo get_option(SHORT_NAME . "_appFBID"); ?>" />
    
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <?php if(is_home() or is_front_page()): ?>
    <meta name="keywords" content="<?php echo get_option('keywords_meta') ?>" />
    <?php 
    endif;
    
    if(get_option(SHORT_NAME . "_googlePlusURL")): 
    ?>
    <link rel="publisher" href="<?php echo get_option(SHORT_NAME . "_googlePlusURL"); ?>"/>
    <?php endif; ?>
    <link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />        
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        var siteUrl = "<?php bloginfo('siteurl'); ?>";
        var themeUrl = "<?php bloginfo('stylesheet_directory'); ?>";
        var is_home = <?php echo is_home() ? 'true' : 'false'; ?>;
        var is_product = <?php echo is_singular('product') ? 'true' : 'false'; ?>;
        var is_mobile = <?php echo wp_is_mobile() ? 'true' : 'false'; ?>;
        var is_user_logged_in = <?php echo is_user_logged_in() ? 'true' : 'false'; ?>;
        var no_image_src = themeUrl + "/images/no_image_available.jpg";
        var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';
        var cartUrl = "<?php echo get_page_link(get_option(SHORT_NAME . "_pageCartID")); ?>";
        var checkoutUrl = "<?php echo get_page_link(get_option(SHORT_NAME . "_pageCheckoutID")); ?>";
        var membershipCheckoutUrl = "<?php echo get_page_link(get_option(SHORT_NAME . "_membershipCheckoutID")); ?>";
        var lang = "<?php echo getLocale(); ?>";
        var loginUrl = "<?php echo get_page_link(get_option(SHORT_NAME . "_pageLoginID")); ?>?redirect_to=<?php echo urlencode(getCurrentRquestUrl()); ?>";
    </script>
    <!--<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/modernizr.js"></script>-->
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <div id="ajax_loading" style="display: none;z-index: 99999" class="ajax-loading-block-window">
        <div class="loading-image"></div>
    </div>
    <!--Alert Message-->
    <div id="nNote" class="nNote" style="display: none;"></div>
    <!--END: Alert Message-->
    
    <!--BEGIN HEADER-->
    <header class="head-fix">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 logo" itemtype="http://schema.org/Organization" itemscope="itemscope">
                    <a rel="home" title="<?php bloginfo("name"); ?>" href="<?php echo home_url(); ?>" itemprop="url">
                        <img src="<?php echo get_option("sitelogo"); ?>" alt="<?php bloginfo("name"); ?>" itemprop="logo" />
                    </a>
                </div>
                <div class="col-sm-9 shortcuts-container">
                    <div class="searchform">
                        <form action="<?php echo home_url(); ?>" method="get">
                            <input type="text" placeholder="<?php _e('Tìm kiếm', SHORT_NAME) ?>" name="s" value="" />
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                        </form>
                    </div>
                    <div class="login-fix">
                        <?php 
                        if(is_user_logged_in()): 
                            global $current_user;
                        ?>
                        <span class="title"><?php echo $current_user->display_name; ?></span>
                        <ul>
                            <li><a href="<?php echo admin_url('profile.php') ?>" title="<?php _e('Chỉnh sửa hồ sơ', SHORT_NAME) ?>"><?php _e('Chỉnh sửa hồ sơ', SHORT_NAME) ?></a></li>
                            <li><a href="<?php echo get_page_link(get_option(SHORT_NAME . "_membershipUpgradeID")) ?>" title="<?php _e('Memberships', SHORT_NAME) ?>"><?php _e('Memberships', SHORT_NAME) ?></a></li>
                            <li><a href="<?php echo get_page_link(get_option(SHORT_NAME . "_pageHistoryOrder")); ?>"><?php _e('Khoá học của tôi', SHORT_NAME) ?></a></li>
                            <li><a rel="logout" href="<?php echo wp_logout_url(getCurrentRquestUrl()); ?>" onclick="return confirm('<?php _e('Bạn có chắc chắn muốn thoát?', SHORT_NAME) ?>');"><?php _e('Đăng xuất', SHORT_NAME) ?></a></li>
                        </ul>
                        <?php else: ?>
                        <span class="title"><?php _e('ĐĂNG NHẬP', SHORT_NAME) ?></span>
                        <ul>
                            <li><a rel="login" href="<?php echo get_page_link(get_option(SHORT_NAME . "_pageLoginID")); ?>?redirect_to=<?php echo getCurrentRquestUrl(); ?>"><?php _e('Đăng nhập', SHORT_NAME) ?></a></li>
                            <li><a rel="register" href="<?php echo get_page_link(get_option(SHORT_NAME . "_pageLoginID")); ?>?redirect_to=<?php echo getCurrentRquestUrl(); ?>"><?php _e('Đăng ký tài khoản', SHORT_NAME) ?></a></li>
                            <li>
                                <span><?php _e('Đăng nhập bằng tài khoản', SHORT_NAME) ?> </span>
                                <a href="<?php bloginfo('siteurl'); ?>/wp-login.php?loginFacebook=1&redirect=<?php echo getCurrentRquestUrl(); ?>" 
                                   title="Facebook" class="icon-fb"><i class="fa fa-facebook-official"></i></a>
                                <a href="<?php bloginfo('siteurl'); ?>/wp-login.php?loginGoogle=1&redirect=<?php echo getCurrentRquestUrl(); ?>" 
                                   title="Google" class="icon-gg"><i class="fa fa-google"></i></a>
                            </li>
                        </ul>
                        <?php endif; ?>
                    </div>
                    <div class="lang-fix">
                        <?php do_action('icl_language_selector'); ?>
                    </div>
                    <div class="menu-fix">
                        <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span> MENU
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--END HEADER-->
    
    <!--BEGIN NAV-->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="row">
                <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <?php
                wp_nav_menu(array(
                    'container' => '',
                    'theme_location' => 'primary',
                    'menu_class' => 'nav navbar-nav',
                    'menu_id' => 'main-nav',
                ));
                ?>
            </div>
            </div>
        </div>
    </nav>
    <!--END NAV-->
    
    <!--MENU MOBILE-->
    <section class="menu-mobile" style="display: none">
        <div style="text-align: right">
            <span class="btn-close-menu"></span>
        </div>
        <?php if(is_user_logged_in()): ?>
        <ul class="mnleft mnleft-acc">
            <li><a href="<?php echo admin_url('profile.php') ?>" title="<?php _e('Chỉnh sửa hồ sơ', SHORT_NAME) ?>"><?php _e('Chỉnh sửa hồ sơ', SHORT_NAME) ?></a></li>
            <li><a href="<?php echo get_page_link(get_option(SHORT_NAME . "_membershipUpgradeID")) ?>" title="<?php _e('Memberships', SHORT_NAME) ?>"><?php _e('Memberships', SHORT_NAME) ?></a></li>
            <li><a href="<?php echo get_page_link(get_option(SHORT_NAME . "_pageHistoryOrder")); ?>"><?php _e('Khoá học của tôi', SHORT_NAME) ?></a></li>
            <li><a rel="logout" href="<?php echo wp_logout_url(getCurrentRquestUrl()); ?>" onclick="return confirm('<?php _e('Bạn có chắc chắn muốn thoát?', SHORT_NAME) ?>');"><?php _e('Đăng xuất', SHORT_NAME) ?></a></li>
        </ul>
        <hr style="margin: 8px 15px;border-color: #999">
        <?php else: ?>
        <ul class="mnleft mnleft-acc">
            <li><a rel="login" href="<?php echo get_page_link(get_option(SHORT_NAME . "_pageLoginID")); ?>"><?php _e('Đăng nhập', SHORT_NAME) ?></a></li>
            <li><a rel="register" href="<?php echo get_page_link(get_option(SHORT_NAME . "_pageLoginID")); ?>"><?php _e('Đăng ký tài khoản', SHORT_NAME) ?></a></li>
            <li>
                <span><?php _e('Đăng nhập bằng tài khoản', SHORT_NAME) ?>:</span><br />
                <a href="<?php bloginfo('siteurl'); ?>/wp-login.php?loginFacebook=1&redirect=<?php echo getCurrentRquestUrl(); ?>" 
                   title="Facebook" class="icon-fb"><i class="fa fa-facebook-official"></i></a>
                <a href="<?php bloginfo('siteurl'); ?>/wp-login.php?loginGoogle=1&redirect=<?php echo getCurrentRquestUrl(); ?>" 
                   title="Google" class="icon-gg"><i class="fa fa-google"></i></a>
            </li>
        </ul>
        <hr style="margin: 8px 15px;border-color: #999">
        <?php
        endif;
        
        wp_nav_menu(array(
            'container' => '',
            'theme_location' => 'mobile',
            'menu_class' => 'mnleft',
        ));
        ?> 
    </section>
    <!--/MENU MOBILE-->