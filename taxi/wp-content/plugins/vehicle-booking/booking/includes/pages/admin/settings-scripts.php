<?php if ( 'yes' === $enable_admin_maps ) { ?>

<?php $google_api = simontaxi_get_option( 'google_api', 'AIzaSyCqRV6HQ_BSw3MMjPen2bT2IwDnZgfjwu4' ); ?>
<script src="//maps.googleapis.com/maps/api/js?libraries=places,drawing&key=<?php echo $google_api; ?>"></script>

<script>
var drawingManager = new google.maps.drawing.DrawingManager();
var drawingManager2 = new google.maps.drawing.DrawingManager();
google.maps.event.addDomListener(window, 'load', google_map);
google.maps.event.addDomListener(window, 'load', google_map2);
var active_tab = 'st-<?php echo esc_attr( $tab ); ?>';
var ajaxUrl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
var message = '<?php esc_html_e( 'Success! Settings are updated !', 'simontaxi' ); ?>';
// move_tab( 'st-<?php echo esc_attr( $tab ); ?>' );
function onchangeField( id ) {
	
	if ( jQuery( '#' + id ).val() == 'predefined' ) {
		jQuery('#'+id+'_display').show();
	} else {
		jQuery('#'+id+'_display').hide();
	}
}
</script>

<?php
$vehicle_country = simontaxi_get_option( 'vehicle_country', 'US' );
$vehicle_country_region_from = simontaxi_get_option( 'vehicle_country_region_from', '' );
$vehicle_country_region_to = simontaxi_get_option( 'vehicle_country_region_to', '' );

$vehicle_country_dropoff = simontaxi_get_option( 'vehicle_country_dropoff', 'US' );
$vehicle_places = simontaxi_get_option( 'vehicle_places', 'googleall' );

$vehicle_places = simontaxi_get_option( 'vehicle_places', 'googleall' );
$vehicle_country_dropoff_region_from = simontaxi_get_option( 'vehicle_country_dropoff_region_from', '' );
$vehicle_country_dropoff_region_to = simontaxi_get_option( 'vehicle_country_dropoff_region_to', '' );
$regionBounds_set = false;
/*
$google_api = simontaxi_get_option( 'google_api', 'AIzaSyCqRV6HQ_BSw3MMjPen2bT2IwDnZgfjwu4' );
?>
<script src="//maps.googleapis.com/maps/api/js?libraries=places&key=<?php echo $google_api; ?>"></script>
<?php */ ?>
<script>
function initialize(id) {
	var input = jQuery( '#' + id);

	var regionBounds_set = false;

	if ( id == 'drop_location' ) {
		var selected_country = '<?php echo $vehicle_country_dropoff; ?>';
		<?php if ( '' !== $vehicle_country_dropoff_region_from && '' !== $vehicle_country_dropoff_region_to ) : ?>
		<?php
		$vehicle_country_dropoff_region_from_parts = explode( ',', $vehicle_country_dropoff_region_from );
		$vehicle_country_dropoff_region_to_parts = explode( ',', $vehicle_country_dropoff_region_to );
		?>

		<?php if ( ! empty( $vehicle_country_dropoff_region_from_parts[1] ) && ! empty( $vehicle_country_dropoff_region_to_parts[1] ) && ! empty( $vehicle_country_dropoff_region_from_parts[0] ) && ! empty( $vehicle_country_dropoff_region_to_parts[0] ) ) { ?>
			var regionBounds = new google.maps.LatLngBounds(
			new google.maps.LatLng(<?php echo $vehicle_country_dropoff_region_from_parts[1]; ?>,<?php echo $vehicle_country_dropoff_region_to_parts[1]; ?>),
			new google.maps.LatLng(<?php echo $vehicle_country_dropoff_region_from_parts[0]; ?>,<?php echo $vehicle_country_dropoff_region_to_parts[0]; ?>) );

			regionBounds_set = true;
		<?php
		$regionBounds_set = true;
		} ?>
		<?php endif; ?>

	} else {
		var selected_country = '<?php echo $vehicle_country; ?>';
		<?php if ( '' !== $vehicle_country_region_from && '' !== $vehicle_country_region_to ) : ?>
		<?php
		$vehicle_country_region_from_parts = explode( ',', $vehicle_country_region_from );
		$vehicle_country_region_to_parts = explode( ',', $vehicle_country_region_to );
		?>
		<?php if ( ! empty( $vehicle_country_region_from_parts[1] ) && ! empty( $vehicle_country_region_to_parts[1] ) && ! empty( $vehicle_country_region_from_parts[0] ) && ! empty( $vehicle_country_region_to_parts[0] ) ) { ?>

		var regionBounds = new google.maps.LatLngBounds(
		new google.maps.LatLng(<?php echo (float) $vehicle_country_region_from_parts[1]; ?>,<?php echo (float) $vehicle_country_region_to_parts[1]; ?>),
		new google.maps.LatLng(<?php echo (float) $vehicle_country_region_from_parts[0]; ?>,<?php echo (float) $vehicle_country_region_to_parts[0]; ?>) );

		regionBounds_set = true;

		<?php
		$regionBounds_set = true;
		} ?>

		<?php endif; ?>
	}

	var options = {				
		<?php
		/**
		* If the admin impose restriction on places, then we are taking only regions (Important places). Reference : https://developers.google.com/places/supported_types
		* Regions: locality (Name)
			sublocality
			postal_code
			country
			administrative_area_level_1 (State)
			administrative_area_level_2 (District)
		* Cities: locality
			administrative_area_level_3
		*/
		if ( $vehicle_places == 'googleregions' ) {
		?>
		types: ['(regions)'],
		<?php }
		if ( $vehicle_places == 'googlecities' ) {
		?>
		types: ['(cities)'],
		<?php }
		?>
		<?php
		/**
		 * We have received many requests to restrict the region to book, so here is solution!
		 */
		if ( 'predefined' !== $vehicle_places && $regionBounds_set ) { ?>
		bounds: regionBounds,
		strictBounds: true,
		<?php } ?>
		<?php if ( ! empty( $vehicle_country_dropoff ) || ! empty( $vehicle_country ) ) { ?>
		componentRestrictions: {
			country: selected_country,
		},
		<?php } ?>
		language: 'en-GB'
	};
	var autocomplete_my = new google.maps.places.Autocomplete(input[0], options);

	google.maps.event.addListener(autocomplete_my, 'place_changed', function () {
		place = autocomplete_my.getPlace();
		// console.log( place );
		jQuery( '#' + id + '_lat' ).val(place.geometry.location.lat() );
		jQuery( '#' + id + '_lng' ).val(place.geometry.location.lng() );

		if (place.address_components) {
			stateID = place.address_components[0] && place.address_components[0].long_name || '';
		}
		if ( place.name ) {
			stateID = place.name;
		} else {
			stateID = place.formatted_address;
		}
		stateID = place.formatted_address;
		input.blur();
		input.val(stateID);
	});
}
</script>
<?php } ?>