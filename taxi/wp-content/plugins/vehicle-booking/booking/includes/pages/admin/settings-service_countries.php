<table <?php if ( $section == 'service_countries' ) { echo 'class="st-table show"';} else { echo 'class="st-table hide"';}?>>
	<tbody>
	<tr valign="top">
		<th class="titledesc" scope="row">
			<label for="service_countries"><?php esc_html_e( 'Service Countries', 'simontaxi' ); ?></label>
		</th>
		<td>
			<?php
			$service_countries = simontaxi_get_option( 'service_countries', array() );
			?>
			<select id="service_countries" name="simontaxi_settings[service_countries][]" title="<?php esc_attr_e( 'Service Countries', 'simontaxi' ); ?>" class="selectpicker" style="width: 25em;" multiple>
				<?php
				$countryList = simontaxi_countries( 'no' );
				if ( ! empty( $countryList ) ) {
					foreach ( $countryList as $code => $name ) {
						?>
						<option value="<?php echo esc_attr( $code ); ?>" <?php if ( in_array( $code, $service_countries ) ) echo 'selected="selected"'; ?>><?php echo esc_attr( $name ); ?> </option>
						<?php
					}
				}
				?>
			</select><br>
			<small><?php esc_html_e( 'This is applicable if you enable the country selection in front end. Settings -> General -> Enable country selection', 'simontaxi' ); ?></small>
		</td>
	</tr>
	</tbody>
</table>