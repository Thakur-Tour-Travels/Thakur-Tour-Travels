<?php

/**
 * @global wpdb  $wpdb  WordPress database abstraction object.
 */
global $wpdb;

$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
$booking_step2 = simontaxi_get_session( 'booking_step2', array() );
$booking_step3 = simontaxi_get_session( 'booking_step3', array() );

$name_display = simontaxi_get_option( 'name_display', 'fullnameoptional' );
$phone_number = simontaxi_get_option( 'phone_number', 'no' );
/**
 * @since 2.0.2
 */
$company_name_display = simontaxi_get_option( 'company_name', 'no' );
$no_of_passengers_display = simontaxi_get_option( 'no_of_passengers', 'yesoptional' );

/**
 * @since 2.0.6
 */
$allow_number_of_persons = simontaxi_get_option( 'allow_number_of_persons', 'no' );

$land_mark_pickupaddress_display = simontaxi_get_option( 'land_mark_pickupaddress', 'no' );
$additional_pickup_address_display = simontaxi_get_option( 'additional_pickup_address', 'no' );

$additional_dropoff_address_display = simontaxi_get_option( 'additional_dropoff_address', 'no' );
$additional_dropoffs = isset( $booking_step1['additional_dropoff'] ) ? $booking_step1['additional_dropoff'] : 0;

$additional_pickups_return_display = simontaxi_get_option( 'additional_pickups_return', 'no' );
$additional_pickups_return = isset( $booking_step1['additional_pickups_return'] ) ? $booking_step1['additional_pickups_return'] : 0;

$additional_pickups = isset( $booking_step1['additional_pickups'] ) ? $booking_step1['additional_pickups'] : 0;

$additional_dropoff_address_return_display = simontaxi_get_option( 'additional_dropoff_address_return', 'no' );
$additional_dropoff_address_return = isset( $booking_step1['additional_dropoff_return'] ) ? $booking_step1['additional_dropoff_return'] : 0;

$special_instructions_display = simontaxi_get_option( 'special_instructions', 'no' );

if ( empty( $booking_step1) || empty( $booking_step2 ) ) {
	/**
	 * @since 2.0.8
	 */
	$template = '/booking/includes/pages/redirection-message.php';
	if ( simontaxi_is_template_customized( $template ) ) {
		require simontaxi_get_theme_template_dir_name() . $template;
	} else {
		require SIMONTAXI_PLUGIN_PATH . $template;
	}
    die();
}

$additional_data = array(
	'current_step' => 'step3',
	);
simontaxi_set_session( 'booking_step1', $additional_data );
$booking_step1 = simontaxi_get_session( 'booking_step1', array() );

if ( isset( $_POST['validatestep3'] ) ) {
	do_action( 'simontaxi_booking_step3_start_before' );
	
    $email = $_POST['email'];
    if ( empty( $email) ) {
        simontaxi_set_error( 'email', esc_html__( 'Please enter email address', 'simontaxi' ) );
    } elseif( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
        simontaxi_set_error( 'email', esc_html__( 'Please enter valid email address', 'simontaxi' ) );
    }
    /**
     * Let us validate the passenger name based on admin settings. Various options for this are
     * 'fullnamerequired', 'firstrequiredlastrequired', 'firstrequiredlastoptional', 'firstoptionallastrequired'
     *
     * We are giving full flexibility to admin in choosing options!
     */
    if ( $name_display == 'fullnamerequired' ) {
        /**
         * Let us validate the full name.
         */
        $full_name = $_POST['full_name'];
        if ( empty( $full_name ) ) {
            simontaxi_set_error( 'full_name', esc_html__( 'Please enter passenger full name', 'simontaxi' ) );
        } elseif ( strlen( $full_name ) < 3 ) {
            simontaxi_set_error( 'full_name', esc_html__( 'Please enter valid name. It should be greater than 2 characters', 'simontaxi' ) );
        }
    } elseif ( $name_display == 'firstrequiredlastrequired' ) {
        /**
         * Let us validate the first name and last name.
         */
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        if ( empty( $first_name ) ) {
            simontaxi_set_error( 'first_name', esc_html__( 'Please enter first name', 'simontaxi' ) );
        }
        if ( empty( $last_name ) ) {
            simontaxi_set_error( 'last_name', esc_html__( 'Please enter last name', 'simontaxi' ) );
        }
    } elseif ( $name_display == 'firstrequiredlastoptional' ) {
        $first_name = $_POST['first_name'];
        if ( empty( $first_name ) ) {
            simontaxi_set_error( 'first_name', esc_html__( 'Please enter first name', 'simontaxi' ) );
        }
    } elseif ( $name_display == 'firstoptionallastrequired' ) {
        $last_name = $_POST['last_name'];
        if ( empty( $last_name ) ) {
            simontaxi_set_error( 'last_name', esc_html__( 'Please enter last name', 'simontaxi' ) );
        }
    }

    /**
     * Let us validate 'phone_number' based on admin settings.
    */
    if ( $phone_number != 'no' ) {
        /**
         * Admin enabled the phone number and hence we need to validate!
         */
         if ( in_array( $phone_number, array( 'phonecountryrequired', 'phonerequired' ) ) ) {
            $mobile_countrycode = $_POST['mobile_countrycode'];
            $mobile = $_POST['mobile'];
            if ( empty( $mobile_countrycode ) ) {
                simontaxi_set_error( 'mobile_countrycode', esc_html__( 'Please select country code', 'simontaxi' ) );
            }
            if ( empty( $mobile ) ) {
                simontaxi_set_error( 'mobile', esc_html__( 'Please enter mobile number', 'simontaxi' ) );
            }
         }
    }
	
	/**
	* Let us validate 'company_name' field based on admin settings
	*/
	if ( $company_name_display != 'no' ) {
	 /**
	  * Admin enabled the 'company_name' field hence we need to validate
	  *
	  * @since 2.0.2
	  */
	 if ( $company_name_display == 'yesrequired' ) {
		 $company_name = $_POST['company_name'];
		  if ( empty( $company_name ) ) {
			  simontaxi_set_error( 'company_name', esc_html__( 'Please enter Company name', 'simontaxi' ) );
		  }
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
              $no_of_passengers = $_POST['no_of_passengers'];
              if ( empty( $no_of_passengers ) ) {
                 simontaxi_set_error( 'no_of_passengers', esc_html__( 'Please enter number of passengers', 'simontaxi' ) );
              } elseif ( ! preg_match( '/^\d+$/', $no_of_passengers ) ) {
                  simontaxi_set_error( 'no_of_passengers', esc_html__( 'Please enter number only for No. of passengers', 'simontaxi' ) );
              }
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
              $land_mark_pickupaddress = $_POST['land_mark_pickupaddress'];
              if ( empty( $land_mark_pickupaddress ) ) {
                  simontaxi_set_error( 'land_mark_pickupaddress', esc_html__( 'Please enter Land Mark / Pickup Address', 'simontaxi' ) );
              }
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
             $additional_pickup_address = $_POST['additional_pickup_address'];
              if ( empty( $additional_pickup_address ) ) {
                  simontaxi_set_error( 'additional_pickup_address', esc_html__( 'Please enter ', 'simontaxi' ) . simontaxi_get_additional_pickup_address_title() );
              }
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
             $additional_dropoff_address = $_POST['additional_dropoff_address'];
              if ( empty( $additional_dropoff_address ) ) {
                  simontaxi_set_error( 'additional_dropoff_address', esc_html__( 'Please enter Additional Dropoff Address', 'simontaxi' ) );
				  simontaxi_set_error( 'additional_pickup_address', esc_html__( 'Please enter ', 'simontaxi' ) . simontaxi_get_additional_dropoff_address_title() );
              }
         }
     }

	 /**
      * Let us validate 'additional_pickup_address_return' field based on admin settings
      */
     if ( $additional_pickups_return_display != 'no' && $additional_pickups_return > 0 ) {
         /**
          * Admin enabled the 'additional_pickup_address_return' field hence we need to validate
          */
         if ( $additional_pickups_return_display == 'yesrequired' ) {
             $additional_pickup_address_return = $_POST['additional_pickup_address_return'];
              if ( empty( $additional_pickup_address_return ) ) {
                  simontaxi_set_error( 'additional_pickup_address_return', esc_html__( 'Please enter ', 'simontaxi' ) . simontaxi_get_additional_pickup_address_title_return() );
              }
         }
     }

     /**
      * Let us validate 'return_dropoff_address' field based on admin settings
      */
     if ( $additional_dropoff_address_return_display != 'no' && $additional_dropoff_address_return > 0 ) {
         /**
          * Admin enabled the 'return_dropoff_address' field hence we need to validate
          */
         if ( $additional_dropoff_address_return_display == 'yesrequired' ) {
             $return_dropoff_address = $_POST['return_dropoff_address'];
              if ( empty( $return_dropoff_address ) ) {
                  simontaxi_set_error( 'return_dropoff_address', esc_html__( 'Please enter ', 'simontaxi' ) . simontaxi_get_additional_dropoff_address_title_return() );
              }
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
             $special_instructions = $_POST['special_instructions'];
              if ( empty( $special_instructions ) ) {
                  simontaxi_set_error( 'special_instructions', esc_html__( 'Please enter Special instructions if any', 'simontaxi' ) );
              }
         }
     }
	 
	 do_action( 'simontaxi_validate_additional_fields', $_POST );

     if ( simontaxi_terms_page() == 'step3' && ! isset( $_POST['terms'] ) ) {
        simontaxi_set_error( 'terms', esc_html__( 'You should accept Terms of Service to proceed', 'simontaxi' ) );
    }
	// $errors = simontaxi_get_errors();
	$errors = apply_filters( 'simontaxi_flt_step3_errors', simontaxi_get_errors() );
	
     if ( empty( $errors ) ) {
         do_action( 'simontaxi_booking_step3_noerrors_before' );
		 /**
          * Everyting is fine and we can go furhter
          */

          /**
           * Let me take all the available data into session to use it later.
          */
		  simontaxi_set_session( 'booking_step3', $_POST );
		  $db_ref = simontaxi_get_session( 'booking_step1', 0, 'db_ref' );
          if( $db_ref > 0 ) {
              $booking_contacts = array( 'email' => $_POST['email'] );
			  if ( in_array( $name_display, array( 'fullnameoptional', 'fullnamerequired' ) ) ) {
				  $booking_contacts['full_name'] = $_POST['full_name'];
			  } elseif ( in_array( $name_display, array( 'firstoptionallastoptional', 'firstrequiredlastrequired', 'firstrequiredlastoptional', 'firstoptionallastrequired' ) ) ) {
				  $booking_contacts['first_name'] = isset( $_POST['first_name'] ) ? $_POST['first_name'] : '';
                  $booking_contacts['last_name'] = isset( $_POST['last_name'] ) ? $_POST['last_name'] : '';
			  }

              if( $phone_number != 'no' ) {
                  if ( ! empty( $_POST['mobile_countrycode'] ) ) {
					$booking_contacts['mobile_countrycode'] = $_POST['mobile_countrycode'];
				  }
				  if ( ! empty( $_POST['mobile'] ) ) {
					$booking_contacts['mobile'] = $_POST['mobile'];
				  }
              }
			  
			  if ( ! empty( $company_name ) && $company_name != 'no' ) {
                  if ( ! empty( $_POST['company_name'] ) ) {
					$booking_contacts['company_name'] = $_POST['company_name'];
				  }
              }
			  
              if ( $no_of_passengers_display != 'no' && $allow_number_of_persons == 'no' ) {
                  $booking_contacts['no_of_passengers'] = $_POST['no_of_passengers'];
              }

              if ( $land_mark_pickupaddress_display != 'no' ) {
                  if ( ! empty( $_POST['land_mark_pickupaddress'] ) ) {
					$booking_contacts['land_mark_pickupaddress'] = $_POST['land_mark_pickupaddress'];
				  }
              }

              if ( $additional_pickup_address_display != 'no' && $additional_pickups > 0 ) {
                  if ( ! empty( $_POST['additional_pickup_address'] ) ) {
					$booking_contacts['additional_pickup_address'] = json_encode( $_POST['additional_pickup_address'] );
				  }
              }

              if ( $additional_dropoff_address_display != 'no' && $additional_dropoffs > 0 ) {
                  if ( ! empty( $_POST['additional_dropoff_address'] ) ) {
					$booking_contacts['additional_dropoff_address'] = json_encode( $_POST['additional_dropoff_address'] );
				  }
              }

			  if ( $additional_pickups_return_display != 'no' && $additional_pickups_return > 0) {
                  if ( ! empty( $_POST['additional_pickup_address_return'] ) ) {
					$booking_contacts['return_pickup_address'] = json_encode( $_POST['additional_pickup_address_return'] );
				  }
              }

              if ( $additional_dropoff_address_return_display != 'no' && $additional_dropoff_address_return > 0) {
                  if ( ! empty( $_POST['return_dropoff_address'] ) ) {
					$booking_contacts['return_dropoff_address'] = json_encode( $_POST['return_dropoff_address'] );
				  }
              }

              if ( $special_instructions_display != 'no' ) {
                  if ( ! empty( $_POST['special_instructions'] ) ) {
					$booking_contacts['special_instructions'] = $_POST['special_instructions'];
				  }
              }

              $data = array( 'booking_contacts' => json_encode( apply_filters( 'simontaxi_additional_booking_contacts', $booking_contacts ) ),
				'user_email' => $_POST['email'],
			  );
              // $data['session_details'] = json_encode(array(simontaxi_get_session( 'booking_step1' ), simontaxi_get_session( 'booking_step2' ), simontaxi_get_session( 'booking_step3' ) ) );
			  
			  $data['session_details'] = json_encode(
					apply_filters( 'simontaxi_session_details', array(
						'step1' => simontaxi_get_session( 'booking_step1' ),
						'step2' => simontaxi_get_session( 'booking_step2' ),
						'step3' => simontaxi_get_session( 'booking_step3' ),
						)
					)
				);
				
				if ( ! empty( $_POST['user_creation'] ) && 'yes' == $_POST['user_creation'] && ! is_user_logged_in() ) {
					$user_data_login = get_user_by( 'login', $_POST['email'] );
					if ( empty ( $user_data_login ) ) {
						$user_data_login = get_user_by( 'email', $_POST['email'] );
					}
					
					if ( empty( $user_data_login ) ) {						
						$user_id = simontaxi_do_registration( $_POST );
					} else {
						$user_id = $user_data_login->ID;
					}
					$data['user_id'] = $user_id;
				}
			  
			  $data = apply_filters( 'simontaxi_step3_data', $data );
			  
              $wpdb->update( $wpdb->prefix .'st_bookings',  $data, array( 'ID'=>$db_ref) );
			  if ( 'quotation' == $_POST['validatestep3'] ) {
				// $redirect_to = simontaxi_get_bookingsteps_urls( 'quotation' );
				// wp_safe_redirect( $redirect_to );
				do_action( 'simontaxi_quotation', $_POST );
			  } else {
				do_action( 'simontaxi_booking_step3_noerrors_after' );
				$redirect_to = simontaxi_get_bookingsteps_urls( 'step4' );
				/**
				 * @since 2.0.9
				 */
				$redirect_to = apply_filters( 'simontaxi_flt_booking_step3_noerrors_after', $redirect_to );
				wp_safe_redirect( $redirect_to );
			  }
          } else {
              /**
               * Let us insert it now. Its good to insert it now.
               */
               if ( ! empty( $booking_step1 ) || ! empty( $booking_step2 ) ) {
               $data_get = simontaxi_get_session( 'booking_step1' );
			   
			   if ( isset( $data_get['db_ref'] ) ) {
					unset( $data_get['db_ref'] );	
				}
				if ( isset( $data_get['pickup_location_new'] ) ) {
					unset( $data_get['pickup_location_new'] );
				}
				if ( isset( $data_get['drop_location_new'] ) ) {
					unset( $data_get['drop_location_new'] );
				}
				if ( isset( $data_get['current_step'] ) ) {
					unset( $data_get['current_step'] );
				}
				if ( isset( $data_get['validtestep1'] ) ) {
					unset( $data_get['validtestep1'] );
				}
				
				$data_get = apply_filters( 'simontaxi_set_unset_post_fields', $data_get );
				
			   $wpdb->insert( $wpdb->prefix . 'st_bookings', $data_get );
			   simontaxi_set_session( 'booking_step1', array( 'db_ref' => $wpdb->insert_id ) );
               
               } else {
                   do_action( 'simontaxi_booking_step3_noerrors_after' );
				   $redirect_to = simontaxi_get_bookingsteps_urls( 'step4' );
				   /**
					 * @since 2.0.9
					 */
					$redirect_to = apply_filters( 'simontaxi_booking_step3_noerrors_after', $redirect_to );
                   wp_safe_redirect( $redirect_to );
               }
          }
     }
}
