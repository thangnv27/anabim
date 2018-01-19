<?php
/**
 * The template for displaying posts in the Video post format
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
        if ( is_single() ) :
            the_title( '<h1 class="entry-title">', '</h1>' );
        else :
            the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark"><i class="fa fa-youtube-play"></i> ', '</a></h3>' );
        endif;
        ?>
        <div class="entry-meta">
            <?php ppo_posted_on(); ?>

            <?php if (!post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
            <span class="comments-link"><?php comments_popup_link(__('<i class="fa fa-comment"></i> Bình luận', SHORT_NAME), __('<i class="fa fa-comment"></i> 1 Bình luận', SHORT_NAME), __('<i class="fa fa-comment"></i> % Bình luận', SHORT_NAME)); ?></span>
            <?php endif; ?>

            <?php edit_post_link( __( '<i class="fa fa-pencil"></i> Chỉnh sửa', SHORT_NAME ), '<span class="edit-link">', '</span>' ); ?>
        </div><!-- .entry-meta -->
    </header><!-- .entry-header -->

    <?php the_tags( '<footer class="entry-meta"><span class="tag-links"><i class="fa fa-tags"></i>', '', '</span></footer>' ); ?>
</article><!-- #post-## -->
