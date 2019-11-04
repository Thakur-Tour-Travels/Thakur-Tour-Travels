<?php
/**
 * This template is used to display the 'bookings' for admin / executive
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  manage_bookings
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'simontaxi_theme_admin_menu_manage_bookings' );
function simontaxi_theme_admin_menu_manage_bookings() {
   		add_submenu_page( 'edit.php?post_type=vehicle', esc_html__( 'Manage Bookings', 'simontaxi' ),esc_html__( 'Manage Bookings', 'simontaxi' ),'manage_bookings','manage_bookings','manage_bookings' );
}

function manage_bookings() {
	$template = 'booking/includes/pages/admin/manage-bookings-page.php';
	if ( simontaxi_is_template_customized( $template ) ) {
		include_once( simontaxi_get_theme_template_dir_name() . $template );
	} else {
		include_once( apply_filters( 'simontaxi_locate_manage_bookings_page', SIMONTAXI_PLUGIN_PATH . $template ) );
	}
}
