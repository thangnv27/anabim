<?php
/**
 * The template for displaying Category pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
if($_GET['video']=='1')
{
	get_header(); ?>
		<section id="primary" class="content-area">
			<div id="content" class="site-content" role="main">
	
				<?php if ( have_posts() ) : ?>
	
				<header class="archive-header">
					<h1 class="archive-title"><?php printf( __( '%s', 'twentyfourteen' ), single_cat_title( '', false ) ); ?></h1>
	
					<?php
						// Show an optional term description.
						$term_description = term_description();
						if ( ! empty( $term_description ) ) :
							echo '<div class="taxonomy-description">' . $term_description.'</div>';
						endif;
					?>
				</header><!-- .archive-header -->
				<ul class="list-fix pl30 clearfix">
				<?php
						
						query_posts($query_string . '&orderby=title&order=ASC');
						
						// Start the Loop.
						while ( have_posts() ) : the_post();
	
						/*
						 * Include the post format-specific template for the content. If you want to
						 * use this in a child theme, then include a file called called content-___.php
						 * (where ___ is the post format) and that will be used instead.
						 */
						get_template_part( 'content', 'videoana' );
	
						endwhile;
						
						wp_reset_query();												
						
						// Previous/next page navigation.
						twentyfourteen_paging_nav();
	
					else :
						// If no content, include the "No posts found" template.
						get_template_part( 'content', 'none' );
	
					endif;
				?>
		
                </ul>
		<div class="fb-comments pl30" data-href="<?php $current_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; echo $current_url; ?>" data-width="100%" data-numposts="5" data-colorscheme="light"></div>
		
			</div><!-- #content -->
		</section><!-- #primary -->
	
	<?php
	get_sidebar( 'content' );
	get_footer();
?>

<?php 
}

elseif($_GET[temp]=='slide' && !isset($_GET[video])){
	get_template_part( 'category-slide' );
	}
	
else{
	get_header(); ?>
	
		<section id="primary" class="content-area">
			<div id="content" class="site-content" role="main">
	
				<?php if ( have_posts() ) : ?>
	
				<header class="archive-header">
					<h1 class="archive-title"><?php printf( __( 'Category Archives: %s', 'twentyfourteen' ), single_cat_title( '', false ) ); ?></h1>
	
					<?php
						// Show an optional term description.
						$term_description = term_description();
						if ( ! empty( $term_description ) ) :
							printf( '<div class="taxonomy-description">%s</div>', $term_description );
						endif;
					?>
				</header><!-- .archive-header -->
	
				<?php
						// Start the Loop.
						while ( have_posts() ) : the_post();
	
						/*
						 * Include the post format-specific template for the content. If you want to
						 * use this in a child theme, then include a file called called content-___.php
						 * (where ___ is the post format) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );
	
						endwhile;
						// Previous/next page navigation.
						twentyfourteen_paging_nav();
	
					else :
						// If no content, include the "No posts found" template.
						get_template_part( 'content', 'none' );
	
					endif;
				?>
			</div><!-- #content -->
		</section><!-- #primary -->
	
	<?php
	get_sidebar( 'content' );
	
	get_footer();
}
