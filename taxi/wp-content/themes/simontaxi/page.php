<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
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
		if ( have_posts() ) :

			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content','page' );
			endwhile;

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>
		
		</div>
		<?php get_sidebar();?>
		</div><!-- #main -->
	</div><!-- #primary -->
	</div>

<?php
get_footer();
