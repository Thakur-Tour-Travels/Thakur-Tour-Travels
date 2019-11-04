<?php
/**
 * This template is used to display the user billing address form
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  billing address
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;	
}

/**
 * @global wpdb  $wpdb  WordPress database abstraction object.
 */
global $wpdb;
$wp_error = new WP_Error();
$updated = false;
if ( isset( $_POST['update_account'] ) ) {
	$first_name = $_POST['billing_firstname'];
	$last_name = $_POST['billing_lastname'];
	$billing_email = $_POST['billing_email'];
	if ( empty( $first_name ) ) {
		$wp_error->add( 'billing_firstname', esc_html__( 'Please enter first name', 'simontaxi' ) );
	}
	if ( empty( $last_name) ) {
		$wp_error->add( 'billing_lastname', esc_html__( 'Please enter last name', 'simontaxi' ) );
	}

	$mobile_countrycode = $_POST['billing_mobile_countrycode'];
	$mobile = $_POST['billing_mobile'];
	if ( empty( $mobile_countrycode ) ) {
		$wp_error->add( 'billing_mobile_countrycode', esc_html__( 'Please select country code', 'simontaxi' ) );
	}
	if ( empty( $mobile ) ) {
		$wp_error->add( 'billing_mobile', esc_html__( 'Please enter mobile number', 'simontaxi' ) );
	}
	if ( empty( $wp_error->errors ) ) {
		$user_id = get_current_user_id();
		update_user_meta( absint( $user_id ), 'billing_firstname', wp_kses_post( $_POST['billing_firstname'] ) );
		update_user_meta( absint( $user_id ), 'billing_lastname', wp_kses_post( $_POST['billing_lastname'] ) );
		update_user_meta( absint( $user_id ), 'billing_email', wp_kses_post( $_POST['billing_email'] ) );
		update_user_meta( absint( $user_id ), 'billing_mobile_countrycode', wp_kses_post( $_POST['billing_mobile_countrycode'] ) );
		update_user_meta( absint( $user_id ), 'billing_mobile', wp_kses_post( $_POST['billing_mobile'] ) );

		update_user_meta( absint( $user_id ), 'billing_companyname', wp_kses_post( $_POST['billing_companyname'] ) );
		
		update_user_meta( absint( $user_id ), 'billing_address', wp_kses_post( $_POST['billing_address'] ) );
		update_user_meta( absint( $user_id ), 'billing_city', wp_kses_post( $_POST['billing_city'] ) );
		update_user_meta( absint( $user_id ), 'billing_state', wp_kses_post( $_POST['billing_state'] ) );
		update_user_meta( absint( $user_id ), 'billing_country', wp_kses_post( $_POST['billing_country'] ) );
		update_user_meta( absint( $user_id ), 'billing_postelCode', wp_kses_post( $_POST['billing_postelCode'] ) );
		$updated = true;
	}
}
$current_user = wp_get_current_user();
$user_meta = simontaxi_filter_gk( ( array ) get_user_meta(  $current_user->ID ) );
//print_r( $user_meta);
?>
<!-- Booking Form -->
<?php if ( ! empty( $wp_error->errors ) ) { ?>
<div class="alert alert-danger">
<ul><?php echo implode( '</li><li>', $wp_error->get_error_messages() ); ?></ul>
</div>
<?php }
if( $updated == true ) {
?>
<div class="alert alert-success"><?php esc_html_e( 'Updated successfully.', 'simontaxi' ); ?></div>
<?php }
/**
 * @since 2.0.8
 */
$template = 'booking/includes/pages/user_left.php';
if ( simontaxi_is_template_customized( $template ) ) {
	include_once( simontaxi_get_theme_template_dir_name() . $template );
} else {
	include_once( apply_filters( 'simontaxi_locate_user_left', SIMONTAXI_PLUGIN_PATH . $template ) );
}
?>
<div class="st-booking-block st-admin-booking-block">
	<div class="tab-content">
		<!-- TAB-1 -->
		<div id="st-booktab1" class="tab-pane fade in active">
			<form class="st-booking-form row" id="update_user_account" method="POST" action="">
				<div class="form-group col-sm-6">
					<label for="billing_firstname"><?php esc_html_e( 'First Name', 'simontaxi' ); ?></label>
					<div class="inner-addon right-addon">
						<input type="text" class="form-control" name="billing_firstname" id="billing_firstname" placeholder="<?php esc_html_e( 'First Name', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $user_meta, 'billing_firstname', $current_user->user_nicename ); ?>">
					</div>
				</div>
				<div class="form-group col-sm-6">
					<label for="billing_lastname"><?php esc_html_e( 'Last Name', 'simontaxi' ); ?></label>
					<div class="inner-addon right-addon">
						<input type="text" class="form-control" name="billing_lastname" id="billing_lastname" placeholder="<?php esc_html_e( 'Last Name', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $user_meta, 'billing_lastname', $current_user->user_nicename ); ?>">
					</div>
				</div>
				<div class="form-group col-sm-6">
					<label for="billing_email"><?php esc_html_e( 'Email Address', 'simontaxi' ); ?></label>
					<div class="inner-addon right-addon">
						<input type="text" class="form-control" name="billing_email" id="billing_email" placeholder="<?php esc_html_e( 'Email Address', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $user_meta, 'billing_email', $current_user->user_email ); ?>">
					</div>
				</div>
				
				<div class="form-group col-sm-6">
					<label for="billing_companyname"><?php esc_html_e( 'Company Name', 'simontaxi' ); ?></label>
					<div class="inner-addon right-addon">
						<input type="text" class="form-control" name="billing_companyname" id="billing_companyname" placeholder="<?php esc_html_e( 'Company Name', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $user_meta, 'billing_companyname', '' ); ?>">
					</div>
				</div>
				<div class="form-group col-sm-6">
					<label for="billing_address"><?php esc_html_e( 'Address', 'simontaxi' ); ?></label>
					<div class="inner-addon right-addon">
						<input type="text" class="form-control" name="billing_address" id="billing_address" placeholder="<?php esc_html_e( 'Address', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $user_meta, 'billing_address', '' ); ?>">
					</div>
				</div>

					<div class="form-group col-sm-6">
						<label id="billing_mobile_countrycode"><?php esc_html_e( 'Country Code', 'simontaxi' ); ?></label>
						<div class="inner-addon right-addon">
						<?php
						$countryList = simontaxi_get_countries();
						?>
						<select id="billing_mobile_countrycode" name="billing_mobile_countrycode" title="<?php esc_html_e( 'Country Code', 'simontaxi' ); ?>"class="selectpicker show-tick show-menu-arrow">
						<option value=""><?php esc_html_e( 'Country Code', 'simontaxi' ); ?></option>
						<?php
						if ( $countryList ) {
							$mobile_countrycode = '';
							if ( isset( $user_meta['mobile_countrycode'] ) )
							$mobile_countrycode = simontaxi_get_value( $user_meta, 'mobile_countrycode', '' );
							foreach ( $countryList as $result ) {
								$code = $result->phonecode.'_' . $result->id_countries;
								?>
								<option value="<?php echo $code; ?>" <?php if ( $mobile_countrycode == $code) echo 'selected="selected"'; ?>><?php echo $result->name . ' ( ' . $result->phonecode . ' )'; ?> </option>
								<?php
							}
						}
						?>
						</select>
						</div>
					</div>
					<div class="form-group col-sm-6">
						<label for="billing_mobile"><?php esc_html_e( 'Mobile Phone', 'simontaxi' ); ?></label>
						<div class="inner-addon right-addon">
							<input type="text" class="form-control" name="billing_mobile" id="billing_mobile" placeholder="<?php esc_html_e( 'Mobile Number', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $user_meta, 'billing_mobile', '' ); ?>">
						</div>
					</div>

					<div class="form-group col-sm-6">
						<label for="billing_city"><?php esc_html_e( 'City', 'simontaxi' ); ?></label>
						<div class="inner-addon right-addon">
							<input type="text" class="form-control" name="billing_city" id="billing_city" placeholder="<?php esc_html_e( 'City', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $user_meta, 'billing_city', '' ); ?>">
						</div>
					</div>
					<div class="form-group col-sm-6">
						<label for="billing_state"><?php esc_html_e( 'State', 'simontaxi' ); ?></label>
						<div class="inner-addon right-addon">
							<input type="text" class="form-control" name="billing_state" id="billing_state" placeholder="<?php esc_html_e( 'State', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $user_meta, 'billing_state', '' ); ?>">
						</div>
					</div>
					<div class="form-group col-sm-6">
						<label for="billing_state"><?php esc_html_e( 'Country', 'simontaxi' ); ?></label>
						<div class="inner-addon right-addon">
							<input type="text" class="form-control" name="billing_country" id="billing_country" placeholder="<?php esc_html_e( 'Country', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $user_meta, 'billing_country', '' ); ?>">
						</div>
					</div>
					<div class="form-group col-sm-6">
						<label for="billing_postelCode"><?php esc_html_e( 'Postal Code', 'simontaxi' ); ?></label>
						<div class="inner-addon right-addon">
							<input type="text" class="form-control" name="billing_postelCode" id="billing_postelCode" placeholder="<?php esc_html_e( 'Postal Code', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $user_meta, 'billing_postelCode', '' ); ?>">
						</div>
					</div>

				<div class="col-sm-12">
					<button type="submit" class="btn btn-primary btn-mobile" name="update_account"><?php esc_html_e( 'Save', 'simontaxi' ); ?></button>
				</div>

			</form>
		</div>

	</div>
</div>
<!-- /Booking Form -->