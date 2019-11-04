<?php
/**
 * This template is used to display the user billing address form
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  billing address
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;	
}

/**
 * @global wpdb  $wpdb  WordPress database abstraction object.
 */
global $wpdb;
$wp_error = new WP_Error();
$updated = false;
if ( isset( $_POST['cancel_booking'] ) ) {
	$reason_message = $_POST['reason_message'];
	if ( empty( $reason_message ) ) {
		$wp_error->add( 'reason_message', esc_html__( 'Please enter reason to cancel', 'simontaxi' ) );
	}
	
	if ( empty( $wp_error->errors ) ) {
		$ref = explode( '-', $_REQUEST['invoice_id']);
		$booking_id = $ref[0];
		$booking_ref = isset( $ref[1] ) ? $ref[1] : '';

		$bookings = $wpdb->prefix . 'st_bookings';
		$payments = $wpdb->prefix . 'st_payments';

		if ( is_user_logged_in() ) {
		$invoice = $wpdb->get_row( 'SELECT *, ' . $bookings. '.ID as booking_id, ' . $bookings. '.reference as booking_ref, ' . $payments . '.reference as payment_ref  FROM ' . $bookings . ' INNER JOIN ' . $payments. ' ON ' . $bookings . '.ID = ' . $payments . '.booking_id WHERE ' . $bookings . '.user_id='.get_current_user_id() . ' AND ' . $bookings . '.ID=' . $booking_id );
		} else {
			$invoice = $wpdb->get_row( 'SELECT *, ' . $bookings. '.ID as booking_id, ' . $bookings. '.reference as booking_ref, ' . $payments . '.reference as payment_ref  FROM ' . $bookings . ' INNER JOIN ' . $payments. ' ON ' . $bookings . '.ID = ' . $payments . '.booking_id WHERE ' . $bookings . '.ID=' . $booking_id );
		}
		
		if ( ! empty( $invoice ) ) {
			$update = array(
				'reason_message' => $reason_message,
				'status' => 'cancelled',
				'status_updated' => date_i18n( 'Y-m-d H:i:s' ),
			);
			
			if ( is_user_logged_in() ) {
				$wpdb->update( $wpdb->prefix . 'st_bookings', $update, array( 'ID' => $booking_id, 'user_id' => get_current_user_id() ) );
			} else {
				$wpdb->update( $wpdb->prefix . 'st_bookings', $update, array( 'ID' => $booking_id ) );
			}
			simontaxi_set_message( 'success', esc_html__( 'Booking cancelled successfully', 'simontaxi' ) );
			
			$redirect_to = simontaxi_get_bookingsteps_urls( 'user_bookings' );
			wp_safe_redirect( $redirect_to );
		} else {
			$wp_error->add( 'not_found', esc_html__( 'Sorry booking not found', 'simontaxi' ) );
		}
	}
}
$current_user = wp_get_current_user();
$user_meta = simontaxi_filter_gk( ( array ) get_user_meta(  $current_user->ID ) );
//print_r( $user_meta);
?>
<!-- Booking Form -->
<?php if ( ! empty( $wp_error->errors ) ) { ?>
<div class="alert alert-danger">
<ul><?php echo implode( '</li><li>', $wp_error->get_error_messages() ); ?></ul>
</div>
<?php }
/**
 * @since 2.0.8
 */
$template = 'booking/includes/pages/user_left.php';
if ( simontaxi_is_template_customized( $template ) ) {
	include_once( simontaxi_get_theme_template_dir_name() . $template );
} else {
	include_once( apply_filters( 'simontaxi_locate_user_left', SIMONTAXI_PLUGIN_PATH . $template ) );
}
?>
<div class="st-booking-block st-admin-booking-block">
	<div class="tab-content">
		<!-- TAB-1 -->
		<div id="st-booktab1" class="tab-pane fade in active">
			<form class="st-booking-form row" id="update_user_account" method="POST" action="">
				<div class="form-group col-sm-12">
					<label for="billing_firstname"><?php esc_html_e( 'Reason', 'simontaxi' ); ?></label>
					<div class="inner-addon right-addon">
						<textarea class="form-control" name="reason_message" id="reason_message" placeholder="<?php esc_html_e( 'Reason', 'simontaxi' ); ?>"><?php echo $invoice->reason_message; ?></textarea>
					</div>
				</div>

				<div class="col-sm-12">
					<button type="submit" class="btn btn-primary btn-mobile" name="cancel_booking"><?php esc_html_e( 'Cancel', 'simontaxi' ); ?></button>
				</div>

			</form>
		</div>

	</div>
</div>
<!-- /Booking Form -->