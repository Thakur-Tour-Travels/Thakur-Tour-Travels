<?php
/**
 * Plugin Updater
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  Updater
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Simontaxi - Vehicle Booking updating manager.
 */
class Simontaxi_Updater {
	/**
	 * @var string
	 */
	protected $api_url = 'https://cdn.wptaxitheme.com/wp/updates/api/index.php';

	/**
	 * @var string
	 */
	public $title = 'Simontaxi - Vehicle Booking';

	/**
	 * @var string
	 */
	 public $plugin_slug = 'vehicle-booking';

	 /**
	 * @var string
	 */
	 public $plugin_slug_index = 'index';

	/**
	 * @var bool
	 */
	protected $auto_updater;

	public function init() {
		if ( defined('SIMONTAXI_UPDATE_DEBUG_MODE') && SIMONTAXI_UPDATE_DEBUG_MODE ) {
			set_site_transient('update_plugins', null); // Need to comment once the functionality done.
			if ( defined('SIMONTAXI_UPDATE_URL_DEBUG_MODE') && SIMONTAXI_UPDATE_URL_DEBUG_MODE ) {
				$this->api_url = 'https://cdn.wptaxitheme.com/wp/updates_test/api/index.php';
			}
		}
		
		add_filter( 'pre_set_site_transient_update_plugins', array(
			$this,
			'check_update',
		) );
		// Take over the Plugin info screen
		add_filter('plugins_api', array(
			$this,
			'plugin_api_call',
		), 10, 3);
	}

	/**
	 * Add our self-hosted autoupdate plugin to the filter transient
	 *
	 * @param $transient
	 *
	 * @return object $ transient
	 */
	public function check_update( $checked_data ) {
		global $wp_version;
		
		$purchase_code = simontaxi_get_option( 'simontaxi_purchase_code', '' );
		if ( empty( $purchase_code ) ) {
			return $checked_data;
		} else {
			$res = simontaxi_validate_envato( $purchase_code );
			if ( false === $res ) {
				return $checked_data;
			}
		}

		$args = array(
			'slug' => $this->plugin_slug,
			'version' => SIMONTAXI_VERSION,
		);

		if ( ! empty( $checked_data->checked[ $this->plugin_slug .'/'. $this->plugin_slug_index .'.php' ] ) ) {
			$args['version'] = $checked_data->checked[ $this->plugin_slug .'/'. $this->plugin_slug_index .'.php' ];
		}
		if ( ! empty( $checked_data->response[ $this->plugin_slug .'/'. $this->plugin_slug_index .'.php' ] ) ) {
			$args['version'] = $checked_data->response[ $this->plugin_slug .'/'. $this->plugin_slug_index .'.php' ];
		}
		
		$request_string = array(
				'body' => array(
					'action' => 'basic_check',
					'request' => serialize( $args ),
					'api-key' => md5( get_bloginfo('url') )
				),
				'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
			);

		// Start checking for an update
		$raw_response = wp_remote_post( $this->api_url, $request_string );

		if ( ! is_wp_error( $raw_response ) && ( $raw_response['response']['code'] == 200 ) ) {
			$response = unserialize( $raw_response['body'] );
		}

		if ( is_object( $response ) && ! empty( $response ) ) { // Feed the update data into WP updater
			$checked_data->response[$this->plugin_slug . '/' . $this->plugin_slug_index . '.php'] = $response;
		}

		return $checked_data;
	}

	/**
	 * Add our self-hosted autoupdate plugin to the filter transient
	 *
	 * @param $transient
	 *
	 * @return object $ transient
	 */
	function plugin_api_call( $def, $action, $args ) {

		global $wp_version;

		if ( ! isset( $args->slug ) || ( $args->slug != $this->plugin_slug ) ) {
			return false;
		}

		// Get the current version
		$plugin_info = get_site_transient('update_plugins');
		
		$current_version = $plugin_info->checked[ $this->plugin_slug . '/' . $this->plugin_slug_index .'.php' ];
		$args->version = $current_version;


		$request_string = array(
				'body' => array(
					'action' => $action,
					'request' => serialize( $args ),
					'api-key' => md5( get_bloginfo('url') )
				),
				'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
			);

		$request = wp_remote_post( $this->api_url, $request_string );

		if ( is_wp_error( $request ) ) {
			$res = new WP_Error('plugins_api_failed', __( 'An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>' ), $request->get_error_message() );
		} else {
			$res = unserialize( $request['body'] );
			if ( $res === false ) {
				$res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
			}
		}
		return $res;
	}
}
