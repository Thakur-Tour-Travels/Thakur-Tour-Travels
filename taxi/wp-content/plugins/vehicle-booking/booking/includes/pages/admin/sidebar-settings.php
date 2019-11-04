<?php
/**
 * Add admin menu for vehicle sidebar settings
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
?>
<tr valign="top"><td><h4><?php esc_html_e( 'Side Bar', 'simontaxi' )?></h4></td><th>&nbsp;</th></tr>						
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="sidebar_fare_details_top"><?php esc_html_e( 'Fare Details (Top)', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $sidebar_fare_details_top = simontaxi_get_option( 'sidebar_fare_details_top', 'yes' ); ?>
		<select id="sidebar_fare_details_top" name="simontaxi_settings[sidebar_fare_details_top]" title="<?php esc_html_e( 'Fare Details (Top)', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="yes" <?php if ( $sidebar_fare_details_top == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $sidebar_fare_details_top == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Fare Details (Top)' ); ?>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="sidebar_start_over"><?php esc_html_e( 'Start Over', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $sidebar_start_over = simontaxi_get_option( 'sidebar_start_over', 'yes' ); ?>
		<select id="sidebar_start_over" name="simontaxi_settings[sidebar_start_over]" title="<?php esc_html_e( 'Start Over', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="yes" <?php if ( $sidebar_start_over == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $sidebar_start_over == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Start Over' ); ?>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="sidebar_booking_reference"><?php esc_html_e( 'Show Booking Reference?', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $sidebar_booking_reference = simontaxi_get_option( 'sidebar_booking_reference', 'yes' ); ?>
		<select id="sidebar_booking_reference" name="simontaxi_settings[sidebar_booking_reference]" title="<?php esc_html_e( 'Show Booking Reference?', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="yes" <?php if ( isset ( $sidebar_booking_reference) && $sidebar_booking_reference == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( isset ( $sidebar_booking_reference) && $sidebar_booking_reference == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="sidebar_booking_type"><?php esc_html_e( 'Show Booking Type?', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $sidebar_booking_type = simontaxi_get_option( 'sidebar_booking_type', 'yes' ); ?>
		<select id="sidebar_booking_type" name="simontaxi_settings[sidebar_booking_type]" title="<?php esc_html_e( 'Show Booking Type?', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="yes" <?php if ( isset ( $sidebar_booking_type) && $sidebar_booking_type == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( isset ( $sidebar_booking_type) && $sidebar_booking_type == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="display_distance"><?php esc_html_e( 'Display distance for user', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $display_distance = simontaxi_get_option( 'display_distance', 'yes' ); ?>
		<select id="display_distance" name="simontaxi_settings[display_distance]" title="Places" style="width: 25em;">
			<option value="yes" <?php if ( $display_distance == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $display_distance == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'You can display / hide distance in front end. This is useful when admin dont want to show distance for the user' ); ?>
	</td>
</tr>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="display_arrival_on"><?php esc_html_e( 'Display arrival on', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $display_arrival_on = simontaxi_get_option( 'display_arrival_on', 'yes' ); ?>
		<select id="display_arrival_on" name="simontaxi_settings[display_arrival_on]" title="Places" style="width: 25em;">
			<option value="yes" <?php if ( $display_arrival_on == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $display_arrival_on == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'You can display / hide arrival on in front end. This is useful when admin want to show arrival on time for the user' ); ?>
	</td>
</tr>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="sidebar_vehicle_details"><?php esc_html_e( 'Vehicle Details', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $sidebar_vehicle_details = simontaxi_get_option( 'sidebar_booking_type', 'yes' ); ?>
		<select id="sidebar_vehicle_details" name="simontaxi_settings[sidebar_vehicle_details]" title="<?php esc_html_e( 'Vehicle Details', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="yes" <?php if ( $sidebar_vehicle_details == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $sidebar_vehicle_details == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Vehicle Details' ); ?>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="sidebar_vehicle_details_vehicle"><?php esc_html_e( 'Vehicle Details(Vehicle Name)', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $sidebar_vehicle_details_vehicle = simontaxi_get_option( 'sidebar_vehicle_details_vehicle', 'yes' ); ?>
		<select id="sidebar_vehicle_details_vehicle" name="simontaxi_settings[sidebar_vehicle_details_vehicle]" title="<?php esc_html_e( 'Vehicle Details(Vehicle Name)', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="yes" <?php if ( $sidebar_vehicle_details_vehicle == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $sidebar_vehicle_details_vehicle == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Vehicle Details(Vehicle Name)' ); ?>
	</td>
</tr>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="sidebar_vehicle_details_basic_distance"><?php esc_html_e( 'Vehicle Details(Basic Distance)', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $sidebar_vehicle_details_basic_distance = simontaxi_get_option( 'sidebar_vehicle_details_basic_distance', 'yes' ); ?>
		<select id="sidebar_vehicle_details_basic_distance" name="simontaxi_settings[sidebar_vehicle_details_basic_distance]" title="<?php esc_html_e( 'Vehicle Details(Basic Distance)', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="yes" <?php if ( $sidebar_vehicle_details_basic_distance == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $sidebar_vehicle_details_basic_distance == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Vehicle Details(Basic Distance)' ); ?>
	</td>
</tr>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="sidebar_vehicle_details_basic_price"><?php esc_html_e( 'Vehicle Details(Basic Price)', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $sidebar_vehicle_details_basic_price = simontaxi_get_option( 'sidebar_vehicle_details_basic_price', 'yes' ); ?>
		<select id="sidebar_vehicle_details_basic_price" name="simontaxi_settings[sidebar_vehicle_details_basic_price]" title="<?php esc_html_e( 'Vehicle Details(Basic Price)', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="yes" <?php if ( $sidebar_vehicle_details_basic_price == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $sidebar_vehicle_details_basic_price == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Vehicle Details(Basic Price)' ); ?>
	</td>
</tr>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="sidebar_vehicle_details_basic_unit_price"><?php esc_html_e( 'Vehicle Details(Basic Unit Price)', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $sidebar_vehicle_details_basic_unit_price = simontaxi_get_option( 'sidebar_vehicle_details_basic_unit_price', 'yes' ); ?>
		<select id="sidebar_vehicle_details_basic_unit_price" name="simontaxi_settings[sidebar_vehicle_details_basic_unit_price]" title="<?php esc_html_e( 'Vehicle Details(Basic Unit Price)', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="yes" <?php if ( $sidebar_vehicle_details_basic_unit_price == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $sidebar_vehicle_details_basic_unit_price == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Vehicle Details(Basic Unit Price)' ); ?>
	</td>
</tr>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="sidebar_fare_details"><?php esc_html_e( 'Fare Details', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $sidebar_fare_details = simontaxi_get_option( 'sidebar_fare_details', 'yes' ); ?>
		<select id="sidebar_fare_details" name="simontaxi_settings[sidebar_fare_details]" title="<?php esc_html_e( 'Fare Details', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="yes" <?php if ( $sidebar_fare_details == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $sidebar_fare_details == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Fare Details' ); ?>
	</td>
</tr>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="sidebar_fare_details_basic_amount"><?php esc_html_e( 'Fare Details (Basic Amount)', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $sidebar_fare_details_basic_amount = simontaxi_get_option( 'sidebar_fare_details_basic_amount', 'yes' ); ?>
		<select id="sidebar_fare_details_basic_amount" name="simontaxi_settings[sidebar_fare_details_basic_amount]" title="<?php esc_html_e( 'Fare Details (Basic Amount)', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="yes" <?php if ( $sidebar_fare_details_basic_amount == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $sidebar_fare_details_basic_amount == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Fare Details (Basic Amount)' ); ?>
	</td>
</tr>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="sidebar_fare_details_total_amount"><?php esc_html_e( 'Fare Details (Total Amount)', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $sidebar_fare_details_total_amount = simontaxi_get_option( 'sidebar_fare_details_total_amount', 'yes' ); ?>
		<select id="sidebar_fare_details_total_amount" name="simontaxi_settings[sidebar_fare_details_total_amount]" title="<?php esc_html_e( 'Fare Details (Total Amount)', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="yes" <?php if ( $sidebar_fare_details_total_amount == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $sidebar_fare_details_total_amount == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Fare Details (Total Amount)' ); ?>
	</td>
</tr>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="sidebar_fare_details_surcharges"><?php esc_html_e( 'Fare Details (Surcharges)', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $sidebar_fare_details_surcharges = simontaxi_get_option( 'sidebar_fare_details_surcharges', 'yes' ); ?>
		<select id="sidebar_fare_details_surcharges" name="simontaxi_settings[sidebar_fare_details_surcharges]" title="<?php esc_html_e( 'Fare Details (Surcharges)', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="yes" <?php if ( $sidebar_fare_details_surcharges == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $sidebar_fare_details_surcharges == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Fare Details (Surcharges)' ); ?>
	</td>
</tr>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="sidebar_fare_details_tax_amount"><?php esc_html_e( 'Fare Details (Tax Amount)', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $sidebar_fare_details_tax_amount = simontaxi_get_option( 'sidebar_fare_details_tax_amount', 'yes' ); ?>
		<select id="sidebar_fare_details_tax_amount" name="simontaxi_settings[sidebar_fare_details_tax_amount]" title="<?php esc_html_e( 'Fare Details (Tax Amount)', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="yes" <?php if ( $sidebar_fare_details_tax_amount == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $sidebar_fare_details_tax_amount == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Fare Details (Tax Amount)' ); ?>
	</td>
</tr>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="sidebar_fare_details_discount"><?php esc_html_e( 'Fare Details (Discount Amount)', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $sidebar_fare_details_discount = simontaxi_get_option( 'sidebar_fare_details_tax_amount', 'yes' ); ?>
		<select id="sidebar_fare_details_discount" name="simontaxi_settings[sidebar_fare_details_discount]" title="<?php esc_html_e( 'Fare Details (Discount Amount)', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="yes" <?php if ( $sidebar_fare_details_discount == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $sidebar_fare_details_discount == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Fare Details (Discount Amount)' ); ?>
	</td>
</tr>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="sidebar_personal_details"><?php esc_html_e( 'Personal Details', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $sidebar_personal_details = simontaxi_get_option( 'sidebar_personal_details', 'yes' ); ?>
		<select id="sidebar_personal_details" name="simontaxi_settings[sidebar_personal_details]" title="<?php esc_html_e( 'Personal Details', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="yes" <?php if ( $sidebar_personal_details == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $sidebar_personal_details == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Personal Details' ); ?>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="sidebar_number_of_persons"><?php esc_html_e( 'No. of Persons', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $sidebar_number_of_persons = simontaxi_get_option( 'sidebar_number_of_persons', 'no' ); ?>
		<select id="sidebar_number_of_persons" name="simontaxi_settings[sidebar_number_of_persons]" title="<?php esc_html_e( 'No. of Persons', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="yes" <?php if ( $sidebar_number_of_persons == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $sidebar_number_of_persons == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'No. of Persons' ); ?>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="sidebar_fare_details_bottom"><?php esc_html_e( 'Fare Details (Bottom)', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $sidebar_fare_details_bottom = simontaxi_get_option( 'sidebar_fare_details_bottom', 'yes' ); ?>
		<select id="sidebar_fare_details_bottom" name="simontaxi_settings[sidebar_fare_details_bottom]" title="<?php esc_html_e( 'Fare Details (Bottom)', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="yes" <?php if ( $sidebar_fare_details_bottom == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $sidebar_fare_details_bottom == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Fare Details (Bottom)' ); ?>
	</td>
</tr>