<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header">
        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>

        <div class="entry-meta">
            <?php
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
            <div class="staff-avatar"><img alt="<?php the_title(); ?>" src="<?php get_image_url(); ?>" /></div>
            
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
</article><!-- #post-## -->
