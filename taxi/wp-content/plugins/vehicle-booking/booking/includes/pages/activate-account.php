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
$wp_error = new WP_Error();

if ( empty( $_GET['code'] ) || empty( $_GET['uname'] ) ) {
	$wp_error->add( 'log', esc_html__( 'Wrong operation', 'simontaxi' ));
}

if ( ! is_user_logged_in() ) :

	$code = $_GET['code'];
	$uname = $_GET['uname'];
	
	$user_data = get_user_by( 'login', $uname );
	if ( ! $user_data ) {
		$wp_error->add( 'login', esc_html__( 'Sorry we have not found user name provided.', 'simontaxi' ));
	} else {
		$approval_code = '';
		// $approval_code = get_user_meta( $user_data->ID, 'approval_code', TRUE );
		$user_meta = get_user_meta( $user_data->ID );
		
		if ( ! empty( $user_meta ) ) {
			foreach( $user_meta as $key => $val ) {
				if ( 'approval_code' == $key && is_array( $val ) ) {
					foreach( $val as $v ) {
						if ( $v == $code ) {
							$approval_code = $v;
						}
					}
					
					
				}
			}
		}
		
		if ( ! $approval_code ) {
			$wp_error->add( 'code', esc_html__( 'We have not found code provided.', 'simontaxi' ));
		} else {
			if ( $approval_code == $code ) {
				update_user_meta($user_data->ID, 'approval_status', 'approved');
			} else {
				$wp_error->add( 'wrong_code', esc_html__( 'We have not found code provided.', 'simontaxi' ));
			}
		}
	}
?>
	<form id="simontaxi_login_form" class="simontaxi_form" action="<?php echo $action; ?>" method="post">
		<fieldset>
			<legend><?php esc_html_e( 'Activate Account', 'simontaxi' ); ?></legend>

			<?php if ( ! empty( $wp_error->errors ) ) { ?>
				<div class="alert alert-danger">
					<ul><?php echo implode( '</li><li>', $wp_error->get_error_messages() );?></ul>
				</div>
			<?php } else {
				?>
				<div class="alert alert-success">
					<ul><li><?php esc_html_e( 'You have activated your account successfully.' );?></li>
					<li><?php echo sprintf( __( '<a href="%s">Click</a> here to login' ), simontaxi_get_bookingsteps_urls('login') ); ?></li>
					</ul>
				</div>
				<?php
			} ?>

		</fieldset>
	</form>
	
<?php else : ?>
	<p class="simontaxi-logged-in"><?php esc_html_e( 'You are already logged in', 'simontaxi' ); ?></p>
<?php endif; ?>