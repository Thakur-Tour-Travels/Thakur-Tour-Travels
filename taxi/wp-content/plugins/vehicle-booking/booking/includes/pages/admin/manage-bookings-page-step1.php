<?php
if ( isset( $_POST['update_location'] ) ) {
	$new_status = $_POST['change_status'];
	$booking_id = $_POST['booking_id'];
	do_action( 'simontaxi_booking_statuschange_before', $booking_id, $new_status );

	$errors = array();
	$pickup_location = $_POST['pickup_location'];
	if ( empty( $pickup_location ) ) {
	  $errors['pickup_location'] = esc_html__( 'Please enter pickup location', 'simontaxi' );
	}
	
	$drop_location = $_POST['drop_location'];
	if ( empty( $drop_location ) ) {
	  $errors['drop_location'] = esc_html__( 'Please enter drop location', 'simontaxi' );
	}
	
	$pickup_date = $_POST['pickup_date'];
	if ( empty( $pickup_date ) ) {
	  $errors['pickup_date'] = esc_html__( 'Please enter pickup date', 'simontaxi' );
	}
	
	$pickup_time = $_POST['pickup_time_hours'] . ':' . $_POST['pickup_time_minutes'];
	if ( $pickup_time == ':' ) {
	  $errors['pickup_time'] = esc_html__( 'Please select pickup time', 'simontaxi' );
	}
	$errors = apply_filters( 'simontaxi_flt_admin_step1_errors', $errors );
	simontaxi_set_error( 'location_errors', $errors );
	if ( empty( $errors ) ) {
		global $wpdb;
		
		$booking_data = array(
			'pickup_location' => $_POST['pickup_location'],
			'drop_location' => $_POST['drop_location'],
			'pickup_date' => date( 'Y-m-d', strtotime( $_POST['pickup_date'] ) ),
			'pickup_time' => $pickup_time,
		);
		$wpdb->update( $wpdb->prefix  . 'st_bookings', $booking_data , array( 'ID' => $booking_id ) );
		
		do_action( 'simontaxi_booking_statuschange_after', $booking_id, $new_status );
		
		simontaxi_set_message( 'location_messages', array( 'success' => esc_html__( 'Location updated successfully', 'simontaxi' ) ) );
		
		$change_status = $_POST['change_status'];
		$redirect_to = admin_url( "admin.php?page=manage_bookings&change_status=$change_status&booking_id=$booking_id" );
		// simontaxi_clear_errors();
		wp_safe_redirect( $redirect_to );
		die();
	}
}
?>
<?php 
$errors = simontaxi_get_errors( 'location_errors' );
if ( ! empty( $errors ) ) {
	simontaxi_print_array_info( $errors );
}
$messages = simontaxi_get_messages( 'location_messages' );
if ( ! empty( $messages ) ) {
	simontaxi_print_array_info( $messages, 'success', array( 'alert', 'alert-success' ) );
}
wp_enqueue_script( 'simontaxi-googleapis' );
?>
<form action="" method="POST">
	<input type="hidden" name="change_status" value="<?php echo $new_status; ?>">
	<input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
	<input type="hidden" name="email" value="<?php echo $contact['email']; ?>">
	<!--<input type="hidden" name="fulldet" value="<?php echo json_encode( $booking ); ?>">-->
	<h4><?php esc_html_e( 'Booking Details:', 'simontaxi' )?></h4>
	<table>
	<tr>
		<td>
		<h5><?php echo simontaxi_get_pickuppoint_title(); ?></h5>
		<input name="pickup_location" id="pickup_location" value="<?php echo simontaxi_get_address( $booking, 'pickup_location' ); ?>" onclick="initialize(this.id)">
		<input type="hidden" name="pickup_location_country" id="pickup_location_country" value="<?php echo simontaxi_get_value( $booking, 'pickup_location_country' ); ?>">
		</td>
		<td>
		<h5><?php echo simontaxi_get_dropoffpoint_title(); ?></h5>
		<input name="drop_location" id="drop_location" value="<?php echo simontaxi_get_address( $booking, 'drop_location' ); ?>" onclick="initialize(this.id)">
		<input type="hidden" name="drop_location_country" id="drop_location_country" value="<?php echo simontaxi_get_value( $booking, 'drop_location_country' ); ?>">
		</td>
	</tr>
	
	<tr>
		<td>
		<h5><?php echo simontaxi_get_pickupdate_title(); ?></h5>
		<input type="text" class="st_datepicker_limit" name="pickup_date" id="pickup_date" value="<?php echo esc_html( simontaxi_date_format( $booking['pickup_date'] ) ); ?>" readonly>
		</td>
		<td>
		<h5><?php echo simontaxi_get_pickuptime_title(); ?></h5>
		<?php
		$parts =  explode( ':', $booking['pickup_time'] );
		if ( count( $parts ) == 1 ) {
			$parts = str_replace(' ', ':', $booking['pickup_time'] );
			$parts =  explode( ':', $parts );
		}						
		
		$hours = ! empty( $parts[0] ) ? trim( $parts[0] ) : 0;
		$minutes = ! empty( $parts[1] ) ? trim( $parts[1] ) : 0;
		?>
		<select class="show-tick show-menu-arrow" data-size="5" name="pickup_time_hours" id="pickup_time_hours">
			<option value=""><?php esc_html_e( 'Hour', 'simontaxi' ); ?></option>
			<?php for ( $h = 0; $h <= 23; $h++ ) {
				$val = str_pad( $h,2,0, STR_PAD_LEFT);
				$display_val = simontaxi_get_time_display_format( $h );
				$sel = '';
				if ( $val == $hours)
					$sel = ' selected="selected"';
				echo '<option value="' . $val . '" ' . $sel . '>' . $display_val . '</option>';
			}?>
		</select>
		&nbsp;
		<select class="show-tick show-menu-arrow" data-size="5" name="pickup_time_minutes" id="pickup_time_minutes">
			<option value=""><?php esc_html_e( 'Min', 'simontaxi' ); ?></option>
			<?php for ( $m = 0; $m < 60; $m+=5 ) {
				$val = str_pad( $m,2,0, STR_PAD_LEFT);
				$sel = '';
				if ( $val == $minutes)
					$sel = ' selected="selected"';
				echo '<option value="' . $val . '" ' . $sel . '>' . $val . '</option>';
			}?>
		</select>
		</td>
	</tr>
	</table>
	
	<?php do_action( 'simontaxi_booking_other_details_inside_form', $booking ); ?>
	
	
	
	<?php
	do_action('simontaxi_manage_booking_additional',
		array( 
			'booking' => $booking,
		)
	);
	
	?>
	<br><input type="submit" class="button button-primary button-large" value="Update Location"  name="update_location"/>
</form>
<script>
function initialize(id) {

	/**
	12.864162,77.438610

	13.139807,77.711895
	*/
	if ( id == 'drop_location' ) {
		var selected_country = jQuery('#drop_location_country').val();

	} else {
		var selected_country = jQuery('#pickup_location_country').val();
	}

	var options = {
		types: ['(regions)'],
		componentRestrictions: {                        
			country: selected_country,
		},
		language: 'en-GB'
	};
	
    var input = jQuery( '#' + id);
    var autocomplete_my = new google.maps.places.Autocomplete(input[0], options);
		
    google.maps.event.addListener(autocomplete_my, 'place_changed', function () {
        place = autocomplete_my.getPlace();
		// console.log( place );
        jQuery( '#' + id + '_lat' ).val(place.geometry.location.lat() );
        jQuery( '#' + id + '_lng' ).val(place.geometry.location.lng() );

        if (place.address_components) {
            stateID = place.address_components[0] && place.address_components[0].long_name || '';
            countryID = place.address_components[3] && place.address_components[3].short_name || '';
            jQuery( '#' + id + '_country' ).val( countryID );
        }
        if ( place.name ) {
			stateID = place.name;
		} else {
			stateID = place.formatted_address;
		}
		stateID = place.formatted_address;
        input.blur();
        input.val(stateID);
    });
}
</script>