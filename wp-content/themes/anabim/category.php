<?php
/**
 * The template for displaying Category pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

$term = get_queried_object();
$term_id = $term->term_id;
$tag_meta = get_option("tag_{$term_id}");
$layout = intval($tag_meta['layout']);
$product_id = intval($tag_meta['product']);
$product_title = get_the_title($product_id);
$price = floatval(get_post_meta($product_id, "price", true));
$sale_price = floatval(get_post_meta($product_id, "sale_price", true));
$product_price = $sale_price;
if($price == $sale_price or $sale_price == 0){
    $product_price = $price;
}
if(!in_array($layout, array(1, 2, 3)) and $term->parent > 0){
    wp_redirect(get_category_link($term->parent));
    exit;
}

get_header();

if ($layout == 1) {
    get_template_part('template', 'slider');
    get_template_part('template', 'category');
} elseif ($layout == 2) { // layout khoa hoc online
    ?>

    <section id="main" class="content-area">
        <div class="container-fluid">
            <?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumbs">','</div>'); } ?>

            <div id="content" class="site-content" role="main">

                <?php if (have_posts()) : ?>
                    <header class="archive-header">
                        <h1 class="archive-title"><?php echo single_cat_title('', false); ?></h1>

                        <?php
                        // Show an optional term description.
                        $term_description = term_description();
                        if (!empty($term_description)) :
                            echo '<div class="taxonomy-description">' . do_shortcode($term_description) . '</div>';
                        endif;
                        ?>
                    </header><!-- .archive-header -->

                    <div class="row">
                        <div id="course_online" class="col-sm-9" style="display: none">
                            <ul>
                                <li><a href="#course-video" title="VIDEO">VIDEO</a></li>
                                <li><a href="#course-file" title="File thực hành"><?php _e('File thực hành', SHORT_NAME) ?></a></li>
                                <li><a href="#course-related" title="Khoá học liên quan" style="border-right: none"><?php _e('Khoá học liên quan', SHORT_NAME) ?></a></li>
                                <div class="clearfix"></div>
                            </ul>
                            <div id="course-video">
                                <H4><?php _e('BÀI HỌC MIỄN PHÍ', SHORT_NAME) ?></H4>
                                <ul class="list-fix free-video mb10">
                                    <?php
                                    $loop_free = new WP_Query($query_string . '&orderby=title&order=ASC&s=free');

                                    // Start the Loop.
                                    while ($loop_free->have_posts()) : $loop_free->the_post();
                                        the_title('<li><a class="colorfix" title="' . get_the_title() . '" href="' . esc_url(get_permalink()) . '?temp_content=video" rel="bookmark" target="player-frame">', '</a></li>');
                                    endwhile;
                                    wp_reset_query();
                                    ?>
                                </ul>
                                <h4><?php _e('TẤT CẢ BÀI HỌC', SHORT_NAME) ?></h4>
                                <ul class="list-fix all-video">
                                    <?php
                                    query_posts($query_string . '&orderby=title&order=ASC');

                                    // Start the Loop.
                                    while (have_posts()) : the_post();
                                        the_title('<li><a class="colorfix" title="' . get_the_title() . '" href="' . esc_url(get_permalink()) . '?temp_content=video" rel="bookmark" target="player-frame">', '</a></li>');
                                    endwhile;
                                    wp_reset_query();
                                    ?>
                                </ul>
                            </div>
                            <div id="course-file">
                                <?php
                                if(is_user_logged_in()){
                                    echo do_shortcode(wpautop($tag_meta['course_file']));
                                } else {
                                    printf(__('<p>Vui lòng <a href="%s">đăng nhập</a> trước để tải file.</p>', SHORT_NAME), get_page_link(get_option(SHORT_NAME . "_pageLoginID")) .  '?redirect_to=' . urlencode(getCurrentRquestUrl()) );
                                }
                                ?>
                            </div>
                            <div id="course-related">
                                <?php
                                $related_courses = get_categories(array('parent' => $term->parent, 'orderby' => 'slug'));
                                if(!empty($related_courses)):
                                ?>
                                <ol class="related-fix">
                                    <?php foreach ($related_courses as $cat_test) : ?>
                                    <li><a href="<?php echo get_category_link($cat_test->term_id); ?>" title="<?php echo $cat_test->cat_name; ?>"><?php echo $cat_test->cat_name; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-sm-3 course-online-payment">
                            <div class="course-online-payment-content">
                                <img alt="ANABIM" src="<?php bloginfo('stylesheet_directory'); ?>/images/logo2.jpg" />
                                <div class="text">
                                    <span class="text1"><?php _e('Bản quyền thuộc', SHORT_NAME) ?></span>
                                    <span class="text2">ANABIM EDUCATION</span>
                                </div>
                                <a class="add-to-cart btn btn-warning" href="javascript://" title="<?php _e('Đăng ký học', SHORT_NAME) ?>" 
                                   onclick="AjaxCart.addToCart(<?php echo $product_id; ?>,'<?php echo $product_title; ?>',<?php echo $product_price; ?>)"><i class="fa fa-cart-plus"></i> <?php _e('Đăng ký học', SHORT_NAME) ?></a>
                                <?php /*
                                <a class="add-to-cart btn btn-warning" href="javascript://" title="Đăng ký học" 
                                   onclick="AjaxCart.addToCart(<?php _e($product_id) ?>,'<?php _e($product_title) ?>',<?php _e($product_price) ?>)"><i class="fa fa-cart-plus"></i> Đăng ký học</a>
                                <div class="accordion">
                                    <h3>Thanh toán qua ATM</h3>
                                    <div><?php echo wpautop(stripslashes(get_option('payment_atm'))); ?></div>
                                    <h3>Thanh toán trực tiếp</h3>
                                    <div><?php echo wpautop(stripslashes(get_option('payment_atOffice'))); ?></div>
                                    <h3>Thanh toán qua Ngân Lượng</h3>
                                    <div><?php echo wpautop(stripslashes(get_option('payment_atNganLuong'))); ?></div>
                                </div>
                                */ ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- BEGIN: PLAYLIST -->
                    <div class="row playlist-video">
                        <div class="col-sm-9">
                            <div id="player">
                                <iframe width="100%" height="556" id="player-frame" name="player-frame" frameborder="0" allowfullscreen></iframe>
                                <div class="video-title"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div id="playlist">
                                <?php
                                $catChilds = get_categories(array(
                                    'hide_empty' => 0,
                                    'parent' => $term_id
                                ));
                                if(count($catChilds) > 0):
                                    foreach ($catChilds as $child) {
                                        echo '<h3 class="title">' . $child->name . '</h3>';
                                        echo '<ul>';
                                        query_posts($query_string . '&orderby=title&order=ASC&cat=' . $child->term_id);

                                        // Start the Loop.
                                        while (have_posts()) : the_post();
                                            the_title('<li><a class="colorfix" title="' . get_the_title() . '" href="' . esc_url(get_permalink()) . '?temp_content=video" rel="bookmark" target="player-frame"><i class="fa fa-youtube-play"></i> ', '</a></li>');
                                        endwhile;
                                        wp_reset_query();
                                        echo '</ul>';
                                    }
                                else:
                                    echo '<ul>';
                                    query_posts($query_string . '&orderby=title&order=ASC');

                                    // Start the Loop.
                                    while (have_posts()) : the_post();
                                        the_title('<li><a class="colorfix" title="' . get_the_title() . '" href="' . esc_url(get_permalink()) . '?temp_content=video" rel="bookmark" target="player-frame"><i class="fa fa-youtube-play"></i> ', '</a></li>');
                                    endwhile;
                                    wp_reset_query();
                                    echo '</ul>';
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- END: PLAYLIST -->

                    <div class="fb-comments" data-href="<?php $current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; echo $current_url; ?>" data-width="100%" data-numposts="5" data-colorscheme="light"></div>
                <?php
                else :
                    // If no content, include the "No posts found" template.
                    get_template_part('content', 'none');
                endif;
                ?>
            </div><!-- #content -->
        </div>
    </section><!-- #main -->
    
    <?php
} elseif ($layout == 3) { // layout khoa hoc offline
    ?>

    <section id="main" class="content-area">
        <div class="container-fluid">
            <?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumbs">','</div>'); } ?>

            <div id="content" class="site-content" role="main">
                <h1 class="archive-title"><?php echo single_cat_title('', false); ?></h1>

                <!--BEGIN SLIDER-->
                <div class="project-slider">
                    <ul>
                        <?php
                        $photos = new WP_Query(array(
                            'post_type' => 'photo',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'photo_category',
                                    'field' => 'id',
                                    'terms' => intval($tag_meta['photo']),
                                ),
                            ),
                        ));
                        while ($photos->have_posts()) : $photos->the_post();
                            $album_title = get_the_title();
                            $album_link = get_permalink();
                            $desc = get_short_content(get_the_content(), 400);
                            $images = rwmb_meta( 'photo_featured_image', array(
                                'type' => 'image_advanced',
                                //'size' => 'full_url'
                            ) );
                            foreach ($images as $image) {
                                echo <<<HTML
                                <li>
                                    <a href="{$album_link}" title="{$album_title}">
                                        <img alt="{$album_title}" src="{$image['url']}" />
                                    </a>
                                    <div class="caption">
                                        <span class="title">{$album_title}</span>
                                        <span class="desc">{$desc}</span>
                                    </div>
                                </li>
HTML;
                            }
                        endwhile;
                        wp_reset_query();
                        ?>
                    </ul>
                </div>
                <!--/END SLIDER-->

                <div id="course_tabs" style="display: none">
                    <ul>
                        <li><a href="#course-reason" title="Lý do học"><?php _e('Lý do', SHORT_NAME) ?></a></li>
                        <li><a href="#course-target" title="Mục tiêu khoá học"><?php _e('Mục tiêu', SHORT_NAME) ?></a></li>
                        <li><a href="#learning-method" title="Phương pháp học"><?php _e('Phương pháp', SHORT_NAME) ?></a></li>
                        <li><a href="#course-content" title="Phương pháp học"><?php _e('Nội dung', SHORT_NAME) ?></a></li>
                        <div class="clearfix"></div>
                    </ul>
                    <div id="course-reason"><?php echo wpautop($tag_meta['reason']); ?></div>
                    <div id="course-target"><?php echo wpautop($tag_meta['target']); ?></div>
                    <div id="learning-method"><?php echo wpautop($tag_meta['learning_method']); ?></div>
                    <div id="course-content"><?php echo wpautop($tag_meta['course_content']); ?></div>
                </div>

                <div class="dang-ky-khoa-hoc">
                    <h2 class="title"><?php _e('Đăng ký', SHORT_NAME) ?> <?php echo single_cat_title('', false); ?> offline</h2>
                    <div class="col-sm-8">
                        <?php
                        // Show an optional term description.
                        $term_description = term_description();
                        if (!empty($term_description)) :
                            echo '<div class="taxonomy-description">' . do_shortcode($term_description) . '</div>';
                        endif;
                        ?>
                    </div>
                    <div class="col-sm-4">
                        <?php 
                        $regForm = stripslashes(get_option(SHORT_NAME . "_cf7_reg_offline"));
                        echo do_shortcode($regForm); 
                        ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                    
                <div class="fb-comments" data-href="<?php $current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; echo $current_url; ?>" data-width="100%" data-numposts="5" data-colorscheme="light"></div>

            </div><!-- #content -->
        </div>
    </section><!-- #main -->
    
    <?php
} else {
    ?>
    <section id="main" class="content-area">
        <div class="container-fluid">
            <div class="row">
                <div id="main-content" class="col-sm-9">
                    <?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumbs">','</div>'); } ?>
                    
                    <div id="content" class="site-content" role="main">
                        <?php if (have_posts()) : ?>

                            <header class="archive-header">
                                <h1 class="archive-title"><?php echo single_cat_title('', false); ?></h1>

                                <?php
                                // Show an optional term description.
                                $term_description = term_description();
                                if (!empty($term_description)) :
                                    printf('<div class="taxonomy-description">%s</div>', $term_description);
                                endif;
                                ?>
                            </header><!-- .archive-header -->

                            <?php
                            // Start the Loop.
                            while (have_posts()) : the_post();

                                /*
                                 * Include the post format-specific template for the content. If you want to
                                 * use this in a child theme, then include a file called called content-___.php
                                 * (where ___ is the post format) and that will be used instead.
                                 */
                                get_template_part('content', get_post_format());

                            endwhile;
                            
                            // Previous/next page navigation.
                            getpagenavi();

                        else :
                            
                            // If no content, include the "No posts found" template.
                            get_template_part('content', 'none');
                        endif;
                        ?>
                    </div><!-- #content -->
                </div><!-- #main-content -->
                
                <?php get_sidebar(); ?>
            </div>
        </div>
    </section><!-- #main -->
    
    <?php
}
get_footer();