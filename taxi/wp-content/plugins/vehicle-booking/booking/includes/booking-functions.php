<?php
/**
 * Plugin Functions/Booking
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  Functions/Booking
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'simontaxi_booking_step1' ) ) :
	/**
	 * Displays the booking step1 (Page Title : Booking Step1)
	 *
	 * @since 1.0
	 * @return mixed
	 */
	function simontaxi_booking_step1( $args = array() ) {

		do_action( 'simontaxi_booking_step1_starting' );
		
		$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
		
		$placement = 'fullpage';
		$columns = 8;
		$pre_class = '';
		if ( isset( $args['placement'] ) ) {
			$placement = $args['placement'];
		}
		if ( isset( $args['columns'] ) ) {
			$columns = $args['columns'];
		}
		if ( isset( $args['class'] ) && $args['class'] != '' ) {
			$pre_class = $args['class'];
		}
		if ( isset( $args['booking_types'] ) ) {
			$booking_types = $args['booking_types'];
		}
		
		ob_start();
		// include_once( SIMONTAXI_PLUGIN_PATH . '/booking/includes/process-step1.php' );
		$template = 'booking/includes/process-step1.php';
		if ( simontaxi_is_template_customized( $template ) ) {
			include_once( simontaxi_get_theme_template_dir_name() . $template );
		} else {
			include_once( apply_filters( 'simontaxi_locate_process_step1', SIMONTAXI_PLUGIN_PATH . $template ) );
		}
		
		/**
		 * @since 2.0.8
		 */
		$template = 'booking/includes/booking-steps/step1.php';
		if ( simontaxi_is_template_customized( $template ) ) {
			include_once( simontaxi_get_theme_template_dir_name() . $template );
		} else {
			include_once( apply_filters( 'simontaxi_locate_step1', SIMONTAXI_PLUGIN_PATH . $template ) );
		}		
		return  ob_get_clean();
	}
endif;

if ( ! function_exists( 'simontaxi_booking_step2' ) ) :
	/**
	 * Displays the booking step2 (Page Title : Select Cab Type)
	 *
	 * @since 1.0
	 * @return mixed
	 */
	function simontaxi_booking_step2() {
		global $wp_error;

		do_action( 'simontaxi_booking_step2_starting' );
		
		$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
		
		// include_once( SIMONTAXI_PLUGIN_PATH . '/booking/includes/process-step2.php' );
		$template = 'booking/includes/process-step2.php';
		if ( simontaxi_is_template_customized( $template ) ) {
			include_once( simontaxi_get_theme_template_dir_name() . $template );
		} else {
			include_once( apply_filters( 'simontaxi_locate_process_step2', SIMONTAXI_PLUGIN_PATH . $template ) );
		}
		/**
		 * @since 2.0.8
		 */
		$template = 'booking/includes/booking-steps/step2.php';
		if ( simontaxi_is_template_customized( $template ) ) {
			include_once( simontaxi_get_theme_template_dir_name() . $template );
		} else {
			include_once( apply_filters( 'simontaxi_locate_step2', SIMONTAXI_PLUGIN_PATH . $template ) );
		}
	}
endif;

if ( ! function_exists( 'simontaxi_booking_step3' ) ) :
	/**
	 * Displays the booking step3 (Page Title : Confirm Booking)
	 *
	 * @since 1.0
	 * @return mixed
	 */
	function simontaxi_booking_step3() {
		global $wp_error;

		do_action( 'simontaxi_booking_step3_starting' );

		$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
		$booking_step2 = simontaxi_get_session( 'booking_step2', array() );
		
		// include_once( SIMONTAXI_PLUGIN_PATH . '/booking/includes/process-step3.php' );
		$template = 'booking/includes/process-step3.php';
		if ( simontaxi_is_template_customized( $template ) ) {
			include_once( simontaxi_get_theme_template_dir_name() . $template );
		} else {
			include_once( apply_filters( 'simontaxi_locate_process_step3', SIMONTAXI_PLUGIN_PATH . $template ) );
		}
		/**
		 * @since 2.0.8
		 */
		$template = 'booking/includes/booking-steps/step3.php';
		if ( simontaxi_is_template_customized( $template ) ) {
			include_once( simontaxi_get_theme_template_dir_name() . $template );
		} else {
			include_once( apply_filters( 'simontaxi_locate_step3', SIMONTAXI_PLUGIN_PATH . $template ) );
		}
	}
endif;

if ( ! function_exists( 'simontaxi_booking_step4' ) ) :
	/**
	 * Displays the booking step4 (Page Title : select-payment-method)
	 *
	 * @since 1.0
	 * @return mixed
	 */
	function simontaxi_booking_step4() {
		global $wp_error;

		do_action( 'simontaxi_booking_step4_starting' );
		
		$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
		$booking_step2 = simontaxi_get_session( 'booking_step2', array() );
		$booking_step3 = simontaxi_get_session( 'booking_step3', array() );
		
		// include_once( SIMONTAXI_PLUGIN_PATH . '/booking/includes/process-step4.php' );
		$template = 'booking/includes/process-step4.php';
		if ( simontaxi_is_template_customized( $template ) ) {
			include_once( simontaxi_get_theme_template_dir_name() . $template );
		} else {
			include_once( apply_filters( 'simontaxi_locate_process_step4', SIMONTAXI_PLUGIN_PATH . $template ) );
		}
		/**
		 * @since 2.0.8
		 */
		$template = 'booking/includes/booking-steps/step4.php';
		if ( simontaxi_is_template_customized( $template ) ) {
			include_once( simontaxi_get_theme_template_dir_name() . $template );
		} else {
			include_once( apply_filters( 'simontaxi_locate_step4', SIMONTAXI_PLUGIN_PATH . $template ) );
		}
	}
endif;



add_action( 'simontaxi_action_proceed_to_pay', 'simontaxi_proceed_to_pay' );
if ( ! function_exists( 'simontaxi_proceed_to_pay' ) ) :
	/**
	 * Fires on a non-authenticated admin post request for the given action i.e 'proceed_to_pay'.
	 *
	 * @since 1.0
	 */
	function simontaxi_proceed_to_pay() {
		$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
		$booking_step2 = simontaxi_get_session( 'booking_step2', array() );
		$booking_step3 = simontaxi_get_session( 'booking_step3', array() );
		$booking_step4 = simontaxi_get_session( 'booking_step4', array() );
		
		if ( empty( $booking_step1 ) || empty( $booking_step2 ) || empty( $booking_step3 ) || empty( $booking_step4 ) ) {
			/**
			 * @since 2.0.8
			 */
			$template = 'booking/includes/pages/redirection-message.php';
			if ( simontaxi_is_template_customized( $template ) ) {
				include_once( simontaxi_get_theme_template_dir_name() . $template );
			} else {
				include_once( apply_filters( 'simontaxi_locate_redirection_message', SIMONTAXI_PLUGIN_PATH . $template ) );
			}
			die();
		} else {
			$payment_id = simontaxi_get_session( 'booking_step4', 0, 'payment_id' );
			if ( $payment_id > 0 ) {
				$payment_method = simontaxi_get_session( 'booking_step4', 0, 'selected_payment_method' );
				if ( 'paypal' === $payment_method ) {
					// include_once(SIMONTAXI_PLUGIN_PATH . '/booking/libraries/Paypal.php' );
					
					$template = 'booking/libraries/Paypal.php';
					if ( simontaxi_is_template_customized( $template ) ) {
						include_once( simontaxi_get_theme_template_dir_name() . $template );
					} else {
						include_once( apply_filters( 'simontaxi_locate_paypal', SIMONTAXI_PLUGIN_PATH . $template ) );
					}

					$paypal = simontaxi_get_option( 'paypal', array() );

					$config['business'] 			= ( isset( $paypal['email'] ) ) ? $paypal['email'] : '';
					$config['cpp_header_image'] 	= ( isset( $paypal['header_logo'] ) ) ? $paypal['header_logo'] : bloginfo( 'template_directory' ) . '/images/logo.png';
					$config['return'] 				= esc_url( simontaxi_get_bookingsteps_urls( 'payment_success' ) );
					$config['cancel_return']		= esc_url( simontaxi_get_bookingsteps_urls( 'payment_failed' ) );
					$config['production'] 			= FALSE;
					$config['currency_code'] 		= simontaxi_get_currency_code();

					/**
					 * Let us take the email as full name by default, because full name is optional in admin. If it is not available let use 'email' as 'full name'
					*/
					$full_name = simontaxi_get_session( 'booking_step3', '', 'email' );
					/**
					 * Full name field is optional in admin. Lets check whether it is enabled and user enter it.
					*/
					if ( '' !== simontaxi_get_session( 'booking_step3', '', 'full_name' ) ) {
						$full_name = simontaxi_get_session( 'booking_step3', '', 'full_name' );
					}
					$config['first_name'] 			= $full_name;
					$config['email'] 				= simontaxi_get_session( 'booking_step3', '', 'email' );

					if( 'sandbox' === $paypal['mode'] ) {
						$config['production'] = FALSE;
					} else {
						$config['production'] = TRUE;
					}
					$paypal = new Paypal( $config);

					$total = simontaxi_get_session( 'booking_step4', 0, 'amount_payable' );
					$paypal->add( 'cab_booking_' . simontaxi_get_session( 'booking_step1', '', 'reference' ), $total, 1, '' ,0, 0 );
					$paypal->pay();
				} elseif ( 'payu' === $payment_method ) {
					/**
					 * Integration reference : http://developers.payu.com/en/classic_api.html
					 * Test Credentions : https://developer.payubiz.in/documentation/Web-Integration-FAQ's/166
					 */
					$payu_live 			= 'https://secure.payu.in/_payment';
					$payu_test 			= 'https://test.payu.in/_payment';
					$payu = simontaxi_get_option( 'payu' );
					$payu_mode = ( isset( $payu['mode'] ) ) ? $payu['mode'] : 'sandbox';
					$url = ( 'sandbox' === $payu_mode ) ? $payu_test : $payu_live;
					if ( 'sandbox' === $payu_mode ) {
						/**
						 * @since 2.0.1
						 */
						// @see https://documentation.payubiz.in/hosted-page-copy/
						$key = 'gtKFFx';
						$salt = 'eCwWELxi';
						$payu_service_provider = (isset( $payu['payu_service_provider'] ) ) ? $payu['payu_service_provider'] : 'money';
						if ( 'money' === $payu_service_provider ) {
							$key = 'rjQUPktU';
							$salt = 'e5iIg1jwi8';
						}
						$merchant_key =  (isset( $payu['merchant_key_sandbox']) ) ? $payu['merchant_key_sandbox'] : $key;
						$salt =  (isset( $payu['salt_sandbox']) ) ? $payu['salt_sandbox'] : $salt;
					} else {
						/**
						 * @since 2.0.1
						 */
						// @see https://www.payumoney.com/dev-guide/development/general.html
						$key = 'gtKFFx';
						$salt = 'eCwWELxi';
						$payu_service_provider = (isset( $payu['payu_service_provider'] ) ) ? $payu['payu_service_provider'] : 'money';
						if ( 'money' === $payu_service_provider ) {
							$key = 'rjQUPktU';
							$salt = 'e5iIg1jwi8';
						}
						$merchant_key =  ( isset( $payu['merchant_key_live'] ) ) ? $payu['merchant_key_live'] : $key;
						$salt =  ( isset( $payu['salt_live'] ) ) ? $payu['salt_live'] : $salt;
					}

					/**
					 * Pre Data
					 * @var array
					 */
					$total = simontaxi_get_session( 'booking_step4', 0, 'amount_payable' );
					$email = simontaxi_get_session( 'booking_step3', '', 'email' );
					/**
					 * Full name field is optional in admin. Lets check whether it is enabled and user enter it.
					*/
					$full_name = '';
					if ( '' !== simontaxi_get_session( 'booking_step3', '', 'full_name' ) ) {
						$full_name = simontaxi_get_session( 'booking_step3', '', 'full_name' );
					}

					//New Way start
					$order_id = simontaxi_get_session( 'booking_step4', 0, 'payment_id' );
					$txnid = $order_id . '_' . date("ymds");
					$order_total = $total;
					$productinfo = 'Booking ref: ' . simontaxi_get_session( 'booking_step1', 0, 'reference' );
					$phone = simontaxi_get_session( 'booking_step3', '', 'mobile' );
					$str = "$merchant_key|$txnid|$order_total|$productinfo|$full_name|$email|$order_id||||||||||$salt";
					$hash = strtolower(hash( 'sha512', $str) );
					$data = array(
						'key' 			=> $merchant_key, // Mandate
						'hash' 			=> $hash, // Mandate
						'txnid' 		=> $txnid, // Mandate
						'amount' 		=> $order_total, // Mandate
						'firstname'		=> $full_name,
						'email' 		=> $email,
						'phone' 		=> $phone,
						'productinfo'	=> $productinfo, // Mandate
						'surl' 			=> simontaxi_get_bookingsteps_urls( 'payment_success' ), // Mandate
						'furl' 			=> simontaxi_get_bookingsteps_urls( 'payment_final' ), // Mandate
						'lastname' 		=> '',
						'address1' 		=> '',
						'address2' 		=> '',
						'city' 			=> '',
						'state' 		=> '',
						'country' 		=> '',
						'zipcode' 		=> '',
						'curl'			=> simontaxi_get_bookingsteps_urls( 'payment_final' ),
						'pg' 			=> 'NB',
						'udf1' 			=> $order_id,
						'service_provider'	=> (isset( $payu['payu_service_provider'] ) ) ? $payu['payu_service_provider'] : 'money',
						//'service_provider' => 'payu_paisa', // @since 2.0.1
					);
					//New way end
					/**
					 * START REQUEST
					 */
					//include_once(SIMONTAXI_PLUGIN_PATH . '/booking/libraries/payugo.php' );
					$template = 'booking/libraries/payugo.php';
					if ( simontaxi_is_template_customized( $template ) ) {
						include_once( simontaxi_get_theme_template_dir_name() . $template );
					} else {
						include_once( apply_filters( 'simontaxi_locate_payugo', SIMONTAXI_PLUGIN_PATH . $template ) );
					}
					

				} elseif ( 'byhand' === $payment_method || 'banktransfer' === $payment_method) {					
					/**
					 * @since 2.0.0
					*/
					do_action( 'simontaxi_payment_success' );
				} else {
					/**
					 * Let us give external developers to develop their own payment gateway!
					 *
					 * @since 2.0.0
					*/
					do_action( 'simontaxi_action_process_payment_' . $payment_method );
				}
			}
		}
	}
endif;

if ( ! function_exists( 'simontaxi_payment_success' ) ) :
	/**
	 * Add an action to call this page.
	 *
	 * @since 2.0.0
	*/
	add_action( 'simontaxi_payment_success', 'simontaxi_payment_success' );
	/**
	 * function to process payment success.
	 *
	 * @since 1.0.0
	*/
	function simontaxi_payment_success() {
		global $wpdb;
		$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
		$booking_step2 = simontaxi_get_session( 'booking_step2', array() );
		$booking_step3 = simontaxi_get_session( 'booking_step3', array() );
		$booking_step4 = simontaxi_get_session( 'booking_step4', array() );
		$success = false;
		/**
		 * Few Payment gateways send data in $_GET method so we need to check it. If the $_POST data is empty then let us take $_REQUEST data so it contain $_POST & $_GET
		 *
		 * @since 2.0.9
		 */
		if ( empty( $_POST ) ) {
			$_POST = $_REQUEST;
		}
	
		do_action( 'simontaxi_payment_success_before', $_POST );
		
		if ( empty( $booking_step1 ) || empty( $booking_step2 ) || empty( $booking_step3 ) || empty( $booking_step4 ) ) {
			/**
			 * @since 2.0.8
			 */
			$template = 'booking/includes/pages/redirection-message.php';
			if ( simontaxi_is_template_customized( $template ) ) {
				include_once( simontaxi_get_theme_template_dir_name() . $template );
			} else {
				include_once( apply_filters( 'simontaxi_locate_redirection_message', SIMONTAXI_PLUGIN_PATH . $template ) );
			}
		} else {
			$selected_payment_method = simontaxi_get_session( 'booking_step4', 0, 'selected_payment_method' );
			$amount_paid = $amount_paid_transaction = 0;
			if ( 'paypal' === $selected_payment_method ) {
				$data = array();
				if ( 'Completed' === $_POST['payment_status'] ) {
					$data['payment_status'] = 'success';
					$data['amount_paid'] = $_POST['mc_gross'];
					$data['amount_paid_transaction'] = $_POST['mc_gross'];
					$amount_paid_transaction = $data['amount_paid_transaction'];
					/**
					 * @since 2.0.0
					*/
					simontaxi_set_session( 'booking_step4', array( 'payment_status' => 'success' ) );
				} else {
					$paypal = simontaxi_get_option( 'paypal', array() );
					/**
					 * Let us show the transaction as success for 'sandbox' mode, to avoid the confusion!
					*/
					if( 'sandbox' === $paypal['mode'] ) {
						$data['payment_status'] = 'success';
						$data['amount_paid'] = $_POST['mc_gross'];
						$data['amount_paid_transaction'] = $_POST['mc_gross'];
						$amount_paid_transaction = $data['amount_paid_transaction'];
						/**
						 * @since 2.0.0
						*/
						simontaxi_set_session( 'booking_step4', array( 'payment_status' => 'success' ) );
					} else {
						$data['payment_status'] = 'pending';
						$data['amount_paid'] = 0;
						/**
						 * @since 2.0.0
						*/
						simontaxi_set_session( 'booking_step4', array( 'payment_status' => 'failed' ) );
					}
				}
				$amount_paid = $data['amount_paid'];
				$data['transaction_status'] = $_POST['payment_status'];
				simontaxi_set_session( 'booking_step4',
					array( 'payment_status' => $data['payment_status'],
							'amount_paid' => $data['amount_paid'],
					) );
				$data['payment_type'] = simontaxi_get_session( 'booking_step4', '', 'selected_payment_method' );
				$data['transaction_reference'] = $_POST['txn_id'];
				$data['gateway_data'] = json_encode( $_POST);
				$wpdb->update( $wpdb->prefix.'st_payments', $data , array( 'ID'=> simontaxi_get_session( 'booking_step4', 0, 'payment_id' ) ) );

				/**
				 * Let us update the booking status if payment is success, based on admin settings. (Settings->Payment Gateways)
				*/
				if ( 'success' === $data['payment_status'] ) {
					$booking = array(
						'status' => simontaxi_get_option( 'booking_status_payment_success', 'new' ),
					);
					$wpdb->update( $wpdb->prefix.'st_bookings', $booking , array( 'ID' => simontaxi_get_session( 'booking_step1', '', 'db_ref' ) ) );
				}
				$success = true;
			} elseif ( 'payu' === $selected_payment_method ) {
				$payu_live 			= "https://secure.payu.in/_payment";
				$payu_test 			= "https://test.payu.in/_payment";
				$payu = simontaxi_get_option( 'payu' );
				$payu_mode = ( isset( $payu['mode'] ) ) ? $payu['mode'] : 'sandbox';
				$url = ( $payu_mode == 'sandbox' ) ? $payu_test : $payu_live;
				if ( 'sandbox' === $payu_mode ) {
					$merchant_key =  ( isset( $payu['merchant_key_sandbox'] ) ) ? $payu['merchant_key_sandbox'] : 'gtKFFx';
					$salt =  ( isset( $payu['salt_sandbox'] ) ) ? $payu['salt_sandbox'] : 'eCwWELxi';
				} else {
					$merchant_key =  ( isset( $payu['merchant_key_live'] ) ) ? $payu['merchant_key_live'] : 'gtKFFx';
					$salt =  ( isset( $payu['salt_live'] ) ) ? $payu['salt_live'] : 'eCwWELxi';
				}

				$status		=	$_POST["status"];
				$firstname  = 	$_POST["firstname"];
				$amount		=	$_POST["amount"];
				$txnid		=	$_POST["txnid"];
				$posted_hash=	$_POST["hash"];
				$key		=	$_POST["key"];
				$productinfo=	$_POST["productinfo"];
				$email		=	$_POST["email"];

				$hash = hash( 'sha512', "$salt|$status||||||||||$_REQUEST[udf1]|$email|$firstname|$productinfo|$amount|$txnid|$merchant_key");

				$data = array();
				$data['transaction_status'] = $status;
				$data['payment_type'] = $selected_payment_method;
				$data['gateway_data'] = json_encode( $_POST);
				$data['transaction_reference'] = $_POST['txnid'];
				$wpdb->update( $wpdb->prefix.'st_payments', $data , array( 'ID'=> simontaxi_get_session( 'booking_step4', '', 'payment_id' ) ) );
				if ( $hash != $posted_hash) {
					$success = false;
				} else {
					$data = array();
					$status = strtolower( $status);
					if ( 'success' === $status ) {
						$data['payment_status'] = 'success';
						$data['amount_paid'] = $amount;
						$data['amount_paid_transaction'] = $amount;
						$amount_paid_transaction = $data['amount_paid_transaction'];
						/**
						 * @since 2.0.0
						*/
						simontaxi_set_session( 'booking_step4', array( 'payment_status' => 'success' ) );
					} else {
						$data['payment_status'] = 'pending';
						$data['amount_paid'] = 0;
						/**
						 * @since 2.0.0
						*/
						simontaxi_set_session( 'booking_step4', array( 'payment_status' => 'failed' ) );
					}
					$amount_paid = $data['amount_paid'];
					simontaxi_set_session( 'booking_step4',
						array( 'payment_status' => $data['payment_status'],
							'amount_paid' => $data['amount_paid'],
						) );
					$wpdb->update( $wpdb->prefix.'st_payments', $data , array( 'ID'=> simontaxi_get_session( 'booking_step4', '', 'payment_id' ) ) );

					/**
					 * Let us update the booking status if payment is success, based on admin settings. (Settings->Payment Gateways)
					*/
					if ( 'success' === $data['payment_status'] ) {
						$booking = array(
							'status' => simontaxi_get_option( 'booking_status_payment_success', 'new' ),
						);
						$wpdb->update( $wpdb->prefix.'st_bookings', $booking , array( 'ID' => simontaxi_get_session( 'booking_step1', '', 'db_ref' ) ) );
					}
					$success = true;
				}
			} elseif ( 'byhand' === $selected_payment_method || 'banktransfer' === $selected_payment_method ) {
				$success = true;
				/**
				 * Let us mark payment as success if administrator or executive made the booking, means they take the amount by hand and made the transaction.
				*/
				if ( simontaxi_is_user( 'administrator' ) || simontaxi_is_user( 'executive' ) ) {
					/**
					 * Update payment.
					 *
					 * @since 2.0.0
					*/
					simontaxi_update_payment( 'success' );
					/**
					 * @since 2.0.0
					*/
					simontaxi_set_session( 'booking_step4', array( 'payment_status' => 'success' ) );
				} else {
					simontaxi_set_session( 'booking_step4',
						array( 'payment_status' => 'pending',
							'amount_paid' => '0',
						) );
				}

			}

			/**
			 * Since we are supporting external plugins for payments let us write common code for flexibility for external developers.
			 *
			 * @since 2.0.0
			*/
			if ( has_action( 'simontaxi_after_payment_success_' . $selected_payment_method, 'simontaxi_after_payment_success_' . $selected_payment_method ) || has_action( 'simontaxi_after_payment_success_' . $selected_payment_method ) ) {
				do_action( 'simontaxi_after_payment_success_' . $selected_payment_method, $_POST );
			} else {
				do_action( 'simontaxi_after_payment_success', $_POST );
			}
			
			/**
			 * @since 2.0.9
			 */
			do_action( 'simontaxi_additional_operations_after_payment_success', $_POST );
		}
	}
endif;

if ( ! function_exists( 'simontaxi_update_payment' ) ) :
	add_action( 'simontaxi_update_payment', 'simontaxi_update_payment', 10, 2 );
	/**
	 * Let us update payment!.
	 *
	 * @since 2.0.0
	 */
	function simontaxi_update_payment( $payment_status, $additional_data = array() ) {
		global $wpdb;

		simontaxi_set_session( 'booking_step4', array(
			'amount_paid' => simontaxi_get_session( 'booking_step4', 0, 'amount_payable' ),
			'payment_status' => $payment_status,
		) );


		$data = array();
		$data['payment_status'] = $payment_status;
		$data['amount_paid'] = simontaxi_get_session( 'booking_step4', 0, 'amount_paid' );
		$wpdb->update( $wpdb->prefix.'st_payments', $data , array( 'ID'=> simontaxi_get_session( 'booking_step4', 0, 'payment_id' ) ) );

		/**
		 * Let us update the booking status if payment is success, based on admin settings. (Settings->Payment Gateways)
		*/
		if ( 'success' === $data['payment_status'] ) {
			$booking = array(
				'status' => simontaxi_get_option( 'booking_status_payment_success', 'new' ),
			);
			$wpdb->update( $wpdb->prefix.'st_bookings', $booking , array( 'ID' => simontaxi_get_session( 'booking_step1', 0, 'db_ref' ) ) );
		}
	}
endif;


if ( ! function_exists( 'simontaxi_after_payment_success' ) ) :

	add_action( 'simontaxi_after_payment_success', 'simontaxi_after_payment_success', 10, 1 );
	/**
	 * Let us make after payment success operations!.
	 *
	 * @since 2.0.0
	 */
	function simontaxi_after_payment_success( $payment_status ) {
		global $wpdb;

		$success = $payment_status;
		
		/**
		 * Let us send email or sms.
		 * This function will send the email and sms based on admin settings
		*/
		do_action( 'simontaxi_send_email_sms', 'onward' );
		
		/**
		 * Let me insert another booking and payment if user selects 'two_way' booking type
		*/
		if ( in_array( simontaxi_get_session( 'booking_step1', 'one_way', 'journey_type' ), apply_filters( 'simontaxi_twoway_other_tabs_payment_success', array( 'two_way' ) ) ) ) {

			$db_ref = simontaxi_get_session( 'booking_step1', '', 'db_ref' );
			$onward_booking = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}st_bookings WHERE ID = $db_ref" );
			if ( ! empty( $onward_booking ) ) {

				/**
				 * Let us update inserted payment record in 'step4.php' which is for onward journey.
				*/
				$amount_details = simontaxi_get_fare_details();
				$data = array(
					'amount_payable' => $amount_details['amount_payable_onward'],
					'tax_amount' => $amount_details['tax_amount_onward'],
					'surcharges_amount' => $amount_details['surcharges_amount_onward'],
				);
				$amount_paid = simontaxi_get_session( 'booking_step4', 0, 'amount_paid' );
				if ( $amount_paid > 0 ) {
					$data['amount_paid'] = $amount_paid / 2;
				}
				$wpdb->update( $wpdb->prefix.'st_payments', $data , array( 'ID'=> simontaxi_get_session( 'booking_step4', '', 'payment_id' ) ) );

				/**
				 * As we are inserting return journey as separate record in database, let us update bookings table 'journey_type' as 'onw_way' for the already inserted record in 'step4.php'
				*/
				$wpdb->update( $wpdb->prefix.'st_bookings', array( 'journey_type' => 'one_way' ) , array( 'ID' => $db_ref) );

				$onward_booking = $onward_booking[0];
				
				/**
				 * Let us insert return journey and return journey payment also
				*/
				$booking = array(
					'user_id' => get_current_user_id(),
					'reference' => simontaxi_get_token(simontaxi_get_option( 'booking_ref_length', 6) ),
					'booking_type' => $onward_booking->booking_type,
					'airport' => $onward_booking->airport,
					'hourly_package' => $onward_booking->hourly_package,
					'selected_vehicle' => $onward_booking->selected_vehicle,
					'vehicle_name' => $onward_booking->vehicle_name,
					/**
					 * We are changing 'pickup_location' to 'drop_location'
					*/
					'pickup_location' => $onward_booking->drop_location,
					'pickup_location_country' => $onward_booking->drop_location_country,
					'pickup_location_lat' => $onward_booking->drop_location_lat,
					'pickup_location_lng' => $onward_booking->drop_location_lng,

					'drop_location' => $onward_booking->pickup_location,
					'drop_location_country' => $onward_booking->pickup_location_country,
					'drop_location_lat' => $onward_booking->pickup_location_lat,
					'drop_location_lng' => $onward_booking->pickup_location_lng,

					'journey_type' => 'one_way',

					/**
					 * Let us take 'return_date' as the 'pickup_date' for now
					*/
					'pickup_date' => $onward_booking->pickup_date_return,
					'pickup_time' => $onward_booking->pickup_time_return,
					'waiting_time' => $onward_booking->waiting_time_return,
					'additional_pickups' => $onward_booking->additional_pickups_return,
					'additional_dropoff' => $onward_booking->additional_dropoff_return,

					'booking_contacts' => $onward_booking->booking_contacts,
					'driver_information' => $onward_booking->driver_information,
					'date' => date( 'Y-m-d h:i:s' ),
					'status' => $onward_booking->status,
					'status_updated' => date( 'Y-m-d h:i:s' ),
					'distance' => $onward_booking->distance,
					'distance_text' => $onward_booking->distance_text,
					'distance_units' => $onward_booking->distance_units,
					'session_details' => $onward_booking->session_details,
					'flight_no' => $onward_booking->flight_no,
					'itineraries' => $onward_booking->itineraries,
					
					'vehicle_no' => $onward_booking->vehicle_no,
					'return_booking_id' => $onward_booking->ID,
					'driver_id' => $onward_booking->driver_id,
				);
				
				/**
				 *
				 * We are taking driver_id from bookings table because for each booking driver may change!
				 *
				 * @since 2.0.8
				 */
				$driver_id = get_post_meta( $onward_booking->selected_vehicle, 'driver_id', true );
				if ( empty( $driver_id ) ) {
					$driver_id = 0;
				}
				$booking['driver_id'] = $driver_id;
				
				$wpdb->insert( $wpdb->prefix .'st_bookings', $booking );
				simontaxi_set_session( 'booking_step1', array( 'db_ref2' => $wpdb->insert_id ) );

				/**
				 * Let me insert payment also for return journey
				*/
				$db_ref = simontaxi_get_session( 'booking_step4', '', 'payment_id' );
				$onward_payment = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}st_payments WHERE ID = $db_ref" );
				$payment_status = 'pending';
				if ( ! empty( $onward_payment ) ) {
					$payment_status = $onward_payment[0]->payment_status;
				}
				$data = array( 'payment_method' => simontaxi_get_session( 'booking_step4', '', 'selected_payment_method' ) );
				$data['reference'] = simontaxi_get_token(simontaxi_get_option( 'booking_ref_length', 6) );
				$data['user_id'] = get_current_user_id();
				simontaxi_set_session( 'booking_step1', array( 'db_ref2' => $wpdb->insert_id ) );
				$data['booking_id'] = $wpdb->insert_id;;

				$data['basic_amount'] = $amount_details['basic_amount'];
				$data['amount_payable'] = $amount_details['amount_payable_return'];
				if ( $amount_paid > 0 ) {
					$data['amount_paid'] = $amount_paid / 2;
				}
				
				$data['amount_paid_transaction'] = $amount_paid;
				$data['tax_amount'] = $amount_details['tax_amount_return'];
				$data['surcharges_amount'] = $amount_details['surcharges_amount_return'];
				$data['amount_details'] = json_encode( $amount_details );
				$data['payment_status'] = $payment_status;
				$data['datetime'] = date( 'Y-m-d h:i:s' );
				$wpdb->insert( $wpdb->prefix .'st_payments', $data);
				simontaxi_set_session( 'booking_step4', array( 'payment_id2' => $wpdb->insert_id ) );
				/**
				 * Let us send email or sms.
				 * This function will send the email and sms based on admin settings
				*/
				do_action( 'simontaxi_send_email_sms', 'return' );
			}
		}

		/**
		 * To display success|failed page.
		 *
		 * @since 2.0.0
		*/
		$selected_payment_method = simontaxi_get_session( 'booking_step4', '', 'selected_payment_method' );
		if ( has_action( 'simontaxi_display_payment_final', 'simontaxi_display_success_page_' . $selected_payment_method, $payment_status ) ) {
			/**
			 * Let us give an option to external plugin developers to override this action.
			*/
			do_action( 'simontaxi_display_payment_final_' . $selected_payment_method, $payment_status );
		} else {
			$redirect_to = simontaxi_get_bookingsteps_urls( 'payment_final' );
			wp_safe_redirect( $redirect_to );
		}
	}
endif;

if ( ! function_exists( 'simontaxi_display_payment_final' ) ) :
	add_action( 'simontaxi_display_payment_final', 'simontaxi_display_payment_final', 10, 1 );
	/**
	 * To display success page.
	 *
	 * @since 2.0.0
	 */
	function simontaxi_display_payment_final( $payment_status ) {
		$success = ( 'success' === $payment_status );
		$payment_status = $payment_status;
		$selected_payment_method = simontaxi_get_session( 'booking_step4', '', 'selected_payment_method' );
		
		$booking_step4 = simontaxi_get_session( 'booking_step4', array() );
		
		// $payment_reference = $booking_step4['payment_reference'];
		
		$payment_reference = simontaxi_get_session( 'booking_step1', '', 'reference' );
		
		$booking_id = simontaxi_get_session( 'booking_step1', 0, 'db_ref' );
		if ( $booking_id > 0 ) {
			global $wpdb;
			$bookings = $wpdb->prefix . 'st_bookings';
			$sql = "SELECT `status` FROM `" . $bookings . "` WHERE ID = " . $booking_id;
			$status = $wpdb->get_var( $sql );
			simontaxi_update_count( $status );
			
			/**
			 * Let us reset the query results so that admin get correct results.
			 *
			 * @since 2.0.9
			 */
			$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'simontaxi_bookings_page_%'" );
		}
		
		/**
		 * @since 2.0.8
		 */
		$template = '/booking/includes/pages/payment-final.php';
		if ( simontaxi_is_template_customized( $template ) ) {
			include_once( simontaxi_get_theme_template_dir_name() . $template );
		} else {
			include_once( apply_filters( 'simontaxi_locate_payment_final', SIMONTAXI_PLUGIN_PATH . $template ) );
		}
		
		/**
		 * @since 2.0.0
		*/
		simontaxi_clear_sessions();
	}
endif;


if ( ! function_exists( 'simontaxi_clear_selections' ) ) :
	/**
	 * This function clears all selections and returns to booking step1
	 */
	function simontaxi_clear_selections() {

		/**
		 * @since 2.0.0
		 */
		simontaxi_clear_sessions();

		/**
		 * @since 2.0.8
		 */
		$template = '/booking/includes/pages/redirection-clear-selections.php';
		if ( simontaxi_is_template_customized( $template ) ) {
			include_once( simontaxi_get_theme_template_dir_name() . $template );
		} else {
			include_once( apply_filters( 'simontaxi_locate_redirection_clear_selections', SIMONTAXI_PLUGIN_PATH . $template ) );
		}
	}
endif;

if ( ! function_exists( 'simontaxi_clear_sessions' ) ) :
	/**
	 * We can use it from anywhere to clear sessions.
	 *
	 * @since 2.0.0
	 */
	function simontaxi_clear_sessions() {
		simontaxi_set_session( 'booking_step1', null );
		simontaxi_set_session( 'booking_step2', null );
		simontaxi_set_session( 'booking_step3', null );
		simontaxi_set_session( 'booking_step4', null );
		simontaxi_set_session( 'discount_details', null );
		simontaxi_set_session( 'payment_gateway', null );
		simontaxi_set_session( 'user_id', null );
		do_action( 'simontaxi_clear_sessions_additional' );
	}
endif;

if ( ! function_exists( 'simontaxi_updater' ) ) :
	/**
	 * Updater function
	 *
	 * @since 2.0.0
	 */
	function simontaxi_updater() {
		return STVB()->updater();
	}
endif;