<?php
/**
 * Display the page to select vehicle (page is for the slug 'select-payment-method' )
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  Booking step4 page
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$paymodes = simontaxi_get_option( 'payment_methods', array() );
if ( empty( $paymodes ) ) {
	$available_pay_methods = array( 
	'paypal' => esc_html__( 'Paypal', 'simontaxi' ),
	'payu' => esc_html__( 'PayU', 'simontaxi' ),
	'byhand' => esc_html__( 'By Hand', 'simontaxi' ),
	// 'banktransfer' => esc_html__( 'Bank Transfer', 'simontaxi' ),
	);
	$paymodes = apply_filters( 'simontaxi_payment_gateways', $available_pay_methods );	
}


$booking_summany_step4 = simontaxi_get_option( 'booking_summany_step4', 'yes' );
$step4_sidebar_position = simontaxi_get_option( 'step4_sidebar_position', 'right' );
$default_breadcrumb_display_step4 = simontaxi_get_option( 'default_breadcrumb_display_step4', 'yes' );

$cols = 8;
if ( 'no' === $booking_summany_step4 ) {
    $cols = 12;
}

$booking_step4 = simontaxi_get_session( 'booking_step4', array() );
?>
<!-- Booking Form -->
<div class="st-section-sm st-grey-bg">
    <div class="container">
        <?php
		if ( 'yes' == simontaxi_get_option('show_numbered_navigation', 'yes') && 'yes' == simontaxi_get_option('show_numbered_navigation_fullwidth', 'yes') ) {
			do_action('simontaxi_bookings_breadcrumb', 'step4'); 
		}
		?>
		<div class="row">
			<?php if ( 'yes' === $booking_summany_step4 && $step4_sidebar_position == 'left' && isset( $booking_step1) && (!empty( $booking_step1) ) ) {
                /**
				 * @since 2.0.8
				 */
				$template = 'booking/includes/booking-steps/right-side.php';
				if ( simontaxi_is_template_customized( $template ) ) {
					require simontaxi_get_theme_template_dir_name() . $template;
				} else {
					require apply_filters( 'simontaxi_locate_rightside',SIMONTAXI_PLUGIN_PATH . $template );
				}
            } 
			do_action( 'simontaxi_sidebar_left_step4' );
			?>
            <div class="col-lg-<?php echo esc_attr( $cols ); ?> col-md-8 col-sm-12">
                <?php
				if ( 'yes' == simontaxi_get_option('show_numbered_navigation', 'yes') && 'no' == simontaxi_get_option('show_numbered_navigation_fullwidth', 'yes') ) {
					do_action('simontaxi_bookings_breadcrumb', 'step4'); 
				}
				?>
				<div class="st-booking-block">
                    <?php echo simontaxi_print_errors(); ?>
                    <!-- Booking Progress -->
					<?php
					if ( 'yes' === $default_breadcrumb_display_step4 ) {
					/**
					 * @since 2.0.8
					 */
					$template = 'booking/includes/booking-steps/bread-crumb.php';
					if ( simontaxi_is_template_customized( $template ) ) {
						include_once( simontaxi_get_theme_template_dir_name() . $template );
					} else {
						include_once( apply_filters( 'simontaxi_locate_bread_crumb', SIMONTAXI_PLUGIN_PATH . $template ) );
					}
					}
					?>
                    <!-- end Booking Progress -->
					<?php do_action( 'simontaxi_step4_before_form' ); ?>
                    <div id="info-div"></div>
                    <div class="tab-content">
                        <form id="select-payment" action="" method="POST">
						
						<?php do_action( 'simontaxi_step4_within_form' ); ?>
                        <!-- TAB-1 -->
                        <div id="st-booktab1" class="tab-pane fade in active">
                            <div class="table-responsive">
                                <table class="table table-hover st-table st-table-payment">
                                    <?php
									$default_payment_method = simontaxi_get_value( $booking_step4, 'selected_payment_method' );
									
									if ( empty( $default_payment_method ) ) {
										$default_payment_method = simontaxi_get_option( 'default_payment_method' );
									}
									$err_message = esc_html__( 'Not Supported', 'simontaxi' );
                                    if ( ! empty( $paymodes ) ) :
                                    foreach ( $paymodes as $paymode ) :
                                    if ( in_array( $paymode, array( 'paypal', 'payu', 'byhand', 'banktransfer' ), true ) ) :
									$can_display = true;
                                    if ( $paymode == 'paypal' ) {
                                        /**
                                         * If the paymod is 'paypal' we need to check whether the paypal is accepting currency!
                                        */
                                        $can_display = simontaxi_is_paypal_accept( simontaxi_get_currency_code() );
                                    } elseif( $paymode == 'payu' ) {
										/**
                                         * If the paymod is 'payu' we need to check whether the admin credentials are set!
                                        */
										$payu_live 	= "https://secure.payu.in/_payment";
										$payu_test 	= "https://test.payu.in/_payment";
										$payu = simontaxi_get_option( 'payu' );
                                        $payu_mode = (isset( $payu['mode']) ) ? $payu['mode'] : 'sandbox';
										$url = ( $payu_mode == 'sandbox' ) ? $payu_test : $payu_live;
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
											$merchant_key =  (isset( $payu['merchant_key_live']) ) ? $payu['merchant_key_live'] : $key;
											$salt =  (isset( $payu['salt_live']) ) ? $payu['salt_live'] : $salt;
										}
										$amount_details = simontaxi_get_fare_details();
										$total = $amount_details['amount_payable'];
										
										/**
										 * Since PayU is accepting upto 50000 Online we need to check this
										 */
										if( $merchant_key == '' || $merchant_key == '' || $total > 50000 ) {
											$can_display = false;
											if ( $merchant_key == '' || $merchant_key == '' ) {
												$err_message = esc_html__( 'No Settings', 'simontaxi' );
											} else {
											$err_message = esc_html__( 'Transaction limit exceeded', 'simontaxi' );
											}
										}
									}
                                    if( ! $can_display ) {
										// continue;
									}
                                    $options = simontaxi_get_option( $paymode);
                                    $title = isset( $options['title'] ) ? $options['title'] : ucfirst( $paymode );
                                    $logo = isset( $options['logo'] ) ? $options['logo'] : '';
                                    $description = isset( $options['description'] ) ? $options['description'] : '';
                                    $instructions = isset( $options['instructions'] ) ? $options['instructions'] : '';
                                    ?>
									<?php
									if ( $title == '' ) {
										switch ( $paymode ) {
											case 'paypal':
												$title = 'Paypal';
												break;
											case 'payu':
												$title = 'PayU';
												break;
											case 'byhand':
												$title = 'By Hand';
												break;
											case 'banktransfer':
												$title = 'Bank Transfer';
												break;
										}
									}
									?>
                                    <tr>
                                        <td>
                                            <?php if ( $can_display ) { ?>
											<input id="paymode<?php echo esc_attr( $paymode ); ?>" type="radio" name="selected_payment_method" class="paymentgateway" value="<?php echo esc_attr( $paymode ); ?>" <?php if ( $default_payment_method == $paymode ) { echo ' checked';}?>>
											<?php } else { ?>
											<span title="<?php echo __( $title . ' does not support your corrency. Contact administrator.', 'simontaxi-paystack-payment' ); ?>" class="not_supported">?
											<p><small><?php echo $err_message; ?></small></p>
											</span>
											<?php } ?>
                                            <label for="paymode<?php echo esc_attr( $paymode ); ?>"><span><span></span></span>
                                            </label>
                                        </td>
                                        <td><?php
                                        echo esc_attr( $title );
                                        ?></td>
                                        <td>
                                        <?php
                                        if ( $logo ==  '' ) {
                                            switch ( $paymode ) {
                                                case 'paypal':
                                                    $logo = SIMONTAXI_PLUGIN_URL . '/images/paypal-logo.png';
                                                    break;
                                                case 'payu':
                                                    $logo = SIMONTAXI_PLUGIN_URL . '/images/payu-logo.jpg';
                                                    break;
                                                case 'byhand':
												case 'banktransfer':
                                                    $logo = SIMONTAXI_PLUGIN_URL . '/images/byhand.jpg';
                                                    break;
                                            }
                                        }
                                        ?>
                                        <img src="<?php echo esc_url( $logo ); ?>" width="80" height="40" title="<?php echo esc_attr( $title ); ?>" alt="<?php echo esc_attr( $title ); ?>">
                                        </td>
                                        <td><p><?php echo esc_attr( $description ); ?></p></td>
                                    </tr>
                                    <?php
									else : // External Payment Gateway handling
										//$external_gateway = '';
										
										//echo apply_filters( 'simontaxi_external_gateway', $external_gateway );
									endif;
									endforeach;
									
									/**
									 * To avoid repetition display of external payment gateway we need to keep this out of 'foreach' loop!!
									 *
									 * @since 2.0.8
									 */
									$external_gateway = '';
										
									echo apply_filters( 'simontaxi_external_gateway', $external_gateway );
										
                                    else :
                                        esc_html_e( 'No Payment methods available. Please contact administrator.', 'simontaxi' );
                                    endif; ?>

                                </table>
                            </div>
							
							<?php do_action( 'simontaxi_step4_additional_fields' ); ?>
							
                            <?php if ( 'step4' === simontaxi_terms_page() ) : ?>
                            <div class="col-sm-12">
                                <div class="input-group st-top40">
                                    <div>
                                        <input id="terms" type="checkbox" name="terms" value="option">
                                        <label for="terms"><span><span></span></span><i class="st-terms-accept"><?php echo simontaxi_terms_text(); ?></i></label>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
							
							<div id="submitbutton">
							<?php echo do_action( 'simontaxi_submit_button_step4' ); ?>
							</div>
                        </div>
						<input type="hidden" name="simontaxi_step4_nonce" value="<?php echo wp_create_nonce( 'simontaxi-step4-nonce' ); ?>"/>
                        </form>
                    </div>
					<?php do_action( 'simontaxi_step4_after_form' ); ?>
                </div>
            </div>
            <?php if ( 'yes' === $booking_summany_step4 && $step4_sidebar_position == 'right' && isset( $booking_step1) && (!empty( $booking_step1) ) ) {
                /**
				 * @since 2.0.8
				 */
				$template = 'booking/includes/booking-steps/right-side.php';
				if ( simontaxi_is_template_customized( $template ) ) {
					require simontaxi_get_theme_template_dir_name() . $template;
				} else {
					require apply_filters( 'simontaxi_locate_rightside', SIMONTAXI_PLUGIN_PATH . $template );
				}
            } 
			do_action( 'simontaxi_sidebar_right_step4' );
			?>
        </div>
    </div>
</div>
<!-- /Booking Form -->

<script type="text/javascript">
jQuery( '#select-payment' ).on( 'submit', function (event) {
    var errors = 0;
    var message = '';
    if (jQuery( 'input[name="selected_payment_method"]:checked' ).val() === undefined) {
        message += '<?php echo esc_html__( 'Please choose a payment method', 'simontaxi' ); ?>';
        errors++;
    }
    <?php if ( 'step4' === simontaxi_terms_page() ) : ?>
    if ( ! document.getElementById( 'terms' ).checked ) {
        jQuery( '#terms' ).closest( '.input-group' ).after( '<span class="error"> <?php esc_html_e( 'You should accept Terms of Service to proceed', 'simontaxi' ); ?></span>' );
        if ( errors > 0 ) message += '<br>';
        message += '<?php echo esc_html__( 'You should accept Terms of Service to proceed', 'simontaxi' ); ?>';
        errors++;
    }
    <?php endif; ?>
    if( errors == 0 ) {
        jQuery( '#info-div' ).html( '' );
    } else {
        jQuery( '#info-div' ).html( '<div class="alert alert-danger"><p>'+message+'</p></div>' );
        event.preventDefault();
    }
});

function total_fare( amount ) {
    jQuery( '#selected_amount' ).val(amount);
}
</script>
