<?php 
get_header();

get_template_part('template', 'slider');
?>

<section id="course" style="display: none">
    <ul class="kwicks-horizontal clearfix kwicks">	
    <?php
    $i = 0;
    $k = 1;
    $categories = get_categories(array('parent' => 162, 'orderby' => 'slug', 'hide_empty' => 0));
    foreach ($categories as $cat):
        $i++;
        $tag_meta = get_option("tag_{$cat->term_id}");
    ?>        	
        <li id='panel-<?php echo $i; ?>' class='panel-<?php echo $i; ?>'>
            <span class="po-fix">
                <span class="title-slide-fix fl">
                    <a href="<?php echo get_category_link($cat->term_id); ?>"><?php echo $cat->cat_name; ?></a>
                    <br />
                    <div class="des"><?php echo $cat->description; ?></div>
                </span>
                <span class="content-slide-fix fl">
                    <?php echo do_shortcode($tag_meta['description2']); ?>
                    <?php /*if($cat->term_id == 8): ?>
                    <a title="Tiết kiệm thời gian. Học bất cứ khi nào bạn muốn">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon-time.png" alt="Tiết kiệm thời gian. Học bất cứ khi nào bạn muốn" />
                        Tiết kiệm thời gian. Học bất cứ khi nào bạn muốn
                    </a>
                    <a title="Chi phí thấp. Tiết kiệm tiền bạc">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon-money.png" alt="Chi phí thấp. Tiết kiệm tiền bạc" />
                        Chi phí thấp. Tiết kiệm tiền bạc
                    </a>
                    <a title="Học bất cứ đâu. Chỉ cần máy tính kết nối mạng">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon-where.png" alt="Học bất cứ đâu. Chỉ cần máy tính kết nối mạng" />
                        Học bất cứ đâu. Chỉ cần máy tính kết nối mạng
                    </a>
                    <a title="Kho thư viện học liệu video phong phú, chất lượng">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon-book.png" alt="Kho thư viện học liệu video phong phú, chất lượng" />
                        Kho thư viện học liệu video phong phú, chất lượng
                    </a>
                    <?php else: ?>
                    <a title="Đào tạo bài bản theo đúng quy trình BIM">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon-time.png" alt="Đào tạo bài bản theo đúng quy trình BIM" />
                        Đào tạo bài bản theo đúng quy trình BIM
                    </a>
                    <a title="Chi phí ưu đãi. Lợi ích to lớn">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon-money.png" alt="Chi phí ưu đãi. Lợi ích to lớn" />
                        Chi phí ưu đãi. Lợi ích to lớn
                    </a>
                    <a title="Phương pháp đào tạo hiện đại: Online kết hợp Offline">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon-where.png" alt="Phương pháp đào tạo hiện đại: Online kết hợp Offline" />
                        Phương pháp đào tạo hiện đại: Online kết hợp Offline
                    </a>
                    <a title="Kiến thức không giới hạn, chia sẻ kinh nghiệm quý giá">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon-book.png" alt="Kiến thức không giới hạn, chia sẻ kinh nghiệm quý giá" />
                        Kiến thức không giới hạn, chia sẻ kinh nghiệm quý giá
                    </a>
                    <?php endif; ?>
                    <a title="Hỗ trợ download và cài đặt phần mềm bản quyền">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon-download.png" alt="Hỗ trợ download và cài đặt phần mềm bản quyền" />
                        Hỗ trợ download và cài đặt phần mềm bản quyền
                    </a>
                    <a title="Hỗ trợ tư vấn trực tuyến từ đội ngũ chuyên gia ANABIM">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon-work.png" alt="Hỗ trợ tư vấn trực tuyến từ đội ngũ chuyên gia ANABIM" />
                        Hỗ trợ tư vấn trực tuyến từ đội ngũ chuyên gia ANABIM
                    </a>
                    <p><a href="<?php echo get_category_link($cat->term_id); ?>" title="Lựa chọn khoá học">Lựa chọn khoá học</a></p>
                    */ ?>
                    <?php /*foreach (get_categories(array('parent' => $cat->term_id, 'orderby' => 'slug', 'hide_empty' => 0)) as $cat_test) : ?>
                        <a href="<?php echo get_category_link($cat->term_id); ?>"><?php echo $cat_test->cat_name; ?></a>
                    <?php endforeach;*/ ?>
                </span>
                <style type='text/css'>
                    .panel-<?php echo $i; ?>{
                        background:#efefef left top no-repeat url(<?php echo z_taxonomy_image_url($cat->term_id); ?>);
                        background-size: cover;
                    }
                    <?php if(!empty($tag_meta['bg_click'])): ?>
                    .home .kwicks-expanded.panel-<?php echo $i; ?>{
                        background:transparent left top no-repeat url(<?php echo $tag_meta['bg_click']; ?>) !important;
                        background-size: cover;
                    }
                    <?php endif; ?>
                </style>
            </span>             
        </li>
    <?php
        if ($k % 4 == 0) echo '</ul><ul class="kwicks-horizontal clearfix kwicks">';

        $k++;
    endforeach;
    ?>
    </ul>
</section>

<section id="quote">
    <div class="wrap">
        <div class="quote-left">
            <h2 class="title"><?php _e('ĐÀO TẠO BIM', SHORT_NAME); ?></h2>
            <ul class="services">
                <li>
                    <img alt="<?php echo get_option(SHORT_NAME . "_home_text1"); ?>" src="<?php echo get_option(SHORT_NAME . "_home_image1"); ?>" />
                    <a href="<?php echo get_option(SHORT_NAME . "_home_link1"); ?>" title="<?php echo get_option(SHORT_NAME . "_home_text1"); ?>"><?php echo get_option(SHORT_NAME . "_home_text1"); ?></a>
                </li>
                <li>
                    <img alt="<?php echo get_option(SHORT_NAME . "_home_text2"); ?>" src="<?php echo get_option(SHORT_NAME . "_home_image2"); ?>" />
                    <a href="<?php echo get_option(SHORT_NAME . "_home_link2"); ?>" title="<?php echo get_option(SHORT_NAME . "_home_text2"); ?>"><?php echo get_option(SHORT_NAME . "_home_text2"); ?></a>
                </li>
                <li>
                    <img alt="<?php echo get_option(SHORT_NAME . "_home_text3"); ?>" src="<?php echo get_option(SHORT_NAME . "_home_image3"); ?>" />
                    <a href="<?php echo get_option(SHORT_NAME . "_home_link3"); ?>" title="<?php echo get_option(SHORT_NAME . "_home_text3"); ?>"><?php echo get_option(SHORT_NAME . "_home_text3"); ?></a>
                </li>
            </ul>
            <h3 class="mt20 bold font18"><?php _e(get_option(SHORT_NAME . "_latest_post_title")); ?></h3>
            <?php
            echo do_shortcode(stripslashes(get_option(SHORT_NAME . "_latest_post")));
            /*
            <ul class="latest_posts">
                <?php
                $lastest_posts = new WP_Query(array(
                    'post_type' => 'post',
                    'posts_per_page' => 3,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'post_format',
                            'field' => 'slug',
                            'terms' => array(
                                'post-format-aside',
                                'post-format-audio',
                                'post-format-chat',
                                'post-format-gallery',
                                'post-format-image',
                                'post-format-link',
                                'post-format-quote',
                                'post-format-status',
                                'post-format-video'
                            ),
                            'operator' => 'NOT IN'
                        )
                    )
                ));
                while($lastest_posts->have_posts()): $lastest_posts->the_post();
                ?>
                <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark" target="_blank"><?php the_title(); ?></a></li>
                <?php endwhile; wp_reset_query(); */?>
            </ul>
            <div class="quote-content">
                <ul style="display: none">
                    <?php
                    $quotes = new WP_Query(array(
                        'post_type' => 'quote',
                        'posts_per_page' => -1,
                    ));
                    while($quotes->have_posts()): $quotes->the_post();
                    ?>
                    <li><?php echo strip_tags(get_the_content()); ?>
                        <span class="author">— <?php the_title(); ?></span>
                    </li>
                    <?php endwhile; wp_reset_query(); ?>
                </ul>
            </div>
        </div>
        <div class="quote-right" style="background-image: url(<?php echo get_option(SHORT_NAME . "_home_right_banner"); ?>);display: none">
            <!--<div class="quote-right-top">
                <a href="#"><img alt="Banner 1" src="<?php bloginfo('stylesheet_directory'); ?>/data/bim1.jpg" /></a>
            </div>
            <div class="quote-right-half">
                <a href="#"><img alt="Banner 2" src="<?php bloginfo('stylesheet_directory'); ?>/data/bim.jpg" /></a>
            </div>
            <div class="quote-right-half">
                <a href="#"><img alt="Banner 3" src="<?php bloginfo('stylesheet_directory'); ?>/data/bim2.jpg" /></a>
            </div>-->
        </div>
    </div>
</section>

<section id="feedbacks">
    <div class="container-fluid">
        <h2 class="title"><?php _e('CỘNG ĐỒNG ANABIM EDUCATION', SHORT_NAME); ?></h2>
        <div class="row">
            <div class="col-sm-5">
                <div class="feedback-user">
                    <h3><?php _e('CHIA SẺ HỌC VIÊN', SHORT_NAME); ?></h3>
                    <ul style="display: none">
                        <?php
                        $testimonials = new WP_Query(array(
                            'post_type' => 'testimonial',
                            'orderby' => 'rand',
                        ));
                        while ($testimonials->have_posts()) : $testimonials->the_post();
                        ?>
                        <li>
                            <div class="content">
                                <div class="feedback-description"><?php the_content(); ?></div>
                                <a href="<?php bloginfo('siteurl'); ?>/lien-he" title="<?php _e('Chia sẻ cảm nhận của bạn', SHORT_NAME) ?>" target="_blank"><?php _e('Chia sẻ cảm nhận của bạn', SHORT_NAME) ?> &Gt;</a>
                                <span class="author"><?php the_title(); ?></span>
                            </div>
                            <img alt="<?php the_title(); ?>" src="<?php get_image_url(); ?>" />
                        </li>
                        <?php endwhile;wp_reset_query(); ?>
                    </ul>
                </div>
            </div>
            <div class="col-sm-7">
                <div class="feedback-lib">
                    <h3><?php _e('THƯ VIỆN HÌNH ẢNH SẢN PHẨM HỌC VIÊN', SHORT_NAME); ?></h3>
                    <h4><?php _e('CHIÊM NGƯỠNG SẢN PHẨM TỪ HỌC VIÊN ANABIM', SHORT_NAME); ?></h4>
                    <ul>
                        <?php
                        $photos = new WP_Query(array(
                            'post_type' => 'photo',
                            'posts_per_page' => 3,
                        ));
                        while ($photos->have_posts()) : $photos->the_post();
                        ?>
                        <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img alt="<?php the_title(); ?>" src="<?php get_image_url(); ?>" /></a></li>
                        <?php endwhile;wp_reset_query(); ?>
                    </ul>
                    <a target="_blank" href="<?php bloginfo('siteurl'); ?>/hinh-anh" title="<?php _e('Xem đầy đủ gallery hình ảnh sản phẩm tại đây', SHORT_NAME) ?>"><?php _e('Xem đầy đủ gallery hình ảnh sản phẩm tại đây', SHORT_NAME) ?> &Gt;</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>