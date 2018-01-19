<?php
/*
  Template Name: Upgrade
*/
if (!is_user_logged_in()) {
    wp_redirect(get_page_link(get_option(SHORT_NAME . "_pageLoginID")) . '?redirect_to=' . urlencode(getCurrentRquestUrl()));
    exit;
}

get_header();
global $current_user;
get_currentuserinfo();
?>

<section class="page-upgrade">
    <div class="container-fluid">
        <div id="content" class="site-content" role="main">
            <h1 class="title"><?php _e('Anabim Memberships', SHORT_NAME) ?></h1>
            
            <div class="packages">
            <?php
            // Current membership
            $membership = esc_attr(get_the_author_meta('user_membership', $current_user->ID));
            $current_price = floatval(get_post_meta($membership, "price", true));
            $current_sale_price = floatval(get_post_meta($membership, "sale_price", true));
            $current_member_price = $current_sale_price;
            if($current_price == $current_sale_price or $current_sale_price == 0){
                $current_member_price = $current_price;
            }
            
            $loop = new WP_Query(array(
                'post_type' => 'membership',
                'posts_per_page' => -1,
                'meta_key' => 'mem_order',
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
            ));
            while ($loop->have_posts()) : $loop->the_post();
                $price = floatval(get_post_meta(get_the_ID(), "price", true));
                $sale_price = floatval(get_post_meta(get_the_ID(), "sale_price", true));

                $member_price = $sale_price;
                if($price == $sale_price or $sale_price == 0){
                    $member_price = $price;
                }
                
                // Price list
                $pdc_attr_name = get_post_meta(get_the_ID(), 'pdc_attr_name', true);
                $pdc_attr_value = get_post_meta(get_the_ID(), 'pdc_attr_value', true);
            ?>
                <div class="col-sm-3 item">
                    <h3><?php the_title(); ?></h3>
                    <div class="price">
                        <?php if($price == $sale_price or $sale_price == 0): ?>
                        <span itemprop="price" class="amount"><?php echo number_format($price, 0, ',', '.'); ?><sup>đ</sup></span>
                        <?php else: ?>
                        <span itemprop="price" class="amount"><?php echo number_format($sale_price, 0, ',', '.'); ?><sup>đ</sup></span>
                        <del><?php echo number_format($price, 0, ',', '.'); ?><sup>đ</sup></del>
                        <?php endif; ?>
                    </div>
                    <div class="per-month">
                        <select id="time_<?php the_ID() ?>">
                            <?php
                            if(is_array($pdc_attr_name) and count($pdc_attr_name) > 0):
                                foreach ($pdc_attr_name as $key => $time) :
                                    echo '<option value="' . $time . '">' . $time . ' tháng - ' . number_format($pdc_attr_value[$key], 0, ',', '.') . ' đ</option>';
                                endforeach;
                            else: ?>
                            <option value="1">1 <?php _e('tháng', SHORT_NAME) ?> - <?php echo number_format($member_price, 0, ',', '.'); ?> đ</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="btns">
                        <?php if($membership == get_the_ID()): ?>
                        <a class="btn btn-default"><?php _e('Hiện tại', SHORT_NAME) ?></a>
                        <?php else: ?>
                            <?php if($member_price > $current_member_price): ?>
                            <a class="btn btn-warning" onclick="AjaxCart.membershipAddToCart(<?php the_ID() ?>, document.getElementById('time_<?php the_ID() ?>').value)">
                                <?php _e('Nâng cấp', SHORT_NAME) ?>
                            </a>
                            <?php else: ?>
                            <a class="btn btn-primary">
                                <?php _e('Đã nâng cấp', SHORT_NAME) ?>
                            </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="description">
                        <?php echo $post->post_content; ?>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_query(); ?>
            </div>
        </div><!-- #content -->
    </div>
</section>

<?php get_footer(); ?>