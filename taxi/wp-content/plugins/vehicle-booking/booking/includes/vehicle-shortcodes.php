<?php
/**
 * Shortcodes
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  Shortcodes
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */
 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'simontaxi_vehicle_booking_step1' ) ) :
	/**
	 * Vehicle booking process step 1. Choosing Journey date, From , To and type of journey (ex: Point To Point, Airport Arrival, Hourly Disposal)
	 *
	 * Displays the booking form.
	 *
	 * @since 1.0
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	function simontaxi_vehicle_booking_step1( $atts ) {
		/**
		 * Arguments for the 'placement' are 'hometop','homeleft','fullpage'
		 */
		$a = shortcode_atts( array(
				'placement' => 'fullpage',
				'columns' => 8,
				'class' => '',
			), $atts );
		return simontaxi_booking_step1( $a );
	}
endif;
add_shortcode( 'simontaxi_booking_step1', 'simontaxi_vehicle_booking_step1' );

if ( ! function_exists( 'simontaxi_vehicle_booking_step2' ) ) :
	/**
	 * Vehicle booking process step 2. Choosing Vehicle based on price
	 *
	 * Displays the vehicle selection form.
	 *
	 * @since 1.0
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	function simontaxi_vehicle_booking_step2( $atts ){
		$atts = shortcode_atts( array(), $atts );
		simontaxi_booking_step2();
	}
endif;
add_shortcode( 'simontaxi_booking_step2', 'simontaxi_vehicle_booking_step2' );

if ( ! function_exists( 'simontaxi_vehicle_booking_step3' ) ) :
	/**
	 * Vehicle booking process step 3. Passenger Details
	 *
	 * Displays the passenger details form
	 *
	 * @since 1.0
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	function simontaxi_vehicle_booking_step3( $atts ){
		$atts = shortcode_atts( array(), $atts );
		simontaxi_booking_step3();
	}
endif;
add_shortcode( 'simontaxi_booking_step3', 'simontaxi_vehicle_booking_step3' );

if ( ! function_exists( 'simontaxi_vehicle_booking_step4' ) ) :
	/**
	 * Vehicle booking process step 4. Payment Method Selection
	 *
	 * Displays Payment methods available
	 *
	 * @since 1.0
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	function simontaxi_vehicle_booking_step4( $atts ){
		$atts = shortcode_atts( array(), $atts );
		simontaxi_booking_step4();
	}
endif;
add_shortcode( 'simontaxi_booking_step4', 'simontaxi_vehicle_booking_step4' );

if ( ! function_exists( 'simontaxi_vehicle_payment_success' ) ) :
	/**
	 * Vehicle booking payment success
	 *
	 * Displays Payment methods available
	 *
	 * @since 1.0
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	function simontaxi_vehicle_payment_success( $atts ){
		$atts = shortcode_atts( array(), $atts );
		simontaxi_payment_success();
	}
endif;
add_shortcode( 'simontaxi_payment_success', 'simontaxi_vehicle_payment_success' );

if ( ! function_exists( 'simontaxi_vehicle_payment_failed' ) ) :
	/**
	 * [vehicle_payment_failed ]
	 * @param array $atts - Attributes
	 * @return shortcode page
	 */
	function simontaxi_vehicle_payment_failed( $atts ){

		_deprecated_function( __FUNCTION__, '2.0.0', 'simontaxi_vehicle_payment_final( $atts )' );

		/**
		 * Let us update the payment record. It will be useful last while we are analyzing data.
		*/
		$selected_payment_method = simontaxi_get_session( 'booking_step4', '', 'selected_payment_method' );
		if ( in_array( $selected_payment_method, array( 'paypal', 'payu' ) ) ) {
			global $wpdb;
			$data = array(
				'gateway_data' => json_encode( $_POST ),
				'payment_type' => $selected_payment_method,
				'transaction_status' => 'Cancelled',
			);
			if ( 'payu' === $selected_payment_method ) {
				$data['transaction_reference'] = $_POST['txnid'];
			}
			$wpdb->update( $wpdb->prefix . 'st_payments', $data , array( 'ID'=> simontaxi_get_session( 'booking_step4', 0, 'payment_id' ) ) );
		}
		/**
		 * Let us clear all sessions.
		 *
		 * @since 2.0.0
		*/
		simontaxi_clear_sessions();
		/**
		 * @since 2.0.8
		 */
		$template = '/booking/includes/pages/payment-failed.php';
		if ( simontaxi_is_template_customized( $template ) ) {
			include_once( simontaxi_get_theme_template_dir_name() . $template );
		} else {
			include_once( apply_filters( 'simontaxi_locate_payment_failed', SIMONTAXI_PLUGIN_PATH . $template ) );
		}
	}
endif;
add_shortcode( 'simontaxi_payment_failed', 'simontaxi_vehicle_payment_failed' );

if ( ! function_exists( 'simontaxi_payment_final' ) ) :
	/**
	 * [simontaxi_payment_final ]
	 * @param array $atts - Attributes
	 * @return shortcode page
	 * @since 2.0.0
	 */
	function simontaxi_payment_final( $atts ){

		/**
		 * Let us update the payment record. It will be useful last while we are analyzing data.
		*/
		$selected_payment_method = simontaxi_get_session( 'booking_step4', '', 'selected_payment_method' );
		$payment_status = simontaxi_get_session( 'booking_step4', '', 'payment_status' );
		
		if ( in_array( $selected_payment_method, array( 'paypal', 'payu', 'byhand' ) ) ) :
			if ( 'failed' === $payment_status && in_array( $selected_payment_method, array( 'paypal', 'payu' ) ) ) {
				global $wpdb;
				$data = array(
					'gateway_data' => json_encode( $_POST ),
					'payment_type' => $selected_payment_method,
					'transaction_status' => 'Cancelled',
				);
				if ( 'payu' === $selected_payment_method ) {
					$data['transaction_reference'] = $_POST['txnid'];
				}
				$wpdb->update( $wpdb->prefix . 'st_payments', $data , array( 'ID'=> simontaxi_get_session( 'booking_step4', '', 'payment_id' ) ) );
			}

		endif;
		do_action( 'simontaxi_display_payment_final', $payment_status );
	}
endif;
add_shortcode( 'simontaxi_payment_final', 'simontaxi_payment_final' );

if ( ! function_exists( 'simontaxi_vehicle_clear_selections' ) ) :
	/**
	 * [vehicle_clear_selections]
	 * @param array $atts - Attributes
	 * @return void
	 */
	function simontaxi_vehicle_clear_selections( $atts ){

		$a = shortcode_atts( array(
	        'popup' => FALSE,
	    ), $atts );

		if ( ! $a['popup'] ) {
			simontaxi_clear_selections();
		}

	}
endif;
add_shortcode( 'simontaxi_vehicle_clear_selections', 'simontaxi_vehicle_clear_selections' ); //This function resides in 'booking-functions.php'

if ( ! function_exists( 'simontaxi_signin' ) ) :
	/**
	 * [simontaxi_signin]
	 * @param array $atts - Attributes
	 * @return shortcode page
	 */
	function simontaxi_signin( $atts ){
		$a = shortcode_atts( array(
	        'popup' => FALSE,
	    ), $atts );

		if ( ! $a['popup'] )
		{
			/**
			 * @since 2.0.8
			 */
			$template = '/booking/includes/pages/login.php';
			if ( simontaxi_is_template_customized( $template ) ) {
				include_once( simontaxi_get_theme_template_dir_name() . $template );
			} else {
				include_once( apply_filters( 'simontaxi_locate_login', SIMONTAXI_PLUGIN_PATH . $template ) );
			}
		}

	}
endif;
add_shortcode( 'simontaxi_signin', 'simontaxi_signin' );

add_shortcode( 'simontaxi_booking_onhome', 'simontaxi_booking_onhome' );

if ( ! function_exists( 'simontaxi_booking_onhome' ) ) :
	/**
	 * [simontaxi_booking_onhome ]
	 * @param array $atts - Attributes
	 * @return wp-shortcode page
	 */
	function simontaxi_booking_onhome( $atts ) {

		/**
		 * Arguments for the 'placement' are 'hometop','homeleft','fullpage'
		*/
		$a = shortcode_atts( array(
				'placement' => 'fullpage',
			), $atts );
		return simontaxi_booking_step1( $a );
	}
endif;

add_shortcode( 'simontaxi_featured_vehicles', 'simontaxi_featured_vehicles' );
if ( ! function_exists( 'simontaxi_featured_vehicles' ) ) :
	/**
	 * featured vehichles
	 *
	 * @param array $atts - Attributes
	 */
	function simontaxi_featured_vehicles( $atts ) {
			global $wpdb;
			$a = shortcode_atts( array(
				'no_of_vehicles' => 3,
				'title' => esc_html__( 'Choose Your Taxi', 'simontaxi' ),
				'display_style' => 'default',
				
				'hide_price' => 'no',
				'hide_passengers' => 'no',
				'hide_luggage' => 'no',
				'hide_view_more' => 'no',
			), $atts );
						
			$query = array(
                'post_type' => 'vehicle',
                'post_status' => array( 'publish' ),
				'posts_per_page' => $a['no_of_vehicles'],
				'orderby' => 'rand',
            );
			$vehicle_types = get_terms( array( 'taxonomy' => 'vehicle_types', 'hide_empty' => false ) );
	ob_start();
			?>
	<section class="st-section-sm st-grey-bg">
        <div class="container">

            <?php if ( ! empty( $vehicle_types ) ) {
			$i = 0;
			?>
			<div class="row text-center">
                <div class="col-md-12">
                    <h2 class="st-heading"><?php echo esc_attr( $a['title'] ); ?></h2>
                    <div class="st-mtabs">
						<ul class="nav nav-pills st-nav-pills st-btm50">
							<?php foreach ( $vehicle_types as $type ) {
							$active = '';
							if ( $i == 0 ) {
								$active = ' class=active';
								$i++;
							}
							?>
							<li <?php echo esc_attr( $active ); ?>><a data-toggle="pill" href="#st-<?php echo esc_attr( $type->term_id ); ?>"><?php echo esc_attr( $type->name ); ?></a></li>
							<?php } ?>
						</ul>
					</div>
                </div>
            </div>
			<?php } ?>

			<div class="row">
                <div class="tab-content">

					<?php if ( ! empty( $vehicle_types ) ) {
							$i = 0;
							foreach ( $vehicle_types as $type ) {
								$query = array(
									'post_type' => 'vehicle',
									'post_status' => array( 'publish' ),
									'posts_per_page' => $a['no_of_vehicles'],
									'orderby' => 'rand',
									'tax_query' => array( array(
										'taxonomy' => 'vehicle_types',
										'field'    => 'slug',
										'terms'    => $type->slug,
									) ),
								);
								$loop = new WP_Query( $query );
								$active = '';
								if ( $i == 0 ) {
									$active = ' in active';
									$i++;
								}
								?>


					<div id="st-<?php echo esc_attr( $type->term_id ); ?>" class="tab-pane fade<?php echo esc_attr( $active); ?>">
					<?php
					$loop = new WP_Query( $query );
					while ( $loop->have_posts() ) : $loop->the_post();
					$meta = simontaxi_filter_gk( get_post_meta( get_the_ID() ) );
					?>
					<?php
					$post_id = get_the_ID();
					$image = get_template_directory_uri() . '/images/car1.png';
					if ( has_post_thumbnail( $post_id ) )
					{
						$thumb = get_post_thumbnail_id( $post_id );
						$attachment_url = wp_get_attachment_url( $thumb, 'full' );
						$image = simontaxi_resize( $attachment_url, 370, 251, true );
					}
					$col = 12 / $a['no_of_vehicles'];
					?>
					<!-- Single Item -->
					<div class="col-lg-<?php echo $col; ?> col-md-<?php echo $col; ?> col-sm-6">
						<?php if ( 'more_details' === $a['display_style'] ) { ?>
							<div class="st-card ">
								<h4 class="st-card-title"><?php the_title(); ?><span class="st-card-price">
								<?php if ( 'no' === $a['hide_price'] ) { ?>
								<span class="st-price-tag"><?php esc_html_e( 'From', 'simontaxi' ); ?> </span> <?php echo (isset( $meta['p2p_unit_price']) ) ? simontaxi_get_currency( $meta['p2p_unit_price']) : esc_html__( 'NA', 'simontaxi' ); ?></span>
								<?php } ?>
								</h4>
								
								<img class="img-responsive st-card-img" src="<?php echo esc_url( $image ); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>">
								<div class="st-card-content">
									
									<?php
									if ( 'no' === $a['hide_passengers'] 
									|| 'no' === $a['hide_luggage'] 
									|| 'no' === $a['hide_view_more']
									) {
									?>
									<ul class="st-card-list">
										<?php if ( 'no' === $a['hide_passengers'] ) { ?>
										<?php if ( isset( $meta['seating_capacity']) ) { ?>
										<li><i class="icon-people"></i> <span><?php esc_html_e( 'Max', 'simontaxi' ); ?> </span> <?php echo (isset( $meta['seating_capacity']) ) ? esc_attr( $meta['seating_capacity']) : esc_html__( 'NA', 'simontaxi' ); ?>
											<span> <?php esc_html_e( 'people', 'simontaxi' ); ?><br><?php esc_html_e( 'per vechicle', 'simontaxi' ); ?></span>
										</li>
										<?php } ?>
										<?php } ?>

										<?php if ( 'no' === $a['hide_luggage'] ) { ?>
										<?php if ( isset( $meta['luggage']) ) { ?>
										<li><i class="icon-briefcase"></i> <span><?php esc_html_e( 'Max', 'simontaxi' ); ?> </span> <?php echo esc_attr( $meta['luggage']); ?> <span class="luggage_type"><?php if ( isset( $meta['luggage_type_symbol']) && $meta['luggage_type_symbol'] != '' ) {
											echo esc_attr( $meta['luggage_type_symbol']);
										} else {
											echo (isset( $meta['luggage_type']) ) ? esc_attr( $meta['luggage_type']) : esc_html__( 'NA', 'simontaxi' );
										}?>
										</span>
											<span> <?php esc_html_e( 'luggage', 'simontaxi' ); ?><br><?php esc_html_e( 'per vechicle', 'simontaxi' ); ?></span>
										</li>
										<?php } ?>
										<?php } ?>
										
										<?php if ( 'no' === $a['hide_view_more'] ) { ?>
										<li><a href="<?php echo esc_url( get_permalink(get_the_ID() ) ); ?>" class="st-card-btn"><?php esc_html_e( 'View more', 'simontaxi' ); ?></a></li>
										<?php } ?>
									</ul>
									<?php } ?>
									
									<?php 
									$other_information = ! empty( $meta['other_information'] ) ? $meta['other_information'] : '';
									if ( ! empty( $other_information ) ) {
										echo '<p>' . $other_information . '</p>';
									}
									?>
								</div>
							</div>
						<?php } else { ?>
						<div class="st-card ">
							<img class="img-responsive st-card-img" src="<?php echo esc_url( $image ); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>">
							<div class="st-card-content">
								<h4 class="st-card-title"><?php the_title(); ?>
								<?php if ( 'no' === $a['hide_price'] ) { ?>
								<span class="st-card-price"><span class="st-price-tag"><?php esc_html_e( 'From', 'simontaxi' ); ?> </span> <?php echo (isset( $meta['p2p_unit_price']) ) ? simontaxi_get_currency( $meta['p2p_unit_price']) : esc_html__( 'NA', 'simontaxi' ); ?></span>
								<?php } ?>
								</h4>

								<?php
									if ( 'no' === $a['hide_passengers'] 
									|| 'no' === $a['hide_luggage'] 
									|| 'no' === $a['hide_view_more']
									) {
									?>
								<ul class="st-card-list">
									<?php if ( 'no' === $a['hide_passengers'] ) { ?>
									<?php if ( isset( $meta['seating_capacity']) ) { ?>
									<li><i class="icon-people"></i> <span><?php esc_html_e( 'Max', 'simontaxi' ); ?> </span> <?php echo (isset( $meta['seating_capacity']) ) ? esc_attr( $meta['seating_capacity']) : esc_html__( 'NA', 'simontaxi' ); ?>
										<span> <?php esc_html_e( 'people', 'simontaxi' ); ?><br><?php esc_html_e( 'per vechicle', 'simontaxi' ); ?></span>
									</li>
									<?php } ?>
									<?php } ?>

									<?php if ( 'no' === $a['hide_luggage'] ) { ?>
									<?php if ( isset( $meta['luggage']) ) { ?>
									<li><i class="icon-briefcase"></i> <span><?php esc_html_e( 'Max', 'simontaxi' ); ?> </span> <?php echo esc_attr( $meta['luggage']); ?> <span class="luggage_type"><?php if ( isset( $meta['luggage_type_symbol']) && $meta['luggage_type_symbol'] != '' ) {
										echo esc_attr( $meta['luggage_type_symbol']);
									} else {
										echo (isset( $meta['luggage_type']) ) ? esc_attr( $meta['luggage_type']) : esc_html__( 'NA', 'simontaxi' );
									}?>
									</span>
										<span> <?php esc_html_e( 'luggage', 'simontaxi' ); ?><br><?php esc_html_e( 'per vechicle', 'simontaxi' ); ?></span>
									</li>
									<?php } ?>
									<?php } ?>
									
									<?php if ( 'no' === $a['hide_view_more'] ) { ?>
									<li><a href="<?php echo esc_url( get_permalink(get_the_ID() ) ); ?>" class="st-card-btn"><?php esc_html_e( 'View more', 'simontaxi' ); ?></a></li>
									<?php } ?>
								</ul>
								<?php } ?>
								<?php 
								$other_information = ! empty( $meta['other_information'] ) ? $meta['other_information'] : '';
								if ( ! empty( $other_information ) ) {
									echo '<p>' . $other_information . '</p>';
								}
								?>
							</div>
						</div>
						<?php } ?>
					</div>
					<!-- /Single Item -->
					<?php
					endwhile;
					if ( $loop->found_posts == 0 ) {
						echo esc_html__( 'No ', 'simontaxi' ) . simontaxi_get_default_title() . esc_html__( ' Found', 'simontaxi' );
					}
					?>
					</div>
					<?php
					}
				}
					?>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
    }
endif;

add_shortcode( 'simontaxi_user_bookings', 'simontaxi_user_bookings' );

if ( ! function_exists( 'simontaxi_user_bookings' ) ) :
	/**
	 * [simontaxi_user_bookings ]
	 * @param array $atts - Attributes
	 * @return wp-shortcode page
	 */
	function simontaxi_user_bookings( $atts ) {
		$invoice_id = ! empty( $_REQUEST['invoice_id'] ) ? $_REQUEST['invoice_id'] : '';
		$redirect = true;
		if ( is_user_logged_in() ) {
			$redirect = false;
		} else {
			$redirect = false;
			if ( empty( $invoice_id ) ) {
				$redirect = true;
			} else {
				$ref = explode( '-', $_REQUEST['invoice_id']);
				$booking_id = $ref[0];
				$booking_ref = $ref[1];
				
				global $wpdb;
				$bookings = $wpdb->prefix . 'st_bookings';
				$payments = $wpdb->prefix . 'st_payments';
				$invoice = $wpdb->get_results( 'SELECT *, ' . $bookings. '.reference as booking_ref, ' . $payments . '.reference as payment_ref  FROM ' . $bookings . ' INNER JOIN ' . $payments. ' ON ' . $bookings . '.ID = ' . $payments . '.booking_id WHERE ' . $bookings . '.ID=' . $booking_id );
				if ( empty( $invoice ) ) {
					$redirect = true;
				}
			}
		}
		if ( $redirect ) {
			echo '<meta http-equiv="refresh" content="0;URL=\''.simontaxi_get_bookingsteps_urls( 'login' ) . '\'"/>';
		} else {
			/**
			 * @since 2.0.8
			 */
			$template = '/booking/includes/pages/user_bookings.php';
			if ( simontaxi_is_template_customized( $template ) ) {
				include_once( simontaxi_get_theme_template_dir_name() . $template );
			} else {
				include_once( apply_filters( 'simontaxi_locate_user_bookings', SIMONTAXI_PLUGIN_PATH . $template ) );
			}
		}
	}
endif;

add_shortcode( 'simontaxi_user_account', 'simontaxi_user_account' );

if ( ! function_exists( 'simontaxi_user_account' ) ) :
	/**
	 * [simontaxi_user_account ]
	 * @param array $atts - Attributes
	 * @return wp-shortcode page
	 */
	function simontaxi_user_account( $atts) {
		if ( ! is_user_logged_in() ) {
			echo '<meta http-equiv="refresh" content="0;URL=\''.simontaxi_get_bookingsteps_urls( 'login' ) . '\'" />';
		} else {
			/**
			 * @since 2.0.8
			 */
			$template = '/booking/includes/pages/user_account.php';
			if ( simontaxi_is_template_customized( $template ) ) {
				include_once( simontaxi_get_theme_template_dir_name() . $template );
			} else {
				include_once( apply_filters( 'simontaxi_locate_user_account', SIMONTAXI_PLUGIN_PATH . $template ) );
			}
		}
	}
endif;

add_shortcode( 'simontaxi_user_activate_account', 'simontaxi_user_activate_account' );

if ( ! function_exists( 'simontaxi_user_activate_account' ) ) :
	/**
	 * [simontaxi_user_activate_account ]
	 * @param array $atts - Attributes
	 * @return wp-shortcode page
	 */
	function simontaxi_user_activate_account( $atts) {
		/**
		 * @since 2.0.8
		 */
		$template = '/booking/includes/pages/activate-account.php';
		if ( simontaxi_is_template_customized( $template ) ) {
			include_once( simontaxi_get_theme_template_dir_name() . $template );
		} else {
			include_once( apply_filters( 'simontaxi_locate_activate_account', SIMONTAXI_PLUGIN_PATH . $template ) );
		}
	}
endif;

add_shortcode( 'simontaxi_user_payment_history', 'simontaxi_user_payment_history' );

if ( ! function_exists( 'simontaxi_user_payment_history' ) ) :
	/**
	 *[simontaxi_user_payment_history ]
	 * @param array $atts - Attributes
	 * @return wp-shortcode page
	 */
	function simontaxi_user_payment_history( $atts) {
		if ( ! is_user_logged_in() ) {
			echo '<meta http-equiv="refresh" content="0;URL=\''.simontaxi_get_bookingsteps_urls( 'login' ) . '\'" />';
		} else {
			/**
			 * @since 2.0.8
			 */
			$template = '/booking/includes/pages/user_payment_history.php';
			if ( simontaxi_is_template_customized( $template ) ) {
				include_once( simontaxi_get_theme_template_dir_name() . $template );
			} else {
				include_once( apply_filters( 'simontaxi_locate_user_payment_history', SIMONTAXI_PLUGIN_PATH . $template ) );
			}
		}
	}
endif;

add_shortcode( 'simontaxi_user_support', 'simontaxi_user_support' );

if ( ! function_exists( 'simontaxi_user_support' ) ) :
	/**
	 * [simontaxi_user_support ]
	 * @param array $atts - Attributes
	 * @return wp-shortcode page
	 */
	function simontaxi_user_support( $atts) {
		if ( !is_user_logged_in() ) {
			echo '<meta http-equiv="refresh" content="0;URL=\''.simontaxi_get_bookingsteps_urls( 'login' ) . '\'" />';
		} else {
			/**
			 * @since 2.0.8
			 */
			$template = '/booking/includes/pages/user_support.php';
			if ( simontaxi_is_template_customized( $template ) ) {
				include_once( simontaxi_get_theme_template_dir_name() . $template );
			} else {
				include_once( apply_filters( 'simontaxi_locate_user_support', SIMONTAXI_PLUGIN_PATH . $template ) );
			}
		}
	}
endif;

add_shortcode( 'simontaxi_user_billing_address', 'simontaxi_user_billing_address' );

if ( ! function_exists( 'simontaxi_user_billing_address' ) ) :
	/**
	 *[simontaxi_user_billing_address ]
	 * @param array $atts - Attributes
	 * @return wp-shortcode page
	 */
	function simontaxi_user_billing_address( $atts) {
		if ( !is_user_logged_in() ) {
			echo '<meta http-equiv="refresh" content="0;URL=\''.simontaxi_get_bookingsteps_urls( 'login' ) . '\'" />';
		} else {
			/**
			 * @since 2.0.8
			 */
			$template = '/booking/includes/pages/user_billing_address.php';
			if ( simontaxi_is_template_customized( $template ) ) {
				include_once( simontaxi_get_theme_template_dir_name() . $template );
			} else {
				include_once( apply_filters( 'simontaxi_locate_user_billing_address', SIMONTAXI_PLUGIN_PATH . $template ) );
			}
		}
	}
endif;


add_shortcode( 'simontaxi_registration', 'simontaxi_registration' );

if ( ! function_exists( 'simontaxi_registration' ) ) :
	/**
	 * [simontaxi_registration]
	 * @param array $atts - Attributes
	 * @return void
	 */
	function simontaxi_registration( $atts ) {
		if ( is_user_logged_in() ) {
			echo '<meta http-equiv="refresh" content="0;URL=\''.simontaxi_get_bookingsteps_urls( 'user_account' ) . '\'" />';
		} else {
			
			$a = shortcode_atts( array(
				'role' => 'Customer',
				'top_description' => '',
				'bottom_description' => '',
				'approval' => 'yes',
			), $atts );
			
			/**
			 * @since 2.0.8
			 */
			$template = '/booking/includes/pages/registration.php';
			if ( simontaxi_is_template_customized( $template ) ) {
				include_once( simontaxi_get_theme_template_dir_name() . $template );
			} else {
				include_once( apply_filters( 'simontaxi_locate_registration', SIMONTAXI_PLUGIN_PATH . $template, $a ) );
			}
		}
	}
endif;

add_shortcode( 'simontaxi_forgotpassword', 'simontaxi_forgotpassword' );

if ( ! function_exists( 'simontaxi_forgotpassword' ) ) :
	/**
	 * [simontaxi_forgotpassword]
	 * @param array $atts - Attributes
	 * @return void
	 */
	function simontaxi_forgotpassword( $atts ){

		if ( is_user_logged_in() ) {
			echo '<meta http-equiv="refresh" content="0;URL=\'' . simontaxi_get_bookingsteps_urls( 'user_account' ) .  '\'" />';
		} else {
			/**
			 * @since 2.0.8
			 */
			$template = '/booking/includes/pages/forgotpassword.php';
			if ( simontaxi_is_template_customized( $template ) ) {
				include_once( simontaxi_get_theme_template_dir_name() . $template );
			} else {
				include_once( apply_filters( 'simontaxi_locate_forgotpassword', SIMONTAXI_PLUGIN_PATH . $template ) );
			}
		}

	}
endif;

add_shortcode( 'simontaxi_resetpassword', 'simontaxi_resetpassword' );

if ( ! function_exists( 'simontaxi_resetpassword' ) ) :
	/**
	 * [simontaxi_resetpassword]
	 * @param array $atts - Attributes
	 * @return void
	 */
	function simontaxi_resetpassword( $atts ){

		if ( is_user_logged_in() ) {
			echo '<meta http-equiv="refresh" content="0;URL=\'' . simontaxi_get_bookingsteps_urls( 'user_account' ) . '\'" />';
		} else {
			/**
			 * @since 2.0.8
			 */
			$template = '/booking/includes/pages/resetpassword.php';
			if ( simontaxi_is_template_customized( $template ) ) {
				include_once( simontaxi_get_theme_template_dir_name() . $template );
			} else {
				include_once( apply_filters( 'simontaxi_locate_resetpassword', SIMONTAXI_PLUGIN_PATH . $template ) );
			}
		}

	}
endif;


add_shortcode( 'simontaxi_testimonials', 'simontaxi_testimonials' );
if ( ! function_exists( 'simontaxi_testimonials' ) ) :
    /**
	 * Testimonials
	 *
	 * @param array $atts - Attributes
	 */
	function simontaxi_testimonials( $atts ) {
			$a = shortcode_atts( array(
				'per_page' => 2,
			), $atts );
			$query = array(
                'post_type' => 'testimonial',
                'post_status' => array( 'publish' ),
				'posts_per_page' => $a['per_page'],
				'orderby' => 'rand',
            );
			?>
	<!-- TESTIMONIALS - Slick Slider -->
    <section class="st-section st-dark-bg">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-12">
                    <!-- Testimonial Slider-->
                    <div class="st-team-slider">
                        <?php
						$loop = new WP_Query( $query);
						while ( $loop->have_posts() ) : $loop->the_post();
						$meta = simontaxi_filter_gk( get_post_meta( get_the_ID() ) );

						$post_id = get_the_ID();
						$image = get_template_directory_uri() . '/images/testm1.png';
						if (has_post_thumbnail( $post_id ) )
						{
							$thumb = get_post_thumbnail_id( $post_id );
							$attachment_url = wp_get_attachment_url( $thumb, 'full' );
							$image = simontaxi_resize( $attachment_url, 370, 251, true );
						}
						?>
						<div class="item">
                            <!-- Team Member item -->
                            <div class="st-testimo">
                                <div class="st-testimo-box">
                                    <div class="st-testimo-profile">
                                        <img src="<?php echo esc_url( $image); ?>" alt="<?php the_title(); ?>" class="img-circle img-responsive" title="<?php the_title(); ?>">
                                    </div>
                                    <p class="st-text"><?php the_content(); ?></p>
                                </div>
                                <h4 class="st-name"><?php the_title(); ?></h4>
                            </div>
                            <!-- /Team Member item -->
                        </div>
						<?php endwhile; ?>

                    </div>
                    <!-- /Testimonial Slider-->
                </div>
            </div>
        </div>
    </section>
    <!-- /TESTIMONIALS - Slick Slider -->
	<?php
    }
endif;



if ( ! function_exists( 'simontaxi_latestupdates' ) ) :
    add_shortcode( 'simontaxi_latestupdates', 'simontaxi_latestupdates' );
	/**
	 * Latest Updates
	 *
	 * @param array $atts - Attributes
	 */
	function simontaxi_latestupdates( $atts ) {
			$a = shortcode_atts( array(
				'per_page' => 3,
				'title' => esc_html__( 'Latest Blog Updates', 'simontaxi' ),
			), $atts );
			?>
	<!-- BLOG POSTS -->
    <section class="st-section-sm">
	<div class="container">
		<div class="row text-center">
			<div class="col-md-12">
				<h2 class="st-heading st-heading-sm"><?php esc_html_e( $a['title'], 'simontaxi' ); ?></h2>
			</div>
		</div>
		<div class="row">
			<?php
			$query = array(
                'post_type' => 'post',
                'post_status' => array( 'publish' ),
				'posts_per_page' => 2,
				'orderby' => 'date',
				'order' => 'DESC',
            );
			$loop = new WP_Query( $query);

			while ( $loop->have_posts() ) : $loop->the_post();
			$meta = simontaxi_filter_gk(get_post_meta( get_the_ID() ) );

			$post_id = get_the_ID();
			$image = get_template_directory_uri() . '/images/358x240.png';
			?>
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<!-- Single Blog Component -->
				<div class="st-blog center-block">
					<div class="st-blog-img">
						<a href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>">
						<?php if ( has_post_thumbnail( $post_id ) ) {
							the_post_thumbnail( 'simontaxi-grid-image', array( 'class' => 'img-responsive' ) );
						} else { ?>
						<img src="<?php echo esc_url( $image ); ?>" alt="<?php the_title(); ?>" class="img-responsive" title="<?php the_title(); ?>">
						<?php } ?>
						</a>
					</div>
					<div class="st-blog-content">
						<ul class="st-blog-post">
							<li><i class="fa fa-tags"></i><span><?php echo esc_html__( 'Category:', 'simontaxi' )?> </span><?php the_category( ',' ); ?></li>
							<li><i class="fa fa-clock-o"></i><span> <?php esc_html_e( 'Date:', 'simontaxi' ); ?> </span><?php echo get_the_date(); ?></li>
						</ul>
						<a class="st-blog-title" href="<?php echo esc_url( get_permalink(get_the_ID() ) ); ?>"><?php the_title(); ?></a>
						<div class="st-blog-text"><?php the_excerpt(); ?></div>
						<!--<a class="st-blog-fullread" href="<?php echo esc_url( get_permalink(get_the_ID() ) ); ?>"><?php esc_html_e( 'Read more', 'simontaxi' ); ?></a>-->
					</div>
				</div>
				<!-- /Single Blog Component -->
			</div>
			<?php endwhile; ?>
		</div>
	</div>
</section>
<!-- BLOG POSTS -->
	<?php
    }
endif;


add_action( 'vc_before_init', 'simontaxi_WITHVS' );
/**
 * Visual Composer Shortcodes start
*/
function simontaxi_WITHVS() {
	$icon = 'icon-wpb-simontaxi';
	$category = esc_html__( 'Simontaxi Widgets', 'simontaxi' );
	vc_map( array(
        'name' => esc_html__( 'Simontaxi Booking Page','simontaxi' ),
		'description' => esc_html__( 'It displays the Booking page', 'simontaxi' ),
        'base' => 'simontaxi_booking_step1',
        "class" => '',
        'category' => $category,
		'icon' => $icon,
        "params" => array(
		    array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Title",'simontaxi' ),
            "param_name" => "title",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert Title text Here.",'simontaxi' ),
            ),
			array(
            'type' => "dropdown",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Title",'simontaxi' ),
            "param_name" => "placement",
            "value" => array( 
				esc_html__( 'Full Page', 'simontaxi' ) => 'fullpage', 
				esc_html__( 'Home Page(Top)', 'simontaxi' ) => 'hometop', 
				esc_html__( 'Home Page(Left)', 'simontaxi' ) => 'homeleft',
				esc_html__( 'Any Where', 'simontaxi' ) => 'anywhere',
			),
            'description' => esc_html__( "Select place where you are inserting this element.",'simontaxi' ),
            ),
        )
    ) );

	/**
	 * FAQs
	*/
	vc_map( array(
        'name' => esc_html__( "Simontaxi FAQs",'simontaxi' ),
		'description' => esc_html__( 'It displays FAQs page', 'simontaxi' ),
        'base' => "simontaxi_faqs",
        "class" => '',
        'category' => $category,
		'icon' => $icon,
        "params" => array(
			array(
            'type' => "dropdown",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Columns",'simontaxi' ),
            "param_name" => "columns",
            "value" => array(esc_html__( 'Two Columns', 'simontaxi' ) => 2, esc_html__( 'One Column', 'simontaxi' ) => 1,),
            'description' => esc_html__( "Select number of columns per layout.",'simontaxi' ),
            ),
        )
    ) );
	/**
	 * Vehicles display
	*/
	$types = array();

	vc_map( array(
        'name' => esc_html__( "Simontaxi Vehicles",'simontaxi' ),
		'description' => esc_html__( 'It displays available vehicles on the page', 'simontaxi' ),
        'base' => "simontaxi_featured_vehicles",
        "class" => '',
        'category' => $category,
		'icon' => $icon,
        "params" => array(
		    array(
				'type' => "textfield",
				"holder" => "div",
				"class" => '',
				"heading" => esc_html__( "Title",'simontaxi' ),
				"param_name" => "title",
				"value" => esc_html__( '','simontaxi' ),
				'description' => esc_html__( "Insert Title text Here.",'simontaxi' ),
            ),
			array(
				'type' => "textfield",
				"holder" => "div",
				"class" => '',
				"heading" => esc_html__( "No. of vehicles",'simontaxi' ),
				"param_name" => "no_of_vehicles",
				"value" => esc_html__( '','simontaxi' ),
				'description' => esc_html__( "Enter No. of vehicles to show per category Here.",'simontaxi' ),
            ),
			array(
				'type' => "dropdown",
				"holder" => "div",
				"class" => '',
				"heading" => esc_html__( "Display Style",'simontaxi' ),
				"param_name" => "display_style",
				"value" => array(
					esc_html__( 'Default', 'simontaxi' ) => 'default', 
					esc_html__( 'More Details', 'simontaxi' ) => 'more_details',
				),
				'description' => esc_html__( "Select display style.",'simontaxi' ),
            ),
			array(
				'type' => "dropdown",
				"holder" => "div",
				"class" => '',
				"heading" => esc_html__( "Hide Price",'simontaxi' ),
				"param_name" => "hide_price",
				"value" => array(
					esc_html__( 'No', 'simontaxi' ) => 'no',
					esc_html__( 'Yes', 'simontaxi' ) => 'yes',
				),
				'description' => esc_html__( "Select display style.",'simontaxi' ),
            ),
			array(
				'type' => "dropdown",
				"holder" => "div",
				"class" => '',
				"heading" => esc_html__( "Hide Passengers",'simontaxi' ),
				"param_name" => "hide_passengers",
				"value" => array(
					esc_html__( 'No', 'simontaxi' ) => 'no',
					esc_html__( 'Yes', 'simontaxi' ) => 'yes',
				),
				'description' => esc_html__( "Select display style.",'simontaxi' ),
            ),
			array(
				'type' => "dropdown",
				"holder" => "div",
				"class" => '',
				"heading" => esc_html__( "Hide Luggage",'simontaxi' ),
				"param_name" => "hide_luggage",
				"value" => array(
					esc_html__( 'No', 'simontaxi' ) => 'no',
					esc_html__( 'Yes', 'simontaxi' ) => 'yes', 
				),
				'description' => esc_html__( "Select display style.",'simontaxi' ),
            ),
			array(
				'type' => "dropdown",
				"holder" => "div",
				"class" => '',
				"heading" => esc_html__( "Hide View More",'simontaxi' ),
				"param_name" => "hide_view_more",
				"value" => array(
					esc_html__( 'No', 'simontaxi' ) => 'no',
					esc_html__( 'Yes', 'simontaxi' ) => 'yes',
				),
				'description' => esc_html__( "Select display style.",'simontaxi' ),
            ),
        )
    ) );
	vc_map( array(
        'name' => esc_html__( "Simontaxi Latest Updates",'simontaxi' ),
		'description' => esc_html__( 'It display the latest updates from the site', 'simontaxi' ),
        'base' => "simontaxi_latestupdates",
        "class" => '',
        'category' => $category,
		'icon' => $icon,
        "params" => array(
		    array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Title",'simontaxi' ),
            "param_name" => "title",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert Title text Here.",'simontaxi' ),
            ),
        )
    ) );
	vc_map( array(
        'name' => esc_html__( "Simontaxi Get Mobile app",'simontaxi' ),
		'description' => esc_html__( 'It display the get mobile app section', 'simontaxi' ),
        'base' => "simontaxi_vc_getmobileapp",
        "class" => '',
        'category' => $category,
		'icon' => $icon,
        "params" => array(
		    array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Title",'simontaxi' ),
            "param_name" => "title",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert Title text Here.",'simontaxi' ),
            ),
		    array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Sub Title",'simontaxi' ),
            "param_name" => "sub_title",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert Sub Title text Here.",'simontaxi' ),
            ),
			array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Android Link",'simontaxi' ),
            "param_name" => "android_link",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert Android Link Here.",'simontaxi' ),
            ),
			array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "IOS Link",'simontaxi' ),
            "param_name" => "ios_link",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert IOS Link Here.",'simontaxi' ),
            ),
			array(
            'type' => "attach_image",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Image",'simontaxi' ),
            "param_name" => "image",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Upload Image here Here.",'simontaxi' ),
            ),
			array(
            'type' => "textarea_html",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Content",'simontaxi' ),
            "param_name" => "content",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert Content text Here.",'simontaxi' ),
            ),
        )
    ) );

	/**
	 * Inner banner element
	*/
	vc_map( array(
        'name' => esc_html__( "Simontaxi Inner banner",'simontaxi' ),
		'description' => esc_html__( 'It display the Inner banner section', 'simontaxi' ),
        'base' => "simontaxi_singlepagebanner",
        "class" => '',
        'category' => $category,
		'icon' => $icon,
        "params" => array(
		    array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Title",'simontaxi' ),
            "param_name" => "title",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert Title text Here.",'simontaxi' ),
            ),
			array(
            'type' => "attach_image",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Image",'simontaxi' ),
            "param_name" => "bg_image",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Upload Image here Here.",'simontaxi' ),
            ),
        )
    ) );

	/**
	 * Inner About us page element
	*/
	vc_map( array(
        'name' => esc_html__( "Simontaxi About us page",'simontaxi' ),
		'description' => esc_html__( 'It display the About us page section', 'simontaxi' ),
        'base' => "simontaxi_aboutuscontent",
        "class" => '',
        'category' => $category,
		'icon' => $icon,
        "params" => array(
		    array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Title",'simontaxi' ),
            "param_name" => "title",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert Title text Here.",'simontaxi' ),
            ),
			array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Sub Title",'simontaxi' ),
            "param_name" => "sub_title",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert Sub Title text Here.",'simontaxi' ),
            ),
			array(
            'type' => "textarea_html",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Content",'simontaxi' ),
            "param_name" => "content",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert Content Here.",'simontaxi' ),
            ),
			array(
            'type' => "attach_image",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Image",'simontaxi' ),
            "param_name" => "left_image",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Upload Image here Here.",'simontaxi' ),
            ),
        )
    ) );

	/**
	 * Call now on home page
	*/
	vc_map( array(
        'name' => esc_html__( "Simontaxi Call Now",'simontaxi' ),
		'description' => esc_html__( 'It display the call now section', 'simontaxi' ),
        'base' => "simontaxi_callnow",
        "class" => '',
        'category' => $category,
		'icon' => $icon,
        "params" => array(
		    array(
			'type' => "attach_image",
			"holder" => "div",
			"class" => '',
			"heading" => esc_html__( "Upload Background Image",'simontaxi' ),
			"param_name" => "callnow_bg_img",
			"value" => esc_html__( '','simontaxi' ),
			'description' => esc_html__( "Upload Image Here.",'simontaxi' ),
			),
			array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Title",'simontaxi' ),
            "param_name" => "title",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert Title text Here.",'simontaxi' ),
            ),
		    array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Sub Title",'simontaxi' ),
            "param_name" => "sub_title",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert Sub Title text Here.",'simontaxi' ),
            ),
			array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Phone",'simontaxi' ),
            "param_name" => "phone",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert phone number here.",'simontaxi' ),
            ),
			array(
            'type' => "textarea",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Content",'simontaxi' ),
            "param_name" => "app_content",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert Content text Here.",'simontaxi' ),
            ),
        )
    ) );

	/**
	 * Advertise on home page
	*/
	$pages = get_pages(array( 'post_status' => 'publish' ) );
	$pages_opts = array(esc_html__( 'Please select page', 'simontaxi' ) => '' );
	if ( ! empty( $pages) ) {
		foreach( $pages as $page ) {
			$pages_opts[$page->post_title] = $page->ID;
		}
	}
	vc_map( array(
        'name' => esc_html__( "Simontaxi Advertise",'simontaxi' ),
		'description' => esc_html__( 'It display the advertise section', 'simontaxi' ),
        'base' => "simontaxi_advertise",
        "class" => '',
        'category' => $category,
		'icon' => $icon,
        "params" => array(
		    array(
			'type' => "attach_image",
			"holder" => "div",
			"class" => '',
			"heading" => esc_html__( "Upload Background Image",'simontaxi' ),
			"param_name" => "advertise_bg_img",
			"value" => esc_html__( '','simontaxi' ),
			'description' => esc_html__( "Upload Image Here.",'simontaxi' ),
			),
			array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Advertise Title",'simontaxi' ),
            "param_name" => "title",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert Title text Here.",'simontaxi' ),
            ),
			array(
            'type' => "textarea",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Description",'simontaxi' ),
            "param_name" => 'description',
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert Content text Here.",'simontaxi' ),
            ),
			array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Title",'simontaxi' ),
            "param_name" => "contactus_title",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert Contact us title here.",'simontaxi' ),
            ),
			array(
            'type' => "dropdown",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Advertise Page Link",'simontaxi' ),
            "param_name" => "pricing_plan",
            "value" => $pages_opts,
            'description' => esc_html__( "Choose Advertise Page Link.",'simontaxi' ),
            ),

        )
    ) );

	/**
	 * Banner on home page
	*/
	$pages = get_pages(array( 'post_status' => 'publish' ) );
	$pages_opts = array(esc_html__( 'No Link', 'simontaxi' ) => '', 'samepage' => esc_html__( 'Same Page', 'simontaxi' ) );
	if ( ! empty( $pages) ) {
		foreach( $pages as $page ) {
			$pages_opts[$page->post_title] = $page->ID;
		}
	}
	vc_map( array(
        'name' => esc_html__( "Simontaxi Home Page Banner",'simontaxi' ),
		'description' => esc_html__( 'It display the home page banner section', 'simontaxi' ),
        'base' => "simontaxi_homepagebanner",
        "class" => '',
        'category' => $category,
		'icon' => $icon,
        "params" => array(
		    array(
			'type' => "attach_image",
			"holder" => "div",
			"class" => '',
			"heading" => esc_html__( "Upload Background Image",'simontaxi' ),
			"param_name" => "bg_img",
			"value" => esc_html__( '','simontaxi' ),
			'description' => esc_html__( "Upload Image Here.",'simontaxi' ),
			),
			array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Title",'simontaxi' ),
            "param_name" => "title",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert Title text Here.",'simontaxi' ),
            ),
			array(
            'type' => "dropdown",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Booking Page?",'simontaxi' ),
            "param_name" => "is_booking",
            "value" => array(esc_html__( 'No', 'simontaxi' ) => 'no', esc_html__( 'Yes', 'simontaxi' ) => 'yes' ),
            'description' => esc_html__( "Choose whether to display booking page here.",'simontaxi' ),
            ),
		    array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Sub Title",'simontaxi' ),
            "param_name" => "sub_title",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert Sub Title text Here.",'simontaxi' ),
			"dependency"=>array( 'element'=>'is_booking','value' => array( 'no' ) ),
            ),
			array(
            'type' => "dropdown",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Book now link",'simontaxi' ),
            "param_name" => "booknow_link",
            "value" => $pages_opts,
            'description' => esc_html__( "Choose Book now link Page.",'simontaxi' ),
			"dependency"=>array( 'element'=>'is_booking','value' => array( 'no' ) ),
            ),
			array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Book now title",'simontaxi' ),
            "param_name" => "booknow_link_title",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Insert Contact us title here.",'simontaxi' ),
			"dependency"=>array( 'element'=>'is_booking','value' => array( 'no' ) ),
            ),

        )
    ) );

	/**
	 * Vehicles list
	*/
	vc_map( array(
        'name' => esc_html__( "Simontaxi Vehicles List",'simontaxi' ),
		'description' => esc_html__( 'It display the ', 'simontaxi' ) . simontaxi_get_default_title_plural() . esc_html__( ' section', 'simontaxi' ),
        'base' => "simontaxi_vehiclesgrid",
        "class" => '',
        'category' => $category,
		'icon' => $icon,
        "params" => array(
		    array(
			'type' => "attach_image",
			"holder" => "div",
			"class" => '',
			"heading" => esc_html__( "Upload Default Image for ", 'simontaxi' ) . simontaxi_get_default_title() . esc_html__( " Image",'simontaxi' ),
			"param_name" => "default_image",
			"value" => esc_html__( '','simontaxi' ),
			'description' => esc_html__( "Upload Image Here.",'simontaxi' ),
			),

			array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Records per page?",'simontaxi' ),
            "param_name" => "display_per_page",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Enter how many records to display per page.",'simontaxi' ),
            ),

        )
    ) );

	/**
	 * Vehicles Gallery
	*/
	vc_map( array(
        'name' => esc_html__( "Simontaxi Vehicles Gallery",'simontaxi' ),
		'description' => esc_html__( 'It display the ', 'simontaxi' ) . simontaxi_get_default_title_plural() . esc_html__( ' gallery section', 'simontaxi' ),
        'base' => "simontaxi_vehiclesgallery",
        "class" => '',
        'category' => $category,
		'icon' => $icon,
        "params" => array(
		    array(
			'type' => "attach_image",
			"holder" => "div",
			"class" => '',
			"heading" => esc_html__( "Upload Default Image for ", 'simontaxi' ) . simontaxi_get_default_title() . esc_html__( " Image",'simontaxi' ),
			"param_name" => "default_image",
			"value" => esc_html__( '','simontaxi' ),
			'description' => esc_html__( "Upload Image Here.",'simontaxi' ),
			),
			array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Title",'simontaxi' ),
            "param_name" => "title",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Enter title to display.",'simontaxi' ),
            ),
			array(
            'type' => "dropdown",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Layout",'simontaxi' ),
            "param_name" => "columns",
            "value" => array(esc_html__( '3 Column', 'simontaxi' ) => 3, esc_html__( '2 Column', 'simontaxi' ) => 2),
            'description' => esc_html__( "Choose columns display.",'simontaxi' ),
            ),
			array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Records per page?",'simontaxi' ),
            "param_name" => "display_per_page",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Enter how many records to display per page.",'simontaxi' ),
            ),

        )
    ) );

	/**
	 * Testimonials Element
	*/
	vc_map( array(
        'name' => esc_html__( "Simontaxi Testimonials",'simontaxi' ),
		'description' => esc_html__( 'This will display testimonial slider on the page', 'simontaxi' ),
        'base' => "simontaxi_vc_testimonial_container",
		"as_parent" => array( 'only' => 'simontaxi_vc_testimonial_single' ), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
        "content_element" => true,
		"show_settings_on_create" => false,
		'category' => $category,
		'icon' => $icon,
        "params" => array(
		    array(
			'type' => "attach_image",
			"holder" => "div",
			"class" => '',
			"heading" => esc_html__( "Upload Background Image",'simontaxi' ),
			"param_name" => "testimonial_bg_img",
			"value" => esc_html__( '','simontaxi' ),
			'description' => esc_html__( "Upload Image Here.",'simontaxi' ),
			),
			array(
			'type' => "dropdown",
			"holder" => "div",
			"class" => '',
			"heading" => esc_html__( "Background",'simontaxi' ),
			"param_name" => "testimonial_bgclass",
			"value" => array(esc_html__( 'No Background', 'simontaxi' ) => '', esc_html__( 'Dark Background', 'simontaxi' ) => 'st-dark-bg' ),
			'description' => esc_html__( "Background of the section.",'simontaxi' ),
			),
        ),
		"js_view" => 'VcColumnView'
    ) );

	//Your "container" content element should extend WPBakeryShortCodesContainer class to inherit all required functionality
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		class WPBakeryShortCode_simontaxi_vc_testimonial_container extends WPBakeryShortCodesContainer {
		}
	}
	vc_map( array(
        'name' => esc_html__( "Simontaxi Testimonial Content",'simontaxi' ),
        'base' => "simontaxi_vc_testimonial_single",
        "class" => '',
        'category' => $category,
		'icon' => $icon,
		"as_child" => array( 'only' => 'simontaxi_vc_testimonial_container' ),
        "params" => array(
		    array(
			'type' => "attach_image",
			"holder" => "div",
			"class" => '',
			"heading" => esc_html__( "Upload Client Image",'simontaxi' ),
			"param_name" => "testimonial_img",
			"value" => esc_html__( '','simontaxi' ),
			'description' => esc_html__( "Upload Image Here.",'simontaxi' )
			),
			array(
            'type' => "textarea",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Client Says",'simontaxi' ),
            "param_name" => "testimonial_content",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Content text Here.",'simontaxi' ),
            ),
			array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Client Name",'simontaxi' ),
            "param_name" => "testimonial_name",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Client Name Here.",'simontaxi' )
            ),
			array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Client Lives in",'simontaxi' ),
            "param_name" => "testimonial_livesin",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Client Lives in Here.",'simontaxi' )
            )
        )
    ) );

	/**
	 * Driver Element
	*/
	vc_map( array(
        'name' => esc_html__( "Simontaxi Drivers",'simontaxi' ),
		'description' => esc_html__( 'This will display drivers on the page', 'simontaxi' ),
        'base' => "simontaxi_vc_driver_container",
		"as_parent" => array( 'only' => 'simontaxi_vc_driver_single' ), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
        "content_element" => true,
		"show_settings_on_create" => false,
		'category' => $category,
		'icon' => $icon,
        "params" => array(
		    array(
			'type' => "attach_image",
			"holder" => "div",
			"class" => '',
			"heading" => esc_html__( "Upload Background Image",'simontaxi' ),
			"param_name" => "driver_bg_img",
			"value" => esc_html__( '','simontaxi' ),
			'description' => esc_html__( "Upload Image Here.",'simontaxi' ),
			),
			array(
			'type' => "dropdown",
			"holder" => "div",
			"class" => '',
			"heading" => esc_html__( "Background",'simontaxi' ),
			"param_name" => "driver_bgclass",
			"value" => array(esc_html__( 'No Background', 'simontaxi' ) => '', esc_html__( 'Yellow Background', 'simontaxi' ) => 'st-yellow-bg' ),
			'description' => esc_html__( "Title of the section.",'simontaxi' ),
			),
			array(
			'type' => "textfield",
			"holder" => "div",
			"class" => '',
			"heading" => esc_html__( "Title",'simontaxi' ),
			"param_name" => "driver_title",
			"value" => esc_html__( '','simontaxi' ),
			'description' => esc_html__( "Title of the section.",'simontaxi' ),
			),
        ),
		"js_view" => 'VcColumnView'
    ) );

	//Your "container" content element should extend WPBakeryShortCodesContainer class to inherit all required functionality
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		class WPBakeryShortCode_simontaxi_vc_driver_container extends WPBakeryShortCodesContainer {
		}
	}
	vc_map( array(
        'name' => esc_html__( "Simontaxi Driver Content",'simontaxi' ),
        'base' => "simontaxi_vc_driver_single",
        "class" => '',
        'category' => $category,
		'icon' => $icon,
		"as_child" => array( 'only' => 'simontaxi_vc_driver_container' ),
        "params" => array(
		    array(
			'type' => "attach_image",
			"holder" => "div",
			"class" => '',
			"heading" => esc_html__( "Upload Driver Image",'simontaxi' ),
			"param_name" => "driver_img",
			"value" => esc_html__( '','simontaxi' ),
			'description' => esc_html__( "Upload Image Here.",'simontaxi' )
			),
			array(
            'type' => "textfield",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Driver Name",'simontaxi' ),
            "param_name" => "driver_name",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Client Name Here.",'simontaxi' )
            ),
			array(
            'type' => "textarea",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Details",'simontaxi' ),
            "param_name" => "driver_content",
            "value" => esc_html__( '','simontaxi' ),
            'description' => esc_html__( "Content text Here.",'simontaxi' ),
            ),
			array(
            'type' => "dropdown",
            "holder" => "div",
            "class" => '',
            "heading" => esc_html__( "Social Links Display?",'simontaxi' ),
            "param_name" => "driver_social_links_display",
            "value" => array(esc_html__( 'No', 'simontaxi' ) => 'no', esc_html__( 'Yes', 'simontaxi' ) => 'yes' ),
            'description' => esc_html__( "Is Driver has social accounts?.",'simontaxi' )
            ),
			array(
			'type' => "textfield",
			"holder" => "div",
			"class" => '',
			"heading" => esc_html__( "Driver Facebook",'simontaxi' ),
			"param_name" => "driver_facebook",
			"value" => esc_html__( '','simontaxi' ),
			'description' => esc_html__( "Drivar Facebook Link.",'simontaxi' ),
			"dependency"=>array( 'element'=>'driver_social_links_display','value' => array( 'yes' ) )
			),
			array(
			'type' => "textfield",
			"holder" => "div",
			"class" => '',
			"heading" => esc_html__( "Driver Linkedin",'simontaxi' ),
			"param_name" => "driver_linkedin",
			"value" => esc_html__( '','simontaxi' ),
			'description' => esc_html__( "Drivar Linkedin Link.",'simontaxi' ),
			"dependency"=>array( 'element'=>'driver_social_links_display','value' => array( 'yes' ) )
			),
			array(
			'type' => "textfield",
			"holder" => "div",
			"class" => '',
			"heading" => esc_html__( "Driver Twitter",'simontaxi' ),
			"param_name" => "driver_twitter",
			"value" => esc_html__( '','simontaxi' ),
			'description' => esc_html__( "Drivar Twitter Link.",'simontaxi' ),
			"dependency"=>array( 'element'=>'driver_social_links_display','value' => array( 'yes' ) )
			),
			array(
			'type' => "textfield",
			"holder" => "div",
			"class" => '',
			"heading" => esc_html__( "Driver Instagram",'simontaxi' ),
			"param_name" => "driver_instagram",
			"value" => esc_html__( '','simontaxi' ),
			'description' => esc_html__( "Drivar Instagram Link.",'simontaxi' ),
			"dependency"=>array( 'element'=>'driver_social_links_display','value' => array( 'yes' ) )
			),
        )
    ) );
	
	/**
	 * Separate Page for Booking Type
	 *
	 * @since 2.0.8
	*/
	vc_map( array(
        'name' => esc_html__( "Simontaxi Booking Page",'simontaxi' ),
		'description' => esc_html__( 'It display the booking page with selected booking types', 'simontaxi' ),
        'base' => "simontaxi_booking_page",
        "class" => '',
        'category' => $category,
		'icon' => $icon,
        "params" => array(		    
			array(
				'type' => "textfield",
				"holder" => "div",
				"class" => '',
				"heading" => esc_html__( "Title",'simontaxi' ),
				"param_name" => "title",
				"value" => esc_html__( '','simontaxi' ),
				'description' => esc_html__( "Enter title to display.",'simontaxi' ),
            ),
			array(
				'type' => "checkbox",
				"holder" => "div",
				"class" => '',
				"heading" => esc_html__( "Booking Types",'simontaxi' ),
				"param_name" => "booking_types",
				"value" => simontaxi_booking_types(),
				'description' => esc_html__( "Choose booking types available for this page.",'simontaxi' ),
            ),
        )
    ) );
	
	/**
	 * Vehicles List show case
	 *
	 * @since 2.0.8
	*/
	vc_map( array(
        'name' => esc_html__( "Simontaxi Vehicles List (Static)",'simontaxi' ),
		'description' => esc_html__( 'This will display vehicles list on the page', 'simontaxi' ),
        'base' => "simontaxi_vc_vehicles_container",
		"as_parent" => array( 'only' => 'simontaxi_vc_vehicle_single' ), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
        "content_element" => true,
		"show_settings_on_create" => true,
		'category' => $category,
		'icon' => $icon,
        "params" => array(
			array(
			'type' => "textfield",
			"holder" => "div",
			"class" => '',
			"heading" => esc_html__( "Title",'simontaxi' ),
			"param_name" => "container_title",
			"value" => esc_html__( '','simontaxi' ),
			'description' => esc_html__( "Title of the section.",'simontaxi' ),
			),
        ),
		"js_view" => 'VcColumnView'
    ) );

	//Your "container" content element should extend WPBakeryShortCodesContainer class to inherit all required functionality
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		class WPBakeryShortCode_simontaxi_vc_vehicles_container extends WPBakeryShortCodesContainer {
		}
	}
	vc_map( array(
        'name' => esc_html__( "Simontaxi Vehicle Content",'simontaxi' ),
        'base' => "simontaxi_vc_vehicle_single",
        "class" => '',
        'category' => $category,
		'icon' => $icon,
		"as_child" => array( 'only' => 'simontaxi_vc_vehicles_container' ),
        "params" => array(
			array(
				'type' => "textfield",
				"holder" => "div",
				"class" => '',
				"heading" => esc_html__( "Vehicle Name",'simontaxi' ),
				"param_name" => "vehicle_name",
				"value" => esc_html__( '','simontaxi' ),
				'description' => esc_html__( "Vehicle Name Here.",'simontaxi' )
            ),
			array(
				'type' => "dropdown",
				"holder" => "div",
				"class" => '',
				"heading" => esc_html__( "Read more",'simontaxi' ),
				"param_name" => "read_more",
				"value" => $pages_opts,
				'description' => esc_html__( "Choose read more page.",'simontaxi' ),
            ),
        )
    ) );
	
	/**
	 * Heading
	 *
	 * @since 2.0.8
	*/
	vc_map( array(
        'name' => esc_html__( "Simontaxi Heading",'simontaxi' ),
		'description' => esc_html__( 'It display heading for simontaxi theme', 'simontaxi' ),
        'base' => "simontaxi_heading",
        "class" => '',
        'category' => $category,
		'icon' => $icon,
		"content_element" => true,
		"show_settings_on_create" => true,
        "params" => array(		    
			array(
				'type' => "textfield",
				"holder" => "div",
				"class" => '',
				"heading" => esc_html__( "Title",'simontaxi' ),
				"param_name" => "title",
				"value" => esc_html__( '','simontaxi' ),
				'description' => esc_html__( "Enter title to display.",'simontaxi' ),
            ),
			array(
				'type' => "dropdown",
				"holder" => "div",
				"class" => '',
				"heading" => esc_html__( "Read more",'simontaxi' ),
				"param_name" => "read_more",
				"value" => $pages_opts,
				'description' => esc_html__( "Choose read more page.",'simontaxi' ),
            ),
        )
    ) );
	
	/**
	 * Registration Page
	 *
	 * @since 2.0.9
	*/
	$available_roles_arr = simontaxi_available_roles();
	$available_roles = array();
	foreach( $available_roles_arr as $key => $val ) {
		$available_roles[ $val ] = $key;
	}
	vc_map( array(
        'name' => esc_html__( "Simontaxi Registration Page",'simontaxi' ),
		'description' => esc_html__( 'It display registration for simontaxi theme', 'simontaxi' ),
        'base' => "simontaxi_registration",
        "class" => '',
        'category' => $category,
		'icon' => $icon,
		"content_element" => true,
		"show_settings_on_create" => true,
        "params" => array(		    
			array(
				'type' => "textfield",
				"holder" => "div",
				"class" => '',
				"heading" => esc_html__( "Top Description",'simontaxi' ),
				"param_name" => "top_description",
				"value" => esc_html__( '','simontaxi' ),
				'description' => esc_html__( "Enter Top Description to display.",'simontaxi' ),
            ),
			array(
				'type' => "dropdown",
				"holder" => "div",
				"class" => '',
				"heading" => esc_html__( "Role",'simontaxi' ),
				"param_name" => "role",
				"value" => $available_roles,
				'description' => esc_html__( "Choose default role for the user who register by using this page.",'simontaxi' ),
            ),
			array(
				'type' => "textfield",
				"holder" => "div",
				"class" => '',
				"heading" => esc_html__( "Bottom Description",'simontaxi' ),
				"param_name" => "bottom_description",
				"value" => esc_html__( '','simontaxi' ),
				'description' => esc_html__( "Enter Bottom Description to display.",'simontaxi' ),
            ),
			array(
				'type' => "dropdown",
				"holder" => "div",
				"class" => '',
				"heading" => esc_html__( "Need Approval?",'simontaxi' ),
				"param_name" => "approval",
				"value" => array(
					esc_html__( 'Yes', 'simontaxi' ) => 'yes',
					esc_html__( 'No', 'simontaxi' ) => 'no',
				),
				'description' => esc_html__( "Choose whether this user need to verify email OR not.",'simontaxi' ),
            ),
        )
    ) );
}

add_shortcode( 'simontaxi_vc_testimonial_single', 'simontaxi_vc_testimonial_single' );
function simontaxi_vc_testimonial_single( $atts,$content = null) { // New function parameter $content is added!
    $a = shortcode_atts( array(
				'testimonial_img' => '',
				'testimonial_content' => '',
				'testimonial_name' => '',
				'testimonial_livesin' => '',
			), $atts );
    $src  = wp_get_attachment_image_src( $a['testimonial_img'] ,'full' );
	if ( ! empty( $src) ) {
	 $src  = $src[0];
	 $thumb_w = '80';
	 $thumb_h = '80';
	 $image = simontaxi_resize( $src, $thumb_w, $thumb_h, true);
	}
	ob_start();
	?>
	<div class="item">
		<div class="st-testimo">
			<div class="st-testimo-box">
				<div class="st-testimo-profile">
					<img src="<?php echo esc_url( $image); ?>" alt='' class="img-circle img-responsive">
				</div>
				<p class="st-text"><?php echo $a['testimonial_content']; ?></p>
			</div>
			<h4 class="st-name"><?php echo $a['testimonial_name']; ?></h4>
			<p class="st-info"><?php esc_html_e( 'Lives in:', 'simontaxi' ); ?> <?php echo $a['testimonial_livesin']; ?></p>
		</div>
	</div>
	<?php
	$result = ob_get_clean();
	return $result;
}

add_shortcode( 'simontaxi_vc_testimonial_container', 'simontaxi_vc_testimonial_container' );
function simontaxi_vc_testimonial_container( $atts,$content = null) {
	$a = shortcode_atts( array(
				'testimonial_bg_img' => '',
				'testimonial_bgclass' => '',
			), $atts );
	$src  = wp_get_attachment_image_src( $a['testimonial_bg_img'] ,'full' );
	$image = $style =  '';
	if ( ! empty( $src) ) {
	 $src  = $src[0];
	 $thumb_w = '80';
	 $thumb_h = '80';
	 $image = simontaxi_resize( $src, $thumb_w, $thumb_h, true);
	}
	if ( ! empty( $image) ) {
		$style = ' style=\'background:rgba(0, 0, 0, 0) url( "'.$image. '") repeat scroll center center / cover \'';
	}
	ob_start();
	?>
	<!-- TESTIMONIALS - Slick Slider -->
    <section class="st-section <?php echo $a['testimonial_bgclass']; ?>" <?php echo $style; ?>>
        <div class="container">
            <div class="row text-center">
                <div class="col-md-12">
                    <!-- Testimonial Slider-->
                    <div class="st-team-slider">
					<?php echo do_shortcode( $content); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
	$result = ob_get_clean();
	return $result;
}

add_shortcode( 'simontaxi_vc_getmobileapp', 'simontaxi_vc_getmobileapp' );
function simontaxi_vc_getmobileapp( $atts,$content = null) {
	$a = shortcode_atts( array(
				'title' => esc_html__( 'Get our mobile app', 'simontaxi' ),
				'sub_title' => esc_html__( 'Book your taxi in just a few second!', 'simontaxi' ),
				'app_content' => esc_html__( '<h4 class="st-app-title">Easy to use</h4>
                    <p class="st-app-text">But the majority have suffered alteration in some form there are many available.</p>
                    <h4 class="st-app-title">Fast responce</h4>
                    <p class="st-app-text">But the majority have suffered.</p>', 'simontaxi' ),
				'image' => '',
				'android_link' => '',
				'ios_link' => '',
			), $atts );
	$a['app_content'] = $content;
	$src  = wp_get_attachment_image_src( $a['image'] ,'full' );
	$image = $a['image'];
	if ( ! empty( $src) ) {
	 $src  = $src[0];
	 $thumb_w = '582';
	 $thumb_h = '415';
	 $image = simontaxi_resize( $src, $thumb_w, $thumb_h, true);
	} else {
		$image = get_template_directory_uri() . '/images/app-down.png';
	}
	ob_start();
	?>
	<!-- DOWNLOAD APP -->
    <section class="st-grey-bg st-appdownload-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <h2 class="st-app-heading"><?php echo esc_attr( $a['title']); ?></h2>
                    <h4 class="st-app-sub-heading"><?php echo esc_attr( $a['sub_title']); ?></h4>
                    <?php echo $a['app_content']; ?>
                    <?php if ( $a['android_link'] != '' || $a['ios_link'] != '' ) { ?>
					<ul class="st-app-download">
                        <?php if ( $a['android_link'] != '' ) { ?>
						<li>
                            <a href="<?php echo esc_url( $a['android_link']); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/play.png" alt=''></a>
                        </li>
						<?php } ?>
                        <?php if ( $a['ios_link'] != '' ) { ?>
						<li>
                            <a href="<?php echo esc_url( $a['ios_link']); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/store.png" alt=''></a>
                        </li>
						<?php } ?>
                    </ul>
					<?php } ?>
                </div>
                <div class="col-lg-2 col-md-1 col-sm-1 col-xs-12">&nbsp;</div>
                <div class="col-lg-6 col-md-5 col-sm-5 col-xs-12">
                    <img src="<?php echo esc_url( $image); ?>" alt='' class="img-responsive">
                </div>
            </div>
        </div>
    </section>
    <!-- DOWNLOAD APP -->
	<?php
	$result = ob_get_clean();
	return $result;
}

add_shortcode( 'simontaxi_vc_driver_single', 'simontaxi_vc_driver_single' );
function simontaxi_vc_driver_single( $atts,$content = null) { // New function parameter $content is added!
	$a = shortcode_atts( array(
				'driver_img' => '',
				'driver_content' => '',
				'driver_name' => '',
				'driver_social_links_display' => 'no',
				'driver_facebook' => '',
				'driver_linkedin' => '',
				'driver_twitter' => '',
				'driver_instagram' => '',
			), $atts );
    $src  = wp_get_attachment_image_src( $a['driver_img'] ,'full' );
	if ( ! empty( $src) ) {
	 $src  = $src[0];
	 $thumb_w = '270';
	 $thumb_h = '280';
	 $image = simontaxi_resize( $src, $thumb_w, $thumb_h, true);
	} else {
		$image = get_template_directory_uri() . '/images/hire-card.png';
	}
	ob_start();
	?>
	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
		<!-- Team Mem - Single Item -->
		<div class="st-portfolio">
			<div class="st-portfolio-item">
				<img src="<?php echo esc_url( $image); ?>" class="img-responsive" alt=''>
				<?php if ( $a['driver_social_links_display'] == 'yes' ) { ?>
				<!-- portfolio item hover -->
				<div class="st-portfolio-hover">
					<div class="st-portfolio-hover-content ">
						<ul class="st-social-share text-center">
							<?php if ( $a['driver_facebook'] != '' ) { ?>
							<li><a href="<?php echo esc_url( $a['driver_facebook']); ?>" target="_blank" tabindex="0"><i class="fa fa-facebook"></i></a></li>
							<?php } ?>

							<?php if ( $a['driver_linkedin'] != '' ) { ?>
							<li><a href="<?php echo esc_url( $a['driver_linkedin']); ?>" target="_blank" tabindex="0"><i class="fa fa-linkedin"></i></a></li>
							<?php } ?>

							<?php if ( $a['driver_twitter'] != '' ) { ?>
							<li><a href="<?php echo esc_url( $a['driver_twitter']); ?>" target="_blank" tabindex="0"><i class="fa fa-twitter"></i></a></li>
							<?php } ?>

							<?php if ( $a['driver_instagram'] != '' ) { ?>
							<li><a href="<?php echo esc_url( $a['driver_instagram']); ?>" target="_blank" tabindex="0"><i class="fa fa-instagram "></i></a></li>
							<?php } ?>

						</ul>
					</div>
				</div>
				<?php } ?>
			</div>
			<div class="st-portfolio-content">
				<h4 class="st-portfolio-name"><?php echo esc_attr( $a['driver_name']); ?></h4>
				<p class="st-portfolio-info"><?php echo esc_attr( $a['driver_content']); ?></p>
			</div>
		</div>
		<!-- /Team Mem - Single Item -->
	</div>
	<?php
	$result = ob_get_clean();
	return $result;
}


if ( ! function_exists( 'simontaxi_vc_driver_container' ) ) :
	add_shortcode( 'simontaxi_vc_driver_container', 'simontaxi_vc_driver_container' );
	/**
	 * Drivers section
	 *
	 * @since 1.0
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	function simontaxi_vc_driver_container( $atts,$content = null) {
		$a = shortcode_atts( array(
					'driver_bg_img' => '',
					'driver_title' => 'Taxi Driver for Hire',
					'driver_bgclass' => '',
				), $atts );
		$src  = wp_get_attachment_image_src( $a['driver_bg_img'] ,'full' );
		$image = $style =  '';
		if ( ! empty( $src) ) {
		 $src  = $src[0];
		 $thumb_w = '80';
		 $thumb_h = '80';
		 $image = simontaxi_resize( $src, $thumb_w, $thumb_h, true);
		}
		if ( ! empty( $image) ) {
			$style = ' style=\'background:rgba(0, 0, 0, 0) url( "'.$image. '") repeat scroll center center / cover \'';
		}
		ob_start();
		?>
		<section class="st-section-sm <?php echo $a['driver_bgclass']; ?>" <?php echo $style; ?>>
			<div class="container">
				<div class="row text-center">
					<div class="col-md-12">
						<h2 class="st-heading"><?php echo esc_attr( $a['driver_title']); ?></h2>
					</div>
				</div>
				<div class="row">
						<?php echo do_shortcode( $content); ?>
				</div>
			</div>
		</section>
		<?php
		$result = ob_get_clean();
		return $result;
	}
endif;

if ( ! function_exists( 'simontaxi_faqs' ) ) :
	add_shortcode( 'simontaxi_faqs', 'simontaxi_faqs' );
	/**
	 * FAQ section
	 *
	 * @since 1.0
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	function simontaxi_faqs( $atts ) {
		$a = shortcode_atts( array(
					'columns' => '2',
				), $atts );
		$query = array(
					'post_type' => 'faq',
					'post_status' => array( 'publish' ),
					'posts_per_page' => -1,
				);
		$columns = 6;
		if ( $a['columns'] == 1 ) {
			$columns = 12;
		}
		ob_start();
		?>
		<!-- FAQ -->
		<div class="st-section-sm">
			<div class="container">
				<div class="row" id="accordion">
					<?php
					$loop = new WP_Query( $query);
					while ( $loop->have_posts() ) : $loop->the_post();
					?>
					<div class="col-sm-<?php echo esc_attr( $columns); ?>">
						<div class="st-faq">
							<h4><?php the_title(); ?></h4>
							<p><?php the_content(); ?></p>
						</div>
					</div>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
		<!-- /FAQ -->

		<?php
		return ob_get_clean();
	}
endif;

if ( ! function_exists( 'simontaxi_callnow' ) ) :
	add_shortcode( 'simontaxi_callnow', 'simontaxi_callnow' );
	/**
	 * Call now section
	 *
	 * @since 1.0
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	function simontaxi_callnow( $atts ) {
		$a = shortcode_atts( array(
					'title' => '24 <span class="st-primary-color">Hours</span>',
					'sub_title' => '7 <span class="st-primary-color">Days</span>',
					'description' => '<i class="fa fa-pencil-square-o st-primary-color" aria-hidden="true"></i> &nbsp;Call as now <span class="st-primary-color">or</span> Use our mobile app',
					'phone' => '',
					'callnow_bg_img' => ''
				), $atts );
		$style = '';
		$class = ' st-left-col';
		if ( $a['callnow_bg_img'] != '' ) {
			$src  = wp_get_attachment_image_src( $a['callnow_bg_img'] ,'full' );
			if ( ! empty( $src) ) {
			 $src  = $src[0];
			 $thumb_w = '788';
			 $thumb_h = '951';
			 $image = simontaxi_resize( $src, $thumb_w, $thumb_h, true);
			 if ( ! empty( $image) ) {
				$style = ' style=\'background:rgba(0, 0, 0, 0) url( "'.$image. '") no-repeat scroll right  center / cover \'';
			}
			}
		}
		ob_start();
		?>
		<!-- BOOKING FORM - Nav-Tabs -->
		<div id="st-search-form" class=" st-booking-bg">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-5 st-equal-col col-md-4<?php echo $class; ?>" <?php echo $style; ?>>
						<div class="st-booking-call st-section ">
							<h4><?php echo $a['title']; ?><br>
								<?php echo $a['sub_title']; ?></h4>
							<p><?php echo $a['description']; ?></p>
							<h2><?php echo $a['phone']; ?></h2>
						</div>
					</div>
					<?php echo do_shortcode( '[simontaxi_booking_step1 placement="homeleft"]' ); ?>
				</div>
			</div>
		</div>
		<!-- /BOOKING FORM - Nav-Tabs -->
		<?php
		return ob_get_clean();
	}
endif;

if ( ! function_exists( 'simontaxi_advertise' ) ) :
	add_shortcode( 'simontaxi_advertise', 'simontaxi_advertise' );
	/**
	 * Advertize Section
	 *
	 * @since 1.0
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	function simontaxi_advertise( $atts ) {
		$a = shortcode_atts( array(
					'title' => 'Advertise <br> on our taxi now !',

					'description' => 'Grab people&#8217;s attention with awesome advertising campaigns our taxi cars. Reach thousands of people with your creative ad. ',
					'contactus_title' => 'Contact us and Get started!',

					'pricing_plan' => '',
					'advertise_bg_img' => '',
				), $atts );
		$image = get_template_directory_uri() . '/images/car2.png';
		if ( $a['advertise_bg_img'] != '' ) {
			$src  = wp_get_attachment_image_src( $a['advertise_bg_img'] ,'full' );
			if ( ! empty( $src) ) {
			 $src  = $src[0];
			 $thumb_w = '316';
			 $thumb_h = '316';
			 $image = simontaxi_resize( $src, $thumb_w, $thumb_h, true);
			}
		}
		ob_start();
		?>
		<!-- ADVERTISE -->
		<div class="st-section st-yellow-bg">
			<div class="container">
				<div class="row">
					<div class="col-md-5 col-sm-5">
						<div class="st-ad-img"><img src="<?php echo esc_url( $image); ?>" alt='' class="img-responsive center-block"></div>
					</div>
					<div class="col-md-7 col-sm-7">
						<h3 class="st-ad-heading"><?php echo $a['title']; ?></h3>
						<?php /*?><h4 class="st-ad-sub-heading"><?php echo $a['sub_title']; ?></h4><?php */?>
						<p class="st-ad-text"><?php echo $a['description']; ?></p>
						<h4 class="st-ad-title"><?php echo $a['contactus_title']; ?></h4>
						<?php if ( $a['pricing_plan'] != '' ) { ?>
						<div><a href="<?php echo esc_url(get_permalink( $a['pricing_plan']) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Contact Us', 'simontaxi' ); ?></a></div>
						<?php } ?>
					</div>
					
				</div>
			</div>
		</div>
		<!-- /ADVERTISE -->
		<?php
		return ob_get_clean();
	}
endif;

if ( ! function_exists( 'simontaxi_homepagebanner' ) ) :
	add_shortcode( 'simontaxi_homepagebanner', 'simontaxi_homepagebanner' );
	/**
	 * Vehicles Grid
	 *
	 * @since 1.0
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	function simontaxi_homepagebanner( $atts ) {
		$a = shortcode_atts( array(
					'title' => esc_html__( 'A Reliable Way To Travel', 'simontaxi' ),
					'sub_title' => esc_html__( 'Best taxi services in your city', 'simontaxi' ),
					'booknow_link' => '',
					'booknow_link_title' => esc_html__( 'Book Now', 'simontaxi' ),
					'bg_img' => '',
					'is_booking' => 'no',
				), $atts );
		$style = '';
		if ( $a['is_booking'] == 'yes' ) {
			$class = 'st-home-banner st-home-banner-booking';
		} else {
			$class = 'st-home-banner';
		}
		if ( $a['bg_img'] != '' ) {
			$src  = wp_get_attachment_image_src( $a['bg_img'] ,'full' );
			if ( ! empty( $src) ) {
			 $src  = $src[0];
			 $thumb_w = '690';
			 $thumb_h = '1920';
			 $image = simontaxi_resize( $src, $thumb_w, $thumb_h, true);
			 if ( ! empty( $image) ) {
				$style = ' style=\' margin-top: 105px; padding: 242px 0; position: relative; background:rgba(0, 0, 0, 0) url( "'.$image. '") no-repeat scroll right  center / cover \'';
				$class = '';
			}
			}
		}
		ob_start();
		?>
		<!--Home Banner -->
		<div class="<?php echo esc_attr( $class); ?>" <?php echo $style; ?>>
			<div class="container">
				<div class="row">
					<?php if ( $a['is_booking'] == 'yes' ) {
						if ( function_exists( 'simontaxi_booking_step1' ) ) {
							echo do_shortcode( "[simontaxi_booking_step1 placement='hometop']");
						}
					} else { ?>
					<div class="col-md-12 text-center">

						<h4 class="st-hero-tag "><?php echo esc_attr( $a['sub_title']); ?></h4>
						<h1 class="st-hero-title animated fadeInUp"><?php echo esc_attr( $a['title']); ?></h1>
						<?php if ( $a['booknow_link'] != '' ) {
						if ( $a['booknow_link'] == 'samepage' ) {
							$booknow_link = '#st-search-form';
						} else {
							$booknow_link = get_permalink( $a['booknow_link']);
						}
						if ( function_exists( 'simontaxi_booking_step1' ) ) {
						?>
						<div>
							<a href="<?php echo $booknow_link; ?>" class="btn btn-primary st-scroll-btn"><?php echo esc_attr( $a['booknow_link_title'] ); ?></a>
						</div>
						<?php }
						}
						?>
					</div>
	<?php } ?>
				</div>
			</div>
		</div>
		<!-- /Home Banner -->
		<?php
		return ob_get_clean();
	}
endif;

if ( ! function_exists( 'simontaxi_vehiclesgrid' ) ) :
	add_shortcode( 'simontaxi_vehiclesgrid', 'simontaxi_vehiclesgrid' );
	/**
	 * Vehicles Grid
	 *
	 * @since 1.0
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	function simontaxi_vehiclesgrid( $atts ) {
		global $wpdb;
		$a = shortcode_atts( array(
					'allow_filter' => 'yes',
					'display_per_page' => 5,
					'default_image' => '',
				), $atts );

		$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;

		$perpage = $a['display_per_page'];
		if ( $page > 1) {
		$offset = $page * $perpage - $perpage;
		} else {
		$offset = 0;
		}
		if ( isset( $_POST['filter_name_value']) ) {
			if ( $_POST['filter_name_value'] != '' ) {
				$parts = explode( '_', $_POST['filter_name_value']);
				$tax_query = array(
								'taxonomy' => $parts[0],
								'field'    => 'slug',
								'terms'    => $parts[1],
							);
				/**
				 * @since 2.0.0
				*/
				simontaxi_set_session( 'filters', $tax_query );
			} else {
				/**
				 * @since 2.0.0
				*/
				simontaxi_set_session( 'filters', array() );
			}

		}
		$args = array( 'perpage' => $perpage, 'offset' => $offset, 'pagination' => true);

		$args['tax_query'] = simontaxi_get_session( 'filters', array() );

		$vehicles_arr = simontaxi_get_vehicles( $args );
		$vehicles = isset( $vehicles_arr['vehicles']) ? $vehicles_arr['vehicles'] : array();
		ob_start();
		?>
		<!-- Grid List with Widget sidebar -->

					<!--  Grid-List View-->

						<?php $vehicle_types = get_terms(array( 'taxonomy' => 'vehicle_types', 'hide_empty' => false) );
						$vehicle_features = get_terms(array( 'taxonomy' => 'vehicle_features', 'hide_empty' => false) );
						?>
						<!-- Filter Bar -->
						<div class="st-filter-bar hidden-xs ">
							<div class="row">

								<div class="col-sm-12">
									<!-- Grid-List Toggle Buttons -->
									<ul class="st-grid-list">
										<li id="st-grid" class="icon-grid active"></li>
										<li id="st-list" class="icon-list"></li>
									</ul>
								</div>

							</div>
						</div>

						<!-- /Filter Bar -->
						<div class="row">
							<?php
							foreach ( $vehicles as $vehicle) :
							$vehicle_id = $vehicle->ID;
							$meta = simontaxi_filter_gk(get_post_meta( $vehicle_id ) );
							?>
							<?php
							$image = get_template_directory_uri() . '/images/car1.png';
							if (has_post_thumbnail( $vehicle_id) )
							{
								$thumb = get_post_thumbnail_id( $vehicle_id);
								$attachment_url = wp_get_attachment_url( $thumb, 'full' );
								$image = simontaxi_resize( $attachment_url, 370, 251, true);
							}
							?>
							<!-- Single Item -->
							<div class="col-md-6 col-sm-6 st-item ">
								<div class="st-card ">
									<a href="<?php echo esc_url( get_permalink( $vehicle_id) ); ?>"><img class="img-responsive st-card-img" src="<?php echo esc_url( $image); ?>" alt="<?php echo esc_attr( $vehicle->post_title); ?>" title="<?php echo esc_attr( $vehicle->post_title); ?>"></a>
									<div class="st-card-content">
										<h4 class="st-card-title"><?php echo esc_attr( $vehicle->post_title); ?> <span class="st-card-price"><span class="st-price-tag"><?php esc_html_e( 'From', 'simontaxi' ); ?></span> <?php echo (isset( $meta['p2p_unit_price']) ) ? simontaxi_get_currency( $meta['p2p_unit_price']) : esc_html__( 'NA', 'simontaxi' ); ?></span></h4>
										<ul class="st-card-list">
											<?php if ( isset( $meta['seating_capacity']) ) { ?>
											<li><i class="fa icon-people"></i> <span><?php esc_html_e( 'Max', 'simontaxi' ); ?> </span> <?php echo (isset( $meta['seating_capacity']) ) ? esc_attr( $meta['seating_capacity']) : esc_html__( 'NA', 'simontaxi' ); ?>
												<span> <?php esc_html_e( 'people', 'simontaxi' ); ?><br><?php esc_html_e( 'per vechicle', 'simontaxi' ); ?></span>
											</li>
											<?php } ?>
											<?php if ( isset( $meta['luggage']) ) { ?>
											<li><i class="fa icon-briefcase"></i> <span><?php esc_html_e( 'Max', 'simontaxi' ); ?> </span> <?php echo esc_attr( $meta['luggage']); ?> <?php if ( isset( $meta['luggage_type_symbol']) && $meta['luggage_type_symbol'] != '' ) {
											echo esc_attr( $meta['luggage_type_symbol']);
										} else {
											echo ( isset( $meta['luggage_type'] ) ) ? esc_attr( substr( $meta['luggage_type'], 0, 1 ) ) : esc_html__( 'NA', 'simontaxi' );
										}?>
												<span> <?php esc_html_e( 'luggage', 'simontaxi' ); ?><br><?php esc_html_e( 'per vechicle', 'simontaxi' ); ?></span>
											</li>
											<?php } ?>
											<li><a href="<?php echo esc_url( get_permalink( $vehicle_id) ); ?>" class="st-card-btn"><?php esc_html_e( 'View more', 'simontaxi' ); ?></a></li>
										</ul>
									</div>
								</div>
							</div>
							<!-- /Single Item -->
							<?php endforeach; ?>
						</div>
						<ul class="pagination st-pagination">
						<?php
						$total = $vehicles_arr['total'];
						echo paginate_links( array(
						'base' => add_query_arg( 'cpage', '%#%' ),
						'format' => '',
						'prev_text' => esc_html__( '&laquo;', 'simontaxi' ),
						'next_text' => esc_html__( '&raquo;', 'simontaxi' ),
						'total' => ceil( $total / $args['perpage']),
						'current' => $page
						) );
						?>
						</ul>

					<!--  Grid-List View-->


		<!-- /Grid List with Widget sidebar -->
		<?php
		return ob_get_clean();
	}
endif;

if ( ! function_exists( 'simontaxi_vehiclesgallery' ) ) :
	add_shortcode( 'simontaxi_vehiclesgallery', 'simontaxi_vehiclesgallery' );
	/**
	 * Vehicles Gallery
	 *
	 * @since 1.0
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	function simontaxi_vehiclesgallery( $atts ) {
		$a = shortcode_atts( array(
					'title' => esc_html__( 'Choose Your Taxi', 'simontaxi' ),
					'columns' => 2,
					'display_per_page' => 5,
					'default_image' => '',
				), $atts );
		$vehicle_types = get_terms( array( 'taxonomy' => 'vehicle_types', 'hide_empty' => false) );
		$class = 'col-lg-6 col-md-6 col-sm-6';
		if ( $a['columns'] == 3 ) {
			$class = 'col-lg-4 col-md-4 col-sm-6';
		}
		ob_start();
		?>
		<!-- Gallery / Portfolio -->
		<section class="st-section">
			<div class="container">
				<div class="row text-center">
					<div class="col-md-12">
						<h2 class="st-heading"><?php echo esc_attr( $a['title']); ?></h2>
						<ul class="nav nav-pills st-nav-pills st-btm50">
							<?php
							$i = 0;
							foreach ( $vehicle_types as $type ) {
							$active = '';
							if ( $i == 0 ) {
								$active = ' class=active';
								$i++;
							}
							?>
							<li <?php echo esc_attr( $active); ?>><a data-toggle="pill" href="#st-<?php echo esc_attr( $type->term_id); ?>"><?php echo esc_attr( $type->name); ?></a></li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<div class="row st-btm50">
					<div class="tab-content">
						<?php if ( ! empty( $vehicle_types) ) {
								$i = 0;
								foreach ( $vehicle_types as $type ) {
									$active = '';
									if ( $i == 0) {
										$active = ' in active';
										$i++;
									}
									?>
						<div id="st-<?php echo esc_attr( $type->term_id); ?>" class="tab-pane fade<?php echo esc_attr( $active); ?>">
							<?php
							$query = array(
								'post_type' => 'vehicle',
								'post_status' => array( 'publish' ),
								'posts_per_page' => $a['display_per_page'],
								'orderby' => 'ID',
								'tax_query' => array( array(
									'taxonomy' => 'vehicle_types',
									'field'    => 'slug',
									'terms'    => $type->slug,
								) ),
							);
							$loop = new WP_Query( $query);
							
							while ( $loop->have_posts() ) : $loop->the_post();
							$post_id = get_the_ID();
							$meta = simontaxi_filter_gk(get_post_meta( $post_id ) );
							if ( $a['default_image'] != '' ) {
								$image = $a['default_image'];
							} else {
								$image = get_template_directory_uri() . '/images/01.png';
							}

							if ( has_post_thumbnail( $post_id ) )
							{
								$thumb = get_post_thumbnail_id( $post_id );
								$attachment_url = wp_get_attachment_url( $thumb, 'full' );
								$image = simontaxi_resize( $attachment_url, 370, 280, true );
							}
							?>
							<!-- Single Item -->
							<div class="<?php echo $class; ?>">
								<div class="st-portfolio-item st-gallery-item">
									<img src="<?php echo esc_url( $image ); ?>" class="img-responsive" alt=''>
									<!-- portfolio item hover -->
									<div class="st-portfolio-hover  st-dark-hover">
										<div class="st-hover-content">
											<h4><?php the_title(); ?></h4>
											<p><?php esc_html_e( 'Price: ', 'simontaxi' ); ?><?php echo (isset( $meta['p2p_unit_price']) ) ? simontaxi_get_currency( $meta['p2p_unit_price']) : esc_html__( 'NA', 'simontaxi' ); ?></p>
											<button class="btn btn-primary" onclick="window.location='<?php echo simontaxi_get_bookingsteps_urls( 'step1' ) . '?selected_vehicle=' . $post_id; ?>'"><?php esc_html_e( 'Book Now', 'simontaxi' ); ?></button>
										</div>
									</div>
								</div>
							</div>
							<!-- /Single Item -->
							<?php endwhile;
							wp_reset_postdata();
							if ( $loop->found_posts == 0 ) {
								?>
								<div class="row text-center">
									<div class="col-xs-12"><?php echo esc_html__( 'No ', 'simontaxi' ) . simontaxi_get_default_title() . esc_html__( ' found', 'simontaxi' ); ?></div>
								</div>
								<?php
							}
							if ( $loop->found_posts > $a['display_per_page'] ) {
							?>
							<div class="row text-center link_selector_div">
								<div class="col-xs-12" id="link_selector_div_inner_<?php echo esc_attr( $type->term_id); ?>">
									<a href="javascript:void(0);" class="st-lagy-load link_selector" data-taxonomyid="<?php echo esc_attr( $type->term_id); ?>" data-page="1" data-perpage="<?php echo $a['display_per_page']; ?>" id="link_selector_<?php echo esc_attr( $type->term_id); ?>" data-total="<?php echo $loop->found_posts; ?>" data-columns="<?php echo $a['columns']; ?>"><?php esc_html_e( 'Load more', 'simontaxi' ); ?></a>
								</div>
							</div>
							<?php }
							 ?>
							</div>
							<?php
						} }?>
					</div>
				</div>

			</div>
		</section>
		<!-- /Gallery / Portfolio -->
		<script type="text/javascript">
		jQuery(document).ready(function( $) {

			$( ".link_selector").click(function() {
				var taxonomyid = $(this).data( 'taxonomyid' );
				var page = $(this).data( 'page' );
				var perpage = $(this).data( 'perpage' );
				var total = $(this).data( 'total' );
				var columns = $(this).data( 'columns' );
				$.ajax({
						type: "POST",
						url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
						dataType: 'html',
						data: ({ action: 'load_more_vehicles', taxonomyid: taxonomyid, cpage:page, perpage:perpage, columns : columns}),
						success: function(data){
							var page = $( '#link_selector_'+taxonomyid).data( 'page' );
							$( '.link_selector_div' ).before(data);
							//$( '#st-'+taxonomyid).hide().fadeIn( 'slow' ).append(data);
							$( '#link_selector_'+taxonomyid).data( 'page', parseInt(page+1) );

							var page = $( '#link_selector_'+taxonomyid).data( 'page' );
							if ( total < (page * perpage) ) {
								$( '#link_selector_div_inner_'+taxonomyid).html( '<?php esc_html_e( 'No more records', 'simontaxi' ); ?>' );
							}
						}
					});
			});
		});

		</script>
		<?php
		return ob_get_clean();
	}
endif;

if ( ! function_exists( 'simontaxi_singlepagebanner' ) ) :
	add_shortcode( 'simontaxi_singlepagebanner', 'simontaxi_singlepagebanner' );
	/**
	 * Page Banner
	 *
	 * @since 1.0
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	function simontaxi_singlepagebanner( $atts ) {
		$a = shortcode_atts( array(
					'title' => esc_html__( 'Page', 'simontaxi' ),
					'bg_image' => '',
				), $atts );
		$src  = wp_get_attachment_image_src( $a['bg_image'] ,'full' );
		$image = $a['bg_image'];
		if ( ! empty( $src) ) {
		 $src  = $src[0];
		 $thumb_w = '1920';
		 $thumb_h = '336';
		 $image = simontaxi_resize( $src, $thumb_w, $thumb_h, true);
		} else {
			$image = $image = get_template_directory_uri() . '/images/inner-banner.png';
		}
		ob_start();
		?>
		<style>
		.st-inner-banner{
			background: rgba(0, 0, 0, 0) url( "<?php echo $image; ?>") repeat scroll center center / cover ;
			margin-top: 105px;
		}
		</style>
		<!-- Inner Banner -->
		<div class="st-inner-banner">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center">
						<h2 class="st-inner-heading animated fadeInUp"><?php echo esc_attr( $a['title']); ?></h2>
					</div>
				</div>
			</div>
		</div>
		<!-- /Inner Banner -->
		<?php
		return ob_get_clean();
	}
endif;

if ( ! function_exists( 'simontaxi_aboutuscontent' ) ) :
	add_shortcode( 'simontaxi_aboutuscontent', 'simontaxi_aboutuscontent' );
	/**
	 * About us content
	 *
	 * @since 1.0
	 * @param array $atts Shortcode attributes
	 * @param string $content Shortcode
	 * @return string
	 */
	function simontaxi_aboutuscontent( $atts, $content) {
		$a = shortcode_atts( array(
					'title' => '',
					'sub_title' => '',
					'left_image' => '',
					'read_more' => '',
				), $atts );
		$a['content'] = $content;
		$src  = wp_get_attachment_image_src( $a['left_image'] ,'full' );
		$image = $a['left_image'];
		if ( ! empty( $src) ) {
		 $src  = $src[0];
		 $thumb_w = '555';
		 $thumb_h = '416';
		 $image = simontaxi_resize( $src, $thumb_w, $thumb_h, true);
		} else {
			$image = $image = get_template_directory_uri() . '/images/about-car.png';
		}
		ob_start();
		?>
		<!-- About Us -->
		<div class="st-section">
			<div class="container">
				<div class="row">
					<div class="col-sm-6 col-xs-12">
						<?php if ( '' !== $a['title'] ) { ?>
						<h2 class="st-heading"><?php echo esc_attr( $a['title'] ); ?></h2>
						<?php } ?>
						<?php if ( '' !== $a['sub_title'] ) { ?>
						<h4 class="st-abt-heading"><?php echo esc_attr( $a['sub_title'] ); ?></h4>
						<?php } ?>
						<p class="st-abt-text"><?php echo $a['content']; ?></p>
						<?php if ( '' !== $a['read_more'] ) { ?>
						<div><a href="<?php echo esc_url( $a['read_more'] ); ?>" class="btn btn-primary"><?php esc_html_e( 'Read More', 'simontaxi' ); ?></a></div>
						<?php } ?>
					</div>

					<div class="col-sm-6 col-xs-12">
						<img src="<?php echo esc_url( $image ); ?>" alt='' class="img-responsive">
					</div>

				</div>
			</div>
		</div>
		<!-- /About Us -->
		<?php
		return ob_get_clean();
	}
endif;

if ( ! function_exists( 'simontaxi_booking_page' ) ) :
	add_shortcode( 'simontaxi_booking_page', 'simontaxi_booking_page' );
	/**
	 * About us content
	 *
	 * @since 1.0
	 * @param array $atts Shortcode attributes
	 * @param string $content Shortcode
	 * @return string
	 */
	function simontaxi_booking_page( $atts ) {
		$types = ( ! empty( $atts['booking_types'] ) ) ? $atts['booking_types'] : simontaxi_booking_types();
		$a = shortcode_atts( array(
				'placement' => 'fullpage',
				'booking_types' => $types,
			), $atts );
		return simontaxi_booking_step1( $a );
	}
endif;

if ( ! function_exists( 'simontaxi_vc_vehicles_container' ) ) :
	add_shortcode( 'simontaxi_vc_vehicles_container', 'simontaxi_vc_vehicles_container' );
	/**
	 * Vehicles section
	 *
	 * @since 1.0
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	function simontaxi_vc_vehicles_container( $atts,$content = null) {
		$a = shortcode_atts( array(
					'container_title' => 'Taxi for Hire',
				), $atts );
		ob_start();
		?>
		<section class="st-section-sm">
			<div class="container">
				<div class="row text-center">
					<div class="col-md-12">
						<h2 class="st-heading"><?php echo esc_attr( $a['vehicle_title']); ?></h2>
					</div>
				</div>
				<div class="row">
						<?php echo do_shortcode( $content); ?>
				</div>
			</div>
		</section>
		<?php
		$result = ob_get_clean();
		return $result;
	}
endif;

add_shortcode( 'simontaxi_vc_vehicle_single', 'simontaxi_vc_vehicle_single' );
function simontaxi_vc_vehicle_single( $atts,$content = null) { // New function parameter $content is added!
	$a = shortcode_atts( array(
				'vehicle_name' => '',
				'read_more' => '0',
			), $atts );
	ob_start();
	?>
	<li>&gt;
	<?php if ( ! empty( $a['read_more'] ) ) : ?>
		<a href="<?php echo esc_url( get_permalink( $a['read_more'] ) ); ?>" target="_blank">
	<?php endif; ?>
	<?php echo $a['vehicle_name']; ?>
	<?php if ( ! empty( $a['read_more'] ) ) : ?>
		</a>
	<?php endif; ?>
	</li>
	<?php
	$result = ob_get_clean();
	return $result;
}

add_shortcode( 'simontaxi_heading', 'simontaxi_heading' );
function simontaxi_heading( $atts,$content = null) { // New function parameter $content is added!
	$a = shortcode_atts( array(
				'title' => '',
				'read_more' => '0',
			), $atts );
	ob_start();
	?>
	<?php if ( ! empty( $a['read_more'] ) ) : ?>
		<a href="<?php echo esc_url( get_permalink( $a['read_more'] ) ); ?>" target="_blank">
	<?php endif; ?>
	<h2 class="st-heading"><?php echo esc_attr( $a['title']); ?></h2>
	<?php if ( ! empty( $a['read_more'] ) ) : ?>
		</a>
	<?php endif; ?>
	<?php
	$result = ob_get_clean();
	return $result;
}