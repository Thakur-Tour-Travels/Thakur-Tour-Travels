<?php
/**
 * This template is used to display the 'payment-final'
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  payment-final
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php $payment_success_message_offline = simontaxi_get_option( 'payment_success_message_offline', '' );
?>
<?php $payment_success_message_online_success = simontaxi_get_option( 'payment_success_message_online_success', '' ); ?>
<?php $payment_success_message_online_failed = simontaxi_get_option( 'payment_success_message_online_failed', '' ); ?>
<?php
$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
if ( 'success' === $payment_status ) : ?>
<div class="st-section">
    <div class="">
        <div class="">
        <div class="st-invoice">
            <?php if ( $success ) { ?>
            <div class="alert alert-success" >
				<?php if ( ( 'byhand' === $selected_payment_method || 'banktransfer' === $selected_payment_method ) && '' !== $payment_success_message_offline ) : ?>
					<?php echo apply_filters( 'simontaxi_payment_final_success_message', sprintf( $payment_success_message_offline, $booking_step1['reference'] ) ); ?>
				<?php elseif ( '' !== $payment_success_message_online_success ) : ?>
					<?php echo apply_filters( 'simontaxi_payment_final_success_message', sprintf( $payment_success_message_online_success, $booking_step1['reference'] ) ); ?>
				<?php else : ?>
				<?php
				echo apply_filters( 'simontaxi_payment_final_success_message', '<i class="fa fa-check-circle" aria-hidden="true"></i><p>' . esc_html__( 'Thank you for your Booking.', 'simontaxi' ) .'<br><br>' . sprintf( __( 'Booking Success and your booking reference is <b>%s</b>', 'simontaxi' ), $booking_step1['reference'] ) .'</p>' );
				?>
				<?php endif; ?>
			</div>

            <?php
            if ( is_user_logged_in() ) {
                if( simontaxi_is_user( 'administrator' ) || simontaxi_is_user( 'executive' ) ) {
                    echo '<meta http-equiv="refresh" content="0;URL=\''.simontaxi_get_bookingsteps_urls( 'manage_bookings' ).'\'" />   ';
                } else {
                    echo '<p style="text-align:center">' . sprintf ( __( 'Go to <a href="%s">Booking History </a> on your dashboard ! ', 'simontaxi' ), simontaxi_get_bookingsteps_urls( 'user_bookings' ) ) . '</p>';
                }
            }
			
			$invoice_url = simontaxi_get_bookingsteps_urls( 'user_bookings' );
			
			$booking_id = simontaxi_get_session( 'booking_step1', 0, 'db_ref' );
			global $wpdb;
			$bookings = $wpdb->prefix. 'st_bookings';
			$payments = $wpdb->prefix. 'st_payments';
			$row = $wpdb->get_row( "SELECT *, `" . $bookings . "`.`ID` AS booking_id, `" . $bookings . "`.`reference` AS booking_ref, `" . $bookings . "`.`vehicle_no` FROM `" . $bookings . "` INNER JOIN `" . $payments . "` ON `" . $payments . "`.`booking_id`=`" . $bookings . "`.`ID` WHERE `" . $bookings . "`.booking_contacts!='' AND `" . $bookings . "`.ID=" . $booking_id );			
			
			$invoice_url = add_query_arg(array( 'invoice_id' => $row->booking_id . '-' . $row->booking_ref, 'action' => 'download_pdf' ),simontaxi_get_bookingsteps_urls( 'user_bookings' ) );
			echo '<p style="padding-top:20px;">';
			$show_invoice_to_user = simontaxi_get_option( 'show_invoice_to_user', 'yes' );
			if ( 'yes' === $show_invoice_to_user ) {
				echo sprintf( __( '<a href="%s" class="btn btn-primary btn-mobile mr-20"> Download Invoice </a> ', 'simontaxi' ), $invoice_url );
			}
			
			echo sprintf( __( '<a href="%s" class="btn btn-primary btn-mobile mr-20"> New Booking </a> ', 'simontaxi' ), simontaxi_get_bookingsteps_urls( 'step1' ) );
			echo '</p>';
            ?>
            <?php } else { ?>
            <div class="alert alert-danger"><i class="fa fa-times-circle" aria-hidden="true"></i><p><?php echo sprintf( __( 'Some thing went wrong. Click <a href="%s">here</a> to try again', 'simontaxi' ), simontaxi_get_bookingsteps_urls( 'step1' ) );?></p></div>
            <?php
            }?>
			<?php do_action( 'simontaxi_payment_final_success_message' ); ?>
        </div>
        </div>
    </div>
</div>
<?php else :
?>

    <div class="st-section-sm st-grey-bg">
        <div class="">

<div class="ppayement st-invoice">
    
	<?php if ( 'byhand' === $selected_payment_method || 'banktransfer' === $selected_payment_method ) { ?>
	<div class="alert alert-info">
	<p style="color:#31708f"><?php esc_html__( 'Payment Pending!', 'simontaxi' );?></p>
	<?php } else { ?>
	<div class="alert alert-danger">
	<p style="color:red"><?php esc_html__( 'Payment Failed!', 'simontaxi' );?></p>
	<?php
	}
	if ( 'payu' === $selected_payment_method ) {
		$str = apply_filters( 'simontaxi_payment_final_payu_message', '<h2>' . esc_html__( 'PayU Money payment status is pending', 'simontaxi' ) . '<br/>' . sprintf(esc_html__( 'PayU Money ID: %s(%s)', 'simontaxi' ), $_REQUEST['mihpayid'], $_REQUEST['txnid']) . '<br/>PG: ' . $_REQUEST['PG_TYPE'] . '( ' . $_REQUEST['unmappedstatus'] . ' )<br/>Bank Ref: ' . $_REQUEST['bank_ref_num'] . '( ' .$_REQUEST['mode'] .' )</h2>' );
		if ( '' !== $payment_success_message_online_failed ) {
			$str = $payment_success_message_online_failed;
		}
		echo apply_filters( 'simontaxi_payment_final_payu_message', $str );
	}
	elseif ( 'byhand' === $selected_payment_method || 'banktransfer' === $selected_payment_method ) {
		$str =  '<h2><i class="fa fa-hand-pointer-o" aria-hidden="true"></i>' . sprintf( __( 'Your payment in pending. Please contact administrator with payment reference number : <b>%s</b>', 'simontaxi' ), $payment_reference ) . '</h2>';
		if ( '' !== $payment_success_message_offline ) {
			$str = sprintf( $payment_success_message_offline, $payment_reference );
		}
		echo apply_filters( 'simontaxi_payment_final_byhand_message', $str );
	} elseif ( 'paypal' === $selected_payment_method ) {
		$str = '<h2><i class="fa fa-hand-pointer-o" aria-hidden="true"></i>' . sprintf( __( 'Your payment in pending. Please contact administrator with payment reference number : <b>%s</b>', 'simontaxi' ), $payment_reference ) . '</h2>';
		if ( '' !== $payment_success_message_online_failed ) {
			$str = $payment_success_message_online_failed;
		}
		echo apply_filters( 'simontaxi_payment_final_paypal_message', $str );
	} else {		
		echo '<h2><i class="fa fa-times-circle" aria-hidden="true"></i>' . sprintf( __( 'Some thing went wrong. Click <a href="%s">here</a> to try again', 'simontaxi' ), simontaxi_get_bookingsteps_urls( 'step1' ) ) . '</h2>';
	}
	if ( is_user_logged_in() ) {
		echo '<h4>'.sprintf( __( 'Go to <a href="%s"> Booking History </a> on your dashboard !', 'simontaxi' ), simontaxi_get_bookingsteps_urls( 'user_bookings' ) ) . ' </h4>';
	}
	
	$invoice_url = simontaxi_get_bookingsteps_urls( 'user_bookings' );
	
	$booking_id = simontaxi_get_session( 'booking_step1', 0, 'db_ref' );
	global $wpdb;
	$bookings = $wpdb->prefix. 'st_bookings';
	$payments = $wpdb->prefix. 'st_payments';
	$row = $wpdb->get_row( "SELECT *, `" . $bookings . "`.`ID` AS booking_id, `" . $bookings . "`.`reference` AS booking_ref, `" . $bookings . "`.`vehicle_no` FROM `" . $bookings . "` INNER JOIN `" . $payments . "` ON `" . $payments . "`.`booking_id`=`" . $bookings . "`.`ID` WHERE `" . $bookings . "`.booking_contacts!='' AND `" . $bookings . "`.ID=" . $booking_id );
	
	if ( ! empty( $row ) ) {
		$invoice_url = add_query_arg(array( 'invoice_id' => $row->booking_id . '-' . $row->booking_ref, 'action' => 'download_pdf' ),simontaxi_get_bookingsteps_urls( 'user_bookings' ) );
	}
	echo '<p style="padding-top:20px;">';
	$show_invoice_to_user = simontaxi_get_option( 'show_invoice_to_user', 'yes' );
	if ( 'yes' === $show_invoice_to_user ) {
		echo sprintf( __( '<a href="%s" class="btn btn-primary btn-mobile mr-20"> Download Invoice </a> ', 'simontaxi' ), $invoice_url );
	}
	
	echo sprintf( __( '<a href="%s" class="btn btn-primary btn-mobile mr-20"> New Booking </a> ', 'simontaxi' ), simontaxi_get_bookingsteps_urls( 'step1' ) );
	echo '</p>';
	?>
	<?php do_action( 'simontaxi_payment_final_failed_message' ); ?>
</div>
</div>

</div>
    </div>
</div>
<?php endif; ?>
