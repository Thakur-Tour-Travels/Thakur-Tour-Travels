<?php
/**
 * This template is used to display the 'user_bookings' with [simontaxi_payment_history]
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  simontaxi_payment_history
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;	
}

$current_user = wp_get_current_user();

global $wpdb;
$bookings = $wpdb->prefix . 'st_bookings';
$payments = $wpdb->prefix . 'st_payments';
if ( isset( $_REQUEST['invoice_id'] ) ) {
	$ref = explode( '-', $_REQUEST['invoice_id'] );
	$booking_id = $ref[0];
	$payment_ref = $ref[1];


	$invoice = $wpdb->get_results( 'SELECT *, ' . $bookings . '.reference as booking_ref, ' . $payments . '.reference as payment_ref  FROM ' . $bookings . ' INNER JOIN ' . $payments . ' ON ' . $bookings . '.ID = ' . $payments . '.booking_id WHERE ' . $bookings . '.user_id=' . get_current_user_id() . ' AND ' . $bookings . '.ID=' . $booking_id );

	if ( empty( $invoice ) ) {
		esc_html_e( 'NO INVOICE FOUND', 'simontaxi' );
	} else {
		$invoice = $invoice[0];
		$user_det = simontaxi_filter_gk( ( array ) get_user_meta( $invoice->user_id ) );
		$fail_message='';
		/**
		 * @since 2.0.8
		 */
		$template = 'booking/includes/pages/purchase_invoice.php';
		if ( simontaxi_is_template_customized( $template ) ) {
			include_once( simontaxi_get_theme_template_dir_name() . $template );
		} else {
			include_once( apply_filters( 'simontaxi_locate_purchase_invoice', SIMONTAXI_PLUGIN_PATH . $template ) );
		}
	}
} else {
	$per_page = 5;
	$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
	if ( $page > 1) {
	$offset = $page * $per_page - $per_page;
	} else {
	$offset = 0;
	}
	$query_all = 'SELECT * FROM ' . $payments . ' WHERE user_id='.get_current_user_id() . ' AND `amount_paid` != 0 ';
	$query = $query_all . ' LIMIT ' . $per_page . ' OFFSET ' . $offset;
	//echo $query;
	$results = $wpdb->get_results( $query );
?>

<!-- Booking Form -->
<?php if ( ! empty( $wp_error->errors ) ) { ?>
<div class="alert alert-danger">
<ul><?php echo implode( '</li><li>', $wp_error->get_error_messages() ); ?></ul>
</div>
<?php } ?>
<?php 
/**
 * @since 2.0.8
 */
$template = '/booking/includes/pages/user_left.php';
if ( simontaxi_is_template_customized( $template ) ) {
	include_once( simontaxi_get_theme_template_dir_name() . $template );
} else {
	include_once( SIMONTAXI_PLUGIN_PATH . $template );
}
?>
<div class="st-booking-block1 st-admin-booking-block">
<div class="tab-content">
<!-- TAB-1 -->
<div id="st-booktab1" class="tab-pane fade in active">
	<div class="table-responsive">
		<table id="user-payments" class="table table-hover table-history datatable st-table-sm st-table-user-bookings">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Transaction ID', 'simontaxi' ); ?></th>
				<th><?php esc_html_e( 'Date', 'simontaxi' ); ?></th>
				<th><?php esc_html_e( 'Method', 'simontaxi' ); ?></th>
				<th><?php esc_html_e( 'Amount', 'simontaxi' ); ?></th>
				<th><?php esc_html_e( 'Invoice', 'simontaxi' ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach( $results as $rows ) { ?>
			<tr>
				<td><?php echo $rows->reference; ?></td>
				<td><?php echo $rows->datetime; ?></td>
				<td><?php echo $rows->payment_method; ?></td>
				<td><?php echo $rows->amount_payable; ?></td>
				<td><a target="_blank" href="<?php echo simontaxi_get_bookingsteps_urls( 'user_payments' ) . '?invoice_id=' . $rows->booking_id . '-' . $rows->reference . '&download_invoice=payments'; ?>" class="btn btn-dark btn-sm"><?php _e( 'Download', 'simontaxi' ); ?></a></td>
			</tr>

		<?php } ?>
		<?php
		$total = count( $wpdb->get_results( $query_all ) );
		if ( $total == 0 ) {
			?>
			<tr>
		<td colspan="7" class="st-center"><?php esc_html_e( 'No Records found', 'simontaxi' ); ?></td></tr>
			<?php
		}
		?>
		<?php if ( $total > $per_page ) { ?>
		<tr>
		<td colspan="7" class="st-center">
		<?php
		echo paginate_links( array(
			'base' => add_query_arg( 'cpage', '%#%' ),
			'format' => '',
			'prev_text' => __( '&laquo;', 'simontaxi' ),
			'next_text' => __( '&raquo;', 'simontaxi' ),
			'total' => ceil( $total / $per_page),
			'current' => $page
		) );
		?>
		</td>
		</tr>
		<?php } ?>

		</tbody>
	</table>
	</div>
</div>

</div>
</div>
<!-- /Booking Form -->
<?php } ?>