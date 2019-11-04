<?php
if( class_exists('OCDI_Plugin') ){	
function simontaxi_ocdi_plugin_intro_text( $default_text ) {
	$default_text .= '
		<div class="ocdi__intro-text">
			<h3>' . esc_html__( 'Read this before importing demo data!', 'simontaxi' ) . '</h3>
			<p>' . esc_html__( 'Please ensure all required plugins in "appearance => install plugins" are installed before running this demo importer.', 'simontaxi' ) . '</p>
			<hr />
		</div>
	';

	return $default_text;
}
add_filter( 'pt-ocdi/plugin_intro_text', 'simontaxi_ocdi_plugin_intro_text' );
	
function simontaxi_ocdi_confirmation_dialog_options ( $options ) {
	return array_merge( $options, array(
		'width'       => 600,
		'dialogClass' => 'wp-dialog',
		'resizable'   => false,
		'height'      => 'auto',
		'modal'       => true,
	) );
}
add_filter( 'pt-ocdi/confirmation_dialog_options', 'simontaxi_ocdi_confirmation_dialog_options', 10, 1 );

//Setup basic demo import
function simontaxi_import_files() {
		
	$import_notice_all = '
		<h3>' . esc_html__( 'Simontaxi Demo Data', 'simontaxi' ) . '</h3>
		<p>' . esc_html__( 'Please ensure all required plugins in "appearance => install plugins" are installed before running this demo importer.', 'simontaxi' ) . '</p>
		<p>' . esc_html__( 'Since you\'re importing Simontaxi Demo Data, please ensure "Vehicle Booking" plugin is enabled in "plugins". This will contain all of your posts, pages, comments, custom fields, terms, navigation menus, and custom posts.', 'simontaxi' ) . ' </p>
	';
	
	$import_notice_vehicle = '
		<h3>' . esc_html__( 'Simontaxi Vehicles', 'simontaxi' ) . '</h3>
		<p>' . esc_html__( 'Please ensure all required plugins in "appearance => install plugins" are installed before running this demo importer.', 'simontaxi' ) . '</p>
		<p>' . esc_html__( 'Since you\'re importing Simontaxi Demo Data, please ensure "Vehicle Booking" plugin is enabled in "plugins". This will contain only vehicles.', 'simontaxi' ) . '</p>
	';
	
	$import_notice_variant_images = '
		<h3>' . esc_html__( 'Ready to Import Simontaxi Demo Images ONLY', 'simontaxi' ) . '</h3>
		<p>' . esc_html__( 'This will import the required demo images for Simontaxi Demo Images. This will not add any page or post data.', 'simontaxi' ) . '</p>		
	';
			
	return array(
		array(
			'import_file_name'             => esc_html__('Simontaxi Demo Data + Vehicles', 'simontaxi'),
			'import_file_url'              => 'https://cdn.conquerorstech.com/wp/simantaxi/simontaxi.xml',
			'import_notice'                => $import_notice_all,
		),
		array(
			'import_file_name'             => esc_html__('Simontaxi Demo Images ONLY', 'simontaxi'),
			'import_file_url'              => 'https://cdn.conquerorstech.com/wp/simantaxi/media.xml',
			'import_notice'                => $import_notice_variant_images,
		)
	);
	
}
add_filter( 'pt-ocdi/import_files', 'simontaxi_import_files' );

//Setup front page and menus
function simontaxi_after_import_setup() {
	
	// Assign menus to their locations.
	$main_menu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );

	set_theme_mod( 'nav_menu_locations', array(
			'primary'  => $main_menu->term_id,
		)
	);

	// Assign front page and posts page (blog page).
	$front_page_id = get_page_by_title( 'Simontaxi - Home' );
	$blog_page_id  = get_page_by_title( 'Blog Listing' );

	update_option( 'show_on_front', 'page' );
	if( $front_page_id > 0 )
	update_option( 'page_on_front', $front_page_id->ID );
	//update_option( 'page_for_posts', $blog_page_id->ID );
	
	
	//Let us set theme options
	$theme_options = array(
		'banner-show-hide-homepage' => 'show',
		'banner-show-hide' => 'show',
		'booking-homepage' => 'yes',
		'simontaxi_send_us_email' => '',
		'simontaxi_call_us' => '9490472748',
	);
	$mods = array();
	foreach( $theme_options as $name => $value )
	{
		$mods[ $name ] = $value;
		update_option( "simontaxi_opts", $mods );
	}
		
	$mods = get_theme_mods();
	foreach( $theme_options as $name => $value )
	{
		$mods[ $name ] = apply_filters( "pre_set_theme_mod_{$name}", $value, '' );
		$theme = get_option( 'stylesheet' );
		update_option( "theme_mods_$theme", $mods );
	}
	
	$widgets = array(
		'social-links',
		'text'
	);
	foreach( $widgets as $widget )
	{
		$temp = array(
			'title' => 'Simon Taxi',
			'text' => 'Simon Taxi Demo text. We provide service',
		);
		if( $widget == 'text' )
		{
			update_option( "widget_text", $temp );
		}
		else
		{
			$temp['facebook'] = 'http://facebook.com';
			$temp['twitter'] = 'http://twitter.com';
			$temp['linked_in'] = 'http://linkedin.com';
			$temp['instagram'] = 'http://instagram.com';
			$theme = get_option( 'stylesheet' );
			update_option( "widget_$theme-$widget", $temp );
		}
	}
	
}
add_action( 'pt-ocdi/after_import', 'simontaxi_after_import_setup' );

//Remove Branding
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );

//Save customize options
add_action( 'pt-ocdi/enable_wp_customize_save_hooks', '__return_true' );

//Stop thumbnail generation
add_filter( 'pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false' );
}