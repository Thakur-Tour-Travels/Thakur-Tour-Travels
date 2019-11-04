<?php
/**
 * Custom top banner for this theme.
 *
 * @package Simontaxi
 */

if ( get_theme_mod( 'banner-show-hide', 'show' ) === 'show' ) {
	$image = '';
	if ( get_theme_mod( 'banner-image' ) !== '' ) {
		$image = wp_get_attachment_image_src( get_theme_mod( 'banner-image' ), 'full' );
		$image = $image[0];
	}
	if ( NULL === $image ) {
		$image = get_template_directory_uri() . '/images/inner-banner.png';
	}
?>
<!-- Inner Banner -->
<div class="st-inner-banner" style="background: rgba(0, 0, 0, 0) url('<?php echo esc_url( $image );?>');">
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<h2 class="st-inner-heading animated fadeInUp">
				<?php
				if ( is_page() || is_single() || is_singular() || is_tag() || is_category() ) {
					if( is_tag() ) :
						single_tag_title( esc_html__( 'Tag: ', 'simontaxi' ) );
					elseif( is_category() ) :
						single_cat_title( esc_html__( 'Category: ', 'simontaxi' ) );
					else :
						the_title();
					endif;
				} elseif ( is_search() ) {
					printf( esc_html__( 'Search Results for: %s', 'simontaxi' ), '<span>' . get_search_query() . '</span>' );
				} else {
					if ( is_404() ) {
						esc_html_e( 'Page not found', 'simontaxi' );
					} else {
						esc_html_e( 'Blog Listing', 'simontaxi' );
					}
				}?>
				</h2>
			</div>
		</div>
	</div>
</div>
<!-- /Inner Banner -->
<?php } ?>
