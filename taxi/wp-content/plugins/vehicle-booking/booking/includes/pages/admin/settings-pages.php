<table <?php if ( $section == 'pages' ) { echo 'class="st-table show"';} else { echo 'class="st-table hide"';}?>>
	<tbody>
	<?php
	$simontaxi_default_pages = simontaxi_default_pages();
	if ( ! empty( $simontaxi_default_pages ) ) { 
		foreach( $simontaxi_default_pages as $key => $gpage ) { 
		$title = isset( $gpage['name'] ) ? $gpage['name'] : esc_html__( 'No name', 'simontaxi' );
		// print_r( $gpage );
		?>
			<tr valign="top">
			<th class="titledesc" scope="row">
				<label for="<?php echo $key; ?>"><?php echo sprintf( esc_html__( '"%s" restore?', 'simontaxi' ), $title  ); ?></label>
			</th>
			<td>
				<select id="<?php echo $key; ?>" name="default_pages[<?php echo $key; ?>]" title="<?php echo $title; ?>" style="width: 25em;"><?php echo $title; ?>
					<option value="no"><?php esc_html_e('No', 'simontaxi' ); ?></option>
					<option value="yes"><?php esc_html_e('Yes', 'simontaxi' ); ?></option>
				</select>
			</td>
		</tr>
		<?php }
	?>
	
	<?php }
	?>
	
	</tbody>
</table>