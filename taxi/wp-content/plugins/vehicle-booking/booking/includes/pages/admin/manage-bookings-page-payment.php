<?php
if ( isset( $_POST['update_payment_new'] ) ) {
	$booking_id = $_POST['booking_id'];
	
	do_action( 'simontaxi_booking_statuschange_payment_before', $booking_id, $new_status );
	$to = $_POST['email'];

	global $wpdb;
	
	if ( empty( $_POST['payment_status'] ) ) {
		$payment_status = $_POST['payment_status_current'];
	} else {
		$payment_status = $_POST['payment_status'];
	}
	$payments_data = array(
		'amount_payable' => $_POST['amount_payable'],
		'amount_paid' => $_POST['amount_paid'],
		'payment_status' => $payment_status,
		'payment_status_updated' => date('Y-m-d H:i:s')
	);
	$wpdb->update( $wpdb->prefix  . 'st_payments', $payments_data , array( 'booking_id' => $booking_id ) );

	do_action( 'simontaxi_booking_statuschange_payment_after', $booking_id, $new_status );
	
	simontaxi_set_message( 'payment_messages', array( 'success' => esc_html__( 'Payment updated successfully', 'simontaxi' ) ) );
	
	$change_status = $_POST['change_status'];
	$redirect_to = admin_url( "admin.php?page=manage_bookings&change_status=$change_status&booking_id=$booking_id" );
	// simontaxi_clear_errors();
	wp_safe_redirect( $redirect_to );
	die();
}
?>
<?php 
$errors = simontaxi_get_errors( 'payment_errors' );
if ( ! empty( $errors ) ) {
	simontaxi_print_array_info( $errors );
}
$messages = simontaxi_get_messages( 'payment_messages' );
if ( ! empty( $messages ) ) {
	simontaxi_print_array_info( $messages, 'success', array( 'alert', 'alert-success' ) );
}
?>
<form action="" method="POST">
	<input type="hidden" name="change_status" value="<?php echo $new_status; ?>">
	<input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
	<input type="hidden" name="email" value="<?php echo $contact['email']; ?>">
<h4><?php esc_html_e( 'Payment Details:', 'simontaxi' )?></h4>
<table>
<tr><td>
<h5><?php esc_html_e( 'Amount Payable:', 'simontaxi' )?></h5>
<input type="number" name="amount_payable" id="amount_payable" value="<?php echo esc_html( $booking['amount_payable'] ); ?>" step="0.01">
</td>
<td>
<h5><?php esc_html_e( 'Amount Paid:', 'simontaxi' )?></h5>
<input type="number" name="amount_paid" id="amount_paid" value="<?php echo esc_html( $booking['amount_paid'] ); ?>" step="0.01">
&nbsp;
<?php
$payment_status = $booking['payment_status']
?>
<input type="hidden" name="payment_status_current" id="payment_status_current" value="<?php echo esc_html( $payment_status ); ?>">
<select name="payment_status" id="payment_status">
	<option value=""><?php esc_html_e( 'Change payment status to', 'simontaxi' ); ?></option>
	<option value="success" <?php if( 'success' == $payment_status ) echo 'selected'; ?>><?php esc_html_e( 'Paid', 'simontaxi' ); ?></option>
	<option value="cancelled" <?php if( 'cancelled' == $payment_status ) echo 'selected'; ?>><?php esc_html_e( 'Cancelled', 'simontaxi' ); ?></option>
	<option value="refunded" <?php if( 'refunded' == $payment_status ) echo 'selected'; ?>><?php esc_html_e( 'Refunded', 'simontaxi' ); ?></option>
	<option value="pending" <?php if( 'pending' == $payment_status ) echo 'selected'; ?>><?php esc_html_e( 'Pending', 'simontaxi' ); ?></option>
</select>
<br><small><?php esc_html_e( 'Current Payment Status: '); echo '<b>' . $payment_status . '</b>'; ?></small>
</td>
</tr>

<tr><td>
<h5><?php esc_html_e( 'Gateway:', 'simontaxi' )?></h5>
<input type="text" name="payment_method" id="payment_method" value="<?php echo esc_html( $booking['payment_method'] ); ?>" disabled>
</td>
<td>
<h5><?php esc_html_e( 'Current Status:', 'simontaxi' )?></h5>
<input type="text" value="<?php echo esc_html( $booking['payment_status'] ); ?>" disabled>
</td>
</tr>
</table>
<input type="submit" class="button button-primary button-large" value="Update Payment" name="update_payment_new"/>
</form>