<!-- Booking Progress -->
<?php if ( in_array( $placement, array( 'hometop', 'homeleft' ) ) ) {
	echo '<p class="st-breadcrumb-empty">&nbsp;</p>';
}?>
<!-- end Booking Progress -->

<form class="st-booking-form row" id="booking-airport" method="POST" action="">
	<input type="hidden" name="booking_type" value="airport">
	<input type="hidden" name="journey_type" value="one_way">
	<?php
	$airport = 'pickup_location';
	/**
	 * Let us display the airport tab options based on admin settings
	*/
	$allow_twoway_airport = simontaxi_get_option( 'allow_twoway_airport', 'both' );
	if ( $allow_twoway_airport == 'both' ) {
	?>
	<div class="col-sm-12">
		<div class="input-group st-radio" id="airporttype">
			<input type="radio" class="airport_transfer_type" id="drop_location_airport" name="airport" value="drop_location" onclick="toggle_pickupdrop( 'drop_location' )" <?php if ( $modify ) { if ( simontaxi_get_value( $booking_step1, 'airport' ) == 'drop_location' ) { echo 'checked'; } } else echo 'checked'?>>
			<label for="drop_location_airport"><span><span></span></span><?php echo apply_filters( 'simontaxi_flt_goingto_title', esc_html__( 'Going to ' . $fixed_point_title, 'simontaxi' ) ); ?></label>

			<input type="radio" class="airport_transfer_type" id="pickup_location_airport" name="airport" value="pickup_location" onclick="toggle_pickupdrop( 'pickup_location')" <?php if ( $modify ) { if ( simontaxi_get_value( $booking_step1, 'airport' ) == 'pickup_location' ) echo 'checked'; }?>>
			<label for="pickup_location_airport"><span><span></span></span><?php echo apply_filters( 'simontaxi_flt_comingfrom_title', esc_html__( 'Coming from ' . $fixed_point_title, 'simontaxi' ) ); ?></label>
		</div>
	</div>
	<?php } else {
		if ( $allow_twoway_airport == 'comingfrom' ) {
			$airport = 'pickup_location';
		} else {
			$airport = 'drop_location';
		}

		if ( $modify ) {
			if ( simontaxi_get_value( $booking_step1, 'airport' ) == 'drop_location' ) {
				$airport = 'drop_location';
			} elseif ( simontaxi_get_value( $booking_step1, 'airport' ) == 'pickup_location' ) {
				$airport = 'pickup_location';
			}
			$airport = simontaxi_get_value( $booking_step1, 'airport' );
		}
		?>
		<input type="hidden" name="airport" id="airport" value="<?php echo esc_attr( $airport ); ?>">
		<?php
	}?>

	<div class="form-group col-sm-<?php echo apply_filters('simontaxi_airport_location_airport_width', 12); ?>">
		<label><?php esc_html_e( $fixed_point_title, 'simontaxi' ); ?><?php echo simontaxi_required_field(); ?></label>
		<div class="inner-addon right-addon">
			<i class="st-icon icon-location-pin"></i>
			<?php
			// $airportname = 'drop_location';
			/**
			 * @since 2.0.8
			 */
			$airportname = $airport;
			if ( $modify ) {
				if ( isset( $booking_step1['airport'] ) ) {
					$airportname = $booking_step1['airport'];
				} else {
					$airportname = 'pickup_location';
				}
			}
			$selected_airport = ! empty( $_GET['airport'] ) ? $_GET['airport'] : '';
			?>
			<select class="selectpicker show-tick show-menu-arrow"  data-size="5" id="airportname" name="<?php echo esc_attr( $airportname ); ?>">
					
					<option value=""><?php esc_html_e( 'Choose ' . $fixed_point_title, 'simontaxi' )?></option>
					
					<?php
				foreach ( $airports as $airport) {
					$selected = '';
					$value = $airport['id'];					

					if ( $modify ) {
						if ( isset( $booking_step1['airport'] ) ) {
							if ( $booking_step1[$booking_step1['airport']] == $airport['id'] )
								$selected = 'selected';
						} elseif ( isset( $_POST[ $airportname ] ) && $_POST[ $airportname ] == $airport['id'] ) {
							$selected = 'selected';
						} elseif ( isset( $_POST[ $airportname . '_new' ] ) && $_POST[ $airportname . '_new' ] == $airport['id'] ) {
							$selected = 'selected';
						}
					} elseif ( $selected_airport == $value ) {
						$selected = 'selected';
					}
					echo '<option value="' . $value . '" ' . $selected . '>' . $airport['name'] . '</option>';
				}
				?>
			</select>
		</div>
	</div>
	<div class="form-group col-sm-<?php echo apply_filters('simontaxi_other_location_airport_width', 12); ?>" id="pickupfieldset">
		<label> <?php
				$location_type = '';
				if ( $modify ) {
					if ( isset( $booking_step1['airport'] ) ) {
							if ( $booking_step1['airport'] == 'pickup_location' ) {
								esc_html_e( 'Drop off', 'simontaxi' );
								$location_type = 'Drop off';
							}
							else {
								if ( $allow_twoway_airport == 'comingfrom' ) {
									esc_html_e( 'Drop off', 'simontaxi' );
									$location_type = 'Drop off';
								} else {
									esc_html_e( 'Pickup', 'simontaxi' );
									$location_type = 'Pickup';
								}
							}
					} else {
						if ( $allow_twoway_airport == 'comingfrom' ) {
							esc_html_e( 'Drop off', 'simontaxi' );
							$location_type = 'Drop off';
						} else {
							esc_html_e( 'Pickup', 'simontaxi' );
							$location_type = 'Pickup';
						}
					}
				} else {
					if ( $allow_twoway_airport == 'comingfrom' ) {
						esc_html_e( 'Drop off', 'simontaxi' );
						$location_type = 'Drop off';
					} else {
						esc_html_e( 'Pickup', 'simontaxi' );
						$location_type = 'Pickup';
					}
				} ?><?php esc_html_e( ' point', 'simontaxi' ); ?><?php echo simontaxi_required_field(); ?></label>
		<div class="inner-addon right-addon">
			<i class="st-icon icon-location-pin"></i>
			<?php
			if ( $airportname == 'pickup_location' ) {
				$name = 'drop_location';
			} else {
				$name = 'pickup_location';
			}
			if ( $modify ) {
				if ( isset( $booking_step1['airport'] ) && $booking_step1['airport'] == 'pickup_location' ) {
					$name = 'drop_location';
				} else {
					if ( isset( $_POST['airport'] ) && $_POST['airport'] == 'pickup_location' ) {
						$name = 'drop_location';
					}
				}
			}

			$value = '';
			if ( $modify ) {
				if ( isset( $booking_step1['airport'] ) &&
					$booking_step1['airport'] == 'pickup_location' ) {
						$value = simontaxi_get_value( $booking_step1, 'drop_location' );
					} else {
						$value = simontaxi_get_value( $booking_step1, $name );
						if ( empty( $value ) ) {
							$value = simontaxi_get_value( $booking_step1, $name . '_new' );
						}
					}
			} elseif ( isset( $booking_step1['pickup_location'] ) ) {
				$value = simontaxi_get_value( $booking_step1, 'pickup_location' );
				if ( empty( $value ) ) {
					$value = simontaxi_get_value( $booking_step1, 'pickup_location_new' );
				}
			} else {
				if ( ! empty( $_POST[ $name ] ) ) {
						$value = $_POST[ $name ];
					} elseif ( ! empty( $_POST[ $name . '_new' ] ) ) {
						$value = $_POST[ $name . '_new' ];
					}
			}
			
			if ( 'predefined_place' === $predefined_place_airport && 'dropdown' === $vehicle_places_airport_display ) {
				$pickup_locations = simontaxi_get_locations( 'pickup_location' );
				$pickup_location = simontaxi_get_value( $booking_step1, 'pickup_location_new' );
			?>
			<select name="<?php echo esc_attr( $name ); ?>" id="pickinguplocation" class="selectpicker show-tick show-menu-arrow" data-width="100%" data-size="5">
			<option value=""><?php esc_html_e( 'Please select ' . $location_type . ' point', 'simontaxi' ) ?></option>
			<?php
			if ( ! empty( $pickup_locations ) ) {
				foreach( $pickup_locations as $key => $val ) { ?>
				<option value="<?php echo $key ?>" <?php if( $pickup_location == $key ) { echo 'selected';} ?>><?php echo $val; ?></option>
				<?php
				}
			}
			?>
			</select>
			<?php } else {
			?>
			<input type="text" autocomplete="off" placeholder="<?php esc_html_e( 'Type and choose location', 'simontaxi' )?>" id="pickinguplocation" name="<?php echo esc_attr( $name ); ?>" class="form-control <?php echo $predefined_place_airport; ?>" value="<?php echo esc_attr( $value ); ?>" <?php echo (in_array( $vehicle_places_airport, array( 'googleall', 'googleregions', 'googlecities' ) ) ? 'onClick="initialize(this.id);" onFocus="initialize(this.id);"' : '' ) ?>>
			<?php } ?>
		</div>
	</div>
	<div class="form-group col-sm-<?php echo apply_filters('simontaxi_pickup_date_airport_width', 6); ?>">
		<label><?php echo simontaxi_get_pickupdate_title()?><?php echo simontaxi_required_field(); ?></label>
		<div class="inner-addon right-addon">
			<i class="st-icon icon-calendar"></i>
			<input type="text" class="form-control st_datepicker_limit" placeholder="<?php echo sprintf( esc_html__( 'Select %s', 'simontaxi' ), simontaxi_get_pickupdate_title() ); ?>" name="pickup_date" id="airport_pickup_date" value="<?php echo simontaxi_get_value( $booking_step1, 'pickup_date' ); ?>" />
		</div>
	</div>
	<div class="form-group col-sm-<?php echo apply_filters('simontaxi_pickup_time_airport_width', 6); ?> pickup_time_airport">
		<div class="row">
		<?php
		$hours = '';
		$minutes = '';
		if ( isset( $_POST['pickup_time_hours'] ) || isset( $_POST['pickup_time_minutes'] ) ) {
			$minutes = $_POST['pickup_time_minutes'];
			$hours = $_POST['pickup_time_hours'];
		} elseif ( isset( $booking_step1['pickup_time'] ) ) {
			$parts =  explode( ':', $booking_step1['pickup_time'] );
			$hours = trim( $parts[0] );
			$minutes = trim( $parts[1] );
		}
		?>
		<div class="col-xs-6 cs-pad-right">
		<label><?php echo simontaxi_get_pickuptime_title(); ?><?php echo simontaxi_required_field(); ?></label>
		<select class="selectpicker show-tick show-menu-arrow" data-size="5" name="pickup_time_hours" id="airport_pickup_time_hours">
			<option value=""><?php esc_html_e( 'Hour', 'simontaxi'  ); ?></option>
			<?php for ( $h = 0; $h <= 23; $h++ ) {
				$display_val = simontaxi_get_time_display_format( $h );
				$val = str_pad( $h,2,0, STR_PAD_LEFT);
				$sel = '';
				if ( $val == $hours )
					$sel = ' selected="selected"';
				echo '<option value="' . $val . '" ' . $sel . '>' . $display_val . '</option>';
			}?>
		</select>
		</div>

		<div class="col-xs-6 cs-pad-left">
		<label>&nbsp;</label>
		<select class="selectpicker show-tick show-menu-arrow" data-size="5" name="pickup_time_minutes" id="airport_pickup_time_minutes">
			<option value=""><?php esc_html_e( 'Min', 'simontaxi' ); ?></option>
			<?php for ( $m = 0; $m < 60; $m+=5 ) {
				$val = str_pad( $m,2,0, STR_PAD_LEFT);
				$sel = '';
				if ( $val == $minutes )
					$sel = ' selected="selected"';
				echo '<option value="' . $val . '" ' . $sel . '>' . $val . '</option>';
			}?>
		</select>
		</div>
	</div>
	</div>
	
	<?php if ( simontaxi_get_option( 'allow_number_of_persons', 'no' ) != 'no' ) { ?>
	<div class="form-group col-sm-<?php echo apply_filters('simontaxi_number_of_persons_airport_width', 12); ?>">
		<label> <?php esc_html_e( 'No. of persons', 'simontaxi' ); ?><?php if ( simontaxi_get_option( 'allow_number_of_persons', 'no' ) == 'yesrequired' ) { echo simontaxi_required_field(); }?></label>
		<div class="inner-addon right-addon">
			<input type="number" name="number_of_persons" id="number_of_persons" class="form-control number_of_persons_airport" placeholder="<?php esc_html_e( 'Enter No. persons', 'simontaxi' )?>" value="<?php if ( $modify ){ echo simontaxi_get_value( $booking_step1, 'number_of_persons' ); } ?>">
		</div>
	</div>
	<?php } ?>
	
	<?php if ( simontaxi_get_option( 'allow_flight_number', 'no' ) != 'no' ) :?>
	<div class="form-group col-sm-<?php echo apply_filters('simontaxi_flight_no_airport_width', 6); ?>">
		<label><?php esc_html_e( $fixed_point_vehicle_name . ' Number', 'simontaxi' ); ?><?php if ( simontaxi_get_option( 'allow_flight_number', 'no' ) == 'yesrequired' ) { echo simontaxi_required_field(); }?></label>
		<input type="text" class="form-control" placeholder="<?php esc_html_e( $fixed_point_vehicle_name . ' Number', 'simontaxi' ); ?>" name="flight_no" id="flight_no" value="<?php
		if ( $modify ) {
			echo simontaxi_get_value( $booking_step1, 'flight_no' );
			} ?>">
	</div>
	<?php endif; ?>
	
	<?php if ( simontaxi_get_option( 'allow_flight_arrival_time', 'no' ) != 'no' ) :?>
	<div class="form-group col-sm-<?php echo apply_filters('simontaxi_flight_arrival_time_airport_width', 6); ?>">
		<label><?php esc_html_e( $fixed_point_vehicle_name . ' Arrival Time', 'simontaxi' ); ?><?php if ( simontaxi_get_option( 'allow_flight_arrival_time', 'no' ) == 'yesrequired' ) { echo simontaxi_required_field(); }?></label>
		<input type="text" class="form-control" placeholder="<?php esc_html_e( $fixed_point_vehicle_name . ' Arrival Time. Eg: 15:26', 'simontaxi' ); ?>" name="flight_arrival_time" id="flight_arrival_time" value="<?php
		if ( $modify ) {
			echo simontaxi_get_value( $booking_step1, 'flight_arrival_time' );
			} ?>">
	</div>
	<?php endif; ?>

	<?php if ( simontaxi_is_allow_additional_pickups() == 'yes' ) :?>
	<div class="form-group col-sm-<?php echo apply_filters('simontaxi_additional_pickups_time_airport_width', 6); ?>">
		<label><?php esc_html_e( 'Additional Pickup Points', 'simontaxi' ); ?></label>
		<?php
		$additional_pickups = 0;
		if ( $modify ) {
			$additional_pickups = simontaxi_get_value( $booking_step1, 'additional_pickups', $additional_pickups);
		}
		?>
		<select class="selectpicker show-tick show-menu-arrow" data-width="100%" data-size="5" name="additional_pickups" id="additional_pickups">
			<option value="0"><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
			<?php for ( $i = 1; $i <= simontaxi_get_max_additional_pickups(); $i++ ) {
				$sel = '';
				if ( $i == $additional_pickups)
					$sel = ' selected="selected"';
				echo '<option value="' . $i . '" ' . $sel . '>' . $i . ' Points</option>';
			}?>
		</select>
	</div>
	<?php endif; ?>
	<?php if ( simontaxi_is_allow_additional_dropoff() == 'yes' ) :?>
	<div class="form-group col-sm-6">
		<label><?php esc_html_e( 'Additional Drop-off Points', 'simontaxi' ); ?></label>
		<?php
		$additional_dropoff = 0;
		if ( $modify ) {
			$additional_dropoff = simontaxi_get_value( $booking_step1, 'additional_dropoff', $additional_dropoff);
		}
		?>
		<select class="selectpicker show-tick show-menu-arrow" data-width="100%" data-size="5" name="additional_dropoff" id="additional_dropoff">
			<option value="0"><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
			<?php for ( $i = 1; $i <= simontaxi_get_max_additional_dropoff(); $i++ ) {
				$sel = '';
				if ( $i == $additional_dropoff )
					$sel = ' selected="selected"';
				echo '<option value="' . $i . '" ' . $sel . '>' . $i.esc_html__( ' Points', 'simontaxi' ) . ' </option>';
			}?>
		</select>
	</div>
	<?php endif; ?>
	<?php if ( simontaxi_get_option( 'allow_waiting_time', 'no' ) == 'yes' ) :?>
	<div class="form-group col-sm-6">
		<div class="row">

		<?php
		$hours = '';
		$minutes = '';
		if ( isset( $_POST['waiting_time_hours'] ) || isset( $_POST['waiting_time_minutes'] ) ) {
			$hours = $_POST['waiting_time_hours'];
			$minutes = $_POST['waiting_time_minutes'];
		} elseif ( isset( $booking_step1['waiting_time'] ) ) {
			$parts =  explode( ':', $booking_step1['waiting_time'] );
			$hours = trim( $parts[0] );
			$minutes = trim( $parts[1] );
		}
		?>
		<div class="col-xs-6 cs-pad-right">
		<label><?php esc_html_e( 'Waiting time', 'simontaxi' ); ?></label>
		<select class="selectpicker show-tick show-menu-arrow" data-size="5" name="waiting_time_hours" id="waiting_time_hours">
			<option value=""><?php esc_html_e( 'Hour', 'simontaxi' ); ?></option>
			<?php for ( $h = 0; $h <= 23; $h++ ) {
				$display_val = simontaxi_get_time_display_format( $h );
				$val = str_pad( $h,2,0, STR_PAD_LEFT);
				$sel = '';
				if ( $val == $hours )
					$sel = ' selected="selected"';
				echo '<option value="' . $val . '" ' . $sel . '>' . $display_val . '</option>';
			}?>
		</select>
		</div>
		<div class="col-xs-6 cs-pad-left">
		<label>&nbsp;</label>
		<select class="selectpicker show-tick show-menu-arrow" data-size="5" name="waiting_time_minutes" id="waiting_time_minutes">
			<option value=""><?php esc_html_e( 'Min', 'simontaxi' ); ?></option>
			<?php for ( $m = 0; $m < 60; $m+=5 ) {
				$val = str_pad( $m,2,0, STR_PAD_LEFT);
				$sel = '';
				if ( $val == $minutes )
					$sel = ' selected="selected"';
				echo '<option value="' . $val . '" ' . $sel . '>' . $val . '</option>';
			}?>
		</select>
		</div>
		</div>
	</div>
	<?php endif; ?>
	<?php
	do_action( 'simontaxi_step1_airport_additional_fields', array( 
		'booking_step1' => $booking_step1,
		'type' => 'airport',
	) );
	?>
	<?php if ( simontaxi_terms_page() == 'step1' ) : ?>
	<div class="col-sm-12">
		<div class="input-group st-top40">
			<div>
				<input id="terms_airport" type="checkbox" name="terms" value="option">
				<label for="terms_airport"><span><span></span></span><i class="st-terms-accept"><?php echo simontaxi_terms_text(); ?></i></label>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<div class="col-sm-12">
		<button type="submit" class="btn btn-primary btn-mobile" name="validtestep1" id="validtestep1airportbutton" value="validtestep1airport"><?php echo apply_filters( 'simontaxi_filter_nextstep_title', esc_html__( 'Next Step', 'simontaxi' ) ); ?></button>
		<?php do_action( 'simontaxi_step1_other_buttons', 'airport' ); ?>

		<input type="hidden" name="distance" id="distance_airport" value="<?php if ( $modify ) { echo simontaxi_get_value( $booking_step1, 'distance' );} ?>">
		<input type="hidden" name="distance_text" id="distance_text_airport" value="<?php if ( $modify ) { echo simontaxi_get_value( $booking_step1, 'distance_text' );} ?>">
		<input type="hidden" name="duration_text" id="duration_text_airport" value="<?php if ( $modify ) { echo simontaxi_get_value( $booking_step1, 'duration_text' );} ?>">
		<input type="hidden" name="distance_units" id="distance_units_airport" value="<?php if ( $modify ) { echo simontaxi_get_value( $booking_step1, 'distance_units' );} ?>">
	</div>
</form>