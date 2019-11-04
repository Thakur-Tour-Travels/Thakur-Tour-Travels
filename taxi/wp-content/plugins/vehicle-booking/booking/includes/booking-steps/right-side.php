<?php
/**
 * Display the page to view the booking details
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  Booking page right side part
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$amount_details = $personal_details = array();
if ( isset( $booking_step2 ) && isset( $booking_step2['selected_amount'] ) ) {
    $amount_details = simontaxi_get_fare_details();
}

if ( isset( $booking_step3 ) ) {
    $personal_details = $booking_step3;
}
$vehicle_distance = simontaxi_get_option( 'vehicle_distance', 'km' );
$date_format = simontaxi_get_option( 'st_date_format', 'd-m-Y' );
if ( ! empty( $booking_step1['booking_type'] ) ) {
$booking_type = $booking_step1['booking_type'];
//dd( $amount_details, false );
?>
<div class="col-md-4 col-sm-12">
    <div class="st-booking-summary-panel">
        <?php if ( 'yes' === simontaxi_get_option( 'sidebar_fare_details_top', 'yes' ) ) : ?>
		<?php
		ob_start();
		?>
		<div class="st-booking-summary-title">
        <?php esc_html_e( 'Booking Summary', 'simontaxi' ); ?>
        <?php if ( ! empty( $amount_details ) ) {
        echo '<br><span id="total_span_top">' . simontaxi_get_currency( $amount_details['amount_payable'] );
        esc_html_e( '( All inclusive )', 'simontaxi' );
		echo '</span>';
        } ?>
        </div>
		<?php
		echo apply_filters( 'simontaxi_sidebar_fare_details_top', ob_get_clean() );
		?>
		<?php endif; ?>

        <ul class="st-booking-summary-content">
            
			<?php do_action( 'simontaxi_right_side_top', $booking_step1 ); ?>
			<?php if ( isset( $booking_step1['pickup_date'] ) ) { ?>
            <?php if ( 'yes' === simontaxi_get_option( 'sidebar_start_over', 'yes' ) ) : ?>
			<li>
            <a href="<?php echo simontaxi_get_bookingsteps_urls( 'start_over' ); ?>" class="btn-active"><?php esc_html_e( 'Start Over', 'simontaxi' ); ?></a>
            </li>
			<?php endif; ?>
            <?php } ?>
            
			<?php if ( 'yes' === simontaxi_get_option( 'sidebar_booking_reference', 'yes' ) ) : ?>
			<li><span><?php esc_html_e( 'Booking Reference :', 'simontaxi' ); ?></span><span><?php echo esc_attr( $booking_step1['reference'] ); ?></span></li>
			<?php endif; ?>
			
            <?php if ( 'p2p' === $booking_type ) { ?>
			<?php
			ob_start();
			?>
            <li><span><?php esc_html_e( 'Journey Type :', 'simontaxi' ); ?></span><span><?php echo esc_attr( ucfirst( str_replace( '_', ' ', $booking_step1['journey_type'] ) ) ); ?></span></li>
			<?php
			echo apply_filters( 'simontaxi_sidebar_journey_type', ob_get_clean() );
			?>
            <?php } ?>

            <?php if ( 'yes' === simontaxi_get_option( 'sidebar_booking_type', 'yes' ) ) : ?>
			<?php
			ob_start();
			?>
			<li><span><?php esc_html_e( 'Booking Type :', 'simontaxi' ); ?></span><span>
			<?php echo esc_html( simontaxi_get_booking_type( $booking_step1['booking_type'] ) ); ?>
			</span></li>
			<?php
			echo apply_filters( 'simontaxi_sidebar_booking_type', ob_get_clean() );
			?>
			<?php endif; ?>
			
			<?php
			if ( 'yes' === simontaxi_get_option( 'sidebar_number_of_persons', 'no' ) && ! empty( $booking_step1['number_of_persons'] ) ) : ?>
			<li><span><?php esc_html_e( 'No. of persons :', 'simontaxi' ); ?></span><span><?php echo esc_html( $booking_step1['number_of_persons'] ); ?></span></li>
			<?php endif; ?>

            <?php if ( 'yes' === simontaxi_get_option( 'display_distance', 'no' ) && 'hourly' !== $booking_type ) { ?>
			<?php
			ob_start();
			?>
            <li><span><?php esc_html_e( 'Distance &amp; Time :', 'simontaxi' ); ?></span>
            <span>
            <?php
            if ( isset( $booking_step1['distance_text'] ) ) {
				echo esc_html( $booking_step1['distance_text'] );
			} else {
				echo esc_html__( 'NA', 'simontaxi' );
			}
			?>&nbsp; &amp; &nbsp;
			<?php
			if ( isset( $booking_step1['duration_text'] ) ) {
				echo esc_html( $booking_step1['duration_text'] );
			} else {
				echo esc_html__( 'NA', 'simontaxi' );
			}
			?>
            </span>
            </li>
			<?php
			$sidebar_distance_details = ob_get_clean();
			echo apply_filters( 'simontaxi_sidebar_distance_details', $sidebar_distance_details );
			?>
            <?php } ?>
			
			<?php if ( 'yes' === simontaxi_get_option( 'display_arrival_on', 'no' ) && 'hourly' !== $booking_type ) { ?>
			<?php
			ob_start();
			?>
            <li><span><?php esc_html_e( 'Arrival On :', 'simontaxi' ); ?></span>
            <span>
			<?php
			// dd( $booking_step1 );
			if ( ! empty( $booking_step1['duration_text'] ) ) {
				$arrival_on = $booking_step1['pickup_date'] . ' ' . $booking_step1['pickup_time'];
				
				//echo simontaxi_date_format( $booking_step1['duration_text'] );
				// echo $booking_step1['duration_text'] . '<br>';
				if ( ! empty( $booking_step1['duration_seconds'] ) ) {
					$duration_minutes = $booking_step1['duration_seconds'] / 60;
					echo simontaxi_arrival_on( $arrival_on, $duration_minutes );
				} else {
					echo simontaxi_arrival_on( $arrival_on, $booking_step1['duration_text'], true );
				}
			} else {
				echo esc_html__( 'NA', 'simontaxi' );
			}
			?>
            </span>
            </li>
			<?php
			$sidebar_arrival_on_details = ob_get_clean();
			echo apply_filters( 'simontaxi_sidebar_arrival_on_details', $sidebar_arrival_on_details );
			?>
            <?php } ?>

            <?php
			if ( ! empty( $amount_details) ) { ?>
            <?php if ( 'yes' === simontaxi_get_option( 'sidebar_vehicle_details', 'yes' ) ) : ?>
			<?php
			ob_start();
			?>
			<li><span><?php echo sprintf( esc_html__( '%s Details :', 'simontaxi' ), simontaxi_get_label_singular() ); ?></span>
            <span>
			<?php if ( 'yes' === simontaxi_get_option( 'sidebar_vehicle_details_vehicle', 'yes' ) ) : 
			$vehicle_id = $booking_step2['vehicle_details']->ID;
			?>
				<?php echo simontaxi_get_label_singular(); ?> : <p><a href="<?php echo esc_url(get_permalink( $vehicle_id ) ); ?>" target="_blank" class="vehicle_details" data-vehicle_id="<?php echo esc_attr( $vehicle_id ); ?>"><?php echo esc_attr( $booking_step2['vehicle_details']->post_title); ?></a></p>
				<?php do_action( 'simontaxi_vehicle_other_details_front', $booking_step2 ); ?>
			<?php endif; ?>
			<br>
            <?php
            if ( 'basicfare' === simontaxi_get_option( 'farecalculation_basedon', 'basicfare' ) ) {
                $vehicle_details = $booking_step2['vehicle_details'];
				if ( ! empty( $vehicle_details ) ) {
					if ( 'p2p' === $booking_step1['booking_type'] ) {
						if ( 'yes' === simontaxi_get_option( 'sidebar_vehicle_details_basic_distance', 'yes' ) && ! empty( $vehicle_details->p2p_basic_distance ) ) {
							echo esc_html__( 'Basic Distance : ', 'simontaxi' ) . $vehicle_details->p2p_basic_distance . ' ' . $vehicle_distance . '<br>';
						}
						if ( 'yes' === simontaxi_get_option( 'sidebar_vehicle_details_basic_price', 'yes' ) && ! empty( $vehicle_details->p2p_basic_price ) ) {
							echo esc_html__( 'Basic Price : ', 'simontaxi' ) . simontaxi_get_currency( $vehicle_details->p2p_basic_price) . '<br>';
						}
						if ( 'yes' === simontaxi_get_option( 'sidebar_vehicle_details_basic_unit_price', 'yes' ) && ! empty( $vehicle_details->p2p_unit_price ) ) {
							echo esc_html__( 'Basic Unit Price : ', 'simontaxi' ) . simontaxi_get_currency( $vehicle_details->p2p_unit_price);
						}
					} elseif ( ( in_array( $booking_type, apply_filters( 'simontaxi_airport_other_tabs', array( 'airport' ) ) ) ) ) {
						// print_r(apply_filters( 'simontaxi_airport_other_tabs', array( 'airport' ) ));
						if ( 'pickup_location' === $booking_step1['airport'] ) {
							if ( 'yes' === simontaxi_get_option( 'sidebar_vehicle_details_basic_distance', 'yes' ) && ! empty( $vehicle_details->from_airport_basic_distance ) ) {
								echo esc_html__( 'Basic Distance : ', 'simontaxi' ) . $vehicle_details->from_airport_basic_distance . '<br>';
							}
							if ( 'yes' === simontaxi_get_option( 'sidebar_vehicle_details_basic_price', 'yes' ) && ! empty( $vehicle_details->from_airport_basic_price ) ) {
								echo esc_html__( 'Basic Price : ', 'simontaxi' ) . simontaxi_get_currency( $vehicle_details->from_airport_basic_price) . '<br>';
							}
							if ( 'yes' === simontaxi_get_option( 'sidebar_vehicle_details_basic_unit_price', 'yes' ) && ! empty( $vehicle_details->from_airport_unit_price ) ) {
								echo esc_html__( 'Basic Unit Price : ', 'simontaxi' ) . simontaxi_get_currency( $vehicle_details->from_airport_unit_price);
							}
						} else {
							
							if ( 'yes' === simontaxi_get_option( 'sidebar_vehicle_details_basic_distance', 'yes' ) && ! empty( $vehicle_details->to_airport_basic_distance ) ) {
								echo esc_html__( 'Basic Distance : ', 'simontaxi' ) . $vehicle_details->to_airport_basic_distance . '<br>';
							}
							if ( 'yes' === simontaxi_get_option( 'sidebar_vehicle_details_basic_price', 'yes' ) && ! empty( $vehicle_details->to_airport_basic_price ) ) {
								echo esc_html__( 'Basic Price : ', 'simontaxi' ) . simontaxi_get_currency( $vehicle_details->to_airport_basic_price) . '<br>';
							}
							if ( 'yes' === simontaxi_get_option( 'sidebar_vehicle_details_basic_unit_price', 'yes' ) && ! empty( $vehicle_details->to_airport_unit_price ) ) {
								echo esc_html__( 'Basic Unit Price : ', 'simontaxi' ) . simontaxi_get_currency( $vehicle_details->to_airport_unit_price);
							}
						}
					}
					do_action( 'simontaxi_sidebar_basic_fare_details', $booking_step1, $vehicle_details );
				}
                do_action( 'simontaxi_vehicle_details' );
            ?>
            <?php } ?></span>
        </li>
		<?php
		$sidebar_vehicle_details = ob_get_clean();
		echo apply_filters( 'simontaxi_sidebar_vehicle_details', $sidebar_vehicle_details );
		?>
		<?php endif; ?>
        <?php } ?>

		<?php
		ob_start();
		?>
		<li><span><?php
		if ( in_array( $booking_step1['journey_type'], apply_filters( 'simontaxi_twoway_other_tabs_step1', array( 'two_way' ) ) ) ) {
			esc_html_e( 'Onward Journey : ', 'simontaxi' );
		} else {
			if ( 'hourly' === $booking_type ) {
				esc_html_e( 'Journey Details', 'simontaxi' );
			} else {
			esc_html_e( 'One Way Journey', 'simontaxi' );
			}
		} ?></span><a href="<?php echo simontaxi_get_bookingsteps_urls( 'step1' ); ?>"><span class="st-edit-btn fa fa-pencil-square-o"></span></a></li>
		<?php
		echo apply_filters( 'simontaxi_sidebar_journey_type_oneway_heading', ob_get_clean() );
		?>

		<?php if ( 'hourly' === $booking_type ) { ?>
		<li><span><?php esc_html_e( 'Package :', 'simontaxi' ); ?></span><span><?php echo esc_attr( $booking_step1['hourly_package'] ); ?></span></li>
		<?php } ?>
			
			<?php
			ob_start();
			?>
            <li><span><?php
            if ( 'hourly' === $booking_type ) {
                echo simontaxi_get_pickuppoint_title() . esc_html__( ' :', 'simontaxi' );
            } else {
            esc_html_e( 'From :', 'simontaxi' );
            }
            ?></span><span>
			<?php			
			if ( ( in_array( $booking_type, apply_filters( 'simontaxi_airport_other_tabs', array( 'airport' ) ) ) ) && ! empty ( $booking_step1['airport'] ) && ( 'pickup_location' === $booking_step1['airport'] ) ) {
				// $pickup_location = $booking_step1['pickup_location'];
				/**
				 * @since 2.0.2
				 */
				$pickup_location = $booking_step1['pickup_location_new'];
				if ( empty( $pickup_location ) ) {
					$pickup_location = $booking_step1['pickup_location'];
				}
				$details = get_term( $pickup_location, 'vehicle_locations' );
				$name = $details->name;
				$term_meta = get_term_meta( $pickup_location );
				$location_address = ( ! empty( $term_meta['location_address'] ) ) ? $term_meta['location_address'][0] : '';
				$name_value = ( '' !== $location_address ) ? $location_address : $name;
				echo esc_attr( $name_value );
			} else {
				if ( ctype_digit( $booking_step1['pickup_location'] ) ) {
					$details = get_term( $booking_step1['pickup_location'], 'vehicle_locations' );
					if ( $details ) {
						echo $details->name;
					} else {
						echo '-';
					}
				} else {
					echo esc_attr( $booking_step1['pickup_location'] );
				}
			}
			?></span></li>
			<?php
			$sidebar_pickup_location = ob_get_clean();
			echo apply_filters( 'simontaxi_sidebar_pickup_location', $sidebar_pickup_location );
			?>
			
			<?php
			ob_start();
			?>
            <?php if ( 'hourly' !== $booking_type ) { ?>
            <li><span><?php esc_html_e( 'To :', 'simontaxi' ); ?></span><span>
			<?php
			if ( ( in_array( $booking_type, apply_filters( 'simontaxi_airport_other_tabs', array( 'airport' ) ) ) ) && ! empty ( $booking_step1['airport'] ) && ( 'drop_location' === $booking_step1['airport'] ) ) {
				// $drop_location = $booking_step1['drop_location'];
				/**
				 * @since 2.0.2
				 */
				$drop_location = $booking_step1['drop_location_new'];
				if ( empty( $drop_location ) ) {
					$drop_location = $booking_step1['drop_location'];
				}
				$details = get_term( $drop_location, 'vehicle_locations' );
				$name = $details->name;
				$term_meta = get_term_meta( $drop_location );
				$location_address = ( ! empty( $term_meta['location_address'] ) ) ? $term_meta['location_address'][0] : '';
				$name_value = ( '' !== $location_address ) ? $location_address : $name;
				echo esc_attr( $name_value );
			} else {
				if ( ctype_digit( $booking_step1['drop_location'] ) ) {
					$details = get_term( $booking_step1['drop_location'], 'vehicle_locations' );
					if ( $details ) {
						echo $details->name;
					} else {
						echo '-';
					}
				} else {
					echo esc_attr( $booking_step1['drop_location'] );
				}
			}
			?></span></li>
			<?php
			$sidebar_droplocation = ob_get_clean();
			echo apply_filters( 'simontaxi_sidebar_droplocation', $sidebar_droplocation );
			?>
            <?php } ?>

			<?php
			ob_start();
			?>
            <li><span><?php esc_html_e( 'Pickup Date :', 'simontaxi' ); ?></span><span><?php echo esc_attr( simontaxi_date_format( $booking_step1['pickup_date'] ) ); ?></span></li>
            <li><span><?php esc_html_e( 'Pickup Time :', 'simontaxi' ); ?></span><span><?php echo esc_attr( simontaxi_get_time_display_format( $booking_step1['pickup_time'] ) ); ?></span></li>
			<?php
			$pickup_date_li = ob_get_clean();
			echo apply_filters( 'simontaxi_pickup_date_li', $pickup_date_li );
			?>


            <?php if ( ! empty( $amount_details ) ) { ?>
            <?php if ( 'yes' === simontaxi_get_option( 'sidebar_fare_details', 'yes' ) ) : ?>
			<?php
			ob_start();
			?>
			<li><span><?php esc_html_e( 'Fare Details :', 'simontaxi' ); ?></span><li>
                <?php
				
				$sidebar_fare_details_basic_amount = simontaxi_get_option( 'sidebar_fare_details_basic_amount', 'yes' );
				$sidebar_fare_details_total_amount = simontaxi_get_option( 'sidebar_fare_details_total_amount', 'yes' );
				$sidebar_fare_details_surcharges = simontaxi_get_option( 'sidebar_fare_details_surcharges', 'yes' );
				$sidebar_fare_details_tax_amount = simontaxi_get_option( 'sidebar_fare_details_tax_amount', 'yes' );
				$sidebar_fare_details_discount = simontaxi_get_option( 'sidebar_fare_details_discount', 'yes' );
				
				if ( 'yes' === $sidebar_fare_details_basic_amount 
				|| 'yes' === $sidebar_fare_details_total_amount 
				|| 'yes' === $sidebar_fare_details_surcharges 
				|| 'yes' === $sidebar_fare_details_tax_amount 
				|| 'yes' === $sidebar_fare_details_discount ) :
				?>
			<li class="simon-price-list">
                	<ul>
				<?php
				if ( 'yes' === $sidebar_fare_details_basic_amount ) {
					ob_start();
					echo '<li>';
					echo simontaxi_get_basic_amount_title();
					?>
					<span>
					<?php echo esc_attr( simontaxi_get_currency( $amount_details['basic_amount'] ) ); ?>
					</span>
					<?php echo '</li>';
					echo apply_filters( 'simontaxi_sidebar_basic_amount', ob_get_clean() );
				}
				?>
                <?php
                if ( $amount_details['tax_amount_onward'] > 0 && 'yes' === $sidebar_fare_details_tax_amount ) {
                    ob_start();
					echo '<li>';
                    esc_html_e( 'Tax amount : ', 'simontaxi' );
                    echo '<span>' . esc_attr( simontaxi_get_currency( $amount_details['tax_amount_onward'] ) ) . '</span>';
					echo '</li>';
					echo apply_filters( 'simontaxi_sidebar_tax_details', ob_get_clean() );
                }
                ?>
                <?php
                if ( $amount_details['discount_amount'] > 0 && 'yes' === $sidebar_fare_details_discount ) {
                    ob_start();
					echo '<li>';
                    esc_html_e( 'Discount : ', 'simontaxi' );
                    echo '<span>'.esc_attr( simontaxi_get_currency( $amount_details['discount_amount'] ) ) . '</span>';
					echo '</li>';
					echo apply_filters( 'simontaxi_sidebar_discount_details', ob_get_clean() );
                }
                ?>
                
                <?php
                if ( $amount_details['surcharges_amount_onward'] > 0 && 'yes' === $sidebar_fare_details_surcharges ) {
                    
						//echo '<ul class="simon-price-list-sub">';
						if ( ! empty( $amount_details['surcharges']['waitingtime_surcharge_onward'] ) && $amount_details['surcharges']['waitingtime_surcharge_onward'] > 0) {
							echo '<li>';
							echo esc_html__( 'Waiting time : ', 'simontaxi' ) .'<span>' . simontaxi_get_currency( $amount_details['surcharges']['waitingtime_surcharge_onward'] ) . '</span>';
							echo '</li>';
						}
						if ( ! empty( $amount_details['surcharges']['additionalpoints_surcharge_onward'] ) && $amount_details['surcharges']['additionalpoints_surcharge_onward'] > 0 ) {
							echo '<li>';
							echo simontaxi_get_additional_pickup_address_price_title() . '<span>' . simontaxi_get_currency( $amount_details['surcharges']['additionalpoints_surcharge_onward'] ) . '</span>';
							echo '</li>';
						}
						if ( ! empty( $amount_details['surcharges']['airport_surcharge'] ) && $amount_details['surcharges']['airport_surcharge'] > 0) {
							echo '<li>';
							echo esc_html__( 'Airport : ', 'simontaxi' ) . simontaxi_get_currency( $amount_details['surcharges']['airport_surcharge'] );
							echo '</li>';
						}
						if ( ! empty( $amount_details['surcharges']['peak_time_surcharge_onward'] ) &&  $amount_details['surcharges']['peak_time_surcharge_onward'] > 0) {
							echo '<li>';
							echo esc_html__( 'Peak time : ', 'simontaxi' ) . '<span>' . simontaxi_get_currency( $amount_details['surcharges']['peak_time_surcharge_onward'] ) . '</span>';
							echo '</li>';
						}
						if ( ! empty( $amount_details['surcharges']['peak_season_surcharge_onward'] ) && $amount_details['surcharges']['peak_season_surcharge_onward'] > 0 ) {
							echo '<li>';
							echo esc_html__( 'Peak Season : ', 'simontaxi' ) . '<span>' . simontaxi_get_currency( $amount_details['surcharges']['peak_season_surcharge_onward'] ) . '</span>';
							echo '</li>';
						}
						
						do_action( 'simontaxi_additional_surcharges_display', 
							array( 
								'amount_details' => $amount_details, 
								'booking_step1' => $booking_step1,
							)
						);
						// echo '</ul>';
					echo '</li>';
                }
				if ( 'yes' === $sidebar_fare_details_total_amount ) {
					ob_start();
					echo '<li>';
					esc_html_e( 'Total amount : ', 'simontaxi' );
					echo '<span>';
					echo esc_attr( simontaxi_get_currency( $amount_details['amount_payable_onward'] ) );
					echo '</span>';
					echo '</li>';
					echo apply_filters( 'simontaxi_sidebar_amount_payable_onward', ob_get_clean() );
					
				}
                ?>
            	</ul>
            </li>
			
                
                
				
				<?php endif; ?>
            <?php
			$sidebar_fare_details = ob_get_clean();
			echo apply_filters( 'simontaxi_sidebar_fare_details', $sidebar_fare_details );
			?>
			<?php endif; ?>
            <?php } ?>

            <?php if ( in_array( $booking_step1['journey_type'], apply_filters( 'simontaxi_twoway_other_tabs_step1', array( 'two_way' ) ) ) ) { ?>
			<?php
			ob_start();
			?>
            <li><span><?php esc_html_e( 'Return Journey', 'simontaxi' ); ?></span><a href="<?php echo simontaxi_get_bookingsteps_urls( 'step1' ); ?>"><span class="st-edit-btn fa fa-pencil-square-o"></span></a></li>
            <li><span><?php esc_html_e( 'From :', 'simontaxi' ); ?></span><span><?php echo esc_attr( $booking_step1['drop_location'] ); ?></span></li>
            <li><span><?php esc_html_e( 'To :', 'simontaxi' ); ?></span><span><?php echo esc_attr( $booking_step1['pickup_location'] ); ?></span></li>
            <li><span><?php esc_html_e( 'Return Pickup Date :', 'simontaxi' ); ?></span><span><?php echo esc_attr( simontaxi_date_format( $booking_step1['pickup_date_return'] ) ); ?></span></li>
            <li><span><?php esc_html_e( 'Return Pickup Time :', 'simontaxi' ); ?></span><span><?php echo esc_attr( simontaxi_get_time_display_format( $booking_step1['pickup_time_return'] ) ); ?></span></li>
			<?php
			echo apply_filters( 'simontaxi_sidebar_twoway_details', ob_get_clean() );
			?>
            <?php
			if ( ! empty( $amount_details) ) {
            ?>
            <?php if ( 'yes' === simontaxi_get_option( 'sidebar_fare_details', 'yes' ) ) : ?>
			<?php
			ob_start();
			?>
			<li><span><?php esc_html_e( 'Fare Details :', 'simontaxi' ); ?></span></li>
                <?php
				if ( 'yes' === $sidebar_fare_details_basic_amount 
				|| 'yes' === $sidebar_fare_details_total_amount 
				|| 'yes' === $sidebar_fare_details_surcharges 
				|| 'yes' === $sidebar_fare_details_tax_amount 
				|| 'yes' === $sidebar_fare_details_discount ) :
				?>
				<li class="simon-price-list">
					<ul>
					<?php
					if ( 'yes' === $sidebar_fare_details_basic_amount ) {
						echo '<li>';
						echo simontaxi_get_basic_amount_title();
						echo '<span>';
						echo esc_attr( simontaxi_get_currency( $amount_details['basic_amount'] ) );
						echo '</span>';
						echo '</li>';
					}
					if ( $amount_details['tax_amount'] > 0 && 'yes' === $sidebar_fare_details_tax_amount ) {
						echo '<li>';
						esc_html_e( 'Tax amount : ', 'simontaxi' );
						echo '<span>';
						echo esc_attr( simontaxi_get_currency( $amount_details['tax_amount_return'] ) );
						echo '</span>';
						echo '</li>';
					}
					?>
					<?php
					
					if ( $amount_details['surcharges_amount_return'] > 0 && 'yes' === $sidebar_fare_details_discount ) {
						/*
						echo '<br>';
						esc_html_e( 'Surcharges : ', 'simontaxi' );
						echo esc_attr( simontaxi_get_currency( $amount_details['surcharges_amount_return'] ) );
						*/
						
						if ( $amount_details['surcharges']['waitingtime_surcharge_return'] > 0) {
							echo '<li>';
							echo esc_html__( 'Waiting time : ', 'simontaxi' );
							echo '<span>';
							echo simontaxi_get_currency( $amount_details['surcharges']['waitingtime_surcharge_return'] );
							echo '</span>';
							echo '</li>';
						}
						if ( $amount_details['surcharges']['additionalpoints_surcharge_return'] > 0) {
							echo '<li>';
							echo esc_html__( 'Additional points : ', 'simontaxi' );
							echo '<span>';
							echo simontaxi_get_currency( $amount_details['surcharges']['additionalpoints_surcharge_return'] );
							echo '</span>';
							echo '</li>';
						}
						if ( $amount_details['surcharges']['airport_surcharge'] > 0 ) {
							echo '<li>';
							echo  esc_html__( 'Airport : ', 'simontaxi' );
							echo '<span>';
							echo simontaxi_get_currency( $amount_details['surcharges']['airport_surcharge'] );
							echo '</span>';
							echo '</li>';
						}
						if ( $amount_details['surcharges']['peak_time_surcharge_return'] > 0 ) {
							echo '<li>';
							echo esc_html__( 'Peak time : ', 'simontaxi' );
							echo '<span>';
							echo simontaxi_get_currency( $amount_details['surcharges']['peak_time_surcharge_return'] );
							echo '</span>';
							echo '</li>';
						}
						if ( $amount_details['surcharges']['peak_season_surcharge_return'] > 0 ) {
							echo '<li>';
							echo esc_html__( 'Peak Season : ', 'simontaxi' );
							echo '<span>';
							echo simontaxi_get_currency( $amount_details['surcharges']['peak_season_surcharge_return'] );
							echo '</span>';
							echo '</li>';
						}
						
						do_action( 'simontaxi_additional_surcharges_display_return', 
							array( 
								'amount_details' => $amount_details, 
								'booking_step1' => $booking_step1,
							)
						);
						
					}
					?>
					<?php
					if ( 'yes' === $sidebar_fare_details_total_amount ) {
						echo '<li>';
						esc_html_e( 'Total amount : ', 'simontaxi' );
						echo '<span>';
						echo esc_attr( simontaxi_get_currency( $amount_details['amount_payable_return'] ) );
						echo '</span>';
						echo '</li>';
					}
					?>
					</ul>
                </li>
				<?php endif; ?>
            </li>
			<?php
			$sidebar_fare_details_return = ob_get_clean();
			echo apply_filters( 'simontaxi_sidebar_fare_details_return', $sidebar_fare_details_return );
			?>
			<?php endif; ?>
            <?php } ?>
            <?php } ?>


            <?php
			if ( ! empty( $personal_details) && 'yes' === simontaxi_get_option( 'sidebar_personal_details', 'yes' ) ) { ?>
			<?php
			ob_start();
			?>
            <li><span><?php esc_html_e( 'Personal Details :', 'simontaxi' )?></span>
            <span>
            <?php echo esc_attr( $personal_details['email'] ); ?>
            <?php
            if ( isset( $personal_details['full_name'] ) )
                echo '<br>'.esc_attr( $personal_details['full_name'] );
            ?>
            <?php
            if ( isset( $personal_details['mobile_countrycode'] ) && isset( $personal_details['mobile'] ) ) {
				$parts = explode( '_', $personal_details['mobile_countrycode'] );
				$mobile = isset( $parts[0] ) ? $parts[0] : '';
				if ( $mobile != '' ) {
					echo '<br>' . esc_attr( $mobile ) . ' - ';
				}
				echo esc_attr( $personal_details['mobile'] );
            }
            ?>
            <?php
            if ( isset( $personal_details['no_of_passengers'] ) )
                echo '<br>'.esc_html__( 'Passengers : ', 'simontaxi' ) . esc_attr( $personal_details['no_of_passengers'] );
            ?>

            </span>
            </li>
			<?php
			$sidebar_personal_details = ob_get_clean();
			$sidebar_personal_details = apply_filters( 'simontaxi_sidebar_personal_details', $sidebar_personal_details );
			echo $sidebar_personal_details;
			?>
            <?php } ?>
			
			<?php do_action( 'simontaxi_right_side_bottom', $booking_step1 ); ?>
			
            <?php if ( ! empty( $amount_details ) && 'yes' === simontaxi_get_option( 'sidebar_fare_details_bottom', 'yes' ) ) { ?>
			<?php
			ob_start();
			?>
            <li class="st-booking-summary"><span style="display: block;"><?php esc_html_e( 'Total Fare : ', 'simontaxi' ); ?></span> <span> <i class="st-booking-price" id="total_span_bottom"><?php echo simontaxi_get_currency( $amount_details['amount_payable'] ); ?> </i> <br><span  class="st-small-text"><?php esc_html_e( '( All inclusive )', 'simontaxi' )?></span></span></li>
			<?php
			echo apply_filters( 'simontaxi_sidebar_fare_details_bottom', ob_get_clean() );
			?>
            <?php } ?>
        </ul>
    </div>
</div>
<?php } ?>
