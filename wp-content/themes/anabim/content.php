<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header">
        <?php
        if (is_single()) :
            the_title('<h1 class="entry-title">', '</h1>');
        else :
            the_title('<h3 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>');
        endif;
        ?>

        <div class="entry-meta">
            <?php
            if ('post' == get_post_type())
                ppo_posted_on();

            if (!post_password_required() && ( comments_open() || get_comments_number() )) :
                ?>
                <span class="comments-link"><?php comments_popup_link(__('<i class="fa fa-comment"></i> Bình luận', SHORT_NAME), __('<i class="fa fa-comment"></i> 1 Bình luận', SHORT_NAME), __('<i class="fa fa-comment"></i> % Bình luận', SHORT_NAME)); ?></span>
                <?php
            endif;

            edit_post_link(__('<i class="fa fa-pencil"></i> Chỉnh sửa', SHORT_NAME), '<span class="edit-link">', '</span>');
            ?>
        </div><!-- .entry-meta -->
    </header><!-- .entry-header -->

    <?php if (is_search() or is_archive()) : ?>
        <div class="entry-summary">
            <?php the_excerpt(); ?>
        </div><!-- .entry-summary -->
    <?php else : ?>
        <div class="entry-content">
            <?php
            /* translators: %s: Name of current post */
            the_content( sprintf( __('Xem thêm <span class="meta-nav">&rarr;</span>', SHORT_NAME) ) );
            
            show_share_socials();

            wp_link_pages(array(
                'before' => '<div class="page-links"><span class="page-links-title">' . __('Trang:', SHORT_NAME) . '</span>',
                'after' => '</div>',
                'link_before' => '<span>',
                'link_after' => '</span>',
            ));
            ?>
        </div><!-- .entry-content -->
    <?php endif; ?>

    <?php the_tags('<footer class="entry-meta"><span class="tag-links"><i class="fa fa-tags"></i>', '', '</span></footer>'); ?>
</article><!-- #post-## -->
