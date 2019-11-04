<?php
/**
 * This template is used to display the login form with [vehicle_login]
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  vehicle_login
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;	
}

if ( ! is_user_logged_in() ) :

	$wp_error = new WP_Error();
	if ( isset( $_POST ) && ! empty( $_POST ) ) {
		$data = $_POST;
		if ( wp_verify_nonce( $data['simontaxi_register_nonce'], 'simontaxi-register-nonce' ) ) {
			
			do_action( 'simontaxi_registration_validation_start' );
			
			if ( $data['user_login'] == '' ) {
				simontaxi_set_error( 'user_login', esc_html__( 'Please enter Username', 'simontaxi' ) );
			} elseif ( ! validate_username( $data['user_login'] ) ) {
				simontaxi_set_error( 'user_login', esc_html__( 'Please enter valid username', 'simontaxi' ) );
			}
			
			if ( $data['user_email'] == '' ) {
				simontaxi_set_error( 'user_email', esc_html__( 'Please enter email address', 'simontaxi' ) );
			} elseif ( ! is_email( $data['user_email'] ) ) {
				simontaxi_set_error( 'user_email', esc_html__( 'Please enter valid email address', 'simontaxi' ) );
			}
			if ( $data['user_first_name'] == '' ) {
				simontaxi_set_error( 'user_first_name', esc_html__( 'Please enter first name', 'simontaxi' ) );
			} elseif ( ! preg_match("/^([a-zA-Z' ]+)$/", $data['user_first_name']) ) {
				simontaxi_set_error( 'user_first_name', esc_html__( 'Special characters not allowed for first name', 'simontaxi' ) );
			}
			if ( $data['user_last_name'] != '' && ! preg_match("/^([a-zA-Z' ]+)$/", $data['user_last_name']) ) {
				simontaxi_set_error( 'user_first_name', esc_html__( 'Special characters not allowed for first name', 'simontaxi' ) );
			}
			if ( $data['user_password'] == '' ) {
				simontaxi_set_error( 'user_password', esc_html__( 'Please enter password', 'simontaxi' ) );
			}
			if ( $data['user_password_confirm'] == '' ) {
				simontaxi_set_error( 'user_password_confirm', esc_html__( 'Please enter password again', 'simontaxi' ) );
			}
			if ( $data['user_password'] != '' && $data['user_password_confirm'] != '' ) {
				if ( $data['user_password'] != $data['user_password_confirm'] ) {
					simontaxi_set_error( 'not_match', esc_html__( 'Passwords do not match', 'simontaxi' ) );
				}
			}
			if ( $data['mobile_countrycode'] == '' ) {
				simontaxi_set_error( 'mobile_countrycode', esc_html__( 'Please select country code', 'simontaxi' ) );
			}
			if ( $data['mobile'] == '' ) {
				simontaxi_set_error( 'mobile', esc_html__( 'Please enter mobile number', 'simontaxi' ) );
			} elseif ( ! preg_match('/^[0-9]+$/', $data['mobile'] ) ) {
				simontaxi_set_error( 'mobile', esc_html__( 'Please enter valid mobile number', 'simontaxi' ) );
			}

			if ( $data['user_login'] != '' && $data['user_email'] != '' ) {
				$user_data_login = get_user_by( 'login', $data['user_login'] );
				$user_data_email = get_user_by( 'email', $data['user_email'] );
				if ( $user_data_login || $user_data_email ) {
					if ( $user_data_login ) {
						simontaxi_set_error( 'already_exists', esc_html__( 'Username already exists', 'simontaxi' ) );
					} elseif ( $user_data_email ) {
						simontaxi_set_error( 'already_exists', esc_html__( 'Email already exists', 'simontaxi' ) );
					}

				}
			}
			
			do_action( 'simontaxi_registration_validation_end' );
			
			// Check for errors and redirect if none present
			$errors = apply_filters( 'simontaxi_flt_registration_errors', simontaxi_get_errors() );

			if ( empty( $errors ) ) {

				$user_args = apply_filters( 'simontaxi_flt_registration_row', array(
					'user_login'      => isset( $data['user_login'] ) ? $data['user_login'] : '',
					'user_pass'       => isset( $data['user_password'] )  ? $data['user_password']  : '',
					'user_email'      => isset( $data['user_email'] ) ? $data['user_email'] : '',
					'first_name'      => isset( $data['user_first_name'] ) ? $data['user_first_name'] : '',
					'last_name'       => isset( $data['user_last_name'] )  ? $data['user_last_name']  : '',
					'user_nicename'		=> isset( $data['user_login'] ) ? $data['user_login'] : '',
					'display_name' 		=> $data['user_first_name'] . ' ' . $data['user_last_name'],
					'nickname' 			=> $data['user_login'],
					'user_registered'	=> date( 'Y-m-d H:i:s' ),
					'role'           	=> isset( $data['role'] ) ? $data['role'] : 'Customer',
					) );
				$user_id = wp_insert_user( $user_args );
				
				update_user_meta( absint( $user_id ), 'first_name', wp_kses_post( $_POST['user_first_name'] ) );
				update_user_meta( absint( $user_id ), 'last_name', wp_kses_post( $_POST['user_last_name'] ) );
				
				update_user_meta( absint( $user_id ), 'mobile_countrycode', wp_kses_post( $_POST['mobile_countrycode'] ) );
				update_user_meta( absint( $user_id ), 'mobile', wp_kses_post( $_POST['mobile'] ) );
				
				update_user_meta( absint( $user_id ), 'approval_code', wp_generate_password(8) );
				if ( ! empty( $data['approval'] ) && 'no' === $data['approval'] ) {
					update_user_meta( absint( $user_id ), 'approval_status', 'approved' );
				} else {
					update_user_meta( absint( $user_id ), 'approval_status', 'pending' );
				}
				
				do_action( 'simontaxi_registration_after_insert', $user_id );
				
				/**
				 * @since 2.0.7
				 */
				simontaxi_registration_email_alert( $user_id, $user_args['user_pass'] );

				$redirect = apply_filters( 'simontaxi_registration_redirect_registered', add_query_arg( array( 'action' => 'registered' ), simontaxi_get_bookingsteps_urls( 'login' ) ) );
				wp_safe_redirect( $redirect );
			}

		}
	}
?>
	<?php do_action( 'simontaxi_registration_top_before_form' ); 
	if ( ! empty( $a['top_description'] ) ) {
		echo '<h3>' . $a['top_description'] . '</h3>';
	}
	?>
	<form id="simontaxi_login_form" class="simontaxi_form-register" action="" method="post">
		<h3 class="widget-title"><?php esc_html_e( 'Register for this site', 'simontaxi' ); ?></h3>
		<fieldset>


			<?php echo simontaxi_print_errors() ?>
			<?php /*if ( ! empty( $wp_error->errors ) ) { ?>
				<div class="alert alert-danger">
					<ul><?php echo implode( '</li><li>', $wp_error->get_error_messages() ); ?></ul>
				</div>
			<?php } */?>
			
			<?php do_action( 'simontaxi_registration_form_top' ); ?>
			
			<div class="form-group col-sm-6">
				<label for="user_login"> <?php esc_html_e( 'Username', 'simontaxi' ); ?><?php echo simontaxi_required_field(); ?></label>
				<div class="inner-addon right-addon">
					<input name="user_login" id="user_login" class="required form-control" type="text" placeholder="<?php esc_html_e( 'Username', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $_POST, 'user_login' ); ?>"/>
				</div>
			</div>

			<div class="form-group col-sm-6">
				<label for="user_email"><?php esc_html_e( 'Email', 'simontaxi' ); ?><?php echo simontaxi_required_field(); ?></label>
				<div class="inner-addon right-addon">
					<input name="user_email" id="user_email" class="password required form-control" type="text" placeholder="<?php esc_html_e( 'Email', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $_POST, 'user_email' ); ?>"/>
				</div>
			</div>

			<div class="form-group col-sm-6">
				<label for="user_first_name"> <?php esc_html_e( 'First Name', 'simontaxi' ); ?><?php echo simontaxi_required_field(); ?></label>
				<div class="inner-addon right-addon">
					<input name="user_first_name" id="user_first_name" class="required form-control" type="text" placeholder="<?php esc_html_e( 'First Name', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $_POST, 'user_first_name' ); ?>"/>
				</div>
			</div>

			<div class="form-group col-sm-6">
				<label for="user_last_name"> <?php esc_html_e( 'Last Name', 'simontaxi' ); ?><?php echo simontaxi_required_field(); ?></label>
				<div class="inner-addon right-addon">
					<input name="user_last_name" id="user_last_name" class="required form-control" type="text" placeholder="<?php esc_html_e( 'Last Name', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $_POST, 'user_last_name' ); ?>"/>
				</div>
			</div>

			<div class="form-group col-sm-6">
				<label for="user_password"><?php esc_html_e( 'Password', 'simontaxi' ); ?><?php echo simontaxi_required_field(); ?></label>
				<div class="inner-addon right-addon">
					<input name="user_password" id="user_password" class="password required form-control" type="password" placeholder="<?php esc_html_e( 'Password', 'simontaxi' ); ?>"/>
				</div>
			</div>

			<div class="form-group col-sm-6">
				<label for="user_password_confirm"><?php esc_html_e( 'Confirm Password', 'simontaxi' ); ?><?php echo simontaxi_required_field(); ?></label>
				<div class="inner-addon right-addon">
					<input name="user_password_confirm" id="user_password_confirm" class="password required form-control" type="password" placeholder="<?php esc_html_e( 'Confirm Password', 'simontaxi' ); ?>"/>
				</div>
			</div>

			<?php $countryList = simontaxi_get_countries(); ?>
			<div class="form-group col-sm-6">
			<label for="mobile_countrycode"><?php esc_html_e( 'Country Code', 'simontaxi' ); ?><?php echo simontaxi_required_field(); ?></label>
			<div class="inner-addon right-addon">
			<select id="mobile_countrycode" name="mobile_countrycode" title="<?php esc_html_e( 'Country code', 'simontaxi' ); ?>"class="selectpicker show-tick show-menu-arrow">
			<option value=""><?php esc_html_e( 'Country Code', 'simontaxi' ); ?></option>
			<?php
			if ( $countryList) {
				$mobile_countrycode = simontaxi_get_value( $_POST, 'mobile_countrycode' );
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
			<label for="mobile"><?php esc_html_e( 'Mobile Phone', 'simontaxi' ); ?><?php echo simontaxi_required_field(); ?></label>
			<div class="inner-addon right-addon">
				<?php
				$mobile = simontaxi_get_value( $_POST, 'mobile' );
				?>
				<input type="text" class="form-control" id="mobile" name="mobile" placeholder="<?php esc_html_e( 'Phone number to receive SMS', 'simontaxi' ); ?>" value="<?php echo esc_attr( $mobile); ?>">
			</div>
		</div>
		<?php do_action( 'simontaxi_registration_form_bottom' ); ?>
            <div class="col-sm-12">
			<p class="simontaxi-login-submit">
				<input type="hidden" name="redirect_to" value="<?php echo simontaxi_get_bookingsteps_urls( 'user_bookings' ); ?>" />
				<input type="hidden" name="simontaxi_register_nonce" value="<?php echo wp_create_nonce( 'simontaxi-register-nonce' ); ?>"/>
				<input type="hidden" name="simontaxi_action" value="user_login"/>
				<input type="hidden" name="role" value="<?php echo ! empty( $a['role'] ) ? $a['role'] : 'Customer'; ?>"/>
				<input type="hidden" name="approval" value="<?php echo ! empty( $a['approval'] ) ? $a['approval'] : 'yes'; ?>"/>
				<input id="simontaxi_login_submit" type="submit" class="simontaxi_submit btn btn-primary" value="<?php esc_html_e( 'Register', 'simontaxi' ); ?>"/>
			</p>
            </div>
            <div class="form-group col-sm-12 st-login-tags">
				<a href="<?php echo simontaxi_get_bookingsteps_urls( 'login' ); ?>">
					<?php esc_html_e( 'Have account?', 'simontaxi' ); ?>
				</a>
			</div>
			<?php do_action( 'simontaxi_registration_bottom_after_button' ); ?>

		</fieldset>
	</form>
	<?php
	if ( ! empty( $a['bottom_description'] ) ) {
		echo '<h3>' . $a['bottom_description'] . '</h3>';
	}
	?>
<?php else : ?>
	<p class="simontaxi-logged-in"><?php esc_html_e( 'You are already logged in', 'simontaxi' ); ?></p>
<?php endif; ?>