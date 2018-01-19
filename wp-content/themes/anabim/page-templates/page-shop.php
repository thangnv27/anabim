<?php
/*
  Template Name: Shop
 */

get_header();

get_template_part('template', 'slider');
?>

<section id="shop_main">
    <div class="container-fluid">
        <h1 class="page-title"><?php _e('KHOÁ HỌC TẠI ANABIM EDUCATION', SHORT_NAME) ?></h1>
        <div class="row products">
            <?php
            $loop = new WP_Query(array(
                'post_type' => 'product',
                'posts_per_page' => -1,
            ));
            while ($loop->have_posts()) : $loop->the_post();
                $course_cat = get_post_meta(get_the_ID(), "course_cat", true);
                $link = get_category_link($course_cat);
                if(empty($course_cat)) $link = get_the_permalink();
                
                $price = floatval(get_post_meta(get_the_ID(), "price", true));
                $sale_price = floatval(get_post_meta(get_the_ID(), "sale_price", true));

                $product_price = $sale_price;
                if($price == $sale_price or $sale_price == 0){
                    $product_price = $price;
                }
            ?>
            <div class="col-sm-3">
                <div class="item">
                    <div class="imagefit">
                        <a href="<?php echo $link; ?>" title="<?php the_title(); ?>">
                            <img alt="<?php echo $link; ?>" src="<?php get_image_url(); ?>" />
                        </a>
                    </div>
                    <a href="<?php echo $link; ?>" title="<?php the_title(); ?>" class="title">
                        <h3><?php the_title(); ?></h3>
                    </a>
                    <div class="object"><?php echo get_post_meta(get_the_ID(), 'object', true); ?></div>
                    <div class="software"><?php echo get_post_meta(get_the_ID(), 'software', true); ?></div>
                    <div class="level"><?php echo get_the_title(get_post_meta(get_the_ID(), 'level', true)); ?></div>
                    <div class="price">
                        <?php if($price == $sale_price or $sale_price == 0): ?>
                        <span itemprop="price" class="amount"><?php echo number_format($price, 0, ',', '.'); ?><sup>đ</sup></span>
                        <?php else: ?>
                        <del><?php echo number_format($price, 0, ',', '.'); ?><sup>đ</sup></del>
                        <span itemprop="price" class="amount"><?php echo number_format($sale_price, 0, ',', '.'); ?><sup>đ</sup></span>
                        <?php endif; ?>
                    </div>
                    <a class="btn btn-warning add-to-cart" href="javascript://" title="<?php echo __('Đăng ký', SHORT_NAME); ?>" 
                       onclick="AjaxCart.addToCart(<?php echo get_the_ID(); ?>,'<?php echo get_the_title(); ?>',<?php echo $product_price; ?>)">
                        <i class="fa fa-cart-plus"></i> <?php echo __('Đăng ký', SHORT_NAME); ?>
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
            <?php wp_reset_query(); ?>
            <div class="clearfix"></div>
        </div>
        
        <?php // getpagenavi(array('query' => $loop)); ?>
    </div>
</section>

<?php get_footer(); ?>