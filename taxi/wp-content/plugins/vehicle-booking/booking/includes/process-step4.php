<?php
/**
 * @global wpdb  $wpdb  WordPress database abstraction object.
 */
global $wpdb;

$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
if ( empty( $booking_step1 ) ) {
    $redirect_to = simontaxi_get_bookingsteps_urls( 'step1' );
	simontaxi_set_error( 'session_expired', esc_html__( 'Sorry, session is expired ! Now you will be redirected ...', 'simontaxi' ) );
    wp_safe_redirect( $redirect_to );
}

$additional_data = array(
	'current_step' => 'step4',
	);
simontaxi_set_session( 'booking_step1', $additional_data );
$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
//var_dump( ( isset( $_POST['validtestep4'] ) && wp_verify_nonce( $_POST['simontaxi_step4_nonce'], 'simontaxi-step4-nonce' ) ) );
// dd( $_POST );

if ( isset( $_POST['validtestep4'] ) && wp_verify_nonce( $_POST['simontaxi_step4_nonce'], 'simontaxi-step4-nonce' ) ) {
	
	do_action( 'simontaxi_booking_step4_start_before' );
	/**
     * Let us validate whether the user selects vehicle or not
     */
    if ( ! isset( $_POST['selected_payment_method'] ) ) {
        simontaxi_set_error( 'selected_payment_method', esc_html__( 'Please select payment gateway', 'simontaxi' ) );
    }
    if ( simontaxi_terms_page() == 'step4' && ! isset( $_POST['terms'] ) ) {
        simontaxi_set_error( 'terms', esc_html__( 'You should accept Terms of Service to proceed', 'simontaxi' ) );
    }
	// $errors = simontaxi_get_errors();
	$errors = apply_filters( 'simontaxi_flt_step4_errors', simontaxi_get_errors() );
	
    if ( empty( $errors ) ) {

        $db_ref = simontaxi_get_session( 'booking_step1', 0, 'db_ref' );
		if ( isset( $_POST ) && $db_ref > 0 && ! empty( $_POST ) ) {
            do_action( 'simontaxi_booking_step4_noerrors_before' );
			
			$amount_details = simontaxi_get_fare_details();
			
            $payment_id = simontaxi_get_session( 'booking_step4', 0, 'payment_id' );
			$payment_reference = simontaxi_get_session( 'booking_step4', '', 'payment_reference' );
			/**
			 * Let us unset nonce field
			 *
			 8 @since 2.0.0
			*/
			unset( $_POST['simontaxi_step4_nonce'] );
			
			simontaxi_set_session( 'booking_step4', $_POST );

			simontaxi_set_session( 'booking_step4', 
					array( 
					'amount_payable' => $amount_details['amount_payable'],
					/**
					 * Let us maintain payment status in session so that we can access from any where!.
					 *
					 * @since 2.0.0
					*/
					'payment_status' => 'pending',
					)
				);
			
            $data = array( 'payment_method' => simontaxi_get_session( 'booking_step4', 0, 'selected_payment_method' ) );

            $data['user_id'] = get_current_user_id();
            $data['booking_id'] = simontaxi_get_session( 'booking_step1', 0, 'db_ref' );

            $data['basic_amount'] = $amount_details['basic_amount'];
            $data['amount_payable'] = simontaxi_get_session( 'booking_step4', 0, 'amount_payable' );
            /**
             * This will update later if payment is success.
             */
            $data['amount_paid'] = 0;
            $data['discount_amount'] = $amount_details['discount_amount'];
            $data['tax_amount'] = $amount_details['tax_amount'];
            $data['surcharges_amount'] = $amount_details['surcharges_amount'];
            $data['amount_details'] = json_encode( $amount_details );
            $data['payment_status'] = 'pending';
			
            if (  $payment_id > 0 ) {
                $check = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}st_payments WHERE ID = $payment_id" );
                if ( empty( $check ) ) { //For any reason if it not already inserted, Let us insert
                    $data['reference'] = simontaxi_get_token( simontaxi_get_option( 'booking_ref_length', 6 ) );
                    $data['datetime'] = date_i18n( 'Y-m-d h:i:s' );
                    $wpdb->insert( $wpdb->prefix .'st_payments', $data );
                    simontaxi_set_session( 'booking_step4', array( 'payment_id' => $wpdb->insert_id, 'payment_reference' => $data['reference'] ) );
                } else {
                    $data['payment_status_updated'] = date_i18n( 'Y-m-d h:i:s' );
					if ( '' === $payment_reference ) {
						$data['reference'] = simontaxi_get_token( simontaxi_get_option( 'booking_ref_length', 6 ) );
					} else {
						$data['reference'] = $payment_reference;
					}
                    $wpdb->update( $wpdb->prefix .'st_payments', $data, array( 'ID' => $payment_id) );
					simontaxi_set_session( 'booking_step4', array( 'payment_id' => $payment_id, 'payment_reference' => $data['reference'] ) );
                }
            } else {
                $data['reference'] = simontaxi_get_token( simontaxi_get_option( 'booking_ref_length', 6) );
                $data['datetime'] = date_i18n( 'Y-m-d h:i:s' );
                $wpdb->insert( $wpdb->prefix .'st_payments', $data);
				simontaxi_set_session( 'booking_step4', array( 'payment_id' => $wpdb->insert_id, 'payment_reference' => $data['reference'] ) );
            }

            /**
             * Let us update sesssion details
            */
            $data = array();
            // $data['session_details'] = json_encode( array( simontaxi_get_session( 'booking_step1' ), simontaxi_get_session( 'booking_step2' ), simontaxi_get_session( 'booking_step3' ), simontaxi_get_session( 'booking_step4' ) ) );
			
			$data['session_details'] = json_encode(
				apply_filters( 'simontaxi_session_details', array(
					'step1' => simontaxi_get_session( 'booking_step1' ),
					'step2' => simontaxi_get_session( 'booking_step2' ),
					'step3' => simontaxi_get_session( 'booking_step3' ),
					'step4' => simontaxi_get_session( 'booking_step4' ),
					)
				)
			);
				
            $wpdb->update( $wpdb->prefix .'st_bookings',  $data, array( 'ID'=> simontaxi_get_session( 'booking_step1', 0, 'db_ref' ) ) );           
			
			/**
			 * Let us use powerfull sessions which is introduced from 2.0.0
			 *
			 & @since 2.0.0
			*/
			simontaxi_set_session( 'payment_gateway', $_POST );
			
			do_action( 'simontaxi_booking_step4_noerrors_after' );
			
			do_action( 'simontaxi_action_proceed_to_pay' );
			
        } else {
            /**
             * Means something went wrong and booking details not inserted into database, let us return back to step1, so that let us give an option for user to choose his location again
             */
            $redirect_to = simontaxi_get_bookingsteps_urls( 'step1' );
            wp_safe_redirect( $redirect_to );
        }
    }
}
