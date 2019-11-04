<?php
if ( isset( $_POST['update_booking'] ) ) {
	
	$change_status = $_POST['change_status'];
	if ( empty( $change_status ) ) {
	  simontaxi_set_error( 'change_status', esc_html__( 'Please select status', 'simontaxi' ) );
	}
	$vehicle_no = $_POST['vehicle_no'];
	if ( empty( $vehicle_no ) ) {
	  // simontaxi_set_error( 'vehicle_no', esc_html__( 'Please enter vehicle number', 'simontaxi' ) );
	}
	$errors = simontaxi_get_errors();
	if ( empty( $errors ) ) {
		
		$new_status = $_POST['change_status'];
		$booking_id = $_POST['booking_id'];
		do_action( 'simontaxi_booking_statuschange_before', $booking_id, $new_status );
		
		$reason_message = $_POST['reason_message'];
			
		global $wpdb;
		$data = array();
		$data['status'] = $new_status;
		$data['status_updated'] = date( 'Y-m-d h:i:s' );
		$data['reason_message'] = $reason_message;
		if ( ! empty( $_POST['selected_vehicle'] ) ) {
			$data['selected_vehicle'] = $_POST['selected_vehicle'];
		}
		$data['vehicle_no'] = $_POST['vehicle_no'];
		
		/**
		 * @since 2.0.6
		 */
		if ( in_array( $new_status, apply_filters( 'simontaxi_newstatus_payment_update', array( 'confirmed' ) ) ) ) {
			
			/**
			 * @since 2.0.8
			 */
			do_action( 'simontaxi_booking_confirmed_before', array(
				'booking_id' => $booking_id,
			) );
			/**
			 * @since 2.0.8
			 */
			do_action( 'simontaxi_booking_confirmed_after', array(
				'booking_id' => $booking_id,
			) );
		}
		if ( 'completed' == $new_status ) {
			$new_status = 'success';
			$data['status'] = $new_status;
		}
		$updated = $wpdb->update( $wpdb->prefix  . 'st_bookings', $data , array( 'ID'=>$booking_id));
		/**
		 * To reduce the number of Databse calls we are using options data to store the statistics. So let us update the options table.
		 *
		 * @since 2.0.9
		 */
		simontaxi_update_count( $new_status, '', false );
		$updated = true;
		$sent = false;
		if ( $updated && 'yes' == $_POST['initiate_emails'] ) {		
			$sent = do_action( 'simontaxi_send_email_sms_adminside', $booking_id, $new_status );
		}
		do_action( 'simontaxi_booking_statuschange_after', $booking_id, $new_status );
		simontaxi_set_message( 'success', esc_html__( 'Booking updated successfully', 'simontaxi' ) );
		$change_status = $_POST['change_status'];
		$redirect_to = admin_url( "admin.php?page=manage_bookings&change_status=change&booking_id=$booking_id" );
		// simontaxi_clear_errors();
		wp_safe_redirect( $redirect_to );
		die();
	}
}
?>
<?php echo simontaxi_print_errors() ?>
<form action="" method="POST">
	
	<input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
	
	<h5><?php esc_html_e( 'Select Vehicle', 'simontaxi' ); ?> :</h5> 
	<?php
	$cabs = get_posts( array( 
		'post_type'=>'vehicle',
		// 'post_status'=>'published',
		)
	);
	$selected_vehicle = $booking['selected_vehicle'];
	echo '<select name="selected_vehicle" id="selected_vehicle" class="selectpicker">';
	echo '<option value="">' . esc_html__( 'Please select vehicle' ) . '</option>';
	foreach( $cabs as $cab ) {
		$cab = ( array ) $cab;
		$vehicle_id = $cab['ID'];
		$selected = '';
		if ( $vehicle_id == $selected_vehicle ) {
			$selected = ' selected';
		}
		echo '<option value="' . $vehicle_id . '"' . $selected . '>' . $cab['post_title'] . '</option>';
	}
	echo '</select>';
	?>
	<h4><?php esc_html_e( 'Message to customer', 'simontaxi' )?></h4>
	<h5><?php esc_html_e( 'Write a message to send an email to customer ! Ignore if no message is need to be sent . ', 'simontaxi' )?></h5>
	<textarea class="wp-editor-area" style="height: 100px;width:100%;" autocomplete="off" cols="40" name="reason_message" id="content"></textarea>
	
	<h5><?php esc_html_e( 'Vehicle no. / Car Plate', 'simontaxi' ); ?> :</h5> 
	
	<?php
	$vehicle_no = ! empty( $booking['vehicle_no'] ) ? $booking['vehicle_no'] : get_post_meta( $selected_vehicle, 'vehicle_no', true );
	?>
	<input type="text" name="vehicle_no" value="<?php echo esc_attr( $vehicle_no ); ?>" id="vehicle_no">
	
	<br>
	<small><font color="red"><?php esc_html_e( 'Before updating booking status, make sure if any changes you want to made from other screens.', 'simontaxi' ); ?></font></small>
	
	<br>
	<h5><?php esc_html_e( 'Initiate Emails / SMS', 'simontaxi' ); ?> :</h5> 
	<select name="initiate_emails" id="initiate_emails">
		<option value="no"><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		<option value="yes"><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
	</select>
	<?php
	$booking_statuses = simontaxi_booking_statuses();
	// dd( $booking_statuses, false );
	$new_status = ! empty( $_GET['change_status'] ) ? $_GET['change_status'] : '';
	?>
	<br>
	<h5><?php esc_html_e( 'Booking Status', 'simontaxi' ); ?> :</h5> 
	<select name="change_status" id="change_status">
		<?php
		foreach( $booking_statuses as $bstatus => $value ) {
			if ( $bstatus == $new_status ) {
				echo '<option value="' . $bstatus . '" selected>' . $value . '</option>';
			} else {
				echo '<option value="' . $bstatus . '">' . $value . '</option>';
			}
		}
		?>
	</select>
	<br><b><?php esc_html_e( 'Current Booking Status : ' ); ?></b><?php echo $booking['status']; ?>	
	
	<br><input type="submit" class="button button-primary button-large" value="Update Booking" name="update_booking"/>
</form>