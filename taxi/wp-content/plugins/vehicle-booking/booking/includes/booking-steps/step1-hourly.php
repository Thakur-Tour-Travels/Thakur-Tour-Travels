<!-- Booking Progress -->
<?php if ( in_array( $placement, array( 'hometop', 'homeleft' ) ) ) {
	echo '<p class="st-breadcrumb-empty">&nbsp;</p>';
}?>
<!-- end Booking Progress -->

<form class="st-booking-form row" id="booking-hourly" action="" method="POST">
	<input type="hidden" name="booking_type" value="hourly">
	<input type="hidden" name="journey_type" value="one_way">
	<div class="form-group col-sm-<?php echo apply_filters('simontaxi_hourly_package_hourly_width', 12); ?>">
		<label><?php esc_html_e( 'Package', 'simontaxi' ); ?><?php echo simontaxi_required_field(); ?></label>
		<div class="inner-addon right-addon">
			<?php
			$hourly_package = ! empty( $_GET['package'] ) ? $_GET['package'] : '';
			if ( $modify ) {
				$hourly_package = simontaxi_get_value( $booking_step1, 'hourly_package' );
			}
			?>
			<select class="selectpicker show-tick show-menu-arrow" data-width="100%" data-size="5" name="hourly_package" id="hourly_package">
				<option value=""><?php esc_html_e( 'Please select', 'simontaxi' ); ?></option>
				<?php
				foreach ( $hourly_packs as $pack) {
					$selected = '';
					
					if ( $hourly_package == $pack['slug'] )
						$selected = 'selected';
					
					echo '<option value="' . $pack['slug'] . '" ' . $selected . '>' . $pack['name'] . ' (' . $pack['hourly_hours'] . ' hours' . ')</option>';
				}
				?>
			</select>
		</div>
	</div>
	<div class="form-group col-sm-<?php echo apply_filters('simontaxi_pickup_location_hourly_width', 12); ?>">
		<label> <?php echo simontaxi_get_pickuppoint_title(); ?><?php echo simontaxi_required_field(); ?></label>
		<div class="inner-addon right-addon">
			<?php
			if ( 'predefined_place' === $predefined_place_hourly && 'dropdown' === $vehicle_places_hourly_display ) {
			$pickup_locations = simontaxi_get_locations( 'pickup_location' );
			$pickup_location = simontaxi_get_value( $booking_step1, 'pickup_location_new' );
			?>
			<select name="pickup_location" id="hourly_pickup_location" class="selectpicker show-tick show-menu-arrow" data-width="100%" data-size="5">
			<option value=""><?php esc_html_e( 'Please select ' . simontaxi_get_pickuppoint_title() ) ?></option>
			<?php
			if ( ! empty( $pickup_locations ) ) {
				foreach( $pickup_locations as $key => $val ) { ?>
				<option value="<?php echo $key ?>" <?php if( $pickup_location == $key ) { echo 'selected';} ?>><?php echo $val; ?></option>
				<?php
				}
			}
			?>
			</select>
			<?php
			} else {
				$plocation = simontaxi_get_value( $booking_step1, 'pickup_location' );
				if ( empty( $plocation ) ) {
					$plocation = simontaxi_get_value( $booking_step1, 'pickup_location_new' );
				}
			?>
			<i class="st-icon icon-location-pin"></i>
			<input type="text" class="form-control required <?php echo $predefined_place_hourly; ?>" placeholder="<?php echo simontaxi_get_pickuppoint_title(); ?>" name="pickup_location" id="hourly_pickup_location" <?php echo (in_array( $vehicle_places_hourly, array( 'googleall', 'googleregions', 'googlecities' ) ) ) ? 'onClick="initialize(this.id);" onFocus="initialize(this.id);"' : ''; ?> value="<?php if ( $modify ){ echo $plocation; }?>" autocomplete="off"/>
			<?php } ?>
		</div>
	</div>
	<div class="form-group col-sm-<?php echo apply_filters('simontaxi_pickup_date_hourly_width', 6); ?>">
		<label><?php echo simontaxi_get_pickupdate_title(); ?><?php echo simontaxi_required_field(); ?></label>
		<div class="inner-addon right-addon">
			<i class="st-icon icon-calendar"></i>
			<input type="text" class="form-control st_datepicker_limit" placeholder="<?php echo simontaxi_get_pickupdate_title(); ?>" name="pickup_date" id="hourly_pickup_date" value="<?php
			if ( $modify ) {
				$pickup_date = simontaxi_get_value( $booking_step1, 'pickup_date' );
				if ( $pickup_date != '' )
					echo date(simontaxi_get_option( 'st_date_format', 'd-m-Y' ), strtotime( $pickup_date) );
				}?>">
		</div>
	</div>
	<div class="form-group col-sm-<?php echo apply_filters('simontaxi_pickup_time_hourly_width', 6); ?> pickup_time_hourly">
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
		<select class="selectpicker show-tick show-menu-arrow" data-size="5" name="pickup_time_hours" id="hourly_pickup_time_hours">
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
		<select class="selectpicker show-tick show-menu-arrow" data-size="5" name="pickup_time_minutes" id="hourly_pickup_time_minutes">
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
	<div class="form-group col-sm-<?php echo apply_filters('simontaxi_number_of_persons_hourly_width', 12); ?>">
		<label> <?php esc_html_e( 'No. of persons', 'simontaxi' ); ?><?php if ( simontaxi_get_option( 'allow_number_of_persons', 'no' ) == 'yesrequired' ) { echo simontaxi_required_field(); }?></label>
		<div class="inner-addon right-addon">
			<input type="number" name="number_of_persons" id="number_of_persons" class="form-control number_of_persons_hourly" placeholder="<?php esc_html_e( 'Enter No. persons', 'simontaxi' )?>" value="<?php if ( $modify ){ echo simontaxi_get_value( $booking_step1, 'number_of_persons' ); } ?>">
		</div>
	</div>
	<?php } ?>

	<?php if ( simontaxi_get_option( 'allow_itinerary', 'no' ) != 'no' ) { ?>
	<div class="form-group col-sm-<?php echo apply_filters('simontaxi_itineraries_hourly_width', 12); ?>">
		<label> <?php esc_html_e( 'Itinerary', 'simontaxi' ); ?><?php if ( simontaxi_get_option( 'allow_itinerary', 'no' ) == 'yesrequired' ) { echo simontaxi_required_field(); }?></label>
		<div class="inner-addon right-addon">
			<textarea name="itineraries" id="itineraries" rows="4" cols="60" class="form-control" placeholder="<?php esc_html_e( 'Itinerary example : 1). Legoland 2). AEON Bukit Indah 3). etc. 4. Return drop off address', 'simontaxi' )?>"><?php if ( $modify ){ echo simontaxi_get_value( $booking_step1, 'itineraries' );}?></textarea>
		</div>
	</div>
	<?php } ?>
	
	<?php
	do_action( 'simontaxi_step1_hourlyrental_additional_fields', array( 
		'booking_step1' => $booking_step1,
		'type' => 'hourlyrental',
	) );
	?>							
	
	<?php if ( simontaxi_terms_page() == 'step1' ) : ?>
	<div class="col-sm-12">
		<div class="input-group st-top40">
			<div>
				<input id="hourly_terms" type="checkbox" name="terms" value="option">
				<label for="hourly_terms"><span><span></span></span><i class="st-terms-accept"><?php echo simontaxi_terms_text(); ?></i></label>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<div class="col-sm-12">
		<button type="submit" class="btn btn-primary btn-mobile" name="validtestep1" id="validtestep1hourlybutton" value="validtestep1hourly"><?php echo apply_filters( 'simontaxi_filter_nextstep_title', esc_html__( 'Next Step', 'simontaxi' ) ); ?></button>
		<?php do_action( 'simontaxi_step1_other_buttons', 'hourly' ); ?>
	</div>
</form>
