<?php
if(!defined('UNAPPROVED')) define ('UNAPPROVED', 0);
if(!defined('APPROVED')) define ('APPROVED', 1);
if(!defined('CANCELLED')) define ('CANCELLED', 2);

if (!class_exists('WP_List_Table'))
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class WPMemberships_Detail_List_Table extends WP_List_Table {

    /**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */
    function __construct() {
        parent::__construct(array(
            'singular' => 'wp_memberships_detail', //Singular label
            'plural' => 'wp_memberships_details', //plural label, also this well be one of the table css class
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
        }
        if ($which == "bottom") {
            global $wpdb;
            $tblMemberships = $wpdb->prefix . 'memberships';
            $membership_id = intval($_GET['membership_id']);
            $membershipsRow = $wpdb->get_row( "SELECT * FROM $tblMemberships WHERE ID = $membership_id" );
            $currentUrl = getCurrentRquestUrl();
            $approveLink = '?page=nvt_memberships&action=approve&membership_id=' . (int) $membershipsRow->ID;
            $unapproveLink = '?page=nvt_memberships&action=unapprove&membership_id=' . (int) $membershipsRow->ID;
            $cancelLink = '?page=nvt_memberships&action=cancel&membership_id=' . (int) $membershipsRow->ID;
            $restoreLink = '?page=nvt_memberships&action=restore&membership_id=' . (int) $membershipsRow->ID;
//            $printLink = get_page_link(get_option(SHORT_NAME . '_pagePrintMembershipsID')) .'/?membership_id=' . (int) $membershipsRow->ID;
            
            echo '<br/>';
            echo <<<HTML
            <h3>Cập nhật ngày hết hạn:</h3>
            <form action="{$currentUrl}">
                <input type="text" name="expire_date" value="" class="order-datepicker" placeholder="yyyy-mm-dd" />
                <input type="button" class="button button-primary order-update-expire" value="Áp dụng" />
            </form>
            <br />
HTML;
            if($membershipsRow->status == UNAPPROVED){
                echo '<a href="?page=nvt_memberships" class="button">Quay lại</a>  <a href="'.$approveLink.'" class="button" onclick="return confirm(\'Bạn có chắc chắn không?\');">Duyệt</a>  <a href="'.$cancelLink.'" class="button" onclick="return confirm(\'Bạn có chắc chắn không?\');">Hủy</a>';
            }else if($membershipsRow->status == APPROVED){
                echo '<a href="?page=nvt_memberships" class="button">Quay lại</a>  <a href="'.$unapproveLink.'" class="button" onclick="return confirm(\'Bạn có chắc chắn không?\');">Không duyệt</a>  <a href="'.$cancelLink.'" class="button" onclick="return confirm(\'Bạn có chắc chắn không?\');">Hủy</a>';
            }else if($membershipsRow->status == CANCELLED){
                echo '<a href="?page=nvt_memberships" class="button">Quay lại</a>  <a href="'.$approveLink.'" class="button" onclick="return confirm(\'Bạn có chắc chắn không?\');">Duyệt</a>  <a href="'.$restoreLink.'" class="button" onclick="return confirm(\'Bạn có chắc chắn không?\');">Phục hồi</a>';
            }
//            echo '<a href="' . $printLink . '" target="_blank" class="button" onclick="window.open(\'' . $printLink . '\', \'windowname1\', \'width=420,height=600,top=0,left=0,scrollbars=1\'); return false;">In hóa đơn</a>';
        }
    }

    /**
     * Define the columns that are going to be used in the table
     * @return array $columns, the array of columns to use with the table
     */
    function get_columns() {
        return $columns = array(
            'col_memberships_id' => __('ID'),
            'col_memberships_name' => __('Gói cấp độ'),
            'col_memberships_price' => __('Giá'),
            'col_memberships_time' => __('Thời gian'),
            'col_memberships_amount' => __('Thành tiền'),
        );
    }

    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */
    function prepare_items() {
        global $wpdb;
        $tblMemberships = $wpdb->prefix . 'memberships';
        $membership_id = intval($_GET['membership_id']);
        if(isset($_GET['expire_date'])){
            $_expire_date = mysql_real_escape_string(trim($_GET['expire_date']));
            $sql_update = "UPDATE $tblMemberships SET status = 1, expire_date = '$_expire_date' WHERE ID = $membership_id";
            $wpdb->query($sql_update);
            updateMembershipExpire($membership_id, $_expire_date);
        }
        
        /* -- Preparing your query -- */
        $query = "SELECT * FROM $tblMemberships WHERE ID = $membership_id ";

        /* -- Register the Columns -- */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);

        /* -- Fetch the items -- */
        $membershipsRow = $wpdb->get_row($query);
        $customer = json_decode($membershipsRow->user_info);
        $expire_date = date('d/m/Y', strtotime($membershipsRow->expire_date));
        $this->items = array(
            'id' => $membershipsRow->package_id,
            'name' => $membershipsRow->package_name,
            'price' => $membershipsRow->price,
            'time' => $membershipsRow->time,
            'amount' => $membershipsRow->total_amount,
        );
        
        echo <<<HTML
        <h3>THÔNG TIN TÀI KHOẢN</h3>
        <p>Username ID: {$membershipsRow->user_id}</p>
        <p>Họ và tên: {$customer->fullname}</p>
        <p>Email: {$customer->email}</p>
        <p>Điện thoại: {$customer->phone}</p>
        <p>Địa chỉ: {$customer->address}</p>
        <p>Nơi công tác: {$customer->workplace}</p>
        <p>Tỉnh thành: {$customer->city}</p>

        <h3>THÔNG TIN GÓI NÂNG CẤP</h3>
        <p>Thời hạn: {$expire_date}</p>
        <p>Phương thức thanh toán: {$membershipsRow->payment_method}</p>
HTML;
        
        // Update status
        if(isset($_GET['action'])){
            $act = $_GET['action'];
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
                default:
                    break;
            }
        }
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
            //Open the line
            echo '<tr id="record_' . $records['id'] . '">';
            foreach ($columns as $column_name => $column_display_name) {

                //Style attributes for each col
                $class = "class='$column_name column-$column_name'";
                $style = "";
                if (in_array($column_name, $hidden))
                    $style = ' style="display:none;"';
                $attributes = $class . $style;

                //Display the cell
                switch ($column_name) {
                    case "col_memberships_id": echo '<td ' . $attributes . '>' . $records['id'] . '</td>';
                        break;
                    case "col_memberships_name": echo '<td ' . $attributes . '>' . $this->column_title($records) . '</td>';
                        break;
                    case "col_memberships_price": echo '<td ' . $attributes . '>' . number_format($records['price'], 0, ',', '.') . ' đ</td>';
                        break;
                    case "col_memberships_time": echo '<td ' . $attributes . '>' . $records['time'] . ' tháng</td>';
                        break;
                    case "col_memberships_amount": echo '<td ' . $attributes . '>' . number_format($records['amount'], 0, ',', '.') . ' đ</td>';
                        break;
                }
            }

            //Close the line
            echo'</tr>';
        }
    }
    
    function column_title($item) {
//        $permalink = get_permalink( $item['id'] );
        $actions = array(
            'edit' => sprintf('<a href="post.php?post=%s&action=edit">Edit</a>', $item['id']),
//            'view' => sprintf('<a href="%s" target="_blank">View</a>', $permalink),
        );

        return sprintf('%1$s %2$s',stripslashes( $item['name'] ), $this->row_actions($actions));
    }

}