<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Simontaxi
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<!-- PRELOADER -->
<div id="preloader">
	<div id="status">&nbsp;</div>
</div>
<!-- /PRELOADER -->

<!-- NAVIGATION -->
<nav class="navbar navbar-default st-navbar-default navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">			
			<?php simontaxi_get_header_logo(); ?>
			
		</div>
		<div id="main-nav" class="stellarnav">
			<?php			
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav navbar-nav navbar-right', 'container' => 'div', 'container_class' => 'itsme_menu' ) );
			} else {				
				if( current_user_can('administrator') ) {					
					?>
					<ul class="nav navbar-nav navbar-right"><li><a href="<?php echo  esc_url(admin_url('nav-menus.php')); ?>"><?php esc_html_e('Set up a navigation menu now', 'simontaxi');?></a></li></ul>
					<?php
				}
			}
			?>
		</div>
	</div>
</nav>
<!-- /NAVIGATION -->
<?php
if ( is_front_page() || is_home() ) {
	if ( get_theme_mod( 'banner-show-hide-homepage', 'hide' ) === 'show' ) {
			include get_template_directory() . '/inc/top-banner-front.php';
	}
} else {
	if ( get_theme_mod( 'banner-show-hide', 'show' ) === 'show' ) {
		$excludes = array();
		if ( get_theme_mod( 'banner-image-exclude' ) !== false ) {
			$excludes[] = get_theme_mod( 'banner-image-exclude' );
		}
		if ( ! in_array( get_the_ID(), $excludes ) ) {
			include get_template_directory() . '/inc/top-banner.php';
		}
	}
}
?>
