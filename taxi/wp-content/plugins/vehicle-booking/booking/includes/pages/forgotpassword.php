<?php
/**
 * This template is used to display the 'forgotpassword'
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  forgotpassword
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;	
}
?>
<div id="lostPassword">

    <form id="lostPasswordForm" method="post" class="simontaxi_form">
	<div id="message"></div>
			<?php
				// this prevent automated script for unwanted spam
				if ( function_exists( 'wp_nonce_field' ) )
					wp_nonce_field( 'rs_user_lost_password_action', 'rs_user_lost_password_nonce' );
			?>

        <div class="form-group ">
            <label for="user_login"><?php esc_attr_e( 'Username or E-mail:', 'simontaxi') ?> <?php echo simontaxi_required_field();?></label>
            <input type="text" name="user_login" id="user_login" class="input required form-control" placeholder="Enter Email Address" value="" size="20" />

        </div>

			<?php
			/**
			 * Fires inside the lostpassword <form> tags, before the hidden fields.
			 *
			 * @since 2.1.0
			 */
			do_action( 'lostpassword_form' ); ?>
			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="btn btn-primary center-block" value="<?php esc_attr_e( 'Get New Password', 'simontaxi'); ?>" />

			</p>
			
			<div class="form-group st-login-tags">
				<a href="<?php echo simontaxi_get_bookingsteps_urls( 'login' ); ?>">
					<?php esc_html_e( 'Have Account?', 'simontaxi' ); ?>
				</a> | <a href="<?php echo simontaxi_get_bookingsteps_urls( 'registration' ); ?>">
					<?php esc_html_e( 'Don\'t have account?', 'simontaxi' ); ?>
				</a>
			</div>
			
		</form>
	</div>
	<script>
	jQuery(document).ready(function($) {

		// for lost password
		$("form#lostPasswordForm").submit(function(){
			var submit = $("div#lostPassword #submit"),
				preloader = $("div#lostPassword #preloader"),
				message	= $("div#lostPassword #message"),
				contents = {
					action: 	'lost_pass',
					nonce: 		this.rs_user_lost_password_nonce.value,
					user_login:	this.user_login.value
				};

			// disable button onsubmit to avoid double submision
			submit.attr("disabled", "disabled").addClass( '' );

			// Display our pre-loading
			preloader.css({'visibility':'visible'});

			$.post( '<?php echo admin_url( 'admin-ajax.php' );?>', contents, function( data ){
				submit.removeAttr("disabled").removeClass( 'disabled');
				// hide pre-loader
				preloader.css({'visibility':'hidden'});

				// display return data
				message.html( data );
			});

			return false;
		});

	});
	</script>