<?php
/**
 * Register Custom post type (CPT) - emailtemplate
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  CPT
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

function simontaxi_emailtemplate_post_type() {
    $emailtemplate_labels = apply_filters( 'simontaxi_emailtemplate_labels', array(
	'name'              => sprintf( _x( 'Email Template', 'taxonomy general name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'singular_name'     => sprintf( _x( 'Email Template', 'taxonomy singular name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'search_items'      => sprintf( esc_html__( 'Search %s Email Template', 'simontaxi' ), simontaxi_get_label_singular() ),
	'all_items'         => sprintf( esc_html__( 'Email Templates', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item'       => sprintf( esc_html__( 'Parent %s Email Template', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item_colon' => sprintf( esc_html__( 'Parent %s Email Template:', 'simontaxi' ), simontaxi_get_label_singular() ),
	'edit_item'         => sprintf( esc_html__( 'Edit %s Email Template', 'simontaxi' ), simontaxi_get_label_singular() ),
	'update_item'       => sprintf( esc_html__( 'Update %s Email Template', 'simontaxi' ), simontaxi_get_label_singular() ),
	'add_new_item'      => sprintf( esc_html__( 'Add New %s Email Template', 'simontaxi' ), simontaxi_get_label_singular() ),
	'new_item_name'     => sprintf( esc_html__( 'New %s Email Template Name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'menu_name'         => esc_html__( 'Email Templates', 'simontaxi' ),
) );
	foreach ( $emailtemplate_labels as $key => $value ) {
		$emailtemplate_labels[ $key ] = sprintf( $value, simontaxi_get_label_singular(), simontaxi_get_label_plural() );
	}
	$emailtemplate_args = array(
		'labels'             => $emailtemplate_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'        => 'edit.php?post_type=vehicle',
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'query_var'          => true,
		'rewrite'            => array('slug' => SIMONTAXI_SLUG . '/emailtemplate', 'with_front' => false),
		'capability_type'    => 'post',
		'capabilities' 		=> get_capabilities( 'manage_email_templates' ),
		'map_meta_cap'       => true,
		'has_archive'        => false,
		'hierarchical'       => false,
		'supports'           => apply_filters( 'simontaxi_emailtemplate_supports', array( 'title', 'editor', 'thumbnail',  'revisions' ) ),
		'menu_position' => 5,
        'menu_icon'           =>'dashicons-migrate',
	);
	register_post_type( 'emailtemplate', apply_filters( 'simontaxi_emailtemplate_post_type_args', $emailtemplate_args ) );
}