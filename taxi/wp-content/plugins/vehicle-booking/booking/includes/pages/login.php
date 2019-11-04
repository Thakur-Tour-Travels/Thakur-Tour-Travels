<?php
/**
 * This template is used to display the login form with [vehicle_login]
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  Login
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;	
}

if ( ! is_user_logged_in() ) :
	$wp_error = new WP_Error();
	if ( ! empty ( $_GET['action'] ) && $_GET['action'] == 'activation_mail' ) {
		if ( ! empty ( $_GET['email'] ) ) {
			$user_data = get_user_by( 'email', $_GET['email'] );
			if ( ! $user_data ) {
				$wp_error->add( 'log', esc_html__( 'Sorry we dont find email provided on ur records', 'simontaxi' ));
			} else {
				$user_id = $user_data->ID;
				simontaxi_registration_email_alert( $user_id, '-', 'activation-link', 'Activation link on : ' . wp_specialchars_decode(get_option('blogname'), ENT_QUOTES) );
				$redirect = add_query_arg( array( 'action' => 'activation_mail_sent' ), simontaxi_get_bookingsteps_urls( 'login' ) );
				wp_safe_redirect( $redirect );
			}
		} else {
			$wp_error->add( 'log', esc_html__( 'Sorry we dont find email address to send activation link', 'simontaxi' ));
		}
	}
	
	if ( isset( $_POST ) && ! empty( $_POST ) ) {
		$data = $_POST;
		if ( wp_verify_nonce( $data['simontaxi_login_nonce'], 'simontaxi-login-nonce' ) ) {
			if ( $data['log'] == '' ) {
				$wp_error->add( 'log', esc_html__( 'Please enter Username or Email', 'simontaxi' ));
			}
			if ( $data['pwd'] == '' ) {
				$wp_error->add( 'pwd', esc_html__( 'Please enter password', 'simontaxi' ));
			}
			if ( $data['log'] != '' && $data['pwd'] != '' ) {
				$user_data = get_user_by( 'login', $data['log'] );
				if ( ! $user_data ) {
					$user_data = get_user_by( 'email', $data['log'] );
				}
				if ( $user_data ) {
					$user_ID = $user_data->ID;
					$approval_status = get_user_meta( $user_ID, 'approval_status', true );
					$activated = TRUE;
					/**
					 * this is to compatible with older version of this plugin.
					 *
					 * @since 2.0.8
					 */
					if ( ! empty( $approval_status ) ) {
						if ( $approval_status != 'approved' ) {
							$activated = FALSE;
						}
					}
					
					if ( TRUE === $activated ) {
						$user_email = $user_data->user_email;

						if ( wp_check_password( $data['pwd'], $user_data->user_pass, $user_data->ID ) ) {

							if ( isset( $data['remember'] ) ) {
								$data['remember'] = true;
							} else {
								$data['remember'] = false;
							}

							simontaxi_log_user_in( $user_data->ID, $data['simontaxi_user_login'], $data['simontaxi_user_pass'], $data['remember'] );
						} else {
							$wp_error->add( 'password_incorrect', esc_html__( 'The password you entered is incorrect', 'simontaxi' ) );
						}
					} else {
						$message = __(  sprintf( 'Please activate your account. Dont receive activation link?. Dont worry click <a href="%s">here</a> to get activation link', simontaxi_get_bookingsteps_urls( 'login' ) . '?action=activation_mail&email=' . $user_data->user_email ), 'simontaxi' );
						$wp_error->add( 'not_activated',  $message);
					}
					
				} else {
					$wp_error->add( 'username_incorrect', esc_html__( 'The username you entered does not exist', 'simontaxi' ) );
				}
				// Check for errors and redirect if none present
				$errors = $wp_error->errors;

				if ( empty( $errors ) ) {
					$redirect = admin_url();
					if ( simontaxi_is_user( 'Customer' ) ) {
						$redirect = simontaxi_get_bookingsteps_urls( 'user_bookings' );
					} else {
						$redirect = simontaxi_get_bookingsteps_urls( 'manage_bookings' );
					}
					wp_safe_redirect( $redirect );
				}
			}
		}
	}
	$action = site_url() . '/wp-login.php';
	$action = '';
	$rememberme = ! empty( $_POST['rememberme'] );
?>
	<form id="simontaxi_login_form" class="simontaxi_form" action="<?php echo $action;?>" method="post">
		<fieldset>
			<legend><?php esc_html_e( 'Log into Your Account', 'simontaxi' ); ?></legend>

			<?php if ( ! empty( $wp_error->errors ) ) { ?>
				<div class="alert alert-danger">
					<ul><?php echo implode( '</li><li>', $wp_error->get_error_messages() );?></ul>
				</div>
			<?php } elseif ( isset( $_GET['action'] ) && $_GET['action'] == 'registered' ) {
				?>
				<div class="alert alert-success">
					<ul><li><?php esc_html_e( 'You have registered sucessfully. Please check your email to activate account and login here.', 'simontaxi' );?></li></ul>
				</div>
				<?php
			} elseif ( isset( $_GET['action'] ) && $_GET['action'] == 'activation_mail_sent' ) {
				?>
				<div class="alert alert-success">
					<ul><li><?php esc_html_e( 'We have sent you activation link with details. Please check your email address to activate your account.', 'simontaxi' );?></li></ul>
				</div>
				<?php
			} ?>
			<div class="form-group ">
				<label for="simontaxi_user_login"> <?php esc_html_e( 'Username or Email', 'simontaxi' ); ?><?php echo simontaxi_required_field(); ?></label>
				<div class="inner-addon right-addon">
					<input name="log" id="simontaxi_user_login" class="required form-control" type="text" placeholder="<?php esc_html_e( 'Username or Email', 'simontaxi' );?>"/>
				</div>
			</div>

			<div class="form-group ">
				<label for="simontaxi_user_pass"><?php esc_html_e( 'Password', 'simontaxi' ); ?><?php echo simontaxi_required_field(); ?></label>
				<div class="inner-addon right-addon">
					<input name="pwd" id="simontaxi_user_pass" class="password required form-control" type="password" placeholder="<?php esc_html_e( 'Password', 'simontaxi' ); ?>"/>
				</div>
			</div>

			<div class="form-group ">
				<div>
					<input id="rememberme" type="hidden" name="rememberme" value="forever">
					<!-- <input id="rememberme" type="checkbox" name="rememberme" value="forever" <?php checked( $rememberme ); ?>>
					<label for="rememberme"><span><span></span></span><i class="st-terms-accept"><?php esc_html_e( 'Remember Me', 'simontaxi' ); ?></i></label> -->
				</div>
			</div>


			<p class="simontaxi-login-submit">
				<input type="hidden" name="redirect_to" value="<?php echo simontaxi_get_bookingsteps_urls( 'user_bookings' );?>" />
				<input type="hidden" name="simontaxi_login_nonce" value="<?php echo wp_create_nonce( 'simontaxi-login-nonce' ); ?>"/>
				<input type="hidden" name="simontaxi_action" value="user_login"/>
				<input id="simontaxi_login_submit" type="submit" class="simontaxi_submit btn btn-primary" value="<?php esc_html_e( 'Log In', 'simontaxi' ); ?>"/>
			</p>
			<div class="form-group st-login-tags">
				<a href="<?php echo wp_lostpassword_url(); ?>">
					<?php esc_html_e( 'Lost Password?', 'simontaxi' ); ?>
				</a> | <a href="<?php echo simontaxi_get_bookingsteps_urls( 'registration' ); ?>">
					<?php esc_html_e( 'Don\'t have account?', 'simontaxi' ); ?>
				</a>
			</div>

		</fieldset>
	</form>
	<script>
	jQuery( '#simontaxi_login_form' ).submit(function (event) {
		var simontaxi_user_login = jQuery( '#simontaxi_user_login' ).val();
		var simontaxi_user_pass = jQuery( '#simontaxi_user_pass' ).val();
		var error = 0;
		jQuery( '.error' ).hide();
		if (simontaxi_user_login == "") {
			jQuery( '#simontaxi_user_login' ).after( '<span class="error"><?php esc_html_e( 'Please enter username OR email address', 'simontaxi' );?></span>' );
			error++;
		}
		if (simontaxi_user_pass == "") {
			jQuery( '#simontaxi_user_pass' ).after( '<span class="error"><?php esc_html_e( 'Please enter password', 'simontaxi' );?></span>' );
			error++;
		}
		if (error > 0) event.preventDefault();
	});
	</script>
<?php else : ?>
	<p class="simontaxi-logged-in"><?php esc_html_e( 'You are already logged in', 'simontaxi' ); ?></p>
<?php endif; ?>