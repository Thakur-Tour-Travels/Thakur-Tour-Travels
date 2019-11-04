<?php
/**
 * Display the page to select journey information (page is for the slug 'pick-locations' )
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  Booking step1 page
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$booking_summany_step1 = simontaxi_get_option( 'booking_summany_step1', 'yes' );
$step1_sidebar_position = simontaxi_get_option( 'step1_sidebar_position', 'right' );
$booking_type_tabs_position = simontaxi_get_option( 'booking_type_tabs_position', 'outside' );
$default_breadcrumb_display_step1 = simontaxi_get_option( 'default_breadcrumb_display_step1', 'yes' );
$cols = $columns;
if ( $booking_summany_step1 == 'no' ) {
    $cols = 12;
}

/**
 * Let us apply the classes based on placemet. We are using this page in different places. Based on place we need to change layout ie. class to fit the content in particular place.
 * Defaults to 'homepageleft'
*/
if ( $pre_class != '' ) {
		$class = $pre_class;
	} else {
$class = 'col-lg-8 st-right-col st-equal-col col-md-8';
	}
$class_div = 'st-section st-right-section-form';
$tabfills = 'nav nav-pills st-booking-pills nav-justified st-home-booking-homeleft';
$tab_content = 'tab-content st-home-tab-content';

$breadcrumb = simontaxi_get_bread_crumb();

if ( $placement == 'hometop' ) 
{
    if ( $pre_class != '' ) {
		$class = $pre_class;
	} else {
		$booking_step1_home_width = simontaxi_get_option( 'booking_step1_home_width', 6 );
		$offset = 3;
		switch( $booking_step1_home_width ) {
			case '12':
				$offset = 0;
			break;
			case '10':
				$offset = 1;
			break;
			case '8':
				$offset = 2;
			break;
			case '6':
				$offset = 3;
			break;
			case '4':
				$offset = 4;
			break;
		}

		$class = 'col-lg-'.$booking_step1_home_width.' col-lg-offset-'.$offset.' col-md-12 form-compact';
	}
    $class_div = '';
    $tabfills = 'nav nav-pills st-booking-pills nav-justified st-home-booking-hometop';
    $tab_content = 'tab-content st-home-tab-content';
} elseif ( $placement == 'anywhere' ) {
	if ( $pre_class != '' ) {
		$class = $pre_class;
	} else {
		$class = 'col-lg-12 col-md-12';
	}
    $class_div = '';
    $tabfills = 'nav nav-tabs st-nav-tabs st-anywhere-booking nav-justified';
    $tab_content = 'tab-content st-anywhere-tab-content';
}
?>
<!-- Booking Form -->
<?php if ( $placement == 'fullpage' ) {
    if ( $pre_class != '' ) {
		$class = $pre_class;
	} else {
		$class = 'col-lg-' . $cols . ' col-md-8 col-sm-12';
	}
    $class_div = 'st-booking-block st-mtabs';
    $tabfills = 'nav nav-pills st-booking-pills nav-justified st-home-booking-fullpage';
    $tab_content = 'tab-content';
    ?>
<div class="st-section-sm st-grey-bg">
    <div class="container">
        <div class="row bang">		
		<?php if ( $booking_summany_step1 == 'yes' && $step1_sidebar_position == 'left' && isset( $booking_step1) && ( ! empty( $booking_step1 ) ) ) {
			$template = 'booking/includes/booking-steps/right-side.php';
			if ( simontaxi_is_template_customized( $template ) ) {
				require simontaxi_get_theme_template_dir_name() . $template;
			} else {
				require apply_filters( 'simontaxi_locate_rightside', SIMONTAXI_PLUGIN_PATH . $template );
			}				
		} 
		do_action( 'simontaxi_sidebar_left_step1' );
		?>
<?php } ?>
            <div class="<?php echo esc_attr( $class ); ?>">

				<?php if ( $placement == 'hometop' ) { 
				$title = simontaxi_get_option( 'booking_step1_title_home', '' );
				if ( ! empty( $title ) ) {
				?>
				<h3 class="st-gheading">
				<?php
				echo $title; ?></h3>
				<?php } } ?>

				<?php if ( $placement != 'hometop' ) { ?>
                <div class="<?php echo esc_attr( $class_div); ?>">
                <?php } ?>
				<?php
				if ( 'outside' === $booking_type_tabs_position ) {
					$template = 'booking/includes/booking-steps/booking-tabs.php';
					
					if ( simontaxi_is_template_customized( $template ) ) {
						require simontaxi_get_theme_template_dir_name() . $template;
					} else {
						require apply_filters( 'simontaxi_locate_booking_tabs', SIMONTAXI_PLUGIN_PATH . $template );
					}
				}
				?>
				<div class="simon-form-layout">

				<?php do_action( 'simontaxi_additional_info_before_pills' ); ?>
					
				<?php
				if ( 'inside' === $booking_type_tabs_position ) {
					$template = 'booking/includes/booking-steps/booking-tabs.php';
					
					if ( simontaxi_is_template_customized( $template ) ) {
						require simontaxi_get_theme_template_dir_name() . $template;
					} else {
						require apply_filters( 'simontaxi_locate_booking_tabs', SIMONTAXI_PLUGIN_PATH . $template );
					}
				}
				
				?>

				<?php /* if ( $placement != 'hometop' ) { ?>
                <div class="<?php echo esc_attr( $class_div); ?>">
                <?php } */ ?>

					<?php echo simontaxi_print_errors() ?>

                    <!-- Booking Progress -->
                    <?php
					if ( 'yes' === $default_breadcrumb_display_step1 && $placement == 'fullpage' ) {
                        echo $breadcrumb;
                    }
					?>
                    <!-- end Booking Progress -->

                    <div class="<?php echo esc_attr( $tab_content); ?>">

                        <!-- TAB-1 -->
                        <div id="st-p2p" class="tab-pane fade <?php if ( $p2p_active == 'active' ) { echo 'in active'; }?>">
                            <?php
							$template = 'booking/includes/booking-steps/step1-p2p.php';
							
							if ( simontaxi_is_template_customized( $template ) ) {
								require simontaxi_get_theme_template_dir_name() .  $template;
							} else {
								require apply_filters( 'simontaxi_locate_step1_p2p', SIMONTAXI_PLUGIN_PATH . $template );
							}
							?>
                        </div>

                        <!-- AIR PORT Transfer-->
                        <div id="st-airport" class="tab-pane fade <?php if ( $airport_active == 'active' ) { echo 'in active'; }?>">
							<?php							
							$template = 'booking/includes/booking-steps/step1-airport.php';
							if ( simontaxi_is_template_customized( $template ) ) {
								require simontaxi_get_theme_template_dir_name() .  $template;
							} else {
								require apply_filters( 'simontaxi_locate_step1_airport', SIMONTAXI_PLUGIN_PATH . $template );
							}
							?>
                            
                        </div>
                        <!-- Hourly Rental -->
                        <div id="st-hourly" class="tab-pane fade <?php if ( $hourly_active == 'active' ) { echo 'in active'; }?>">
							<?php
							$template = 'booking/includes/booking-steps/step1-hourly.php';
							
							if ( simontaxi_is_template_customized( $template ) ) {
								require simontaxi_get_theme_template_dir_name() .  $template;
							} else {
								require apply_filters( 'simontaxi_locate_step1_hourly', SIMONTAXI_PLUGIN_PATH . $template );
							}
							?>
                            
                        </div>
						<?php
						echo do_action('simontaxi_addtional_booking_tabs_content', $booking_step1 ); ?>
                    </div>
                <?php if ( $placement != 'hometop' ) { ?>
                </div>
                <?php } ?>
            </div>
            </div>
			<?php if ( $placement == 'fullpage' ) { ?>
            <?php if ( $booking_summany_step1 == 'yes' && $step1_sidebar_position == 'right' && isset( $booking_step1) && ( ! empty( $booking_step1['journey_type'] ) ) ) {
                $template = 'booking/includes/booking-steps/right-side.php';
				
				if ( simontaxi_is_template_customized( $template ) ) {
					require simontaxi_get_theme_template_dir_name() . $template;
				} else {
					require apply_filters( 'simontaxi_locate_rightside', SIMONTAXI_PLUGIN_PATH . $template );
				}			
            } 
			do_action( 'simontaxi_sidebar_right_step1' );
			?>
        </div>
    </div>
</div>
            <?php } ?>
<!-- /Booking Form -->
<?php
$template = 'booking/includes/booking-steps/step1-scripts.php';
if ( simontaxi_is_template_customized( $template ) ) {
	require simontaxi_get_theme_template_dir_name() . $template;
} else {
	require apply_filters( 'simontaxi_locate_step1_scripts', SIMONTAXI_PLUGIN_PATH . $template );
}
?>
<?php do_action('simontaxi_addtional_step1_scripts', $booking_step1 ); ?>