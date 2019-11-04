<?php
/**
 * Vehicle Settings Class
 *
 * Handles settings related to vehicle booking.
 *
 * @class 		Vehicle_Settings
 * @package		Simontaxi - Vehicle Booking
 * @category	Class
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//add_action( 'admin_init', 'simontaxi_db_install' );
// add_action( 'admin_init', 'simontaxi_install_pages' );
// add_action( 'admin_init', 'simontaxi_default_templates' );
//add_action( 'admin_init', 'simontaxi_install_pages_new' );

// add_action( 'admin_init', 'simontaxi_install' );

function simontaxi_install() {
	$simontaxi_db_install = get_option( 'simontaxi_db_install', 'no' );
	if ( 'no' === $simontaxi_db_install ) {
		simontaxi_db_install();
		simontaxi_update_db_fields();
	}
	
	$simontaxi_pages = get_option( 'simontaxi_pages', array() );
	if ( empty( $simontaxi_pages ) ) {
		simontaxi_install_pages_new();
	}
}

function simontaxi_update_db_fields() {
	global $wpdb;
	$updated = 0;
	$st_bookings = $wpdb->prefix . 'st_bookings';
	/**
	 * @since 2.0.8
	 */
	$driver_id = $wpdb->get_var( "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$st_bookings' AND COLUMN_NAME = 'driver_id'" );
	if ( empty( $driver_id ) ) {
		$wpdb->query( "ALTER TABLE $st_bookings ADD COLUMN driver_id bigint(20) DEFAULT 0" );
		$updated = 1;
	}
	
	/**
	 * @since 2.0.9
	 */
	$user_email = $wpdb->get_var( "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$st_bookings' AND COLUMN_NAME = 'user_email'" );
	if ( empty( $user_email ) ) {
		$wpdb->query( "ALTER TABLE $st_bookings ADD COLUMN user_email varchar(256) DEFAULT NULL" );
		$updated = 1;
	}
	
	$from_place_id = $wpdb->get_var( "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$st_bookings' AND COLUMN_NAME = 'from_place_id'" );
	if ( empty( $from_place_id ) ) {
		$wpdb->query( "ALTER TABLE $st_bookings ADD COLUMN from_place_id varchar(256) DEFAULT NULL" );
		$updated = 1;
	}
	
	$to_place_id = $wpdb->get_var( "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$st_bookings' AND COLUMN_NAME = 'to_place_id'" );
	if ( empty( $to_place_id ) ) {
		$wpdb->query( "ALTER TABLE $st_bookings ADD COLUMN to_place_id varchar(256) DEFAULT NULL" );
		$updated = 1;
	}
	
	/**
	 * @since 2.0.9
	 */
	$st_coupons_history = $wpdb->prefix . 'st_coupons_history';
	$coupon_amount = $wpdb->get_var( "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$st_coupons_history' AND COLUMN_NAME = 'coupon_amount'" );
	if ( empty( $coupon_amount ) ) {
		$wpdb->query( "ALTER TABLE  $st_coupons_history ADD COLUMN coupon_amount float(10,2) DEFAULT 0" );
		$updated = 1;
	}
	
	$booking_id = $wpdb->get_var( "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$st_coupons_history' AND COLUMN_NAME = 'booking_id'" );
	if ( empty( $booking_id ) ) {
		$wpdb->query( "ALTER TABLE  $st_coupons_history ADD COLUMN booking_id bigint(20) DEFAULT 0" );
		$updated = 1;
	}
	return $updated;
}

/**
 * Function to install the required database tables.
 *
 * @since 2.0.0
*/
function simontaxi_db_install() {
	global $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php' );

	$st_bookings = $wpdb->prefix . 'st_bookings';
	$st_callbacks = $wpdb->prefix . 'st_callbacks';
	$st_countries = $wpdb->prefix . 'st_countries';
	$st_payments = $wpdb->prefix . 'st_payments';
	$st_request_callback = $wpdb->prefix . 'st_request_callback';
	$st_user_support = $wpdb->prefix . 'st_user_support';
	$db_version = '2.0.0';

	$charset = 'utf8';
	$collate = 'utf8_general_ci';

	if ( $wpdb->has_cap( 'collation' ) ) {
		if ( ! empty( $wpdb->charset ) ) {
			$charset = $wpdb->charset;
		}
		if ( ! empty( $wpdb->collate ) ) {
			$collate = $wpdb->collate;
		}
	}
	$db_version = '2.0.1';
	$st_bookings_table = "CREATE TABLE `$st_bookings` (
	  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
	  `user_id` bigint(20) NOT NULL,
	  `reference` varchar(64) COLLATE $collate NOT NULL,
	  `booking_type` varchar(20) COLLATE $collate NOT NULL,
	  `airport` varchar(30) COLLATE $collate DEFAULT NULL,
	  `hourly_package` varchar(500) COLLATE $collate NOT NULL,
	  `selected_vehicle` bigint(20) NOT NULL,
	  `vehicle_name` varchar(256) COLLATE $collate DEFAULT NULL,
	  `pickup_location` varchar(256) COLLATE $collate NOT NULL,
	  `pickup_location_country` varchar(10) COLLATE $collate DEFAULT NULL,
	  `pickup_location_lat` varchar(20) COLLATE $collate DEFAULT NULL,
	  `pickup_location_lng` varchar(20) COLLATE $collate DEFAULT NULL,
	  `drop_location` varchar(256) COLLATE $collate NOT NULL,
	  `drop_location_country` varchar(10) COLLATE $collate DEFAULT NULL,
	  `drop_location_lat` varchar(20) COLLATE $collate DEFAULT NULL,
	  `drop_location_lng` varchar(20) COLLATE $collate DEFAULT NULL,
	  `journey_type` varchar(11) COLLATE $collate NOT NULL,
	  `pickup_date` date NOT NULL,
	  `pickup_time` varchar(20) COLLATE $collate NOT NULL,
	  `waiting_time` varchar(20) COLLATE $collate DEFAULT NULL,
	  `additional_pickups` int(11) DEFAULT NULL,
	  `additional_dropoff` int(11) DEFAULT NULL,
	  `pickup_date_return` varchar(20) COLLATE $collate DEFAULT NULL,
	  `pickup_time_return` varchar(20) COLLATE $collate DEFAULT NULL,
	  `waiting_time_return` varchar(20) COLLATE $collate DEFAULT NULL,
	  `additional_pickups_return` int(11) DEFAULT NULL,
	  `additional_dropoff_return` int(11) DEFAULT NULL,
	  `booking_contacts` text COLLATE $collate,
	  `driver_information` text COLLATE $collate NOT NULL,
	  `date` datetime NOT NULL,
	  `status` varchar(150) COLLATE $collate COMMENT 'Default stautsus new,confirmed,cancelled,success,onride' DEFAULT 'new',
	  `status_updated` datetime NOT NULL,
	  `distance` int(20) DEFAULT NULL,
	  `distance_text` varchar(120) COLLATE $collate DEFAULT NULL,
	  `duration_text` varchar(126) COLLATE $collate DEFAULT NULL,
	  `distance_units` varchar(50) COLLATE $collate DEFAULT NULL,
	  `coupon_code` varchar(20) COLLATE $collate DEFAULT NULL,
	  `session_details` longtext COLLATE $collate,
	  `flight_no` varchar(50) COLLATE $collate DEFAULT NULL,
	  `itineraries` text COLLATE $collate,
	  `reason_message` text COLLATE $collate COMMENT 'This field can used used for dual purpose. When booking is cancelled it has reason for cancel, If it confirmed admin can send any special instructions to user',
	  `vehicle_no` varchar(256) COLLATE $collate DEFAULT NULL,
	  `return_booking_id` bigint(20) DEFAULT NULL,
	  PRIMARY KEY (`ID`)
	) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collate";
	dbDelta( $st_bookings_table);
	
	$db_version = '2.0.0';
	$st_callbacks_table = "CREATE TABLE `$st_callbacks` (
	  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
	  `name` varchar(256) DEFAULT NULL,
	  `phone` varchar(256) DEFAULT NULL,
	  `email` varchar(256) DEFAULT NULL,
	  `ip_address` varchar(10) DEFAULT NULL,
	  `user_agent` varchar(256) DEFAULT NULL,
	  `date_time` datetime DEFAULT NULL,
	  PRIMARY KEY (`ID`)
	) ENGINE=InnoDB DEFAULT CHARSET=$charset;";
	dbDelta( $st_callbacks_table);
	update_option( $st_callbacks . '_db_version', $db_version );

	$db_version_countries = '2.0.2';
	$st_countries_table = "CREATE TABLE `$st_countries` (
	  `id_countries` int(3) unsigned NOT NULL AUTO_INCREMENT,
	  `name` varchar(200) COLLATE $collate DEFAULT NULL,
	  `iso_alpha2` varchar(2) COLLATE $collate DEFAULT NULL,
	  `iso_alpha3` varchar(3) COLLATE $collate DEFAULT NULL,
	  `iso_numeric` int(11) DEFAULT NULL,
	  `currency_code` char(3) COLLATE $collate DEFAULT NULL,
	  `currency_name` varchar(32) COLLATE $collate DEFAULT NULL,
	  `currency_symbol` varchar(50) COLLATE $collate DEFAULT NULL,
	  `flag` varchar(6) COLLATE $collate DEFAULT NULL,
	  `phonecode` int(6) DEFAULT NULL,
	  PRIMARY KEY (`id_countries`)
	) ENGINE=MyISAM DEFAULT CHARSET=$charset COLLATE=$collate;";
	dbDelta( $st_countries_table );
	$previous_version = get_option( $st_countries . '_db_version' );
	update_option( $st_countries . '_db_version', $db_version_countries );
	
	$st_countries_data = "INSERT INTO `$st_countries` (`id_countries`, `name`, `iso_alpha2`, `iso_alpha3`, `iso_numeric`, `currency_code`, `currency_name`, `currency_symbol`, `flag`, `phonecode`) VALUES
(1,	'Afghanistan',	'AF',	'AFG',	4,	'AFN',	'Afghan afghani',	'؋',	'AF.png',	93),
(2,	'Albania',	'AL',	'ALB',	8,	'ALL',	'Albanian Lek',	'ALL',	'AL.png',	355),
(3,	'Algeria',	'DZ',	'DZA',	12,	'DZD',	'Algerian Dinar',	'د.ج',	'DZ.png',	213),
(4,	'American Samoa',	'AS',	'ASM',	16,	'USD',	'United States Dollar',	'$',	'AS.png',	1),
(5,	'Andorra',	'AD',	'AND',	20,	'EUR',	'Euro',	'&euro;',	'AD.png',	376),
(6,	'Angola',	'AO',	'AGO',	24,	'AOA',	'Angolan Kwanza',	'Kz',	'AO.png',	244),
(7,	'Anguilla',	'AI',	'AIA',	660,	'XCD',	'Eastern Caribbean dollar',	'$',	'AI.png',	1),
(8,	'Antarctica',	'AQ',	'AQD',	10,	'A$',	'Antarctican dollar',	'A$',	'AQ.png',	672),
(9,	'Antigua and Barbuda',	'AG',	'ATG',	28,	'XCD',	'Eastern Caribbean dollar',	'$',	'AG.png',	1),
(10,	'Argentina',	'AR',	'ARG',	32,	'ARS',	'Argentine Peso',	'$',	'AR.png',	54),
(11,	'Armenia',	'AM',	'ARM',	51,	'AMD',	'Dram',	'&#1423;',	'AM.png',	374),
(12,	'Aruba',	'AW',	'ABW',	533,	'AWG',	'Guilder',	'&fnof;',	'AW.png',	297),
(13,	'Australia',	'AU',	'AUS',	36,	'AUD',	'Australian dollar',	'$',	'AU.png',	61),
(14,	'Austria',	'AT',	'AUT',	40,	'EUR',	'Euro',	'&euro;',	'AT.png',	43),
(15,	'Azerbaijan',	'AZ',	'AZE',	31,	'AZN',	'Manat',	'&#8380;',	'AZ.png',	994),
(16,	'Bahamas',	'BS',	'BHS',	44,	'BSD',	'Bahamian dollar',	'B$',	'BS.png',	1),
(17,	'Bahrain',	'BH',	'BHR',	48,	'BHD',	'Bahraini dinar',	'.د.ب',	'BH.png',	973),
(18,	'Bangladesh',	'BD',	'BGD',	50,	'BDT',	'Taka',	'&#2547;&nbsp;',	'BD.png',	880),
(19,	'Barbados',	'BB',	'BRB',	52,	'BBD',	'Barbadian dollar',	'Bds$',	'BB.png',	1),
(20,	'Belarus',	'BY',	'BLR',	112,	'BYR',	'Belarusian ruble',	'ꀷ',	'BY.png',	375),
(21,	'Belgium',	'BE',	'BEL',	56,	'EUR',	'Euro',	'&euro;',	'BE.png',	32),
(22,	'Belize',	'BZ',	'BLZ',	84,	'BZD',	'Belize dollar',	'$',	'BZ.png',	501),
(23,	'Benin',	'BJ',	'BEN',	204,	'XOF',	'West African CFA franc',	'Fr',	'BJ.png',	229),
(24,	'Bermuda',	'BM',	'BMU',	60,	'BMD',	'Bermudian dollar',	'$',	'BM.png',	1),
(25,	'Bhutan',	'BT',	'BTN',	64,	'BTN',	'Ngultrum',	'Nu.',	'BT.png',	975),
(26,	'Bolivia',	'BO',	'BOL',	68,	'BOB',	'Bolivian boliviano',	'Bs',	'BO.png',	591),
(27,	'Bosnia and Herzegovina',	'BA',	'BIH',	70,	'BAM',	'Marka',	'KM',	'BA.png',	387),
(28,	'Botswana',	'BW',	'BWA',	72,	'BWP',	'Botswana pula',	'P',	'BW.png',	267),
(29,	'Bouvet Island',	'BV',	'BVT',	74,	'NOK',	'Norwegian Krone',	'kr',	'BV.png',	55),
(30,	'Brazil',	'BR',	'BRA',	76,	'BRL',	'Brazilian real',	'R$',	'BR.png',	55),
(31,	'British Indian Ocean Territory',	'IO',	'IOT',	86,	'USD',	'United States Dollar',	'$',	'IO.png',	246),
(32,	'British Virgin Islands',	'VG',	'VGB',	92,	'USD',	'United States Dollar',	'$',	'VG.png',	1),
(33,	'Brunei',	'BN',	'BRN',	96,	'BND',	'Brunei dollar',	'B$',	'BN.png',	673),
(34,	'Bulgaria',	'BG',	'BGR',	100,	'BGN',	'Lev',	'&#1083;&#1074;.',	'BG.png',	359),
(35,	'Burkina Faso',	'BF',	'BFA',	854,	'XOF',	'West African CFA franc',	'Fr',	'BF.png',	226),
(36,	'Burundi',	'BI',	'BDI',	108,	'BIF',	'Burundian franc',	'Fr',	'BI.png',	257),
(37,	'Cambodia',	'KH',	'KHM',	116,	'KHR',	'Cambodian riel',	'៛',	'KH.png',	855),
(38,	'Cameroon',	'CM',	'CMR',	120,	'XAF',	'Central African CFA franc',	'Fr',	'CM.png',	237),
(39,	'Canada',	'CA',	'CAN',	124,	'CAD',	'Canadian dollar',	'$',	'CA.png',	1),
(40,	'Cape Verde',	'CV',	'CPV',	132,	'CVE',	'Escudo',	'&#36;',	'CV.png',	238),
(41,	'Cayman Islands',	'KY',	'CYM',	136,	'KYD',	'Cayman Islands dollar',	'$',	'KY.png',	1),
(42,	'Central African Republic',	'CF',	'CAF',	140,	'XAF',	'Central African CFA franc',	'Fr',	'CF.png',	236),
(43,	'Chad',	'TD',	'TCD',	148,	'XAF',	'Central African CFA franc',	'Fr',	'TD.png',	235),
(44,	'Chile',	'CL',	'CHL',	152,	'CLP',	'Chilean peso',	'$',	'CL.png',	56),
(45,	'China',	'CN',	'CHN',	156,	'CNY',	'Yuan Renminbi',	'&yen;',	'CN.png',	86),
(46,	'Christmas Island',	'CX',	'CXR',	162,	'AUD',	'Australian dollar',	'$',	'CX.png',	61),
(47,	'Cocos Islands',	'CC',	'CCK',	166,	'AUD',	'Australian dollar',	'$',	'CC.png',	891),
(48,	'Colombia',	'CO',	'COL',	170,	'COP',	'Colombian peso',	'$',	'CO.png',	57),
(49,	'Comoros',	'KM',	'COM',	174,	'KMF',	'Comorian franc',	'Fr',	'KM.png',	269),
(50,	'Cook Islands',	'CK',	'COK',	184,	'NZD',	'New Zealand dollar',	'$',	'CK.png',	670),
(51,	'Costa Rica',	'CR',	'CRI',	188,	'CRC',	'Colon',	'&#x20a1;',	'CR.png',	506),
(52,	'Croatia',	'HR',	'HRV',	191,	'HRK',	'Croatian kuna',	'Kn',	'HR.png',	385),
(53,	'Cuba',	'CU',	'CUB',	192,	'CUP',	'Cuban peso',	'$',	'CU.png',	53),
(54,	'Cyprus',	'CY',	'CYP',	196,	'CYP',	'Cypriot pound',	'£',	'CY.png',	357),
(55,	'Czech Republic',	'CZ',	'CZE',	203,	'CZK',	'Czech koruna',	'Kč',	'CZ.png',	420),
(56,	'Democratic Republic of the Congo',	'CD',	'COD',	180,	'CDF',	'Congolese franc',	'Fr',	'CD.png',	242),
(57,	'Denmark',	'DK',	'DNK',	208,	'DKK',	'Danish krone',	'DKK',	'DK.png',	45),
(58,	'Djibouti',	'DJ',	'DJI',	262,	'DJF',	'Djiboutian franc',	'Fr',	'DJ.png',	253),
(59,	'Dominica',	'DM',	'DMA',	212,	'DOP',	'Dominican peso',	'$',	'DM.png',	1),
(60,	'Dominican Republic',	'DO',	'DOM',	214,	'DOP',	'Dominican peso',	'RD$',	'DO.png',	1),
(61,	'East Timor',	'TL',	'TLS',	626,	'USD',	'United States Dollar',	'$',	'TL.png',	670),
(62,	'Ecuador',	'EC',	'ECU',	218,	'USD',	' United States Dollar',	'$',	'EC.png',	593),
(63,	'Egypt',	'EG',	'EGY',	818,	'EGP',	'Egyptian pound',	'E£',	'EG.png',	20),
(64,	'El Salvador',	'SV',	'SLV',	222,	'SVC',	'Salvadoran colón',	'₡',	'SV.png',	503),
(65,	'Equatorial Guinea',	'GQ',	'GNQ',	226,	'XAF',	'Central African CFA franc',	'Fr',	'GQ.png',	240),
(66,	'Eritrea',	'ER',	'ERI',	232,	'ERN',	'Nakfa',	'Nfk',	'ER.png',	291),
(67,	'Estonia',	'EE',	'EST',	233,	'EEK',	'Estonian kroon',	'kr',	'EE.png',	372),
(68,	'Ethiopia',	'ET',	'ETH',	231,	'ETB',	'Ethiopian birr',	'Br',	'ET.png',	251),
(279,	'Ghana',	'GH',	'GHA',	NULL,	'GHS',	'Ghanaian cedi',	'GH₵',	NULL,	233),
(70,	'Faroe Islands',	'FO',	'FRO',	234,	'DKK',	'Danish krone',	'DKK',	'FO.png',	298),
(71,	'Fiji',	'FJ',	'FJI',	242,	'FJD',	'Fijian dollar',	'$',	'FJ.png',	679),
(72,	'Finland',	'FI',	'FIN',	246,	'EUR',	'Euro',	'&euro;',	'FI.png',	358),
(73,	'France',	'FR',	'FRA',	250,	'EUR',	'Euro',	'&euro;',	'FR.png',	33),
(74,	'French Guiana',	'GF',	'GUF',	254,	'EUR',	'Euro',	'&euro;',	'GF.png',	594),
(75,	'French Polynesia',	'PF',	'PYF',	258,	'XPF',	'CFP franc',	'Fr',	'PF.png',	689),
(76,	'French Southern Territories',	'TF',	'ATF',	260,	'EUR',	'Euro  ',	'€',	'TF.png',	689),
(77,	'Gabon',	'GA',	'GAB',	266,	'XAF',	'Central African CFA franc',	'Fr',	'GA.png',	241),
(78,	'Gambia',	'GM',	'GMB',	270,	'GMD',	'Gambian Dalasi',	'D',	'GM.png',	220),
(79,	'Georgia',	'GE',	'GEO',	268,	'GEL',	'Georgian Lari',	'ლ',	'GE.png',	995),
(80,	'Germany',	'DE',	'DEU',	276,	'EUR',	'Euro',	'&euro;',	'DE.png',	49),
(81,	'Ghana',	'GH',	'GHA',	288,	'GHC',	'Ghanaian cedi',	' GH₵',	'GH.png',	233),
(82,	'Gibraltar',	'GI',	'GIB',	292,	'GIP',	'Gibraltar Pound',	'£',	'GI.png',	350),
(83,	'Greece',	'GR',	'GRC',	300,	'EUR',	'Euro',	'&euro;',	'GR.png',	30),
(84,	'Greenland',	'GL',	'GRL',	304,	'DKK',	'Danish krone',	'DKK',	'GL.png',	299),
(85,	'Grenada',	'GD',	'GRD',	308,	'XCD',	'Eastern Caribbean dollar',	'$',	'GD.png',	1),
(86,	'Guadeloupe',	'GP',	'GLP',	312,	'EUR',	'Euro',	'&euro;',	'GP.png',	590),
(87,	'Guam',	'GU',	'GUM',	316,	'USD',	'United States Dollar',	'$',	'GU.png',	1),
(88,	'Guatemala',	'GT',	'GTM',	320,	'GTQ',	'Guatemala Quetzal',	'Q',	'GT.png',	502),
(89,	'Guinea',	'GN',	'GIN',	324,	'GNF',	'Guinea Franc',	'Fr',	'GN.png',	224),
(90,	'Guinea-Bissau',	'GW',	'GNB',	624,	'XOF',	'West African CFA franc',	'Fr',	'GW.png',	245),
(91,	'Guyana',	'GY',	'GUY',	328,	'GYD',	'Guyana Dollar',	'$',	'GY.png',	592),
(92,	'Haiti',	'HT',	'HTI',	332,	'HTG',	'Haiti Gourde',	'G',	'HT.png',	509),
(93,	'Heard Island and McDonald Islands',	'HM',	'HMD',	334,	'AUD',	'Australian Dollar',	'$',	'HM.png',	672),
(94,	'Honduras',	'HN',	'HND',	340,	'HNL',	'Honduras Lempira',	'L',	'HN.png',	504),
(95,	'Hong Kong',	'HK',	'HKG',	344,	'HKD',	'Hong Kong Dollar',	'$',	'HK.png',	852),
(96,	'Hungary',	'HU',	'HUN',	348,	'HUF',	'Hungary Forint',	'Ft',	'HU.png',	36),
(97,	'Iceland',	'IS',	'ISL',	352,	'ISK',	'Iceland Krona',	'kr.',	'IS.png',	354),
(98,	'India',	'IN',	'IND',	356,	'INR',	'Indian rupee',	'₹',	'IN.png',	91),
(99,	'Indonesia',	'ID',	'IDN',	360,	'IDR',	'Indonesian Rupiah',	'Rp',	'ID.png',	62),
(100,	'Iran',	'IR',	'IRN',	364,	'IRR',	'Iranian Rial',	'﷼',	'IR.png',	98),
(101,	'Iraq',	'IQ',	'IRQ',	368,	'IQD',	'Iraqi Dinar',	'ع.د',	'IQ.png',	964),
(102,	'Ireland',	'IE',	'IRL',	372,	'EUR',	'Euro',	'&euro;',	'IE.png',	353),
(103,	'Israel',	'IL',	'ISR',	376,	'ILS',	'Israeli new Shekel',	'₪',	'IL.png',	972),
(104,	'Italy',	'IT',	'ITA',	380,	'EUR',	'Euro',	'&euro;',	'IT.png',	39),
(105,	'Ivory Coast',	'CI',	'CIV',	384,	'XOF',	'West African CFA franc',	'Fr',	'CI.png',	225),
(106,	'Jamaica',	'JM',	'JAM',	388,	'JMD',	'Jamaican Dollar',	'$',	'JM.png',	1),
(107,	'Japan',	'JP',	'JPN',	392,	'JPY',	'Japanese Yen',	'¥',	'JP.png',	81),
(108,	'Jordan',	'JO',	'JOR',	400,	'JOD',	'Jordanian Dinar',	'د.ا',	'JO.png',	962),
(109,	'Kazakhstan',	'KZ',	'KAZ',	398,	'KZT',	'Kazakhstani Tenge',	'KZT',	'KZ.png',	7),
(110,	'Kenya',	'KE',	'KEN',	404,	'KES',	'Kenyan Shilling',	'KSh',	'KE.png',	254),
(111,	'Kiribati',	'KI',	'KIR',	296,	'AUD',	' Australian dollar',	'$',	'KI.png',	686),
(112,	'Kuwait',	'KW',	'KWT',	414,	'KWD',	'Kuwaiti Dinar',	'د.ك',	'KW.png',	965),
(113,	'Kyrgyzstan',	'KG',	'KGZ',	417,	'KGS',	'Kyrgyzstani Som',	'сом',	'KG.png',	996),
(114,	'Laos',	'LA',	'LAO',	418,	'LAK',	'Lao Kip',	'₭',	'LA.png',	856),
(115,	'Latvia',	'LV',	'LVA',	428,	'LVL',	'Latvian Lats',	'Ls',	'LV.png',	371),
(116,	'Lebanon',	'LB',	'LBN',	422,	'LBP',	'Lebanese Pound',	'ل.ل',	'LB.png',	961),
(117,	'Lesotho',	'LS',	'LSO',	426,	'LSL',	'Lesotho Loti',	'L',	'LS.png',	266),
(118,	'Liberia',	'LR',	'LBR',	430,	'LRD',	'Liberian Dollar',	'$',	'LR.png',	231),
(119,	'Libya',	'LY',	'LBY',	434,	'LYD',	'Libyan Dinar',	'ل.د',	'LY.png',	218),
(120,	'Liechtenstein',	'LI',	'LIE',	438,	'CHF',	'Swiss franc',	'CHF',	'LI.png',	423),
(121,	'Lithuania',	'LT',	'LTU',	440,	'LTL',	'Lithuanian Litas',	'Lt',	'LT.png',	370),
(122,	'Luxembourg',	'LU',	'LUX',	442,	'EUR',	'Euro',	'&euro;',	'LU.png',	352),
(123,	'Macao',	'MO',	'MAC',	446,	'MOP',	'Macanese Pataca',	'MOP$',	'MO.png',	853),
(124,	'Macedonia',	'MK',	'MKD',	807,	'MKD',	'Macedonian Denar',	'ден',	'MK.png',	389),
(125,	'Madagascar',	'MG',	'MDG',	450,	'MGA',	'Malagasy ariary',	'Ar',	'MG.png',	261),
(126,	'Malawi',	'MW',	'MWI',	454,	'MWK',	'Malawian Kwacha',	'MK',	'MW.png',	265),
(127,	'Malaysia',	'MY',	'MYS',	458,	'MYR',	'Malaysian Ringgit',	'RM',	'MY.png',	60),
(128,	'Maldives',	'MV',	'MDV',	462,	'MVR',	'Maldivian Rufiyaa',	'.ރ',	'MV.png',	960),
(129,	'Mali',	'ML',	'MLI',	466,	'XOF',	'West African CFA franc',	'Fr',	'ML.png',	223),
(130,	'Malta',	'MT',	'MLT',	470,	'MTL',	'Maltese lira',	'₤ ',	'MT.png',	356),
(131,	'Marshall Islands',	'MH',	'MHL',	584,	'USD',	' United States Dollar',	'$',	'MH.png',	692),
(132,	'Martinique',	'MQ',	'MTQ',	474,	'EUR',	'Euro',	'&euro;',	'MQ.png',	596),
(133,	'Mauritania',	'MR',	'MRT',	478,	'MRO',	'Mauritanian Ouguiya',	'UM',	'MR.png',	222),
(134,	'Mauritius',	'MU',	'MUS',	480,	'MUR',	'Mauritian Rupee',	'₨',	'MU.png',	230),
(135,	'Mayotte',	'YT',	'MYT',	175,	'EUR',	'Euro',	'€',	'YT.png',	262),
(136,	'Mexico',	'MX',	'MEX',	484,	'MXN',	'Mexican Peso',	'$',	'MX.png',	52),
(137,	'Micronesia',	'FM',	'FSM',	583,	'USD',	' United States Dollar',	'$',	'FM.png',	691),
(138,	'Moldova',	'MD',	'MDA',	498,	'MDL',	'Moldovan Leu',	'L',	'MD.png',	373),
(139,	'Monaco',	'MC',	'MCO',	492,	'EUR',	'Euro',	'&euro;',	'MC.png',	377),
(140,	'Mongolia',	'MN',	'MNG',	496,	'MNT',	'Mongolian Tugrik',	'₮',	'MN.png',	976),
(141,	'Montserrat',	'MS',	'MSR',	500,	'XCD',	' Eastern Caribbean dollar',	'$',	'MS.png',	1),
(142,	'Morocco',	'MA',	'MAR',	504,	'MAD',	'Moroccan Dirham',	'د.م.',	'MA.png',	212),
(143,	'Mozambique',	'MZ',	'MOZ',	508,	'MZN',	'Meticail',	'MT',	'MZ.png',	258),
(144,	'Myanmar',	'MM',	'MMR',	104,	'MMK',	'Burmese kyat',	'K',	'MM.png',	95),
(145,	'Namibia',	'NA',	'NAM',	516,	'NAD',	'Namibian Dollar',	'$',	'NA.png',	264),
(146,	'Nauru',	'NR',	'NRU',	520,	'AUD',	' Australian dollar',	'$',	'NR.png',	674),
(147,	'Nepal',	'NP',	'NPL',	524,	'NPR',	'Nepalese Rupee',	'₨',	'NP.png',	977),
(148,	'Netherlands',	'NL',	'NLD',	528,	'EUR',	'Euro',	'&euro;',	'NL.png',	31),
(149,	'Netherlands Antilles',	'AN',	'ANT',	530,	'ANG',	'Netherlands Antillean Guilder',	'ƒ',	'AN.png',	599),
(150,	'New Caledonia',	'NC',	'NCL',	540,	'XPF',	'CFP franc',	'Fr',	'NC.png',	687),
(151,	'New Zealand',	'NZ',	'NZL',	554,	'NZD',	'New Zealand Dollar',	'$',	'NZ.png',	64),
(152,	'Nicaragua',	'NI',	'NIC',	558,	'NIO',	'Nicaraguan Cordoba',	'C$',	'NI.png',	505),
(153,	'Niger',	'NE',	'NER',	562,	'XOF',	'West African CFA franc',	'Fr',	'NE.png',	227),
(154,	'Nigeria',	'NG',	'NGA',	566,	'NGN',	'Nigerian Naira',	'₦',	'NG.png',	234),
(155,	'Niue',	'NU',	'NIU',	570,	'NZD',	'New Zealand dollar',	'$',	'NU.png',	683),
(156,	'Norfolk Island',	'NF',	'NFK',	574,	'AUD',	'Australian dollar',	'$',	'NF.png',	672),
(157,	'North Korea',	'KP',	'PRK',	408,	'KPW',	'North Korean Won',	'₩',	'KP.png',	850),
(158,	'Northern Mariana Islands',	'MP',	'MNP',	580,	'USD',	' United States Dollar',	'$',	'MP.png',	1),
(159,	'Norway',	'NO',	'NOR',	578,	'NOK',	'Norwegian Krone',	'kr',	'NO.png',	47),
(160,	'Oman',	'OM',	'OMN',	512,	'OMR',	'Omani Rial',	'ر.ع.',	'OM.png',	968),
(161,	'Pakistan',	'PK',	'PAK',	586,	'PKR',	'Pakistani Rupee',	'₨',	'PK.png',	92),
(162,	'Palau',	'PW',	'PLW',	585,	'USD',	' United States Dollar',	'$',	'PW.png',	680),
(163,	'Palestinian Territory',	'PS',	'PSE',	275,	'ILS',	'Shekel',	'₪',	'PS.png',	970),
(164,	'Panama',	'PA',	'PAN',	591,	'PAB',	'Panamanian Balboa',	'B/.',	'PA.png',	507),
(165,	'Papua New Guinea',	'PG',	'PNG',	598,	'PGK',	'Papua New Guinean Kina',	'K',	'PG.png',	675),
(166,	'Paraguay',	'PY',	'PRY',	600,	'PYG',	'Paraguayan Guarani',	'₲',	'PY.png',	595),
(167,	'Peru',	'PE',	'PER',	604,	'PEN',	'Sol',	'S/.',	'PE.png',	51),
(168,	'Philippines',	'PH',	'PHL',	608,	'PHP',	'Philippine Peso',	'₱',	'PH.png',	63),
(169,	'Pitcairn',	'PN',	'PCN',	612,	'NZD',	'New Zealand dollar',	'$',	'PN.png',	64),
(170,	'Poland',	'PL',	'POL',	616,	'PLN',	'Polish Zloty',	'zł',	'PL.png',	48),
(171,	'Portugal',	'PT',	'PRT',	620,	'EUR',	'Euro',	'&euro;',	'PT.png',	351),
(172,	'Puerto Rico',	'PR',	'PRI',	630,	'USD',	' United States Dollar',	'$',	'PR.png',	1),
(173,	'Qatar',	'QA',	'QAT',	634,	'QAR',	'Qatari Rial',	'ر.ق',	'QA.png',	974),
(174,	'Republic of the Congo',	'CG',	'COG',	178,	'XAF',	'Central African CFA franc',	'Fr',	'CG.png',	242),
(175,	'Reunion',	'RE',	'REU',	638,	'EUR',	'Euro',	'&euro;',	'RE.png',	262),
(176,	'Romania',	'RO',	'ROU',	642,	'RON',	'Romanian Leu',	'lei',	'RO.png',	40),
(177,	'Russia',	'RU',	'RUS',	643,	'RUB',	'Russian Ruble',	'₽',	'RU.png',	70),
(178,	'Rwanda',	'RW',	'RWA',	646,	'RWF',	'Rwandan Franc',	'Fr',	'RW.png',	250),
(179,	'Saint Helena',	'SH',	'SHN',	654,	'SHP',	'Saint Helena Pound',	'£',	'SH.png',	290),
(180,	'Saint Kitts and Nevis',	'KN',	'KNA',	659,	'XCD',	' Eastern Caribbean dollar',	'$',	'KN.png',	1),
(181,	'Saint Lucia',	'LC',	'LCA',	662,	'XCD',	'Eastern Caribbean dollar',	'$',	'LC.png',	1),
(182,	'Saint Pierre and Miquelon',	'PM',	'SPM',	666,	'EUR',	'Euro',	'&euro;',	'PM.png',	508),
(183,	'Saint Vincent and the Grenadines',	'VC',	'VCT',	670,	'XCD',	'Eastern Caribbean dollar',	'$',	'VC.png',	1),
(184,	'Samoa',	'WS',	'WSM',	882,	'WST',	'Samoan Tala',	'T',	'WS.png',	684),
(185,	'San Marino',	'SM',	'SMR',	674,	'EUR',	'Euro',	'&euro;',	'SM.png',	378),
(186,	'Sao Tome and Principe',	'ST',	'STP',	678,	'STD',	'Sao Tome and Principe Dobra',	'Db',	'ST.png',	239),
(187,	'Saudi Arabia',	'SA',	'SAU',	682,	'SAR',	'Saudi Arabian Rial',	'ر.س',	'SA.png',	966),
(188,	'Senegal',	'SN',	'SEN',	686,	'XOF',	'West African CFA franc',	'Fr',	'SN.png',	221),
(189,	'Serbia and Montenegro',	'CS',	'SCG',	891,	'RSD',	'SerbianDinar',	'дин.',	'CS.png',	381),
(190,	'Seychelles',	'SC',	'SYC',	690,	'SCR',	'Seychellois Rupee',	'₨',	'SC.png',	248),
(191,	'Sierra Leone',	'SL',	'SLE',	694,	'SLL',	'Sierra Leonean Leone',	'Le',	'SL.png',	232),
(192,	'Singapore',	'SG',	'SGP',	702,	'SGD',	'Singapore Dollar',	'$',	'SG.png',	65),
(193,	'Slovakia',	'SK',	'SVK',	703,	'SKK',	'Slovak Koruna',	'Sk',	'SK.png',	421),
(194,	'Slovenia',	'SI',	'SVN',	705,	'EUR',	'Euro',	'&euro;',	'SI.png',	386),
(195,	'Solomon Islands',	'SB',	'SLB',	90,	'SBD',	'Solomon Islands Dollar',	'$',	'SB.png',	677),
(196,	'Somalia',	'SO',	'SOM',	706,	'SOS',	'Somalian Shilling',	'Sh',	'SO.png',	252),
(197,	'South Africa',	'ZA',	'ZAF',	710,	'ZAR',	'South African Rand',	'R',	'ZA.png',	27),
(198,	'South Georgia and the South Sandwich Islands',	'GS',	'SGS',	239,	'GBP',	'Pound sterling',	'£',	'GS.png',	500),
(199,	'South Korea',	'KR',	'KOR',	410,	'KRW',	'South Korean Won',	'₩',	'KR.png',	82),
(200,	'Spain',	'ES',	'ESP',	724,	'EUR',	'Euro',	'&euro;',	'ES.png',	34),
(201,	'Sri Lanka',	'LK',	'LKA',	144,	'LKR',	'Sri Lankan Rupee',	'රු',	'LK.png',	94),
(202,	'Sudan',	'SD',	'SDN',	736,	'SDD',	'Sudanese pound',	' ج.س',	'SD.png',	249),
(203,	'Suriname',	'SR',	'SUR',	740,	'SRD',	'Surinamese Dollar',	'$',	'SR.png',	597),
(204,	'Svalbard and Jan Mayen',	'SJ',	'SJM',	744,	'NOK',	'Svalbard and Jan Mayen Krone',	'kr',	'SJ.png',	47),
(205,	'Swaziland',	'SZ',	'SWZ',	748,	'SZL',	'Swazi lilangeni',	'L',	'SZ.png',	268),
(206,	'Sweden',	'SE',	'SWE',	752,	'SEK',	'Swedish krona',	'kr',	'SE.png',	46),
(207,	'Switzerland',	'CH',	'CHE',	756,	'CHF',	'Swiss franc',	'CHF',	'CH.png',	41),
(208,	'Syria',	'SY',	'SYR',	760,	'SYP',	'Syrian Pound',	'ل.س',	'SY.png',	963),
(209,	'Taiwan',	'TW',	'TWN',	158,	'TWD',	'New Taiwan dollar',	'NT$',	'TW.png',	886),
(210,	'Tajikistan',	'TJ',	'TJK',	762,	'TJS',	'Tajikistani Somoni',	'ЅМ',	'TJ.png',	992),
(211,	'Tanzania',	'TZ',	'TZA',	834,	'TZS',	'Tanzanian Shilling',	'Sh',	'TZ.png',	255),
(212,	'Thailand',	'TH',	'THA',	764,	'THB',	'Thai Bhat',	'฿',	'TH.png',	66),
(213,	'Togo',	'TG',	'TGO',	768,	'XOF',	'West African CFA franc',	'Fr',	'TG.png',	228),
(214,	'Tokelau',	'TK',	'TKL',	772,	'NZD',	'New Zealand dollar',	'$',	'TK.png',	690),
(215,	'Tonga',	'TO',	'TON',	776,	'TOP',	'Tongan Pa&#39;anga',	'T$',	'TO.png',	676),
(216,	'Trinidad and Tobago',	'TT',	'TTO',	780,	'TTD',	'Trinidad and Tobago Dollar',	'$',	'TT.png',	1),
(217,	'Tunisia',	'TN',	'TUN',	788,	'TND',	'Tunisian Dinar',	'د.ت',	'TN.png',	216),
(218,	'Turkey',	'TR',	'TUR',	792,	'TRY',	'Turkish Lira',	'₺',	'TR.png',	90),
(219,	'Turkmenistan',	'TM',	'TKM',	795,	'TMM',	'Turkmenistan manat',	'T',	'TM.png',	7370),
(220,	'Turks and Caicos Islands',	'TC',	'TCA',	796,	'USD',	' United States Dollar',	'$',	'TC.png',	1),
(221,	'Tuvalu',	'TV',	'TUV',	798,	'AUD',	'Australian dollar',	'$',	'TV.png',	688),
(222,	'U.S. Virgin Islands',	'VI',	'VIR',	850,	'USD',	' United States Dollar',	'$',	'VI.png',	1),
(223,	'Uganda',	'UG',	'UGA',	800,	'UGX',	'Ugandan Shilling',	'UGX',	'UG.png',	256),
(224,	'Ukraine',	'UA',	'UKR',	804,	'UAH',	'Ukrainian Hryvnia',	'₴',	'UA.png',	380),
(225,	'United Arab Emirates',	'AE',	'ARE',	784,	'AED',	'United Arab Emirates Dirham',	'د.إ',	'AE.png',	971),
(226,	'United Kingdom',	'GB',	'GBR',	826,	'GBP',	'Pound sterling',	'£',	'GB.png',	44),
(227,	'United States',	'US',	'USA',	840,	'USD',	'United States Dollar',	'$',	'US.png',	1),
(228,	'United States Minor Outlying Islands',	'UM',	'UMI',	581,	'USD',	'United States Dollar',	'$',	'UM.png',	1),
(229,	'Uruguay',	'UY',	'URY',	858,	'UYU',	'Uruguayan Peso',	'$',	'UY.png',	598),
(230,	'Uzbekistan',	'UZ',	'UZB',	860,	'UZS',	'Uzbekistani Som',	'UZS',	'UZ.png',	998),
(231,	'Vanuatu',	'VU',	'VUT',	548,	'VUV',	'Vanuatu vatu',	'Vt',	'VU.png',	678),
(232,	'Vatican',	'VA',	'VAT',	336,	'EUR',	'Euro',	'&euro;',	'VA.png',	39),
(233,	'Venezuela',	'VE',	'VEN',	862,	'VEF',	'Venezuelan Bolivar',	'Bs F',	'VE.png',	58),
(234,	'Vietnam',	'VN',	'VNM',	704,	'VND',	'Vietnamese Dong',	'₫',	'VN.png',	84),
(235,	'Wallis and Futuna',	'WF',	'WLF',	876,	'XPF',	'CFP franc',	'Fr',	'WF.png',	681),
(236,	'Western Sahara',	'EH',	'ESH',	732,	'MAD',	'Dirham',	'&#x62f;.&#x645;.',	'EH.png',	212),
(237,	'Yemen',	'YE',	'YEM',	887,	'YER',	'Yemeni Rial',	'﷼',	'YE.png',	967),
(238,	'Zambia',	'ZM',	'ZMB',	894,	'ZMK',	'Zambian Kwacha',	'ZK',	'ZM.png',	260),
(239,	'Zimbabwe',	'ZW',	'ZWE',	716,	'ZWD',	'Zimbabwean dollar',	'$',	'ZW.png',	263),
(242,	'British Pound',	'GB',	'GBP',	NULL,	'GBP',	'British Pound',	'GBP',	NULL,	44),
(247,	'Bangladesh',	'BD',	'BGD',	NULL,	'BDT',	'Bangladeshi taka',	'৳',	NULL,	880),
(244,	'Armenia',	'AM',	'ARM',	NULL,	'AMD',	'Armenian Dram',	'֏ ',	NULL,	374),
(245,	'Aruba',	'AW',	'ABW',	NULL,	'AWG',	'Aruban florin',	'Afl',	NULL,	297),
(246,	'Azerbaijan',	'AZ',	'AZE',	NULL,	'AZN',	'Azerbaijani manat',	'₼',	NULL,	994),
(248,	'Belarus',	'BY',	'BLR',	NULL,	'BYN',	'Belarusian ruble',	'p',	NULL,	375),
(249,	'Bhutan',	'BT',	'BTN',	NULL,	'BTN',	'Bhutanese ngultrum',	'Nu',	NULL,	975),
(250,	'Bosnia',	'BA',	'BIH',	NULL,	'BAM',	'Bosnia and Herzegovina convertib',	'KM',	NULL,	387),
(251,	'Botswana',	'BW',	'BWA',	NULL,	'BWP',	'Botswana pula',	'P',	NULL,	267),
(252,	'Brazil',	'BR',	'BRA',	NULL,	'BRL',	'Brazilian real',	'R$',	NULL,	55),
(253,	'Bulgaria',	'BG',	'BGR',	NULL,	'BGN',	'Bulgarian lev',	'лв',	NULL,	359),
(254,	'Burundi',	'BI',	'BDI',	NULL,	'BIF',	'Burundian franc',	'FBu',	NULL,	257),
(255,	'Cambodia',	'CM',	'CMR',	NULL,	'KHR',	'Cambodian riel',	'៛',	NULL,	855),
(256,	'Cape Verde',	'CV',	'CPV',	NULL,	'CVE',	'Cape Verdean escudo',	' $',	NULL,	238),
(257,	'Central African Republic',	'CF',	'CAF',	NULL,	'XAF',	'Central African CFA franc',	'FCFA',	NULL,	236),
(258,	'French Polynesia',	'PF',	'PYF',	NULL,	'XPF',	'CFP franc',	'CFP',	NULL,	689),
(259,	'Chile',	'CL',	'CHL',	NULL,	'CLP',	'Chilean peso',	' $',	NULL,	56),
(261,	'China',	'CN',	'CHN',	NULL,	'CNY',	'Renminbi',	'元',	NULL,	86),
(262,	'China',	'CN',	'CHN',	NULL,	'CNY',	'Renminbi',	'元',	NULL,	86),
(263,	'Colombia',	'CO',	'COL',	NULL,	'COP',	'Colombian peso',	'$',	NULL,	57),
(264,	'Comoros',	'KM',	'COM',	NULL,	'KMF',	'Comorian franc',	'CF',	NULL,	269),
(265,	'Democratic Republic of the Congo',	'CG',	'COG',	NULL,	'CDF',	'Congolese franc',	'FC',	NULL,	242),
(266,	'Costa Rica',	'CR',	'CRI',	NULL,	'CRC',	'Costa Rican colón',	'₡',	NULL,	506),
(267,	'Croatia',	'HR',	'HRV',	NULL,	'HRK',	'Croatian kuna',	'kn',	NULL,	385),
(268,	'Cuba',	'CU',	'CUB',	NULL,	'CUC',	'Cuban convertible peso',	'$',	NULL,	53),
(269,	'Denmark',	'DK',	'DNK',	NULL,	'DKK',	'Danish krone',	'kr',	NULL,	45),
(270,	'Djibouti',	'DJ',	'DJI',	NULL,	'DJF',	'Djiboutian franc',	'Fdj',	NULL,	253),
(272,	'Eritrea',	'ER',	'ERI',	NULL,	'ERN',	'Eritrean nakfa',	'ናቕፋ',	NULL,	291),
(273,	'Estonia',	'EE',	'EST',	NULL,	'EEK',	'Estonian kroon',	'kr',	NULL,	372),
(275,	'Austria',	'AT',	'AUT',	NULL,	'EUR',	'Euro',	'€',	NULL,	43),
(276,	'Falkland Islands',	'FK',	'FLK',	NULL,	'FKP',	'Falkland Islands pound',	'£',	NULL,	500),
(280,	'Itali',	'IT',	'ITA',	NULL,	'ITL',	'Italian lira',	'₤',	NULL,	39),
(281,	'Mozambique',	'MZ',	'MOZ',	NULL,	'MZN',	'Mozambican metical',	'MT',	NULL,	258),
(282,	'Sudan',	'SD',	'SDN',	NULL,	'SDG',	'Sudanese pound',	'SD',	NULL,	249),
(283,	'Turkmenistan',	'TM',	'TMT',	NULL,	'TMT',	'Turkmenistan manat',	'T',	NULL,	993),
(284,	'Vanuatu',	'VU',	'VUT',	NULL,	'VUV',	'Vanuatu vatu',	'VT',	NULL,	678);";

	if ( version_compare( $previous_version, $db_version_countries, '<' ) ) {
		$query = "DELETE FROM $st_countries";
		$wpdb->get_results( $query );
	}
	$query = "SELECT * FROM $st_countries";
    $countryData = $wpdb->get_results( $query );
	
	if ( count( $countryData ) == 0 ) {
		$wpdb->query( $st_countries_data );
	}

	$st_payments_table = "CREATE TABLE `$st_payments` (
	  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
	  `reference` varchar(20) COLLATE $collate NOT NULL,
	  `user_id` bigint(20) NOT NULL,
	  `booking_id` bigint(20) NOT NULL,
	  `payment_type` varchar(120) COLLATE $collate DEFAULT NULL,
	  `basic_amount` float(10,2) DEFAULT NULL,
	  `amount_payable` float(10,2) NOT NULL DEFAULT '0.00',
	  `amount_paid` float(10,2) NOT NULL DEFAULT '0.00',
	  `amount_paid_transaction` float(10,2) DEFAULT NULL COMMENT 'This is the original amount paid with transaction.',
	  `discount_amount` float(10,2) NOT NULL DEFAULT '0.00',
	  `tax_amount` float(10,2) NOT NULL DEFAULT '0.00',
	  `surcharges_amount` float(10,2) DEFAULT '0.00',
	  `amount_details` text COLLATE $collate,
	  `payment_method` varchar(120) COLLATE $collate NOT NULL,
	  `payment_status` varchar(120) DEFAULT 'pending' COMMENT 'success,failed,cancelled,pending,refunded'  COLLATE $collate NOT NULL,
	  `transaction_status` varchar(50) COLLATE $collate DEFAULT NULL,
	  `transaction_reference` varchar(50) COLLATE $collate DEFAULT NULL,
	  `datetime` datetime NOT NULL,
	  `payment_status_updated` datetime DEFAULT NULL,
	  `gateway_data` text COLLATE $collate,
	  PRIMARY KEY (`ID`),
	  KEY `booking_id_fk` (`booking_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collate;";
	dbDelta( $st_payments_table );
	
	$previous_version = get_option( $st_payments . '_db_version' );
	update_option( $st_payments . '_db_version', $db_version );
	
	$st_request_callback_table = "CREATE TABLE `$st_request_callback` (
	  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
	  `name` varchar(50) COLLATE $collate NOT NULL,
	  `phone` varchar(20) COLLATE $collate NOT NULL,
	  `email` varchar(256) COLLATE $collate DEFAULT NULL,
	  `date_time` datetime NOT NULL,
	  `is_read` tinyint(4) NOT NULL DEFAULT '0',
	  `ip_address` varchar(20) COLLATE $collate DEFAULT NULL,
	  `user_agent` varchar(256) COLLATE $collate DEFAULT NULL,
	  PRIMARY KEY (`ID`)
	) ENGINE=MyISAM DEFAULT CHARSET=$charset COLLATE=$collate;";
	dbDelta( $st_request_callback_table );
	update_option( $st_request_callback . '_db_version', $db_version );

	$st_user_support_table = "CREATE TABLE `$st_user_support` (
	  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
	  `user_id` int(11) NOT NULL,
	  `first_name` varchar(256) COLLATE $collate NOT NULL,
	  `last_name` varchar(256) COLLATE $collate DEFAULT NULL,
	  `mobile_phonecode` varchar(10) COLLATE $collate DEFAULT NULL,
	  `mobile` varchar(15) COLLATE $collate DEFAULT NULL,
	  `email` varbinary(256) DEFAULT NULL,
	  `subject` varchar(500) COLLATE $collate NOT NULL,
	  `message` text COLLATE $collate NOT NULL,
	  `date` datetime NOT NULL,
	  `last_updated` datetime NOT NULL,
	  `status` varchar(150) DEFAULT 'new' COMMENT 'new,solved,cancelled' COLLATE $collate NOT NULL,
	  `admin_comments` text COLLATE $collate NOT NULL,
	  PRIMARY KEY (`ID`)
	) ENGINE=MyISAM DEFAULT CHARSET=$charset COLLATE=$collate;";
	dbDelta( $st_user_support_table );
	update_option( $st_user_support . '_db_version', $db_version );
	
	/**
	 * @since 2.0.8
	 */
	$st_coupons_history = $wpdb->prefix . 'st_coupons_history';
	$st_coupons_history_table = "CREATE TABLE `$st_coupons_history` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `coupon_code` varchar(50) COLLATE $collate NOT NULL,
	  `user_id` int(11) NOT NULL,
	  `ip_address` varchar(50) NOT NULL,
	  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=$charset COLLATE=$collate;";
	dbDelta( $st_coupons_history_table );
	update_option( $st_coupons_history . '_db_version', $db_version );
	
	update_option( 'simontaxi_db_install', 'yes' );
}

function simontaxi_install_pages_new() {
	global $user_ID, $simontaxi_pages;
	$current_options = get_option( 'simontaxi_pages', array() );
	$installed_pages = array();
	
	$default_pages = simontaxi_default_pages();
	if ( ! empty( $default_pages ) ) {
		foreach( $default_pages as $key => $simon_page ) {
			$is_installed = array_key_exists( $key, $current_options ) ? get_post( $simon_page['name'] ) : false;
			if ( empty( $is_installed ) ) {
				if ( get_page_by_title( $simon_page['name'] ) == NULL) {
					$is_installed = false;
				} elseif ( ! empty( $simon_page['slug'] ) && get_page_by_path( $simon_page['slug'] ) == NULL) {
					$is_installed = false;
				} else {
					$is_installed = true;
				}
			}
			if ( empty( $is_installed ) ) {
				$new_post = array(
					'post_title' => $simon_page['name'],
					'post_status' => 'publish',
					'post_date' => date( 'Y-m-d H:i:s' ),
					'post_author' => $user_ID,
					'post_type' => $simon_page['type'],
				);
				if ( ! empty( $simon_page['shortcode'] ) ) {
					$new_post['post_content'] = '[' . $simon_page['shortcode'] . ']';
				} else {
					$new_post['post_content'] = $simon_page['desc'];
				}
				$post_id = wp_insert_post( $new_post );
				if ( $post_id > 0 ) {	
					if ( ! empty( $simon_page['template'] ) ) {
						if ( ! add_post_meta( $post_id, '_wp_page_template', $simon_page['template'], true ) ) {
						   update_post_meta( $post_id, '_wp_page_template', $simon_page['template'] );
						}
					} else {
						if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php', true ) ) {
						   update_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php' );
						}
					}
					
				}
				$installed_pages[ $key ] = $post_id;
			} else {
				$existing_post = get_page_by_title( $simon_page['name'] );
				if ( ! empty( $existing_post ) ) {
					$installed_pages[ $key ] = $existing_post->ID;
				}
			}
		}
	}
	if ( ! empty( $installed_pages ) ) {
		$merged = array_merge( $current_options, $installed_pages );
		update_option( 'simontaxi_pages', $merged );
		$simontaxi_pages = $merged;
	}
}

function simontaxi_install_pages() {
	global $user_ID;
	if ( get_page_by_title( 'Sign In' ) == NULL) {
		$new_post = array(
			'post_title' => 'Sign In',
			'post_content' => '[simontaxi_signin]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
		if ( $post_id > 0 ) {
			/**
			 * Let us add OR update template meta filed
			*/
			if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php', true ) ) {
			   update_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php' );
			}
		}
	}

	if ( get_page_by_title( 'Registration' ) == NULL) {
		$new_post = array(
			'post_title' => 'Registration',
			'post_content' => '[simontaxi_registration]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
		if ( $post_id > 0 ) {
			/**
			 * Let us add OR update template meta filed
			*/
			if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php', true ) ) {
			   update_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php' );
			}
		}
	}

	if ( get_page_by_title( 'forgotpassword' ) == NULL) {
		$new_post = array(
			'post_title' => 'forgotpassword',
			'post_content' => '[simontaxi_forgotpassword]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
		if ( $post_id > 0 ) {
			/**
			 * Let us add OR update template meta filed
			*/
			if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php', true ) ) {
			   update_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php' );
			}
		}
	}

	if ( get_page_by_title( 'resetpassword' ) == NULL) {
		$new_post = array(
			'post_title' => 'resetpassword',
			'post_content' => '[simontaxi_resetpassword]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
		if ( $post_id > 0 ) {
			/**
			 * Let us add OR update template meta filed
			*/
			if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php', true ) ) {
			   update_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php' );
			}
		}
	}

	if ( get_page_by_title( 'Pick Locations' ) == NULL) {
		$new_post = array(
			'post_title' => 'Pick Locations',
			'post_content' => '[simontaxi_booking_step1]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
		if ( $post_id > 0 ) {
			/**
			 * Let us add OR update template meta filed
			*/
			if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-fullwidth.php', true ) ) {
			   update_post_meta( $post_id, '_wp_page_template', 'templates/template-fullwidth.php' );
			}
		}
	}

	if ( get_page_by_title( 'Select Vehicle' ) == NULL) {
		$new_post = array(
			'post_title' => 'Select Vehicle',
			'post_content' => '[simontaxi_booking_step2]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
		if ( $post_id > 0 ) {
			/**
			 * Let us add OR update template meta filed
			*/
			if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-fullwidth.php', true ) ) {
			   update_post_meta( $post_id, '_wp_page_template', 'templates/template-fullwidth.php' );
			}
		}
	}

	if ( get_page_by_title( 'Confirm Booking' ) == NULL) {
		$new_post = array(
			'post_title' => 'Confirm Booking',
			'post_content' => '[simontaxi_booking_step3]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
		if ( $post_id > 0 ) {
			/**
			 * Let us add OR update template meta filed
			*/
			if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-fullwidth.php', true ) ) {
			   update_post_meta( $post_id, '_wp_page_template', 'templates/template-fullwidth.php' );
			}
		}
	}

	if ( get_page_by_title( 'Select Payment Method' ) == NULL) {
		$new_post = array(
			'post_title' => 'Select Payment Method',
			'post_content' => '[simontaxi_booking_step4]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
		if ( $post_id > 0 ) {
			/**
			 * Let us add OR update template meta filed
			*/
			if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-fullwidth.php', true ) ) {
			   update_post_meta( $post_id, '_wp_page_template', 'templates/template-fullwidth.php' );
			}
		}
	}

	if ( get_page_by_title( 'Proceed to Pay' ) == NULL) {
		$new_post = array(
			'post_title' => 'Proceed to Pay',
			'post_content' => '[simontaxi_proceed_to_pay]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
		if ( $post_id > 0 ) {
			/**
			 * Let us add OR update template meta filed
			*/
			if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-fullwidth.php', true ) ) {
			   update_post_meta( $post_id, '_wp_page_template', 'templates/template-fullwidth.php' );
			}
		}
	}

	if ( get_page_by_title( 'Payment success' ) == NULL) {
		$new_post = array(
			'post_title' => 'Payment success',
			'post_content' => '[simontaxi_payment_success]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
		if ( $post_id > 0 ) {
			/**
			 * Let us add OR update template meta filed
			*/
			if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-fullwidth.php', true ) ) {
			   update_post_meta( $post_id, '_wp_page_template', 'templates/template-fullwidth.php' );
			}
		}
	}
	/**
	 * @since 2.0.4
	 */
	/*
	if ( get_page_by_title( 'Payment Failed' ) == NULL) {
		$new_post = array(
			'post_title' => 'Payment Failed',
			'post_content' => '[simontaxi_payment_failed]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
		if ( $post_id > 0 ) {
			
			if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-fullwidth.php', true ) ) {
			   update_post_meta( $post_id, '_wp_page_template', 'templates/template-fullwidth.php' );
			}
		}
	}
	*/

	if ( get_page_by_title( 'Payment Final' ) == NULL) {
		$new_post = array(
			'post_title' => 'Payment Final',
			'post_content' => '[simontaxi_payment_final]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
		if ( $post_id > 0 ) {
			/**
			 * Let us add OR update template meta filed
			*/
			if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-fullwidth.php', true ) ) {
			   update_post_meta( $post_id, '_wp_page_template', 'templates/template-fullwidth.php' );
			}
		}
	}

	if ( get_page_by_title( 'User Bookings' ) == NULL) {
		$new_post = array(
			'post_title' => 'User Bookings',
			'post_content' => '[simontaxi_user_bookings]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
		if ( $post_id > 0 ) {
			/**
			 * Let us add OR update template meta filed
			*/
			if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php', true ) ) {
			   update_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php' );
			}
		}
	}
	if ( get_page_by_title( 'user-payments' ) == NULL) {
		$new_post = array(
			'post_title' => 'user-payments',
			'post_content' => '[simontaxi_user_payment_history]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
		if ( $post_id > 0 ) {
			/**
			 * Let us add OR update template meta filed
			*/
			if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php', true ) ) {
			   update_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php' );
			}
		}
	}
	if ( get_page_by_title( 'User Account' ) == NULL) {
		$new_post = array(
			'post_title' => 'User Account',
			'post_content' => '[simontaxi_user_account]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
		if ( $post_id > 0 ) {
			/**
			 * Let us add OR update template meta filed
			*/
			if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php', true ) ) {
			   update_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php' );
			}
		}
	}
	
	if ( get_page_by_title( 'Activate Account' ) == NULL) {
		$new_post = array(
			'post_title' => 'Activate Account',
			'post_content' => '[simontaxi_user_activate_account]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
		if ( $post_id > 0 ) {
			/**
			 * Let us add OR update template meta filed
			*/
			if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php', true ) ) {
			   update_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php' );
			}
		}
	}
	
	 if ( get_page_by_title( 'User Billing Address' ) == NULL) {
		$new_post = array(
			'post_title' => 'User Billing Address',
			'post_content' => '[simontaxi_user_billing_address]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
		if ( $post_id > 0 ) {
			/**
			 * Let us add OR update template meta filed
			*/
			if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php', true ) ) {
			   update_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php' );
			}
		}
	}
	if ( get_page_by_title( 'User Support' ) == NULL) {
		$new_post = array(
			'post_title' => 'User Support',
			'post_content' => '[simontaxi_user_support]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
		if ( $post_id > 0 ) {
			/**
			 * Let us add OR update template meta filed
			*/
			if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php', true ) ) {
			   update_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php' );
			}
		}
	}

	if ( get_page_by_title( 'Clear Selections' ) == NULL) {
		$new_post = array(
			'post_title' => 'Clear Selections',
			'post_content' => '[simontaxi_vehicle_clear_selections]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
		if ( $post_id > 0 ) {
			/**
			 * Let us add OR update template meta filed
			*/
			if ( ! add_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php', true ) ) {
			   update_post_meta( $post_id, '_wp_page_template', 'templates/template-simonpage.php' );
			}
		}
	}
}

/**
 * Common function to check wether sms/email template exists.
 * @param  string $name [template name]
 * @return [bool]       [exists true]
 */
function simontaxi_template_is_exists( $name='', $type = 'emailtemplate' )
{
	if( $name!='' )
	{
		global $wpdb;
		$check = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title='$name' AND post_status='publish' AND post_type = '$type'");
		
		if( $check == NULL) return FALSE;
		else return TRUE;
	}
	else return FALSE;
}

function simontaxi_default_templates() {
	global $user_ID;
	if ( ! simontaxi_template_is_exists( 'new-user' ) ) {

	   $new_user = array(
	  'post_title'    => wp_strip_all_tags( 'new-user' ),
	  'post_content'  => '&nbsp;
<h1>{FIRST_NAME}</h1>
<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
<div class="header" style="background: #f5f5f5; padding: 20px;">
<h1>{BLOG_TITLE}</h1>
<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE}</a></div>
</div>
{new_user_mail_additional_top}
<div class="content" style="padding: 20px;"><center><span style="color: #00ccff;"><strong>Congratulations..! welcome to {BLOG_TITLE}</strong></span></center>
<p style="text-align: center;"><span style="color: #333333;">Thank you for For Registering  with {BLOG_TITLE}</span></p>
<p style="text-align: center;"><span style="color: #333333;">User Name : {USERNAME}</span></p>
<p style="text-align: center;"><span style="color: #333333;">Password : {PASSWORD}</span></p>
<p style="text-align: center;"><span style="color: #333333;">{ACTIVATION_LINK}</span></p>
<p style="text-align: center;"><span style="color: #333333;">Use this coupon code {COUPON_CODE} to get discount on your 1st booking</span></p>
<p style="text-align: center;"><span style="color: #333333;">Thanks</span></p>

</div>
{new_user_mail_additional_bottom}
<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;"><span style="float: right;">Copyright © 2016 {BLOG_TITLE} . All right reserved Inc. </span></div>
</div>
<h1>{FIRST_NAME}</h1>
&nbsp;',
	  'post_status'   => 'publish',
	  'post_author'   => $user_ID,
	  'post_type' => 'emailtemplate'
		);
		wp_insert_post( $new_user);
	}
	
	if ( ! simontaxi_template_is_exists( 'activation-link' ) ) {

	   $new_user = array(
	  'post_title'    => wp_strip_all_tags( 'activation-link' ),
	  'post_content'  => '&nbsp;
<h1>{FIRST_NAME}</h1>
<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
<div class="header" style="background: #f5f5f5; padding: 20px;">
<h1>{BLOG_TITLE}</h1>
<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE}</a></div>
</div>
{activation_link_mail_additional_top}
<div class="content" style="padding: 20px;"><center><span style="color: #00ccff;"><strong>Congratulations..! welcome to {BLOG_TITLE}</strong></span></center>
<p style="text-align: center;"><span style="color: #333333;">Thank you for For your interest with {BLOG_TITLE}</span></p>
<p style="text-align: center;"><span style="color: #333333;">User Name : {USERNAME}</span></p>
<p style="text-align: center;"><span style="color: #333333;">Password : {PASSWORD}</span></p>
<p style="text-align: center;"><span style="color: #333333;">{ACTIVATION_LINK}</span></p>
<p style="text-align: center;"><span style="color: #333333;">Use this coupon code {COUPON_CODE} to get discount on your 1st booking</span></p>
<p style="text-align: center;"><span style="color: #333333;">Thanks</span></p>

</div>
{activation_link_mail_additional_bottom}
<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;"><span style="float: right;">Copyright © 2016 {BLOG_TITLE} . All right reserved Inc. </span></div>
</div>
<h1>{FIRST_NAME}</h1>
&nbsp;',
	  'post_status'   => 'publish',
	  'post_author'   => $user_ID,
	  'post_type' => 'emailtemplate'
		);
		wp_insert_post( $new_user);
	}

	if ( ! simontaxi_template_is_exists( 'resetpassword-mail' ) ) {

	   $new_user = array(
	  'post_title'    => wp_strip_all_tags( 'resetpassword-mail' ),
	  'post_content'  => '<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
							<div class="header" style="background: #f5f5f5; padding: 20px;">
							<h1>{BLOG_TITLE}</h1>
							<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE}</a></div>
							</div>
							{resetpassword_mail_additional_top}
							<div class="content" style="padding: 20px;"><center><span style="color: #00ccff;"><strong>Welcome to {BLOG_TITLE}</strong></span></center>
							<p style="text-align: center;"><span style="color: #333333;">Someone requested that the password be reset for the following account on {BLOG_TITLE}</span></p>
							<p>	{BLOG_LINK} </p>
							<p style="text-align: center;"><span style="color: #333333;">Username or Email</span> {USER_NAME}</p>
							<p style="text-align: center;"><span style="color: #333333;">If this was a mistake, just ignore this email and nothing will happen.</span></p>

							<p style="text-align: center;"><span style="color: #333333;">To reset your password, visit the following address:</span></p>

							<p>{RESET_LINK}</p>

							<p style="text-align: center;"><span style="color: #333333;">Thanks</span></p>

							</div>
							{resetpassword_mail_additional_bottom}
							<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;"><span style="float: right;">Copyright © 2016 {BLOG_TITLE} . All right reserved Inc. </span></div>
							</div>',
	  'post_status'   => 'publish',
	  'post_author'   => $user_ID,
	  'post_type' => 'emailtemplate'
		);
		wp_insert_post( $new_user);
	}

	if( ! simontaxi_template_is_exists( 'booking-success' ) ) {
		
		$booking_success = array(
			'post_title'    => wp_strip_all_tags( 'booking-success' ),
			'post_content'  => '&nbsp;
			<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
			<div class="header" style="background: #f5f5f5; padding: 20px;">
			<h1>{BLOG_TITLE}</h1>
			<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE} </a></div>
			</div>
			<h1><span style="color: #ff6600;">Your Booking Details</span></h1>
			<div class="">
			{booking_success_mail_additional_top}
			<table class="booking-status-update">
			<tbody>
			<tr>
			<td style="text-align: justify;" width="20%">
			<blockquote><span style="color: #993366;">Booking Reference</span></blockquote>
			</td>
			<td style="text-align: justify;">
			<blockquote><span style="color: #993366;">:{INVOICE}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Name</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{NAME}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Mobile</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{MOBILE}</span></blockquote>
			</td>
			</tr>

			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Email</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{EMAIL}</span></blockquote>
			</td>
			</tr>

			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">From</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{FROM}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">TO</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{TO}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Journy Date</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{JOURNY_DATE}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Journy Time</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{JOURNY_TIME}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Jounry Type</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{JOURNY_TYPE}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Amount Payable</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{AMOUNT}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Paid</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAID}</span></blockquote>
							</td>
							</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Payment Status</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{PAYMENT_STATUS}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Payment Method</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{PAYMENT_METHOD}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Flight Number</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{FLIGHT_NUMBER}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Number of Passengers</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{NO_OF_PASSENGERS}</span></blockquote>
			</td>
			</tr>
			<tr style="text-align: justify;">
			<td width="20%">
			<blockquote><span style="color: #993366;">Special Instructions</span></blockquote>
			</td>
			<td>
			<blockquote><span style="color: #993366;">:{SPECIAL_INSTRUCTIONS}</span></blockquote>
			</td>
			</tr>
			</tbody>
			</table>
			{booking_success_mail_additional_bottom}
			</div>
			<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;"><span style="float: center;">Copyright © 2018 {BLOG_TITLE} All right reserved Inc. </span></div>
			</div>
			&nbsp;',
			'post_status'   => 'publish',
			'post_author'   => $user_ID,
			'post_type' => 'emailtemplate',
		);
		wp_insert_post( $booking_success );
	}

	if( ! simontaxi_template_is_exists( 'booking-status' ) ){
		$booking_status = array(
		  'post_title'    => wp_strip_all_tags( 'booking-status' ),
		  'post_content'  =>'&nbsp;
							<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
							<div class="header" style="background: #f5f5f5; padding: 20px;">
							<h1><span style="color: #0000ff;">{BLOG_TITLE}</span></h1>
							<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE} </a></div>
							</div>
							<h1 style="text-align: center;"><span style="color: #ff6600;"><strong>Booking Status updated</strong></span></h1>
							
							<h2 style="text-align: center;"><span style="color: #0000ff;">Booking Details</span></h2>
							<div class="">
							{booking_status_mail_additional_top}
							<table class="booking-status-update" style="height: 458px;" width="1266">
							<tbody>
							<tr>
							<td width="20%">Reference</td>
							<td>:<span style="color: #ff0000;">{BOOKING_REF}</span></td>
							</tr>
							<tr>
							<td width="20%">Journey Type</td>
							<td>:{JOURNEY_TYPE}</td>
							</tr>
							<tr>
							<td width="20%">From</td>
							<td>:{PICKUP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">To</td>
							<td>: {DROP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Date</td>
							<td>: {PICKUP_DATE}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Time</td>
							<td>: {PICKUP_TIME}</td>
							</tr>
							<tr>
							<td width="20%">Name</td>
							<td>: {CONTACT_NAME}</td>
							</tr>
							<tr>
							<td width="20%">Mobile</td>
							<td>: {CONTACT_MOBILE}</td>
							</tr>
							<tr>
							<td width="20%">Email</td>
							<td>: {CONTACT_EMAIL}</td>
							</tr>
							<tr>
							<td width="20%">Current Status:</td>
							<td>:{BOOKING_STATUS}</td>
							</tr>
							<tr>
							
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Payable</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{AMOUNT}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Paid</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAID}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Status</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_STATUS}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Method</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_METHOD}</span></blockquote>
							</td>
							</tr>

							<td width="20%">Status Updated Time:</td>
							<td>:{BOOKING_STATUS_UPDATED}</td>
							</tr>
							
							<tr>
							<td width="20%">Comments:</td>
							<td>:{REASON}</td>
							</tr>
							
							</tbody>
							</table>
							{booking_status_mail_additional_bottom}
							</div>
							<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;">

							<span style="float: right;">Copyright © 2016 <span style="color: #0000ff;">{BLOG_TITLE}</span> All right reserved Inc. </span>

							</div>
							</div>
							&nbsp;',
		  'post_status'   => 'publish',
		  'post_author'   => $user_ID,
		  'post_type' => 'emailtemplate'

		 );
		wp_insert_post( $booking_status);
	}

	if( ! simontaxi_template_is_exists( 'booking-confirmed' ) ){
		$booking_status = array(
		  'post_title'    => wp_strip_all_tags( 'booking-confirmed' ),
		  'post_content'  =>'&nbsp;
							<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
							<div class="header" style="background: #f5f5f5; padding: 20px;">
							<h1><span style="color: #0000ff;">{BLOG_TITLE}</span></h1>
							<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE} </a></div>
							</div>
							<h1 style="text-align: center;"><span style="color: #ff6600;"><strong>Booking Status updated</strong></span></h1>
							
							<h2 style="text-align: center;"><span style="color: #0000ff;">Booking Details</span></h2>
							<div class="">
							{booking_confirmed_mail_additional_top}
							<table class="booking-status-update" style="height: 458px;" width="1266">
							<tbody>
							<tr>
							<td width="20%">Reference</td>
							<td>:<span style="color: #ff0000;">{BOOKING_REF}</span></td>
							</tr>
							<tr>
							<td width="20%">Journey Type</td>
							<td>:{JOURNEY_TYPE}</td>
							</tr>
							<tr>
							<td width="20%">From</td>
							<td>:{PICKUP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">To</td>
							<td>: {DROP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Date</td>
							<td>: {PICKUP_DATE}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Time</td>
							<td>: {PICKUP_TIME}</td>
							</tr>
							<tr>
							<td width="20%">Name</td>
							<td>: {CONTACT_NAME}</td>
							</tr>
							<tr>
							<td width="20%">Mobile</td>
							<td>: {CONTACT_MOBILE}</td>
							</tr>
							<tr>
							<td width="20%">Email</td>
							<td>: {CONTACT_EMAIL}</td>
							</tr>
							<tr>
							<td width="20%">Current Status:</td>
							<td>:{BOOKING_STATUS}</td>
							</tr>
							
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Payable</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{AMOUNT}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Paid</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAID}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Status</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_STATUS}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Method</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_METHOD}</span></blockquote>
							</td>
							</tr>

							<tr>
							<td width="20%">Status Updated Time:</td>
							<td>:{BOOKING_STATUS_UPDATED}</td>
							</tr>
							
							<tr>
							<td width="20%">Comments:</td>
							<td>:{REASON}</td>
							</tr>
							
							</tbody>
							</table>
							{booking_confirmed_mail_additional_bottom}
							</div>
							<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;">

							<span style="float: right;">Copyright © 2016 <span style="color: #0000ff;">{BLOG_TITLE}</span> All right reserved Inc. </span>

							</div>
							</div>
							&nbsp;',
		  'post_status'   => 'publish',
		  'post_author'   => $user_ID,
		  'post_type' => 'emailtemplate'

		 );
		wp_insert_post( $booking_status);
	}

	if( ! simontaxi_template_is_exists( 'booking-cancel' ) ){
		$booking_status = array(
		  'post_title'    => wp_strip_all_tags( 'booking-cancel' ),
		  'post_content'  =>'&nbsp;
							<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
							<div class="header" style="background: #f5f5f5; padding: 20px;">
							<h1><span style="color: #0000ff;">{BLOG_TITLE}</span></h1>
							<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE} </a></div>
							</div>
							<h1 style="text-align: center;"><span style="color: #ff6600;"><strong>Booking Status updated</strong></span></h1>
							
							<h2 style="text-align: center;"><span style="color: #0000ff;">Booking Details</span></h2>
							<div class="">
							{booking_cancel_mail_additional_top}
							<table class="booking-status-update" style="height: 458px;" width="1266">
							<tbody>
							<tr>
							<td width="20%">Reference</td>
							<td>:<span style="color: #ff0000;">{BOOKING_REF}</span></td>
							</tr>
							<tr>
							<td width="20%">Journey Type</td>
							<td>:{JOURNEY_TYPE}</td>
							</tr>
							<tr>
							<td width="20%">From</td>
							<td>:{PICKUP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">To</td>
							<td>: {DROP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Date</td>
							<td>: {PICKUP_DATE}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Time</td>
							<td>: {PICKUP_TIME}</td>
							</tr>
							<tr>
							<td width="20%">Name</td>
							<td>: {CONTACT_NAME}</td>
							</tr>
							<tr>
							<td width="20%">Mobile</td>
							<td>: {CONTACT_MOBILE}</td>
							</tr>
							<tr>
							<td width="20%">Email</td>
							<td>: {CONTACT_EMAIL}</td>
							</tr>
							<tr>
							<td width="20%">Current Status:</td>
							<td>:{BOOKING_STATUS}</td>
							</tr>
							
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Payable</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{AMOUNT}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Paid</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAID}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Status</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_STATUS}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Method</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_METHOD}</span></blockquote>
							</td>
							</tr>

							<tr>
							<td width="20%">Status Updated Time:</td>
							<td>:{BOOKING_STATUS_UPDATED}</td>
							</tr>
							<tr>
							<td width="20%">Comments:</td>
							<td>:{REASON}</td>
							</tr>
							</tbody>
							</table>
							{booking_cancel_mail_additional_bottom}
							</div>
							<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;">

							<span style="float: right;">Copyright © 2016 <span style="color: #0000ff;">{BLOG_TITLE}</span> All right reserved Inc. </span>

							</div>
							</div>
							&nbsp;',
		  'post_status'   => 'publish',
		  'post_author'   => $user_ID,
		  'post_type' => 'emailtemplate'

		 );
		wp_insert_post( $booking_status);
	}

	if( ! simontaxi_template_is_exists( 'payment-status' ) ){
	  $payment_status = array(

	  'post_title'    => wp_strip_all_tags( 'payment-status' ),
	  'post_content'  =>'&nbsp;
					<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
					<div class="header" style="background: #f5f5f5; padding: 20px;">
					<h1><span style="color: #0000ff;">{BLOG_TITLE}</span></h1>
					<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE} </a></div>
					</div>
					<h1 style="text-align: center;"><span style="color: #ff6600;"><strong>Payment Status updated</strong></span></h1>
					
					<h2 style="text-align: center;"><span style="color: #0000ff;">Payment Details</span></h2>
					<div class="">
					{payment_status_mail_additional_top}
					<table class="payment-status-update" style="height: 458px;" width="1266">
					<tbody>
					<tr>
					<td width="20%">Reference</td>
					<td>:<span style="color: #ff0000;">{BOOKING_REF}</span></td>
					</tr>
					<tr>
					<td width="20%">Journey Type</td>
					<td>:{JOURNEY_TYPE}</td>
					</tr>
					<tr>
					<td width="20%">From</td>
					<td>:{PICKUP_LOCATION}</td>
					</tr>
					<tr>
					<td width="20%">To</td>
					<td>: {DROP_LOCATION}</td>
					</tr>
					<tr>
					<td width="20%">Pickup Date</td>
					<td>: {PICKUP_DATE}</td>
					</tr>
					<tr>
					<td width="20%">Pickup Time</td>
					<td>: {PICKUP_TIME}</td>
					</tr>
					<tr>
					<td width="20%">Name</td>
					<td>: {CONTACT_NAME}</td>
					</tr>
					<tr>
					<td width="20%">Mobile</td>
					<td>: {CONTACT_MOBILE}</td>
					</tr>
					<tr>
					<td width="20%">Email</td>
					<td>: {CONTACT_EMAIL}</td>
					</tr>
					<tr>
					<td width="20%">Current Status:</td>
					<td>:{PAYMENT_STATUS}</td>
					</tr>
					
					<tr style="text-align: justify;">
					<td width="20%">
					<blockquote><span style="color: #993366;">Amount Payable</span></blockquote>
					</td>
					<td>
					<blockquote><span style="color: #993366;">:{AMOUNT}</span></blockquote>
					</td>
					</tr>
					<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Paid</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAID}</span></blockquote>
							</td>
							</tr>
					<tr style="text-align: justify;">
					<td width="20%">
					<blockquote><span style="color: #993366;">Payment Status</span></blockquote>
					</td>
					<td>
					<blockquote><span style="color: #993366;">:{PAYMENT_STATUS}</span></blockquote>
					</td>
					</tr>
					<tr style="text-align: justify;">
					<td width="20%">
					<blockquote><span style="color: #993366;">Payment Method</span></blockquote>
					</td>
					<td>
					<blockquote><span style="color: #993366;">:{PAYMENT_METHOD}</span></blockquote>
					</td>
					</tr>

					<tr>
					<td width="20%">Status Updated Time:</td>
					<td>:{BOOKING_STATUS_UPDATED}</td>
					</tr>
					
					<tr>
							<td width="20%">Comments:</td>
							<td>:{REASON}</td>
							</tr>
							
					</tbody>
					</table>
					{payment_status_mail_additional_bottom}
					</div>
					<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;">

					<span style="float: right;">Copyright © 2016 <span style="color: #0000ff;">{BLOG_TITLE}</span> All right reserved Inc. </span>

					</div>
					</div>
					&nbsp;',
	  'post_status'   => 'publish',
	  'post_author'   => $user_ID,
	  'post_type' => 'emailtemplate'

		 );
		 wp_insert_post( $payment_status);
	}
	
	/**
	 * @since 2.0.9
	 */
	if( ! simontaxi_template_is_exists( 'ride-start' ) ){
		$booking_status = array(
		  'post_title'    => wp_strip_all_tags( 'ride-start' ),
		  'post_content'  =>'&nbsp;
							<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
							<div class="header" style="background: #f5f5f5; padding: 20px;">
							<h1><span style="color: #0000ff;">{BLOG_TITLE}</span></h1>
							<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE} </a></div>
							</div>
							<h1 style="text-align: center;"><span style="color: #ff6600;"><strong>Your ride start now</strong></span></h1>
							
							<h2 style="text-align: center;"><span style="color: #0000ff;">Booking Details</span></h2>
							<div class="">
							{ride_start_mail_additional_top}
							<table class="booking-status-update" style="height: 458px;" width="1266">
							<tbody>
							<tr>
							<td width="20%">Reference</td>
							<td>:<span style="color: #ff0000;">{BOOKING_REF}</span></td>
							</tr>
							<tr>
							<td width="20%">Journey Type</td>
							<td>:{JOURNEY_TYPE}</td>
							</tr>
							<tr>
							<td width="20%">From</td>
							<td>:{PICKUP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">To</td>
							<td>: {DROP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Date</td>
							<td>: {PICKUP_DATE}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Time</td>
							<td>: {PICKUP_TIME}</td>
							</tr>
							<tr>
							<td width="20%">Name</td>
							<td>: {CONTACT_NAME}</td>
							</tr>
							<tr>
							<td width="20%">Mobile</td>
							<td>: {CONTACT_MOBILE}</td>
							</tr>
							<tr>
							<td width="20%">Email</td>
							<td>: {CONTACT_EMAIL}</td>
							</tr>
							<tr>
							<td width="20%">Current Status:</td>
							<td>:{BOOKING_STATUS}</td>
							</tr>
							
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Payable</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{AMOUNT}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Paid</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAID}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Status</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_STATUS}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Method</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_METHOD}</span></blockquote>
							</td>
							</tr>

							<tr>
							<td width="20%">Status Updated Time:</td>
							<td>:{BOOKING_STATUS_UPDATED}</td>
							</tr>
							
							<tr>
							<td width="20%">Instructions:</td>
							<td>:{REASON}</td>
							</tr>
							
							</tbody>
							</table>
							{ride_start_mail_additional_bottom}
							</div>
							<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;">

							<span style="float: right;">Copyright © 2016 <span style="color: #0000ff;">{BLOG_TITLE}</span> All right reserved Inc. </span>

							</div>
							</div>
							&nbsp;',
		  'post_status'   => 'publish',
		  'post_author'   => $user_ID,
		  'post_type' => 'emailtemplate'

		 );
		wp_insert_post( $booking_status);
	}
	
	if( ! simontaxi_template_is_exists( 'ride-completed' ) ){
		$booking_status = array(
		  'post_title'    => wp_strip_all_tags( 'ride-completed' ),
		  'post_content'  =>'&nbsp;
							<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
							<div class="header" style="background: #f5f5f5; padding: 20px;">
							<h1><span style="color: #0000ff;">{BLOG_TITLE}</span></h1>
							<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE} </a></div>
							</div>
							<h1 style="text-align: center;"><span style="color: #ff6600;"><strong>Congratulations your ride completed.</strong></span></h1>
							
							<h2 style="text-align: center;"><span style="color: #0000ff;">Booking Details</span></h2>
							<div class="">
							{ride_completed_mail_additional_top}
							<table class="booking-status-update" style="height: 458px;" width="1266">
							<tbody>
							<tr>
							<td width="20%">Reference</td>
							<td>:<span style="color: #ff0000;">{BOOKING_REF}</span></td>
							</tr>
							<tr>
							<td width="20%">Journey Type</td>
							<td>:{JOURNEY_TYPE}</td>
							</tr>
							<tr>
							<td width="20%">From</td>
							<td>:{PICKUP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">To</td>
							<td>: {DROP_LOCATION}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Date</td>
							<td>: {PICKUP_DATE}</td>
							</tr>
							<tr>
							<td width="20%">Pickup Time</td>
							<td>: {PICKUP_TIME}</td>
							</tr>
							<tr>
							<td width="20%">Name</td>
							<td>: {CONTACT_NAME}</td>
							</tr>
							<tr>
							<td width="20%">Mobile</td>
							<td>: {CONTACT_MOBILE}</td>
							</tr>
							<tr>
							<td width="20%">Email</td>
							<td>: {CONTACT_EMAIL}</td>
							</tr>
							<tr>
							<td width="20%">Current Status:</td>
							<td>:{BOOKING_STATUS}</td>
							</tr>
							
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Payable</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{AMOUNT}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Amount Paid</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAID}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Status</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_STATUS}</span></blockquote>
							</td>
							</tr>
							<tr style="text-align: justify;">
							<td width="20%">
							<blockquote><span style="color: #993366;">Payment Method</span></blockquote>
							</td>
							<td>
							<blockquote><span style="color: #993366;">:{PAYMENT_METHOD}</span></blockquote>
							</td>
							</tr>

							<tr>
							<td width="20%">Status Updated Time:</td>
							<td>:{BOOKING_STATUS_UPDATED}</td>
							</tr>
							
							<tr>
							<td width="20%">Instructions:</td>
							<td>:{REASON}</td>
							</tr>
							
							</tbody>
							</table>
							{ride_completed_mail_additional_bottom}
							</div>
							<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;">

							<span style="float: right;">Copyright © 2016 <span style="color: #0000ff;">{BLOG_TITLE}</span> All right reserved Inc. </span>

							</div>
							</div>
							&nbsp;',
		  'post_status'   => 'publish',
		  'post_author'   => $user_ID,
		  'post_type' => 'emailtemplate'

		 );
		wp_insert_post( $booking_status);
	}

	if( ! simontaxi_template_is_exists( 'sms-new-user', 'smstemplate' ) ){
		$sms_new_user = array(

		'post_title'    => wp_strip_all_tags( 'sms-new-user' ),
		'post_content'  => '{sms_new_user_additional_top} Thank you for Registering with {BLOG_TITLE} .
							please login to book a cab. {sms_new_user_additional_bottom}',
		'post_status'   => 'publish',
		'post_author'   => $user_ID,
		'post_type' => 'smstemplate'

		);
		wp_insert_post( $sms_new_user);
	}

	if( ! simontaxi_template_is_exists( 'sms-booking-success', 'smstemplate' ) ){
		$sms_booking_success = array(

		'post_title'    => wp_strip_all_tags( 'sms-booking-success' ),
		'post_content'  =>' {sms_booking_success_additional_top} Booking Success!
							Ref ID: {BOOKING_REF}
							From : {FROM}
							To: {TO}
							Amount: {AMOUNT}
							Payment: {PAYMENT_STATUS}
							{DATE} {sms_booking_success_additional_bottom}',
		'post_status'   => 'publish',
		'post_author'   => $user_ID,
		'post_type' => 'smstemplate'

		);
		wp_insert_post( $sms_booking_success);
	}

	if( ! simontaxi_template_is_exists( 'sms-booking-confirmed', 'smstemplate' ) ){
		$sms_booking_success = array(

		'post_title'    => wp_strip_all_tags( 'sms-booking-confirmed' ),
		'post_content'  =>' {sms_booking_confirmed_additional_top} Booking Confirmed!
							Ref ID: {BOOKING_REF}
							From : {FROM}
							To: {TO}
							Date: {PICKUP_DATE}
							Time: {PICKUP_TIME}
							Car Plate: {CAR_PLATE} {sms_booking_confirmed_additional_bottom}',
		'post_status'   => 'publish',
		'post_author'   => $user_ID,
		'post_type' => 'smstemplate'

		);
		wp_insert_post( $sms_booking_success);
	}

	if( ! simontaxi_template_is_exists( 'sms-booking-cancel', 'smstemplate' ) ){
		$sms_booking_success = array(

		'post_title'    => wp_strip_all_tags( 'sms-booking-cancel' ),
		'post_content'  =>' {sms_booking_cancel_additional_top} Booking Cancelled!
							Ref ID: {BOOKING_REF}
							From : {FROM}
							To: {TO}
							Date: {PICKUP_DATE}
							Time: {PICKUP_TIME}
							Reason: {REASON} {sms_booking_cancel_additional_bottom}',
		'post_status'   => 'publish',
		'post_author'   => $user_ID,
		'post_type' => 'smstemplate'

		);
		wp_insert_post( $sms_booking_success);
	}

	if( ! simontaxi_template_is_exists( 'sms-booking-status', 'smstemplate' ) ){
		$sms_booking_status = array(

		'post_title'    => wp_strip_all_tags( 'sms-booking-status' ),
		'post_content'  => '{sms_booking_status_additional_top} Booking Status updated!
							Ref ID: {BOOKING_REF}
							Status:{BOOKING_STATUS_UPDATED}
							{DATE} {sms_booking_status_additional_bottom}',
		'post_status'   => 'publish',
		'post_author'   => $user_ID,
		'post_type' => 'smstemplate'

		);
		wp_insert_post( $sms_booking_status);
	}

	if( ! simontaxi_template_is_exists( 'sms-payment-status', 'smstemplate' ) ){
		$sms_payment_status = array(

		'post_title'    => wp_strip_all_tags( 'sms-payment-status' ),
		'post_content'  => '{sms_payment_status_additional_top} Payment Status Updated!
							Payment ID:{PAYMENT_REF}
							Status: {PAYMENT_STATUS}
							{DATE} {sms_payment_status_additional_bottom}',
		'post_status'   => 'publish',
		'post_author'   => $user_ID,
		'post_type' => 'smstemplate'

		);
		wp_insert_post( $sms_payment_status);
	}
	
	/**
	 * @since 2.0.9
	 */
	if( ! simontaxi_template_is_exists( 'sms-ride-start', 'smstemplate' ) ){
		$sms_booking_success = array(

		'post_title'    => wp_strip_all_tags( 'sms-ride-start' ),
		'post_content'  =>' {sms_ride_start_additional_top} Your Ride Start Now!
							Ref ID: {BOOKING_REF}
							From : {FROM}
							To: {TO}
							Date: {PICKUP_DATE}
							Time: {PICKUP_TIME}
							Car Plate: {CAR_PLATE} {sms_ride_start_additional_bottom}',
		'post_status'   => 'publish',
		'post_author'   => $user_ID,
		'post_type' => 'smstemplate'

		);
		wp_insert_post( $sms_booking_success);
	}
	
	if( ! simontaxi_template_is_exists( 'sms-ride-completed', 'smstemplate' ) ){
		$sms_booking_success = array(

		'post_title'    => wp_strip_all_tags( 'sms-ride-completed' ),
		'post_content'  =>' {sms_ride_completed_additional_top} Congratulations your ride completed!
							Ref ID: {BOOKING_REF}
							From : {FROM}
							To: {TO}
							Date: {PICKUP_DATE}
							Time: {PICKUP_TIME}
							Car Plate: {CAR_PLATE} {sms_ride_completed_additional_bottom}',
		'post_status'   => 'publish',
		'post_author'   => $user_ID,
		'post_type' => 'smstemplate'

		);
		wp_insert_post( $sms_booking_success);
	}
}