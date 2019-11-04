<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Simontaxi
 */

if ( is_active_sidebar( 'sidebar-1' ) ) :
?>
<!-- Widget Sidebar -->
<div class="col-lg-4 col-md-3 col-sm-12 col-xs-12 st-widget-pad">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</div>
<?php endif;