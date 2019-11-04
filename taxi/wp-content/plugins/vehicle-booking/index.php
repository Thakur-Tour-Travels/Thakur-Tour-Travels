<?php
/**
 * Plugin Name: Simontaxi - Vehicle Booking
 * Plugin URI:https://simontaxi.conquerorstech.com/
 * Description: This plugin create vehicle custom post type, some meta option and widgets. Implements the vehicle booking system. Vehicle Booking System is developed and customized for commercial fleet owners and organizations. Its modules support most type of vehicles (passenger, Truck, construction and other commercial vehicles). Keep accurate records for any type of vehicle. Help you plan annual vehicle budgets faster.
 * Version: 2.0.9
 * Text Domain: simontaxi
 * Author: Digisamaritan
 * Author URI: https://digisamaritan.com/
 * Requires at least: 4.4
 * Tested up to: 4.9
 *
 * @package STVB
 * @author Digisamaritan
 * @version 2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin URL.
define( 'SIMONTAXI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Plugin Path.
define( 'SIMONTAXI_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// Plugin Slug.
if ( ! defined( 'SIMONTAXI_SLUG' ) ) {
	define( 'SIMONTAXI_SLUG', 'vehicle' );
}

// Plugin Slug.
if ( ! defined( 'SIMONTAXI_PLUGIN_ID' ) ) {
	define( 'SIMONTAXI_PLUGIN_ID', 'vehicle-booking/index.php' );
}

// Plugin Version.
if ( ! defined( 'SIMONTAXI_VERSION' ) ) {
	define( 'SIMONTAXI_VERSION', '2.0.9' );
}

// Plugin Mode of running.
if ( ! defined( 'SIMONTAXI_SCRIPT_DEBUG' ) ) {
	define( 'SIMONTAXI_SCRIPT_DEBUG', false );
}

// Plugin Root File.
if ( ! defined( 'SIMONTAXI_PLUGIN_FILE' ) ) {
	define( 'SIMONTAXI_PLUGIN_FILE', __FILE__ );
}

// Plugin Root File.
if ( ! defined( 'SIMONTAXI_TEMPLATE_DEBUG_MODE' ) ) {
	define( 'SIMONTAXI_TEMPLATE_DEBUG_MODE', false );
}

// Plugin Update Test Constant.
if ( ! defined( 'SIMONTAXI_UPDATE_DEBUG_MODE' ) ) {
	define( 'SIMONTAXI_UPDATE_DEBUG_MODE', false );
}

// Plugin Update URL Constant.
if ( ! defined( 'SIMONTAXI_UPDATE_URL_DEBUG_MODE' ) ) {
	define( 'SIMONTAXI_UPDATE_URL_DEBUG_MODE', false );
}

// require_once 'booking.php';

if ( ! class_exists( 'Simontaxi_Vehicle_Booking' ) ) :

/**
 * Main Simontaxi_Vehicle_Booking Class.
 *
 * @since 2.0.0
 */
final class Simontaxi_Vehicle_Booking {
	/** Singleton *************************************************************/

	/**
	 * @var Simontaxi_Vehicle_Booking The one true Simontaxi_Vehicle_Booking
	 * @since 1.0.0
	 */
	private static $instance;

	/**
	 * Main Simontaxi_Vehicle_Booking Instance.
	 *
	 * Insures that only one instance of Simontaxi_Vehicle_Booking exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 2.0.0
	 * @static
	 * @staticvar array $instance
	 * @uses Simontaxi_Vehicle_Booking::setup_constants() Setup the constants needed.
	 * @uses Simontaxi_Vehicle_Booking::includes() Include the required files.
	 * @uses Simontaxi_Vehicle_Booking::load_textdomain() load the language files.
	 * @return object|Simontaxi_Vehicle_Booking The one true Simontaxi_Vehicle_Booking
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Simontaxi_Vehicle_Booking ) ) {
			self::$instance = new Simontaxi_Vehicle_Booking;

			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

			self::$instance->includes();
			self::$instance->session = new Simontaxi_Session();
		}

		return self::$instance;
	}

	/**
	 * File inclues.
	 *
	 * @access private
	 * @since 2.0.0
	 * @return void
	 */
	private function includes() {
		global $simontaxi_pages;
		/**
		 * Functions.
		 */
		require SIMONTAXI_PLUGIN_PATH . '/booking/functions.php';
		$simontaxi_file_includes = array(
				'constants' => array(
					'templatepath' => 'booking/includes/constants.php',
					'fullpath' => SIMONTAXI_PLUGIN_PATH . 'booking/includes/constants.php',
					'customizable' => 'yes',
					'operation' => 'require_once', // This may be 'include', 'include_once', 'require', 'require_once'
				),
				'booking' => array(
					'templatepath' => 'booking.php',
					'fullpath' => SIMONTAXI_PLUGIN_PATH . 'booking.php',
					'customizable' => 'yes',
					'operation' => 'require_once', // This may be 'include', 'include_once', 'require', 'require_once'
				),
				'vehicle_install' => array(
					'templatepath' => 'booking/includes/vehicle-install.php',
					'fullpath' => SIMONTAXI_PLUGIN_PATH . 'booking/includes/vehicle-install.php',
					'customizable' => 'yes',
					'operation' => 'require_once', // This may be 'include', 'include_once', 'require', 'require_once'
				),
				'vehicle_settings' => array(
					'templatepath' => 'booking/includes/vehicle-settings.php',
					'fullpath' => SIMONTAXI_PLUGIN_PATH . 'booking/includes/vehicle-settings.php',
					'customizable' => 'yes',
					'operation' => 'require_once', // This may be 'include', 'include_once', 'require', 'require_once'
				),
				
				'aq_resizer' => array(
					'templatepath' => 'booking/includes/aq-resizer.php',
					'fullpath' => SIMONTAXI_PLUGIN_PATH . 'booking/includes/aq-resizer.php',
					'customizable' => 'yes',
					'operation' => 'require_once', // This may be 'include', 'include_once', 'require', 'require_once'
				),
				'request_callbacks' => array(
					'templatepath' => 'booking/includes/pages/admin/request-callbacks.php',
					'fullpath' => SIMONTAXI_PLUGIN_PATH . 'booking/includes/pages/admin/request-callbacks.php',
					'customizable' => 'yes',
					'operation' => 'include_once', // This may be 'include', 'include_once', 'require', 'require_once'
				),
				'support_request' => array(
					'templatepath' => 'booking/includes/pages/admin/support-request.php',
					'fullpath' => SIMONTAXI_PLUGIN_PATH . 'booking/includes/pages/admin/support-request.php',
					'customizable' => 'yes',
					'operation' => 'include_once', // This may be 'include', 'include_once', 'require', 'require_once'
				),
				'manage_bookings' => array(
					'templatepath' => 'booking/includes/pages/admin/manage-bookings.php',
					'fullpath' => SIMONTAXI_PLUGIN_PATH . 'booking/includes/pages/admin/manage-bookings.php',
					'customizable' => 'yes',
					'operation' => 'require_once', // This may be 'include', 'include_once', 'require', 'require_once'
				),
				/**
				 * @since 2.0.8
				 */
				'manage_extensions' => array(
					'templatepath' => 'booking/includes/pages/admin/manage-extensions.php',
					'fullpath' => SIMONTAXI_PLUGIN_PATH . 'booking/includes/pages/admin/manage-extensions.php',
					'customizable' => 'yes',
					'operation' => 'include_once', // This may be 'include', 'include_once', 'require', 'require_once'
				),
				'manage_countries' => array(
					'templatepath' => 'booking/includes/pages/admin/manage-countries.php',
					'fullpath' => SIMONTAXI_PLUGIN_PATH . 'booking/includes/pages/admin/manage-countries.php',
					'customizable' => 'yes',
					'operation' => 'include_once', // This may be 'include', 'include_once', 'require', 'require_once'
				),
				'vehicle_shortcodes' => array(
					'templatepath' => 'booking/includes/vehicle-shortcodes.php',
					'fullpath' => SIMONTAXI_PLUGIN_PATH . 'booking/includes/vehicle-shortcodes.php',
					'customizable' => 'yes',
					'operation' => 'require_once', // This may be 'include', 'include_once', 'require', 'require_once'
				),
				'booking_functions' => array(
					'templatepath' => 'booking/includes/booking-functions.php',
					'fullpath' => SIMONTAXI_PLUGIN_PATH . 'booking/includes/booking-functions.php',
					'customizable' => 'yes',
					'operation' => 'require_once', // This may be 'include', 'include_once', 'require', 'require_once'
				),
				'ajax_functions' => array(
					'templatepath' => 'booking/includes/ajax-functions.php',
					'fullpath' => SIMONTAXI_PLUGIN_PATH . 'booking/includes/ajax-functions.php',
					'customizable' => 'yes',
					'operation' => 'require_once', // This may be 'include', 'include_once', 'require', 'require_once'
				),
				/**
				 * Session handling introduced
				 *
				 * @since 2.0.0
				*/
				'class_simontaxi_session' => array(
					'templatepath' => 'booking/includes/class-simontaxi-session.php',
					'fullpath' => SIMONTAXI_PLUGIN_PATH . 'booking/includes/class-simontaxi-session.php',
					'customizable' => 'yes',
					'operation' => 'require_once', // This may be 'include', 'include_once', 'require', 'require_once'
				),
				'error_handling' => array(
					'templatepath' => 'booking/includes/error-handling.php',
					'fullpath' => SIMONTAXI_PLUGIN_PATH . 'booking/includes/error-handling.php',
					'customizable' => 'yes',
					'operation' => 'require_once', // This may be 'include', 'include_once', 'require', 'require_once'
				),
				/**
				 * @since 2.0.9
				 */
				'class_simontaxi_manage_bookings' => array(
					'templatepath' => 'booking/includes/class-simontaxi-manage-bookings.php',
					'fullpath' => SIMONTAXI_PLUGIN_PATH . 'booking/includes/class-simontaxi-manage-bookings.php',
					'customizable' => 'yes',
					'operation' => 'require_once', // This may be 'include', 'include_once', 'require', 'require_once'
				),
			);
		$file_includes = apply_filters( 'simontaxi_file_includes', $simontaxi_file_includes );
		
		if ( ! empty( $file_includes ) ) {
			foreach( $file_includes as $key => $include_file ) {
				if ( 'yes' === $include_file['customizable'] ) {
					$template = $include_file['templatepath'];
					if ( simontaxi_is_template_customized( $template ) ) {
						if ( isset( $include_file['operation'] ) ) {
							switch( $include_file['operation'] ) {
								case 'include':
									include( simontaxi_get_theme_template_dir_name() . $template );
									break;
								case 'include_once':
									include_once( simontaxi_get_theme_template_dir_name() . $template );
									break;
								case 'require':
									require( simontaxi_get_theme_template_dir_name() . $template );
									break;
								case 'require_once':
									require_once( simontaxi_get_theme_template_dir_name() . $template );
									break;
								default:
									include_once( simontaxi_get_theme_template_dir_name() . $template );
									break;
							}
						} else {
							include_once( simontaxi_get_theme_template_dir_name() . $template );
						}						
					} else {
						include_once( $include_file['fullpath'] );
					}
				} else {
					include_once( $include_file['fullpath'] );
				}
			}
		}
		global $pagenow;
		if ( $pagenow === 'plugins.php' ) {
			$this->updater();
		}
	}

	/**
	 * Loads the plugin language files.
	 *
	 * @access public
	 * @since 2.0.0
	 * @return void
	 */
	public function load_textdomain() {

		// Set filter for plugin's languages directory.
		$simontaxi_lang_dir  = dirname( plugin_basename( SIMONTAXI_PLUGIN_FILE ) ) . '/languages/';
		$simontaxi_lang_dir  = apply_filters( 'simontaxi_languages_directory', $simontaxi_lang_dir );

		// Load the default language files.
		load_plugin_textdomain( 'simontaxi', false, $simontaxi_lang_dir );
	}
	
	/**
	 * Gets updater instance.
	 * @since 2.0.0
	 *
	 * @return STVB UPdater
	 */
	public function updater() {
		require_once SIMONTAXI_PLUGIN_PATH .  'booking/includes/updaters/class-simontaxi-updater.php';
		$updater = new Simontaxi_Updater();
		$updater->init();
	}
	
	/**
	 * Get the template path.
	 *
	 * @return string
	 *
	 *@since 2.0.6
	 */
	public function template_path() {
		return apply_filters( 'simontaxi_template_path', 'vehicle-booking/' );
	}
	
	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( SIMONTAXI_PLUGIN_FILE ) );
	}

}

endif;

/**
 * The main function for that returns Vehicle Booking
 *
 * The main function responsible for returning the one true Vehicle Booking
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $simontaxi = STVB(); ?>
 *
 * @since 1.0.0
* @return object|Simontaxi_Vehicle_Booking The one true Simontaxi_Vehicle_Booking Instance.
 */
function STVB() {
	return Simontaxi_Vehicle_Booking::instance();
}

STVB();

$plugin = plugin_basename( __FILE__ );
function simontaxi_plugin_add_settings_link( $links ) {
    $settings_link = '<a href="edit.php?post_type=vehicle&page=vehicle_settings">' . __( 'Settings', 'simontaxi' ) . '</a>';
    array_push( $links, $settings_link );
	
	$plugin = plugin_basename( __FILE__ );
	
	$plugin_data = array(
		'slug' => $plugin,
		'folder' => 'vehicle-booking',
		'PluginURI' => 'https://simontaxi.conquerorstech.com/',
		'plugin_name' => esc_html__( 'Simontaxi - Vehicle Booking', 'simontaxi' ),
		'support' => 'https://simontaxi.conquerorstech.com/submit-ticket',
	);
	
	$support_link = '<a href="' . $plugin_data['support'] . '" target="_blank">' . __( 'Support', 'simontaxi' ) . '</a>';
    array_push( $links, $support_link );
	
	// Details link using API info, if available
	if ( isset( $plugin_data['slug'] ) && current_user_can( 'install_plugins' ) ) {
		$links[] = sprintf( '<a href="%s" class="thickbox" aria-label="%s" data-title="%s">%s</a>',
			esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=' . $plugin_data['folder'] .
				'&TB_iframe=true&width=600&height=550&section=changelog' ) ),
			esc_attr( sprintf( __( 'More information about %s', 'simontaxi' ), $plugin_data['plugin_name'] ) ),
			esc_attr( $plugin_data['plugin_name'] ),
			__( 'View details', 'simontaxi' )
		);
	} elseif ( ! empty( $plugin_data['PluginURI'] ) ) {
		$links[] = sprintf( '<a href="%s">%s</a>',
			esc_url( $plugin_data['PluginURI'] ),
			__( 'Visit plugin site', 'simontaxi' )
		);
	}
  	return $links;
}
add_filter( "plugin_action_links_$plugin", 'simontaxi_plugin_add_settings_link' );
