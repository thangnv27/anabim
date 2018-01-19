<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div id="page" class="hfeed site">	
    
	<?php if ( get_header_image() ) : ?>
	<div id="site-header">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
		</a>
	</div>
	<?php endif; ?>
	
    <div class="head-fix">
    	<div class="box-fix clearfix">
        	<a class="logo-fix" href="http://anabim.com" rel="home">Anabim</a>
            
            <div class="tool-fix fr">
                <form role="search-fix" method="get" id="searchform-fix" class="searchform-fix" action="<?php echo esc_url( home_url( '/' ) ); ?>">                    
                        <input type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" placeholder="Search" />                        
                </form>
                <?php $current_user = wp_get_current_user();
if ( is_user_logged_in() ) {
	echo '<a class="login-fix" href="http://edu.anabim.com/my-account/" rel="login">'. $current_user->user_login .'</a>';
} else {
	echo '<a class="login-fix" href="http://edu.anabim.com/my-account/" rel="login">Đăng nhập</a>
              <a class="register-fix" href="http://edu.anabim.com/my-account/" rel="Register">Đăng kí</a>';
}
?>
            </div> 
        </div>        	
    </div>
    
	<header id="masthead" class="site-header" role="banner">
		<div class="header-main">
			<h1 class="site-title"><a class="" href="http://edu.anabim.com">Education &nbsp;&nbsp;&nbsp; &frasl; </a></h1>
			
			<nav id="primary-navigation" class="site-navigation primary-navigation" role="navigation">
				<button class="menu-toggle"><?php _e( 'Primary Menu', 'twentyfourteen' ); ?></button>
				<a class="screen-reader-text skip-link" href="#content"><?php _e( 'Skip to content', 'twentyfourteen' ); ?></a>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu', 'menu_id' => 'primary-menu' ) ); ?>
			</nav>
		</div>		
	</header><!-- #masthead -->

	<div id="main" class="site-main">