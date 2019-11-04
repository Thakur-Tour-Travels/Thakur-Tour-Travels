<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @global wpdb  $wpdb  WordPress database abstraction object.
 */
global $wpdb;

$booking_step1 = simontaxi_get_session( 'booking_step1', array() );

/**
 * User may change vehicle so we are unsetting selected vehicle and amount. so that we can caluclate amount once he selects vehicle in next step.
*/
simontaxi_unset_session( 'booking_step2', 'selected_amount' );

$booking_step2 = simontaxi_get_session( 'booking_step2', array() );
if ( empty( $booking_step1 ) ) {
	/**
	 * @since 2.0.8
	 */
	$template = '/booking/includes/pages/redirection-message.php';
	if ( simontaxi_is_template_customized( $template ) ) {
		require simontaxi_get_theme_template_dir_name() . $template;
	} else {
		require SIMONTAXI_PLUGIN_PATH . $template;
	}
	die();
}

$additional_data = array(
	'current_step' => 'step2',
	);
simontaxi_set_session( 'booking_step1', $additional_data );
$booking_step1 = simontaxi_get_session( 'booking_step1', array() );

if ( isset( $_POST['validtestep2'] ) ) {
	
	do_action( 'simontaxi_booking_step2_start_before' );
	
	/**
	 * Let us validate whether the user selects vehicle or not
	 */
	if ( ! isset( $_POST['selected_vehicle'] ) ) {
		simontaxi_set_error( 'selected_vehicle', sprintf( esc_html__( 'Please select %s', 'simontaxi' ) , simontaxi_get_label_singular() ) );
	}
	if ( simontaxi_terms_page() == 'step2' && !isset( $_POST['terms'] ) ) {
		simontaxi_set_error( 'terms', esc_html__( 'You should accept Terms of Service to proceed', 'simontaxi' ) );
	}	

	if ( isset( $_POST['selected_vehicle'] ) ) {
		/**
		 * If user changes amount with browser tools! We are here to validate!!
		 */
		$vehicle_id = $_POST['selected_vehicle'];
		$vehicle = simontaxi_get_vehiclle_details( $vehicle_id );

		if ( ! empty( $vehicle ) ) {
			$fare = simontaxi_get_fare( $vehicle, $booking_step1 );
		} else {
			$fare = 0;
		}
		if ( round( $fare ) !=  round( $_POST['selected_amount'] ) ) {
			simontaxi_set_error( 'selected_amount', esc_html__( 'Something went wrong.', 'simontaxi' ) );
		}
	}
	
	/**
	 * Let us restrict number of vehicles
	 *
	 * @since 2.0.2
	*/
	$selected_vehicle = $_POST['selected_vehicle'];
	$number_of_vehicles_available = get_post_meta( $selected_vehicle, 'number_of_vehicles', true );
	
	if ( ! empty( $number_of_vehicles_available ) && 'yes' === simontaxi_get_option( 'restrict_vehicles_count', 'no' ) ) {
		$bookings = $wpdb->prefix. 'st_bookings';
		$payments = $wpdb->prefix. 'st_payments';
		$confirmed_vehicle_status = simontaxi_get_option( 'confirmed_vehicle_status', 'confirmed' );
		
		$date = simontaxi_get_session( 'booking_step1', date_i18n( 'Y-m-d' ), 'pickup_date' );
		$pickup_date_return = simontaxi_get_session( 'booking_step1', date_i18n( 'Y-m-d' ), 'pickup_date_return' );
		
		$sql = "SELECT COUNT(*) FROM `" . $bookings  ."` INNER JOIN `" . $payments . "` ON `" . $payments . "`.`booking_id`=`" . $bookings . "`.`ID` WHERE `" . $bookings."`.booking_contacts!='' AND `" . $bookings . "`.status='" . $confirmed_vehicle_status . "' AND `" . $bookings . "`.`selected_vehicle` = '" . $selected_vehicle . "' AND `".$bookings."`.pickup_date = '" . $date . "'";
		$bookings_for_the_vehicle = $wpdb->get_var( $sql );
		
		/**
		 * If the time restriction applied it will check below for the condition
		 *
		 * @since 2.0.9
		 */
		$apply_time_restriction = get_post_meta( $selected_vehicle, 'apply_time_restriction', true );
		if ( empty( $apply_time_restriction ) ) {
			$apply_time_restriction = 'no';
		}
		
		if ( 'no' === $apply_time_restriction && ! empty( $number_of_vehicles_available ) && $number_of_vehicles_available <= $bookings_for_the_vehicle  ) {
			simontaxi_set_error( 'selected_vehicle', esc_html__( 'Sorry the selected vehicle not available for booking', 'simontaxi' ) );
		}
		
		/*
		We are checking vehicles availability on return date also, if user trying to book for return journey
		*/
		$journey_type = simontaxi_get_session( 'booking_step1', 'one_way', 'journey_type' );
		if ( in_array( $journey_type, apply_filters( 'simontaxi_twoway_other_tabs_step2', array( 'two_way' ) ) ) ) {
			$sql = "SELECT COUNT(*) FROM `" . $bookings  ."` INNER JOIN `" . $payments . "` ON `" . $payments . "`.`booking_id`=`" . $bookings . "`.`ID` WHERE `" . $bookings."`.booking_contacts!='' AND `" . $bookings . "`.status='" . $confirmed_vehicle_status . "' AND `" . $bookings . "`.`selected_vehicle` = '" . $selected_vehicle . "' AND `".$bookings."`.pickup_date = '" . $pickup_date_return . "'";
			$bookings_for_the_vehicle = $wpdb->get_var( $sql );

			$number_of_vehicles_available = get_post_meta( $selected_vehicle, 'number_of_vehicles', true );
			
			/**
			 * If the time restriction applied it will check below for the condition
			 *
			 * @since 2.0.9
			 */
			$apply_time_restriction = get_post_meta( $vehicle->ID, 'apply_time_restriction', true );
			if ( empty( $apply_time_restriction ) ) {
				$apply_time_restriction = 'no';
			}
			if ( 'no' === $apply_time_restriction && ! empty( $number_of_vehicles_available ) && $number_of_vehicles_available <= $bookings_for_the_vehicle  ) {
				simontaxi_set_error( 'selected_vehicle_return', esc_html__( 'Sorry the selected vehicle not available for return booking', 'simontaxi' ) );
			}
		}
	}
			
	if ( ! empty( $booking_step1['number_of_persons'] ) ) {
		$sql = "SELECT * FROM `" . $wpdb->prefix . "posts` WHERE post_status='publish' AND post_type='vehicle' AND ID = " . $_POST['selected_vehicle'];
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		if ( ! empty( $result ) ) {
			foreach ( $result as $row ) {					
				$number_of_seats_available = get_post_meta( $row['ID'], 'seating_capacity', true );
				/**
				 * @since 2.0.8
				 *
				 * If the vehicle contains less than the selected number of persons, based on admin settings we need to restrict.
				 */
				$apply_seats_restriction = get_post_meta( $row['ID'], 'apply_seats_restriction', true );
				if ( ! empty( $number_of_seats_available ) && $number_of_seats_available < $booking_step1['number_of_persons'] && 'yes' === $apply_seats_restriction ) {
					simontaxi_set_error( 'selected_vehicle_seats', esc_html__( 'Sorry the selected vehicle do not have ' . $booking_step1['number_of_persons'] . ' Seats', 'simontaxi' ) );
				}
			}
		}
	}
	
	/**
	 * @since 2.0.8
	 *
	 * If some one book particular vehicle on particular time, Let us say vehicle1 is booked for 12/04/2018 at 12pm, Other customer should not be able to book same vehicle at same time to avoid clashes.
	 */
	$apply_time_restriction = get_post_meta( $vehicle->ID, 'apply_time_restriction', true );
	if ( ! empty( $apply_time_restriction ) && 'yes' === $apply_time_restriction ) {
		
		$bookings = $wpdb->prefix. 'st_bookings';
		$payments = $wpdb->prefix. 'st_payments';
		$selected_vehicle = $_POST['selected_vehicle'];
		$date = simontaxi_get_session( 'booking_step1', date_i18n( 'Y-m-d' ), 'pickup_date' );
		$time = simontaxi_get_session( 'booking_step1', date_i18n( 'H:i' ), 'pickup_time' );
		$confirmed_vehicle_status = simontaxi_get_option( 'confirmed_vehicle_status', 'confirmed' );
		$bookings_for_the_vehicle = 0;	
		/*
		// Deprecated since 2.0.9
		$sql = "SELECT COUNT(*) FROM $bookings INNER JOIN $payments ON $payments.booking_id=$bookings.ID WHERE $bookings.booking_contacts!='' AND $bookings.status='$confirmed_vehicle_status' AND $bookings.selected_vehicle = '$selected_vehicle' AND $bookings.pickup_date = '$date' AND $bookings.pickup_time = '$time'";
		$bookings_for_the_vehicle = $wpdb->get_var( $sql );	
		if ( $bookings_for_the_vehicle > 0  ) {
			simontaxi_set_error( 'selected_vehicle', esc_html__( 'Sorry the selected vehicle not available for booking for this time', 'simontaxi' ) );
		}
		*/
		
		/**
		 * @since 2.0.9
		 */
		$selected_vehicle = $vehicle->ID;
		$sql = "SELECT * FROM $bookings INNER JOIN $payments ON $payments.booking_id=$bookings.ID WHERE $bookings.booking_contacts != '' AND $bookings.status IN( '$confirmed_vehicle_status', 'onride') AND $bookings.selected_vehicle = '$selected_vehicle' AND $bookings.pickup_date = '$date' ORDER BY $bookings.pickup_date, $bookings.pickup_time DESC LIMIT 1";
		$bookings_for_the_vehicle_on_selected_date = $wpdb->get_results( $sql );
		
		$days = $hours = $minutes = 0;
		$booked_date = date_i18n( 'Y-m-d' );
		$booked_time = date_i18n( 'H:i' );
		$booked_date_time = date_i18n( 'Y-m-d H:i' );
		if ( ! empty( $bookings_for_the_vehicle_on_selected_date ) ) {
			foreach( $bookings_for_the_vehicle_on_selected_date as $booking ) {
				$booked_date = $booking->pickup_date;
				$booked_time = $booking->pickup_time;
				$booked_date_time = $booking->pickup_date . ' ' . $booking->pickup_time;
				$ride_duration = explode( ' ', $booking->duration_text ); // Eg: 1 day 3 hours, 21 hours 8 mins, 2 days 1 hour
				
				if ( ! empty( $ride_duration ) ) {
					$previous_part = '';
					foreach( $ride_duration as $part ) {
						if ( in_array( trim( $part ), array( 'day', 'days' ) ) ) {
							$days = $previous_part;
						}
						if ( in_array( trim( $part ), array( 'hour', 'hours' ) ) ) {
							$hours = $previous_part;
						}
						if ( in_array( trim( $part ), array( 'mins', 'min' ) ) ) {
							$minutes = $previous_part;
						}
						$previous_part = $part;
					}
				}
			}
			
			$selected_pickup_date = $date . ' ' . $time;
			$ride_will_complete = date( 'Y-m-d H:i', simontaxi_strtotime( "$booked_date_time +$days day +$hours hour +$minutes minutes" ) );
			/**
			 * Let us add transition time
			 */
			$transition_time = get_post_meta( $selected_vehicle, 'transition_time', true );
			if ( empty( $transition_time ) ) {
				$transition_time = '5'; // Minimum Transition Time
			}
			$transition_time_type = get_post_meta( $selected_vehicle, 'transition_time_type', true );
			if ( empty( $transition_time_type ) ) {
				$transition_time_type = 'minutes'; // Minimum Transition Time
			}
			
			if( simontaxi_is_between_dates( $booked_time, $ride_will_complete, $selected_pickup_date ) ) {
				simontaxi_set_error( 'selected_vehicle', esc_html__( 'Sorry the selected vehicle not available for booking for this time. It clashes with other booking dates.', 'simontaxi' ) );
			}
			if ( $bookings_for_the_vehicle > 0  ) {
				simontaxi_set_error( 'selected_vehicle', esc_html__( 'Sorry the selected vehicle not available for booking for this time', 'simontaxi' ) );
			}
		}
		
		/*
		We are checking vehicles availability on return date also, if user trying to book for return journey
		*/
		$journey_type = simontaxi_get_session( 'booking_step1', 'one_way', 'journey_type' );
		if ( in_array( $journey_type, apply_filters( 'simontaxi_twoway_other_tabs_step2', array( 'two_way' ) ) ) ) {
			$pickup_date_return = simontaxi_get_session( 'booking_step1', date_i18n( 'Y-m-d' ), 'pickup_date_return' );
			$pickup_time_return = simontaxi_get_session( 'booking_step1', date_i18n( 'Y-m-d' ), 'pickup_time_return' );
			
			$bookings_for_the_vehicle = 0;
			/*
			$sql = "SELECT COUNT(*) FROM `" . $bookings  ."` INNER JOIN `" . $payments . "` ON `" . $payments . "`.`booking_id`=`" . $bookings . "`.`ID` WHERE `" . $bookings."`.booking_contacts!='' AND `" . $bookings . "`.status='" . $confirmed_vehicle_status . "' AND `" . $bookings . "`.`selected_vehicle` = '" . $selected_vehicle . "' AND `".$bookings."`.pickup_date = '" . $pickup_date_return . "' AND `".$bookings."`.pickup_time = '" . $pickup_time_return . "'";
			$bookings_for_the_vehicle = $wpdb->get_var( $sql );
			*/
			$sql = "SELECT * FROM $bookings INNER JOIN $payments ON $payments.booking_id=$bookings.ID WHERE $bookings.booking_contacts != '' AND $bookings.status IN( '$confirmed_vehicle_status', 'onride') AND $bookings.selected_vehicle = '$selected_vehicle' AND $bookings.pickup_date = '$pickup_date_return' ORDER BY $bookings.pickup_date, $bookings.pickup_time DESC LIMIT 1";
			$bookings_for_the_vehicle_on_selected_date = $wpdb->get_results( $sql );
			$days = $hours = $minutes = 0;
			$booked_date = date_i18n( 'Y-m-d' );
			$booked_time = date_i18n( 'H:i' );
			$booked_date_time = date_i18n( 'Y-m-d H:i' );
			if ( ! empty( $bookings_for_the_vehicle_on_selected_date ) ) {
				foreach( $bookings_for_the_vehicle_on_selected_date as $booking ) {
					$booked_date = $booking->pickup_date;
					$booked_time = $booking->pickup_time;
					$booked_date_time = $booking->pickup_date . ' ' . $booking->pickup_time;
					$ride_duration = explode( ' ', $booking->duration_text ); // Eg: 1 day 3 hours, 21 hours 8 mins, 2 days 1 hour
					
					if ( ! empty( $ride_duration ) ) {
						$previous_part = '';
						foreach( $ride_duration as $part ) {
							if ( in_array( trim( $part ), array( 'day', 'days' ) ) ) {
								$days = $previous_part;
							}
							if ( in_array( trim( $part ), array( 'hour', 'hours' ) ) ) {
								$hours = $previous_part;
							}
							if ( in_array( trim( $part ), array( 'mins', 'min' ) ) ) {
								$minutes = $previous_part;
							}
							$previous_part = $part;
						}
					}
				}
				
				$selected_pickup_date = $date . ' ' . $time;
				$ride_will_complete = date( 'Y-m-d H:i', simontaxi_strtotime( "$booked_date_time +$days day +$hours hour +$minutes minutes" ) );
				/**
				 * Let us add transition time
				 */
				$transition_time = get_post_meta( $selected_vehicle, 'transition_time', true );
				if ( empty( $transition_time ) ) {
					$transition_time = '5'; // Minimum Transition Time
				}
				$transition_time_type = get_post_meta( $selected_vehicle, 'transition_time_type', true );
				if ( empty( $transition_time_type ) ) {
					$transition_time_type = 'minutes'; // Minimum Transition Time
				}
				$ride_will_complete = date( 'Y-m-d H:i', simontaxi_strtotime( "$ride_will_complete +$transition_time $transition_time_type" ) );
				
				if( simontaxi_is_between_dates( $booked_time, $ride_will_complete, $selected_pickup_date ) ) {
					// $blockout_vehicles[] = $vehicle->ID;
					simontaxi_set_error( 'selected_vehicle_return', esc_html__( 'Sorry the selected vehicle not available for return booking for this time. It clashes with other booking dates.', 'simontaxi' ) );
				}
			}
			/*
			if ( $bookings_for_the_vehicle > 0  ) {
				simontaxi_set_error( 'selected_vehicle_return', esc_html__( 'Sorry the selected vehicle not available for return booking for this time', 'simontaxi' ) );
			}
			*/
		}
	}
	
	// $errors = simontaxi_get_errors();
	$errors = apply_filters( 'simontaxi_flt_step2_errors', simontaxi_get_errors() );
	if ( empty( $errors ) ) {
		
		do_action( 'simontaxi_booking_step2_noerrors_before' );
		
		$db_ref = simontaxi_get_session( 'booking_step1', '0', 'db_ref' );
		if ( isset( $_POST ) && $db_ref > 0 && ! empty( $_POST ) ) {
			$_POST['vehicle_details'] = $vehicle;
			simontaxi_set_session( 'booking_step2', $_POST );
			$data = array(
				'selected_vehicle' => $_POST['selected_vehicle'],
				'vehicle_name' => $vehicle->post_title,
				'session_details' => json_encode(
					apply_filters( 'simontaxi_session_details', array(
						'step1' => simontaxi_get_session( 'booking_step1' ),
						'step2' => simontaxi_get_session( 'booking_step2' ),
						)
					) 
				),
			);
			/**
			 *
			 * We are taking driver_id from bookings table because for each booking driver may change!
			 *
			 * @since 2.0.8
			 */
			$driver_id = get_post_meta( $data['selected_vehicle'], 'driver_id', true );
			if ( empty( $driver_id ) ) {
				$driver_id = 0;
			}
			$data['driver_id'] = $driver_id;
			
			$wpdb->update( $wpdb->prefix . 'st_bookings',  $data, array( 'ID' => $db_ref ) );
			$redirect_to = simontaxi_get_bookingsteps_urls( 'step3' );
			
			do_action( 'simontaxi_booking_step2_noerrors_after' );
			/**
			 * @since 2.0.9
			 */
			$redirect_to = apply_filters( 'simontaxi_flt_booking_step2_noerrors_after', $redirect_to );
			wp_safe_redirect( $redirect_to );
		} else {
			/**
			 * Means something went wrong and booking details not inserted into database, let us return back to step1, so that let us give an option to user to choose his / her location again
			 */
			$redirect_to = simontaxi_get_bookingsteps_urls( 'step1' );
			wp_safe_redirect( $redirect_to );
		}
		die();
	}
}
