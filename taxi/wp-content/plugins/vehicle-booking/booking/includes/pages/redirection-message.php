<?php
$redirect = simontaxi_get_bookingsteps_urls( 'step1' );
echo '<section class="inner-page-content">
<div class="container">
<div class="row">';
echo '<meta http-equiv="refresh" content="0;' . $redirect . '">';
echo '<div style="margin-top:50px;" class="alert alert-danger"><b>Sorry, session is expired ! Now you will be redirected ...</b></div>';
echo '			</div>
</div>
</section>';
