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
		<ul class="st-blog-post">
			<?php
			if ( is_sticky() && is_home() && ! is_paged() ) {
				printf( '<li><span class="sticky-post">%s</span></li>', esc_html__( 'Sticky', 'simontaxi' ) );
			}
			?>
			<?php simontaxi_categories(); ?>
			<?php simontaxi_date(); ?>
			<?php simontaxi_edit_link(); ?>
		</ul>
		<?php
		if ( is_single() ) {
			the_title( '<a href="javascript:void(0);" rel="bookmark" class="st-blog-title">', '</a>' );
		} else {
			the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="st-blog-title">', '</a>' );
		}
		?>
		<div class="st-blog-text">
		<?php
		if ( ! is_single() ) {
			the_excerpt();
		} else {
			the_content( sprintf(
				/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'simontaxi' ), array( 'span' => array( 'class' => array('meta-nav') ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );
		}

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'simontaxi' ),
			'after'  => '</div>',
			)
		);
		?>
		</div>
		<?php
		if ( has_tag() ) {
			simontaxi_tags();
		}
		?>
	</div>
</div>
<?php
// If comments are open or we have at least one comment, load up the comment template.
if ( comments_open() || get_comments_number() ) :
	comments_template();
endif;
