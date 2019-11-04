jQuery(function($) {
	$(document).ready(function () {
		/**
		 * Boot strap tabs loading
		*/
				
		$('.paymentgateway').on('change',function(e){
			
			var paymentmethod = $( this ).val();
			
			var response = '<img src="'+simontaxi_vars.ajax_loader+'" width="60" height="60">';
			
			$('#submitbutton').html(response);
			
			$.ajax({
				url : simontaxi_vars.ajaxurl,
				type : 'post',
				data : {
					action : 'simontaxi_submit_button_step4',
					paymentmethod : paymentmethod
				},
				success : function( response ) {
					$('#submitbutton').html(response);
				}
			});
			
		 });
	});

	$( ".selectpicker" ).select2();
	$('[data-toggle="tooltip"]').tooltip({html:true,placement:'auto'}); 
});

