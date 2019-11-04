<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Simontaxi
 */

get_header(); ?>
<!-- 404 -->
<section class="st-section">
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<img src="<?php echo esc_url( get_template_directory_uri() );?>/images/404.png" alt="" class="img-responsive center-block">
				<h2 class="st-btm50"><?php esc_html_e( 'we could\'t find page you were looking', 'simontaxi' );?></h2>
				<div>
					<a href="<?php echo esc_url( get_home_url() );?>" class="btn btn-primary"><?php esc_html_e( 'Home', 'simontaxi' );?></a>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- /404  -->
<?php
get_footer();
