jQuery(function($) {
	$(document).ready(function () {
		
		$('form#options').on('submit',function(e){
			e.preventDefault();
			return update_vehicle_options();
		  });
		  
				  
		function update_vehicle_options(){			   
		var data = $('form#options').serializeArray(); 
			$.post(ajaxUrl, data, function(response){
				if(response){
					$('#update-but').before('<div class="notice updated lc-plugin-activated is-dismissible"><p>'+message+'</p></div>');
				}
			});
		}
	});	
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