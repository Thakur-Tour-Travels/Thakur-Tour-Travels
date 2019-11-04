<?php
/**
 * Template Name: Posts List View
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
		$paged = (get_query_var( 'paged' )) ? get_query_var( 'paged' ) : 1;
		$original_query = $wp_query;
		$wp_query = null;
		$args = array( 'post_type' => 'post', 'paged' => intval( $paged ) );
		$wp_query = new WP_Query( $args );
		
		if ( $wp_query->have_posts() ) :

			if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>

			<?php
			endif;

			/* Start the Loop */
			while ( $wp_query->have_posts() ) : $wp_query->the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_format() );
			endwhile;

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>
		
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
		<?php get_sidebar();?>
		</div><!-- #main -->
	</div><!-- #primary -->
	</div>

<?php
get_footer();
