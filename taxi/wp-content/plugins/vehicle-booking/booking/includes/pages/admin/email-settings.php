<tr valign="top"><td colspan="2"><h3><?php esc_html_e( 'Start Ride', 'simontaxi' ); ?></h3></td></tr>
<!-- User -->
<?php $vehicle_bookings_startride_email_user = simontaxi_get_option( 'vehicle_bookings_startride_email_user', 'yes' ); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_bookings_startride_email_user"><?php esc_html_e( 'Send Email To User?', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select name="simontaxi_settings[vehicle_bookings_startride_email_user]" id="vehicle_bookings_startride_email_user" title="<?php esc_html_e( 'Start Ride', 'simontaxi' ); ?>">
			<option value="yes" <?php if ( $vehicle_bookings_startride_email_user == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $vehicle_bookings_startride_email_user == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>
<?php
if ( simontaxi_is_sms_gateway_active() ) {
$vehicle_booking_startride_sms_user = simontaxi_get_option( 'vehicle_booking_startride_sms_user', 'no' ); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_booking_startride_sms_user"><?php esc_html_e( 'Send SMS To User?', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select name="simontaxi_settings[vehicle_booking_startride_sms_user]" id="vehicle_booking_startride_sms_user" title="<?php esc_html_e( 'Send SMS', 'simontaxi' ); ?>">
			<option value="yes" <?php if ( $vehicle_booking_startride_sms_user == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $vehicle_booking_startride_sms_user == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>

<?php $vehicle_booking_startride_sms_body = simontaxi_get_option( 'vehicle_booking_startride_sms_body', 'smstemplate' ); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_booking_startride_sms_body"><?php esc_html_e( 'SMS Body?', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select name="simontaxi_settings[vehicle_booking_startride_sms_body]" id="vehicle_booking_startride_sms_body" title="<?php esc_html_e( 'SMS Body?', 'simontaxi' ); ?>">
			<option value="smstemplate" <?php if ( $vehicle_booking_startride_sms_body == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'SMS Template (Post)', 'simontaxi' ); ?></option>
			<option value="file" <?php if ( $vehicle_booking_startride_sms_body == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'File', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>
<?php } ?>

<!-- Admin -->
<?php $vehicle_bookings_startride_email_admin = simontaxi_get_option( 'vehicle_bookings_startride_email_admin', 'yes' ); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_bookings_startride_email_admin"><?php esc_html_e( 'Send Email To Admin?', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select name="simontaxi_settings[vehicle_bookings_startride_email_admin]" id="vehicle_bookings_startride_email_admin" title="<?php esc_html_e( 'Start Ride', 'simontaxi' ); ?>">
			<option value="yes" <?php if ( $vehicle_bookings_startride_email_admin == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $vehicle_bookings_startride_email_admin == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>
<?php
if ( simontaxi_is_sms_gateway_active() ) {
$vehicle_bookings_startride_sms_admin = simontaxi_get_option( 'vehicle_bookings_startride_sms_admin', 'no' ); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_bookings_startride_sms_admin"><?php esc_html_e( 'Send SMS To Admin?', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select name="simontaxi_settings[vehicle_bookings_startride_sms_admin]" id="vehicle_bookings_startride_sms_admin" title="<?php esc_html_e( 'Send SMS', 'simontaxi' ); ?>">
			<option value="yes" <?php if ( $vehicle_bookings_startride_sms_admin == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $vehicle_bookings_startride_sms_admin == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>
<?php } ?>

<?php $vehicle_bookings_startride_from_name = simontaxi_get_option( 'vehicle_bookings_startride_from_name', get_option( 'blogname' )); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_bookings_startride_from_name"><?php esc_html_e( '"From" Name', 'simontaxi' ); ?></label>
	</th>
	<td>
		<input type="text" id="vehicle_bookings_startride_from_name" value="<?php
		if ( isset ( $vehicle_bookings_startride_from_name)) {
			echo $vehicle_bookings_startride_from_name;
		}
		?>" name="simontaxi_settings[vehicle_bookings_startride_from_name]" title="<?php esc_html_e( 'From Name', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'From Name', 'simontaxi' ); ?>">
	</td>
</tr>

<?php $vehicle_booking_startride_from_address = simontaxi_get_option( 'vehicle_booking_startride_from_address', get_option( 'admin_email' )); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_booking_startride_from_address"><?php esc_html_e( '"From" Email Address', 'simontaxi' ); ?></label>
	</th>
	<td>
		<input type="text" id="vehicle_booking_startride_from_address" value="<?php
		if ( isset ( $vehicle_booking_startride_from_address)) {
			echo $vehicle_booking_startride_from_address;
		}
		?>" name="simontaxi_settings[vehicle_booking_startride_from_address]" title="<?php esc_html_e( 'From Address', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'From Address', 'simontaxi' ); ?>">
	</td>
</tr>

<?php $vehicle_booking_startride_email_subject = simontaxi_get_option( 'vehicle_booking_startride_email_subject', 'Your Ride Start Now' ); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_booking_startride_email_subject"><?php esc_html_e( 'Subject', 'simontaxi' ); ?></label>
	</th>
	<td>
		<input type="text" id="vehicle_booking_startride_email_subject" value="<?php
		if ( isset ( $vehicle_booking_startride_email_subject)) {
			echo $vehicle_booking_startride_email_subject;
		}
		?>" name="simontaxi_settings[vehicle_booking_startride_email_subject]" title="<?php esc_html_e( 'Subject', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'Subject', 'simontaxi' ); ?>">
	</td>
</tr>

<?php $vehicle_booking_startride_email_type = simontaxi_get_option( 'vehicle_booking_startride_email_type', 'html' ); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_booking_startride_email_type"><?php esc_html_e( 'Email Type', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select name="simontaxi_settings[vehicle_booking_startride_email_type]" id="vehicle_booking_startride_email_type" title="<?php esc_html_e( 'Email Type', 'simontaxi' ); ?>">
			<option value="html" <?php if ( $vehicle_booking_startride_email_type == 'html' ) { echo 'selected'; } ?>><?php esc_html_e( 'HTML', 'simontaxi' ); ?></option>
			<option value="plain" <?php if ( $vehicle_booking_startride_email_type == 'plain' ) { echo 'selected'; } ?>><?php esc_html_e( 'Plain text', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>

<?php $vehicle_booking_startride_email_body = simontaxi_get_option( 'vehicle_booking_startride_email_body', 'emailtemplate' ); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_booking_startride_email_body"><?php esc_html_e( 'Mail Content?', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select name="simontaxi_settings[vehicle_booking_startride_email_body]" id="vehicle_booking_startride_email_body" title="<?php esc_html_e( 'Mail Content?', 'simontaxi' ); ?>">
			<option value="emailtemplate" <?php if ( $vehicle_booking_startride_email_body == 'emailtemplate' ) { echo 'selected'; } ?>><?php esc_html_e( 'Email Template (Post)', 'simontaxi' ); ?></option>
			<option value="file" <?php if ( $vehicle_booking_startride_email_body == 'file' ) { echo 'selected'; } ?>><?php esc_html_e( 'File', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>

<tr valign="top"><td colspan="2"><h3><?php esc_html_e( 'Ride Completed', 'simontaxi' ); ?></h3></td></tr>
<!-- User -->
<?php $vehicle_bookings_completed_email_user = simontaxi_get_option( 'vehicle_bookings_completed_email_user', 'yes' ); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_bookings_completed_email_user"><?php esc_html_e( 'Send Email To User?', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select name="simontaxi_settings[vehicle_bookings_completed_email_user]" id="vehicle_bookings_completed_email_user" title="<?php esc_html_e( 'Ride Completed', 'simontaxi' ); ?>">
			<option value="yes" <?php if ( $vehicle_bookings_completed_email_user == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $vehicle_bookings_completed_email_user == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>
<?php
if ( simontaxi_is_sms_gateway_active() ) {
$vehicle_booking_completed_sms_user = simontaxi_get_option( 'vehicle_booking_completed_sms_user', 'no' ); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_booking_completed_sms_user"><?php esc_html_e( 'Send SMS To User?', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select name="simontaxi_settings[vehicle_booking_completed_sms_user]" id="vehicle_booking_completed_sms_user" title="<?php esc_html_e( 'Send SMS', 'simontaxi' ); ?>">
			<option value="yes" <?php if ( $vehicle_booking_completed_sms_user == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $vehicle_booking_completed_sms_user == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>

<?php $vehicle_booking_completed_sms_body = simontaxi_get_option( 'vehicle_booking_completed_sms_body', 'smstemplate' ); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_booking_completed_sms_body"><?php esc_html_e( 'SMS Body?', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select name="simontaxi_settings[vehicle_booking_completed_sms_body]" id="vehicle_booking_completed_sms_body" title="<?php esc_html_e( 'SMS Body?', 'simontaxi' ); ?>">
			<option value="smstemplate" <?php if ( $vehicle_booking_completed_sms_body == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'SMS Template (Post)', 'simontaxi' ); ?></option>
			<option value="file" <?php if ( $vehicle_booking_completed_sms_body == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'File', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>
<?php } ?>

<!-- Admin -->
<?php $vehicle_bookings_completed_email_admin = simontaxi_get_option( 'vehicle_bookings_completed_email_admin', 'yes' ); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_bookings_completed_email_admin"><?php esc_html_e( 'Send Email To Admin?', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select name="simontaxi_settings[vehicle_bookings_completed_email_admin]" id="vehicle_bookings_completed_email_admin" title="<?php esc_html_e( 'Bookings Cancel', 'simontaxi' ); ?>">
			<option value="yes" <?php if ( $vehicle_bookings_completed_email_admin == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $vehicle_bookings_completed_email_admin == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>
<?php
if ( simontaxi_is_sms_gateway_active() ) {
$vehicle_bookings_completed_sms_admin = simontaxi_get_option( 'vehicle_bookings_completed_sms_admin', 'no' ); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_bookings_completed_sms_admin"><?php esc_html_e( 'Send SMS To Admin?', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select name="simontaxi_settings[vehicle_bookings_completed_sms_admin]" id="vehicle_bookings_completed_sms_admin" title="<?php esc_html_e( 'Send SMS', 'simontaxi' ); ?>">
			<option value="yes" <?php if ( $vehicle_bookings_completed_sms_admin == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $vehicle_bookings_completed_sms_admin == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>
<?php } ?>

<?php $vehicle_bookings_completed_from_name = simontaxi_get_option( 'vehicle_bookings_completed_from_name', get_option( 'blogname' )); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_bookings_completed_from_name"><?php esc_html_e( '"From" Name', 'simontaxi' ); ?></label>
	</th>
	<td>
		<input type="text" id="vehicle_bookings_completed_from_name" value="<?php
		if ( isset ( $vehicle_bookings_completed_from_name)) {
			echo $vehicle_bookings_completed_from_name;
		}
		?>" name="simontaxi_settings[vehicle_bookings_completed_from_name]" title="<?php esc_html_e( 'From Name', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'From Name', 'simontaxi' ); ?>">
	</td>
</tr>

<?php $vehicle_booking_completed_from_address = simontaxi_get_option( 'vehicle_booking_completed_from_address', get_option( 'admin_email' )); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_booking_completed_from_address"><?php esc_html_e( '"From" Email Address', 'simontaxi' ); ?></label>
	</th>
	<td>
		<input type="text" id="vehicle_booking_completed_from_address" value="<?php
		if ( isset ( $vehicle_booking_completed_from_address)) {
			echo $vehicle_booking_completed_from_address;
		}
		?>" name="simontaxi_settings[vehicle_booking_completed_from_address]" title="<?php esc_html_e( 'From Address', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'From Address', 'simontaxi' ); ?>">
	</td>
</tr>

<?php $vehicle_booking_completed_email_subject = simontaxi_get_option( 'vehicle_booking_completed_email_subject', 'Congratulations Your Ride Completed' ); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_booking_completed_email_subject"><?php esc_html_e( 'Subject', 'simontaxi' ); ?></label>
	</th>
	<td>
		<input type="text" id="vehicle_booking_completed_email_subject" value="<?php
		if ( isset ( $vehicle_booking_completed_email_subject)) {
			echo $vehicle_booking_completed_email_subject;
		}
		?>" name="simontaxi_settings[vehicle_booking_completed_email_subject]" title="<?php esc_html_e( 'Subject', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'Subject', 'simontaxi' ); ?>">
	</td>
</tr>

<?php $vehicle_booking_completed_email_type = simontaxi_get_option( 'vehicle_booking_completed_email_type', 'html' ); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_booking_completed_email_type"><?php esc_html_e( 'Email Type', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select name="simontaxi_settings[vehicle_booking_completed_email_type]" id="vehicle_booking_completed_email_type" title="<?php esc_html_e( 'Email Type', 'simontaxi' ); ?>">
			<option value="html" <?php if ( $vehicle_booking_completed_email_type == 'html' ) { echo 'selected'; } ?>><?php esc_html_e( 'HTML', 'simontaxi' ); ?></option>
			<option value="plain" <?php if ( $vehicle_booking_completed_email_type == 'plain' ) { echo 'selected'; } ?>><?php esc_html_e( 'Plain text', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>

<?php $vehicle_booking_completed_email_body = simontaxi_get_option( 'vehicle_booking_completed_email_body', 'emailtemplate' ); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_booking_completed_email_body"><?php esc_html_e( 'Mail Content?', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select name="simontaxi_settings[vehicle_booking_completed_email_body]" id="vehicle_booking_completed_email_body" title="<?php esc_html_e( 'Mail Content?', 'simontaxi' ); ?>">
			<option value="emailtemplate" <?php if ( $vehicle_booking_completed_email_body == 'emailtemplate' ) { echo 'selected'; } ?>><?php esc_html_e( 'Email Template (Post)', 'simontaxi' ); ?></option>
			<option value="file" <?php if ( $vehicle_booking_completed_email_body == 'file' ) { echo 'selected'; } ?>><?php esc_html_e( 'File', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>