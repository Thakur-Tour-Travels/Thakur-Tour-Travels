<?php
/**
* Vehicle Settings Class
*
* Handles settings related to vehicle booking.
*
* @class 		Vehicle_Settings
* @package		Simontaxi - Vehicle Booking
* @category	Class
* @copyright   Copyright (c) 2017, Digisamaritan
* @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;	
}

	class Simontaxi_Vehicle_settings {


	protected static $_instance = null;

	static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}		
		return self::$_instance;
	}


	public function __construct() {
		$this->Vehicle_settings();
	}

	public function Vehicle_settings() {
		// General Settings.
		$default_title = simontaxi_get_option( 'default_title', esc_html__( 'Vehicle', 'simontaxi' ) );
		$vehicle_places = simontaxi_get_option( 'vehicle_places', 'googleall' );
		/**
		* @since 1.0.1
		*/
		$vehicle_places_dropoff = simontaxi_get_option( 'vehicle_places_dropoff', 'googleall' );
		$google_api = simontaxi_get_option( 'google_api', 'AIzaSyCqRV6HQ_BSw3MMjPen2bT2IwDnZgfjwu4' );
		$distance_taken_from = simontaxi_get_option( 'distance_taken_from', 'google' );
		$display_distance = simontaxi_get_option( 'display_distance', 'yes' );
		$outofservice = simontaxi_get_option( 'outofservice', 0);
		$vehicle_distance = simontaxi_get_option( 'vehicle_distance', 'km' );
		$farecalculation_basedon = simontaxi_get_option( 'farecalculation_basedon', 'basicfare' );
		$terms_page = simontaxi_get_option( 'terms_page', 'step1' );
		$booking_ref_length = simontaxi_get_option( 'booking_ref_length', 6);
		$st_date_format = simontaxi_get_option( 'st_date_format', 'd-m-Y' );

		$minimum_notice = simontaxi_get_option( 'minimum_notice', 0);
		$maximum_notice = simontaxi_get_option( 'maximum_notice', 3);
		$maximum_notice_type = simontaxi_get_option( 'maximum_notice_type', 'month' );
		$records_per_page = simontaxi_get_option( 'records_per_page', 10);

		/** Optional Fields */
		// Booking step1.
		$allow_additional_pickups = simontaxi_get_option( 'allow_additional_pickups', 'no' );
		$max_additional_pickups = simontaxi_get_option( 'max_additional_pickups', '5' );
		$allow_additional_dropoff = simontaxi_get_option( 'allow_additional_dropoff', 'no' );
		$max_additional_dropoff = simontaxi_get_option( 'max_additional_dropoff', '5' );
		$alloow_waiting_time = simontaxi_get_option( 'alloow_waiting_time', 'yes' );
		$alloow_twoway_booking = simontaxi_get_option( 'alloow_twoway_booking', 'yes' );

		$allow_twoway_airport = simontaxi_get_option( 'allow_twoway_airport', 'both' );

		$alloow_itinerary = simontaxi_get_option( 'alloow_itinerary', 'no' );

		// Booking step2.
		$coupon_code_form = simontaxi_get_option( 'coupon_code_form', 'yes' );
		$show_luggage_information = simontaxi_get_option( 'show_luggage_information', 'yes' );
		$show_seating_capacity = simontaxi_get_option( 'show_seating_capacity', 'yes' );
		$show_fare = simontaxi_get_option( 'show_fare', 'totalbasic' );

		// Booking step3.
		$name_display = simontaxi_get_option( 'name_display', 'fullnameoptional' );
		$phone_number = simontaxi_get_option( 'phone_number', 'no' );
		$no_of_passengers = simontaxi_get_option( 'no_of_passengers', 'yes' );
		$land_mark_pickupaddress = simontaxi_get_option( 'land_mark_pickupaddress', 'no' );
		$additional_pickup_address = simontaxi_get_option( 'additional_pickup_address', 'no' );
		$additional_dropoff_address = simontaxi_get_option( 'additional_dropoff_address', 'no' );
		$additional_dropoff_address_return = simontaxi_get_option( 'additional_dropoff_address_return', 'no' );
		$special_instructions = simontaxi_get_option( 'special_instructions' );

		/** Currency Settings */
		$vehicle_country = simontaxi_get_option( 'vehicle_country', 'US' );
		$vehicle_country_dropoff = simontaxi_get_option( 'vehicle_country_dropoff', 'US' );
		$vehicle_currency = simontaxi_get_option( 'vehicle_currency', 'USD_32' );
		$display_currency = simontaxi_get_option( 'display_currency', 'symbol' );
		$currency_position = simontaxi_get_option( 'currency_position', 'left' );
		$thousand_separator = simontaxi_get_option( 'thousand_separator', ',' );
		$decimal_separator = simontaxi_get_option( 'decimal_separator', '.' );
		$number_of_decimals = simontaxi_get_option( 'number_of_decimals', 2);

		/** Tabs & Titles */
		$booking_types = simontaxi_booking_types();
		
		$active_tabs = simontaxi_get_option( 'active_tabs', 'default' );
		if($active_tabs == 'default' ){
		$active_tabs = $booking_types;
		}
		$p2p_tab_title = simontaxi_get_option( 'p2p_tab_title', esc_html__( 'Point to Point Transfer', 'simontaxi' ) );
		$airport_tab_title = simontaxi_get_option( 'airport_tab_title', esc_html__( 'Airport Transfer', 'simontaxi' ) );
		$hourly_tab_title = simontaxi_get_option( 'hourly_tab_title', esc_html__( 'Hourly Rental', 'simontaxi' ) );
		$booking_step1_title = simontaxi_get_option( 'booking_step1_title', esc_html__( 'Location', 'simontaxi' ) );
		$booking_step2_title = simontaxi_get_option( 'booking_step2_title', esc_html__( 'Select Cab', 'simontaxi' ) );
		$booking_step3_title = simontaxi_get_option( 'booking_step3_title', esc_html__( 'Confirm Booking', 'simontaxi' ) );
		$booking_step4_title = simontaxi_get_option( 'booking_step4_title', 'Payment' );

		/** Payment Gateways */
		$payment_methods = simontaxi_get_option( 'payment_methods', 'paypal' );
		// If the admin dont select any payment method, lets enable all available payments(Native) so that admin may disable them after
		if($payment_methods == 'paypal' ){
		$payment_methods = array( 'byhand','paypal','payu' );
		}
		$default_payment_method = simontaxi_get_option( 'default_payment_method', 'paypal' );

		/** Tax Settings */
		$tax_rate = simontaxi_get_option( 'tax_rate', 0);
		$tax_rate_type = simontaxi_get_option( 'tax_rate_type', 'percent' );
		$display_tax_rate = simontaxi_get_option( 'display_tax_rate', 'no' );

		/** Surcharges */
		$peak_time_surcharge = simontaxi_get_option( 'peak_time_surcharge_type', '0' );
		$peak_time_surcharge_type = simontaxi_get_option( 'peak_time_surcharge_type', 'value' );
		$mid_night_time_from = simontaxi_get_option( 'mid_night_time_from', '0' );
		$mid_night_time_to = simontaxi_get_option( 'mid_night_time_from', '6' );
		$airport_surcharge = simontaxi_get_option( 'airport_surcharge_type', '0' );
		$airport_surcharge_type = simontaxi_get_option( 'airport_surcharge_type', 'value' );
		$additionalpoints_surcharge = simontaxi_get_option( 'additionalpoints_surcharge', '0' );
		$waitingtime_surcharge = simontaxi_get_option( 'waitingtime_surcharge', '0' );

		/** Email Settings */
		$vehicle_from_name = simontaxi_get_option( 'vehicle_from_name', get_bloginfo( 'blogname' ) );
		$vehicle_from_address = simontaxi_get_option( 'vehicle_from_address', get_option( 'admin_email' ) );
		$vehicle_payment_queries = simontaxi_get_option( 'vehicle_payment_queries', get_option( 'admin_email' ) );

		/** Billing */
		$vehicle_billing_phone = simontaxi_get_option( 'vehicle_billing_phone' );
		$vehicle_billing_email = simontaxi_get_option( 'vehicle_billing_email', get_option( 'admin_email' ) );
		$vehicle_billing_address = simontaxi_get_option( 'vehicle_billing_address' );
		/**
		 * @since 2.0.8
		 */
		$template = '/booking/includes/pages/admin/settings.php';
		if ( simontaxi_is_template_customized( $template ) ) {
			include_once( simontaxi_get_theme_template_dir_name() . $template );
		} else {
			include_once( SIMONTAXI_PLUGIN_PATH . $template );
		}		
	}
}
?>