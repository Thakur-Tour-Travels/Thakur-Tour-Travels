jQuery(function($) {
	$(document).ready(function () {
		/**
		 * Boot strap tabs loading
		*/
				
		$('form#options').on('submit',function(e){
			e.preventDefault();
			return update_vehicle_options();
		});		
		  
		function update_vehicle_options(){			   
		$('#update-but').hide();
		$('.update-but-notice').remove();
		$('#update-but-loader').show();
		var data = $('form#options').serializeArray(); 
			$.post(simontaxi_vars.ajaxurl, data, function(response){
				if(response){
					$('#update-but-loader').hide();
					$('#update-but').show();
					$('#update-but').before('<div class="notice notice-success is-dismissible update-but-notice"><p>'+message+'</p></div>');
					
				}
			});
		}
	});
	
	$('[data-toggle="tooltip"]').tooltip({html:true}); 
	
	$( ".selectpicker" ).select2();
});

var media_uploader = null;
function open_media_uploader_image(id) {
	media_uploader = wp.media({
		frame:    "post", 
		state:    "insert", 
		multiple: false
	});
	media_uploader.on("insert", function(){
		var json = media_uploader.state().get("selection").first().toJSON();
		var image_url = json.url;
		var image_caption = json.caption;
		var image_title = json.title;
		jQuery('#'+id).val(image_url);
	});
	media_uploader.open();
}
function move_tab(e) {
	var selected_tab = e;
	
	var tabs = jQuery('ul.nav li');
	jQuery.each(tabs, function(index, tab) {
		var tab_id = jQuery(tab).find('a').data('tab');
		
		if ( selected_tab == tab_id ) {
			jQuery('#'+tab_id).show();
			jQuery(tab).addClass('active');
		} else {
			jQuery('#'+tab_id).hide();
			jQuery(tab).removeClass('active');
		}
	});
}

/**
 * If user want to delete the custom image and want to use default?
 *
 * @since 2.0.0
*/
function remove_image( id ) {
	jQuery( '#' + id).val('');
	jQuery( '#' + id + '_remove').val('yes');
	jQuery( '#' + id + '_image').remove();
	jQuery( '#' + id + '_link' ).remove();
}

function toggle_options( id ) {
	if ( jQuery( '#' + id ).is(':checked') ) {
		jQuery( '#' + id + '_span' ).show();
	} else {
		jQuery( '#' + id + '_span' ).hide();
	}
}

var geocoder;
var map;
var rectangle;
var selectedShape;
var gmarkers = [];
function google_map() {
	
	var mapOptions = {
        center: new google.maps.LatLng(44.5452, -78.5389),
        zoom: 5
    };
    map = new google.maps.Map(document.getElementById('vehicle_country_region_from_map'),
    mapOptions);
	
	//var vehicle_country = document.getElementById('vehicle_country').value;
	var vehicle_country = jQuery('#vehicle_country option:selected').html();
	
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode( {'address' : vehicle_country}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);
		}
	});
	
	var vehicle_country_region_from = document.getElementById('vehicle_country_region_from').value.split(',');
			
			
	var vehicle_country_region_to = document.getElementById('vehicle_country_region_to').value.split(',');	
	
	var vehicle_country_region_from_0 = parseFloat( vehicle_country_region_from[0] );
	var vehicle_country_region_from_1 = parseFloat( vehicle_country_region_from[1] );
	
	var vehicle_country_region_to_0 = parseFloat( vehicle_country_region_to[0] );
	var vehicle_country_region_to_1 = parseFloat( vehicle_country_region_to[1] );
	
	var draw_rec = true;
	
	if ( isNaN( vehicle_country_region_from_0 ) || isNaN( vehicle_country_region_from_1 ) || isNaN( vehicle_country_region_to_0 ) || isNaN( vehicle_country_region_to_1 ) ) {
		draw_rec = false;
	}

	if ( draw_rec ) {
		var bounds = {
			  north: parseFloat( vehicle_country_region_from[0] ),
			  south: parseFloat( vehicle_country_region_from[1] ),
			  east: parseFloat( vehicle_country_region_to[0] ),
			  west: parseFloat( vehicle_country_region_to[1] )
			};
		// Define the rectangle and set its editable property to true.
		rectangle = new google.maps.Rectangle({
			  strokeColor: '#6c6c6c',
			  strokeOpacity: 0.8,
			  strokeWeight: 3.5,
			  fillColor: '#926239',
			  fillOpacity: 0.6,
			  map: map,
			  bounds: bounds,
			  editable: true,
				draggable: true
			});
		
		//bounds  = new google.maps.LatLngBounds();
		
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(vehicle_country_region_from[0], vehicle_country_region_to[1]),
			title: vehicle_country,
			map: map,
		});
		gmarkers.push(marker);
		
		map.panTo(marker.getPosition());
		map.setCenter(marker.getPosition());
		map.panToBounds(bounds);
		
		map.setCenter({lat:parseFloat( vehicle_country_region_from[0] ),lng:parseFloat( vehicle_country_region_to[1] )});
		map.setCenter(marker.getPosition());
	}
	
}

function deleteSelectedShape() {
	if (rectangle) {
		rectangle.setMap(null);
	}
	rectangle = null;
}

function drawRec() {		
		
		if (drawingManager) {
		  drawingManager.setMap(null);
		}
		//Setting options for the Drawing Tool. In our case, enabling Polygon shape.
		drawingManager.setOptions({
			drawingMode : google.maps.drawing.OverlayType.RECTANGLE,
			drawingControl : true,
			markerOptions: {
                draggable: true
            },
			drawingControlOptions : {
				position : google.maps.ControlPosition.TOP_CENTER,
				drawingModes : [ google.maps.drawing.OverlayType.RECTANGLE ]
			},
			rectangleOptions : {
				strokeColor : '#6c6c6c',
				strokeWeight : 3.5,
				fillColor : '#926239',
				fillOpacity : 0.6,
                editable: true,
              draggable: true
			},
			map: map
		});
		// Loading the drawing Tool in the Map.
		drawingManager.setMap(map);
		
		google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
		  var newShape = e.overlay;                    
           newShape.type = e.type;
		  if (e.type == 'rectangle') {			
			google.maps.event.addListener(newShape, 'click', function (e) {
				setSelection(newShape);
			});
			var bounds = e.overlay.getBounds();
			var NE = bounds.getNorthEast();
			var SW = bounds.getSouthWest();
			document.getElementById('vehicle_country_region_from').value = NE.lat().toFixed(3) + ',' + SW.lat().toFixed(3);
			
			
			document.getElementById('vehicle_country_region_to').value = NE.lng().toFixed(3) + ',' + SW.lng().toFixed(3);			
		  }
		setSelection(newShape);
		});
		
		/**
		 * https://stackoverflow.com/questions/15536241/how-to-get-4-vertex-coordinates-from-gmaps-rectangle-overlay
		 * http://jsfiddle.net/geocodezip/aom5o2o5/
		*/
}

function setSelection (shape) {
	if (shape.type !== 'marker') {
		clearSelection();
		shape.setEditable(true);
	}
	
	selectedShape = shape;
}

function clearSelection () {
	
	if (selectedShape) {
		if (selectedShape.type !== 'marker') {
			
			selectedShape.setEditable(false);			
		}
		selectedShape = null;
	}
}

function removeMarkers() {
	if (gmarkers) {
		for(i=0; i<gmarkers.length; i++){
			gmarkers[i].setMap(null);
		}
	}
}


function clearRec() {
	if (drawingManager) {
      drawingManager.setMap(null);
    }
	if ( rectangle ) {
		rectangle.setMap(null);
	}
	rectangle = null;
	if (selectedShape) {
		selectedShape.setMap(null);
	}
	removeMarkers();
}

// Drop-off Region
var map2;
var rectangle2;
var selectedShape2;
var gmarkers2 = [];
function google_map2() {
	var mapOptions = {
        center: new google.maps.LatLng(44.5452, -78.5389),
        zoom: 5
    };
    map2 = new google.maps.Map(document.getElementById('vehicle_country_dropoff_region_from_map'),
    mapOptions);
	
	var vehicle_country_dropoff = jQuery('#vehicle_country_dropoff option:selected').html();
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode( {'address' : vehicle_country_dropoff}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map2.setCenter(results[0].geometry.location);
		}
	});
	
	var vehicle_country_dropoff_region_from = document.getElementById('vehicle_country_dropoff_region_from').value.split(',');
			
	var vehicle_country_dropoff_region_to = document.getElementById('vehicle_country_dropoff_region_to').value.split(',');	
	
	var vehicle_country_dropoff_region_from_0 = parseFloat( vehicle_country_dropoff_region_from[0] );
	var vehicle_country_dropoff_region_from_1 = parseFloat( vehicle_country_dropoff_region_from[1] );
	
	var vehicle_country_dropoff_region_to_0 = parseFloat( vehicle_country_dropoff_region_to[0] );
	var vehicle_country_dropoff_region_to_1 = parseFloat( vehicle_country_dropoff_region_to[1] );
	
	var draw_rec = true;
	
	if ( isNaN( vehicle_country_dropoff_region_from_0 ) || isNaN( vehicle_country_dropoff_region_from_1 ) || isNaN( vehicle_country_dropoff_region_to_0 ) || isNaN( vehicle_country_dropoff_region_to_1 ) ) {
		draw_rec = false;
	}

	if ( draw_rec ) {
		var bounds = {
			  north: parseFloat( vehicle_country_dropoff_region_from[0] ),
			  south: parseFloat( vehicle_country_dropoff_region_from[1] ),
			  east: parseFloat( vehicle_country_dropoff_region_to[0] ),
			  west: parseFloat( vehicle_country_dropoff_region_to[1] )
			};
		// Define the rectangle and set its editable property to true.
		rectangle2 = new google.maps.Rectangle({
			  strokeColor: '#6c6c6c',
			  strokeOpacity: 0.8,
			  strokeWeight: 3.5,
			  fillColor: '#926239',
			  fillOpacity: 0.6,
			  map: map2,
			  bounds: bounds,
			  editable: true,
              draggable: true
			});
			
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(vehicle_country_dropoff_region_from[0], vehicle_country_dropoff_region_to[1]),
			title: vehicle_country_dropoff,
			map: map2,
		});
		
		gmarkers2.push( marker );
		
		map2.panTo(marker.getPosition());
		map2.setCenter(marker.getPosition());
		map2.panToBounds(bounds);
	}
}

function drawRec2() {		
		
		if (drawingManager2) {
		  drawingManager2.setMap(null);
		}
		//Setting options for the Drawing Tool. In our case, enabling Polygon shape.
		drawingManager2.setOptions({
			drawingMode : google.maps.drawing.OverlayType.RECTANGLE,
			drawingControl : true,
			markerOptions: {
                draggable: true
            },
			drawingControlOptions : {
				position : google.maps.ControlPosition.TOP_CENTER,
				drawingModes : [ google.maps.drawing.OverlayType.RECTANGLE ]
			},
			rectangleOptions : {
				strokeColor : '#6c6c6c',
				strokeWeight : 3.5,
				fillColor : '#926239',
				fillOpacity : 0.6,
                editable: true,
              draggable: true
			},
			map: map2	
		});
		// Loading the drawing Tool in the Map.
		drawingManager2.setMap(map2);
		
		google.maps.event.addListener(drawingManager2, 'overlaycomplete', function(e) {
		  var newShape = e.overlay;                    
           newShape.type = e.type;
		  if (e.type == 'rectangle') {
			google.maps.event.addListener(newShape, 'click', function (e) {
				setSelection(newShape);
			});
			var bounds = e.overlay.getBounds();
			var NE = bounds.getNorthEast();
			var SW = bounds.getSouthWest();
			document.getElementById('vehicle_country_dropoff_region_from').value = NE.lat().toFixed(3) + ',' + SW.lat().toFixed(3);
			
			
			document.getElementById('vehicle_country_dropoff_region_to').value = NE.lng().toFixed(3) + ',' + SW.lng().toFixed(3);			
		  }
		setSelection2(newShape);		  
		});		
}

function setSelection2 (shape) {
	if (shape.type !== 'marker') {
		clearSelection2();
		shape.setEditable(true);
	}	
	selectedShape2 = shape;
}

function clearSelection2 () {
	
	if (selectedShape2) {
		if (selectedShape2.type !== 'marker') {
			
			selectedShape2.setEditable(false);			
		}
		selectedShape2 = null;
	}
}

function removeMarkers2() {
	if (gmarkers2) {
		for(i=0; i<gmarkers2.length; i++){
			gmarkers2[i].setMap(null);
		}
	}
}

function clearRec2() {
	if (drawingManager2) {
      drawingManager2.setMap(null);
    }
	if (rectangle2) {
      rectangle2.setMap(null);
    }
	rectangle2 = null;
	if (selectedShape2) {
		selectedShape2.setMap(null);
	}
	removeMarkers2();
}

function setMapCenter1( country ) {	
var country = jQuery('#vehicle_country option:selected').html();
var geocoder = new google.maps.Geocoder();
	geocoder.geocode( {'address' : country}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);
		}
	});
}


function setMapCenter2( country ) {	
	var country = jQuery('#vehicle_country_dropoff option:selected').html();
	var geocoder2 = new google.maps.Geocoder();
	geocoder2.geocode( {'address' : country}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map2.setCenter(results[0].geometry.location);
		}
	});
}