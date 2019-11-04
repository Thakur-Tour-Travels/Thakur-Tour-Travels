<?php
/**
 * Simontaxi functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package simontaxi-child
 */
 
add_action( 'wp_enqueue_scripts', 'simontaxichild_enqueue_styles' );
function simontaxichild_enqueue_styles() {
   $parent_style = 'parent-style'; // This is 'simontaxi-style' for the Simontaxi theme.

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'simontaxi-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
?>