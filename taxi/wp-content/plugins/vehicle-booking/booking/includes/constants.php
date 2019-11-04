<?php
/**
 * Simontaxi - Vehicle Booking Constants
 *
 * Application wide constants
 *
 * @author   Digisamaritan
 * @package  Simontaxi - Vehicle Booking
 * @since    2.0.9
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined('SIMONTAXI_PRODUCT_SITE') ) {
	define('SIMONTAXI_PRODUCT_SITE', 'https://digisamaritan.com/');
}
if ( ! defined('SIMONTAXI_UPDATES_SITE') ) {
	define('SIMONTAXI_UPDATES_SITE', 'https://digisamaritan.com/');
}
if ( ! defined('SIMONTAXI_DEMO_SITE') ) {
	define('SIMONTAXI_DEMO_SITE', 'https://simontaxi.wptaxitheme.com/');
}

if ( ! defined('TBL_ST_BOOKINGS') ) {
	define('TBL_ST_BOOKINGS', 'st_bookings');
}

if ( ! defined('TBL_ST_BOOKINGS') ) {
	define('TBL_ST_BOOKINGS', 'st_bookings');
}
if ( ! defined('TBL_ST_CALLBACKS') ) {
	define('TBL_ST_CALLBACKS', 'st_callbacks');
}
if ( ! defined('TBL_ST_COUNTRIES') ) {
	define('TBL_ST_COUNTRIES', 'st_countries');
}
if ( ! defined('TBL_ST_COUPONS_HISTORY') ) {
	define('TBL_ST_COUPONS_HISTORY', 'st_coupons_history');
}
if ( ! defined('TBL_ST_PAYMENTS') ) {
	define('TBL_ST_PAYMENTS', 'st_payments');
}
if ( ! defined('TBL_ST_REQUEST_CALLBACK') ) {
	define('TBL_ST_REQUEST_CALLBACK', 'st_request_callback');
}
if ( ! defined('TBL_ST_USER_SUPPORT') ) {
	define('TBL_ST_USER_SUPPORT', 'st_user_support');
}
if ( ! defined('SIMONTAXI_ROUNDING_PRECISION') ) {
	define('SIMONTAXI_ROUNDING_PRECISION', 6);
}
