<?php

add_action('after_setup_theme', 'memberships_install');
add_action('admin_menu', 'add_memberships_page');

/* ----------------------------------------------------------------------------------- */
# Create table in database
/* ----------------------------------------------------------------------------------- */
if (!function_exists('memberships_install')) {
    function memberships_install() {
        global $wpdb;

        $memberships = $wpdb->prefix . 'memberships';

        $sql = "CREATE TABLE IF NOT EXISTS $memberships (
                ID int AUTO_INCREMENT PRIMARY KEY,
                user_id int NOT NULL,
                user_info longtext character set utf8 NOT NULL,
                payment_method varchar(255) character set utf8 NOT NULL,
                package_id int NOT NULL,
                package_name longtext character set utf8 NOT NULL,
                price varchar(100) NOT NULL,
                time int NOT NULL default 0,
                expire_date date NOT NULL,
                total_amount varchar(100) NOT NULL,
                status int default 0 comment '0: Unapproved, 1: Approved, 2: Cancel',
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                nl_payment_id varchar(255) NULL,
                nl_secure_code varchar(255) NULL
        );";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

/* ----------------------------------------------------------------------------------- */
# Add memberships page menu
/* ----------------------------------------------------------------------------------- */
function add_memberships_page(){
    add_submenu_page(PPOCART_MENU_NAME, //Menu ID – Defines the unique id of the menu that we want to link our submenu to. 
                                    //To link our submenu to a custom post type page we must specify - 
                                    //edit.php?post_type=my_post_type
            __('Memberships Management'), // Page title
            __('Memberships'), // Menu title
            'manage_options', // Capability - see: http://codex.wordpress.org/Roles_and_Capabilities#Capabilities
            'nvt_memberships', // Submenu ID – Unique id of the submenu.
            'theme_memberships_page' // render output function
        );
}
/* ----------------------------------------------------------------------------------- */
# Orders layout
/* ----------------------------------------------------------------------------------- */
function theme_memberships_page() {
    if(isset($_GET['action']) and $_GET['action'] == 'view-detail'){
        require_once 'class-memberships-detail-list-table.php';

        echo <<<HTML
        <div class="wrap">
            <div id="icon-users" class="icon32"></div>
            <h2>Chi tiết đơn hàng</h2>
HTML;

        //Prepare Table of elements
        $wp_list_table = new WPMemberships_Detail_List_Table();
        $wp_list_table->prepare_items();
        //Table of elements
        $wp_list_table->display();

        echo '</div>';
    }else{
        require_once 'class-memberships-list-table.php';
        
        $product_id = 0;
        if(isset($_REQUEST['product_id']) and intval($_REQUEST['product_id']) > 0){
            $product_id = intval($_REQUEST['product_id']);
        }

        echo <<<HTML
        <div class="wrap">
            <div id="icon-users" class="icon32"></div>
            <h2>Danh sách memberships</h2>
            <ul class="subsubsub">
HTML;
                echo '<li><a class="', (!isset($_GET['status'])) ? 'current' : '' ,'" href="?page=nvt_memberships', ($product_id > 0) ? '&product_id=' . $product_id : '' ,'">Chờ duyệt</a> | </li>';
                //echo '<li><a class="', (isset($_GET['status']) && $_GET['status'] == "0") ? 'current' : '' ,'" href="?page=nvt_memberships&status=0', ($product_id > 0) ? '&product_id=' . $product_id : '' ,'">Chờ duyệt</a> | </li>';
                echo '<li><a class="', (isset($_GET['status']) && $_GET['status'] == 1) ? 'current' : '' ,'" href="?page=nvt_memberships&status=1', ($product_id > 0) ? '&product_id=' . $product_id : '' ,'">Đã duyệt</a> | </li>';
                echo '<li><a class="', (isset($_GET['status']) && $_GET['status'] == 2) ? 'current' : '' ,'" href="?page=nvt_memberships&status=2', ($product_id > 0) ? '&product_id=' . $product_id : '' ,'">Đã hủy</a> | </li>';
                echo '<li><a class="', (isset($_GET['status']) && $_GET['status'] == "all") ? 'current' : '' ,'" href="?page=nvt_memberships&status=all', ($product_id > 0) ? '&product_id=' . $product_id : '' ,'">Tất cả</a></li>';
        echo <<<HTML
            </ul>
            <form id="ppo-memberships-form" action="" method="get">
            <input type="hidden" name="page" value="nvt_memberships" />
HTML;
        if ( ! empty( $_REQUEST['status'] ) )
            echo '<input type="hidden" name="status" value="' . esc_attr( $_REQUEST['status'] ) . '" />';
        
        //Prepare Table of elements
        $wp_list_table = new WPMemberships_List_Table();
        $wp_list_table->prepare_items();
        //Search box
        $wp_list_table->search_box('Tìm member', 'search_id');
        //Table of elements
        $wp_list_table->display();

        echo '</form></div>';
    }
}
function updateMembershipExpire($membership_id, $expire_date){
    global $wpdb;
    $tblMemberships = $wpdb->prefix . 'memberships';

    $user_id = $wpdb->get_var( "SELECT user_id FROM $tblMemberships WHERE ID = $membership_id" );
    $package_id = $wpdb->get_var( "SELECT package_id FROM $tblMemberships WHERE ID = $membership_id" );
    if($user_id && $package_id){
        update_usermeta($user_id, 'user_membership', $package_id);
        update_usermeta($user_id, 'membership_expire', $expire_date);
    }
}
function setMembership($membership_id){
    global $wpdb;
    $tblMemberships = $wpdb->prefix . 'memberships';

    $user_id = $wpdb->get_var( "SELECT user_id FROM $tblMemberships WHERE ID = $membership_id" );
    $package_id = $wpdb->get_var( "SELECT package_id FROM $tblMemberships WHERE ID = $membership_id" );
    if($user_id && $package_id){
        update_usermeta($user_id, 'user_membership', $package_id);
    }
}
function unMembership($membership_id){
    global $wpdb;
    $tblMemberships = $wpdb->prefix . 'memberships';

    $user_id = $wpdb->get_var( "SELECT user_id FROM $tblMemberships WHERE ID = $membership_id" );
    $package_id = intval(get_option(SHORT_NAME . "_membershipID"));
    if($user_id && $package_id){
        update_usermeta($user_id, 'user_membership', $package_id);
    }
}