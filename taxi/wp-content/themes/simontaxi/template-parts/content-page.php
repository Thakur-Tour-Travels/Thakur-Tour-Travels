<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Simontaxi
 */

?>
<div id="post-<?php the_ID(); ?>" <?php post_class( 'st-blog center-block st-blog-lg' ); ?>>
	
	<?php
	$post_id = get_the_ID();
	if ( has_post_thumbnail( $post_id ) ) {
	?>
	<div class="st-blog-img">
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
		<?php the_post_thumbnail( 'simontaxi-featured', array( 'class' => 'img-responsive' ) ); ?>
		</a>
	</div>
	<?php } ?>
	
	<div class="st-blog-content">
		<?php
		if ( get_theme_mod( 'banner-show-hide', 'show' ) === 'hide' ) {
			if ( is_single() ) :
				the_title( '<a href="javascript:void(0);" rel="bookmark" class="st-blog-title">', '</a>' );
			else :
				the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="st-blog-title">', '</a>' );
			endif;
		}
		?>		
		<div class="st-blog-text"><?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'simontaxi' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'simontaxi' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
		</div>		
		<?php if ( has_tag() ) { 
			simontaxi_tags();
		} ?>
		<ul class="st-blog-post"><?php simontaxi_edit_link(); ?></ul>
			
	</div>
</div>
<?php
if ( comments_open() || get_comments_number() ) :
	comments_template();
endif;
?>
