<?php
/**
 * This template is used to display the user links
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  user left
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;	
}

global $post;
$slug = $post->post_name;
$step1_active = '';
$user_account_active = '';
$user_bookings_active = '';
$user_payments_active = '';
$user_support_active = '';
$billing_address_active = '';
$purchase_history = '';
switch( $slug ) {
    case 'user-bookings':
        $user_bookings_active = 'class="active"';
		break;
    case 'user-account':
        $user_account_active = 'class="active"';
		break;
    case 'user-payments':
        $user_payments_active = 'class="active"';
		break;
    case 'user-support':
        $user_support_active = 'class="active"';
		break;
    case 'user-billing-address':
        $billing_address_active = 'class="active"';
		break;
    case 'pick-locations':
        $step1_active = 'class="active"';
		break;
	case 'purchase-history':
        $purchase_history = 'class="active"';
		break;
}
do_action( 'simontaxi_user_menu_variables', $slug );

$dynamic_links = FALSE;
if ( function_exists('simontaxi_user_menu_links') ) {
	$user_menu_links = simontaxi_user_menu_links();
	if ( ! empty( $user_menu_links ) ) {
		$dynamic_links = TRUE;
	}
} 
if ( $dynamic_links ) {
	$user_menu_links = simontaxi_user_menu_links();
	?>
	<ol class="nav nav-tabs st-nav-tabs nav-justified st-admin-tabs">
	<?php foreach( $user_menu_links as $key => $link ) {
		if ( $link['loginrequired'] == true ) {
			if ( is_user_logged_in() ) { ?>
			<li <?php if ( $slug == $link['slug'] ) { echo 'class="active"'; } ?>><a href="<?php echo $link['url']; ?>"><?php echo $link['icon']; ?><?php echo $link['title']; ?></a></li>
			<?php }
		} else {
		?>
		<li <?php if ( $slug == $link['slug'] ) { echo 'class="active"'; } ?>><a href="<?php echo $link['url']; ?>"><?php echo $link['icon']; ?><?php echo $link['title']; ?></a></li>
		<?php
		}
	} ?>
	</ol>
	<?php
} else {
?>
<!-- Booking Progress -->
<ol class="nav nav-tabs st-nav-tabs nav-justified st-admin-tabs">
    <li <?php echo $step1_active; ?>><a href="<?php echo simontaxi_get_bookingsteps_urls( 'step1' ); ?>"><span class="icon icon-plus"></span><?php esc_html_e( 'Book Now', 'simontaxi' ); ?></a></li>
    <li <?php echo $user_account_active; ?>><a href="<?php echo simontaxi_get_bookingsteps_urls( 'user_account' ); ?>"><span class="icon icon-user"></span><?php esc_html_e( 'Profile', 'simontaxi' ); ?></a></li>
    <li <?php echo $user_bookings_active; ?>><a href="<?php echo simontaxi_get_bookingsteps_urls( 'user_bookings' ); ?>"><span class="icon icon-book-open"></span><?php esc_html_e( 'Booking History', 'simontaxi' ); ?></a></li>
    <li <?php echo $user_payments_active; ?>><a href="<?php echo simontaxi_get_bookingsteps_urls( 'user_payments' ); ?>"><span class="icon icon-credit-card"></span><?php esc_html_e( 'Payment History', 'simontaxi' ); ?></a></li>
    <li <?php echo $user_support_active; ?>><a href="<?php echo simontaxi_get_bookingsteps_urls( 'user_support' ); ?>"><span class="icon icon-support"></span><?php esc_html_e( 'Support', 'simontaxi' ); ?></a></li>
	<?php if ( class_exists( 'Easy_Digital_Downloads' ) ) { ?>
	<li <?php echo $purchase_history; ?>><a href="<?php echo get_permalink( edd_get_option('purchase_history_page') ); ?>"><span class="icon icon-support"></span><?php esc_html_e( 'Downloads', 'simontaxi' ); ?></a></li>
	<?php }	?>
    <li><a href="<?php echo wp_logout_url( simontaxi_get_bookingsteps_urls( 'login' ) ); ?>"><span class="icon icon-logout"></span><?php esc_html_e( 'Logout', 'simontaxi' ); ?></a></li>
</ol>
<!-- end Booking Progress -->
<?php } ?>
