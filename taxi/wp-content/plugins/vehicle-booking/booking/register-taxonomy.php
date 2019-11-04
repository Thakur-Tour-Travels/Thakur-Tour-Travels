<?php
/**
 * Plugin taxonomy
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  taxonomy
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require SIMONTAXI_PLUGIN_PATH . '/booking/taxonomies/taxonomy-vehicle_features.php';
simontaxi_vehicle_features_taxonomy();

require SIMONTAXI_PLUGIN_PATH . '/booking/taxonomies/taxonomy-vehicle_types.php';
simontaxi_vehicle_types_taxonomy();

require SIMONTAXI_PLUGIN_PATH . '/booking/taxonomies/taxonomy-vehicle_locations.php';
simontaxi_vehicle_locations_taxonomy();

require SIMONTAXI_PLUGIN_PATH . '/booking/taxonomies/taxonomy-hourly_package.php';
simontaxi_hourly_packages_taxonomy();

require SIMONTAXI_PLUGIN_PATH . '/booking/taxonomies/taxonomy-blockout_date.php';
simontaxi_blockout_date_taxonomy();

require SIMONTAXI_PLUGIN_PATH . '/booking/taxonomies/taxonomy-peak_season.php';
simontaxi_peak_season_taxonomy();

require SIMONTAXI_PLUGIN_PATH . '/booking/taxonomies/taxonomy-distance_price.php';
simontaxi_distance_price_taxonomy();

require SIMONTAXI_PLUGIN_PATH . '/booking/taxonomies/taxonomy-coupon_code.php';
simontaxi_coupon_code_taxonomy();
