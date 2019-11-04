<?php
/**
 * Template part for displaying search results.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Simontaxi
 */

?>
<div id="post-<?php the_ID(); ?>" <?php post_class( 'st-blog center-block st-blog-lg' ); ?>>
	
	<?php $post_id = get_the_ID();	?>
	
	
	<div class="st-blog-content">
		<ul class="st-blog-post">
			<?php simontaxi_categories(); ?>
			<?php simontaxi_date(); ?>
			<?php simontaxi_edit_link(); ?>
			
		</ul>
		<?php the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="st-blog-title">', '</a>' ); ?>		
		<div class="st-blog-text"><?php the_excerpt(); ?></div>
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
?>
