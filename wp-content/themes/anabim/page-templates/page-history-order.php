<?php
/*
  Template Name: Lịch sử đặt hàng
 */
if(!is_user_logged_in()) {
    header("location: " . wp_login_url(getCurrentRquestUrl()));
    exit;
}

get_header(); 
?>
<section id="main" class="content-area">
    <div class="container-fluid">
        <?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumbs">','</div>'); } ?>
        
        <!--BEGIN HISTORY ORDER PAGE-->
        <div class="table-responsive cart">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr style="height: 38px;">
                        <th style="max-width: 100px;"><?php _e('Mã đơn hàng', SHORT_NAME) ?></th>
                        <th><?php _e('Tình trạng đơn hàng', SHORT_NAME) ?></th>
                        <th><?php _e('Ngày đăng ký', SHORT_NAME) ?></th>
                        <th><?php _e('Tổng tiền', SHORT_NAME) ?></th>
                        <th><?php _e('Chi tiết', SHORT_NAME) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $records = get_history_order();
                        $page = 1;
                        foreach ($records as $key => $row) :
                            $status = "<font color='orange'>".__('Đang chờ duyệt', SHORT_NAME)."</font>";
                            $transfer = "<font color='orange'>".__('Chưa thanh toán', SHORT_NAME)."</font>";
                            if($row->status == 1){
                                $status = "<font color='green'>".__('Đã hoàn tất', SHORT_NAME)."</font>";
                                $transfer = "<font color='green'>".__('Đã thanh toán', SHORT_NAME)."</font>";
                            }elseif($row->status == 2){
                                $status = "<font color='red'>".__('Bỏ qua', SHORT_NAME)."</font>";
                                $transfer = "<font color='red'>".__('Không thanh toán', SHORT_NAME)."</font>";
                            }
                            $customer_info = json_decode($row->customer_info);
                            $products = json_decode($row->products);
                            $discount = number_format($row->discount, 0, ',', '.');
                            $total_amount = number_format($row->total_amount,0,',','.');
                            $expire_date = date('d/m/Y', strtotime($row->expire_date));
                            
                            $btnViewText = __('Xem chi tiết', SHORT_NAME);
                            $orderDetailTitle = __('Chi tiết đơn hàng', SHORT_NAME);
                            echo <<<HTML
                        <tr page="{$page}">
                            <td>{$row->ID}</td>
                            <td>{$status}</td>
                            <td>{$row->created_at}</td>
                            <td><font color='red'>{$total_amount} đ</font></td>
                            <td>
                                <input type="button" value="{$btnViewText}" class="btnXem" />
                                <div id="view_order_{$row->ID}" style="display: none;">
                                    <div class="addrRowTitle">{$orderDetailTitle}</div>
                                    <div class="donhangBg">
                                        <div class="donhangDetail1">
                                        <p><b>Đơn hàng: #{$row->ID}</b><br />
                                         Ngày đặt hàng: <i>{$row->created_at}</i><br />
                                        Tình trạng:<i> {$status}</i><br />
                                         Tổng tiền: <font color='red'><i>{$total_amount} đ</i></font></p>
                                        <p><b>Tình trạng thanh toán: </b><br />
                                         {$transfer}</p>
                                        <b>Thời hạn: </b><br />
                                         {$expire_date}
                                        </div>
                                        <div class="donhangDetail2">
                                            <b> Địa chỉ thanh toán</b><br />
                                            Họ và tên: {$customer_info->fullname}<br />
                                            Email: {$row->user_email}<br />
                                            Điện thoại: {$customer_info->phone}<br />
                                            Địa chỉ: {$customer_info->address}<br />
                                            Tỉnh/Thành phố: {$customer_info->city}<br />
                                            <br>
                                        <b>Phương thức thanh toán</b><br />
                                        {$row->payment_method}
                                       </div>
                                       <div class="clearfix"></div>
                                    </div>

                                    <div class="product-order-bg">
                                        <div class="addrRowTitle">Khoá học đã đăng ký</div>
                                        <table class="table">
                                            <tbody><tr>
                                                    <th>Khoá học</th>
                                                    <th>Chi phí</th>
                                                </tr>
HTML;
                                        $totalAmount = 0;
                                        foreach ($products as $product) :
                                            $totalAmount += $product->amount;
                                            $permalink = get_permalink($product->id);
                                            $amount = number_format($product->amount,0,',','.');
                                            echo <<<HTML
                                                <tr>
                                                    <td>
                                                        <em><a title="{$btnViewText}" href="{$permalink}" target="_blank" style="color:#373737;">{$product->title}</a></em>
                                                    </td>
                                                    <td>{$amount} đ</td>
                                                </tr>
HTML;
                                        endforeach;

                                        $totalAmount = number_format($totalAmount,0,',','.');
                                        echo <<<HTML
                                                <tr>
                                                    <td style="text-align:right;">
                                                        <strong>Thành tiền</strong>
                                                    </td>
                                                    <td>{$totalAmount} đ</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:right;">
                                                        <strong>Giảm giá</strong>
                                                    </td>
                                                    <td>{$discount} đ</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:right;">
                                                        <strong>Tổng thanh toán</strong>
                                                    </td>
                                                    <td>
                                                        <font color='red'><strong>{$total_amount} đ</strong></font>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </td>
                        </tr>
HTML;
                        endforeach; 
                        ?>
                </tbody>
            </table>
            <script type="text/javascript">
                jQuery(function() {
                    $(".btnXem").click(function(){
                        ShowPoupOrderDetail($(this).next().html());
                    });
                })
            </script>
        </div>
        <!--END HISTORY ORDER PAGE-->
    </div>
</section>
<?php get_footer(); ?>