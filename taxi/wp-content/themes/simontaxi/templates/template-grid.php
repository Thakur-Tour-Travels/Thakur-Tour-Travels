<?php
/**
 * Template Name: Posts Grid View
 *
 * @package Simontaxi
 */

get_header(); ?>
<!-- Blog Grid with Widget sidebar -->
	<div class="st-section">
		<div class="container">
			<div class="row">
			<!--  Blog Grid -->
			<div class="col-lg-8 col-md-9 col-sm-12">
			<div class="grid">
		<?php		
		$paged = (get_query_var( 'paged' )) ? get_query_var( 'paged' ) : 1;
		$original_query = $wp_query;
		$wp_query = null;
		$args = array( 'post_type' => 'post', 'posts_per_page' => 4, 'paged' => intval( $paged ) );
		$wp_query = new WP_Query( $args );
		
		if ( $wp_query->have_posts() ) :

			/* Start the Loop */
			while ( $wp_query->have_posts() ) : $wp_query->the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', 'grid' );
			endwhile;

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>
		
		
		</div>
		<div class="row">
			<div class="col-sm-12">
				<?php
				// Previous/next page navigation.
				the_posts_pagination( array(
					'prev_text'          => esc_html__( '&laquo;', 'simontaxi' ),
					'next_text'          => esc_html__( '&raquo;', 'simontaxi' ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'simontaxi' ) . ' </span>',
					'mid_size' => 2,
				) );
				?>
			</div>
		</div>
		</div>
		<?php get_sidebar();?>
		</div><!-- #main -->
	</div><!-- #primary -->
	</div>
<?php
get_footer();
