<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Simontaxi
 */

?>
<div class="grid-item">
	<!-- Single Blog Component -->
	<div class="st-blog center-block">
		
		<?php
		$post_id = get_the_ID();
		if ( has_post_thumbnail( $post_id ) ) {
		?>
		<div class="st-blog-img">
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
			<?php the_post_thumbnail( 'simontaxi-grid-image', array( 'class' => 'img-responsive' ) ); ?>
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
				the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="st-blog-title">', '</a>' );
			?>
			<div class="st-blog-text"><?php the_excerpt();?></div>
			<?php
			if ( has_tag() ) {
				simontaxi_tags();
			}
			?>
		</div>
	</div>
	<!-- /Single Blog Component -->
</div>
