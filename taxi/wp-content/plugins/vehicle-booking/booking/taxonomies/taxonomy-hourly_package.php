<?php
/**
 * Register taxonomy - hourly_packages
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  taxonomy
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

function simontaxi_hourly_packages_taxonomy() {
$slug = (defined('SIMONTAXI_SLUG')) ? SIMONTAXI_SLUG : 'vehicle';
//Hourly Packages
$hourly_packages_labels = array(
	'name'              => sprintf( _x( '%s Hourly Package', 'taxonomy general name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'singular_name'     => sprintf( _x( '%s Hourly Packages', 'taxonomy singular name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'search_items'      => sprintf( esc_html__( 'Search %s Hourly Packages', 'simontaxi' ), simontaxi_get_label_singular() ),
	'all_items'         => sprintf( esc_html__( 'All %s Hourly Packages', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item'       => sprintf( esc_html__( 'Parent %s Hourly Package', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item_colon' => sprintf( esc_html__( 'Parent %s Hourly Package:', 'simontaxi' ), simontaxi_get_label_singular() ),
	'edit_item'         => sprintf( esc_html__( 'Edit %s Hourly Package', 'simontaxi' ), simontaxi_get_label_singular() ),
	'update_item'       => sprintf( esc_html__( 'Update %s Hourly Package', 'simontaxi' ), simontaxi_get_label_singular() ),
	'add_new_item'      => sprintf( esc_html__( 'Add New %s Hourly Package', 'simontaxi' ), simontaxi_get_label_singular() ),
	'new_item_name'     => sprintf( esc_html__( 'New %s Hourly Package Name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'menu_name'         => esc_html__( 'Hourly Packages', 'simontaxi' ),
);
$hourly_packages_args = apply_filters( 'simontaxi_hourly_packages_args', array(
		'hierarchical' => false,
		'labels'       => apply_filters('simontaxi_hourly_packages_labels', $hourly_packages_labels),
		'show_ui'      => true,
		'query_var'    => 'hourly_packages',
		'rewrite'      => array('slug' => $slug . '/hourly_packages', 'with_front' => false, 'hierarchical' => true ),
		'show_admin_column'=>true,
		/*
		 * @since 2.0.0
		*/
		'capabilities' => array(
			'manage_terms' 	=> 'read_manage_hourly_packages',
			'edit_terms' 	=> 'edit_manage_hourly_packages',
			'delete_terms' 	=> 'delete_manage_hourly_packages',
			'assign_terms'	=> 'edit_manage_hourly_packages',
		),
		'map_meta_cap' => true,
	)
);
register_taxonomy( 'hourly_packages', array('vehicle'), $hourly_packages_args );
}

add_action( 'hourly_packages_add_form_fields', 'simontaxi_hourly_packages_add_form_fields', 10, 2 ); //{$taxonomy}_add_form_fields
add_action( 'hourly_packages_edit_form_fields', 'simontaxi_hourly_packages_add_form_fields', 10, 2 ); //{$taxonomy}_edit_form_fields

/**
 * Add additional form fields to hourly packages add / edit screen
 *
 * @since   1.0
 * @return string
 */
function simontaxi_hourly_packages_add_form_fields( $term ) {

if(is_object($term))
{
$t_id = $term->term_id;
$term_meta['hourly_hours'] = get_term_meta( $t_id, 'hourly_hours', true );
$term_meta['fare'] = get_term_meta( $t_id, 'fare', true );
}
?>
<table class="form-table">
<?php do_action('simontaxi_hourly_packages_add_form_fields_additional_top'); ?>
<tr>
	<th><label for="term_meta[hourly_hours]"><?php esc_html_e( 'Package Duration in Hours', 'simontaxi' ); ?></label></th>
	<td><input type="number" min="0" name="term_meta[hourly_hours]" placeholder="<?php esc_html_e( 'Hours', 'simontaxi' ); ?>" value="<?php if(isset($term_meta['hourly_hours'])) echo $term_meta['hourly_hours']; else echo '0';?>"/></td>
</tr>

<?php
$args = array(
	'post_status' => 'publish',
	'orderby' => 'name',
	'order' => 'ASC',
	'post_type' => 'vehicle',
	'posts_per_page' => -1,
);
$vehicles = get_posts( $args );
foreach ( $vehicles as $vehicle ) {
	$vehicle_id = VARIABLE_PREFIX . $vehicle->ID;
?>
<tr>
<th><label for="<?php echo esc_attr($vehicle_id);?>"><?php echo $vehicle->post_title;?></label></th>
<td><input  name="term_meta[fare][<?php echo $vehicle_id; ?>]" type="number" placeholder="<?php echo esc_html__('Fare ', 'simontaxi') . simontaxi_get_currency();?>" aria-required="true" value="<?php if(isset($term_meta['fare'][$vehicle_id])) echo $term_meta['fare'][$vehicle_id]; else echo '0';?>" id="<?php echo esc_attr($vehicle_id);?>" min="0">
</td></tr>
<?php } ?>
<?php do_action('simontaxi_hourly_packages_add_form_fields_additional_bottom'); ?>
</table>
<?php
}

add_action( 'edited_hourly_packages', 'simontaxi_save_taxonomy_custom_meta_hourly_packages', 10, 2 ); //edited_{$taxonomy} hook
add_action( 'created_hourly_packages', 'simontaxi_save_taxonomy_custom_meta_hourly_packages', 10, 2 );
/**
 * save hourly packages form additional form fieilds
 *
 * @since   1.0
 */
function simontaxi_save_taxonomy_custom_meta_hourly_packages( $term_id, $tt_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$cat_keys = array_keys( $_POST['term_meta'] );
		$cat_keys = apply_filters( 'simontaxi_save_taxonomy_custom_meta_hourly_packages', $cat_keys);
		foreach ( $cat_keys as $key )
		{
			if ( isset ( $_POST['term_meta'][$key] ) )
			{
			   $value = $_POST['term_meta'][$key];
				update_term_meta( $term_id, $key, $value );
			}
		}
	}
}

add_filter('manage_edit-hourly_packages_columns', 'simontaxi_add_hourly_packages_additional_columns' ); //manage_edit-{$taxonomy}_columns hook
function simontaxi_add_hourly_packages_additional_columns( $columns ){
    $columns['hourly_hours'] = esc_html__( 'Hours', 'simontaxi' );
	$columns['hourly_price'] = esc_html__( 'Price', 'simontaxi' );
    return $columns;
}

add_filter('manage_hourly_packages_custom_column', 'simontaxi_add_hourly_hours_column_content', 10, 3 ); //manage_{$taxonomy}_custom_column hook.
function simontaxi_add_hourly_hours_column_content( $content, $column_name, $term_id )
{
    if( $column_name != 'hourly_hours' ){
        return $content;
    }
    $term_id = absint( $term_id );
    $hourly_hours = get_term_meta( $term_id, 'hourly_hours', true );
    if( !empty( $hourly_hours ) ){
        $content .= esc_attr( $hourly_hours );
    }
    return $content;
}
/*
add_filter('manage_hourly_packages_custom_column', 'simontaxi_add_hourly_price_column_content', 10, 3 ); //manage_{$taxonomy}_custom_column hook.
function simontaxi_add_hourly_price_column_content( $content, $column_name, $term_id )
{
    if( $column_name != 'hourly_price' ){
        return $content;
    }
    $term_id = absint( $term_id );
    $hourly_price = get_term_meta( $term_id, 'hourly_price', true );
    if( !empty( $hourly_price ) ){
        $content .= esc_attr( $hourly_price );
    }
    return $content;
}
*/
add_filter( 'manage_edit-hourly_packages_sortable_columns', 'simontaxi_add_hourly_packages_additional_column_sortable' ); //manage_edit-{$taxonomy}_sortable_columns

function simontaxi_add_hourly_packages_additional_column_sortable( $sortable ){
    $sortable[ 'hourly_hours' ] = 'hourly_hours';
	$sortable[ 'hourly_price' ] = 'hourly_price';
    return $sortable;
}