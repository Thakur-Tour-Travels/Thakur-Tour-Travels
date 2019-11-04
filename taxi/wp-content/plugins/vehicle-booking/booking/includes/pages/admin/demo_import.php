<?php
/**
 * This will used to import demo data
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  demo data
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;	
}

/**
 * Filters the maximum allowed upload size for import files.
 *
 * @since 2.3.0
 *
 * @see wp_max_upload_size()
 *
 * @param int $max_upload_size Allowed upload size. Default 1 MB.
 */
$action = admin_url( 'admin.php?import=wordpress&amp;step=2' );
$upload_dir = wp_upload_dir();
$is_plugin_inactive = true;
if (  class_exists( 'WP_Import' ) ) {
	$is_plugin_inactive = false;
}

if ( ! function_exists( 'wordpress_importer_init' ) )  :
	$plugin_slug = 'wordpress-importer/wordpress-importer.php';
	$label = 'Install Now';
	$url = wp_nonce_url( add_query_arg( array(
						'action' => 'install-plugin',
						'plugin' => 'wordpress-importer',
						'from'   => 'import',
					), self_admin_url( 'update.php' ) ), 'install-plugin_' . $plugin_slug );
	$url = admin_url( 'import.php' );
	$action = sprintf( '<a href="%s" class="install-now" data-slug="wordpress-importer" data-name="WordPress" aria-label="Install WordPress">%s</a>', esc_url( $url ), $label );
	?><div class="error"><p><?php echo __( '"<b>WordPress Importer</b>" plugin in not installed and activated.', 'simontaxi' ); ?></p>
	<p><strong><?php echo $action; ?></strong></p></div><?php
else :
?>
<form enctype="multipart/form-data" id="import-upload-form" method="post" class="" action="<?php echo $action; ?>">
<?php wp_nonce_field( 'import-wordpress' ); ?>
<p>
<label for="upload"><?php esc_html_e( 'Choose a file to import:' ); ?></label>
<?php
$attachments = array( 
	'vehicles.xml.txt' => 'Vehicles',
	'media.xml.txt' => 'Attachments',
	);
$attachments_ids = array();
foreach( $attachments as $key => $attachment ) {
	$page = get_page_by_title( $key, OBJECT, 'attachment' );
	if ( $page != NULL ) {
		$attachments_ids[ $page->ID ] = $attachment;
	}
}

?>
<select name="import_id" id="import_id">
	<?php if ( ! empty( $attachments_ids ) ) {
		foreach( $attachments_ids as $id => $val ) {
			echo '<option value="' . $id . '">' . $val . '</option>';
		}
	}?>
</select>
<input id="import-attachments" name="fetch_attachments" type="hidden" value="1">
</p>
<input type="submit" class="button" value="<?php esc_html_e( 'Submit', 'simontaxi' ); ?>" />
</form>
<?php
endif;