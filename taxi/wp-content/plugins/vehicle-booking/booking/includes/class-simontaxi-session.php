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

/**
 * Simontaxi_Session Class
 *
 * @since 1.5
 */
class Simontaxi_Session {

	/**
	 * Holds our session data
	 *
	 * @var array
	 * @access private
	 * @since 2.0.0
	 */
	private $session;


	/**
	 * Whether to use PHP $_SESSION or WP_Session
	 *
	 * @var bool
	 * @access private
	 * @since 2.0.0
	 */
	private $use_php_sessions = false;

	/**
	 * Session index prefix
	 *
	 * @var string
	 * @access private
	 * @since 2.0.0
	 */
	private $prefix = 'stvb_';


	/**
	 * Get things started
	 *
	 * Defines our WP_Session constants, includes the necessary libraries and
	 * retrieves the WP Session instance
	 *
	 * @since 1.5
	 */
	public function __construct() {

		$this->use_php_sessions = $this->use_php_sessions();

		if ( $this->use_php_sessions ) {

			if ( is_multisite() ) {

				$this->prefix = '_' . get_current_blog_id();

			}

			// Use PHP SESSION
			add_action( 'init', array( $this, 'maybe_start_session' ), -2 );

		} else {

			if( ! $this->should_start_session() ) {
				return;
			}

			// Use WP_Session (default)
			if ( ! defined( 'WP_SESSION_COOKIE' ) ) {
				define( 'WP_SESSION_COOKIE', 'simontaxi_wp_session' );
			}

			add_filter( 'wp_session_expiration_variant', array( $this, 'set_expiration_variant_time' ), 99999 );
			add_filter( 'wp_session_expiration', array( $this, 'set_expiration_time' ), 99999 );

		}

		if ( empty( $this->session ) && ! $this->use_php_sessions ) {
			add_action( 'plugins_loaded', array( $this, 'init' ), -1 );
		} else {
			add_action( 'init', array( $this, 'init' ), -1 );
		}

	}

	/**
	 * Setup the WP_Session instance
	 *
	 * @access public
	 * @since 1.5
	 * @return void
	 */
	public function init() {

		if( $this->use_php_sessions ) {
			$this->session = isset( $_SESSION[ $this->prefix ] ) && is_array( $_SESSION[ $this->prefix ] ) ? $_SESSION[ $this->prefix ] : array();
		} else {
			$this->session = WP_Session::get_instance();
		}

		return $this->session;
	}


	/**
	 * Retrieve session ID
	 *
	 * @access public
	 * @since 2.0.0
	 * @return string Session ID
	 */
	public function get_id() {
		return $this->session->session_id;
	}


	/**
	 * Retrieve a session variable
	 *
	 * @access public
	 * @since 2.0.0
	 * @param string $key Session key
	 * @return string Session variable
	 */
	public function get( $key ) {
		if ( '' === $key ) {
			return $this->session;
		} else {
			$key = sanitize_key( $key );
			return isset( $this->session[ $key ] ) ? maybe_unserialize( $this->session[ $key ] ) : false;
		}
	}

	/**
	 * Set a session variable
	 *
	 * @since 2.0.0
	 *
	 * @param string $key Session key
	 * @param integer $value Session variable
	 * @return string Session variable
	 */
	public function set( $key, $value ) {

		$key = sanitize_key( $key );

		if ( is_array( $value ) ) {
			$this->session[ $key ] = serialize( $value );
		} else {
			$this->session[ $key ] = $value;
		}
		if( $this->use_php_sessions ) {
			$_SESSION[ $this->prefix ] = $this->session;
		}

		return $this->session[ $key ];
	}

	/**
	 * Force the session expiration variant time to 23 hours
	 *
	 * @access public
	 * @since 2.0.0
	 * @param int $exp Default expiration (1 hour)
	 * @return int
	 */
	public function set_expiration_variant_time( $exp ) {
		return ( 30 * 60 * 23 );
	}

	/**
	 * Force the session expiration time to 24 hours
	 *
	 * @access public
	 * @since 2.0.0
	 * @param int $exp Default expiration (1 hour)
	 * @return int Cookie expiration time
	 */
	public function set_expiration_time( $exp ) {
		return ( 30 * 60 * 24 );
	}

	/**
	 * Starts a new session if one hasn't started yet.
	 *
	 * @return boolean
	 * Checks to see if the server supports PHP sessions
	 *
	 * @access public
	 * @since 2.0.0
	 * @return boolean $ret True if we are using PHP sessions, false otherwise
	 */
	public function use_php_sessions() {

		$ret = false;

		// If the database variable is already set, no need to run autodetection
		$simontaxi_use_php_sessions = (bool) get_option( 'simontaxi_use_php_sessions' );

		if ( ! $simontaxi_use_php_sessions ) {

			// Attempt to detect if the server supports PHP sessions
			if( function_exists( 'session_start' ) ) {

				$this->set( 'simontaxi_use_php_sessions', 1 );

				if( $this->get( 'simontaxi_use_php_sessions' ) ) {

					$ret = true;

					// Set the database option
					update_option( 'simontaxi_use_php_sessions', true );

				}

			}

		} else {
			$ret = $simontaxi_use_php_sessions;
		}


		return (bool) $ret;
	}



	/**
	 * Determines if we should start sessions
	 *
	 * @since  2.0.0
	 * @return bool
	 */
	public function should_start_session() {

		$start_session = true;

		if( ! empty( $_SERVER[ 'REQUEST_URI' ] ) ) {

			$blacklist = $this->get_blacklist();
			$uri       = ltrim( $_SERVER[ 'REQUEST_URI' ], '/' );
			$uri       = untrailingslashit( $uri );

			if( in_array( $uri, $blacklist ) ) {
				$start_session = false;
			}

			if( false !== strpos( $uri, 'feed=' ) ) {
				$start_session = false;
			}

		}

		return apply_filters( 'simontaxi_start_session', $start_session );

	}

	/**
	 * Retrieve the URI blacklist
	 *
	 * These are the URIs where we never start sessions
	 *
	 * @since  2.0.0
	 * @return array
	 */
	public function get_blacklist() {

		$blacklist = apply_filters( 'simontaxi_session_start_uri_blacklist', array(
			'feed',
			'feed/rss',
			'feed/rss2',
			'feed/rdf',
			'feed/atom',
			'comments/feed'
		) );

		// Look to see if WordPress is in a sub folder or this is a network site that uses sub folders
		$folder = str_replace( network_home_url(), '', get_site_url() );

		if( ! empty( $folder ) ) {
			foreach( $blacklist as $path ) {
				$blacklist[] = $folder . '/' . $path;
			}
		}

		return $blacklist;
	}

	/**
	 * Starts a new session if one hasn't started yet.
	 */
	public function maybe_start_session() {

		if( ! $this->should_start_session() ) {
			return;
		}

		if( ! session_id() && ! headers_sent() ) {
			session_start();
		}
	}

}
