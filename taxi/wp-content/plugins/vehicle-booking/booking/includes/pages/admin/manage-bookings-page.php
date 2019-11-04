<script>
function printContent(el){
	var restorepage = document.body.innerHTML;
	var printcontent = document.getElementById(el).innerHTML;
	document.body.innerHTML = printcontent;
	window.print();
	document.body.innerHTML = restorepage;
}
</script>
<style type="text/css">
	table{font-family: arial; width: 100%; }
	table.booking-status-update {background: white;border:1px solid #e6e6e6;padding:10px;}
	table.booking-status-update th{text-align: left;padding:10px;padding-left: 0px; vertical-align: text-top;}
	table.booking-status-update td{ vertical-align: text-top;}

	.small-gray{color:gray;font-size:11px;}
	.status-count{font-size: 11px;background:transparent;color:white;font-weight:bold;padding:0px 7px;border-radius:100%;display: inline-block;border:1px solid white;}
	.bg-happygreen{background:#5cc550;}
	.bg-cancel{background:gray;}
	.bg-success{background:#44923b;}
	.bg-warning{background:#eab107;}
	.bg-danger{background:#ce4141;}
	.bg-sky{background:#00a0d2;}
	.bg-purple{background:#b35e89;}
	.font-white{color:white !important;min-width:100px;display: inline-block;text-indent:3px;border-radius: 2px;font-family: arial;font-size:13px;}

</style>
<?php

	$displaying_status = (isset( $_REQUEST['status']) ? $_REQUEST['status'] : 'new' );

	if(isset( $_GET['view_status']))
	{
		global $wpdb;
		$bookings = $wpdb->prefix . 'st_bookings';
		$payments = $wpdb->prefix . 'st_payments';

		$booking_id = $_GET['view_status'];

		$sql = "SELECT *, `" . $bookings . "`.`ID` AS booking_id, `" . $bookings . "`.`reference` AS booking_ref, `" . $bookings . "`.`vehicle_no` FROM `" . $bookings . "` INNER JOIN `" . $payments . "` ON `" . $payments . "`.`booking_id`=`" . $bookings . "`.`ID` WHERE `" . $bookings . "`.booking_contacts!='' AND `" . $bookings . "`.ID=" . $booking_id;

		$result = $wpdb->get_results( $sql );
		if ( ! empty( $result ) ) {
			$booking =( array ) $result[0]; 
			$session_details = json_decode( $booking['session_details'] );
			if ( is_object( $session_details ) ) {
				  $session_details = (array) $session_details;
			  }
			?>
			<div class="wrap">
				<div id="icon-users" class="icon32"></div>
				<a style="float:right;" href="<?php echo admin_url( 'admin.php?page=manage_bookings' ); ?>"><?php esc_html_e( 'Back to bookings', 'simontaxi' ); ?></a>
					<h3><?php esc_html_e( 'Booking Details', 'simontaxi' ); ?> <button onclick="printContent( 'booking-div' )"><?php esc_html_e( 'Print', 'simontaxi' ); ?></button></h3>
					<div id="booking-div">

						<table class="booking-status-update">
							<tbody>
							<tr>
								<th><?php esc_html_e( 'Reference : ', 'simontaxi' ); ?></th><td><?php echo $booking['booking_ref']; ?></td>
							</tr>
							<tr>
								<th ><?php esc_html_e( 'Journey Type : ', 'simontaxi' ); ?></th><td><?php echo strtoupper( str_replace( '_', ' ', $booking['journey_type'])) ; ?></td>
							</tr>
							<tr>
								<th ><?php esc_html_e( 'Booking Type : ', 'simontaxi' ); ?></th><td><?php echo esc_html( simontaxi_get_booking_type( $booking['booking_type'] ) ); ?></td></tr>


							<?php if( $booking['booking_type'] == 'hourly' ) {?>
							<tr><th><?php esc_html_e( 'Booking', 'simontaxi' ); ?></th><td>
								<table>
								<tr><td width='20%'><?php esc_html_e( 'Package Type : ', 'simontaxi' ); ?></td><td><?php echo $booking['hourly_package']; ?></td></tr>
								<tr><td width='20%'><?php esc_html_e( 'Pickup Location : ', 'simontaxi' ); ?></td><td><?php echo simontaxi_get_address( $booking, 'pickup_location' ); ?></td></tr>
								<tr><td width='20%'><?php esc_html_e( 'Pickup Date : ', 'simontaxi' ); ?> </td><td><?php echo simontaxi_date_format( $booking['pickup_date'] ) . ' ' . simontaxi_get_time_display_format( $booking['pickup_time'] ); ?></td></tr>

								</table>
								<?php
								do_action( 'simontaxi_other_booking_details', array(
									'session_details' => $session_details,
								) );
								?>
								</td></tr>
							<?php } else { ?>

							<tr>
								<th><?php esc_html_e( 'Booking', 'simontaxi' ); ?></th><td>
								<table>
								<tr><td width='20%'><?php esc_html_e( 'From : ', 'simontaxi' ); ?></td><td><?php echo simontaxi_get_address( $booking, 'pickup_location' ); ?></td></tr>
								<tr><td width='20%'><?php esc_html_e( 'To : ', 'simontaxi' ); ?></td><td><?php echo simontaxi_get_address( $booking, 'drop_location' ); ?></td></tr>
								<tr><td width='20%'><?php esc_html_e( 'Pickup Date : ', 'simontaxi' ); ?> </td><td><?php echo simontaxi_date_format( $booking['pickup_date'] ) . ' ' . simontaxi_get_time_display_format( $booking['pickup_time'] ); ?></td></tr>
								</table>
								<?php if( in_array( $booking['journey_type'], apply_filters( 'simontaxi_twoway_other_tabs_step1', array( 'two_way' ) ) ) ) {?>
								<b><?php esc_html_e( 'Return journey', 'simontaxi' ); ?></b>
								<table>
								<tr><td width='20%'><?php esc_html_e( 'From : ', 'simontaxi' ); ?></td><td><?php echo simontaxi_get_address( $booking, 'drop_location' ); ?></td></tr>
								<tr><td width='20%'><?php esc_html_e( 'To : ', 'simontaxi' ); ?></td><td><?php echo simontaxi_get_address( $booking, 'pickup_location' ); ?></td></tr>
								<tr><td width='20%'><?php esc_html_e( 'Pickup Date : ', 'simontaxi' ); ?> </td><td><?php echo simontaxi_date_format( $booking['pickup_date_return'] ) . ' ' . simontaxi_get_time_display_format( $booking['pickup_time_return'] ); ?></td></tr>
								</table>
								<?php } ?>
								<?php
								do_action( 'simontaxi_other_booking_details', array(
									'session_details' => $session_details,
								) );
								?>
								</td>
							</tr>
							<?php } ?>
							
														
							<?php $contact = (array)json_decode( $booking['booking_contacts']); ?>
							<tr>
								<th><?php esc_html_e( 'Contacts', 'simontaxi' ); ?></th><td>
								<table>
								<tr><td width='20%'><?php esc_html_e( 'Name : ', 'simontaxi' ); ?> </td><td>
								<?php if ( ! empty( $contact['full_name'] ) ) { ?>
								<?php echo $contact['full_name']; ?>
								<?php } elseif ( ! empty( $contact['first_name'] ) ) {
									echo $contact['first_name'];
									if ( ! empty( $contact['last_name'] ) ) {
										echo ' ' . $contact['last_name'];
									}
								} ?>
								</td></tr>
								
								<?php if ( ! empty( $contact['mobile'] ) ) { ?>
								<tr><td width='20%'><?php esc_html_e( 'Mobile : ', 'simontaxi' ); ?></td><td>
								<?php
								$mobile_countrycode = '';
								if ( ! empty( $contact['mobile_countrycode'] ) ) {
									$parts = explode( '_', $contact['mobile_countrycode'] );
									if ( ! empty( $parts[0] ) ) {
										$mobile_countrycode = '+' . $parts[0] . ' ';
									}
								}
								echo $mobile_countrycode . $contact['mobile']; ?></td></tr>
								<?php } ?>
								<tr><td width='20%'><?php esc_html_e( 'Email : ', 'simontaxi' ); ?></td><td><?php echo $contact['email']; ?></td></tr>
								<?php
								if ( ! empty( $contact['company_name'] ) ) { ?>
								<tr><td width='20%'><?php esc_html_e( 'Company : ', 'simontaxi' ); ?></td><td><?php echo $contact['company_name']; ?></td></tr>
								<?php
								}
								if ( ! empty( $contact['land_mark_pickupaddress'] ) ) { ?>
								<tr><td width='20%'><?php esc_html_e( 'Landmark / Pickup address : ', 'simontaxi' ); ?></td><td><?php echo $contact['land_mark_pickupaddress']; ?></td></tr>
								<?php
								}
								if ( ! empty( $contact['special_instructions'] ) ) { ?>
								<tr><td width='20%'><?php esc_html_e( 'Special Instructions : ', 'simontaxi' ); ?></td><td><?php echo $contact['special_instructions']; ?></td></tr>
								<?php
								}
								
								$persons = '';
																
								if ( ! empty( $session_details ) ) {
									
									$step1 = isset( $session_details[0] ) ? $session_details[0] : array();
									if ( empty( $step1 ) ) {
										$step1 = isset( $session_details['step1'] ) ? $session_details['step1'] : array();
									}
									/*
									if ( ! empty( $step1->number_of_persons ) ) { ?>
									<tr><td width='20%'><?php esc_html_e( 'No. of passengers : ', 'simontaxi' ); ?></td><td><?php echo $step1->number_of_persons; ?></td></tr>
									<?php
									}
									*/
									
									$step3 = isset( $session_details[2] ) ? $session_details[2] : array();
									if ( empty( $step3 ) ) {
										$step3 = isset( $session_details['step3'] ) ? $session_details['step3'] : array();
									}
									if ( ! empty( $step3->additional_pickup_address ) ) { ?>
									<tr><td width='20%'><?php echo simontaxi_get_additional_pickup_address_title(); ?></td>
									<td>
									<?php 
									$additional_pickup_address = $step3->additional_pickup_address;
									if ( ! empty( $additional_pickup_address ) ) {
										?>
										<table border="">
											<?php
											foreach( $additional_pickup_address as $key => $addr ) {
												?>
												<tr><td><?php echo $key; ?></td><td><?php echo $addr; ?></td></tr>
												<?php
											}
											?>
										</table>
										<?php
									} ?></td></tr>
									<?php
									}
									
									if ( ! empty( $step3->additional_dropoff_address ) ) { ?>
									<tr><td width='20%'><?php echo simontaxi_get_additional_dropoff_address_title(); ?></td>
									<td>
									<?php 
									$additional_dropoff_address = $step3->additional_dropoff_address;
									if ( ! empty( $additional_dropoff_address ) ) {
										?>
										<table border="">
											<?php
											foreach( $additional_dropoff_address as $key => $addr ) {
												?>
												<tr><td><?php echo $key; ?></td><td><?php echo $addr; ?></td></tr>
												<?php
											}
											?>
										</table>
										<?php
									} ?></td></tr>
									<?php
									}
								}					
								if ( $persons == '' && ! empty( $contact['no_of_passengers'] ) ) { ?>
								<tr><td width='20%'><?php esc_html_e( 'No. of passengers : ', 'simontaxi' ); ?></td><td><?php echo $contact['no_of_passengers']; ?></td></tr>
								<?php
								}								
								if ( ! empty( $session_details ) ) {
									$step1 = isset( $session_details[0] ) ? $session_details[0] : array();
									if ( empty( $step1 ) ) {
										$step1 = isset( $session_details['step1'] ) ? $session_details['step1'] : array();
									}
									if ( ! empty( $step1->flight_no ) ) { ?>
									<tr><td width='20%'><?php esc_html_e( 'Flight No. : ', 'simontaxi' ); ?></td><td><?php echo $step1->flight_no; ?></td></tr>
									<?php
									}
									if ( ! empty( $step1->flight_arrival_time ) ) { ?>
									<tr><td width='20%'><?php esc_html_e( 'Arrival Time : ', 'simontaxi' ); ?></td><td><?php echo $step1->flight_arrival_time; ?></td></tr>
									<?php
									}
								}								
								?>
								</table>
								</td>
							</tr>
							
							<?php
							/**
							 * @since 2.0.0
							*/
							?>
							<tr>
								<th><?php esc_html_e( 'Payment Details', 'simontaxi' ); ?></th><td>
								<table>
								
								<tr><td width='20%'><?php esc_html_e( 'Amount Payable : ', 'simontaxi' ); ?> </td><td>								
								<?php echo simontaxi_get_currency( apply_filters( 'simontaxi_amount_payable', $booking['amount_payable'], $booking ) ); ?></td></tr>
								
								<tr><td width='20%'><?php esc_html_e( 'Amount Paid : ', 'simontaxi' ); ?></td><td>
								<?php echo simontaxi_get_currency( apply_filters( 'simontaxi_amount_paid', $booking['amount_paid'], $booking ) ) ; ?></td></tr>
								
								<tr><td width='20%'><?php esc_html_e( 'Gateway : ', 'simontaxi' ); ?></td><td><?php echo ucfirst( $booking['payment_method'] ); ?></td></tr>
								
								<tr><td width='20%'><?php esc_html_e( 'Reference : ', 'simontaxi' ); ?></td><td><?php echo ( $booking['transaction_reference'] ) ? $booking['transaction_reference'] : $booking['reference']; ?></td></tr>
								
								<tr><td width='20%'><?php esc_html_e( 'Status : ', 'simontaxi' ); ?></td><td><?php echo ucfirst( $booking['payment_status'] ); ?></td></tr>
								<?php do_action( 'simontaxi_other_payment_details' ); ?>
								
								</table>
								</td>
							</tr>
							
							<?php
							/**
							 * @since 2.0.0
							*/
							$vehicle_details = array();
							$session_details = json_decode( $booking['session_details'] );
							if ( is_object( $session_details ) ) {
								  $session_details = (array) $session_details;
							  }
							if ( ! empty( $session_details ) ) {
								
								foreach ( $session_details as $session ) {
									
									if ( isset( $session->vehicle_details ) ) {
										
										$vehicle_details = $session->vehicle_details;
										$vehicle_details->booking_id = $booking['booking_id'];
										if ( ! empty( $booking['driver_id'] ) ) {
											$vehicle_details->driver_id = $booking['driver_id'];
										} else {
											$vehicle_details->driver_id = 0;
										}
									}
								}
							}

							if ( ! empty( $vehicle_details ) ) {
							?>
							<tr>
								<th><?php esc_html_e( 'Vehicle Details', 'simontaxi' ); ?></th><td>
								<table>
								
								<?php if( ! empty( $vehicle_details->post_title ) ) { ?>
								<tr><td width='20%'><?php esc_html_e( 'Vehicle : ', 'simontaxi' ); ?> </td><td> 
								<?php if ( ! simontaxi_is_user( 'administrator' ) && ! simontaxi_is_user( 'executive' ) ) { ?>
								<a href="<?php echo esc_url(get_permalink( $vehicle_details->ID ) ); ?>" target="_blank" class="vehicle_details" data-vehicle_id="<?php echo esc_attr( $vehicle_details->ID ); ?>" data-booking_id="<?php echo esc_attr( $booking['booking_id'] ); ?>"><?php echo esc_html( $vehicle_details->post_title ); ?></a>
								<?php									
								} else { ?>
								<a href="<?php echo esc_url( $vehicle_details->guid );?>" target="_blank" class="vehicle_details" data-vehicle_id="<?php echo esc_attr( $vehicle_details->ID ); ?>" data-booking_id="<?php echo esc_attr( $booking['booking_id'] ); ?>"><?php echo esc_html( $vehicle_details->post_title ); ?></a>
								<?php } ?>
								</td></tr>
								<?php } ?>
								
								<?php 
								//print_r( $booking );
								if( ! empty( $booking['vehicle_no'] ) ) { ?>
								<tr><td width='20%'><?php esc_html_e( 'Vehicle no / Car plate : ', 'simontaxi' ); ?> </td>
								<td><?php echo esc_html( $booking['vehicle_no'] ); ?></td>
								</tr>
								<?php } ?>
								
								</table>
								</td>
							</tr>
							<?php } ?>
							
							
							<?php do_action( 'simontaxi_vehicle_other_details', $vehicle_details ); ?>
														
							<tr>
								<th><?php esc_html_e( 'Current Status', 'simontaxi' ); ?></th><td>
								<table>
								<tr><td width='20%'><?php esc_html_e( 'Status : ', 'simontaxi' ); ?></td><td><?php echo strtoupper( $booking['status']); ?></td></tr>
								<tr><td width='20%'><?php esc_html_e( 'Time : ', 'simontaxi' ); ?></td><td><?php echo simontaxi_date_format( $booking['status_updated'], true ); ?></td></tr>
								</table>
								</td>
							</tr>
							<?php do_action( 'simontaxi_booking_other_details', $booking, 'view_status' ); ?>
							</tbody>
						</table>
						<h3><?php esc_html_e( 'Change Status', 'simontaxi' ); ?></h3>
						<?php
						$status_change = '';
						if( $booking['status']=='new' ) {
							$status_change = '<a href="' . admin_url( 'admin.php?page=manage_bookings&change_status=confirmed&booking_id=' . $booking['booking_id']) . '">' .  esc_html__( 'Confirm', 'simontaxi' ) . '</a> | <a href="' .admin_url( 'admin.php?page=manage_bookings&change_status=cancelled&booking_id=' . $booking['booking_id']) . '">' .  esc_html__( 'Cancel', 'simontaxi' ) . '</a>';
						}
				    	else if( $booking['status']=='confirmed' ) {
				    		$status_change = '<a href="' .admin_url( 'admin.php?page=manage_bookings&change_status=onride&booking_id=' . $booking['booking_id']) . '">' .  esc_html__( 'Start Ride', 'simontaxi' ) . '</a> | <a href="' .admin_url( 'admin.php?page=manage_bookings&change_status=cancelled&booking_id=' . $booking['booking_id']) . '">' .  esc_html__( 'Cancel', 'simontaxi' ) . '</a>';
						}
				    	elseif( $booking['status']=='onride' ) {
				    		/*
							$status_change = '<a href="' .admin_url( 'admin.php?page=manage_bookings&change_status=success&booking_id=' . $booking['booking_id']) . '">' .  esc_html__( 'Completed', 'simontaxi' ) . '</a> | <a href="' .admin_url( 'admin.php?page=manage_bookings&change_status=cancelled&booking_id=' . $booking['booking_id']) . '">' .  esc_html__( 'Cancel', 'simontaxi' ) . '</a>';
							*/
							$status_change = '<a href="' .admin_url( 'admin.php?page=manage_bookings&change_status=success&booking_id=' . $booking['booking_id']) . '">' .  esc_html__( 'Completed', 'simontaxi' ) . '</a>';
						}
				    	else {
							$status_change = '<span class="small-gray">' .  esc_html__( 'NO ACTIONS', 'simontaxi' ) . '</span>';
						}
				    	// $status_change_other = do_action( 'simontaxi_booking_status_links', $booking );
						$status_change_other = apply_filters( 'simontaxi_status_links_table', $status_change, $booking );
						if ( ! empty( $status_change_other ) ) {
							$status_change = $status_change_other;
						}
						$status_change = $status_change_other;
						echo $status_change;
						?>
						<a style="float:right;" href="<?php echo admin_url( 'admin.php?page=manage_bookings' ); ?>"><?php esc_html_e( 'Back to bookings', 'simontaxi' ); ?></a>
					</div>

				</div>
			</div>
<?php
		}
	}
	elseif( isset( $_GET['change_status'] ) ) {
		global $wpdb;
		$bookings = $wpdb->prefix . 'st_bookings';
		$payments = $wpdb->prefix . 'st_payments';

		$new_status = $_GET['change_status'];
		if ( 'reset_counts' === $new_status ) {
			$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'simontaxi_bookings_%'" );
			$redirect = admin_url( 'admin.php?page=manage_bookings&status=new' );
			wp_redirect( $redirect );
			exit;
		}
		$booking_id = $_GET['booking_id'];

		$sql = "SELECT *, `" . $bookings . "`.`ID` AS booking_id, `" . $bookings . "`.`reference` AS booking_ref FROM `" . $bookings . "` INNER JOIN `" . $payments . "` ON `" . $payments . "`.`booking_id`=`" . $bookings . "`.`ID` WHERE `" . $bookings . "`.booking_contacts!='' AND `" . $bookings . "`.ID=" . $booking_id;

		$result = $wpdb->get_results( $sql);
		if ( !empty( $result) ) {
			$booking=(array)$result[0];
			
		?>
			<div class="wrap">
				<div id="icon-users" class="icon32"></div>
					<h3><?php esc_html_e( 'Editing :', 'simontaxi' ); ?><b><?php esc_html_e(strtoupper( $new_status), 'simontaxi' ); ?></b></h3>
					
					
					<div class="row" style="padding-left:16px;">
					<h3><?php esc_html_e( 'Select to edit :', 'simontaxi' ); ?></h3>
					<ul class="subsubsub">
					<?php
					$admin_update_buttons = simontaxi_admin_update_buttons();
					foreach( $admin_update_buttons as $key => $button ) {
						$active = '';
						if ( $key === $new_status ) {
							$active = ' active';
						}
						if ( $key == 'change' && in_array( $new_status, array_keys( simontaxi_booking_statuses() ) ) ) {
							$active = ' active';
						}
						echo '<li class="' . $key . $active . '"><a class="" href="' . $button['url'] . '&booking_id=' . $booking_id . '">' . $button['title'] . '</a> |</li>';
					}
					?>
					</ul>
					</div>

					<div class="row">
						
						<table class="booking-status-update">
							<tr><td colspan="4"><h4 style="margin-bottom: 0px;"><?php esc_html_e( 'Booking Details : ', 'simontaxi' ); ?></h4></td></tr>
							<tr>
								<th><?php esc_html_e( 'Reference', 'simontaxi' ); ?></th>
								<th><?php esc_html_e( 'Booking', 'simontaxi' ); ?></th>
								<th><?php esc_html_e( 'Contacts', 'simontaxi' ); ?></th>
								<th><?php esc_html_e( 'Current Status', 'simontaxi' ); ?></th>
							</tr>
							<tbody>
							<tr>
								<td><?php echo $booking['booking_id'] . '#' . $booking['booking_ref']?></td>
								<td>
								<?php 
								echo simontaxi_get_address( $booking, 'pickup_location' ) . '-' . simontaxi_get_address( $booking, 'drop_location' ) . '<br>' . simontaxi_date_format( $booking['pickup_date'] ) . ' ' . simontaxi_get_time_display_format( $booking['pickup_time'] );
								$vehicle_id = $booking['selected_vehicle'];
								?>
								<a href="<?php echo esc_url(get_permalink( $vehicle_id ) ); ?>" target="_blank" class="vehicle_details" data-vehicle_id="<?php echo esc_attr( $vehicle_id ); ?>" data-booking_id="<?php echo esc_attr( $booking['booking_id'] ); ?>"><?php echo esc_html( get_the_title( $vehicle_id ) ); ?></a>
								<?php
								$session_details = json_decode( $booking['session_details'] );
								if ( is_object( $session_details ) ) {
									$session_details = (array) $session_details;
								}
								  if ( ! empty( $session_details ) ) {
										$step1 = isset( $session_details[0] ) ? $session_details[0] : array();
										if ( empty( $step1 ) ) {
											$step1 = isset( $session_details['step1'] ) ? $session_details['step1'] : array();
										}
										if ( ! empty( $step1->distance_text ) ) {
											echo '<br><span class="small-gray">Distance: ' . $step1->distance_text . '</span>';
										}
										
										foreach ( $session_details as $session ) {
											if ( isset( $session->vehicle_details ) ) {
												$vehicle_details = $session->vehicle_details;
												$vehicle_details->booking_id = $booking['booking_id'];
												if ( ! empty( $booking['driver_id'] ) ) {
													$vehicle_details->driver_id = $booking['driver_id'];
												} else {
													$vehicle_details->driver_id = 0;
												}
											}
										}
									}
								?>
								<?php 
								if ( ! empty( $vehicle_details ) ) {
									do_action( 'simontaxi_vehicle_other_details_display',  $vehicle_details );
								}
								?>
								</td>
								<td><?php $contact = (array)json_decode( $booking['booking_contacts']);
											if ( isset( $contact['full_name']) ) {
												echo esc_attr( $contact['full_name']) . '<br>';
											}elseif ( isset( $contact['first_name']) ) {
												echo $contact['first_name'];
												if ( isset( $contact['last_name']) && $contact['last_name'] != '' ) {
													echo ' ' . $contact['last_name'];
												}
											}

											if ( isset( $contact['mobile']) ) {
												$mobile_countrycode = '';
												if ( ! empty( $contact['mobile_countrycode'] ) ) {
													$parts = explode( '_', $contact['mobile_countrycode'] );
													if ( ! empty( $parts[0] ) ) {
														$mobile_countrycode = '+' . $parts[0] . ' ';
													}
												}
												echo esc_attr( $mobile_countrycode . $contact['mobile']) . '<br>';
											}
											echo $contact['email'];
											
											/**
											 * @since 2.0.6
											 */
											$str = '';
											if ( ! empty( $contact['company_name'] ) ) {
												$str .= '<br><span class="small-gray">Company: ' . $contact['company_name'] . '</span>';
											}
											if ( ! empty( $contact['land_mark_pickupaddress'] ) ) {
												$str .= '<br><span class="small-gray">Landmark / Pickup address: ' . $contact['land_mark_pickupaddress'] . '</span>';
											}
											if ( ! empty( $contact['special_instructions'] ) ) {
												$str .= '<br><span class="small-gray">Special Instructions: ' . $contact['special_instructions'] . '</span>';
											}
											$persons = '';
											$session_details = json_decode( $booking['session_details'] );
											if ( is_object( $session_details ) ) {
												$session_details = (array) $session_details;
											}
											if ( ! empty( $session_details ) ) {
												$step1 = isset( $session_details[0] ) ? $session_details[0] : array();
												if ( empty( $step1 ) ) {
													$step1 = isset( $session_details['step1'] ) ? $session_details['step1'] : array();
												}
												if ( ! empty( $step1->number_of_persons ) ) {
													$persons .= '<br><span class="small-gray">No. of passengers: ' . $step1->number_of_persons . '</span>';
												}
											}					
											if ( $persons == '' && ! empty( $contact['no_of_passengers'] ) ) {
												$persons .= '<br><span class="small-gray">No. of passengers: ' . $contact['no_of_passengers'] . '</span>';
											}
											
											if ( ! empty( $session_details ) ) {
												$step1 = isset( $session_details[0] ) ? $session_details[0] : array();
												if ( empty( $step1 ) ) {
													$step1 = isset( $session_details['step1'] ) ? $session_details['step1'] : array();
												}
												
												if ( ! empty( $step1->flight_no ) ) {
													$persons .= '<br><span class="small-gray">Flight No.: ' . $step1->flight_no . '</span>';
												}
												if ( ! empty( $step1->flight_arrival_time ) ) {
													$persons .= '<br><span class="small-gray">Arrival Time: ' . $step1->flight_arrival_time . '</span>';
												}
											}
											echo $str .= $persons;
											$session_details_temp = $booking['session_details'];
											// unset( $booking['session_details'] ); /* No need this information so kill it!!*/
											?></td>
								<td><?php echo esc_html__(strtoupper( $booking['status']), 'simontaxi' ) . '<br>' . simontaxi_date_format( $booking['status_updated'] , true ); ?></td>
							</tr>
							</tbody>
						</table>
					</div>
					
					<?php
					
					if ( 'change' === $new_status || in_array( $new_status, array_keys( simontaxi_booking_statuses() ) ) ) {
						$template = 'booking/includes/pages/admin/manage-bookings-page-update.php';
						if ( simontaxi_is_template_customized( $template ) ) {
							include_once simontaxi_get_theme_template_dir_name() . $template;
						} else {
							include_once apply_filters( 'simontaxi_locate_manage_bookings_page_update', SIMONTAXI_PLUGIN_PATH . $template );
						}
					}
					?>
					
					<?php
					if ( 'vehicle' === $new_status ) {
						$template = 'booking/includes/pages/admin/manage-bookings-page-vehicle.php';
						if ( simontaxi_is_template_customized( $template ) ) {
							include_once simontaxi_get_theme_template_dir_name() . $template;
						} else {
							include_once apply_filters( 'simontaxi_locate_manage_bookings_page_vehicle', SIMONTAXI_PLUGIN_PATH . $template );
						}
					}
					?>
					
					<?php
					if ( 'location' === $new_status ) {
						$template = 'booking/includes/pages/admin/manage-bookings-page-step1.php';
						if ( simontaxi_is_template_customized( $template ) ) {
							include_once simontaxi_get_theme_template_dir_name() . $template;
						} else {
							include_once apply_filters( 'simontaxi_locate_manage_bookings_page_step1', SIMONTAXI_PLUGIN_PATH . $template );
						}
					}
					
					?>
					
					<?php
					if ( 'payment' === $new_status ) {
						$template = 'booking/includes/pages/admin/manage-bookings-page-payment.php';
						if ( simontaxi_is_template_customized( $template ) ) {
							include_once simontaxi_get_theme_template_dir_name() . $template;
						} else {
							include_once apply_filters( 'simontaxi_locate_manage_bookings_page_payment', SIMONTAXI_PLUGIN_PATH . $template );
						}
					}
					?>
					
					<?php
					if ( 'personal' === $new_status ) {
						$template = 'booking/includes/pages/admin/manage-bookings-page-personal.php';
						if ( simontaxi_is_template_customized( $template ) ) {
							include_once simontaxi_get_theme_template_dir_name() . $template;
						} else {
							include_once apply_filters( 'simontaxi_locate_manage_bookings_page_personal', SIMONTAXI_PLUGIN_PATH . $template );
						}
					}
					?>
					
					<?php do_action( 'simontaxi_booking_other_details_outside_form', $booking, 'change_status' ); ?>
					<?php
						do_action('simontaxi_manage_booking_additional_outside_form',
							array( 
								'booking' => $booking,
							)
						);						
					?>
				</div>
			</div>
			
			<script type="text/javascript">
				function validate()
				{
					var payment_status = document.getElementById('payment_status').value;
					var content = document.getElementById('content').value;
					<?php if ( 'new' === $booking['status'] && 'pending' == $booking['payment_status'] ) { ?>
					if ( payment_status == '' ) {
						alert( '<?php esc_html_e( 'Please select payment status' ); ?>' );
						return false;
					}
					/*
					if ( content == '' ) {
						alert( '<?php esc_html_e( 'Please enter message' ); ?>' );
						return false;
					}
					*/
					<?php } ?>
				}
				jQuery(document).ready(function ( $ ) {
					$( '.st_datepicker_limit' ).datepicker({
						dateFormat: 'dd-mm-yy'
					});
				});
			</script>
<?php
		}
	}
	else
	{	
	echo '<div class="wrap">
			<div id="icon-users" class="icon32"></div>
			<h2>' . esc_html__( 'Bookings', 'simontaxi' ) . ' <span style="color:gray">' . strtoupper( $displaying_status ) . '</span></h2>';
				$bookings = new Bookings_List();
				$bookings->views();
				$bookings->prepare_items();
				$bookings->search_box( esc_html__( 'Search', 'simontaxi' ), 'simontaxi' );
				$bookings->display();
	echo '	</div>
		  </div>';
}