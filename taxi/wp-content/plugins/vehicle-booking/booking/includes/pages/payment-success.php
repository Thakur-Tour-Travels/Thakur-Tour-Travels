<?php
/**
 * This template is used to display the 'payment-success'
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  payment-success
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
?>
<?php $payment_success_message_offline = simontaxi_get_option( 'payment_success_message_offline', '' ); ?>
<?php $payment_success_message_online = simontaxi_get_option( 'payment_success_message_online', '' ); ?>
<div class="st-section">
    <div class="">
        <div class="">
        <div class="st-invoice">
            <?php if ( $success ) { ?>
            <div class="alert alert-success" >
			<?php if ( 'byhand' === $selected_payment_method && '' !== $payment_success_message_offline ) : ?>
				<?php echo sprintf( $payment_success_message_offline, $booking_step1['reference'] ); ?>
			<?php elseif ( '' !== $payment_success_message_online ) : ?>
				<?php echo sprintf( $payment_success_message_online, $booking_step1['reference'] ); ?>
			<?php else : ?>
			<i class="fa fa-check-circle" aria-hidden="true"></i><p><?php esc_html_e( 'Thank you for your Booking.', 'simontaxi' );?><br><br><?php echo sprintf( __( 'Booking Success and your booking reference is <b>%s</b>', 'simontaxi' ), $booking_step1['reference'] );?></p>
			<?php endif; ?>
			</div>

            <?php
            if ( is_user_logged_in() ) {
                if( simontaxi_is_user('administrator') || simontaxi_is_user('executive') ) {
                    echo '<meta http-equiv="refresh" content="0;URL=\''.simontaxi_get_bookingsteps_urls('manage_bookings').'\'" />   ';
                } else {
                    echo '<p style="text-align:center">'.sprintf ( __( 'Go to <a href="%s">Booking History </a> on your dashboard ! ', 'simontaxi'), simontaxi_get_bookingsteps_urls('user_bookings') ).'</p>';
                }
            }
            ?>
            <?php } else { ?>
            <div class="alert alert-danger"><i class="fa fa-times-circle" aria-hidden="true"></i><p><?php echo sprintf( __("Some thing went wrong. Click <a href='%s'>here</a> to try again", 'simontaxi'), simontaxi_get_bookingsteps_urls('step1') );?></p></div>
            <?php
            }?>
        </div>
        </div>
    </div>
</div>
