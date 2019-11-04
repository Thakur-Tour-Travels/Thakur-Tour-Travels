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

register_activation_hook( SIMONTAXI_PLUGIN_PATH . 'index.php', 'simontaxi_activate' );

register_deactivation_hook( SIMONTAXI_PLUGIN_PATH . 'index.php', 'flush_rewrite_rules' );
/**
 * Function to activate required CPT, Taxonomies and Database
 *
 * @since 1.0.0
 * @return void
 */
function simontaxi_activate() {

	simontaxi_register_post_types();

	simontaxi_register_taxonomies();

	//simontaxi_db_install();

	//simontaxi_install_pages();

	//simontaxi_default_templates();
	
	simontaxi_install();
	
	/**
	* Let us update roles capabilities.
	*
	* @since 2.0.9
	*/
   add_theme_caps();

	flush_rewrite_rules();
}

/**
 * Function to register CPT
 *
 * @since 1.0.0
 * @return void
 */
function simontaxi_register_post_types() {
	require SIMONTAXI_PLUGIN_PATH . '/booking/post-types.php';
}
add_action( 'init', 'simontaxi_register_post_types', 10 );

/**
 * Function to register Taxonomies
 *
 * @since 1.0.0
 * @return void
 */
function simontaxi_register_taxonomies() {
	require SIMONTAXI_PLUGIN_PATH . '/booking/register-taxonomy.php';
}
add_action( 'init', 'simontaxi_register_taxonomies', 10 );
