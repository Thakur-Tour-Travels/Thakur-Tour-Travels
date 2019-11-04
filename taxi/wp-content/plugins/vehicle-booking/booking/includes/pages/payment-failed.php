<?php
/**
 * This template is used to display the 'payment-failed'
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  payment-failed
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="st-section">
    <div class="">
        <div class="">

<div class="ppayement st-invoice">
    <div class="alert alert-danger">
<p style="color:red"><?php esc_html__('Payment Failed!', 'simontaxi');?></p>
        <?php
        if ( $selected_payment_method == 'payu' ) {
            echo '<h2>' . __('PayU Money payment status is pending', 'simontaxi') . '<br/>'.sprintf(__('PayU Money ID: %s(%s)', 'simontaxi'), $_REQUEST['mihpayid'], $_REQUEST['txnid']).'<br/>PG: '.$_REQUEST['PG_TYPE'].'('.$_REQUEST['unmappedstatus'].')<br/>Bank Ref: '.$_REQUEST['bank_ref_num'].'('.$_REQUEST['mode'].')</h2>';
        }
    echo '<h2><i class="fa fa-times-circle" aria-hidden="true"></i>' . sprintf( __( 'Click <a href="%s">here</a> to try again', 'simontaxi'), simontaxi_get_bookingsteps_urls('step1') ) . '</h2>';
        if ( is_user_logged_in() )
        {
            echo '<h4>'.sprintf(__("Go to <a href='%s'> Booking History </a> on your dashboard !", 'simontaxi'), simontaxi_get_bookingsteps_urls('user_bookings')).' </h4>';
        }
        ?>
</div>
</div>

        </div>
    </div>
</div>
