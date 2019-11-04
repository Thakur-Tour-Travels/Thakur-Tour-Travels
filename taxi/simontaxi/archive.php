<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
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
				<?php if ( have_posts() ) : ?>
					<?php if ( get_theme_mod( 'banner-show-hide', 'show' ) === 'hide' ) { ?>
					<header class="page-header">
					<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="archive-description">', '</div>' );
					?>
					</header><!-- .page-header -->
					<?php } ?>
					<?php

					/* Start the Loop */
					while ( have_posts() ) : the_post();

						/*
						* Include the Post-Format-specific template for the content.
						* If you want to override this in a child theme, then include a file
						* called content-___.php (where ___ is the Post Format name) and that will be used instead.
						*/
						get_template_part( 'template-parts/content', 'search' );
					endwhile;
					the_posts_pagination( array(
						'prev_text'          => esc_html__( '&laquo;', 'simontaxi' ),
						'next_text'          => esc_html__( '&raquo;', 'simontaxi' ),
						'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'simontaxi' ) . ' </span>',
						'mid_size' => 2,
					) );

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
