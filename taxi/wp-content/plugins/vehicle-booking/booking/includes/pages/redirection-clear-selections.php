<?php
$url = simontaxi_get_bookingsteps_urls( 'step1' );
echo '<div class="ppayement">';
echo '<meta http-equiv="refresh" content="0;URL=\'' . $url . '\'" />';
echo '</div>';