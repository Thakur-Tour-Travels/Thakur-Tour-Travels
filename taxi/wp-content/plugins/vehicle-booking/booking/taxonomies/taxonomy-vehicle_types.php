<?php
/**
 * Register taxonomy - vehicle_types
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  taxonomy
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

function simontaxi_vehicle_types_taxonomy() {
$slug = (defined('SIMONTAXI_SLUG')) ? SIMONTAXI_SLUG : 'vehicle';
//Vehicle Types
$type_labels = array(
	'name'              => sprintf( _x( '%s Type', 'taxonomy general name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'singular_name'     => sprintf( _x( '%s Types', 'taxonomy singular name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'search_items'      => sprintf( esc_html__( 'Search %s Types', 'simontaxi' ), simontaxi_get_label_singular() ),
	'all_items'         => sprintf( esc_html__( 'All %s Types', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item'       => sprintf( esc_html__( 'Parent %s Type', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item_colon' => sprintf( esc_html__( 'Parent %s Type:', 'simontaxi' ), simontaxi_get_label_singular() ),
	'edit_item'         => sprintf( esc_html__( 'Edit %s Type', 'simontaxi' ), simontaxi_get_label_singular() ),
	'update_item'       => sprintf( esc_html__( 'Update %s Type', 'simontaxi' ), simontaxi_get_label_singular() ),
	'add_new_item'      => sprintf( esc_html__( 'Add New %s Type', 'simontaxi' ), simontaxi_get_label_singular() ),
	'new_item_name'     => sprintf( esc_html__( 'New %s Type Name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'menu_name'         => esc_html__( 'Types', 'simontaxi' ),
);
$type_args = apply_filters( 'simontaxi_vehicle_types_args', array(
		'hierarchical' => false,
		'labels'       => apply_filters('simontaxi_vehicle_types_labels', $type_labels),
		'show_ui'      => true,
		'query_var'    => 'vehicle_types',
		'rewrite'      => array('slug' => $slug . '/vehicle_types', 'with_front' => false, 'hierarchical' => true ),
		'show_admin_column'=>true,
		/*
		 * @since 2.0.0
		*/
		'capabilities' => array(
			'manage_terms' 	=> 'read_manage_types',
			'edit_terms' 	=> 'edit_manage_types',
			'delete_terms' 	=> 'delete_manage_types',
			'assign_terms'	=> 'edit_manage_types',
		),
		'map_meta_cap' => true,
	)
);
register_taxonomy( 'vehicle_types', array('vehicle'), $type_args );
}