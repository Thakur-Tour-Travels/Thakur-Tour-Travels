<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
<div class="header" style="background: #f5f5f5; padding: 20px;">
<h1>{BLOG_TITLE}</h1>
<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE}</a></div>
</div>
<div class="content" style="padding: 20px;"><center><span style="color: #00ccff;"><strong><?php esc_html_e( sprintf( 'Welcome to %s', '{BLOG_TITLE}' ), 'simontaxi' ); ?></strong></span></center>
<p style="text-align: center;"><span style="color: #333333;"><?php esc_html_e( sprintf( 'Someone requested that the password be reset for the following account on %s', '{BLOG_TITLE}' ), 'simontaxi' ); ?></span></p>
<p>	{BLOG_LINK} </p>
<p style="text-align: center;"><span style="color: #333333;"><?php esc_html_e( 'Username or Email', 'simontaxi' ); ?></span> {USER_NAME}</p>
<p style="text-align: center;"><span style="color: #333333;"><?php esc_html_e( 'If this was a mistake, just ignore this email and nothing will happen.', 'simontaxi' ); ?></span></p>

<p style="text-align: center;"><span style="color: #333333;"><?php esc_html_e( 'To reset your password, visit the following address:', 'simontaxi' ); ?></span></p>

<p>{RESET_LINK}</p>

<p style="text-align: center;"><span style="color: #333333;"><?php esc_html_e( 'Thanks', 'simontaxi' ); ?></span></p>

</div>
<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;"><span style="float: right;"><?php esc_html_e( sprintf( 'Copyright © %s %s . All right reserved Inc.', date('Y'), '{BLOG_TITLE}' ), 'simontaxi' ); ?> </span></div>
</div>