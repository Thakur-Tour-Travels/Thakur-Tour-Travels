<?php
/**
 * Plugin custom post types
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  Post types
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require SIMONTAXI_PLUGIN_PATH . '/booking/post_types/post_type-vehicle.php';
simontaxi_vehicle_post_type();

require SIMONTAXI_PLUGIN_PATH . '/booking/post_types/post_type-faq.php';
simontaxi_faq_post_type();

require SIMONTAXI_PLUGIN_PATH . '/booking/post_types/post_type-emailtemplate.php';
simontaxi_emailtemplate_post_type();

require SIMONTAXI_PLUGIN_PATH . '/booking/post_types/post_type-smstemplate.php';
simontaxi_smstemplate_post_type();

require SIMONTAXI_PLUGIN_PATH . '/booking/post_types/post_type-testimonial.php';
simontaxi_testimonial_post_type();
