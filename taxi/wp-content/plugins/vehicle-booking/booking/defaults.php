<?php
if ( ! function_exists( 'simontaxi_booking_steps' ) ) :
	/**
	 * Return active tab. which means user selected tab
	 *
	 * @since 2.0.9
	 * @return string
	 */
	function simontaxi_booking_steps() {		
		return apply_filters( 'simontaxi_booking_steps', 
			array( 
				'step1' => array(
					'title' => simontaxi_get_step1_title(),
					'url' => simontaxi_get_bookingsteps_urls( 'step1' ),
				),
				'step2' => array(
					'title' => simontaxi_get_step2_title(),
					'url' => simontaxi_get_bookingsteps_urls( 'step2' ),
				),
				'step3' => array(
					'title' => simontaxi_get_step3_title(),
					'url' => simontaxi_get_bookingsteps_urls( 'step3' ),
				),
				'step4' => array(
					'title' => simontaxi_get_step4_title(),
					'url' => simontaxi_get_bookingsteps_urls( 'step4' ),
				),
			)
		);
	}
endif;

if ( ! function_exists( 'simontaxi_primary_payment_gateways' ) ) :
	/**
	 * Return active tab. which means user selected tab
	 *
	 * @since 2.0.9
	 * @return string
	 */
	function simontaxi_primary_payment_gateways() {		
		return array( 
				'paypal' => esc_html__( 'Paypal', 'simontaxi' ), 
				'payu' => esc_html__( 'PayU', 'simontaxi' ), 
				'byhand' => esc_html__( 'By Hand', 'simontaxi' ),
			);
	}
endif;

if ( ! function_exists( 'simontaxi_payment_gateways' ) ) :
	/**
	 * Return active tab. which means user selected tab
	 *
	 * @since 2.0.9
	 * @return string
	 */
	function simontaxi_payment_gateways() {		
		return apply_filters( 'simontaxi_payment_gateways', 
			array( 
				'paypal' => esc_html__( 'Paypal', 'simontaxi' ), 
				'payu' => esc_html__( 'PayU', 'simontaxi' ), 
				'byhand' => esc_html__( 'By Hand', 'simontaxi' ),
			)
		);
	}
endif;

if ( ! function_exists( 'simontaxi_default_units' ) ) :
	/**
	 * Return active tab. which means user selected tab
	 *
	 * @since 2.0.9
	 * @return string
	 */
	function simontaxi_default_units( $key = '' ) {
		$units = array(
			'weight' => array(
				'kg' => esc_html__( 'Kilogram', 'simontaxi' ),
				'g' => esc_html__( 'Gram', 'simontaxi' ),
				'mm' => esc_html__( 'Milligram', 'simontaxi' ),
				'mg' => esc_html__( 'Microgram', 'simontaxi' ),
				'Imperial ton' => esc_html__( 'Imperial ton', 'simontaxi' ),
				'US t' => esc_html__( 'US ton', 'simontaxi' ),
				'st' => esc_html__( 'Stone', 'simontaxi' ),
				'lb' => esc_html__( 'Pound', 'simontaxi' ),
				'oz' => esc_html__( 'Ounce', 'simontaxi' ),
				'Tonne' => esc_html__( 'Tonne', 'simontaxi' ),
			),
			'length' => array(
				'cm' => esc_html__( 'Centimetre', 'simontaxi' ),
				'm' => esc_html__( 'Metre', 'simontaxi' ),
				'km' => esc_html__( 'Kilometre', 'simontaxi' ),
			),
			'height' => array(
				'cm' => esc_html__( 'Centimetre', 'simontaxi' ),
				'm' => esc_html__( 'Metre', 'simontaxi' ),
				'km' => esc_html__( 'Kilometre', 'simontaxi' ),
			),
			'width' => array(
				'cm' => esc_html__( 'Centimetre', 'simontaxi' ),
				'm' => esc_html__( 'Metre', 'simontaxi' ),
				'km' => esc_html__( 'Kilometre', 'simontaxi' ),
			),
		);
		$units = apply_filters( 'simontaxi_default_units', $units );
		if ( ! empty( $key ) && ! empty( $units[ $key ] ) ) {
			$units = $units[ $key ];
		}
		return $units;
	}
endif;

if ( ! function_exists( 'simontaxi_salutations' ) ) :
	/**
	 * Return active tab. which means user selected tab
	 *
	 * @since 2.0.9
	 * @return string
	 */
	function simontaxi_salutations( $key = '' ) {		
		$salutations = array( 
				'Master' => esc_html__( 'Master', 'simontaxi' ), 
				'Mr.' => esc_html__( 'Mr.', 'simontaxi' ), 
				'Miss.' => esc_html__( 'Miss', 'simontaxi' ),				
				'Mrs.' => esc_html__( 'Mrs.', 'simontaxi' ), 
				'Ms.' => esc_html__( 'Ms.', 'simontaxi' ), 
				'Mx.' => esc_html__( 'Mx.', 'simontaxi' ),
				'M.' => esc_html__( 'M.', 'simontaxi' ),				
				'Sir' => esc_html__( 'Sir', 'simontaxi' ), 
				'Gentleman' => esc_html__( 'Gentleman', 'simontaxi' ), 
				'Dr.' => esc_html__( 'Dr.', 'simontaxi' ),
				'Professor.' => esc_html__( 'Professor.', 'simontaxi' ), 
				'Excellency' => esc_html__( 'Excellency', 'simontaxi' ), 
				'The Honourable' => esc_html__( 'The Honourable', 'simontaxi' ),
			);
		$salutations = apply_filters( 'simontaxi_salutations', $salutations );
		if ( ! empty( $key ) && ! empty( $salutations[ $key ] ) ) {
			$salutations = $salutations[ $key ];
		}
		return $salutations;
	}
endif;

if ( ! function_exists( 'simontaxi_user_actions' ) ) :
	/**
	 * Return active tab. which means user selected tab
	 *
	 * @since 2.0.9
	 * @return string
	 */
	function simontaxi_user_actions( $key = '' ) {		
		$user_actions = array( 
				'invoice' => array(
					'title' => esc_html__( 'Invoice', 'simontaxi' ),
					'link' => simontaxi_get_bookingsteps_urls( 'user_bookings' ),
					'class' => 'btn btn-dark btn-sm',
					'params' => array(
						'param1' => array(
							'name' => 'invoice_id',
							'type' => 'DB',
							'value' => array( 'ID', 'reference' ),
							'separator' => '-',
						),
						'param2' => array(
							'name' => 'action',
							'type' => 'string',
							'value' => 'download_invoice',
						),
					),
				),
			);
		$user_actions = apply_filters( 'simontaxi_user_actions', $user_actions );
		if ( ! empty( $key ) && ! empty( $user_actions[ $key ] ) ) {
			$user_actions = $user_actions[ $key ];
		}
		return $user_actions;
	}
endif;

if ( ! function_exists('simontaxi_upload_user_file') ) :
	function simontaxi_upload_user_file( $file = array() ) {
		require_once( ABSPATH . 'wp-admin/includes/admin.php' );
		  $file_return = wp_handle_upload( $file, array('test_form' => false ) );
		  if( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
			  return false;
		  } else {
			  $filename = $file_return['file'];
			  $attachment = array(
				  'post_mime_type' => $file_return['type'],
				  'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				  'post_content' => '',
				  'post_status' => 'inherit',
				  'guid' => $file_return['url']
			  );
			  $attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );
			  require_once(ABSPATH . 'wp-admin/includes/image.php');
			  $attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
			  wp_update_attachment_metadata( $attachment_id, $attachment_data );
			  if( 0 < intval( $attachment_id ) ) {
				return $attachment_id;
			  }
		  }
		  return false;
	}
endif;

if ( ! function_exists( 'simontaxi_booking_statuses' ) ) :
	/**
	 * Return default booking statuses
	 *
	 * @since 2.0.9
	 * @return string
	 */
	function simontaxi_booking_statuses( $key = '' ) {		
		$statuses = apply_filters( 'simontaxi_booking_statuses', 
			array( 
				'new' => esc_html__( 'New', 'simontaxi' ), 
				'confirmed' => esc_html__( 'Confirmed', 'simontaxi' ), 
				'cancelled' => esc_html__( 'Cancelled', 'simontaxi' ), 
				'onride' => esc_html__( 'On-Ride', 'simontaxi' ),
				'success' => esc_html__( 'Completed', 'simontaxi' ),
			)
		);
		if ( ! empty( $key ) && ! empty( $statuses[ $key ] ) ) {
			$statuses = $statuses[ $key ];
		}
		return $statuses;
	}
endif;

if ( ! function_exists( 'simontaxi_payment_statuses' ) ) :
	/**
	 * Return default booking statuses
	 *
	 * @since 2.0.9
	 * @return string
	 */
	function simontaxi_payment_statuses() {		
		return apply_filters( 'simontaxi_payment_statuses', 
			array( 
				'success' => esc_html__( 'Success', 'simontaxi' ), 
				'failed' => esc_html__( 'Failed', 'simontaxi' ), 
				'cancelled' => esc_html__( 'Cancelled', 'simontaxi' ),
				'pending' => esc_html__( 'Pending', 'simontaxi' ),
				'refunded' => esc_html__( 'Refunded', 'simontaxi' ),
			)
		);
	}
endif;

if ( ! function_exists( 'simontaxi_status_button_links_details' ) ) :
	/**
	 * Return default booking statuses
	 *
	 * @since 2.0.9
	 * @return string
	 */
	function simontaxi_status_button_links_details() {		
		return apply_filters( 'simontaxi_status_button_links_details', 
			array(
				'new' => array(
					'title' => esc_html__( 'New', 'simontaxi' ),
					'url' => admin_url( 'admin.php?page=manage_bookings&status=new' ),
					'spanclass' => 'status-count bg-danger',
					'anchorclass' => '',
				),
				
				'confirmed' => array(
					'title' => esc_html__( 'Confirmed', 'simontaxi' ),
					'url' => admin_url( 'admin.php?page=manage_bookings&status=confirmed' ),
					'spanclass' => 'status-count bg-purple',
					'anchorclass' => '',
				),
				
				'onride' => array(
					'title' => esc_html__( 'On-Ride', 'simontaxi' ),
					'url' => admin_url( 'admin.php?page=manage_bookings&status=onride' ),
					'spanclass' => 'status-count bg-warning',
					'anchorclass' => '',
				),
				
				'success' => array(
					'title' => esc_html__( 'Completed', 'simontaxi' ),
					'url' => admin_url( 'admin.php?page=manage_bookings&status=success' ),
					'spanclass' => 'status-count bg-happygreen',
					'anchorclass' => '',
				),
				
				'cancelled' => array(
					'title' => esc_html__( 'Cancelled', 'simontaxi' ),
					'url' => admin_url( 'admin.php?page=manage_bookings&status=cancelled' ),
					'spanclass' => 'status-count bg-cancel',
					'anchorclass' => '',
				),
				
				'all' => array(
					'title' => esc_html__( 'All', 'simontaxi' ),
					'url' => admin_url( 'admin.php?page=manage_bookings&status=all' ),
					'spanclass' => 'status-count bg-sky',
					'anchorclass' => '',
				),
			)
		);
	}
endif;

if ( ! function_exists( 'simontaxi_admin_update_buttons' ) ) :
	/**
	 * Return default booking statuses
	 *
	 * @since 2.0.9
	 * @return string
	 */
	function simontaxi_admin_update_buttons() {
		return apply_filters( 'simontaxi_admin_update_buttons', 
			array(
				'change' => array(
					'title' => esc_html__( 'Status', 'simontaxi' ),
					'url' => admin_url( 'admin.php?page=manage_bookings&change_status=change' ),
					'spanclass' => 'status-count bg-danger',
					'anchorclass' => '',
				),
				'location' => array(
					'title' => esc_html__( 'Location', 'simontaxi' ),
					'url' => admin_url( 'admin.php?page=manage_bookings&change_status=location' ),
					'spanclass' => 'status-count bg-danger',
					'anchorclass' => '',
				),				
				'vehicle' => array(
					'title' => esc_html__( 'Vehicle', 'simontaxi' ),
					'url' => admin_url( 'admin.php?page=manage_bookings&change_status=vehicle' ),
					'spanclass' => 'status-count bg-purple',
					'anchorclass' => '',
				),
				'personal' => array(
					'title' => esc_html__( 'Personal', 'simontaxi' ),
					'url' => admin_url( 'admin.php?page=manage_bookings&change_status=personal' ),
					'spanclass' => 'status-count bg-warning',
					'anchorclass' => '',
				),
				'payment' => array(
					'title' => esc_html__( 'Payment', 'simontaxi' ),
					'url' => admin_url( 'admin.php?page=manage_bookings&change_status=payment' ),
					'spanclass' => 'status-count bg-happygreen',
					'anchorclass' => '',
				),
			)
		);
	}
endif;

function simontaxi_week_days() {
	$timestamp = strtotime('next Sunday');
	$days = array();
	for ($i = 0; $i < 7; $i++) {
		$days[] = strftime('%A', $timestamp);
		$timestamp = strtotime('+1 day', $timestamp);
	}
	return $days;
}

function simontaxi_convert_seconds_to_readable( $seconds ){
	$string = "";

	$days = intval(intval($seconds) / (3600*24));
	$hours = (intval($seconds) / 3600) % 24;
	$minutes = (intval($seconds) / 60) % 60;
	$seconds = (intval($seconds)) % 60;

	if($days> 0){
		$string .= "$days ".esc_html__('Days', 'simontaxi')." ";
	}
	if($hours > 0){
		$string .= "$hours ".esc_html__('Hours', 'simontaxi')." ";
	}
	if($minutes > 0){
		$string .= "$minutes ".esc_html__('Minutes', 'simontaxi')." ";
	}
	if ($seconds > 0){
		$string .= "$seconds ".esc_html__('Seconds', 'simontaxi');
	}
	return $string;
}

function simontaxi_default_pages( $key = '' ) {
$simontaxi_pages = apply_filters( 'simontaxi_flt_default_pages', array(
			'login' => array(
				'id'          => 'simontaxi_signin',
				'name'        => __( 'Sign In', 'simontaxi' ),
				'slug'        => 'sign-in',
				'desc'        => __( 'Sign In.', 'simontaxi' ),
				'shortcode'   => 'simontaxi_signin',
				'type'        => 'page',
				'template'    => 'templates/template-simonpage.php',
			),
			'registration' => array(
				'id'          => 'simontaxi_registration',
				'name'        => __( 'Registration', 'simontaxi' ),
				'slug'        => 'registration',
				'desc'        => __( 'Registration.', 'simontaxi' ),
				'shortcode'   => 'simontaxi_registration',
				'type'        => 'page',
				'template'    => 'templates/template-simonpage.php',
			),
			
			'forgotpassword' => array(
				'id'          => 'simontaxi_forgotpassword',
				'name'        => __( 'forgotpassword', 'simontaxi' ),
				'slug'        => 'forgotpassword',
				'desc'        => __( 'forgotpassword.', 'simontaxi' ),
				'shortcode'   => 'simontaxi_forgotpassword',
				'type'        => 'page',
				'template'    => 'templates/template-simonpage.php',
			),
			'resetpassword' => array(
				'id'          => 'simontaxi_resetpassword',
				'name'        => __( 'resetpassword', 'simontaxi' ),
				'slug'        => 'resetpassword',
				'desc'        => __( 'resetpassword.', 'simontaxi' ),
				'shortcode'   => 'simontaxi_resetpassword',
				'type'        => 'page',
				'template'    => 'templates/template-simonpage.php',
			),
			'step1' => array(
				'id'          => 'simontaxi_booking_step1',
				'name'        => __( 'Pick Locations', 'simontaxi' ),
				'slug'        => 'pick-locations',
				'desc'        => __( 'Pick Locations.', 'simontaxi' ),
				'shortcode'   => 'simontaxi_booking_step1',
				'type'        => 'page',
				'template'    => 'templates/template-fullwidth.php',
			),
			'step2' => array(
				'id'          => 'simontaxi_booking_step2',
				'name'        => __( 'Select Vehicle', 'simontaxi' ),
				'slug'        => 'select-vehicle',
				'desc'        => __( 'Select Vehicle.', 'simontaxi' ),
				'shortcode'   => 'simontaxi_booking_step2',
				'type'        => 'page',
				'template'    => 'templates/template-fullwidth.php',
			),
			'step3' => array(
				'id'          => 'simontaxi_booking_step3',
				'name'        => __( 'Confirm Booking', 'simontaxi' ),
				'slug'        => 'confirm-booking',
				'desc'        => __( 'Confirm Booking.', 'simontaxi' ),
				'shortcode'   => 'simontaxi_booking_step3',
				'type'        => 'page',
				'template'    => 'templates/template-fullwidth.php',
			),
			'step4' => array(
				'id'          => 'simontaxi_booking_step4',
				'name'        => __( 'Select Payment Method', 'simontaxi' ),
				'slug'        => 'select-payment-method',
				'desc'        => __( 'Select Payment Method.', 'simontaxi' ),
				'shortcode'   => 'simontaxi_booking_step4',
				'type'        => 'page',
				'template'    => 'templates/template-fullwidth.php',
			),
			'proceed_to_pay' => array(
				'id'          => 'simontaxi_proceed_to_pay',
				'name'        => __( 'Proceed to Pay', 'simontaxi' ),
				'slug'        => 'proceed-to-pay',
				'desc'        => __( 'Proceed to Pay.', 'simontaxi' ),
				'shortcode'   => 'simontaxi_proceed_to_pay',
				'type'        => 'page',
				'template'    => 'templates/template-fullwidth.php',
			),
			'payment_success' => array(
				'id'          => 'simontaxi_payment_success',
				'name'        => __( 'Payment success', 'simontaxi' ),
				'slug'        => 'payment-success',
				'desc'        => __( 'Payment success.', 'simontaxi' ),
				'shortcode'   => 'simontaxi_payment_success',
				'type'        => 'page',
				'template'    => 'templates/template-fullwidth.php',
			),
			'payment_final' => array(
				'id'          => 'simontaxi_payment_final',
				'name'        => __( 'Payment Final', 'simontaxi' ),
				'slug'        => 'payment-final',
				'desc'        => __( 'Payment Final.', 'simontaxi' ),
				'shortcode'   => 'simontaxi_payment_final',
				'type'        => 'page',
				'template'    => 'templates/template-fullwidth.php',
			),
			'user_bookings' => array(
				'id'          => 'simontaxi_user_bookings',
				'name'        => __( 'User Bookings', 'simontaxi' ),
				'slug'        => 'user-bookings',
				'desc'        => __( 'User Bookings.', 'simontaxi' ),
				'shortcode'   => 'simontaxi_user_bookings',
				'type'        => 'page',
				'template'    => 'templates/template-simonpage.php',
			),
			'user_payments' => array(
				'id'          => 'simontaxi_user_payment_history',
				'name'        => __( 'User Payments', 'simontaxi' ),
				'slug'        => 'user-payments',
				'desc'        => __( 'user-payments.', 'simontaxi' ),
				'shortcode'   => 'simontaxi_user_payment_history',
				'type'        => 'page',
				'template'    => 'templates/template-simonpage.php',
			),
			'user_account' => array(
				'id'          => 'simontaxi_user_account',
				'name'        => __( 'User Account', 'simontaxi' ),
				'slug'        => 'user-account',
				'desc'        => __( 'User Account.', 'simontaxi' ),
				'shortcode'   => 'simontaxi_user_account',
				'type'        => 'page',
				'template'    => 'templates/template-simonpage.php',
			),
			'activate-account' => array(
				'id'          => 'simontaxi_user_activate_account',
				'name'        => __( 'Activate Account', 'simontaxi' ),
				'slug'        => 'activate-account',
				'desc'        => __( 'Activate Account.', 'simontaxi' ),
				'shortcode'   => 'simontaxi_user_activate_account',
				'type'        => 'page',
				'template'    => 'templates/template-simonpage.php',
			),
			'billing_address' => array(
				'id'          => 'simontaxi_user_billing_address',
				'name'        => __( 'User Billing Address', 'simontaxi' ),
				'slug'        => 'user-billing-address',
				'desc'        => __( 'User Billing Address.', 'simontaxi' ),
				'shortcode'   => 'simontaxi_user_billing_address',
				'type'        => 'page',
				'template'    => 'templates/template-simonpage.php',
			),
			'user_support' => array(
				'id'          => 'simontaxi_user_support',
				'name'        => __( 'User Support', 'simontaxi' ),
				'slug'        => 'user-support',
				'desc'        => __( 'User Support.', 'simontaxi' ),
				'shortcode'   => 'simontaxi_user_support',
				'type'        => 'page',
				'template'    => 'templates/template-simonpage.php',
			),
			'start_over' => array(
				'id'          => 'simontaxi_vehicle_clear_selections',
				'name'        => __( 'Clear Selections', 'simontaxi' ),
				'slug'        => 'clear-selections',
				'desc'        => __( 'Clear Selections.', 'simontaxi' ),
				'shortcode'   => 'simontaxi_vehicle_clear_selections',
				'type'        => 'page',
				'template'    => 'templates/template-simonpage.php',
			),
			
			// Email Templates
			'new_user' => array(
				'id'          => 'new-user',
				'name'        => __( 'new-user', 'simontaxi' ),
				'desc'        => '&nbsp;
<h1>{FIRST_NAME}</h1>
<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
<div class="header" style="background: #f5f5f5; padding: 20px;">
<h1>{BLOG_TITLE}</h1>
<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE}</a></div>
</div>
{new_user_mail_additional_top}
<div class="content" style="padding: 20px;"><center><span style="color: #00ccff;"><strong>Congratulations..! welcome to {BLOG_TITLE}</strong></span></center>
<p style="text-align: center;"><span style="color: #333333;">Thank you for For Registering  with {BLOG_TITLE}</span></p>
<p style="text-align: center;"><span style="color: #333333;">User Name : {USERNAME}</span></p>
<p style="text-align: center;"><span style="color: #333333;">Password : {PASSWORD}</span></p>
<p style="text-align: center;"><span style="color: #333333;">{ACTIVATION_LINK}</span></p>
<p style="text-align: center;"><span style="color: #333333;">Use this coupon code {COUPON_CODE} to get discount on your 1st booking</span></p>
<p style="text-align: center;"><span style="color: #333333;">Thanks</span></p>

</div>
{new_user_mail_additional_bottom}
<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;"><span style="float: right;">Copyright © 2016 {BLOG_TITLE} . All right reserved Inc. </span></div>
</div>
<h1>{FIRST_NAME}</h1>
&nbsp;',
				'shortcode'   => '',
				'type'        => 'emailtemplate',
			),
			'activation_link' => array(
				'id'          => 'activation_link',
				'name'        => __( 'activation-link', 'simontaxi' ),
				'desc'        => '&nbsp;
<h1>{FIRST_NAME}</h1>
<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
<div class="header" style="background: #f5f5f5; padding: 20px;">
<h1>{BLOG_TITLE}</h1>
<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE}</a></div>
</div>
{activation_link_mail_additional_top}
<div class="content" style="padding: 20px;"><center><span style="color: #00ccff;"><strong>Congratulations..! welcome to {BLOG_TITLE}</strong></span></center>
<p style="text-align: center;"><span style="color: #333333;">Thank you for For your interest with {BLOG_TITLE}</span></p>
<p style="text-align: center;"><span style="color: #333333;">User Name : {USERNAME}</span></p>
<p style="text-align: center;"><span style="color: #333333;">Password : {PASSWORD}</span></p>
<p style="text-align: center;"><span style="color: #333333;">{ACTIVATION_LINK}</span></p>
<p style="text-align: center;"><span style="color: #333333;">Use this coupon code {COUPON_CODE} to get discount on your 1st booking</span></p>
<p style="text-align: center;"><span style="color: #333333;">Thanks</span></p>

</div>
{activation_link_mail_additional_bottom}
<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;"><span style="float: right;">Copyright © 2016 {BLOG_TITLE} . All right reserved Inc. </span></div>
</div>
<h1>{FIRST_NAME}</h1>
&nbsp;',
				'shortcode'   => '',
				'type'        => 'emailtemplate',
			),
			'resetpassword_mail' => array(
				'id'          => 'resetpassword-mail',
				'name'        => __( 'resetpassword-mail', 'simontaxi' ),
				'desc'        => '<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
							<div class="header" style="background: #f5f5f5; padding: 20px;">
							<h1>{BLOG_TITLE}</h1>
							<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE}</a></div>
							</div>
							{resetpassword_mail_additional_top}
							<div class="content" style="padding: 20px;"><center><span style="color: #00ccff;"><strong>Welcome to {BLOG_TITLE}</strong></span></center>
							<p style="text-align: center;"><span style="color: #333333;">Someone requested that the password be reset for the following account on {BLOG_TITLE}</span></p>
							<p>	{BLOG_LINK} </p>
							<p style="text-align: center;"><span style="color: #333333;">Username or Email</span> {USER_NAME}</p>
							<p style="text-align: center;"><span style="color: #333333;">If this was a mistake, just ignore this email and nothing will happen.</span></p>

							<p style="text-align: center;"><span style="color: #333333;">To reset your password, visit the following address:</span></p>

							<p>{RESET_LINK}</p>

							<p style="text-align: center;"><span style="color: #333333;">Thanks</span></p>

							</div>
							{resetpassword_mail_additional_bottom}
							<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;"><span style="float: right;">Copyright © 2016 {BLOG_TITLE} . All right reserved Inc. </span></div>
							</div>',
				'shortcode'   => '',
				'type'        => 'emailtemplate',
			),
			'booking_success' => array(
				'id'          => 'booking-success',
				'name'        => __( 'booking-success', 'simontaxi' ),
				'desc'        => '&nbsp;
			<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
			<div class="header" style="background: #f5f5f5; padding: 20px;">
			<h1>{BLOG_TITLE}</h1>
			<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE} </a></div>
			</div>
			<h1><span style="color: #ff6600;">Your Booking Details</span></h1>
			<div class="">
			{booking_success_mail_additional_top}
			<table class="booking-status-update">
			<tbody>
			<tr>
			<td style="text-align: justify;" width="20%">
			<blockquote><span style="color: #993366;">Booking Reference</span></blockquote>
			</td>
			<td style="text-align: justify;">
			<blockquote><span style="color: #993366;">:{INVOICE}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Name</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{NAME}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Mobile</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{MOBILE}</span></blockquote>
			</td>
			</tr>

			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Email</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{EMAIL}</span></blockquote>
			</td>
			</tr>

			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">From</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{FROM}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">TO</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{TO}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Journy Date</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{JOURNY_DATE}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Journy Time</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{JOURNY_TIME}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Jounry Type</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{JOURNY_TYPE}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Amount Payable</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{AMOUNT}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Paid</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAID}</span></blockquote>
							</td>
							</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Payment Status</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{PAYMENT_STATUS}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Payment Method</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{PAYMENT_METHOD}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Flight Number</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{FLIGHT_NUMBER}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Number of Passengers</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{NO_OF_PASSENGERS}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Special Instructions</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{SPECIAL_INSTRUCTIONS}</span></blockquote>
			</td>
			</tr>
			</tbody>
			</table>
			{booking_success_mail_additional_bottom}
			</div>
			<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;"><span style="float: center;">Copyright © 2018 {BLOG_TITLE} All right reserved Inc. </span></div>
			</div>
			&nbsp;',
				'shortcode'   => '',
				'type'        => 'emailtemplate',
			),
			'booking_status' => array(
				'id'          => 'booking-status',
				'name'        => __( 'booking-status', 'simontaxi' ),
				'desc'        => '&nbsp;
							<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
							<div class="header" style="background: #f5f5f5; padding: 20px;">
							<h1><span style="color: #0000ff;">{BLOG_TITLE}</span></h1>
							<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE} </a></div>
							</div>
							<h1 style="text-align: center;"><span style="color: #ff6600;"><strong>Booking Status updated</strong></span></h1>
							
							<h2 style="text-align: center;"><span style="color: #0000ff;">Booking Details</span></h2>
							<div class="">
							{booking_status_mail_additional_top}
							<table class="booking-status-update" style="height: 458px;" width="1266">
							<tbody>
							<tr>
							<td width="20%">Reference</td>
							<td>:<span style="color: #ff0000;">{BOOKING_REF}</span></td>
							</tr>
							<tr>
							<td width="20%">Journey Type</td>
							<td>:{JOURNEY_TYPE}</td>
							</tr>
							<tr>
							<td width="20%">From</td>
							<td>:{PICKUP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">To</td>
							<td>: {DROP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Date</td>
							<td>: {PICKUP_DATE}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Time</td>
							<td>: {PICKUP_TIME}</td>
							</tr>
							<tr>
							<td width="20%">Name</td>
							<td>: {CONTACT_NAME}</td>
							</tr>
							<tr>
							<td width="20%">Mobile</td>
							<td>: {CONTACT_MOBILE}</td>
							</tr>
							<tr>
							<td width="20%">Email</td>
							<td>: {CONTACT_EMAIL}</td>
							</tr>
							<tr>
							<td width="20%">Current Status:</td>
							<td>:{BOOKING_STATUS}</td>
							</tr>
							<tr>
							
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Payable</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{AMOUNT}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Paid</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAID}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Status</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_STATUS}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Method</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_METHOD}</span></blockquote>
							</td>
							</tr>

							<td width="20%">Status Updated Time:</td>
							<td>:{BOOKING_STATUS_UPDATED}</td>
							</tr>
							
							<tr>
							<td width="20%">Comments:</td>
							<td>:{REASON}</td>
							</tr>
							
							</tbody>
							</table>
							{booking_status_mail_additional_bottom}
							</div>
							<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;">

							<span style="float: right;">Copyright © 2016 <span style="color: #0000ff;">{BLOG_TITLE}</span> All right reserved Inc. </span>

							</div>
							</div>
							&nbsp;',
				'shortcode'   => '',
				'type'        => 'emailtemplate',
			),
			'booking_confirmed' => array(
				'id'          => 'booking-confirmed',
				'name'        => __( 'booking-confirmed', 'simontaxi' ),
				'desc'        => '&nbsp;
							<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
							<div class="header" style="background: #f5f5f5; padding: 20px;">
							<h1><span style="color: #0000ff;">{BLOG_TITLE}</span></h1>
							<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE} </a></div>
							</div>
							<h1 style="text-align: center;"><span style="color: #ff6600;"><strong>Booking Status updated</strong></span></h1>
							
							<h2 style="text-align: center;"><span style="color: #0000ff;">Booking Details</span></h2>
							<div class="">
							{booking_confirmed_mail_additional_top}
							<table class="booking-status-update" style="height: 458px;" width="1266">
							<tbody>
							<tr>
							<td width="20%">Reference</td>
							<td>:<span style="color: #ff0000;">{BOOKING_REF}</span></td>
							</tr>
							<tr>
							<td width="20%">Journey Type</td>
							<td>:{JOURNEY_TYPE}</td>
							</tr>
							<tr>
							<td width="20%">From</td>
							<td>:{PICKUP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">To</td>
							<td>: {DROP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Date</td>
							<td>: {PICKUP_DATE}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Time</td>
							<td>: {PICKUP_TIME}</td>
							</tr>
							<tr>
							<td width="20%">Name</td>
							<td>: {CONTACT_NAME}</td>
							</tr>
							<tr>
							<td width="20%">Mobile</td>
							<td>: {CONTACT_MOBILE}</td>
							</tr>
							<tr>
							<td width="20%">Email</td>
							<td>: {CONTACT_EMAIL}</td>
							</tr>
							<tr>
							<td width="20%">Current Status:</td>
							<td>:{BOOKING_STATUS}</td>
							</tr>
							
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Payable</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{AMOUNT}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Paid</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAID}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Status</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_STATUS}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Method</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_METHOD}</span></blockquote>
							</td>
							</tr>

							<tr>
							<td width="20%">Status Updated Time:</td>
							<td>:{BOOKING_STATUS_UPDATED}</td>
							</tr>
							
							<tr>
							<td width="20%">Comments:</td>
							<td>:{REASON}</td>
							</tr>
							
							</tbody>
							</table>
							{booking_confirmed_mail_additional_bottom}
							</div>
							<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;">

							<span style="float: right;">Copyright © 2016 <span style="color: #0000ff;">{BLOG_TITLE}</span> All right reserved Inc. </span>

							</div>
							</div>
							&nbsp;',
				'shortcode'   => '',
				'type'        => 'emailtemplate',
			),
			'booking_cancel' => array(
				'id'          => 'booking-cancel',
				'name'        => __( 'booking-cancel', 'simontaxi' ),
				'desc'        => '&nbsp;
							<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
							<div class="header" style="background: #f5f5f5; padding: 20px;">
							<h1><span style="color: #0000ff;">{BLOG_TITLE}</span></h1>
							<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE} </a></div>
							</div>
							<h1 style="text-align: center;"><span style="color: #ff6600;"><strong>Booking Status updated</strong></span></h1>
							
							<h2 style="text-align: center;"><span style="color: #0000ff;">Booking Details</span></h2>
							<div class="">
							{booking_cancel_mail_additional_top}
							<table class="booking-status-update" style="height: 458px;" width="1266">
							<tbody>
							<tr>
							<td width="20%">Reference</td>
							<td>:<span style="color: #ff0000;">{BOOKING_REF}</span></td>
							</tr>
							<tr>
							<td width="20%">Journey Type</td>
							<td>:{JOURNEY_TYPE}</td>
							</tr>
							<tr>
							<td width="20%">From</td>
							<td>:{PICKUP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">To</td>
							<td>: {DROP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Date</td>
							<td>: {PICKUP_DATE}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Time</td>
							<td>: {PICKUP_TIME}</td>
							</tr>
							<tr>
							<td width="20%">Name</td>
							<td>: {CONTACT_NAME}</td>
							</tr>
							<tr>
							<td width="20%">Mobile</td>
							<td>: {CONTACT_MOBILE}</td>
							</tr>
							<tr>
							<td width="20%">Email</td>
							<td>: {CONTACT_EMAIL}</td>
							</tr>
							<tr>
							<td width="20%">Current Status:</td>
							<td>:{BOOKING_STATUS}</td>
							</tr>
							
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Payable</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{AMOUNT}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Paid</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAID}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Status</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_STATUS}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Method</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_METHOD}</span></blockquote>
							</td>
							</tr>

							<tr>
							<td width="20%">Status Updated Time:</td>
							<td>:{BOOKING_STATUS_UPDATED}</td>
							</tr>
							<tr>
							<td width="20%">Comments:</td>
							<td>:{REASON}</td>
							</tr>
							</tbody>
							</table>
							{booking_cancel_mail_additional_bottom}
							</div>
							<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;">

							<span style="float: right;">Copyright © 2016 <span style="color: #0000ff;">{BLOG_TITLE}</span> All right reserved Inc. </span>

							</div>
							</div>
							&nbsp;',
				'shortcode'   => '',
				'type'        => 'emailtemplate',
			),
			'payment_status' => array(
				'id'          => 'payment-status',
				'name'        => __( 'payment-status', 'simontaxi' ),
				'desc'        => '&nbsp;
					<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
					<div class="header" style="background: #f5f5f5; padding: 20px;">
					<h1><span style="color: #0000ff;">{BLOG_TITLE}</span></h1>
					<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE} </a></div>
					</div>
					<h1 style="text-align: center;"><span style="color: #ff6600;"><strong>Payment Status updated</strong></span></h1>
					
					<h2 style="text-align: center;"><span style="color: #0000ff;">Payment Details</span></h2>
					<div class="">
					{payment_status_mail_additional_top}
					<table class="payment-status-update" style="height: 458px;" width="1266">
					<tbody>
					<tr>
					<td width="20%">Reference</td>
					<td>:<span style="color: #ff0000;">{BOOKING_REF}</span></td>
					</tr>
					<tr>
					<td width="20%">Journey Type</td>
					<td>:{JOURNEY_TYPE}</td>
					</tr>
					<tr>
					<td width="20%">From</td>
					<td>:{PICKUP_LOCATION}</td>
					</tr>
					<tr>
					<td width="20%">To</td>
					<td>: {DROP_LOCATION}</td>
					</tr>
					<tr>
					<td width="20%">Pickup Date</td>
					<td>: {PICKUP_DATE}</td>
					</tr>
					<tr>
					<td width="20%">Pickup Time</td>
					<td>: {PICKUP_TIME}</td>
					</tr>
					<tr>
					<td width="20%">Name</td>
					<td>: {CONTACT_NAME}</td>
					</tr>
					<tr>
					<td width="20%">Mobile</td>
					<td>: {CONTACT_MOBILE}</td>
					</tr>
					<tr>
					<td width="20%">Email</td>
					<td>: {CONTACT_EMAIL}</td>
					</tr>
					<tr>
					<td width="20%">Current Status:</td>
					<td>:{PAYMENT_STATUS}</td>
					</tr>
					
					<tr style="text-align: justify;">
					<td width="20%">
					<blockquote><span style="color: #993366;">Amount Payable</span></blockquote>
					</td>
					<td>
					<blockquote><span style="color: #993366;">:{AMOUNT}</span></blockquote>
					</td>
					</tr>
					<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Paid</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAID}</span></blockquote>
							</td>
							</tr>
					<tr style="text-align: justify;">
					<td width="20%">
					<blockquote><span style="color: #993366;">Payment Status</span></blockquote>
					</td>
					<td>
					<blockquote><span style="color: #993366;">:{PAYMENT_STATUS}</span></blockquote>
					</td>
					</tr>
					<tr style="text-align: justify;">
					<td width="20%">
					<blockquote><span style="color: #993366;">Payment Method</span></blockquote>
					</td>
					<td>
					<blockquote><span style="color: #993366;">:{PAYMENT_METHOD}</span></blockquote>
					</td>
					</tr>

					<tr>
					<td width="20%">Status Updated Time:</td>
					<td>:{BOOKING_STATUS_UPDATED}</td>
					</tr>
					
					<tr>
							<td width="20%">Comments:</td>
							<td>:{REASON}</td>
							</tr>
							
					</tbody>
					</table>
					{payment_status_mail_additional_bottom}
					</div>
					<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;">

					<span style="float: right;">Copyright © 2016 <span style="color: #0000ff;">{BLOG_TITLE}</span> All right reserved Inc. </span>

					</div>
					</div>
					&nbsp;',
				'shortcode'   => '',
				'type'        => 'emailtemplate',
			),
			'ride_start' => array(
				'id'          => 'ride-start',
				'name'        => __( 'ride-start', 'simontaxi' ),
				'desc'        => '&nbsp;
							<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
							<div class="header" style="background: #f5f5f5; padding: 20px;">
							<h1><span style="color: #0000ff;">{BLOG_TITLE}</span></h1>
							<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE} </a></div>
							</div>
							<h1 style="text-align: center;"><span style="color: #ff6600;"><strong>Your ride start now</strong></span></h1>
							
							<h2 style="text-align: center;"><span style="color: #0000ff;">Booking Details</span></h2>
							<div class="">
							{ride_start_mail_additional_top}
							<table class="booking-status-update" style="height: 458px;" width="1266">
							<tbody>
							<tr>
							<td width="20%">Reference</td>
							<td>:<span style="color: #ff0000;">{BOOKING_REF}</span></td>
							</tr>
							<tr>
							<td width="20%">Journey Type</td>
							<td>:{JOURNEY_TYPE}</td>
							</tr>
							<tr>
							<td width="20%">From</td>
							<td>:{PICKUP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">To</td>
							<td>: {DROP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Date</td>
							<td>: {PICKUP_DATE}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Time</td>
							<td>: {PICKUP_TIME}</td>
							</tr>
							<tr>
							<td width="20%">Name</td>
							<td>: {CONTACT_NAME}</td>
							</tr>
							<tr>
							<td width="20%">Mobile</td>
							<td>: {CONTACT_MOBILE}</td>
							</tr>
							<tr>
							<td width="20%">Email</td>
							<td>: {CONTACT_EMAIL}</td>
							</tr>
							<tr>
							<td width="20%">Current Status:</td>
							<td>:{BOOKING_STATUS}</td>
							</tr>
							
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Payable</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{AMOUNT}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Paid</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAID}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Status</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_STATUS}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Method</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_METHOD}</span></blockquote>
							</td>
							</tr>

							<tr>
							<td width="20%">Status Updated Time:</td>
							<td>:{BOOKING_STATUS_UPDATED}</td>
							</tr>
							
							<tr>
							<td width="20%">Instructions:</td>
							<td>:{REASON}</td>
							</tr>
							
							</tbody>
							</table>
							{ride_start_mail_additional_bottom}
							</div>
							<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;">

							<span style="float: right;">Copyright © 2016 <span style="color: #0000ff;">{BLOG_TITLE}</span> All right reserved Inc. </span>

							</div>
							</div>
							&nbsp;',
				'shortcode'   => '',
				'type'        => 'emailtemplate',
			),
			'ride_completed' => array(
				'id'          => 'ride-completed',
				'name'        => __( 'ride-completed', 'simontaxi' ),
				'desc'        => '&nbsp;
							<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
							<div class="header" style="background: #f5f5f5; padding: 20px;">
							<h1><span style="color: #0000ff;">{BLOG_TITLE}</span></h1>
							<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE} </a></div>
							</div>
							<h1 style="text-align: center;"><span style="color: #ff6600;"><strong>Congratulations your ride completed.</strong></span></h1>
							
							<h2 style="text-align: center;"><span style="color: #0000ff;">Booking Details</span></h2>
							<div class="">
							{ride_completed_mail_additional_top}
							<table class="booking-status-update" style="height: 458px;" width="1266">
							<tbody>
							<tr>
							<td width="20%">Reference</td>
							<td>:<span style="color: #ff0000;">{BOOKING_REF}</span></td>
							</tr>
							<tr>
							<td width="20%">Journey Type</td>
							<td>:{JOURNEY_TYPE}</td>
							</tr>
							<tr>
							<td width="20%">From</td>
							<td>:{PICKUP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">To</td>
							<td>: {DROP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Date</td>
							<td>: {PICKUP_DATE}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Time</td>
							<td>: {PICKUP_TIME}</td>
							</tr>
							<tr>
							<td width="20%">Name</td>
							<td>: {CONTACT_NAME}</td>
							</tr>
							<tr>
							<td width="20%">Mobile</td>
							<td>: {CONTACT_MOBILE}</td>
							</tr>
							<tr>
							<td width="20%">Email</td>
							<td>: {CONTACT_EMAIL}</td>
							</tr>
							<tr>
							<td width="20%">Current Status:</td>
							<td>:{BOOKING_STATUS}</td>
							</tr>
							
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Payable</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{AMOUNT}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Paid</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAID}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Status</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_STATUS}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Method</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_METHOD}</span></blockquote>
							</td>
							</tr>

							<tr>
							<td width="20%">Status Updated Time:</td>
							<td>:{BOOKING_STATUS_UPDATED}</td>
							</tr>
							
							<tr>
							<td width="20%">Instructions:</td>
							<td>:{REASON}</td>
							</tr>
							
							</tbody>
							</table>
							{ride_completed_mail_additional_bottom}
							</div>
							<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;">

							<span style="float: right;">Copyright © 2016 <span style="color: #0000ff;">{BLOG_TITLE}</span> All right reserved Inc. </span>

							</div>
							</div>
							&nbsp;',
				'shortcode'   => '',
				'type'        => 'emailtemplate',
			),
			
			// SMS Templates
			'sms_new_user' => array(
				'id'          => 'sms-new-user',
				'name'        => __( 'sms-new-user', 'simontaxi' ),
				'desc'        => '{sms_new_user_additional_top} Thank you for Registering with {BLOG_TITLE}. Please login to book a cab. {sms_new_user_additional_bottom}',
				'shortcode'   => '',
				'type'        => 'smstemplate',
			),
			'sms_booking_success' => array(
				'id'          => 'sms-booking-success',
				'name'        => __( 'sms-booking-success', 'simontaxi' ),
				'desc'        => '{sms_booking_success_additional_top} Booking Success!
Ref ID: {BOOKING_REF}
From : {FROM}
To: {TO}
Amount: {AMOUNT}
Payment: {PAYMENT_STATUS}
{DATE} {sms_booking_success_additional_bottom}',
				'shortcode'   => '',
				'type'        => 'smstemplate',
			),
			'sms_booking_confirmed' => array(
				'id'          => 'sms-booking-confirmed',
				'name'        => __( 'sms-booking-confirmed', 'simontaxi' ),
				'desc'        => '{sms_booking_confirmed_additional_top} Booking Confirmed!
Ref ID: {BOOKING_REF}
From : {FROM}
To: {TO}
Date: {PICKUP_DATE}
Time: {PICKUP_TIME}
Car Plate: {CAR_PLATE} {sms_booking_confirmed_additional_bottom}',
				'shortcode'   => '',
				'type'        => 'smstemplate',
			),
			'sms_booking_cancel' => array(
				'id'          => 'sms-booking-cancel',
				'name'        => __( 'sms-booking-cancel', 'simontaxi' ),
				'desc'        => '{sms_booking_cancel_additional_top} Booking Cancelled!
Ref ID: {BOOKING_REF}
From : {FROM}
To: {TO}
Date: {PICKUP_DATE}
Time: {PICKUP_TIME}
Reason: {REASON} {sms_booking_cancel_additional_bottom}',
				'shortcode'   => '',
				'type'        => 'smstemplate',
			),
			'sms_booking_status' => array(
				'id'          => 'sms-booking-status',
				'name'        => __( 'sms-booking-status', 'simontaxi' ),
				'desc'        => '{sms_booking_status_additional_top} Booking Status updated!
Ref ID: {BOOKING_REF}
Status:{BOOKING_STATUS_UPDATED}
{DATE} {sms_booking_status_additional_bottom}',
				'shortcode'   => '',
				'type'        => 'smstemplate',
			),
			'sms_payment_status' => array(
				'id'          => 'sms-payment-status',
				'name'        => __( 'sms-payment-status', 'simontaxi' ),
				'desc'        => '{sms_payment_status_additional_top} Payment Status Updated!
Payment ID:{PAYMENT_REF}
Status: {PAYMENT_STATUS}
{DATE} {sms_payment_status_additional_bottom}',
				'shortcode'   => '',
				'type'        => 'smstemplate',
			),
			'sms_ride_start' => array(
				'id'          => 'sms-ride-start',
				'name'        => __( 'sms-ride-start', 'simontaxi' ),
				'desc'        => '{sms_ride_start_additional_top} Your Ride Start Now!
Ref ID: {BOOKING_REF}
From : {FROM}
To: {TO}
Date: {PICKUP_DATE}
Time: {PICKUP_TIME}
Car Plate: {CAR_PLATE} {sms_ride_start_additional_bottom}',
				'shortcode'   => '',
				'type'        => 'smstemplate',
			),
			'sms_ride_completed' => array(
				'id'          => 'sms-ride-completed',
				'name'        => __( 'sms-ride-completed', 'simontaxi' ),
				'desc'        => '{sms_ride_completed_additional_top} Congratulations your ride completed!
Ref ID: {BOOKING_REF}
From : {FROM}
To: {TO}
Date: {PICKUP_DATE}
Time: {PICKUP_TIME}
Car Plate: {CAR_PLATE} {sms_ride_completed_additional_bottom}',
				'shortcode'   => '',
				'type'        => 'smstemplate',
			),
		)
);
if ( ! empty( $key ) ) {
	if ( ! empty( $simontaxi_pages[ $key ] ) ) {
		$simontaxi_pages = $simontaxi_pages[ $key ];
	} else {
		$simontaxi_pages = true;
	}
}
return $simontaxi_pages;
}

/**
 * Defer Pasing JavaScript for entire website, including Plugins, but without jQuery.js
 * Feel free to change "async" to "defer". $files will be excluded
 *
 * @since 2.0.9
*/
function simontaxi_defer_parsing_of_js($url)
{
	//Specify which files to EXCLUDE from defer method. Always add jquery.js
    $files = array('jquery.js', 'select2.js', 'select2.min.js', 'autocomplete.min.js', 'menu.min.js', 'datepicker.min.js', 'datepicker.js' );
	//let's not break back-end
    if ( ! is_admin() ) {
        if (false === strpos($url, '.js')) {
            return $url;
        }
        foreach ($files as $file) {
            if (strpos($url, $file)) {
                return $url;
            }
        }
    } else {
        return $url;
    }
    return "$url' async='async";
}
// add_filter('clean_url', 'simontaxi_defer_parsing_of_js', 11, 1);

/**
 * Remove query strings from static resources
 *
 * @since 2.0.9
 */
function simontaxi_remove_cssjs_ver( $src ) {
	if( strpos( $src, '?ver=' ) ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}
add_filter( 'style_loader_src', 'simontaxi_remove_cssjs_ver', 10, 2 );
add_filter( 'script_loader_src', 'simontaxi_remove_cssjs_ver', 10, 2 );
