<?php
/* ----------------------------------------------------------------------------------- */
# adds the plugin initalization scripts that add styles and functions
/* ----------------------------------------------------------------------------------- */
if(!current_theme_supports('deactivate_layerslider')) require_once( "config-layerslider/config.php" );//layerslider plugin

######## BLOCK CODE NAY LUON O TREN VA KHONG DUOC XOA ##########################
include 'includes/config.php';
include 'libs/HttpFoundation/Request.php';
include 'libs/HttpFoundation/Response.php';
include 'libs/HttpFoundation/Session.php';
include 'libs/custom.php';
include 'libs/common-scripts.php';
include 'libs/meta-box.php';
include 'libs/theme_functions.php';
include 'libs/theme_settings.php';
include 'libs/template-tags.php';
include 'libs/nganluong.php';
######## END: BLOCK CODE NAY LUON O TREN VA KHONG DUOC XOA ##########################
include 'includes/membership.php';
include 'includes/custom-user.php';
include 'includes/custom-post.php';
include 'includes/testimonial.php';
include 'includes/quote.php';
include 'includes/staff.php';
include 'includes/photo.php';
include 'includes/project.php';
include 'includes/product.php';
include 'includes/ppocart.php';
include 'ajax.php';

if (is_admin()) {
    $basename_excludes = array('plugin-editor.php', 'themes.php', 'theme-editor.php', 
        'tools.php', 'import.php', 'export.php');
    if (in_array($basename, $basename_excludes)) {
        wp_redirect(admin_url());
    }
    
    include 'includes/orders.php';
    include 'includes/memberships.php';
    include 'includes/coupon.php';
    
    // Add filter
    add_filter( 'enter_title_here', 'ppo_change_title_text' );
    
    // Add action
    add_action('admin_menu', 'custom_remove_menu_pages');
    add_action('admin_menu', 'remove_menu_editor', 102);
}

/**
 * Remove admin menu
 */
function custom_remove_menu_pages() {
    remove_menu_page('edit-comments.php');
    // remove_menu_page('plugins.php');
    remove_menu_page('tools.php');
}

function remove_menu_editor() {
    remove_submenu_page('themes.php', 'themes.php');
    remove_submenu_page('themes.php', 'theme-editor.php');
    remove_submenu_page('plugins.php', 'plugin-editor.php');
    remove_submenu_page('options-general.php', 'options-writing.php');
    remove_submenu_page('options-general.php', 'options-discussion.php');
    remove_submenu_page('options-general.php', 'options-media.php');
}

/* ----------------------------------------------------------------------------------- */
# Setup Theme
/* ----------------------------------------------------------------------------------- */
if (!function_exists("ppo_theme_setup")) {

    function ppo_theme_setup() {
        /*
	 * Make theme available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Fourteen, use a find and
	 * replace to change 'twentyfourteen' to the name of your theme in all
	 * template files.
	 */
	load_theme_textdomain( SHORT_NAME, get_template_directory() . '/languages' );
        
        ## Enable Links Manager (WP 3.5 or higher)
        //add_filter('pre_option_link_manager_enabled', '__return_true');
        
        // This theme styles the visual editor to resemble the theme style.
	add_editor_style( array( 'css/editor-style.css', get_stylesheet_directory_uri(), '/genericons/genericons.css' ) );

	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

        ## Post Thumbnails
        if (function_exists('add_theme_support')) {
            add_theme_support('post-thumbnails');
        }
//        add_image_size('200x300', 200, 300, true);
//        add_image_size('80x120', 80, 120, true);
        
        ## Post formats
//        add_theme_support('post-formats', array('link', 'quote', 'gallery', 'video', 'image', 'audio', 'aside'));
        add_theme_support('post-formats', array('gallery', 'video', 'audio'));
        
        ## Add support for featured content.
//	add_theme_support( 'featured-content', array(
//            'featured_content_filter' => 'ppo_get_featured_posts',
//            'max_posts' => 6,
//	));

        ## Register menu location
        register_nav_menus(array(
            'primary' => 'Primary Location',
            'mobile' => 'Mobile Location',
        ));
        
        // Front-end remove admin bar
        if (!current_user_can('administrator') && !current_user_can('editor') && !is_admin()) {
            show_admin_bar(false);
        }
    }

}

add_action('after_setup_theme', 'ppo_theme_setup');

/**
 * Enqueue scripts and styles for the front end.
 */
function ppo_enqueue_scripts() {
    // Add bbpress stylesheet
    wp_enqueue_style( SHORT_NAME . 'bbpress', get_template_directory_uri() . '/css/custom-bbpress.css', array(), FALSE );
    
    // Add Bootstrap stylesheet
    wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '3.3.6' );
    
    // Add font awesome
    wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '4.5.0' );
    
    // Add Genericons font, used in the main stylesheet.
    wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.0.3' );
    
    // Add kwicks stylesheet
    if(is_home() or is_front_page()){
        wp_enqueue_style( 'kwicks', get_template_directory_uri() . '/css/jquery.kwicks.css', array(), '2.2.1' );
        wp_enqueue_style( 'home', get_template_directory_uri() . '/css/home.css' );
    }
    if(is_archive()){
        wp_enqueue_style( 'kwicks', get_template_directory_uri() . '/css/jquery.kwicks.css', array(), '2.2.1' );
    }
    
    // Add colorbox stylesheet
    wp_enqueue_style( 'colorbox', get_template_directory_uri() . '/colorbox/colorbox.css', array(), '1.4.33' );
    
    // Add Bootstrap stylesheet
//    wp_enqueue_style( 'jquery-ui', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css', array(), '1.11.4' );

    // Load our main stylesheet.
    wp_enqueue_style( SHORT_NAME . '-style', get_stylesheet_uri() );
    
    // Add wordpress default stylesheet
    wp_enqueue_style( 'wp-default', get_template_directory_uri() . '/css/wp-default.css', array(), FALSE );
    
    // Add common stylesheet
    wp_enqueue_style( SHORT_NAME . '-common', get_template_directory_uri() . '/css/common.css', array(), FALSE );

    // Load the Internet Explorer specific stylesheet.
    wp_enqueue_style( SHORT_NAME . '-ie', get_template_directory_uri() . '/css/ie.css', array( SHORT_NAME . '-style' ), '20131205' );
    wp_style_add_data( SHORT_NAME . '-ie', 'conditional', 'lt IE 9' );

    if ( is_singular() && comments_open() ) {
        // Add comment stylesheet
        wp_enqueue_style( 'comment', get_template_directory_uri() . '/css/comment.css', array(), FALSE );
        
        wp_enqueue_script( 'comment-reply' );
    }

    // Add kwicks script
//    if(is_home() or is_front_page()){
//        wp_enqueue_script( SHORT_NAME . '-kwicks', get_template_directory_uri() . '/js/jquery.kwicks.js', array( 'jquery' ), '2.2.1', true );
//    }
//    wp_enqueue_script( SHORT_NAME . '-script', get_template_directory_uri() . '/js/app.js', array( 'jquery' ), '20150315', true );
    if(!is_admin()){
        wp_enqueue_script('ajax.js', get_bloginfo('template_directory') . "/js/ajax.js", array('jquery'), false, true);
    }
}

add_action( 'wp_enqueue_scripts', 'ppo_enqueue_scripts' );

/* ----------------------------------------------------------------------------------- */
# Widgets init
/* ----------------------------------------------------------------------------------- */
if (!function_exists("ppo_widgets_init")) {

    // Register Sidebar
    function ppo_widgets_init() {
        register_sidebar(array(
            'id' => 'sidebar',
            'name' => __('Sidebar', SHORT_NAME),
            'before_widget' => '<section class="widget">',
            'after_widget' => '</section>',
            'before_title' => '<div class="widget-title">',
            'after_title' => '</div>',
        ));
        register_sidebar(array(
            'id' => 'footer1',
            'name' => __('Sidebar Footer 1', SHORT_NAME),
            'before_widget' => '<div class="widget">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        register_sidebar(array(
            'id' => 'footer2',
            'name' => __('Sidebar Footer 2', SHORT_NAME),
            'before_widget' => '<div class="widget">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        register_sidebar(array(
            'id' => 'footer3',
            'name' => __('Sidebar Footer 3', SHORT_NAME),
            'before_widget' => '<div class="widget">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        register_sidebar(array(
            'id' => 'footer4',
            'name' => __('Sidebar Footer 4', SHORT_NAME),
            'before_widget' => '<div class="widget">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
    }

    // Register widgets
//    register_widget('Ads_Widget');
}

add_action('widgets_init', 'ppo_widgets_init');

/* ----------------------------------------------------------------------------------- */
# Unset size of post thumbnails
/* ----------------------------------------------------------------------------------- */

function ppo_filter_image_sizes($sizes) {
    unset($sizes['thumbnail']);
    unset($sizes['medium']);
    unset($sizes['large']);

    return $sizes;
}

add_filter('intermediate_image_sizes_advanced', 'ppo_filter_image_sizes');
/*
  function ppo_custom_image_sizes($sizes){
  $myimgsizes = array(
  "image-in-post" => __("Image in Post"),
  "full" => __("Original size")
  );

  return $myimgsizes;
  }

  add_filter('image_size_names_choose', 'ppo_custom_image_sizes');
 */

//PPO Feed all post type

function ppo_feed_request($qv) {
    if (isset($qv['feed']))
        $qv['post_type'] = get_post_types();
    return $qv;
}

add_filter('request', 'ppo_feed_request');

function getLocale() {
    $locale = "vn";
    if (get_query_var("lang") != null) {
        $locale = get_query_var("lang");
    } else if (function_exists("qtrans_getLanguage")) {
        $locale = qtrans_getLanguage();
    } else if (defined('ICL_LANGUAGE_CODE')) {
        $locale = ICL_LANGUAGE_CODE;
    }
    if ($locale == "vi") {
        $locale = "vn";
    }
    return $locale;
}

/**
 * Override wp title for add new/edit a post
 * 
 * @param string $title
 * @return string
 */
function ppo_change_title_text( $title ){
    $screen = get_current_screen();
 
    switch ($screen->post_type) {
        case 'quote':
            $title = __('Nhập tên nguồn trích dẫn', SHORT_NAME);
            break;
        case 'coupon':
            $title = __('Nhập tên mã giảm giá', SHORT_NAME);
            break;
        case 'staff':
            $title = __('Nhập tên thành viên', SHORT_NAME);
            break;
        case 'project':
            $title = __('Nhập tên dự án', SHORT_NAME);
            break;
        case 'testimonial':
            $title = __('Nhập tên học viên', SHORT_NAME);
            break;
        case 'membership':
            $title = __('Nhập tên gói', SHORT_NAME);
            break;
        default:
            break;
    }
 
     return $title;
}
/* ----------------------------------------------------------------------------------- */
# Custom Login / Logout
/* ----------------------------------------------------------------------------------- */
function change_username_wps_text($text) {
    if (in_array($GLOBALS['pagenow'], array('wp-login.php'))) {
        if ($text == 'Username') {
            $text = 'Username or Email';
        }
    }
    return $text;
}

add_filter('gettext', 'change_username_wps_text');

function login_failed() {
    $login_page = get_page_link(get_option(SHORT_NAME . "_pageLoginID"));
    wp_redirect($login_page . '?login=failed');
    exit;
}

add_action('wp_login_failed', 'login_failed');

function verify_username_password($user, $username, $password) {
    $login_page = get_page_link(get_option(SHORT_NAME . "_pageLoginID"));
    if ($username == "" || $password == "") {
        wp_redirect($login_page . "?login=empty".$username);
        exit;
    }
}

add_filter('authenticate', 'verify_username_password', 1, 3);

// remove the default filter
remove_filter('authenticate', 'wp_authenticate_username_password', 20, 3);

// add custom filter
add_filter('authenticate', 'ppo_authenticate_username_password', 20, 3);

function ppo_authenticate_username_password($user, $username, $password) {

    // If an email address is entered in the username box, 
    // then look up the matching username and authenticate as per normal, using that.
    if(is_email($username)){
        if (!empty($username))
            $user = get_user_by('email', $username);

        if (isset($user->user_login, $user))
            $username = $user->user_login;
    }

    // using the username found when looking up via email
    return wp_authenticate_username_password(NULL, $username, $password);
}

/**
 * Redirects the user to the custom "Forgot your password?" page instead of
 * wp-login.php?action=lostpassword.
 */
/*
function redirect_to_custom_lostpassword() {
    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
        if ( is_user_logged_in() ) {
            wp_redirect(home_url());
            exit;
        }
 
        $login_page = get_page_link(get_option(SHORT_NAME . "_pageLoginID"));
        wp_redirect( $login_page . "?login=lostpassword" );
        exit;
    }
}

add_action( 'login_form_lostpassword', 'redirect_to_custom_lostpassword' );
*/

/**
 * A shortcode for rendering the form used to initiate the password reset.
 * [custom-lost-password-form]
 *
 * @param  array   $attributes  Shortcode attributes.
 * @param  string  $content     The text content for shortcode. Not used.
 *
 * @return string  The shortcode output
 */
function ppo_render_password_lost_form( $attributes, $content = null ) {
    // Parse shortcode attributes
    $default_attributes = array( 'show_title' => true );
    $attributes = shortcode_atts( $default_attributes, $attributes );

    return get_template_html( 'password_lost_form', $attributes );
}

add_shortcode( 'custom-lost-password-form', 'ppo_render_password_lost_form' );

/*
function redirect_after_logout() {
    $login_page  = get_page_link(get_option(SHORT_NAME . "_pageLoginID"));
    wp_redirect( $login_page . "?login=false" );
    exit;
}

add_action('wp_logout','redirect_after_logout');
*/

/**
* Render the contents of the given template to a string and returns it.
* @param    string  $template_name  The name of the template to render (without .php)
* @param    array   $attributes     The PHP variables for the template
*
* @return   string                  The contents of the template.
*/
function get_template_html($template_name, $attributes = null) {
    if (!$attributes) {
        $attributes = array();
    }
    
    ob_start();

    do_action('personalize_div_before_' . $template_name);

    require( $template_name . '.php' );

    do_action('personalize_div_before_' . $template_name);

    $html = ob_get_contents();

    ob_end_clean();

    return $html;
}

/* ----------------------------------------------------------------------------------- */
# Custom search
/* ----------------------------------------------------------------------------------- */
add_action('pre_get_posts', 'custom_search_filter');

function custom_search_filter($query) {
    if (!is_admin() && $query->is_main_query()) {
        if ($query->is_search) {
            $query->set('post_type', array('product','project'));
        } else if(is_tax('photo_category')){
            $query->set('posts_per_page', -1);
        }
    }
    return $query;
}

/* ----------------------------------------------------------------------------------- */
# History Orders
/* ----------------------------------------------------------------------------------- */
function get_history_order() {
    global $wpdb, $current_user;
    get_currentuserinfo();
    $records = array();
    if (is_user_logged_in()) {
        $tblOrders = $wpdb->prefix . 'orders';
        $query = "SELECT $tblOrders.*, $wpdb->users.display_name, $wpdb->users.user_email FROM $tblOrders 
            JOIN $wpdb->users ON $wpdb->users.ID = $tblOrders.customer_id 
            WHERE $tblOrders.customer_id = $current_user->ID ORDER BY $tblOrders.ID DESC";
        $records = $wpdb->get_results($query);
    }
    return $records;
}

/* ----------------------------------------------------------------------------------- */
# Check product in order
/* ----------------------------------------------------------------------------------- */
function check_product_in_order($product_id) {
    global $wpdb, $current_user;
    get_currentuserinfo();
    $records = array();
    if (is_user_logged_in()) {
        $tblOrders = $wpdb->prefix . 'orders';
        $query = "SELECT $tblOrders.* FROM $tblOrders 
                WHERE $tblOrders.status = 1 AND $tblOrders.customer_id = $current_user->ID 
                    AND $tblOrders.expire_date >= CURDATE() ";
//        $query .= " AND products REGEXP '.*{\"id\":\"$product_id\",.*'";
        $query .= " AND products REGEXP '.*{\"id\":$product_id,.*'";
        $records = $wpdb->get_results($query);
    }
    if(count($records) > 0)
        return TRUE;
        
    return FALSE;
}

/**
 * Getter function for Featured Content Plugin.
 *
 * @return array An array of WP_Post objects.
 */
//function ppo_get_featured_posts() {
    /**
     * Filter the featured posts to return in Twenty Fourteen.
     *
     * @param array|bool $posts Array of featured posts, otherwise false.
     */
//    return apply_filters( 'ppo_get_featured_posts', array() );
//}

/**
 * A helper conditional function that returns a boolean value.
 *
 * @return bool Whether there are featured posts.
 */
//function ppo_has_featured_posts() {
//    return ! is_paged() && (bool) ppo_get_featured_posts();
//}

//add extra fields to tag category form hook
add_action('edit_category_form_fields', 'extra_category_fields');

//add extra fields to category edit form callback function
function extra_category_fields($tag) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $tag_meta = get_option("tag_$t_id");
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_description2"><?php _e('Mô tả khi click'); ?></label></th>
        <td>
            <?php
            wp_editor(stripslashes_deep($tag_meta['description2']), 'tag_meta_description2', array(
                'textarea_name' => 'tag_meta[description2]',
                'textarea_rows' => 15,
            ));
            ?>
            <br />
            <span class="description">Hiển thị ở trang chủ</span>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_layout"><?php _e('View Layout'); ?></label></th>
        <td>
            <select class="postform" name="tag_meta[layout]" id="tag_meta_layout">
                <?php
                $layout = array(0 => 'Default', 1 => 'Slide', 2 => 'Online Details', 3 => 'Offline Details');
                foreach ($layout as $k => $v) {
                    if ($tag_meta['layout'] == $k) {
                        echo '<option value="' . $k . '" selected>' . $v . '</option>';
                    } else {
                        echo '<option value="' . $k . '">' . $v . '</option>';
                    }
                }
                ?>
            </select>
            <br />
            <span class="description">Layout display in front end</span>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_photo"><?php _e('Chuyên mục hình ảnh'); ?></label></th>
        <td>
            <select class="postform" name="tag_meta[photo]" id="tag_meta_photo">
                <option value="">-- Chọn một chuyên mục hình ảnh --</option>
                <?php
                $terms = get_terms('photo_category', array(
                    'hide_empty' => 0,
                ));
                foreach ($terms as $term) {
                    if ($tag_meta['photo'] == $term->term_id) {
                        echo '<option value="' . $term->term_id . '" selected>' . $term->name . '</option>';
                    } else {
                        echo '<option value="' . $term->term_id . '">' . $term->name . '</option>';
                    }
                }
                ?>
            </select>
            <br />
            <span class="description">Dành cho khoá học Offline</span>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_reason"><?php _e('Lý do học'); ?></label></th>
        <td>
            <?php
            wp_editor(stripslashes_deep($tag_meta['reason']), 'tag_meta_reason', array(
                'textarea_name' => 'tag_meta[reason]',
                'textarea_rows' => 15,
            ));
            ?>
            <br />
            <span class="description">Dành cho khoá học Offline</span>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_target"><?php _e('Mục tiêu khoá học'); ?></label></th>
        <td>
            <?php
            wp_editor(stripslashes_deep($tag_meta['target']), 'tag_meta_target', array(
                'textarea_name' => 'tag_meta[target]',
                'textarea_rows' => 15,
            ));
            ?>
            <br />
            <span class="description">Dành cho khoá học Offline</span>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_learning_method"><?php _e('Phương pháp học'); ?></label></th>
        <td>
            <?php
            wp_editor(stripslashes_deep($tag_meta['learning_method']), 'tag_meta_learning_method', array(
                'textarea_name' => 'tag_meta[learning_method]',
                'textarea_rows' => 15,
            ));
            ?>
            <br />
            <span class="description">Dành cho khoá học Offline</span>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_course_content"><?php _e('Nội dung khoá học'); ?></label></th>
        <td>
            <?php
            wp_editor(stripslashes_deep($tag_meta['course_content']), 'tag_meta_course_content', array(
                'textarea_name' => 'tag_meta[course_content]',
                'textarea_rows' => 15,
            ));
            ?>
            <br />
            <span class="description">Dành cho khoá học Offline</span>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_course_file"><?php _e('File thực hành'); ?></label></th>
        <td>
            <?php
            wp_editor(stripslashes_deep($tag_meta['course_file']), 'tag_meta_course_file', array(
                'textarea_name' => 'tag_meta[course_file]',
                'textarea_rows' => 15,
            ));
            ?>
            <br />
            <span class="description">Dành cho khoá học Online</span>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_product"><?php _e('Chọn khoá học'); ?></label></th>
        <td>
            <select class="postform" name="tag_meta[product]" id="tag_meta_product">
                <option value="">-- Chọn một khoá học --</option>
                <?php
                $products = new WP_Query(array(
                    'post_type' => 'product',
                    'posts_per_page' => -1,
                ));
                while ($products->have_posts()) : $products->the_post();
                    if ($tag_meta['product'] == get_the_ID()) {
                        echo '<option value="' . get_the_ID() . '" selected>' . get_the_title() . '</option>';
                    } else {
                        echo '<option value="' . get_the_ID() . '">' . get_the_title() . '</option>';
                    }
                endwhile;
                wp_reset_query();
                ?>
            </select>
            <br />
            <span class="description">Dành cho khoá học Online</span>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_bg_click"><?php _e('Ảnh nền khi click'); ?></label></th>
        <td>
            <input type="text" name="tag_meta[bg_click]" id="tag_meta_bg_click" style="width:84%;" value="<?php echo $tag_meta['bg_click'] ? $tag_meta['bg_click'] : ''; ?>">
            <button type="button" onclick="uploadByField('tag_meta_bg_click')" class="button button-upload" id="upload_tag_meta_bg_click_button" />Upload</button><br />
            <span class="description">Hiển thị ở trang chủ. Size bằng size ảnh nền khi chưa click.</span>
        </td>
    </tr>
    <?php
}

// save category extra fields hook
add_action('edited_category', 'save_extra_category_fileds');

// save category extra fields callback function
function save_extra_category_fileds($term_id) {
    if (isset($_POST['tag_meta'])) {
        $t_id = $term_id;
        $tag_meta = get_option("tag_$t_id");
        $tag_keys = array_keys($_POST['tag_meta']);
        foreach ($tag_keys as $key) {
            if (isset($_POST['tag_meta'][$key])) {
                $tag_meta_value = stripslashes_deep($_POST['tag_meta'][$key]);
                if(!empty($tag_meta_value)){
                    $tag_meta[$key] = $tag_meta_value;
                    if($key == "product"){
                        update_post_meta($tag_meta_value, 'course_cat', $t_id);
                    }
                }
            }
        }
        //save the option array
        update_option("tag_$t_id", $tag_meta);
    }
}