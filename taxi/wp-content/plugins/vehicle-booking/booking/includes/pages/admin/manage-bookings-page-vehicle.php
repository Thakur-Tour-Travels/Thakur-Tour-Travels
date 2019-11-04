<?php
$booking_id = $_GET['booking_id'];
if ( isset( $_POST['update_vehicle'] ) ) {
	
	$booking_id = $_POST['booking_id'];
	do_action( 'simontaxi_booking_statuschange_before', $booking_id, $new_status );
	
	
	global $wpdb;
	
	$selected_vehicle = $_POST['selected_vehicle'];
	if ( empty( $selected_vehicle ) ) {
	  simontaxi_set_error( 'selected_vehicle', esc_html__( 'Please select vehicle', 'simontaxi' ) );
	}
	$vehicle_no = $_POST['vehicle_no'];
	if ( empty( $vehicle_no ) ) {
	  simontaxi_set_error( 'vehicle_no', esc_html__( 'Please enter vehicle number', 'simontaxi' ) );
	}
	$errors = simontaxi_get_errors();
	if ( empty( $errors ) ) {
		$data = array();
		$data['status_updated'] = date( 'Y-m-d h:i:s' );	
		$data['selected_vehicle'] = isset( $_POST['selected_vehicle'] ) ? $_POST['selected_vehicle'] : 0;
		$data['vehicle_no'] = isset( $_POST['vehicle_no'] ) ? $_POST['vehicle_no'] : 0;

		$updated = $wpdb->update( $wpdb->prefix  . 'st_bookings', $data , array( 'ID'=>$booking_id));

		simontaxi_set_message( 'success', esc_html__( 'Vehicle updated successfully', 'simontaxi' ) );
		$change_status = 'vehicle';
		$redirect_to = admin_url( "admin.php?page=manage_bookings&change_status=$change_status&booking_id=$booking_id" );
		// simontaxi_clear_errors();
		wp_safe_redirect( $redirect_to );
		die();
	}	
}
?>
<?php echo simontaxi_print_errors() ?>
<form action="" method="POST">
	
	<input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
		
	<?php $selected_vehicle = $booking['selected_vehicle']; ?>
	
	<?php
	//if( $new_status=='onride' ) {
		$cabs = get_posts( array( 
			'post_type' => 'vehicle',
			)
		);
		echo '<h4>' .esc_html__( 'Choose among the available', 'simontaxi' ) . strtoupper( $booking['vehicle_name']) . '!</h4>';

		if ( empty( $cabs ) ) {
			echo esc_html__( 'Sorry , no ', 'simontaxi' ) . simontaxi_get_default_title() . esc_html__( ' is available ! Still you can start ride !', 'simontaxi' );
		} else {
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
		}
	//}
	?>
	<h5><?php esc_html_e( 'Vehicle no. / Car Plate', 'simontaxi' ); ?> :</h5> 
	<?php
	$vehicle_no = ! empty( $booking['vehicle_no'] ) ? $booking['vehicle_no'] : get_post_meta( $selected_vehicle, 'vehicle_no', true );
	?>
	<input type="text" name="vehicle_no" value="<?php echo esc_attr( $vehicle_no ); ?>" id="vehicle_no">
	
	<br><input type="submit" class="button button-primary button-large" value="<?php esc_html_e( 'Update Vehicle', 'simontaxi' ); ?>" name="update_vehicle"/>
</form>