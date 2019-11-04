<?php
/**
 * Register taxonomy - vehicle_features
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  taxonomy
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

function simontaxi_vehicle_features_taxonomy() {
$slug = (defined('SIMONTAXI_SLUG')) ? SIMONTAXI_SLUG : 'vehicle';
//Vehicle Features
$feature_labels = array(
	'name'              => sprintf( _x( '%s Feature', 'taxonomy general name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'singular_name'     => sprintf( _x( '%s Features', 'taxonomy singular name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'search_items'      => sprintf( esc_html__( 'Search %s Features', 'simontaxi' ), simontaxi_get_label_singular() ),
	'all_items'         => sprintf( esc_html__( 'All %s Features', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item'       => sprintf( esc_html__( 'Parent %s Feature', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item_colon' => sprintf( esc_html__( 'Parent %s Feature:', 'simontaxi' ), simontaxi_get_label_singular() ),
	'edit_item'         => sprintf( esc_html__( 'Edit %s Feature', 'simontaxi' ), simontaxi_get_label_singular() ),
	'update_item'       => sprintf( esc_html__( 'Update %s Feature', 'simontaxi' ), simontaxi_get_label_singular() ),
	'add_new_item'      => sprintf( esc_html__( 'Add New %s Feature', 'simontaxi' ), simontaxi_get_label_singular() ),
	'new_item_name'     => sprintf( esc_html__( 'New %s Feature Name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'menu_name'         => esc_html__( 'Features', 'simontaxi' ),
);
$feature_args = apply_filters( 'simontaxi_vehicle_features_args', array(
		'hierarchical' => false,
		'labels'       => apply_filters('simontaxi_vehicle_features_labels', $feature_labels),
		'show_ui'      => true,
		'query_var'    => 'vehicle_features',
		'rewrite'      => array('slug' => $slug . '/vehicle_features', 'with_front' => false, 'hierarchical' => true ),
		'show_admin_column'=>true,
		/*
		 * @since 2.0.0
		*/
		'capabilities' => array(
			'manage_terms' 	=> 'read_manage_features',
			'edit_terms' 	=> 'edit_manage_features',
			'delete_terms' 	=> 'delete_manage_features',
			'assign_terms'	=> 'edit_manage_features',
		),
		'map_meta_cap' => true,
	)
);
register_taxonomy( 'vehicle_features', array('vehicle'), $feature_args );
}