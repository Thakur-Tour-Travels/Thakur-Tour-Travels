<?php
/**
 * This template is used to display the user account form with [simontaxi_user_account]
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  simontaxi_user_account
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

if ( isset( $_POST['update_password'] ) ) {
	$current_user = wp_get_current_user();
	$new_password = $_POST['new_password'];
	$repeat_new_password = $_POST['repeat_new_password'];
	if ( empty( $new_password) ) {
		$wp_error->add( 'new_password', esc_html__( 'Please enter new password', 'simontaxi' ) );
	}
	if ( empty( $repeat_new_password) ) {
		$wp_error->add( 'repeat_new_password', esc_html__( 'Please enter repeat new password', 'simontaxi' ) );
	}
	if ( $new_password != $repeat_new_password ) {
		$wp_error->add( 'new_password', esc_html__( 'The passwords you entered do not match. Please try again.', 'simontaxi' ) );
	}
	if ( empty( $wp_error->errors ) ) {
		$user_id = $current_user->ID;
		$user_login = $current_user->user_login;
		wp_set_password( $new_password, $user_id );
		$current_user = wp_signon( array(
			'user_login' => $user_login,
			'user_password' => $_POST['new_password'],
			) );
		wp_safe_redirect( add_query_arg( array( 
			'action' => 'password_changed',
			), simontaxi_get_bookingsteps_urls( 'user_account' ) ) );
	}
}
if ( isset( $_POST['update_account'] ) ) {
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	if ( empty( $first_name ) ) {
		$wp_error->add( 'first_name', esc_html__( 'Please enter first name', 'simontaxi' ) );
	}
	if ( empty( $last_name ) ) {
		$wp_error->add( 'last_name', esc_html__( 'Please enter last name', 'simontaxi' ) );
	}

	$mobile_countrycode = $_POST['mobile_countrycode'];
	$mobile = $_POST['mobile'];
	if ( empty( $mobile_countrycode ) ) {
		$wp_error->add( 'mobile_countrycode', esc_html__( 'Please select country code', 'simontaxi' ) );
	}
	if ( empty( $mobile ) ) {
		$wp_error->add( 'mobile', esc_html__( 'Please enter mobile number', 'simontaxi' ) );
	}
	
	do_action( 'simontaxi_registration_validation_start' );
	
	if ( empty( $wp_error->errors ) ) {
		$user_id = get_current_user_id();
		update_user_meta( absint( $user_id ), 'first_name', wp_kses_post( $_POST['first_name'] ) );
		update_user_meta( absint( $user_id ), 'last_name', wp_kses_post( $_POST['last_name'] ) );
		update_user_meta( absint( $user_id ), 'mobile_countrycode', wp_kses_post( $_POST['mobile_countrycode'] ) );
		update_user_meta( absint( $user_id ), 'mobile', wp_kses_post( $_POST['mobile'] ) );
		do_action( 'simontaxi_registration_after_insert', $user_id );
		$updated = true;
	}
}
$current_user = wp_get_current_user();
$user_meta = simontaxi_filter_gk( ( array ) get_user_meta( $current_user->ID ) );
?>
<!-- Booking Form -->

<div class="row">
	<div class="col-md-12">
		<?php if ( ! empty( $wp_error->errors ) ) { ?>
		<div class="alert alert-danger">
		<ul><?php echo implode( '</li><li>', $wp_error->get_error_messages() );?></ul>
		</div>
		<?php }
			if( $updated == true ) {
			?>
			<div class="alert alert-success"><?php esc_html_e( 'Updated successfully.', 'simontaxi' );?></div>
			<?php
			} elseif ( isset( $_GET['action'] ) ) {
				if ( $_GET['action'] == 'password_changed' ) {
					?>
					<div class="alert alert-success"><?php esc_html_e( 'Password Updated successfully.', 'simontaxi' );?></div>
					<?php
				} elseif ( $_GET['action'] == 'password_error' ) {
					?>
					<div class="alert alert-danger"><?php esc_html_e( 'Error while updating to new passowrd address. Please try again.', 'simontaxi' );?></div>
					<?php
				}
			}
		?>
		<?php
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

					<?php if ( isset( $_GET['action'] ) && in_array( $_GET['action'], array( 'change_password', 'password_error' ) ) ) {
						?>
						
						<form class="st-booking-form row" id="update_user_account" method="POST" action="">
							<div class="form-group col-sm-6">
								<label><?php esc_html_e( 'New Password', 'simontaxi' );?> <?php echo simontaxi_required_field();?></label>
								<div class="inner-addon right-addon">
									<input type="password" class="form-control" name="new_password" id="new_password" placeholder="<?php esc_html_e( 'New Password', 'simontaxi' );?>" value="">
								</div>
							</div>
							<div class="form-group col-sm-6">
								<label><?php esc_html_e( 'Repeat New Password', 'simontaxi' );?> <?php echo simontaxi_required_field();?></label>
								<div class="inner-addon right-addon">
									<input type="password" class="form-control" name="repeat_new_password" id="repeat_new_password" placeholder="<?php esc_html_e( 'Repeat New Password', 'simontaxi' );?>" value="">
								</div>
							</div>


							<div class="col-sm-12">
								<button type="submit" class="btn btn-primary btn-mobile" name="update_password"><?php esc_html_e( 'Update', 'simontaxi' );?></button>
							</div>

						</form>
						<?php
					} else { ?>
					<a href="<?php echo esc_url( add_query_arg(array( 'action' => 'change_password' ),simontaxi_get_bookingsteps_urls( 'user_account' ) ) ) ?>" class="btn btn-primary btn-support"><?php esc_html_e( 'Change Password', 'simontaxi' );?></a>
					<form class="st-booking-form row" id="update_user_account" method="POST" action="">

						<div class="form-group col-sm-6">
							<label for="first_name"><?php esc_html_e( 'First Name', 'simontaxi' );?></label>
							<div class="inner-addon right-addon">
								<input type="text" class="form-control" name="first_name" id="first_name" placeholder="<?php esc_html_e( 'First Name', 'simontaxi' );?>" value="<?php echo isset( $user_meta['first_name'] ) ? $user_meta['first_name'] : '';?>">
							</div>
						</div>
						<div class="form-group col-sm-6">
							<label><?php esc_html_e( 'Last Name', 'simontaxi' );?></label>
							<div class="inner-addon right-addon">
								<input type="text" class="form-control" name="last_name" id="last_name" placeholder="<?php esc_html_e( 'Last Name', 'simontaxi' );?>" value="<?php echo isset( $user_meta['last_name'] ) ? $user_meta['last_name'] : '';?>">
							</div>
						</div>

							<div class="form-group col-sm-6">
								<label><?php esc_html_e( 'Country code', 'simontaxi' );?></label>
								<div class="inner-addon right-addon">
								<?php
								$countryList = simontaxi_get_countries();
								?>
								<select id="mobile_countrycode" name="mobile_countrycode" title="<?php esc_html_e( 'Country code', 'simontaxi' );?>"class="selectpicker show-tick show-menu-arrow">
								<option value=""><?php esc_html_e( 'Country code', 'simontaxi' );?></option>
								<?php
								if ( $countryList) {
									$mobile_countrycode = '';
									if ( isset ( $user_meta['mobile_countrycode'] ) ) {
										$mobile_countrycode = $user_meta['mobile_countrycode'];
									}
									foreach ( $countryList as $result) {
										$code = $result->phonecode.'_'.$result->id_countries;
										?>
										<option value="<?php echo $code; ?>" <?php if ( $mobile_countrycode == $code) echo 'selected="selected"'; ?>><?php echo $result->name . ' ( '.$result->phonecode.' )'; ?> </option>
										<?php
									}
								}
								?>
								</select>
								</div>
							</div>
							<div class="form-group col-sm-6">
								<label><?php esc_html_e( 'Mobile phone', 'simontaxi' );?></label>
								<div class="inner-addon right-addon">
									<input type="text" class="form-control" name="mobile" placeholder="<?php esc_html_e( 'Phone number to receive SMS', 'simontaxi' );?>" value="<?php echo isset( $user_meta['mobile'] ) ? $user_meta['mobile'] : '';?>">
								</div>
							</div>
							<?php do_action( 'simontaxi_registration_form_bottom' ); ?>

						<div class="col-sm-12">
							<button type="submit" class="btn btn-primary btn-mobile" name="update_account"><?php esc_html_e( 'Save', 'simontaxi' );?></button>
						</div>

					</form>
					<?php } ?>
				</div>

			</div>
		</div>
	</div>

</div>
<!-- /Booking Form -->