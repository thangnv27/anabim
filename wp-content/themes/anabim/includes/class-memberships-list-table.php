<?php
if(!defined('UNAPPROVED')) define ('UNAPPROVED', 0);
if(!defined('APPROVED')) define ('APPROVED', 1);
if(!defined('CANCELLED')) define ('CANCELLED', 2);

if (!class_exists('WP_List_Table'))
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class WPMemberships_List_Table extends WP_List_Table {

    /**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */
    function __construct() {
        parent::__construct(array(
            'singular' => 'wp_list_membership', //Singular label
            'plural' => 'wp_list_memberships', //plural label, also this well be one of the table css class
            'ajax' => false //We won't support Ajax for this table
        ));
    }

    /**
     * Add extra markup in the toolbars before or after the list
     * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
     */
    function extra_tablenav($which) {
        if ($which == "top") {
            //The code that goes before the table is here
            //echo"Hello, I'm before the table";
        }
        if ($which == "bottom") {
            //The code that goes after the table is there
            //echo"Hi, I'm after the table";
        }
    }

    /**
     * Define the columns that are going to be used in the table
     * @return array $columns, the array of columns to use with the table
     */
    function get_columns() {
        return $columns = array(
            'col_memberships_cb' => '<input type="checkbox" class="cb-memberships-select-all" />',
            'col_memberships_id' => __('ID'),
            'col_memberships_user' => __('Thành viên'),
            'col_memberships_package' => __('Gói cấp độ'),
            'col_memberships_date' => __('Ngày đăng ký'),
            'col_memberships_expire' => __('Ngày hết hạn'),
            'col_memberships_amount' => __('Tổng tiền'),
            'col_memberships_options' => __('Tùy chọn')
        );
    }

    /**
     * Decide which columns to activate the sorting functionality on
     * @return array $sortable, the array of columns that can be sorted by the user
     */
    public function get_sortable_columns() {
        return $sortable = array(
            'col_memberships_id' => array('ID', true),
            'col_memberships_user' => array('display_name', false),
            'col_memberships_date' => array('created_at', false),
            'col_memberships_expire' => array('expire_date', false),
            'col_memberships_amount' => array('total_amount', false),
        );
    }

    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */
    function prepare_items() {
        global $wpdb;
        $screen = get_current_screen();
        $tblMemberships = $wpdb->prefix . 'memberships';

        $this->process_bulk_action();

        // Update status
        if (isset($_GET['action'])) {
            $act = $_GET['action'];
            $membership_id = intval($_GET['membership_id']);
            switch ($act) {
                case "approve":
                    $query = "UPDATE $tblMemberships SET status = 1 WHERE ID = $membership_id and status <> 1";
                    $wpdb->query($query);
                    setMembership($membership_id);
                    break;
                case "unapprove":
                    $query = "UPDATE $tblMemberships SET status = 0 WHERE ID = $membership_id and status <> 0";
                    $wpdb->query($query);
                    unMembership($membership_id);
                    break;
                case "cancel":
                    $query = "UPDATE $tblMemberships SET status = 2 WHERE ID = $membership_id and status <> 2";
                    $wpdb->query($query);
                    unMembership($membership_id);
                    break;
                case "restore":
                    $query = "UPDATE $tblMemberships SET status = 0 WHERE ID = $membership_id and status <> 0";
                    $wpdb->query($query);
                    unMembership($membership_id);
                    break;
                case "delete":
                    $query = "DELETE FROM $tblMemberships WHERE ID = $membership_id";
                    $wpdb->query($query);
                    unMembership($membership_id);
                    break;
                default:
                    break;
            }
            wp_redirect(admin_url("admin.php?page=nvt_memberships"));
            exit();
        }

        /* -- Preparing your query -- */
        $query = "SELECT $tblMemberships.*, $wpdb->users.display_name FROM $tblMemberships LEFT JOIN $wpdb->users ON $wpdb->users.ID = $tblMemberships.user_id ";

        $status = (isset($_GET['status'])) ? $_GET['status'] : null;
        if ($status == null) {
            $query .= "WHERE status = 0";
        } else if (in_array($status, array(APPROVED, CANCELLED))) {
            $query .= "WHERE status = $status";
        }
        
        // Search by keyword
        if(isset($_REQUEST['s']) and !empty($_REQUEST['s'])){
            $search_query = esc_sql($_REQUEST['s']);
            if(strpos($query, "WHERE") !== FALSE){
//                $query .= " AND user_info REGEXP '.*{\"fullname\":\"$search_query\",.*'";
                $query .= " AND user_info LIKE '%$search_query%'";
            } else {
//                $query .= " WHERE user_info REGEXP '.*{\"fullname\":\"$search_query\",.*'";
                $query .= " WHERE user_info LIKE '%$search_query%'";
            }
        }

        /* -- Ordering parameters -- */
        //Parameters that are going to be used to order the result
        $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'ID';
        $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'DESC';
        if (!empty($orderby) & !empty($order)) {
            $query.=' ORDER BY ' . $orderby . ' ' . $order;
        }

        /* -- Pagination parameters -- */
        //Number of elements in your table?
        $totalitems = $wpdb->query($query); //return the total number of affected rows
        //How many to display per page?
        $perpage = 20;
        //Which page is this?
        //$paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
        $paged = $this->get_pagenum();
        //Page Number
        if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
            $paged = 1;
        }
        //How many pages do we have in total?
        $totalpages = ceil($totalitems / $perpage);
        //adjust the query to take pagination into account
        if (!empty($paged) && !empty($perpage)) {
            $offset = ($paged - 1) * $perpage;
            $query.=' LIMIT ' . (int) $offset . ',' . (int) $perpage;
        }

        /* -- Register the pagination -- */
        $this->set_pagination_args(array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ));
        //The pagination links are automatically built according to those parameters

        /* -- Register the Columns -- */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        /* -- Fetch the items -- */
        $this->items = $wpdb->get_results($query);
    }

    /**
     * Display the rows of records in the table
     * @return string, echo the markup of the rows
     */
    function display_rows() {

        //Get the records registered in the prepare_items method
        $records = $this->items;

        //Get the columns registered in the get_columns and get_sortable_columns methods
        list( $columns, $hidden ) = $this->get_column_info();

        //Loop for each record
        if (!empty($records)) {
            foreach ($records as $rec) {

                //Open the line
                echo '<tr id="record_' . $rec->ID . '">';
                foreach ($columns as $column_name => $column_display_name) {

                    //Style attributes for each col
                    $class = "class='$column_name column-$column_name'";
                    $style = "";
                    if (in_array($column_name, $hidden))
                        $style = ' style="display:none;"';
                    $attributes = $class . $style;
                    
                    //links
                    $viewlink = '?page=nvt_memberships&action=view-detail&membership_id=' . (int) $rec->ID;
                    $approveLink = '?page=nvt_memberships&action=approve&membership_id=' . (int) $rec->ID;
                    $unapproveLink = '?page=nvt_memberships&action=unapprove&membership_id=' . (int) $rec->ID;
                    $cancelLink = '?page=nvt_memberships&action=cancel&membership_id=' . (int) $rec->ID;
                    $restoreLink = '?page=nvt_memberships&action=restore&membership_id=' . (int) $rec->ID;
                    $deleteLink = '?page=nvt_memberships&action=delete&membership_id=' . (int) $rec->ID;
//                    $printLink = get_page_link(get_option(SHORT_NAME . '_pagePrintMembershipsID')) .'/?membership_id=' . (int) $rec->ID;
                        
                    //Display the cell
                     
                    switch ($column_name) {
                        case "col_memberships_cb": echo '<th ' . $attributes . '>' . $this->column_cb($rec) . '</th>';
                            break;
                        case "col_memberships_id": echo '<td ' . $attributes . '>' . $rec->ID . '</td>';
                            break;
                        case "col_memberships_user": echo '<td ' . $attributes . '>' . $rec->display_name . '</td>';
                            break;
                        case "col_memberships_package": echo '<td ' . $attributes . '>' . $rec->package_name . '</td>';
                            break;
                        case "col_memberships_date": echo '<td ' . $attributes . '>' . $rec->created_at . '</td>';
                            break;
                        case "col_memberships_expire": echo '<td ' . $attributes . '>' . date('d/m/Y', strtotime($rec->expire_date)) . '</td>';
                            break;
                        case "col_memberships_amount": echo '<td ' . $attributes . '>' . number_format($rec->total_amount, 0, ',', '.') . ' đ</td>';
                            break;
                        case "col_memberships_options":
                            echo '<td ' . $attributes . '>';
//                            echo '<a href="' . $printLink . '" target="_blank">In hóa đơn</a> | ';
                            if ($rec->status == UNAPPROVED) {
                                echo '<a href="' . $viewlink . '">Xem</a> | <a href="' . $approveLink . '">Duyệt</a> | <a href="' . $cancelLink . '">Hủy</a>';
                            } else if ($rec->status == APPROVED) {
                                echo '<a href="' . $viewlink . '">Xem</a> | <a href="' . $unapproveLink . '">Không duyệt</a> | <a href="' . $cancelLink . '">Hủy</a>';
                            } else if ($rec->status == CANCELLED) {
                                echo '<a href="' . $viewlink . '">Xem</a> | <a href="' . $approveLink . '">Duyệt</a> | <a href="' . $restoreLink . '">Phục hồi</a>| <a onclick="return confirm(\'Bạn có chắc chắn không?\')" href="' . $deleteLink . '">Xóa vĩnh viễn</a>';
                            }
                            echo '</td>';
                            break;
                    }
                }

                //Close the line
                echo'</tr>';
            }
        }
    }

    function get_bulk_actions() {
        $status = (isset($_GET['status'])) ? $_GET['status'] : UNAPPROVED;
        if ($status == APPROVED) {
            $actions = array(
                'unapprove' => __('Không duyệt', 'nvt_memberships'),
                'cancel' => __('Hủy', 'nvt_memberships')
            );
        } elseif ($status == CANCELLED) {
            $actions = array(
                'approve' => __('Duyệt', 'nvt_memberships'),
                'restore' => __('Phục hồi', 'nvt_memberships'),
                'delete' => __('Xóa vĩnh viễn', 'nvt_memberships')
            );
        } else {
            $actions = array(
                'approve' => __('Duyệt', 'nvt_memberships'),
                'cancel' => __('Hủy', 'nvt_memberships')
            );
        }

        return $actions;
    }

    function process_bulk_action() {
        global $wpdb;
        $tblMemberships = $wpdb->prefix . 'memberships';

        // security check!
        if (isset($_POST['_wpnonce']) && !empty($_POST['_wpnonce'])) {
            $nonce = filter_input(INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING);
            $action = 'bulk-' . $this->_args['plural'];

            if (!wp_verify_nonce($nonce, $action))
                wp_die('Nope! Security check failed!');
        }

        $action = $this->current_action();
        $wp_list_memberships = getRequest('wp_list_membership');
        if(is_array($wp_list_memberships)){
            switch ($action) {
                case "approve":
                    foreach ($wp_list_memberships as $id) {
                        $query = "UPDATE $tblMemberships SET status = 1 WHERE ID = $id and status <> 1";
                        $wpdb->query($query);
                        setMembership($id);
                    }
                    break;
                case "unapprove":
                    foreach ($wp_list_memberships as $id) {
                        $query = "UPDATE $tblMemberships SET status = 0 WHERE ID = $id and status <> 0";
                        $wpdb->query($query);
                        unMembership($id);
                    }
                    break;
                case "cancel":
                    foreach ($wp_list_memberships as $id) {
                        $query = "UPDATE $tblMemberships SET status = 2 WHERE ID = $id and status <> 2";
                        $wpdb->query($query);
                        unMembership($id);
                    }
                    break;
                case "restore":
                    foreach ($wp_list_memberships as $id) {
                        $query = "UPDATE $tblMemberships SET status = 0 WHERE ID = $id and status <> 0";
                        $wpdb->query($query);
                        unMembership($id);
                    }
                    break;
                case "delete":
                    foreach ($wp_list_memberships as $id) {
                        $query = "DELETE FROM $tblMemberships WHERE ID = $id";
                        $wpdb->query($query);
                        unMembership($id);
                    }
                    break;
                default:
                    break;
            }
        }

        return;
    }

    function column_default($item, $column_name) {
        return '';
    }

    function column_cb($item) {
        return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item->ID);
    }

}

################################################################################
add_action('admin_print_footer_scripts', 'memberships_bulk_actions_select_all', 99);

function memberships_bulk_actions_select_all() {
    echo <<<HTML
<style type="text/css">
    #col_memberships_cb{width: 30px;}
    #col_memberships_id{width: 50px;}
</style>
<script type="text/javascript">/* <![CDATA[ */
jQuery(function($){
    $("input.cb-memberships-select-all").click(function(){
        if($(this).is(':checked')){
            $("input[name='wp_list_membership[]']").attr('checked', 'checked');
            $("input.cb-memberships-select-all").attr('checked', 'checked');
        }else{
            $("input[name='wp_list_membership[]']").removeAttr('checked');
            $("input.cb-memberships-select-all").removeAttr('checked');
        }
    });
    $("form#ppo-memberships-form").submit(function(){
        var str_query = $("#search-submit").prev().val().trim();
        if(str_query.length > 0){
            window.location = window.location.href + "&s=" + str_query;
            return false;
        }
    });
});
/* ]]> */
</script>
HTML;
}