<?php
/**
 * Plugin widgets
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  widgets
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * @since 2.0.8
 */
$template = '/booking/widgets/widget-simontaxi-request-callback.php';
if ( simontaxi_is_template_customized( $template ) ) {
	include_once( simontaxi_get_theme_template_dir_name() . $template );
} else {
	include_once( SIMONTAXI_PLUGIN_PATH . $template );
}

/**
 * @since 2.0.8
 */
$template = '/booking/widgets/widget-simontaxi-support-contact.php';
if ( simontaxi_is_template_customized( $template ) ) {
	include_once( simontaxi_get_theme_template_dir_name() . $template );
} else {
	include_once( SIMONTAXI_PLUGIN_PATH . $template );
}
