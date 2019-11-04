<?php
/**
 * Plugin Functions/AJAX
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  Functions/AJAX
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



add_action( 'wp_ajax_get_coupon_amount', 'simontaxi_get_coupon_amount' );
add_action( 'wp_ajax_nopriv_get_coupon_amount', 'simontaxi_get_coupon_amount' );
if ( ! function_exists( 'simontaxi_get_coupon_amount' ) ) {
	/**
	 * Validate and calculate coupon amount based on settings in admin
	 *
	 * @since 1.0
	 * @return string
	 */
	function simontaxi_get_coupon_amount() {
		global $wpdb;
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$received_coupon_code = $_POST['coupon_code'];
			$found = false;
			$today = date( 'Y-m-d' );
			$minimum_purchase_selected = 0;
			$start_date_selected = date( 'Y-m-d' );
			$end_date_selected = date( 'Y-m-d' );
			
			$discount_details = simontaxi_get_session( 'discount_details', array() );
			if ( false === ! empty( $discount_details ) ) {
				/**
				 * Let us get all available coupon codes
				 */
				$coupon_codes = get_terms( array( 'taxonomy' => 'coupon_code', 'hide_empty' => false ) );

				if ( ! empty( $coupon_codes ) && ! is_wp_error( $coupon_codes ) ) {
					foreach ( $coupon_codes as $term_meta ) {
						$term_meta = ( array ) $term_meta;

						$coupon_code = get_term_meta( $term_meta['term_id'], 'coupon_code', true );
						
						$start_date = get_term_meta( $term_meta['term_id'], 'coupon_code_start', true );
						$end_date = get_term_meta( $term_meta['term_id'], 'coupon_code_end', true );

						$start_date = ( $start_date != '' ) ? date( 'Y-m-d', strtotime( $start_date ) ) : '';
						$end_date = ( $end_date != '' ) ? date( 'Y-m-d', strtotime( $end_date ) ) : '';

						$minimum_purchase = get_term_meta( $term_meta['term_id'], 'minimum_purchase', true );
						if ( $coupon_code == $received_coupon_code ) {
							$minimum_purchase_selected = $minimum_purchase;
							$start_date_selected = $start_date;
							$end_date_selected = $end_date;
						}
						$selected_amount = $_POST['selected_amount'];
						
						

						/**
						 * Just to know whether the received code is exists and not expired and meets minimum purchase conditions!
						 */
						 $msg = $amount = $status = '';
						 
						
						if ( ( $coupon_code == $received_coupon_code ) && ( $today >= $start_date ) && ( $today <= $end_date ) && $selected_amount >= $minimum_purchase ) {
							/**
							 * @since 2.0.8
							 */
							$login_required = get_term_meta( $term_meta['term_id'], 'login_required', true );
							
							if ( ! empty( $login_required ) && $login_required == 'yes' && ! is_user_logged_in() ) {
								$msg = 'Sorry Please login to use this coupon.';
								$amount = 0;
								$status = 'failed';
								echo json_encode( array( 
									'msg' => $msg, 
									'amount' => $amount, 
									'status' => $status,
								) );
								die();
							}
							
							$actual_usage_count = get_term_meta( $term_meta['term_id'], 'usage_count', true );
							if ( ! empty( $actual_usage_count ) ) {
								if ( is_user_logged_in()  ) {
									$usage_count = $wpdb->get_var( "SELECT count(*) FROM " . $wpdb->prefix . 'st_coupons_history WHERE coupon_code = "'.$coupon_code.'" AND user_id = ' .get_current_user_id() );
								} else {
									$usage_count = $wpdb->get_var( "SELECT count(*) FROM " . $wpdb->prefix . 'st_coupons_history WHERE coupon_code = "'.$coupon_code.'" AND ip_address = "'.simontaxi_get_the_user_ip().'"' );
								}
								if ( ! $usage_count < $actual_usage_count ) {
									$msg = 'Sorry you have already used this coupon.';
									$amount = 0;
									$status = 'failed';
									echo json_encode( array( 
										'msg' => $msg, 
										'amount' => $amount, 
										'status' => $status,
									) );
									die();
								}
							}
							$found = true;
							$coupon_value = get_term_meta( $term_meta['term_id'], 'coupon_value', true );
							$coupon_value_type = get_term_meta( $term_meta['term_id'], 'coupon_value_type', true );
							if ( $coupon_value_type == 'percent' ) {
								/**
								 * If the type is percent we need to calculate the percentage
								 */
								$discount = ( $selected_amount * $coupon_value) / 100;
							} else {
								$discount = $coupon_value;
							}
							simontaxi_set_session( 'discount_details', array(
								'coupon_code' => $coupon_code,
								'discount_amount' => $discount,
								'amount' => ( $selected_amount - $discount),
								'details' => array(
									'code' => $coupon_code,
									'start_date' => $start_date,
									'end_date' => $end_date,
									'minimum_purchase' => $minimum_purchase,
									'selected_amount' => $selected_amount,
									'coupon_value' => $coupon_value,
									'coupon_value_type' => $coupon_value_type,
									'discount' => $discount,
									),
								)
							);
							
							/**
							 * @since 2.0.8
							 *
							 */
							$coupon_history = array(
								'coupon_code' => $coupon_code,
								'user_id' => is_user_logged_in() ? get_current_user_id() : 0,
								'ip_address' => simontaxi_get_the_user_ip(),
								'coupon_amount' => $discount,
								'booking_id' => simontaxi_get_session( 'booking_step1', 0, 'db_ref' ),
							);
							$wpdb->insert( $wpdb->prefix . 'st_coupons_history', $coupon_history );
							
							$msg = esc_html__( 'Wow! You got discount of ', 'simontaxi' ) . simontaxi_get_currency_code( $discount );
							$amount = $discount;
							$status = 'success';
							break;
						} else {
							$title = apply_filters( 'simontaxi_filter_coupon_title', esc_html__( 'Coupon', 'simontaxi' ) );
							if ( ! ( $selected_amount >= $minimum_purchase_selected ) ) {
								$msg = esc_html__( sprintf( 'You need to purchase minimum of %s to apply this %s', simontaxi_get_currency( $minimum_purchase_selected ), $title ), 'simontaxi' );
							} elseif ( ! ( ( $today >= $start_date_selected ) && ( $today <= $end_date_selected ) ) ) {
								$msg =  $title . esc_html__( ' Expired', 'simontaxi' );
							} else
								$msg =  $title . esc_html__( ' not found', 'simontaxi' );
							}
							$amount = 0;
							$status = 'failed';
						}
					}
					echo json_encode( array( 
						'msg' => $msg, 
						'amount' => $amount, 
						'status' => $status,
						'discount_details' => simontaxi_get_session( 'discount_details', null ),
					) );
					die();
				} else {
					$title = apply_filters( 'simontaxi_filter_coupon_title', esc_html__( 'Coupon', 'simontaxi' ) );
					$msg =  $title . esc_html__( ' not found', 'simontaxi' );
					$amount = 0;
					$status = 'failed';
					echo json_encode( array( 
						'msg' => $msg, 
						'amount' => $amount, 
						'status' => $status,
						'discount_details' => simontaxi_get_session( 'discount_details', null ),
					) );
					die();
				}
			} else {
				$msg = esc_html__( 'You have alreay applied the coupon', 'simontaxi' );
				$amount = $_POST['selected_amount'];
				$status = 'failed';
				echo json_encode( array( 
					'msg' => $msg, 
					'amount' => $amount, 
					'status' => $status,
					'discount_details' => simontaxi_get_session( 'discount_details', null ),
				) );
				die();
			}
		}
	}

add_action( 'wp_ajax_insert_settings', 'simontaxi_insert_settings' ); //wp_ajax_{$action} hook
add_action( 'wp_ajax_nopriv_insert_settings', 'simontaxi_insert_settings' ); //wp_ajax_nopriv_{$action} hook
if ( ! function_exists( 'simontaxi_insert_settings' ) ) :
	/**
	 * Add or update admin vehicle settings
	 *
	 * @since 1.0
	 * @return void
	 */
	function simontaxi_insert_settings() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$page_options = $_POST['simontaxi_settings'];
			$page_options_new = array();
			$image_manipulation_field_keys = apply_filters( 'simontaxi_image_manipulation_field_keys', array( 'paypal', 'payu', 'byhand', 'loaders', 'banktransfer' ) );
			foreach ( $page_options as $key => $value) {
			   if ( in_array( $key, $image_manipulation_field_keys ) ) {
				   $values = simontaxi_get_option( $key );
				   if ( isset( $page_options[ $key ]['logo'] ) && $page_options[ $key ]['logo'] == '' ) {
					   if ( isset( $page_options[ $key ]['logo_remove'] ) && 'yes' === $page_options[ $key ]['logo_remove'] ) {
						   $value['logo'] = '';
					   } else {
						$value['logo'] = isset( $values['logo'] ) ? $values['logo'] : '';
					   }
				   } else {
					   $value['logo'] = isset( $page_options[ $key ]['logo'] ) ? $page_options[ $key ]['logo'] : '';
				   }

				   if ( isset( $page_options[ $key ]['header_logo'] ) && $page_options[ $key ]['header_logo'] == '' ) {
					   if ( isset( $page_options[ $key ]['header_logo_remove'] ) && 'yes' === $page_options[ $key ]['header_logo_remove'] ) {
						   $value['header_logo'] = '';
					   } else {
						$value['header_logo'] = isset( $values['header_logo'] ) ? $values['header_logo'] : '';
					   }
				   } else {
					   $value['header_logo'] = isset( $page_options[ $key ]['header_logo'] ) ? $page_options[ $key ]['header_logo'] : '';
				   }

				   /**
					 * We are receiving request from client to change loaded image, so here is the provision.
					 *
					 * @since 2.0.0
					*/
				   if ( isset( $page_options[ $key ]['main_loader'] ) && $page_options[ $key ]['main_loader'] == '' ) {
					   if ( isset( $page_options[ $key ]['main_loader_remove'] ) && 'yes' === $page_options[ $key ]['main_loader_remove'] ) {
						   $value['main_loader'] = '';
					   } else {
						$value['main_loader'] = isset( $values['main_loader'] ) ? $values['main_loader'] : '';
					   }
				   } else {
					   $value['main_loader'] = isset( $page_options[ $key ]['main_loader'] ) ? $page_options[ $key ]['main_loader'] : '';
				   }

				   if ( isset( $page_options[ $key ]['ajax_loader'] ) && '' === $page_options[ $key ]['ajax_loader'] ) {
					   if ( isset( $page_options[ $key ]['ajax_loader_remove'] ) && 'yes' === $page_options[ $key ]['ajax_loader_remove'] ) {
						   $value['ajax_loader'] = '';
					   } else {
						$value['ajax_loader'] = isset( $values['ajax_loader'] ) ? $values['ajax_loader'] : '';
					   }
				   } else {
					   $value['ajax_loader'] = isset( $page_options[ $key ]['ajax_loader'] ) ? $page_options[ $key ]['ajax_loader'] : '';
				   }
				   
				   /**
				    * @since 2.0.9
				    */
				   if ( isset( $page_options[ $key ]['billing_logo'] ) && $page_options[ $key ]['billing_logo'] == '' ) {
					   if ( isset( $page_options[ $key ]['billing_logo_remove'] ) && 'yes' === $page_options[ $key ]['billing_logo_remove'] ) {
						   $value['billing_logo'] = '';
					   } else {
						$value['billing_logo'] = isset( $values['billing_logo'] ) ? $values['billing_logo'] : '';
					   }
				   } else {
					   $value['billing_logo'] = isset( $page_options[ $key ]['billing_logo'] ) ? $page_options[ $key ]['billing_logo'] : '';
				   }

				   /**
					* Let us give the provision for external developers to manipulate image fields in settings
					*
					* @since 2.0.0
				   */
				   $custom_fields = apply_filters( 'simontaxi_image_manipulation_fields', array() );
				   if ( ! empty( $custom_fields ) ) {
					   foreach ( $custom_fields as $customkey => $customfield ) {
						   if ( ! empty( $customfield ) ) {
							   foreach ( $customfield as $cfield ) {
								   if ( $customkey === $key ) {
									   if ( isset( $page_options[ $key ][ $cfield ] ) && '' === $page_options[ $key ][ $cfield ] ) {
										   if ( isset( $page_options[ $key ][ $cfield . '_remove'] ) && 'yes' === $page_options[ $key ][ $cfield . '_remove'] ) {
											   $value[ $cfield ] = '';
										   } else {
											$value[ $cfield ] = isset( $values[ $cfield ] ) ? $values[ $cfield ] : '';
										   }
									   } else {
										   $value[ $cfield ] = isset( $page_options[ $key ][ $cfield ] ) ? $page_options[ $key ][ $cfield ] : '';
									   }
								   }
							   }
						   }
					   }
				   }
				   $page_options_new[ $key ] = $value;
			   } else {
				   if ( 'simontaxi_purchase_code' === $key ) {
					   if ( ! empty( $page_options_new[ $key ] ) ) {
						   $page_options_new[ $key ] = '';
					   } else {
						$res = simontaxi_validate_envato( $value );
						if ( true === $res ) {
							$page_options_new[ $key ] = $value;
						} else {
							$page_options_new[ $key ] = '';
						}
					   }
				   } else {
						$page_options_new[ $key ] = $value;
				   }
			   }
			}
			
		   $x =  update_option( 'simontaxi_settings', $page_options_new );
		   if ( ! $x )
			   add_option( 'simontaxi_settings', $page_options_new );
		   
		   // Restoration of pages. @since 2.0.9
		   $default_pages = $_POST['default_pages'];
		   if ( ! empty( $default_pages ) ){			   
				$current_options = get_option( 'simontaxi_pages', array() );
				$installed_pages = array();
			   foreach ( $default_pages as $key => $value) {
				   if ( 'yes' === $value ) {
					   $simon_page = simontaxi_default_pages( $key );
					   if ( ! empty( $simon_page ) ) {
						   $ID = array_key_exists( $key, $current_options ) ? $current_options[ $key ] : false;
						   if ( ! empty( $ID ) ) {
							   wp_delete_post( $ID );
						   }
						   $new_post = array(
								'post_title' => $simon_page['name'],
								'post_status' => 'publish',
								'post_date' => date( 'Y-m-d H:i:s' ),
								'post_author' => $user_ID,
								'post_type' => $simon_page['type'],
							);
							if ( ! empty( $simon_page['shortcode'] ) ) {
								$new_post['post_content'] = '[' . $simon_page['shortcode'] . ']';
							} else {
								$new_post['post_content'] = $simon_page['desc'];
							}
							$post_id = wp_insert_post( $new_post );
							if ( $post_id > 0 ) {					
								if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php', true ) ) {
								   update_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php' );
								}
							}
							$installed_pages[ $key ] = $post_id;
					   }
				   }
			   }
			   if ( ! empty( $installed_pages ) ) {
					$merged = array_merge( $current_options, $installed_pages );
					update_option( 'simontaxi_pages', $merged );
				}
		   }
		   
		   /**
		    * Let us update roles capabilities.
			*
			* @since 2.0.9
		    */
		   add_theme_caps();
		   
		   do_action( 'simontaxi_save_settings_last', $_POST );
		  echo 1;
		}
		die();
	}
endif;

add_action( 'wp_ajax_st_auto_places','simontaxi_autoplaces_callback' ); //wp_ajax_{$action} hook
add_action( 'wp_ajax_nopriv_st_auto_places','simontaxi_autoplaces_callback' ); //wp_ajax_nopriv_{$action} hook
if ( ! function_exists( 'simontaxi_autoplaces_callback' ) ) :
	/**
	 * Fetch all predefined places details.
	 *
	 * @since 1.0
	 * @return void
	 */
	function simontaxi_autoplaces_callback() {
		global $wpdb;
		if ( isset( $_REQUEST['term'] ) ) {
			if ( ! empty( $_REQUEST['term'] ) ) {
				$term = $_REQUEST['term'];
				$json = array();
				$metaquery_args = array(
					'relation' => 'OR',
					array(
						'key'     => 'display_type',
						'value'   => 'pickup_location',
						'compare' => '='
					),
					array(
						'key'     => 'display_type',
						'value'   => 'both',
						'compare' => '='
					),
					array(
						'key'     => 'location_address',
						'value'   => $term,
						'compare' => 'LIKE'
					),
				);
				if ( $_REQUEST['type'] == 'drop_location' ) {
					$metaquery_args = array(
						'relation' => 'OR',
						array(
							'key'     => 'display_type',
							'value'   => 'drop_location',
							'compare' => '='
						),
						array(
							'key'     => 'display_type',
							'value'   => 'both',
							'compare' => '='
						),
						array(
							'key'     => 'location_address',
							'value'   => $term,
							'compare' => 'LIKE'
						),
					);
				}
				$vehicle_locations = get_terms( array( 'taxonomy' => 'vehicle_locations', 'orderby' => 'name', 'hide_empty' => false, 'name__like' => $term, 'meta_query' => new WP_Date_Query( $metaquery_args ) ));
				//echo $wpdb->last_query;

				if ( ! empty( $vehicle_locations ) && ! is_wp_error( $vehicle_locations ) ) {
					foreach ( $vehicle_locations as $vehicle_location ) {
						$term_data = ( array ) $vehicle_location;
						$name = $term_data['name'];
						
						/**
						 * Let us take the address, so that we can calculate distance manually using google API
						 *
						 * @since 2.0.0
						*/
						$term_meta = get_term_meta( $term_data['term_id'] );
						$location_address = ( ! empty( $term_meta['location_address'] ) ) ? $term_meta['location_address'][0] : '';
						$name_value = ( '' !== $location_address ) ? $location_address : $name;
						$json[] = array( 'value' => $name_value, 'label' => $name_value);
					}
				}
				echo json_encode( $json);
			}
		}
		die();
	}
endif;

add_action( 'wp_ajax_load_more_vehicles','simontaxi_load_more_vehicles' ); // wp_ajax_{$action} hook.
add_action( 'wp_ajax_nopriv_load_more_vehicles','simontaxi_load_more_vehicles' ); // wp_ajax_nopriv_{$action} hook.
if ( ! function_exists( 'simontaxi_load_more_vehicles' ) ) :
	/**
	 * Fetch more vehicles.
	 *
	 * @since 1.0
	 * @return void
	 */
	function simontaxi_load_more_vehicles() {
		global $wpdb;
		$taxonomyid = $_POST['taxonomyid'];
		$page = isset( $_POST['cpage'] ) ? abs( (int) $_POST['cpage'] ) : 1;
		//$page = $page +1;
		$perpage = $_POST['perpage'];
		if ( $page > 1) {
		$offset = $page * $perpage - $perpage;
		} else {
		$offset = 0;
		}
		$query = array(
			'post_type' => 'vehicle',
			'post_status' => array( 'publish' ),
			'posts_per_page' => $perpage,
			'paged' => $page,
			'orderby' => 'title',
			'tax_query' => array(array(
				'taxonomy' => 'vehicle_types',
				'field'    => 'ID',
				'terms'    => $taxonomyid,
			) ),
		);
		$loop = new WP_Query( $query);
		$class = 'col-lg-6 col-md-6 col-sm-6';
		if ( $_POST['columns'] == 3 ) {
			$class = 'col-lg-4 col-md-4 col-sm-6';
		}
		ob_start();
		while ( $loop->have_posts() ) : $loop->the_post();
			$post_id = get_the_ID();
			$meta = simontaxi_filter_gk(get_post_meta( $post_id ) );
		?>
		<!-- Single Item -->
		<div class="<?php echo $class;?>">
			<div class="st-portfolio-item st-gallery-item">
				<?php if ( has_post_thumbnail( $post_id) ) {
					the_post_thumbnail( 'simontaxi-vehicle-grid-image', array( 'class' => 'img-responsive' ) );
				} else {
					?>
					<img src="<?php echo esc_url( SIMONTAXI_PLUGIN_URL . '/images/01.png' );?>" class="img-responsive" alt="">
					<?php
				}?>

				<!-- portfolio item hover -->
				<div class="st-portfolio-hover  st-dark-hover">
					<div class="st-hover-content">
						<h4><?php the_title();?></h4>
						<p><?php esc_html_e( 'Price: ', 'simontaxi' );?><?php echo ( isset( $meta['p2p_unit_price'] ) ) ? simontaxi_get_currency( $meta['p2p_unit_price'] ) : esc_html__( 'NA', 'simontaxi' );?></p>
						<button class="btn btn-primary" onclick="window.location='<?php echo simontaxi_get_bookingsteps_urls( 'step1' );?>'"><?php esc_html_e( 'Book Now', 'simontaxi' );?></button>
					</div>
				</div>
			</div>
		</div>
		<!-- /Single Item -->
		<?php
		endwhile;
		wp_reset_postdata();
		$output = ob_get_clean();
		echo $output;
		die(0);
	}
endif;

add_action( 'wp_ajax_lost_pass','simontaxi_lost_pass' ); // wp_ajax_{$action} hook.
add_action( 'wp_ajax_nopriv_lost_pass','simontaxi_lost_pass' ); // wp_ajax_nopriv_{$action} hook.
if ( ! function_exists( 'simontaxi_lost_pass' ) ) :
	/*
	 *	Process lost password
	 */
	function simontaxi_lost_pass() {

		global $wpdb, $wp_hasher;

		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'rs_user_lost_password_action' ) )
			die ( 'Security checked!' );

		//We shall SQL escape all inputs to avoid sql injection.
		$user_login = $_POST['user_login'];

		$errors = new WP_Error();

		if ( empty( $user_login ) ) {
			$errors->add( 'empty_username', esc_html__( 'Enter a username or e-mail address.', 'simontaxi' ) );
		} elseif ( strpos( $user_login, '@' ) ) {
			$user_data = get_user_by( 'email', trim( $user_login ) );
			if ( empty( $user_data ) )
				$errors->add( 'invalid_email', esc_html__( 'There is no user registered with that email address.', 'simontaxi' ) );
		} else {
			$login = trim( $user_login );
			$user_data = get_user_by( 'login', $login );
		}
		if ( ! $user_data ) {
			$errors->add( 'invalidcombo', esc_html__( 'Invalid username or email.', 'simontaxi' ) );
		}

		/**
		 * Fires before errors are returned from a password reset request.
		 *
		 * @since 2.1.0
		 * @since 4.4.0 Added the `$errors` parameter.
		 *
		 * @param WP_Error $errors A WP_Error object containing any errors generated
		 *                         by using invalid credentials.
		 */
		do_action( 'lostpassword_post', $errors );

		if ( $errors->get_error_code() ) {
			echo '<p class="error"><b>' . $errors->get_error_message( $errors->get_error_code() ) . '</b></p>';
		} else {
		// Redefining user_login ensures we return the right case in the email.
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;
		$key = get_password_reset_key( $user_data );

		if ( is_wp_error( $key ) ) {
			$errors->add( 'invalidkey', esc_html__( 'Invalid key generated. Refresh the page and try again.', 'simontaxi' ) );
		}

		$message = esc_html__( 'Someone requested that the password be reset for the following account:', 'simontaxi' ) . "\r\n\r\n";
		$message .= network_home_url( '/' ) . "\r\n\r\n";
		$message .= sprintf(esc_html__( 'Username: %s', 'simontaxi' ), $user_login) . "\r\n\r\n";
		$message .= esc_html__( 'If this was a mistake, just ignore this email and nothing will happen.', 'simontaxi' ) . "\r\n\r\n";
		$message .= esc_html__( 'To reset your password, visit the following address:', 'simontaxi' ) . "\r\n\r\n";

		// replace PAGE_ID with reset page ID
		$message .= simontaxi_get_bookingsteps_urls( 'resetpassword' ) . "/?action=rp&key=$key&login=" . rawurlencode( $user_login) . "\r\n";

		if ( is_multisite() )
			$blogname = $GLOBALS['current_site']->site_name;
		else
			/*
			 * The blogname option is escaped with esc_html on the way into the database
			 * in sanitize_option we want to reverse this for the plain text arena of emails.
			 */
			$blogname = wp_specialchars_decode(get_option( 'blogname' ), ENT_QUOTES);

		$title = sprintf( esc_html__( '[%s] Password Reset', 'simontaxi' ), $blogname );

		/**
		 * Filter the subject of the password reset email.
		 *
		 * @since 2.8.0
		 * @since 4.4.0 Added the `$user_login` and `$user_data` parameters.
		 *
		 * @param string  $title      Default email title.
		 * @param string  $user_login The username for the user.
		 * @param WP_User $user_data  WP_User object.
		 */
		$title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );

		/**
		 * Filter the message body of the password reset mail.
		 *
		 * @since 2.8.0
		 * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
		 *
		 * @param string  $message    Default mail message.
		 * @param string  $key        The activation key.
		 * @param string  $user_login The username for the user.
		 * @param WP_User $user_data  WP_User object.
		 */
		$message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );

		if ( wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
			$errors->add( 'confirm', esc_html__( 'Check your e-mail for the confirmation link.', 'simontaxi' ), 'message' );
		} else {
			$errors->add( 'could_not_sent', esc_html__( 'The e-mail could not be sent.', 'simontaxi' ) . "<br />\n" . esc_html__( 'Possible reason: your host may have disabled the mail() function.', 'simontaxi' ), 'message' );
		}

		// display error message
		if ( $errors->get_error_code() ) {
			echo '<p class="error"><b>' . $errors->get_error_message( $errors->get_error_code() ) .'</b></p>';
		}
		}
		// return proper result
		die();
	}
endif;

add_action( 'wp_ajax_nopriv_reset_pass', 'simontaxi_reset_pass_callback' );
add_action( 'wp_ajax_reset_pass', 'simontaxi_reset_pass_callback' );
if ( ! function_exists( 'simontaxi_reset_pass_callback' ) ) :
	/*
	 *	Process reset password
	 */
	function simontaxi_reset_pass_callback() {

		$errors = new WP_Error();
		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'rs_user_reset_password_action' ) )
			die ( 'Security checked!' );

		$pass1 	= $_POST['pass1'];
		$pass2 	= $_POST['pass2'];
		$key 	= $_POST['user_key'];
		$login 	= $_POST['user_login'];

		$user = check_password_reset_key( $key, $login );

		// check to see if user added some string
		if ( empty( $pass1 ) || empty( $pass2 ) ) {
			$errors->add( 'password_required', esc_html__( 'Password is required field', 'simontaxi' ) );
		}

		// is pass1 and pass2 match?
		if ( isset( $pass1 ) && $pass1 != $pass2 ) {
			$errors->add( 'password_reset_mismatch', esc_html__( 'The passwords do not match.', 'simontaxi' ) );
		}
		
		if ( is_wp_error( $user ) ) {
            if ( $user->get_error_code() === 'expired_key' ) {
                $errors->add( 'expiredkey', esc_html__( 'Sorry, that key has expired. Please try again.', 'simontaxi' ) );
			} else {
                $errors->add( 'invalidkey', esc_html__( 'Sorry, that key does not appear to be valid.', 'simontaxi' ) );
			}
        }	

		/**
		 * Fires before the password reset procedure is validated.
		 *
		 * @since 3.5.0
		 *
		 * @param object           $errors WP Error object.
		 * @param WP_User|WP_Error $user   WP_User object if the login and reset key match. WP_Error object otherwise.
		 */
		do_action( 'validate_password_reset', $errors, $user );

		if ( ( ! $errors->get_error_code() ) && isset( $pass1 ) && ! empty( $pass1 ) ) {
			reset_password( $user, $pass1 );

			$errors->add( 'password_reset', esc_html__( 'Your password has been reset.', 'simontaxi' ) );
		}

		// display error message
		if ( $errors->get_error_code() ) {
			if ( 'password_reset' === $errors->get_error_code() ) {
				echo '<div class="alert alert-success">';
			} else {
				echo '<div class="alert alert-danger">';
			}
			echo $errors->get_error_message( $errors->get_error_code() ) . '</div>';
		}
		die();
	}
endif;

add_action( 'wp_ajax_save_request_callback', 'simontaxi_save_request_callback' );
add_action( 'wp_ajax_nopriv_save_request_callback', 'simontaxi_save_request_callback' );

if ( ! function_exists( 'simontaxi_save_request_callback' ) ) :
	/**
	 * Function to save request from front end
	 *
	 * @since 1.0.0
	 */
	function simontaxi_save_request_callback() {
		global $wpdb;
		$data = array(
			'name' => isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '',
			'phone' => isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '',
			'email' => isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '',
			'user_agent' => $_SERVER['HTTP_USER_AGENT'],
			'date_time' => date( 'Y-m-d H:i:s' )
		);
		$id = $wpdb->insert( $wpdb->prefix .'st_request_callback', $data );
		if ( $id  > 0 )
		echo json_encode( array( 
			'msg' => esc_html__( 'We have received your request. we will get back to you soon', 'simontaxi' ), 
			'status' => 'success',
			) );
		else
		echo json_encode( array( 
			'msg' => esc_html__( 'Failed to receive request. Please try again', 'simontaxi' ),
			'status' => 'failed',
			) );
	die();
	}
endif;

if ( ! function_exists( 'simontaxi_get_locations' ) ):
	/**
	 * Function to get predefined locations
	 *
	 * @since 1.0.0
	 */
	function simontaxi_get_locations( $type = 'pickup_location' ) {
		$metaquery_args = array(
			'relation' => 'OR',
			array(
				'key'     => 'display_type',
				'value'   => $type,
				'compare' => '='
			),
			array(
				'key'     => 'display_type',
				'value'   => 'both',
				'compare' => '='
			),
		);
		$vehicle_locations = get_terms( array( 'taxonomy' => 'vehicle_locations', 'orderby' => 'name', 'hide_empty' => false,  'meta_query' => new WP_Date_Query( $metaquery_args ) ));
		$locations = array();
		if ( ! empty( $vehicle_locations ) && ! is_wp_error( $vehicle_locations ) ) {
			foreach ( $vehicle_locations as $vehicle_location ) {
				$term_data = ( array ) $vehicle_location;
				$name = $term_data['name'];
				/**
				 * Let us take the address, so that we can calculate distance manually using google API
				 *
				 * @since 2.0.0
				*/
				$term_meta = get_term_meta( $term_data['term_id'] );
				$location_address = ( ! empty( $term_meta['location_address'] ) ) ? $term_meta['location_address'][0] : '';
				$name_value = ( '' !== $location_address ) ? $location_address : $name;
				$locations[ $term_data['term_id'] ] = $name_value;
			}
		}
		return $locations;
	}
endif;