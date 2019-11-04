<?php
/**
 * Display the page to select vehicle (page is for the slug 'select-cab-type' )
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  Booking step2 page
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$currency = simontaxi_get_currency();

$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
$perpage = simontaxi_get_option( 'records_per_page', 5 );
if ( $page > 1 ) {
	$offset = $page * $perpage - $perpage;
} else {
	$offset = 0;
}
$args = array( 
	'perpage' => $perpage,
	'offset' => $offset,
	'pagination' => true,
);
if ( isset( $_GET['vname'] ) && 'desc' === $_GET['vname'] ) {
	$args['orderby'] = 'title';
	$args['order'] = 'DESC';
} else {
	$args['orderby'] = 'title';
	$args['order'] = 'ASC';
}
$vehicles_arr = simontaxi_get_vehicles( $args );

$vehicles = isset( $vehicles_arr['vehicles'] ) ? $vehicles_arr['vehicles'] : array();
$discount_details = '';

/**
 * User may select different vahicle and user may get more or less discount based on vehicle selecting!!. So we need to unset discount_details if already applied so user may enter coupon.
*/
simontaxi_set_session( 'discount_details', null );

$is_hourly = ( 'hourly' === $booking_step1['booking_type'] ? TRUE : FALSE);
$journey_type = $booking_step1['journey_type'];

$distance_taken_from = simontaxi_get_option( 'distance_taken_from', 'google' );
$vehicle_places = simontaxi_get_option( 'vehicle_places', 'googleall' );
if ( $is_hourly ) {
	$distance=0;
} else {
	$from = $booking_step1['pickup_location'];
	$to = $booking_step1['drop_location'];
	if ( 'predefined' === $distance_taken_from ) {
		/**
		* Which means admin wants to enter places and distances and user only pick those locations for pickup or dropup. So we need to know the distance between pickup and drop-off locaitons from predefined list
		*/
		$distance = simontaxi_get_distance_time( $from, $to, 'distance' );
	} else {
		/**
		* If the "distance_taken_from" is 'google' means there is no restriction on places hence we are using google given distance and same will be used to calculate fare
		*/
		$distance = $booking_step1['distance'];
	}
}

/**
 * Let us display layout according admin settings
*/
$booking_summany_step2 = simontaxi_get_option( 'booking_summany_step2', 'yes' );
$step2_sidebar_position = simontaxi_get_option( 'step2_sidebar_position', 'right' );
$default_breadcrumb_display_step2 = simontaxi_get_option( 'default_breadcrumb_display_step2', 'yes' );

$cols = 8;
if ( 'no' === $booking_summany_step2 ) {
	$cols = 12;
}

?>
<!-- Booking Form -->
<div class="st-section-sm st-grey-bg">
	<div class="container">
		<?php
		if ( 'yes' == simontaxi_get_option('show_numbered_navigation', 'yes') && 'yes' == simontaxi_get_option('show_numbered_navigation_fullwidth', 'yes') ) {
			do_action('simontaxi_bookings_breadcrumb', 'step2'); 
		}
		?>
		<div class="row">
			<?php if ( $booking_summany_step2 == 'yes' && $step2_sidebar_position == 'left' && isset( $booking_step1 ) && ( ! empty( $booking_step1 ) ) ) {
				/**
				 * @since 2.0.8
				 */
				$template = 'booking/includes/booking-steps/right-side.php';
				if ( simontaxi_is_template_customized( $template ) ) {
					require simontaxi_get_theme_template_dir_name() . $template;
				} else {
					require $template = apply_filters( 'simontaxi_locate_rightside', SIMONTAXI_PLUGIN_PATH . $template );
				}
			} 
			do_action( 'simontaxi_sidebar_left_step2' );
			?>
			<div class="col-lg-<?php echo esc_attr( $cols ); ?> col-md-8 col-sm-12">
				<?php
				if ( 'yes' == simontaxi_get_option('show_numbered_navigation', 'yes') && 'no' == simontaxi_get_option('show_numbered_navigation_fullwidth', 'yes') ) {
					do_action('simontaxi_bookings_breadcrumb', 'step2'); 
				}
				?>
				<div class="st-booking-block">
					<?php echo simontaxi_print_errors() ?>
					<!-- Booking Progress -->
					<?php
					if ( 'yes' === $default_breadcrumb_display_step2 ) {
						/**
						 * @since 2.0.8
						 */
						$template = 'booking/includes/booking-steps/bread-crumb.php';
						if ( simontaxi_is_template_customized( $template ) ) {
							include_once( simontaxi_get_theme_template_dir_name() . $template );
						} else {
							include_once( apply_filters( 'simontaxi_locate_bread_crumb', SIMONTAXI_PLUGIN_PATH . $template ) );
						}
					}
					?>
					<!-- end Booking Progress -->
					<?php do_action( 'simontaxi_step2_before_form' ); ?>
					<div id="info-div"></div>
					<div class="tab-content">
						<form id="select-vehicle" action="" method="POST">
						<?php do_action( 'simontaxi_step2_within_form' ); ?>
						<!-- TAB-1 -->
						<div id="st-booktab1" class="tab-pane fade in active">
							<div class="table-responsive">
								<?php
								/**
								 * @since 2.0.9
								 */
								$template = 'booking/includes/booking-steps/step2-vehicles.php';
								if ( simontaxi_is_template_customized( $template ) ) {
									include_once( simontaxi_get_theme_template_dir_name() . $template );
								} else {
									include_once( apply_filters( 'simontaxi_locate_step2_vehicles', SIMONTAXI_PLUGIN_PATH . $template ) );
								}
								?>
							</div>
							<?php if ( simontaxi_terms_page() == 'step2' ) : ?>
							<div class="col-sm-12">
								<div class="input-group st-top40">
									<div>
										<input id="terms" type="checkbox" name="terms" value="option">
										<label for="terms"><span><span></span></span><i class="st-terms-accept"><?php echo simontaxi_terms_text(); ?></i></label>
									</div>
								</div>
							</div>
							<?php endif; ?>

							<?php
							if ( simontaxi_get_option( 'coupon_code_form', 'yes' ) == 'yes' ) :
								if ( isset( $discount_details) && $discount_details == '' ) { ?>
								<div class="row">
									<div id="coupon_div_msg"></div>
									<div class="coupon_div  col-md-8 lc-coupon_div" id="coupon_div">
										<p><strong><?php echo apply_filters( 'simontaxi_filter_coupon_title', esc_html__( 'Coupon', 'simontaxi' ) ); ?></strong></p>
										<div class="input-group">
										<input type="text" name="coupon_code" class="form-control input-lg" id="coupon_code" value="" placeholder="<?php esc_html_e( 'Enter', 'simontaxi' ); ?> <?php echo apply_filters( 'simontaxi_filter_coupon_title', esc_html__( 'Coupon', 'simontaxi' ) ); ?>" autocomplete="off">
										<span class="input-group-addon" name="apply_coupon" id="apply_coupon">
										<?php esc_html_e( 'Apply', 'simontaxi' ); ?> <?php echo apply_filters( 'st_filter_coupon_title', __( 'Coupon', 'simontaxi' ) ); ?>
										</span>
										</div>
									</div>
								</div>
								<?php }
							endif;
							?>
							
							<?php do_action( 'simontaxi_step2_additional_fields' ); ?>

							<div class="st-terms-block">
								<a href="<?php echo apply_filters( 'step2_back_url', simontaxi_get_bookingsteps_urls( 'step1' ) ); ?>" class="btn-dull"><i class="fa fa-angle-double-left"></i> <?php esc_html_e( 'Back', 'simontaxi' ); ?> </a>
								<button type="submit" class="btn btn-primary btn-mobile" name="validtestep2"><?php echo apply_filters( 'simontaxi_filter_booknow_title', esc_html__( 'Book Now', 'simontaxi' ) ); ?></button>
								<?php do_action('simontaxi_step2_other_buttons'); ?>
								<input type="hidden" name="selected_amount" id="selected_amount" value="0">
								<input type="hidden" name="selected_amount_onward" id="selected_amount_onward" value="0">
								<input type="hidden" name="selected_amount_return" id="selected_amount_return" value="0">
							</div>
						</div>
						</form>



					</div>
					<?php do_action( 'simontaxi_step2_after_form' ); ?>
				</div>
			</div>
			<?php if ( $booking_summany_step2 == 'yes' && $step2_sidebar_position == 'right' && isset( $booking_step1 ) && ( ! empty( $booking_step1 ) ) ) {
				/**
				 * @since 2.0.8
				 */
				$template = 'booking/includes/booking-steps/right-side.php';
				if ( simontaxi_is_template_customized( $template ) ) {
					require simontaxi_get_theme_template_dir_name() . $template;
				} else {
					require apply_filters( 'simontaxi_locate_rightside', SIMONTAXI_PLUGIN_PATH . $template );
				}
			} 
			do_action( 'simontaxi_sidebar_right_step2' );
			?>
		</div>
	</div>
</div>
<!-- /Booking Form -->

<script type="text/javascript">
jQuery( '#apply_coupon' ).on( 'click', function (e) {

	var coupon_code = jQuery( '#coupon_code' ).val();
	var selected_amount = jQuery( '#selected_amount' ).val();
	if (jQuery( 'input[name="selected_vehicle"]:checked' ).val() === undefined) {
		e.preventDefault();
		jQuery( '#coupon_div_msg' ).html( '<div class="alert alert-danger"><p><?php echo sprintf( esc_html__( 'Please choose a %s', 'simontaxi' ) , simontaxi_get_label_singular() ); ?></p></div>' );
		return false
	}
	if (coupon_code == '' ) {
		jQuery( '#coupon_div_msg' ).html( '<div class="alert alert-danger"><p><?php esc_html_e( 'Please enter coupon code', 'simontaxi' ); ?></p></div>' );
		return false;
	}
	var data = {
		'action': 'get_coupon_amount',
		'coupon_code': coupon_code,
		'selected_amount': selected_amount
		};
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
	jQuery.post( '<?php echo admin_url( 'admin-ajax.php ' ); ?>', data
		, function (response) {
			var result = jQuery.parseJSON(response);
			if(result['status'] == 'success' ) {
			jQuery( '#coupon_code' ).val( '' );
			jQuery( '#apply_coupon' ).prop( 'disabled', true);
			jQuery( '#coupon_div' ).html( '<div class="alert alert-success">'+result['msg']+'</div>' );
			jQuery( '#coupon_div_msg' ).html( '' );
			} else {
				jQuery( '#coupon_div_msg' ).html( '<div class="alert alert-danger">'+result['msg']+'</div>' );
			}
		});
});
jQuery( '#select-vehicle' ).on( 'submit', function (event) {
	var errors = 0;
	var message = '';
	if (jQuery( 'input[name="selected_vehicle"]:checked' ).val() === undefined) {
		message += '<?php echo sprintf( esc_html__( 'Please choose a %s', 'simontaxi' ) ,simontaxi_get_label_singular() ); ?>';
		errors++;
	}
	<?php if ( simontaxi_terms_page() == 'step2' ) : ?>
	if ( !document.getElementById( 'terms' ).checked ) {
		if ( errors > 0 ) message += '<br>';
		message += '<?php echo esc_html__( 'You should accept Terms of Service to proceed', 'simontaxi' ); ?>';
		errors++;
	}
	<?php endif; ?>
	if( errors == 0 ) {
		jQuery( '#info-div' ).html( '' );
	} else {
		jQuery( '#info-div' ).html( '<div class="alert alert-danger"><p>'+message+'</p></div>' );
		event.preventDefault();
	}

});

function total_fare( amount ) {
	jQuery( '#selected_amount' ).val(amount);
}
</script>