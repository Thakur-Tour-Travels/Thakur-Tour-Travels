<?php
/**
 * Error Handling
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  includes
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Print Errors
 *
 * Prints all stored errors. For use during checkout.
 * If errors exist, they are returned.
 *
 * @since 2.0.9
 * @uses simontaxi_get_errors()
 * @uses simontaxi_clear_errors()
 * @return void
 */
function simontaxi_print_array_info( $errors, $type = 'error', $classes = array( 'alert', 'alert-danger' ) ) {
	$classes = apply_filters( 'simontaxi_array_error_class', $classes );
	$title = apply_filters( 'simontaxi_error_title', esc_html__( 'Error : ', 'simontaxi' ) );
	if ( 'success' === $type ) {
		$title = apply_filters( 'simontaxi_success_title', esc_html__( 'Success : ', 'simontaxi' ) );
		$classes = apply_filters( 'simontaxi_array_success_class', $classes );
	}elseif ( 'info' === $type ) {
		$title = apply_filters( 'simontaxi_info_title', esc_html__( 'Info : ', 'simontaxi' ) );
		$classes = apply_filters( 'simontaxi_array_info_class', $classes );
	}
	
	echo '<div class="' . implode( ' ', $classes ) . '"><ul>';
		// Loop error codes and display errors
	   foreach ( $errors as $error_id => $error ) {
			if ( is_array( $error ) ) {
				foreach( $error as $e ) {
					if ( is_array( $e ) ) {
						foreach( $e as $e1 ) {
							echo '<li><strong>' . $title . '</strong> ' . $e1 . '</li>';
						}
					} else {
					echo '<li><strong>' . $title . '</strong> ' . $e . '</li>';
					}
				}
			} else {
			echo '<li><strong>' . $title . '</strong> ' . $error . '</li>';
			}
	   }
	echo '</ul></div>';
	
	simontaxi_clear_errors();
	simontaxi_clear_messages();
	simontaxi_clear_infomessages();
}

/**
 * Print Errors
 *
 * Prints all stored errors. For use during checkout.
 * If errors exist, they are returned.
 *
 * @since 1.0
 * @uses simontaxi_get_errors()
 * @uses simontaxi_clear_errors()
 * @return void
 */
function simontaxi_print_errors( $key = '' ) {
	if ( empty( $key ) ) {
		$errors = simontaxi_get_errors( $key );
	} else {
		$errors = simontaxi_get_errors( $key . '_errors' );
	}
		
	$wp_error = new WP_Error();
	if ( $errors ) {
		$classes = apply_filters( 'simontaxi_error_class', array(
			'alert', 'alert-danger'
		) );
		$title = apply_filters( 'simontaxi_error_title', esc_html__( 'Error : ', 'simontaxi' ) );
		echo '<div class="' . implode( ' ', $classes ) . '"><ul>';
		    // Loop error codes and display errors
		   foreach ( $errors as $error_id => $error ) {
		        if ( is_array( $error ) ) {
					foreach( $error as $e ) {
						if ( is_array( $e ) ) {
							foreach( $e as $e1 ) {
								echo '<li><strong>' . $title . '</strong> ' . $e1 . '</li>';
							}
						} else {
						echo '<li><strong>' . $title . '</strong> ' . $e . '</li>';
						}
					}
				} else {
				echo '<li><strong>' . $title . '</strong> ' . $error . '</li>';
				}
		   }
		echo '</ul></div>';
	} elseif ( ! empty( $wp_error->errors ) ) {
		$classes = apply_filters( 'simontaxi_error_class', array(
			'alert', 'alert-danger'
		) );
		$title = apply_filters( 'simontaxi_error_title', esc_html__( 'Error : ', 'simontaxi' ) );
		echo '<div class="' . implode( ' ', $classes ) . '"><ul>';
		    // Loop error codes and display errors
		   foreach ( $errors as $error_id => $error ) {
		        if ( is_array( $error ) ) {
					foreach( $error as $e ) {
						echo '<li><strong>' . $title . '</strong> ' . $e . '</li>';
					}
				} else {
					echo '<li><strong>' . $title . '</strong> ' . $error . '</li>';
				}
		   }
		echo '</ul></div>';
	}
	if ( empty( $key ) ) {
		$messages = simontaxi_get_messages( $key );
	} else {
		$messages = simontaxi_get_messages( $key . '_messages' );
	}

	if ( $messages ) {
		$classes = apply_filters( 'simontaxi_success_class', array(
			'alert', 'alert-success'
		) );
		$title = apply_filters( 'simontaxi_success_title', esc_html__( 'Success : ', 'simontaxi' ) );
		echo '<div class="' . implode( ' ', $classes ) . '"><ul>';
		    // Loop error codes and display errors
		   foreach ( $messages as $error_id => $error ) {
		        echo '<li><strong>' . $title . '</strong> ' . $error . '</li>';
		   }
		echo '</ul></div>';
	}
	
	if ( empty( $key ) ) {
		$messages = simontaxi_get_infomessages( $key );
	} else {
		$messages = simontaxi_get_infomessages( $key . '_infomessages' );
	}
	if ( $messages ) {
		$classes = apply_filters( 'simontaxi_info_class', array(
			'alert', 'alert-info'
		) );
		$title = apply_filters( 'simontaxi_info_title', esc_html__( 'Info : ', 'simontaxi' ) );
		echo '<div class="' . implode( ' ', $classes ) . '"><ul>';
		    // Loop error codes and display errors
		   foreach ( $messages as $error_id => $error ) {
		        echo '<li><strong>' . $title . '</strong> ' . $error . '</li>';
		   }
		echo '</ul></div>';
	}
	
	simontaxi_clear_errors();
	simontaxi_clear_messages();
	simontaxi_clear_infomessages();
}
add_action( 'simontaxi_purchase_form_before_submit', 'simontaxi_print_errors' );
add_action( 'simontaxi_ajax_checkout_errors', 'simontaxi_print_errors' );
add_action( 'simontaxi_print_errors', 'simontaxi_print_errors' );

/**
 * Get Errors
 *
 * Retrieves all error messages stored during the checkout process.
 * If errors exist, they are returned.
 *
 * @since 2.0.0
 * @uses Simontaxi_Session::get()
 * @return mixed array if errors are present, false if none found
 */
function simontaxi_get_errors( $key = '' ) {
	$errors = STVB()->session->get( 'simontaxi_errors' );	
	if ( ! empty( $key ) ) {
		if ( isset( $errors[ $key ] ) ) {
			$errors = $errors[ $key ];
		} else {
			$errors = array();
		}
	}
	return $errors;
}

/**
 * Get Messages
 *
 * Retrieves all error messages stored during the checkout process.
 * If errors exist, they are returned.
 *
 * @since 2.0.0
 * @uses Simontaxi_Session::get()
 * @return mixed array if errors are present, false if none found
 */
function simontaxi_get_messages( $key = '' ) {
	$messages = STVB()->session->get( 'simontaxi_messages' );
	if ( ! empty( $key ) ) {
		if ( isset( $messages[ $key ] ) ) {
			$messages = $messages[ $key ];
		} else {
			$messages = array();
		}
	}
	return $messages;
}

/**
 * Get Messages
 *
 * Retrieves all error messages stored during the checkout process.
 * If errors exist, they are returned.
 *
 * @since 2.0.0
 * @uses Simontaxi_Session::get()
 * @return mixed array if errors are present, false if none found
 */
function simontaxi_get_infomessages( $key = '' ) {
	$infomessages = STVB()->session->get( 'simontaxi_infomessages' );
	if ( ! empty( $key ) ) {
		if ( isset( $infomessages[ $key ] ) ) {
			$infomessages = $infomessages[ $key ];
		} else {
			$infomessages = array();
		}
	}
	return $infomessages;
}

/**
 * Set Error
 *
 * Stores an error in a session var.
 *
 * @since 2.0.0
 * @uses Simontaxi_Session::get()
 * @param int $error_id ID of the error being set
 * @param string $error_message Message to store with the error
 * @return void
 */
function simontaxi_set_error( $error_id, $error_message ) {
	$errors = simontaxi_get_errors();
	if ( ! $errors ) {
		$errors = array();
	}
	$errors[ $error_id ] = $error_message;
	STVB()->session->set( 'simontaxi_errors', $errors );
}

/**
 * Set Error
 *
 * Stores an error in a session var.
 *
 * @since 2.0.0
 * @uses Simontaxi_Session::get()
 * @param int $error_id ID of the error being set
 * @param string $error_message Message to store with the error
 * @return void
 */
function simontaxi_set_message( $error_id, $error_message ) {
	$errors = simontaxi_get_messages();
	if ( ! $errors ) {
		$errors = array();
	}
	$errors[ $error_id ] = $error_message;
	STVB()->session->set( 'simontaxi_messages', $errors );
}

/**
 * Set Error
 *
 * Stores an error in a session var.
 *
 * @since 2.0.0
 * @uses Simontaxi_Session::get()
 * @param int $error_id ID of the error being set
 * @param string $error_message Message to store with the error
 * @return void
 */
function simontaxi_set_infomessage( $error_id, $error_message ) {
	$errors = simontaxi_get_infomessages();
	if ( ! $errors ) {
		$errors = array();
	}
	$errors[ $error_id ] = $error_message;
	STVB()->session->set( 'simontaxi_infomessages', $errors );
}

/**
 * Clears all stored errors.
 *
 * @since 2.0.0
 * @uses Simontaxi_Session::set()
 * @return void
 */
function simontaxi_clear_errors() {
	STVB()->session->set( 'simontaxi_errors', null );
}

/**
 * Clears all stored errors.
 *
 * @since 2.0.0
 * @uses Simontaxi_Session::set()
 * @return void
 */
function simontaxi_clear_messages() {
	STVB()->session->set( 'simontaxi_messages', null );
}

/**
 * Clears all stored errors.
 *
 * @since 2.0.0
 * @uses Simontaxi_Session::set()
 * @return void
 */
function simontaxi_clear_infomessages() {
	STVB()->session->set( 'simontaxi_infomessages', null );
}

/**
 * Removes (unsets) a stored error
 *
 * @since 1.3.4
 * @uses Simontaxi_Session::set()
 * @param int $error_id ID of the error being set
 * @return string
 */
function simontaxi_unset_error( $error_id ) {
	$errors = simontaxi_get_errors();
	if ( $errors ) {
		unset( $errors[ $error_id ] );
		STVB()->session->set( 'simontaxi_errors', $errors );
	}
}

/**
 * Register die handler for simontaxi_die()
 *
 * @since 2.0.0
 * @return void
 */
function _simontaxi_die_handler() {
	if ( defined( 'SIMONTAXI_UNIT_TESTS' ) )
		return '_simontaxi_die_handler';
	else
		die();
}

/**
 * Wrapper function for wp_die(). This function adds filters for wp_die() which
 * kills execution of the script using wp_die(). This allows us to then to work
 * with functions using simontaxi_die() in the unit tests.
 *
 * @since 2.0.0
 * @return void
 */
function simontaxi_die( $message = '', $title = '', $status = 400 ) {
	add_filter( 'wp_die_ajax_handler', '_simontaxi_die_handler', 10, 3 );
	add_filter( 'wp_die_handler', '_simontaxi_die_handler', 10, 3 );
	wp_die( $message, $title, array( 'response' => $status ));
}

/**
 * Set Session
 *
 * Stores an session variable in a session.
 *
 * @since 2.0.0
 * @uses Simontaxi_Session::simontaxi_set_error()
 * @param int $error_id ID of the error being set
 * @param string $error_message Message to store with the error
 * @return void
 */
function simontaxi_set_session( $key, $val ) {

	$values = simontaxi_get_session( $key );

	if ( ! $values ) {
		$values = array();
	}

	if ( is_array( $values ) && is_array( $val ) ) {
		$new_array = array();
		if ( ! empty( $values ) ) {
			foreach ( $values as $innerkey => $innerval ) {
				$new_array[ $innerkey ] = $innerval;
			}
		}
		if ( ! empty( $val ) ) {
			foreach ( $val as $innerkey => $innerval ) {
				$new_array[ $innerkey ] = $innerval;
			}
		}
		$val = $new_array;
	}

	STVB()->session->set( $key, $val );
}

/**
 * Helper function to check the array is associative OR not
 *
 * @since 2.0.0
*/
function is_associative(array $arr)
{
    if ( array() === $arr ) return false;
    return array_keys( $arr ) !== range(0, count($arr) - 1);
}

/**
 * Get Session
 *
 * Retrieves the session values based on key.
 * If errors exist, they are returned.
 *
 * @since 2.0.0
 * @uses Simontaxi_Session::get()
 * @return mixed array if errors are present, false if none found
 */
function simontaxi_get_session( $key = '', $default = '', $subkey = '' ) {
	//var_dump( STVB() );die();
	if ( '' !== $subkey ) {
		$values = STVB()->session->get( $key, $default );
		if ( is_array( $values ) ) {
			return isset( $values[ $subkey ] ) ? $values[ $subkey ] : $default;
		} else {
			return $values;
		}
	} else {
		return STVB()->session->get( $key, $default );
	}
}

/**
 * Removes (unsets) a stored error
 *
 * @since 2.0.0
 * @uses Simontaxi_Session::set()
 * @param int $error_id ID of the error being set
 * @return string
 */
function simontaxi_unset_session( $key = '', $subkey = '' ) {
	if ( '' === $key ) {
		STVB()->session->set( 'booking_step1', null );
		STVB()->session->set( 'booking_step2', null );
		STVB()->session->set( 'booking_step3', null );
		STVB()->session->set( 'booking_step4', null );
		STVB()->session->set( 'discount_details', null );
	} else {
		if ( '' !== $subkey ) {
			$values = simontaxi_get_session( $key );

			if ( ! $values ) {
				$values = array();
			}

			if ( isset( $values[ $key ] ) && is_array( $values[ $key ] ) ) {
				$new_array = array();
				if ( ! empty( $values[ $key ] ) ) {
					foreach ( $values[ $key ] as $innerkey => $innerval ) {
						if ( $innerkey === $subkey ) {
							continue;
						}
						$new_array[ $key ][ $innerkey ] = $innerval;
					}
				}
				$values = $new_array;
			}

			STVB()->session->set( $key, $values );
		} else {
			STVB()->session->set( $key, null );
		}
	}
}
