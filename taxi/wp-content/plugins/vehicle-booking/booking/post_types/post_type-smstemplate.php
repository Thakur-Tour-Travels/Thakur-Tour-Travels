<?php
/**
 * Register Custom post type (CPT) - smstemplate
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  CPT
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

function simontaxi_smstemplate_post_type() {
    $smstemplate_labels = apply_filters( 'simontaxi_smstemplate_labels', array(
	'name'              => sprintf( _x( '%s SMS Template', 'taxonomy general name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'singular_name'     => sprintf( _x( '%s SMS Template', 'taxonomy singular name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'search_items'      => sprintf( esc_html__( 'Search %s SMS Template', 'simontaxi' ), simontaxi_get_label_singular() ),
	'all_items'         => sprintf( esc_html__( 'SMS Templates', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item'       => sprintf( esc_html__( 'Parent %s SMS Template', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item_colon' => sprintf( esc_html__( 'Parent %s SMS Template:', 'simontaxi' ), simontaxi_get_label_singular() ),
	'edit_item'         => sprintf( esc_html__( 'Edit %s SMS Template', 'simontaxi' ), simontaxi_get_label_singular() ),
	'update_item'       => sprintf( esc_html__( 'Update %s SMS Template', 'simontaxi' ), simontaxi_get_label_singular() ),
	'add_new_item'      => sprintf( esc_html__( 'Add New %s SMS Template', 'simontaxi' ), simontaxi_get_label_singular() ),
	'new_item_name'     => sprintf( esc_html__( 'New %s SMS Template Name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'menu_name'         => esc_html__( 'SMS Templates', 'simontaxi' ),
) );
	foreach ( $smstemplate_labels as $key => $value ) {
		$smstemplate_labels[ $key ] = sprintf( $value, simontaxi_get_label_singular(), simontaxi_get_label_plural() );
	}
	$smstemplate_args = array(
		'labels'             => $smstemplate_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'        => 'edit.php?post_type=vehicle',
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'query_var'          => true,
		'rewrite'            => array('slug' => SIMONTAXI_SLUG . '/smstemplate', 'with_front' => false),
		'capability_type'    => 'post',
		'capabilities' 		=> get_capabilities( 'manage_sms_templates' ),
		'map_meta_cap'       => true,
		'has_archive'        => false,
		'hierarchical'       => false,
		'supports'           => apply_filters( 'simontaxi_smstemplate_supports', array( 'title', 'editor',  'revisions' ) ),
		'menu_position' => 5,
        'menu_icon'           =>'dashicons-migrate',
	);
	register_post_type( 'smstemplate', apply_filters( 'simontaxi_smstemplate_post_type_args', $smstemplate_args ) );
}