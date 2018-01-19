<?php
/**
 * The Template for displaying all single posts
 */
if ($_GET['temp_content'] == 'video') {
?>
<style type="text/css">
    body{margin: 0;padding: 0;background: #ccc;}
    p{margin: 0;}
    iframe{width: 100%;height: 100%}
</style>
<?php
    while (have_posts()) : the_post();
        $check_link = substr(get_the_content('More', true), 0, 8);
        if ($check_link != 'https://') {
            if(!is_user_logged_in()){ // Nếu chưa đăng nhập
        ?>
            <div style="text-align:center; padding:5px 0 10px 0;">
                Vui lòng <a target="_parent" href="<?php echo get_page_link(get_option(SHORT_NAME . "_pageLoginID")); ?>">đăng nhập</a> để xem được video này.
            </div>
        <?php
            } else {
                global $current_user;
                get_currentuserinfo();
                $default_membership = intval(get_option(SHORT_NAME . "_membershipID"));
                $membership = esc_attr(get_the_author_meta('user_membership', $current_user->ID));
                $today = date("Y-m-d H:i:s");
                $expire_date = esc_attr(get_the_author_meta('membership_expire', $current_user->ID));
                $categories = get_the_category();
                $level = 0;
                foreach ($categories as $category) {
                    $tag_meta = get_option("tag_{$category->term_id}");
                    $product_id = intval($tag_meta['product']);
                    if($product_id > 0){
                        $level = get_post_meta($product_id, 'level', true);
                        break;
                    }
                }
                $membership_level = get_post_meta($membership, 'level', true);
                if($membership > 0 and $membership != $default_membership and 
                    in_array($level, $membership_level) and $today < $expire_date){ // Kiem tra cap do tai khoan
                    include 'template-video_premium.php';
                } else { // Kiểm tra xem đã đăng ký khoá học này chưa
                    $is_purchased = false;
                    foreach ($categories as $category) {
                        $tag_meta = get_option("tag_{$category->term_id}");
                        $product_id = intval($tag_meta['product']);
                        if(check_product_in_order($product_id)){
                            $is_purchased = TRUE;
                            break;
                        }
                    }
                    if(!$is_purchased){ // Nếu chưa đăng ký
                        $video_type = get_post_meta($post->ID, 'video_type', true);
                        if($video_type == 'regular'){ // Chi can dang nhap la xem duoc
                            include 'template-video_premium.php';
                        } else { // Phai mua premium
                        ?>				
                            <div style="text-align:center; padding:5px 0 10px 0;">
                                <?php printf(__('Đây là khóa học tính phí,bạn có thể mua từng phần của khóa học hoặc trọn bộ chương trình của chúng tôi để tiếp tục theo dõi <a target="_parent" href="%s">tại đây</a>.', SHORT_NAME), "http://edu.anabim.com/shop") ?>
                            </div>
                        <?php
                        }
                    } else { // Nếu đã đăng ký
                        include 'template-video_premium.php';
                    }
                }
            }
        } else { // Xem video free
?>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.min.js"></script>
<script type="text/javascript">
    var frame = window.parent.document.getElementById('player-frame');
    var url = "<?php echo trim(strip_tags($post->post_content)); ?>";
    if(url.lastIndexOf('youtu.be') !== -1){
        url = url.replace("youtu.be", "www.youtube.com/embed");
    } else {
        url = url.replace("watch?v=", "embed/");
    }
    jQuery(function (){
        jQuery(frame).attr('src', url);
    });
</script>
<?php
        }
    endwhile;
} else {
    get_header();
    ?>

    <section id="main">
        <div class="container-fluid">
            <div class="row">
                <div id="main-content" class="col-sm-9">
                    <?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumbs">','</div>'); } ?>
                    
                    <div id="content" class="site-content" role="main">
                        <?php
                        // Start the Loop.
                        while (have_posts()) : the_post();

                            /*
                             * Include the post format-specific template for the content. If you want to
                             * use this in a child theme, then include a file called called content-___.php
                             * (where ___ is the post format) and that will be used instead.
                             */
                            get_template_part('content', get_post_format());

                            // Previous/next post navigation.
                            ppo_post_nav();

                            // If comments are open or we have at least one comment, load up the comment template.
                            if (comments_open() || get_comments_number()) {
                                comments_template();
                            }
                        endwhile;
                        ?>
                    </div><!-- #content -->
                </div><!-- #main-content -->

                <?php get_sidebar(); ?>
            </div>
        </div>
    </section><!-- #main -->

    <?php
    get_footer();
}