<?php
/**
 * Simontaxi - Vehicle Booking Session
 *
 * This is a wrapper class for WP_Session / PHP $_SESSION and handles the storage of session data between pages, errors, etc
 *
 * @author   Digisamaritan
 * @package  Simontaxi - Vehicle Booking
 * @since    2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
		
		$simontaxi_file_includes = array(
				'constants' => array(
					'templatepath' => 'booking/includes/constants.php',
					'fullpath' => SIMONTAXI_PLUGIN_PATH . 'booking/includes/constants.php',
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
		
		// $this->updater();
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
