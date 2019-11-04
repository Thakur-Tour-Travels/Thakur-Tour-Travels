<?php
if ( ! wp_script_is( 'simontaxi-googleapis' ) ) {
	wp_enqueue_script( 'simontaxi-googleapis' );
}
if ( ! wp_script_is( 'simontaxi-gmap3' ) ) {
	wp_enqueue_script( 'simontaxi-gmap3' );
}
?>

<script type="text/javascript">
function toggle_show(type) {
    var type = jQuery( 'input[name="journey_type"]:checked' ).val();
    if (type == 'two_way' ) {
    jQuery( '#showvalue' ).show();
    }
    else {
    jQuery( '#showvalue' ).hide();
    }
}
toggle_show();
</script>

<script type="text/javascript">
jQuery(document).ready(function ( $ ) {
    <?php
    $minimum_notice = simontaxi_get_option( 'minimum_notice', 1);
    $maximum_notice = simontaxi_get_option( 'maximum_notice', 1);;
    $maximum_notice_type = simontaxi_get_option( 'maximum_notice_type', 'month' );

    $maximum_notice_days = 30;
    if ( $maximum_notice_type == 'day' )
    $maximum_notice_days = $maximum_notice;
    elseif ( $maximum_notice_type == 'month' )
    $maximum_notice_days = $maximum_notice * 30;
    elseif ( $maximum_notice_type == 'year' )
    $maximum_notice_days = $maximum_notice * 12 * 30;	
	$st_date_format_js = simontaxi_get_option( 'st_date_format_js', 'dd-mm-yy' );
    ?>
    var dateFormat = '<?php echo $st_date_format_js; ?>';
    /*reference docs - http://api.jqueryui.com/datepicker/ */
    var onward_date = $( '.st_datepicker_limit' ).datepicker({
    minDate: new Date(new Date().getTime()+(<?php echo $minimum_notice; ?>*24*60*60*1000) ),
    todayButton:true,
    clearButton:true,
    autoClose: true,
    timePicker: false,
    dateFormat: dateFormat,
    maxDate: new Date(new Date().getTime()+(<?php echo $maximum_notice_days; ?>*24*60*60*1000) ),
    showOtherYears: true
    }).on( 'change', function(){
        return_date.datepicker( "option", "minDate", getDate( this ) );
    });

    var return_date = $( '.st_datepicker_limit_return' ).datepicker({
        minDate: new Date(new Date().getTime()+(<?php echo $minimum_notice; ?>*24*60*60*1000) ),
        todayButton:true,
        clearButton:true,
        autoClose: true,
        timePicker: false,
        dateFormat: dateFormat,
        maxDate: new Date(new Date().getTime()+(<?php echo $maximum_notice_days; ?>*24*60*60*1000) ),
        showOtherYears: true
        });

      function getDate( element ) {
      var date;
      try {
        date = jQuery.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
      return date;
    }


$( '#booking-p2p' ).submit(function (event) {

    var pickup_location = $( '#pickup_location' ).val();
    var drop_location = jQuery( '#drop_location' ).val();
    var p2p_pickup_date = jQuery( '#p2p_pickup_date' ).val();
    var pickup_time = jQuery( '#pickup_time' ).val();
    var pickup_time_hours = jQuery( '#pickup_time_hours' ).val();
    var pickup_time_minutes = jQuery( '#pickup_time_minutes' ).val();
    var pickup_date_return = jQuery( '#pickup_date_return' ).val();
    var pickup_time_hours_return = jQuery( '#pickup_time_hours_return' ).val();
    var pickup_time_minutes_return = jQuery( '#pickup_time_minutes_return' ).val();
    var distance = jQuery( '#distance' ).val();

    var error = 0;
    jQuery( '.error' ).hide();

	if ( pickup_location == '') {
    jQuery( '#pickup_location' ).after( '<span class="error"> <?php echo sprintf( esc_html__( 'Please Enter your %s', 'simontaxi' ), simontaxi_get_pickuppoint_title() ); ?> </span>' );
    error++;
    }
    if (drop_location == '') {
    jQuery( '#drop_location' ).after( '<span class="error"> <?php echo sprintf( esc_html__( 'Please Enter your %s', 'simontaxi' ), simontaxi_get_dropoffpoint_title() ); ?> </span>' );
    error++;
    }

    if (pickup_location != '' && drop_location != '')
    {
    if ( pickup_location == drop_location)
    {
    jQuery( '#pickup_location' ).after( '<span class="error"> <?php echo sprintf( esc_html__( '%s and %s should not be same', 'simontaxi' ), simontaxi_get_pickuppoint_title(), simontaxi_get_dropoffpoint_title() ); ?></span>' );
    error++;
    }
    }

    <?php
    $outofservice = simontaxi_get_option( 'outofservice', 0);
    if ( $outofservice > 0) :
    ?>
    if ( distance > <?php echo $outofservice; ?>)
    {
    jQuery( '#pickup_location' ).after( '<span class="error"> <?php echo sprintf( esc_html__( 'Service not available for above %s%s', 'simontaxi' ), $outofservice, simontaxi_get_distance_units() ); ?></span>' );
    error++;
    }
    <?php endif; ?>
    if (p2p_pickup_date == '') {
    jQuery( '#p2p_pickup_date' ).after( '<span class="error"> <?php echo sprintf( esc_html__( 'Please Enter your %s', 'simontaxi' ),  simontaxi_get_pickupdate_title() ); ?></span>' );
    error++;
    }

    if (pickup_time_minutes == '') {
    jQuery( '.pickup_time' ).after( '<span class="error"><?php echo sprintf( esc_html__( 'Please select your %s minutes', 'simontaxi' ), simontaxi_get_pickuptime_title() ); ?></span>' );
    error++;
    }
    if (pickup_time_hours == '') {
    jQuery( '.pickup_time' ).after( '<span class="error"> <?php echo sprintf( esc_html__( 'Please select your %s hours', 'simontaxi' ), simontaxi_get_pickuptime_title() ); ?></span>' );
    error++;
    }

    if (jQuery( 'input[name="journey_type"]:checked' ).val() == 'two_way' ) {
        if (pickup_date_return == '') {
        jQuery( '#pickup_date_return' ).after( '<span class="error"> <?php echo sprintf( esc_html__( 'Please select your return %s', 'simontaxi' ),simontaxi_get_pickupdate_title() ); ?></span>' );
        error++;
        }
        if ( p2p_pickup_date != '' && pickup_date_return != '' )
        {
            var parts = p2p_pickup_date.split( '-' );
			p2p_pickup_date_utc = Date.UTC( parts[0], parts[1], parts[2],0,0,0,0);
			var parts2 = pickup_date_return.split( '-' );
			pickup_date_utc_return = Date.UTC( parts2[0], parts2[1], parts2[2],0,0,0,0);
			
			if ( new Date( pickup_date_return ) < new Date( p2p_pickup_date ) ) {
                jQuery( '#pickup_date_return' ).after( '<span class="error"> <?php echo sprintf( esc_html__( 'Return %s should be after %s', 'simontaxi' ), simontaxi_get_pickupdate_title(), simontaxi_get_pickupdate_title() ); ?></span>' );
                error++;
            } else {
                
                if ( pickup_date_utc_return < p2p_pickup_date_utc)
                {
                jQuery( '#pickup_date_return' ).after( '<span class="error"> <?php echo sprintf( esc_html__( 'Date of return should be after %s', 'simontaxi' ), simontaxi_get_pickuptime_title() ); ?></span>' );
                error++;
                }
            }
        }

        if (pickup_time_minutes_return == '') {
        jQuery( '#pickup_time_minutes_return' ).after( '<span class="error"> <?php echo sprintf( esc_html__( 'Please select your return %s minutes', 'simontaxi' ), simontaxi_get_pickuptime_title() ); ?></span>' );
        error++;
        }
        if (pickup_time_hours_return == '') {
        jQuery( '#pickup_time_hours_return' ).after( '<span class="error"> <?php echo sprintf( esc_html__( 'Please select your return %s hours', 'simontaxi' ), simontaxi_get_pickuptime_title() ); ?></span>' );
        error++;
        }
    }
	
	<?php if ( simontaxi_get_option( 'allow_number_of_persons', 'no' ) === 'yesrequired' ) { ?>
	if ( jQuery( '.number_of_persons_p2p' ).val() == '' ) {
        jQuery( '.number_of_persons_p2p' ).after( '<span class="error"> <?php esc_html_e( 'Please enter number of persons', 'simontaxi' ); ?></span>' );
        error++;
    }
	<?php } ?>

    <?php if ( simontaxi_terms_page() == 'step1' ) : ?>
    if ( ! document.getElementById( 'terms' ).checked ) {
        jQuery( '#terms' ).closest( '.input-group' ).after( '<span class="error"> <?php esc_html_e( 'You should accept Terms of Service to proceed', 'simontaxi' )?></span>' );
        error++;
    }
    <?php endif; ?>
    if (error > 0 ){
		event.preventDefault();
	}
});

});
</script>
<?php /* ?>
<script src="//maps.googleapis.com/maps/api/js?libraries=places&key=<?php echo $google_api; ?>"></script>
<?php */ ?>
<?php // wp_enqueue_script( 'gmap3', SIMONTAXI_PLUGIN_URL . 'js/gmap3.min.js' );
$unitSystem = 'google.maps.UnitSystem.METRIC';
if ( $vehicle_distance == 'miles' ) {
    $unitSystem = 'google.maps.UnitSystem.IMPERIAL';
}

$vehicle_country_region_from = simontaxi_get_option( 'vehicle_country_region_from', '' );
$vehicle_country_region_to = simontaxi_get_option( 'vehicle_country_region_to', '' );

$vehicle_country_dropoff_region_from = simontaxi_get_option( 'vehicle_country_dropoff_region_from', '' );
$vehicle_country_dropoff_region_to = simontaxi_get_option( 'vehicle_country_dropoff_region_to', '' );

$jquery_version = '1.12.1';
wp_enqueue_style( 'jquery-ui-style', '//code.jquery.com/ui/' . $jquery_version . '/themes/base/jquery-ui.min.css', array(), $jquery_version );

// wp_enqueue_script( 'jquery-ui-new', '//code.jquery.com/ui/1.11.4/jquery-ui.min.js', array('jquery'), false, true );
?>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
var ajaxUrl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
jQuery(".predefined_place").autocomplete({
    source: function (request, response) {
        var type = jQuery( '.predefined_place:focus' ).attr( 'id' );
        jQuery.getJSON(ajaxUrl + '?action=st_auto_places&type='+type+'&term=' + request.term, function (data) {
            response(data);
        });
    }
    , select: function (event, ui) {
        jQuery(this).val(ui.item.value);
    }
    , minLength: 2
});
function initialize(id) {

	/**
	12.864162,77.438610

	13.139807,77.711895
	*/
	if ( id == 'drop_location' ) {
		var selected_country = jQuery('#drop_location_country').val();
		
		<?php if ( '' !== $vehicle_country_dropoff_region_from && '' !== $vehicle_country_dropoff_region_to ) : ?>
		<?php
		$vehicle_country_dropoff_region_from_parts = explode( ',', $vehicle_country_dropoff_region_from );
		$vehicle_country_dropoff_region_to_parts = explode( ',', $vehicle_country_dropoff_region_to );
		?>
		
		<?php if ( ! empty( $vehicle_country_dropoff_region_from_parts[1] ) 
			&& ! empty( $vehicle_country_dropoff_region_to_parts[1] ) 
		&& ! empty( $vehicle_country_dropoff_region_from_parts[0] ) 
		&& ! empty( $vehicle_country_dropoff_region_to_parts[0] ) ) { ?>
			var regionBounds = new google.maps.LatLngBounds(
			new google.maps.LatLng(<?php echo $vehicle_country_dropoff_region_from_parts[1]; ?>,<?php echo $vehicle_country_dropoff_region_to_parts[1]; ?>),
			new google.maps.LatLng(<?php echo $vehicle_country_dropoff_region_from_parts[0]; ?>,<?php echo $vehicle_country_dropoff_region_to_parts[0]; ?>) );
		<?php } ?>
		<?php endif; ?>

	} else {
		var selected_country = jQuery('#pickup_location_country').val();
		<?php if ( '' !== $vehicle_country_region_from && '' !== $vehicle_country_region_to ) : ?>
		<?php
		$vehicle_country_region_from_parts = explode( ',', $vehicle_country_region_from );
		$vehicle_country_region_to_parts = explode( ',', $vehicle_country_region_to );
		?>
		<?php if ( ! empty( $vehicle_country_region_from_parts[1] ) 
			&& ! empty( $vehicle_country_region_to_parts[1] ) 
		&& ! empty( $vehicle_country_region_from_parts[0] ) 
		&& ! empty( $vehicle_country_region_to_parts[0] ) ) { ?>
		
		var regionBounds = new google.maps.LatLngBounds(
		new google.maps.LatLng(<?php echo (float) $vehicle_country_region_from_parts[1]; ?>,<?php echo (float) $vehicle_country_region_to_parts[1]; ?>),
		new google.maps.LatLng(<?php echo (float) $vehicle_country_region_from_parts[0]; ?>,<?php echo (float) $vehicle_country_region_to_parts[0]; ?>) );
		
		<?php } ?>
		
		<?php endif; ?>
	}

	if ( typeof(regionBounds) == 'undefined' ) {
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
                    <?php } ?>
                    <?php if ( ! empty( $vehicle_country_dropoff ) || ! empty( $vehicle_country ) ) { ?>
					componentRestrictions: {                        
						country: selected_country,
                    },
					<?php } ?>
					language: 'en-GB'
                };
	} else {
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
					if ( 'predefined' !== $vehicle_places ) { ?>
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
	}
	console.log( selected_country );
    var input = jQuery( '#' + id);
    var autocomplete_my = new google.maps.places.Autocomplete(input[0], options);
	
	if ( typeof(regionBounds) != 'undefined' ) {
		<?php
		/**
		 * We have received many requests to restrict the region to book, so here is solution!
		 */
		if ( 'predefined' !== $vehicle_places ) { ?>
		autocomplete_my.setOptions({bounds:regionBounds, strictBounds: true});
		<?php } ?>
	}
	
    google.maps.event.addListener(autocomplete_my, 'place_changed', function () {
        place = autocomplete_my.getPlace();
		// console.log( place );
        jQuery( '#' + id + '_lat' ).val(place.geometry.location.lat() );
        jQuery( '#' + id + '_lng' ).val(place.geometry.location.lng() );

        if (place.address_components) {
            stateID = place.address_components[0] && place.address_components[0].long_name || '';
            countryID = place.address_components[3] && place.address_components[3].short_name || '';
            jQuery( '#' + id + '_country' ).val( countryID );
        }
        if ( place.name ) {
			stateID = place.name;
		} else {
			stateID = place.formatted_address;
		}
		stateID = place.formatted_address;
        input.blur();
        input.val(stateID);
        calculate_distance( id );
    });
}

function calculate_distance( id )
{
    if ( id == 'pickinguplocation' ) { //Airport transfer
        var pickup_location = jQuery( '#airportname' ).val();
        var drop_location = jQuery( '#pickinguplocation' ).val();
    } else {
        var pickup_location = jQuery( '#pickup_location' ).val();
        var drop_location = jQuery( '#drop_location' ).val();
    }
    if ( pickup_location != '' && drop_location != '' ) {
		get_map(pickup_location, drop_location, id);
    }
}
function get_map(PickLocation,DropLocation,id) {

    jQuery("#map_canvas").gmap3({
    clear: {},

     getroute:{
       options:{
           origin:PickLocation,
           destination:DropLocation,
           travelMode: google.maps.TravelMode.DRIVING,
           provideRouteAlternatives: true,
           optimizeWaypoints: true,
           /*--- Set avoid Tolls and Highways by Zunnie@FreshDigital 18 Apr 2016 ---*/
           avoidHighways: false,
           avoidTolls: false,
        /*
        google.maps.UnitSystem.METRIC - specifies usage of the metric system. Distances are shown using kilometers.
        google.maps.UnitSystem.IMPERIAL - specifies usage of the Imperial (English) system. Distances are shown using miles.
        */
        unitSystem: <?php echo $unitSystem; ?>
       },
       callback: function(results){

    //console.log(results);
    if ( results)
    {
        var no_of_routes = results.routes.length;
        // var distance_temp = results.routes[0].legs[0].distance.text;
		var distance_temp_text = results.routes[0].legs[0].distance.text;
		var distance_temp = results.routes[0].legs[0].distance.value; // The distance in Meters || Feet.
        for (var i = 0; i < no_of_routes; i++) {
            /*
			route_wise_distance = results.routes[i].legs[0].distance.text;
			if ( parseFloat(route_wise_distance) < parseFloat(distance_temp) ) {
              distance_temp = route_wise_distance;
            }
			*/
			var route_wise_distance_text = results.routes[i].legs[0].distance.text;
			var route_wise_distance = results.routes[i].legs[0].distance.value; // The distance in meters.
			if (parseFloat(route_wise_distance) < parseFloat(distance_temp) ) {
			  distance_temp = route_wise_distance;
			  distance_temp_text = route_wise_distance_text;
			}

        }

        //var distance = distance_temp;
        //var dist0 = distance;
        //var dist  = distance.split(" ")[0];
        //dist = dist.replace( ',', '' );
		<?php if ( 'miles' === $vehicle_distance ) {
			?>
		var distance = parseFloat( distance_temp / 5280).toFixed(2); // Hence we are getting distance in feet we are converting that into Miles
			<?php
		} else { ?>
		var distance = parseFloat( distance_temp / 1000).toFixed(2); // Hence we are getting distance in meters we are converting that into KM
		<?php } ?>
		var dist = distance;

        var time  = results.routes[0].legs[0].duration.text+" (Approx)";
        if (id == 'pickinguplocation' ) {
            jQuery( '#distance_airport' ).val(dist);
            jQuery( '#distance_text_airport' ).val(distance_temp_text);
            jQuery( '#duration_text_airport' ).val(time);
            /**
             * To make sure if admin changes unit system in middle of the user booking process, we need to find out
            */
            jQuery( '#distance_units_airport' ).val( '<?php echo $vehicle_distance; ?>' );
        } else {
        jQuery( '#distance' ).val(dist);
        jQuery( '#distance_text' ).val(distance_temp_text);
        jQuery( '#duration_text' ).val(time);
        /**
         * To make sure if admin changes unit system in middle of the user booking process, we need to find out
        */
        jQuery( '#distance_units' ).val( '<?php echo $vehicle_distance; ?>' );
        }

          if ( ! results ) return;
       }
       }
     }

    });
}

/* Airport transfer functions */
function toggle_pickupdrop(type) {
    if (type != 'pickup_location' ) {
        jQuery( '#pickupfieldset' ).removeClass( 'hide' );
        jQuery( '#pickinguplocation' ).attr( 'name', 'pickup_location' );
        jQuery( '#pickupfieldset label' ).text( '<?php echo simontaxi_get_pickuppoint_title(); ?>' );
        jQuery( '#airportname' ).attr( 'name', 'drop_location' );
		
		jQuery("#pickinguplocation option[value='']").text('<?php esc_html_e( 'Please select ' . simontaxi_get_pickuppoint_title(), 'simontaxi' ); ?>');
		jQuery('#pickinguplocation.selectpicker').selectpicker('refresh');
    }
    else {
        jQuery( '#airportname' ).attr( 'name', 'pickup_location' );
        jQuery( '#pickinguplocation' ).attr( 'name', 'drop_location' );
        jQuery( '#pickupfieldset label' ).text( '<?php echo simontaxi_get_dropoffpoint_title(); ?>' );
		
		jQuery("#pickinguplocation option[value='']").text('<?php esc_html_e( 'Please select ' . simontaxi_get_dropoffpoint_title(), 'simontaxi' ); ?>');
		jQuery('#pickinguplocation.selectpicker').selectpicker('refresh');
    }
}

 jQuery( '#booking-airport' ).submit(function (event) {
    var airport = jQuery( 'input[name="airport"]:checked' ).val();
    var pickinguplocation = jQuery( '#pickinguplocation' ).val();
    var airportname = jQuery( '#airportname' ).val();
    var select_date = jQuery( '#airport_pickup_date' ).val();
    var airport_pickup_time_hours = jQuery( '#airport_pickup_time_hours' ).val();
    var airport_pickup_time_minutes = jQuery( '#airport_pickup_time_minutes' ).val();
    var distance_airport = jQuery( '#distance_airport' ).val();

    var error = 0;
    jQuery( '.error' ).hide();
    <?php if ( $allow_twoway_airport == 'both' ) { ?>
	if ( typeof(airport) == 'undefined' ) {
			jQuery( '#airporttype' ).after( '<span class="error"><?php esc_html_e( 'Please select', 'simontaxi' ); ?></span>' );
            error++;
    }
	<?php } ?>
    if (airportname == '') {
        jQuery( '#airportname' ).after( '<span class="error"><?php esc_html_e( 'Please select ' . $fixed_point_title . ' name', 'simontaxi' ); ?></span>' );
        error++;
    }
    if (pickinguplocation == '') {
        if ( jQuery( "input[name='airport']:checked" ).val() == 'pickup_location' ) {
            jQuery( '#pickinguplocation' ).after( '<span class="error"> <?php echo esc_html__( 'Please Enter your ', 'simontaxi' ) . simontaxi_get_dropoffpoint_title(); ?> </span>' );
        } else {
        jQuery( '#pickinguplocation' ).after( '<span class="error"> <?php echo esc_html__( 'Please Enter your ', 'simontaxi' ) . simontaxi_get_pickuppoint_title(); ?> </span>' );
        }
        error++;
    }
    <?php
    $outofservice = simontaxi_get_option( 'outofservice', 0);
    if ( $outofservice > 0) :
    ?>
    if ( distance_airport > <?php echo $outofservice; ?>)
    {
    jQuery( '#pickinguplocation' ).after( '<span class="error"> <?php echo sprintf( esc_html__( 'Service not available for above %s%s', 'simontaxi' ), $outofservice, simontaxi_get_distance_units() ); ?></span>' );
    error++;
    }
    <?php endif; ?>
    if (select_date == '') {
        jQuery( '#airport_pickup_date' ).after( '<span class="error"><?php echo sprintf( esc_html__( 'Please Enter your %s', 'simontaxi' ), simontaxi_get_pickupdate_title() ); ?></span>' );
        error++;
    }
    if (airport_pickup_time_minutes == '') {
    jQuery( '.pickup_time_airport' ).after( '<span class="error"><?php echo sprintf( esc_html__( 'Please select your %s minutes', 'simontaxi' ), simontaxi_get_pickuptime_title() ); ?></span>' );
    error++;
    }
    if (airport_pickup_time_hours == '') {
    jQuery( '.pickup_time_airport' ).after( '<span class="error"> <?php echo sprintf( esc_html__( 'Please select your %s hours', 'simontaxi' ), simontaxi_get_pickuptime_title() ); ?></span>' );
    error++;
    }
	
	<?php if ( 'yesrequired' === simontaxi_get_option( 'allow_number_of_persons', 'no' ) ) { ?>
    if ( jQuery( '.number_of_persons_airport' ).val() == '' ) {
        jQuery( '.number_of_persons_airport' ).after( '<span class="error"> <?php esc_html_e( 'Please enter number of persons', 'simontaxi' ); ?></span>' );
        error++;
    }
    <?php } ?>
	
    <?php if ( simontaxi_get_option( 'allow_flight_number', 'no' ) == 'yesrequired' ) { ?>
    if ( jQuery( '#flight_no' ).val() == '' ) {
        jQuery( '#flight_no' ).after( '<span class="error"> <?php esc_html_e( 'Please enter ' . $fixed_point_vehicle_name . ' number', 'simontaxi' ); ?></span>' );
        error++;
    }
    <?php } ?>
	<?php if ( simontaxi_get_option( 'allow_flight_arrival_time', 'no' ) == 'yesrequired' ) { ?>
    if ( jQuery( '#flight_arrival_time' ).val() == '' ) {
        jQuery( '#flight_arrival_time' ).after( '<span class="error"> <?php esc_html_e( 'Please enter ' . $fixed_point_vehicle_name . ' arrival time', 'simontaxi' ); ?></span>' );
        error++;
    }
    <?php } ?>
    <?php if ( simontaxi_terms_page() == 'step1' ) : ?>
    if ( ! document.getElementById( 'terms_airport' ).checked ) {
        jQuery( '#terms_airport' ).closest( '.input-group' ).after( '<span class="error"> <?php esc_html_e( 'You should accept Terms of Service to proceed', 'simontaxi' )?></span>' );
        error++;
    }
    <?php endif; ?>
    if (error > 0 ){
		event.preventDefault();
	}
});

//Hourly Rental
jQuery( '#booking-hourly' ).submit(function (event) {
    var hourly_package = jQuery( '#hourly_package' ).val();
    var hourly_pickup_location = jQuery( '#hourly_pickup_location' ).val();
    var hourly_pickup_date = jQuery( '#hourly_pickup_date' ).val();
    var hourly_pickup_time_hours = jQuery( '#hourly_pickup_time_hours' ).val();
    var hourly_pickup_time_minutes = jQuery( '#hourly_pickup_time_minutes' ).val();

    var error = 0;
    jQuery( '.error' ).hide();
    if (hourly_package == '') {
        jQuery( '#hourly_package' ).after( '<span class="error"><?php esc_html_e( 'Please select hourly package', 'simontaxi' ); ?></span>' );
        error++;
    }
    if (hourly_pickup_location == '') {
        jQuery( '#hourly_pickup_location' ).after( '<span class="error"> <?php echo sprintf( esc_html__( 'Please Enter your %s', 'simontaxi' ), simontaxi_get_pickuppoint_title() ); ?> </span>' );
        error++;
    }
    if (hourly_pickup_date == '') {
        jQuery( '#hourly_pickup_date' ).after( '<span class="error"><?php echo sprintf( esc_html__( 'Please Enter your %s', 'simontaxi' ), simontaxi_get_pickupdate_title() ); ?></span>' );
        error++;
    }
    if (hourly_pickup_time_minutes == '') {
    jQuery( '.pickup_time_hourly' ).after( '<span class="error"><?php echo sprintf( esc_html__( 'Please select your %s minutes', 'simontaxi' ), simontaxi_get_pickuptime_title() ); ?></span>' );
    error++;
    }
    if (hourly_pickup_time_hours == '') {
    jQuery( '.pickup_time_hourly' ).after( '<span class="error"> <?php echo sprintf( esc_html__( 'Please select your %s hours', 'simontaxi' ), simontaxi_get_pickuptime_title() ); ?></span>' );
    error++;
    }
    <?php if ( simontaxi_get_option( 'allow_itinerary', 'no' ) == 'yesrequired' ) { ?>
    if ( jQuery( '#itineraries' ).val() == '' ) {
        jQuery( '#itineraries' ).after( '<span class="error"> <?php esc_html_e( 'Please enter itineraries', 'simontaxi' ); ?></span>' );
        error++;
    }
    <?php } ?>
	<?php if ( 'yesrequired' === simontaxi_get_option( 'allow_number_of_persons', 'no' ) ) { ?>
    if ( jQuery( '.number_of_persons_hourly' ).val() == '' ) {
        jQuery( '.number_of_persons_hourly' ).after( '<span class="error"> <?php esc_html_e( 'Please enter number of persons', 'simontaxi' ); ?></span>' );
        error++;
    }
    <?php } ?>
    <?php if ( simontaxi_terms_page() == 'step1' ) : ?>
    if ( ! document.getElementById( 'hourly_terms' ).checked ) {
        jQuery( '#hourly_terms' ).closest( '.input-group' ).after( '<span class="error"> <?php esc_html_e( 'You should accept Terms of Service to proceed', 'simontaxi' )?></span>' );
        error++;
    }
    <?php endif; ?>
    if (error > 0 ){
		event.preventDefault();
	}
});

</script>