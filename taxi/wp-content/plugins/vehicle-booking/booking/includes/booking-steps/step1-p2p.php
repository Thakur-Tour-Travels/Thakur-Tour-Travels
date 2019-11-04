<!-- Booking Progress -->
<?php
// echo $default_breadcrumb_display_step1;die();
if ( 'yes' === $default_breadcrumb_display_step1 && in_array( $placement, array( 'hometop', 'homeleft' ) ) ) {
	// echo $breadcrumb;
	echo '<p class="st-breadcrumb-empty">&nbsp;</p>';
}?>
<!-- end Booking Progress -->
<form id="booking-p2p" action="" method="POST" class="st-booking-form row">
<input type="hidden" name="booking_type" value="p2p">
	<?php if ( simontaxi_is_allow_twoway_booking() == 'yes' ) :?>
	<div class="col-sm-<?php echo apply_filters('simontaxi_journey_type_p2p_width', 12); ?>">
		<div class="input-group st-radio">
			<input id="radio11" type="radio" name="journey_type" value="one_way" onclick="toggle_show(this.value)" <?php if ( $modify ) { if ( simontaxi_get_value( $booking_step1, 'journey_type' ) == 'one_way' ) echo 'checked'; } else echo 'checked'?>>
			<label for="radio11"><span><span></span></span><?php echo simontaxi_get_oneway_title(); ?></label>
			<input id="radio22" type="radio" name="journey_type" value="two_way" onclick="toggle_show(this.value)" <?php if ( $modify ) { if ( simontaxi_get_value( $booking_step1, 'journey_type' ) == 'two_way' ) echo 'checked'; }?>>
			<label for="radio22"><span><span></span></span><?php echo simontaxi_get_twoway_title(); ?></label>
		</div>
	</div>
	<?php else:
	?>
	<input type="hidden" name="journey_type" value="one_way">
	<?php
	endif; ?>
	<?php
	$vehicle_country = simontaxi_get_option( 'vehicle_country', 'US' );
	?>
	<?php $enable_country_selection_p2p = simontaxi_get_option( 'enable_country_selection_p2p', 'no' ); 
	if ( 'yes' === $enable_country_selection_p2p ) {
	?>
	<div class="form-group col-sm-<?php echo apply_filters('simontaxi_pickup_country_p2p_width', 6); ?>">
		<label for="pickup_location_country"> <?php echo simontaxi_get_pickuppoint_title() . ' ' . esc_html__('Country', 'simontaxi'); ?><?php echo simontaxi_required_field(); ?></label>
		<div class="inner-addon right-addon">
			<?php
			$service_countries = simontaxi_get_option( 'service_countries', array() );
			if ( ! in_array( $vehicle_country, $service_countries ) ) {
				array_push( $service_countries, $vehicle_country);
			}
			$pickup_location_country = simontaxi_get_value( $booking_step1, 'pickup_location_country', $vehicle_country );
			?>
			<select name="pickup_location_country" id="pickup_location_country" class="selectpicker show-tick show-menu-arrow" data-width="100%" data-size="5">
			<?php
			if ( ! empty( $service_countries ) ) {
				foreach( $service_countries as $key => $val ) { 
				$details = simontaxi_countries( 'no', $val );
				$name = $details[ $val ];
				?>
				<option value="<?php echo $val ?>" <?php if( $pickup_location_country == $val ) { echo 'selected';} ?>><?php echo $name; ?></option>
				<?php
				}
			}
			?>
			</select>
		</div>
	</div>
	<?php } ?>

	<div class="form-group col-sm-<?php echo apply_filters('simontaxi_pickup_location_p2p_width', 6); ?>">
		<label for="pickup_location"> <?php echo simontaxi_get_pickuppoint_title(); ?><?php echo simontaxi_required_field(); ?></label>
		<div class="inner-addon right-addon">
			<?php
			if ( 'predefined_place' === $predefined_place && 'dropdown' === $vehicle_places_display ) {
			$pickup_locations = simontaxi_get_locations( 'pickup_location' );
			$pickup_location = simontaxi_get_value( $booking_step1, 'pickup_location_new' );

			?>
			<select name="pickup_location" id="pickup_location" class="selectpicker show-tick show-menu-arrow" data-width="100%" data-size="5">
			<option value=""><?php esc_html_e( 'Please select ' .simontaxi_get_pickuppoint_title() ) ?></option>
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
			$plocation = simontaxi_get_value( $booking_step1, 'pickup_location' );
			if ( empty( $plocation ) ) {
				$plocation = simontaxi_get_value( $booking_step1, 'pickup_location_new' );
			}
			?>
			<i class="st-icon icon-location-pin"></i>
			<input type="text" class="form-control required <?php echo $predefined_place ?>" placeholder="<?php echo simontaxi_get_pickuppoint_title(); ?>" name="pickup_location" id="pickup_location" <?php echo (in_array( $vehicle_places, array( 'googleall', 'googleregions', 'googlecities' ) ) ) ? 'onClick="initialize(this.id);" onFocus="initialize(this.id);"' : ''; ?> value="<?php if ( $modify ){ echo $plocation; }?>" autocomplete="off" tabIndex="0"/>
			<?php } ?>
			<?php if ( 'no' === $enable_country_selection_p2p ) { ?>
			<input type="hidden" id="pickup_location_country" name="pickup_location_country" value="<?php if ( $modify ){ echo simontaxi_get_value( $booking_step1, 'pickup_location_country', $vehicle_country ); } else { echo $vehicle_country; } ?>">
			<?php } ?>
			<input type="hidden" id="pickup_location_lat" name="pickup_location_lat" value="<?php if ( $modify ){ echo simontaxi_get_value( $booking_step1, 'pickup_location_lat' ); }?>">
			<input type="hidden" id="pickup_location_lng" name="pickup_location_lng" value="<?php if ( $modify ){ echo simontaxi_get_value( $booking_step1, 'pickup_location_lng' ); }?>">
		</div>
	</div>
	
	<?php
	$vehicle_country_dropoff = simontaxi_get_option( 'vehicle_country_dropoff', 'US' );
	if ( 'yes' === $enable_country_selection_p2p ) {
	?>
	<div class="form-group col-sm-<?php echo apply_filters('simontaxi_drop_location_p2p_width', 6); ?>">
		<label for="drop_location_country"> <?php echo simontaxi_get_dropoffpoint_title() . ' ' . esc_html__('Country', 'simontaxi'); ?><?php echo simontaxi_required_field(); ?></label>
		<div class="inner-addon right-addon">
			<?php
			$service_countries = simontaxi_get_option( 'service_countries', array() );
			if ( ! in_array( $vehicle_country_dropoff, $service_countries ) ) {
				array_push( $service_countries, $vehicle_country_dropoff);
			}
			$drop_location_country = simontaxi_get_value( $booking_step1, 'drop_location_country', $vehicle_country_dropoff );
			?>
			<select name="drop_location_country" id="drop_location_country" class="selectpicker show-tick show-menu-arrow" data-width="100%" data-size="5">
			<?php
			if ( ! empty( $service_countries ) ) {
				foreach( $service_countries as $key => $val ) { 
				$details = simontaxi_countries( 'no', $val );
				$name = $details[ $val ];
				?>
				<option value="<?php echo $val ?>" <?php if( $drop_location_country == $val ) { echo 'selected';} ?>><?php echo $name; ?></option>
				<?php
				}
			}
			?>
			</select>
		</div>
	</div>
	<?php } ?>
	<div class="form-group col-sm-<?php echo apply_filters('simontaxi_drop_location_p2p_width', 6); ?>">
		<label for="drop_location"> <?php echo simontaxi_get_dropoffpoint_title(); ?><?php echo simontaxi_required_field(); ?></label>
		<div class="inner-addon right-addon">
			<?php if ( '' !== $predefined_place_dropoff && 'dropdown' === $vehicle_places_dropoff_display ) {
			$drop_locations = simontaxi_get_locations( 'drop_location' );
			$drop_location = simontaxi_get_value( $booking_step1, 'drop_location_new' );
			?>
			<select name="drop_location" id="drop_location" class="selectpicker show-tick show-menu-arrow" data-width="100%" data-size="5">
			<option value=""><?php esc_html_e( 'Please select ' . simontaxi_get_dropoffpoint_title() ) ?></option>
			<?php
			if ( ! empty( $drop_locations ) ) {
				foreach( $drop_locations as $key => $val ) { ?>
				<option value="<?php echo $key ?>" <?php if ( $drop_location == $key ) { echo 'selected';}?>><?php echo $val; ?></option>
				<?php
				}
			}
			?>
			</select>
			<?php
			} else {
				$dlocation = simontaxi_get_value( $booking_step1, 'drop_location' );
				if ( empty( $dlocation ) ) {
					$dlocation = simontaxi_get_value( $booking_step1, 'drop_location_new' );
				}
			?>
			<i class="st-icon icon-location-pin"></i>
			<input type="text" class="form-control <?php echo $predefined_place_dropoff?>" placeholder="<?php echo simontaxi_get_dropoffpoint_title(); ?>" <?php echo (in_array( $vehicle_places_dropoff, array( 'googleall', 'googleregions', 'googlecities' ) ) ? 'onClick="initialize(this.id);" onFocus="initialize(this.id);"' : '' ) ?> value="<?php if ( $modify ){ echo $dlocation; }?>" name="drop_location" id="drop_location" autocomplete="off" tabIndex="1"/>
			<?php } ?>

			<?php if ( 'no' === $enable_country_selection_p2p ) { ?>
			<input type="hidden" id="drop_location_country" name="drop_location_country" value="<?php if ( $modify ){ echo simontaxi_get_value( $booking_step1, 'drop_location_country', $vehicle_country_dropoff ); } else { echo $vehicle_country_dropoff; } ?>">
			<?php } ?>
			<input type="hidden" id="drop_location_lat" name="drop_location_lat" value="<?php if ( $modify ){ echo simontaxi_get_value( $booking_step1, 'drop_location_lat' ); }?>">
			<input type="hidden" id="drop_location_lng" name="drop_location_lng" value="<?php if ( $modify ){ echo simontaxi_get_value( $booking_step1, 'drop_location_lng' ); }?>">
		</div>
	</div>
	<div class="form-group col-sm-<?php echo apply_filters('simontaxi_pickup_time_p2p_width', 6); ?>">
		<label for="p2p_pickup_date"><?php echo simontaxi_get_pickupdate_title(); ?><?php echo simontaxi_required_field(); ?></label>
		<div class="inner-addon right-addon">
			<i class="st-icon icon-calendar"></i>
			<input type="text" class="form-control st_datepicker_limit" data-language='en' data-timepicker="false" placeholder="<?php echo simontaxi_get_pickupdate_title(); ?>" name="pickup_date" id="p2p_pickup_date" value="<?php if ( $modify ) {
			$pickup_date = simontaxi_get_value( $booking_step1, 'pickup_date' );
			if ( $pickup_date != '' )
				echo date(simontaxi_get_option( 'st_date_format', 'd-m-Y' ), strtotime( $pickup_date) ); } ?>">
		</div>
	</div>
	<div class="form-group col-sm-6 pickup_time">
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
		<label for="pickup_time_hours"><?php echo simontaxi_get_pickuptime_title(); ?><?php echo simontaxi_required_field(); ?></label>
		<select class="selectpicker show-tick show-menu-arrow" data-size="5" name="pickup_time_hours" id="pickup_time_hours">
			<option value=""><?php esc_html_e( 'Hour', 'simontaxi' ); ?></option>
			<?php for ( $h = 0; $h <= 23; $h++ ) {
				$display_val = simontaxi_get_time_display_format( $h );
				$val = str_pad( $h,2,0, STR_PAD_LEFT);
				$sel = '';
				if ( $val == $hours)
					$sel = ' selected="selected"';
				echo '<option value="' . $val . '" ' . $sel . '>' . $display_val . '</option>';
			}?>
		</select>
		</div>
		<div class="col-xs-6 cs-pad-left">
		<label>&nbsp;</label>
		<select class="selectpicker show-tick show-menu-arrow" data-size="5" name="pickup_time_minutes" id="pickup_time_minutes">
			<option value=""><?php esc_html_e( 'Min', 'simontaxi' ); ?></option>
			<?php for ( $m = 0; $m < 60; $m+=5 ) {
				$val = str_pad( $m,2,0, STR_PAD_LEFT);
				$sel = '';
				if ( $val == $minutes)
					$sel = ' selected="selected"';
				echo '<option value="' . $val . '" ' . $sel . '>' . $val . '</option>';
			}?>
		</select>
		</div>
		</div>
	</div>
	
	<?php if ( simontaxi_get_option( 'allow_number_of_persons', 'no' ) != 'no' ) { ?>
	<div class="form-group col-sm-12">
		<label for="number_of_persons"> <?php esc_html_e( 'No. of persons', 'simontaxi' ); ?><?php if ( simontaxi_get_option( 'allow_number_of_persons', 'no' ) == 'yesrequired' ) { echo simontaxi_required_field(); }?></label>
		<div class="inner-addon right-addon">
			<input type="number" name="number_of_persons" id="number_of_persons" class="form-control number_of_persons_p2p" placeholder="<?php esc_html_e( 'Enter No. persons', 'simontaxi' )?>" value="<?php if ( $modify ){ echo simontaxi_get_value( $booking_step1, 'number_of_persons' ); } else { echo 1; } ?>" min="1">
		</div>
	</div>
	<?php } ?>
	
	<?php if ( simontaxi_is_allow_additional_pickups() == 'yes' ) :?>
	<div class="form-group col-sm-6">
		<label for="additional_pickups"><?php esc_html_e( 'Additional Pickup Points', 'simontaxi' ); ?></label>
		<?php
		$additional_pickups = 0;
		if ( $modify ) {
			$additional_pickups = simontaxi_get_value( $booking_step1, 'additional_pickups', $additional_pickups);
		}
		?>
		<select class="selectpicker show-tick show-menu-arrow" data-width="100%" data-size="5" name="additional_pickups" id="additional_pickups">
			<option value="0"><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
			<?php for( $i = 1; $i <= simontaxi_get_option( 'max_additional_pickups', 5); $i++) {
				$sel = '';
				if ( $i == $additional_pickups)
					$sel = ' selected="selected"';
				echo '<option value="' . $i . '" ' . $sel . '>' . $i.esc_html__( ' Points', 'simontaxi' ) . '</option>';
			}?>
		</select>
	</div>
	<?php endif; ?>
	<?php if ( simontaxi_is_allow_additional_dropoff() == 'yes' ) :?>
	<div class="form-group col-sm-6">
		<label for="additional_dropoff"><?php esc_html_e( 'Additional Dropoff Points', 'simontaxi' ); ?></label>
		<?php
		$additional_dropoff = 0;
		if ( $modify ) {
			$additional_dropoff = simontaxi_get_value( $booking_step1, 'additional_dropoff', $additional_dropoff);
		}
		?>
		<select class="selectpicker show-tick show-menu-arrow" data-width="100%" data-size="5" name="additional_dropoff" id="additional_dropoff">
			<option value="0"><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
			<?php for( $i = 1; $i <= simontaxi_get_max_additional_dropoff(); $i++) {
				$sel = '';
				if ( $i == $additional_dropoff)
					$sel = ' selected="selected"';
				echo '<option value="' . $i . '" ' . $sel . '>' . $i . ' Points</option>';
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
		<label for="waiting_time_hours"><?php esc_html_e( 'Waiting time', 'simontaxi' ); ?></label>
		<select class="selectpicker show-tick show-menu-arrow" data-size="5" name="waiting_time_hours" id="waiting_time_hours">
			<option value=""><?php esc_html_e( 'Hour', 'simontaxi' ); ?></option>
			<?php for ( $h = 0; $h <= 23; $h++ ) {
				// $display_val = simontaxi_get_time_display_format( $h );
				$display_val = $h;
				$val = str_pad( $h,2,0, STR_PAD_LEFT);
				$sel = '';
				if ( $val == $hours)
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

	<div <?php if ( isset( $booking_step1[ 'journey_type'] )&& $booking_step1[ 'journey_type']=='two_way' ) echo 'style="display:block;"'; else echo 'style="display:none;"'; ?> id="showvalue">
	<div class="form-group col-sm-6">
		<label for="pickup_date_return"><?php echo esc_html__( 'Return ', 'simontaxi' ) . simontaxi_get_pickupdate_title(); ?><?php echo simontaxi_required_field(); ?></label>
		<div class="inner-addon right-addon">
			<i class="st-icon icon-calendar"></i>
			<input type="text" class="form-control st_datepicker_limit_return" data-language='en' data-timepicker="false" placeholder="<?php echo simontaxi_get_pickupdate_title(); ?>" name="pickup_date_return" id="pickup_date_return" value="<?php

if ( simontaxi_get_value( $booking_step1, 'pickup_date_return' ) != '' )
echo date(simontaxi_get_option( 'st_date_format', 'd-m-Y' ), strtotime(simontaxi_get_value( $booking_step1, 'pickup_date_return' ) ) );

?>">
		</div>
	</div>
	<div class="form-group col-sm-12">
	  <div class="row">
		<?php
		$hours = '';
		$minutes = '';
		if ( isset( $booking_step1['pickup_time_return'] ) ) {
			$parts =  explode( ':', $booking_step1['pickup_time_return'] );
			$hours = trim( $parts[0] );
			$minutes = trim( $parts[1] );
		}
		?>
		<div class="col-xs-6 cs-pad-right">
		<label for="pickup_time_hours_return"><?php esc_html_e( 'Return ', 'simontaxi' );
		echo simontaxi_get_pickuptime_title(); ?>
		<?php echo simontaxi_required_field(); ?></label>
		<select class="selectpicker show-tick show-menu-arrow" data-size="5" name="pickup_time_hours_return" id="pickup_time_hours_return">
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
		<select class="selectpicker show-tick show-menu-arrow" data-size="5" name="pickup_time_minutes_return" id="pickup_time_minutes_return">
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

	<?php if ( simontaxi_get_option( 'allow_additional_pickups_return', 'no' ) == 'yes' ) :?>
	<div class="form-group col-sm-6">
		<label for="additional_pickups_return"><?php esc_html_e( 'Additional Pickup Points (Return)', 'simontaxi' ); ?></label>
		<?php
		$additional_pickups_return = 0;
		if ( $modify ) {
			$additional_pickups = simontaxi_get_value( $booking_step1, 'additional_pickups_return', $additional_pickups_return);
		}
		?>
		<select class="selectpicker show-tick show-menu-arrow" data-width="100%" data-size="5" name="additional_pickups_return" id="additional_pickups_return">
			<option value="0"><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
			<?php for( $i = 1; $i <= simontaxi_get_option( 'max_additional_dropoff', '5' ); $i++ ) {
				$sel = '';
				if ( $i == $additional_pickups_return )
					$sel = ' selected="selected"';
				echo '<option value="' . $i . '" ' . $sel . '>' . $i.esc_html__( ' Points', 'simontaxi' ) . ' </option>';
			}?>
		</select>
	</div>
	<?php endif; ?>
	<?php if ( simontaxi_get_option( 'allow_additional_pickups_return', 'no' ) == 'yes' ) :?>
	<div class="form-group col-sm-6">
		<label for="additional_dropoff_return"><?php esc_html_e( 'Additional Dropoff Points (Return)', 'simontaxi' ); ?></label>
		<?php
		$additional_dropoff_return = 0;
		if ( $modify ) {
			$additional_dropoff_return = simontaxi_get_value( $booking_step1, 'additional_dropoff_return', $additional_dropoff_return);
		}
		?>
		<select class="selectpicker show-tick show-menu-arrow" data-width="100%" data-size="5" name="additional_dropoff_return" id="additional_dropoff_return">
			<option value="0"><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
			<?php for ( $i = 1; $i <= simontaxi_get_option( 'max_additional_dropoff_return', '5' ); $i++ ) {
				$sel = '';
				if ( $i == $additional_dropoff_return )
					$sel = ' selected="selected"';
				echo '<option value="' . $i . '" ' . $sel . '>' . $i.esc_html__( ' Points', 'simontaxi' ) . '</option>';
			}?>
		</select>
	</div>
	<?php endif; ?>
	<?php if ( simontaxi_get_option( 'allow_waiting_time_return', 'no' ) == 'yes' ) :?>
	<div class="form-group col-sm-6">
		<div class="row">

		<?php
		$hours = '';
		$minutes = '';
		if ( isset( $_POST['waiting_time_hours_return'] ) || isset( $_POST['waiting_time_minutes_return'] ) ) {
			$hours = $_POST['waiting_time_hours_return'];
			$minutes = $_POST['waiting_time_minutes_return'];
		} elseif ( isset( $booking_step1['waiting_time_return'] ) ) {
			$parts =  explode( ':', $booking_step1['waiting_time_return'] );
			$hours = trim( $parts[0] );
			$minutes = trim( $parts[1] );
		}
		?>
		<div class="col-xs-6 cs-pad-right">
		<label for="waiting_time_hours_return"><?php esc_html_e( 'Waiting time (Return)', 'simontaxi' ); ?></label>
		<select class="selectpicker show-tick show-menu-arrow" data-width="47%" data-size="5" name="waiting_time_hours_return" id="waiting_time_hours_return">
			<option value=""><?php esc_html_e( 'Hour', 'simontaxi' ); ?></option>
			<?php for ( $h = 0; $h <= 23; $h++ ) {
				// $display_val = simontaxi_get_time_display_format( $h );
				$display_val = $h;
				$val = str_pad( $h,2,0, STR_PAD_LEFT);
				$sel = '';
				if ( $val == $hours)
					$sel = ' selected="selected"';
				echo '<option value="' . $val . '" ' . $sel . '>' . $display_val . '</option>';
			}?>
		</select>
		</div>

		<div class="col-xs-6 cs-pad-left">
		<label>&nbsp;</label>
		<select class="selectpicker show-tick show-menu-arrow" data-width="47%" data-size="5" name="waiting_time_minutes_return" id="waiting_time_minutes_return">
			<option value=""><?php esc_html_e( 'Min', 'simontaxi' ); ?></option>
			<?php for ( $m = 0; $m < 60; $m+=5 ) {
				$val = str_pad( $m,2,0, STR_PAD_LEFT);
				$sel = '';
				if ( $val == $minutes)
					$sel = ' selected="selected"';
				echo '<option value="' . $val . '" ' . $sel . '>' . $val . '</option>';
			}?>
		</select>
		</div>
		</div>
	</div>
	<?php endif; ?>
	
	</div>
	
	<?php
	do_action( 'simontaxi_step1_p2p_additional_fields', array( 
		'booking_step1' => $booking_step1,
		'type' => 'p2p',
	) );
	?>
	<?php if ( simontaxi_terms_page() == 'step1' ) : ?>
	<div class="col-sm-12">
		<div class="input-group st-top40">
			<div>
				<input id="terms" type="checkbox" name="terms" value="option">
				<label class="terms_label" for="terms"><span><span></span></span><i class="st-terms-accept"><?php echo simontaxi_terms_text(); ?></i></label>
			</div>
		</div>
	</div>
	<?php endif; ?>


	<div class="col-sm-12">
		<button type="submit" class="btn btn-primary btn-mobile" name="validtestep1" id="validtestep1p2pbutton" value="validtestep1p2p"><?php echo apply_filters( 'simontaxi_filter_nextstep_title', esc_html__( 'Next Step', 'simontaxi' ) ); ?></button>
		<?php do_action('simontaxi_step1_other_buttons', 'p2p' ); ?>

		<input type="hidden" name="distance" id="distance" value="<?php if ( $modify ) { echo simontaxi_get_value( $booking_step1, 'distance' );} ?>">
		<input type="hidden" name="distance_text" id="distance_text" value="<?php if ( $modify ) { echo simontaxi_get_value( $booking_step1, 'distance_text' );} ?>">
		<input type="hidden" name="duration_text" id="duration_text" value="<?php if ( $modify ) { echo simontaxi_get_value( $booking_step1, 'duration_text' );} ?>">
		<input type="hidden" name="distance_units" id="distance_units" value="<?php if ( $modify ) { echo simontaxi_get_value( $booking_step1, 'distance_units' );} ?>">
	</div>

</form>