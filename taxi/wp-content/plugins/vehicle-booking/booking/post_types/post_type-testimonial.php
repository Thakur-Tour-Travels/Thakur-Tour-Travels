<?php
/**
 * Register Custom post type (CPT) - testimonial
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  CPT
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

function simontaxi_testimonial_post_type() {
	$post_type = 'testimonial';
	$post_type_title = 'Testimonial';
	$post_type_title_plural = 'Testimonials';
   $testimonial_labels = apply_filters( 'simontaxi_testimonial_labels', array(
		'name'                  => _x( '%2$s', 'testimonial post type name', 'simontaxi' ),
		'singular_name'         => _x( '%1$s', 'singular testimonial post type name', 'simontaxi' ),
		'add_new'               => esc_html__( 'Add New', 'simontaxi' ),
		'add_new_item'          => esc_html__( 'Add New %1$s', 'simontaxi' ),
		'edit_item'             => esc_html__( 'Edit %1$s', 'simontaxi' ),
		'new_item'              => esc_html__( 'New %1$s', 'simontaxi' ),
		'all_items'             => esc_html__( 'All %2$s', 'simontaxi' ),
		'view_item'             => esc_html__( 'View %1$s', 'simontaxi' ),
		'search_items'          => esc_html__( 'Search %2$s', 'simontaxi' ),
		'not_found'             => esc_html__( 'No %2$s found', 'simontaxi' ),
		'not_found_in_trash'    => esc_html__( 'No %2$s found in Trash', 'simontaxi' ),
		'parent_item_colon'     => '',
		'menu_name'             => _x( '%2$s', 'testimonial post type menu name', 'simontaxi' ),
		'featured_image'        => esc_html__( '%1$s Image', 'simontaxi' ),
		'set_featured_image'    => esc_html__( 'Set %1$s Image', 'simontaxi' ),
		'remove_featured_image' => esc_html__( 'Remove %1$s Image', 'simontaxi' ),
		'use_featured_image'    => esc_html__( 'Use as %1$s Image', 'simontaxi' ),
		'filter_items_list'     => esc_html__( 'Filter %2$s list', 'simontaxi' ),
		'items_list_navigation' => esc_html__( '%2$s list navigation', 'simontaxi' ),
		'items_list'            => esc_html__( '%2$s list', 'simontaxi' ),
   ) );
	foreach ( $testimonial_labels as $key => $value ) {
		$testimonial_labels[ $key ] = sprintf( $value, $post_type_title, $post_type_title_plural );
	}
	$testimonial_args = array(
		'labels'             => $testimonial_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'        => 'edit.php?post_type=vehicle',
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'query_var'          => true,
		'rewrite'            => array('slug' => SIMONTAXI_SLUG . '/testimonial', 'with_front' => false),
		'capability_type'    => 'post',
		'capabilities' 		=> get_capabilities( 'manage_testimonials' ),
		'map_meta_cap'       => true,
		'has_archive'        => false,
		'hierarchical'       => false,
		'supports'           => apply_filters( 'simontaxi_testimonial_supports', array( 'title', 'editor', 'thumbnail',  'revisions' ) ),
		'menu_position' => 5,
        'menu_icon'           =>'dashicons-migrate',
	);
	register_post_type( $post_type, apply_filters( 'simontaxi_testimonial_post_type_args', $testimonial_args ) );
}