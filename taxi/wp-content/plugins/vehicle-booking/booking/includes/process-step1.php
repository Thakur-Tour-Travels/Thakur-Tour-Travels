<?php
/**
 * Display the page to select journey information (page is for the slug 'pick-locations' )
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  Booking step1 page
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @global wpdb  $wpdb  WordPress database abstraction object.
 */
global $wpdb;

$vehicle_country = simontaxi_get_option( 'vehicle_country', 'US' );
/**
 * @since 2.0.0
*/
$vehicle_country_dropoff = simontaxi_get_option( 'vehicle_country_dropoff', 'US' );
$vehicle_places = simontaxi_get_option( 'vehicle_places', 'googleall' );

$vehicle_places_airport = simontaxi_get_option( 'vehicle_places_airport', 'googleall' );
$vehicle_places_airport_display = simontaxi_get_option( 'vehicle_places_airport_display', 'auto' );

$vehicle_places_hourly = simontaxi_get_option( 'vehicle_places_hourly', 'googleall' );
$vehicle_places_hourly_display = simontaxi_get_option( 'vehicle_places_hourly_display', 'auto' );

$vehicle_places_dropoff = simontaxi_get_option( 'vehicle_places_dropoff', 'googleall' );

$google_api = simontaxi_get_option( 'google_api', 'AIzaSyCqRV6HQ_BSw3MMjPen2bT2IwDnZgfjwu4' );
$vehicle_distance = simontaxi_get_option( 'vehicle_distance', 'km' );
$outofservice = simontaxi_get_option( 'outofservice', 0);
$minimum_distance = simontaxi_get_option( 'minimum_distance', 0);
$distance_taken_from = simontaxi_get_option( 'distance_taken_from', 'google' );

$predefined_place = '';
if ( 'predefined' === $vehicle_places ) {
	$predefined_place = 'predefined_place';
}

$predefined_place_airport = '';
if ( 'predefined' === $vehicle_places_airport ) {
	$predefined_place_airport = 'predefined_place';
}

$predefined_place_hourly = '';
if ( 'predefined' === $vehicle_places_hourly ) {
	$predefined_place_hourly = 'predefined_place';
}

/**
 * As we have many requests to display the predefined places as Drop down, we have given an opiton in admin 'Settings->General->Pickup Places' so that admin can decide the way of displaying places in front end
 *
 * @since 2.0.0
 */
$vehicle_places_display = simontaxi_get_option( 'vehicle_places_display', 'auto' );
$vehicle_places_dropoff_display = simontaxi_get_option( 'vehicle_places_dropoff_display', 'auto' );

$fixed_point_vehicle_name = simontaxi_get_option( 'fixed_point_vehicle_name', 'Flight' );

/**
 * @since 2.0.0
*/
$predefined_place_dropoff = '';
if ( 'predefined' === $vehicle_places_dropoff ) {
	$predefined_place_dropoff = 'predefined_place';
}

if ( 
	( in_array( $vehicle_places, array( 'googleall', 'googleregions' ) ) && $google_api == '' ) 
	|| ( in_array( $vehicle_places_dropoff, array( 'googleall', 'googleregions' ) ) && $google_api == '' )
	|| ( in_array( $vehicle_places_airport, array( 'googleall', 'googleregions' ) ) && $google_api == '' )
	|| ( in_array( $vehicle_places_hourly, array( 'googleall', 'googleregions' ) ) && $google_api == '' )
	) {
    simontaxi_set_error( 'pickup_location', esc_html__( 'Google API key is not set. Please contact administrator', 'simontaxi' ) );
}
//Get the session data if it exists.
$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
/**
 * User may change vehicle so we are unsetting selected vehicle and amount. so that we can caluclate amount once he selects vehicle in next step.
*/
$booking_step2 = simontaxi_get_session( 'booking_step2', 'selected_amount' );
if ( ! empty( $booking_step2 ) ) {
	simontaxi_unset_session( 'booking_step2', 'selected_amount' );
}

$booking_step2 = simontaxi_get_session( 'booking_step2', array() );
$modify = ! empty( $booking_step1 );
if ( ! empty( $_POST ) ) {
	$modify = true;
}

$additional_data = array(
	'current_step' => 'step1',
	);
simontaxi_set_session( 'booking_step1', $additional_data );
$booking_step1 = simontaxi_get_session( 'booking_step1', array() );

$fixed_point_title = simontaxi_get_fixed_point_title();
// dd( $_POST );
if ( isset( $_POST['validtestep1'] ) ) {
    $booking_type = $_POST['booking_type'];
	
	do_action( 'simontaxi_booking_step1_start_before' );
	
    /**
     * If some one tries to hack with out selecting "journey_type", here we are validating and take it as "one_way" journey
    */
    if ( ! isset( $_POST['journey_type'] ) ) {
        $_POST['journey_type'] = 'one_way';
    }
    if ( $_POST['pickup_location'] == '' ) {
        simontaxi_set_error( 'pickup_location', sprintf( esc_html__( 'Please select %s', 'simontaxi' ), simontaxi_get_pickuppoint_title() ) );
    }
    if ( in_array( $booking_type, apply_filters( 'simontaxi_distance_other_tabs', array( 'p2p', 'airport' ) ) ) ) {
        if ( $_POST['drop_location'] == '' ) {
            simontaxi_set_error( 'drop_location', sprintf( esc_html__( 'Please select %s', 'simontaxi' ), simontaxi_get_dropoffpoint_title() ) );
        }
        if ( $_POST['pickup_location'] != '' && $_POST['drop_location'] != '' ) {
            if ( $_POST['pickup_location'] == $_POST['drop_location'] ) {
                simontaxi_set_error( 'pickup_location', sprintf( esc_html__( '%s and %s should be different', 'simontaxi' ), simontaxi_get_pickuppoint_title(), simontaxi_get_dropoffpoint_title() ) );
            }
        }
    }

	$pickup_date = '';
	if ( $_POST['pickup_date'] != '' ) {
		$pickup_date = simontaxi_date_format_ymd( $_POST['pickup_date'] );
	}
	
	$date_time = $pickup_date . ' ' . $_POST['pickup_time_hours'] . ':' . $_POST['pickup_time_minutes'];
	
	/**
	 * Let us take the local time based on WP settings of time zone, so that there is not need to depend on server settings of time zone.
	 *
	 * @since 2.0.8
	 */
	$current_date = date_i18n( 'Y-m-d' );
	$current_date_time = date_i18n( 'Y-m-d H:i' );
	
	$minimum_notice = date( 'Y-m-d', strtotime("+".simontaxi_get_option( 'minimum_notice' )." days") );
	
	$minimum_notice = date_i18n( 'Y-m-d', strtotime( date( 'Y-m-d', strtotime("+".simontaxi_get_option( 'minimum_notice' )." days") ) ) );
	
	$maximum_notice = date( 'Y-m-d', strtotime("+".simontaxi_get_maximum_notice()." days") );
	
	$maximum_notice = date_i18n( 'Y-m-d', strtotime( date( 'Y-m-d', strtotime("+".simontaxi_get_maximum_notice()." days") ) ) );
	
	// echo date( 'Y-m-d H:i', strtotime ( $date_time ) ) .'<'. date( 'Y-m-d H:i' );die();
    if ( $pickup_date == '' ) {
        simontaxi_set_error( 'pickup_date', sprintf( esc_html__( 'Please select %s', 'simontaxi' ), simontaxi_get_pickupdate_title() ) );
    } elseif ( date( 'Y-m-d H:i', strtotime ( $date_time ) ) < $current_date_time ) {
		/* Validating the pickup date and time it should be in future date. Thanks to our testers!!!*/
		simontaxi_set_error( 'pickup_date', sprintf( esc_html__( '%s and time should be future date', 'simontaxi' ), simontaxi_get_pickupdate_title() ) );
	} elseif ( date( 'Y-m-d', strtotime( $pickup_date ) ) < date( 'Y-m-d', strtotime($current_date ) ) ) {
        /* Validating the pickup date it should be in future date. If any one tries to hack the system by changing system date!. We are here to validate!!. Thanks to our testers!!!*/
        simontaxi_set_error( 'pickup_date', sprintf( esc_html__( '%s should be future date', 'simontaxi' ), simontaxi_get_pickupdate_title() ) );
    } elseif ( date( 'Y-m-d', strtotime( $pickup_date ) ) < $minimum_notice ) {
        /* Validating the minimum notice */
        simontaxi_set_error( 'pickup_date', sprintf( esc_html__( '%s should be %s day(s) before', 'simontaxi' ), simontaxi_get_pickupdate_title(), simontaxi_get_option( 'minimum_notice' ) ) );
    } elseif ( date( 'Y-m-d', strtotime( $pickup_date ) ) > $maximum_notice ) {
        /* Validating the maximum notice */
        simontaxi_set_error( 'pickup_date', sprintf( esc_html__( '%s should be %s day(s) before', 'simontaxi' ),  simontaxi_get_pickupdate_title(), simontaxi_get_maximum_notice() ) );
    }
    if ( $_POST['pickup_time_hours'] == '' ) {
        simontaxi_set_error( 'pickup_time_hours', sprintf( esc_html__( 'Please select %s hours', 'simontaxi' ), simontaxi_get_pickuptime_title() ) );
    }
    if ( $_POST['pickup_time_minutes'] == '' ) {
        simontaxi_set_error( 'pickup_time_minutes', sprintf( esc_html__( 'Please select %s minutes', 'simontaxi' ), simontaxi_get_pickuptime_title() ) );
    }
	/**
	 * @since 2.0.6
	 */
	if ( 'yesrequired' === simontaxi_get_option( 'allow_number_of_persons', 'no' ) ) {
		if ( empty( $_POST['number_of_persons'] ) ) {
			simontaxi_set_error( 'number_of_persons', esc_html__( 'Please enter number of persons', 'simontaxi' ) );
		} elseif ( ! ctype_digit( $_POST['number_of_persons'] ) ) {
			simontaxi_set_error( 'number_of_persons', esc_html__( 'Please enter valid number', 'simontaxi' ) );
		}
	}
	if ( 'yesoptional' === simontaxi_get_option( 'allow_number_of_persons', 'no' ) ) {
		if ( ! empty( $_POST['number_of_persons'] ) && ! ctype_digit( $_POST['number_of_persons'] ) ) {
			simontaxi_set_error( 'number_of_persons', esc_html__( 'Please enter valid number', 'simontaxi' ) );
		}
	}
	
    if ( 'airport' === $booking_type ) {
        if ( 'yesrequired' === simontaxi_get_option( 'allow_flight_number', 'no' ) && $_POST['flight_no'] == '' ) {
            simontaxi_set_error( 'flight_no', esc_html__( 'Please enter ' . $fixed_point_vehicle_name . ' number', 'simontaxi' ) );
        }
		if ( simontaxi_get_option( 'allow_flight_arrival_time', 'no' ) == 'yesrequired' && $_POST['flight_arrival_time'] == '' ) {
            simontaxi_set_error( 'flight_arrival_time', esc_html__( 'Please enter ' . $fixed_point_vehicle_name . ' arrival', 'simontaxi' ) );
        }
    }

    if ( $booking_type == 'hourly' ) {
        if ( simontaxi_get_option( 'allow_itinerary', 'no' ) == 'yesrequired' && $_POST['itineraries'] == '' ) {
            simontaxi_set_error( 'itineraries', esc_html__( 'Please enter itinerary', 'simontaxi' ) );
        }
    }
	
	$google_errors = array();

    if ( 'hourly' === $booking_type ) {
        $distance = 0;
    } else {
		if ( 'google' === $distance_taken_from ) {
			if ( 'airport' === $booking_type ) {
				
				$pickup_location = $_POST['pickup_location'];
				$drop_location = $_POST['drop_location'];
				if ( 'drop_location' === $_POST['airport'] ) {					
					if ( simontaxi_is_airport( $pickup_location ) ) {
						$_POST['drop_location'] = $pickup_location;
						$_POST['pickup_location'] = $drop_location;
					}
					
					if ( simontaxi_is_airport( $drop_location ) ) {
						$_POST['drop_location'] = $drop_location;
						$_POST['pickup_location'] = $pickup_location;
					}
				} else {
					if ( ! simontaxi_is_airport( $pickup_location ) ) {
						$_POST['drop_location'] = $pickup_location;
						$_POST['pickup_location'] = $drop_location;
					}
					
					if ( ! simontaxi_is_airport( $drop_location ) ) {
						$_POST['drop_location'] = $drop_location;
						$_POST['pickup_location'] = $pickup_location;
					}
				}
				
				$pickup_location = $_POST['pickup_location'];
				$drop_location = $_POST['drop_location'];
				if ( 'drop_location' === $_POST['airport'] ) {
					$details = get_term( $drop_location, 'vehicle_locations' );
					if ( ! empty( $details ) ) {
						$name = $details->name;
						$term_meta = get_term_meta( $drop_location );
						$location_address = ( ! empty( $term_meta['location_address'] ) ) ? $term_meta['location_address'][0] : '';
						$name_value = ( '' !== $location_address ) ? $location_address : $name;
						$_POST['drop_location'] = $name_value;
						$_POST['drop_location_new'] = $drop_location;
					}
				} else {
					$details = get_term( $pickup_location, 'vehicle_locations' );
					if ( ! empty( $details ) ) {
						$name = $details->name;
						$term_meta = get_term_meta( $pickup_location );
						$location_address = ( ! empty( $term_meta['location_address'] ) ) ? $term_meta['location_address'][0] : '';
						$name_value = ( '' !== $location_address ) ? $location_address : $name;
						$_POST['pickup_location'] = $name_value;
						$_POST['pickup_location_new'] = $pickup_location;
					}
				}
				
			} else {
				if (  ( 'predefined' === $vehicle_places && 'dropdown' === $vehicle_places_display ) ) {
					$pickup_location = $_POST['pickup_location'];
					$details = get_term( $pickup_location, 'vehicle_locations' );
					$name = $details->name;
					$term_meta = get_term_meta( $pickup_location );

					$location_address = ( ! empty( $term_meta['location_address'] ) ) ? $term_meta['location_address'][0] : '';
					$name_value = ( '' !== $location_address ) ? $location_address : $name;
					$_POST['pickup_location'] = $name_value;
					$_POST['pickup_location_new'] = $pickup_location;
				}
				if ( ( 'predefined' === $vehicle_places_dropoff && 'dropdown' === $vehicle_places_dropoff_display ) ) {
					$drop_location = $_POST['drop_location'];
					if ( ! empty( $drop_location ) ) {
						$details = get_term( $drop_location, 'vehicle_locations' );
						
						$name = '';
						if ( $details ) {
							$name = $details->name;
						}
						$term_meta = get_term_meta( $drop_location );
						$location_address = ( ! empty( $term_meta['location_address'] ) ) ? $term_meta['location_address'][0] : '';
						$name_value = ( '' !== $location_address ) ? $location_address : $name;
						$_POST['drop_location'] = $name_value;
						$_POST['drop_location_new'] = $drop_location;
					}
				}
			}
			
			$pickup_location = $_POST['pickup_location'];
			$drop_location = $_POST['drop_location'];
			/**
			 * @since 2.0.8
			 */
			if ( ctype_digit( $pickup_location ) ) {				
				$details = get_term( $pickup_location, 'vehicle_locations' );
				if ( $details ) {
					$pickup_location = $details->name;
					if ( 'airport' === $booking_type ) {
						$term_meta = get_term_meta( $pickup_location );
						$pickup_location = ( ! empty( $term_meta['location_address'] ) ) ? $term_meta['location_address'][0] : $pickup_location;
					}
					$_POST['pickup_location_new'] = $pickup_location;
				}				
			}			
			if ( ctype_digit( $drop_location ) ) {
				$details = get_term( $drop_location, 'vehicle_locations' );
				if ( $details ) {
					$drop_location = $details->name;
					if ( 'airport' === $booking_type ) {
						$term_meta = get_term_meta( $drop_location );
						$drop_location = ( ! empty( $term_meta['location_address'] ) ) ? $term_meta['location_address'][0] : $drop_location;
					}
					$_POST['drop_location_new'] = $drop_location;
				}
			}
			
			if ( ! empty( $pickup_location ) && ! empty( $drop_location ) ) {
				$distance_details = get_google_distance( $pickup_location, $drop_location, simontaxi_get_distance_units() );
				
				if ( ! empty( $distance_details['status'] ) ) {
					$google_errors['code'] = $distance_details['status'];
				} else {
					$distance = $distance_details['distance'];
					$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
							   
					$u = simontaxi_get_distance_units();
					$_POST['distance'] = $distance;
					$_POST['distance_text'] = ! empty( $distance_details['distance_text'] ) ? $distance_details['distance_text'] : '';
					$_POST['distance_meters'] = ! empty( $distance_details['distance_meters'] ) ? $distance_details['distance_meters'] : 0;
					$_POST['distance_units'] = ! empty( $distance_details['distance_units'] ) ? $distance_details['distance_units'] : $u;
					$_POST['duration_text'] = ! empty( $distance_details['duration_text'] ) ? $distance_details['duration_text'] : '';
					$_POST['duration_seconds'] = ! empty( $distance_details['duration_seconds'] ) ? $distance_details['duration_seconds'] : 0;
					$_POST['from_place_id'] = ! empty( $distance_details['from_place_id'] ) ? $distance_details['from_place_id'] : 0;
					$_POST['to_place_id'] = ! empty( $distance_details['to_place_id'] ) ? $distance_details['to_place_id'] : 0;
										
					if ( ! empty( $distance_details['pickup_location_lat'] ) ) {
						$_POST['pickup_location_lat'] =  $distance_details['pickup_location_lat'];
					}
					if ( ! empty( $distance_details['pickup_location_lng'] ) ) {
						$_POST['pickup_location_lng'] = $distance_details['pickup_location_lng'];
					}
					if ( ! empty( $distance_details['drop_location_lat'] ) ) {
						$_POST['drop_location_lat'] = $distance_details['drop_location_lat'];
					}
					if ( ! empty( $distance_details['drop_location_lng'] ) ) {
						$_POST['drop_location_lng'] = $distance_details['drop_location_lng'];
					}
					
					/**
					 * Let us save location details so that we can use later if any purpose.
					 *
					 * @since 2.0.9
					 */
					$_POST['pickup_location_details'] = ! empty( $distance_details['pickup_location_details'] ) ? $distance_details['pickup_location_details'] : '';
					$_POST['drop_location_details'] = ! empty( $distance_details['drop_location_details'] ) ? $distance_details['drop_location_details'] : '';
				}
			}			
			
		} else {
			$pickup_location = $_POST['pickup_location'];
			$drop_location = $_POST['drop_location'];
			
			if ( 'airport' === $booking_type ) {
				
				if ( 'drop_location' === $_POST['airport'] ) {					
					if ( simontaxi_is_airport( $pickup_location ) ) {
						$_POST['drop_location'] = $pickup_location;
						$_POST['pickup_location'] = $drop_location;
					}
					
					if ( simontaxi_is_airport( $drop_location ) ) {
						$_POST['drop_location'] = $drop_location;
						$_POST['pickup_location'] = $pickup_location;
					}
				} else {
					if ( ! simontaxi_is_airport( $pickup_location ) ) {
						$_POST['drop_location'] = $pickup_location;
						$_POST['pickup_location'] = $drop_location;
					}
					
					if ( ! simontaxi_is_airport( $drop_location ) ) {
						$_POST['drop_location'] = $drop_location;
						$_POST['pickup_location'] = $pickup_location;
					}
				}
			}
			
			$pickup_location = $_POST['pickup_location'];
			$drop_location = $_POST['drop_location'];
			
			if (  ( 'predefined' === $vehicle_places && 'dropdown' === $vehicle_places_display && 'p2p' === $booking_type )
			|| ( 'predefined' === $vehicle_places_airport && 'dropdown' === $vehicle_places_airport_display && 'pickup_location' === $_POST['airport'] && 'airport' === $booking_type )
			) {
				$details = get_term( $pickup_location, 'vehicle_locations' );
				$name = $details->name;
				$term_meta = get_term_meta( $pickup_location );

				$location_address = ( ! empty( $term_meta['location_address'] ) ) ? $term_meta['location_address'][0] : '';
				$name_value = ( '' !== $location_address ) ? $location_address : $name;
				$_POST['pickup_location'] = $name_value;
				$_POST['pickup_location_new'] = $pickup_location;
				
				$pickup_location = $_POST['pickup_location'];
				
			}
			if ( ( 'predefined' === $vehicle_places_dropoff && 'dropdown' === $vehicle_places_dropoff_display && 'p2p' === $booking_type )
			|| ( 'predefined' === $vehicle_places_dropoff && 'dropdown' === $vehicle_places_dropoff_display && 'airport' === $booking_type && 'drop_location' === $_POST['airport'] )
			) {
				$details = get_term( $drop_location, 'vehicle_locations' );
				$name = $details->name;
				$term_meta = get_term_meta( $drop_location );
				$location_address = ( ! empty( $term_meta['location_address'] ) ) ? $term_meta['location_address'][0] : '';
				$name_value = ( '' !== $location_address ) ? $location_address : $name;
				$_POST['drop_location'] = $name_value;
				$_POST['drop_location_new'] = $drop_location;
				
				$drop_location = $_POST['drop_location'];
			}
			
			$pick = $pickup_location;
			$drop = $drop_location;
			
			if ( ! empty( $_POST['pickup_location_new'] ) ) {
				$pick = $_POST['pickup_location_new'];
			}
			if ( ! empty( $_POST['drop_location_new'] ) ) {
				$drop = $_POST['drop_location_new'];
			}
			
			$distance_details = simontaxi_get_distance_time( $pick, $drop );
			
            $distance = $distance_details['distance'];
			$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
			          
			$u = simontaxi_get_distance_units();
			$_POST['distance'] = $distance;
			$_POST['distance_text'] = ! empty( $distance_details['distance_text'] ) ? $distance_details['distance_text'] : '';
			$_POST['distance_meters'] = ! empty( $distance_details['distance_meters'] ) ? $distance_details['distance_meters'] : 0;
			$_POST['distance_units'] = ! empty( $distance_details['distance_units'] ) ? $distance_details['distance_units'] : $u;
			$_POST['duration_text'] = ! empty( $distance_details['duration_text'] ) ? $distance_details['duration_text'] : '';
			$_POST['duration_seconds'] = ! empty( $distance_details['duration_seconds'] ) ? $distance_details['duration_seconds'] : 0;
			$_POST['from_place_id'] = ! empty( $distance_details['from_place_id'] ) ? $distance_details['from_place_id'] : 0;
			$_POST['to_place_id'] = ! empty( $distance_details['to_place_id'] ) ? $distance_details['to_place_id'] : 0;
			
			if ( ! empty( $distance_details['pickup_location_lat'] ) ) {
				$_POST['pickup_location_lat'] =  $distance_details['pickup_location_lat'];
			}
			if ( ! empty( $distance_details['pickup_location_lng'] ) ) {
				$_POST['pickup_location_lng'] = $distance_details['pickup_location_lng'];
			}
			if ( ! empty( $distance_details['drop_location_lat'] ) ) {
				$_POST['drop_location_lat'] = $distance_details['drop_location_lat'];
			}
			if ( ! empty( $distance_details['drop_location_lng'] ) ) {
				$_POST['drop_location_lng'] = $distance_details['drop_location_lng'];
			}
			
			/**
			 * Let us save location details so that we can use later if any purpose.
			 *
			 * @since 2.0.9
			 */
			$_POST['pickup_location_details'] = ! empty( $distance_details['pickup_location_details'] ) ? $distance_details['pickup_location_details'] : '';
			$_POST['drop_location_details'] = ! empty( $distance_details['drop_location_details'] ) ? $distance_details['drop_location_details'] : '';
		}
	}

	/**
	 * @since 2.0.8 
	 *
	 * Let us allow other plugins to inject other variables into POST
	 */
	$_POST = apply_filters('simontaxi_additional_post_variables', $_POST);
	
    if ( in_array( $booking_type, apply_filters( 'simontaxi_distance_other_tabs', array( 'p2p', 'airport' ) ) ) ) {
        $distance_number = filter_var( $_POST['distance'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		if ( $distance_number == '' ) {
            $errors = simontaxi_get_errors();
			if ( ! empty( $errors['distance'] ) ) {
				simontaxi_set_error( 'distance', esc_html__( 'Please select locations', 'simontaxi' ) );
			}
        } elseif ( $minimum_distance > 0 ) {
			/**
			 * If admin enables any restriction on distance, we need to validate
			 */
			if ( $distance_number < $minimum_distance ) {
                simontaxi_set_error( 'distance', sprintf( esc_html__( 'Online bookings are only available above %s %s. Distance between selected locations is %s %s', 'simontaxi' ), $minimum_distance, simontaxi_get_distance_units(), $distance_number, simontaxi_get_distance_units() ) );
            }
		} elseif ( $outofservice > 0 ) {
            if ( $distance_number > $outofservice ) {
                simontaxi_set_error( 'distance', sprintf( esc_html__( 'Service not available for above %s %s', 'simontaxi' ), $outofservice, simontaxi_get_distance_units() ) );
            }
        }

        if ( isset( $_POST['journey_type'] ) && in_array( $_POST['journey_type'], apply_filters( 'simontaxi_twoway_other_tabs_step1', array( 'two_way' ) ) ) ) {
            $pickup_date_return = '';
			if ( $_POST['pickup_date_return'] != '' ) {
				$pickup_date_return = simontaxi_date_format_ymd( $_POST['pickup_date_return'] );
			}
			if ( $pickup_date_return == '' ) {
                simontaxi_set_error( 'pickup_date_return', sprintf( esc_html__( 'Please select return %s', 'simontaxi' ), simontaxi_get_pickupdate_title() ) );
            } elseif ( date( 'Y-m-d', strtotime( $pickup_date_return ) ) < $current_date ) {
                /* Validating the return pickup date it should be in future date and after the pickup date. If any one tries to hack the system by changing system date!. We are here to validate!!. Thanks to our testers!!!*/
                simontaxi_set_error( 'pickup_date_return', sprintf( esc_html__( 'Return %s should be future date', 'simontaxi' ), simontaxi_get_pickupdate_title() ) );
            } elseif ( date( 'Y-m-d', strtotime( $pickup_date_return ) ) < $minimum_notice ) {
                /* Validating the minimum notice */
                simontaxi_set_error( 'pickup_date_return', sprintf( esc_html__( '%s should be %s days(s) before', 'simontaxi' ), simontaxi_get_pickupdate_title(), simontaxi_get_option( 'minimum_notice' ) ) );
            } elseif ( date( 'Y-m-d', strtotime( $pickup_date_return ) ) > $maximum_notice ) {
                /* Validating the maximum notice */
                simontaxi_set_error( 'pickup_date_return', sprintf( esc_html__( '%s should be %s day(s) before', 'simontaxi' ), simontaxi_get_pickupdate_title(), simontaxi_get_maximum_notice() ) );
            } elseif ( date( 'Y-m-d', strtotime( $pickup_date_return ) ) < date( 'Y-m-d', strtotime( $pickup_date ) ) ) {
				/**
				 * @since 2.0.0
				 */
				simontaxi_set_error( 'pickup_date_return', sprintf( esc_html__( 'Return %s should be after %s', 'simontaxi' ), simontaxi_get_pickupdate_title(), simontaxi_get_pickupdate_title() ) );
			} else {
				/**
				 * @since 2.0.0
				 */
				$pickup_date_time = date( 'Y-m-d', simontaxi_strtotime( $_POST['pickup_date'] ) ) . $_POST['pickup_time_hours'] . ':' . $_POST['pickup_time_minutes'];

				// Let us assume a journey is minimum 1 hour, so there will be no return booking upto 1 hour
				$pickup_date_time = date( 'Y-m-d H:i', strtotime( "+1 hour", strtotime( $pickup_date_time ) ) );

				$pickup_date_time_return = date( 'Y-m-d', strtotime( $pickup_date_return ) ) . ' ' . $_POST['pickup_time_hours_return'] . ':' . $_POST['pickup_time_minutes_return'];

				if ( $pickup_date_time_return < $pickup_date_time ) {
					simontaxi_set_error( 'pickup_date_return', sprintf( esc_html__( 'Return %s should be after one hour', 'simontaxi' ), simontaxi_get_pickupdate_title(), simontaxi_get_pickupdate_title() ) );
				}
			}

            if ( $_POST['pickup_time_hours_return'] == '' ) {
                simontaxi_set_error( 'pickup_time_hours_return', sprintf( esc_html__( 'Please select return %s hours', 'simontaxi' ), simontaxi_get_pickuptime_title() ) );
            }
            if ( $_POST['pickup_time_minutes_return'] == '' ) {
                simontaxi_set_error( 'pickup_time_minutes_return', sprintf( esc_html__( 'Please select return %s minutes', 'simontaxi' ), simontaxi_get_pickuptime_title() ) );
            }
        }
    }

	if ( isset( $_POST['distance'] ) && ( ( '' === $_POST['distance'] ) || ( '0.00' === $_POST['distance'] ) ) && ( $booking_type != 'hourly' ) ) {
        $pickup_location = $_POST['pickup_location'];
		$drop_location = $_POST['drop_location'];
		
		if ( ! empty( $google_errors ) ) {
			
			// @link https://developers.google.com/maps/documentation/directions/intro
			 
			$message = esc_html__( 'At least one of the locations specified in the ' . $pickup_location . ', ' . $drop_location . ', could not be geocoded', 'simontaxi' );
			switch( $google_errors['code'] ) {
				case 'ZERO_RESULTS':
					$message = esc_html__( 'No route could be found between the ' . $pickup_location . ' and ' . $drop_location, 'simontaxi' );
					break;
				
				case 'MAX_WAYPOINTS_EXCEEDED':
					$message = esc_html__( 'Too many waypoints were provided in the request', 'simontaxi' );
					break;
				case 'MAX_ROUTE_LENGTH_EXCEEDED':
					$message = esc_html__( 'Requested route is too long and cannot be processed', 'simontaxi' );
					break;
				case 'INVALID_REQUEST':
					$message = esc_html__( 'Provided request was invalid', 'simontaxi' );
					break;
				case 'OVER_QUERY_LIMIT':
					$message = esc_html__( 'Service has received too many requests from your application within the allowed time period. You have exceeded your daily request quota for this API.', 'simontaxi' );
					break;
				case 'REQUEST_DENIED':
					$message = esc_html__( 'Service denied use of the directions service by your application', 'simontaxi' );
					break;
				case 'UNKNOWN_ERROR':
					$message = esc_html__( 'Request could not be processed due to a server error. The request may succeed if you try again.', 'simontaxi' );
					break;
			}
			simontaxi_set_error( 'distance', $message );
		} else {
			simontaxi_set_error( 'distance', sprintf( esc_html__( 'Sorry, no services from %s to %s', 'simontaxi' ), $pickup_location, $drop_location ) );
		}
    } elseif ( $outofservice > 0 && $distance > $outofservice) {
        simontaxi_set_error( 'distance', sprintf( esc_html__( 'Service not available for above %s%s', 'simontaxi' ), $outofservice, simontaxi_get_distance_units() ) );
    }
	
    if ( simontaxi_terms_page() == 'step1' && ! isset( $_POST['terms'] ) ) {
        simontaxi_set_error( 'terms', esc_html__( 'You should accept Terms of Service to proceed', 'simontaxi' ) );
    }
	$errors = apply_filters( 'simontaxi_flt_step1_errors', simontaxi_get_errors() );
	
    if ( empty( $errors ) ) {
        $_POST['pickup_date'] = date( 'Y-m-d', simontaxi_strtotime( $_POST['pickup_date'] ) );
		
        $_POST['pickup_time'] = $_POST['pickup_time_hours'] . ':' . $_POST['pickup_time_minutes'];
        unset( $_POST['pickup_time_hours'] );
        unset( $_POST['pickup_time_minutes'] );

        if ( isset( $_POST['waiting_time_hours'] ) && isset( $_POST['waiting_time_minutes'] ) && $_POST['waiting_time_hours'] != '' &&  $_POST['waiting_time_minutes'] != '' ) {
            $_POST['waiting_time'] = $_POST['waiting_time_hours'] . ':' . $_POST['waiting_time_minutes'];
        }

        if ( isset( $_POST['additional_pickups'] ) ) {
            $_POST['additional_pickups'] = $_POST['additional_pickups'];
        }

        if ( isset( $_POST['additional_dropoff'] ) ) {
            $_POST['additional_dropoff'] = $_POST['additional_dropoff'];
        }

        if ( in_array( $_POST['journey_type'], apply_filters( 'simontaxi_twoway_other_tabs_step1', array( 'two_way' ) ) ) ) {
            $_POST['pickup_date_return'] = date( 'Y-m-d', simontaxi_strtotime( $_POST['pickup_date_return'] ) );
            $_POST['pickup_time_return'] = $_POST['pickup_time_hours_return'] . ':' . $_POST['pickup_time_minutes_return'];
            if ( isset( $_POST['waiting_time_hours_return'] ) && isset( $_POST['waiting_time_minutes_return'] ) && $_POST['waiting_time_hours_return'] != '' &&  $_POST['waiting_time_minutes_return'] != '' ) {
                $_POST['waiting_time_return'] = $_POST['waiting_time_hours_return'] . ':' . $_POST['waiting_time_minutes_return'];
            }
            if ( isset( $_POST['additional_pickups_return'] ) ) {
                $_POST['additional_pickups_return'] = $_POST['additional_pickups_return'];
            }

            if ( isset( $_POST['additional_dropoff_return'] ) ) {
                $_POST['additional_dropoff_return'] = $_POST['additional_dropoff_return'];
            }
        }

        if ( isset( $_POST['waiting_time_hours'] ) ) {
			unset( $_POST['waiting_time_hours'] );
		}
        if ( isset( $_POST['waiting_time_minutes'] ) ) {
			unset( $_POST['waiting_time_minutes'] );
		}
        if ( isset( $_POST['terms'] ) ) {
			unset( $_POST['terms'] );
		}
        /*
		if ( isset( $_POST['validtestep1'] ) ) {
			unset( $_POST['validtestep1'] );
		}
		*/

        if ( isset( $_POST['pickup_time_hours_return'] ) ) {
			unset( $_POST['pickup_time_hours_return'] );
		}
        if ( isset( $_POST['pickup_time_minutes_return'] ) ) {
			unset( $_POST['pickup_time_minutes_return'] );
		}
        if ( isset( $_POST['waiting_time_hours_return'] ) ) {
			unset( $_POST['waiting_time_hours_return'] );
		}
        if ( isset( $_POST['waiting_time_minutes_return'] ) ) {
			unset( $_POST['waiting_time_minutes_return'] );
		}
		
        $db_ref = simontaxi_get_session( 'booking_step1', 0, 'db_ref' );
		// $data_get = simontaxi_get_session( 'booking_step1' );
		$data_get = $_POST;
		
		$data_get = apply_filters( 'simontaxi_set_unset_post_fields', $data_get );
		
		if ( isset( $data_get['number_of_persons'] ) ) {
			unset( $data_get['number_of_persons'] );
		}
		if ( isset( $data_get['flight_arrival_time'] ) ) {
			unset( $data_get['flight_arrival_time'] );
		}
		
		/**
		 * Let us save location details so that we can use later if any purpose.
		 *
		 * @since 2.0.9
		 */
		if ( isset( $data_get['pickup_location_details'] ) ) {
			unset( $data_get['pickup_location_details'] );
		}
		if ( isset( $data_get['drop_location_details'] ) ) {
			unset( $data_get['drop_location_details'] );
		}
		if ( isset( $data_get['distance_meters'] ) ) {
			unset( $data_get['distance_meters'] );
		}
		if ( isset( $data_get['duration_seconds'] ) ) {
			unset( $data_get['duration_seconds'] );
		}

		$pickup_location_new = isset( $_POST['pickup_location_new'] ) ? $_POST['pickup_location_new'] : '';
		$drop_location_new = isset( $_POST['drop_location_new'] ) ? $_POST['drop_location_new'] : '';
		
		if ( ! $modify ) {
            simontaxi_set_session( 'booking_step1', null );
			simontaxi_set_session( 'booking_step2', null );
			simontaxi_set_session( 'booking_step3', null );
			simontaxi_set_session( 'booking_step4', null );
			simontaxi_set_session( 'discount_details', null );

            simontaxi_set_session( 'booking_step1', $_POST );

			$additional_data = array(
				'reference' => simontaxi_get_token(simontaxi_get_option( 'booking_ref_length', 6) ),
				'user_id' => get_current_user_id(),
				'date' => date_i18n( 'Y-m-d h:i:s' ),
				'status_updated' => date_i18n( 'Y-m-d h:i:s' ),
				/**
				 * @since 2.0.0
				 */
				 'pickup_location_new' => $pickup_location_new,
				 'drop_location_new' => $drop_location_new,
				);
			simontaxi_set_session( 'booking_step1', $additional_data );
			foreach( $additional_data as $key => $val ) {
				$data_get[ $key ] = $val;
			}
            if ( $_POST['booking_type']=='hourly' ) {
                $additional_data = array(
					'drop_location' => '-' ,
					'journey_type' => '-',
					);
				simontaxi_set_session( 'booking_step1', $additional_data );
				foreach( $additional_data as $key => $val ) {
					$data_get[ $key ] = $val;
				}
            }

			if ( isset( $data_get['pickup_location_new'] ) ) {
				unset( $data_get['pickup_location_new'] );
			}
			if ( isset( $data_get['drop_location_new'] ) ) {
				unset( $data_get['drop_location_new'] );
			}
						
            $wpdb->insert( $wpdb->prefix . 'st_bookings', $data_get );
			$additional_data = array(
					'db_ref' => $wpdb->insert_id,
					);
			simontaxi_set_session( 'booking_step1', $additional_data );
			foreach( $additional_data as $key => $val ) {
				$data_get[ $key ] = $val;
			}
        } elseif ( $db_ref > 0 ) {
            $db_ref = simontaxi_get_session( 'booking_step1', 0, 'db_ref' );
            $ref = simontaxi_get_session( 'booking_step1', '', 'reference' );
            if ( '' === $ref ) {
                $ref = simontaxi_get_token(simontaxi_get_option( 'booking_ref_length', 6) );
            }
            simontaxi_set_session( 'booking_step1', $_POST );
			$additional_data = array(
				'reference' => $ref,
				/**
				 * @since 2.0.0
				 */
				 'pickup_location_new' => $pickup_location_new,
				 'drop_location_new' => $drop_location_new,
				);
			simontaxi_set_session( 'booking_step1', $additional_data );
			foreach( $additional_data as $key => $val ) {
				$data_get[ $key ] = $val;
			}

			$additional_data = array(
					'reference' => $ref,
					'user_id' => get_current_user_id(),
					);
			simontaxi_set_session( 'booking_step1', $additional_data );
			foreach( $additional_data as $key => $val ) {
				$data_get[ $key ] = $val;
			}

            if ( $_POST['booking_type']=='hourly' ) {
                $additional_data = array(
					'drop_location' => '-' ,
					'journey_type' => '-',
					);
				simontaxi_set_session( 'booking_step1', $additional_data );
				foreach( $additional_data as $key => $val ) {
					$data_get[ $key ] = $val;
				}
            }

            $check = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}st_bookings WHERE ID = $db_ref" );

            if ( $db_ref == 0 || empty( $check ) ) { //For any reason if it not already inserted, Let us insert
                $additional_data = array(
					'reference' => simontaxi_get_token(simontaxi_get_option( 'booking_ref_length', 6) ),
					'user_id' => get_current_user_id(),
					'date' => date( 'Y-m-d h:i:s' ),
					'status_updated' => date( 'Y-m-d h:i:s' ),
					);
				simontaxi_set_session( 'booking_step1', $additional_data );
				foreach( $additional_data as $key => $val ) {
					$data_get[ $key ] = $val;
				}
                if ( isset( $data_get['pickup_location_new'] ) ) {
					unset( $data_get['pickup_location_new'] );
				}
				if ( isset( $data_get['drop_location_new'] ) ) {
					unset( $data_get['drop_location_new'] );
				}
				if ( isset( $data_get['validtestep1'] ) ) {
					unset( $data_get['validtestep1'] );
				}
				$wpdb->insert( $wpdb->prefix . 'st_bookings', $data_get );

                $db_ref = $wpdb->insert_id;
            } else {
				$additional_data = array(
					'status_updated' => date_i18n( 'Y-m-d h:i:s' ),
					);
				simontaxi_set_session( 'booking_step1', $additional_data );
				foreach( $additional_data as $key => $val ) {
					$data_get[ $key ] = $val;
				}
				if ( isset( $data_get['db_ref'] ) ) {
					unset( $data_get['db_ref'] );
				}
				if ( isset( $data_get['pickup_location_new'] ) ) {
					unset( $data_get['pickup_location_new'] );
				}
				if ( isset( $data_get['drop_location_new'] ) ) {
					unset( $data_get['drop_location_new'] );
				}
				if ( isset( $data_get['validtestep1'] ) ) {
					unset( $data_get['validtestep1'] );
				}
                $wpdb->update( $wpdb->prefix . 'st_bookings',  $data_get, array( 'ID' => $db_ref) );
            }

            simontaxi_set_session( 'booking_step1', array( 'db_ref' => $db_ref ) );
			simontaxi_set_session( 'booking_step2', null );
			simontaxi_set_session( 'booking_step3', null );
			simontaxi_set_session( 'booking_step4', null );
        } else {

			simontaxi_set_session( 'booking_step1', $_POST );

			$additional_data = array(
					'reference' => simontaxi_get_token(simontaxi_get_option( 'booking_ref_length', 6) ),
					'user_id' => get_current_user_id(),
					'date' => date_i18n( 'Y-m-d h:i:s' ),
					'status_updated' => date_i18n( 'Y-m-d h:i:s' ),
					/**
					 * @since 2.0.0
					 */
					 'pickup_location_new' => $pickup_location_new,
					 'drop_location_new' => $drop_location_new,
					);
			simontaxi_set_session( 'booking_step1', $additional_data );
			foreach( $additional_data as $key => $val ) {
				$data_get[ $key ] = $val;
			}
			if ( isset( $data_get['db_ref'] ) ) {
				unset( $data_get['db_ref'] );
			}
			if ( isset( $data_get['pickup_location_new'] ) ) {
				unset( $data_get['pickup_location_new'] );
			}
			if ( isset( $data_get['drop_location_new'] ) ) {
				unset( $data_get['drop_location_new'] );
			}
			if ( isset( $data_get['validtestep1'] ) ) {
				unset( $data_get['validtestep1'] );
			}
			// $wpdb->insert( $wpdb->prefix . 'st_bookings', $data_get );
			$sql = 'insert into ' . $wpdb->prefix . 'st_bookings (' . implode( ',', array_keys( $data_get ) ) . ') values("' . implode( '","', array_values( $data_get ) ) . '")';
			
			$wpdb->query( $sql );
			$db_ref = $wpdb->insert_id;
            simontaxi_set_session( 'booking_step1', array( 'db_ref' => $db_ref ) );
			simontaxi_set_session( 'booking_step2', null );
			simontaxi_set_session( 'booking_step3', null );
			simontaxi_set_session( 'booking_step4', null );			
        }
		
		/**
		 * @since 2.0.8
		 *
		 * To minimize number of booking steps
		 */
		$redirect_to = simontaxi_get_bookingsteps_urls( 'step2' );
		
		$can_redirect = TRUE;
		if ( ! empty( $_GET['selected_vehicle'] ) || ! empty( $_POST['selected_vehicle'] ) ) {
			$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
			
			$vehicle_id = ! empty( $_GET['selected_vehicle'] ) ? $_GET['selected_vehicle'] : 0;
			$selected_vehicle = ! empty( $_GET['selected_vehicle'] ) ? $_GET['selected_vehicle'] : 0;
			if ( ! empty( $_POST['selected_vehicle'] ) ) {
				$vehicle_id = $_POST['selected_vehicle'];
				$selected_vehicle = $_POST['selected_vehicle'];
			}
			
			$vehicle = simontaxi_get_vehiclle_details( $vehicle_id );
						
			if ( ! empty( $vehicle ) ) {
				$fare = simontaxi_get_fare( $vehicle, $booking_step1 );
			} else {
				$fare = 0;
			}
			
			if ( 'hourly' != $booking_type && $fare == 0 ) {
				simontaxi_set_error( 'selected_amount', esc_html__( 'Something went wrong. Selected vehicle seems not available', 'simontaxi' ) );
				$can_redirect = FALSE;
			} else {
				/**
				 * Let us restrict number of vehicles
				 *
				 * @since 2.0.8
				*/
				if ( 'yes' === simontaxi_get_option( 'restrict_vehicles_count', 'no' ) ) {
					$bookings = $wpdb->prefix. 'st_bookings';
					$payments = $wpdb->prefix. 'st_payments';
					$confirmed_vehicle_status = simontaxi_get_option( 'confirmed_vehicle_status', 'confirmed' );
					
					$date = simontaxi_get_session( 'booking_step1', date_i18n( 'Y-m-d' ), 'pickup_date' );
					$pickup_date_return = simontaxi_get_session( 'booking_step1', date_i18n( 'Y-m-d' ), 'pickup_date_return' );
					
					$selected_vehicle = $_GET['selected_vehicle'];
					if ( ! empty( $_POST['selected_vehicle'] ) ) {
						$selected_vehicle = $_POST['selected_vehicle'];
					}
					$sql = "SELECT COUNT(*) FROM `" . $bookings  ."` INNER JOIN `" . $payments . "` ON `" . $payments . "`.`booking_id`=`" . $bookings . "`.`ID` WHERE `" . $bookings."`.booking_contacts!='' AND `" . $bookings . "`.status='" . $confirmed_vehicle_status . "' AND `" . $bookings . "`.`selected_vehicle` = '" . $selected_vehicle . "' AND `".$bookings."`.pickup_date = '" . $date . "'";
					$bookings_for_the_vehicle = $wpdb->get_var( $sql );

					$number_of_vehicles_available = get_post_meta( $selected_vehicle, 'number_of_vehicles', true );
					
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
					
					/**
					 * We are checking vehicles availability on return date also, if user trying to book for return journey
					*/
					$journey_type = simontaxi_get_session( 'booking_step1', 'one_way', 'journey_type' );
					if ( in_array( $journey_type, apply_filters( 'simontaxi_twoway_other_tabs_step1', array( 'two_way' ) ) ) ) {
						$sql = "SELECT COUNT(*) FROM `" . $bookings  ."` INNER JOIN `" . $payments . "` ON `" . $payments . "`.`booking_id`=`" . $bookings . "`.`ID` WHERE `" . $bookings."`.booking_contacts!='' AND `" . $bookings . "`.status='" . $confirmed_vehicle_status . "' AND `" . $bookings . "`.`selected_vehicle` = '" . $selected_vehicle . "' AND `".$bookings."`.pickup_date = '" . $pickup_date_return . "'";
						$bookings_for_the_vehicle = $wpdb->get_var( $sql );

						$number_of_vehicles_available = get_post_meta( $selected_vehicle, 'number_of_vehicles', true );
						
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
							simontaxi_set_error( 'selected_vehicle_return', esc_html__( 'Sorry the selected vehicle not available for return booking', 'simontaxi' ) );
						}
					}
				}
				
				/**
				 * @since 2.0.8
				 *
				 * If some one book particular vehicle on particular time, Let us say vehicle1 is booked for 12/04/2018 at 12pm, Other customer should not be able to book same vehicle at same time to avoid clashes.
				 */
				$apply_time_restriction = get_post_meta( $selected_vehicle, 'apply_time_restriction', true );
				
				if ( ! empty( $apply_time_restriction ) && 'yes' === $apply_time_restriction ) {
					$bookings = $wpdb->prefix. 'st_bookings';
					$payments = $wpdb->prefix. 'st_payments';
					$confirmed_vehicle_status = simontaxi_get_option( 'confirmed_vehicle_status', 'confirmed' );
					
					$date = simontaxi_get_session( 'booking_step1', date_i18n( 'Y-m-d' ), 'pickup_date' );
					$time = simontaxi_get_session( 'booking_step1', date_i18n( 'Y-m-d' ), 'pickup_time' );
					/*
					$sql = "SELECT COUNT(*) FROM `" . $bookings  ."` INNER JOIN `" . $payments . "` ON `" . $payments . "`.`booking_id`=`" . $bookings . "`.`ID` WHERE `" . $bookings."`.booking_contacts!='' AND `" . $bookings . "`.status='" . $confirmed_vehicle_status . "' AND `" . $bookings . "`.`selected_vehicle` = '" . $selected_vehicle . "' AND `".$bookings."`.pickup_date = '" . $date . "' AND `".$bookings."`.pickup_time = '" . $time . "'";
					
					$bookings_for_the_vehicle = $wpdb->get_var( $sql );
					
					if ( $bookings_for_the_vehicle > 0  ) {
						simontaxi_set_error( 'selected_vehicle', esc_html__( 'Sorry the selected vehicle not available for booking for this time', 'simontaxi' ) );
					}
					*/
					
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
					if ( in_array( $journey_type, apply_filters( 'simontaxi_twoway_other_tabs_step1', array( 'two_way' ) ) ) ) {
						$pickup_date_return = simontaxi_get_session( 'booking_step1', date_i18n( 'Y-m-d' ), 'pickup_date_return' );
						$pickup_time_return = simontaxi_get_session( 'booking_step1', date_i18n( 'Y-m-d' ), 'pickup_time_return' );
						/*
						$sql = "SELECT COUNT(*) FROM `" . $bookings  ."` INNER JOIN `" . $payments . "` ON `" . $payments . "`.`booking_id`=`" . $bookings . "`.`ID` WHERE `" . $bookings."`.booking_contacts!='' AND `" . $bookings . "`.status='" . $confirmed_vehicle_status . "' AND `" . $bookings . "`.`selected_vehicle` = '" . $selected_vehicle . "' AND `".$bookings."`.pickup_date = '" . $pickup_date_return . "' AND `".$bookings."`.pickup_time = '" . $pickup_time_return . "'";
						$bookings_for_the_vehicle = $wpdb->get_var( $sql );

						if ( $bookings_for_the_vehicle > 0  ) {
							simontaxi_set_error( 'selected_vehicle_return', esc_html__( 'Sorry the selected vehicle not available for return booking for this time', 'simontaxi' ) );
						}
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
					}
				}
				
				if ( ! empty( $booking_step1['number_of_persons'] ) ) {
					$sql = "SELECT * FROM `" . $wpdb->prefix . "posts` WHERE post_status='publish' AND post_type='vehicle' AND ID = " . $_GET['selected_vehicle'];
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
				
				$errors = apply_filters( 'simontaxi_flt_step1_errors', simontaxi_get_errors() );
				// dd( $_POST );				
				if ( empty( $errors ) ) {
					
					$booking_step2 = array(
						'vehicle_details' => $vehicle,
						'selected_vehicle' => $selected_vehicle,
						'vehicle_name' => $vehicle->post_title,
						'selected_amount' => $fare,
					);
					simontaxi_set_session( 'booking_step2', $booking_step2 );
					
					$data = array(
						'selected_vehicle' => $selected_vehicle,
						'vehicle_name' => $vehicle->post_title,
						'session_details' => json_encode( 
							apply_filters( 'simontaxi_session_details', array(
								'step1' => simontaxi_get_session( 'booking_step1' ),
								'step2' => simontaxi_get_session( 'booking_step2' ),
								)
							) 
						),
					);
					$wpdb->update( $wpdb->prefix . 'st_bookings',  $data, array( 'ID' => $db_ref ) );
					$redirect_to = simontaxi_get_bookingsteps_urls( 'step3' );
				} else {
					$can_redirect = FALSE;
				}
			}
		}

        if ( $can_redirect ) {
			do_action( 'simontaxi_booking_step1_complete_after' );
			$redirect_to = apply_filters( 'simontaxi_flt_booking_step1_complete_after', $redirect_to );
			wp_safe_redirect( $redirect_to );
		}
    }
}

//Getting action tab user selects
$tabs = simontaxi_get_active_tab();
/**
 * @since 2.0.8
 */
if ( ! empty( $booking_types ) ) {
	$tabs = explode( ',', $booking_types );
}

//Get all airports entered in admin
$airports = simontaxi_get_airports();

//Get all hourly packages
$hourly_packs = simontaxi_get_hourly_packages();

if ( ! $modify )
{
    simontaxi_set_session( 'booking_step1', null );
	simontaxi_set_session( 'booking_step2', null );
	simontaxi_set_session( 'booking_step3', null );
	simontaxi_set_session( 'booking_step4', null );
}
else
{
    if ( ! empty( $_POST ) ) {
        if ( isset( $_POST['pickup_location'] ) ) {
			$from = $_POST['pickup_location'];
		}
    } else {
        $from = (isset( $booking_step1['pickup_location'] ) ) ? $booking_step1['pickup_location'] : '';
    }
    $to = $from = '';
    if ( ! empty( $_POST ) ) {
        if ( isset( $_POST['drop_location'] ) ) {
			$to = $_POST['drop_location'];
		}
    } else {
        $from = ( isset( $booking_step1['drop_location'] ) ) ? $booking_step1['drop_location'] : '';
    }
    $is_hourly = FALSE;
    if ( ! empty( $_POST ) ) {
        if ( isset( $_POST['booking_type'] ) && $_POST['booking_type'] == 'hourly' ) {
            $is_hourly = TRUE;
		}
    } else {
        $is_hourly = ( isset( $booking_step1['booking_type'] ) && $booking_step1['booking_type'] == 'hourly' ) ? TRUE : FALSE;
    }

    if ( $is_hourly)
    {
        $pack = simontaxi_get_value( $booking_step1, 'hourly_package' );
        $dt=simontaxi_get_hourly_packages( $pack );
        $distance=0;
    }
    else
    {
        $dt=simontaxi_get_distance_time( $from, $to, '' );
        $distance = $dt[0];
    }

    $du = simontaxi_get_distance_units();
    $currency_code = simontaxi_get_currency_code();
    if ( ! $distance )
    $distance=0;
}
