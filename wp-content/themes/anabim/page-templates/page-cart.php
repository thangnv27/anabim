<?php
/*
  Template Name: Cart
 */
get_header();
?>
<section id="main" class="content-area">
    <div class="container-fluid">
        <div class="row">
            <div id="main-content" class="col-sm-9">
                <?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumbs">','</div>'); } ?>
                
                <div id="content" role="main" class="site-content">
                    <?php
                        if (empty($_SESSION['cart'])) {
                            _e('<div class="cart-empty">Bạn chưa đăng ký khoá học nào!!!<br>Hãy chọn những khoá học mà bạn yêu thích để đăng ký.</div>', SHORT_NAME);
                        } else {
                    ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr style="height: 38px;">
                                    <th><?php _e('Tên khoá học', SHORT_NAME) ?></th>
                                    <th><?php _e('Chi phí', SHORT_NAME) ?></th>
                                    <th><?php _e('Thời hạn', SHORT_NAME) ?></th>
                                    <th style="width: 50px;"><?php _e('Xóa', SHORT_NAME) ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($_SESSION['cart']) and ! empty($_SESSION['cart'])):
                                    $cart = $_SESSION['cart'];
                                    $totalAmount = 0;
                                    foreach ($cart as $product) :
                                        $totalAmount += $product['amount'];
                                        $product_id = $product['id'];
                                        $course_cat = get_post_meta($product_id, "course_cat", true);
                                        $permalink = get_category_link($course_cat);
                                        if(empty($course_cat)) $permalink = get_permalink($product_id);
                                        $title = get_the_title($product_id);
                                        
                                        // Price
                                        $pdc_attr_name = get_post_meta($product_id, 'pdc_attr_name', true);
                                        $pdc_attr_value = get_post_meta($product_id, 'pdc_attr_value', true);
                                        ?>
                                        <tr id="product_item_<?php echo $product_id; ?>">
                                            <td><a href="<?php echo $permalink; ?>" title="<?php echo $title; ?>" target="_blank"><?php echo $title; ?></a></td>
                                            <td class="product-subtotal"><?php echo number_format($product['amount'], 0, ',', '.'); ?> đ</td>
                                            <td>
                                                <select onchange="AjaxCart.updateItem(<?php echo $product_id; ?>, this.value)">
                                                <?php
                                                if(is_array($pdc_attr_name) and count($pdc_attr_name) > 0):
                                                    foreach ($pdc_attr_name as $key => $time) :
                                                        if($product['time'] == $time){
                                                            echo '<option value="' . $time . '" selected>' . $time . ' ngày - ' . number_format($pdc_attr_value[$key], 0, ',', '.') . ' đ</option>';
                                                        } else {
                                                            echo '<option value="' . $time . '">' . $time . ' ngày - ' . number_format($pdc_attr_value[$key], 0, ',', '.') . ' đ</option>';
                                                        }
                                                    endforeach;
                                                else: ?>
                                                    <option value="45">45 ngày - <?php echo number_format($product['amount'], 0, ',', '.'); ?> đ</option>
                                                <?php endif; ?>
                                                </select>
                                            </td>
                                            <td class="t_center delete"><a href="#" onclick="if (confirm('<?php _e('Bạn có chắc chắn muốn xoá không?', SHORT_NAME) ?>')) {
                                                        AjaxCart.deleteItem(<?php echo $product_id; ?>);
                                                    }
                                                    return false;" title="Delete"><img width="28" src="<?php bloginfo('stylesheet_directory'); ?>/images/btnDel.png"/></a>
                                            </td>
                                        </tr>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="cart-price">
                        <span><?php _e('Tổng tiền', SHORT_NAME) ?>: </span> <span class="total_price"><?php echo number_format($totalAmount, 0, ',', '.'); ?> đ</span>
                    </div>
                    <div class="btnCart">
                        <a href="<?php echo home_url() . "/shop"; ?>"><?php _e('Tiếp tục mua hàng', SHORT_NAME) ?></a>
                        <a href="javascript://" onclick="AjaxCart.preCheckout();"><?php _e('Thanh toán', SHORT_NAME) ?></a>
                    </div>
                    <?php }?>
                </div><!-- #content -->
            </div><!-- #main-content -->
                
            <?php get_sidebar(); ?>
        </div>
    </div>
</section><!-- #main -->

<?php get_footer(); ?>