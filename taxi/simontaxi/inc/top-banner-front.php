<?php
/**
 * Custom top banner for this theme home page.
 *
 * @package Simontaxi
 */

$image = '';
if ( get_theme_mod( 'banner-show-hide-homepage', 'show' ) === 'show' ) {
	if ( get_theme_mod( 'banner-image-homepage' ) !== '' ) {
		$image = wp_get_attachment_image_src( get_theme_mod( 'banner-image-homepage' ), 'full' );
		$image = $image[0];
	}
}
if ( NULL === $image ) {
	$image = get_template_directory_uri() . '/images/home-banner.png';
}
$class = '';
if ( get_theme_mod( 'booking-homepage', 'no' ) === 'yes' ) {
	$class = ' st-home-banner-booking';
}
?>
<!--Home Banner -->
<div class="st-home-banner<?php echo esc_attr( $class );?>" style="background: rgba(0, 0, 0, 0) url('<?php echo esc_url( $image );?>')">
	<div class="container">
		<div class="row">
			<?php
			if ( get_theme_mod( 'booking-homepage' ) === 'yes' ) {
				/**
				 * Arguments for the 'placement' are 'hometop','homeleft','fullpage'
				*/
				echo do_shortcode( '[simontaxi_booking_onhome placement="hometop"]' );
			} else {
			?>
			<div class="col-md-12 text-center">
				<h4 class="st-hero-tag "><?php esc_html_e( 'Best taxi services in your city', 'simontaxi' );?></h4>
				<h1 class="st-hero-title animated fadeInUp"><?php esc_html_e( 'A Reliable Way To Travel', 'simontaxi' );?></h1>
				<?php if ( function_exists( 'booking_step1' ) ) { ?>
				<div>
					<a href="#st-search-form" class="btn btn-primary st-scroll-btn"><?php esc_html_e( 'Book Now', 'simontaxi' );?></a>
				</div>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<!-- /Home Banner -->
