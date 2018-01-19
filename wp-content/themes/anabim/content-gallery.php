<?php
/**
 * The template for displaying posts in the Gallery post format
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header">
        <?php if (in_array('category', get_object_taxonomies(get_post_type())) && ppo_categorized_blog()) : ?>
            <div class="entry-meta">
                <span class="cat-links"><?php echo get_the_category_list(_x(', ', 'Used between list items, there is a space after the comma.', SHORT_NAME)); ?></span>
            </div><!-- .entry-meta -->
            <?php
        endif;

        if (is_single()) :
            the_title('<h1 class="entry-title">', '</h1>');
        else :
            the_title('<h3 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>');
        endif;
        ?>

        <div class="entry-meta">
            <span class="post-format">
                <a class="entry-format" href="<?php echo esc_url(get_post_format_link('gallery')); ?>"><?php echo get_post_format_string('gallery'); ?></a>
            </span>

            <?php ppo_posted_on(); ?>

            <?php if (!post_password_required() && ( comments_open() || get_comments_number() )) : ?>
                <span class="comments-link"><?php comments_popup_link(__('Leave a comment', SHORT_NAME), __('1 Comment', SHORT_NAME), __('% Comments', SHORT_NAME)); ?></span>
            <?php endif; ?>

            <?php edit_post_link(__('<i class="fa fa-pencil"></i> Chỉnh sửa', SHORT_NAME), '<span class="edit-link">', '</span>'); ?>
        </div><!-- .entry-meta -->
    </header><!-- .entry-header -->

    <div class="entry-content">
        <?php
        /* translators: %s: Name of current post */
        the_content(sprintf(
                        __('Continue reading %s <span class="meta-nav">&rarr;</span>', SHORT_NAME), the_title('<span class="screen-reader-text">', '</span>', false)
        ));
        
        show_share_socials();

        wp_link_pages(array(
            'before' => '<div class="page-links"><span class="page-links-title">' . __('Trang:', SHORT_NAME) . '</span>',
            'after' => '</div>',
            'link_before' => '<span>',
            'link_after' => '</span>',
        ));
        ?>
    </div><!-- .entry-content -->

    <?php the_tags('<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>'); ?>
</article><!-- #post-## -->
