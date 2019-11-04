<?php
/**
 * Add admin menu for vehicle settings
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  settings
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tab = 'general';
if ( isset ( $_GET['tab'] ) ) {
	$tab = $_GET['tab'];
}
$section = $payment_form = '';
if ( isset ( $_GET['section'] ) ) {
	$section = $_GET['section'];
}
$url = admin_url( 'edit.php?post_type=vehicle&page=vehicle_settings' );

if ( 'permissions' === $tab && 'update_db_fields' === $section ) {
	$updated = simontaxi_update_db_fields();
	$msg = esc_html__( 'Database fields updated successfully.' );
	if ( empty( $updated ) ) {
		$msg = esc_html__( 'There are no changes in Database fields.' );
	}
	simontaxi_set_message( 'success', $msg );
	$redirect_to = $url . '&tab=permissions';
	wp_safe_redirect( $redirect_to );
	die();
}
?>
<?php $fixed_point_vehicle_name = simontaxi_get_option( 'fixed_point_vehicle_name', 'Flight' ); ?>
<?php $fixed_point_title = simontaxi_get_option( 'fixed_point_title', 'Airport' ); ?>

    <div class="wrap" >
                <!-- action="options.php"   method="post" -->
            <?php echo simontaxi_print_errors() ?>
			<form id="options">
                <h3><?php esc_html_e( 'Global Settings', 'simontaxi' ); ?></h3>
                <input type="hidden" name="action" value="insert_settings" />
                <?php wp_nonce_field( 'update-options' );
				
				?>

				<!-- TabPills Navigation -->
				<ul class="nav nav-pills st-booking-pills nav-justified">
					<li <?php if( '' === $tab || $tab === 'general' ) { echo 'class="active"'; } ?>><a href="<?php echo esc_url( $url ) . '&tab=general'; ?>"><?php esc_html_e( 'General', 'simontaxi' ); ?></a></li>
					<li <?php if( $tab === 'optional' ) { echo 'class="active"'; } ?>><a href="<?php echo esc_url( $url ) . '&tab=optional'; ?>" ><?php esc_html_e( 'Optional Fields', 'simontaxi' ); ?></a></li>
					<li <?php if( $tab === 'currency' ) { ?> class="active" <?php } ?>><a href="<?php echo esc_url( $url ) . '&tab=currency'; ?>"><?php esc_html_e( 'Currency', 'simontaxi' ); ?></a></li>
					<li <?php if( $tab === 'tabstitles' ) { echo 'class="active"'; } ?>><a href="<?php echo esc_url( $url ) . '&tab=tabstitles'; ?>"><?php esc_html_e( 'Tabs & Titles', 'simontaxi' ); ?></a></li>
					<li <?php if( $tab === 'paymentgateways' ) { echo 'class="active"'; } ?>><a href="<?php echo esc_url( $url ) . '&tab=paymentgateways'; ?>" ><?php esc_html_e( 'Payment Gateways', 'simontaxi' ); ?></a></li>
					<li <?php if( $tab === 'taxsettings' ) { echo 'class="active"'; } ?>><a href="<?php echo esc_url( $url ) . '&tab=taxsettings'; ?>" ><?php esc_html_e( 'GST / Tax / Discount Settings', 'simontaxi' ); ?></a></li>
					<li <?php if( $tab === 'surcharges' ) { echo 'class="active"'; } ?>><a href="<?php echo esc_url( $url ) . '&tab=surcharges'; ?>" ><?php esc_html_e( 'Additional Charges', 'simontaxi' ); ?></a></li>
					<li <?php if( $tab === 'emailsettings' ) { echo 'class="active"'; } ?>><a href="<?php echo esc_url( $url ) . '&tab=emailsettings'; ?>" ><?php
					if ( simontaxi_is_sms_gateway_active() ) {
						esc_html_e( 'Email & SMS Settings', 'simontaxi' );
					} else {
						esc_html_e( 'Email Settings', 'simontaxi' );
					}
					?></a></li>

					<li <?php if( $tab === 'billingsettings' ) { echo 'class="active"'; } ?>><a href="<?php echo esc_url( $url ) . '&tab=billingsettings'; ?>" ><?php esc_html_e( 'Billing', 'simontaxi' ); ?></a></li>
					<li <?php if( $tab === 'permissions' ) { echo 'class="active"'; } ?>><a href="<?php echo esc_url( $url ) . '&tab=permissions'; ?>"><?php esc_html_e( 'Permissions', 'simontaxi' ); ?></a></li>
					<?php do_action( 'simontaxi_settings_tab' ); ?>
				</ul>
				<!-- end TabPills Navigation -->

				<div class="tab-content nav-pills-content">
					<!-- TAB-1 -->
					<div id="st-general" class="tab-pane fade in <?php if ( $tab == 'general' ) echo 'active'; ?>" style="display:<?php if( '' === $tab || $tab === 'general' ) { echo 'block'; } else { echo 'none'; } ?>">
						<?php						
						$template = 'booking/includes/pages/admin/settings-general.php';
						if ( simontaxi_is_template_customized( $template ) ) {
							require simontaxi_get_theme_template_dir_name() . $template;
						} else {
							require apply_filters( 'simontaxi_locate_settings_general', SIMONTAXI_PLUGIN_PATH . $template );
						}						
						?>
					</div>

					<!-- Optional Fields -->
					<div id="st-optional" class="tab-pane fade in" style="display:<?php if( $tab === 'optional' ) { echo 'block'; } else { echo 'none'; } ?>">
						<table class="st-table">
						<tbody>
						<tr valign="top"><td><h4><?php esc_html_e( 'Booking Step1', 'simontaxi' )?></h4></td><th>&nbsp;</th></tr>

						<?php $booking_summany_step1 = simontaxi_get_option( 'booking_summany_step1', 'yes' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="booking_summany_step1"><?php esc_html_e( 'Show Booking Summary?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="booking_summany_step1" name="simontaxi_settings[booking_summany_step1]" title="<?php esc_html_e( 'Show Booking Summary?', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="yes" <?php if ( isset ( $booking_summany_step1) && $booking_summany_step1 == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if ( isset ( $booking_summany_step1) && $booking_summany_step1 == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						<?php
						$step1_sidebar_position = simontaxi_get_option( 'step1_sidebar_position', 'right' );
						?>
						<tr valign="top">
							<th class="titledesc" scope="row">
								<label for="step1_sidebar_position"><?php esc_html_e( 'Step-1 Sidebar Position', 'simontaxi' ); ?></label>
							</th>
							<td>
								<select id="step1_sidebar_position" name="simontaxi_settings[step1_sidebar_position]" title="<?php esc_html_e( 'Step-1 Sidebar Position', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="right" <?php if ( isset ( $step1_sidebar_position) && $step1_sidebar_position == 'right' ) { echo 'selected'; }?>><?php esc_html_e( 'Right', 'simontaxi' ); ?></option>
									<option value="left" <?php if ( isset ( $step1_sidebar_position) && $step1_sidebar_position == 'left' ) { echo 'selected'; }?>><?php esc_html_e( 'Left', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Step-1 Sidebar Position' ); ?>
							</td>
						</tr>
						
						<?php
						/*
						$booking_type_tabs_position = simontaxi_get_option( 'booking_type_tabs_position', 'inside' );
						?>
						<tr valign="top">
							<th class="titledesc" scope="row">
								<label for="booking_type_tabs_position"><?php esc_html_e( 'Tabs Position', 'simontaxi' ); ?></label>
							</th>
							<td>
								<select id="booking_type_tabs_position" name="simontaxi_settings[booking_type_tabs_position]" title="<?php esc_html_e( 'Tabs Position', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="outside" <?php if ( $booking_type_tabs_position == 'outside' ) { echo 'selected'; }?>><?php esc_html_e( 'Outside Box', 'simontaxi' ); ?></option>
									<option value="inside" <?php if (  $booking_type_tabs_position == 'inside' ) { echo 'selected'; }?>><?php esc_html_e( 'Inside Box', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Tabs Position' ); ?>
							</td>
						</tr>
						<?php */ ?>
						
						<?php
						$default_breadcrumb_display_step1 = simontaxi_get_option( 'default_breadcrumb_display_step1', 'yes' );
						?>
						<tr valign="top">
							<th class="titledesc" scope="row">
								<label for="default_breadcrumb_display_step1"><?php esc_html_e( 'Default Breadcrumb', 'simontaxi' ); ?></label>
							</th>
							<td>
								<select id="default_breadcrumb_display_step1" name="simontaxi_settings[default_breadcrumb_display_step1]" title="<?php esc_html_e( 'Default Breadcrumb', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="yes" <?php if ( $default_breadcrumb_display_step1 == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if (  $default_breadcrumb_display_step1 == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Default Breadcrumb' ); ?>
							</td>
						</tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="allow_additional_pickups"><?php esc_html_e( 'Allow additional pickup points', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="allow_additional_pickups" name="simontaxi_settings[allow_additional_pickups]" title="<?php esc_html_e( 'Allow additional pickup points', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="no" <?php if ( isset ( $allow_additional_pickups) && $allow_additional_pickups == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
									<option value="yes" <?php if ( isset ( $allow_additional_pickups) && $allow_additional_pickups == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
								</select>&nbsp;
								<input type="text" id="max_additional_pickups" value="<?php if ( isset ( $max_additional_pickups)) { echo $max_additional_pickups; } else { echo 5;}?>" name="simontaxi_settings[max_additional_pickups]" title="<?php esc_html_e( 'Max. Additional Pickup Points', 'simontaxi' ); ?>" style="width: 25em;"><?php echo simontaxi_get_help( 'Max. Additional Pickup Points if allow additional pickup points "yes".' ); ?>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="allow_additional_dropoff"><?php esc_html_e( 'Allow additional drop-off points', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="allow_additional_dropoff" name="simontaxi_settings[allow_additional_dropoff]" title="Terms Page" style="width: 25em;">
									<option value="no" <?php if ( isset ( $allow_additional_dropoff) && $allow_additional_dropoff == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
									<option value="yes" <?php if ( isset ( $allow_additional_dropoff) && $allow_additional_dropoff == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
								</select>&nbsp;
								<input type="text" id="max_additional_dropoff" value="<?php if ( isset ( $max_additional_dropoff)) { echo $max_additional_dropoff; } else { echo 5;}?>" name="simontaxi_settings[max_additional_dropoff]" title="<?php esc_html_e( 'Max. Additional Drop-off Points', 'simontaxi' ); ?>" style="width: 25em;"><?php echo simontaxi_get_help( 'Max. Additional Drop-off Points if allow additional drop-off points "yes".' ); ?>
                            </td>
                        </tr>

						<!-- Return Journey-->
						<?php
						$allow_additional_pickups_return = simontaxi_get_option( 'allow_additional_pickups_return', 'no' );
						$max_additional_pickups_return = simontaxi_get_option( 'max_additional_pickups_return', '5' );
						?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="allow_additional_pickups_return"><?php esc_html_e( 'Allow additional pickup points (Return)', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="allow_additional_pickups_return" name="simontaxi_settings[allow_additional_pickups_return]" title="<?php esc_html_e( 'Allow additional pickup points', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="no" <?php if ( isset ( $allow_additional_pickups_return) && $allow_additional_pickups_return == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
									<option value="yes" <?php if ( isset ( $allow_additional_pickups_return) && $allow_additional_pickups_return == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
								</select>&nbsp;
								<input type="text" id="max_additional_pickups_return" value="<?php if ( isset ( $max_additional_pickups_return)) { echo $max_additional_pickups_return; } else { echo 5;}?>" name="simontaxi_settings[max_additional_pickups_return]" title="<?php esc_html_e( 'Max. Additional Pickup Points', 'simontaxi' ); ?>" style="width: 25em;"><?php echo simontaxi_get_help( 'Max. Additional Pickup Points if allow additional pickup points "yes".' ); ?>
                            </td>
                        </tr>

						<?php
						$allow_additional_dropoff_return = simontaxi_get_option( 'allow_additional_dropoff_return', 'no' );
						$max_additional_dropoff_return = simontaxi_get_option( 'max_additional_dropoff_return', '5' );
						?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="allow_additional_dropoff_return"><?php esc_html_e( 'Allow additional drop-off points (Return)', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="allow_additional_dropoff_return" name="simontaxi_settings[allow_additional_dropoff_return]" title="Terms Page" style="width: 25em;">
									<option value="no" <?php if ( isset ( $allow_additional_dropoff_return) && $allow_additional_dropoff_return == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
									<option value="yes" <?php if ( isset ( $allow_additional_dropoff_return) && $allow_additional_dropoff_return == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
								</select>&nbsp;
								<input type="text" id="max_additional_dropoff_return" value="<?php if ( isset ( $max_additional_dropoff_return)) { echo $max_additional_dropoff_return; } else { echo 5;}?>" name="simontaxi_settings[max_additional_dropoff_return]" title="<?php esc_html_e( 'Max. Additional Dropoff Points', 'simontaxi' ); ?>" style="width: 25em;"><?php echo simontaxi_get_help( 'Max. Additional Drop-off Points if allow additional drop-off points "yes".' ); ?>
                            </td>
                        </tr>
						<!-- ENd -->

						<?php $allow_waiting_time = simontaxi_get_option( 'allow_waiting_time', 'no' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="allow_waiting_time"><?php esc_html_e( 'Allow waiting time', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="allow_waiting_time" name="simontaxi_settings[allow_waiting_time]" title="<?php esc_html_e( 'Allow waiting time', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="no" <?php if ( isset ( $allow_waiting_time) && $allow_waiting_time == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
									<option value="yes" <?php if ( isset ( $allow_waiting_time) && $allow_waiting_time == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of waiting time in front end.' ); ?>
                            </td>
                        </tr>

						<?php $allow_waiting_time_return = simontaxi_get_option( 'allow_waiting_time_return', 'no' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="allow_waiting_time_return"><?php esc_html_e( 'Allow waiting time (Return)', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="allow_waiting_time_return" name="simontaxi_settings[allow_waiting_time_return]" title="<?php esc_html_e( 'Allow waiting time (Return)', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="no" <?php if ( isset ( $allow_waiting_time_return) && $allow_waiting_time_return == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
									<option value="yes" <?php if ( isset ( $allow_waiting_time_return) && $allow_waiting_time_return == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of waiting time in front end for return journey if user choose two way journey.' ); ?>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="alloow_twoway_booking"><?php esc_html_e( 'Allow two way booking', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="alloow_twoway_booking" name="simontaxi_settings[alloow_twoway_booking]" title="<?php esc_html_e( 'Allow two way booking', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="no" <?php if ( isset ( $alloow_twoway_booking) && $alloow_twoway_booking == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
									<option value="yes" <?php if ( isset ( $alloow_twoway_booking) && $alloow_twoway_booking == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of One way and two way booking in front end.' ); ?>
                            </td>
                        </tr>

						<?php $allow_twoway_airport = simontaxi_get_option( 'allow_twoway_airport', 'both' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="allow_twoway_airport"><?php esc_html_e( 'Allow Going to / Coming from ' . $fixed_point_title, 'simontaxi' ); ?></label>
                            </th>
                            <td>
								<select name="simontaxi_settings[allow_twoway_airport]" id="allow_twoway_airport">
									<option value="goingto" <?php if ( isset ( $allow_twoway_airport) && $allow_twoway_airport == 'goingto' ) { echo 'selected'; }?>><?php esc_html_e( 'Going to ' . $fixed_point_title, 'simontaxi' )?></option>
									<option value="comingfrom" <?php if ( isset ( $allow_twoway_airport) && $allow_twoway_airport == 'comingfrom' ) { echo 'selected'; }?>><?php esc_html_e( 'Coming from ' . $fixed_point_title, 'simontaxi' )?></option>
									<option value="both" <?php if ( isset ( $allow_twoway_airport) && $allow_twoway_airport == 'both' ) { echo 'selected'; }?>><?php esc_html_e( 'Both', 'simontaxi' )?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of "Allow Going to /Coming from ' . $fixed_point_title . '". It is applicable only for ' . $fixed_point_title ); ?>
                            </td>
                        </tr>

						<?php $allow_flight_number = simontaxi_get_option( 'allow_flight_number', 'no' );
						if ( '' !== $fixed_point_vehicle_name ) :
						?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="allow_flight_number"><?php esc_html_e( 'Allow ' . $fixed_point_vehicle_name . ' Number?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
								<select name="simontaxi_settings[allow_flight_number]" id="allow_flight_number">
									<option value="no" <?php if ( isset ( $allow_flight_number) && $allow_flight_number == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No Display', 'simontaxi' )?></option>
									<option value="yesoptional" <?php if ( isset ( $allow_flight_number) && $allow_flight_number == 'yesoptional' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Optional)', 'simontaxi' )?></option>
									<option value="yesrequired" <?php if ( isset ( $allow_flight_number) && $allow_flight_number == 'yesrequired' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Required)', 'simontaxi' )?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of "Allow Flight Number?". It is applicable only for fixed point transfer' ); ?>
                            </td>
                        </tr>
						<?php endif; ?>
						
						<?php $allow_flight_arrival_time = simontaxi_get_option( 'allow_flight_arrival_time', 'no' );
						if ( '' !== $fixed_point_vehicle_name ) :
						?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="allow_flight_arrival_time"><?php esc_html_e( 'Allow ' . $fixed_point_vehicle_name . ' Arrival Time?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
								<select name="simontaxi_settings[allow_flight_arrival_time]" id="allow_flight_arrival_time">
									<option value="no" <?php if ( isset ( $allow_flight_arrival_time) && $allow_flight_arrival_time == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No Display', 'simontaxi' )?></option>
									<option value="yesoptional" <?php if ( isset ( $allow_flight_arrival_time) && $allow_flight_arrival_time == 'yesoptional' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Optional)', 'simontaxi' )?></option>
									<option value="yesrequired" <?php if ( isset ( $allow_flight_arrival_time) && $allow_flight_arrival_time == 'yesrequired' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Required)', 'simontaxi' )?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of "Allow Flight Arrival Time?". It is applicable only for fixed point transfer' ); ?>
                            </td>
                        </tr>
						<?php endif; ?>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="allow_itinerary"><?php esc_html_e( 'Allow to enter Itinerary', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="allow_itinerary" name="simontaxi_settings[allow_itinerary]" title="<?php esc_html_e( 'Allow to enter Itinerary', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="no" <?php if ( isset ( $allow_itinerary) && $allow_itinerary == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No Display', 'simontaxi' ); ?></option>
									<option value="yesoptional" <?php if ( isset ( $allow_itinerary) && $allow_itinerary == 'yesoptional' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Optional)', 'simontaxi' ); ?></option>
									<option value="yesrequired" <?php if ( isset ( $allow_itinerary) && $allow_itinerary == 'yesrequired' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Required)', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of Itinerary. It is applicable only for hourly rental' ); ?>
                            </td>
                        </tr>
						
						<?php
						$allow_number_of_persons = simontaxi_get_option( 'allow_number_of_persons', 'no' );
						?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="allow_number_of_persons"><?php esc_html_e( 'Allow to enter No. of persons', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="allow_number_of_persons" name="simontaxi_settings[allow_number_of_persons]" title="<?php esc_html_e( 'Allow to enter Itinerary', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="no" <?php if ( isset ( $allow_number_of_persons) && $allow_number_of_persons == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No Display', 'simontaxi' ); ?></option>
									<option value="yesoptional" <?php if ( isset ( $allow_number_of_persons) && $allow_number_of_persons == 'yesoptional' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Optional)', 'simontaxi' ); ?></option>
									<option value="yesrequired" <?php if ( isset ( $allow_number_of_persons) && $allow_number_of_persons == 'yesrequired' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Required)', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of Allow to enter No. of persons. It will use if admin enable to calculate fare based on number of persons' ); ?>
                            </td>
                        </tr>
												
						<?php do_action( 'simontaxi_optional_fields_step1' ); ?>

						<tr valign="top"><td><h4><?php esc_html_e( 'Booking Step2', 'simontaxi' )?></h4></td><th>&nbsp;</th></tr>
						<?php $booking_summany_step2 = simontaxi_get_option( 'booking_summany_step2', 'half' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="booking_summany_step2"><?php esc_html_e( 'Show Booking Summary?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="booking_summany_step2" name="simontaxi_settings[booking_summany_step2]" title="<?php esc_html_e( 'Show Booking Summary?', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="yes" <?php if ( isset ( $booking_summany_step2) && $booking_summany_step2 == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if ( isset ( $booking_summany_step2) && $booking_summany_step2 == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						
						<?php
						$step2_sidebar_position = simontaxi_get_option( 'step2_sidebar_position', 'right' );
						?>
						<tr valign="top">
							<th class="titledesc" scope="row">
								<label for="step2_sidebar_position"><?php esc_html_e( 'Step-2 Sidebar Position', 'simontaxi' ); ?></label>
							</th>
							<td>
								<select id="step2_sidebar_position" name="simontaxi_settings[step2_sidebar_position]" title="<?php esc_html_e( 'Step-2 Sidebar Position', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="right" <?php if ( isset ( $step2_sidebar_position) && $step2_sidebar_position == 'right' ) { echo 'selected'; }?>><?php esc_html_e( 'Right', 'simontaxi' ); ?></option>
									<option value="left" <?php if ( isset ( $step2_sidebar_position) && $step2_sidebar_position == 'left' ) { echo 'selected'; }?>><?php esc_html_e( 'Left', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Step-2 Sidebar Position' ); ?>
							</td>
						</tr>
						
						<?php
						$default_breadcrumb_display_step2 = simontaxi_get_option( 'default_breadcrumb_display_step2', 'yes' );
						?>
						<tr valign="top">
							<th class="titledesc" scope="row">
								<label for="default_breadcrumb_display_step2"><?php esc_html_e( 'Default Breadcrumb', 'simontaxi' ); ?></label>
							</th>
							<td>
								<select id="default_breadcrumb_display_step2" name="simontaxi_settings[default_breadcrumb_display_step2]" title="<?php esc_html_e( 'Default Breadcrumb', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="yes" <?php if ( $default_breadcrumb_display_step2 == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if (  $default_breadcrumb_display_step2 == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Default Breadcrumb' ); ?>
							</td>
						</tr>
						
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="coupon_code_form"><?php esc_html_e( 'Coupon code form', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="coupon_code_form" name="simontaxi_settings[coupon_code_form]" title="<?php esc_html_e( 'Coupon code form', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="no" <?php if ( isset ( $coupon_code_form) && $coupon_code_form == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No Display', 'simontaxi' ); ?></option>
									<option value="yes" <?php if ( isset ( $coupon_code_form) && $coupon_code_form == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of Coupon code form in booking.' ); ?>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="show_luggage_information"><?php esc_html_e( 'Luggage Information', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="show_luggage_information" name="simontaxi_settings[show_luggage_information]" title="<?php esc_html_e( 'Luggage Information', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="no" <?php if ( isset ( $show_luggage_information) && $show_luggage_information == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No Display', 'simontaxi' ); ?></option>
									<option value="yes" <?php if ( isset ( $show_luggage_information) && $show_luggage_information == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of luggage information in booking.' ); ?>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="show_seating_capacity"><?php esc_html_e( 'Seating Capacity', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="show_seating_capacity" name="simontaxi_settings[show_seating_capacity]" title="<?php esc_html_e( 'Seating Capacity', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="no" <?php if ( isset ( $show_seating_capacity) && $show_seating_capacity == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No Display', 'simontaxi' ); ?></option>
									<option value="yes" <?php if ( isset ( $show_seating_capacity) && $show_seating_capacity == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of Seating Capacity in booking.' ); ?>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="show_fare"><?php esc_html_e( 'Display Fare', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="show_fare" name="simontaxi_settings[show_fare]" title="<?php esc_html_e( 'Coupon code form', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="totalonly" <?php if ( isset ( $show_fare ) && $show_fare == 'totalonly' ) { echo 'selected'; }?>><?php esc_html_e( 'Total only', 'simontaxi' ); ?></option>
									<option value="totalwithminfare" <?php if ( isset ( $show_fare ) && $show_fare == 'totalwithminfare' ) { echo 'selected'; }?>><?php esc_html_e( 'Total with minimum fare', 'simontaxi' ); ?></option>
									<option value="basicdetailsonly" <?php if ( isset ( $show_fare) && $show_fare == 'basicdetailsonly' ) { echo 'selected'; }?>><?php esc_html_e( 'Basic Details', 'simontaxi' ); ?></option>
									<option value="totalbasic" <?php if ( isset ( $show_fare ) && $show_fare == 'totalbasic' ) { echo 'selected'; }?>><?php esc_html_e( 'Total and Basic Details', 'simontaxi' ); ?></option>
									<option value="none" <?php if ( isset ( $show_fare ) && $show_fare == 'none' ) { echo 'selected'; }?>><?php esc_html_e( 'No Display', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of sub total in booking.' ); ?>
                            </td>
                        </tr>
						
						<?php do_action( 'simontaxi_optional_fields_step2' ); ?>

						<tr valign="top"><td><h4><?php esc_html_e( 'Booking Step3', 'simontaxi' )?></h4></td><th>&nbsp;</th></tr>
						<?php $booking_summany_step3 = simontaxi_get_option( 'booking_summany_step3', 'half' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="booking_summany_step3"><?php esc_html_e( 'Show Booking Summary?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="booking_summany_step3" name="simontaxi_settings[booking_summany_step3]" title="<?php esc_html_e( 'Show Booking Summary?', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="yes" <?php if ( isset ( $booking_summany_step3) && $booking_summany_step3 == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if ( isset ( $booking_summany_step3) && $booking_summany_step3 == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						
						<?php
						$step3_sidebar_position = simontaxi_get_option( 'step3_sidebar_position', 'right' );
						?>
						<tr valign="top">
							<th class="titledesc" scope="row">
								<label for="step3_sidebar_position"><?php esc_html_e( 'Step-3 Sidebar Position', 'simontaxi' ); ?></label>
							</th>
							<td>
								<select id="step3_sidebar_position" name="simontaxi_settings[step3_sidebar_position]" title="<?php esc_html_e( 'Step-3 Sidebar Position', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="right" <?php if ( isset ( $step3_sidebar_position) && $step3_sidebar_position == 'right' ) { echo 'selected'; }?>><?php esc_html_e( 'Right', 'simontaxi' ); ?></option>
									<option value="left" <?php if ( isset ( $step3_sidebar_position) && $step3_sidebar_position == 'left' ) { echo 'selected'; }?>><?php esc_html_e( 'Left', 'simontaxi' ); ?></option>
									<option value="none" <?php if ( isset ( $step3_sidebar_position) && $step3_sidebar_position == 'none' ) { echo 'selected'; }?>><?php esc_html_e( 'None', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Step-3 Sidebar Position' ); ?>
							</td>
						</tr>
						
						<?php
						/**
						 * @since 2.0.8
						 */
						$default_breadcrumb_display_step3 = simontaxi_get_option( 'default_breadcrumb_display_step3', 'yes' );
						?>
						<tr valign="top">
							<th class="titledesc" scope="row">
								<label for="default_breadcrumb_display_step3"><?php esc_html_e( 'Default Breadcrumb', 'simontaxi' ); ?></label>
							</th>
							<td>
								<select id="default_breadcrumb_display_step3" name="simontaxi_settings[default_breadcrumb_display_step3]" title="<?php esc_html_e( 'Default Breadcrumb', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="yes" <?php if ( $default_breadcrumb_display_step3 == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if (  $default_breadcrumb_display_step3 == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Default Breadcrumb' ); ?>
							</td>
						</tr>
						
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="name_display"><?php esc_html_e( 'Name display as', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="name_display" name="simontaxi_settings[name_display]" title="<?php esc_html_e( 'Name display as', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="fullnameoptional" <?php if ( isset ( $name_display) && $name_display == 'fullnameoptional' ) { echo 'selected'; }?>><?php esc_html_e( 'Full Name (Optional)', 'simontaxi' ); ?></option>
									<option value="fullnamerequired" <?php if ( isset ( $name_display) && $name_display == 'fullnamerequired' ) { echo 'selected'; }?>><?php esc_html_e( 'Full Name (Required)', 'simontaxi' ); ?></option>
									<option value="firstoptionallastoptional" <?php if ( isset ( $name_display) && $name_display == 'firstoptionallastoptional' ) { echo 'selected'; }?>><?php esc_html_e( 'First Name(Optional) Last Name (Optional)', 'simontaxi' ); ?></option>
									<option value="firstrequiredlastrequired" <?php if ( isset ( $name_display) && $name_display == 'firstrequiredlastrequired' ) { echo 'selected'; }?>><?php esc_html_e( 'First Name(Required) Last Name (Required)', 'simontaxi' ); ?></option>
									<option value="firstrequiredlastoptional" <?php if ( isset ( $name_display) && $name_display == 'firstrequiredlastoptional' ) { echo 'selected'; }?>><?php esc_html_e( 'First Name (Required) Last Name (Optional)', 'simontaxi' ); ?></option>
									<option value="firstoptionallastrequired" <?php if ( isset ( $name_display) && $name_display == 'firstoptionallastrequired' ) { echo 'selected'; }?>><?php esc_html_e( 'First Name (Optional) Last Name (Required)', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of passenger name for booking.' ); ?>
                            </td>
                        </tr>
						<?php
						 /**
						  * @since 2.0.2
						  */
						?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="company_name"><?php esc_html_e( 'Company Name', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <?php $company_name = simontaxi_get_option( 'company_name', 'no' ); ?>
								<select id="company_name" name="simontaxi_settings[company_name]" title="<?php esc_html_e( 'Company Name', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="no" <?php if ( isset ( $company_name) && $company_name == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No Display', 'simontaxi' ); ?></option>
									<option value="yesoptional" <?php if ( isset ( $company_name) && $company_name == 'yesoptional' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Optional)', 'simontaxi' ); ?></option>
									<option value="yesrequired" <?php if ( isset ( $company_name) && $company_name == 'yesrequired' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Required)', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of Company Name field in booking.' ); ?>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="phone_number"><?php esc_html_e( 'Phone number', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="phone_number" name="simontaxi_settings[phone_number]" title="Terms Page" style="width: 25em;">
									<option value="no" <?php if ( isset ( $phone_number) && $phone_number == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No Display', 'simontaxi' ); ?></option>
									<option value="phonecountryoptional" <?php if ( isset ( $phone_number) && $phone_number == 'phonecountryoptional' ) { echo 'selected'; }?>><?php esc_html_e( 'Phone with country (Optional)', 'simontaxi' ); ?></option>
									<option value="phonecountryrequired" <?php if ( isset ( $phone_number) && $phone_number == 'phonecountryrequired' ) { echo 'selected'; }?>><?php esc_html_e( 'Phone with country (Required)', 'simontaxi' ); ?></option>
									<option value="phoneoptional" <?php if ( isset ( $phone_number) && $phone_number == 'phoneoptional' ) { echo 'selected'; }?>><?php esc_html_e( 'Phone only (Optional)', 'simontaxi' ); ?></option>
									<option value="phonerequired" <?php if ( isset ( $phone_number) && $phone_number == 'phonerequired' ) { echo 'selected'; }?>><?php esc_html_e( 'Phone only (Required', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of Phone number field in booking.' ); ?>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="no_of_passengers"><?php esc_html_e( 'No. of Passengers', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="no_of_passengers" name="simontaxi_settings[no_of_passengers]" title="Terms Page" style="width: 25em;">
									<option value="no" <?php if ( isset ( $no_of_passengers) && $no_of_passengers == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No Display', 'simontaxi' ); ?></option>
									<option value="yesoptional" <?php if ( isset ( $no_of_passengers) && $no_of_passengers == 'yesoptional' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Optional)', 'simontaxi' ); ?></option>
									<option value="yesrequired" <?php if ( isset ( $no_of_passengers) && $no_of_passengers == 'yesrequired' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Required)', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of No. of Passengers field in booking.' ); ?>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="land_mark_pickupaddress"><?php esc_html_e( 'Land Mark / Pickup Address', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="land_mark_pickupaddress" name="simontaxi_settings[land_mark_pickupaddress]" title="<?php esc_html_e( 'Land mark', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="no" <?php if ( isset ( $land_mark_pickupaddress) && $land_mark_pickupaddress == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No Display', 'simontaxi' ); ?></option>
									<option value="yesoptional" <?php if ( isset ( $land_mark_pickupaddress) && $land_mark_pickupaddress == 'yesoptional' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Optional)', 'simontaxi' ); ?></option>
									<option value="yesrequired" <?php if ( isset ( $land_mark_pickupaddress) && $land_mark_pickupaddress == 'yesrequired' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Required)', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of Land mark field in booking.' ); ?>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="additional_pickup_address"><?php esc_html_e( 'Additional Pickup Addresses', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="additional_pickup_address" name="simontaxi_settings[additional_pickup_address]" title="<?php esc_html_e( 'Additional Pickup Addresses', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="no" <?php if ( isset ( $additional_pickup_address) && $additional_pickup_address == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No Display', 'simontaxi' ); ?></option>
									<option value="yesoptional" <?php if ( isset ( $additional_pickup_address) && $additional_pickup_address == 'yesoptional' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Optional)', 'simontaxi' ); ?></option>
									<option value="yesrequired" <?php if ( isset ( $additional_pickup_address) && $additional_pickup_address == 'yesrequired' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Required)', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of Additional Pickup Addresses field in booking.' ); ?>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="additional_dropoff_address"><?php esc_html_e( 'Additional Drop-off Addresses', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="additional_dropoff_address" name="simontaxi_settings[additional_dropoff_address]" title="<?php esc_html_e( 'Additional Drop-off Addresses', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="no" <?php if ( isset ( $additional_dropoff_address) && $additional_dropoff_address == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No Display', 'simontaxi' ); ?></option>
									<option value="yesoptional" <?php if ( isset ( $additional_dropoff_address) && $additional_dropoff_address == 'yesoptional' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Optional)', 'simontaxi' ); ?></option>
									<option value="yesrequired" <?php if ( isset ( $additional_dropoff_address) && $additional_dropoff_address == 'yesrequired' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Required)', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of Additional Drop-off Addresses field in booking.' ); ?>
                            </td>
                        </tr>

						<!-- Return Journey -->
						<?php $additional_pickups_return = simontaxi_get_option( 'additional_pickups_return', 'no' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="additional_pickups_return"><?php esc_html_e( 'Additional Pickup Addresses (Return)', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="additional_pickups_return" name="simontaxi_settings[additional_pickups_return]" title="<?php esc_html_e( 'Return Drop-off Addresses', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="no" <?php if ( isset ( $additional_pickups_return) && $additional_pickups_return == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No Display', 'simontaxi' ); ?></option>
									<option value="yesoptional" <?php if ( isset ( $additional_pickups_return) && $additional_pickups_return == 'yesoptional' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Optional)', 'simontaxi' ); ?></option>
									<option value="yesrequired" <?php if ( isset ( $additional_pickups_return) && $additional_pickups_return == 'yesrequired' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Required)', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of Return Pickup Addresses field in booking.' ); ?>
                            </td>
                        </tr>

						<?php $additional_dropoff_address_return = simontaxi_get_option( 'additional_dropoff_address_return', 'no' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="additional_dropoff_address_return"><?php esc_html_e( 'Additional Drop-off Addresses (Return)', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="additional_dropoff_address_return" name="simontaxi_settings[additional_dropoff_address_return]" title="<?php esc_html_e( 'Return Drop-off Addresses', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="no" <?php if ( isset ( $additional_dropoff_address_return) && $additional_dropoff_address_return == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No Display', 'simontaxi' ); ?></option>
									<option value="yesoptional" <?php if ( isset ( $additional_dropoff_address_return) && $additional_dropoff_address_return == 'yesoptional' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Optional)', 'simontaxi' ); ?></option>
									<option value="yesrequired" <?php if ( isset ( $additional_dropoff_address_return) && $additional_dropoff_address_return == 'yesrequired' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Required)', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of Return Drop-off Addresses field in booking.' ); ?>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="special_instructions"><?php esc_html_e( 'Special Instructions', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="special_instructions" name="simontaxi_settings[special_instructions]" title="<?php esc_html_e( 'Return Drop-off Addresses', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="no" <?php if ( isset ( $special_instructions) && $special_instructions == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No Display', 'simontaxi' ); ?></option>
									<option value="yesoptional" <?php if ( isset ( $special_instructions) && $special_instructions == 'yesoptional' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Optional)', 'simontaxi' ); ?></option>
									<option value="yesrequired" <?php if ( isset ( $special_instructions) && $special_instructions == 'yesrequired' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes (Required)', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Controls the display of Special Instructions (Like message to driver, booking note etc.) field in booking.' ); ?>
                            </td>
                        </tr>
						
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="additional_address_instructions"><?php esc_html_e( 'Additional address instructions', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <?php $additional_address_instructions = simontaxi_get_option( 'additional_address_instructions', '' ); ?>
								<textarea id="additional_address_instructions" name="simontaxi_settings[additional_address_instructions]" title="<?php esc_html_e( 'Additional address instructions', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'Additional stop address should be on the way to drop-off point. If your stop exceeding more than 5 miles, fare needs to be adjusted', 'simontaxi' ); ?>"><?php echo $additional_address_instructions; ?></textarea><?php echo simontaxi_get_help( 'Eg: Additional stop address should be on the way to drop-off point. If your stop exceeding more than 5 miles, fare needs to be adjusted' ); ?>
                            </td>
                        </tr>
						
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="additional_address_instructions_dropoff"><?php esc_html_e( 'Additional address instructions (Drop-off)', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <?php $additional_address_instructions_dropoff = simontaxi_get_option( 'additional_address_instructions_dropoff', '' ); ?>
								<textarea id="additional_address_instructions_dropoff" name="simontaxi_settings[additional_address_instructions_dropoff]" title="<?php esc_html_e( 'Additional address instructions', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'Additional stop address should be on the way to drop-off point. If your stop exceeding more than 5 miles, fare needs to be adjusted', 'simontaxi' ); ?>"><?php echo $additional_address_instructions_dropoff; ?></textarea><?php echo simontaxi_get_help( 'Eg: Additional stop address should be on the way to drop-off point. If your stop exceeding more than 5 miles, fare needs to be adjusted' ); ?>
                            </td>
                        </tr>
						
						<?php do_action( 'simontaxi_optional_fields_step3' ); ?>


						<tr valign="top"><td><h4><?php esc_html_e( 'Booking Step4', 'simontaxi' )?></h4></td><th>&nbsp;</th></tr>

						<?php $booking_summany_step4 = simontaxi_get_option( 'booking_summany_step4', 'half' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="booking_summany_step4"><?php esc_html_e( 'Show Booking Summary?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="booking_summany_step4" name="simontaxi_settings[booking_summany_step4]" title="<?php esc_html_e( 'Show Booking Summary?', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="yes" <?php if ( isset ( $booking_summany_step4) && $booking_summany_step4 == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if ( isset ( $booking_summany_step4) && $booking_summany_step4 == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						
						<?php
						$step4_sidebar_position = simontaxi_get_option( 'step4_sidebar_position', 'right' );
						?>
						<tr valign="top">
							<th class="titledesc" scope="row">
								<label for="step4_sidebar_position"><?php esc_html_e( 'Step-4 Sidebar Position', 'simontaxi' ); ?></label>
							</th>
							<td>
								<select id="step4_sidebar_position" name="simontaxi_settings[step4_sidebar_position]" title="<?php esc_html_e( 'Step-4 Sidebar Position', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="right" <?php if ( isset ( $step4_sidebar_position) && $step4_sidebar_position == 'right' ) { echo 'selected'; }?>><?php esc_html_e( 'Right', 'simontaxi' ); ?></option>
									<option value="left" <?php if ( isset ( $step4_sidebar_position) && $step4_sidebar_position == 'left' ) { echo 'selected'; }?>><?php esc_html_e( 'Left', 'simontaxi' ); ?></option>
									<option value="none" <?php if ( isset ( $step4_sidebar_position) && $step4_sidebar_position == 'none' ) { echo 'selected'; }?>><?php esc_html_e( 'None', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Step-4 Sidebar Position' ); ?>
							</td>
						</tr>
						
						<?php
						/**
						 * @since 2.0.8
						 */
						$default_breadcrumb_display_step4 = simontaxi_get_option( 'default_breadcrumb_display_step4', 'yes' );
						?>
						<tr valign="top">
							<th class="titledesc" scope="row">
								<label for="default_breadcrumb_display_step4"><?php esc_html_e( 'Default Breadcrumb', 'simontaxi' ); ?></label>
							</th>
							<td>
								<select id="default_breadcrumb_display_step4" name="simontaxi_settings[default_breadcrumb_display_step4]" title="<?php esc_html_e( 'Default Breadcrumb', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="yes" <?php if ( $default_breadcrumb_display_step4 == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if (  $default_breadcrumb_display_step4 == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Default Breadcrumb' ); ?>
							</td>
						</tr>
						
						<?php do_action( 'simontaxi_optional_fields_step4' ); ?>
						
						<?php
						$template = 'booking/includes/pages/admin/sidebar-settings.php';
						if ( simontaxi_is_template_customized( $template ) ) {
							include_once simontaxi_get_theme_template_dir_name() . $template;
						} else {
							include_once apply_filters( 'simontaxi_locate_sidebar_settings', SIMONTAXI_PLUGIN_PATH . $template );
						}
						?>
						
						
						</tbody>
						</table>
					</div>

					<!-- Currency settings -->
					<div id="st-currency" class="tab-pane fade in" style="display:<?php if( $tab === 'currency' ) { echo 'block'; } else { echo 'none'; } ?>">
						<table class="st-table">
						<tbody>
						<?php do_action( 'simontaxi_currency_fields_before' ); ?>
                        <tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_currency"><?php esc_html_e( 'Currency Type', 'simontaxi' ); ?></label>
                            </th>
                            <td>
								<select id="vehicle_currency" name="simontaxi_settings[vehicle_currency]" title="Currency" style="width: 25em;">
                                    <?php
                                    $currencyList = simontaxi_currencies();
									if ( $currencyList) {
                                        foreach ( $currencyList as $result) {
                                            $current_symbol = $result->currency_code . '_' . $result->id_countries;
											?>
                                            <option value="<?php echo $result->currency_code . '_' . $result->id_countries; ?>" <?php if ( strcasecmp( $vehicle_currency, $current_symbol ) == 0 ) echo 'selected="selected"'; ?>><?php echo $result->name . ' / ' . $result->currency_name . "&nbsp&nbsp(" . $result->currency_symbol . ")"; ?> </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="display_currency"><?php esc_html_e( 'Display Currency', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="display_currency" name="simontaxi_settings[display_currency]" title="Terms Page" style="width: 25em;">
									<option value="symbol" <?php if ( isset ( $display_currency) && $display_currency == 'symbol' ) { echo 'selected'; }?>><?php esc_html_e( 'Symbol', 'simontaxi' ); ?></option>
									<option value="code" <?php if ( isset ( $display_currency) && $display_currency == 'code' ) { echo 'selected'; }?>><?php esc_html_e( 'Code', 'simontaxi' ); ?></option>
								</select><?php echo simontaxi_get_help( 'Display of currency for user in front end.' ); ?>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="currency_position"><?php esc_html_e( 'Currency Position', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <?php $currency_position = simontaxi_get_option( 'currency_position', 'left' ); ?>
								<select id="currency_position" name="simontaxi_settings[currency_position]" title="Terms Page" style="width: 25em;">
									<option value="left" <?php if ( isset ( $currency_position) && $currency_position == 'left' ) { echo 'selected'; }?>><?php esc_html_e( 'Left', 'simontaxi' ); ?></option>
									<option value="right" <?php if ( isset ( $currency_position) && $currency_position == 'right' ) { echo 'selected'; }?>><?php esc_html_e( 'Right', 'simontaxi' ); ?></option>
									<option value="left_with_space" <?php if ( isset ( $currency_position) && $currency_position == 'left_with_space' ) { echo 'selected'; }?>><?php esc_html_e( 'Left with space', 'simontaxi' ); ?></option>
									<option value="right_with_space" <?php if ( isset ( $currency_position) && $currency_position == 'right_with_space' ) { echo 'selected'; }?>><?php esc_html_e( 'Right with space', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="thousand_separator" scope="row">
                                <label for="thousand_separator"><?php esc_html_e( 'Thousand Separator', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="thousand_separator" value="<?php if ( isset ( $thousand_separator)) { echo $thousand_separator; } else { esc_html_e( ',', 'simontaxi' );}?>" name="simontaxi_settings[thousand_separator]" title="<?php esc_html_e( 'Thousand Separator', 'simontaxi' ); ?>" style="width: 25em;">
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="decimal_separator" scope="row">
                                <label for="decimal_separator"><?php esc_html_e( 'Decimal Separator', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="decimal_separator" value="<?php if ( isset ( $decimal_separator)) { echo $decimal_separator; } else { esc_html_e( '.', 'simontaxi' );}?>" name="simontaxi_settings[decimal_separator]" title="<?php esc_html_e( 'Decimal Separator', 'simontaxi' ); ?>" style="width: 25em;">
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="number_of_decimals" scope="row">
                                <label for="number_of_decimals"><?php esc_html_e( 'Number of Decimals', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="number" id="number_of_decimals" value="<?php if ( isset ( $number_of_decimals)) { echo $number_of_decimals; } else { esc_html_e( '.', 'simontaxi' );}?>" name="simontaxi_settings[number_of_decimals]" title="<?php esc_html_e( 'Number of Decimals', 'simontaxi' ); ?>" style="width: 25em;" min="0">
                            </td>
                        </tr>
						
						<?php do_action( 'simontaxi_currency_fields_after' ); ?>

						</tbody>
						</table>
					</div>
					<!-- Tab & titles settings -->
					<div id="st-tabstitles" class="tab-pane fade in" style="display:<?php if( $tab === 'tabstitles' ) { echo 'block'; } else { echo 'none'; } ?>">
						<table class="st-table">
						<tbody>
						<?php do_action( 'simontaxi_tabstitles_before' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="active_tabs"><?php esc_html_e( 'Active Tabs of Booking types', 'simontaxi' ); ?></label>
                            </th>
                            <td>
								<?php
								foreach( $booking_types as $key => $booking_type ) {
									?>
									<input type="checkbox" <?php if(is_array( $active_tabs)) { if ( in_array( $key, $active_tabs)) { ?>checked="checked"<?php }} ?> name="simontaxi_settings[active_tabs][]" value="<?php esc_html_e( $key, 'simontaxi' ); ?>">&nbsp;<?php esc_html_e( $booking_type, 'simontaxi' ); ?> &nbsp;
									<?php
								}
								?>
                            </td>
                        </tr>
						
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="default_active_tab"><?php esc_html_e( 'Default Active Tab', 'simontaxi' ); ?></label>
                            </th>
                            <td>
								<?php $default_active_tab = simontaxi_get_option( 'default_active_tab', 'p2p' ); ?>
								<select name="simontaxi_settings[default_active_tab]" id="default_active_tab">
								<?php
								foreach( $booking_types as $key => $booking_type ) {
									?>
									<option value="<?php echo $key; ?>" <?php if( $key === $default_active_tab ) echo 'selected'; ?>>&nbsp;<?php esc_html_e( $booking_type, 'simontaxi' ); ?></option>
									<?php
								}
								?>
								</select>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="active_tabs"><?php esc_html_e( 'Tab Names', 'simontaxi' ); ?></label>
                            </th>
                            <td>&nbsp;
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="p2p_tab_title"><?php esc_html_e( 'Point to Point Transfer', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="p2p_tab_title" value="<?php if ( isset ( $p2p_tab_title)) { echo $p2p_tab_title; } else { esc_html_e( 'Point to Point Transfer', 'simontaxi' );}?>" name="simontaxi_settings[p2p_tab_title]" title="P2P Tab Title" style="width: 25em;">
                            </td>
                        </tr>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="airport_tab_title"><?php esc_html_e( 'Fixed Point Transfer', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="airport_tab_title" value="<?php if ( isset ( $airport_tab_title)) { echo $airport_tab_title; } else { esc_html_e( 'Airport Transfer', 'simontaxi' );}?>" name="simontaxi_settings[airport_tab_title]" title="<?php esc_html_e( 'Fixed Point Transfer', 'simontaxi' ); ?>" style="width: 25em;">
                            </td>
                        </tr>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="fixed_point_title"><?php esc_html_e( 'Fixed Point Title', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                
								<input type="text" id="fixed_point_title" value="<?php if ( isset ( $fixed_point_title)) { echo $fixed_point_title; } else { esc_html_e( 'Airport', 'simontaxi' );}?>" name="simontaxi_settings[fixed_point_title]" title="<?php esc_html_e( 'Fixed Point Title', 'simontaxi' ); ?>" style="width: 25em;">
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="fixed_point_vehicle_name"><?php esc_html_e( 'Fixed Point Vehicle Name', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <?php $fixed_point_vehicle_name = simontaxi_get_option( 'fixed_point_vehicle_name', 'Flight' ); ?>
								<input type="text" id="fixed_point_vehicle_name" value="<?php if ( isset ( $fixed_point_vehicle_name)) { echo $fixed_point_vehicle_name; } else { esc_html_e( 'Flight', 'simontaxi' );}?>" name="simontaxi_settings[fixed_point_vehicle_name]" title="<?php esc_html_e( 'Fixed Point Vehicle Name', 'simontaxi' ); ?>" style="width: 25em;">
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="hourly_tab_title"><?php esc_html_e( 'Hourly Rental', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="hourly_tab_title" value="<?php if ( isset ( $hourly_tab_title)) { echo $hourly_tab_title; } else { esc_html_e( 'Hourly Rental', 'simontaxi' );}?>" name="simontaxi_settings[hourly_tab_title]" title="<?php esc_attr_e( 'Hourly Rental Tab Title', 'simontaxi-roundtrip' ); ?>" style="width: 25em;">
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="booking_step1_title"><?php esc_html_e( 'Booking Step 1 Title', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="booking_step1_title" value="<?php if ( isset ( $booking_step1_title)) { echo $booking_step1_title; } else { esc_html_e( 'Booking Step 1 Title', 'simontaxi' );}?>" name="simontaxi_settings[booking_step1_title]" title="<?php esc_attr_e( 'Booking Step 1 Title', 'simontaxi-roundtrip' ); ?>" style="width: 25em;">
                            </td>
                        </tr>
						
						<?php do_action( 'simontaxi_additional_booking_type_titles' ); ?>

						<?php $booking_step1_title_home = simontaxi_get_option( 'booking_step1_title_home', esc_html__( 'Book a taxi now' ) ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="booking_step1_title_home"><?php esc_html_e( 'Booking Step 1 Title (Home)', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="booking_step1_title_home" value="<?php if ( isset ( $booking_step1_title_home)) { echo $booking_step1_title_home; } else { esc_html_e( 'Book a taxi now', 'simontaxi' );}?>" name="simontaxi_settings[booking_step1_title_home]" title="<?php esc_attr_e( 'Booking Step 1 Title (Home)', 'simontaxi-roundtrip' ); ?>" style="width: 25em;">
                            </td>
                        </tr>
						
						<?php $booking_step1_home_width = simontaxi_get_option( 'booking_step1_home_width', 6 ); 
						?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="booking_step1_home_width"><?php esc_html_e( 'Booking Step 1 Home Width', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[booking_step1_home_width]" id="booking_step1_home_width">
									<?php
									/**
									 * Bootstrap grid columns should add up to twelve for a row. More than that, columns will stack no matter the viewport.
									 */
									for( $c = 4; $c <= 12; $c+=2 ) {
										$selected = '';
										if ( $c == $booking_step1_home_width ) {
											$selected = ' selected';
										}
										echo '<option value="'.$c.'"'.$selected.'>'.$c . ' ' . esc_html__( 'Columns', 'simontaxi' ).'</option>';
									}
									?>
								</select>
								<?php
								
								?>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="booking_step2_title"><?php esc_html_e( 'Booking Step 2 Title', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="booking_step2_title" value="<?php if ( isset ( $booking_step2_title)) { echo $booking_step2_title; } else { esc_html_e( 'Select Cab', 'simontaxi' );}?>" name="simontaxi_settings[booking_step2_title]" title="Booking Step 2 Title" style="width: 25em;">
                            </td>
                        </tr>


						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="booking_step3_title"><?php esc_html_e( 'Booking Step 3 Title', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="booking_step3_title" value="<?php if ( isset ( $booking_step3_title)) { echo $booking_step3_title; } else { esc_html_e( 'Confirm Booking', 'simontaxi' );}?>" name="simontaxi_settings[booking_step3_title]" title="Booking Step 3 Title" style="width: 25em;">
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="booking_step4_title"><?php esc_html_e( 'Booking Step 4 Title', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="booking_step4_title" value="<?php if ( isset ( $booking_step4_title)) { echo $booking_step4_title; } else { esc_html_e( 'Payment', 'simontaxi' );}?>" name="simontaxi_settings[booking_step4_title]" title="<?php esc_html_e( 'Booking Step 4 Title', 'simontaxi' ); ?>" style="width: 25em;">
                            </td>
                        </tr>
						
						<?php 
						/**
						 * @since 2.0.8
						 */
						$surcharges_title = simontaxi_get_option( 'surcharges_title', esc_html__( 'Surcharges', 'simontaxi' ) ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="surcharges_title"><?php esc_html_e( 'Surcharges', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="surcharges_title" value="<?php if ( isset ( $surcharges_title)) { echo $surcharges_title; } else { esc_html_e( 'Surcharges', 'simontaxi' );}?>" name="simontaxi_settings[surcharges_title]" title="<?php esc_html_e( 'Surcharges', 'simontaxi' ); ?>" style="width: 25em;">
                            </td>
                        </tr>
						
						<?php do_action( 'simontaxi_tabstitles_after' ); ?>
						
						</tbody>
						</table>
					</div>
					<!-- Tax settings -->
					<div id="st-taxsettings" class="tab-pane fade in" style="display:<?php if( $tab === 'taxsettings' ) { echo 'block'; } else { echo 'none'; } ?>">
						<table class="st-table">
						<tbody>
						<?php do_action( 'simontaxi_taxsettings_fields_before' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="tax_rate"><?php esc_html_e( 'GST / Tax Rate', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="number" id="tax_rate" value="<?php
                                if ( isset ( $tax_rate)) {
                                    echo $tax_rate;
                                } else { echo '0'; }
                                ?>" name="simontaxi_settings[tax_rate]" title="<?php esc_html_e( 'GST / Tax Rate', 'simontaxi' )?>" placeholder="<?php esc_html_e( 'GST / Tax Rate', 'simontaxi' )?>" style="width: 25em;">&nbsp;
								<select name="simontaxi_settings[tax_rate_type]">
									<option value="percent" <?php if(( isset ( $tax_rate_type) && $tax_rate_type == 'percent' )) echo 'selected'; ?>><?php esc_html_e( 'Percent %', 'simontaxi' )?></option>
									<option value="value" <?php if(( isset ( $tax_rate_type) && $tax_rate_type == 'value' )) echo 'selected'; ?>><?php esc_html_e( 'Value', 'simontaxi' )?></option>
								</select>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="gst_no"><?php esc_html_e( 'GST No.', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <?php $gst_no = simontaxi_get_option( 'gst_no', '' ); ?>
								<input type="text" id="gst_no" value="<?php
                                if ( isset ( $gst_no)) {
                                    echo $gst_no;
                                }
                                ?>" name="simontaxi_settings[gst_no]" title="<?php esc_html_e( 'GST No.', 'simontaxi' )?>" placeholder="<?php esc_html_e( 'GST No.', 'simontaxi' )?>" style="width: 25em;">
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="display_tax_rate"><?php esc_html_e( 'Display Tax Rate on Prices', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="display_tax_rate" name="simontaxi_settings[display_tax_rate]" title="Terms Page" style="width: 25em;">
									<option value="no" <?php if ( isset ( $display_tax_rate) && $display_tax_rate == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' )?></option>
									<option value="yes" <?php if ( isset ( $display_tax_rate) && $display_tax_rate == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' )?></option>
								</select>
                            </td>
                        </tr>

						<?php $tax_calculation_based_on = simontaxi_get_option( 'tax_calculation_based_on', 'basicfare' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="tax_calculation_based_on"><?php esc_html_e( 'Calculate tax based on', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="tax_calculation_based_on" name="simontaxi_settings[tax_calculation_based_on]" title="Terms Page" style="width: 25em;">
									<option value="basicfare" <?php if ( isset ( $tax_calculation_based_on) && $tax_calculation_based_on == 'basicfare' ) { echo 'selected'; }?>><?php esc_html_e( 'Basic Fare', 'simontaxi' )?></option>
									
									<option value="basicfaresurcharges" <?php if ( isset ( $tax_calculation_based_on) && $tax_calculation_based_on == 'basicfaresurcharges' ) { echo 'selected'; }?>><?php esc_html_e( 'Basic Fare + Surcharges', 'simontaxi' )?></option>
							
									<option value="basicfaresurchargesminusdiscount" <?php if ( isset ( $tax_calculation_based_on) && $tax_calculation_based_on == 'basicfaresurchargesminusdiscount' ) { echo 'selected'; }?>><?php esc_html_e( 'Basic Fare + Surcharges - Discount if any', 'simontaxi' )?></option>
								</select>
                            </td>
                        </tr>
						
						<?php do_action( 'simontaxi_taxsettings_fields_after' ); ?>
						
						</tbody>
						</table>
					</div>

					<!-- paymentgateways Tab -->
					<div id="st-paymentgateways" class="tab-pane fade in" style="display:<?php if( $tab === 'paymentgateways' ) { echo 'block'; } else { echo 'none'; } ?>">
						<table class="st-table">
						<tbody>

						<?php do_action( 'simontaxi_paymentgateways_fields_before' ); ?>
                        <?php
						$available_pay_methods = array( 
							'paypal' => esc_html__( 'Paypal', 'simontaxi' ), 
							'payu' => esc_html__( 'PayU', 'simontaxi' ), 
							'byhand' => esc_html__( 'By Hand', 'simontaxi' ),
							// 'banktransfer' => esc_html__( 'Bank Transfer', 'simontaxi' ),
							);
						$available_pay_methods = apply_filters( 'simontaxi_payment_gateways', $available_pay_methods );
						 ?>

                        <tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="payment_mode"><?php esc_html_e( 'Payment Mode', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <?php if ( $section == '' ) {
									esc_html_e( 'General', 'simontaxi' );
								} else { ?>
								<a href="<?php echo admin_url( 'edit.php?post_type=vehicle&page=vehicle_settings&tab=paymentgateways' ); ?>"><?php esc_html_e( 'General', 'simontaxi' ); ?></a>
								<?php } ?>
								<?php
								foreach ( $available_pay_methods as $key => $val ) {
									?>
									&nbsp;|&nbsp;
									<?php if ( $section == $key ) {
										esc_html_e( $val, 'simontaxi' );
									} else { ?>
									<a href="<?php echo admin_url( 'edit.php?post_type=vehicle&page=vehicle_settings&tab=paymentgateways&section='.$key); ?>"><?php esc_html_e( $val, 'simontaxi' ); ?></a>
									<?php
									}
								}
								?>
                            </td>
                        </tr>

						<table <?php if ( $section == '' ) { echo 'class="st-table show"';} else { echo 'class="st-table hide"';}?>>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="payment_mode"><?php esc_html_e( 'Payment Gateways', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <?php
								foreach ( $available_pay_methods as $key => $val ) {
									?>
									<input type="checkbox" <?php if(is_array( $payment_methods)) { if ( in_array( $key, $payment_methods)) { ?>checked="checked"<?php }} ?> name="simontaxi_settings[payment_methods][]" value="<?php echo esc_attr( $key); ?>"><?php esc_html_e( $val, 'simontaxi' ); ?><br>
									<?php
								}
								?>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="payment_mode"><?php esc_html_e( 'Default Payment Gateway', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[default_payment_method]">
								<?php
								foreach ( $available_pay_methods as $key => $val ) {
									?>
									<option value="<?php echo $key; ?>" <?php if( $default_payment_method == $key ) echo ' selected'; ?>><?php echo $val; ?></option>
									<?php
								}
								?>
								</select>
                            </td>
                        </tr>

						<?php $booking_status_payment_success = simontaxi_get_option( 'booking_status_payment_success', 'new' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="booking_status_payment_success"><?php esc_html_e( 'Booking status when payment success', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[booking_status_payment_success]">
									<option value="new" <?php if( $booking_status_payment_success == 'new' ) echo ' selected'; ?>><?php esc_html_e( 'New', 'simontaxi' ); ?></option>
									<option value="confirmed" <?php if( $booking_status_payment_success == 'confirmed' ) echo ' selected'; ?>><?php esc_html_e( 'Confirmed', 'simontaxi' ); ?></option>
									<option value="success" <?php if( $booking_status_payment_success == 'success' ) echo ' selected'; ?>><?php esc_html_e( 'Success', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>

						<?php $confirmed_vehicle_status = simontaxi_get_option( 'confirmed_vehicle_status', 'confirmed' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="confirmed_vehicle_status"><?php esc_html_e( 'Confirmed Vehicle Status', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[confirmed_vehicle_status]">
									<option value="new" <?php if( $confirmed_vehicle_status == 'new' ) echo ' selected'; ?>><?php esc_html_e( 'New', 'simontaxi' ); ?></option>
									<option value="confirmed" <?php if( $confirmed_vehicle_status == 'confirmed' ) echo ' selected'; ?>><?php esc_html_e( 'Confirmed', 'simontaxi' ); ?></option>
									<option value="success" <?php if( $confirmed_vehicle_status == 'success' ) echo ' selected'; ?>><?php esc_html_e( 'Success', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						
						<?php do_action( 'simontaxi_additional_payment_general' ); ?>

						</table>

						<tr><td colspan="2" >
						<?php
						$paypal = simontaxi_get_option( 'paypal' );
						$paypal_mode = ( isset ( $paypal['mode'] )) ? $paypal['mode'] : 'sandbox';
						?>
						<table <?php if ( $section == 'paypal' ) { echo 'class="st-table show"';} else { echo 'class="st-table hide"';}?>>
						<tr><td colspan="2" style="border-bottom:1px dashed #e6e6e6; text-align:right;"><?php esc_html_e( 'Paypal Settings', 'simontaxi' ); ?></td></tr>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="paypal_mode"><?php esc_html_e( 'Paypal Mode', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="paypal_mode" name="simontaxi_settings[paypal][mode]" title="payment Method" style="width: 25em;">
                                    <option value="sandbox" <?php if ( $paypal_mode == 'sandbox' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sandbox', 'simontaxi' ); ?></option>
                                    <option value="live" <?php if ( $paypal_mode == 'live' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Live', 'simontaxi' ); ?></option>
                                </select>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="paypal_email"><?php esc_html_e( 'Paypal Email', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="paypal_email" value="<?php
                                if ( isset ( $paypal['email'] )) {
                                    echo esc_attr( $paypal['email'] );
                                }
                                ?>" name="simontaxi_settings[paypal][email]" title="<?php esc_html_e( 'Paypal Email', 'simontaxi' ); ?>" placeholder="<?php esc_html_e( 'Paypal Email', 'simontaxi' ); ?>" style="width: 25em;">
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="paypal_title"><?php esc_html_e( 'Title', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="paypal_title" value="<?php
                                if ( isset ( $paypal['title'] )) {
                                    echo esc_attr( $paypal['title'] );
                                }
                                ?>" name="simontaxi_settings[paypal][title]" title="<?php esc_html_e( 'Title', 'simontaxi' ); ?>" placeholder="<?php esc_html_e( 'Title', 'simontaxi' ); ?>" style="width: 25em;">
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="paypal_description"><?php esc_html_e( 'Description', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <textarea id="paypal_description" name="simontaxi_settings[paypal][description]" title="<?php esc_html_e( 'Description', 'simontaxi' ); ?>" rows="4" cols="40"><?php
                                if ( isset ( $paypal['description'] )) {
                                    echo esc_attr( $paypal['description'] );
                                }
                                ?></textarea>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="paypal_logo"><?php esc_html_e( 'Logo', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="paypal_logo" name="simontaxi_settings[paypal][logo]" title="<?php esc_html_e( 'Logo', 'simontaxi' ); ?>" style="width: 25em;" onclick="open_media_uploader_image(this.id)" readonly>&nbsp;
								<input type="hidden" name="simontaxi_settings[paypal][logo_remove]" id="paypal_logo_remove" value="no">
								<?php echo simontaxi_get_help( 'This will display at front end while user selecting payment method.' )?>
								<?php if ( isset( $paypal['logo'] ) && $paypal['logo'] != '' ) { ?><img src="<?php echo $paypal['logo']; ?>" width="50" height="50" title="<?php esc_html_e( 'Logo', 'simontaxi' ); ?>" alt="<?php esc_html_e( 'Logo', 'simontaxi' ); ?>" id="paypal_logo_image">
								&nbsp;<a href="javascript:void(0);" onclick="remove_image( 'paypal_logo' )"><span class="icon-close" id="paypal_logo_link"><?php esc_html_e( 'Remove', 'simontaxi' ); ?></span></a>
								<?php } ?>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="header_logo"><?php esc_html_e( 'Header Logo', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="header_logo" name="simontaxi_settings[paypal][header_logo]" title="<?php esc_html_e( 'Logo', 'simontaxi' ); ?>" style="width: 25em;" onclick="open_media_uploader_image(this.id)" readonly>&nbsp;

								<input type="hidden" name="simontaxi_settings[paypal][header_logo_remove]" id="header_logo_remove" value="no">

								<?php echo simontaxi_get_help( 'This will display at paypal screen while user paying.' )?>
								<?php if ( isset( $paypal['header_logo'] ) && $paypal['header_logo'] != '' ) { ?><img src="<?php echo $paypal['header_logo']; ?>" width="50" height="50" title="<?php esc_html_e( 'Header Logo', 'simontaxi' ); ?>" alt="<?php esc_html_e( 'Header Logo', 'simontaxi' ); ?>" id="header_logo_image">

								&nbsp;<a href="javascript:void(0);" onclick="remove_image( 'header_logo' )"><span class="icon-close" id="header_logo_link"><?php esc_html_e( 'Remove', 'simontaxi' ); ?></span></a>
								<?php } ?>
                            </td>
                        </tr>
						
						<?php do_action( 'simontaxi_additional_payment_paypal' ); ?>

						</table>
						</td></tr>

						<tr><td colspan="2" >
						<?php
						$payu = simontaxi_get_option( 'payu' );
						$payu_mode = ( isset ( $payu['mode'] )) ? $payu['mode'] : 'sandbox';
						?>
						<table  <?php if ( $section == 'payu' ) { echo 'class="st-table show"';} else { echo 'class="st-table hide"';}?>>
						<tr><td colspan="2" style="border-bottom:1px dashed #e6e6e6;text-align:right;"><?php esc_html_e( 'Payu Settings', 'simontaxi' ); ?></td></tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="payu_mode"><?php esc_html_e( 'PayU Mode', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="payu_mode" name="simontaxi_settings[payu][mode]" title="<?php esc_html_e( 'PayU Mode', 'simontaxi' ); ?>" style="width: 25em;">
                                    <option value="sandbox" <?php if ( $payu_mode == 'sandbox' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sandbox', 'simontaxi' ); ?></option>
                                    <option value="live" <?php if ( $payu_mode == 'live' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Live', 'simontaxi' ); ?></option>
                                </select>&nbsp;<?php echo simontaxi_get_help( '<br/><br/><u>Test Mode is <strong>ACTIVE</strong>, use following Credit Card details:-</u><br/>'."\n"
									 .'Test Card Name: <strong><em>any name</em></strong><br/>'."\n"
									 .'Test Card Number: <strong>5123 4567 8901 2346</strong><br/>'."\n"
									 .'Test Card CVV: <strong>123</strong><br/>'."\n"
									 .'Test Card Expiry: <strong>May 2017</strong>' ); ?>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="payu_merchant_key_live"><?php esc_html_e( 'Merchant Key (Live)', 'simontaxi' ); ?> </label>
                            </th>
                            <td>
                                <input type="text" id="payu_merchant_key_live" value="<?php
                                if ( isset ( $payu['merchant_key_live'] )) {
                                    echo esc_attr( $payu['merchant_key_live'] );
                                }
                                ?>" name="simontaxi_settings[payu][merchant_key_live]" title="<?php esc_html_e( 'Merchant Key', 'simontaxi' ); ?>" placeholder="<?php esc_html_e( 'Merchant Key (Live)', 'simontaxi' ); ?>" style="width: 25em;"><?php echo simontaxi_get_help( 'Manage Account ⇒ My Account ⇒ Merchant -Key Salt' ); ?>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="payu_salt_live"><?php esc_html_e( 'Salt (Live)', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="payu_salt_live" value="<?php
                                if ( isset ( $payu['salt_live'] )) {
                                    echo esc_attr( $payu['salt_live'] );
                                }
                                ?>" name="simontaxi_settings[payu][salt_live]" title="<?php esc_html_e( 'ALT (For live)', 'simontaxi' ); ?>" placeholder="<?php esc_html_e( 'Salt (Live)', 'simontaxi' ); ?>" style="width: 25em;"><?php echo simontaxi_get_help( 'Manage Account ⇒ My Account ⇒ Merchant -Key Salt' ); ?>
                            </td>
                        </tr>

						<!--Sandbox-->
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="payu_merchant_key_sandbox"><?php esc_html_e( 'Merchant Key (Sandbox)', 'simontaxi' ); ?> </label>
                            </th>
                            <td>
                                <input type="text" id="payu_merchant_key_sandbox" value="<?php
                                if ( isset ( $payu['merchant_key_sandbox'] )) {
                                    echo esc_attr( $payu['merchant_key_sandbox'] );
                                }
                                ?>" name="simontaxi_settings[payu][merchant_key_sandbox]" title="<?php esc_html_e( 'Merchant Key (Sandbox)', 'simontaxi' ); ?>" placeholder="<?php esc_html_e( 'Merchant Key (Sandbox)', 'simontaxi' ); ?>" style="width: 25em;"><?php echo simontaxi_get_help( 'Manage Account ⇒ My Account ⇒ Merchant -Key Salt' ); ?>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="payu_salt_sandbox"><?php esc_html_e( 'Salt (Sandbox)', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="payu_salt_sandbox" value="<?php
                                if ( isset ( $payu['salt_sandbox'] )) {
                                    echo esc_attr( $payu['salt_sandbox'] );
                                }
                                ?>" name="simontaxi_settings[payu][salt_sandbox]" title="<?php esc_html_e( 'Salt (Sandbox)', 'simontaxi' ); ?>" placeholder="<?php esc_html_e( 'Salt (Sandbox)', 'simontaxi' ); ?>" style="width: 25em;"><?php echo simontaxi_get_help( 'Manage Account ⇒ My Account ⇒ Merchant -Key Salt' ); ?>
                            </td>
                        </tr>

						<?php $payu_service_provider = ( isset ( $payu['payu_service_provider'] )) ? $payu['payu_service_provider'] : 'money'; ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="payu_service_provider"><?php esc_html_e( 'Service Provider', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select id="payu_service_provider" name="simontaxi_settings[payu][payu_service_provider]" title="<?php esc_html_e( 'Service Provider', 'simontaxi' ); ?>" placeholder="<?php esc_html_e( 'Service Provider', 'simontaxi' ); ?>" style="width: 25em;">
									<option value="money" <?php if ( $payu_service_provider == 'money' ) echo 'selected'; ?>><?php esc_html_e( 'PayUmoney', 'simontaxi' ); ?></option>
									<option value="biz" <?php if ( $payu_service_provider == 'biz' ) echo 'selected'; ?>><?php esc_html_e( 'PayUbiz', 'simontaxi' ); ?></option>
									<option value="payu_paisa" <?php if ( $payu_service_provider == 'payu_paisa' ) echo 'selected'; ?>><?php esc_html_e( 'PayU Paisa', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="payu_title"><?php esc_html_e( 'Title', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="payu_title" value="<?php
                                if ( isset ( $payu['title'] )) {
                                    echo esc_attr( $payu['title'] );
                                }
                                ?>" name="simontaxi_settings[payu][title]" title="<?php esc_html_e( 'Title', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'Title', 'simontaxi' ); ?>">
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="payu_description"><?php esc_html_e( 'Description', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <textarea id="payu_description" name="simontaxi_settings[payu][description]" title="<?php esc_html_e( 'Description', 'simontaxi' ); ?>" rows="4" cols="40" title="<?php esc_html_e( 'Description', 'simontaxi' ); ?>" placeholder="<?php esc_html_e( 'Description', 'simontaxi' ); ?>"><?php
                                if ( isset ( $payu['description'] )) {
                                    echo esc_attr( $payu['description'] );
                                }
                                ?></textarea>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="payu_logo"><?php esc_html_e( 'Logo', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="payu_logo" name="simontaxi_settings[payu][logo]" title="<?php esc_html_e( 'Logo', 'simontaxi' ); ?>" placeholder="<?php esc_html_e( 'Logo', 'simontaxi' ); ?>" style="width: 25em;" onclick="open_media_uploader_image(this.id)" readonly>&nbsp;

								<input type="hidden" name="simontaxi_settings[payu][logo_remove]" id="payu_logo_remove" value="no">

								<?php if ( isset( $payu['logo'] ) && $payu['logo'] != '' ) { ?><img src="<?php echo $payu['logo']; ?>" width="50" height="50" title="<?php esc_html_e( 'Logo', 'simontaxi' ); ?>" alt="<?php esc_html_e( 'Logo', 'simontaxi' ); ?>" id="payu_logo_image">
								&nbsp;<a href="javascript:void(0);" onclick="remove_image( 'payu_logo' )"><span class="icon-close" id="payu_logo_link"><?php esc_html_e( 'Remove', 'simontaxi' ); ?></span></a>
								<?php } ?>
                            </td>
                        </tr>
						
						<?php do_action( 'simontaxi_additional_payment_payu' ); ?>
						</table>
						</td>
						</tr>

						<!--By Hand-->
						<tr><td colspan="2" >
						<?php
						$byhand = simontaxi_get_option( 'byhand' );
						?>
						<table  <?php if ( $section == 'byhand' ) { echo 'class="st-table show"';} else { echo 'class="st-table hide"';}?>>
						<tr><td colspan="2" style="border-bottom:1px dashed #e6e6e6;text-align:right;"><?php esc_html_e( 'By Hand Settings', 'simontaxi' ); ?></td></tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="byhand_title"><?php esc_html_e( 'Title', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="byhand_title" value="<?php
                                if ( isset ( $byhand['title'] )) {
                                    echo esc_attr( $byhand['title'] );
                                }
                                ?>" name="simontaxi_settings[byhand][title]" title="<?php esc_html_e( 'Title', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'Title', 'simontaxi' ); ?>">
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="byhand_description"><?php esc_html_e( 'Description', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <textarea id="byhand_description" name="simontaxi_settings[byhand][description]" title="<?php esc_html_e( 'Description', 'simontaxi' ); ?>" placeholder="<?php esc_html_e( 'Description', 'simontaxi' ); ?>" rows="4" cols="40"><?php
                                if ( isset ( $byhand['description'] )) {
                                    echo esc_attr( $byhand['description'] );
                                }
                                ?></textarea>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="byhand_instructions"><?php esc_html_e( 'Instructions', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <textarea id="byhand_instructions" name="simontaxi_settings[byhand][instructions]" title="<?php esc_html_e( 'Instructions', 'simontaxi' ); ?>" placeholder="<?php esc_html_e( 'Instructions', 'simontaxi' ); ?>" rows="4" cols="40"><?php
                                if ( isset ( $byhand['instructions'] )) {
                                    echo esc_attr( $byhand['instructions'] );
                                }
                                ?></textarea>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="byhand_logo"><?php esc_html_e( 'Logo', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="byhand_logo" name="simontaxi_settings[byhand][logo]" title="<?php esc_html_e( 'Logo', 'simontaxi' ); ?>" style="width: 25em;" onclick="open_media_uploader_image(this.id)" readonly>&nbsp;

								<input type="hidden" name="simontaxi_settings[byhand][byhand_logo_remove]" id="byhand_logo_remove" value="no">

								<?php if ( isset( $byhand['logo'] ) && $byhand['logo'] != '' ) { ?><img src="<?php echo $byhand['logo']; ?>" width="50" height="50" title="<?php esc_html_e( 'Logo', 'simontaxi' ); ?>" alt="<?php esc_html_e( 'Logo', 'simontaxi' ); ?>" id="byhand_logo_image">

								&nbsp;<a href="javascript:void(0);" onclick="remove_image( 'byhand_logo' )"><span class="icon-close" id="byhand_logo_link"><?php esc_html_e( 'Remove', 'simontaxi' ); ?></span></a>

								<?php } ?>
                            </td>
                        </tr>
						
						<?php do_action( 'simontaxi_additional_payment_byhand' ); ?>

						</table>
						</td>
						</tr>
						
						<?php do_action( 'simontaxi_payment_form' ); ?>

						</tbody>
						</table>
					</div>

					<!-- Surcharges Tab -->
					<div id="st-surcharges" class="tab-pane fade in" style="display:<?php if( $tab === 'surcharges' ) { echo 'block'; } else { echo 'none'; } ?>">
						<table class="st-table" >
						<tbody>
						<?php do_action( 'simontaxi_surcharges_fields_before' ); ?>

						<?php $peak_time_surcharge = simontaxi_get_option( 'peak_time_surcharge', 0);
						$peak_time_surcharge_type = simontaxi_get_option( 'peak_time_surcharge_type', 'no' );
						?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="peak_time_surcharge"><?php esc_html_e( 'Peak time surcharge', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="number" id="peak_time_surcharge" value="<?php
                                if ( isset ( $peak_time_surcharge)) {
                                    echo $peak_time_surcharge;
                                }
                                ?>" name="simontaxi_settings[peak_time_surcharge]" title="<?php esc_html_e( 'Peak time charge', 'simontaxi' )?>" placeholder="<?php esc_html_e( 'Peak time charge', 'simontaxi' )?>" style="width: 25em;">&nbsp;
								<select name="simontaxi_settings[peak_time_surcharge_type]">
									<option value="percent" <?php if(( isset ( $peak_time_surcharge_type) && $peak_time_surcharge_type == 'percent' )) echo 'selected'; ?>><?php esc_html_e( 'Percent %', 'simontaxi' )?></option>
									<option value="value" <?php if(( isset ( $peak_time_surcharge_type) && $peak_time_surcharge_type == 'value' )) echo 'selected'; ?>><?php esc_html_e( 'Value', 'simontaxi' )?></option>
								</select>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="peak_time"><?php esc_html_e( 'Peak time', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <?php $peak_time_from = simontaxi_get_option( 'peak_time_from', 1); ?>
								<?php esc_html_e( 'From : ', 'simontaxi' )?>
								<select id="peak_time_from" name="simontaxi_settings[peak_time_from]" title="<?php esc_html_e( 'Peak time', 'simontaxi' ); ?>" style="width: 10%;">
									<?php
									for( $h = 1; $h < 24; $h++) {
										$val = str_pad( $h,2,0, STR_PAD_LEFT);
										$display_val = simontaxi_get_time_display_format( $h );
										?>
										<option value="<?php echo esc_attr( $val ); ?>" <?php if ( isset ( $peak_time_from) && $peak_time_from == $val) { echo 'selected'; }?>><?php esc_html_e( $display_val, 'simontaxi' )?></option>
										<?php
									}
									?>
								</select>
								<?php $peak_time_from_minutes = simontaxi_get_option( 'peak_time_from_minutes', 0); ?>
								<select id="peak_time_from_minutes" name="simontaxi_settings[peak_time_from_minutes]" title="<?php esc_html_e( 'Peak time', 'simontaxi' ); ?>" style="width: 10%;">
									<?php
									for( $h = 0; $h < 60; $h++) {
										$val = str_pad( $h,2,0, STR_PAD_LEFT);
										?>
										<option value="<?php echo esc_attr( $val ); ?>" <?php if ( isset ( $peak_time_from_minutes) && $peak_time_from_minutes == $val) { echo 'selected'; }?>><?php echo $val . esc_html__( ' Mins', 'simontaxi' )?></option>
										<?php
									}
									?>
								</select>

								<?php $peak_time_to = simontaxi_get_option( 'peak_time_to', 6); ?>
								<?php esc_html_e( 'To : ', 'simontaxi' )?> <select id="peak_time_to" name="simontaxi_settings[peak_time_to]" title="<?php esc_html_e( 'Peak time', 'simontaxi' ); ?>" style="width: 10%;">
									<?php
									for( $h = 1; $h < 24; $h++) {
										$val = str_pad( $h,2,0, STR_PAD_LEFT);
										$display_val = simontaxi_get_time_display_format( $h );
										?>
										<option value="<?php echo esc_attr( $val ); ?>" <?php if ( isset ( $peak_time_to) && $peak_time_to == $val) { echo 'selected'; }?>><?php esc_html_e( $display_val, 'simontaxi' )?></option>
										<?php
									}
									?>
								</select>
								<?php $peak_time_to_minutes = simontaxi_get_option( 'peak_time_to_minutes', 0); ?>
								<select id="peak_time_to_minutes" name="simontaxi_settings[peak_time_to_minutes]" title="<?php esc_html_e( 'Peak time', 'simontaxi' ); ?>" style="width: 10%;">
									<?php
									for( $h = 0; $h < 60; $h++) {
										$val = str_pad( $h,2,0, STR_PAD_LEFT);
										?>
										<option value="<?php echo esc_attr( $val); ?>" <?php if ( isset ( $peak_time_to_minutes) && $peak_time_to_minutes == $val) { echo 'selected'; }?>><?php echo $val . esc_html__( ' Mins', 'simontaxi' )?></option>
										<?php
									}
									?>
								</select>
                            </td>
                        </tr>
						
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="peak_time_apply"><?php esc_html_e( 'Peak time apply', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <?php $peak_time_apply_from = simontaxi_get_option( 'peak_time_apply_from', 5); 
								$week_days = simontaxi_week_days();
								?>
								<?php esc_html_e( 'From : ', 'simontaxi' )?>
								<select id="peak_time_apply_from" name="simontaxi_settings[peak_time_apply_from]" title="<?php esc_html_e( 'Peak time', 'simontaxi' ); ?>" style="width: 10%;">
									<?php
									foreach( $week_days as $index => $day ) {
										?>
										<option value="<?php echo esc_attr( $index ); ?>" <?php if ( isset ( $peak_time_apply_from) && $peak_time_apply_from == $index) { echo 'selected'; }?>><?php esc_html_e( $day, 'simontaxi' )?></option>
										<?php
									}
									?>
								</select>
								
								<?php $peak_time_apply_to = simontaxi_get_option( 'peak_time_apply_to', 1); ?>
								<?php esc_html_e( 'To : ', 'simontaxi' )?> <select id="peak_time_apply_to" name="simontaxi_settings[peak_time_apply_to]" title="<?php esc_html_e( 'Peak time', 'simontaxi' ); ?>" style="width: 10%;">
									<?php
									foreach( $week_days as $index => $day ) {
										?>
										<option value="<?php echo esc_attr( $index ); ?>" <?php if ( isset ( $peak_time_apply_to ) && $peak_time_apply_to == $index) { echo 'selected'; }?>><?php esc_html_e( $day, 'simontaxi' )?></option>
										<?php
									}
									?>
								</select>
                            </td>
                        </tr>
						
						<?php do_action( 'simontaxi_additional_peak_times' ); ?>
						<?php $airport_surcharge = simontaxi_get_option( 'airport_surcharge', '0' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="airport_surcharge"><?php esc_html_e( $fixed_point_title . ' Charge', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="number" id="airport_surcharge" value="<?php
                                if ( isset ( $airport_surcharge)) {
                                    echo $airport_surcharge;
                                }
                                ?>" name="simontaxi_settings[airport_surcharge]" title="<?php esc_html_e( $fixed_point_title, 'simontaxi' )?>" placeholder="<?php esc_html_e( $fixed_point_title, 'simontaxi' )?>" style="width: 25em;" min="0">&nbsp;
								<select name="simontaxi_settings[airport_surcharge_type]">
									<option value="percent" <?php if(( isset ( $airport_surcharge_type) && $airport_surcharge_type == 'percent' )) echo 'selected'; ?>><?php esc_html_e( 'Percent %', 'simontaxi' )?></option>
									<option value="value" <?php if(( isset ( $airport_surcharge_type) && $airport_surcharge_type == 'value' )) echo 'selected'; ?>><?php esc_html_e( 'Value', 'simontaxi' )?></option>
								</select>
                            </td>
                        </tr>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="additionalpoints_surcharge"><?php esc_html_e( 'Additional pick up / drop off points', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="number" id="additionalpoints_surcharge" value="<?php if ( isset ( $additionalpoints_surcharge)) {
                                    echo $additionalpoints_surcharge;
                                }?>" name="simontaxi_settings[additionalpoints_surcharge]" title="<?php esc_html_e( 'Additional pick up / drop off points', 'simontaxi' )?>" placeholder="<?php esc_html_e( 'Additional pick up / drop off points', 'simontaxi' )?>" style="width: 25em;" min="0"> /<?php esc_html_e( 'each additional pick up / drop-off', 'simontaxi' )?>
                            </td>
                        </tr>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="waitingtime_surcharge"><?php esc_html_e( 'Waiting time', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="number" id="waitingtime_surcharge" value="<?php if ( isset ( $waitingtime_surcharge)) {
                                    echo $waitingtime_surcharge;
                                }?>" name="simontaxi_settings[waitingtime_surcharge]" title="<?php esc_html_e( 'Waiting time', 'simontaxi' )?>" placeholder="<?php echo esc_html__( 'Waiting time charge ', 'simontaxi' ) . simontaxi_get_currency() . esc_html__( ' / Hour', 'simontaxi' ); ?>" style="width: 25em;" min="0"> <?php echo simontaxi_get_currency() . esc_html__( ' / Hour', 'simontaxi' ); ?>
                            </td>
                        </tr>
						
						<?php do_action( 'simontaxi_additional_charges_settings' ); ?>


						</tbody>
						</table>
					</div>

					<!-- Email Settings Tab -->
					<div id="st-emailsettings" class="tab-pane fade in" style="display:<?php if( $tab === 'emailsettings' ) { echo 'block'; } else { echo 'none'; } ?>">
						<table class="st-table" >
						<tbody>

						<?php do_action( 'simontaxi_emailsettings_fields_before' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_from_name"><?php esc_html_e( '"From" Name', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="vehicle_from_name" value="<?php
                                if ( isset ( $vehicle_from_name)) {
                                    echo $vehicle_from_name;
                                }
                                ?>" name="simontaxi_settings[vehicle_from_name]" title="<?php esc_html_e( 'From Name', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'From Name', 'simontaxi' ); ?>">
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_from_address"><?php esc_html_e( '"From" Email Address', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="vehicle_from_address" value="<?php
                                if ( isset ( $vehicle_from_address)) {
                                    echo $vehicle_from_address;
                                }
                                ?>" name="simontaxi_settings[vehicle_from_address]" title="<?php esc_html_e( 'From Address', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'From Address', 'simontaxi' ); ?>">
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_payment_queries"><?php esc_html_e( 'Contact Email for user payment queries !', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="vehicle_payment_queries" value="<?php
                                if ( isset ( $vehicle_payment_queries)) {
                                    echo $vehicle_payment_queries;
                                }
                                ?>" name="simontaxi_settings[vehicle_payment_queries]" title="<?php esc_html_e( 'Contact Email for user payment queries !', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'Contact Email for user payment queries !', 'simontaxi' ); ?>">
                            </td>
                        </tr>

						<?php $vehicle_bookings_admin_email = simontaxi_get_option( 'vehicle_bookings_admin_email', get_option( 'admin_email' )); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_bookings_admin_email"><?php esc_html_e( 'Bookings Admin Email', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="vehicle_bookings_admin_email" value="<?php
                                if ( isset ( $vehicle_bookings_admin_email)) {
                                    echo $vehicle_bookings_admin_email;
                                }
                                ?>" name="simontaxi_settings[vehicle_bookings_admin_email]" title="<?php esc_html_e( 'Bookings Admin Email', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'Bookings Admin Email', 'simontaxi' ); ?>">
                            </td>
                        </tr>
						
						<?php 
						if ( simontaxi_is_sms_gateway_active() ) {
						$append_country_code_to_mobile = simontaxi_get_option( 'append_country_code_to_mobile', 'yes' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="append_country_code_to_mobile"><?php esc_html_e( 'Append Country Code To Mobile', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[append_country_code_to_mobile]" id="append_country_code_to_mobile" title="<?php esc_html_e( 'Append Country Code To Mobile', 'simontaxi' ); ?>">
									<option value="yes" <?php if ( $append_country_code_to_mobile == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if ( $append_country_code_to_mobile == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						<?php } ?>
						
						<tr valign="top"><td colspan="2"><h3><?php esc_html_e( 'New User Registration', 'simontaxi' ); ?></h3></td></tr>
						<?php $vehicle_booking_userregistration_from_name = simontaxi_get_option( 'vehicle_booking_userregistration_from_name', get_option( 'blogname' )); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_userregistration_from_name"><?php esc_html_e( '"From" Name', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="vehicle_booking_userregistration_from_name" value="<?php
                                if ( isset ( $vehicle_booking_userregistration_from_name)) {
                                    echo $vehicle_booking_userregistration_from_name;
                                }
                                ?>" name="simontaxi_settings[vehicle_booking_userregistration_from_name]" title="<?php esc_html_e( 'From Name', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'From Name', 'simontaxi' ); ?>">
                            </td>
                        </tr>
						
						<?php $vehicle_booking_userregistration_from_address = simontaxi_get_option( 'vehicle_booking_userregistration_from_address', get_option( 'admin_email' )); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_userregistration_from_address"><?php esc_html_e( '"From" Email Address', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="vehicle_booking_userregistration_from_address" value="<?php
                                if ( isset ( $vehicle_booking_userregistration_from_address)) {
                                    echo $vehicle_booking_userregistration_from_address;
                                }
                                ?>" name="simontaxi_settings[vehicle_booking_userregistration_from_address]" title="<?php esc_html_e( 'From Address', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'From Address', 'simontaxi' ); ?>">
                            </td>
                        </tr>
						
						<?php $vehicle_booking_userregistration_email_subject = simontaxi_get_option( 'vehicle_booking_userregistration_email_subject', 'New User Registration' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_userregistration_email_subject"><?php esc_html_e( 'Subject', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="vehicle_booking_userregistration_email_subject" value="<?php
                                if ( isset ( $vehicle_booking_userregistration_email_subject)) {
                                    echo $vehicle_booking_userregistration_email_subject;
                                }
                                ?>" name="simontaxi_settings[vehicle_booking_userregistration_email_subject]" title="<?php esc_html_e( 'Subject', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'Subject', 'simontaxi' ); ?>">
                            </td>
                        </tr>
						
						<?php $vehicle_booking_userregistration_email_body = simontaxi_get_option( 'vehicle_booking_userregistration_email_body', 'emailtemplate' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_userregistration_email_body"><?php esc_html_e( 'Mail Content?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_booking_userregistration_email_body]" id="vehicle_booking_userregistration_email_body" title="<?php esc_html_e( 'Mail Content?', 'simontaxi' ); ?>">
									<option value="emailtemplate" <?php if ( $vehicle_booking_userregistration_email_body == 'emailtemplate' ) { echo 'selected'; } ?>><?php esc_html_e( 'Email Template (Post)', 'simontaxi' ); ?></option>
									<option value="file" <?php if ( $vehicle_booking_userregistration_email_body == 'file' ) { echo 'selected'; } ?>><?php esc_html_e( 'File', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>

						<tr valign="top"><td colspan="2"><h3><?php esc_html_e( 'Bookings Success', 'simontaxi' ); ?></h3></td></tr>
						<?php $vehicle_booking_success_email_user = simontaxi_get_option( 'vehicle_booking_success_email_user', 'yes' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_success_email_user"><?php esc_html_e( 'Send Email To User?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_booking_success_email_user]" id="vehicle_booking_success_email_user" title="<?php esc_html_e( 'Send Email?', 'simontaxi' ); ?>">
									<option value="yes" <?php if ( $vehicle_booking_success_email_user == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if ( $vehicle_booking_success_email_user == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						<?php
						if ( simontaxi_is_sms_gateway_active() ) {
						$vehicle_booking_success_sms_user = simontaxi_get_option( 'vehicle_booking_success_sms_user', 'yes' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_success_sms_user"><?php esc_html_e( 'Send SMS To User?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_booking_success_sms_user]" id="vehicle_booking_success_sms_user" title="<?php esc_html_e( 'Send SMS?', 'simontaxi' ); ?>">
									<option value="yes" <?php if ( $vehicle_booking_success_sms_user == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if ( $vehicle_booking_success_sms_user == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						
						<?php $vehicle_booking_success_sms_body = simontaxi_get_option( 'vehicle_booking_success_sms_user', 'smstemplate' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_success_sms_body"><?php esc_html_e( 'SMS Body?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_booking_success_sms_body]" id="vehicle_booking_success_sms_body" title="<?php esc_html_e( 'SMS Body?', 'simontaxi' ); ?>">
									<option value="smstemplate" <?php if ( $vehicle_booking_success_sms_body == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'SMS Template (Post)', 'simontaxi' ); ?></option>
									<option value="file" <?php if ( $vehicle_booking_success_sms_body == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'File', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						<?php } ?>

						<!-- Admin -->
						<?php $vehicle_booking_success_email_admin = simontaxi_get_option( 'vehicle_booking_success_email_admin', 'yes' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_success_email_admin"><?php esc_html_e( 'Send Email To Admin?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_booking_success_email_admin]" id="vehicle_booking_success_email_admin" title="<?php esc_html_e( 'Send Email?', 'simontaxi' ); ?>">
									<option value="yes" <?php if ( $vehicle_booking_success_email_admin == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if ( $vehicle_booking_success_email_admin == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						<?php
						if ( simontaxi_is_sms_gateway_active() ) {
						$vehicle_booking_success_sms_admin = simontaxi_get_option( 'vehicle_booking_success_sms_admin', 'yes' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_success_sms_admin"><?php esc_html_e( 'Send SMS To Admin?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_booking_success_sms_admin]" id="vehicle_booking_success_sms_admin" title="<?php esc_html_e( 'Send SMS?', 'simontaxi' ); ?>">
									<option value="yes" <?php if ( $vehicle_booking_success_sms_admin == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if ( $vehicle_booking_success_sms_admin == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						<?php } ?>

						<?php $vehicle_booking_success_from_name = simontaxi_get_option( 'vehicle_booking_success_from_name', get_option( 'blogname' )); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_success_from_name"><?php esc_html_e( '"From" Name', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="vehicle_booking_success_from_name" value="<?php
                                if ( isset ( $vehicle_booking_success_from_name)) {
                                    echo $vehicle_booking_success_from_name;
                                }
                                ?>" name="simontaxi_settings[vehicle_booking_success_from_name]" title="<?php esc_html_e( 'From Name', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'From Name', 'simontaxi' ); ?>">
                            </td>
                        </tr>

						<?php $vehicle_booking_success_from_address = simontaxi_get_option( 'vehicle_booking_success_from_address', get_option( 'admin_email' )); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_success_from_address"><?php esc_html_e( '"From" Email Address', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="vehicle_booking_success_from_address" value="<?php
                                if ( isset ( $vehicle_booking_success_from_address)) {
                                    echo $vehicle_booking_success_from_address;
                                }
                                ?>" name="simontaxi_settings[vehicle_booking_success_from_address]" title="<?php esc_html_e( 'From Address', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'From Address', 'simontaxi' ); ?>">
                            </td>
                        </tr>

						<?php $vehicle_booking_success_email_subject = simontaxi_get_option( 'vehicle_booking_success_email_subject', 'Booking Success' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_success_email_subject"><?php esc_html_e( 'Subject', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="vehicle_booking_success_email_subject" value="<?php
                                if ( isset ( $vehicle_booking_success_email_subject)) {
                                    echo $vehicle_booking_success_email_subject;
                                }
                                ?>" name="simontaxi_settings[vehicle_booking_success_email_subject]" title="<?php esc_html_e( 'Subject', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'Subject', 'simontaxi' ); ?>">
                            </td>
                        </tr>

						<?php $vehicle_booking_success_email_type = simontaxi_get_option( 'vehicle_booking_success_email_type', 'html' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_success_email_type"><?php esc_html_e( 'Email Type', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_booking_success_email_type]" id="vehicle_booking_success_email_type" title="<?php esc_html_e( 'Email Type', 'simontaxi' ); ?>">
									<option value="html" <?php if ( $vehicle_booking_success_email_type == 'html' ) { echo 'selected'; } ?>><?php esc_html_e( 'HTML', 'simontaxi' ); ?></option>
									<option value="plain" <?php if ( $vehicle_booking_success_email_type == 'plain' ) { echo 'selected'; } ?>><?php esc_html_e( 'Plain text', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						
						<?php $vehicle_booking_success_email_body = simontaxi_get_option( 'vehicle_booking_success_email_body', 'emailtemplate' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_success_email_body"><?php esc_html_e( 'Mail Content?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_booking_success_email_body]" id="vehicle_booking_success_email_body" title="<?php esc_html_e( 'Mail Content?', 'simontaxi' ); ?>">
									<option value="emailtemplate" <?php if ( $vehicle_booking_success_email_body == 'emailtemplate' ) { echo 'selected'; } ?>><?php esc_html_e( 'Email Template (Post)', 'simontaxi' ); ?></option>
									<option value="file" <?php if ( $vehicle_booking_success_email_body == 'file' ) { echo 'selected'; } ?>><?php esc_html_e( 'File', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>

						<tr valign="top"><td colspan="2"><h3><?php esc_html_e( 'Bookings Cancel', 'simontaxi' ); ?></h3></td></tr>
						<!-- User -->
						<?php $vehicle_bookings_cancel_email_user = simontaxi_get_option( 'vehicle_bookings_cancel_email_user', 'yes' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_bookings_cancel_email_user"><?php esc_html_e( 'Send Email To User?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_bookings_cancel_email_user]" id="vehicle_bookings_cancel_email_user" title="<?php esc_html_e( 'Bookings Cancel', 'simontaxi' ); ?>">
									<option value="yes" <?php if ( $vehicle_bookings_cancel_email_user == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if ( $vehicle_bookings_cancel_email_user == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						<?php
						if ( simontaxi_is_sms_gateway_active() ) {
						$vehicle_booking_cancel_sms_user = simontaxi_get_option( 'vehicle_booking_cancel_sms_user', 'no' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_cancel_sms_user"><?php esc_html_e( 'Send SMS To User?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_booking_cancel_sms_user]" id="vehicle_booking_cancel_sms_user" title="<?php esc_html_e( 'Send SMS', 'simontaxi' ); ?>">
									<option value="yes" <?php if ( $vehicle_booking_cancel_sms_user == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if ( $vehicle_booking_cancel_sms_user == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						
						<?php $vehicle_booking_cancel_sms_body = simontaxi_get_option( 'vehicle_booking_cancel_sms_body', 'smstemplate' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_cancel_sms_body"><?php esc_html_e( 'SMS Body?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_booking_cancel_sms_body]" id="vehicle_booking_cancel_sms_body" title="<?php esc_html_e( 'SMS Body?', 'simontaxi' ); ?>">
									<option value="smstemplate" <?php if ( $vehicle_booking_cancel_sms_body == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'SMS Template (Post)', 'simontaxi' ); ?></option>
									<option value="file" <?php if ( $vehicle_booking_cancel_sms_body == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'File', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						<?php } ?>

						<!-- Admin -->
						<?php $vehicle_bookings_cancel_email_admin = simontaxi_get_option( 'vehicle_bookings_cancel_email_admin', 'yes' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_bookings_cancel_email_admin"><?php esc_html_e( 'Send Email To Admin?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_bookings_cancel_email_admin]" id="vehicle_bookings_cancel_email_admin" title="<?php esc_html_e( 'Bookings Cancel', 'simontaxi' ); ?>">
									<option value="yes" <?php if ( $vehicle_bookings_cancel_email_admin == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if ( $vehicle_bookings_cancel_email_admin == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						<?php
						if ( simontaxi_is_sms_gateway_active() ) {
						$vehicle_bookings_cancel_sms_admin = simontaxi_get_option( 'vehicle_bookings_cancel_sms_admin', 'no' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_bookings_cancel_sms_admin"><?php esc_html_e( 'Send SMS To Admin?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_bookings_cancel_sms_admin]" id="vehicle_bookings_cancel_sms_admin" title="<?php esc_html_e( 'Send SMS', 'simontaxi' ); ?>">
									<option value="yes" <?php if ( $vehicle_bookings_cancel_sms_admin == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if ( $vehicle_bookings_cancel_sms_admin == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						<?php } ?>

						<?php $vehicle_bookings_cancel_from_name = simontaxi_get_option( 'vehicle_bookings_cancel_from_name', get_option( 'blogname' )); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_bookings_cancel_from_name"><?php esc_html_e( '"From" Name', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="vehicle_bookings_cancel_from_name" value="<?php
                                if ( isset ( $vehicle_bookings_cancel_from_name)) {
                                    echo $vehicle_bookings_cancel_from_name;
                                }
                                ?>" name="simontaxi_settings[vehicle_bookings_cancel_from_name]" title="<?php esc_html_e( 'From Name', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'From Name', 'simontaxi' ); ?>">
                            </td>
                        </tr>

						<?php $vehicle_booking_cancel_from_address = simontaxi_get_option( 'vehicle_booking_cancel_from_address', get_option( 'admin_email' )); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_cancel_from_address"><?php esc_html_e( '"From" Email Address', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="vehicle_booking_cancel_from_address" value="<?php
                                if ( isset ( $vehicle_booking_cancel_from_address)) {
                                    echo $vehicle_booking_cancel_from_address;
                                }
                                ?>" name="simontaxi_settings[vehicle_booking_cancel_from_address]" title="<?php esc_html_e( 'From Address', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'From Address', 'simontaxi' ); ?>">
                            </td>
                        </tr>

						<?php $vehicle_booking_cancel_email_subject = simontaxi_get_option( 'vehicle_booking_cancel_email_subject', 'Booking Cancelled' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_cancel_email_subject"><?php esc_html_e( 'Subject', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="vehicle_booking_cancel_email_subject" value="<?php
                                if ( isset ( $vehicle_booking_cancel_email_subject)) {
                                    echo $vehicle_booking_cancel_email_subject;
                                }
                                ?>" name="simontaxi_settings[vehicle_booking_cancel_email_subject]" title="<?php esc_html_e( 'Subject', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'Subject', 'simontaxi' ); ?>">
                            </td>
                        </tr>

						<?php $vehicle_booking_cancel_email_type = simontaxi_get_option( 'vehicle_booking_cancel_email_type', 'html' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_cancel_email_type"><?php esc_html_e( 'Email Type', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_booking_cancel_email_type]" id="vehicle_booking_cancel_email_type" title="<?php esc_html_e( 'Email Type', 'simontaxi' ); ?>">
									<option value="html" <?php if ( $vehicle_booking_cancel_email_type == 'html' ) { echo 'selected'; } ?>><?php esc_html_e( 'HTML', 'simontaxi' ); ?></option>
									<option value="plain" <?php if ( $vehicle_booking_cancel_email_type == 'plain' ) { echo 'selected'; } ?>><?php esc_html_e( 'Plain text', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						
						<?php $vehicle_booking_cancel_email_body = simontaxi_get_option( 'vehicle_booking_cancel_email_body', 'emailtemplate' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_cancel_email_body"><?php esc_html_e( 'Mail Content?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_booking_cancel_email_body]" id="vehicle_booking_cancel_email_body" title="<?php esc_html_e( 'Mail Content?', 'simontaxi' ); ?>">
									<option value="emailtemplate" <?php if ( $vehicle_booking_cancel_email_body == 'emailtemplate' ) { echo 'selected'; } ?>><?php esc_html_e( 'Email Template (Post)', 'simontaxi' ); ?></option>
									<option value="file" <?php if ( $vehicle_booking_cancel_email_body == 'file' ) { echo 'selected'; } ?>><?php esc_html_e( 'File', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>

						<!-- Booking Confirm (Its admin/executive operation) -->
						<tr valign="top"><td colspan="2"><h3><?php esc_html_e( 'Bookings Confirm (Admin/Executive)', 'simontaxi' ); ?></h3></td></tr>
						<?php $vehicle_booking_confirm_email_user = simontaxi_get_option( 'vehicle_booking_confirm_confirm_user', 'yes' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_confirm_email_user"><?php esc_html_e( 'Send Email To User?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_booking_confirm_email_user]" id="vehicle_booking_confirm_email_user" title="<?php esc_html_e( 'Send Email?', 'simontaxi' ); ?>">
									<option value="yes" <?php if ( $vehicle_booking_confirm_email_user == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if ( $vehicle_booking_confirm_email_user == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						<?php
						if ( simontaxi_is_sms_gateway_active() ) {
						$vehicle_booking_confirm_sms_user = simontaxi_get_option( 'vehicle_booking_confirm_sms_user', 'yes' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_confirm_sms_user"><?php esc_html_e( 'Send SMS To User?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_booking_confirm_sms_user]" id="vehicle_booking_confirm_sms_user" title="<?php esc_html_e( 'Send SMS?', 'simontaxi' ); ?>">
									<option value="yes" <?php if ( $vehicle_booking_confirm_sms_user == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if ( $vehicle_booking_confirm_sms_user == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						
						<?php $vehicle_booking_confirm_sms_body = simontaxi_get_option( 'vehicle_booking_confirm_sms_body', 'smstemplate' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_confirm_sms_body"><?php esc_html_e( 'SMS Body?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_booking_confirm_sms_body]" id="vehicle_booking_confirm_sms_body" title="<?php esc_html_e( 'SMS Body?', 'simontaxi' ); ?>">
									<option value="smstemplate" <?php if ( $vehicle_booking_confirm_sms_body == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'SMS Template (Post)', 'simontaxi' ); ?></option>
									<option value="file" <?php if ( $vehicle_booking_confirm_sms_body == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'File', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						<?php } ?>

						<!-- Admin -->
						<?php $vehicle_booking_confirm_email_admin = simontaxi_get_option( 'vehicle_booking_confirm_email_admin', 'yes' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_confirm_email_admin"><?php esc_html_e( 'Send Email To Admin?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_booking_confirm_email_admin]" id="vehicle_booking_confirm_email_admin" title="<?php esc_html_e( 'Send Email?', 'simontaxi' ); ?>">
									<option value="yes" <?php if ( $vehicle_booking_confirm_email_admin == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if ( $vehicle_booking_confirm_email_admin == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						<?php
						if ( simontaxi_is_sms_gateway_active() ) {
						$vehicle_booking_confirm_sms_admin = simontaxi_get_option( 'vehicle_booking_confirm_sms_admin', 'yes' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_confirm_sms_admin"><?php esc_html_e( 'Send SMS To Admin?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_booking_confirm_sms_admin]" id="vehicle_booking_confirm_sms_admin" title="<?php esc_html_e( 'Send SMS?', 'simontaxi' ); ?>">
									<option value="yes" <?php if ( $vehicle_booking_confirm_sms_admin == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if ( $vehicle_booking_confirm_sms_admin == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						<?php } ?>

						<?php $vehicle_booking_confirm_from_name = simontaxi_get_option( 'vehicle_booking_confirm_from_name', get_option( 'blogname' )); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_confirm_from_name"><?php esc_html_e( '"From" Name', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="vehicle_booking_confirm_from_name" value="<?php
                                if ( isset ( $vehicle_booking_confirm_from_name)) {
                                    echo $vehicle_booking_confirm_from_name;
                                }
                                ?>" name="simontaxi_settings[vehicle_booking_confirm_from_name]" title="<?php esc_html_e( 'From Name', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'From Name', 'simontaxi' ); ?>">
                            </td>
                        </tr>

						<?php $vehicle_booking_confirm_from_address = simontaxi_get_option( 'vehicle_booking_confirm_from_address', get_option( 'admin_email' )); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_confirm_from_address"><?php esc_html_e( '"From" Email Address', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="vehicle_booking_confirm_from_address" value="<?php
                                if ( isset ( $vehicle_booking_confirm_from_address)) {
                                    echo $vehicle_booking_confirm_from_address;
                                }
                                ?>" name="simontaxi_settings[vehicle_booking_confirm_from_address]" title="<?php esc_html_e( 'From Address', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'From Address', 'simontaxi' ); ?>">
                            </td>
                        </tr>

						<?php $vehicle_booking_confirm_email_subject = simontaxi_get_option( 'vehicle_booking_confirm_email_subject', 'Booking Confirmed' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_confirm_email_subject"><?php esc_html_e( 'Subject', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="vehicle_booking_confirm_email_subject" value="<?php
                                if ( isset ( $vehicle_booking_success_email_subject)) {
                                    echo $vehicle_booking_success_email_subject;
                                }
                                ?>" name="simontaxi_settings[vehicle_booking_confirm_email_subject]" title="<?php esc_html_e( 'Subject', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'Subject', 'simontaxi' ); ?>">
                            </td>
                        </tr>

						<?php $vehicle_booking_confirm_email_type = simontaxi_get_option( 'vehicle_booking_confirm_email_type', 'html' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_confirm_email_type"><?php esc_html_e( 'Email Type', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_booking_confirm_email_type]" id="vehicle_booking_confirm_email_type" title="<?php esc_html_e( 'Email Type', 'simontaxi' ); ?>">
									<option value="html" <?php if ( $vehicle_booking_confirm_email_type == 'html' ) { echo 'selected'; } ?>><?php esc_html_e( 'HTML', 'simontaxi' ); ?></option>
									<option value="plain" <?php if ( $vehicle_booking_confirm_email_type == 'plain' ) { echo 'selected'; } ?>><?php esc_html_e( 'Plain text', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						
						<?php $vehicle_booking_confirm_email_body = simontaxi_get_option( 'vehicle_booking_confirm_email_body', 'emailtemplate' ); ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_booking_confirm_email_body"><?php esc_html_e( 'Mail Content?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <select name="simontaxi_settings[vehicle_booking_confirm_email_body]" id="vehicle_booking_confirm_email_body" title="<?php esc_html_e( 'Mail Content?', 'simontaxi' ); ?>">
									<option value="emailtemplate" <?php if ( $vehicle_booking_confirm_email_body == 'emailtemplate' ) { echo 'selected'; } ?>><?php esc_html_e( 'Email Template (Post)', 'simontaxi' ); ?></option>
									<option value="file" <?php if ( $vehicle_booking_confirm_email_body == 'file' ) { echo 'selected'; } ?>><?php esc_html_e( 'File', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						
						<?php
						$template = 'booking/includes/pages/admin/email-settings.php';
						
						if ( simontaxi_is_template_customized( $template ) ) {
							include_once simontaxi_get_theme_template_dir_name() . $template;
						} else {
							include_once apply_filters( 'simontaxi_locate_sidebar_settings', SIMONTAXI_PLUGIN_PATH . $template );
						}
						?>
						
						<?php do_action( 'simontaxi_additional_email_settings' ); ?>

						</tbody>
						</table>
					</div>


					<!-- Billing settings Tab -->
					<div id="st-billingsettings" class="tab-pane fade in" style="display:<?php if( $tab === 'billingsettings' ) { echo 'block'; } else { echo 'none'; } ?>">
						<table class="st-table" >
						<tbody>
						
						<?php do_action( 'simontaxi_billingsettings_fields_before' ); ?>

                        <tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="show_invoice_to_user"><?php esc_html_e( 'Show Invoice to User?', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <?php $show_invoice_to_user = simontaxi_get_option( 'show_invoice_to_user', 'yes' ); ?>
								<select name="simontaxi_settings[show_invoice_to_user]" id="show_invoice_to_user" title="<?php esc_html_e( 'Show Invoice to User?', 'simontaxi' ); ?>">
									<option value="yes" <?php if ( $show_invoice_to_user == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if ( $show_invoice_to_user == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="user_address_for_invoice_header"><?php esc_html_e( 'User for invoice header', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <?php $user_address_for_invoice_header = simontaxi_get_option( 'user_address_for_invoice_header', 'yes' ); ?>
								<select name="simontaxi_settings[user_address_for_invoice_header]" id="user_address_for_invoice_header" title="<?php esc_html_e( 'User for invoice header', 'simontaxi' ); ?>">
									<option value="yes" <?php if ( $user_address_for_invoice_header == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
									<option value="no" <?php if ( $user_address_for_invoice_header == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
								</select>
                            </td>
                        </tr>
						
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="billing_logo"><?php esc_html_e( 'Company Logo', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <?php
								$loaders = simontaxi_get_option( 'loaders', array());
								$billing_logo = ( isset( $loaders['billing_logo'] ) && '' !== $loaders['billing_logo'] ) ? $loaders['billing_logo'] : '';
								?>
																
								<input type="text" id="billing_logo" name="simontaxi_settings[loaders][billing_logo]" title="<?php esc_html_e( 'Logo', 'simontaxi' ); ?>" style="width: 25em;" onclick="open_media_uploader_image(this.id)" readonly>&nbsp;
								<input type="hidden" name="simontaxi_settings[loaders][billing_logo_remove]" id="billing_logo_remove" value="no">
								<?php echo simontaxi_get_help( 'This will display at front end while user selecting payment method.' )?>
								<?php if ( isset( $billing_logo ) && $billing_logo != '' ) { ?><img src="<?php echo $billing_logo; ?>" width="50" height="50" title="<?php esc_html_e( 'Logo', 'simontaxi' ); ?>" alt="<?php esc_html_e( 'Logo', 'simontaxi' ); ?>" id="billing_logo_image">
								&nbsp;<a href="javascript:void(0);" onclick="remove_image( 'billing_logo' )"><span class="icon-close" id="billing_logo_link"><?php esc_html_e( 'Remove', 'simontaxi' ); ?></span></a>
								<?php } ?>
                            </td>
                        </tr>
						
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_billing_company"><?php esc_html_e( 'Company Name', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <?php
								$vehicle_billing_company = simontaxi_get_option( 'vehicle_billing_company', '' );
								?>
								<input type="text" id="vehicle_billing_company" value="<?php echo $vehicle_billing_company; ?>" name="simontaxi_settings[vehicle_billing_company]" title="<?php esc_attr_e( 'Company Name', 'simontaxi' ); ?>" style="width: 25em;">
                            </td>
                        </tr>
						
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_billing_address"><?php esc_html_e( 'Billing Address', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <textarea type="text" id="vehicle_billing_address" value="" name="simontaxi_settings[vehicle_billing_address]" title="Billing Address" style="width: 25em;"><?php
                                if ( isset ( $vehicle_billing_address)) {
                                    echo $vehicle_billing_address;
                                }
                                ?></textarea>
                            </td>
                        </tr>

						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_billing_phone"><?php esc_html_e( 'Billing Phone', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="vehicle_billing_phone" value="<?php
                                if ( isset ( $vehicle_billing_phone)) {
                                    echo $vehicle_billing_phone;
                                }
                                ?>" name="simontaxi_settings[vehicle_billing_phone]" title="Billing Phone" style="width: 25em;">
                            </td>
                        </tr>
						
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_billing_fax"><?php esc_html_e( 'Fax', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <?php
								$vehicle_billing_fax = simontaxi_get_option( 'vehicle_billing_fax', '' );
								?>
								<input type="text" id="vehicle_billing_fax" value="<?php echo $vehicle_billing_fax; ?>" name="simontaxi_settings[vehicle_billing_fax]" title="<?php esc_attr_e( 'Fax', 'simontaxi' ); ?>" style="width: 25em;">
                            </td>
                        </tr>
						
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_billing_mobile"><?php esc_html_e( 'Mobile', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <?php
								$vehicle_billing_mobile = simontaxi_get_option( 'vehicle_billing_mobile', '' );
								?>
								<input type="text" id="vehicle_billing_mobile" value="<?php echo $vehicle_billing_mobile; ?>" name="simontaxi_settings[vehicle_billing_mobile]" title="<?php esc_attr_e( 'Mobile', 'simontaxi' ); ?>" style="width: 25em;">
                            </td>
                        </tr>
						

                        <tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_billing_email"><?php esc_html_e( 'Billing Email', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <input type="text" id="vehicle_billing_email" value="<?php
                                if ( isset ( $vehicle_billing_email)) {
                                    echo $vehicle_billing_email;
                                }
                                ?>" name="simontaxi_settings[vehicle_billing_email]" title="Billing Email" style="width: 25em;">
                            </td>
                        </tr>
						
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="vehicle_billing_footer"><?php esc_html_e( 'Invoice Footer', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <?php
								$vehicle_billing_footer = simontaxi_get_option( 'vehicle_billing_footer', '' );
								?>
								<textarea type="text" id="vehicle_billing_footer" value="" name="simontaxi_settings[vehicle_billing_footer]" title="<?php esc_attr_e( 'Invoice Footer', 'simontaxi' ); ?>" style="width: 25em;"><?php echo $vehicle_billing_footer; ?></textarea>
                            </td>
                        </tr>

                        
						<?php do_action( 'simontaxi_billingsettings_fields_after' ); ?>
						</tbody>
						</table>
					</div>

					<!-- Permissions Tab -->
					<div id="st-permissions" class="tab-pane fade in" style="display:<?php if( $tab === 'permissions' ) { echo 'block'; } else { echo 'none'; } ?>">
						<table class="st-table" >
						<tbody>
						
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="update_db_fields"><?php esc_html_e( 'Update DB Fields', 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <a id="update-but" style="margin:10px;" class="button-primary" href="<?php echo esc_url( $url ) . '&tab=permissions&section=update_db_fields'; ?>"><?php esc_html_e( 'Update DB Fields', 'simontaxi' ); ?></a>
                            </td>
                        </tr>

						<?php do_action( 'simontaxi_permissions_fields_before' ); ?>
						
                        <?php foreach( apply_filters( 'simontaxi_available_roles', simontaxi_available_roles() ) as $role => $role_title ) : ?>
						<tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="permissions"><?php esc_html_e( $role_title, 'simontaxi' ); ?></label>
                            </th>
                            <td>
                                <?php
								$available_capabilities = simontaxi_available_capabilities();
								$assigned_caps = simontaxi_get_option( 'permissions', array());

								foreach ( $available_capabilities as $key => $val ) {
									?>
									<input id="<?php echo $role . '_' . $key; ?>" type="checkbox" <?php if( isset ( $assigned_caps[ $role ] ) && is_array( $assigned_caps[ $role ] )) { if ( in_array( $key, $assigned_caps[ $role ] )) { ?>checked="checked"<?php }} ?> name="simontaxi_settings[permissions][<?php echo $role; ?>][]" value="<?php echo esc_attr( $key); ?>" onclick="toggle_options( '<?php echo $role . '_' . $key; ?>');"><?php esc_html_e( $val, 'simontaxi' ); ?><br>

									<span id="<?php echo $role . '_' . $key; ?>_span" style="display:<?php if ( ! empty( $assigned_caps[ $role ][ $key ] ) ) { ?>block<?php } else { ?>none<?php } ?>;">
										<?php
										$view = ! empty( $assigned_caps[ $role ][ $key ]['view'] ) ? array( 'view' ) : array();
										$create = ! empty( $assigned_caps[ $role ][ $key ]['create'] ) ? array( 'create' ) : array();
										$edit = ! empty( $assigned_caps[ $role ][ $key ]['edit'] ) ? array( 'edit' ) : array();
										$delete = ! empty( $assigned_caps[ $role ][ $key ]['delete'] ) ? array( 'delete' ) : array();
										$publish = ! empty( $assigned_caps[ $role ][ $key ]['publish'] ) ? array( 'publish' ) : array();
										?>

										&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"<?php if( isset ( $assigned_caps[ $role ] ) && is_array( $assigned_caps[ $role ] )) { if ( in_array( 'edit', $edit)) { ?>checked="checked"<?php }} ?> name="simontaxi_settings[permissions][<?php echo $role; ?>][<?php echo $key; ?>][edit]" value="<?php echo esc_attr( $key ); ?>"><?php esc_html_e( 'Add & Edit', 'simontaxi' ); ?><br>
										&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"<?php if( isset ( $assigned_caps[ $role ] ) && is_array( $assigned_caps[ $role ] )) { if ( in_array( 'publish', $publish)) { ?>checked="checked"<?php }} ?> name="simontaxi_settings[permissions][<?php echo $role; ?>][<?php echo $key; ?>][publish]" value="<?php echo esc_attr( $key ); ?>"><?php esc_html_e( 'Publish', 'simontaxi' ); ?><br>
										&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"<?php if( isset ( $assigned_caps[ $role ] ) && is_array( $assigned_caps[ $role ] )) { if ( in_array( 'delete', $delete)) { ?>checked="checked"<?php }} ?> name="simontaxi_settings[permissions][<?php echo $role; ?>][<?php echo $key; ?>][delete]" value="<?php echo esc_attr( $key ); ?>"><?php esc_html_e( 'Delete', 'simontaxi' ); ?><br>
									</span>
									<?php
								}
								?>
                            </td>
                        </tr>
						<?php endforeach; ?>

						<?php do_action( 'simontaxi_permissions_fields_before' ); ?>
						
						</tbody>
						</table>
					</div>
					<?php do_action( 'simontaxi_settings_tab_content' ); ?>

               <center> <input type="submit" id="update-but" name="Submit" value="Update" style="margin:10px;" class="button-primary"/> 
			   <?php $ajax_loader = ( isset( $loaders['ajax_loader'] ) && '' !== $loaders['ajax_loader'] ) ? $loaders['ajax_loader'] : SIMONTAXI_PLUGIN_URL . '/images/preloader.gif'; ?>
			   <img src="<?php echo esc_url( $ajax_loader ); ?>" title="<?php esc_attr_e('Ajax Loader', 'simontaxi'); ?>" alt="<?php esc_attr_e('Ajax Loader', 'simontaxi'); ?>" id="update-but-loader" style="display:none;">
			   </center>
            </form>
        </div>

		<?php
		
		$template = 'booking/includes/pages/admin/settings-scripts.php';
		if ( simontaxi_is_template_customized( $template ) ) {
			require simontaxi_get_theme_template_dir_name() . $template;
		} else {
			require apply_filters( 'simontaxi_locate_settings_scripts', SIMONTAXI_PLUGIN_PATH . $template );
		}
		
		?>