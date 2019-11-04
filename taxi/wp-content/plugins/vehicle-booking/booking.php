<?php
/**
 * Vehicle Booking 'booking'
 *
 * Handles all include files here
 *
 * @author   Digisamaritan
 * @package  Simontaxi - Vehicle Booking
 * @since    1.0.0
 */


/**
 * Custom widgets.
 */
require SIMONTAXI_PLUGIN_PATH . '/booking/widgets.php';

/**
 * Custom widgets register.
 */
function simontaxi_vehicle_widgets() {
	register_widget( 'Simon_Widget_Requestcall' );
	register_widget( 'Simon_Widget_Supportcontact' );
}
add_action( 'widgets_init', 'simontaxi_vehicle_widgets' );

/**
 * Add OR Remove Capability for a user
 */
function add_theme_caps() {
	global $wp_roles;
	
	if ( class_exists( 'WP_Roles' ) ) {
		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}
	}

	$administrator = get_role( 'administrator' );
	$special = array( 'manage_bookings', 'manage_callbacks', 'manage_support_request', 'manage_settings', 'manage_extensions', 'get_extension', 'manage_countries' );
	foreach( $special as $cap ) {
		$administrator->add_cap( $cap );
	}


	$capabilities = array(
		'read' => true,
	);
	$available_capabilities = simontaxi_available_capabilities();

	$assigned_caps = simontaxi_get_option( 'permissions', array() );

	foreach ( simontaxi_available_roles() as $role => $role_title ) :

		if ( get_role( $role ) ) {
			remove_role( $role );
		}

		$assigned = isset( $assigned_caps[ $role ] ) ? $assigned_caps[ $role ] : array();
		$role_info = get_role( $role );
		if ( empty( $role_info ) ) {
			add_role( $role, $role_title, array() );
		}
		$role_obj = get_role( $role );
		foreach ( $available_capabilities as $cap => $lable ) {

			$vehicle_caps = get_capabilities( $cap );

			foreach ( $vehicle_caps as $key => $vehicle_cap ) {

				if ( isset( $assigned[ $cap ]['edit'] ) ) {
					if ( substr( $vehicle_cap, 0, 4 ) === 'read' ) {
						$role_obj->add_cap( $vehicle_cap );
					}
					if ( substr( $vehicle_cap, 0, 5 ) === 'read_' ) {
						$role_obj->add_cap( $vehicle_cap );
					}
					if ( substr( $vehicle_cap, 0, 5 ) === 'edit_' ) {
						$role_obj->add_cap( $vehicle_cap );
					}
					if ( in_array( $cap, $special, true ) ) {
						$role_obj->add_cap( $cap );
					}
				}
				if ( isset( $assigned[ $cap ]['delete'] ) ) {
					if ( substr( $vehicle_cap, 0, 7 ) === 'delete_' ) {
						$role_obj->add_cap( $vehicle_cap );
					}
				}
				if ( isset( $assigned[ $cap ]['publish'] ) ) {
					if ( substr( $vehicle_cap, 0, 8 ) === 'publish_' ) {
						$role_obj->add_cap( $vehicle_cap );
					}
				}
				$administrator->add_cap( $vehicle_cap );
			}
		}

	endforeach;
	
}
// add_action( 'admin_init', 'add_theme_caps' );

add_action( 'admin_menu', 'setting_page' );
/**
 * Add the Settings page to the admin 'Vehicles' menu
 *
 * @since 1.0.0
 */
function setting_page() {
	add_submenu_page( 'edit.php?post_type=vehicle', esc_html__( 'Settings', 'simontaxi' ), esc_html__( 'Settings', 'simontaxi' ), 'manage_settings', 'vehicle_settings', 'vehicle_settings' );
	
}
require SIMONTAXI_PLUGIN_PATH . '/booking/vehicle-activation.php';

// require SIMONTAXI_PLUGIN_PATH . '/booking/includes/class-simontaxi-vehicle-booking.php';

$purchase_code = simontaxi_get_option( 'simontaxi_purchase_code', '' );

if ( empty( $purchase_code )  ) {
	add_action( 'admin_notices', 'simontaxi_licence_notice' );
	add_action( 'after_plugin_row_' . SIMONTAXI_PLUGIN_ID, 'simontaxi_show_purchase_notice', 10, 3 );
} else {
	$res = simontaxi_validate_envato( $purchase_code );
	
	if ( false === $res ) {
		add_action( 'admin_notices', 'simontaxi_licence_notice' );
		add_action( 'after_plugin_row_' . SIMONTAXI_PLUGIN_ID, 'simontaxi_show_purchase_notice', 10, 3 );
	}
}
function simontaxi_licence_notice() {
	_e( '<div class="notice notice-success is-dismissible"><p><strong> <i> Simontaxi - Vehicle Booking : </i> </strong> Would you like to receive automatic updates? Please activate your copy of <b>Simontaxi - Vehicle Booking</b> by entering your envato purchase code in <a href="' . simontaxi_get_bookingsteps_urls( 'settings' ) . '" title="Settings">settings</a> page.</div>', 'simontaxi' );
}

function simontaxi_show_purchase_notice() {
	$wp_list_table = _get_list_table('WP_Plugins_List_Table');
	?>
	<tr class="plugin-update-tr"><td colspan="<?php echo $wp_list_table->get_column_count(); ?>" class="plugin-update colspanchange">
		<div class="update-message installer-q-icon">
		<?php _e('To receive automatic updates, please activate your copy of <b>Simontaxi - Vehicle Booking</b> by entering your envato purchase code in <a href="' . simontaxi_get_bookingsteps_urls( 'settings' ) . '" title="Settings">settings</a> page.', 'simontaxi'); ?>
		</div>
	</tr>
	<?php
}
