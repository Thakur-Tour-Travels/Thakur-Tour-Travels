<?php
/**
 * Simontaxi Theme Customizer.
 *
 * @package Simontaxi
 */

/**
 * Options for Simontaxi Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function simontaxi_theme_customizer( $wp_customize ) {

	$wp_customize->add_panel( 'simontaxi_theme_settings', array(
		'priority'       => 30,
		'capability'     => 'edit_theme_options',
		'title'          => esc_html__( 'Simontaxi : Customizer', 'simontaxi' ),
		'description'    => esc_html__( 'Customize the simontaxi theme of your website.', 'simontaxi' ),
		)
	);
	$wp_customize->add_section('simontaxi_top_info_section', array(
		'priority' => 5,
		'title' => esc_html__( 'Top Info Editor', 'simontaxi' ),
		'panel'  => 'simontaxi_theme_settings',
		)
	);
	// Call Us.
	$wp_customize->add_setting('simontaxi_call_us', array(
		'default' => '',
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'simontaxi_sanitize_strip_slashes',
		)
	);

	$wp_customize->add_control('simontaxi_call_us', array(
		'label' => esc_html__( 'Call Us', 'simontaxi' ),
		'section' => 'simontaxi_top_info_section',
		'type' => 'theme_mod',
		'priority' => 5,
		'settings' => 'simontaxi_call_us',
		)
	);
	
	/**
	 * Home page banner section
	*/
	$wp_customize->add_section('simontaxi_homebanner', array(
		'priority' => 6,
		'title' => esc_html__( 'Home Banner', 'simontaxi' ),
		'panel'  => 'simontaxi_theme_settings',
		)
	);
	$wp_customize->add_setting('banner-show-hide-homepage', array(
		'default' => 'show',
		'sanitize_callback' => 'simontaxi_sanitize_show_hide',
		)
	);
	$wp_customize->add_control('banner-show-hide-homepage', array(
		'label' => esc_html__( 'Website Homepage banner Options', 'simontaxi' ),
		'section' => 'simontaxi_homebanner',
		'type' => 'select',
		'description' => esc_html__( 'Choose Whether to show the home page banner or not', 'simontaxi' ),
		'choices' => array( 'show' => esc_html__( 'Show', 'simontaxi' ), 'hide' => esc_html__( 'Hide', 'simontaxi' ) ),
		)
	);
	// Background Image.
	$wp_customize->add_setting('banner-image-homepage', array(
		'default' => get_template_directory_uri() . '/images/home-banner.png',
		'sanitize_callback' => 'simontaxi_sanitize_strip_slashes',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control($wp_customize, 'banner-image-homepage', array(
			'label' => esc_html__( 'Background Image', 'simontaxi' ),
			'section' => 'simontaxi_homebanner',
			'mime_type' => 'image',
			'priority' => 10,
			)
		)
	);

	if ( function_exists( 'simontaxi_booking_step1' ) ) {
		/**
		* Is Home page containes Booking page
		*/
		$wp_customize->add_setting('booking-homepage', array(
			'default' => 'yes',
			'sanitize_callback' => 'simontaxi_sanitize_yes_no',
			)
		);
		$wp_customize->add_control('booking-homepage', array(
			'label' => esc_html__( 'Website Homepage booking page options', 'simontaxi' ),
			'section' => 'simontaxi_homebanner',
			'type' => 'select',
			'description' => esc_html__( 'Choose Whether to show the booking page on home page banner or not', 'simontaxi' ),
			'choices' => array( 'yes' => esc_html__( 'Yes', 'simontaxi' ), 'no' => esc_html__( 'No', 'simontaxi' ) ),
			)
		);
	}

	/**
	 * Inner page banner section
	*/
	$wp_customize->add_section('simontaxi_pagebanner', array(
		'priority' => 6,
		'title' => esc_html__( 'Page Banner', 'simontaxi' ),
		'panel'  => 'simontaxi_theme_settings',
		)
	);
	$wp_customize->add_setting('banner-show-hide', array(
		'default' => 'show',
		'type' => 'theme_mod',
		'sanitize_callback' => 'simontaxi_sanitize_show_hide',
		)
	);
	$wp_customize->add_control('banner-show-hide', array(
		'label' => esc_html__( 'Website Inner banner Options', 'simontaxi' ),
		'section' => 'simontaxi_pagebanner',
		'type' => 'select',
		'description' => esc_html__( 'Choose Whether to show the inner page banner or not', 'simontaxi' ),
		'choices' => array( 'show' => esc_html__( 'Show', 'simontaxi' ), 'hide' => esc_html__( 'Hide', 'simontaxi' ) ),
		)
	);
	// Background Image.
	$wp_customize->add_setting('banner-image', array(
		'default' => get_template_directory_uri() . '/images/inner-banner.png',
		'sanitize_callback' => 'simontaxi_sanitize_strip_slashes',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control($wp_customize, 'banner-image', array(
			'label' => esc_html__( 'Background Image', 'simontaxi' ),
			'section' => 'simontaxi_pagebanner',
			'mime_type' => 'image',
			'priority' => 10,
			)
		)
	);

	// Exclude on pages.
	$wp_customize->add_setting('banner-image-exclude', array(
		'sanitize_callback' => 'simontaxi_sanitize_is_numeric',
		)
	);
	$wp_customize->add_control('banner-image-exclude', array(
		'label' => esc_html__( 'Website Inner banner Options', 'simontaxi' ),
		'section' => 'simontaxi_pagebanner',
		'type' => 'dropdown-pages',
		'description' => esc_html__( 'Exclude to display the banner on these pages', 'simontaxi' ),
		)
	);
}

/**
 * Adds sanitization callback function: Strip Slashes.
 *
 * @param string $input value from the customizer.
 */
function simontaxi_sanitize_strip_slashes( $input ) {
	return wp_kses_stripslashes( $input );
}

/**
 * Adds sanitization callback function: Validate Email.
 *
 * @param string $input value from the customizer.
 */
function simontaxi_validate_email( $input ) {
	return ( is_email( $input ) ) ? $input : '';
}

/**
 * Adds sanitization callback function: simontaxi_sanitize_show_hide.
 *
 * @param string $input value from the customizer.
 */
function simontaxi_sanitize_show_hide( $input ) {
	if ( in_array( $input, array( 'show', 'hide' ), true ) ) {
		return $input;
	} else {
		return '';
	}
}

/**
 * Adds sanitization callback function: simontaxi_sanitize_yes_no.
 *
 * @param string $input value from the customizer.
 */
function simontaxi_sanitize_yes_no( $input ) {
	if ( in_array( $input, array( 'yes', 'no' ), true ) ) {
		return $input;
	} else {
		return '';
	}
}

/**
 * Adds sanitization callback function: simontaxi_sanitize_is_numeric.
 *
 * @param string $input value from the customizer.
 */
function simontaxi_sanitize_is_numeric( $input ) {
	if ( is_numeric( $input ) ) {
		return $input;
	}
}
add_action( 'customize_register', 'simontaxi_theme_customizer' );
