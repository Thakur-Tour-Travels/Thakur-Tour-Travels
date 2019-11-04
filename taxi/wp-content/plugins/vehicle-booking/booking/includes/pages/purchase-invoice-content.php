<div id="invoice-print-div" class="content" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; display: block; max-width: 700px; margin: 0 auto; padding: 0;padding:10px;">
	
	<?php
	$use_address_for_invoice_header = simontaxi_get_option( 'use_address_for_invoice_header', 'yes' );
	if ( 'yes' === $use_address_for_invoice_header ) {
	?>
	<table style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; width: 100%; margin: 0; padding: 0;">
		<tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0;">
		<td width="50%">&nbsp;</td>
		<td>
		<?php
		$loaders = simontaxi_get_option( 'loaders', array());
		$billing_logo = ( isset( $loaders['billing_logo'] ) && '' !== $loaders['billing_logo'] ) ? $loaders['billing_logo'] : '';
		
		$vehicle_billing_company = simontaxi_get_option( 'vehicle_billing_company', '' );
		$vehicle_billing_address = simontaxi_get_option( 'vehicle_billing_address', '' );
		$vehicle_billing_phone = simontaxi_get_option( 'vehicle_billing_phone', '' );
		
		$vehicle_billing_fax = simontaxi_get_option( 'vehicle_billing_fax', '' );
		$vehicle_billing_mobile = simontaxi_get_option( 'vehicle_billing_mobile', '' );
		$vehicle_billing_email = simontaxi_get_option( 'vehicle_billing_email', '' );
		if ( ! empty( $billing_logo ) ) {
			echo '<p><img src="'.$billing_logo.'" with="100" height="80"><p>';
		}
		if ( ! empty( $vehicle_billing_company ) ) {
			echo '<h3>' . $vehicle_billing_company . '</h3>';
		}
		if ( ! empty( $vehicle_billing_address ) ) {
			echo '<p>' . $vehicle_billing_address . '</p>';
		}
		if ( ! empty( $vehicle_billing_phone ) ) {
			echo '<p><b>' . esc_html__( 'Telephone : ' ) . '</b>' . $vehicle_billing_phone . '</p>';
		}
		if ( ! empty( $vehicle_billing_fax ) ) {
			echo '<p><b>' . esc_html__( 'Fax : ' ) . '</b>' . $vehicle_billing_fax . '</p>';
		}
		if ( ! empty( $vehicle_billing_mobile ) ) {
			echo '<p><b>' . esc_html__( 'Mobile : ' ) . '</b>' . $vehicle_billing_mobile . '</p>';
		}
		if ( ! empty( $vehicle_billing_email ) ) {
			echo '<p><b>' . esc_html__( 'Email : ' ) . '</b>' . $vehicle_billing_email . '</p>';
		}
		?>
		</td>
		</tr>
	</table>
	<?php } ?>
	<table style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; width: 100%; margin: 0; padding: 0;">
	  <tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0;">
		<td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0 0 0px; ">


		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tbody >
			  <tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0;">
				<td width="35%" valign="center" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0 0 10px; border-bottom:1px solid #ddd;">
					<span style=''><h2><?php echo get_bloginfo( 'name' ); ?></h2></span>
				</td>
				<td width="35%" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0 0 10px; border-bottom:1px solid #ddd">

				</td>
				<td width="30%" valign="top" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0 0 10px; border-bottom:1px solid #ddd">

							<p><b><u><?php esc_html_e( 'Invoice Date', 'simontaxi' ); ?> </b></u><br><?php echo simontaxi_date_format( $invoice->date ); ?></p>
							<p><strong><b><u><?php esc_html_e( 'Booking Reference', 'simontaxi' ); ?> </u></strong><br><?php echo $invoice->booking_ref; ?></b></p>


				</td>
			  </tr>
			</tbody>
		</table>
	   </td>
	  </tr>
	  <tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0;">
		<td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 15px;">

		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tbody>
			  <tr>
				<td width="70%" align="left" valign="top" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; padding:20px; line-height: 1.6em; margin: 0; padding: 0;vertical-align: top;">

				  <h5><?php esc_html_e( 'CUSTOMER', 'simontaxi' ); ?></h5>

				  <?php
						if ( ! isset( $user_det['billing_firstname'] ) || !isset( $user_det['billing_email'] ) || ! isset( $user_det['billing_phone'] ) ) {
							$contact = ( array ) json_decode( $invoice->booking_contacts);

							$name = isset( $contact['full_name']) ? $contact['full_name'] : '';
							if( $name == '' ) {
								$first_name = isset( $contact['first_name']) ? $contact['first_name'] : '';
								$last_name = isset( $contact['last_name']) ? $contact['last_name'] : '';
								$name = $first_name;
								if ( $last_name != '' ) {
										$name .= ' ' . $last_name;
								}
								if ( $name == '' ) {
									$name = isset( $user_det['first_name']) ? $user_det['first_name'] : '';
									if ( isset( $user_det['last_name']) ) {
										$name .= ' ' . $user_det['last_name'];
									}
								}
							}
							echo esc_html__( 'Name:', 'simontaxi' ).' ' .ucfirst( $name );
							if ( isset( $contact['mobile']) ) {
								echo '<br>'.esc_html__( 'Phone : ', 'simontaxi' ) . $contact['mobile'];
							}
							echo '<br>' . esc_html__( 'Email: ', 'simontaxi' ) . $contact['email'];
						}
						else
						{
							if ( isset( $user_det['first_name']) || isset( $user_det['last_name']) ) {
								$name = isset( $user_det['first_name']) ? $user_det['first_name'] : '';
								if ( isset( $user_det['last_name']) ) {
									$name .= ' ' . $user_det['last_name'];
								}
							} else {
								$name = $user_det['nickname'];
							}

				  ?>
				  <p>
						<strong><?php esc_html_e( 'NAME:', 'simontaxi' ); ?><?php echo $name; ?></strong>
						<br><?php esc_html_e( 'Email:', 'simontaxi' ); ?>
						<?php echo $user_det['billing_email']; ?> <br>
						<?php esc_html_e( 'Phone:', 'simontaxi' ); ?> <?php echo $user_det['billing_phone']; ?>
				  </p>
				  <p>
						<?php esc_html_e( 'Address:', 'simontaxi' ); ?><?php if(isset( $user_det['billing_address'])) echo $user_det['billing_address']; ?> <br>
						<?php if(isset( $user_det['billing_state'])) echo $user_det['billing_state']; ?>, <br>
						<?php if(isset( $user_det['billing_country'])) echo $user_det['billing_country']; ?> <?php if(isset( $user_det['billing_postelCode'])) echo $user_det['billing_postelCode']; ?>
				  </p>

				 <?php } ?>
				  </td>
				  <td  align="left" valign="top" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; padding:20px; line-height: 1.6em; margin: 0; padding: 0;">
						<div class='pull-right' style='margin-right:35px;'>
							<h5><?php esc_html_e( 'SOLD BY', 'simontaxi' ); ?></h5>
							 <p><?php echo get_bloginfo( 'name' ); ?><br>
								<?php echo simontaxi_get_option( 'vehicle_payment_queries' ); ?></p>
							  <?php if ( simontaxi_get_option( 'contact_phone' ) != '' ) { ?>
							  <p><strong><?php esc_html_e( 'PH:', 'simontaxi' ); ?></strong><?php echo simontaxi_get_option( 'contact_phone' ); ?></p>
							  <?php } ?>
							  <p><strong><?php esc_html_e( 'EMAIL:', 'simontaxi' ); ?></strong><br>
								<?php echo simontaxi_get_option( 'vehicle_payment_queries' ); ?></p>
							
							<?php $gst_no = simontaxi_get_option( 'gst_no', '' );
							if ( $gst_no != '' ) {
							?>
							<p><strong><?php esc_html_e( 'GST NO.:', 'simontaxi' ); ?></strong><br>
								<?php echo $gst_no; ?></p>
							<?php } ?>
						</div>
				</td>
			  </tr>
			</tbody>
		  </table>
		</td>
	  </tr>
	  <tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0;">
		<td width="100%" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0;">&nbsp;</td>
	  </tr>
	  <tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0;">
		<td width="100%" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0;">

		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<thead class='simple-thead'>
			  <tr  style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0; border-bottom:1px solid #000">
				<th align="left" scope="col" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0; border-bottom:1px solid #000; padding:0 0 10px;">
					<?php esc_html_e( 'Booking Details', 'simontaxi' ); ?>
				</th>
				<!--<th align="right" scope="col" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0; border-bottom:1px solid #000; padding:0 0 10px;">Quantity</th>-->
				<th align="left" scope="col" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0; border-bottom:1px solid #000; padding:0 0 10px;">
					<?php esc_html_e( 'Unit Price', 'simontaxi' ); ?>
				</th>
				<th align="left" scope="col" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0; border-bottom:1px solid #000; padding:0 0 10px;">
					<?php esc_html_e( 'Surcharges', 'simontaxi' ); ?>
				</th>
				<th align="left" scope="col" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0; border-bottom:1px solid #000; padding:0 0 10px;">
					<?php esc_html_e( 'Discount', 'simontaxi' ); ?>
				</th>
				<th align="left" scope="col" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0; border-bottom:1px solid #000; padding:0 0 10px;">
					<?php esc_html_e( 'Tax', 'simontaxi' ); ?>
				</th>
				<th align="left" scope="col" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0; border-bottom:1px solid #000; padding:0 0 10px;">
					<?php esc_html_e( 'Total', 'simontaxi' ); ?>
				</th>
			  </tr>
			</thead>
			<tbody>
			  <?php $index=0;/*$total_s = 0;
					$discount_dist = json_decode( $invoice['discount_dist']);

					if( $invoice['txn_type']=='purchase' ) {
						foreach( $products as $index=>$rec)
						{
						  $product = json_decode( $rec->product_info); */
						  if(property_exists( $invoice,'booking_ref' ))
						  {
			   ?>
			  <tr>
			   <td align="left" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding:10px 0; border-bottom:1px solid #ddd">

					<?php echo esc_html__( 'From: ', 'simontaxi' ) . $invoice->pickup_location . '<br>'. esc_html__( 'To:', 'simontaxi' ). $invoice->drop_location. ' <br>'.esc_html__( 'Picking Date & Time :', 'simontaxi' ). simontaxi_date_format( $invoice->pickup_date ) . ' ' . simontaxi_get_time_display_format( $invoice->pickup_time ); ?></br>
					<?php if( in_array( $invoice->journey_type, apply_filters( 'simontaxi_twoway_other_tabs_step1', array( 'two_way' ) ) ) )
					{ ?>

					<br><br>---<?php esc_html_e( 'Retrun Journy', 'simontaxi' ); ?>--- <br>
					<?php
					echo esc_html__( 'From:', 'simontaxi' ). $invoice->drop_location . '<br>';
					echo esc_html__( ' To: ', 'simontaxi' ) . $invoice->pickup_location . ' <br>'.esc_html__( 'Return Date & Time :', 'simontaxi' ) . '' . simontaxi_date_format( $invoice->pickup_date_return ) . ' ' . simontaxi_get_time_display_format( $invoice->pickup_time_return ); ?></br><?php }?>
				</td>

				<td align="left" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding:10px 0; border-bottom:1px solid #ddd; vertical-align:top;">
				<?php  echo simontaxi_get_currency( $invoice->basic_amount); ?>
				</td>
				<td align="left" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding:10px 0; border-bottom:1px solid #ddd; vertical-align:top;">
					<?php if ( ( $invoice->surcharges_amount ) > 0 ) { echo simontaxi_get_currency( $invoice->surcharges_amount );
					} else {
						echo '-';
					}
					?>
				</td>
				<td align="left" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding:10px 0; border-bottom:1px solid #ddd; vertical-align:top;">
				<?php  echo simontaxi_get_currency( $invoice->discount_amount ); ?>
				</td>
				
				<td align="left" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding:10px 0; border-bottom:1px solid #ddd; vertical-align:top;">
					<?php if ( ( $invoice->tax_amount) > 0 ) { echo simontaxi_get_currency( $invoice->tax_amount );
					} else {
						echo '-';
					}
					?>
				</td>
				
				<td align="left" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding:10px 0; border-bottom:1px solid #ddd; vertical-align:top;">
				<?php echo simontaxi_get_currency( $invoice->amount_payable ); ?>
				</td>
			  </tr>
				  <?php   }

					if(FALSE && $invoice['txn_type']=='wallet_add' )
					{ ?>
						<tr>
			   <td align="left" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding:10px 0; border-bottom:1px solid #ddd">
					<?php esc_html_e( 'Add to wallet', 'simontaxi' ); ?>
				</td>

				<td align="left" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding:10px 0; border-bottom:1px solid #ddd">
				<?php echo simontaxi_get_currency( $txn['total_amount'] ); ?>
				</td>
				<td align="left" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding:10px 0; border-bottom:1px solid #ddd">
				<?php echo simontaxi_get_currency( $txn['discount_amount']); ?>
				</td>
				<td align="left" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding:10px 0; border-bottom:1px solid #ddd">
				<?php echo simontaxi_get_currency( $txn['paid_amount']); ?>
				</td>
			  </tr>
				<?php
					}
				?>



			  <tr>
				<td align="left">&nbsp;</td>
				<td align="right" colspan="3" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding:10px 0;padding-right:20px;color:gray;"><?php esc_html_e( 'TOTAL', 'simontaxi' ); ?>
				</td>
				<td align="left" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding:10px 0;">
					<?php echo simontaxi_get_currency( $invoice->amount_payable); ?>
				</td>
			  </tr>

			  

			</tbody>
		  </table></td>
	  </tr>

	   <tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0;">

		<td width="100%" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; border-top:2px dashed #ccc; padding:15px; ">

		 <?php if( $invoice->payment_status =='success' ) { ?>
		  <h5 class='text-muted'><?php esc_html_e( 'ACKNOWLEDGMENT', 'simontaxi' ); ?></h5><br>

		  <table width="100%" border="0" cellspacing="0" cellpadding="0">

			<tbody>
			  <tr>
				<td width="50%" align="left" valign="top" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; ">

					<strong><?php esc_html_e( 'PAID TO', 'simontaxi' ); ?></strong>

					<p><?php echo get_bloginfo( 'name' ); ?><br>
								<?php echo simontaxi_get_option( 'vehicle_billing_address' ); ?></p>
							  <p><strong><?php esc_html_e( 'PH:', 'simontaxi' ); ?></strong><?php echo simontaxi_get_option( 'vehicle_billing_phone' ); ?></p>
							  <p><strong><?php esc_html_e( 'EMAIL:', 'simontaxi' ); ?></strong><br>
								<?php echo simontaxi_get_option( 'vehicle_billing_email' ); ?></p>

				</td>
				<td width="50%" align="left" valign="top">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tbody>
						<tr>
						  <td width="70%" align="left" valign="top" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; padding:20px; line-height: 1.6em; margin: 0; padding: 0;">


				  <?php //var_dump( $user_det); die();
						if(!isset( $user_det['billing_firstname']) || !isset( $user_det['billing_email']) || !isset( $user_det['billing_phone']) ) {

							$contact = (array)json_decode( $invoice->booking_contacts);
							if ( isset( $contact['full_name']) ) {
								echo esc_attr( $contact['full_name']);
								echo '<br>';
							} elseif ( isset( $contact['first_name']) || isset( $contact['last_name']) ) {
								if ( isset( $contact['first_name']) ) {
									echo esc_attr( $contact['first_name']);
								}
								if ( isset( $contact['last_name']) ) {
									echo esc_attr( ' '.$contact['last_name']);
								}
								echo '<br>';
							}
							if ( isset( $contact['mobile']) ) {
								echo esc_attr( $contact['mobile']) . '<br>';
							}
							echo $contact['email'];
						} else {
				  ?>
				  <p>
						<strong><?php esc_html_e( 'NAME:', 'simontaxi' ); ?>
						<?php echo $user_det['billing_firstname'].' '.$user_det['billing_lastname']; ?></strong>
						<br><?php esc_html_e( 'Email:', 'simontaxi' ); ?>
						<?php echo $user_det['billing_email']; ?> <br> <?php esc_html_e( 'Phone:', 'simontaxi' ); ?> <?php echo $user_det['billing_phone']; ?>
				  </p>
				  <p>
						<?php esc_html_e( 'Address:', 'simontaxi' ); ?><?php if(isset( $user_det['billing_address'])) echo $user_det['billing_address']; ?> <br>
						<?php if(isset( $user_det['billing_state'])) echo $user_det['billing_state']; ?>, <br>
						<?php if(isset( $user_det['billing_country'])) echo $user_det['billing_country']; ?> <?php if(isset( $user_det['billing_postelCode'])) echo $user_det['billing_postelCode']; ?>
				  </p>

				 <?php } ?>


				  </td>
						</tr>
						<tr>
						  <td  style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding:5px; border-bottom:1px solid #ddd;"><strong><?php esc_html_e( 'Transaction ID', 'simontaxi' ); ?></strong></td>
						  <td  style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding:5px; border-bottom:1px solid #ddd; "><?php echo $invoice->payment_ref; ?></td>
						</tr>
						<tr>
						  <td  style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding:5px; border-bottom:1px solid #ddd; ">
							  <strong><?php esc_html_e( 'Date', 'simontaxi' ); ?></strong></td>
						  <td  style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding:5px; border-bottom:1px solid #ddd; ">
							  <?php echo $invoice->datetime; ?></td>
						</tr>
						<tr>
						  <td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding:20px; " valign="top">
							  <h4><?php esc_html_e( 'Amount Paid:', 'simontaxi' ); ?></h4></td>
						  <td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding:12px ">
							  <h4><strong><?php echo simontaxi_get_currency( $invoice->amount_paid); ?></strong></h4>

								<img style='width:120px;height:auto;' src="<?php echo SIMONTAXI_PLUGIN_URL; ?>/images/paid.png" />

						  </td>

						</tr>
					  </tbody>
					</table>


				</td>
			  </tr>
			</tbody>

		   </table>

			<?php } ?>
							  <?php if( $invoice->payment_status =='process' ) { ?>
								<div style="padding:20px;">
								<center>
								<h4 class="text-orange"><?php esc_html_e( 'Payment In Process', 'simontaxi' ); ?></h4>
								<?php 
								if(( $txn['cad_verification_info']!=NULL)) {
									echo '<h5 class="text-info"> ' . esc_html__( 'We have received your verification request . Please wait for our team to verify your payment !', 'simontaxi' ) . '</h5>';
								} else {
									echo '<h5 class="text-info">' . esc_html__( 'Please pay through bank and update transaction reference ID.', 'simontaxi' ) . '  <br><br><span class="small">' . esc_html__( 'UPDATE BANK REF ID BY clicking on `Click to Verify` beside this transcation in your', 'simontaxi' ) . ' [ <i class="fa fa-credit-card"></i> ' . esc_html__( 'payments', 'simontaxi' ) . ' ] tab on dashboard <br> For any other queries, mail us  <b>'.simontaxi_get_option( 'vehicle_payment_queries' ).'</b> </span> </h5>';
								}
								?>
								</center>
								</div>
							  <?php } ?>

							  <?php if( $invoice->payment_status =='failed' ) { ?>
								<div style="padding:20px;">
								<center>
								<h4 class="text-danger"><?php esc_html_e( 'Payment Failed', 'simontaxi' ); ?></h4>
								<h5 class="text-info"><?php esc_html_e( 'For any queries please mail us to', 'simontaxi' ); ?> <b><?php echo simontaxi_get_option( 'vehicle_payment_queries' ); ?></b></h5>
								</center>
								</div>
							  <?php } ?>
							   <?php if( $invoice->payment_status =='cancelled' ) { ?>
								<div style="padding:20px;">
								<center>
								<h4 class="text-danger"><?php esc_html_e( 'Payment cancelled', 'simontaxi' ); ?></h4>
								<h5 class="text-info"><?php esc_html_e( 'For any queries please mail us to', 'simontaxi' ); ?> <b><?php echo simontaxi_get_option( 'vehicle_payment_queries' ); ?></b></h5>
								</center>
								</div>
							  <?php } ?>
							  <?php if( $invoice->payment_status == 'pending' ) { ?>
								<div style="padding:20px;">
								<center>
								<h4 style="color:orange"><?php esc_html_e( 'Payment is Pending', 'simontaxi' ); ?></h4>
								<h5 style="font-size:12px" class="text-info"><?php esc_html_e( 'For any queries please mail us to', 'simontaxi' ); ?> <b><?php echo simontaxi_get_option( 'vehicle_payment_queries' ); ?></b></h5>
								</center>
								</div>
							   <?php } ?>

		 </td>
	   </tr>
		<?php
		$vehicle_billing_footer = simontaxi_get_option( 'vehicle_billing_footer', '' );
		if ( empty( $vehicle_billing_footer ) ) {
		?>
		<tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0;">
		<td align="center" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0;">
			<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 12px; line-height: 1.6em; color: #666666; font-weight: normal; margin: 0 0 10px; padding:20px 0 0 0; border-top:1px solid #ddd;">
			 <?php echo get_bloginfo( 'name' ); ?>
			</p></td>
		</tr>
		<?php } ?>
	</table>
	
	<?php
	if ( ! empty( $vehicle_billing_footer ) ) {
	?>
	<table style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; width: 100%; margin: 0; padding: 0;">
		<tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0; border-top: 1px solid;">
		<td>
		<?php
			echo '<p style="text-align:center;">' . $vehicle_billing_footer . '</p>';
		?>
		</td>
		</tr>
	</table>
	<?php } ?>
  </div>