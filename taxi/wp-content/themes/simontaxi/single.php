<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Simontaxi
 */

get_header(); ?>
	<!-- Blog Grid with Widget sidebar -->
	<div class="st-section">
		<div class="container">
			<div class="row">
			<div class="col-lg-8 col-md-9 col-sm-12 col-xs-12">
			<?php
			while ( have_posts() ) : the_post();
				if ( get_post_type() === 'vehicle' ) {
					get_template_part( 'template-parts/content', get_post_type() );
				} else {
					get_template_part( 'template-parts/content', get_post_format() );
				}

			endwhile; // End of the loop.
			?>
			<?php
			// Previous/next post navigation.
			the_post_navigation( array(
				'next_text' => '<span class="post-title">%title</span>' . '<span class="meta-nav" aria-hidden="true">' . esc_html__( '&raquo;', 'simontaxi' ) . '</span> ' .
					'<span class="screen-reader-text">' . esc_html__( 'Next post:', 'simontaxi' ) . '</span> ',
				'prev_text' => '<span class="meta-nav" aria-hidden="true">' . esc_html__( '&laquo;', 'simontaxi' ) . '</span> ' .
					'<span class="screen-reader-text">' . esc_html__( 'Previous post:', 'simontaxi' ) . '</span> ' .
					'<span class="post-title">%title</span>',
			) );
			
			?>
			</div>
			<?php get_sidebar();?>			
			</div><!-- #row -->
		</div><!-- #container -->
	</div><!-- .st-section -->
<?php
get_footer();
