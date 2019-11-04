<table class="table table-hover st-table st-table-pagination st-table-select-cab">
	<tr class="st-table-head">
	<td><?php esc_html_e( 'Select', 'simontaxi' ); ?></td>
	<td>&nbsp;</td>
	<td>
	<?php
	/**
	 * @since 2.0.1
	 */									
	$step2_url = simontaxi_get_bookingsteps_urls( 'step2' ) . '?vname=desc';
	if ( isset( $_GET['vname'] ) && 'desc' === $_GET['vname'] ) {
		$step2_url = simontaxi_get_bookingsteps_urls( 'step2' ) . '?vname=asc';
	}
	?>
	<a href="<?php echo esc_url( $step2_url ); ?>" title="<?php esc_html_e( 'Vehicle', 'simontaxi' ); ?>"><?php esc_html_e( 'Vehicle', 'simontaxi' ); ?></a>
	<?php $cols = 3; ?>
	</td>
	<?php if ( simontaxi_get_option( 'show_luggage_information', 'yes' ) == 'yes' ) {
	$cols++;
	?>
	<td><?php esc_html_e( 'Luggage', 'simontaxi' ); ?></td>
	<?php } ?>
	<?php
	/**
	 * @since 2.0.1
	 */									
	$step2_url = simontaxi_get_bookingsteps_urls( 'step2' ) . '?price=desc';
	if ( isset( $_GET['price'] ) && 'desc' === $_GET['price'] ) {
		$step2_url = simontaxi_get_bookingsteps_urls( 'step2' ) . '?price=asc';
	}								
	
	$show_fare = simontaxi_get_option( 'show_fare', 'totalbasic' );
	/**
	 * Let us display the fare for the user based on admin settings.
	 */
	if ( in_array( $show_fare, array( 'totalbasic', 'totalonly', 'totalwithminfare' ) ) ) {
	$cols++;
	?>
	<td><a href="<?php echo esc_url( $step2_url ); ?>" title="<?php esc_html_e( 'Total Fare', 'simontaxi' ); ?>"><?php esc_html_e( 'Total Fare', 'simontaxi' ); ?></a></td>
	<?php } ?>
	<?php
	/**
	 * Let us display the fare for the user based on admin settings.
	 */

	if ( in_array( $show_fare, array( 'totalbasic', 'basicdetailsonly' ) ) && ! $is_hourly ) {
	$cols++;
	?>
	<td><?php esc_html_e( 'Basic Fare', 'simontaxi' ); ?></td>
	<?php } ?>
	</tr>
	<?php if ( ! empty( $vehicles) ) :
	/**
	 * @since 2.0.1
	 */									
	if ( ! isset( $_GET['vname'] ) ) {
		$vehicles_old = $vehicles;
		$vehicles = array();
		foreach ( $vehicles_old as $vehicle ) :
			$fare = simontaxi_get_fare( $vehicle, $booking_step1 );
			$vehicle->calculated_amount = $fare;
			$vehicles[] = $vehicle;
		endforeach;									
		
		$vehicles_sort = array();
		foreach ($vehicles as $key => $row ) {
			$vehicles_sort[ $row->ID ] = $row->calculated_amount;
		}
		if ( isset( $_GET['price'] ) && 'desc' === $_GET['price'] ) {
			array_multisort( $vehicles_sort, SORT_DESC, $vehicles );
		} else {
			array_multisort( $vehicles_sort, SORT_ASC, $vehicles );
		}
	}
	
	$thumb_w = '50';
	$thumb_h = '50';
	
	foreach ( $vehicles as $vehicle ) :
	$vehicle_id = $vehicle->ID;
	/**
	 * Let us find calculate the basic fare for the user selection.
	 */
	$fare = simontaxi_get_fare( $vehicle, $booking_step1 );																		
	?>
	<tr>
		<td>
			<input id="vehicle<?php echo esc_attr( $vehicle_id ); ?>" type="radio" name="selected_vehicle" value="<?php echo esc_attr( $vehicle_id); ?>" onClick="total_fare( '<?php echo esc_attr( $fare ); ?>' )">
			<label for="vehicle<?php echo esc_attr( $vehicle_id ); ?>"><span><span></span></span>
			</label>
		</td>
		<td>
			<?php
			if ( has_post_thumbnail( $vehicle_id ) ) {
				$thumb = get_post_thumbnail_id( $vehicle_id );
				$attachment_url = wp_get_attachment_url( $thumb, 'full' );
				
				$width = apply_filters( 'simontaxi_step2_vehicle_width', 200 );
				$height = apply_filters( 'simontaxi_step2_vehicle_height', 100 );
				
				$image = simontaxi_resize( $attachment_url, $width, $height, true );
				?>
				<img src="<?php echo esc_url( $image ); ?>" class="car-images" alt="<?php echo esc_attr( $vehicle->post_title ); ?>" title="<?php echo esc_attr( $vehicle->post_title ); ?>">
				<?php
			} else {
			$image = apply_filters('simontaxi_step2_vehicle_default', SIMONTAXI_PLUGIN_URL .  '/images/cabs-new.png');
			?>
			
			<img class="st-cab" src="<?php echo esc_url( $image ); ?>" class="car-images" alt="<?php echo esc_attr( $vehicle->post_title ); ?>" title="<?php echo esc_attr( $vehicle->post_title ); ?>">
				<!-- <div class="st-cab"></div> -->
			<?php
			}
			?>

		</td>
		<td>
			<a href="<?php echo esc_url(get_permalink( $vehicle_id ) ); ?>" target="_blank" class="vehicle_details" data-vehicle_id="<?php echo esc_attr( $vehicle_id ); ?>"><h4 class="vehicle_title"><?php echo esc_attr( $vehicle->post_title ); ?></h4></a>
			<?php if ( simontaxi_get_option( 'show_seating_capacity', 'yes' ) == 'yes' ) { ?>
			<p>
			<?php 
			$seating_capacity = get_post_meta( $vehicle_id, 'seating_capacity', true );
			if ( ! empty( $seating_capacity ) ) {
				echo $seating_capacity . ' ' . esc_html__( 'seats', 'simontaxi' );
			} else {
				echo esc_html__( 'NA', 'simontaxi' );
			}
			?>
			</p>
			<?php } ?>
			
			<?php
			$show_distance_away = simontaxi_get_option( 'show_distance_away', 'yes' );
			if ( 'yes' === $show_distance_away && $vehicle->distance_away > 0 ) { ?>
			<p>
			<?php echo $vehicle->distance_away . ' ' . simontaxi_get_option( 'vehicle_distance', 'km' ) . __( ' away', 'simontaxi' ); ?>
			</p>
			<?php } ?>
		</td>

		<?php
		if ( simontaxi_get_option( 'show_luggage_information', 'yes' ) == 'yes' ) { ?>
		<td>
		<?php
		// Luggage
		$luggage = get_post_meta( $vehicle_id, 'luggage', true );
		$luggage2 = get_post_meta( $vehicle_id, 'luggage2', true );
		
		// Luggage type
		$luggage_type = get_post_meta( $vehicle_id, 'luggage_type', true );
		$luggage2_type = get_post_meta( $vehicle_id, 'luggage2_type', true );
		
		// Luggage type symbol
		$luggage_type_symbol = get_post_meta( $vehicle_id, 'luggage_type_symbol', true );
		$luggage2_type_symbol = get_post_meta( $vehicle_id, 'luggage2_type_symbol', true );
		
		if ( ! empty( $luggage ) && ! empty( $luggage2 ) ) {
			
			echo '<p>';
			if ( ! empty( $luggage_type_symbol ) ) {
				echo $luggage . ' ' . $luggage_type_symbol;
			} elseif ( ! empty( $luggage_type ) ) {
				echo $luggage . ' ' . $luggage_type;
			} else {
				echo esc_html__( 'NA', 'simontaxi' );
			}
			echo ' + ';
			
			if ( ! empty( $luggage2_type_symbol ) ) {
				echo $luggage2 . ' ' . $luggage2_type_symbol;
			} elseif ( ! empty( $luggage2_type ) ) {
				echo $luggage2 . ' ' . $luggage2_type;
			} else {
				echo esc_html__( 'NA', 'simontaxi' );
			}
			echo '</p>';
		} elseif ( ! empty( $luggage ) ) {
		echo $luggage;
		?>										
		<i class="fa fa-suitcase st-fa-icon"><span>&nbsp;<?php
		if ( ! empty( $luggage_type_symbol ) ) {
			echo $luggage_type_symbol;
		} elseif ( ! empty( $luggage_type ) ) {
			echo $luggage_type;
		} else {
			echo esc_html__( 'NA', 'simontaxi' );
		}
		?></span></i>
		<?php } elseif ( ! empty( $luggage2 ) ) {
		echo $luggage2;
		?>										
		<i class="fa fa-suitcase st-fa-icon"><span>&nbsp;<?php
		if ( ! empty( $luggage2_type_symbol ) ) {
			echo $luggage2_type_symbol;
		} elseif ( ! empty( $luggage2_type ) ) {
			echo $luggage2_type;
		} else {
			echo esc_html__( 'NA', 'simontaxi' );
		}
		?></span></i>
		<?php } else {
			echo esc_html__( 'NA', 'simontaxi' );
		}?>
		</td>
		<?php } ?>

		<?php
		/**
		 * Let us display the fare for the user based on admin settings.
		 */
		if ( in_array( $show_fare, array( 'totalbasic', 'totalonly', 'totalwithminfare' ) ) ) { ?>
		<td>
		<h4 class="vehicle_fare"><?php
		$display_tax_rate = simontaxi_get_option( 'display_tax_rate', 'yes' );
		/**
		 * Bug Fixed.
		 * @since 2.0.1
		 */
		if ( in_array( $booking_step1['journey_type'], apply_filters( 'simontaxi_twoway_other_tabs_step2_display', array( 'two_way' ) ) ) ) {
			$fare = $fare * 2; //To avoid the confusion let us display whole basic fare for the user
		}
		if ( $display_tax_rate == 'yes' ) {
			$tax = simontaxi_get_tax( $fare );
			echo esc_html( simontaxi_get_currency( $fare + $tax ) );
		} else {
			echo ( $fare > 0) ? esc_html( simontaxi_get_currency( $fare ) ) : simontaxi_get_currency( '0' );
		}?></h4>
		<?php if ( $show_fare != 'totalonly' ) { ?>
		<p><?php
		/**
		* Display basic price for the user based on selection
		*/
		if (  ! $is_hourly ) {
		if ( $booking_step1['booking_type'] == 'p2p' ) {
			$p2p_basic_price = get_post_meta( $vehicle_id, 'p2p_basic_price', true );
			
			echo ( ! empty( $p2p_basic_price ) ) ? esc_attr( simontaxi_get_currency( $p2p_basic_price ) ) : esc_html__( 'NA', 'simontaxi' );
		} elseif ( $booking_step1['booking_type'] == 'airport' ) {
			if ( $booking_step1['airport'] == 'pickup_location' ) {
				$from_airport_basic_price = get_post_meta( $vehicle_id, 'from_airport_basic_price', true );
				echo ( ! empty( $from_airport_basic_price ) ) ? esc_attr( simontaxi_get_currency( $from_airport_basic_price ) ) : esc_html__( 'NA', 'simontaxi' );
			} else {
				$to_airport_basic_price = get_post_meta( $vehicle_id, 'to_airport_basic_price', true );
				
				echo ( ! empty( $to_airport_basic_price ) ) ? esc_attr( simontaxi_get_currency( $to_airport_basic_price ) ) : esc_html__( 'NA', 'simontaxi' );
			}
		}
		
		/**
		  * @since 2.0.8
		  */
		do_action( 'simontaxi_step2_basic_fare_display', $booking_step1, $vehicle_id );
		
		echo esc_html_e( ' (min charge)', 'simontaxi' );
		}
		?></p>
		<?php } ?>
		</td>
		<?php } ?>

		<?php
		/**
		 * Let us display the fare for the user based on admin settings.
		 */

		if ( in_array( $show_fare, array( 'totalbasic', 'basicdetailsonly' ) ) && ! $is_hourly ) { ?>
		<td>
		<?php
		/**
		* Display unit price for the user based on selection
		*/
		if ( $booking_step1['booking_type'] == 'p2p' ) {
			$p2p_unit_price = get_post_meta( $vehicle_id, 'p2p_unit_price', true );
			echo ( ! empty( $p2p_unit_price ) ) ? simontaxi_get_currency( $p2p_unit_price ) : esc_html__( 'NA', 'simontaxi' );
		} elseif ( $booking_step1['booking_type'] == 'airport' ) {
			if ( $booking_step1['airport'] == 'pickup_location' ) {
				$from_airport_unit_price = get_post_meta( $vehicle_id, 'from_airport_unit_price', true );
				
				echo ( ! empty( $from_airport_unit_price ) ) ? esc_attr( simontaxi_get_currency( $from_airport_unit_price ) ) : esc_html__( 'NA', 'simontaxi' );
			} else {
				$to_airport_unit_price = get_post_meta( $vehicle_id, 'to_airport_unit_price', true );
				
				echo ( ! empty( $to_airport_unit_price ) ) ? esc_attr( simontaxi_get_currency( $to_airport_unit_price ) ) : esc_html__( 'NA', 'simontaxi' );
			}
		}
		 
		 /**
		* Display Price per Standard Unit Distance
		*/
		if ( in_array( $booking_step1['booking_type'], array( 'p2p', 'airport' ) ) ) {
			echo esc_html__( ' / after', 'simontaxi' ) . ' ';
		}
		 if ( $booking_step1['booking_type'] == 'p2p' ) {
			 $p2p_basic_distance = get_post_meta( $vehicle_id, 'p2p_basic_distance', true );
			 
			 echo ( ! empty( $p2p_basic_distance ) ) ? esc_html( $p2p_basic_distance ) : esc_html__( 'NA', 'simontaxi' );
		 } elseif ( $booking_step1['booking_type'] == 'airport' ) {
			 if ( $booking_step1['airport'] == 'pickup_location' ) {
				 $from_airport_basic_distance = get_post_meta( $vehicle_id, 'from_airport_basic_distance', true );
				 
				 echo ( ! empty( $from_airport_basic_distance ) ) ? esc_html( $from_airport_basic_distance ) : esc_html__( 'NA', 'simontaxi' );
			 } else {
				 $to_airport_basic_distance = get_post_meta( $vehicle_id, 'to_airport_basic_distance', true );
				 
				 echo ( ! empty( $to_airport_basic_distance ) ) ? esc_html( $to_airport_basic_distance ) : esc_html__( 'NA', 'simontaxi' );
			 }
		 }
		 
		 /**
		  * @since 2.0.8
		  */
		 do_action( 'simontaxi_step2_total_fare_display', $booking_step1, $vehicle_id );
		 
		 echo  ' ' . simontaxi_get_option( 'vehicle_distance', 'km' );

		 ?>
		</td>
		<?php } ?>

	</tr>
	<?php endforeach; ?>
	<tr><td colspan="<?php echo esc_attr( $cols ); ?>" style="text-align:right">
	<?php
	$total = $vehicles_arr['total'];
	echo paginate_links(array(
	'base' => add_query_arg( 'cpage', '%#%' ),
	'format' => '',
	'prev_text' => esc_html__( '&laquo;', 'simontaxi' ),
	'next_text' => esc_html__( '&raquo;', 'simontaxi' ),
	'total' => ceil( $total / $args['perpage'] ),
	'current' => $page
	) );
	?>
	</td></tr>
	<?php else : ?>
	<tr><td colspan="<?php echo esc_attr( $cols ); ?>" style="text-align:center">
	<?php esc_html_e( 'No records found', 'simontaxi' ); ?>
	</td></tr>
	<?php endif; ?>

</table>