<table class="st-table">
<tbody>

<?php
$available_general_settings = array( 
	'pages' => array( 
		'title' => esc_html__( 'Pages', 'simontaxi' ),
		'path' => SIMONTAXI_PLUGIN_PATH . 'booking/includes/pages/admin/',
	),
	'service_countries' => array( 
		'title' => esc_html__( 'Service Countries', 'simontaxi' ),
		'path' => SIMONTAXI_PLUGIN_PATH . 'booking/includes/pages/admin/',			
	),
);
$available_general_settings = apply_filters( 'simontaxi_available_general_settings', $available_general_settings );
 ?>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="payment_mode"><?php esc_html_e( 'Settings', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php if ( $section == '' ) {
			esc_html_e( 'General', 'simontaxi' );
		} else { ?>
		<a href="<?php echo admin_url( 'edit.php?post_type=vehicle&page=vehicle_settings&tab=general' ); ?>"><?php esc_html_e( 'General', 'simontaxi' ); ?></a>
		<?php } ?>
		<?php
		foreach ( $available_general_settings as $key => $val ) {
			?>
			&nbsp;|&nbsp;
			<?php if ( $section == $key ) {
				echo esc_html( $val['title'], 'simontaxi' );
			} else { ?>
			<a href="<?php echo admin_url( 'edit.php?post_type=vehicle&page=vehicle_settings&tab=general&section='.$key); ?>"><?php echo esc_html( $val['title'], 'simontaxi' ); ?></a>
			<?php
			}
		}
		?>
	</td>
</tr>
</table>

<?php
foreach ( $available_general_settings as $key => $val ) {
	include $val['path'] . 'settings-' . $key . '.php';
}
?>

<table <?php if ( $section == '' ) { echo 'class="st-table show"';} else { echo 'class="st-table hide"';}?>>
<tbody>

<?php do_action( 'simontaxi_additional_generalsettings_before' ); ?>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="simontaxi_purchase_code"><?php esc_html_e( 'Purchase Code', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $purchase_code = simontaxi_get_option( 'simontaxi_purchase_code', '' ); ?>
		<input type="text" id="simontaxi_purchase_code" value="<?php echo esc_attr( $purchase_code ); ?>" name="simontaxi_settings[simontaxi_purchase_code]" title="<?php esc_html_e( 'Purchase Code', 'simontaxi' ); ?>" style="width: 25em;"><?php echo simontaxi_get_help( 'Purchase Code' );?><br>
		<?php $res = simontaxi_validate_envato( $purchase_code ); 
		if ( false === $res ) {
		?>
		<small><?php esc_html_e( 'To receive automatic updates please enter purchase code here' ); ?></small>
		<?php } ?>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="google_api"><?php esc_html_e( 'Google API Key', 'simontaxi' ); ?></label>
	</th>
	<td>
		<input type="text" id="google_api" value="<?php if ( isset ( $google_api)) { echo esc_attr( $google_api ); } else { esc_html_e( '0', 'simontaxi' );}?>" name="simontaxi_settings[google_api]" title="<?php esc_html_e( 'Google API Key', 'simontaxi' ); ?>" style="width: 25em;"><?php echo simontaxi_get_help( 'If the "Places" is google you need to add google API Key. Fore more details visit <a href="https://developers.google.com/places/web-service/get-api-key" target="_blank">Google Places</a>' ); ?>
		<br><small><?php echo 'If the "Places" is google you need to add google API Key. Fore more details visit <a href="https://developers.google.com/places/web-service/get-api-key" target="_blank">Google Places</a>'; ?></small>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="default_title"><?php esc_html_e( 'Default Title', 'simontaxi' ); ?></label>
	</th>
	<td>
		<input type="text" id="default_title" value="<?php echo simontaxi_get_option( 'default_title', esc_html__( 'Vehicle', 'simontaxi' )); ?>" name="simontaxi_settings[default_title]" title="<?php esc_html_e( 'Default Title', 'simontaxi' ); ?>" style="width: 25em;"><?php echo simontaxi_get_help( 'Default item title we are using through out the system. Eg: Vehicle, Cab, Car etc' ); ?>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="default_title_plural"><?php esc_html_e( 'Default Title (Plural)', 'simontaxi' ); ?></label>
	</th>
	<td>
		<input type="text" id="default_title_plural" value="<?php echo simontaxi_get_option( 'default_title_plural', esc_html__( 'Vehicles', 'simontaxi' )); ?>" name="simontaxi_settings[default_title_plural]" title="<?php esc_html_e( 'Default Title (Plural)', 'simontaxi' ); ?>" style="width: 25em;"><?php echo simontaxi_get_help( 'Default item title we are using through out the system. Eg: Vehicles, Cabs, Cars, Busses etc' ); ?>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_country"><?php esc_html_e( 'Pickup Country', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select id="vehicle_country" class="selectpicker" name="simontaxi_settings[vehicle_country]" title="<?php esc_html_e( 'Pickup Country', 'simontaxi' ); ?>" style="width: 25em;" onchange="setMapCenter1(this.value)">
			<!--<option value="0"><?php esc_html_e( 'Not a single country', 'simontaxi' ); ?></option>-->
			<?php
			$countryList = simontaxi_countries( 'no' );
			if ( ! empty( $countryList ) ) {
				foreach ( $countryList as $code => $name ) {
					?>
					<option value="<?php echo esc_attr( $code ); ?>" <?php if ( $vehicle_country == $code ) echo 'selected="selected"'; ?>><?php echo esc_attr( $name ); ?> </option>
					<?php
				}
			}
			?>
		</select>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="enable_admin_maps"><?php esc_html_e( 'Enable admin maps', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $enable_admin_maps = simontaxi_get_option( 'enable_admin_maps', 'yes' ); ?>
		<select id="enable_admin_maps" name="simontaxi_settings[enable_admin_maps]" title="Country" style="width: 25em;">
			<option value="no" <?php if( 'no' == $enable_admin_maps ) echo ' selected'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
			<option value="yes" <?php if( 'yes' == $enable_admin_maps ) echo ' selected'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
		</select><br>
		<small><ul><li><?php esc_html_e( 'If you dont need region selection you can turn off google maps on admin end.', 'simontaxi' ); ?></li>
		<li><?php esc_html_e( 'This may inprove the speed of your site!', 'simontaxi' ); ?></li>
		</ul></small>
	</td>
</tr>

<?php
/**
 * @since 2.0.0
 */
?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_country_region"><?php esc_html_e( 'Pickup Region', 'simontaxi' ); ?></label>
	</th>
	<td><?php esc_html_e( 'From :', 'simontaxi' ); ?>
		<input type="text" id="vehicle_country_region_from" value="<?php echo simontaxi_get_option( 'vehicle_country_region_from','' ); ?>" name="simontaxi_settings[vehicle_country_region_from]" title="<?php esc_html_e( 'Region From', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'NE Lat,SW Lat', 'simontaxi' ); ?>">
		&nbsp;&nbsp;&nbsp;
		<?php esc_html_e( 'To :', 'simontaxi' ); ?>
		<input type="text" id="vehicle_country_region_to" value="<?php echo simontaxi_get_option( 'vehicle_country_region_to','' ); ?>" name="simontaxi_settings[vehicle_country_region_to]" title="<?php esc_html_e( 'Region To', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'NE Lng,SW Lng', 'simontaxi' ); ?>"><?php echo simontaxi_get_help( 'Region' ); ?>
		<br>
		<small><ul><li><?php esc_html_e( 'You need to enter Region starting NE(North East) Lat and SW(South West) Lat here.', 'simontaxi' ); ?></li>
		<li><?php esc_html_e( 'Click on "Draw Rectangle" and select your region on map', 'simontaxi' ); ?></li>
		</ul></small>

	</td>
</tr>

<?php if ( 'yes' === $enable_admin_maps ) { ?>
<tr><td>&nbsp;</td><td>
<table width="100%">
<tr><td width="100%">
<button onclick="drawRec();"><?php esc_html_e( 'Draw Rectangle', 'simontaxi' ); ?></button>&nbsp;|&nbsp;
<button onclick="clearRec();"><?php esc_html_e( 'Clear Rectangle', 'simontaxi' ); ?></button>
<div id="vehicle_country_region_from_map" style="height:300px; width:100%;"></div></td>
</tr>
</table>
</td></tr>
<?php } ?>


<?php
/**
 * @since 2.0.0
*/
?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_country_dropoff"><?php esc_html_e( 'Drop-off Country', 'simontaxi' ); ?></label>
	</th>
	<td>

		<select id="vehicle_country_dropoff" class="selectpicker" name="simontaxi_settings[vehicle_country_dropoff]" title="Country" style="width: 25em;" onchange="setMapCenter2(this.value)">
			<!--<option value="0"><?php esc_html_e( 'Not a single country', 'simontaxi' ); ?></option>-->
			<?php
			$countryList = simontaxi_countries( 'no' );
			if ( $countryList) {
				foreach ( $countryList as $code => $name) {
					?>
					<option value="<?php echo esc_attr( $code ); ?>" <?php if ( $vehicle_country_dropoff == $code) echo 'selected="selected"'; ?>><?php echo esc_attr( $name ); ?> </option>
					<?php
				}
			}
			?>
		</select>
	</td>
</tr>

<?php
/**
 * @since 2.0.0
 */
?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_country_dropoff_region_from"><?php esc_html_e( 'Drop-off Region', 'simontaxi' ); ?></label>
	</th>
	<td><?php esc_html_e( 'From :', 'simontaxi' ); ?>
		<input type="text" id="vehicle_country_dropoff_region_from" value="<?php echo simontaxi_get_option( 'vehicle_country_dropoff_region_from','' ); ?>" name="simontaxi_settings[vehicle_country_dropoff_region_from]" title="<?php esc_html_e( 'Region From', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'NE Lat,SW Lat', 'simontaxi' ); ?>">
		&nbsp;&nbsp;&nbsp;
		<?php esc_html_e( 'To :', 'simontaxi' ); ?>
		<input type="text" id="vehicle_country_dropoff_region_to" value="<?php echo simontaxi_get_option( 'vehicle_country_dropoff_region_to','' ); ?>" name="simontaxi_settings[vehicle_country_dropoff_region_to]" title="<?php esc_html_e( 'Region To', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'NE Lng,SW Lng', 'simontaxi' ); ?>"><?php echo simontaxi_get_help( 'Region' ); ?><br>
		<small><ul><li><?php esc_html_e( 'You need to enter Region starting NE(North East) Lat and SW(South West) Lat here.', 'simontaxi' ); ?></li>
		<li><?php esc_html_e( 'Click on "Draw Rectangle" and select your region on map', 'simontaxi' ); ?></li>
		</ul></small>
	</td>
</tr>

<?php if ( 'yes' === $enable_admin_maps ) { ?>
<tr><td>&nbsp;</td><td>
<table width="100%">
<tr><td width="100%">
<button onclick="drawRec2();"><?php esc_html_e( 'Draw Rectangle', 'simontaxi' ); ?></button>&nbsp;|&nbsp;
<button onclick="clearRec2();"><?php esc_html_e( 'Clear Rectangle', 'simontaxi' ); ?></button>
<div id="vehicle_country_dropoff_region_from_map" style="height:300px; width:100%;"></div></td>
</tr>
</table>
</td></tr>
<?php } ?>

<tr valign="top"><th colspan="2"><h3><?php esc_html_e( 'Settings only for Point to Point Transfer', 'simontaxi' ); ?></h3></th></tr>
<!-- @since 2.0.9 -->
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="enable_country_selection_p2p"><?php esc_html_e( 'Enable country selection', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $enable_country_selection_p2p = simontaxi_get_option( 'enable_country_selection_p2p', 'no' ); ?>
		<select id="enable_country_selection_p2p" name="simontaxi_settings[enable_country_selection_p2p]" title="Country" style="width: 25em;">
			<option value="no" <?php if( 'no' == $enable_country_selection_p2p ) echo ' selected'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
			<option value="yes" <?php if( 'yes' == $enable_country_selection_p2p ) echo ' selected'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Enable country selection in front end. Applicable only for google places and if the transport operations in multiple countries. If you enable this you need to add service countries "Settings -> General -> Service Countries"' ); ?>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_places"><?php esc_html_e( 'Pickup Places', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select id="vehicle_places" name="simontaxi_settings[vehicle_places]" title="<?php esc_html_e( 'Places', 'simontaxi' ); ?>" style="width: 25em;" onchange="onchangeField('vehicle_places')">
			<option value="googleall" <?php if ( $vehicle_places == 'googleall' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Google Places (All)', 'simontaxi' ); ?></option>
			<option value="googleregions" <?php if ( $vehicle_places == 'googleregions' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Google Places (Regions only)', 'simontaxi' ); ?></option>
			<option value="googlecities" <?php if ( $vehicle_places == 'googlecities' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Google Places (Cities only)', 'simontaxi' ); ?></option>
			<option value="predefined" <?php if ( $vehicle_places == 'predefined' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Pre-defined', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Selection of From and To location is based on this setting.' ); ?>

		&nbsp;&nbsp;
		<?php $vehicle_places_display = simontaxi_get_option( 'vehicle_places_display', 'auto' ); 
		$display = 'display:none;';
		if ( 'predefined' === $vehicle_places ) {
			$display = '';
		}
		?>
		<select id="vehicle_places_display" name="simontaxi_settings[vehicle_places_display]" title="<?php esc_html_e( 'Places display', 'simontaxi' ); ?>" style="width: 25em;<?php echo esc_html( $display ); ?>">
			<option value="auto" <?php if ( $vehicle_places_display == 'auto' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Auto Populate', 'simontaxi' ); ?></option>
			<option value="dropdown" <?php if ( $vehicle_places_display == 'dropdown' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Drop Down', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>

<?php
/**
 * We have received request from clients to apply the places restriction on Pickup and Drop off locations.
 *
 * @since 2.0.0
*/
?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_places_dropoff"><?php esc_html_e( 'Drop-off Places', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select id="vehicle_places_dropoff" name="simontaxi_settings[vehicle_places_dropoff]" title="Places" style="width: 25em;" onchange="onchangeField('vehicle_places_dropoff')">
			<option value="googleall" <?php if ( $vehicle_places_dropoff == 'googleall' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Google Places (All)', 'simontaxi' ); ?></option>
			<option value="googleregions" <?php if ( $vehicle_places_dropoff == 'googleregions' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Google Places (Regions only)', 'simontaxi' ); ?></option>
			<option value="googlecities" <?php if ( $vehicle_places_dropoff == 'googlecities' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Google Places (Cities only)', 'simontaxi' ); ?></option>
			<option value="predefined" <?php if ( $vehicle_places_dropoff == 'predefined' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Pre-defined', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Selection of From and To location is based on this setting.' ); ?>

		&nbsp;&nbsp;
		<?php $vehicle_places_dropoff_display = simontaxi_get_option( 'vehicle_places_dropoff_display', 'auto' ); 
		$display = 'display:none;';
		if ( 'predefined' === $vehicle_places_dropoff ) {
			$display = '';
		}
		?>
		<select id="vehicle_places_dropoff_display" name="simontaxi_settings[vehicle_places_dropoff_display]" title="<?php esc_html_e( 'Places display', 'simontaxi' ); ?>" style="width: 25em;<?php echo $display; ?>">
			<option value="auto" <?php if ( $vehicle_places_dropoff_display == 'auto' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Auto Populate', 'simontaxi' ); ?></option>
			<option value="dropdown" <?php if ( $vehicle_places_dropoff_display == 'dropdown' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Drop Down', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>

<tr valign="top"><th colspan="2"><h3><?php esc_html_e( 'Settings only for Fixed Point Transfer', 'simontaxi' ); ?></h3></th></tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_places_airport"><?php esc_html_e( 'Pickup / Drop-off Places', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $vehicle_places_airport = simontaxi_get_option( 'vehicle_places_airport', 'googleall' ); ?>
		<select id="vehicle_places_airport" name="simontaxi_settings[vehicle_places_airport]" title="<?php esc_html_e( 'Places', 'simontaxi' ); ?>" style="width: 25em;" onchange="onchangeField('vehicle_places_airport')">
			<option value="googleall" <?php if ( $vehicle_places_airport == 'googleall' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Google Places (All)', 'simontaxi' ); ?></option>
			<option value="googleregions" <?php if ( $vehicle_places_airport == 'googleregions' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Google Places (Regions only)', 'simontaxi' ); ?></option>
			<option value="googlecities" <?php if ( $vehicle_places_airport == 'googlecities' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Google Places (Cities only)', 'simontaxi' ); ?></option>
			<option value="predefined" <?php if ( $vehicle_places_airport == 'predefined' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Pre-defined', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Selection of From and To location is based on this setting.' ); ?>

		&nbsp;&nbsp;
		<?php $vehicle_places_airport_display = simontaxi_get_option( 'vehicle_places_airport_display', 'auto' ); 
		$display = 'display:none;';
		if ( 'predefined' === $vehicle_places_airport ) {
			$display = '';
		}
		?>
		<select id="vehicle_places_airport_display" name="simontaxi_settings[vehicle_places_airport_display]" title="<?php esc_html_e( 'Places display', 'simontaxi' ); ?>" style="width: 25em;<?php echo $display; ?>">
			<option value="auto" <?php if ( $vehicle_places_airport_display == 'auto' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Auto Populate', 'simontaxi' ); ?></option>
			<option value="dropdown" <?php if ( $vehicle_places_airport_display == 'dropdown' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Drop Down', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>

<tr valign="top"><th colspan="2"><h3><?php esc_html_e( 'Settings only for Hourly Rental', 'simontaxi' ); ?></h3></th></tr>
<?php /* ?>
<!-- @since 2.0.9 -->
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="enable_country_selection_hourly"><?php esc_html_e( 'Enable country selection', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $enable_country_selection_hourly = simontaxi_get_option( 'enable_country_selection_hourly', 'no' ); ?>
		<select id="enable_country_selection_hourly" name="simontaxi_settings[enable_country_selection_hourly]" title="Country" style="width: 25em;">
			<option value="no" <?php if( 'no' == $enable_country_selection_hourly ) echo ' selected'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
			<option value="yes" <?php if( 'yes' == $enable_country_selection_hourly ) echo ' selected'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Enable country selection in front end. Applicable only for google places and if the transport operations in multiple countries.' ); ?>
	</td>
</tr>
<?php */ ?>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_places_hourly"><?php esc_html_e( 'Pickup / Drop-off Places', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $vehicle_places_hourly = simontaxi_get_option( 'vehicle_places_hourly', 'googleall' ); ?>
		<select id="vehicle_places_hourly" name="simontaxi_settings[vehicle_places_hourly]" title="<?php esc_html_e( 'Places', 'simontaxi' ); ?>" style="width: 25em;" onchange="onchangeField('vehicle_places_hourly')">
			<option value="googleall" <?php if ( $vehicle_places_hourly == 'googleall' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Google Places (All)', 'simontaxi' ); ?></option>
			<option value="googleregions" <?php if ( $vehicle_places_hourly == 'googleregions' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Google Places (Regions only)', 'simontaxi' ); ?></option>
			<option value="googlecities" <?php if ( $vehicle_places_hourly == 'googlecities' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Google Places (Cities only)', 'simontaxi' ); ?></option>
			<option value="predefined" <?php if ( $vehicle_places_hourly == 'predefined' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Pre-defined', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Selection of From and To location is based on this setting.' ); ?>

		&nbsp;&nbsp;
		<?php $vehicle_places_hourly_display = simontaxi_get_option( 'vehicle_places_hourly_display', 'auto' ); 
		$display = 'display:none;';
		if ( 'predefined' === $vehicle_places_hourly ) {
			$display = '';
		}
		?>
		<select id="vehicle_places_hourly_display" name="simontaxi_settings[vehicle_places_hourly_display]" title="<?php esc_html_e( 'Places display', 'simontaxi' ); ?>" style="width: 25em;<?php echo $display; ?>">
			<option value="auto" <?php if ( $vehicle_places_hourly_display == 'auto' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Auto Populate', 'simontaxi' ); ?></option>
			<option value="dropdown" <?php if ( $vehicle_places_hourly_display == 'dropdown' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Drop Down', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>

<?php do_action( 'simontaxi_booking_type_settings' ); ?>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="distance_taken_from"><?php esc_html_e( 'Distance Calculation', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php
		$distance_taken_from = simontaxi_get_option( 'distance_taken_from', 'google' );
		?>
		<select id="distance_taken_from" name="simontaxi_settings[distance_taken_from]" title="<?php esc_html_e( 'Distance Calculation', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="google" <?php if ( $distance_taken_from == 'google' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Google', 'simontaxi' ); ?></option>
			<option value="predefined" <?php if ( $distance_taken_from == 'predefined' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Pre-difined', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Distance Calculation' ); ?>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="user_creation"><?php esc_html_e( 'User Creation', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php
		$user_creation = simontaxi_get_option( 'user_creation', 'no' );
		?>
		<select id="user_creation" name="simontaxi_settings[user_creation]" title="<?php esc_html_e( 'User Creation', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="yes" <?php if ( $user_creation == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $user_creation == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
			<option value="askuser" <?php if ( $user_creation == 'askuser' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Ask user', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'User Creation. This option will visible on customer personal details page.' ); ?>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="restrict_vehicles_count"><?php esc_html_e( 'Apply Number of vehicles Restriction?', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php
		$restrict_vehicles_count = simontaxi_get_option( 'restrict_vehicles_count', 'no' );
		?>
		<select id="restrict_vehicles_count" name="simontaxi_settings[restrict_vehicles_count]" title="Places" style="width: 25em;">
			<option value="yes" <?php if ( $restrict_vehicles_count == 'yes' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( $restrict_vehicles_count == 'no' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Apply Number of vahicles Restriction?' ); ?>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="minimum_distance"><?php esc_html_e( 'Minimum Distance', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php
		$minimum_distance = simontaxi_get_option( 'minimum_distance', 0);
		?>
		<input type="text" id="minimum_distance" value="<?php if ( isset ( $minimum_distance)) { echo esc_attr( $minimum_distance ); } else { esc_html_e( '0', 'simontaxi' );}?>" name="simontaxi_settings[minimum_distance]" title="<?php esc_html_e( 'Minimum Distance', 'simontaxi' ); ?>" style="width: 25em;"><?php echo simontaxi_get_help( 'Specifies if there is any minimum distance limitation for services. 0 means no limitation on minimum distance.' ); ?>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="outofservice"><?php esc_html_e( 'Out of service', 'simontaxi' ); ?></label>
	</th>
	<td>
		<input type="text" id="outofservice" value="<?php if ( isset ( $outofservice)) { echo esc_attr( $outofservice ); } else { esc_html_e( '0', 'simontaxi' );}?>" name="simontaxi_settings[outofservice]" title="<?php esc_html_e( 'Out of service', 'simontaxi' ); ?>" style="width: 25em;"><?php echo simontaxi_get_help( 'Specifies if there is any distance limitation for services. 0 means no limitation on distance.' ); ?>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="vehicle_distance"><?php esc_html_e( 'Distance Type', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select id="vehicle_distance" name="simontaxi_settings[vehicle_distance]" title="Distance" style="width: 25em;">
			<option value="km" <?php if ( $vehicle_distance == 'km' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Metric (Kilometers & Meters)', 'simontaxi' ); ?></option>
			<option value="miles" <?php if ( $vehicle_distance == 'miles' ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Imperial (Miles & Feet)', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="farecalculation_basedon"><?php esc_html_e( 'Fare Calculation Based On', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select id="farecalculation_basedon" name="simontaxi_settings[farecalculation_basedon]" title="<?php esc_html_e( 'Fare Calculation Based On', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="basicfare" <?php if ( isset ( $farecalculation_basedon) && $farecalculation_basedon == 'basicfare' ) { echo 'selected'; }?>><?php esc_html_e( 'Basic Fare', 'simontaxi' ); ?></option>
			<option value="predefined" <?php if ( isset ( $farecalculation_basedon) && $farecalculation_basedon == 'predefined' ) { echo 'selected'; }?>><?php esc_html_e( 'Predefined Charges', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Specifies the fare calculation based on which criteria for user in front end.' ); ?>
	</td>
</tr>

<?php
$pesons_calculation = simontaxi_get_option( 'pesons_calculation', 'no' );
?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="pesons_calculation"><?php esc_html_e( 'Fare Calculation Based On Persons?', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select id="pesons_calculation" name="simontaxi_settings[pesons_calculation]" title="<?php esc_html_e( 'Fare Calculation Based On', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="no" <?php if ( isset ( $pesons_calculation) && $pesons_calculation == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
			<option value="yes" <?php if ( isset ( $pesons_calculation) && $pesons_calculation == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Fare Calculation Based On Persons?' ); ?>
	</td>
</tr>

<?php
$enable_garage_to_garage = simontaxi_get_option( 'enable_garage_to_garage', 'no' );
?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="enable_garage_to_garage"><?php esc_html_e( 'Enable Garage to Garage Fare Calculation?', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select id="enable_garage_to_garage" name="simontaxi_settings[enable_garage_to_garage]" title="<?php esc_html_e( 'Enable Garage to Garage Fare Calculation?', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="no" <?php if ( $enable_garage_to_garage == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
			<option value="yes" <?php if ( $enable_garage_to_garage == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'As per vendors in car rentals regular business standards, the cab meter reading and timings for calculation starts from vendor garage and ends back to vendor garage (i.e. The kilometers and/or hours charged will start from and end at vendor rental office/garage.)

So meter reading is Not considered from customer pickup location or drop location unless the cab vendor is providing Radio Taxi or special fare.' ); ?>
	</td>
</tr>

<?php
$garage_address = simontaxi_get_option( 'garage_address', '' );
?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="garage_address"><?php esc_html_e( 'Garage address', 'simontaxi' ); ?></label>
	</th>
	<td>
		<input type="ytext" id="garage_address"  value="<?php if ( isset ( $garage_address)) { echo $garage_address; } else { echo '';}?>" name="simontaxi_settings[garage_address]" title="<?php esc_html_e( 'Garage address', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'Garage address', 'simontaxi' ); ?>" onClick="initialize(this.id);" onFocus="initialize(this.id);">
	</td>
</tr>

<?php $show_distance_away = simontaxi_get_option( 'show_distance_away', 'yes' ); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="show_distance_away"><?php esc_html_e( 'Show distance away message on vehicle display?', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select id="show_distance_away" name="simontaxi_settings[show_distance_away]" title="<?php esc_html_e( 'Show distance away message on vehicle display?', 'simontaxi' ); ?>" style="width: 25em;">
			<option value="no" <?php if ( $show_distance_away == 'no' ) { echo 'selected'; }?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
			<option value="yes" <?php if ( $show_distance_away == 'yes' ) { echo 'selected'; }?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
		</select><?php echo simontaxi_get_help( 'Show distance away message on vehicle display. So user can know the how far the vehicle is available from his pickup location.' ); ?>
	</td>
</tr>


<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="simontaxi_terms_page"><?php esc_html_e( 'Terms & Conditions on page', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select id="simontaxi_terms_page" name="simontaxi_settings[terms_page]" title="Terms Page" style="width: 25em;">
			<option value="0" <?php if ( isset ( $terms_page) && $terms_page == 0) { echo 'selected'; }?>><?php esc_html_e( 'Please select page', 'simontaxi' ); ?></option>
			<option value="step1" <?php if ( isset ( $terms_page) && $terms_page == 'step1' ) { echo 'selected'; }?>><?php esc_html_e( 'Booking Step 1', 'simontaxi' ); ?></option>
			<option value="step2" <?php if ( isset ( $terms_page) && $terms_page == 'step2' ) { echo 'selected'; }?>><?php esc_html_e( 'Booking Step 2', 'simontaxi' ); ?></option>
			<option value="step3" <?php if ( isset ( $terms_page) && $terms_page == 'step3' ) { echo 'selected'; }?>><?php esc_html_e( 'Booking Step 3', 'simontaxi' ); ?></option>
			<option value="step4" <?php if ( isset ( $terms_page) && $terms_page == 'step4' ) { echo 'selected'; }?>><?php esc_html_e( 'Booking Step 4', 'simontaxi' ); ?></option>
		</select>
		<?php
		$terms_page_id = simontaxi_get_option( 'terms_page_id', 0);
		$pages = get_pages(array( 'post_status' => 'publish' ));
		?>
		<select id="terms_page" name="simontaxi_settings[terms_page_id]" title="Terms Page" style="width: 25em;">
		<option value="0" <?php if ( $terms_page_id == 0) { echo 'selected'; }?>><?php esc_html_e( 'Please select page', 'simontaxi' ); ?></option>
		<?php
		if ( ! empty( $pages) ) {
			foreach( $pages as $page ) {
				?>
				<option value="<?php echo esc_attr( $page->ID); ?>" <?php if ( $terms_page_id == $page->ID) { echo 'selected'; }?>><?php echo esc_attr( $page->post_title); ?></option>
				<?php
			}
		}
		?>
		</select>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="booking_ref_length"><?php esc_html_e( 'Booking Reference String Length', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php $booking_ref_prefix = simontaxi_get_option( 'booking_ref_prefix' ); ?>
		<input type="text" id="booking_ref_prefix" value="<?php if ( isset ( $booking_ref_prefix)) { echo $booking_ref_prefix; }?>" name="simontaxi_settings[booking_ref_prefix]" title="<?php esc_html_e( 'Booking Reference Prefix', 'simontaxi' ); ?>" style="width: 5em;" placeholder="<?php esc_html_e( 'Booking Reference Prefix', 'simontaxi' ); ?>"><?php echo simontaxi_get_help( 'Specifies Booking Reference Prefix.' ); ?>
		
		<input type="number" id="booking_ref_length"  max="50" value="<?php if ( isset ( $booking_ref_length)) { echo $booking_ref_length; } else { esc_html_e( '6', 'simontaxi' );}?>" name="simontaxi_settings[booking_ref_length]" title="<?php esc_html_e( 'Booking Reference String Length', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'Booking Reference String Length', 'simontaxi' ); ?>"><?php echo simontaxi_get_help( 'Specifies string lenght for booking reference in front end.' ); ?>
		
		<?php $booking_ref_postfix = simontaxi_get_option( 'booking_ref_postfix' ); ?>
		<input type="text" id="booking_ref_postfix" value="<?php if ( isset ( $booking_ref_postfix)) { echo $booking_ref_postfix; }?>" name="simontaxi_settings[booking_ref_postfix]" title="<?php esc_html_e( 'Booking Reference Postfix', 'simontaxi' ); ?>" style="width: 5em;" placeholder="<?php esc_html_e( 'Booking Reference Postfix', 'simontaxi' ); ?>"><?php echo simontaxi_get_help( 'Specifies Booking Reference Postfix.' ); ?>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="st_date_format"><?php esc_html_e( 'Date Format', 'simontaxi' ); ?></label>
	</th>
	<td>
		<input type="text" id="st_date_format" value="<?php if ( isset ( $st_date_format)) { echo $st_date_format; } else { esc_html_e( 'd-m-Y', 'simontaxi' );}?>" name="simontaxi_settings[st_date_format]" title="<?php esc_html_e( 'Date Format', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'Date Format', 'simontaxi' ); ?>"><?php echo simontaxi_get_help( 'Date format to display. You can use PHP date format strings as options, for more information<a href="http://php.net/manual/en/function.date.php" target="_blank">date</a>' ); ?>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="st_date_format_with_time"><?php esc_html_e( 'Date Format (With Time)', 'simontaxi' ); ?></label>
	</th>
	<td>
	<?php $st_date_format_with_time = simontaxi_get_option('st_date_format_with_time', 'd-m-Y H:i'); ?>
		<input type="text" id="st_date_format_with_time" value="<?php echo $st_date_format_with_time; ?>" name="simontaxi_settings[st_date_format_with_time]" title="<?php esc_html_e( 'Date Format (With Time)', 'simontaxi' ); ?>" style="width: 25em;" placeholder="<?php esc_html_e( 'Date Format (With Time)', 'simontaxi' ); ?>"><?php echo simontaxi_get_help( 'Date format to display. You can use PHP date format strings as options, for more information<a href="http://php.net/manual/en/function.date.php" target="_blank">date</a>' ); ?>
	</td>
</tr>

<?php $st_date_format_js = simontaxi_get_option( 'st_date_format_js', 'd-m-Y' ); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="st_date_format_js"><?php esc_html_e( 'Date Format (For JavaScript Calendars)', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select id="st_date_format_js" name="simontaxi_settings[st_date_format_js]" title="<?php esc_html_e( 'Date Format (For Calendars)', 'simontaxi' ); ?>">
			<?php /* ?>
			<option value="MM d, yy" <?php if ( $st_date_format_js == 'MM d, yy' ) echo ' selected'; ?>><?php echo date( 'F j, Y' ); ?> (F j, Y)</option>
			<?php */ ?>
			<option value="yy-mm-dd" <?php if ( $st_date_format_js == 'yy-mm-dd' ) echo ' selected'; ?>><?php echo date( 'Y-m-d' ); ?> (Y-m-d)</option>
			<option value="mm-dd-yy" <?php if ( $st_date_format_js == 'mm-dd-yy' ) echo ' selected'; ?>><?php echo date( 'm-d-Y' ); ?> (m-d-Y)</option>
			<option value="dd-mm-yy" <?php if ( $st_date_format_js == 'dd-mm-yy' ) echo ' selected'; ?>><?php echo date( 'd-m-Y' ); ?> (d-m-Y)</option>
			
			<option value="yy/mm/dd" <?php if ( $st_date_format_js == 'yy/mm/dd' ) echo ' selected'; ?>><?php echo date( 'Y/m/d' ); ?> (Y/m/d)</option>
			<option value="mm/dd/yy" <?php if ( $st_date_format_js == 'mm/dd/yy' ) echo ' selected'; ?>><?php echo date( 'm/d/Y' ); ?> (m/d/Y)</option>
			<option value="dd/mm/yy" <?php if ( $st_date_format_js == 'dd/mm/yy' ) echo ' selected'; ?>><?php echo date( 'd/m/Y' ); ?> (d/m/Y)</option>
			
			<option value="yy.mm.dd" <?php if ( $st_date_format_js == 'yy.mm.dd' ) echo ' selected'; ?>><?php echo date( 'Y.m.d' ); ?> (Y.m.d)</option>
			<option value="mm.dd.yy" <?php if ( $st_date_format_js == 'mm.dd.yy' ) echo ' selected'; ?>><?php echo date( 'm.d.Y' ); ?> (m.d.Y)</option>
			<option value="dd.mm.yy" <?php if ( $st_date_format_js == 'dd.mm.yy' ) echo ' selected'; ?>><?php echo date( 'd.m.Y' ); ?> (d.m.Y)</option>
			
		</select>
		<?php echo simontaxi_get_help( 'Date format to display. You can use jQuery date format strings as options, for more information<a href="https://api.jqueryui.com/datepicker/#option-dateFormat" target="_blank">date</a>' ); ?>

	</td>
</tr>

<?php $st_time_dispaly_format = simontaxi_get_option( 'st_time_dispaly_format', 'standard' ); ?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="st_time_dispaly_format"><?php esc_html_e( 'Time Display Format', 'simontaxi' ); ?></label>
	</th>
	<td>
		<select id="st_time_dispaly_format" name="simontaxi_settings[st_time_dispaly_format]" title="<?php esc_html_e( 'Time Display Format', 'simontaxi' ); ?>">
			<option value="standard" <?php if ( $st_time_dispaly_format == 'standard' ) echo ' selected'; ?>><?php esc_html_e( 'Standard (12 Hrs Format)', 'simontaxi' ); ?></option>
			<option value="military" <?php if ( $st_time_dispaly_format == 'military' ) echo ' selected'; ?>><?php esc_html_e( 'Military (24 Hrs Format)', 'simontaxi' ); ?></option>
		</select>
		<?php echo simontaxi_get_help( 'Time Display Format' ); ?>

	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="minimum_notice"><?php esc_html_e( 'Advance booking minimum', 'simontaxi' ); ?></label>
	</th>
	<td>
		<input type="number" id="minimum_notice" value="<?php
		if ( isset ( $minimum_notice)) {
			echo esc_attr( $minimum_notice );
		}
		?>" name="simontaxi_settings[minimum_notice]" title="<?php esc_html_e( 'Advance booking minimum', 'simontaxi' )?>" placeholder="<?php esc_html_e( 'Advance booking minimum', 'simontaxi' )?>" style="width: 25em;" min="0">&nbsp;<?php esc_html_e( 'Day(s)', 'simontaxi' )?>
	</td>
</tr>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="maximum_notice"><?php esc_html_e( 'Advance booking maximum', 'simontaxi' ); ?></label>
	</th>
	<td>
		<input type="number" id="maximum_notice" value="<?php
		if ( isset ( $maximum_notice)) {
			echo esc_attr( $maximum_notice );
		}
		?>" name="simontaxi_settings[maximum_notice]" title="<?php esc_html_e( 'Advance booking maximum', 'simontaxi' )?>" placeholder="<?php esc_html_e( 'Advance booking maximum', 'simontaxi' )?>" style="width: 25em;">&nbsp;
		<select name="simontaxi_settings[maximum_notice_type]">
			<option value="day" <?php if ( isset ( $maximum_notice_type) && $maximum_notice_type == 'day' ) { echo 'selected'; }?>><?php esc_html_e( 'Day(s)', 'simontaxi' )?></option>
			<option value="month" <?php if ( isset ( $maximum_notice_type) && $maximum_notice_type == 'month' ) { echo 'selected'; }?>><?php esc_html_e( 'Month(s)', 'simontaxi' )?></option>
			<option value="year" <?php if ( isset ( $maximum_notice_type) && $maximum_notice_type == 'year' ) { echo 'selected'; }?>><?php esc_html_e( 'Year(s)', 'simontaxi' )?></option>
		</select>
	</td>
</tr>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="records_per_page"><?php esc_html_e( 'Records Per Page', 'simontaxi' ); ?></label>
	</th>
	<td>
		<input type="number" id="records_per_page" value="<?php
		if ( isset ( $records_per_page)) {
			echo esc_attr( $records_per_page );
		}
		?>" name="simontaxi_settings[records_per_page]" title="<?php esc_html_e( 'Records Per Page', 'simontaxi' )?>" placeholder="<?php esc_html_e( 'Records Per Page', 'simontaxi' )?>" style="width: 25em;" min="1">
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="contact_phone"><?php esc_html_e( 'Contact Phone', 'simontaxi' ); ?></label>
	</th>
	<td>
		<input type="text" id="contact_phone" value="<?php echo simontaxi_get_option( 'contact_phone' ); ?>" name="simontaxi_settings[contact_phone]" title="<?php esc_html_e( 'Contact Phone', 'simontaxi' )?>" placeholder="<?php esc_html_e( 'Contact Phone', 'simontaxi' )?>" style="width: 25em;">
	</td>
</tr>

<?php
/**
 * We are receiving request from client to change loaded image, so here is the provision.
 *
 * @since 2.0.0
*/

?>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="main_loader"><?php esc_html_e( 'Main Loader', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php
		$loaders = simontaxi_get_option( 'loaders', array());
		$main_loader = ( isset( $loaders['main_loader'] ) && '' !== $loaders['main_loader'] ) ? $loaders['main_loader'] : '';
		?>
		<input type="text" id="main_loader" name="simontaxi_settings[loaders][main_loader]" title="<?php esc_html_e( 'Main Loader', 'simontaxi' ); ?>" style="width: 25em;" onclick="open_media_uploader_image(this.id)" readonly>
		<input type="hidden" name="simontaxi_settings[loaders][main_loader_remove]" id="main_loader_remove" value="no">
		&nbsp;
		<?php echo simontaxi_get_help( 'This will change the main loaded image.' )?>
		<?php if ( '' !== $main_loader ) { ?><img src="<?php echo $main_loader; ?>" width="50" height="50" title="<?php esc_html_e( 'Main Loader', 'simontaxi' ); ?>" alt="<?php esc_html_e( 'Main Loader', 'simontaxi' ); ?>" id="main_loader_image">&nbsp;<a href="javascript:void(0);" onclick="remove_image( 'main_loader' )"><span class="icon-close" id="main_loader_link"><?php esc_html_e( 'Remove', 'simontaxi' ); ?></span></a><?php } ?>
	</td>
</tr>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="ajax_loader"><?php esc_html_e( 'Ajax Loader', 'simontaxi' ); ?></label>
	</th>
	<td>
		<?php
		$ajax_loader = ( isset( $loaders['ajax_loader'] ) && '' !== $loaders['ajax_loader'] ) ? $loaders['ajax_loader'] : '';
		?>
		<input type="text" id="ajax_loader" name="simontaxi_settings[loaders][ajax_loader]" title="<?php esc_html_e( 'Ajax Loader', 'simontaxi' ); ?>" style="width: 25em;" onclick="open_media_uploader_image(this.id)" readonly>
		<input type="hidden" name="simontaxi_settings[loaders][ajax_loader_remove]" id="ajax_loader_remove" value="no">
		&nbsp;
		<?php echo simontaxi_get_help( 'This will change the Ajax loaded image.' )?>
		<?php if ( '' !== $ajax_loader ) { ?><img src="<?php echo esc_url( $ajax_loader ); ?>" width="50" height="50" title="<?php esc_html_e( 'Ajax Loader', 'simontaxi' ); ?>" alt="<?php esc_html_e( 'Ajax Loader', 'simontaxi' ); ?>" id="ajax_loader_image">&nbsp;<a href="javascript:void(0);" onclick="remove_image( 'ajax_loader' )"><span class="icon-close" id="ajax_loader_link"><?php esc_html_e( 'Remove', 'simontaxi' ); ?></span></a><?php } ?>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="login_menu_item"><?php esc_html_e( 'Login menu item', 'simontaxi' ); ?></label>
	</th>
	<td>                                
		<?php $login_menu_item = simontaxi_get_option( 'login_menu_item', 'yes' ); ?>
		<select name="simontaxi_settings[login_menu_item]">
			<option value="yes" <?php if ( isset ( $login_menu_item ) && $login_menu_item == 'yes' ) { echo 'selected'; } ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			<option value="no" <?php if ( isset ( $login_menu_item ) && $login_menu_item == 'no' ) { echo 'selected'; } ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
		</select>
	</td>
</tr>

<?php do_action( 'simontaxi_additional_generalsettings_middle' ); ?>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="payment_success_message_offline"><?php esc_html_e( 'Payment Success Page Message(Offline)', 'simontaxi' ); ?></label>
	</th>
	<td>                                
		<?php $payment_success_message_offline = simontaxi_get_option( 'payment_success_message_offline', __( 'Thank you for your Booking. <br></br>Booking Success and your booking reference is <b>%s</b>', 'simontaxi' ) ); ?>
		<textarea name="simontaxi_settings[payment_success_message_offline]" id="payment_success_message_offline" placeholder="<?php esc_html_e( 'Payment Success Page Message(Offline)' ); ?>" rows="4" cols="60" class="wp-editor-area"><?php echo $payment_success_message_offline; ?></textarea>
		<br><small>
		<?php esc_html_e( 'Note: You can use HTML tags', 'simontaxi' ); ?><br>
		<?php esc_html_e( 'Eg: Thank you for your Booking. <br></br>Booking Success and your booking reference is <b>%s</b>', 'simontaxi' ); ?></small>
	</td>
</tr>

<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="payment_success_message_online_success"><?php esc_html_e( 'Payment Success Page Message(Online Success)', 'simontaxi' ); ?></label>
	</th>
	<td>                                
		<?php $payment_success_message_online_success = simontaxi_get_option( 'payment_success_message_online_success', __( 'Thank you for your Booking. <br></br>Booking Success and your booking reference is <b>%s</b>', 'simontaxi' ) ); ?>
		<textarea name="simontaxi_settings[payment_success_message_online_success]" id="payment_success_message_online_success" placeholder="<?php esc_html_e( 'Payment Success Page Message(Online Success)' ); ?>" rows="4" cols="60" class="wp-editor-area"><?php echo $payment_success_message_online_success; ?></textarea>
		<br><small>
		<?php esc_html_e( 'Note: You can use HTML tags', 'simontaxi' ); ?><br>
		<?php esc_html_e( 'Eg: Thank you for your Booking. <br></br>Booking Success and your booking reference is <b>%s</b>', 'simontaxi' ); ?></small>
	</td>
</tr>
<tr valign="top">
	<th class="titledesc" scope="row">
		<label for="payment_success_message_online_failed"><?php esc_html_e( 'Payment Success Page Message(Online Failed)', 'simontaxi' ); ?></label>
	</th>
	<td>                                
		<?php $payment_success_message_online_failed = simontaxi_get_option( 'payment_success_message_online_failed', __( 'Thank you for your Booking. <br></br>Booking Success and your booking reference is <b>%s</b>', 'simontaxi' ) ); ?>
		<textarea name="simontaxi_settings[payment_success_message_online_failed]" id="payment_success_message_online_failed" placeholder="<?php esc_html_e( 'Payment Failed Page Message(Online Failed)' ); ?>" rows="4" cols="60" class="wp-editor-area"><?php echo $payment_success_message_online_failed; ?></textarea>
		<br><small>
		<?php esc_html_e( 'Note: You can use HTML tags', 'simontaxi' ); ?><br>
		<?php esc_html_e( 'Eg: Sorry. <br></br>Booking Failed', 'simontaxi' ); ?></small>
	</td>
</tr>

<?php do_action( 'simontaxi_additional_generalsettings_after' ); ?>


</tbody>
</table>