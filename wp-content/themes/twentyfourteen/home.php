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
    <script src="http://jwpsrv.com/library/MDberugLEeSDYxJtO5t17w.js"></script>
    <link rel='stylesheet' id='home-css'  href='<?php echo get_template_directory_uri(); ?>/home.css' type='text/css' media='all' />
    
    <link rel='stylesheet' type='text/css' href='<?php echo get_template_directory_uri(); ?>/css/jquery.kwicks.css' />
		<style type='text/css'>
			.kwicks {
				height: 650px;
			}
			.kwicks > li {
				height: 650px;
			}			
		</style>
	<script src='<?php echo get_template_directory_uri(); ?>/js/jquery.kwicks.js' type='text/javascript'></script>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">	
    
	<?php if ( get_header_image() ) : ?>
	<div id="site-header">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
		</a>
	</div>
	<?php endif; ?>
	
    <header class="head-fix">
    	<div class="box-fix clearfix">
        	<a class="logo-fix" href="http://anabim.com" rel="home">ANABIM</a>
            
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
    </header>
    	
    <div class="slide-fix">
    	<div class="doc-fix">
            <div class="header-main p30">
                <h1 class="site-title"><a class="" href="http://edu.anabim.com">Education &nbsp;&nbsp;&nbsp; &frasl; </a></h1>
                
                <nav id="primary-navigation" class="site-navigation primary-navigation" role="navigation">
                    <button class="menu-toggle"><?php _e( 'Primary Menu', 'twentyfourteen' ); ?></button>
                    <a class="screen-reader-text skip-link" href="#content"><?php _e( 'Skip to content', 'twentyfourteen' ); ?></a>
                    <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu', 'menu_id' => 'primary-menu' ) ); ?>
                </nav>
            </div>
            
            <?php if ( is_active_sidebar( 'sidebar-video' ) ) : ?>
            <div id="line-fix" class="line-fix widget-area p30">
                <?php dynamic_sidebar( 'sidebar-video' ); ?>
            </div>
            <?php endif; ?>
        </div>
        
        
        <div class="clip-fix">
            <div id='playerom0ZzJN3'></div>
		<script type='text/javascript'>
		    jwplayer('playerom0ZzJN3').setup({
		         file: 'https://www.youtube.com/watch?v=gPm5glhwW9A',
        image: '//www.longtailvideo.com/content/images/jw-player/lWMJeVvV-876.jpg',
        width: '100%',
        aspectratio: '16:9',
        controls: 'false',
        mute: 'true',
        autostart: 'true',
        repeat: 'true'
		    });
		</script>
        </div>
    </div>
    
	<div id="main" class="site-main">
        
    		<ul class="kwicks-horizontal clearfix kwicks">	
        	<?php $i=0; $k=1; foreach (get_categories(array('parent' => 6,'orderby' => 'slug', 'hide_empty' => 0)) as $cat) : ?>            	
					<?php $i++; ?>        	
                    <li id='panel-<?php echo $i; ?>' class='panel-<?php echo $i; ?>'>
                        <span class="po-fix">
                        	<span class="title-slide-fix fl">
                                <a href="<?php echo get_category_link($cat->term_id); ?>?temp=slide"><?php echo $cat->cat_name; ?></a>
                                <br /><?php echo $cat->description; ?>
                            </span>
                            
                            <span class="content-slide-fix fl">
								<?php foreach (get_categories(array('parent' => $cat->term_id,'orderby' => 'slug', 'hide_empty' => 0)) as $cat_test) : ?>							
                                	<a href="<?php echo get_category_link($cat->term_id); ?>?temp=slide"><?php echo $cat_test->cat_name; ?></a>
                                <?php endforeach; ?>
                            </span>
                            
                            <style type='text/css'>
                                .panel-<?php echo $i; ?>{
                                    background:#efefef left bottom no-repeat url(<?php echo z_taxonomy_image_url($cat->term_id); ?>)
                                }
                            </style>
                         </span>             
                    </li>
                    
                	<?php if($k%4==0) echo '</ul><ul class="kwicks-horizontal clearfix kwicks">'; ?>
                    
                <?php $k++; ?>                                                                             
            <?php endforeach; ?>	
            </ul>
            <div class="contact-fix">
            	<div class="contact-fix-bg clerfix">
					<?php if ( is_active_sidebar( 'sidebar-contact-content' ) ) : ?>
                        <div id="contact-content-fix" class="contact-content-fix widget-area fl">
                        	<div class="p50">
	                            <?php dynamic_sidebar( 'sidebar-contact-content' ); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ( is_active_sidebar( 'sidebar-contact-form' ) ) : ?>
                        <div id="contact-form-fix" class="contact-form-fix widget-area fl">
                        	<div class="p50">
                            	<?php dynamic_sidebar( 'sidebar-contact-form' ); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
                            
    </div><!-- #main -->
		<footer id="colophon" class="site-footer" role="contentinfo">

			<?php get_sidebar( 'footer' ); ?>

			<div class="site-info">
				<?php do_action( 'twentyfourteen_credits' ); ?>
				<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'twentyfourteen' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'twentyfourteen' ), 'WordPress' ); ?></a>
			</div><!-- .site-info -->
		</footer><!-- #colophon -->
	</div><!-- #page -->

	<?php wp_footer(); ?> 
    <script type='text/javascript'>
		jQuery(function() {
			jQuery('.kwicks').kwicks({
				maxSize: '80%',
				behavior: 'menu',
				delayMouseIn: 5000000
			});
		});
	</script>    
</body>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-58878923-2', 'auto');
  ga('send', 'pageview');

</script>
</html>
<head>
<script>(function() {
var _fbq = window._fbq || (window._fbq = []);
if (!_fbq.loaded) {
var fbds = document.createElement('script');
fbds.async = true;
fbds.src = '//connect.facebook.net/en_US/fbds.js';
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(fbds, s);
_fbq.loaded = true;
}
_fbq.push(['addPixelId', '946846935375221']);
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', 'PixelInitialized', {}]);
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?

id=946846935375221&amp;ev=PixelInitialized" /></noscript>
</head>
<head>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-58878923-2', 'auto');
  ga('send', 'pageview');

</script>
</head>