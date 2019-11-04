&nbsp;
<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
<div class="header" style="background: #f5f5f5; padding: 20px;">
<h1><span style="color: #0000ff;">{BLOG_TITLE}</span></h1>
<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE} </a></div>
</div>
<h1 style="text-align: center;"><span style="color: #ff6600;"><strong><?php esc_html_e( 'Your ride start now', 'simontaxi' ); ?></strong></span></h1>

<h2 style="text-align: center;"><span style="color: #0000ff;"><?php esc_html_e( 'Booking Details', 'simontaxi' ); ?></span></h2>
<div class="">
<table class="booking-status-update" style="height: 458px;" width="1266">
<tbody>
<tr>
<td width="20%"><?php esc_html_e( 'Reference:', 'simontaxi' ); ?></td>
<td><span style="color: #ff0000;">{BOOKING_REF}</span></td>
</tr>
<tr>
<td width="20%"><?php esc_html_e( 'Journey Type:', 'simontaxi' ); ?></td>
<td>{JOURNEY_TYPE}</td>
</tr>
<tr>
<td width="20%"><?php esc_html_e( 'From:', 'simontaxi' ); ?></td>
<td>{PICKUP_LOCATION}</td>
</tr>
<tr>
<td width="20%"><?php esc_html_e( 'To:', 'simontaxi' ); ?></td>
<td>{DROP_LOCATION}</td>
</tr>
<tr>
<td width="20%"><?php esc_html_e( 'Pickup Date:', 'simontaxi' ); ?></td>
<td>{PICKUP_DATE}</td>
</tr>
<tr>
<td width="20%"><?php esc_html_e( 'Pickup Time:', 'simontaxi' ); ?></td>
<td>{PICKUP_TIME}</td>
</tr>
<tr>
<td width="20%"><?php esc_html_e( 'Name:', 'simontaxi' ); ?></td>
<td>{CONTACT_NAME}</td>
</tr>
<tr>
<td width="20%"><?php esc_html_e( 'Mobile:', 'simontaxi' ); ?></td>
<td>{CONTACT_MOBILE}</td>
</tr>
<tr>
<td width="20%"><?php esc_html_e( 'Email:', 'simontaxi' ); ?></td>
<td>{CONTACT_EMAIL}</td>
</tr>
<tr>
<td width="20%"><?php esc_html_e( 'Current Status::', 'simontaxi' ); ?></td>
<td>{BOOKING_STATUS}</td>
</tr>
<tr>
<td width="20%"><?php esc_html_e( 'Status Updated Time::', 'simontaxi' ); ?></td>
<td>{BOOKING_STATUS_UPDATED}</td>
</tr>

<tr>
<td width="20%"><?php esc_html_e( 'Instructions:', 'simontaxi' ); ?></td>
<td>{REASON}</td>
</tr>

</tbody>
</table>
</div>
<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;">

<span style="float: right;"><?php esc_html_e( 'Copyright © ' . date('Y'), 'simontaxi' ); ?> <span style="color: #0000ff;">{BLOG_TITLE}</span> <?php esc_html_e( 'All right reserved Inc.', 'simontaxi' ); ?> </span>

</div>
</div>
&nbsp;