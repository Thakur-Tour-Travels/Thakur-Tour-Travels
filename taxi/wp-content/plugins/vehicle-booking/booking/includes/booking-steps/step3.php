<?php
/**
 * Display the page to select vehicle (page is for the slug 'select-cab-type' )
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  Booking step3 page
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user_meta = array();
$current_user = array();
if(is_user_logged_in() ) {
    $current_user = wp_get_current_user();
    $user_meta = simontaxi_filter_gk( (array)get_user_meta(get_current_user_id() ) );
}
$booking_summany_step3 = simontaxi_get_option( 'booking_summany_step3', 'yes' );
$step3_sidebar_position = simontaxi_get_option( 'step3_sidebar_position', 'right' );
$default_breadcrumb_display_step3 = simontaxi_get_option( 'default_breadcrumb_display_step3', 'yes' );
$cols = 8;
if ( $booking_summany_step3 == 'no' ) {
    $cols = 12;
}
?>
<!-- Booking Form -->
<div class="st-section-sm st-grey-bg">
    <div class="container">
        <?php
		if ( 'yes' == simontaxi_get_option('show_numbered_navigation', 'yes') && 'yes' == simontaxi_get_option('show_numbered_navigation_fullwidth', 'yes') ) {
			do_action('simontaxi_bookings_breadcrumb', 'step3'); 
		}
		?>
		<div class="row">
			<?php
			if ( $booking_summany_step3 == 'yes' && $step3_sidebar_position == 'left' && isset( $booking_step1) && ( ! empty( $booking_step1 ) ) ) {
                $template = 'booking/includes/booking-steps/right-side.php';
				if ( simontaxi_is_template_customized( $template ) ) {
					require simontaxi_get_theme_template_dir_name() . $template;
				} else {
					require apply_filters( 'simontaxi_locate_rightside', SIMONTAXI_PLUGIN_PATH . $template );
				}
            } 
			do_action( 'simontaxi_sidebar_left_step3' );
			?>
            <div class="col-lg-<?php echo esc_attr( $cols ); ?> col-md-8 col-sm-12">
                <?php
				if ( 'yes' == simontaxi_get_option('show_numbered_navigation', 'yes') && 'no' == simontaxi_get_option('show_numbered_navigation_fullwidth', 'yes') ) {
					do_action('simontaxi_bookings_breadcrumb', 'step3'); 
				}
				?>
				<div class="st-booking-block">
                    <?php echo simontaxi_print_errors() ?>
                    <!-- Booking Progress -->
					<?php
					if ( 'yes' === $default_breadcrumb_display_step3 ) {
						/**
						 * @since 2.0.8
						 */
						$template = 'booking/includes/booking-steps/bread-crumb.php';
						if ( simontaxi_is_template_customized( $template ) ) {
							include_once( simontaxi_get_theme_template_dir_name() . $template );
						} else {
							include_once( apply_filters( 'simontaxi_locate_bread_crumb', SIMONTAXI_PLUGIN_PATH . $template ) );
						}
					}
					?>
                    <!-- end Booking Progress -->
					<?php do_action( 'simontaxi_step3_before_form' ); ?>
                    <div class="tab-content step3-layout">
                        <!-- TAB-1 -->
                        <div id="st-booktab1" class="tab-pane fade in active">
                            <form class="st-booking-form row" action="" method="POST" id="confirm-booking">
                                <?php do_action( 'simontaxi_step3_within_form' ); ?>
								<?php $user_creation = simontaxi_get_option( 'user_creation', 'no' ); 
								$email_cols = 12;
								if ( 'askuser' === $user_creation ) {
									$email_cols = 8;
								}
								?>
								<div class="form-group col-sm-<?php echo $email_cols; ?>">
                                    <label for="email"><?php esc_html_e( 'Email', 'simontaxi' ); ?><?php echo simontaxi_required_field(); ?></label>
                                    <div class="inner-addon right-addon">
                                        <?php
                                        $email = simontaxi_get_value( $booking_step3, 'email' );
                                        if ( $email == '' ) {
                                            $email = ( isset( $current_user->data->user_email) ) ? $current_user->data->user_email : '';
                                        }
                                        ?>
                                        <input type="text" class="form-control" name="email" id="email" placeholder="<?php esc_html_e( 'Enter email to receive booking confirmation', 'simontaxi' ); ?>" value="<?php echo esc_attr( $email ); ?>">
                                    </div>
                                </div>
								<?php
								if ( is_user_logged_in() ) {
									echo '<input type="hidden" name="user_creation" id="user_creation" value="no">';
								} else if ( 'yes' === $user_creation ) {
									echo '<input type="hidden" name="user_creation" id="user_creation" value="yes">';
								}
								else if ( 'askuser' === $user_creation ) {
								?>
								<div class="form-group col-sm-4">
                                    <label for="user_creation"><?php esc_html_e( 'Create an account?', 'simontaxi' ); ?></label>
                                    <div class="inner-addon right-addon">
                                        <?php
                                        $user_creation = simontaxi_get_value( $booking_step3, 'user_creation' );
                                        ?>
										<select class="form-control" name="user_creation" id="user_creation" title="<?php esc_html_e( 'Create an account?', 'simontaxi' ); ?>">
											<option value="yes" <?php if( 'yes' == $user_creation ) echo ' selected'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
											<option value="no" <?php if( 'no' == $user_creation ) echo ' selected'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
										</select>
                                        
                                    </div>
                                </div>
								<?php } ?>
								<?php do_action('simontaxi_step3_create_action'); ?>
                                <?php
                                /**
                                 * Let us display the passenger name based on admin settings
                                 */
                                if ( in_array( $name_display, array( 'fullnameoptional', 'fullnamerequired' ) ) ) {
                                    $full_name = simontaxi_get_value( $booking_step3, 'full_name' );
                                    if ( $full_name == '' ) {
                                        $full_name .= ( isset( $user_meta['first_name'] ) ) ? $user_meta['first_name'] : '';
                                        $full_name .= ( isset( $user_meta['last_name'] ) ) ? ' ' . $user_meta['last_name'] : '';
                                    }
                                ?>
                                <div class="form-group col-sm-12">
                                    <label for="full_name"><?php esc_html_e( 'Full Name', 'simontaxi' ); ?><?php if ( $name_display == 'fullnamerequired' ) { echo simontaxi_required_field(); } ?></label>
                                    <div class="inner-addon right-addon form-invalid">
                                        <input type="text" class="form-control" name="full_name" id="full_name" placeholder="<?php echo apply_filters( 'simontaxi_filter_passengername', esc_html__( 'Enter passenger name', 'simontaxi' ) ); ?>" value="<?php echo esc_attr( $full_name); ?>">
                                    </div>
                                </div>
                                <?php } elseif ( in_array( $name_display, array( 'firstoptionallastoptional', 'firstrequiredlastrequired', 'firstrequiredlastoptional', 'firstoptionallastrequired' ) ) ) {
                                    $first_name = simontaxi_get_value( $booking_step3, 'first_name' );
                                    if ( $first_name == '' ) {
                                        $first_name = ( isset( $user_meta['first_name'] ) ) ? $user_meta['first_name'] : '';
                                    }
                                    $last_name = simontaxi_get_value( $booking_step3, 'last_name' );
                                    if ( $last_name == '' ) {
                                        $last_name = ( isset( $user_meta['last_name'] ) ) ? ' ' . $user_meta['last_name'] : '';
                                    }
                                    ?>
                                    <div class="form-group col-sm-6">
                                        <label for="first_name"><?php esc_html_e( 'First Name', 'simontaxi' ); ?><?php if(in_array( $name_display, array( 'firstrequiredlastrequired', 'firstrequiredlastoptional' ) ) ) { echo simontaxi_required_field(); } ?></label>
                                        <div class="inner-addon right-addon">
                                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="<?php echo apply_filters( 'simontaxi_filter_passengerfirstname', esc_html__( 'Enter passenger first name', 'simontaxi' ) ); ?>" value="<?php echo esc_attr( $first_name); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="last_name"><?php esc_html_e( 'Last Name', 'simontaxi' ); ?><?php if ( in_array( $name_display, array( 'firstrequiredlastrequired', 'firstoptionallastrequired' ) ) ) { echo simontaxi_required_field(); } ?></label>
                                        <div class="inner-addon right-addon">
                                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="<?php echo apply_filters( 'simontaxi_filter_passengerlastname', esc_html__( 'Enter passenger last name', 'simontaxi' ) ); ?>" value="<?php echo esc_attr( $last_name); ?>">
                                        </div>
                                    </div>
                                    <?php
                                } ?>

                                <?php
                                /**
                                 * Let us display Phone number field based on admin settings
                                 */

                                if ( $phone_number != 'no' ){
                                    if ( in_array( $phone_number, array( 'phonecountryoptional', 'phonecountryrequired' ) ) ) {
                                        $countryList = simontaxi_get_countries();
                                    ?>
                                    <div class="">
                                        <div class="form-group col-sm-6">
                                            <label><?php esc_html_e( 'Country code', 'simontaxi' ); ?><?php if(in_array( $phone_number, array( 'phonecountryrequired' ) ) ) { echo simontaxi_required_field(); } ?></label>
                                            <div class="inner-addon right-addon">
                                            <select id="mobile_countrycode" name="mobile_countrycode" title="<?php esc_html_e( 'Country code', 'simontaxi' ); ?>"class="selectpicker show-tick show-menu-arrow">
                                            <option value=""><?php esc_html_e( 'Country code', 'simontaxi' ); ?></option>
                                            <?php
                                            if ( $countryList ) {
                                                $mobile_countrycode = simontaxi_get_value( $booking_step3, 'mobile_countrycode' );
                                                if ( simontaxi_get_session( 'booking_step3', '', 'mobile_countrycode' ) != '' ) {
													$mobile_countrycode = simontaxi_get_session( 'booking_step3', '', 'mobile_countrycode' );
												} 
                                                elseif(isset( $user_meta['mobile_countrycode'] ) )
                                                    $mobile_countrycode = $user_meta['mobile_countrycode'];
                                                foreach ( $countryList as $result) {
                                                    $code = $result->phonecode . '_' . $result->id_countries;
                                                    ?>
                                                    <option value="<?php echo $code; ?>" <?php if ( $mobile_countrycode == $code) echo 'selected="selected"'; ?>><?php echo $result->name . ' ( ' . $result->phonecode.' )'; ?> </option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label><?php esc_html_e( 'Mobile phone', 'simontaxi' ); ?><?php if(in_array( $phone_number, array( 'phonecountryrequired' ) ) ) { echo simontaxi_required_field(); } ?></label>
                                            <div class="inner-addon right-addon">
                                                <?php
                                                $mobile = simontaxi_get_value( $booking_step3, 'mobile' );
                                                if ( $mobile == '' ) {
                                                    $mobile = (isset( $user_meta['mobile'] ) ) ? $user_meta['mobile'] : '';
                                                }
                                                ?>
                                                <input type="text" class="form-control" name="mobile" id="mobile" placeholder="<?php esc_html_e( 'Phone number to receive SMS', 'simontaxi' ); ?>" value="<?php echo esc_attr( $mobile ); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <?php } elseif ( in_array( $phone_number, array( 'phoneoptional', 'phonerequired' ) ) ) {
                                        $country_code = simontaxi_get_countries_values( 'iso_alpha2', simontaxi_get_option( 'vehicle_country' ), 'phonecode' );
                                        $country_id = simontaxi_get_countries_values( 'iso_alpha2', simontaxi_get_option( 'vehicle_country' ), 'id_countries' );
                                        $mobile_countrycode = $country_code . '_' . $country_id;
                                        ?>
                                        <div class="form-group col-sm-12">
                                        <label><?php esc_html_e( 'Mobile phone', 'simontaxi' ); ?><?php if(in_array( $phone_number, array( 'phonerequired' ) ) ) { echo simontaxi_required_field(); } ?></label>
                                        <div class="inner-addon right-addon">
                                            <input type="text" class="form-control" name="mobile" placeholder="<?php esc_html_e( 'Phone number to receive SMS', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $booking_step3, 'mobile' ); ?>">
                                            <input type="hidden" name="mobile_countrycode" value="<?php echo $mobile_countrycode; ?>">
                                        </div>
                                        </div>
                                        <?php
                                    }
                                } ?>
								
								<?php
                                /**
                                 * Let us displaycompany_name fields based on admin settings
								 *
								 * @since 2.0.2
                                 */
								 
								 $company_name = simontaxi_get_option( 'company_name', 'no' );

                                if ( $company_name != 'no' ) {
                                    ?>
                                    <div class="form-group col-sm-12">
                                    <label for="company_name"><?php esc_html_e( 'Company Name', 'simontaxi' ); ?><?php if(in_array( $company_name, array( 'yesrequired' ) ) ) { echo simontaxi_required_field(); } ?></label>
                                    <div class="inner-addon right-addon">
                                        <input type="text" class="form-control" name="company_name"  id="company_name" placeholder="<?php esc_html_e( 'Company Name', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $booking_step3, 'company_name' ); ?>">
                                    </div>
                                    </div>
                                    <?php
                                }
                                ?>

                                <?php
                                /**
                                 * Let us display No. of passengers fields based on admin settings
                                 */
                                if ( $no_of_passengers_display != 'no' && $allow_number_of_persons == 'no' ) {
								?>
                                    <div class="form-group col-sm-12">
                                    <label for="no_of_passengers"><?php esc_html_e( 'No. of passengers', 'simontaxi' ); ?><?php if(in_array( $no_of_passengers_display, array( 'yesrequired' ) ) ) { echo simontaxi_required_field(); } ?></label>
                                    <div class="inner-addon right-addon">
                                        <input type="text" class="form-control" name="no_of_passengers"  id="no_of_passengers" placeholder="<?php esc_html_e( 'No. of passengers', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $booking_step3, 'no_of_passengers' ); ?>">
                                    </div>
                                    </div>
                                    <?php
                                } else { 
								/*
								?>
									<div class="form-group col-sm-12">
									<label for="no_of_passengers"><?php esc_html_e( 'No. of passengers', 'simontaxi' ); ?><?php if(in_array( $no_of_passengers_display, array( 'yesrequired' ) ) ) { echo simontaxi_required_field(); } ?></label>
									<div class="inner-addon right-addon">
										<input type="text" class="form-control" name="no_of_passengers"  id="no_of_passengers" placeholder="<?php esc_html_e( 'No. of passengers', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $booking_step1, 'number_of_persons' ); ?>" disabled>
									</div>
									</div>
								<?php 
								*/
								}
                                ?>

                                <?php
                                /**
                                 * Let us display land mark field based on admin settings
                                 */

                                 if ( $land_mark_pickupaddress_display != 'no' ) {
                                 ?>
                                <div class="col-sm-12 form-group">
                                    <label for="land_mark_pickupaddress"><?php esc_html_e( 'Land Mark / Pickup Address', 'simontaxi' ); ?><?php if(in_array( $land_mark_pickupaddress_display, array( 'yesrequired' ) ) ) { echo simontaxi_required_field(); } ?></label>
                                    <textarea name="land_mark_pickupaddress" id="land_mark_pickupaddress" class="form-control" rows="4" placeholder="<?php esc_html_e( 'Enter Land Mark / Pickup Address', 'simontaxi' ); ?>"><?php echo simontaxi_get_value( $booking_step3, 'land_mark_pickupaddress' ); ?></textarea>
                                </div>
                                 <?php } ?>

                                <?php
                                /**
                                 * Let us display additional pickup addresses based on admin settings and user selection
                                 */
                                $additional_pickups = isset( $booking_step1['additional_pickups'] ) ? $booking_step1['additional_pickups'] : 0;
                                if ( $additional_pickup_address_display != 'no' && $additional_pickups > 0 ) {
                                    if ( isset ( $_POST['additional_pickup_address'] ) ) {
										 $additional_pickup_address = $_POST['additional_pickup_address'];
									 } else {
									$additional_pickup_address = simontaxi_get_session( 'booking_step3', array(), 'additional_pickup_address' );
									 }
                                    for ( $ap = 1; $ap <= $additional_pickups; $ap++ ) {
                                    ?>
                                    <div class="col-sm-12 form-group">
                                        <label for="additional_pickup_address_<?php echo esc_attr( $ap ); ?>"><?php echo simontaxi_get_additional_pickup_address_title(); ?>-<?php echo esc_attr( $ap ); ?>
                                        <?php if(in_array( $additional_pickup_address_display, array( 'yesrequired' ) ) ) { echo simontaxi_required_field(); } ?>
                                        </label>
										<?php /* ?>
                                        <textarea name="additional_pickup_address[<?php echo esc_attr( $ap ); ?>]" id ="additional_pickup_address_<?php echo esc_attr( $ap ); ?>" class="form-control additional_pickup_address" rows="4" placeholder="<?php esc_html_e( 'Additional Pickup Address', 'simontaxi' ); ?>-<?php echo esc_attr( $ap ); ?>"><?php echo (isset( $additional_pickup_address[ $ap ] ) ) ? $additional_pickup_address[ $ap ] : ''; ?></textarea>
										<?php */ ?>
										<input type="text" name="additional_pickup_address[<?php echo esc_attr( $ap ); ?>]" id ="additional_pickup_address_<?php echo esc_attr( $ap ); ?>" class="form-control additional_pickup_address" placeholder="<?php echo simontaxi_get_additional_pickup_address_title(); ?>-<?php echo esc_attr( $ap ); ?>" value="<?php echo (isset( $additional_pickup_address[ $ap ] ) ) ? $additional_pickup_address[ $ap ] : ''; ?>" onclick="initialize(this.id)">
										<?php
										$additional_address_instructions = simontaxi_get_option('additional_address_instructions');
										if ( ! empty( $additional_address_instructions ) ) { ?>
											
											<small class="additional_address_instructions"><font color="red"><?php echo simontaxi_get_option('additional_address_instructions'); ?></font></small>
										<?php } ?>
										<?php 
										do_action('additional_pickup_dropoff_note3',
											array(
												'booking_step1' => $booking_step1,
												'type' => 'pickup',
												'journey_type' => 'onward',
											)
										); 
										?>
                                    </div>
                                    <?php }
                                } ?>

                                <?php
                                /**
                                 * Let us display additional dropoff addresses based on admin settings and user selection
                                 */
                                if ( $additional_dropoff_address_display != 'no' && $additional_dropoffs > 0 ) {
                                    if ( isset ( $_POST['additional_dropoff_address'] ) ) {
										 $additional_dropoff_address = $_POST['additional_dropoff_address'];
									 } else {
									$additional_dropoff_address = simontaxi_get_session( 'booking_step3', array(), 'additional_dropoff_address' );
									 }
                                    for ( $ap = 1; $ap <= $additional_dropoffs; $ap++ ) {
                                    ?>
                                    <div class="col-sm-12 form-group">
                                        <label for="additional_dropoff_address_<?php echo esc_attr( $ap ); ?>">
										
										<?php echo simontaxi_get_additional_dropoff_address_title(); ?>-<?php echo esc_attr( $ap ); ?>
                                        <?php if(in_array( $additional_dropoff_address_display, array( 'yesrequired' ) ) ) { echo simontaxi_required_field(); } ?>
                                        </label>
										<?php /* ?>
                                        <textarea name="additional_dropoff_address[<?php echo esc_attr( $ap ); ?>]" id ="additional_dropoff_address_<?php echo esc_attr( $ap ); ?>" class="form-control additional_dropoff_address" rows="4" placeholder="<?php esc_html_e( 'Additional Dropoff Address', 'simontaxi' ); ?>-<?php echo esc_attr( $ap ); ?>"><?php echo (isset( $additional_dropoff_address[ $ap ] ) ) ? $additional_dropoff_address[ $ap ] : ''; ?></textarea>
										
										<?php */ ?>
										<input name="additional_dropoff_address[<?php echo esc_attr( $ap ); ?>]" id ="additional_dropoff_address_<?php echo esc_attr( $ap ); ?>" class="form-control additional_dropoff_address" rows="4" placeholder="<?php echo simontaxi_get_additional_dropoff_address_title(); ?>-<?php echo esc_attr( $ap ); ?>" value="<?php echo (isset( $additional_dropoff_address[ $ap ] ) ) ? $additional_dropoff_address[ $ap ] : ''; ?>" onclick="initialize_return(this.id)">
										<?php
										$additional_address_instructions_dropoff = simontaxi_get_option('additional_address_instructions_dropoff');
										if ( ! empty( $additional_address_instructions_dropoff ) ) { ?>
											<small class="additional_address_instructions_dropoff"><font color="red"><?php echo simontaxi_get_option('additional_address_instructions_dropoff'); ?></font></small>
										<?php } ?>
										<?php 
										do_action('additional_pickup_dropoff_note3',
											array(
												'booking_step1' => $booking_step1,
												'type' => 'dropoff',
												'journey_type' => 'onward',
											)
										); 
										?>
                                    </div>
                                    <?php }
                                } ?>

								<?php
                                /**
                                 * Let us display return pickup addresses based on admin settings
                                 */

								 if ( $additional_pickups_return_display != 'no' && $additional_pickups_return > 0 ) {
									 if ( isset ( $_POST['additional_pickup_address_return'] ) ) {
										 $additional_pickup_address_return = $_POST['additional_pickup_address_return'];
									 } else {
									 $additional_pickup_address_return = simontaxi_get_session( 'booking_step3', array(), 'additional_pickup_address_return' );
									 }

									for ( $ap = 1; $ap <= $additional_pickups_return; $ap++ ) {
                                    ?>
                                    <div class="col-sm-12 form-group">
                                        <label for="additional_pickup_address_return_<?php echo esc_attr( $ap ); ?>">
										<?php echo simontaxi_get_additional_pickup_address_title_return(); ?>-<?php echo esc_attr( $ap ); ?>
                                        <?php if(in_array( $additional_pickups_return_display, array( 'yesrequired' ) ) ) { echo simontaxi_required_field(); } ?>
                                        </label>
										<?php /* ?>
                                        <textarea name="additional_pickup_address_return[<?php echo esc_attr( $ap ); ?>]" id ="additional_pickup_address_return_<?php echo esc_attr( $ap ); ?>" class="form-control additional_pickup_address_return" rows="4" placeholder="<?php echo apply_filters( 'additional_pickup_address_title', esc_html__( 'Additional Pickup Address', 'simontaxi' ) ) . esc_html__('(Return)'); ?>-<?php echo esc_attr( $ap ); ?>"><?php echo (isset( $additional_pickup_address_return[ $ap ] ) ) ? $additional_pickup_address_return[ $ap ] : ''; ?></textarea>
										<?php */ ?>
										<input name="additional_pickup_address_return[<?php echo esc_attr( $ap ); ?>]" id ="additional_pickup_address_return_<?php echo esc_attr( $ap ); ?>" class="form-control additional_pickup_address_return" placeholder="<?php echo simontaxi_get_additional_pickup_address_title_return(); ?>-<?php echo esc_attr( $ap ); ?>" value="<?php echo (isset( $additional_pickup_address_return[ $ap ] ) ) ? $additional_pickup_address_return[ $ap ] : ''; ?>" onclick="initialize_return(this.id)">
										
										<?php 
										do_action('additional_pickup_dropoff_note3',
											array(
												'booking_step1' => $booking_step1,
												'type' => 'pickup',
												'journey_type' => 'return',
											)
										); 
										?>
                                    </div>
                                    <?php }
									} ?>

                                <?php
                                /**
                                 * Let us display return dropoff addresses based on admin settings
                                 */
                                 if ( $additional_dropoff_address_return_display != 'no' && $additional_dropoff_address_return > 0 ) {
									 if ( isset ( $_POST['return_dropoff_address'] ) ) {
										 $return_dropoff_address = $_POST['return_dropoff_address'];
									 } else {
									 $return_dropoff_address = simontaxi_get_session( 'booking_step3', array(), 'return_dropoff_address' );
									 }
                                    for ( $ap = 1; $ap <= $additional_dropoff_address_return; $ap++ ) {
                                    ?>
                                    <div class="col-sm-12 form-group">
                                        <label for="return_dropoff_address_<?php echo esc_attr( $ap ); ?>">
										<?php echo simontaxi_get_additional_dropoff_address_title_return(); ?>-<?php echo esc_attr( $ap ); ?>
                                        <?php if(in_array( $additional_dropoff_address_return_display, array( 'yesrequired' ) ) ) { echo simontaxi_required_field(); } ?>
                                        </label>
										<?php /* ?>
                                        <textarea name="return_dropoff_address[<?php echo esc_attr( $ap ); ?>]" id ="return_dropoff_address_<?php echo esc_attr( $ap ); ?>" class="form-control return_dropoff_address" rows="4" placeholder="<?php esc_html_e( 'Additional Dropoff Address', 'simontaxi' ); ?>-<?php echo esc_attr( $ap ); ?>"><?php echo (isset( $return_dropoff_address[ $ap ] ) ) ? $return_dropoff_address[ $ap ] : ''; ?></textarea>
										<?php */ ?>
										<input name="return_dropoff_address[<?php echo esc_attr( $ap ); ?>]" id ="return_dropoff_address_<?php echo esc_attr( $ap ); ?>" class="form-control return_dropoff_address" rows="4" placeholder="<?php echo simontaxi_get_additional_dropoff_address_title_return(); ?>-<?php echo esc_attr( $ap ); ?>" value="<?php echo (isset( $return_dropoff_address[ $ap ] ) ) ? $return_dropoff_address[ $ap ] : ''; ?>" onclick="initialize_return(this.id)">
										<?php 
										do_action('additional_pickup_dropoff_note3',
											array(
												'booking_step1' => $booking_step1,
												'type' => 'dropoff',
												'journey_type' => 'return',
											)
										); 
										?>
                                    </div>
                                    <?php }
									} ?>

                                 <?php
                                /**
                                 * Let us display return dropoff addresses based on admin settings
                                 */
                                 if ( $special_instructions_display != 'no' ) {
                                ?>
                                <div class="col-sm-12 form-group">
                                    <label for="special_instructions"><?php esc_html_e( 'Special instructions if any', 'simontaxi' ); ?><?php if(in_array( $special_instructions_display, array( 'yesrequired' ) ) ) { echo simontaxi_required_field(); } ?></label>
                                    <textarea name="special_instructions" id="special_instructions" class="form-control" rows="3" placeholder="<?php esc_html_e( 'Enter Special instructions if any', 'simontaxi' ); ?>"><?php echo simontaxi_get_value( $booking_step3, 'special_instructions' ); ?></textarea>
                                </div>
                                 <?php } 
								 do_action( 'simontaxi_optional_fields_step3_display' );
								 ?>

                                 <?php if ( simontaxi_terms_page() == 'step3' ) : ?>
                                    <div class="col-sm-12">
                                        <div class="input-group st-top40">
                                            <div>
                                                <input id="terms" type="checkbox" name="terms" value="option">
                                                <label for="terms"><span><span></span></span><i class="st-terms-accept"><?php echo simontaxi_terms_text(); ?></i></label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>


                                <div class="col-sm-12 st-terms-block">

                                    <a href="<?php echo apply_filters( 'step3_back_url', simontaxi_get_bookingsteps_urls( 'step2' ) ); ?>" class="btn-dull"><i class="fa fa-angle-double-left"></i> <?php esc_html_e( 'Back', 'simontaxi' ); ?> </a>
                                    <button type="submit" class="btn btn-primary btn-mobile" name="validatestep3" id="validatestep3"><?php echo apply_filters( 'simontaxi_filter_step3_nextbutton_title', esc_html__( 'Confirm Booking', 'simontaxi' ) ); ?></button>
									<?php do_action('simontaxi_step3_other_buttons'); ?>

                                </div>

                            </form>
                        </div>



                    </div>
					<?php do_action( 'simontaxi_step3_after_form' ); ?>
                </div>
            </div>
            <?php if ( $booking_summany_step3 == 'yes' && $step3_sidebar_position == 'right' && isset( $booking_step1) && ( ! empty( $booking_step1 ) ) ) {
                $template = 'booking/includes/booking-steps/right-side.php';
				if ( simontaxi_is_template_customized( $template ) ) {
					require simontaxi_get_theme_template_dir_name() . $template;
				} else {
					require apply_filters( 'simontaxi_locate_rightside',SIMONTAXI_PLUGIN_PATH . $template );
				}
            } 
			do_action( 'simontaxi_sidebar_right_step3' );
			?>
        </div>
    </div>
</div>
<!-- /Booking Form -->

<script type="text/javascript">
jQuery(document).ready(function ( $ ) {

    jQuery( '#confirm-booking' ).submit(function (event) {
        /**
         * Let us remove all errors to prevent appending more errors to the same element!.
        */
        jQuery( '.error' ).remove();
        var email = jQuery( '#email' ).val();
        var error = 0;
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (email == "") {

            jQuery( '#email' ).after( '<span class="error"> <?php esc_html_e( 'Please enter your email address', 'simontaxi' ); ?> </span>' );
            error++;
        } else if( !re.test(email) ) {
            jQuery( '#email' ).after( '<span class="error"> <?php esc_html_e( 'Please enter valid email address', 'simontaxi' ); ?> </span>' );
            error++;
        }
        <?php
        if ( $name_display == 'fullnamerequired' ) {
            /**
             * Let us validate the full name.
             */
            ?>
            if(jQuery( '#full_name' ).val() == '' ) {
                jQuery( '#full_name .error' ).remove();
                jQuery( '#full_name' ).after( '<span class="error"> <?php esc_html_e( 'Please enter passenger full name', 'simontaxi' ); ?> </span>' );
                error++;
            }
            <?php
        } elseif ( $name_display == 'firstrequiredlastrequired' ) {
            /**
             * Let us validate the first name and last name.
             */
            ?>
            if(jQuery( '#first_name' ).val() == '' ) {
                jQuery( '#first_name' ).after( '<span class="error"> <?php esc_html_e( 'Please enter passenger first name', 'simontaxi' ); ?> </span>' );
                error++;
            }
            if(jQuery( '#last_name' ).val() == '' ) {
                jQuery( '#last_name' ).after( '<span class="error"> <?php esc_html_e( 'Please enter passenger last name', 'simontaxi' ); ?> </span>' );
                error++;
            }
            <?php
        } elseif ( $name_display == 'firstrequiredlastoptional' ) {
            /**
             * Let us validate the first name.
             */
            ?>
            if(jQuery( '#first_name' ).val() == '' ) {
                jQuery( '#first_name' ).after( '<span class="error"> <?php esc_html_e( 'Please enter passenger first name', 'simontaxi' ); ?> </span>' );
                error++;
            }
            <?php
        } elseif ( $name_display == 'firstoptionallastrequired' ) {
            /**
             * Let us validate the last name.
             */
            ?>
            if(jQuery( '#last_name' ).val() == '' ) {
                jQuery( '#last_name' ).after( '<span class="error"> <?php esc_html_e( 'Please enter passenger last name', 'simontaxi' ); ?> </span>' );
                error++;
            }
            <?php
        }

        /**
         * Let us validate 'phone_number' based on admin settings.
         */
         if ( $phone_number != 'no' ) {
             /**
             * Admin enabled the phone number and hence we need to validate!
             */
             if ( in_array( $phone_number, array( 'phonecountryrequired', 'phonerequired' ) ) ) {
                 ?>
                 if(jQuery( '#mobile_countrycode' ).val() == '' ) {
                    jQuery( '#mobile_countrycode' ).after( '<span class="error"> <?php esc_html_e( 'Please select country code', 'simontaxi' ); ?> </span>' );
                    error++;
                }
                if(jQuery( '#mobile' ).val() == '' ) {
                    jQuery( '#mobile' ).after( '<span class="error"> <?php esc_html_e( 'Please enter mobile number', 'simontaxi' ); ?> </span>' );
                    error++;
                }
                 <?php
             }
         }
		 
		 /**
         * Let us validate 'company_name' field based on admin settings
		 *
		 * @since 2.0.2
         */
         if ( $company_name != 'no' ) {
             /**
              * Admin enabled the 'company_name' field hence we need to validate
              */
              if ( $company_name == 'yesrequired' ) {
                 ?>
                 if(jQuery( '#company_name' ).val() == '' ) {
                    jQuery( '#company_name' ).after( '<span class="error"> <?php esc_html_e( 'Please company name', 'simontaxi' ); ?> </span>' );
                    error++;
                }
                 <?php
              }
         }

         /**
         * Let us validate 'no_of_passengers' field based on admin settings
         */
         if ( $no_of_passengers_display != 'no' && $allow_number_of_persons == 'no' ) {
             /**
              * Admin enabled the 'no_of_passengers' field hence we need to validate
              */
              if ( $no_of_passengers_display == 'yesrequired' ) {
                 ?>
                 var numbers = /^[0-9]+$/;
                 if(jQuery( '#no_of_passengers' ).val() == '' ) {
                    jQuery( '#no_of_passengers' ).after( '<span class="error"> <?php esc_html_e( 'Please enter number of passengers', 'simontaxi' ); ?> </span>' );
                    error++;
                } else if( !jQuery( '#no_of_passengers' ).val().match(numbers) ) {
                    jQuery( '#no_of_passengers' ).after( '<span class="error"> <?php esc_html_e( 'Please enter number only for No. of passengers', 'simontaxi' ); ?> </span>' );
                    error++;
                }
                 <?php
              }
         }


         /**
          * Let us validate 'land_mark_pickupaddress' field based on admin settings
          */
         if ( $land_mark_pickupaddress_display != 'no' ) {
             /**
              * Admin enabled the 'land_mark_pickupaddress' field hence we need to validate
              */
              if ( $land_mark_pickupaddress_display == 'yesrequired' ) {
                 ?>
                 if(jQuery( '#land_mark_pickupaddress' ).val() == '' ) {
                    jQuery( '#land_mark_pickupaddress' ).after( '<span class="error"> <?php esc_html_e( 'Please enter Land Mark / Pickup Address', 'simontaxi' ); ?> </span>' );
                    error++;
                }
                 <?php
            }
         }

         /**
          * Let us validate 'additional_pickup_address' field based on admin settings
          */
         if ( $additional_pickup_address_display != 'no' ) {
             /**
              * Admin enabled the 'additional_pickup_address' field hence we need to validate
              */
             if ( $additional_pickup_address_display == 'yesrequired' && $additional_pickups > 0 ) {
                ?>
                if(jQuery( '.additional_pickup_address' ).length  > 0 ) {
                    for( var i = 1; i <= jQuery( '.additional_pickup_address' ).length; i++) {
                        if (jQuery( '#additional_pickup_address_'+i).val() == '' ) {
                            jQuery( '#additional_pickup_address_'+i).after( '<span class="error"> <?php esc_html_e( 'Please enter Additional Pickup Address', 'simontaxi' ); ?> </span>' );
                            error++;
                        }
                    }
                }
                <?php
             }
         }


         /**
          * Let us validate 'additional_dropoff_address' field based on admin settings
          */
         if ( $additional_dropoff_address_display != 'no' && $additional_dropoffs > 0 ) {
             /**
              * Admin enabled the 'additional_dropoff_address' field hence we need to validate
              */
             if ( $additional_dropoff_address_display == 'yesrequired' ) {
                ?>
                if(jQuery( '.additional_dropoff_address' ).length  > 0 ) {
                    for( var i = 1; i <= jQuery( '.additional_dropoff_address' ).length; i++) {
                        if (jQuery( '#additional_dropoff_address_'+i).val() == '' ) {
                            jQuery( '#additional_dropoff_address_'+i).after( '<span class="error"> <?php esc_html_e( 'Please enter Additional Dropoff Address', 'simontaxi' ); ?> </span>' );
                            error++;
                        }
                    }
                }
                <?php
             }
         }

		 /**
          * Let us validate 'additional_pickup_address' field based on admin settings
          */
         if ( $additional_pickups_return_display != 'no' ) {
             /**
              * Admin enabled the 'additional_pickup_address' field hence we need to validate
              */
             if ( $additional_pickups_return_display == 'yesrequired' && $additional_pickups > 0 ) {
                ?>
                if(jQuery( '.additional_pickup_address_return' ).length  > 0 ) {
                    for( var i = 1; i <= jQuery( '.additional_pickup_address_return' ).length; i++) {
                        if (jQuery( '#additional_pickup_address_return_'+i).val() == '' ) {
                            jQuery( '#additional_pickup_address_return_'+i).after( '<span class="error"> <?php esc_html_e( 'Please enter Additional Pickup Address (Return)', 'simontaxi' ); ?> </span>' );
                            error++;
                        }
                    }
                }
                <?php
             }
         }

		 /**
          * Let us validate 'additional_dropoff_address_return' field based on admin settings
          */
         if ( $additional_dropoff_address_return_display != 'no' ) {
             /**
              * Admin enabled the 'additional_dropoff_address_return' field hence we need to validate
              */
             if ( $additional_dropoff_address_return_display == 'yesrequired' && $additional_pickups > 0 ) {
                ?>
                if(jQuery( '.return_dropoff_address' ).length  > 0 ) {
                    for( var i = 1; i <= jQuery( '.return_dropoff_address' ).length; i++) {
                        if (jQuery( '#return_dropoff_address_'+i).val() == '' ) {
                            jQuery( '#return_dropoff_address_'+i).after( '<span class="error"> <?php esc_html_e( 'Please enter Additional Dropoff Address (Return)', 'simontaxi' ); ?> </span>' );
                            error++;
                        }
                    }
                }
                <?php
             }
         }


         /**
          * Let us validate 'special_instructions' field based on admin settings
          */
         if ( $special_instructions_display != 'no' ) {
             /**
              * Admin enabled the 'special_instructions' field hence we need to validate
              */
             if ( $special_instructions_display == 'yesrequired' ) {
                ?>
                if(jQuery( '#special_instructions' ).val() == '' ) {
                    jQuery( '#special_instructions' ).after( '<span class="error"> <?php esc_html_e( 'Please enter Special instructions if any', 'simontaxi' ); ?> </span>' );
                    error++;
                }
                <?php
             }
         }
        ?>

        <?php
        /**
         * Let me validate whether user accepts terms and conditions based on admin settings
         */
        if ( simontaxi_terms_page() == 'step3' ) : ?>
        if ( !document.getElementById( 'terms' ).checked ) {
            jQuery( '#terms' ).closest( '.input-group' ).after( '<span class="error"> <?php esc_html_e( 'You should accept Terms of Service to proceed', 'simontaxi' ); ?></span>' );
            error++;
        }
        <?php endif; ?>

        if (error > 0) {
            console.log(error);
            event.preventDefault();
        }
    });
});
</script>
<?php
$vehicle_country = simontaxi_get_option( 'vehicle_country', 'US' );
/**
 * @since 2.0.0
*/
$vehicle_country_dropoff = simontaxi_get_option( 'vehicle_country_dropoff', 'US' );
$vehicle_places = simontaxi_get_option( 'vehicle_places', 'googleall' );

$google_api = simontaxi_get_option( 'google_api', 'AIzaSyCqRV6HQ_BSw3MMjPen2bT2IwDnZgfjwu4' );
?>
<?php /* ?>
<script src="//maps.googleapis.com/maps/api/js?libraries=places&key=<?php echo $google_api; ?>"></script>
<?php */ ?>
<script>
function initialize( id )
{
	var selected_country = '<?php echo $vehicle_country; ?>';
	
	var options = {
		language: 'en-GB',
		<?php
		if ( $vehicle_places == 'googleregions' ) {
		?>
		types: ['(regions)'],
		<?php }
		if ( $vehicle_places == 'googlecities' ) {
		?>
		types: ['(cities)'],
		<?php } ?>
		componentRestrictions: {
			country: selected_country
		}
	};
	
	var input = jQuery( '#' + id);
    var autocomplete_my = new google.maps.places.Autocomplete(input[0], options);
	
	google.maps.event.addListener(autocomplete_my, 'place_changed', function () {
        place = autocomplete_my.getPlace();

        if (place.address_components) {
            stateID = place.address_components[0] && place.address_components[0].long_name || '';
            countryID = place.address_components[3] && place.address_components[3].short_name || '';
        }
        if ( place.name ) {
			stateID = place.name;
		} else {
			stateID = place.formatted_address;
		}
        input.blur();
        input.val(stateID);
    });
}

function initialize_return( id )
{
	var selected_country = '<?php echo $vehicle_country_dropoff; ?>';
	
	var options = {
		language: 'en-GB',
		<?php
		if ( $vehicle_places == 'googleregions' ) {
		?>
		types: ['(regions)'],
		<?php }
		if ( $vehicle_places == 'googlecities' ) {
		?>
		types: ['(cities)'],
		<?php } ?>
		componentRestrictions: {
			country: selected_country
		}
	};
	
	var input = jQuery( '#' + id);
    var autocomplete_my = new google.maps.places.Autocomplete(input[0], options);
	
	google.maps.event.addListener(autocomplete_my, 'place_changed', function () {
        place = autocomplete_my.getPlace();
		
        if (place.address_components) {
            stateID = place.address_components[0] && place.address_components[0].long_name || '';
            countryID = place.address_components[3] && place.address_components[3].short_name || '';
        }
        if ( place.name ) {
			stateID = place.name;
		} else {
			stateID = place.formatted_address;
		}
        input.blur();
        input.val(stateID);
    });
}
</script>