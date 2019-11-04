<?php
if ( isset( $_POST['update_personal'] ) ) {
	$new_status = $_POST['change_status'];
	$booking_id = $_POST['booking_id'];
	do_action( 'simontaxi_booking_statuschange_personal_before', $booking_id, $new_status );

	$errors = array();
	$email = $_POST['email'];
    if ( empty( $email) ) {
        simontaxi_set_error( 'email', esc_html__( 'Please enter email address', 'simontaxi' ) );
    } elseif( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
        simontaxi_set_error( 'email', esc_html__( 'Please enter valid email address', 'simontaxi' ) );
    }
	$first_name = $_POST['first_name'];
	if ( empty( $first_name ) ) {
		simontaxi_set_error( 'first_name', esc_html__( 'Please enter first name', 'simontaxi' ) );
	}
	
	$errors = apply_filters( 'simontaxi_flt_admin_step3_errors', $errors );
	simontaxi_set_error( 'personal_errors', $errors );
	if ( empty( $errors ) ) {
		global $wpdb;
		
		$booking_contacts = array( 'email' => $_POST['email'] );
		$booking_contacts['first_name'] = isset( $_POST['first_name'] ) ? $_POST['first_name'] : '';
        $booking_contacts['last_name'] = isset( $_POST['last_name'] ) ? $_POST['last_name'] : '';
		if ( ! empty( $_POST['mobile_countrycode'] ) ) {
			$booking_contacts['mobile_countrycode'] = $_POST['mobile_countrycode'];
		}
		if ( ! empty( $_POST['mobile'] ) ) {
			$booking_contacts['mobile'] = $_POST['mobile'];
		}
		if ( ! empty( $_POST['company_name'] ) ) {
			$booking_contacts['company_name'] = $_POST['company_name'];
		}
		if ( ! empty( $_POST['company_name'] ) ) {
			$booking_contacts['no_of_passengers'] = $_POST['no_of_passengers'];
		}
		if ( ! empty( $_POST['land_mark_pickupaddress'] ) ) {
			$booking_contacts['land_mark_pickupaddress'] = $_POST['land_mark_pickupaddress'];
		}
		
		$booking_data = array(
			'user_email' => $email,
			'booking_contacts' => json_encode( apply_filters( 'simontaxi_additional_booking_contacts', $booking_contacts ) ),
		);
		$details = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}st_bookings WHERE ID = $booking_id" );
		if ( ! empty( $details ) ) {
			$session_details = json_decode( $details->session_details, true );
			$step3 =  array();
			if ( isset( $session_details[2] ) ) {
				$step3 = $session_details[2];
				/**
				 * Since we are not going to use the session details with index 0,1,2 etc. Let us unset it.
				 *
				 * @since 2.0.9
				 */
				unset($session_details[2]);
			}
			if ( empty( $booking_step3 ) ) {
				$step3 = isset( $session_details['step3'] ) ? $session_details['step3'] : array();
			}
			$session_details['step3'] = array_merge( $_POST, $step3 );
			$booking_data['session_details'] = json_encode( $session_details );
		}
		
		$wpdb->update( $wpdb->prefix  . 'st_bookings', $booking_data , array( 'ID' => $booking_id ) );
		
		do_action( 'simontaxi_booking_statuschange_personalafter', $booking_id, $new_status );
		
		simontaxi_set_message( 'personal_messages', array( 'success' => esc_html__( 'Details updated successfully', 'simontaxi' ) ) );
		
		$change_status = 'personal';
		$redirect_to = admin_url( "admin.php?page=manage_bookings&change_status=$change_status&booking_id=$booking_id" );
		// simontaxi_clear_errors();
		wp_safe_redirect( $redirect_to );
		die();
	}
}
?>
<?php 
$errors = simontaxi_get_errors( 'personal_errors' );
if ( ! empty( $errors ) ) {
	simontaxi_print_array_info( $errors );
}
$messages = simontaxi_get_messages( 'personal_messages' );
if ( ! empty( $messages ) ) {
	simontaxi_print_array_info( $messages, 'success', array( 'alert', 'alert-success' ) );
}

$booking_step3 = isset( $session_details[2] ) ? $session_details[2] : array();
if ( empty( $booking_step3 ) ) {
	$booking_step3 = isset( $session_details['step3'] ) ? $session_details['step3'] : array();
}
?>
<form action="" method="POST">
	<input type="hidden" name="change_status" value="<?php echo $new_status; ?>">
	<input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
	<h4><?php esc_html_e( 'Booking Details:', 'simontaxi' )?></h4>
	<table>
	<tr>
		<td>
		<h5><?php esc_html_e( 'Email', 'simontaxi' ); ?></h5>
		<?php $email = simontaxi_get_value( $booking_step3, 'email' ); ?>
		<input type="text" name="email" id="email" placeholder="<?php esc_html_e( 'Enter email to receive booking confirmation', 'simontaxi' ); ?>" value="<?php echo esc_attr( $email ); ?>">
		</td>
		<td>
		<h5><?php esc_html_e( 'First Name', 'simontaxi' ); ?></h5>
		<?php $first_name = simontaxi_get_value( $booking_step3, 'first_name' ); ?>
		<input type="text" name="first_name" id="first_name" placeholder="<?php echo esc_html__( 'Enter passenger first name', 'simontaxi' ); ?>" value="<?php echo esc_attr( $first_name); ?>">
		</td>
	</tr>
	
	<tr>
		<td>
		<h5><?php esc_html_e( 'Last Name', 'simontaxi' ); ?></h5>
		<?php
		$last_name = simontaxi_get_value( $booking_step3, 'last_name' );
		?>
		<input type="text" name="last_name" id="last_name" placeholder="<?php echo esc_html__( 'Enter passenger last name', 'simontaxi' ); ?>" value="<?php echo esc_attr( $last_name); ?>">
		</td>
		<td>
		<h5><?php esc_html_e( 'Country code', 'simontaxi' ); ?></h5>
		<select id="mobile_countrycode" name="mobile_countrycode" title="<?php esc_html_e( 'Country code', 'simontaxi' ); ?>" class="show-tick show-menu-arrow selectpicker" style="width:150px;">
		<option value=""><?php esc_html_e( 'Country code', 'simontaxi' ); ?></option>
		<?php
		$countryList = simontaxi_get_countries();
		if ( $countryList ) {
			$mobile_countrycode = simontaxi_get_value( $booking_step3, 'mobile_countrycode' );
			foreach ( $countryList as $result) {
				$code = $result->phonecode . '_' . $result->id_countries;
				?>
				<option value="<?php echo $code; ?>" <?php if ( $mobile_countrycode == $code) echo 'selected="selected"'; ?>><?php echo $result->name . ' ( ' . $result->phonecode.' )'; ?> </option>
				<?php
			}
		}
		?>
		</select>
		&nbsp;
		<?php
		$mobile = simontaxi_get_value( $booking_step3, 'mobile' );
		?>
		<input type="text" name="mobile" id="mobile" placeholder="<?php esc_html_e( 'Phone number to receive SMS', 'simontaxi' ); ?>" value="<?php echo esc_attr( $mobile ); ?>">
		</td>
	</tr>
	
	<tr>
		<td>
		<h5><?php esc_html_e( 'Company Name', 'simontaxi' ); ?></h5>
		<?php $email = simontaxi_get_value( $booking_step3, 'email' ); ?>
		<input type="text" name="company_name"  id="company_name" placeholder="<?php esc_html_e( 'Company Name', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $booking_step3, 'company_name' ); ?>">
		</td>
		<td>
		<h5><?php esc_html_e( 'No. of passengers', 'simontaxi' ); ?></h5>
		<input type="text" name="no_of_passengers"  id="no_of_passengers" placeholder="<?php esc_html_e( 'No. of passengers', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $booking_step3, 'no_of_passengers' ); ?>">
		</td>
	</tr>
	
	<tr>
		<td>
		<h5><?php esc_html_e( 'Land Mark / Pickup Address', 'simontaxi' ); ?></h5>
		<textarea name="land_mark_pickupaddress" id="land_mark_pickupaddress" rows="4" placeholder="<?php esc_html_e( 'Enter Land Mark / Pickup Address', 'simontaxi' ); ?>"><?php echo simontaxi_get_value( $booking_step3, 'land_mark_pickupaddress' ); ?></textarea>
		</td>
		<td>
		<h5><?php esc_html_e( 'No. of passengers', 'simontaxi' ); ?></h5>
		<input type="text" name="no_of_passengers"  id="no_of_passengers" placeholder="<?php esc_html_e( 'No. of passengers', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $booking_step3, 'no_of_passengers' ); ?>">
		</td>
	</tr>
	
	</table>
	
	<br><input type="submit" class="button button-primary button-large" value="<?php esc_html_e( 'Update Details', 'simontaxi' ); ?>"  name="update_personal"/>
</form>