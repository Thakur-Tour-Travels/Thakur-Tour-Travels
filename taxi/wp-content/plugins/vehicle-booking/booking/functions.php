<?php
/**
 * Plugin core functions
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  Functions
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since 2.0.8
 *
 * Function to check if the template is customized.
 * @param String $template Page Path.
 */
function simontaxi_is_template_customized( $template )
{
	$theme_templates_path = apply_filters( 'simontaxi_templates_dir_final', simontaxi_get_theme_template_dir_name() );
	
	if( file_exists( $theme_templates_path . $template ) ) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function simontaxi_get_theme_template_dir_name() {
	return apply_filters('simontaxi_templates_dir_final', get_stylesheet_directory() . '/' . apply_filters( 'simontaxi_templates_dir', 'vehicle-booking-templates/' ));
}

if ( ! function_exists( 'simontaxi_get_option' ) ) {
	/**
	 * Returns given option value
	 *
	 * @param string $option - option to get.
	 * @param string $default - default value.
	 * @since 1.0.0
	 * @return string
	 */
	function simontaxi_get_option( $option = '', $default = '', $group = 'simontaxi_settings' ) {
		$simontaxi_settings = get_option( $group );
		if ( ! empty( $option ) ) {
			return ( isset( $simontaxi_settings[ $option ] ) ) ? $simontaxi_settings[ $option ] : $default;
		} else {
			return $simontaxi_settings;
		}
	}
}

if ( ! function_exists( 'simontaxi_validate_envato_curl' ) ) :
	/**
	 * This function return the boolean based on envato result
	 *
	 * @since 2.0.0
	 */
	function simontaxi_validate_envato_curl( $product_code ) {
		
		$url = 'https://api.envato.com/v3/market/author/sale?code=' . $product_code;
		$curl = curl_init( $url );
		$personal_token = '3GteFmA41PkDQEEaao8Bq78mLMzUPQMF';
		$header = array();
		$header[] = 'Authorization: Bearer ' . $personal_token;
		$header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:41.0) Gecko/20100101 Firefox/41.0';
		$header[] = 'timeout: 20';
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $header );
		$envatoRes = curl_exec( $curl );
		curl_close( $curl );
		$envatoRes = json_decode( $envatoRes );
		
		$valid = false;
		if ( isset( $envatoRes->item->name ) && 'SimonTaxi - Taxi Booking WordPress Theme' === $envatoRes->item->name ) {
			$valid = true;
		}
		return $valid;
	}
endif;

function simontaxi_validate_envato( $product_code ) {
	
	$cache_key = 'simontaxi_product_code';
	$cache = get_option( $cache_key );
	if ( empty( $cache ) ) {
		$cache = array(
			'timeout' => '',
			'value' => $product_code,
			'valid' => 'no',
		);
	}
	$valid = false;
	$hours = 5;
	if( empty( $cache['timeout'] ) ) {
		$valid = simontaxi_validate_envato_curl( $product_code );
		if ( $valid ) {
			$data = array(
				'timeout' => strtotime( "+$hours hours", time() ),
				'value'   => $product_code,
				'valid' => 'yes',
			);
			update_option( $cache_key, $data, 'yes' );
		} else {
			$data = array(
				'timeout' => strtotime( "+$hours hours", time() ),
				'value'   => $product_code,
				'valid' => 'no',
			);
			update_option( $cache_key, $data, 'yes' );
		}
	} elseif( time() > $cache['timeout'] ) {
		$valid = simontaxi_validate_envato_curl( $product_code );
		if ( $valid ) {
			$data = array(
				'timeout' => strtotime( "+$hours hours", time() ),
				'value'   => $product_code,
				'valid' => 'yes',
			);
			update_option( $cache_key, $data, 'yes' );
		} else {
			$data = array(
				'timeout' => strtotime( "+$hours hours", time() ),
				'value'   => $product_code,
				'valid' => 'no',
			);
			update_option( $cache_key, $data, 'yes' );
		}
	} else {
		if ( 'yes' === $cache['valid'] ) {
			$valid = true;
		} else {
			$valid = false;
			$data = array(
				'timeout' => '',
				'value' => $product_code,
				'valid' => 'no',
			);
			update_option( $cache_key, $data, 'yes' );
		}		
	}
	return $valid;
}

if ( ! function_exists( 'simontaxi_get_default_title_plural' ) ) {
	/**
	 * Returns Default usage plural title for the items
	 *
	 * @since 1.0.0
	 * @return string
	 */
	function simontaxi_get_default_title_plural() {
		return apply_filters( 'simontaxi__filter_default_title_plural', simontaxi_get_option( 'default_title_plural', esc_html__( 'Vehicles', 'simontaxi' ) ) );
	}
}

if ( ! function_exists( 'simontaxi_get_default_title' ) ) {
	/**
	 * Returns Default usage title for the items
	 *
	 * @since 1.0.0
	 * @return string
	 */
	function simontaxi_get_default_title() {
		return apply_filters( 'simontaxi_filter_default_title', simontaxi_get_option( 'default_title', esc_html__( 'Vehicle', 'simontaxi' ) ) );
	}
}

if ( ! function_exists( 'simontaxi_booking_types' ) ) :
	/**
	 * Return active tab. which means user selected tab
	 *
	 * @since 2.0.8
	 * @return string
	 */
	function simontaxi_booking_types() {		
		return apply_filters( 'simontaxi_additional_booking_types', 
			array(
				'p2p' => 'Point to Point Transfer',
				'airport' => 'Airport Transfer',
				'hourly' => 'Hourly Rental',
			)
		);
	}
endif;

if ( ! function_exists( 'simontaxi_available_roles' ) ) :
	/**
	 * This function return the capabiities
	 *
	 * @since 2.0.0
	 */
	function simontaxi_available_roles() {
		$roles = array(
			'Customer' => esc_html__( 'Customer', 'simontaxi' ),
			'executive' => esc_html__( 'Executive', 'simontaxi' ),
		);
		return apply_filters( 'simontaxi_available_roles', $roles );
	}
endif;

if ( ! function_exists( 'simontaxi_get_label_singular' ) ) :
	/**
	 * Get Singular Label
	 *
	 * @since 1.0.0
	 *
	 * @param bool $lowercase - Case of the string.
	 * @return string $defaults['singular'] Singular label
	 */
	function simontaxi_get_label_singular( $lowercase = false ) {
		$defaults = simontaxi_get_default_labels();
		return ( $lowercase) ? strtolower( $defaults['singular'] ) : $defaults['singular'];
	}
endif;

/**
* Constants used throughout the booking applicaiton
*/
define( 'VARIABLE_PREFIX', 'v_' );

if ( ! function_exists( 'simontaxi_get_default_labels' ) ) :
	/**
	 * Get Default Labels
	 *
	 * @since 1.0.0
	 * @return array $defaults Default labels
	 */
	function simontaxi_get_default_labels() {
		$defaults = array(
			'singular' => simontaxi_get_default_title(),
			'plural'   => simontaxi_get_default_title_plural(),
		);
		return apply_filters( 'simontaxi_filter_default_vehicle_name', $defaults );
	}
endif;

if ( ! function_exists( 'simontaxi_get_label_plural' ) ) :
	/**
	 * Get Plural Label
	 *
	 * @since 1.0.0
	 * @param bool $lowercase - Case of the string.
	 * @return string $defaults['plural'] Plural label
	 */
	function simontaxi_get_label_plural( $lowercase = false ) {
		$defaults = simontaxi_get_default_labels();
		return ( $lowercase ) ? strtolower( $defaults['plural'] ) : $defaults['plural'];
	}
endif;

if ( ! function_exists( 'get_capabilities' ) ) :
	/**
	 * Function to get Capabilities
	 *
	 * @param String $capability_type - Type.
	 * @since 2.0.0
	 */
	function get_capabilities( $capability_type ) {
		return array(
			// Meta capabilities.
			'edit_post' => "edit_{$capability_type}",
			'read_post' => "read_{$capability_type}",
			'delete_post' => "delete_{$capability_type}",

			// Primitive capabilities used outside of map_meta_cap().
			'edit_posts' => "edit_{$capability_type}s",
			'edit_others_posts' => "edit_other_{$capability_type}s",
			'publish_posts' => "publish_{$capability_type}s",
			'read_private_posts' => "read_private_{$capability_type}",

			// Primitive capabilities used within map_meta_cap().
			'read' => 'read',
			'delete_posts' => "delete_{$capability_type}s",
			'delete_private_posts' => "delete_private_{$capability_type}s",
			'delete_published_posts' => "delete_published_{$capability_type}s",
			'delete_others_posts' => "delete_others_{$capability_type}s",
			'edit_private_posts' => "edit_private_{$capability_type}s",
			'edit_published_posts' => "edit_published_{$capability_type}s",
		);
	}
endif;

///////////////////////////////////////////////////////////////////////////////
add_action( 'init', 'simontaxi_do_output_buffer' );
/**
 * Allow redirection even if my theme starts to send output to the browser
 */
function simontaxi_do_output_buffer() {
	ob_start();
}


/**
 * Enqueue scripts and styles on admin end.
 */
function simontaxi_enqueue_media_uploader() {
	// Use minified libraries if SIMONTAXI_SCRIPT_DEBUG is turned off
	$suffix = ( defined( 'SIMONTAXI_SCRIPT_DEBUG' ) && SIMONTAXI_SCRIPT_DEBUG ) ? '' : '.min';
	
	wp_enqueue_style( 'jquery-ui', SIMONTAXI_PLUGIN_URL . 'css/jquery-ui.min.css' );
	wp_enqueue_style( 'simontaxi-admin-style', SIMONTAXI_PLUGIN_URL . 'css/admin-style' . $suffix . '.css' );	
	 
	/**
	 * Simple Line icons
	 *
	 * @since 2.0.0
	*/
	wp_enqueue_style( 'simple-line-icons', 'https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css' );
	
	if ( ! wp_style_is( 'font-awesome' ) ) {
		wp_enqueue_style( 'font-awesome', SIMONTAXI_PLUGIN_URL . 'css/font-awesome.min.css' );
	}
	
	/**
	 * Bootstrap
	 *
	 * @since 2.0.6
	 */
	wp_enqueue_script( 'bootstrap', SIMONTAXI_PLUGIN_URL . 'js/bootstrap.min.js' );
	/*
	if ( ! wp_style_is( 'bootstrap' ) ) {
		wp_enqueue_style( 'bootstrap', SIMONTAXI_PLUGIN_URL . 'css/bootstrap.min.css' );
	}
	*/
	wp_enqueue_script( 'simontaxi-admin-main', SIMONTAXI_PLUGIN_URL . 'js/admin-main' . $suffix . '.js' );
	
	
	//wp_enqueue_style( 'simontaxi-bootstrap-select', SIMONTAXI_PLUGIN_URL . 'css/bootstrap-select.min.css' );
	//wp_enqueue_script( 'simontaxi-bootstrap-select', SIMONTAXI_PLUGIN_URL . 'js/bootstrap-select.min.js', array( 'jquery' ) );
	
	wp_register_style( 'select2', SIMONTAXI_PLUGIN_URL . 'css/select2' . $suffix . '.css', false, '1.0', 'all' );
	wp_enqueue_style( 'select2' );
    wp_register_script( 'select2', SIMONTAXI_PLUGIN_URL . 'js/select2.full' . $suffix . '.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'select2' );
		
	wp_register_script( 'jquery-ui-autocomplete', array( 'jquery' ) );
		
	$google_api = simontaxi_get_option( 'google_api', 'AIzaSyCqRV6HQ_BSw3MMjPen2bT2IwDnZgfjwu4' );
	wp_register_script( 'simontaxi-googleapis', '//maps.googleapis.com/maps/api/js?libraries=places&key=' . $google_api );
	
	/**
	 * Since we are separating the plugin from theme ie. Plugin can use independent, we need to use separate variables.
	 *
	 * @since 2.0.8
	*/
	$loaders = simontaxi_get_option( 'loaders', array() );
	$main_loader = ( isset( $loaders['main_loader'] ) && '' !== $loaders['main_loader'] ) ? $loaders['main_loader'] : SIMONTAXI_PLUGIN_URL . '/images/preloader.gif';

	$ajax_loader = ( isset( $loaders['ajax_loader'] ) && '' !== $loaders['ajax_loader'] ) ? $loaders['ajax_loader'] : SIMONTAXI_PLUGIN_URL . '/images/preloader.gif';
	wp_localize_script( 'simontaxi-admin-main', 'simontaxi_vars', apply_filters( 'simontaxi_ajax_vars_admin', array(
		'ajaxurl'                 => admin_url( 'admin-ajax.php' ),
		'base_url' => site_url(),
		'plugin_url' => SIMONTAXI_PLUGIN_URL,
		'main_loader' => $main_loader,
		'ajax_loader' => $ajax_loader,
	) ) );
	
	wp_enqueue_script( 'jquery-ui-datepicker', array( 'jquery', 'jquery-ui-core' ), time() );
	wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'simontaxi_enqueue_media_uploader' );

add_action( 'admin_footer', 'simontaxi_custom_css_admin' );

/**
 * Enqueue scripts and styles on admin end to fix footer issue.
 *
 * @since 2.0.0
 */
function simontaxi_custom_css_admin() {
	echo '<style>#wpfooter { position: initial !important; }</style>';
}



/**
 * Enqueue scripts and styles on front end.
 */
function simontaxi_vehicle_scripts() {
	
	// Use minified libraries if SIMONTAXI_SCRIPT_DEBUG is turned off
	$suffix = ( defined( 'SIMONTAXI_SCRIPT_DEBUG' ) && SIMONTAXI_SCRIPT_DEBUG ) ? '' : '.min';
	
	wp_enqueue_style( 'jquery-ui', SIMONTAXI_PLUGIN_URL . 'css/jquery-ui.min.css' );
	
	if ( ! wp_style_is( 'font-awesome' ) ) {
		wp_enqueue_style( 'font-awesome', SIMONTAXI_PLUGIN_URL . 'css/font-awesome.min.css' );
	}
	/*
	if ( ! wp_style_is( 'bootstrap-select2' ) ) {
		wp_enqueue_style( 'bootstrap-select2', SIMONTAXI_PLUGIN_URL . '/css/bootstrap-select.min.css' );
	}
	*/
	if ( ! wp_style_is( 'bootstrap' ) ) {
		wp_enqueue_style( 'bootstrap', SIMONTAXI_PLUGIN_URL . 'css/bootstrap.min.css' );
	}
	wp_enqueue_style( 'simple-line-icons', 'https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css' );
	
	if ( ! wp_style_is( 'simontaxi-frontend' ) ) {
		wp_enqueue_style( 'simontaxi-frontend', SIMONTAXI_PLUGIN_URL . 'css/booking-frontend' . $suffix . '.css' );
	}

	if ( ! wp_script_is( 'jquery' ) ) {
		wp_enqueue_script( 'jquery' );
	}
	if ( ! wp_script_is( 'bootstrap-js' ) ) {
		wp_enqueue_script( 'bootstrap-js', SIMONTAXI_PLUGIN_URL . 'js/bootstrap.min.js' );
	}

	if ( ! wp_script_is( 'jquery-ui-autocomplete' ) ) {
		wp_enqueue_script( 'jquery-ui-autocomplete', array( 'jquery' ) );
	}
	if ( ! wp_script_is( 'jquery-ui-datepicker' ) ) {
		wp_enqueue_script( 'jquery-ui-datepicker', array( 'jquery', 'jquery-ui-core' ), time() );
	}
	// wp_enqueue_script( 'bootstrap-select2', SIMONTAXI_PLUGIN_URL . '/js/bootstrap-select.min.js', array( 'jquery' ) );
	
	wp_register_style( 'select2', SIMONTAXI_PLUGIN_URL . 'css/select2' . $suffix . '.css', false, '1.0', 'all' );
	wp_enqueue_style( 'select2' );
    wp_register_script( 'select2', SIMONTAXI_PLUGIN_URL . 'js/select2' . $suffix . '.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'select2' );

	
	wp_enqueue_script( 'simontaxi-front-main-vars', SIMONTAXI_PLUGIN_URL . 'js/front-main' . $suffix . '.js', array( 'jquery' ) );
	
	$google_api = simontaxi_get_option( 'google_api', 'AIzaSyCqRV6HQ_BSw3MMjPen2bT2IwDnZgfjwu4' );
	wp_register_script( 'simontaxi-googleapis', '//maps.googleapis.com/maps/api/js?libraries=places&key=' . $google_api );
	
	wp_enqueue_script( 'simontaxi-gmap3', SIMONTAXI_PLUGIN_URL . 'js/gmap3.min.js' );

	/**
	 * We are receiving request from client to change loader image, so here is the provision.
	 *
	 * @since 2.0.0
	*/
	$loaders = simontaxi_get_option( 'loaders', array() );
	$main_loader = ( isset( $loaders['main_loader'] ) && '' !== $loaders['main_loader'] ) ? $loaders['main_loader'] : SIMONTAXI_PLUGIN_URL . '/images/preloader.gif';

	$ajax_loader = ( isset( $loaders['ajax_loader'] ) && '' !== $loaders['ajax_loader'] ) ? $loaders['ajax_loader'] : SIMONTAXI_PLUGIN_URL . '/images/preloader.gif';
	wp_localize_script( 'simontaxi-front-main-vars', 'simontaxi_vars', apply_filters( 'simontaxi_ajax_vars', array(
		'ajaxurl'                 => admin_url( 'admin-ajax.php' ),
		'base_url' => site_url(),
		'plugin_url' => SIMONTAXI_PLUGIN_URL,
		'main_loader' => $main_loader,
		'ajax_loader' => $ajax_loader,
	) ) );
	add_action( 'wp_head', 'simontaxi_main_loader' );
}
add_action( 'wp_enqueue_scripts', 'simontaxi_vehicle_scripts', 11 );

if ( ! function_exists( 'simontaxi_main_loader' ) ) :
	/**
	 * To change the loader image provided with theme.
	 *
	 * @since 2.0.2
	 */
	function simontaxi_main_loader() {
		$loaders = simontaxi_get_option( 'loaders', array() );
		$main_loader = ( isset( $loaders['main_loader'] ) && '' !== $loaders['main_loader'] ) ? $loaders['main_loader'] : SIMONTAXI_PLUGIN_URL . '/images/preloader.gif';
		?>
		<style type="text/css">
		#status{
				background-image: url(<?php echo esc_url( $main_loader ); ?>) !important;
		}
		</style>
		<?php
	}
endif;


/**
 * Append additional menu items.
 *
 * @param mixed $items - items.
 * @param array $args - items array.
 * @since 1.0.0
 */
function simontaxi_vehicle_menu( $items, $args ) {
	if ( 'primary' !== $args->theme_location ) {
		return $items;
	}
	$link = '';
	$login_menu_item = simontaxi_get_option( 'login_menu_item', 'yes' );

	/**
	 * Let us check whether the funtion is available or not. This function is availanle in booking plusin which is provided along with this theme.
	*/
	if ( function_exists( 'simontaxi_get_bookingsteps_urls' ) && 'yes' === $login_menu_item  ) {
		if ( is_user_logged_in() ) {
			$link .= sprintf( '<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-235"><a href="%s">' . esc_html__( 'My Account', 'simontaxi' ) . '</a></li>', simontaxi_get_bookingsteps_urls( 'user_account' ) );
		} else {
			$link .= sprintf( '<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-235"><a href="%s">' . esc_html__( 'Login', 'simontaxi' ) . '</a></li>', simontaxi_get_bookingsteps_urls( 'login' ) );
		}
	}
	return $items . $link;
}
add_filter( 'wp_nav_menu_items', 'simontaxi_vehicle_menu', 10, 2 );

if ( ! function_exists( 'vehicle_settings' ) ) :
	/**
	 * Returns the instance of vehicle settings class
	 *
	 * @since 1.0.0
	 * @return object
	 */
	function vehicle_settings() {
			return Simontaxi_Vehicle_settings::instance();
	}
endif;

if ( ! function_exists( 'simontaxi_currencies' ) ) :
	/**
	 * Returns the currency list select item
	 *
	 * @since 1.0.0
	 * @return string
	 */
	function simontaxi_currencies() {
		global $wpdb;
		
		$expired = false;
		$simontaxi_currencies = get_option( 'simontaxi_currencies' );
		if( empty( $simontaxi_currencies['timeout'] ) || time() > $simontaxi_currencies['timeout'] ) {
			$expired = true; // Cache is expired
		} else {
			$currency_list = $simontaxi_currencies['value'];
		}
		
		if ( true === $expired ) {
			$currency_list = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}st_countries WHERE `name` != '' AND  `currency_code` != '' GROUP BY `name` ORDER BY `name`" );
			
			$data = array(
				'timeout' => strtotime( '+24 hours', time() ),
				'value'   => $currency_list,
			);
			update_option( 'simontaxi_currencies', $data, 'no' );
		}
		return $currency_list;
	}
endif;

if ( ! function_exists( 'simontaxi_countries' ) ) :
	/**
	 * Returns the countries list select item
	 *
	 * @since 1.0.0
	 * @return string
	 */
	function simontaxi_countries( $show_currency = 'yes', $country_code = '' ) {
		global $wpdb;

		$expired = false;
		$simontaxi_countries = get_option( 'simontaxi_countries' );
		if( empty( $simontaxi_countries['timeout'] ) || time() > $simontaxi_countries['timeout'] ) {
			$expired = true; // Cache is expired
		} else {
			$country_list = $simontaxi_countries['value'];
		}
		
		if ( true === $expired ) {
			$country_list = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}st_countries WHERE `name` != '' GROUP BY `name` ORDER BY name" );
			
			$data = array(
				'timeout' => strtotime( '+24 hours', time() ),
				'value'   => $country_list,
			);
			update_option( 'simontaxi_countries', $data, 'no' );
		}
		$countries = array();
		if ( ! empty( $country_list ) ) {

			foreach ( $country_list as $country ) {
				if ( ! empty( $country_code ) ) {
					if ( $country_code == $country->iso_alpha2 ) {
						if ( 'no' === $show_currency ) {
							$countries[ $country->iso_alpha2 ] = $country->name;				
						} else {
							$countries[ $country->iso_alpha2 ] = $country->name . ' ( ' . $country->currency_symbol . ' )';
						}
						break;
					}
				} else {
					if ( 'no' === $show_currency ) {
						$countries[ $country->iso_alpha2 ] = $country->name;				
					} else {
						$countries[ $country->iso_alpha2 ] = $country->name . ' ( ' . $country->currency_symbol . ' )';
					}
				}
			}
		}
		
		return $countries;
	}
endif;

if ( ! function_exists( 'simontaxi_get_currency_symbol' ) ) :
	/**
	 * Return the currency symbol for the entire vehicle system
	 *
	 * @since 1.0.0
	 * @param string $value - Value to display.
	 * @return string
	 */
	function simontaxi_get_currency_symbol( $value = '' ) {
		global $wpdb;
		$currency_code = simontaxi_get_option( 'vehicle_currency' );
		if ( '' !== $currency_code ) {
			/**
			 * Since we are displaying all countries, some countries using same curreny so we need to take only ISO code 
			 *
			 * @since 2.0.0
			 */
			$currency_code = substr( $currency_code, 0, 3 );
			$simontaxi_currency_details = get_option( 'simontaxi_currency' );
			$currency_symbol_found = false;
			if ( ! empty( $simontaxi_currency_details ) ) {
				if ( $simontaxi_currency_details->currency_code === $currency_code ) {
					$currency_symbol = $simontaxi_currency_details->currency_symbol;
					$currency_symbol_found = true;
				}
			}
			if ( false === $currency_symbol_found ) {
				$result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}st_countries WHERE currency_code = '%s'", $currency_code ) );
				
				if ( ! empty( $result ) ) {
					update_option( 'simontaxi_currency', $result, 'no' );
					$currency_symbol = $result->currency_symbol;
				} else {
					/*
					"$" symbol is default
					*/
					$currency_symbol = '&#36;';
				}
			}
		} else {
			/*
			"$" symbol is default
			*/
			$currency_symbol = '&#36;';
		}

		if ( '' !== $value ) {
			$currency_symbol = simontaxi_currency_placement( $currency_symbol, $value );
		}
		return $currency_symbol;
	}
endif;

if ( ! function_exists( 'simontaxi_get_currency_code' ) ) :
	/**
	 * Return the currency code for the entire vehicle system
	 *
	 * @param string $value - Value to display.
	 * @return string
	 * @since 1.0.0
	 */
	function simontaxi_get_currency_code( $value = '' ) {
		$currency_symbol = simontaxi_get_option( 'vehicle_currency' );
		if ( '' === $currency_symbol ) {
			$currency_symbol = 'USD';
		} else {
			/**
			 * Since we are displaying all countries, some countries using same curreny so we need to take only ISO code 
			 *
			 * @since 2.0.0
			 */
			 $currency_symbol = substr( $currency_symbol, 0, 3 );
		}
		if ( '' !== $value ) {
			$currency_symbol = simontaxi_currency_placement( $currency_symbol, $value );
		}
		return $currency_symbol;
	}
endif;

if ( ! function_exists( 'simontaxi_currency_placement' ) ) :
	/**
	 * This function returns the currency based on admin settings
	 *
	 * @param string $currency - currency.
	 * @param string $value - Value to display.
	 * @return string
	 * @since 1.0.0
	 */
	function simontaxi_currency_placement( $currency, $value ) {
		/**
		* Format the number according to the admin settings 'Currency' - (Number of Decimals, Decimal Separator, Thousand Separator)
		*/
		$number_of_decimals = simontaxi_get_option( 'number_of_decimals', 2 );
		if ( empty( $number_of_decimals ) ) {
			$number_of_decimals = 2;
		}
		$decimal_separator = simontaxi_get_option( 'decimal_separator', '.' );
		$thousand_separator = simontaxi_get_option( 'thousand_separator', ',' );
		if ( filter_var( $value, FILTER_VALIDATE_FLOAT ) === false ) {
			$number_of_decimals = 0;
		}
		
		/**
		 * @since 2.0.2
		 */
		$value = number_format( $value, $number_of_decimals, $decimal_separator, $thousand_separator );
		
		$currency_position = simontaxi_get_option( 'currency_position', 'left' );
		if ( 'left' === $currency_position ) {
			$currency_value = $currency . $value; /* Appending currency left side */
		} elseif ( 'right' === $currency_position ) {
			$currency_value = $value . $currency; /* Appending currency right side */
		} elseif ( 'left_with_space' === $currency_position ) {
			$currency_value = $currency . ' ' . $value; /* Appending currency left side with space */
		} elseif ( 'right_with_space' === $currency_position ) {
			$currency_value = $value . ' ' . $currency; /* Appending currency right side with space */
		}
		return $currency_value;
	}
endif;

if ( ! function_exists( 'simontaxi_get_currency' ) ) :
	/**
	 * Return the currency code or symbol depends on admin settings
	 *
	 * @param string $value - Value to display.
	 * @return string
	 * @since 1.0.0
	 */
	function simontaxi_get_currency( $value = '' ) {
		$display_currency = simontaxi_get_option( 'display_currency', 'symbol' );
		if ( 'code' === $display_currency ) {
			$currency = simontaxi_get_currency_code();
		} else {
			$currency = simontaxi_get_currency_symbol();
		}
		if ( '' !== $value ) {
			$currency = simontaxi_currency_placement( $currency, $value );
		}
		return $currency;
	}
endif;

if ( ! function_exists( 'simontaxi_get_country' ) ) :
	 /**
	  * Return the country code for the entire vehicle system
	  *
	  * @return string
	  * @since 1.0
	  */
	function simontaxi_get_country() {
		return simontaxi_get_option( 'vehicle_country', 'USA' );
	}
endif;

if ( ! function_exists( 'simontaxi_get_active_tab' ) ) :
	/**
	 * Return active tab. which means user selected tab
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_active_tab() {
		$tabs = simontaxi_get_option( 'active_tabs', 'Point to Point Transfer' );

		if ( 'Point to Point Transfer' === $tabs ) {
			$tabs = simontaxi_booking_types();
		}
		return $tabs;
	}
endif;



if ( ! function_exists( 'simontaxi_primary_booking_types' ) ) :
	/**
	 * Return active tab. which means user selected tab
	 *
	 * @since 2.0.8
	 * @return string
	 */
	function simontaxi_primary_booking_types() {		
		return array(
				'p2p' => 'Point to Point Transfer',
				'airport' => 'Airport Transfer',
				'hourly' => 'Hourly Rental',
			);
	}
endif;

if ( ! function_exists( 'simontaxi_get_airports' ) ) :
	/**
	 * Return airports
	 *
	 * @since 1.0
	 * @return array
	 */
	function simontaxi_get_airports() {
		$args = array(
			'taxonomy' => 'vehicle_locations',
			'hide_empty' => false,
			'meta_key' => 'location_type',
			'meta_value' => 'airport',
		);
		$airports = get_terms( $args );
		$airports_return = array();
		if ( ! empty( $airports ) && ! is_wp_error( $airports ) ) {
			foreach ( $airports as $term ) {
				$airports_return[] = array(
					'id' => $term->term_id,
					'name' => $term->name,
					'slug' => $term->slug,
					'description' => $term->description,
					'location_type' => get_term_meta( $term->term_id, 'location_type', true ),
					'location_address' => get_term_meta( $term->term_id, 'location_address', true ),
					'distances' => get_term_meta( $term->term_id, 'distances', true ),
					'times' => get_term_meta( $term->term_id, 'location_type', true ),
				);
			}
		}
		return $airports_return;
	}
endif;

if ( ! function_exists( 'simontaxi_get_hourly_packages' ) ) {
	/**
	 * Return Hourly packages
	 *
	 * @since 1.0
	 * @param string $get - term to get.
	 * @return array
	 */
	function simontaxi_get_hourly_packages( $get = '' ) {
		$args = array(
			'taxonomy' => 'hourly_packages',
			'hide_empty' => false,
		);
		$packages = get_terms( $args );
		$packages_return = array();
		if ( ! empty( $packages ) && ! is_wp_error( $packages ) ) {
			foreach ( $packages as $term ) {
				if ( '' === $get ) {
					$packages_return[] = array(
						'name' => $term->name,
						'slug' => $term->slug,
						'description' => $term->slug,
						'hourly_hours' => get_term_meta( $term->term_id, 'hourly_hours', true ),
						'hourly_price' => get_term_meta( $term->term_id, 'hourly_price', true ),
					);
				} elseif ( is_array( $get ) ) {

				} else {
					if ( $get === $term->name ) {
						$packages_return[] = array(
							'name' => $term->name,
							'slug' => $term->slug,
							'description' => $term->slug,
							'hourly_hours' => get_term_meta( $term->term_id, 'hourly_hours', true ),
							'hourly_price' => get_term_meta( $term->term_id, 'hourly_price', true ),
					);
					}
				}
			}
		}
		return $packages_return;
	}
}

if ( ! function_exists( 'simontaxi_get_distance_time' ) ) {
	/**
	 * Return Distance and Time From one point to another point
	 *
	 * @since 1.0
	 * @return array
	 */
	function simontaxi_get_distance_time( $from, $to, $case='' ) {
        
		if ( $from != '' && $to != '' ) {
            $dist = $time = false;
			$term = ( is_numeric( $from ) ) ? get_term_by( 'id', $from, 'vehicle_locations' ) : get_term_by( 'name', $from, 'vehicle_locations' );
			$term2 = ( is_numeric( $to ) ) ? get_term_by( 'id', $to, 'vehicle_locations' ) : get_term_by( 'name', $to, 'vehicle_locations' );
			
            if ( ! ( $term == false || $term2 == false) ) {
                $from_term_id = $term->term_id;
                $to_term_id = $term2->term_id;

                $from_distances = ( array ) json_decode(get_term_meta( $from_term_id, 'distances', true ) );
				$from_times = ( array ) json_decode(get_term_meta( $from_term_id, 'times', true ) );

                $to_distances = ( array ) json_decode(get_term_meta( $to_term_id, 'distances', true ) );
				$to_times = ( array ) json_decode(get_term_meta( $to_term_id, 'times', true ) );
			
				$from_dt_index = is_numeric( $from ) ? 'dt_' . $from : 'dt_' . $from_term_id;
				$to_dt_index = is_numeric( $to ) ? 'dt_' . $to : 'dt_' . $to_term_id;
				
                if ( isset( $from_distances[ $to_dt_index ] ) && $from_distances[ $to_dt_index ] != '' ) {
					$dist = $from_distances[ $to_dt_index ];
				} elseif ( isset( $to_distances[ $from_dt_index ] ) && $to_distances[ $from_dt_index ] != '' ) {
					$dist = $to_distances[ $from_dt_index ] ;	
				} else {
					$dist = false;	
				}
                if ( $dist=='' ) {
					return false;	
				}

				$from_tm_index = is_numeric( $from ) ? 'tm_' . $from : 'tm_' . $from_term_id;
				$to_tm_index = is_numeric( $to ) ? 'tm_' . $to : 'tm_' . $to_term_id;
                if ( isset( $from_times[ $to_tm_index ] ) ) {
					$tm = $from_times[ $to_tm_index ] ;
				}
                elseif ( isset( $to_times[ $from_tm_index ] ) ) {
					$tm = $to_times[ $from_tm_index ] ;	
				} else {
					$tm = false;	
				}

                $u = simontaxi_get_distance_units();
				$output = array();
				$output['distance'] = $dist;

				$output['distance_units'] = $u;
				$output['distance_text'] = $dist . ' ' . $u;
				$output['duration_text'] = $tm;
				if ( $tm != false ) {
					$parts = explode( ':', $tm);
					$str = '';
					if ( isset( $parts[0] ) ) {
						$str .= $parts[0] . ' hours';
					}
					if ( isset( $parts[1] ) ) {
						$str .= ' ' . $parts[1] . ' mins';
					}
					$output['duration_text'] = $str;
				}

				if ( $case == '' ) {
					return $output;
				} elseif ( isset( $output[ $case ] ) ) {
					return $output[ $case ];
				} else {
					return false;
				}
            }
            else {
				return false;
			}
        } else {
			return false;
		}
    }
}

if ( ! function_exists( 'get_google_distance' ) ) :
	/**
	 * Helper function to get the distance from google between two places.
	 *
	 * @params string $from - Origin.
	 * @param string $to - Destination.
	 * @return array
	 * @since 2.0.0
	*/
	function get_google_distance( $from, $to, $units = 'km' ){
		$origin = str_replace( ' ', '+', $from);
		$destination = str_replace( ' ', '+', $to);
		/**
		 * It supports only two metrics.
		 *
		 * @link https://developers.google.com/maps/documentation/javascript/distancematrix
		 * google.maps.UnitSystem.METRIC - Kilometers & Meters
		 * google.maps.UnitSystem.IMPERIAL - Miles & Feet
		 *
		 * https://developers.google.com/maps/documentation/directions/intro
		*/
		if ( 'miles' === $units ) {
			$google_units = 'imperial';
		} else {
			$google_units = 'metric';
		}
		
		$google_api = simontaxi_get_option( 'google_api', 'AIzaSyCqRV6HQ_BSw3MMjPen2bT2IwDnZgfjwu4' );
		$url = 'https://maps.googleapis.com/maps/api/directions/json?key='.$google_api.'&origin=' . $origin . '&destination=' . $destination . '&sensor=false&units=' . $google_units;
		// sendRequest
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$body = curl_exec( $ch);
		curl_close( $ch);
		$json = json_decode( $body);
		
		if ( 'OK' === $json->status ) {
			$legs = $json->routes[0]->legs[0];
			// dd( $legs );
			$drivingSteps = $json->routes[0]->legs[0]->steps;
			
			$from_place_id = '';
			$to_place_id = '';
			if ( ! empty( $json->geocoded_waypoints[0] ) ) {
				$from_place_id = $json->geocoded_waypoints[0]->place_id;
			}
			if ( ! empty( $json->geocoded_waypoints[1] ) ) {
				$to_place_id = $json->geocoded_waypoints[1]->place_id;
			}
			
			/*
			echo '<pre>';
			print_r( $json );
			die();
			*/			
			$output = array();
			/**
			 * @since 2.0.8
			 *
			 * Since there is no official information about toll gates from API, we used this approach to find tollgates.
			 *
			 * @https://stackoverflow.com/questions/25411714/google-maps-directions-find-out-if-route-contains-toll?rq=1
			 * @https://stackoverflow.com/questions/19960634/return-if-route-has-tolls-google-maps-api-xml-request/
			 */
			$toll_gates = 0;
			
			if ( ! empty( $drivingSteps ) ) {
				foreach( $drivingSteps as $step ) {
					if (strpos(strtolower($step->html_instructions), 'toll road') !== false) {
						$toll_gates++;
					}
				}				
			}					
			
			/*
			$google_api = simontaxi_get_option( 'google_api', 'AIzaSyCqRV6HQ_BSw3MMjPen2bT2IwDnZgfjwu4' );
			$from_details = "https://maps.googleapis.com/maps/api/place/details/json?placeid=$from_place_id&key=$google_api";
			$from_json = file_get_contents($from_details);
			$from_data = json_decode($from_json);
			if ( ! empty( $from_data->result->geometry->location ) ) {
				$output['pickup_location_lat'] = $from_data->result->geometry->location->lat;
				$output['pickup_location_lng'] = $from_data->result->geometry->location->lng;
			}
			
			$to_details = "https://maps.googleapis.com/maps/api/place/details/json?placeid=$to_place_id&key=$google_api";
			$to_json = file_get_contents($to_details);
			$to_data = json_decode($to_json);
			if ( ! empty( $to_data->result->geometry->location ) ) {
				$output['drop_location_lat'] = $to_data->result->geometry->location->lat;
				$output['drop_location_lng'] = $to_data->result->geometry->location->lng;
			}
			*/
			$start_location = $json->routes[0]->legs[0]->start_location;
			if ( ! empty( $start_location ) ) {
				$output['pickup_location_lat'] = $start_location->lat;
				$output['pickup_location_lng'] = $start_location->lng;
				$output['pickup_location_details'] = simontaxi_get_location_details_latlng( $output['pickup_location_lat'], $output['pickup_location_lng'] );
			}
			$end_location = $json->routes[0]->legs[0]->end_location;
			if ( ! empty( $start_location ) ) {
				$output['drop_location_lat'] = $end_location->lat;
				$output['drop_location_lng'] = $end_location->lng;
				$output['drop_location_details'] = simontaxi_get_location_details_latlng( $output['drop_location_lat'], $output['drop_location_lng'] );
			}
			
									
			/**
			 * The total distance of this route, expressed in meters.
			 *
			 * @link https://developers.google.com/maps/documentation/javascript/distancematrix
			 *
			 * distance: The total distance of this route, expressed in meters (value) and as text. The textual value is formatted according to the unitSystem specified in the request (or in metric, if no preference was supplied).
			*/
			/**
			 * @since 2.0.6
			 */
			 $distance = $legs->distance->text;
			 $distance = filter_var( $distance, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
			
			$output['distance'] = $distance; // Meters

			$u = simontaxi_get_distance_units();
			$output['distance_units'] = $u;
			$output['distance_meters'] = $legs->distance->value;
			$output['distance_text'] = $legs->distance->text;
			
			$output['duration_seconds'] = $legs->duration->value;
			$output['duration_text'] = $legs->duration->text;
			$output['toll_gates'] = $toll_gates;
			$output['from_place_id'] = $from_place_id;
			$output['to_place_id'] = $to_place_id;
			
			$output['start_address'] = $legs->start_address;
			$output['start_location'] = $legs->start_location;
			$output['end_address'] = $legs->end_address;
			$output['end_location'] = $legs->end_location;
			$output['steps'] = $legs->steps;
			$output['pickup_location'] = simontaxi_get_value( $_POST, 'pickup_location', '');
			$output['pickup_location_country'] = simontaxi_get_value( $_POST, 'pickup_location_country', '');
			$output['drop_location'] = simontaxi_get_value( $_POST, 'drop_location', '');
			$output['drop_location_country'] = simontaxi_get_value( $_POST, 'drop_location_country', '');
			$output['post_fileds'] = $_POST;
			
			return apply_filters( 'simontaxi_googledistance_additional', $output);
		} else {
			return array( 'status' => $json->status );
		}
	}
endif;

if ( ! function_exists( 'simontaxi_get_google_distance' ) ) :
	/**
	 * Helper function to get the distance from google between two places.
	 *
	 * @params string $from - Origin.
	 * @param string $to - Destination.
	 * @return array
	 * @since 2.0.0
	*/
	function simontaxi_get_google_distance( $from, $to, $units = 'km' ){
		$origin = str_replace( ' ', '+', $from);
		$destination = str_replace( ' ', '+', $to);
		/**
		 * It supports only two metrics.
		 *
		 * @link https://developers.google.com/maps/documentation/javascript/distancematrix
		 * google.maps.UnitSystem.METRIC - Kilometers & Meters
		 * google.maps.UnitSystem.IMPERIAL - Miles & Feet
		 *
		 * https://developers.google.com/maps/documentation/directions/intro
		*/
		if ( 'miles' === $units ) {
			$google_units = 'imperial';
		} else {
			$google_units = 'metric';
		}
		
		$google_api = simontaxi_get_option( 'google_api', 'AIzaSyCqRV6HQ_BSw3MMjPen2bT2IwDnZgfjwu4' );
		$url = 'https://maps.googleapis.com/maps/api/directions/json?key='.$google_api.'&origin=' . $origin . '&destination=' . $destination . '&sensor=false&units=' . $google_units;
		// sendRequest
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$body = curl_exec( $ch);
		curl_close( $ch);
		$json = json_decode( $body);
		
		if ( 'OK' === $json->status ) {
			$legs = $json->routes[0]->legs[0];
			// dd( $legs );
			$drivingSteps = $json->routes[0]->legs[0]->steps;
			
			$from_place_id = '';
			$to_place_id = '';
			if ( ! empty( $json->geocoded_waypoints[0] ) ) {
				$from_place_id = $json->geocoded_waypoints[0]->place_id;
			}
			if ( ! empty( $json->geocoded_waypoints[1] ) ) {
				$to_place_id = $json->geocoded_waypoints[1]->place_id;
			}
			
			/*
			echo '<pre>';
			print_r( $json );
			die();
			*/			
			$output = array();
			/**
			 * @since 2.0.8
			 *
			 * Since there is no official information about toll gates from API, we used this approach to find tollgates.
			 *
			 * @https://stackoverflow.com/questions/25411714/google-maps-directions-find-out-if-route-contains-toll?rq=1
			 * @https://stackoverflow.com/questions/19960634/return-if-route-has-tolls-google-maps-api-xml-request/
			 */
			$toll_gates = 0;
			
			if ( ! empty( $drivingSteps ) ) {
				foreach( $drivingSteps as $step ) {
					if (strpos(strtolower($step->html_instructions), 'toll road') !== false) {
						$toll_gates++;
					}
				}				
			}					
			
			/*
			$google_api = simontaxi_get_option( 'google_api', 'AIzaSyCqRV6HQ_BSw3MMjPen2bT2IwDnZgfjwu4' );
			$from_details = "https://maps.googleapis.com/maps/api/place/details/json?placeid=$from_place_id&key=$google_api";
			$from_json = file_get_contents($from_details);
			$from_data = json_decode($from_json);
			if ( ! empty( $from_data->result->geometry->location ) ) {
				$output['pickup_location_lat'] = $from_data->result->geometry->location->lat;
				$output['pickup_location_lng'] = $from_data->result->geometry->location->lng;
			}
			
			$to_details = "https://maps.googleapis.com/maps/api/place/details/json?placeid=$to_place_id&key=$google_api";
			$to_json = file_get_contents($to_details);
			$to_data = json_decode($to_json);
			if ( ! empty( $to_data->result->geometry->location ) ) {
				$output['drop_location_lat'] = $to_data->result->geometry->location->lat;
				$output['drop_location_lng'] = $to_data->result->geometry->location->lng;
			}
			*/
			$start_location = $json->routes[0]->legs[0]->start_location;
			if ( ! empty( $start_location ) ) {
				$output['pickup_location_lat'] = $start_location->lat;
				$output['pickup_location_lng'] = $start_location->lng;
				$output['pickup_location_details'] = simontaxi_get_location_details_latlng( $output['pickup_location_lat'], $output['pickup_location_lng'] );
			}
			$end_location = $json->routes[0]->legs[0]->end_location;
			if ( ! empty( $start_location ) ) {
				$output['drop_location_lat'] = $end_location->lat;
				$output['drop_location_lng'] = $end_location->lng;
				$output['drop_location_details'] = simontaxi_get_location_details_latlng( $output['drop_location_lat'], $output['drop_location_lng'] );
			}
			
									
			/**
			 * The total distance of this route, expressed in meters.
			 *
			 * @link https://developers.google.com/maps/documentation/javascript/distancematrix
			 *
			 * distance: The total distance of this route, expressed in meters (value) and as text. The textual value is formatted according to the unitSystem specified in the request (or in metric, if no preference was supplied).
			*/
			/**
			 * @since 2.0.6
			 */
			 $distance = $legs->distance->text;
			 $distance = filter_var( $distance, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
			
			$output['distance'] = $distance; // Meters

			$u = simontaxi_get_distance_units();
			$output['distance_units'] = $u;
			$output['distance_meters'] = $legs->distance->value;
			$output['distance_text'] = $legs->distance->text;
			
			$output['duration_seconds'] = $legs->duration->value;
			$output['duration_text'] = $legs->duration->text;
			$output['toll_gates'] = $toll_gates;
			$output['from_place_id'] = $from_place_id;
			$output['to_place_id'] = $to_place_id;
			
			$output['start_address'] = $legs->start_address;
			$output['start_location'] = $legs->start_location;
			$output['end_address'] = $legs->end_address;
			$output['end_location'] = $legs->end_location;
			$output['steps'] = $legs->steps;
			$output['pickup_location'] = simontaxi_get_value( $_POST, 'pickup_location', '');
			$output['pickup_location_country'] = simontaxi_get_value( $_POST, 'pickup_location_country', '');
			$output['drop_location'] = simontaxi_get_value( $_POST, 'drop_location', '');
			$output['drop_location_country'] = simontaxi_get_value( $_POST, 'drop_location_country', '');
			$output['post_fileds'] = $_POST;
			
			return $output;
		} else {
			return array( 'status' => $json->status );
		}
	}
endif;

if ( ! function_exists( 'simontaxi_get_location_details' ) ) {
	/**
	 * Return distance units set in admin
	 *
	 * @since 2.0.9
	 * @return string
	 */
	function simontaxi_get_location_details( $placeid ) {
		$google_api = simontaxi_get_option( 'google_api', 'AIzaSyCqRV6HQ_BSw3MMjPen2bT2IwDnZgfjwu4' );
		$to_details = "https://maps.googleapis.com/maps/api/place/details/json?placeid=$placeid&key=$google_api";
		$to_json = file_get_contents($to_details);
		$json = json_decode($to_json);

		if ( 'OK' === $json->status ) {
			$address_components = $json->result->address_components;
			$output = array(
				'result' => $json->result,
			);
			if ( ! empty( $address_components ) ) {
				foreach( $address_components as $component ) {
					if ( in_array( 'postal_code', $component->types ) ) {
						$output['postal_code'] = $component->long_name;
					}
					if ( in_array( 'country', $component->types ) ) {
						$output['country'] = $component->long_name;
					}
				}
			}
			
			$geometry = $json->result->geometry;
			if ( ! empty( $geometry->location ) ) {
				$location = $geometry->location;
				$output['lat'] = $location->lat;
				$output['lng'] = $location->lng;
			}
			//$lat = $latLng->lat;
			//$lng = $latLng->lng;
			return $output;
		} else {
			return false;
		}
		
	}
}

if ( ! function_exists( 'simontaxi_get_location_details_latlng' ) ) {
	/**
	 * Return distance units set in admin
	 *
	 * @since 2.0.9
	 * @return string
	 */
	function simontaxi_get_location_details_latlng( $lat, $lng ) {
		$geocode=file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=false");
		$json = json_decode($geocode);
		if ( 'OK' === $json->status ) {
			$results = $json->results[0];
			$address_components = ! empty( $results->address_components ) ? $results->address_components : array();
			$address = array();
			if ( ! empty( $address_components ) ) {
				$address = array( 
					'components' => $address_components,
				);
				foreach( $address_components as $component ) {
					if ( in_array( 'country', $component->types ) ) {
						$address['country'] = $component->long_name;
					}
					if ( in_array( 'street_address', $component->types ) ) {
						$address['street_address'] = $component->long_name;
					}
					
					if ( in_array( 'sublocality_level_5', $component->types ) ) {
						$address['city'] = $component->long_name;
					}elseif ( in_array( 'sublocality_level_4', $component->types ) ) {
						$address['city'] = $component->long_name;
					} elseif ( in_array( 'sublocality_level_3', $component->types ) ) {
						$address['city'] = $component->long_name;
					} elseif ( in_array( 'sublocality_level_2', $component->types ) ) {
						$address['city'] = $component->long_name;
					} elseif ( in_array( 'sublocality_level_1', $component->types ) ) {
						$address['city'] = $component->long_name;
					} elseif ( in_array( 'administrative_area_level_1', $component->types ) ) {
						$address['city'] = $component->long_name;
					} elseif ( in_array( 'administrative_area_level_2', $component->types ) ) {
						$address['city'] = $component->long_name;
					} elseif ( in_array( 'administrative_area_level_3', $component->types ) ) {
						$address['city'] = $component->long_name;
					} elseif ( in_array( 'administrative_area_level_4', $component->types ) ) {
						$address['city'] = $component->long_name;
					} elseif ( in_array( 'administrative_area_level_5', $component->types ) ) {
						$address['city'] = $component->long_name;
					}
					
					if ( in_array( 'postal_code', $component->types ) ) {
						$address['postal_code'] = $component->long_name;
					}
					if ( in_array( 'administrative_area_level_1', $component->types ) ) {
						$address['state'] = $component->long_name;
					}
				}
			}
			return $address;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'simontaxi_get_distance_units' ) ) {
	/**
	 * Return distance units set in admin
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_distance_units() {
		return simontaxi_get_option( 'vehicle_distance', 'km' );
	}
}

/**
 * Returns the p2p tab title. It is useful if user want to change tab names
 *
 * @since 1.0
 * @return string
 */
 if ( ! function_exists( 'simontaxi_get_p2ptab_title' ) ) {
	function simontaxi_get_p2ptab_title() {
		return apply_filters( 'simontaxi_filter_p2ptab_title', esc_html__( simontaxi_get_option( 'p2p_tab_title', esc_html__( 'Point To Point', 'simontaxi' ) ), 'simontaxi' ) );
	}
}

if ( ! function_exists( 'simontaxi_get_airporttab_title' ) ) {
	/**
	 * Returns the airport transfer tab title. It is useful if user want to change tab names
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_airporttab_title() {
		return apply_filters( 'simontaxi_filter_airporttab_title', esc_html__( simontaxi_get_option( 'airport_tab_title', esc_html__( 'Airport Transfer', 'simontaxi' ) ), 'simontaxi' ) );
	}
}

if ( ! function_exists( 'simontaxi_get_hourlytab_title' ) ) {
	/**
	 * Returns the hourly rental tab title. It is useful if user want to change tab names
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_hourlytab_title() {
		return apply_filters( 'simontaxi_filter_hourlytab_title', esc_html__( simontaxi_get_option( 'hourly_tab_title', esc_html__('Hourly Rental', 'simontaxi' ) ), 'simontaxi' ) );
	}
}

if ( ! function_exists( 'simontaxi_get_step1_title' ) ) {
	/**
	 * Returns the Booking Step 1 Title. It is useful if user want to change tab names
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_step1_title() {
		return apply_filters( 'simontaxi_filter_step1_title', esc_html__( simontaxi_get_option( 'booking_step1_title', esc_html__( 'Location', 'simontaxi' ) ), 'simontaxi' ) );
	}
}

if (! function_exists( 'simontaxi_get_step2_title' ) ) {
	/**
	 * Returns the Booking Step 2 Title. It is useful if user want to change tab names
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_step2_title() {
		return apply_filters( 'simontaxi_filter_step2_title', esc_html__( simontaxi_get_option( 'booking_step2_title', esc_html__('Select Cab', 'simontaxi' ) ), 'simontaxi' ) );
	}
}

if ( ! function_exists( 'simontaxi_get_step3_title' ) ) {
	/**
	 * Returns the Booking Step 3 Title. It is useful if user want to change tab names
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_step3_title() {
		return apply_filters( 'simontaxi_filter_step3_title', esc_html__( simontaxi_get_option( 'booking_step3_title', esc_html__( 'Confirm Booking', 'simontaxi' ) ), 'simontaxi' ) );
	}
}

if ( ! function_exists( 'simontaxi_get_step4_title' ) ) {
	/**
	 * Returns the Booking Step 4 Title. It is useful if user want to change tab names
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_step4_title() {
		return apply_filters( 'simontaxi_filter_step4_title', esc_html__( simontaxi_get_option( 'booking_step4_title', esc_html__( 'Payment', 'simontaxi' ) ), 'simontaxi' ) );
	}
}

if (! function_exists( 'simontaxi_get_help' ) ) {
	/**
	 * Returns the help text as tooltip
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_help( $help = 'This is help text', $icon = 'dashicons dashicons-editor-help', $class = '' ) {
		$text = sprintf( '&nbsp;<span class="st_tooltip '.$class.'" title="%s" data-toggle="tooltip"><span class="' . $icon . '"></span></span>', esc_html__( $help ) );
		return apply_filters( 'simontaxi_filter_get_help', $text);
	}
}

if (! function_exists( 'simontaxi_get_help_nospace' ) ) {
	/**
	 * Returns the help text as tooltip
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_help_nospace( $help = 'This is help text', $icon = 'dashicons dashicons-editor-help', $class = '' ) {
		$text = sprintf( '<span class="st_tooltip '.$class.'" title="%s" data-toggle="tooltip"><span class="' . $icon . '"></span></span>', esc_html__( $help ) );
		return apply_filters( 'simontaxi_filter_get_help', $text);
	}
}

if (! function_exists( 'simontaxi_get_oneway_title' ) ) {
	/**
	 * Returns 'One way' title to display in front end. It is useful if user wants to change this to other title
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_oneway_title() {
		return apply_filters( 'simontaxi_filter_oneway_title', esc_html__( 'One way', 'simontaxi' ) );
	}
}

if ( ! function_exists( 'simontaxi_get_twoway_title' ) ) {
	/**
	 * Returns 'Two way' title to display in front end. It is useful if user wants to change this to other title
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_twoway_title() {
		return apply_filters( 'simontaxi_filter_twoway_title', esc_html__( 'Two way', 'simontaxi' ) );
	}
}


 if ( ! function_exists( 'simontaxi_get_pickuppoint_title' ) ) {
	/**
	 * Returns 'Pick up point' title to display in front end. It is useful if user wants to change this to other title
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_pickuppoint_title() {
		return apply_filters( 'simontaxi_filter_pickuppoint_title', esc_html__( 'Pickup point', 'simontaxi' ) );
	}
}

if ( ! function_exists( 'simontaxi_get_dropoffpoint_title' ) ) {
	/**
	 * Returns 'Drop off point' title to display in front end. It is useful if user wants to change this to other title
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_dropoffpoint_title() {
		return apply_filters( 'simontaxi_filter_dropoffpoint_title', esc_html__( 'Drop-off point', 'simontaxi' ) );
	}
}


 if ( ! function_exists( 'simontaxi_get_pickupdate_title' ) ) {
	/**
	 * Returns 'Pick Up Date' title to display in front end. It is useful if user wants to change this to other title
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_pickupdate_title() {
		return apply_filters( 'simontaxi_filter_pickupdate_title', esc_html__( 'Pickup Date', 'simontaxi' ) );
	}
}

if ( ! function_exists( 'simontaxi_get_pickuptime_title' ) ) {
	/**
	 * Returns 'Pick Up Time' title to display in front end. It is useful if user wants to change this to other title
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_pickuptime_title() {
		return apply_filters( 'simontaxi_filter_pickuptime_title', esc_html__( 'Pickup Time', 'simontaxi' ) );
	}
}

if ( ! function_exists( 'simontaxi_terms_page' ) ) {
	/**
	 * Returns the terms & conditions page.
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_terms_page() {
		return simontaxi_get_option( 'terms_page', 'step1' );
	}
}

if ( ! function_exists( 'simontaxi_is_allow_twoway_booking' ) ) {
	/**
	 * Returns whether the two way booking is allowed in admin. Default 'yes'
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_is_allow_twoway_booking() {
		return simontaxi_get_option( 'alloow_twoway_booking', 'yes' );
	}
}

if ( ! function_exists( 'simontaxi_required_field' ) ) {
	/**
	 * Returns the "*" mark
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_required_field() {
		return '<font color="red"> * </font>';
	}
}

if ( ! function_exists( 'simontaxi_get_value' ) ) {
	/**
	 * Returns the value from a given array
	 *
	 * @param array|stdClass $item.
	 * @param string $key.
	 * @param string $default - Optional.
	 * @param string $item_type - Optional Allowed values (post|array|object)
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_value( $item, $key, $default = '', $item_type = '' ) {
		$value = $default;
		if ( ! empty( $item_type ) ) {
			if ( 'post' == $item_type && isset( $_POST[ $key ] ) ) {
				$value = $_POST[ $key ];
			} elseif ( 'array' == $item_type && is_array( $item ) ) {
				if ( isset( $item[ $key ] ) ) {
					$value = $item[ $key ];
				}
			} elseif ( 'object' == $item_type && is_object( $item ) ) {
				if ( isset( $item->$key ) ) {
					$value = $item->$key;
				}
			}
		} else {
			if ( isset( $_POST[ $key ] ) ) {
				$value = $_POST[ $key ];
			} elseif ( is_array( $item ) ) {
				if ( isset( $item[ $key ] ) ) {
					$value = $item[ $key ];
				}
			} else {
				if ( isset( $item->$key ) ) {
					$value = $item->$key;
				}
			}
		}		
		return $value;
	}
}

if ( ! function_exists( 'simontaxi_date_format' ) ) {
	/**
	 * Returns the date format for the bookings applicaiton
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_date_format( $date = '', $with_time = false ) {
		$format = simontaxi_get_option( 'st_date_format', 'd-m-Y' );
		if ( empty( $date ) ) {
			$date = date_i18n('d-m-Y');
			if ( $with_time ) {
				$date = date_i18n('d-m-Y H:i');
			}
		}
		
		/**
		 * Actually strtotime() does not work with format 'd/m/Y'
		 */
		$date = str_replace('/', '-', $date);
		
		$format = date_i18n( $format, strtotime( $date ) );
		if ( $with_time ) {
			$format = simontaxi_get_option( 'st_date_format_with_time', 'd-m-Y H:i' );
			$format = date_i18n( $format, strtotime( $date ) );
		}		
		return $format;
	}
}

if ( ! function_exists( 'simontaxi_is_allow_additional_pickups' ) ) {
	/**
	 * Returns whether the additional pickup points allowed in admin. Default 'no'
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_is_allow_additional_pickups() {
		return simontaxi_get_option( 'allow_additional_pickups', 'no' );
	}
}

 if ( ! function_exists( 'simontaxi_get_max_additional_pickups' ) ) {
	/**
	 * Returns Max. Additional Pickup Points
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_max_additional_pickups() {
		return simontaxi_get_option( 'max_additional_pickups', 5);
	}
}

if ( ! function_exists( 'simontaxi_is_allow_additional_dropoff' ) ) {
	/**
	 * Returns whether the additional dropoff points allowed in admin. Default 'no'
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_is_allow_additional_dropoff() {
		return simontaxi_get_option( 'allow_additional_dropoff', 'no' );
	}
}


if ( ! function_exists( 'simontaxi_get_max_additional_dropoff' ) ) {
	/**
	 * Returns Max. Additional Drop-off Points
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_max_additional_dropoff() {
		return simontaxi_get_option( 'max_additional_dropoff', 5);
	}
}

if ( ! function_exists( 'simontaxi_get_maximum_notice' ) ) {
	/**
	 * Returns maximum notice days
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_maximum_notice() {
		$maximum_notice = simontaxi_get_option( 'maximum_notice', 3);
		$maximum_notice_type = simontaxi_get_option( 'maximum_notice_type', 'month' );
		/*Convert maximum notice period into days so that we can manage easily*/
		$maximum_notice_days = 30;
		if ( $maximum_notice_type == 'day' )
		$maximum_notice_days = $maximum_notice;
		elseif ( $maximum_notice_type == 'month' )
		$maximum_notice_days = $maximum_notice * 30;
		elseif ( $maximum_notice_type == 'year' )
		$maximum_notice_days = $maximum_notice * 12 * 30;
		return $maximum_notice_days;
	}
}

if ( ! function_exists( 'simontaxi_crypto_rand_secure' ) ){
    /**
	 * Returns the random string which is used in 'simontaxi_get_token'
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_crypto_rand_secure( $min, $max ) {
        $range = $max - $min;
        if ( $range < 1 ) return $min; // not so random...
        $log = ceil(log( $range, 2) );
        $bytes = (int) ( $log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes( $bytes ) ) );
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ( $rnd >= $range );
        return $min + $rnd;
    }
}

if ( ! function_exists( 'simontaxi_get_token' ) ) {
	/**
	 * Returns random string
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_token( $length = 6 ) {
		$token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "0123456789";
        $max = strlen( $codeAlphabet ) - 1;
        for ( $i=0; $i < $length; $i++ ) {
            $token .= $codeAlphabet[ simontaxi_crypto_rand_secure( 0, $max ) ];
        }
		/**
		 * @since 2.0.8
		 */
		$prefix = simontaxi_get_option( 'booking_ref_prefix');
		$postfix = simontaxi_get_option( 'booking_ref_postfix');
		if ( ! empty( $prefix ) ) {
			$token = $prefix . $token;
		}
		if ( ! empty( $postfix ) ) {
			$token = $token . $postfix;
		}
        return $token;
	}
}

 if ( ! function_exists( 'simontaxi_neat_print_die' ) ) {
	 /**
	 * Print the neatly formated output
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_neat_print_die( $item = '', $die = true ) {
		echo '<pre>';
		if ( '' === $item ) {
			print_r( $_POST );
		} else {
			print_r ( $item );
		}
		if ( $die ) {
			die( '<br>-----------------------DIE----------------' );
		}
	}
 }


 if ( ! function_exists( 'simontaxi_get_vehicles' ) ) {
	 /**
	 * Return the all available vehicles based on other settings.
	 *
	 * @since 1.0
	 * @param array $args - arguments.
	 * @return string
	 */
	 function simontaxi_get_vehicles( $args = array() ) {
		global $wpdb;
		$pickup_date = ! empty( $args['pickup_date'] ) ? $args['pickup_date'] : date( 'Y-m-d' );
		$date = simontaxi_get_session( 'booking_step1', $pickup_date, 'pickup_date' );
		
		$pickup_date_return = ! empty( $args['pickup_date_return'] ) ? $args['pickup_date_return'] : date( 'Y-m-d' );
		$pickup_date_return = simontaxi_get_session( 'booking_step1', $pickup_date_return, 'pickup_date_return' );

		/*
		We are finding vehicles which are blocked for the selected date
		*/
		$vehicles_array = $blockout_vehicles = array();
		// $blockout_dates_objects = get_terms( 'blockout_date', array( 'hide_empty' => false ) );
		/**
		 * @since 2.0.8
		 *
		 * We need to write our custom query to get rid of WPML strings!!
		 */
		$blockout_dates_objects = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "terms t INNER JOIN " . $wpdb->prefix . "term_taxonomy tt ON t.term_id = tt.term_id WHERE tt.taxonomy = 'blockout_date'" );
		
		if ( ! empty ( $blockout_dates_objects ) ) {
			foreach ( $blockout_dates_objects as $blockout_date ) {
				$term_meta['block_date'] = get_term_meta( $blockout_date->term_id, 'block_date', true );
				$term_meta['block_date_end'] = get_term_meta( $blockout_date->term_id, 'block_date_end', true );
				$term_meta['vehicles'] = get_term_meta( $blockout_date->term_id, 'vehicles', true );

				$block_date = ( isset( $term_meta['block_date'] ) && $term_meta['block_date'] != '' ) ? date( 'Y-m-d', strtotime( $term_meta['block_date'] ) ) : '';
				$block_date_end = ( isset( $term_meta['block_date_end'] ) && $term_meta['block_date_end'] != '' ) ? date( 'Y-m-d', strtotime( $term_meta['block_date_end'] ) ) : '';

				/*
				If the selected date is between blocked date! then it is blockeddate!!
				*/
				if ( $date >= $block_date && $date <= $block_date_end ) {
					$blocked_cabs = (array)json_decode( $term_meta['vehicles'] );
					if ( ! empty( $blocked_cabs ) ) {
						foreach ( $blocked_cabs as $key => $val ) {
								$blockout_vehicles[] = $val;
						}
					}
				}
				/*
				We are checking vehicles availability on return date also, if user trying to book for return journey
				*/
				$journey_type = simontaxi_get_session( 'booking_step1', 'one_way', 'journey_type' );
				if ( in_array( $journey_type, apply_filters( 'simontaxi_twoway_other_tabs_availability_return', array( 'two_way' ) ) ) ) {
					if ( $pickup_date_return >= $block_date && $pickup_date_return <= $block_date_end ) {
					$blocked_cabs = (array)json_decode( $term_meta['vehicles'] );
						if ( !empty( $blocked_cabs) ) {
							foreach ( $blocked_cabs as $key => $val ) {
									$blockout_vehicles[] = $val;
							}
						}
					}
				}
			}
		}
		
		/**
		 * Let us restrict number of vehicles
		 *
		 * @since 2.0.0
		*/
		if ( 'yes' === simontaxi_get_option( 'restrict_vehicles_count', 'no' ) ) {
			$bookings = $wpdb->prefix. 'st_bookings';
			$payments = $wpdb->prefix. 'st_payments';
			$confirmed_vehicle_status = simontaxi_get_option( 'confirmed_vehicle_status', 'confirmed' );
			$sql = "SELECT *, `" . $bookings . "`.`ID` AS booking_id, `" . $bookings . "`.`reference` AS booking_ref FROM `" . $bookings . "` INNER JOIN `" . $payments . "` ON `" . $payments . "`.`booking_id`=`" . $bookings . "`.`ID` WHERE `" . $bookings . "`.booking_contacts!='' AND `" . $bookings . "`.status='" . $confirmed_vehicle_status . "' AND `" . $bookings . "`.`pickup_date` = '" . $date . "' GROUP BY selected_vehicle";
			
			$result = $wpdb->get_results( $sql, 'ARRAY_A' );
			
			if ( ! empty( $result ) ) {
				foreach ( $result as $row ) {
					$sql = "SELECT COUNT(*) FROM `" . $bookings."` INNER JOIN `" . $payments."` ON `" . $payments . "`.`booking_id`=`" . $bookings."`.`ID` WHERE `" . $bookings . "`.booking_contacts!='' AND `" . $bookings . "`.status='" . $confirmed_vehicle_status . "' AND `" . $bookings . "`.`selected_vehicle` = '" . $row['selected_vehicle'] . "' AND `".$bookings."`.pickup_date = '" . $date . "'";
					
					$bookings_for_the_vehicle = $wpdb->get_var( $sql );

					$number_of_vehicles_available = get_post_meta( $row['selected_vehicle'], 'number_of_vehicles', true );
					
					/**
					 * If the time restriction applied it will check below for the condition
					 *
					 * @since 2.0.9
					 */
					$apply_time_restriction = get_post_meta( $vehicle->ID, 'apply_time_restriction', true );
					if ( empty( $apply_time_restriction ) ) {
						$apply_time_restriction = 'no';
					}
					
					if ( 'no' === $apply_time_restriction && $number_of_vehicles_available <= $bookings_for_the_vehicle  ) {
						$blockout_vehicles[] = $row['selected_vehicle'];
					}
				}
			}
			
			/*
			We are checking vehicles availability on return date also, if user trying to book for return journey
			*/
			$journey_type = simontaxi_get_session( 'booking_step1', 'one_way', 'journey_type' );
			if ( in_array( $journey_type, apply_filters( 'simontaxi_twoway_other_tabs_vehicles', array( 'two_way' ) ) ) ) {
				$sql = "SELECT *, `" . $bookings . "`.`ID` AS booking_id, `" . $bookings . "`.`reference` AS booking_ref FROM `" . $bookings . "` INNER JOIN `" . $payments . "` ON `" . $payments . "`.`booking_id`=`" . $bookings . "`.`ID` WHERE `" . $bookings . "`.booking_contacts!='' AND `" . $bookings . "`.status='" . $confirmed_vehicle_status . "' AND `" . $bookings . "`.pickup_date = '" . $pickup_date_return . "' GROUP BY selected_vehicle";
				$result = $wpdb->get_results( $sql, 'ARRAY_A' );
				if ( ! empty( $result ) ) {
					foreach ( $result as $row ) {
						$sql = "SELECT COUNT(*) FROM `" . $bookings."` INNER JOIN `" . $payments."` ON `" . $payments . "`.`booking_id`=`" . $bookings."`.`ID` WHERE `" . $bookings . "`.booking_contacts!='' AND `" . $bookings . "`.status='" . $confirmed_vehicle_status . "' AND `" . $bookings . "`.`selected_vehicle` = '" . $row['selected_vehicle'] . "' AND `".$bookings."`.pickup_date = '" . $pickup_date_return . "'";
						$bookings_for_the_vehicle = $wpdb->get_var( $sql );

						$number_of_vehicles_available = get_post_meta( $row['selected_vehicle'], 'number_of_vehicles', true );
						if ( $number_of_vehicles_available <= $bookings_for_the_vehicle  ) {
							$blockout_vehicles[] = $row['selected_vehicle'];
						}
					}
				}
			}
		}
		
		/**
		 * Let us block the vehicle based on number of passengers
		 *
		 * 2.0.8
		 */
		$booking_step1 = simontaxi_get_session( 'booking_step1', array() );		
		if ( ! empty( $booking_step1['number_of_persons'] ) ) {
			$sql = "SELECT * FROM `" . $wpdb->prefix . "posts` WHERE post_status='publish' AND post_type='vehicle'";
			$result = $wpdb->get_results( $sql, 'ARRAY_A' );
			if ( ! empty( $result ) ) {
				foreach ( $result as $row ) {					
					$number_of_seats_available = get_post_meta( $row['ID'], 'seating_capacity', true );
					$apply_seats_restriction = get_post_meta( $row['ID'], 'apply_seats_restriction', true );
					if ( empty( $apply_seats_restriction ) ) {
						$apply_seats_restriction = 'no';
					}
					if ( $apply_seats_restriction == 'yes' && ! empty( $number_of_seats_available ) && $number_of_seats_available < $booking_step1['number_of_persons'] ) {
						$blockout_vehicles[] = $row['ID'];
					}
				}
			}
		}
		
		/**
		 * @since 2.0.8
		 */
		$sql = "SELECT * FROM `" . $wpdb->prefix . "posts` WHERE post_status='publish' AND post_type='vehicle'";
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		if ( ! empty( $result ) ) {
			$booking_types = simontaxi_booking_types();
			foreach ( $result as $row ) {
				$vehicle_available_for = array_keys( (array) json_decode( get_post_meta( $row['ID'], 'vehicle_available_for', true ) ) );
				if ( ! empty( $vehicle_available_for ) && ! empty( $booking_step1['booking_type'] ) && ! in_array($booking_step1['booking_type'], $vehicle_available_for ) ) {
					$blockout_vehicles[] = $row['ID'];
				}
				
				/**
				 * @since 2.0.9
				 */
				$vehicle_display_status = get_post_meta( $row['ID'], 'vehicle_display_status', true );
				if ( empty( $vehicle_display_status ) ) {
					 $vehicle_display_status = 'display';
				}
				if ( 'nodisplay' === $vehicle_display_status ) {
					$blockout_vehicles[] = $row['ID'];
				}
			}
		}
			
		/**
		 * Let us apply minimum distance to handle booking from base location
		 *
		 * @since 2.0.8
		 */				
		$args_new                = array(
			'post_status'    => 'publish',
			'post_type'      => 'vehicle',
			'exclude'        => $blockout_vehicles,
			'posts_per_page' => -1,
		);
		$all_vehicles                = get_posts( $args_new );
			
		$vehicles = array();
		$pickup_location = simontaxi_get_session( 'booking_step1', '', 'pickup_location' );
		
		foreach ( $all_vehicles as $vehicle ) {
			$drop_location = get_post_meta( $vehicle->ID, 'vehicle_base_location', true );
			if ( empty( $drop_location ) ) {
				$drop_location = simontaxi_get_option( 'garage_address', '' );
			}
			
			if ( ! empty( $pickup_location ) && ! empty( $drop_location ) ) {
				$distance_details = get_google_distance( $pickup_location, $drop_location, simontaxi_get_distance_units() );
				
				if ( empty( $distance_details['status'] ) ) {
					$distance = $distance_details['distance'];
					
					$minimum_distance_to_handle_booking = get_post_meta( $vehicle->ID, 'minimum_distance_to_handle_booking', true );
					if ( empty( $minimum_distance_to_handle_booking ) ) {
						$minimum_distance_to_handle_booking = 0;
					}
					if ( $minimum_distance_to_handle_booking > 0 ) {
						if ( $distance > $minimum_distance_to_handle_booking ) {
							$blockout_vehicles[] = $vehicle->ID;
						}
					}
				}
			}
			
			/**
			 * Added time restriction optional feature for admin. Eg: If some one book particular vehicle on particular time, Let us say vehicle1 is booked for 12/04/2018 at 12pm, Other customer should not be able to book same vehicle at same time to avoid clashes
			 */
			$apply_time_restriction = get_post_meta( $vehicle->ID, 'apply_time_restriction', true );
			if ( empty( $apply_time_restriction ) ) {
				$apply_time_restriction = 'no';
			}
			if ( 'yes' === $apply_time_restriction ) {
				$bookings = $wpdb->prefix. 'st_bookings';
				$payments = $wpdb->prefix. 'st_payments';
				$confirmed_vehicle_status = simontaxi_get_option( 'confirmed_vehicle_status', 'confirmed' );
				$time = simontaxi_get_session( 'booking_step1', date( 'H:i' ), 'pickup_time' );
				
				$bookings_for_the_vehicle = 0;
				/**
				// Deprecated since 2.0.9
				$sql = "SELECT COUNT(*) FROM `" . $bookings."` INNER JOIN `" . $payments."` ON `" . $payments . "`.`booking_id`=`" . $bookings."`.`ID` WHERE `" . $bookings . "`.booking_contacts!='' AND `" . $bookings . "`.status='" . $confirmed_vehicle_status . "' AND `" . $bookings . "`.`selected_vehicle` = '" . $vehicle->ID . "' AND `".$bookings."`.pickup_date = '" . $date . "' AND `".$bookings."`.pickup_time = '" . $time . "'";					
				$bookings_for_the_vehicle = $wpdb->get_var( $sql );
				*/
				
				/**
				 * @since 2.0.9
				 */
				$selected_vehicle = $vehicle->ID;
				$sql = "SELECT * FROM $bookings INNER JOIN $payments ON $payments.booking_id=$bookings.ID WHERE $bookings.booking_contacts != '' AND $bookings.status IN( '$confirmed_vehicle_status', 'onride') AND $bookings.selected_vehicle = '$selected_vehicle' AND $bookings.pickup_date = '$date' ORDER BY $bookings.pickup_date, $bookings.pickup_time DESC LIMIT 1";
				$bookings_for_the_vehicle_on_selected_date = $wpdb->get_results( $sql );
								
				$days = $hours = $minutes = 0;
				$booked_date = date_i18n( 'Y-m-d' );
				$booked_time = date_i18n( 'H:i' );
				$booked_date_time = date_i18n( 'Y-m-d H:i' );
				if ( ! empty( $bookings_for_the_vehicle_on_selected_date ) ) {
					foreach( $bookings_for_the_vehicle_on_selected_date as $booking ) {
						$booked_date = $booking->pickup_date;
						$booked_time = $booking->pickup_time;
						$booked_date_time = $booking->pickup_date . ' ' . $booking->pickup_time;
						$ride_duration = explode( ' ', $booking->duration_text ); // Eg: 1 day 3 hours, 21 hours 8 mins, 2 days 1 hour
						
						if ( ! empty( $ride_duration ) ) {
							$previous_part = '';
							foreach( $ride_duration as $part ) {
								if ( in_array( trim( $part ), array( 'day', 'days' ) ) ) {
									$days = $previous_part;
								}
								if ( in_array( trim( $part ), array( 'hour', 'hours' ) ) ) {
									$hours = $previous_part;
								}
								if ( in_array( trim( $part ), array( 'mins', 'min' ) ) ) {
									$minutes = $previous_part;
								}
								$previous_part = $part;
							}
						}
					}
					
					$selected_pickup_date = $date . ' ' . $time;
					$ride_will_complete = date( 'Y-m-d H:i', simontaxi_strtotime( "$booked_date_time +$days day +$hours hour +$minutes minutes" ) );
					/**
					 * Let us add transition time
					 */
					$transition_time = get_post_meta( $selected_vehicle, 'transition_time', true );
					if ( empty( $transition_time ) ) {
						$transition_time = '5'; // Minimum Transition Time
					}
					$transition_time_type = get_post_meta( $selected_vehicle, 'transition_time_type', true );
					if ( empty( $transition_time_type ) ) {
						$transition_time_type = 'minutes'; // Minimum Transition Time
					}
					$ride_will_complete = date( 'Y-m-d H:i', simontaxi_strtotime( "$ride_will_complete +$transition_time $transition_time_type" ) );
					
					if( simontaxi_is_between_dates( $booked_time, $ride_will_complete, $selected_pickup_date ) ) {
						$blockout_vehicles[] = $vehicle->ID;
					}
					if ( $bookings_for_the_vehicle > 0  ) {
						$blockout_vehicles[] = $vehicle->ID;
					}
				}
			}
		}

		/**
		 * Let us allow 3rd party plugins to change this if need.
		 *
		 * @since 2.0.9
		 */
		$blockout_vehicles = apply_filters( 'simontaxi_blockout_vehicles', $blockout_vehicles );
		
		/**
		 * Now we are getting vehicles which are availabile for display means which are not blocked
		*/
		if ( ! isset( $args['orderby'] ) ) {
			$args['orderby'] = 'name';
		}
		if ( ! isset( $args['order'] ) ) {
			$args['order'] = 'ASC';
		}
		if ( isset( $args['pagination'] ) && $args['pagination'] == true ) {
			$args_new = array(
				'post_status' => 'publish',
				'orderby' => 'name',
				'order' => 'ASC',
				'post_type' => 'vehicle',
				'exclude' => $blockout_vehicles,
				'posts_per_page' => -1,
			);
			if ( ! empty( $args['vehicle_id'] ) ) {
				$args_new['vehicle_id'] = $args['vehicle_id'];
			}

			$all_vehicles = get_posts( $args_new );
			$vehicles_array['total'] = count( $all_vehicles);

			$args_new = array(
				'post_status' => 'publish',
				'orderby' => $args['orderby'],
				'order' => $args['order'],
				'post_type' => 'vehicle',
				'exclude' => $blockout_vehicles,
				'posts_per_page' => $args['perpage'],
				'offset' => $args['offset'],
			);
			if ( ! empty( $args['vehicle_id'] ) ) {
				$args_new['include'] = array( $args['vehicle_id'] );
			}
			$vehicles = get_posts( $args_new );
		} else {
			$args_new = array(
				'post_status' => 'publish',
				'orderby' => $args['orderby'],
				'order' => $args['order'],
				'post_type' => 'vehicle',
				'exclude' => $blockout_vehicles,
				'posts_per_page' => -1,
			);
			if ( ! empty( $args['vehicle_id'] ) ) {
				$args_new['include'] = array( $args['vehicle_id'] );
			}
			$vehicles = get_posts( $args_new );
		}
		
		foreach ( $vehicles as $vehicle ) {
			$post_id = $vehicle->ID;
			$vehicle->post_meta = simontaxi_filter_gk( get_post_meta( $post_id) );
			$vehicle->thumbnail = get_the_post_thumbnail( $post_id);
			$vehicle->types = wp_get_post_terms( $post_id, 'vehicle_types' );
			$vehicle->features = wp_get_post_terms( $post_id, 'vehicle_features' );
			
			/**
			 * @since 2.0.9
			 */
			$bookings = $wpdb->prefix. 'st_bookings';
			$payments = $wpdb->prefix. 'st_payments';
			$confirmed_vehicle_status = simontaxi_get_option( 'confirmed_vehicle_status', 'confirmed' );
			$sql = "SELECT COUNT(*) FROM `" . $bookings."` INNER JOIN `" . $payments."` ON `" . $payments . "`.`booking_id`=`" . $bookings."`.`ID` WHERE `" . $bookings . "`.booking_contacts!='' AND `" . $bookings . "`.status='" . $confirmed_vehicle_status . "' AND `" . $bookings . "`.`selected_vehicle` = '" . $post_id . "' AND `".$bookings."`.pickup_date = '" . $date . "'";
			$bookings_for_the_vehicle = $wpdb->get_var( $sql );
			$vehicle->bookings_for_the_vehicle = $bookings_for_the_vehicle;
			
			/**
			 * Since @2.0.8
			 *
			 * Let us display how far the vehicle from the pickup point
			 */
			$base_location = get_post_meta( $vehicle->ID, 'vehicle_base_location', true );
			if ( empty( $base_location ) ) {
				$base_location = simontaxi_get_option( 'garage_address', '' );
			}
			if ( ! empty( $pickup_location ) && ! empty( $base_location ) ) {
				$distance_details = get_google_distance( $pickup_location, $base_location, simontaxi_get_distance_units() );
				if ( empty( $distance_details['status'] ) ) {
					$distance = $distance_details['distance'];
					$vehicle->distance_away = $distance;
				}
			} else {
				$vehicle->distance_away = 0;
			}
			$vehicles_array['vehicles'][] = $vehicle;
		}
		
		/**
		 * Let us allow 3rd party plugins to change this if need.
		 *
		 * @since 2.0.9
		 */
		$vehicles_array = apply_filters( 'simontaxi_vehicles_array', $vehicles_array );
		
		return $vehicles_array;
	 }
 }
 
 if ( ! function_exists( 'simontaxi_get_vehicles_order' ) ) {
	 /**
	 * Return the all available vehicles based on other settings.
	 *
	 * @since 1.0
	 * @param array $args - arguments.
	 * @return string
	 */
	 function simontaxi_get_vehicles_order( $args = array() ) {
		global $wpdb;
		$date = simontaxi_get_session( 'booking_step1', date( 'Y-m-d' ), 'pickup_date' );
		$pickup_date_return = simontaxi_get_session( 'booking_step1', date( 'Y-m-d' ), 'pickup_date_return' );

		/*
		We are finding vehicles which are blocked for the selected date
		*/
		$vehicles_array = $blockout_vehicles = array();
		$blockout_dates_objects = get_terms( 'blockout_date', array( 'hide_empty' => false ) );
		if ( ! empty ( $blockout_dates_objects ) ) {
			foreach ( $blockout_dates_objects as $blockout_date ) {
				$term_meta['block_date'] = get_term_meta( $blockout_date->term_id, 'block_date', true );
				$term_meta['block_date_end'] = get_term_meta( $blockout_date->term_id, 'block_date_end', true );
				$term_meta['vehicles'] = get_term_meta( $blockout_date->term_id, 'vehicles', true );

				$block_date = ( isset( $term_meta['block_date'] ) && $term_meta['block_date'] != '' ) ? date( 'Y-m-d', strtotime( $term_meta['block_date'] ) ) : '';
				$block_date_end = ( isset( $term_meta['block_date_end'] ) && $term_meta['block_date_end'] != '' ) ? date( 'Y-m-d', strtotime( $term_meta['block_date_end'] ) ) : '';

				/*
				If the selected date is between blocked date! then it is blockeddate!!
				*/
				if ( $date >= $block_date && $date <= $block_date_end ) {
					$blocked_cabs = (array)json_decode( $term_meta['vehicles'] );
					if ( ! empty( $blocked_cabs ) ) {
						foreach ( $blocked_cabs as $key => $val ) {
								$blockout_vehicles[] = $val;
						}
					}
				}
				/*
				We are checking vehicles availability on return date also, if user trying to book for return journey
				*/
				$journey_type = simontaxi_get_session( 'booking_step1', 'one_way', 'journey_type' );
				if ( in_array( $journey_type, apply_filters( 'simontaxi_twoway_other_tabs_availability_tabs', array( 'two_way' ) ) ) ) {
					if ( $pickup_date_return >= $block_date && $pickup_date_return <= $block_date_end ) {
					$blocked_cabs = (array)json_decode( $term_meta['vehicles'] );
						if ( !empty( $blocked_cabs) ) {
							foreach ( $blocked_cabs as $key => $val ) {
									$blockout_vehicles[] = $val;
							}
						}
					}
				}
			}
		}

		/**
		 * Let us restrict number of vehicles
		 *
		 * @since 2.0.0
		*/
		if ( 'yes' === simontaxi_get_option( 'restrict_vehicles_count', 'no' ) ) {
			$bookings = $wpdb->prefix. 'st_bookings';
			$payments = $wpdb->prefix. 'st_payments';
			$confirmed_vehicle_status = simontaxi_get_option( 'confirmed_vehicle_status', 'confirmed' );
			$sql = "SELECT *, `" . $bookings."`.`ID` AS booking_id, `" . $bookings."`.`reference` AS booking_ref FROM `" . $bookings."` INNER JOIN `" . $payments."` ON `" . $payments."`.`booking_id`=`" . $bookings."`.`ID` WHERE `" . $bookings."`.booking_contacts!='' AND `" . $bookings."`.status='" . $confirmed_vehicle_status."' GROUP BY selected_vehicle";
			$result = $wpdb->get_results( $sql, 'ARRAY_A' );
			if ( ! empty( $result ) ) {
				foreach ( $result as $row ) {
					$sql = "SELECT COUNT(*) FROM `" . $bookings."` INNER JOIN `" . $payments."` ON `" . $payments."`.`booking_id`=`" . $bookings."`.`ID` WHERE `" . $bookings."`.booking_contacts!='' AND `" . $bookings."`.status='" . $confirmed_vehicle_status."' AND `" . $bookings."`.`selected_vehicle` = '" . $row['selected_vehicle']."'";
					$bookings_for_the_vehicle = $wpdb->get_var( $sql );

					$number_of_vehicles_available = get_post_meta( $row['selected_vehicle'], 'number_of_vehicles', true );
					if ( $number_of_vehicles_available <= $bookings_for_the_vehicle  ) {
						$blockout_vehicles[] = $row['selected_vehicle'];
					}
				}
			}
		}

		/**
		 * Now we are getting vehicles which are availabile for display means which are not blocked
		*/
		if ( isset( $args['pagination'] ) && $args['pagination'] == true ) {
			$args_new = array(
				'post_status' => 'publish',
				'orderby' => 'name',
				'order' => 'ASC',
				'post_type' => 'vehicle',
				'exclude' => $blockout_vehicles,
				'posts_per_page' => -1,
			);

			$all_vehicles = get_posts( $args_new );
			$vehicles_array['total'] = count( $all_vehicles);

			$args_new = array(
				'post_status' => 'publish',
				'orderby' => 'name',
				'order' => 'ASC',
				'post_type' => 'vehicle',
				'exclude' => $blockout_vehicles,
				'posts_per_page' => $args['perpage'],
				'offset' => $args['offset'],
			);

			$vehicles = get_posts( $args_new );

		} else {
			$args_new = array(
				'post_status' => 'publish',
				'orderby' => 'name',
				'order' => 'ASC',
				'post_type' => 'vehicle',
				'exclude' => $blockout_vehicles,
				'posts_per_page' => -1,
			);

			$vehicles = get_posts( $args_new );
		}
		foreach ( $vehicles as $vehicle ) {
			$post_id = $vehicle->ID;
			$vehicle->post_meta = simontaxi_filter_gk( get_post_meta( $post_id) );
			$vehicle->thumbnail = get_the_post_thumbnail( $post_id);
			$vehicle->types = wp_get_post_terms( $post_id, 'vehicle_types' );
			$vehicle->features = wp_get_post_terms( $post_id, 'vehicle_features' );
			$vehicles_array['vehicles'][] = $vehicle;
		}
		return $vehicles_array;
	 }
 }


if ( ! function_exists( 'simontaxi_array_push_assoc' ) ) {
	/*
	 * instead of making several dozen calls to the get_post_meta function to grab the keys I wanted. So here is my logic
	 *
	 * @since 1.0
	 * @returns array
	 */
	function simontaxi_array_push_assoc( $array, $key, $value ){
		$array[ $key ] = $value;
		return $array;
	}
}

if ( ! function_exists( 'simontaxi_filter_gk' ) ) {
	/*
	* instead of making several dozen calls to the get_post_meta function to grab the keys I wanted. So here is my logic. It grab all the keys(filter grab keys - filter_gk) I want.
	*
	* @since 1.0
	* @param array - $array - array of keys.
	* @returns array
	*/
	function simontaxi_filter_gk( $array ) {
		$mk = array();
		foreach( $array as $k => $v ){
			if ( is_array( $v ) && count( $v ) == 1 ) {
				$mk = simontaxi_array_push_assoc( $mk, $k, $v[0] );
			} else {
				$mk = simontaxi_array_push_assoc( $mk, $k, $v );
			}
		}
		return $mk;
	}
}

if ( ! function_exists( 'simontaxi_get_bookingsteps_urls' ) ) {
	/**
	* Filter for Booking steps Page URLs. We are creating filter so that later any one can change URLs to their requirements
	*
	* @since 1.0
	* @param string $step - URL to get.
	* @return string
	*/
	function simontaxi_get_bookingsteps_urls( $step = '', $return_all = false ) {
		global $simontaxi_pages;
		
		$cache = get_option( 'simontaxi_pages' );

		$urls = array( 
			'login' => get_permalink( ! empty( $cache['login'] ) ? $cache['login'] : get_page_by_path( 'sign-in' ) ),
			'registration' => get_permalink( ! empty( $cache['registration'] ) ? $cache['registration'] : get_page_by_path( 'registration' ) ),
			'forgotpassword' => get_permalink( ! empty( $cache['forgotpassword'] ) ? $cache['forgotpassword'] : get_page_by_path( 'forgotpassword' ) ),
			'resetpassword' => get_permalink( ! empty( $cache['resetpassword'] ) ? $cache['resetpassword'] : get_page_by_path( 'resetpassword' ) ),			
			'step1' => get_permalink( ! empty( $cache['step1'] ) ? $cache['step1'] : get_page_by_path( 'pick-locations' ) ),
			'step2' => get_permalink( ! empty( $cache['step2'] ) ? $cache['step2'] : get_page_by_path( 'select-vehicle' ) ),
			'step3' => get_permalink( ! empty( $cache['step3'] ) ? $cache['step3'] : get_page_by_path( 'confirm-booking' ) ),
			'step4' => get_permalink( ! empty( $cache['step4'] ) ? $cache['step4'] : get_page_by_path( 'select-payment-method' ) ),
			'proceed_to_pay' => admin_url( 'admin-post.php?action=proceed_to_pay' ),
			'payment_success' => get_permalink( ! empty( $cache['payment_success'] ) ? $cache['payment_success'] : get_page_by_path( 'payment-success' ) ),		
			'payment_final' => get_permalink( ! empty( $cache['payment_final'] ) ? $cache['payment_final'] : get_page_by_path( 'payment-final' ) ),
			'user_bookings' => get_permalink( ! empty( $cache['user_bookings'] ) ? $cache['user_bookings'] : get_page_by_path( 'user-bookings' ) ),
			'user_payments' => get_permalink( ! empty( $cache['user_payments'] ) ? $cache['user_payments'] : get_page_by_path( 'user-payments' ) ),			
			'user_account' => get_permalink( ! empty( $cache['user_account'] ) ? $cache['user_account'] : get_page_by_path( 'user-account' ) ),
			'activate-account' => get_permalink( ! empty( $cache['activate-account'] ) ? $cache['activate-account'] : get_page_by_path( 'activate-account' ) ),
			'billing_address' => get_permalink( ! empty( $cache['billing_address'] ) ? $cache['billing_address'] : get_page_by_path( 'user-billing-address' ) ),
			'user_support' => get_permalink( ! empty( $cache['user_support'] ) ? $cache['user_support'] : get_page_by_path( 'user-support' ) ),
			'start_over' => get_permalink( ! empty( $cache['start_over'] ) ? $cache['start_over'] : get_page_by_path( 'clear-selections' ) ),			
			
			'manage_bookings' => admin_url( 'edit.php?post_type=vehicle&page=manage_bookings' ),
			'manage_extensions' => admin_url( 'edit.php?post_type=vehicle&page=manage_extensions' ),			
			'manage_support' => admin_url( 'edit.php?post_type=vehicle&page=view_support_request' ),
			'settings' => admin_url( 'edit.php?post_type=vehicle&page=vehicle_settings' ),
			
		);
		$urls = apply_filters( 'simontaxi_filter_bookingsteps', $urls );
				
		if ( isset( $urls[ $step ] ) ) {
			return $urls[ $step ];
		} elseif ( $return_all ) {
			return  $urls;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'simontaxi_get_fare' ) ) :
	/**
	 * This function serves to get the fare based on distance and vehicle
	 *
	 * @since 1.0
	 * @param Object $vehicle - which contains all the vehicle information.
	 * @param array $booking_step1 - Booking details.
	 * @return decimal
	 */
	function simontaxi_get_fare( $vehicle = '', $booking_step1 ) {
		/**
		 * Let us calculate the fare based on admin settings so that admin will have flexibility to choose the way he want to calculate the fare!
		 */
		$booking_type = $booking_step1['booking_type'];
		
		$vehicle_id = 0;
		if ( ! empty( $vehicle ) ) {
			if ( is_array( $vehicle ) ) {
				$vehicle = (Object) $vehicle;
			}
			$vehicle_id = $vehicle->ID;
		}
		
		/**
		 * Fare calculation can be set at vehicle level.
		 *
		 * @since 2.0.9
		 */
		$farecalculation_basedon = get_post_meta( $vehicle_id, 'farecalculation_basedon', true );
		if ( empty( $farecalculation_basedon ) ) {
			$farecalculation_basedon = simontaxi_get_option( 'farecalculation_basedon', 'basicfare' );
		}

		/**
		 * The fare what we return. Default it is '0'
		 */
		$fare = $basic_distance = $distance = $basic_distance = $basic_price = $unit_price = 0;

		if ( $farecalculation_basedon == 'basicfare' && $booking_type != 'hourly' ) {
			$distance = $booking_step1['distance'];
			/**
			 * @since 2.0.0
			 */
			$distance = filter_var( $distance, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
			
			if ( $booking_type == 'p2p' ) {
								
				$basic_distance = get_post_meta( $vehicle_id, 'p2p_basic_distance', true );
				if ( empty( $basic_distance ) ) {
					$basic_distance = 0;
				}
				$basic_price = get_post_meta( $vehicle_id, 'p2p_basic_price', true );
				if ( empty( $basic_price ) ) {
					$basic_price = 0;
				}
				$unit_price = get_post_meta( $vehicle_id, 'p2p_unit_price', true );
				if ( empty( $unit_price ) ) {
					$unit_price = 0;
				}
			} elseif ( $booking_type == 'airport' ) {
				if ( $booking_step1['airport'] == 'pickup_location' ) {
										
					$basic_distance = get_post_meta( $vehicle_id, 'from_airport_basic_distance', true );
					if ( empty( $basic_distance ) ) {
						$basic_distance = 0;
					}
					$basic_price = get_post_meta( $vehicle_id, 'from_airport_basic_price', true );
					if ( empty( $basic_price ) ) {
						$basic_price = 0;
					}
					$unit_price = get_post_meta( $vehicle_id, 'from_airport_unit_price', true );
					if ( empty( $unit_price ) ) {
						$unit_price = 0;
					}
				} else {
										
					$basic_distance = get_post_meta( $vehicle_id, 'to_airport_basic_distance', true );
					if ( empty( $basic_distance ) ) {
						$basic_distance = 0;
					}
					$basic_price = get_post_meta( $vehicle_id, 'to_airport_basic_price', true );
					if ( empty( $basic_price ) ) {
						$basic_price = 0;
					}
					$unit_price = get_post_meta( $vehicle_id, 'to_airport_unit_price', true );
					if ( empty( $unit_price ) ) {
						$unit_price = 0;
					}
				}
			}
			
			/**
			 * @since 2.0.8
			 *
			 * Let us allow other plugins to change the details
			 */
			$basic_distance = apply_filters('simontaxi_basic_distance', $basic_distance, $vehicle_id, $booking_step1 );
			$basic_price = apply_filters('simontaxi_basic_price', $basic_price, $vehicle_id, $booking_step1 );
			$unit_price = apply_filters('simontaxi_unit_price', $unit_price, $vehicle_id, $booking_step1 );
			
			
						
			if ( $distance > $basic_distance ) {
				/**
				 * Let us find how much the additional distance user is travelling, so that we can caluclate the fare based on per unit charge
				 */
				$additional_distance = $distance - $basic_distance;

				$fare = $basic_price + ( $additional_distance * $unit_price);
				
			} else {
				$fare = $basic_price;
			}
		} else {
			$distance = 0;
			if ( $booking_type != 'hourly' ) {
				$distance = $booking_step1['distance'];
			}			
			
			$vehicle_id = VARIABLE_PREFIX . $vehicle->ID;
			$result = get_terms( array( 'taxonomy' => 'distance_price', 'hide_empty' => false) );
			if ( $booking_type == 'hourly' ) {
				$result = get_terms( array( 'taxonomy' => 'hourly_packages', 'hide_empty' => false, 'slug' => $booking_step1['hourly_package']  ) );
			}
			$fare_details = '';
			/**
			 * The maximum fare for the particular vehicle. we will return this if given distance is not in the list so that fare should not be '0' at any time!!
			 */
			$maximumfare = 0;
			
			/**
			 * @since 2.0.6
			 *
			 * If user travels more distance than maximum distance specified in the admin distance chart. We need to calculate additional price
			 */
			$maximum_distance_found = 0;
			
			if ( $booking_type == 'p2p' ) {
				$unit_price = get_post_meta( $vehicle_id, 'p2p_unit_price', true );
				if ( empty( $unit_price ) ) {
					$unit_price = 0;
				}				
			} elseif ( $booking_type == 'airport' ) {
				if ( $booking_step1['airport'] == 'pickup_location' ) {					
					$unit_price = get_post_meta( $vehicle_id, 'from_airport_unit_price', true );
					if ( empty( $unit_price ) ) {
						$unit_price = 0;
					}
				} else {					
					$unit_price = get_post_meta( $vehicle_id, 'to_airport_unit_price', true );
					if ( empty( $unit_price ) ) {
						$unit_price = 0;
					}
				}
			}
			/**
			 * @since 2.0.8
			 *
			 * Let us allow other plugins to change the unit distance!
			 */
			if ( empty( $unit_price ) ) {
				$unit_price = 0;
			}
			$unit_price = apply_filters('simontaxi_unit_price', $unit_price, $vehicle_id, $booking_step1 );

			if ( ! empty( $result ) && ! is_wp_error( $result ) ) {
				foreach ( $result as $row ) {
					$row = ( array ) $row;
					$term_id = $row['term_id'];
					if ( $booking_type == 'hourly' ) {
						$t_id = $row['term_id'];
						$fare_details = ( Object ) get_term_meta( $term_id, 'fare', true );
					} else {
						$minimum_distance = get_term_meta( $term_id, 'minimum_distance', true );
						$maximum_distance = get_term_meta( $term_id, 'maximum_distance', true );
						
						/**
						 * If the distance is between minimum and maximum distance we need take that price
						 */
						if ( ( $distance >= $minimum_distance && $distance <= $maximum_distance) ) {
							$fare_details = json_decode( get_term_meta( $term_id, 'fare', true ) );
						} else {
							$fare_details_notfound = json_decode( get_term_meta( $term_id, 'fare', true ) );
							/**
							 * Let us take maximum fare for the vehicle to return it if the given distance is not in the list.
							 */
							if ( ! empty( $fare_details_notfound ) ) {
								foreach ( $fare_details_notfound as $key => $val ) {
									if ( $key == $vehicle_id && $maximumfare < $val) {
										$maximumfare = $val;
										$maximum_distance_found = $maximum_distance;
									}
								}
							}
						}
					}
				}
				if ( is_object( $fare_details) ) {
					if ( ! empty( $fare_details ) ) {
						foreach ( $fare_details as $key => $val ) {
							if ( $key == $vehicle_id ) {
								$fare = $val;
							}
						}
					}
				}
				
				/**
				* Let us make sure the fare should not be the '0' at any time. If it is '0' means not in the list which admin add, then let us return the maximum fare for that vehicle.
				*/
				if ( $fare == 0) {
					if ( $farecalculation_basedon == 'predefined' && $booking_type != 'hourly' ) {
						/**
						 * @since 2.0.6
						 *
						 * If user travels more distance than maximum distance specified in the admin distance chart. We need to calculate additional price
						 */
						if ( $distance > $maximum_distance_found ) {
							/**
							 * Let us find how much the additional distance user is travelled, so that we can caluclate the fare based on per unit charge
							 */
							$additional_distance = $distance - $maximum_distance_found;

							$fare = $maximumfare + ( $additional_distance * $unit_price);
						} else {
							$fare = $maximumfare;
						}
					} else {
						$fare = $maximumfare;
					}
				}
			}
		}
		
		/**
		 * Let us check minimum fare
		 *
		 * @since 2.0.9
		 */
		$minimum_fare = get_post_meta( $vehicle_id, 'minimum_fare', true );
		$minimum_fare_on = get_post_meta( $vehicle_id, 'minimum_fare_on', true );
		if ( ! empty( $minimum_fare ) && ! empty( $minimum_fare_on ) ) {
			if ( $fare < $minimum_fare ) {
				$fare = $minimum_fare;
			}
		}
		
		/**
		 * 2.0.6
		 *
		 * Let us calculate fare based on number of persons if admin set
		 */
		if ( simontaxi_get_option( 'pesons_calculation', 'no' ) === 'yes' ) {
			if ( ! empty( $booking_step1['number_of_persons'] ) ) {
				$number_of_persons = $booking_step1['number_of_persons'];
				if ( ctype_digit( $number_of_persons ) ) {
					$fare = $fare * $number_of_persons;
				}
			}
		}
		
		/**
		 * 2.0.8
		 *
		 * Garage to Garage Fare Calculation
		 * Description: As per vendors in car rentals regular business standards, the cab meter reading and timings for calculation starts from vendor garage and ends back to vendor garage (i.e. The kilometers and/or hours charged will start from and end at vendor rental office/garage.)
		 So meter reading is Not considered from customer pickup location or drop location unless the cab vendor is providing Radio Taxi or special fare.
		 */
		$enable_garage_to_garage = simontaxi_get_option( 'enable_garage_to_garage', 'no' );
		if ( $enable_garage_to_garage == 'yes' && ! empty( $booking_step1['journey_type'] ) && $booking_step1['journey_type'] == 'one_way' ) {
		    $fare = $fare * 2;
		}
		
		/**
		 * If any changes need to do with other plugins lets do here!
		 *
		 * @since 2.0.8
		 */
		$fare = apply_filters( 'simontaxi_final_fare', $fare, $vehicle, $booking_step1 );
		
		/**
		 * Let us format the number according to admin settings
		 */
		return $fare;
	}
endif;

if ( ! function_exists( 'simontaxi_get_tax' ) ) {
	/**
	 * This funciton returns the tax amount which admin sets for the entire applicaiton
	 * @param float $price - Price.
	 */
	function simontaxi_get_tax( $price ) {
		$tax_rate = simontaxi_get_option( 'tax_rate', 0 );
		$tax_amount = 0;
		if ( $tax_rate > 0 ) {
			$tax_rate_type = simontaxi_get_option( 'tax_rate_type', 'percent' );
			if ( $tax_rate_type == 'percent' ) {
				$tax_amount = ( $price * $tax_rate) / 100;
			} else {
				$tax_amount =  $tax_rate;
			}
		}
		return $tax_amount;
	}
}

if ( ! function_exists( 'simontaxi_get_surcharges' ) ) {
  /**
   * This funtion will return the all surcharges applied as per admin settings
   * @param string $key (Optional) - Desired array key.
   */
  function simontaxi_get_surcharges( $key = '', $booking_id = '' ) {
	global $wpdb;
	if ( ! empty( $booking_id ) ) {
		$bookings = $wpdb->prefix . 'st_bookings';
		$sql = "SELECT *, `" . $bookings . "`.`ID` AS booking_id, `" . $bookings . "`.`reference` AS booking_ref FROM `" . $bookings . "` WHERE `" . $bookings . "`.booking_contacts!='' AND `" . $bookings . "`.ID=" . $booking_id;
		
		$booking_details = $wpdb->get_row( $sql );
		if ( ! empty( $booking_details ) ) {
			$session_details = json_decode( $booking_details->session_details );
			if ( is_object( $session_details ) ) {
				$session_details = (array) $session_details;
			}
			$booking_step1 = array();
			if ( ! empty( $session_details[0] ) ) {
				$booking_step1 = (array) $session_details[0];
			}
			$booking_step2 = array();
			if ( ! empty( $session_details[1] ) ) {
				$booking_step2 = (array) $session_details[1];
			}
			$discount_details = '';
		}
	} else {
		$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
		$booking_step2 = simontaxi_get_session( 'booking_step2', array() );
	}
	$basic_amount = ( isset( $booking_step2['selected_amount'] ) ) ? $booking_step2['selected_amount'] : 0;
	
	/**
	 * @since 2.0.8
	 */
	$selected_vehicle = ( isset( $booking_step2['selected_vehicle'] ) ) ? $booking_step2['selected_vehicle'] : 0;
	
	$surcharges = array();
	  /**
	  * Let me calculate surcharges if any
	  * Following surcharges we are considering for now
	  * Peak season, Peak time, Airport, Additional pick up / drop off points, Waiting time
	  */
	  $waitingtime_surcharge = $waitingtime_surcharge_onward = $waitingtime_surcharge_return = 0;
	  if ( isset( $booking_step1['waiting_time'] ) && $booking_step1['waiting_time'] != '' ) {
		  $parts = explode( ':', $booking_step1['waiting_time'] );
		  $hours = $parts[0];
		  if ( isset( $parts[1] ) && $parts[1] > 30 ) {
			  /* if the minutes is greater than 30 minutes we will consider it as 1 hour */
			  $hours = $hours + 1;
		  }
		  $waitingtime_surcharge_onward = simontaxi_get_option( 'waitingtime_surcharge', 0) * $hours;
	  }
	  if ( isset( $booking_step1['waiting_time_return'] ) && $booking_step1['waiting_time_return'] != '' ) {
		  $parts = explode( ':', $booking_step1['waiting_time_return'] );
		  $hours = $parts[0];
		  if ( isset( $parts[1] ) && $parts[1] > 30 ) {
			  /* if the minutes is greater than 30 minutes we will consider it as 1 hour */
			  $hours = $hours + 1;
		  }
		  $waitingtime_surcharge_return = simontaxi_get_option( 'waitingtime_surcharge', 0) * $hours;
	  }
	  $surcharges['waitingtime_surcharge'] = $waitingtime_surcharge_onward + $waitingtime_surcharge_return;
	  $surcharges['waitingtime_surcharge_onward'] = $waitingtime_surcharge_onward;
	  $surcharges['waitingtime_surcharge_return'] = $waitingtime_surcharge_return;

	  $additionalpoints_surcharge = simontaxi_get_option( 'additionalpoints_surcharge', 0);
	  $additionalpoints_surcharge_amount = $additionalpoints_surcharge_amount_onward = $additionalpoints_surcharge_amount_return = 0;
	  /**
	   * Which means admin sets additional points surcharge. Let us calculate.
	   */
	  if ( $additionalpoints_surcharge > 0 ) {
		  $additionalpoints = 0;
		  if ( isset( $booking_step1['additional_pickups'] ) && $booking_step1['additional_pickups'] > 0 ) {
			  $additionalpoints += $booking_step1['additional_pickups'];
		  }
		  if ( isset( $booking_step1['additional_dropoff'] ) && $booking_step1['additional_dropoff'] > 0 ) {
			  $additionalpoints += $booking_step1['additional_dropoff'];
		  }
		  if ( $additionalpoints > 0 ) {
			$additionalpoints_surcharge_amount_onward = $additionalpoints_surcharge * $additionalpoints;
		  }

		  $additionalpoints_return = 0;
		  if ( isset( $booking_step1['additional_pickups_return'] ) && $booking_step1['additional_pickups_return'] > 0 ) {
			  $additionalpoints_return += $booking_step1['additional_pickups_return'];
		  }
		  if ( isset( $booking_step1['additional_dropoff_return'] ) && $booking_step1['additional_dropoff_return'] > 0 ) {
			  $additionalpoints_return += $booking_step1['additional_dropoff_return'];
		  }
		  if ( $additionalpoints_return > 0 ) {
			$additionalpoints_surcharge_amount_return = $additionalpoints_surcharge * $additionalpoints_return;
		  }
	  }
	  $surcharges['additionalpoints_surcharge'] = $additionalpoints_surcharge_amount_onward + $additionalpoints_surcharge_amount_return;
	  $surcharges['additionalpoints_surcharge_onward'] = $additionalpoints_surcharge_amount_onward;
	  $surcharges['additionalpoints_surcharge_return'] = $additionalpoints_surcharge_amount_return;

	  /**
	   * If the 'booking_type' is airport then we need to take the airport surcharges if admin sets
	   */
	  $airport_surcharge = 0;
	  if ( simontaxi_get_option( 'airport_surcharge', 0) > 0 && in_array( $booking_step1['booking_type'], apply_filters( 'simontaxi_airport_other_tabs', array( 'airport' ) ) ) ) {
		  if ( simontaxi_get_option( 'airport_surcharge_type', 'value' ) == 'percent' ) {
			  $airport_surcharge = ( $basic_amount * simontaxi_get_option( 'airport_surcharge', 0) ) / 100;
		  } else {
			$airport_surcharge = simontaxi_get_option( 'airport_surcharge', 0);
		  }
	  }
	  $surcharges['airport_surcharge'] = $airport_surcharge;
	  /**
	   * Let us calculate Peak time surcharges
	   */
	  $peak_time_surcharge_onward = $peak_time_surcharge_return = 0;
	  
	  /**
	   * Peak Season charges handled at vehicle level. Special fare is for selected vehicles only, not for all. People are opting to charge extra fare for selected vehicles only
	   *
	   * @since 2.0.8
	   */
	  $apply_peakseason_rates = get_post_meta( $selected_vehicle, 'apply_peakseason_rates', true );
	  if ( empty( $apply_peakseason_rates ) ) {
		  $apply_peakseason_rates = 'yes';
	  }
	  
	  /**
	   * Description: To handle only weekend peak charges.
	   *
	   * @since 2.0.9
	   */
	  $peak_time_apply_from = simontaxi_get_option( 'peak_time_apply_from', 0 );
	  $peak_time_apply_to = simontaxi_get_option( 'peak_time_apply_to', 6 );
	  $pickup_date = isset( $booking_step1['pickup_date'] ) ? $booking_step1['pickup_date'] : '';
	  $peak_time_surcharge = 'no';
	  if ( ! empty( $pickup_date ) ) {
		  $day_of_pickup = date('w', strtotime( $pickup_date ) );
		  $allowed_days = array();
		  $from = $peak_time_apply_from;
		  $to = $peak_time_apply_to;
		  if ( $to < $from ) {
			  for( $i = 0; $i <= $to; $i++ ) {
				  $allowed_days[ $i ] = $i;
			  }
			  $to = 6;
		  }
		  for( $i = $from; $i <= $to; $i++ ) {
			  $allowed_days[ $i ] = $i;
		  }
		  // dd( array_keys( $allowed_days ), false );
		  //echo $day_of_pickup;
		  //print_r( $allowed_days );
		  if ( in_array( $day_of_pickup, array_keys( $allowed_days ) ) ) {
			  $peak_time_surcharge = 'yes';
		  } else {
			 $peak_time_surcharge = 'no'; 
		  }
	  }
	  // echo $peak_time_surcharge;
	  if ( $peak_time_surcharge == 'yes' && simontaxi_get_option( 'peak_time_surcharge', 0 ) > 0 ) {

		  /** Let us take 'Peak time time' which admin specified */
		  $peak_time_from = simontaxi_get_option( 'peak_time_from', 0 );
		  $peak_time_from_minutes = simontaxi_get_option( 'peak_time_from_minutes', 0 );
		  $str = ' am';
		  if ( $peak_time_from >= 12 ) {
			 $str = ' pm';
			 $peak_time_from = $peak_time_from - 12;
			 if ( $peak_time_from == 0 ) {
				 $peak_time_from = 12;
			 }
		  }
		  $peak_time_from = $peak_time_from . ':' . $peak_time_from_minutes . $str;
		  
		  $peak_time_to = simontaxi_get_option( 'peak_time_to', 6 );
		  $peak_time_to_minutes = simontaxi_get_option( 'peak_time_to_minutes', 0 );
		  $str = ' am';
		  if ( $peak_time_to >= 12 ) {
			 $str = ' pm';
			 $peak_time_to = $peak_time_to - 12;
			 if ( $peak_time_to == 0 ) {
				 $peak_time_to = 12;
			 }
		  }
		  $peak_time_to = $peak_time_to . ':' . $peak_time_to_minutes . $str;
		  		  
		  /**
		   * Let us calculate peak-time surcharge for onward journey
		   */
		  $onward_pickup_time = $booking_step1['pickup_time'];
		  $hours = explode( ':', $onward_pickup_time );
		  
		  /**
		   * @since 2.0.2
		   * Change Description: 
		   * PHP 5.3 doesn't support the [] array syntax. Only PHP 5.4 and later does. For older PHP, you need to use array() instead of [].
		   */
		  if ( ! empty( $hours ) ) {
			 $hours_only = $hours[0];
			if ( $hours_only >= 12 ) {
				$mins = ! empty( $hours[1] ) ? $hours[1] : '00';
				$hours_only = $hours_only -12;
				if ( $hours_only == 0 ) {
					 $hours_only = 12;
				 }
				$onward_pickup_time = $hours_only . ':' . $mins . ' pm';
			} else {
				$onward_pickup_time = $onward_pickup_time . ' am';
			}
		  }
		  // echo $peak_time_from . '##' . $peak_time_to . '@@' . $hours;
		  //var_dump( simontaxi_is_between_times( $peak_time_from, $peak_time_to, $hours ) );
		  /** Which means user selects time in peak-time. Then we need to add surcharges */
		  // if ( $hours >= $peak_time_from && $hours <= $peak_time_to ) {
		  if ( simontaxi_is_between_times( $peak_time_from, $peak_time_to, $onward_pickup_time ) ) {		
			  if ( simontaxi_get_option( 'peak_time_surcharge_type', 'value' ) == 'percent' ) {
				  $peak_time_surcharge_onward = ( $basic_amount * simontaxi_get_option( 'peak_time_surcharge', 0) ) / 100;
			  } else {
				  $peak_time_surcharge_onward = simontaxi_get_option( 'peak_time_surcharge', 0);
			  }			 
		  }

		  /**
		   * Let us calculate peak-time surcharge for return journey if it exists
		   */
		  if ( in_array( $booking_step1['journey_type'], apply_filters( 'simontaxi_twoway_other_tabs_peaktime', array( 'two_way' ) ) ) ) {
			  $pickup_time_return = $booking_step1['pickup_time_return'];
			  $hours = explode( ':', $pickup_time_return );
			  /**
			   * @since 2.0.2
			   * Change Description: 
			   * PHP 5.3 doesn't support the [] array syntax. Only PHP 5.4 and later does. For older PHP, you need to use array() instead of [].
			   */
			  if ( ! empty( $hours ) ) {
				 $hours = $hours[0]; 
			  }
			  /** Which means user selects time in peak-time. Then we need to add surcharges */
			  if ( $hours >= $peak_time_from && $hours < $peak_time_to ) {				  
				  if ( simontaxi_get_option( 'peak_time_surcharge_type', 'value' ) == 'percent' ) {
					$peak_time_surcharge_return = ( $basic_amount * simontaxi_get_option( 'peak_time_surcharge', 0 ) ) / 100;
				  } else {
					$peak_time_surcharge_return = simontaxi_get_option( 'peak_time_surcharge', 0 );
				  }
			  }
		  }
	  }
	  $surcharges['peak_time_surcharge'] = $peak_time_surcharge_onward + $peak_time_surcharge_return;
	  $surcharges['peak_time_surcharge_onward'] = $peak_time_surcharge_onward;
	  $surcharges['peak_time_surcharge_return'] = $peak_time_surcharge_return;
	  

	/**
	 * Let us calculate Peack season surcharges if any
	 */
	$peak_season_surcharge_onward = $peak_season_surcharge_return = 0;
	$peak_seasons = get_terms( 'peak_season', array( 'hide_empty' => false ) );

	if ( ! empty( $peak_seasons ) ) {

		$pickup_date = isset( $booking_step1['pickup_date'] ) ? $booking_step1['pickup_date'] : '';
		$pickup_date_return = isset( $booking_step1['pickup_date_return'] ) ? $booking_step1['pickup_date_return'] : '';
		
		
		/**
		 * Strtotime() doesn't work with dd/mm/YYYY format
		 *
		 * @since 2.0.8 
		 * @see https://stackoverflow.com/questions/2891937/strtotime-doesnt-work-with-dd-mm-yyyy-format
		 */
		if ( ! empty( $pickup_date ) ) {
			$pickup_date = str_replace('/', '-', $pickup_date);
		}
		if ( ! empty( $pickup_date_return ) ) {
			$pickup_date_return = str_replace('/', '-', $pickup_date_return);
		}
		
		 /**
		 * Let us convert date into a sinle format so that comparision is simple.
		*/
		if ( $pickup_date != '' ) {
			$pickup_date = date( 'Y-m-d', strtotime( $pickup_date ) );
		}
		if ( $pickup_date_return != '' ) {
			$pickup_date_return = date( 'Y-m-d', strtotime( $pickup_date_return ) );
		}
		foreach ( $peak_seasons as $peak_season ) {
			$peak_season_start = get_term_meta( $peak_season->term_id, 'peak_season', true );
			$peak_season_end = get_term_meta( $peak_season->term_id, 'peak_season_end', true );
			
			/**
			 * Strtotime() doesn't work with dd/mm/YYYY format
			 *
			 * @since 2.0.8 
			 * @see https://stackoverflow.com/questions/2891937/strtotime-doesnt-work-with-dd-mm-yyyy-format
			 */
			if ( ! empty( $peak_season_start ) ) {
				$peak_season_start = str_replace('/', '-', $peak_season_start);
			}
			if ( ! empty( $peak_season_end ) ) {
				$peak_season_end = str_replace('/', '-', $peak_season_end);
			}
			
			/**
			 * Let us convert date into a sinle format so that comparision is simple.
			*/
			if ( $peak_season_start != '' ) {
				$peak_season_start = date( 'Y-m-d', strtotime( $peak_season_start ) );
			}
			if ( $peak_season_end != '' ) {
				$peak_season_end = date( 'Y-m-d', strtotime( $peak_season_end ) );
			}
			/**
			 * Let us calculate peak season surcharge for onward journey
			 */
			/**
			 * If the selected date is between peack season dates, then we need apply peack season surcharges
			 */

			if ( $pickup_date >= $peak_season_start && $pickup_date <= $peak_season_end ) {
				$surcharge = get_term_meta( $peak_season->term_id, 'surcharge', true );
				$surcharge_type = get_term_meta( $peak_season->term_id, 'surcharge_type', true );
				/**
				 * We are adding all peack season charges, if more than one.
				 *
				 * @since 2.0.0
				 */
				if ( $surcharge_type == 'percent' ) {
					$peak_season_surcharge_onward += ( $basic_amount * $surcharge) / 100;
				} else {
					$peak_season_surcharge_onward += $surcharge;
				}
			}

			/**
			 * Let us calculate peak season surcharge for return journey
			 */
			 if ( $pickup_date_return != '' ) {
				 if ( $pickup_date_return >= $peak_season_start && $pickup_date_return <= $peak_season_end ) {
					$surcharge = get_term_meta( $peak_season->term_id, 'surcharge', true );
					$surcharge_type = get_term_meta( $peak_season->term_id, 'surcharge_type', true );
					if ( $surcharge_type == 'percent' ) {
						$peak_season_surcharge_return += ( $basic_amount * $surcharge) / 100;
					} else {
						$peak_season_surcharge_return += $surcharge;
					}
				}
			 }
		}
	}
	
	$surcharges['peak_season_surcharge'] = ( $peak_season_surcharge_onward + $peak_season_surcharge_return );
	$surcharges['peak_season_surcharge_onward'] = $peak_season_surcharge_onward;
	$surcharges['peak_season_surcharge_return'] = $peak_season_surcharge_return;

	$surcharges['surcharge_total'] = ( $surcharges['waitingtime_surcharge'] + $surcharges['additionalpoints_surcharge'] + $surcharges['airport_surcharge'] + $surcharges['peak_time_surcharge'] + $surcharges['peak_season_surcharge'] );
	
	/**
	 * We are 'airport_surcharge' surcharges to only onward journey
	 */
	$surcharges['surcharge_total_onward'] = ( $surcharges['waitingtime_surcharge_onward'] + $surcharges['additionalpoints_surcharge_onward'] + $surcharges['airport_surcharge'] + $peak_time_surcharge_onward + $peak_season_surcharge_onward);

	$surcharges['surcharge_total_return'] = ( $surcharges['waitingtime_surcharge_return'] + $surcharges['additionalpoints_surcharge_return'] + $peak_time_surcharge_return + $peak_season_surcharge_return);
	
	/**
	 * Let us give ability to modify external plugins if any other surcharges.
	 */
	$surcharges_new = apply_filters( 'simontaxi_additional_surcharges', $surcharges, $booking_step1, $basic_amount );
	if ( ! empty( $surcharges_new ) ) {
		$surcharges = $surcharges_new;
	}

	if ( $key != '' ) {
		 return isset( $surcharges[ $key ] ) ? $surcharges[ $key ] : '';
	 } else {
		 return $surcharges;
	 }
  }
}


 if ( ! function_exists( 'simontaxi_get_fare_details' ) ) {
	 /**
	 * This function returns the fare details which includes basic fare, service tax if any, surcharges if any, onward fare, return fare separately, so that it helps to get the any amount at any point of time.
	 *
	 * @since 1.0
	 * @param string $key (Optional) - desired key.
	 * @return array|string
	 */
	 function simontaxi_get_fare_details( $key = '', $booking_id = '' ) {
		 global $wpdb;
		 if ( ! empty( $booking_id ) ) {
			$bookings = $wpdb->prefix . 'st_bookings';
			$sql = "SELECT *, `" . $bookings . "`.`ID` AS booking_id, `" . $bookings . "`.`reference` AS booking_ref FROM `" . $bookings . "` WHERE `" . $bookings . "`.booking_contacts!='' AND `" . $bookings . "`.ID=" . $booking_id;
			
			$booking_details = $wpdb->get_row( $sql );
			
			if ( ! empty( $booking_details ) ) {
				$session_details = json_decode( $booking_details->session_details );
				if ( is_object( $session_details ) ) {
					$session_details = (array) $session_details;
				}
				$booking_step1 = array();
				if ( ! empty( $session_details[0] ) ) {
					$booking_step1 = (array) $session_details[0];
				}
				$booking_step2 = array();
				if ( ! empty( $session_details[1] ) ) {
					$booking_step2 = (array) $session_details[1];
				}
				$discount_details = '';
			}
			
		 } else {
			 $booking_step1 = simontaxi_get_session( 'booking_step1', array() );

			 $booking_step2 = simontaxi_get_session( 'booking_step2', array() );
			 $discount_details = simontaxi_get_session( 'discount_details', '' );
			 // echo 'NO Booking ID';
		 }

		 $discount_amount = 0;
		 if ( $discount_details != '' ) {
			 $discount_amount = ( isset( $discount_details['discount_amount'] ) ) ? $discount_details['discount_amount'] : 0;
		 }
		 $basic_amount = ( isset( $booking_step2['selected_amount'] ) ) ? $booking_step2['selected_amount'] : 0;
		 /* Amount selected for a vehicle */
		 $details = array( 'basic_amount' => $basic_amount);

		 /**
		  * Let us calculate tax based on admin settings
		  */
		 $tax_calculation_based_on = simontaxi_get_option( 'tax_calculation_based_on', 'basicfare' );
		 if ( $tax_calculation_based_on == 'basicfaresurcharges' ) {
			$surcharges = simontaxi_get_surcharges( $key, $booking_id );
			$details['tax_amount_onward'] = simontaxi_get_tax( $basic_amount + $surcharges['surcharge_total_onward'] );
			if ( in_array( $booking_step1['journey_type'], apply_filters( 'simontaxi_twoway_other_tabs_tax', array( 'two_way' ) ) ) ) {
				$details['tax_amount_return'] = simontaxi_get_tax( $basic_amount + $surcharges['surcharge_total_return'] );
			} else {
				$details['tax_amount_return'] = 0;
			}
		 } elseif ( $tax_calculation_based_on == 'basicfaresurchargesminusdiscount' ) {
			$surcharges = simontaxi_get_surcharges();
			
			$discount_details = simontaxi_get_session( 'discount_details', array() );
			$discount_amount = 0;
			if ( ! empty( $discount_details ) ) {
				$discount_amount = $discount_details['discount_amount'];
			}
			$details['tax_amount_onward'] = simontaxi_get_tax( $basic_amount + $surcharges['surcharge_total_onward'] - $discount_amount );
			if ( in_array( $booking_step1['journey_type'], apply_filters( 'simontaxi_twoway_other_tabs_tax_return', array( 'two_way' ) ) ) ) {
				$details['tax_amount_return'] = simontaxi_get_tax( $basic_amount + $surcharges['surcharge_total_return'] );
			} else {
				$details['tax_amount_return'] = 0;
			}
		 } else {
			$tax_amount = simontaxi_get_tax( $basic_amount );
			$details['tax_amount_onward'] = $tax_amount;
			if ( in_array( $booking_step1['journey_type'], apply_filters( 'simontaxi_twoway_other_tabs_tax_return', array( 'two_way' ) ) ) ) {
				$details['tax_amount_return'] = $tax_amount;
			} else {
				$details['tax_amount_return'] = 0;
			}
		 }
		 $details['tax_amount'] = $details['tax_amount_onward'] + $details['tax_amount_return'];


		 /* Discount applied */
		 $details['discount_amount'] = $discount_amount;

		 /* Discount details */
		 $details['discount_details'] = $discount_details;

		 /**
		  * get all surcharges if any
		  */
		 $details['surcharges'] = simontaxi_get_surcharges($key, $booking_id);
		 // var_dump(  $details['surcharges']['surcharge_total'] );die();
		 /* Surcharges
		  * For now we are calculating
		  * - 'waitingtime_surcharge'
		  * - 'additionalpoints_surcharge',
		  * - 'airport_surcharge',
		  * - 'peak_time_surcharge',
		  * - 'peak_season_surcharge'
		  */
		 $details['surcharges_amount'] = $details['surcharges']['surcharge_total'];
		 $details['surcharges_amount_onward'] = $details['surcharges']['surcharge_total_onward'];
		 $details['surcharges_amount_return'] = $details['surcharges']['surcharge_total_return'];

		 /** Final amount For Onward
		  * We are assuming same additional pickups or drop-offs for onward and return if user choose 'Two way' Journey
		  * We are showing airport surcharges in onward journey only
		  * We are showing discount details in onward journey only
		  */
		 $amount_payable = ( $basic_amount + $details['tax_amount_onward'] + $details['surcharges_amount_onward'] ) - ( $details['discount_amount'] );
		 $details['amount_payable_onward'] = $amount_payable;

		 /** Final amount For Return
		  * We are assuming same additional pickups or drop-offs for onward and return if user choose 'Two way' Journey
		  * We are showing airport surcharges in onward journey only
		  * We are showing discount details in onward journey only
		  */
		 if ( in_array( $booking_step1['journey_type'], apply_filters( 'simontaxi_twoway_other_tabs_tax', array( 'two_way' ) ) ) ) {
		 $amount_payable = ( $basic_amount + $details['tax_amount_return'] + $details['surcharges_amount_return'] );
		 $details['amount_payable_return'] = $amount_payable;
		 } else {
			 $details['amount_payable_return'] = 0;
		 }

		 /** Final amount Both Onward and Return if it exists */
		 $details['amount_payable'] = $details['amount_payable_onward'] + $details['amount_payable_return'];
		 
		 $details = apply_filters( 'simontaxi_additional_charges', $details );
		 		
		 /**
		  * PHP number format without comma
		  *
		  * @since 2.0.1
		  */
		 $details['amount_payable'] = number_format( $details['amount_payable'], 2, '.', '' );
		 
		 

		 if ( $key != '' ) {
			 return isset( $details[ $key ] ) ? $details[ $key ] : '';
		 } else {
			 return $details;
		 }
	 }
 }
 

if ( ! function_exists( 'simontaxi_get_countries' ) ) :
	/**
	 * Let us get countries list which are available
	 *
	 * @global wpdb  $wpdb  WordPress database abstraction object.
	 * @since 1.0
	 */
	function simontaxi_get_countries() {
		global $wpdb;
	$query = "SELECT * FROM {$wpdb->prefix}st_countries GROUP BY name ORDER BY name ASC";
		return $wpdb->get_results( $query);
	}
endif;

if ( ! function_exists( 'simontaxi_get_countries_values' ) ) :
	/**
	 * Let us get specific value regarding countries
	 *
	 * @global wpdb  $wpdb  WordPress database abstraction object.
	 * @since 1.0
	 */
	function simontaxi_get_countries_values( $key, $value, $field ) {
		global $wpdb;
		$query = "SELECT * FROM {$wpdb->prefix}st_countries WHERE $key = '$value' ORDER BY name ASC";
		$result = $wpdb->get_results( $query);
		$val = '';
		if ( !empty( $result) ) {
			foreach ( $result as $row ) {
				if ( isset( $row->$field) ) {
					$val = $row->$field;
					break;
				}
			}
		}
		return $val;
	}
endif;

if ( ! function_exists( 'simontaxi_get_vehiclle_details' ) ) :
	/**
	 * This function will get all data related to a vehicle
	 * @param integer $vehicle_id - ID.
	 */
	function simontaxi_get_vehiclle_details( $vehicle_id ) {
		if ( ! empty( $vehicle_id ) ) {
			$vehicle = get_post( $vehicle_id );
			if ( ! empty( $vehicle ) ) {
				$post_id = $vehicle->ID;
				$vehicle->post_meta = simontaxi_filter_gk( get_post_meta( $post_id ) );
				$vehicle->thumbnail = get_the_post_thumbnail( $post_id );
				$vehicle->types = wp_get_post_terms( $post_id, 'vehicle_types' );
				$vehicle->features = wp_get_post_terms( $post_id, 'vehicle_features' );
				$vehicles_array[] = $vehicle;
			}
			return $vehicle;
		} else {
			return '';
		}
	}
endif;

if ( ! function_exists( 'simontaxi_is_paypal_accept' ) ) {
	/**
	 * This function checks whether the given currency is accepted by paypal or not
	 * @param string $currency_code - Code.
	 */
	function simontaxi_is_paypal_accept( $currency_code ) {
		/**
		 * Refenrence : https://developer.paypal.com/docs/classic/api/currency_codes/#paypal
		 */
		$accepted_currencies = array(
			'AUD' => 'Australian Dollar',
			'BRL' => 'Brazilian Real',
			'CAD' => 'Canadian Dollar',
			'CZK' => 'Czech Koruna',
			'DKK' => 'Danish Krone',
			'EUR' => 'Euro',
			'HKD' => 'Hong Kong Dollar',
			'HUF' => 'Hungarian Forint',
			'ILS' => 'Israeli New Sheqel',
			'JPY' => 'Japanese Yen',
			'MYR' => 'Malaysian Ringgit',
			'MXN' => 'Mexican Peso',
			'NOK' => 'Norwegian Krone',
			'NZD' => 'New Zealand Dollar',
			'PHP' => 'Philippine Peso',
			'PLN' => 'Polish Zloty',
			'GBP' => 'Pound Sterling',
			'RUB' => 'Russian Ruble',
			'SGD' => 'Singapore Dollar',
			'SEK' => 'Swedish Krona',
			'CHF' => 'Swiss Franc',
			'TWD' => 'Taiwan New Dollar',
			'THB' => 'Thai Baht',
			'USD' => 'U.S. Dollar',
		);
		if ( isset( $accepted_currencies[ $currency_code ] ) ) {
			return true;
		} else {
			return false;
		}
	}
}


if ( ! function_exists( 'simontaxi_send_email' ) ) {
	/**
	 * This function will send the email to the specified user
	 *
	 * @param string $email - Recepient email.
	 * @param string $template - Template to send.
	 * @param string $type - Type of Journey.
	 */
	function simontaxi_send_email( $email, $template, $type = 'onward', $options = array() ) {
		if ( in_array( $template, apply_filters( 'simontaxi_booking_success_templates', array( 'booking-success' ) ) ) ) {
			$options['template'] = $template;
			$email_body = simontaxi_booking_success_mail( $type, $options );
			
			$email_body = apply_filters( 'simontaxi_flt_booking_success_mail_body', $email_body, $email, $template, $type, $options );
			
			$email_subject = simontaxi_get_option( 'vehicle_booking_success_email_subject', esc_html__( 'Booking Success', 'simontaxi' ) );
			$from_email = simontaxi_get_option( 'vehicle_booking_success_from_address', get_option( 'admin_email' ) );
			$from_name = simontaxi_get_option( 'vehicle_booking_success_from_name', get_bloginfo() );
			$headers = 'From: ' . $from_name. ' <' . $from_email. '>' . "\r\n";
            /**
			 * Let us change the email type based on admin settings
			*/
			if ( simontaxi_get_option( 'vehicle_booking_success_email_type', 'html' ) == 'html' ) {
				add_filter( 'wp_mail_content_type', 'simontaxi_mail_html_type' );
			} else {
				add_filter( 'wp_mail_content_type', 'simontaxi_mail_text_type' );
			}			
			wp_mail( $email, $email_subject, $email_body, $headers);

			/**
			 * Reset content-type to avoid conflicts -- https://core.trac.wordpress.org/ticket/23578
			*/
			if ( simontaxi_get_option( 'vehicle_booking_success_email_type', 'html' ) == 'html' ) {
				remove_filter( 'wp_mail_content_type', 'simontaxi_mail_html_type' );
			} else {
				remove_filter( 'wp_mail_content_type', 'simontaxi_mail_text_type' );
			}
		}
	}
}

if ( ! function_exists( 'simontaxi_send_sms' ) ) :
	/**
	 * This function send the SMS based on admin settings
	 *
	 * @since 1.0
	 * @param string $mobile Mobile number.
	 * @param string $template Template to send.
	 * @param string $type onward|return.
	 * @return array|string
	 */
	function simontaxi_send_sms( $mobile, $template, $type = 'onward', $options = array() ) {
		
		global $wpdb;		
		$booking_type = simontaxi_get_session( 'booking_step1', '', 'booking_type' );
		$vehicle_id = simontaxi_get_session( 'booking_step2', '', 'selected_vehicle' );
		$user_type = ( ! empty( $options['user_type'] ) ) ? $options['user_type'] : '';
		/**
		 * We are using 3rd party plugin to send SMS so we need to check whether the plugin is active or not
		*/
		if ( simontaxi_is_sms_gateway_active() && $mobile != '' && $template != '' ) {
			/** we can send different emails for different users. For this we need to create separate files for each user.
			 *
			 * suppose if template is "sms-booking-confirmed" if you want to send different SMS template to driver rather than regular template, then you need to create a post with name "sms-booking-confirmed-driver"
			 *
			 * @since 2.0.9
			 */
			$posttitle = $template;
			
			if ( ! empty( $vehicle_id ) && simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id, 'smstemplate' ) ) {
				$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id;
			} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type, 'smstemplate' ) ) {
				$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type;
			} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type, 'smstemplate' ) ) {
				$posttitle = $posttitle . '-' . $user_type;
			}
			$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '$posttitle'  AND post_status='publish' AND post_type='smstemplate'" );
			$getpost = get_post( $postid );
			
			/**
			 * @since 2.0.8
			 *
			 * But my site now has two languages and if someone orders in a different language, he gets a notification in Polish.
			 */
			if ( 'file' == simontaxi_get_option( 'vehicle_booking_success_sms_body', 'smstemplate' ) ) {
				ob_start();
				$posttitle = $template;
				$template = '/templates/smstemplates/' . $template . '.php';
				/**
				 * In this way we can send different emails for different users. For this we need to create separate files for each user.
				 *
				 * suppose if template is "sms-booking-confirmed" if you want to send different email template to driver rather than regular template, then you need to create a file with name "sms-booking-confirmed-driver.php"
				 *
				 * Hierarchy - "wp-content/plugins/vehicle-booking/templates"
				 * 1. template-user_type-booking_type-vehicle_id (Eg: sms-booking-confirmed-driver-p2p-965.php)
				 * 2. template-user_type-booking_type (Eg: sms-booking-confirmed-driver-p2p.php)
				 * 3. template-user_type (Eg: sms-booking-confirmed-driver.php)
				 * 4. template-booking_type (Eg: sms-booking-confirmed-p2p.php)
				 * 5. template (Eg: sms-booking-confirmed.php)
				 *
				 */
				if ( ! empty( $vehicle_id ) && ! empty( $user_type ) && simontaxi_is_file_exists( '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php' ) ) {
					$template = '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php';
				} elseif ( ! empty( $user_type ) && simontaxi_is_file_exists( '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php' ) ) {
					$template = '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php';
				} elseif ( ! empty( $user_type ) && simontaxi_is_file_exists( '/templates/smstemplates/' . $posttitle . '-' . $user_type . '.php' ) ) {
					$template = '/templates/smstemplates/' . $posttitle . '-' . $user_type . '.php';
				} elseif ( ! empty( $booking_type ) && simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $booking_type . '.php' ) ) {
					$template = '/templates/smstemplates/' . $posttitle . '-' . $booking_type . '.php';
				}
				if ( simontaxi_is_template_customized( $template ) ) {
					include_once( simontaxi_get_theme_template_dir_name() . $template );
				} else {
					include_once( SIMONTAXI_PLUGIN_PATH . $template );
				}
				$template = ob_get_clean();
			} else {
				$template= $getpost->post_content;
			}
			
			$content = $getpost->post_content;
			$pattern = array(
				'/\{BLOG_TITLE\}/',
				'/\{BOOKING_REF\}/',
				'/\{FROM\}/',
				'/\{TO\}/',
				'/\{AMOUNT\}/',
				'/\{PAYMENT_STATUS\}/',
				'/\{DATE\}/',
				'/\{PAID\}/',
			);
			/**
			 * 2.0.8
			 */
			$pattern = apply_filters('simontaxi_send_sms_pattern', $pattern);
			$paid = simontaxi_get_session( 'booking_step4', 0, 'amount_paid' ); //This contains total amount (Onward+Return) paid at transaction, means paid through payment gateway.
			
			if ( in_array( simontaxi_get_session( 'booking_step1', 'one_way', 'journey_type' ), apply_filters( 'simontaxi_twoway_other_tabs_payable', array( 'two_way' ) ) ) ) {
				$amount_details = simontaxi_get_fare_details();
				if ( $type == 'onward' ) {
					$paid = $paid - $amount_details['amount_payable_return']; //Means not '$paid' variable has amount paid for 'onward' journey
				} else {
					$paid = $paid - $amount_details['amount_payable_onward'];
				}
			}
			$amount_details = simontaxi_get_fare_details();
			if ( 'return' === $type ) {
				$journey_date = simontaxi_get_session( 'booking_step1', date( 'Y-m-d' ), 'pickup_date_return' ) . ' ' . simontaxi_get_time_display_format( simontaxi_get_session( 'booking_step1', date( 'Y-m-d' ), 'pickup_time_return' ) );
				
				$paid_replace = simontaxi_get_currency( $paid );
				/**
				 * Let us find if the amount contains '$' at its starting. If it contains '$' it comes backreference and trying to find for PHP varaible!!
				 * So let us add a '\' before the '$'
				 * Specially this will be the case with currency Dollor ($) with left placement (Settings->currency->Currency Position)
				 * @see http://php.net/manual/en/function.preg-replace.php#106263
				 */				
				$first_character = substr( $paid_replace, 0, 1 );
				if ( '$' === $first_character ) {
					$paid_replace = '\\' . $paid_replace;
				}

				$replacement = array(
					get_bloginfo(),
					simontaxi_get_session( 'booking_step1', '', 'reference' ),
					simontaxi_get_session( 'booking_step1', '', 'drop_location' ),
					simontaxi_get_session( 'booking_step1', '', 'pickup_location' ),
					$amount_details['amount_payable_onward'],
					ucfirst(simontaxi_get_session( 'booking_step4', 'failed', 'payment_status' ) ),
					$journey_date,
					$paid_replace,
				);
				/**
				 * 2.0.8
				 */
				$replacement = apply_filters('simontaxi_send_sms_replacement_return', $replacement);
			} else {
				$journey_date = simontaxi_get_session( 'booking_step1', date( 'Y-m-d' ), 'pickup_date' ) . ' ' . simontaxi_get_time_display_format( simontaxi_get_session( 'booking_step1', date( 'Y-m-d' ), 'pickup_time' ) );

				$paid_replace = simontaxi_get_currency( $paid );
				/**
				 * Let us find if the amount contains '$' at its starting. If it contains '$' it comes backreference and trying to find for PHP varaible!!
				 * So let us add a '\' before the '$'
				 * Specially this will be the case with currency Dollor ($) with left placement (Settings->currency->Currency Position)
				 * @see http://php.net/manual/en/function.preg-replace.php#106263
				 */				
				$first_character = substr( $paid_replace, 0, 1 );
				if ( '$' === $first_character ) {
					$paid_replace = '\\' . $paid_replace;
				}
				$replacement = array(
					get_bloginfo(),
					simontaxi_get_session( 'booking_step1', '', 'reference' ),
					simontaxi_get_session( 'booking_step1', '', 'pickup_location' ),
					simontaxi_get_session( 'booking_step1', '', 'drop_location' ),
					$amount_details['amount_payable_onward'],
					ucfirst(simontaxi_get_session( 'booking_step4', 'failed', 'payment_status' ) ),
					$journey_date,
					$paid_replace,
				);
				/**
				 * 2.0.8
				 */
				$replacement = apply_filters('simontaxi_send_sms_replacement_onward', $replacement);
			}
			$content = preg_replace( $pattern, $replacement, $content);
			
			/**
			 * @since 2.0.9
			 */
			$filter_type = str_replace( '-', '_', $template );
			$additional_top = apply_filters( "{$filter_type}_additional_top", '' );
			$content = str_replace( "{{$filter_type}_additional_top}", $additional_top, $content );
			
			$additional_bottom = apply_filters( "{$filter_type}_additional_bottom", '' );
			$content = str_replace( "{{$filter_type}_additional_bottom}", $additional_bottom, $content );
			
			$content = apply_filters( "simontaxi_flt_{$filter_type}_content", $mobile, $template, $type, $options );
			
			global $sms;
			$sms->to = array( $mobile );
			$sms->msg =  $content;
			try{
			$sms->SendSMS();
			} catch(Exception $e) {}
		}
	}
endif;

if ( ! defined( 'simontaxi_booking_success_mail' ) ) :
	/**
	* This function generate the SMS message to send
	*
	* @since 1.0
	* @param string $type onward|return.
	* @return string
	*/
	function simontaxi_booking_success_mail( $type, $options = array() ) {
		global $wpdb;
		$blog_title = get_bloginfo();
		$posttitle = $options['template'];
		
		/**
		 * In this way we can send different emails for different users. For this we need to create separate files for each user.
		 *
		 * suppose if template is "booking-success" if you want to send different email template to driver rather than regular template, then you need to create a post with name "booking-success-driver"
		 *
		 * Hierarchy
		 * 1. template-user_type-booking_type-vehicle_id (Eg: booking-success-driver-p2p-926)
		 * 2. template-user_type-booking_type (Eg: booking-success-driver-p2p)
		 * 3. template-user_type (Eg: booking-success-driver)
		 * 4. template-booking_type (Eg: booking-success-p2p)
		 * 5. template (Eg: booking-success)
		 *
		 * @since 2.0.9
		 */

		$booking_type = simontaxi_get_session( 'booking_step1', '', 'booking_type' );
		$vehicle_id = simontaxi_get_session( 'booking_step2', '', 'selected_vehicle' );
		$user_type = ( ! empty( $options['user_type'] ) ) ? $options['user_type'] : '';
		if ( ! empty( $vehicle_id ) && simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id ) ) {
			$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id;
		} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type ) ) {
			$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type;
		} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type ) ) {
			$posttitle = $posttitle . '-' . $user_type;
		} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $booking_type ) ) {
			$posttitle = $posttitle . '-' . $booking_type;
		}
		
		$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $posttitle . "' AND post_status='publish' AND post_type='emailtemplate'" );
		$getpost= get_post( $postid );
		if ( ! $getpost ) {
			return;
		}
		/**
		 * @since 2.0.8
		 *
		 * But my site now has two languages and if someone orders in a different language, he gets a notification in Polish.
		 */
		if ( 'file' == simontaxi_get_option( 'vehicle_booking_success_email_body', 'emailtemplate' ) ) {
			ob_start();
			$template = '/templates/emailtemplates/' . $posttitle . '.php';
			/**
			 * In this way we can send different emails for different users. For this we need to create separate files for each user.
			 *
			 * suppose if template is "booking-confirmed" if you want to send different email template to driver rather than regular template, then you need to create a file with name "booking-confirmed-driver.php"
			 *
			 * Hierarchy - "wp-content/plugins/vehicle-booking/templates"
			 * 1. template-user_type-booking_type-vehicle_id (Eg: booking-success-driver-p2p-965.php)
			 * 2. template-user_type-booking_type (Eg: booking-success-driver-p2p.php)
			 * 3. template-user_type (Eg: booking-success-driver.php)
			 * 4. template-booking_type (Eg: booking-success-p2p.php)
			 * 5. template (Eg: booking-success.php)
			 *
			 */
			if ( ! empty( $vehicle_id ) && ! empty( $options['user_type'] ) && simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php' ) ) {
				$template = '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php';
			} elseif ( ! empty( $options['user_type'] ) && simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php' ) ) {
				$template = '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php';
			} elseif ( ! empty( $options['user_type'] ) && simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $options['user_type'] . '.php' ) ) {
				$template = '/templates/emailtemplates/' . $posttitle . '-' . $options['user_type'] . '.php';
			} elseif ( ! empty( $booking_type ) && simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $booking_type . '.php' ) ) {
				$template = '/templates/emailtemplates/' . $posttitle . '-' . $booking_type . '.php';
			}
			if ( simontaxi_is_template_customized( $template ) ) {
				include_once( simontaxi_get_theme_template_dir_name() . $template );
			} else {
				include_once( SIMONTAXI_PLUGIN_PATH . $template );
			}
			$template = ob_get_clean();
		} else {
			$template= $getpost->post_content;
		}
		$pattern = array(
			'/\{BLOG_TITLE\}/',
			'/\{DATE\}/',
			'/\{INVOICE\}/',
			'/\{FROM\}/',
			'/\{TO\}/',
			'/\{JOURNY_DATE\}/',
			'/\{JOURNY_TIME\}/',
			'/\{JOURNY_TYPE\}/',
			'/\{AMOUNT\}/',
			'/\{PAYMENT_STATUS\}/',
			'/\{NAME\}/',
			'/\{MOBILE\}/',
			'/\{EMAIL\}/',
			'/\{VEHICLE\}/',
			'/\{PAID\}/',
			'/\{BOOKING_STATUS\}/',
			
			'/\{PAYMENT_METHOD\}/',
			'/\{FLIGHT_NUMBER\}/',
			'/\{NO_OF_PASSENGERS\}/',
			'/\{SPECIAL_INSTRUCTIONS\}/',
			);
		/**
		 * @since 2.0.8
		 */
		$pattern = apply_filters( 'simontaxi_booking_success_mail_pattern', $pattern );
		
		if ( simontaxi_get_session( 'booking_step4', '', 'payment_status' ) == 'success' ) {
			$booking_status_payment_success = simontaxi_get_option( 'booking_status_payment_success', 'new' );
		} else {
			$booking_status_payment_success = 'new';
		}
		$paid = simontaxi_get_session( 'booking_step4', 0, 'amount_paid' ); //This contains total amount (Onward+Return) paid at transaction, means paid through payment gateway.
		
		$amount_details = simontaxi_get_fare_details();		
		if ( in_array( simontaxi_get_session( 'booking_step1', 'one_way', 'journey_type' ), apply_filters( 'simontaxi_twoway_other_tabs_tax', array( 'two_way' ) ) ) ) {
			if ( 'onward' === $type ) {
				$paid = $paid - $amount_details['amount_payable_return']; //Means not '$paid' variable has amount paid for 'onward' journey
			} else {
				$paid = $paid - $amount_details['amount_payable_onward'];
			}
		}
		
		if ( ! empty( $options['amount'] ) ) { // Hence we are receiving the amount from other function. Here we no need to do anything expect to send mail.
			$paid = $amount;
		}
		
		if ( 'onward' === $type ) {
			$amount_total = $amount_details['amount_payable_onward'];
		} else {
			$amount_total = $amount_details['amount_payable_return'];
		}
		
		if ( ! empty( $options['amount_payable'] ) ) {
			$amount_total = $options['amount_payable'];
		}

		$journey_type = 'ONE WAY';
		$mobile = '-';
		/**
		 * Mobile number field is optional in admin. Lets check whether it is enabled and user enter it.
		 */
		$mobile = simontaxi_get_session( 'booking_step3', '', 'mobile' );
		$mobile_countrycode = simontaxi_get_session( 'booking_step3', '', 'mobile_countrycode' );

		if ( '' !== $mobile && '' !== $mobile_countrycode ) {
			$mobile_countrycode = explode( '_', $mobile_countrycode);
			/**
			 * @since 2.0.2
			 * Change Description: 
			 * PHP 5.3 doesn't support the [] array syntax. Only PHP 5.4 and later does. For older PHP, you need to use array() instead of [].
			 */
			if ( ! empty( $mobile_countrycode ) ) {
				$mobile_countrycode = $mobile_countrycode[0]; 
			}
			$mobile = $mobile_countrycode . $mobile;
		}
		$vehicle_details = simontaxi_get_session( 'booking_step2', array(), 'vehicle_details' );
		$vehicle = ( isset( $vehicle_details->post_title ) ) ? $vehicle_details->post_title : '-';

		$full_name = simontaxi_get_session( 'booking_step3', '', 'email' );
		/**
		 * Full name field is optional in admin. Lets check whether it is enabled and user enter it.
		 */
		if ( simontaxi_get_session( 'booking_step3', '', 'full_name' ) != '' ) {
			$full_name = simontaxi_get_session( 'booking_step3', '', 'full_name' );
		}elseif ( simontaxi_get_session( 'booking_step3', '', 'first_name' ) != '' ) {
			$full_name = simontaxi_get_session( 'booking_step3', '', 'first_name' );
			if ( simontaxi_get_session( 'booking_step3', '', 'last_name' ) != '' ) {
				$full_name .= simontaxi_get_session( 'booking_step3', '', 'last_name' );
			}
		}

		$email = simontaxi_get_session( 'booking_step3', '', 'email' );

		if ( 'return' === $type ) {
			$amount_total_replace = simontaxi_get_currency( $amount_total );
			$paid_replace = simontaxi_get_currency( $paid );
			/**
			 * Let us find if the amount contains '$' at its starting. If it contains '$' it comes backreference and trying to find for PHP varaible!!
			 * So let us add a '\' before the '$'
			 * Specially this will be the case with currency Dollor ($) with left placement (Settings->currency->Currency Position)
			 * @see http://php.net/manual/en/function.preg-replace.php#106263
			 */
			$first_character = substr( $amount_total_replace, 0, 1 );
			if ( '$' === $first_character ) {
				$amount_total_replace = '\\' . $amount_total_replace;
			}
			$first_character = substr( $paid_replace, 0, 1 );
			if ( '$' === $first_character ) {
				$paid_replace = '\\' . $paid_replace;
			}
			
			$no_of_passengers = simontaxi_get_session( 'booking_step3', '', 'no_of_passengers' );
			if ( empty( $no_of_passengers ) ) {
				$no_of_passengers = simontaxi_get_session( 'booking_step1', '', 'number_of_persons' );
			}
			$selected_payment_method = simontaxi_get_session( 'booking_step4', '', 'selected_payment_method' );
			if ( ! empty( $selected_payment_method ) ) {
				$selected_payment_method_title = simontaxi_get_option( $selected_payment_method );
				if ( ! empty( $selected_payment_method_title ) ) {
					$selected_payment_method = ! empty( $selected_payment_method_title['title'] ) ? $selected_payment_method_title['title'] : $selected_payment_method;
				}
			}
			
			$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
			
			$drop_location = simontaxi_get_address($booking_step1, 'drop_location');
			$pickup_location = simontaxi_get_address($booking_step1, 'pickup_location');
			
			$replacement = array(
				$blog_title,
				simontaxi_date_format( date_i18n( 'Y-m-d',time() ) ),
				simontaxi_get_session( 'booking_step1', '', 'reference' ),
				$drop_location,
				$pickup_location,
				simontaxi_date_format( simontaxi_get_session( 'booking_step1', '', 'pickup_date_return' ) ),
				simontaxi_get_time_display_format( simontaxi_get_session( 'booking_step1', '', 'pickup_time_return' ) ),
				ucfirst( str_replace( '_', ' ', $journey_type ) ),
				$amount_total_replace,
				ucfirst(simontaxi_get_session( 'booking_step4', '', 'payment_status' ) ),
				$full_name,
				$mobile,
				$email,
				$vehicle,
				$paid_replace,
				$booking_status_payment_success,
				
				$selected_payment_method,
				simontaxi_get_session( 'booking_step1', '', 'flight_no' ),
				$no_of_passengers,
				simontaxi_get_session( 'booking_step3', '', 'special_instructions' ),
			);
			/**
			 * @since 2.0.8
			 */
			$replacement = apply_filters( 'simontaxi_booking_success_mail_replacement_return', $replacement );
		} else {
			$drop_location = '-';
			/**
			* For hourly rental there will be no drop location, hence let us check whether it exists or not.
			*/
			if ( simontaxi_get_session( 'booking_step1', '', 'drop_location' ) !== '' ) {
				$drop_location = simontaxi_get_session( 'booking_step1', '', 'drop_location' );
			}
			
			$amount_total_replace = simontaxi_get_currency( $amount_total );
			$paid_replace = simontaxi_get_currency( $paid );
						
			/**
			 * Let us find if the amount contains '$' at its starting. If it contains '$' it comes backreference and trying to find for PHP varaible!!
			 * So let us add a '\' before the '$'
			 * Specially this will be the case with currency Dollor ($) with left placement (Settings->currency->Currency Position)
			 * @see http://php.net/manual/en/function.preg-replace.php#106263
			 */
			$first_character = substr( $amount_total_replace, 0, 1 );
			if ( '$' === $first_character ) {
				$amount_total_replace = '\\' . $amount_total_replace;
			}
			$first_character = substr( $paid_replace, 0, 1 );
			if ( '$' === $first_character ) {
				$paid_replace = '\\' . $paid_replace;
			}
			
			$no_of_passengers = simontaxi_get_session( 'booking_step3', '', 'no_of_passengers' );
			if ( empty( $no_of_passengers ) ) {
				$no_of_passengers = simontaxi_get_session( 'booking_step1', '', 'number_of_persons' );
			}			
			$selected_payment_method = simontaxi_get_session( 'booking_step4', '', 'selected_payment_method' );
			if ( ! empty( $selected_payment_method ) ) {
				$selected_payment_method_title = simontaxi_get_option( $selected_payment_method );
				if ( ! empty( $selected_payment_method_title ) ) {
					$selected_payment_method = ! empty( $selected_payment_method_title['title'] ) ? $selected_payment_method_title['title'] : $selected_payment_method;
				}
			}
			
			$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
			
			$drop_location = simontaxi_get_address($booking_step1, 'drop_location');
			$pickup_location = simontaxi_get_address($booking_step1, 'pickup_location');
			
			$replacement = array(
				$blog_title,
				simontaxi_date_format( date_i18n( 'Y-m-d',time() ) ),
				simontaxi_get_session( 'booking_step1', '', 'reference' ),
				$pickup_location,
				$drop_location,
				simontaxi_date_format( simontaxi_get_session( 'booking_step1', '', 'pickup_date' ) ),
				simontaxi_get_time_display_format( simontaxi_get_session( 'booking_step1', '', 'pickup_time' ) ),
				ucfirst( str_replace( '_', ' ', $journey_type ) ),
				$amount_total_replace,
				ucfirst(simontaxi_get_session( 'booking_step4', '', 'payment_status' ) ),
				$full_name,
				$mobile,
				$email,
				$vehicle,
				$paid_replace,
				$booking_status_payment_success,
				
				$selected_payment_method,
				simontaxi_get_session( 'booking_step1', '', 'flight_no' ),
				$no_of_passengers,
				simontaxi_get_session( 'booking_step3', '', 'special_instructions' ),
			);
			/**
			 * @since 2.0.8
			 */
			$replacement = apply_filters( 'simontaxi_booking_success_mail_replacement_onward', $replacement );
		}
		$template =  preg_replace( $pattern, $replacement, $template );
		
		/**
		 * @since 2.0.9
		 */
		$success_mail_additional_top = apply_filters( 'booking_success_mail_additional_top', '' );
		$template = str_replace( '{booking_success_mail_additional_top}', $success_mail_additional_top, $template );
		
		$success_mail_additional_bottom = apply_filters( 'booking_success_mail_additional_bottom', '' );
		$template = str_replace( '{booking_success_mail_additional_bottom}', $success_mail_additional_bottom, $template );
		
		return $template;
	}
endif;

if ( ! defined( 'simontaxi_is_user' ) ) :
	/**
	 * This function check whenter the user is belongs to the particualte user role or not
	 * @param string $user_role - Role.
	 */
	function simontaxi_is_user( $user_role ) {
		$current_user = ( array ) wp_get_current_user();
		$roles = $status_links = array();
		foreach ( $current_user['roles'] as $role ) {
			$roles[] = trim( $role );
		}
		if ( in_array( $user_role, $roles ) )
			return true;
		else
			return false;
	}
endif;

add_action( 'show_user_profile', 'simontaxi_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'simontaxi_show_extra_profile_fields' );

if ( ! function_exists( 'simontaxi_show_extra_profile_fields' ) ) :
	/**
	 * This function to add additional profile fields
	 *
	 * @param string $user - User bject.
	 */
	function simontaxi_show_extra_profile_fields( $user ) {
	$countryList = simontaxi_get_countries();
	?>
	<h3><?php esc_html_e( 'Extra profile information', 'simontaxi' ); ?></h3>
	<link href="<?php echo SIMONTAXI_PACKANDMOVE_PLUGIN_URL ?>css/bootstrap-formhelpers.min.css" rel="stylesheet"></link>
		<script type="text/javascript" src="<?php echo SIMONTAXI_PACKANDMOVE_PLUGIN_URL ?>js/bootstrap-formhelpers.min.js"></script>
		<script type="text/javascript" src="<?php echo SIMONTAXI_PACKANDMOVE_PLUGIN_URL ?>js/bootstrap-formhelpers-timepicker.js"></script>
	<table class="form-table">
		<tr>
			<th><label for="mobile_no"><?php esc_html_e( 'Mobile No.', 'simontaxi' ); ?></label></th>
			<td>
			<select id="mobile_countrycode" name="mobile_countrycode" title="<?php esc_html_e( 'Country code', 'simontaxi' ); ?>"class="selectpicker show-tick show-menu-arrow">
			<option value=""><?php esc_html_e( 'Country code', 'simontaxi' ); ?></option>
			<?php
			if ( $countryList) {
				$mobile_countrycode = get_the_author_meta( 'mobile_countrycode', $user->ID );
				if ( isset( $user_meta['mobile_countrycode'] ) )
					$mobile_countrycode = $user_meta['mobile_countrycode'];
				foreach ( $countryList as $result) {
					$code = $result->phonecode. '_' . $result->id_countries;
					?>
					<option value="<?php echo $code; ?>" <?php if ( $mobile_countrycode == $code) echo 'selected="selected"'; ?>><?php echo $result->name . ' ( ' . $result->phonecode. ' )'; ?> </option>
					<?php
				}
			}
			?>
			</select>
			&nbsp;<input type="text" name="mobile" id="mobile" value="<?php echo esc_attr( get_the_author_meta( 'mobile', $user->ID ) ); ?>" class="regular-text" />
			</td>
		</tr>
		
		<tr>
			<th><label for="company"><?php esc_html_e( 'Company', 'simontaxi' ); ?></label></th>
			<td>
			<?php $company = get_the_author_meta( 'company', $user->ID ); ?>
			<input type="text" class="form-control" id="company" name="company" placeholder="<?php esc_html_e( 'Company name', 'simontaxi' ); ?>" value="<?php echo esc_attr( $company ); ?>">			
			</td>
		</tr>
		
		<tr>
			<th><label for="address"><?php esc_html_e( 'Address', 'simontaxi' ); ?></label></th>
			<td>
			<?php $address = get_the_author_meta( 'address', $user->ID ); ?>
			<input type="text" class="form-control" id="address" name="address" placeholder="<?php esc_html_e( 'Address', 'simontaxi' ); ?>" value="<?php echo esc_attr( $address ); ?>">			
			</td>
		</tr>
		
		<tr>
			<th><label for="city"><?php esc_html_e( 'City', 'simontaxi' ); ?></label></th>
			<td>
			<?php $city = get_the_author_meta( 'city', $user->ID ); ?>
			<input type="text" class="form-control" id="city" name="city" placeholder="<?php esc_html_e( 'City', 'simontaxi' ); ?>" value="<?php echo esc_attr( $city ); ?>">			
			</td>
		</tr>
		
		<tr>
			<th><label for="postal_code"><?php esc_html_e( 'Postal Code', 'simontaxi' ); ?></label></th>
			<td>
			<?php $postal_code = get_the_author_meta( 'postal_code', $user->ID ); ?>
			<input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="<?php esc_html_e( 'Postal Code', 'simontaxi' ); ?>" value="<?php echo esc_attr( $postal_code ); ?>">			
			</td>
		</tr>
		
		<tr>
			<th><label for="fax"><?php esc_html_e( 'Fax', 'simontaxi' ); ?></label></th>
			<td>
			<?php $fax = get_the_author_meta( 'fax', $user->ID ); ?>
			<input type="text" class="form-control" id="fax" name="fax" placeholder="<?php esc_html_e( 'Fax', 'simontaxi' ); ?>" value="<?php echo esc_attr( $fax ); ?>">			
			</td>
		</tr>
		
		<tr>
			<th><label for="tax_number"><?php esc_html_e( 'Tax Number', 'simontaxi' ); ?></label></th>
			<td>
			<?php $tax_number = get_the_author_meta( 'tax_number', $user->ID ); ?>
			<input type="text" class="form-control" id="tax_number" name="tax_number" placeholder="<?php esc_html_e( 'Tax Number', 'simontaxi' ); ?>" value="<?php echo esc_attr( $tax_number ); ?>">			
			</td>
		</tr>
		
		<tr>
			<th><label for="tax_id"><?php esc_html_e( 'Tax ID', 'simontaxi' ); ?></label></th>
			<td>
			<?php $tax_id = get_the_author_meta( 'tax_id', $user->ID ); ?>
			<input type="text" class="form-control" id="tax_id" name="tax_id" placeholder="<?php esc_html_e( 'Tax ID', 'simontaxi' ); ?>" value="<?php echo esc_attr( $tax_id ); ?>">			
			</td>
		</tr>
		
		<tr>
			<th><label for="tax_id"><?php esc_html_e( 'Available dates', 'simontaxi' ); ?></label></th>
			<td>
			<?php $pickup_date_from = get_the_author_meta( 'pickup_date_from', $user->ID ); ?>
			<input type="text" class="st_datepicker_limit" data-language='en' data-timepicker="false" placeholder="<?php echo esc_html__( 'Pickup Date From', 'simontaxi-packandmove' ); ?>" name="pickup_date_from" id="pickup_date_from" value="<?php echo simontaxi_get_value( $_POST, 'pickup_date_from', $pickup_date_from ); ?>" readonly>
			-
			<?php $pickup_date_to = get_the_author_meta( 'pickup_date_to', $user->ID ); ?>
			<input type="text" class="st_datepicker_limit" data-language='en' data-timepicker="false" placeholder="<?php echo esc_html__( 'Pickup Date To', 'simontaxi-packandmove' ); ?>" name="pickup_date_to" id="pickup_date_to" value="<?php echo simontaxi_get_value( $_POST, 'pickup_date_to', $pickup_date_to ); ?>" readonly>
			</td>
		</tr>
		
		<tr>
			<th><label for="tax_id"><?php esc_html_e( 'Pickup time between', 'simontaxi' ); ?><?php echo simontaxi_required_field(); ?></label></th>
			<td>
			<div class="row">
			
			<div class="inner-addon right-addon">
							
				<?php $pickup_time_from = get_the_author_meta( 'pickup_time_from', $user->ID ); ?>
				<?php
				$pickup_time_from = simontaxi_get_value( $_POST, 'pickup_time_from', $pickup_time_from );
				if ( ! empty( $pickup_time_from ) ) {
					list($hours, $minutes) = explode( ':', $pickup_time_from);
				}
				$selected_time = '';
				if ( ! empty( $hours ) ) {
					$selected_time = $hours . ':' . $minutes;
				}
				?>
				<div class="bfh-timepicker form-group col-sm-4" data-name="pickup_time_from" data-time="<?php echo $selected_time; ?>" data-placeholder="<?php echo simontaxi_get_pickuptime_title(); ?>" data-input="timepicker">
				  <div class="input-prepend bfh-timepicker-toggle" data-toggle="bfh-timepicker">
					<span class="add-on"><i class="icon-time"></i></span>
					<input type="text" class="input-medium" readonly>
				  </div>
				  <div class="bfh-timepicker-popover">
					<table class="table">
					  <tbody>
						<tr>
						  <td class="hour">
							<a class="next" href="#"><i class="icon-chevron-up"></i></a><br>
							<input type="text" class="input-mini" readonly><br>
							<a class="previous" href="#"><i class="icon-chevron-down"></i></a>
						  </td>
						  <td class="separator">:</td>
						  <td class="minute">
							<a class="next" href="#"><i class="icon-chevron-up"></i></a><br>
							<input type="text" class="input-mini" readonly><br>
							<a class="previous" href="#"><i class="icon-chevron-down"></i></a>
						  </td>
						</tr>
					  </tbody>
					</table>
				  </div>
				</div>
				<div class="form-group col-sm-2">
				-
				</div>
				<?php $pickup_time_to = get_the_author_meta( 'pickup_time_to', $user->ID ); ?>
				<?php
				$pickup_time_to = simontaxi_get_value( $_POST, 'pickup_time_to', $pickup_time_to );
				if ( ! empty( $pickup_time_to ) ) {
					list($hours, $minutes) = explode( ':', $pickup_time_to);
				}
				$selected_time = '';
				if ( ! empty( $hours ) ) {
					$selected_time = $hours . ':' . $minutes;
				}
				?>
				<div class="bfh-timepicker form-group col-sm-6" data-name="pickup_time_to" data-time="<?php echo $selected_time; ?>" data-placeholder="<?php echo simontaxi_get_pickuptime_title(); ?>" data-input="timepicker">
				  <div class="input-prepend bfh-timepicker-toggle" data-toggle="bfh-timepicker">
					<span class="add-on"><i class="icon-time"></i></span>
					<input type="text" class="input-medium" readonly>
				  </div>
				  <div class="bfh-timepicker-popover">
					<table class="table">
					  <tbody>
						<tr>
						  <td class="hour">
							<a class="next" href="#"><i class="icon-chevron-up"></i></a><br>
							<input type="text" class="input-mini" readonly><br>
							<a class="previous" href="#"><i class="icon-chevron-down"></i></a>
						  </td>
						  <td class="separator">:</td>
						  <td class="minute">
							<a class="next" href="#"><i class="icon-chevron-up"></i></a><br>
							<input type="text" class="input-mini" readonly><br>
							<a class="previous" href="#"><i class="icon-chevron-down"></i></a>
						  </td>
						</tr>
					  </tbody>
					</table>
				  </div>
				</div>
			</div>
		</div>
			</td>
		</tr>
				
		
		
		<tr>
			<th><label for="approval_status"><?php esc_html_e( 'Approval Status', 'simontaxi' ); ?></label></th>
			<td>
			<?php $approval_status = get_the_author_meta( 'approval_status', $user->ID ); ?>
			<select id="approval_status" name="approval_status" title="<?php esc_html_e( 'Approval Status', 'simontaxi' ); ?>"class="selectpicker show-tick show-menu-arrow">
				<option value="pending" <?php if ( 'pending' == $approval_status ) echo 'selected'; ?>><?php esc_html_e( 'Pending', 'simontaxi' ); ?></option>
				<option value="approved" <?php if ( 'approved' == $approval_status ) echo 'selected'; ?>><?php esc_html_e( 'Approved', 'simontaxi' ); ?></option>
			</select>
			
			</td>
		</tr>
		
	</table>
	<?php
	$st_date_format_js = simontaxi_get_option( 'st_date_format_js', 'dd-mm-yy' );
	?>
	<script>
		var dateFormat = '<?php echo $st_date_format_js; ?>';
		jQuery(document).ready( function( $ ) {
			$( '.st_datepicker_limit' ).datepicker({
				dateFormat: dateFormat
			});
		});
	</script>
	<?php }
endif;

add_action( 'personal_options_update', 'simontaxi_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'simontaxi_save_extra_profile_fields' );

if ( ! function_exists( 'simontaxi_save_extra_profile_fields' ) ) :
	/**
	 * This function to save additional profile fields
	 */
	function simontaxi_save_extra_profile_fields( $user_id ) {
		
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}
		
		/* Copy and paste this line for additional fields. Make sure to change 'mobile' to the field ID. */
		update_user_meta( absint( $user_id ), 'mobile', wp_kses_post( $_POST['mobile'] ) );
		update_user_meta( absint( $user_id ), 'mobile_countrycode', wp_kses_post( $_POST['mobile_countrycode'] ) );
		update_user_meta( absint( $user_id ), 'approval_status', wp_kses_post( $_POST['approval_status'] ) );
		
		update_user_meta( absint( $user_id ), 'company', wp_kses_post( $_POST['company'] ) );
		update_user_meta( absint( $user_id ), 'address', wp_kses_post( $_POST['address'] ) );
		update_user_meta( absint( $user_id ), 'postal_code', wp_kses_post( $_POST['postal_code'] ) );
		update_user_meta( absint( $user_id ), 'city', wp_kses_post( $_POST['city'] ) );
		update_user_meta( absint( $user_id ), 'fax', wp_kses_post( $_POST['fax'] ) );
		update_user_meta( absint( $user_id ), 'tax_number', wp_kses_post( $_POST['tax_number'] ) );
		update_user_meta( absint( $user_id ), 'tax_id', wp_kses_post( $_POST['tax_id'] ) );
		
		update_user_meta( absint( $user_id ), 'tax_id', wp_kses_post( $_POST['tax_id'] ) );
		
		update_user_meta( absint( $user_id ), 'pickup_time_from', wp_kses_post( $_POST['pickup_time_from'] ) );
		update_user_meta( absint( $user_id ), 'pickup_time_to', wp_kses_post( $_POST['pickup_time_to'] ) );
		
		update_user_meta( absint( $user_id ), 'pickup_date_from', wp_kses_post( $_POST['pickup_date_from'] ) );
		update_user_meta( absint( $user_id ), 'pickup_date_to', wp_kses_post( $_POST['pickup_date_to'] ) );
		
		
	}
endif;

if ( ! function_exists( 'simontaxi_send_email_sms' ) ) :
	/**
	 * @since 2.0.0
	 */
	add_action( 'simontaxi_send_email_sms', 'simontaxi_send_email_sms', 10, 1 );
	
	/**
	 * This function to send email OR SMS based on admin settings
	 *
	 * @param string $type - Booking type Onward|Return.
	 */
	function simontaxi_send_email_sms( $type = 'onward' ) {
		$pages = get_option( 'simontaxi_pages' );
		
		/**
		 * Let us send email to user based on admin settings
		 */
		if ( simontaxi_get_option( 'vehicle_booking_success_email_user', 'yes' ) == 'yes' ) {
			$email = simontaxi_get_session( 'booking_step3', '', 'email' );
			/**
			 * @since 2.0.9
			 */
			$template = __( 'booking-success', 'simontaxi' );
			if ( ! empty( $pages['booking_success'] ) ) {
				$template = get_the_title( $pages['booking_success'] );
				if ( ! empty( $template ) ) {
					$template = __( $template, 'simontaxi' );
				}
			}
			simontaxi_send_email( $email, $template, $type, array('user_type' => 'user' ) );
		}
		
		/**
		 * Let us send SMS to user based on admin settings
		 * Mobile number field is optional in admin. Lets check whether it is enabled and user enter it.
		 */
		$mobile = simontaxi_get_session( 'booking_step3', '', 'mobile' );
		
		$mobile_countrycode = simontaxi_get_session( 'booking_step3', '', 'mobile_countrycode' );
		
		if ( simontaxi_get_option( 'vehicle_booking_success_sms_user', 'no' ) == 'yes' && '' !== $mobile && '' !== $mobile_countrycode ) {
			$mobile_countrycode = explode( '_', $mobile_countrycode );
			/**
			   * @since 2.0.2
			   * Change Description: 
			   * PHP 5.3 doesn't support the [] array syntax. Only PHP 5.4 and later does. For older PHP, you need to use array() instead of [].
			   */
			  if ( ! empty( $mobile_countrycode ) ) {
				 $mobile_countrycode = $mobile_countrycode[0]; 
			  }
			$append_country_code_to_mobile = simontaxi_get_option( 'append_country_code_to_mobile', 'yes' );
			if ( 'yes' === $append_country_code_to_mobile ) {
				$mobile = $mobile_countrycode . $mobile;
			}
			/**
			 * @since 2.0.9
			 */
			$template = __( 'sms-booking-success', 'simontaxi' );
			if ( ! empty( $pages['sms_booking_success'] ) ) {
				$template = get_the_title( $pages['sms_booking_success'] );
				if ( ! empty( $template ) ) {
					$template = __( $template, 'simontaxi' );
				}
			}
			simontaxi_send_sms( $mobile, $template, $type, array('user_type' => 'user' ) );				
		}

		/**
		 * Let us send email to admin based on admin settings
		 */
		if ( simontaxi_get_option( 'vehicle_booking_success_email_admin', 'yes' ) == 'yes' ) {
			$email = simontaxi_get_option('vehicle_bookings_admin_email', $email);
			if ( empty( $email ) ) {
				$email = get_option( 'admin_email' );
			}
			/**
			 * @since 2.0.9
			 */
			$template = __( 'booking-success', 'simontaxi' );
			if ( ! empty( $pages['booking_success'] ) ) {
				$template = get_the_title( $pages['booking_success'] );
				if ( ! empty( $template ) ) {
					$template = __( $template, 'simontaxi' );
				}
			}
			simontaxi_send_email( $email, $template, $type, array('user_type' => 'admin' ));
		}
		
		/**
		 * Let us send SMS to admin based on admin settings
		 * Mobile number field is optional for admin users. So lets check whether admin user has updated mobile number or not.
		 * Here the admin refers to the mail admin only.
		 */
		$mobile_no = simontaxi_get_primary_admin_mobile(); //Assuming admin ID is '1'
		if ( simontaxi_get_option( 'vehicle_booking_success_sms_admin', 'no' ) == 'yes' && $mobile_no != '' ) {
				/**
				 * @since 2.0.9
				 */
				$template = __( 'sms-booking-success', 'simontaxi' );
				if ( ! empty( $pages['sms_booking_success'] ) ) {
					$template = get_the_title( $pages['sms_booking_success'] );
					if ( ! empty( $template ) ) {
						$template = __( $template, 'simontaxi' );
					}
				}
				simontaxi_send_sms( $mobile_no, $template, $type, array('user_type' => 'admin' ) );
		}
		
	}
endif;

if ( ! function_exists( 'simontaxi_send_status_change_email' ) ) :
	/**
	 * @since 2.0.0
	 */
	add_action( 'simontaxi_send_status_change_email', 'simontaxi_send_status_change_email', 10, 4 );
	/**
	 * This function serves to send email when status is changed in admin area
	 *
	 * @param int $booking_id - ID.
	 * @param string $status - Booking Status.
	 * $param string $type - User Type - User|Admin.
	 */
	function simontaxi_send_status_change_email( $booking_id, $status, $type = 'user', $email_template = '' ) {
		global $wpdb;
		$pages = get_option( 'simontaxi_pages' );
		
		$bookings = $wpdb->prefix. 'st_bookings';
		$payments = $wpdb->prefix. 'st_payments';
		$sql = "SELECT *, `" . $bookings."`.`ID` AS booking_id, `" . $bookings."`.`reference` AS booking_ref, `" . $bookings."`.`pickup_date`, `" . $bookings."`.`pickup_time`  FROM `" . $bookings."` INNER JOIN `" . $payments."` ON `" . $payments."`.`booking_id`=`" . $bookings."`.`ID` WHERE `" . $bookings."`.booking_contacts!='' AND `" . $bookings."`.ID=" . $booking_id;
		$result = $wpdb->get_results( $sql);
		
		$filter_type = '';
		if ( ! empty( $result ) ) {
			$booking=( array ) $result[0];
			unset( $booking['session_details'] ); /* No need this information so kill it!!*/
			$contact = (array)json_decode( $booking['booking_contacts'] );
			if ( $type == 'user' ) {
				 $email = $contact['email'];
			 } elseif( $type == 'admin' ) {
				 $email = simontaxi_get_option('vehicle_bookings_admin_email', get_option( 'admin_email' ) );
				 // $email = simontaxi_get_option( 'vehicle_booking_confirm_from_address', get_option( 'admin_email' ) );
			 } else {
				 $email = apply_filters( 'simontaxi_get_email', $booking_id );
			 }
			 			 
			$booking_type = $booking['booking_type'];
			$vehicle_id = $booking['selected_vehicle'];
			$user_type = $type;
			if ( '' !== $email ) {
				$blog_title = get_bloginfo( 'name' );
				if ( $status == 'confirmed' ) {
					/**
					 * @since 2.0.9
					 */
					$default_template = __( 'booking-confirmed', 'simontaxi' );
					if ( ! empty( $pages['booking_confirmed'] ) ) {
						$default_template = get_the_title( $pages['booking_confirmed'] );
						if ( ! empty( $default_template ) ) {
							$default_template = __( $default_template, 'simontaxi' );
						}
					}
					$posttitle = ( $email_template ) ? $email_template : $default_template;
					/**
					 * @since 2.0.9
					 */
					$filter_type = str_replace( '-', '_', $posttitle );
					/**
					 * In this way we can send different emails for different users. For this we need to create separate files for each user.
					 *
					 * suppose if template is "booking-confirmed" if you want to send different email template to driver rather than regular template, then you need to create a post with name "booking-confirmed-driver"
					 *
					 * Hierarchy
					 * 1. template-user_type-booking_type-vehicle_id (Eg: booking-confirmed-driver-p2p-926)
					 * 2. template-user_type-booking_type (Eg: booking-confirmed-driver-p2p)
					 * 3. template-user_type (Eg: booking-confirmed-driver)
					 * 4. template-booking_type (Eg: booking-confirmed-p2p)
					 * 5. template (Eg: booking-confirmed)
					 *
					 * @since 2.0.9
					 */
					if ( ! empty( $vehicle_id ) && simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id ) ) {
						$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type ) ) {
						$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type ) ) {
						$posttitle = $posttitle . '-' . $user_type;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $booking_type ) ) {
						$posttitle = $posttitle . '-' . $booking_type;
					}
					$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $posttitle . "'  AND post_status='publish' AND post_type='emailtemplate'" );
					$getpost= get_post( $postid );

					if ( ! empty( $getpost ) ) {
						/**
						 * @since 2.0.8
						 *
						 * But my site now has two languages and if someone orders in a different language, he gets a notification in Polish.
						 */
						if ( 'file' == simontaxi_get_option( 'vehicle_booking_confirm_email_body', 'emailtemplate' ) ) {
							ob_start();
							$template = '/templates/emailtemplates/' . $posttitle . '.php';
							/**
							 * In this way we can send different emails for different users. For this we need to create separate files for each user.
							 *
							 * suppose if template is "booking-confirmed" if you want to send different email template to driver rather than regular template, then you need to create a file with name "booking-confirmed-driver.php"
							 *
							 * Hierarchy - "wp-content/plugins/vehicle-booking/templates"
							 * 1. template-user_type-booking_type-vehicle_id (Eg: booking-confirmed-driver-p2p-965.php)
							 * 2. template-user_type-booking_type (Eg: booking-confirmed-driver-p2p.php)
							 * 3. template-user_type (Eg: booking-confirmed-driver.php)
							 * 4. template-booking_type (Eg: booking-confirmed-p2p.php)
							 * 5. template (Eg: booking-confirmed.php)
							 *
							 * @since 2.0.9
							 */
							if ( ! empty( $vehicle_id ) && simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php' ) ) {
								$template = '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php';
							} elseif ( simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php' ) ) {
								$template = '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php';
							} elseif ( simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '.php' ) ) {
								$template = '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '.php';
							} elseif ( simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $booking_type . '.php' ) ) {
								$template = '/templates/emailtemplates/' . $posttitle . '-' . $booking_type . '.php';
							}
							if ( simontaxi_is_template_customized( $template ) ) {
								include_once( simontaxi_get_theme_template_dir_name() . $template );
							} else {
								include_once( SIMONTAXI_PLUGIN_PATH . $template );
							}
							$template = ob_get_clean();
						} else {
							$template= $getpost->post_content;
						}
						
						// $template= $getpost->post_content;
						$pattern = array( 
							'/\{BLOG_TITLE\}/',
							'/\{DATE\}/',
							'/\{BOOKING_REF\}/' ,
							'/\{JOURNEY_TYPE\}/' ,
							'/\{PICKUP_LOCATION\}/',
							'/\{DROP_LOCATION\}/',
							'/\{PICKUP_DATE\}/',
							'/\{PICKUP_TIME\}/',
							'/\{CONTACT_NAME\}/',
							'/\{CONTACT_MOBILE\}/',
							'/\{CONTACT_EMAIL\}/',
							'/\{BOOKING_STATUS\}/',
							'/\{BOOKING_STATUS_UPDATED\}/',
							'/\{REASON\}/',
							
							'/\{AMOUNT\}/',
							'/\{PAID\}/',
							'/\{PAYMENT_STATUS\}/',
							'/\{PAYMENT_METHOD\}/',
						);
					
						/**
						 * @since 2.0.8
						 */
						$pattern = apply_filters('simontaxi_send_status_change_email_confirmed_pattern', $pattern, $booking_id, $status, $type, $email_template);

						$full_name = '-';
						 if ( isset( $contact['full_name'] ) ) {
							 $full_name = $contact['full_name'];
						 }elseif ( isset( $contact['first_name'] ) ) {
							$full_name = $contact['first_name'];
							if ( isset( $contact['last_name'] ) && $contact['last_name'] != '' ) {
								$full_name .= ' ' . $contact['last_name'];
							}
						}
						 $mobile = '-';
						 if ( isset( $contact['mobile'] ) ) {
							 $mobile = $contact['mobile'];
						 }
						 
						 if ( '-' !== $mobile && '' !== $mobile && ! empty( $contact['mobile_countrycode'] ) ) {
							$mobile_countrycode = explode( '_', $contact['mobile_countrycode']);
							/**
							 * @since 2.0.2
							 * Change Description: 
							 * PHP 5.3 doesn't support the [] array syntax. Only PHP 5.4 and later does. For older PHP, you need to use array() instead of [].
							 */
							if ( ! empty( $mobile_countrycode ) ) {
								$mobile_countrycode = '+' . $mobile_countrycode[0] . ' '; 
							}
							$mobile = $mobile_countrycode . $mobile;
						}
						
						 $message = '';
						 if ( isset( $contact['reason_message'] ) ) {
							 $message = $contact['reason_message'];
						 }
						 
						 $amount_payable = apply_filters( 'simontaxi_amount_payable', $booking['amount_payable'], $booking, $booking_id, $status, $type, $email_template );
						 $amount_total_replace = simontaxi_get_currency( $amount_payable );
						 /**
						 * Let us find if the amount contains '$' at its starting. If it contains '$' it comes backreference and trying to find for PHP varaible!!
						 * So let us add a '\' before the '$'
						 * Specially this will be the case with currency Dollor ($) with left placement (Settings->currency->Currency Position)
						 * @see http://php.net/manual/en/function.preg-replace.php#106263
						 */
						$first_character = substr( $amount_total_replace, 0, 1 );
						if ( '$' === $first_character ) {
							$amount_total_replace = '\\' . $amount_total_replace;
						}
						
						$paid = apply_filters( 'simontaxi_amount_paid', $booking['amount_paid'], $booking, $booking_id, $status, $type, $email_template );
						$paid_replace = simontaxi_get_currency( $paid );
						$first_character = substr( $paid_replace, 0, 1 );
						if ( '$' === $first_character ) {
							$paid_replace = '\\' . $paid_replace;
						}
	
						$selected_payment_method = $booking['payment_method'];
						 $replacement = array(
							$blog_title,
							simontaxi_date_format( date_i18n( 'Y-m-d',time() ) ),
							$booking['booking_ref'],
							strtoupper( str_replace( '_', ' ', $booking['journey_type'] ) ),
							$booking['pickup_location'],
							$booking['drop_location'],
							simontaxi_date_format( $booking['pickup_date'] ),
							simontaxi_get_time_display_format( $booking['pickup_time'] ),
							$full_name,
							$mobile,
							$contact['email'],
							strtoupper( $booking['status'] ),
							simontaxi_date_format( $booking['status_updated'] ),
							$booking['reason_message'],
							
							$amount_total_replace,
							$paid_replace,
							ucfirst( $booking['payment_status'] ),
							$selected_payment_method,
						);
						
						/**
						 * @since 2.0.8
						 */
						$replacement = apply_filters('simontaxi_send_status_change_email_confirmed_replacement',$replacement, $booking_id, $status, $type, $email_template );
						
						$template =  preg_replace( $pattern, $replacement, $template);
						
						$subject = simontaxi_get_option( 'vehicle_booking_confirm_email_subject', 'Booking Confirmed' );
						$from_email = simontaxi_get_option( 'vehicle_booking_confirm_from_address', get_option( 'admin_email' ) );
						$from_name = simontaxi_get_option( 'vehicle_booking_confirm_from_name', get_bloginfo() );
						$headers = 'From: ' . $from_name. ' <' . $from_email. '>' . "\r\n";
						/**
						 * Let us change the email type based on admin settings
						*/
						if ( simontaxi_get_option( 'vehicle_booking_confirm_email_type', 'html' ) == 'html' ) {
							add_filter( 'wp_mail_content_type', 'simontaxi_mail_html_type' );
						} else {
							add_filter( 'wp_mail_content_type', 'simontaxi_mail_text_type' );
						}
				
						/**
						 * @since 2.0.9
						 */
						$additional_top = apply_filters( "{$filter_type}_mail_additional_top", '' );
						$template = str_replace( "{{$filter_type}_mail_additional_top}", $additional_top, $template );
						
						$additional_bottom = apply_filters( "{$filter_type}_mail_additional_bottom", '' );
						$template = str_replace( "{{$filter_type}_mail_additional_bottom}", $additional_bottom, $template );
						
						$template = apply_filters( "simontaxi_flt_booking_confirmed_mail_body", $booking_id, $status, $type, $email_template );
						
						wp_mail( $email, $subject, $template, $headers);				

						/**
						 * Reset content-type to avoid conflicts -- https://core.trac.wordpress.org/ticket/23578
						*/
						if ( simontaxi_get_option( 'vehicle_booking_confirm_email_type', 'html' ) == 'html' ) {
							remove_filter( 'wp_mail_content_type', 'simontaxi_mail_html_type' );
						} else {
							remove_filter( 'wp_mail_content_type', 'simontaxi_mail_text_type' );
						}
					}
				} elseif ( $status == 'cancelled' ) {
					/**
					 * @since 2.0.9
					 */
					$default_template = __( 'booking-cancel', 'simontaxi' );
					if ( ! empty( $pages['booking_cancel'] ) ) {
						$default_template = get_the_title( $pages['booking_cancel'] );
						if ( ! empty( $default_template ) ) {
							$default_template = __( $default_template, 'simontaxi' );
						}
					}
					$posttitle = ( $email_template ) ? $email_template : $default_template;
					/**
					 * @since 2.0.9
					 */
					$filter_type = str_replace( '-', '_', $posttitle );
					/**
					 * In this way we can send different emails for different users. For this we need to create separate files for each user.
					 *
					 * suppose if template is "booking-cancel" if you want to send different email template to driver rather than regular template, then you need to create a post with name "booking-cancel-driver"
					 *
					 * Hierarchy
					 * 1. template-user_type-booking_type-vehicle_id (Eg: booking-cancel-driver-p2p-926)
					 * 2. template-user_type-booking_type (Eg: booking-cancel-driver-p2p)
					 * 3. template-user_type (Eg: booking-cancel-driver)
					 * 4. template-booking_type (Eg: booking-cancel-p2p)
					 * 5. template (Eg: booking-cancel)
					 *
					 * @since 2.0.9
					 */
					if ( ! empty( $vehicle_id ) && simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id ) ) {
						$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type ) ) {
						$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type ) ) {
						$posttitle = $posttitle . '-' . $user_type;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $booking_type ) ) {
						$posttitle = $posttitle . '-' . $booking_type;
					}
					$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $posttitle . "'  AND post_status='publish' AND post_type='emailtemplate'" );
					$getpost= get_post( $postid );
					if ( !empty( $getpost) ) {
						/**
						 * @since 2.0.8
						 *
						 * But my site now has two languages and if someone orders in a different language, he gets a notification in Polish.
						 */
						if ( 'file' == simontaxi_get_option( 'vehicle_booking_cancel_email_body', 'emailtemplate' ) ) {
							ob_start();
							$template = '/templates/emailtemplates/' . $posttitle . '.php';
							/**
							 * In this way we can send different emails for different users. For this we need to create separate files for each user.
							 *
							 * suppose if template is "booking-cancel" if you want to send different email template to driver rather than regular template, then you need to create a file with name "booking-cancel-driver.php"
							 *
							 * Hierarchy - "wp-content/plugins/vehicle-booking/templates"
							 * 1. template-user_type-booking_type-vehicle_id (Eg: booking-cancel-driver-p2p-965.php)
							 * 2. template-user_type-booking_type (Eg: booking-cancel-driver-p2p.php)
							 * 3. template-user_type (Eg: booking-cancel-driver.php)
							 * 4. template-booking_type (Eg: booking-cancel-p2p.php)
							 * 5. template (Eg: booking-cancel.php)
							 *
							 * @since 2.0.9
							 */
							if ( ! empty( $vehicle_id ) && simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php' ) ) {
								$template = '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php';
							} elseif ( simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php' ) ) {
								$template = '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php';
							} elseif ( simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '.php' ) ) {
								$template = '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '.php';
							} elseif ( simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $booking_type . '.php' ) ) {
								$template = '/templates/emailtemplates/' . $posttitle . '-' . $booking_type . '.php';
							}
							if ( simontaxi_is_template_customized( $template ) ) {
								include_once( simontaxi_get_theme_template_dir_name() . $template );
							} else {
								include_once( SIMONTAXI_PLUGIN_PATH . $template );
							}
							$template = ob_get_clean();
						} else {
							$template= $getpost->post_content;
						}
						
						// $template= $getpost->post_content;
						$pattern = array( 
							'/\{BLOG_TITLE\}/',
							'/\{DATE\}/',
							'/\{BOOKING_REF\}/',
							'/\{JOURNEY_TYPE\}/',
							'/\{PICKUP_LOCATION\}/',
							'/\{DROP_LOCATION\}/',
							'/\{PICKUP_DATE\}/',
							'/\{PICKUP_TIME\}/',
							'/\{CONTACT_NAME\}/',
							'/\{CONTACT_MOBILE\}/',
							'/\{CONTACT_EMAIL\}/',
							'/\{BOOKING_STATUS\}/',
							'/\{BOOKING_STATUS_UPDATED\}/',
							'/\{REASON\}/',
							
							'/\{AMOUNT\}/',
							'/\{PAID\}/',
							'/\{PAYMENT_STATUS\}/',
							'/\{PAYMENT_METHOD\}/',
						);
						/**
						 * @since 2.0.8
						 */
						$pattern = apply_filters('simontaxi_send_status_change_email_cancelled_pattern', $pattern);
						$message = '';
						 if ( isset( $contact['reason_message'] ) ) {
							 $message = $contact['reason_message'];
						 }
						 $full_name = '';
						 if ( isset( $contact['full_name'] ) ) {
							 $full_name = $contact['full_name'];
						 }elseif ( isset( $contact['first_name'] ) ) {
							$full_name = $contact['first_name'];
							if ( isset( $contact['last_name'] ) && $contact['last_name'] != '' ) {
								$full_name .= ' ' . $contact['last_name'];
							}
						}
						$mobile = '-';
						 if ( isset( $contact['mobile'] ) ) {
							 $mobile = $contact['mobile'];
						 }
						 
						 if ( '-' !== $mobile && '' !== $mobile && ! empty( $contact['mobile_countrycode'] ) ) {
							$mobile_countrycode = explode( '_', $contact['mobile_countrycode']);
							/**
							 * @since 2.0.2
							 * Change Description: 
							 * PHP 5.3 doesn't support the [] array syntax. Only PHP 5.4 and later does. For older PHP, you need to use array() instead of [].
							 */
							if ( ! empty( $mobile_countrycode ) ) {
								$mobile_countrycode = '+' . $mobile_countrycode[0] . ' '; 
							}
							$mobile = $mobile_countrycode . $mobile;
						}
						
						$amount_total_replace = simontaxi_get_currency( $booking['amount_payable'] );
						 /**
						 * Let us find if the amount contains '$' at its starting. If it contains '$' it comes backreference and trying to find for PHP varaible!!
						 * So let us add a '\' before the '$'
						 * Specially this will be the case with currency Dollor ($) with left placement (Settings->currency->Currency Position)
						 * @see http://php.net/manual/en/function.preg-replace.php#106263
						 */
						$first_character = substr( $amount_total_replace, 0, 1 );
						if ( '$' === $first_character ) {
							$amount_total_replace = '\\' . $amount_total_replace;
						}
						
						$paid = $booking['amount_paid'];
						$paid_replace = simontaxi_get_currency( $paid );
						$first_character = substr( $paid_replace, 0, 1 );
						if ( '$' === $first_character ) {
							$paid_replace = '\\' . $paid_replace;
						}
						$selected_payment_method = $booking['payment_method'];
						
						$replacement = array(
							$blog_title,
							simontaxi_date_format( date_i18n( 'Y-m-d',time() ) ),
							$booking['booking_ref'],
							strtoupper( str_replace( '_', ' ', $booking['journey_type'] ) ),
							$booking['pickup_location'],
							$booking['drop_location'],
							simontaxi_date_format( $booking['pickup_date'] ),
							simontaxi_get_time_display_format( $booking['pickup_time'] ),
							$full_name,
							$mobile,
							$contact['email'],
							strtoupper( $booking['status'] ),
							simontaxi_date_format( $booking['status_updated'] ),
							$booking['reason_message'],
							
							$amount_total_replace,
							$paid_replace,
							ucfirst( $booking['payment_status'] ),
							$selected_payment_method,
						);
						/**
						 * @since 2.0.8
						 */
						$replacement = apply_filters('simontaxi_send_status_change_email_cancelled_replacement', $replacement);

						$template =  preg_replace( $pattern, $replacement, $template);

						$subject = simontaxi_get_option( 'vehicle_booking_cancel_email_subject', 'Booking Cancelled' );
						$from_email = simontaxi_get_option( 'vehicle_booking_cancel_from_address', get_option( 'admin_email' ) );
						$from_name = simontaxi_get_option( 'vehicle_booking_cancel_from_name', get_bloginfo() );
						$headers = 'From: ' . $from_name. ' <' . $from_email. '>' . "\r\n";
						/**
						 * Let us change the email type based on admin settings
						*/
						if ( simontaxi_get_option( 'vehicle_booking_cancel_email_type', 'html' ) == 'html' ) {
							add_filter( 'wp_mail_content_type', 'simontaxi_mail_html_type' );
						} else {
							add_filter( 'wp_mail_content_type', 'simontaxi_mail_text_type' );
						}
						
						/**
						 * @since 2.0.9
						 */
						$additional_top = apply_filters( "{$filter_type}_mail_additional_top", '' );
						$template = str_replace( "{{$filter_type}_mail_additional_top}", $additional_top, $template );
						
						$additional_bottom = apply_filters( "{$filter_type}_mail_additional_bottom", '' );
						$template = str_replace( "{{$filter_type}_mail_additional_bottom}", $additional_bottom, $template );
						
						$template = apply_filters( "simontaxi_flt_booking_cancelled_mail_body", $booking_id, $status, $type, $email_template );
						
						wp_mail( $email, $subject, $template, $headers);

						/**
						 * Reset content-type to avoid conflicts -- https://core.trac.wordpress.org/ticket/23578
						*/
						if ( simontaxi_get_option( 'vehicle_booking_cancel_email_type', 'html' ) == 'html' ) {
							remove_filter( 'wp_mail_content_type', 'simontaxi_mail_html_type' );
						} else {
							remove_filter( 'wp_mail_content_type', 'simontaxi_mail_text_type' );
						}
					}
				} elseif ( $status == 'onride' ) {
					/**
					 * @since 2.0.9
					 */
					$default_template = __( 'ride-start', 'simontaxi' );
					if ( ! empty( $pages['ride_start'] ) ) {
						$default_template = get_the_title( $pages['ride_start'] );
						if ( ! empty( $default_template ) ) {
							$default_template = __( $default_template, 'simontaxi' );
						}
					}
					$posttitle = ( $email_template ) ? $email_template : $default_template;
					/**
					 * @since 2.0.9
					 */
					$filter_type = str_replace( '-', '_', $posttitle );
					/**
					 * In this way we can send different emails for different users. For this we need to create separate files for each user.
					 *
					 * suppose if template is "ride-start" if you want to send different email template to driver rather than regular template, then you need to create a post with name "ride-start-driver"
					 *
					 *
					 * Hierarchy
					 * 1. template-user_type-booking_type-vehicle_id (Eg: ride-start-driver-p2p-926)
					 * 2. template-user_type-booking_type (Eg: ride-start-driver-p2p)
					 * 3. template-user_type (Eg: ride-start-driver)
					 * 4. template-booking_type (Eg: ride-start-p2p)
					 * 5. template (Eg: ride-start)
					 *
					 * @since 2.0.9
					 */
					if ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id ) ) {
						$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type ) ) {
						$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type ) ) {
						$posttitle = $posttitle . '-' . $user_type;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $booking_type ) ) {
						$posttitle = $posttitle . '-' . $booking_type;
					}
					$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $posttitle . "'  AND post_status='publish' AND post_type='emailtemplate'" );
					$getpost= get_post( $postid );

					if ( ! empty( $getpost ) ) {
						/**
						 * @since 2.0.9
						 *
						 * But my site now has two languages and if someone orders in a different language, he gets a notification in Polish.
						 */
						if ( 'file' == simontaxi_get_option( 'vehicle_booking_startride_email_body', 'emailtemplate' ) ) {
							ob_start();
							$template = '/templates/emailtemplates/' . $posttitle . '.php';
							/**
							 * In this way we can send different emails for different users. For this we need to create separate files for each user.
							 *
							 * suppose if template is "ride-start" if you want to send different email template to driver rather than regular template, then you need to create a file with name "ride-start-driver.php"
							 *
							 * Hierarchy - "wp-content/plugins/vehicle-booking/templates"
							 * 1. template-user_type-booking_type-vehicle_id (Eg: ride-start-driver-p2p-963.php)
							 * 2. template-user_type-booking_type (Eg: ride-start-driver-p2p.php)
							 * 3. template-user_type (Eg: ride-start-driver.php)
							 * 4. template-booking_type (Eg: ride-start-p2p.php)
							 * 5. template (Eg: ride-start.php)
							 *
							 * @since 2.0.9
							 */
							if ( simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php' ) ) {
								$template = '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php';
							} elseif ( simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php' ) ) {
								$template = '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php';
							} elseif ( simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '.php' ) ) {
								$template = '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '.php';
							} elseif ( simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $booking_type . '.php' ) ) {
								$template = '/templates/emailtemplates/' . $posttitle . '-' . $booking_type . '.php';
							}
							if ( simontaxi_is_template_customized( $template ) ) {
								include_once( simontaxi_get_theme_template_dir_name() . $template );
							} else {
								include_once( SIMONTAXI_PLUGIN_PATH . $template );
							}
							$template = ob_get_clean();
						} else {
							$template= $getpost->post_content;
						}
						
						// $template= $getpost->post_content;
						$pattern = array( 
							'/\{BLOG_TITLE\}/',
							'/\{DATE\}/',
							'/\{BOOKING_REF\}/' ,
							'/\{JOURNEY_TYPE\}/' ,
							'/\{PICKUP_LOCATION\}/',
							'/\{DROP_LOCATION\}/',
							'/\{PICKUP_DATE\}/',
							'/\{PICKUP_TIME\}/',
							'/\{CONTACT_NAME\}/',
							'/\{CONTACT_MOBILE\}/',
							'/\{CONTACT_EMAIL\}/',
							'/\{BOOKING_STATUS\}/',
							'/\{BOOKING_STATUS_UPDATED\}/',
							'/\{REASON\}/',
							
							'/\{AMOUNT\}/',
							'/\{PAID\}/',
							'/\{PAYMENT_STATUS\}/',
							'/\{PAYMENT_METHOD\}/',
						);
						
						/**
						 * @since 2.0.8
						 */
						$pattern = apply_filters('simontaxi_send_status_change_email_startride_pattern', $pattern, $booking_id, $status, $type, $email_template);

						$full_name = '-';
						 if ( isset( $contact['full_name'] ) ) {
							 $full_name = $contact['full_name'];
						 }elseif ( isset( $contact['first_name'] ) ) {
							$full_name = $contact['first_name'];
							if ( isset( $contact['last_name'] ) && $contact['last_name'] != '' ) {
								$full_name .= ' ' . $contact['last_name'];
							}
						}
						 $mobile = '-';
						 if ( isset( $contact['mobile'] ) ) {
							 $mobile = $contact['mobile'];
						 }
						 
						 if ( '-' !== $mobile && '' !== $mobile && ! empty( $contact['mobile_countrycode'] ) ) {
							$mobile_countrycode = explode( '_', $contact['mobile_countrycode']);
							/**
							 * @since 2.0.2
							 * Change Description: 
							 * PHP 5.3 doesn't support the [] array syntax. Only PHP 5.4 and later does. For older PHP, you need to use array() instead of [].
							 */
							if ( ! empty( $mobile_countrycode ) ) {
								$mobile_countrycode = '+' . $mobile_countrycode[0] . ' '; 
							}
							$mobile = $mobile_countrycode . $mobile;
						}
						
						 $message = '';
						 if ( isset( $contact['reason_message'] ) ) {
							 $message = $contact['reason_message'];
						 }
						 
						 $amount_total_replace = simontaxi_get_currency( $booking['amount_payable'] );
						 /**
						 * Let us find if the amount contains '$' at its starting. If it contains '$' it comes backreference and trying to find for PHP varaible!!
						 * So let us add a '\' before the '$'
						 * Specially this will be the case with currency Dollor ($) with left placement (Settings->currency->Currency Position)
						 * @see http://php.net/manual/en/function.preg-replace.php#106263
						 */
						$first_character = substr( $amount_total_replace, 0, 1 );
						if ( '$' === $first_character ) {
							$amount_total_replace = '\\' . $amount_total_replace;
						}
						
						$paid = $booking['amount_paid'];
						$paid_replace = simontaxi_get_currency( $paid );
						$first_character = substr( $paid_replace, 0, 1 );
						if ( '$' === $first_character ) {
							$paid_replace = '\\' . $paid_replace;
						}
						
						$selected_payment_method = $booking['payment_method'];
						
						 $replacement = array(
							$blog_title,
							simontaxi_date_format( date_i18n( 'Y-m-d',time() ) ),
							$booking['booking_ref'],
							strtoupper( str_replace( '_', ' ', $booking['journey_type'] ) ),
							$booking['pickup_location'],
							$booking['drop_location'],
							simontaxi_date_format( $booking['pickup_date'] ),
							simontaxi_get_time_display_format( $booking['pickup_time'] ),
							$full_name,
							$mobile,
							$contact['email'],
							strtoupper( $booking['status'] ),
							simontaxi_date_format( $booking['status_updated'] ),
							$booking['reason_message'],
							
							$amount_total_replace,
							$paid_replace,
							ucfirst( $booking['payment_status'] ),
							$selected_payment_method,
						);
						/**
						 * @since 2.0.9
						 */
						$replacement = apply_filters('simontaxi_send_status_change_email_startride_replacement',$replacement, $booking_id, $status, $type, $email_template );
						$template =  preg_replace( $pattern, $replacement, $template);
						
						
						
						$subject = simontaxi_get_option( 'vehicle_booking_startride_email_subject', 'Your Ride Start Now' );
						$from_email = simontaxi_get_option( 'vehicle_booking_startride_from_address', get_option( 'admin_email' ) );
						$from_name = simontaxi_get_option( 'vehicle_booking_startride_from_name', get_bloginfo() );
						$headers = 'From: ' . $from_name. ' <' . $from_email. '>' . "\r\n";
						/**
						 * Let us change the email type based on admin settings
						*/
						if ( simontaxi_get_option( 'vehicle_booking_startride_email_type', 'html' ) == 'html' ) {
							add_filter( 'wp_mail_content_type', 'simontaxi_mail_html_type' );
						} else {
							add_filter( 'wp_mail_content_type', 'simontaxi_mail_text_type' );
						}
						
						/**
						 * @since 2.0.9
						 */
						$additional_top = apply_filters( "{$filter_type}_mail_additional_top", '' );
						$template = str_replace( "{{$filter_type}_mail_additional_top}", $additional_top, $template );
						
						$additional_bottom = apply_filters( "{$filter_type}_mail_additional_bottom", '' );
						$template = str_replace( "{{$filter_type}_mail_additional_bottom}", $additional_bottom, $template );
						
						$template = apply_filters( "simontaxi_flt_booking_onride_mail_body", $booking_id, $status, $type, $email_template );
						
						wp_mail( $email, $subject, $template, $headers);				

						/**
						 * Reset content-type to avoid conflicts -- https://core.trac.wordpress.org/ticket/23578
						*/
						if ( simontaxi_get_option( 'vehicle_booking_startride_email_type', 'html' ) == 'html' ) {
							remove_filter( 'wp_mail_content_type', 'simontaxi_mail_html_type' );
						} else {
							remove_filter( 'wp_mail_content_type', 'simontaxi_mail_text_type' );
						}
					}
				} elseif ( $status == 'success' ) {
					/**
					 * @since 2.0.9
					 */
					$default_template = __( 'ride-completed', 'simontaxi' );
					if ( ! empty( $pages['ride_completed'] ) ) {
						$default_template = get_the_title( $pages['ride_completed'] );
						if ( ! empty( $default_template ) ) {
							$default_template = __( $default_template, 'simontaxi' );
						}
					}
					$posttitle = ( $email_template ) ? $email_template : $default_template;
					/**
					 * @since 2.0.9
					 */
					$filter_type = str_replace( '-', '_', $posttitle );
					/**
					 * In this way we can send different emails for different users. For this we need to create separate files for each user.
					 *
					 * suppose if template is "ride-completed" if you want to send different email template to driver rather than regular template, then you need to create a post with name "ride-completed-driver"
					 *
					 * Hierarchy
					 * 1. template-user_type-booking_type-vehicle_id (Eg: ride-completed-driver-p2p-958)
					 * 2. template-user_type-booking_type (Eg: ride-completed-driver-p2p)
					 * 3. template-user_type (Eg: ride-completed-driver)
					 * 4. template-booking_type (Eg: ride-completed-p2p)
					 * 5. template (Eg: ride-completed)
					 *
					 * @since 2.0.9
					 */
					if ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id ) ) {
						$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type ) ) {
						$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type ) ) {
						$posttitle = $posttitle . '-' . $user_type;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $booking_type ) ) {
						$posttitle = $posttitle . '-' . $booking_type;
					}
					$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $posttitle . "' AND post_status='publish' AND post_type='emailtemplate'" );
					$getpost= get_post( $postid );

					if ( ! empty( $getpost ) ) {
						/**
						 * @since 2.0.9
						 *
						 * But my site now has two languages and if someone orders in a different language, he gets a notification in Polish.
						 */
						if ( 'file' == simontaxi_get_option( 'vehicle_booking_completed_email_body', 'emailtemplate' ) ) {
							ob_start();
							$template = '/templates/emailtemplates/' . $posttitle . '.php';
							/**
							 * In this way we can send different emails for different users. For this we need to create separate files for each user.
							 *
							 * suppose if template is "ride-completed" if you want to send different email template to driver rather than regular template, then you need to create a file with name "ride-completed-driver.php"
							 *
							 * Hierarchy - "wp-content/plugins/vehicle-booking/templates"
							 * 1. template-user_type-booking_type-vehicle_id (Eg: ride-completed-driver-p2p-869.php)
							 * 2. template-user_type-booking_type (Eg: ride-completed-driver-p2p.php)
							 * 3. template-user_type (Eg: ride-completed-driver.php)
							 * 4. template-booking_type (Eg: ride-completed-p2p.php)
							 * 5. template (Eg: ride-completed.php)
							 *
							 * @since 2.0.9
							 */
							if ( simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php' ) ) {
								$template = '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php';
							} elseif ( simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php' ) ) {
								$template = '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php';
							} elseif ( simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '.php' ) ) {
								$template = '/templates/emailtemplates/' . $posttitle . '-' . $user_type . '.php';
							} elseif ( simontaxi_is_file_exists( '/templates/emailtemplates/' . $posttitle . '-' . $booking_type . '.php' ) ) {
								$template = '/templates/emailtemplates/' . $posttitle . '-' . $booking_type . '.php';
							}
							if ( simontaxi_is_template_customized( $template ) ) {
								include_once( simontaxi_get_theme_template_dir_name() . $template );
							} else {
								include_once( SIMONTAXI_PLUGIN_PATH . $template );
							}
							$template = ob_get_clean();
						} else {
							$template= $getpost->post_content;
						}
						
						// $template= $getpost->post_content;
						$pattern = array( 
							'/\{BLOG_TITLE\}/',
							'/\{DATE\}/',
							'/\{BOOKING_REF\}/' ,
							'/\{JOURNEY_TYPE\}/' ,
							'/\{PICKUP_LOCATION\}/',
							'/\{DROP_LOCATION\}/',
							'/\{PICKUP_DATE\}/',
							'/\{PICKUP_TIME\}/',
							'/\{CONTACT_NAME\}/',
							'/\{CONTACT_MOBILE\}/',
							'/\{CONTACT_EMAIL\}/',
							'/\{BOOKING_STATUS\}/',
							'/\{BOOKING_STATUS_UPDATED\}/',
							'/\{REASON\}/',
							
							'/\{AMOUNT\}/',
							'/\{PAID\}/',
							'/\{PAYMENT_STATUS\}/',
							'/\{PAYMENT_METHOD\}/',
						);
						
						/**
						 * @since 2.0.8
						 */
						$pattern = apply_filters('simontaxi_send_status_change_email_completed_pattern', $pattern, $booking_id, $status, $type, $email_template);

						$full_name = '-';
						 if ( isset( $contact['full_name'] ) ) {
							 $full_name = $contact['full_name'];
						 }elseif ( isset( $contact['first_name'] ) ) {
							$full_name = $contact['first_name'];
							if ( isset( $contact['last_name'] ) && $contact['last_name'] != '' ) {
								$full_name .= ' ' . $contact['last_name'];
							}
						}
						 $mobile = '-';
						 if ( isset( $contact['mobile'] ) ) {
							 $mobile = $contact['mobile'];
						 }
						 
						 if ( '-' !== $mobile && '' !== $mobile && ! empty( $contact['mobile_countrycode'] ) ) {
							$mobile_countrycode = explode( '_', $contact['mobile_countrycode']);
							/**
							 * @since 2.0.2
							 * Change Description: 
							 * PHP 5.3 doesn't support the [] array syntax. Only PHP 5.4 and later does. For older PHP, you need to use array() instead of [].
							 */
							if ( ! empty( $mobile_countrycode ) ) {
								$mobile_countrycode = '+' . $mobile_countrycode[0] . ' '; 
							}
							$mobile = $mobile_countrycode . $mobile;
						}
						
						 $message = '';
						 if ( isset( $contact['reason_message'] ) ) {
							 $message = $contact['reason_message'];
						 }
						 
						 $amount_total_replace = simontaxi_get_currency( $booking['amount_payable'] );
						 /**
						 * Let us find if the amount contains '$' at its starting. If it contains '$' it comes backreference and trying to find for PHP varaible!!
						 * So let us add a '\' before the '$'
						 * Specially this will be the case with currency Dollor ($) with left placement (Settings->currency->Currency Position)
						 * @see http://php.net/manual/en/function.preg-replace.php#106263
						 */
						$first_character = substr( $amount_total_replace, 0, 1 );
						if ( '$' === $first_character ) {
							$amount_total_replace = '\\' . $amount_total_replace;
						}
						
						$paid = $booking['amount_paid'];
						$paid_replace = simontaxi_get_currency( $paid );
						$first_character = substr( $paid_replace, 0, 1 );
						if ( '$' === $first_character ) {
							$paid_replace = '\\' . $paid_replace;
						}
						$selected_payment_method = $booking['payment_method'];
						
						 $replacement = array(
							$blog_title,
							simontaxi_date_format( date_i18n( 'Y-m-d',time() ) ),
							$booking['booking_ref'],
							strtoupper( str_replace( '_', ' ', $booking['journey_type'] ) ),
							$booking['pickup_location'],
							$booking['drop_location'],
							simontaxi_date_format( $booking['pickup_date'] ),
							simontaxi_get_time_display_format( $booking['pickup_time'] ),
							$full_name,
							$mobile,
							$contact['email'],
							strtoupper( $booking['status'] ),
							simontaxi_date_format( $booking['status_updated'] ),
							$booking['reason_message'],
							
							$amount_total_replace,
							$paid_replace,
							ucfirst( $booking['payment_status'] ),
							$selected_payment_method,
						);
						/**
						 * @since 2.0.8
						 */
						$replacement = apply_filters('simontaxi_send_status_change_email_completed_replacement',$replacement, $booking_id, $status, $type, $email_template );
						$template =  preg_replace( $pattern, $replacement, $template );
						
						/**
						 * @since 2.0.9
						 */
						$additional_top = apply_filters( 'booking_success_mail_additional_top', '' );
						$template = str_replace( '{booking_success_mail_additional_top}', $additional_top, $template );
						
						$additional_bottom = apply_filters( 'booking_success_mail_additional_bottom', '' );
						$template = str_replace( '{booking_success_mail_additional_bottom}', $additional_bottom, $template );
						
						$template = apply_filters( "simontaxi_flt_booking_completed_mail_body", $booking_id, $status, $type, $email_template );
						
						$subject = simontaxi_get_option( 'vehicle_booking_completed_email_subject', 'Congratulations Your Ride Completed' );
						$from_email = simontaxi_get_option( 'vehicle_booking_completed_from_address', get_option( 'admin_email' ) );
						$from_name = simontaxi_get_option( 'vehicle_booking_completed_from_name', get_bloginfo() );
						$headers = 'From: ' . $from_name. ' <' . $from_email. '>' . "\r\n";
						/**
						 * Let us change the email type based on admin settings
						*/
						if ( simontaxi_get_option( 'vehicle_booking_completed_email_type', 'html' ) == 'html' ) {
							add_filter( 'wp_mail_content_type', 'simontaxi_mail_html_type' );
						} else {
							add_filter( 'wp_mail_content_type', 'simontaxi_mail_text_type' );
						}
						
						/**
						 * @since 2.0.9
						 */
						$additional_top = apply_filters( "{$filter_type}_mail_additional_top", '' );
						$template = str_replace( "{{$filter_type}_mail_additional_top}", $additional_top, $template );
						
						$additional_bottom = apply_filters( "{$filter_type}_mail_additional_bottom", '' );
						$template = str_replace( "{{$filter_type}_mail_additional_bottom}", $additional_bottom, $template );
						
						wp_mail( $email, $subject, $template, $headers);

						/**
						 * Reset content-type to avoid conflicts -- https://core.trac.wordpress.org/ticket/23578
						*/
						if ( simontaxi_get_option( 'vehicle_booking_completed_email_type', 'html' ) == 'html' ) {
							remove_filter( 'wp_mail_content_type', 'simontaxi_mail_html_type' );
						} else {
							remove_filter( 'wp_mail_content_type', 'simontaxi_mail_text_type' );
						}
					}
				}
			}
		}
	}
endif;

if ( ! function_exists( 'simontaxi_mail_html_type' ) ) :
	/**
	 * This function to set the mail type which is called through 'wp_mail_content_type' filter
	 */
	function simontaxi_mail_html_type() {
		return 'text/html';
	}
endif;

if ( ! function_exists( 'simontaxi_mail_text_type' ) ) :
	/**
	 * This function to set the mail type which is called through 'wp_mail_content_type' filter
	 */
	function simontaxi_mail_text_type() {
		return 'text/plain';
	}
endif;

if ( ! function_exists( 'simontaxi_replace_constants' ) ) :
	/**
	 * This function prepares the content to sent by replacing variables with values
	 *
	 * @param array $variables - variables.
	 * @param string $message - Message contains constants.
	 */
	function simontaxi_replace_constants( $variables, $message ) {
		if ( is_array( $variables) ) {
			foreach( $variables as $key => $val ) {
				$message = str_replace( $key, $val, $message);
			}
		}
		return $message;
	}
endif;

if ( ! function_exists( 'simontaxi_send_status_change_sms' ) ) :
	/**
	 * @since 2.0.0
	 */
	add_action( 'simontaxi_send_status_change_sms', 'simontaxi_send_status_change_sms', 10, 3 );
	/**
	 * This function serves to send SMS when status is changed in admin area
	 *
	 * @param int $booking_id - ID.
	 * @param string $status - Booking Status.
	 * @param string $type - User|Admin for now.
	 */
	function simontaxi_send_status_change_sms( $booking_id, $status, $type = 'user' ) {
		/**
		 * We are using 3rd party plugin to send SMS so we need to check whether the plugin is active or not
		 */
		if ( simontaxi_is_sms_gateway_active() && $booking_id != '' && $status != '' ) {
			global $wpdb;
			$bookings = $wpdb->prefix. 'st_bookings';
			$payments = $wpdb->prefix. 'st_payments';
			$sql = "SELECT *, `" . $bookings."`.`ID` AS booking_id, `" . $bookings."`.`reference` AS booking_ref FROM `" . $bookings."` INNER JOIN `" . $payments."` ON `" . $payments."`.`booking_id`=`" . $bookings."`.`ID` WHERE `" . $bookings."`.booking_contacts!='' AND `" . $bookings."`.ID=" . $booking_id;
			$result = $wpdb->get_results( $sql);
			$booking=(array)$result[0];
			unset( $booking['session_details'] ); /* No need this information so kill it!!*/
			$contact = (array)json_decode( $booking['booking_contacts'] );
			$mobile = '';
			
			$booking_type = $booking['booking_type'];
			$vehicle_id = $booking['selected_vehicle'];
			$user_type = $type;
			if ( $type == 'user' ) {
				 $mobile_countrycode = '';
				 if ( isset( $contact['mobile_countrycode'] ) ) {
					$mobile_countrycode_parts = explode( '_', $contact['mobile_countrycode'] );
					/**
					   * @since 2.0.2
					   * Change Description: 
					   * PHP 5.3 doesn't support the [] array syntax. Only PHP 5.4 and later does. For older PHP, you need to use array() instead of [].
					   */
					  if ( ! empty( $mobile_countrycode_parts ) ) {
						 $mobile_countrycode = $mobile_countrycode_parts[0]; 
					  }
				 }
				 if ( isset( $contact['mobile'] ) ) {
					$append_country_code_to_mobile = simontaxi_get_option( 'append_country_code_to_mobile', 'yes' );
					if ( 'yes' === $append_country_code_to_mobile ) {
						$mobile = $mobile_countrycode . $contact['mobile'];
					} else {
					 $mobile = $contact['mobile'];
					}
				 }
			 } elseif( 'admin' === $type ) {
				 $mobile = simontaxi_get_primary_admin_mobile(); //Assuming admin ID is '1'
			 } else {
				 $mobile = apply_filters( 'simontaxi_get_mobile', $booking_id );
			 }
			$filter_type = '';
			 if ( ! empty( $result) && $mobile != '' ) {
				 if ( $status == 'confirmed' ) {
					$posttitle = 'sms-booking-confirmed';
					/**
					 * @since 2.0.9
					 */
					$filter_type = str_replace( '-', '_', $posttitle );
					
					/**
					 * In this way we can send different emails for different users. For this we need to create separate files for each user.
					 *
					 * suppose if template is "sms-booking-confirmed" if you want to send different email template to driver rather than regular template, then you need to create a post with name "sms-booking-confirmed-driver"
					 *
					 * Hierarchy
					 * 1. template-user_type-booking_type-vehicle_id (Eg: sms-booking-confirmed-driver-p2p-958)
					 * 2. template-user_type-booking_type (Eg: sms-booking-confirmed-driver-p2p)
					 * 3. template-user_type (Eg: sms-booking-confirmed-driver)
					 * 4. template-booking_type (Eg: sms-booking-confirmed-p2p)
					 * 5. template (Eg: sms-booking-confirmed)
					 *
					 * @since 2.0.9
					 */
					if ( ! empty( $vehicle_id ) && simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id, 'smstemplate' ) ) {
						$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type, 'smstemplate' ) ) {
						$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type, 'smstemplate' ) ) {
						$posttitle = $posttitle . '-' . $user_type;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $booking_type, 'smstemplate' ) ) {
						$posttitle = $posttitle . '-' . $booking_type;
					}
					$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $posttitle . "' AND post_status='publish'AND post_type='smstemplate'" );
					$getpost= get_post( $postid);
					
					/**
					 * @since 2.0.8
					 *
					 * But my site now has two languages and if someone orders in a different language, he gets a notification in Polish.
					 */
					if ( 'file' == simontaxi_get_option( 'vehicle_booking_confirm_sms_body', 'smstemplate' ) ) {
						ob_start();
						$posttitle = 'sms-booking-confirmed';
						$template = '/templates/smstemplates/sms-booking-confirmed.php';
						/**
						 * In this way we can send different emails for different users. For this we need to create separate files for each user.
						 *
						 * suppose if template is "sms-booking-confirmed" if you want to send different email template to driver rather than regular template, then you need to create a file with name "sms-booking-confirmed-driver.php"
						 *
						 * Hierarchy - "wp-content/plugins/vehicle-booking/templates"
						 * 1. template-user_type-booking_type-vehicle_id (Eg: sms-booking-confirmed-driver-p2p-965.php)
						 * 2. template-user_type-booking_type (Eg: sms-booking-confirmed-driver-p2p.php)
						 * 3. template-user_type (Eg: sms-booking-confirmed-driver.php)
						 * 4. template-booking_type (Eg: sms-booking-confirmed-p2p.php)
						 * 5. template (Eg: sms-booking-confirmed.php)
						 *
						 */
						if ( ! empty( $vehicle_id ) && ! empty( $user_type ) && simontaxi_is_file_exists( '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php' ) ) {
							$template = '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php';
						} elseif ( ! empty( $user_type ) && simontaxi_is_file_exists( '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php' ) ) {
							$template = '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php';
						} elseif ( ! empty( $user_type ) && simontaxi_is_file_exists( '/templates/smstemplates/' . $posttitle . '-' . $user_type . '.php' ) ) {
							$template = '/templates/smstemplates/' . $posttitle . '-' . $user_type . '.php';
						} elseif ( ! empty( $user_type ) && simontaxi_is_file_exists( '/templates/smstemplates/' . $posttitle . '-' . $booking_type . '.php' ) ) {
							$template = '/templates/smstemplates/' . $posttitle . '-' . $booking_type . '.php';
						}
						if ( simontaxi_is_template_customized( $template ) ) {
							include_once( simontaxi_get_theme_template_dir_name() . $template );
						} else {
							include_once( SIMONTAXI_PLUGIN_PATH . $template );
						}
						$template = ob_get_clean();
					} else {
						$template= $getpost->post_content;
					}
					// $template= $getpost->post_content;
					$pattern = array( 
						'/\{BOOKING_REF\}/',
						'/\{PICKUP_DATE\}/',
						'/\{PICKUP_TIME\}/',
						'/\{CAR_PLATE\}/',
						'/\{FROM\}/',
						'/\{TO\}/',
					);
					$replacement = array(
						$booking['booking_ref'],
						simontaxi_date_format( $booking['pickup_date'] ),
						simontaxi_get_time_display_format( $booking['pickup_time'] ),
						$booking['vehicle_no'],
						$booking['pickup_location'],
						$booking['drop_location'],
					);
				 } elseif ( $status == 'cancelled' ) {
					$posttitle = 'sms-booking-cancel';
					
					/**
					 * @since 2.0.9
					 */
					$filter_type = str_replace( '-', '_', $posttitle );
					
					/**
					 * In this way we can send different emails for different users. For this we need to create separate files for each user.
					 *
					 * suppose if template is "sms-booking-cancel" if you want to send different email template to driver rather than regular template, then you need to create a post with name "sms-booking-cancel-driver"
					 *
					 * Hierarchy
					 * 1. template-user_type-booking_type-vehicle_id (Eg: sms-booking-cancel-driver-p2p-958)
					 * 2. template-user_type-booking_type (Eg: sms-booking-cancel-driver-p2p)
					 * 3. template-user_type (Eg: sms-booking-cancel-driver)
					 * 4. template-booking_type (Eg: sms-booking-cancel-p2p)
					 * 5. template (Eg: sms-booking-cancel)
					 *
					 * @since 2.0.9
					 */
					if ( ! empty( $vehicle_id ) && simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id, 'smstemplate' ) ) {
						$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type, 'smstemplate' ) ) {
						$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type, 'smstemplate' ) ) {
						$posttitle = $posttitle . '-' . $user_type;
					}
					$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $posttitle . "' AND post_status='publish'AND post_type='smstemplate'" );
					$getpost= get_post( $postid);
					
					/**
					 * @since 2.0.8
					 *
					 * But my site now has two languages and if someone orders in a different language, he gets a notification in Polish.
					 */
					if ( 'file' == simontaxi_get_option( 'vehicle_booking_cancel_sms_body', 'smstemplate' ) ) {
						ob_start();
						$posttitle = 'sms-booking-cancel';
						$template = '/templates/smstemplates/sms-booking-cancel.php';
						/**
						 * In this way we can send different emails for different users. For this we need to create separate files for each user.
						 *
						 * suppose if template is "sms-booking-cancel" if you want to send different email template to driver rather than regular template, then you need to create a file with name "sms-booking-cancel-driver.php"
						 *
						 * Hierarchy - "wp-content/plugins/vehicle-booking/templates"
						 * 1. template-user_type-booking_type-vehicle_id (Eg: sms-booking-cancel-driver-p2p-965.php)
						 * 2. template-user_type-booking_type (Eg: sms-booking-cancel-driver-p2p.php)
						 * 3. template-user_type (Eg: sms-booking-cancel-driver.php)
						 * 4. template-booking_type (Eg: sms-booking-cancel-p2p.php)
						 * 5. template (Eg: sms-booking-cancel.php)
						 *
						 */
						if ( ! empty( $vehicle_id ) && ! empty( $user_type ) && simontaxi_is_file_exists( '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php' ) ) {
							$template = '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php';
						} elseif ( ! empty( $user_type ) && simontaxi_is_file_exists( '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php' ) ) {
							$template = '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php';
						} elseif ( ! empty( $user_type ) && simontaxi_is_file_exists( '/templates/smstemplates/' . $posttitle . '-' . $user_type . '.php' ) ) {
							$template = '/templates/smstemplates/' . $posttitle . '-' . $user_type . '.php';
						} elseif ( ! empty( $user_type ) && simontaxi_is_file_exists( '/templates/smstemplates/' . $posttitle . '-' . $booking_type . '.php' ) ) {
							$template = '/templates/smstemplates/' . $posttitle . '-' . $booking_type . '.php';
						}
						if ( simontaxi_is_template_customized( $template ) ) {
							include_once( simontaxi_get_theme_template_dir_name() . $template );
						} else {
							include_once( SIMONTAXI_PLUGIN_PATH . $template );
						}
						$template = ob_get_clean();
					} else {
						$template= $getpost->post_content;
					}

					// $template= $getpost->post_content;
					$pattern = array(
						'/\{BOOKING_REF\}/',
						'/\{PICKUP_DATE\}/',
						'/\{PICKUP_TIME\}/',
						'/\{REASON\}/',
						'/\{FROM\}/',
						'/\{TO\}/',
					);
					$replacement = array(
						$booking['booking_ref'],
						simontaxi_date_format( $booking['pickup_date'] ),
						simontaxi_get_time_display_format( $booking['pickup_time'] ),
						$booking['reason_message'],
						$booking['pickup_location'],
						$booking['drop_location'],
					);
				} elseif ( $status == 'onride' ) {
					$posttitle = 'sms-ride-start';
					
					/**
					 * @since 2.0.9
					 */
					$filter_type = str_replace( '-', '_', $posttitle );
					
					/**
					 * In this way we can send different emails for different users. For this we need to create separate files for each user.
					 *
					 * suppose if template is "sms-ride-start" if you want to send different email template to driver rather than regular template, then you need to create a post with name "sms-ride-start"
					 *
					 * Hierarchy
					 * 1. template-user_type-booking_type-vehicle_id (Eg: sms-ride-start-driver-p2p-926)
					 * 2. template-user_type-booking_type (Eg: sms-ride-start-driver-p2p)
					 * 3. template-user_type (Eg: sms-ride-start-driver)
					 * 4. template-booking_type (Eg: sms-ride-start-p2p)
					 * 5. template (Eg: sms-ride-start)
					 *
					 * @since 2.0.9
					 */
					if ( ! empty( $vehicle_id ) && simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id, 'smstemplate' ) ) {
						$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type, 'smstemplate' ) ) {
						$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type, 'smstemplate' ) ) {
						$posttitle = $posttitle . '-' . $user_type;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $booking_type, 'smstemplate' ) ) {
						$posttitle = $posttitle . '-' . $booking_type;
					}
					$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $posttitle . "' AND post_status='publish' AND post_type='smstemplate'" );
					$getpost= get_post( $postid);
					
					/**
					 * @since 2.0.8
					 *
					 * But my site now has two languages and if someone orders in a different language, he gets a notification in Polish.
					 */
					if ( 'file' == simontaxi_get_option( 'vehicle_booking_startride_sms_body', 'smstemplate' ) ) {
						ob_start();
						$posttitle = 'sms-ride-start';
						$template = '/templates/smstemplates/sms-ride-start.php';
						/**
						 * In this way we can send different emails for different users. For this we need to create separate files for each user.
						 *
						 * suppose if template is "sms-ride-start" if you want to send different email template to driver rather than regular template, then you need to create a file with name "sms-ride-start-driver"
						 *
						 * Hierarchy - "wp-content/plugins/vehicle-booking/templates"
						 * 1. template-user_type-booking_type-vehicle_id (Eg: sms-ride-start-driver-p2p-965.php)
						 * 2. template-user_type-booking_type (Eg: sms-ride-start-driver-p2p.php)
						 * 3. template-user_type (Eg: sms-ride-start-driver.php)
						 * 4. template-booking_type (Eg: sms-ride-start-p2p.php)
						 * 5. template (Eg: sms-ride-start.php)
						 *
						 */
						if ( ! empty( $vehicle_id ) && ! empty( $user_type ) && simontaxi_is_file_exists( '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php' ) ) {
							$template = '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php';
						} elseif ( ! empty( $user_type ) && simontaxi_is_file_exists( '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php' ) ) {
							$template = '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php';
						} elseif ( ! empty( $user_type ) && simontaxi_is_file_exists( '/templates/smstemplates/' . $posttitle . '-' . $user_type . '.php' ) ) {
							$template = '/templates/smstemplates/' . $posttitle . '-' . $user_type . '.php';
						} elseif ( ! empty( $booking_type ) && simontaxi_is_file_exists( '/templates/smstemplates/' . $posttitle . '-' . $booking_type . '.php' ) ) {
							$template = '/templates/smstemplates/' . $posttitle . '-' . $booking_type . '.php';
						}
						
						if ( simontaxi_is_template_customized( $template ) ) {
							include_once( simontaxi_get_theme_template_dir_name() . $template );
						} else {
							include_once( SIMONTAXI_PLUGIN_PATH . $template );
						}
						$template = ob_get_clean();
					} else {
						$template= $getpost->post_content;
					}
					// $template= $getpost->post_content;
					$pattern = array( 
						'/\{BOOKING_REF\}/',
						'/\{PICKUP_DATE\}/',
						'/\{PICKUP_TIME\}/',
						'/\{CAR_PLATE\}/',
						'/\{FROM\}/',
						'/\{TO\}/',
					);
					$replacement = array(
						$booking['booking_ref'],
						simontaxi_date_format( $booking['pickup_date'] ),
						simontaxi_get_time_display_format( $booking['pickup_time'] ),
						$booking['vehicle_no'],
						$booking['pickup_location'],
						$booking['drop_location'],
					);
				} elseif ( $status == 'success' ) {
					$posttitle = 'sms-ride-completed';
					
					/**
					 * @since 2.0.9
					 */
					$filter_type = str_replace( '-', '_', $posttitle );
					
					/**
					 * In this way we can send different emails for different users. For this we need to create separate files for each user.
					 *
					 * suppose if template is "sms-ride-completed" if you want to send different email template to driver rather than regular template, then you need to create a post with name "sms-ride-completed"
					 *
					 * Hierarchy
					 * 1. template-user_type-booking_type-vehicle_id (Eg: sms-ride-completed-driver-p2p-926)
					 * 2. template-user_type-booking_type (Eg: sms-ride-completed-driver-p2p)
					 * 3. template-user_type (Eg: sms-ride-completed-driver)
					 * 4. template-booking_type (Eg: sms-ride-completed-p2p)
					 * 5. template (Eg: sms-ride-completed)
					 *
					 * @since 2.0.9
					 */
					if ( ! empty( $vehicle_id ) && simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id, 'smstemplate' ) ) {
						$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type . '-' . $booking_type, 'smstemplate' ) ) {
						$posttitle = $posttitle . '-' . $user_type . '-' . $booking_type;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $user_type, 'smstemplate' ) ) {
						$posttitle = $posttitle . '-' . $user_type;
					} elseif ( simontaxi_get_post_by_slug( $posttitle . '-' . $booking_type, 'smstemplate' ) ) {
						$posttitle = $posttitle . '-' . $booking_type;
					}
					$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $posttitle . "' AND post_status='publish' AND post_type='smstemplate'" );
					$getpost= get_post( $postid);
					
					/**
					 * @since 2.0.8
					 *
					 * But my site now has two languages and if someone orders in a different language, he gets a notification in Polish.
					 */
					if ( 'file' == simontaxi_get_option( 'vehicle_booking_completed_sms_body', 'smstemplate' ) ) {
						ob_start();
						$posttitle = 'sms-ride-completed';
						$template = '/templates/smstemplates/sms-ride-completed.php';
						/**
						 * In this way we can send different emails for different users. For this we need to create separate files for each user.
						 *
						 * suppose if template is "sms-ride-completed" if you want to send different email template to driver rather than regular template, then you need to create a file with name "sms-ride-completed-driver.php"
						 *
						 * Hierarchy - "wp-content/plugins/vehicle-booking/templates"
						 * 1. template-user_type-booking_type-vehicle_id (Eg: sms-ride-completed-driver-p2p-965.php)
						 * 2. template-user_type-booking_type (Eg: sms-ride-completed-driver-p2p.php)
						 * 3. template-user_type (Eg: sms-ride-completed-driver.php)
						 * 4. template-booking_type (Eg: sms-ride-completed-p2p.php)
						 * 5. template (Eg: sms-ride-completed.php)
						 *
						 */
						if ( ! empty( $vehicle_id ) && ! empty( $user_type ) && simontaxi_is_file_exists( '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php' ) ) {
							$template = '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '-' . $vehicle_id . '.php';
						} elseif ( ! empty( $user_type ) && simontaxi_is_file_exists( '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php' ) ) {
							$template = '/templates/smstemplates/' . $posttitle . '-' . $user_type . '-' . $booking_type . '.php';
						} elseif ( ! empty( $user_type ) && simontaxi_is_file_exists( '/templates/smstemplates/' . $posttitle . '-' . $user_type . '.php' ) ) {
							$template = '/templates/smstemplates/' . $posttitle . '-' . $user_type . '.php';
						} elseif ( ! empty( $booking_type ) && simontaxi_is_file_exists( '/templates/smstemplates/' . $posttitle . '-' . $booking_type . '.php' ) ) {
							$template = '/templates/smstemplates/' . $posttitle . '-' . $booking_type . '.php';
						}
						if ( simontaxi_is_template_customized( $template ) ) {
							include_once( simontaxi_get_theme_template_dir_name() . $template );
						} else {
							include_once( SIMONTAXI_PLUGIN_PATH . $template );
						}
						$template = ob_get_clean();
					} else {
						$template= $getpost->post_content;
					}
					// $template= $getpost->post_content;
					$pattern = array( 
						'/\{BOOKING_REF\}/',
						'/\{PICKUP_DATE\}/',
						'/\{PICKUP_TIME\}/',
						'/\{CAR_PLATE\}/',
						'/\{FROM\}/',
						'/\{TO\}/',
					);
					$replacement = array(
						$booking['booking_ref'],
						simontaxi_date_format( $booking['pickup_date'] ),
						simontaxi_get_time_display_format( $booking['pickup_time'] ),
						$booking['vehicle_no'],
						$booking['pickup_location'],
						$booking['drop_location'],
					);
				 }
				$template = preg_replace( $pattern, $replacement, $template);
				
				/**
				 * @since 2.0.9
				 */
				$additional_top = apply_filters( "{$filter_type}_additional_top", '' );
				$template = str_replace( "{{$filter_type}_additional_top}", $additional_top, $template );
				
				$additional_bottom = apply_filters( "{$filter_type}_additional_bottom", '' );
				$template = str_replace( "{{$filter_type}_additional_bottom}", $additional_bottom, $template );

				global $sms;
				$sms->to = array( $mobile);
				$sms->msg =  $template;
				try{
					$sms->SendSMS();
				} catch( Exception $e ) {}
			}
		}
	}
endif;

if ( ! function_exists( 'simontaxi_send_email_sms_adminside' ) ) :
	/**
	 * @since 2.0.0
	 */
	add_action( 'simontaxi_send_email_sms_adminside', 'simontaxi_send_email_sms_adminside', 10, 2 );
	/**
	 * This function send SMS and Email based on admin settings while changing booking status from admin end
	 *
	 * @param int $booking_id - ID.
	 * @param string $status - Booking Status.
	 */
	function simontaxi_send_email_sms_adminside( $booking_id, $status ) {
		$sent = false;
		if ( 'confirmed' === $status ) {
			/**
			 * Let us send email to user based on admin settings
			 */
			if ( simontaxi_get_option( 'vehicle_booking_confirm_email_user', 'yes' ) == 'yes' ) {
					do_action( 'simontaxi_send_status_change_email', $booking_id, $status, 'user' );
					$sent = true;
			}

			/**
			 * Let us send SMS to user based on admin settings
			 * Mobile number field is optional in admin. Lets check whether it is enabled and user enter it.
			 */
			if ( simontaxi_get_option( 'vehicle_booking_confirm_sms_user', 'no' ) == 'yes' ) {
					do_action( 'simontaxi_send_status_change_sms', $booking_id, $status, 'user' );
					$sent = true;
			}

			/**
			 * Let us send email to admin based on admin settings
			 */
			if ( simontaxi_get_option( 'vehicle_booking_confirm_email_admin', 'yes' ) == 'yes' ) {
					do_action( 'simontaxi_send_status_change_email', $booking_id, $status, 'admin' );
					$sent = true;
			}

			/**
			 * Let us send SMS to admin based on admin settings
			 * Mobile number field is optional for admin users. So lets check whether admin user has updated mobile number or not.
			 * Here the admin refers to the mail admin only.
			 */
			if ( simontaxi_get_option( 'vehicle_booking_confirm_sms_admin', 'no' ) == 'yes' ) {
					do_action( 'simontaxi_send_status_change_sms', $booking_id, $status, 'admin' );
					$sent = true;
			}
		} elseif ( 'cancelled' === $status ) {
			/**
			 * Let us send email to user based on admin settings
			 */
			if ( simontaxi_get_option( 'vehicle_booking_cancel_email_user', 'yes' ) == 'yes' ) {
					do_action( 'simontaxi_send_status_change_email', $booking_id, $status, 'user' );
					$sent = true;
			}

			/**
			 * Let us send SMS to user based on admin settings
			 * Mobile number field is optional in admin. Lets check whether it is enabled and user enter it.
			 */
			if ( simontaxi_get_option( 'vehicle_booking_cancel_sms_user', 'no' ) == 'yes' ) {
					do_action( 'simontaxi_send_status_change_sms', $booking_id, $status, 'user' );
					$sent = true;
			}

			/**
			 * Let us send email to admin based on admin settings
			 */
			if ( simontaxi_get_option( 'vehicle_booking_cancel_email_admin', 'yes' ) == 'yes' ) {					
					do_action( 'simontaxi_send_status_change_email', $booking_id, $status, 'admin' );
					$sent = true;
			}

			/**
			 * Let us send SMS to admin based on admin settings
			 * Mobile number field is optional for admin users. So lets check whether admin user has updated mobile number or not.
			 * Here the admin refers to the mail admin only.
			 */
			if ( simontaxi_get_option( 'vehicle_booking_cancel_sms_user', 'no' ) == 'yes' ) {
					do_action( 'simontaxi_send_status_change_sms', $booking_id, $status, 'admin' );
					$sent = true;
			}
		}
		return $sent;
	}
endif;

if ( ! function_exists( 'simontaxi_log_user_in' ) ) :
	/**
	 * Log User In
	 *
	 * @since 1.0
	 * @param int $user_id User ID.
	 * @param string $user_login Username.
	 * @param string $user_pass Password.
	 * @param boolean $remember Remember me.
	 * @return void
	 */
	function simontaxi_log_user_in( $user_id, $user_login, $user_pass, $remember = false ) {
		if ( $user_id < 1 )
			return;
		wp_set_auth_cookie( $user_id, $remember );
		wp_set_current_user( $user_id, $user_login );
		do_action( 'wp_login', $user_login, get_userdata( $user_id ) );
		do_action( 'simontaxi_log_user_in', $user_id, $user_login, $user_pass );
	}
endif;

if ( ! function_exists( 'simontaxi_terms_text' ) ) :
	/**
	 * This function reurns the terms and conditions text
	 */
	function simontaxi_terms_text() {
		$terms_page_id = simontaxi_get_option( 'terms_page_id', 0);
		$str = apply_filters( 'simontaxi_filter_termstitle', esc_html__( 'I understand and agree with the Terms of Service and Cancellation. ', 'simontaxi' ) );
		if ( $terms_page_id != 0) {
			$permalink = get_permalink( $terms_page_id );
			$link_title = apply_filters( 'simontaxi_filter_termslinktitle', esc_html__( ' Click here to view', 'simontaxi' ) );
			if ( $permalink ) {
				$str = $str . sprintf( '<a href="%s" target="_blank">%s</a>', $permalink, $link_title);
			}
		}
		return $str;
	}
endif;

if ( ! function_exists( 'simontaxi_redirect_login_page' ) ) :
	/**
	 * This function redirect the default wordpress login page
	 */
	function simontaxi_redirect_login_page(){

		// Store for checking if this page equals wp-login.php
		$page_viewed = basename( $_SERVER['REQUEST_URI'] );

		// permalink to the custom login page
		$login_page  = simontaxi_get_bookingsteps_urls( 'login' );

		if ( (( $page_viewed == "wp-login.php" && ! isset( $_POST ) )  || ( isset( $_REQUEST['loggedout'] ) ) )
		){
			wp_redirect( $login_page );
			exit();
		}
	}
endif;

add_action( 'init','simontaxi_redirect_login_page' );

add_action( 'login_form_lostpassword', 'simontaxi_redirect_to_custom_lostpassword' );
if ( ! function_exists( 'simontaxi_redirect_to_custom_lostpassword' ) ) :
	/**
	 * This function redirect the custom lost password page.
	 */
	function simontaxi_redirect_to_custom_lostpassword() {
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			if ( is_user_logged_in() ) {
				wp_redirect( home_url() );
				exit;
			}

			wp_redirect( simontaxi_get_bookingsteps_urls( 'forgotpassword' ) );
			exit;
		}
	}
endif;
add_action( 'login_form_rp', 'simontaxi_redirect_to_custom_password_reset' );
add_action( 'login_form_resetpass', 'simontaxi_redirect_to_custom_password_reset' );

if ( ! function_exists( 'simontaxi_redirect_to_custom_password_reset' ) ) :
	/**
	 * This function redirect the custom reset password page.
	 */
	function simontaxi_redirect_to_custom_password_reset() {
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			// Verify key / login combo
			$user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
			if ( ! $user || is_wp_error( $user ) ) {
				if ( $user && $user->get_error_code() === 'expired_key' ) {
					wp_redirect( home_url( 'member-login?login=expiredkey' ) );
				} else {
					wp_redirect( home_url( 'member-login?login=invalidkey' ) );
				}
				exit;
			}

			$redirect_url = simontaxi_get_bookingsteps_urls( 'resetpassword' );
			$redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
			$redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );

			wp_redirect( $redirect_url );
			exit;
		}
	}
endif;

if ( ! function_exists( 'simontaxi_login_logo' ) ) :
	/**
	 * This function changes the default wordpress logo with new logo.
	 */
	function simontaxi_login_logo(){
	
	$logo = SIMONTAXI_PLUGIN_URL . '/images/logo.png';
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( ! empty( $custom_logo_id ) ) {
		$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
		if ( ! empty( $image ) ) {
			$logo = $image[0];
		}
	}
	?>
	<style type="text/css">
		#login h1 a, .login h1 a {
			background-image: url(<?php echo esc_url( $logo ); ?>);
			padding-bottom: 30px;
			height: auto;
			width: auto;
			background-size: auto;
		}
	</style>
	<?php }
endif;

add_action( 'login_enqueue_scripts', 'simontaxi_login_logo',1 );

if ( ! function_exists( 'simontaxi_social_section_posts' ) ) :
	/**
	 * This function add social sharing buttons to the content.
	 */
	function simontaxi_social_section_posts( $content ) {
		/**
		 * We dont want to add social icons for the vehicle post type
		 */
		if ( is_single() ) {
			ob_start();
				$my_theme = wp_get_theme();
				if ( get_post_type() != 'vehicle' && in_array( $my_theme->get( 'Name' ), array( 'simontaxi' ) ) ) {					
					/**
					 * @since 2.0.8
					 */
					$template = '/booking/includes/pages/section-socialshare.php';
					if ( simontaxi_is_template_customized( $template ) ) {
						include_once( simontaxi_get_theme_template_dir_name() . $template );
					} else {
						include_once( SIMONTAXI_PLUGIN_PATH . $template );
					}
				}
			$social =  ob_get_clean();
			$content = $content . $social;
		}
		return $content;
	}
endif;
add_filter( 'the_content', 'simontaxi_social_section_posts', 10 );

if ( ! function_exists( 'simontaxi_demo_import' ) ) :
	/**
	 * Import demo.
	 */
	function simontaxi_demo_import() {
		/**
		 * @since 2.0.8
		 */
		$template = '/booking/includes/pages/admin/demo_import.php';
		if ( simontaxi_is_template_customized( $template ) ) {
			include_once( simontaxi_get_theme_template_dir_name() . $template );
		} else {
			include_once( SIMONTAXI_PLUGIN_PATH . $template );
		}
	}
endif;

if ( ! function_exists( 'simontaxi_get_vehicle_categories' ) ) :
	/**
	 * This function returns vehicle types.
	 */
	function simontaxi_get_vehicle_categories() {
		$types = array();
		$vehicle_types = get_terms( array( 
			'taxonomy' => 'vehicle_types', 
			'hide_empty' => false,
		) );
		if ( ! empty( $vehicle_types ) ) {
			foreach ( $vehicle_types as $type ) {
				$types[ $type->name ] = $type->term_id;
			}
		}
		return $types;
	}
endif;

add_action( 'after_setup_theme', 'simontaxi_remove_admin_bar' );

if ( ! function_exists( 'simontaxi_remove_admin_bar' ) ) :
	/**
	 * Disable Admin Bar for All Users Except for Administrators OR Executives
	 */
	function simontaxi_remove_admin_bar() {
		if ( simontaxi_is_user( 'Customer' ) ) {
		  show_admin_bar( false );
		}
	}
endif;

if ( ! function_exists( 'simontaxi_is_proceed_to_payment' ) ) :
	/**
	 * Let us check the validity of the checkout!!
	 *
	 * @since 2.0.0
	 */
	function simontaxi_is_proceed_to_payment() {
		$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
		$booking_step2 = simontaxi_get_session( 'booking_step2', array() );
		$booking_step3 = simontaxi_get_session( 'booking_step3', array() );
		$booking_step4 = simontaxi_get_session( 'booking_step4', array() );

		return ( ! empty( $booking_step1 ) && ! empty( $booking_step2 ) && ! empty( $booking_step3 ) && ! empty( $booking_step4 ) );
	}

endif;

if ( ! function_exists( 'simontaxi_is_payment_page' ) ) :
	/**
	 * Let us check the validity of the checkout page!!
	 *
	 * @since 2.0.0
	 */
	function simontaxi_is_payment_page() {
		$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
		$booking_step2 = simontaxi_get_session( 'booking_step2', array() );
		$booking_step3 = simontaxi_get_session( 'booking_step3', array() );
		return ( ! empty( $booking_step1 ) && ! empty( $booking_step2 ) && ! empty( $booking_step3 ) );
	}
endif;

if ( ! function_exists( 'simontaxi_booking_details' ) ) :
	/**
	 * Let us check the validity of the checkout!!
	 *
	 * @param string $key - Key to get
	 * @since 2.0.0
	 */
	function simontaxi_booking_details( $key = '' ) {

		$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
		$booking_step2 = simontaxi_get_session( 'booking_step2', array() );
		$booking_step3 = simontaxi_get_session( 'booking_step3', array() );
		$booking_step4 = simontaxi_get_session( 'booking_step4', array() );

		if ( '' === $key ) {
			return array_merge( $booking_step1, $booking_step2, $booking_step3, $booking_step4 );
		} else {
			$string = '-';
			switch ( $key ) {
				case 'journey':
					if ( ! empty( $booking_step1 ) ) {
						$string = $booking_step1['pickup_location'] . esc_html__( ' TO ' ) . $booking_step1['drop_location'];
					}
					break;
				case 'email':
					if ( ! empty( $booking_step3 ) ) {
						$string = isset( $booking_step3['email'] ) ? $booking_step3['email'] : '';
					}
					break;
				case 'payment_id':
					if ( ! empty( $booking_step4 ) ) {
						$string = isset( $booking_step4['payment_id'] ) ? $booking_step4['payment_id'] : 0;
					}
					break;
				case 'db_ref':
					if ( ! empty( $booking_step1 ) ) {
						$string = isset( $booking_step1['db_ref'] ) ? $booking_step1['db_ref'] : '';
					}
					break;
			}
			return $string;
		}
	}

endif;

add_action( 'wp_ajax_simontaxi_submit_button_step4', 'simontaxi_submit_button_step4' );
add_action( 'wp_ajax_nopriv_simontaxi_submit_button_step4', 'simontaxi_submit_button_step4' );
if ( ! function_exists( 'simontaxi_submit_button_step4' ) ) :

	add_action( 'simontaxi_submit_button_step4', 'simontaxi_submit_button_step4' );
	/**
	 * Filter for Step4 Submit Button
	 *
	 * @since 2.0.0
	 */
	function simontaxi_submit_button_step4() {
		ob_start();
		$paymentmethod = isset( $_POST['paymentmethod'] ) ? sanitize_text_field( wp_unslash( $_POST['paymentmethod'] ) ) : 'byhand';
		if ( in_array( $paymentmethod, array( 'paypal', 'payu', 'byhand', 'banktransfer' ), true ) ) :
			?>
			<div class="st-terms-block">
				<a href="<?php echo apply_filters( 'step4_back_url', simontaxi_get_bookingsteps_urls( 'step3' ) ); ?>" class="btn-dull"><i class="fa fa-angle-double-left"></i> <?php esc_html_e( 'Back', 'simontaxi' ); ?> </a>
				<button type="submit" class="btn btn-primary btn-mobile" name="validtestep4"><?php echo apply_filters( 'simontaxi_filter_step4_nextbutton_title', esc_html__( 'Book Now', 'simontaxi' ) ); ?></button>
			</div>
			<?php
			$content = ob_get_contents();
			ob_get_clean();
			echo apply_filters( 'simontaxi_filter_submit_button_step4', $content );
		else :
			do_action( 'simontaxi_submit_button_step4_' . $paymentmethod );
		endif;
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			die();
		}
	}
endif;

if ( ! function_exists( 'simontaxi_available_capabilities' ) ) :
	/**
	 * This function return the capabiities
	 *
	 * @since 2.0.0
	 */
	function simontaxi_available_capabilities() {
		$caps = array(
			'manage_bookings' => esc_html__( 'Manage Bookings', 'simontaxi' ),
			'manage_vehicles' => simontaxi_get_default_title_plural(),
			'manage_features' => esc_html__( 'Features', 'simontaxi' ),
			'manage_types' => esc_html__( 'Types', 'simontaxi' ),
			'manage_locations' => esc_html__( 'Locations', 'simontaxi' ),
			'manage_hourly_packages' => esc_html__( 'Hourly Packages', 'simontaxi' ),
			'manage_layoutdates' => esc_html__( 'Lay out Dates', 'simontaxi' ),
			'manage_special_fare' => esc_html__( 'Special Fare', 'simontaxi' ),
			'manage_distance_prices' => esc_html__( 'Distance Prices', 'simontaxi' ),
			'manage_coupon_codes' => esc_html__( 'Coupon Codes', 'simontaxi' ),
			'manage_faq' => esc_html__( 'All Vehicle FAQ', 'simontaxi' ),
			'manage_email_templates' => esc_html__( 'Email Templates', 'simontaxi' ),
			'manage_sms_templates' => esc_html__( 'SMS Templates', 'simontaxi' ),
			'manage_testimonials' => esc_html__( 'All Testimonials', 'simontaxi' ),
			'manage_settings' => esc_html__( 'Settings', 'simontaxi' ),
			'manage_callbacks' => esc_html__( 'Request Callbacks', 'simontaxi' ),
			'manage_support_request' => esc_html__( 'Support Request', 'simontaxi' ),
		);
		return apply_filters( 'simontaxi_available_capabilities', $caps );
	}
endif;


if ( ! function_exists( 'simontaxi_is_sms_gateway_active' ) ) :
	/**
	 * Function to check the WP_SMS plugin activation
	 *
	 * @since 2.0.0
	 */
	function simontaxi_is_sms_gateway_active() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		return ( is_plugin_active( 'wp-sms/wp-sms.php' ) || is_plugin_active( 'wp-sms-pro/wp-sms.php' ) ) ? true : false;
	}
endif;

if ( ! function_exists( 'simontaxi_special_capabilities' ) ) :
	/**
	 * This function return the capabiities
	 *
	 * @since 2.0.0
	 */
	function simontaxi_special_capabilities() {
		$caps = array(
			'manage_bookings' => esc_html__( 'Manage Bookings', 'simontaxi' ),
			'manage_callbacks' => esc_html__( 'Request Callbacks', 'simontaxi' ),
			'manage_support_request' => esc_html__( 'Support Request', 'simontaxi' ),
			'manage_settings' => esc_html__( 'Settings', 'simontaxi' ),
			'manage_extensions' => esc_html__( 'Manage Extensions', 'simontaxi' ),
			'get_extension' => esc_html__( 'Get Extensions', 'simontaxi' ),			
		);
		return apply_filters( 'simontaxi_special_capabilities', $caps );
	}
endif;

if ( ! function_exists( 'simontaxi_get_primary_admin_mobile' ) ) :
	/**
	 * This function return the mobile number of primary admin and we assume the primary admin ID as '1'
	 *
	 * @since 2.0.2
	 */
	function simontaxi_get_primary_admin_mobile() {
		$mobile = get_user_meta( 1, 'mobile', true ); //Assuming admin ID is '1'
		$mobile_countrycode = get_user_meta( 1, 'mobile_countrycode', true ); //Assuming admin ID is '1'

		if ( '' !== $mobile && '' !== $mobile_countrycode ) {
			$mobile_countrycode = explode( '_', $mobile_countrycode);
			/**
			   * @since 2.0.2
			   * Change Description: 
			   * PHP 5.3 doesn't support the [] array syntax. Only PHP 5.4 and later does. For older PHP, you need to use array() instead of [].
			   */
			  if ( ! empty( $mobile_countrycode ) ) {
				 $mobile_countrycode = $mobile_countrycode[0]; 
			  }
			$append_country_code_to_mobile = simontaxi_get_option( 'append_country_code_to_mobile', 'yes' );
			if ( 'yes' === $append_country_code_to_mobile ) {
				$mobile = $mobile_countrycode. $mobile;
			}
		} else {
			$mobile = '';
		}
		return $mobile;
	}
endif;

if ( ! function_exists( 'simontaxi_get_fixed_point_title' ) ) :
	/**
	 * This function return the mobile number of primary admin and we assume the primary admin ID as '1'
	 *
	 * @since 2.0.2
	 */
	function simontaxi_get_fixed_point_title() {
		return simontaxi_get_option( 'fixed_point_title', 'Airport' );
	}
endif;



if ( ! function_exists( 'dd' ) ) :
	/**
	 * This function prints the mixed value
	 *
	 * @param $mixed Mixed - Value to print
	 * @since 2.0.6
	 */
	function dd( $mixed = '', $stop = TRUE )
	{
		echo '<pre>';
		if ( ! empty( $mixed ) ) {			
			print_r( $mixed );
		} else {
			print_r( $_POST );
		}
		if ( $stop ) {
			die();
		}
	}
endif;


/**
 * @since 2.0.8
 *
 * Function to get the template file
 * @param String $template Page Path.
 * @param String $filter_tag Filter name for future use.
 * @param String $default_path default template path.
 */
function simontaxi_get_template( $template, $filter_tag, $output = 'return', $default_path = SIMONTAXI_PLUGIN_PATH ) {
	if ( $output == 'return' ) {
		ob_start();
	}
	if ( simontaxi_is_template_customized( $template ) ) {
		require simontaxi_get_theme_template_dir_name() . $template;
	} else {
		require apply_filters( $filter_tag, $default_path . $template );
	}
	if ( $output == 'return' ) {
		return ob_get_clean();
	}
}


/**
 * Sends the email registration alert to admin and user
 *
 * @since 2.0.7
 * @return void
*/
function simontaxi_registration_email_alert( $user_id, $plaintext_pass = '', $template = 'new-user', $subject = '' ) {
    global $wpdb;
	$user    = get_userdata( $user_id );
	$user_meta = get_user_meta( $user_id );
	
    // The blogname option is escaped with esc_html on the way into the database in sanitize_option
    // we want to reverse this for the plain text arena of emails.
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $message  = sprintf(__('New user registration on your site %s:'), $blogname) . "<br><br>";
    $message .= sprintf(__('Username: %s'), $user->user_login) . "<br><br>";
    $message .= sprintf(__('E-mail: %s'), $user->user_email) . "<br><br>";

	// $admin_email = get_option('admin_email');
	$admin_email = simontaxi_get_option('vehicle_bookings_admin_email');
	
	add_filter( 'wp_mail_content_type', 'simontaxi_mail_html_type' );
	
	$headers = 'From: ' . $blogname. ' <' . $admin_email. '>' . "\r\n";
	if ( empty( $subject ) ) {
		$subject = simontaxi_get_option( 'vehicle_booking_userregistration_email_subject', '' );
		if ( empty( $subject ) ) {
			$subject = sprintf(__('[%s] New User Registration'), $blogname);
		}
	}
    @wp_mail($admin_email, $subject, $message, $headers);

    if ( empty($plaintext_pass) )
        return;

    if ( ! empty( $user_meta['approval_code'] ) ) {
		$approval_code = $user_meta['approval_code'][0];
	} else {
		$approval_code = wp_generate_password(8);
		update_user_meta( absint( $user_id ), 'approval_code', $approval_code );
	}
	
	/*
	$message  = sprintf(__('Username: %s'), $user->user_login) . "<br>";
    $message .= sprintf(__('Password: %s'), $plaintext_pass) . "<br>";
    $message .= wp_login_url() . "<br><br>";	
	
	$message .= sprintf( __('Click <a href="%s">here</a> to activate your account and login.'), simontaxi_get_bookingsteps_urls('activate-account') . '?code=' . $approval_code . '&uname=' . $user->user_login);
	*/
	// $postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = 'new-user'" );
	$getpost= get_user_template( $template );
	if ( ! $getpost ) {
		return;
	}
	
	/**
	 * @since 2.0.8
	 *
	 * But my site now has two languages and if someone orders in a different language, he gets a notification in Polish.
	 */
	if ( 'file' == simontaxi_get_option( 'vehicle_booking_userregistration_email_body', 'emailtemplate' ) ) {
		ob_start();
		$template = '/templates/emailtemplates/' . $template . '.php';
		if ( simontaxi_is_template_customized( $template ) ) {
			include_once( simontaxi_get_theme_template_dir_name() . $template );
		} else {
			include_once( SIMONTAXI_PLUGIN_PATH . $template );
		}
		$template = ob_get_clean();
	} else {
		$template= $getpost->post_content;
	}

	// $template = $getpost->post_content;
	
	$coupon_codes = get_terms( array( 'taxonomy' => 'coupon_code', 'name' => 'promotion', 'hide_empty' => false ) );
	$coupon_codes = get_terms( array( 'taxonomy' => 'coupon_code', 'name' => 'promotion', 'hide_empty' => false ) );
	$coupon_code = '';
	$today = date_i18n( 'Y-m-d',time() );
	if ( ! empty( $coupon_codes ) && ! is_wp_error( $coupon_codes ) ) {
		foreach ( $coupon_codes as $term_meta ) {
			$term_meta = ( array ) $term_meta;
			$start_date = get_term_meta( $term_meta['term_id'], 'coupon_code_start', true );
			$end_date = get_term_meta( $term_meta['term_id'], 'coupon_code_end', true );

			$start_date = ( $start_date != '' ) ? date( 'Y-m-d', strtotime( $start_date ) ) : '';
			$end_date = ( $end_date != '' ) ? date( 'Y-m-d', strtotime( $end_date ) ) : '';
			if ( ( $today >= $start_date ) && ( $today <= $end_date ) ) {
				$coupon_code = get_term_meta( $term_meta['term_id'], 'coupon_code', true );
			}
		}		
	}
	$pattern = array(
		'/\{BLOG_TITLE\}/',
		'/\{FIRST_NAME\}/',
		'/\{USERNAME\}/',
		'/\{PASSWORD\}/',		
		'/\{ACTIVATION_LINK\}/',
		'/\{DATE\}/',
		'/\{COUPON_CODE\}/',
	);
	
	/* @since 2.0.8
	 */
	$pattern = apply_filters( 'simontaxi_registration_mail_pattern', $pattern );
	
	$url = add_query_arg( array(
		'code' => $approval_code,
		'uname' => $user->user_login,
	), simontaxi_get_bookingsteps_urls('activate-account') );
	
	$activation_link = sprintf( __('Click <a href="%s">here</a> to activate your account and login.'), $url );
	$replacement = array(
		$blogname,
		$user->first_name . ' ' . $user->last_name,
		$user->user_login,
		$plaintext_pass,
		$activation_link,
		simontaxi_date_format( date_i18n( 'Y-m-d',time() ) ),
		$coupon_code,
	);
		
	/**
	 * @since 2.0.8
	 */
	$replacement = apply_filters( 'simontaxi_registration_mail_replacement', $replacement );
	$template =  preg_replace( $pattern, $replacement, $template );
		
	$headers = 'From: ' . $blogname. ' <' . $admin_email. '>' . "\r\n";
	if ( empty( $subject ) ) {
		$subject = simontaxi_get_option( 'vehicle_booking_userregistration_email_subject', '' );
		if ( empty( $subject ) ) {
			$subject = sprintf(__('[%s] Your username and password'), $blogname);
		}
	}
    wp_mail($user->user_email, $subject, $template, $headers);

	remove_filter( 'wp_mail_content_type', 'simontaxi_mail_html_type' );
}

if ( ! function_exists( 'simontaxi_get_additional_pickup_address_title' ) ) {
	/**
	 * Returns 'Additional Pickup Address'
	 *
	 * @since 2.0.8
	 * @return string
	 */
	function simontaxi_get_additional_pickup_address_title() {
		return apply_filters('additional_pickup_address_title', esc_html__( 'Additional Pickup Address', 'simontaxi' ) );
	}
}

if ( ! function_exists( 'simontaxi_get_additional_pickup_address_price_title' ) ) {
	/**
	 * Returns 'Additional Stops'
	 *
	 * @since 2.0.8
	 * @return string
	 */
	function simontaxi_get_additional_pickup_address_price_title() {
		return apply_filters('additional_pickup_address_price_title', esc_html__( ' Additional Stops : ', 'simontaxi' ) );
	}
}

if ( ! function_exists( 'simontaxi_get_additional_pickup_address_title_return' ) ) {
	/**
	 * Returns 'Additional Pickup Address (Return)'
	 *
	 * @since 2.0.8
	 * @return string
	 */
	function simontaxi_get_additional_pickup_address_title_return() {
		return apply_filters('additional_pickup_address_title_return', esc_html__( ' Additional Pickup Address (Return)', 'simontaxi' ) );
	}
}

if ( ! function_exists( 'simontaxi_get_additional_dropoff_address_title' ) ) {
	/**
	 * Returns 'Additional Drop-off Address'
	 *
	 * @since 2.0.8
	 * @return string
	 */
	function simontaxi_get_additional_dropoff_address_title() {
		return apply_filters('additional_dropoff_address_title', esc_html__( 'Additional Drop-off Address', 'simontaxi' ) );
	}
}

if ( ! function_exists( 'simontaxi_get_additional_dropoff_address_title_return' ) ) {
	/**
	 * Returns 'Additional Drop-off Address (Return)'
	 *
	 * @since 2.0.8
	 * @return string
	 */
	function simontaxi_get_additional_dropoff_address_title_return() {
		return apply_filters('additional_dropoff_address_title_return', esc_html__( ' Additional Drop-off Address (Return)', 'simontaxi' ) );
	}
}

if ( ! function_exists( 'simontaxi_get_basic_amount_title' ) ) {
	/**
	 * Returns 'Basic amount' string
	 *
	 * @since 2.0.8
	 * @return string
	 */
	function simontaxi_get_basic_amount_title() {
		return apply_filters('simontaxi_basic_amount_title', esc_html__('Basic amount : ', 'simontaxi') );
	}
}

if ( ! function_exists( 'simontaxi_get_bread_crumb' ) ) {
	/**
	 * Returns Bread crumb on pages
	 *
	 * @since 2.0.8
	 * @return string
	 */
	function simontaxi_get_bread_crumb() {
		ob_start();
		/**
		 * @since 2.0.8
		 */
		$template = '/booking/includes/booking-steps/bread-crumb.php';
		if ( simontaxi_is_template_customized( $template ) ) {
			include_once simontaxi_get_theme_template_dir_name() . $template;
		} else {
			include_once apply_filters( 'simontaxi_locate_bread_crumb', SIMONTAXI_PLUGIN_PATH . $template );
		}
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
}

/**
 * Finds the whether the given time between other two times
 *
 * @param String $start - Start Time
 * @param String $end - End Time
 * @param String $compare - Comparing value
 * @returns bool
 *
 * @since 2.0.8
 */
function simontaxi_is_between_times( $start = null, $end = null, $compare = null ) {
	/*
	$compare = $compare . "h 01m";
	$start = $start . "h 01m";
	$end = $end . "h 01m";
	$f = DateTime::createFromFormat('H\h i\m', $start);
    $t = DateTime::createFromFormat('H\h i\m', $end);
    $i = DateTime::createFromFormat('H\h i\m', $compare);
		
    if ( ! empty( $f ) && ! empty( $t ) && ! empty( $i ) ) {
		if ($f > $t) $t->modify('+1 day');
		return ($f <= $i && $i <= $t) || ($f <= $i->modify('+1 day') && $i <= $t);
	} else {
		return FALSE;
	}
	*/
	
	/*
	$start = $start . ":00 am";
	$end = $end . ":00 pm";
	$compare = $compare . ":00 pm";
	
	$f = DateTime::createFromFormat('H:i:s', $start);
    $t = DateTime::createFromFormat('H:i:s', $end);
    $i = DateTime::createFromFormat('H:i:s', $compare);
	
	if ( ! empty( $f ) && ! empty( $t ) && ! empty( $i ) ) {
		if ($f > $t) $t->modify('+1 day');
		return ($f <= $i && $i <= $t) || ($f <= $i->modify('+1 day') && $i <= $t);
	} else {
		return FALSE;
	}
	*/
	$current_time = "$compare";
	$sunrise = "$start";
	$sunset = "$end";
	// echo $sunrise . '##' . $current_time . '##' . $end;
	$date1 = DateTime::createFromFormat('H:i a', $current_time);
	$date2 = DateTime::createFromFormat('H:i a', $sunrise);
	$date3 = DateTime::createFromFormat('H:i a', $sunset);
	if ( $date1 > $date3 ) {
		$date3 = $date3->modify('+1 day');
	}
	
	if ($date1 > $date2 && $date1 < $date3) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * Finds the whether the given time between other two times
 *
 * @param String $start - Start Time
 * @param String $end - End Time
 * @param String $compare - Comparing value
 * @returns bool
 *
 * @since 2.0.9
 */
function simontaxi_is_between_dates( $start = null, $end = null, $compare = null ) {
	
	$start = simontaxi_strtotime( $start );
	$end = simontaxi_strtotime( $end );
	$compare = simontaxi_strtotime( $compare );
	
	if($compare > $start && $compare < $end) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * Function to test whether the variable is number OR Not
 *
 * @param String $string - Value to test
 * @return bool
 *
 * @since 2.0.8
 */
function simontaxi_is_number( $string ) {
	$return = false;
	if ( ctype_digit( $string ) ) {
		$return = true;
	} elseif ( is_int( $string ) ) {
		$return = true;
	} elseif( is_float( $string ) ) {
		$return = true;
	}
	return $return;
}

/**
 * Since we are using different ways to display Pickup and Dropoff fields, If the value is number we need to take the data from Locations Taxonomy. For easy manipulation we are using this function.
 *
 * @param mixed - Object OR array -  date to take
 * @return string
 *
 * @since 2.0.8
 */
function simontaxi_get_address( $mixed, $key ) {
	if ( is_array( $mixed ) ) {
		if ( 'pickup_location' === $key ) {
			$pickup_location = ! empty( $mixed['pickup_location'] ) ? $mixed['pickup_location'] : '';
			if ( simontaxi_is_number( $pickup_location ) ) {
				$details = get_term( $pickup_location, 'vehicle_locations' );
				$name = $details->name;
				$term_meta = get_term_meta( $pickup_location );
				$location_address = ( ! empty( $term_meta['location_address'] ) ) ? $term_meta['location_address'][0] : '';
				return ( '' !== $location_address ) ? $location_address : $name;
			} else {
				return $pickup_location;
			}
		}
		
		if ( 'drop_location' === $key ) {
			$drop_location = ! empty( $mixed['drop_location'] ) ? $mixed['drop_location'] : '';
			if ( simontaxi_is_number( $drop_location ) ) {
				$details = get_term( $drop_location, 'vehicle_locations' );
				$name = $details->name;
				$term_meta = get_term_meta( $drop_location );
				$location_address = ( ! empty( $term_meta['location_address'] ) ) ? $term_meta['location_address'][0] : '';
				return ( '' !== $location_address ) ? $location_address : $name;
			} else {
				return $drop_location;
			}
		}
	} else {
		if ( 'pickup_location' === $key ) {
			
		}
	}
}

/**
 * For server validation of date format in front end we can use this function.
 *
 * @param string - string to compare date format
 * @param string - target format to compare
 *
 * @since 2.0.8
 */
function simontaxi_is_valid_date($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

/**
 * Add a column to the users list in admin.
 *
 * @since 2.0.8
 */
add_filter( 'manage_users_columns', function( $columns ) {
	$columns['approval_status'] = 'Status';
    return $columns;
}, 10, 1 );

/**
 * return approval status of user
 *
 * @param string - string to compare date format
 * @param column_name - column_name
 * @param user_id - user_id
 *
 * @since 2.0.8
 * @return string
 */
function simontaxi_modify_user_table_row( $val, $column_name, $user_id ) {
    $user_info = get_userdata( $user_id );
	switch ($column_name) {
        case 'approval_status' :
            if ( in_array( 'Customer', $user_info->roles ) ) {
				return get_the_author_meta( 'approval_status', $user_id );
			} else {
				return get_the_author_meta( 'approval_status', $user_id );
			}
            break;
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'simontaxi_modify_user_table_row', 10, 3 );

/**
 * return client system IP
 *
 * @since 2.0.8
 * @return string
 */ 
function simontaxi_get_the_user_ip() {
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
	//check ip from share internet
	$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
	//to check ip is pass from proxy
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
	$ip = $_SERVER['REMOTE_ADDR'];
	}
	return apply_filters( 'wpb_get_ip', $ip );
}

/**
 * return current system date
 *
 * @since 2.0.8
 * @return string
 */ 
function simontaxi_date_format_ymd( $date ) {
	/**
	 * Finally we got the date in English so we can use 'strtotime', Actually strtotime() does not work with format 'd/m/Y' so let us replace '/' with '-'!
	 */
	$date = str_replace('/', '-', $date);
	$date = str_replace('.', '-', $date);
		
	$parse_date = date('Y-m-d', strtotime( $date ) );
	/**
	 * Which means 'strtotime' failed to convert the date let use another way!
	 * Crooked way of doing things!!
	*/
	if ( '1970-01-01' == $parse_date ) {
		$st_date_format_js = simontaxi_get_option( 'st_date_format_js', 'dd-mm-yy' );
		$separator = '-';
		if (strpos($st_date_format_js, '/') !== false) {
			$separator = '/';
		} elseif (strpos($st_date_format_js, '.') !== false) {
			$separator = '.';
		}
		$parts = explode( $separator, $st_date_format_js );
		
		$date_parts = explode( '-', $date );
		$index = 0;
		$date_temp = $month = $year = '';
		foreach( $parts as $part ) {
			if ( in_array( $part, array( 'dd' ) ) ) {
				$date_temp = $date_parts[ $index ];
			}
			if ( in_array( $part, array( 'mm' ) ) ) {
				$month = $date_parts[ $index ];
			}
			if ( in_array( $part, array( 'yy' ) ) ) {
				$year = $date_parts[ $index ];
			}
			$index++;
		}
		$date = $year . '-' . $month . '-' . $date_temp;
		
	} else {
		$date = $parse_date;
	}

	return $date;
}

function simontaxi_update_count( $status = '', $action = 'increase', $user_cache = true ) {
	$key = 'simontaxi_bookings_total';
	if ( ! empty( $key ) ) {
		$key = 'simontaxi_bookings_' . $status;
	}
	$total = record_count( $status, $user_cache );
	if( false === $total ) {
		$total = record_count( $status, $user_cache );
	}
	if ( 'increase' === $action ) {
		$total = $total + 1;
	} elseif ( 'decrease' === $action ) {
		$total = $total - 1;
	}
	update_option( $key, $total );
}

/**
 * @since 2.0.8
 */

 function record_count( $status = '', $user_cache = true ) {
	
	if ( $user_cache ) {
		$key = 'simontaxi_bookings_total';
		if ( ! empty( $key ) ) {
			$key = 'simontaxi_bookings_' . $status;
		}
		$total = get_option( $key, false );
	} else {
		$total = false;
	}
	// If no total stored in DB, use old method of calculating total bookings
	if( false === $total ) {
		global $wpdb;
		
		$bookings = $wpdb->prefix . 'st_bookings';
		$payments = $wpdb->prefix . 'st_payments';
					
		$sql = "SELECT COUNT(*) FROM `" . $bookings . "` INNER JOIN `" . $payments . "` ON `" . $payments . "`.`booking_id`=`" . $bookings . "`.`ID`";
		if ( ! simontaxi_is_user( 'administrator' ) && ! simontaxi_is_user( 'executive' ) ) {
			$sql .= apply_filters( 'simontaxi_bookings_join_condition', '' );
		}
		$sql .= " WHERE `" . $bookings . "`.booking_contacts!='' ";
		$today = date_i18n( 'Y-m-d' );
		if ( $status == '' ) {
			$sql .=" AND `" . $bookings . "`.`status`='new' AND pickup_date < '" . $today . "'";
		} elseif( $status != 'all' ) {
			if ( 'expired' == $status ) {
				$sql .= " AND status='new' AND pickup_date < '" . $today . "'";
			} elseif( 'new' == $status ) {
				$sql .= " AND status='" . $status . "' AND pickup_date >= '" . $today . "'";
			} else {
				$sql .= " AND status='" . $status . "'";
			}
		}
		if ( ! simontaxi_is_user( 'administrator' ) && ! simontaxi_is_user( 'executive' ) ) {
			$sql .= apply_filters( 'simontaxi_bookings_where_condition', '' );
		}
		if ( 'new' === $status ) {
			// echo $sql;die();
		}
		
		$total = $wpdb->get_var( $sql );

		// Store the total for the first time
		update_option( $key, $total );
	}

	return $total;
}

/**
 * return counts of support callback requests
 *
 * @since 2.0.8
 * @return number
 */
function record_count_callback( $is_read = 0 ) {
	global $wpdb;

	$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}st_request_callback WHERE ID!=0 AND is_read=".$is_read;
	
	return $wpdb->get_var( $sql );
}

if ( ! function_exists( 'record_count_support' ) ) { 
	/**
	 * return counts of support tickets
	 *
	 * @since 2.0.8
	 * @return number
	 */
	function record_count_support( $status='' ) {
	  global $wpdb;

	  $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}st_user_support WHERE ID!=0 ";
	  if ( $status !== 'all' ) {
		$sql.=" AND status='" . $status . "'";
	  }
	  return $wpdb->get_var( $sql );
	}
}

add_action('wp_dashboard_setup', 'simontaxi_custom_dashboard_widgets');
if ( ! function_exists( 'simontaxi_custom_dashboard_widgets' ) ) { 
	/**
	 * Add a widget to WP dashboard which lists summary of booking plugin
	 *
	 * @since 2.0.8
	 * @return string
	 */
	function simontaxi_custom_dashboard_widgets() {
		if ( simontaxi_is_user( 'driver' ) ) {
				return;
			}
		add_meta_box('simontaxi_booking_summary', __('Vehicle Booking Summay'), function() {
			global $wpdb;
			
			?>
			<table width="100%">
			<?php do_action('simontaxi_dashboard_widget_top'); ?>
			<tr>
				<td><?php echo "<a class='' href='".admin_url( 'admin.php?page=manage_bookings&status=new' ) . "'>" . esc_html__( 'New', 'simontaxi' ) . " (<span class='status-count bg-danger'>" . record_count( 'new' ) . "</span>)</a>"; ?></td>
				<td><?php echo "<a class='' href='".admin_url( 'admin.php?page=manage_bookings&status=confirmed' ) . "'>" . esc_html__( 'Confirmed', 'simontaxi' ) . " (<span class='status-count bg-danger'>" . record_count( 'confirmed' ) . "</span>)</a>"; ?></td>
				<td><?php echo "<a class='' href='".admin_url( 'admin.php?page=manage_bookings&status=success' ) . "'>" . esc_html__( 'Success', 'simontaxi' ) . " (<span class='status-count bg-danger'>" . record_count( 'success' ) . "</span>)</a>"; ?></td>
			</tr>
			
			<?php
			$args = array(
				'taxonomy' => 'vehicle_locations',
				'hide_empty' => false,
			);
			$locations = get_terms( $args );
			$locations_count = 0;
			if ( ! empty( $locations ) && ! is_wp_error( $locations ) ) {
				$locations_count = count( $locations );
			}
			?>
			<tr>
				<td><?php echo "<a class='' href='".admin_url( 'edit-tags.php?taxonomy=vehicle_locations&post_type=vehicle' ) . "'>" . esc_html__( 'Locations', 'simontaxi' ) . " (<span class='status-count bg-danger'>" . $locations_count . "</span>)</a>"; ?></td>
				<td><?php echo "<a class='' href='".admin_url( 'edit.php?post_type=vehicle' ) . "'>" . esc_html__( 'Vehicles', 'simontaxi' ) . " (<span class='status-count bg-danger'>" . wp_count_posts( 'vehicle' )->publish . "</span>)</a>"; ?></td>
				
				<?php
				$args = array(
					'taxonomy' => 'vehicle_features',
					'hide_empty' => false,
				);
				$vehicle_features = get_terms( $args );
				$vehicle_features_count = 0;
				if ( ! empty( $vehicle_features ) && ! is_wp_error( $vehicle_features ) ) {
					$vehicle_features_count = count( $vehicle_features );
				}
				?>
				<td><?php echo "<a class='' href='".admin_url( 'edit-tags.php?taxonomy=vehicle_features&post_type=vehicle' ) . "'>" . esc_html__( 'Features', 'simontaxi' ) . " (<span class='status-count bg-danger'>" . $vehicle_features_count . "</span>)</a>"; ?></td>
			</tr>
			
			<tr>
				<?php
				$args = array(
					'taxonomy' => 'vehicle_types',
					'hide_empty' => false,
				);
				$vehicle_types = get_terms( $args );
				$vehicle_types_count = 0;
				if ( ! empty( $vehicle_types ) && ! is_wp_error( $vehicle_types ) ) {
					$vehicle_types_count = count( $vehicle_types );
				}
				?>
				
				<td><?php echo "<a class='' href='".admin_url( 'edit-tags.php?taxonomy=vehicle_types&post_type=vehicle' ) . "'>" . esc_html__( 'Types', 'simontaxi' ) . " (<span class='status-count bg-danger'>" . $vehicle_types_count . "</span>)</a>"; ?></td>
				
				<?php
				$args = array(
					'taxonomy' => 'hourly_packages',
					'hide_empty' => false,
				);
				$hourly_packages = get_terms( $args );
				$hourly_packages_count = 0;
				if ( ! empty( $hourly_packages ) && ! is_wp_error( $hourly_packages ) ) {
					$hourly_packages_count = count( $hourly_packages );
				}
				?>
				<td><?php echo "<a class='' href='".admin_url( 'edit-tags.php?taxonomy=hourly_packages&post_type=vehicle' ) . "'>" . esc_html__( 'Hourly', 'simontaxi' ) . " (<span class='status-count bg-danger'>" . $hourly_packages_count . "</span>)</a>"; ?></td>
				
				<?php
				$args = array(
					'taxonomy' => 'coupon_code',
					'hide_empty' => false,
				);
				$coupon_code = get_terms( $args );
				$coupon_code_count = 0;
				if ( ! empty( $coupon_code ) && ! is_wp_error( $coupon_code ) ) {
					$coupon_code_count = count( $coupon_code );
				}
				?>
				<td><?php echo "<a class='' href='".admin_url( 'edit-tags.php?taxonomy=coupon_code&post_type=vehicle' ) . "'>" . esc_html__( 'Coupons', 'simontaxi' ) . " (<span class='status-count bg-danger'>" . $coupon_code_count . "</span>)</a>"; ?></td>
			</tr>
			
			<tr>
				<td><?php echo "<a class='' href='".admin_url( 'edit.php?post_type=vehicle&page=view_request_callback' ) . "'>" . esc_html__( 'Callbacks', 'simontaxi' ) . " (<span class='status-count bg-danger'>" . record_count_callback('0') . "</span>)</a>"; ?></td>
				
				
				<td><?php echo "<a class='' href='".admin_url( 'edit.php?post_type=vehicle&page=view_support_request' ) . "'>" . esc_html__( 'Support', 'simontaxi' ) . " (<span class='status-count bg-danger'>" . record_count_support('new') . "</span>)</a>"; ?></td>
				

				<td><?php echo "<a class='' href='".admin_url( 'edit.php?post_type=vehicle&page=vehicle_settings' ) . "'>" . esc_html__( 'Settings', 'simontaxi' ) . "</a>"; ?></td>
			</tr>
			
			<?php do_action('simontaxi_dashboard_widget_bottom'); ?>
			
			</table>
			<?php
		}, 'dashboard', 'side', 'core');
	}
}

if ( ! function_exists( 'get_user_template' ) ) {
	/**
	 * Returns post of "type" specified Otherwise FALSE 
	 *
	 * @since 2.0.8
	 * @return mixed
	 */
	function get_user_template( $name, $type = 'emailtemplate') {
		global $wpdb;
		$row = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_title='$name' AND post_status='publish' AND post_type = '$type'");
		if( $row == NULL) {
			return FALSE;
		} else {
			return $row;
		}
	}
}

if ( ! function_exists( 'simontaxi_get_return_pickupdate_title' ) ) {
	/**
	 * Returns 'Pick Up Date' title to display in front end. It is useful if user wants to change this to other title
	 *
	 * @since 2.0.8
	 * @return string
	 */
	function simontaxi_get_return_pickupdate_title() {
		return apply_filters( 'simontaxi_filter_return_pickupdate_title', esc_html__( 'Return Pickup Date', 'simontaxi' ) );
	}
}


if ( ! function_exists( 'simontaxi_get_return_pickuptime_title' ) ) {
	/**
	 * Returns 'Pick Up Time' title to display in front end. It is useful if user wants to change this to other title
	 *
	 * @since 2.0.8
	 * @return string
	 */
	function simontaxi_get_return_pickuptime_title() {
		return apply_filters( 'simontaxi_filter_return_pickuptime_title', esc_html__( 'Return Pickup Time', 'simontaxi' ) );
	}
}

if ( ! function_exists( 'simontaxi_get_booking_type' ) ) {
	/**
	 * Returns the display name for booking type. Eg: p2p - Point to Point
	 *
	 * @since 2.0.8
	 * @return string
	 */
	function simontaxi_get_booking_type( $booking_type ) {
		$booking_title = simontaxi_get_p2ptab_title();
		
		$booking_types = simontaxi_booking_types();
		if ( ! empty( $booking_types[ $booking_type ] ) ) {
			$booking_title = $booking_types[ $booking_type ];
		}

		return $booking_title;
	}
}

if ( ! function_exists( 'simontaxi_get_time_display_format' ) ) {
	/**
	 * Returns the display value for given time
	 *
	 * @since 2.0.8
	 * @return string
	 */
	function simontaxi_get_time_display_format( $val ) {
		$st_time_dispaly_format = simontaxi_get_option( 'st_time_dispaly_format', 'standard' );
		
		$val = str_replace(' ', ':', $val);
		
		$display_val = $val;
		// if ( ! empty( $val ) ) 
		{
			$parts = explode( ':', $val );
			
			$mins = '00';
			
			if ( isset( $parts[0] ) ) {
				$val = $parts[0];
			}
			if ( isset( $parts[1] ) ) {
				$mins = $parts[1];
			}
			
			if ( 'standard' === $st_time_dispaly_format ) {
				if ( $val == '0' ) {
					if ( '00' === $mins ) {
						$display_val = esc_html__( 'Midnight', 'simontaxi' );
					} else {
						$display_val = $val . ':' . $mins . ' ' . esc_html__( 'Midnight', 'simontaxi' );
					}
				} elseif ( $val > 12 ) {
					if ( '00' === $mins ) {
						$display_val = ( $val - 12 ) . ' ' . esc_html__( 'PM', 'simontaxi' );
					} else {
						$display_val = ( $val - 12 ) . ':' . $mins . ' ' . esc_html__( 'PM', 'simontaxi' );
					}
				} elseif ( $val == 12 ) {
					if ( '00' === $mins ) {
						$display_val = $val . ' ' . esc_html__( 'Noon', 'simontaxi' );
					} else {
						$display_val = $val . ':' . $mins . ' ' . esc_html__( 'Noon', 'simontaxi' );
					}
				} else {
					if ( '00' === $mins ) {
						$display_val = $val . ' ' . esc_html__( 'AM', 'simontaxi' );
					} else {
						$display_val = $val . ':' . $mins . ' ' . esc_html__( 'AM', 'simontaxi' );
					}
				}
				
				// $display_val .= $mins;
			}
		}
		return $display_val;
	}
}

if ( ! function_exists( 'simontaxi_get_active_plugins' ) ) {
	/**
	 * Returns the all activated plugins across all blogs if it is multisite
	 *
	 * @since 2.0.8
	 * @return string
	 */
	function simontaxi_get_active_plugins() {
		
		$active_plugins = get_option( 'active_plugins' );
		
		$network_active = get_site_option('active_sitewide_plugins');
		
		if ( ! empty( $network_active ) ) {
			foreach( $network_active as $key => $val ) {
				$active_plugins[ $key ] = $val;
			}
		}
		return $active_plugins;
	}
}

if ( ! function_exists( 'simontaxi_user_menu_links' ) ) {
	/**
	 * Returns the user menu links
	 *
	 * @since 2.0.8
	 * @return string
	 */
	function simontaxi_user_menu_links() {
		$user_menu_links = array(
			'book_now' => array(
				'title' => esc_html__( 'Book Now', 'simontaxi' ),
				'url' => simontaxi_get_bookingsteps_urls( 'step1' ),
				'slug' => 'pick-locations',
				'icon' => '<span class="icon icon-plus"></span>',
				'loginrequired' => false,
			),
			'user_account' => array(
				'title' => esc_html__( 'Profile', 'simontaxi' ),
				'url' => simontaxi_get_bookingsteps_urls( 'user_account' ),
				'slug' => 'user-account',
				'icon' => '<span class="icon icon-user"></span>',
				'loginrequired' => true,
			),
			'user_bookings' => array(
				'title' => esc_html__( 'Bookings', 'simontaxi' ),
				'url' => simontaxi_get_bookingsteps_urls( 'user_bookings' ),
				'slug' => 'user-bookings',
				'icon' => '<span class="icon icon-book-open"></span>',
				'loginrequired' => true,
			),
			'user_payments' => array(
				'title' => esc_html__( 'Payments', 'simontaxi' ),
				'url' => simontaxi_get_bookingsteps_urls( 'user_payments' ),
				'slug' => 'user-payments',
				'icon' => '<span class="icon icon-credit-card"></span>',
				'loginrequired' => true,
			),
			'billing_address' => array(
				'title' => esc_html__( 'Billing', 'simontaxi' ),
				'url' => simontaxi_get_bookingsteps_urls( 'billing_address' ),
				'slug' => 'user-billing-address',
				'icon' => '<span class="icon icon-credit-card"></span>',
				'loginrequired' => true,
			),
			'user_support' => array(
				'title' => esc_html__( 'Support', 'simontaxi' ),
				'url' => simontaxi_get_bookingsteps_urls( 'user_support' ),
				'slug' => 'user-support',
				'icon' => '<span class="icon icon-support"></span>',
				'loginrequired' => true,
			),
			'logout' => array(
				'title' => esc_html__( 'Logout', 'simontaxi' ),
				'url' => wp_logout_url( simontaxi_get_bookingsteps_urls( 'login' ) ),
				'slug' => 'logout',
				'icon' => '<span class="icon icon-logout"></span>',
				'loginrequired' => true,
			),
		);
		
		return apply_filters( 'simontaxi_user_menu_links', $user_menu_links );
	}
}

/**
 * return current system date
 *
 * @since 2.0.8
 * @return string
 */ 
function simontaxi_strtotime( $date ) {
	/**
	 * Actually strtotime() does not work with format 'd/m/Y'
	 */
	$date = str_replace('/', '-', $date);
	$date = str_replace('.', '-', $date);
	
	$strtotime = strtotime( $date );
	if ( ! $strtotime ) {
		$st_date_format_js = simontaxi_get_option( 'st_date_format_js', 'dd-mm-yy' );
		$separator = '-';
		if (strpos($st_date_format_js, '/') !== false) {
			$separator = '/';
		} elseif (strpos($st_date_format_js, '.') !== false) {
			$separator = '.';
		}
		$parts = explode( $separator, $st_date_format_js );
		
		$date_parts = explode( '-', $date );
		$index = 0;
		$date_temp = $month = $year = '';
		foreach( $parts as $part ) {
			if ( in_array( $part, array( 'dd' ) ) ) {
				$date_temp = $date_parts[ $index ];
			}
			if ( in_array( $part, array( 'mm' ) ) ) {
				$month = $date_parts[ $index ];
			}
			if ( in_array( $part, array( 'yy' ) ) ) {
				$year = $date_parts[ $index ];
			}
			$index++;
		}
		$date = $year . '-' . $month . '-' . $date_temp;
		$strtotime = strtotime( $date );
	}
	return $strtotime;
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var Data to sanitize.
 * @return string|array
 */
function simontaxi_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'simontaxi_clean', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}

if ( ! function_exists( 'simontaxi_get_booking_details' ) ) :
	/**
	 * Simontaxi Booking statuses
	 *
	 * @return array
	 * @since 2.0.9
	 */
	function simontaxi_get_booking_details( $args = array() ) {
		global $wpdb;

		$a = shortcode_atts( array(
				'booking_id' => '',
				'select' => '*',
				'where' => '',
				'operation_type' => 'get_row',
				'join' => array(
					'table' => '',
					'type' => 'INNER',
					'left' => '',
					'right' => '',
					'additional' => '',
				),
			), $args );
		$sql = "SELECT {$a['select']} FROM {TBL_ST_BOOKINGS} as b";
		if ( ! empty($a['booking_id'])) {
			$sql .= "WHERE b.ID = " . $a['booking_id'];
		}
		return $wpdb->{$a['operation_type']}( $sql );
	}
endif;

if ( ! function_exists( 'simontaxi_is_airport' ) ) :
	/**
	 * Return the given location is airport
	 *
	 * @since 2.0.9
	 * @return bool
	 */
	function simontaxi_is_airport( $term_str ) {
		$airports = get_term_by( 'id', $term_str, 'vehicle_locations' );
		
		if ( empty( $airports ) ) {
			$airports = get_term_by( 'name', $term_str, 'vehicle_locations' );
		}
		$is_airport = false;
		if ( ! empty( $airports ) && ! is_wp_error( $airports ) ) {
			$term = $airports;
			$location_type = get_term_meta( $term->term_id, 'location_type', true );
			if ( 'airport' === $location_type ) {
				$is_airport = true;
			}
		}
		return $is_airport;
	}
endif;

if ( ! function_exists( 'simontaxi_get_session_value' ) ) :
	/**
	 * Return the value for given value
	 *
	 * @since 2.0.9
	 * @return string
	 */
	function simontaxi_get_session_value( $key, $step, $default = '' ) {
		$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
		$booking_step2 = simontaxi_get_session( 'booking_step2', array() );
		$booking_step3 = simontaxi_get_session( 'booking_step3', array() );
		$booking_step4 = simontaxi_get_session( 'booking_step4', array() );
		
		$value = $default;
		if ( 'step1' === $step ) {
			$value = simontaxi_get_session( 'booking_step1', $default, $key );
		} elseif ( 'step2' === $step ) {
			$value = simontaxi_get_session( 'booking_step2', $default, $key );
		} elseif ( 'step3' === $step ) {
			$value = simontaxi_get_session( 'booking_step3', $default, $key );
		} elseif ( 'step4' === $step ) {
			$value = simontaxi_get_session( 'booking_step4', $default, $key );
		}
		return $value;
	}
endif;

if ( ! function_exists( 'simontaxi_get_post_by_slug' ) ) :
	/**
	 * Return the single post with given slug
	 *
	 * @since 2.0.9
	 * @return bool|Object
	 */
	function simontaxi_get_post_by_slug( $slug, $post_type = 'emailtemplate' ){
		$posts = get_posts(array(
				'name' => $slug,
				'posts_per_page' => 1,
				'post_type' => $post_type,
				'post_status' => 'publish'
		));
		
		if( ! $posts ) {
			return false;
		}
		
		return $posts[0];
	}
	
endif;

if ( ! function_exists( 'simontaxi_is_file_exists' ) ) :
	/**
	 * Return the true|false
	 *
	 * @since 2.0.9
	 * @return bool
	 */
	function simontaxi_is_file_exists( $template_path ){
		$exists = false;
		if ( simontaxi_is_template_customized( $template_path ) ) {
			$exists = true;
		} elseif ( file_exists( SIMONTAXI_PLUGIN_PATH . $template ) ) {
			$exists = true;
		}
		return $exists;
	}
	
endif;

if ( ! function_exists( 'simontaxi_get_price_decimal_separator' ) ) :
	/**
	 * Return the decimal separator for prices.
	 *
	 * @since  2.0.9
	 * @return string
	 */
	function simontaxi_get_price_decimal_separator() {
		$decimal_separator = simontaxi_get_option( 'decimal_separator', '.' );
		$separator = apply_filters( 'simontaxi_get_price_decimal_separator', $decimal_separator );
		return $separator ? stripslashes( $separator ) : '.';
	}
endif;

if ( ! function_exists( 'simontaxi_get_price_decimals' ) ) :
/**
 * Return the number of decimals after the decimal point.
 *
 * @since  2.0.9
 * @return int
 */
function simontaxi_get_price_decimals() {
	$number_of_decimals = simontaxi_get_option( 'number_of_decimals', 2 );
	return absint( apply_filters( 'simontaxi_get_price_decimals', $number_of_decimals ) );
}
endif;

if ( ! function_exists( 'simontaxi_get_rounding_precision' ) ) :
/**
 * Get rounding precision for internal Simontaxi calculations.
 * Will increase the precision of simontaxi_get_price_decimals by 2 decimals, unless SIMONTAXI_ROUNDING_PRECISION is set to a higher number.
 *
 * @since 2.0.9
 * @return int
 */
function simontaxi_get_rounding_precision() {
	$precision = simontaxi_get_price_decimals() + 2;
	if ( absint( SIMONTAXI_ROUNDING_PRECISION ) > $precision ) {
		$precision = absint( SIMONTAXI_ROUNDING_PRECISION );
	}
	return $precision;
}
endif;

/**
 * Format decimal numbers ready for DB storage.
 *
 * Sanitize, remove decimals, and optionally round + trim off zeros.
 *
 * This function does not remove thousands - this should be done before passing a value to the function.
 *
 * @param  float|string $number     Expects either a float or a string with a decimal separator only (no thousands).
 * @param  mixed        $dp number  Number of decimal points to use, blank to use false to avoid all rounding.
 * @param  bool         $trim_zeros From end of string.
 * @return string
 */
function simontaxi_format_decimal( $number, $dp = false, $trim_zeros = false ) {
	$locale   = localeconv();
		
	$decimals = array( simontaxi_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'] );

	// Remove locale from string.
	if ( ! is_float( $number ) ) {
		$number = str_replace( $decimals, '.', $number );
		$number = preg_replace( '/[^0-9\.,-]/', '', simontaxi_clean( $number ) );
	}

	if ( false !== $dp ) {
		$dp     = intval( '' === $dp ? simontaxi_get_price_decimals() : $dp );
		$number = number_format( floatval( $number ), $dp, '.', '' );
	} elseif ( is_float( $number ) ) {
		// DP is false - don't use number format, just return a string using whatever is given. Remove scientific notation using sprintf.
		$number     = str_replace( $decimals, '.', sprintf( '%.' . simontaxi_get_rounding_precision() . 'f', $number ) );
		// We already had a float, so trailing zeros are not needed.
		$trim_zeros = true;
	}

	if ( $trim_zeros && strstr( $number, '.' ) ) {
		$number = rtrim( rtrim( $number, '0' ), '.' );
	}

	return $number;
}

if ( ! function_exists( 'simontaxi_get_dropofftime_title' ) ) {
	/**
	 * Returns 'Drop-off Time' title to display in front end. It is useful if user wants to change this to other title
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_dropofftime_title() {
		return apply_filters( 'simontaxi_filter_dropofftime_title', esc_html__( 'Dropoff Time', 'simontaxi' ) );
	}
}

function simontaxi_is_admin_user() {
	$admin_users = apply_filters( 'simontaxi_admin_users', 
		array(
			'administrator',
			'executive',
		)
	);
	
	$is_admin_user = false;
	if ( is_super_admin() ) {
		$is_admin_user = true;
	} else {
		foreach( $admin_users as $admin_user ) {
			if ( simontaxi_is_user( $admin_user ) ) {
				$is_admin_user = true;
			}
		}
	}
	return $is_admin_user;
}

function simontaxi_is_vendor_user() {
	$admin_users = apply_filters( 'simontaxi_vendor_users', 
		array(
			'driver',
		)
	);
	
	$is_admin_user = false;
	if ( is_super_admin() ) {
		$is_admin_user = true;
	} else {
		foreach( $admin_users as $admin_user ) {
			if ( simontaxi_is_user( $admin_user ) ) {
				$is_admin_user = true;
			}
		}
	}
	return $is_admin_user;
}

function simontaxi_is_user_can_edit( $type = 'booking' ) {
	$admin_users = apply_filters( "simontaxi_is_user_can_edit_$type", 
		array(
			'administrator',
			'executive',
		)
	);
	
	$can_edit = false;
	if ( is_super_admin() ) {
		$can_edit = true;
	} else {
		foreach( $admin_users as $admin_user ) {
			if ( simontaxi_is_user( $admin_user ) ) {
				$can_edit = true;
			}
		}
	}

	return $can_edit;
}

function simontaxi_do_registration( $data ) {
	$first_name = ! empty( $data['first_name'] ) ? $data['first_name'] : '';
	$last_name = ! empty( $data['last_name'] ) ? $data['last_name'] : '';
	$user_nicename = ! empty( $data['full_name'] ) ? $data['full_name'] : '';
	if ( empty( $user_nicename ) ) {
		$user_nicename = $first_name;
		if ( ! empty( $last_name ) ) {
			$user_nicename = $first_name . ' ' . $last_name;
		}
	}
	$user_args = array(
		'user_login'      => isset( $data['email'] ) ? $data['email'] : '',
		'user_pass'       => wp_generate_password(8),
		'user_email'      => isset( $data['email'] ) ? $data['email'] : '',
		'first_name'      => $first_name,
		'last_name'       => $last_name,
		'user_nicename'		=> $user_nicename,
		'display_name' 		=> $user_nicename,
		'nickname' 			=> $user_nicename,
		'user_registered'	=> date( 'Y-m-d H:i:s' ),
		'role'           	=> 'Customer',
		);
	$user_id = wp_insert_user( $user_args );
	
	if ( ! empty( $data['mobile_countrycode'] ) ) {
		update_user_meta( absint( $user_id ), 'mobile_countrycode', wp_kses_post( $data['mobile_countrycode'] ) );
	}
	if ( ! empty( $data['mobile'] ) ) {
		update_user_meta( absint( $user_id ), 'mobile', wp_kses_post( $data['mobile'] ) );
	}
	
	update_user_meta( absint( $user_id ), 'approval_code', wp_generate_password(8) );
	update_user_meta( absint( $user_id ), 'approval_status', 'pending' );
	
	simontaxi_registration_email_alert( $user_id, $user_args['user_pass'] );
	
	return $user_id;
}

/**
 * @since 2.0.9
 */
function simontaxi_is_assoc_array( $array ){
    if ( is_array( $array ) !== true ) {
        return false;
    }else{
        $check = json_decode( json_encode( $array ) );
        if ( is_object ( $check ) === true ) {
            return true;
        }else{
            return false;
        }
    }
}

/**
 * Calculate the arrival time of the booking
 *
 * @param date_time $start_date_time     Date and time.
 * @param number|string $duration Dutation in minutes
 * @param boolean $need_process - If it s true the $duration string is need to process
 *
 * return string
 * @since 2.0.9
 */
function simontaxi_arrival_on( $start_date_time, $duration, $need_process = false ) {
    $str = $start_date_time;
	$minutes = 0;
	if ( $need_process ) {
		$duration_parts = explode( ' ', $duration );
		
		if ( ! empty( $duration_parts ) ) {
			for( $i = 0; $i < count( $duration_parts ); $i++ ) {
				$part = trim( $duration_parts[ $i ] );
				if ( in_array( $part, array( 'Minute', 'Minutes' ) ) ) {
					$duration_minutes = ! empty( $duration_parts[ $i-1 ] ) ? trim( $duration_parts[ $i-1 ] ) : 0;
					
					$minutes = $minutes + $duration_minutes;
				}
				if ( in_array( $part, array( 'Hour', 'Hours' ) ) ) {
					$hours = ! empty( $duration_parts[ $i-1 ] ) ? trim( $duration_parts[ $i-1 ] ) : 0;
					$hours_minutes = 0;
					$hours_parts = explode( '.', $hours );
					if ( count( $hours_parts ) > 1 ) {
						$hours = $hours_parts[0];
						$hours_minutes = $hours_parts[1];
					}
					$minutes = $minutes + $hours_minutes + ( $hours * 60 );
				}
				if ( in_array( $part, array( 'Day', 'Days' ) ) ) {
					$days = ! empty( $duration_parts[ $i-1 ] ) ? trim( $duration_parts[ $i-1 ] ) : 0;
					
					$minutes = $minutes + ( $days * 24 * 60 );
				}
			}
		}
	}
	$str = simontaxi_date_format( date('Y-m-d H:i', strtotime( "+$minutes minutes", strtotime( $start_date_time ) ) ), true );
	return $str;
}
include_once( SIMONTAXI_PLUGIN_PATH . 'booking/defaults.php' );
