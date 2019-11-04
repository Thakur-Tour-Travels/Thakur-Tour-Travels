<?php
/**
 * Plugin activation
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  activation
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

register_activation_hook( __FILE__, 'simontaxi_activate' );

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
/**
 * Add a flag that will allow to flush the rewrite rules when needed.
 */
function simontaxi_activate() {
	if ( ! get_option( 'simontaxi_flush_rewrite_rules_flag' ) ) {
		add_option( 'simontaxi_flush_rewrite_rules_flag', true );
	}
}
add_action( 'init', 'simontaxi_register_post_types', 10 );
/**
 * Register a custom post type.
 */
function simontaxi_register_post_types() {
	require SIMONTAXI_PLUGIN_PATH . '/booking/post-types.php';
	require SIMONTAXI_PLUGIN_PATH . '/booking/register-taxonomy.php';
}
add_action( 'init', 'simontaxi_flush_rewrite_rules_maybe', 20 );
/**
 * Flush rewrite rules if the previously added flag exists,
 * and then remove the flag.
 */
function simontaxi_flush_rewrite_rules_maybe() {
	if ( get_option( 'simontaxi_flush_rewrite_rules_flag' ) ) {
		flush_rewrite_rules();
		delete_option( 'simontaxi_flush_rewrite_rules_flag' );
	}
}
