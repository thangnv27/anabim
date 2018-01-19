<?php
/**
 * The template for displaying posts in the Video post format
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>
<?php
	if ( is_single() ) :
		the_title( '<li class="list-video fl">', '</li>' );
	else :
		the_title( '<li class="list-video fl"><a class="colorfix thickbox" title="' . get_the_title() . '" href="' . esc_url( get_permalink() ) . '?temp_content=video&keepThis=true&TB_iframe=true&height=600&width=1000" rel="bookmark">', '</a></li>' );
	endif;
?>