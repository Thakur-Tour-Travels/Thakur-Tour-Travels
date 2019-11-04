<?php  
/**
 * Paypal Class
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  Paypal
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Paypal {
	
	var $config 		= Array();
	var $production_url = 'https://www.paypal.com/cgi-bin/webscr?';
	var $sandbox_url	= 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
	var $item			= 1;
	
	/**
	 * Constructor
	 *
	 * @param	string
	 * @return	void
	 */
	public function __construct($props = array())
	{
		$this->__initialize($props);
	}
	// --------------------------------------------------------------------

	/**
	 * initialize Paypal preferences
	 *
	 * @access	public
	 * @param	array
	 * @return	bool
	 */
	function __initialize($props = array())
	{
		#Account information
		$config["business"] 	= ''; //Account email or id
		$config["cmd"] 			= '_cart'; //Do not modify
		$config["production"] 	= FALSE;

		#Custom variable here we send the billing code-->
		$config["custom"]	= '';
		$config["invoice"]	= ''; //Code to identify the bill

		#API Configuration-->
		$config["upload"] 		 = '1'; //Do not modify
		$config["currency_code"] = 'GBP'; //http://bit.ly/anciiH
		$config["disp_tot"] = 'Y';

		#Page Layout -->
		$config["cpp_header_image"] 		= ''; //Image header url [750 pixels wide by 90 pixels high]
		$config["cpp_cart_border_color"] 	= '000'; //The HTML hex code for your principal identifying color
		$config["no_note"] 	= 1; //[0,1] 0 show, 1 hide

		#Payment Page Information -->
		$config["return"] 			= ''; //The URL to which PayPal redirects buyersâ€™ browser after they complete their payments.
		$config["cancel_return"]	= ''; //Specify a URL on your website that displays a â€œPayment Canceledâ€ page.
		$config["notify_url"] 		= '';  //The URL to which PayPal posts information about the payment (IPN)
		$config["rm"] = '2'; //Leave this to get payment information 
		$config["lc"] = 'EN'; //Languaje [EN,ES]

		#Shipping and Misc Information -->
		$config["shipping"] 	= '';
		$config["shipping2"] 	= '';
		$config["handling"] 	= '';
		$config["tax"] 			= '';
		$config["discount_amount_cart"] = ''; //Discount amount [9.99]
		$config["discount_rate_cart"] 	= ''; //Discount percentage [15]

		#Customer Information -->
		$config["first_name"] 		= '';
		$config["last_name"] 		= '';
		$config["address1"] 		= '';
		$config["address2"] 		= '';
		$config["city"] 			= '';
		$config["state"] 			= '';
		$config["zip"] 				= '';
		$config["email"] 			= '';
		$config["night_phone_a"] 	= '';
		$config["night_phone_b"] 	= '';
		$config["night_phone_c"] 	= '';
		
		/*
		 * Convert array elements into class variables
		 */
		if (count($props) > 0)
		{
			foreach ($props as $key => $val)
			{
				$config[$key] = $val;
			}
		}
		$this->config = $config;
	}
	
	// --------------------------------------------------------------------	
	
	/**
	 * Perform payment process
	 *
	 * @access	public
	 * @param	array
	 * @return	void
	 */	
	function pay(){
		
		#Convert the array to url encode variables 
		$vars =  http_build_query($this->config);
		if($this->config['production'] == TRUE){
			header('LOCATION:'.$this->production_url.$vars);
		}else{
			header('LOCATION:'.$this->sandbox_url.$vars);
		}
	}
	
	// --------------------------------------------------------------------	
	
	/**
	 * Add a product to the list
	 *
	 * @access	public
	 * @param	array
	 * @return	void
	 */	
	function add($item_name = '',$item_amount = NULL,$item_qty = NULL,$item_number = NULL, $shipping = NULL, $tax = NULL){
		$this->config['item_name_'.$this->item] 	= $item_name;
		$this->config['amount_'.$this->item] 		= $item_amount;
		$this->config['quantity_'.$this->item] 		= $item_qty;
		$this->config['item_number_'.$this->item] 	= $item_number;
		$this->config['shipping_'.$this->item] 	= $shipping;
		$this->config['tax_'.$this->item] 	= $tax;
		$this->item++;
	}	
}
// END Paypal Class