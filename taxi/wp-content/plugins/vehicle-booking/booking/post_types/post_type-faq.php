<?php
/**
 * Register Custom post type (CPT) - faq
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  CPT
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

function simontaxi_faq_post_type() {
    $faq_labels = apply_filters( 'simontaxi_faq_labels', array(
	'name'              => sprintf( _x( '%s FAQ', 'taxonomy general name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'singular_name'     => sprintf( _x( '%s FAQ', 'taxonomy singular name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'search_items'      => sprintf( esc_html__( 'Search %s FAQ', 'simontaxi' ), simontaxi_get_label_singular() ),
	'all_items'         => sprintf( esc_html__( 'All %s FAQ', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item'       => sprintf( esc_html__( 'Parent %s FAQ', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item_colon' => sprintf( esc_html__( 'Parent %s FAQ:', 'simontaxi' ), simontaxi_get_label_singular() ),
	'edit_item'         => sprintf( esc_html__( 'Edit %s FAQ', 'simontaxi' ), simontaxi_get_label_singular() ),
	'update_item'       => sprintf( esc_html__( 'Update %s FAQ', 'simontaxi' ), simontaxi_get_label_singular() ),
	'add_new_item'      => sprintf( esc_html__( 'Add New %s FAQ', 'simontaxi' ), simontaxi_get_label_singular() ),
	'new_item_name'     => sprintf( esc_html__( 'New %s FAQ Name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'menu_name'         => esc_html__( 'Hourly Packages', 'simontaxi' ),
) );
	foreach ( $faq_labels as $key => $value ) {
		$faq_labels[ $key ] = sprintf( $value, simontaxi_get_label_singular(), simontaxi_get_label_plural() );
	}
	$faq_args = array(
		'label'               => esc_html__( 'faq', 'simontaxi' ),
		'description'         => esc_html__( 'Frequently Asked Questions', 'simontaxi' ),
		'labels'             => $faq_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'        => 'edit.php?post_type=vehicle',
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'query_var'          => true,
		'rewrite'            => array('slug' => SIMONTAXI_SLUG . '/faq', 'with_front' => false),
		'capability_type'    => 'post',
		'capabilities' 		=> get_capabilities( 'manage_faq' ),
		'map_meta_cap'       => true,
		'has_archive'        => false,
		'hierarchical'       => false,
		'supports'           => apply_filters( 'simontaxi_faq_supports', array( 'title', 'editor', 'thumbnail',  'revisions' ) ),
		'menu_position' => 5,
        'menu_icon'           =>'dashicons-migrate',
	);
	register_post_type( 'faq', apply_filters( 'simontaxi_faq_post_type_args', $faq_args ) );
}