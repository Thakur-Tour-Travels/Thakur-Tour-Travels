<?php
/**
 * Register taxonomy - distance_price
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  taxonomy
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

function simontaxi_distance_price_taxonomy() {
$slug = (defined('SIMONTAXI_SLUG')) ? SIMONTAXI_SLUG : 'vehicle';
//Distance Prices
$distance_price_labels = array(
	'name'              => sprintf( _x( '%s Distance Price', 'taxonomy general name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'singular_name'     => sprintf( _x( '%s Distance Prices', 'taxonomy singular name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'search_items'      => sprintf( esc_html__( 'Search %s Distance Prices', 'simontaxi' ), simontaxi_get_label_singular() ),
	'all_items'         => sprintf( esc_html__( 'All %s Distance Prices', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item'       => sprintf( esc_html__( 'Parent %s Distance Price', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item_colon' => sprintf( esc_html__( 'Parent %s Distance Price:', 'simontaxi' ), simontaxi_get_label_singular() ),
	'edit_item'         => sprintf( esc_html__( 'Edit %s Distance Price', 'simontaxi' ), simontaxi_get_label_singular() ),
	'update_item'       => sprintf( esc_html__( 'Update %s Distance Price', 'simontaxi' ), simontaxi_get_label_singular() ),
	'add_new_item'      => sprintf( esc_html__( 'Add New %s Distance Price', 'simontaxi' ), simontaxi_get_label_singular() ),
	'new_item_name'     => sprintf( esc_html__( 'New %s Distance Price Name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'menu_name'         => esc_html__( 'Distance Prices', 'simontaxi' ),
);
$distance_price_args = apply_filters( 'simontaxi_distance_price_args', array(
		'hierarchical' => false,
		'labels'       => apply_filters('simontaxi_distance_price_labels', $distance_price_labels),
		'show_ui'      => true,
		'query_var'    => 'distance_price',
		'rewrite'      => array('slug' => $slug . '/distance_price', 'with_front' => false, 'hierarchical' => true ),
		'show_admin_column'=>true,
		/*
		 * @since 2.0.0
		*/
		'capabilities' => array(
			'manage_terms' 	=> 'read_manage_distance_prices',
			'edit_terms' 	=> 'edit_manage_distance_prices',
			'delete_terms' 	=> 'delete_manage_distance_prices',
			'assign_terms'	=> 'edit_manage_distance_prices',
		),
		'map_meta_cap' => true,
	)
);
register_taxonomy( 'distance_price', array('vehicle'), $distance_price_args );
}

add_action( 'distance_price_add_form_fields', 'simontaxi_distance_price_add_form_fields', 10, 2 ); //{$taxonomy}_add_form_fields
add_action( 'distance_price_edit_form_fields', 'simontaxi_distance_price_add_form_fields', 10, 2 ); //{$taxonomy}_edit_form_fields

/**
 * Add additional form fields to Distance Prices add / edit screen
 *
 * @since   1.0
 * @return string
 */
function simontaxi_distance_price_add_form_fields( $term ) {

if ( is_object($term)) {
$t_id = $term->term_id;
$term_meta['minimum_distance'] = get_term_meta( $t_id, 'minimum_distance', true );
$term_meta['maximum_distance'] = get_term_meta( $t_id, 'maximum_distance', true );
$term_meta['fare'] = (get_term_meta( $t_id, 'fare', true )) ? (array)json_decode(get_term_meta( $t_id, 'fare', true )) : array();
}
?>
<table class="form-table">
<?php do_action('simontaxi_distance_price_add_form_fields_additional_top'); ?>
<tr>
	<td><div class="form-field">
		<label for="term_meta[minimum_distance]"><?php esc_html_e( 'Minimum Distance', 'simontaxi' ); ?></label>
		<input type="number" min="0" name="term_meta[minimum_distance]" placeholder="<?php esc_html_e( 'Minimum Distance', 'simontaxi' ); ?>" value="<?php if(isset($term_meta['minimum_distance'])) echo $term_meta['minimum_distance']; ?>" step="0.01"/>

	</div></td>

<td>
	<div class="form-field">
		<label for="term_meta[maximum_distance]"><?php esc_html_e( 'Maximum Distance', 'simontaxi' ); ?></label>
		<input type="number" min="0" name="term_meta[maximum_distance]" placeholder="<?php esc_html_e( 'Maximum Distance', 'simontaxi' ); ?>" value="<?php if(isset($term_meta['maximum_distance'])) echo $term_meta['maximum_distance']; ?>" step="0.01"/>
	</div>
</td></tr>

<tr><td colspan="2">
<label><?php echo simontaxi_get_default_title() . esc_html__(' Fare', 'simontaxi' ); ?></label>
</td></tr>
<?php
$args = array(
	'post_status' => 'publish',
	'orderby' => 'name',
	'order' => 'ASC',
	'post_type' => 'vehicle',
	'posts_per_page' => -1,
);
$vehicles = get_posts( $args );
foreach($vehicles as $vehicle) {
	$vehicle_id = VARIABLE_PREFIX . $vehicle->ID;
?>
<tr><td><?php echo $vehicle->post_title;?> : </td><td>
<input type="number" name="term_meta[fare][<?php echo $vehicle_id;?>]" value="<?php if(isset($term_meta['fare'][$vehicle_id])) echo $term_meta['fare'][$vehicle_id]; ?>" min="0" placeholder="<?php echo esc_html__('Fare ', 'simontaxi') . simontaxi_get_currency();?>" step="0.01"/>
</td></tr>
<?php } ?>

<?php do_action('simontaxi_distance_price_add_form_fields_additional_bottom'); ?>

</table>
<?php
}

add_action( 'edited_distance_price', 'simontaxi_save_taxonomy_custom_meta_distance_price', 10, 2 ); //edited_{$taxonomy} hook
add_action( 'created_distance_price', 'simontaxi_save_taxonomy_custom_meta_distance_price', 10, 2 );
/**
 * save Distance Prices form additional form fieilds
 *
 * @since   1.0
 */
function simontaxi_save_taxonomy_custom_meta_distance_price( $term_id, $tt_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$cat_keys = array_keys( $_POST['term_meta'] );
		$cat_keys = apply_filters( 'simontaxi_save_taxonomy_custom_meta_distance_price', $cat_keys);
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
			   $value = $_POST['term_meta'][$key];
			   if ( $key == 'fare' ) //We are doing 'json_decode' for array type of variables
				$value = json_encode($_POST['term_meta'][$key]);
				update_term_meta( $term_id, $key, $value );
			}
		}
	}
}

add_filter('manage_edit-distance_price_columns', 'simontaxi_add_distance_price_additional_columns' ); //manage_edit-{$taxonomy}_columns hook
function simontaxi_add_distance_price_additional_columns( $columns ){
	$columns['minimum_distance'] = esc_html__( 'Minimum', 'simontaxi' );
    $columns['maximum_distance'] = esc_html__( 'Maximum', 'simontaxi' );
    return $columns;
}

add_filter('manage_distance_price_custom_column', 'simontaxi_add_maximum_distance_column_content', 10, 3 ); //manage_{$taxonomy}_custom_column hook.
function simontaxi_add_maximum_distance_column_content( $content, $column_name, $term_id ) {
    if( $column_name != 'maximum_distance' ){
        return $content;
    }
    $term_id = absint( $term_id );
    $maximum_distance = get_term_meta( $term_id, 'maximum_distance', true );
    if( !empty( $maximum_distance ) ){
        $content .= esc_attr( $maximum_distance );
    }
    return $content;
}

add_filter('manage_distance_price_custom_column', 'simontaxi_add_minimum_distance_column_content', 10, 3 ); //manage_{$taxonomy}_custom_column hook.
function simontaxi_add_minimum_distance_column_content( $content, $column_name, $term_id ) {
    if ( $column_name != 'minimum_distance' ){
        return $content;
    }
    $term_id = absint( $term_id );
    $minimum_distance = get_term_meta( $term_id, 'minimum_distance', true );
    if ( !empty( $minimum_distance ) ) {
        $content .= esc_attr( $minimum_distance );
    }
    return $content;
}

add_filter( 'manage_edit-distance_price_sortable_columns', 'simontaxi_add_distance_price_additional_column_sortable' ); //manage_edit-{$taxonomy}_sortable_columns

function simontaxi_add_distance_price_additional_column_sortable( $sortable ){
    $sortable[ 'maximum_distance' ] = 'maximum_distance';
	$sortable[ 'minimum_distance' ] = 'minimum_distance';
    return $sortable;
}