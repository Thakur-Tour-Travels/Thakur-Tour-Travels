<?php
/**
 * Register taxonomy - block_date
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  taxonomy
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function simontaxi_blockout_date_taxonomy() {
$slug = (defined( 'SIMONTAXI_SLUG' ) ) ? SIMONTAXI_SLUG : 'vehicle';
//Lay out Dates
$blockout_date_labels = array(
	'name'              => sprintf( _x( '%s Lay out Date', 'taxonomy general name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'singular_name'     => sprintf( _x( '%s Lay out Dates', 'taxonomy singular name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'search_items'      => sprintf( esc_html__( 'Search %s Lay out Dates', 'simontaxi' ), simontaxi_get_label_singular() ),
	'all_items'         => sprintf( esc_html__( 'All %s Lay out Dates', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item'       => sprintf( esc_html__( 'Parent %s Lay out Date', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item_colon' => sprintf( esc_html__( 'Parent %s Lay out Date:', 'simontaxi' ), simontaxi_get_label_singular() ),
	'edit_item'         => sprintf( esc_html__( 'Edit %s Lay out Date', 'simontaxi' ), simontaxi_get_label_singular() ),
	'update_item'       => sprintf( esc_html__( 'Update %s Lay out Date', 'simontaxi' ), simontaxi_get_label_singular() ),
	'add_new_item'      => sprintf( esc_html__( 'Add New %s Lay out Date', 'simontaxi' ), simontaxi_get_label_singular() ),
	'new_item_name'     => sprintf( esc_html__( 'New %s Lay out Date Name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'menu_name'         => esc_html__( 'Lay out Dates', 'simontaxi' ),
);
$blockout_date_args = apply_filters( 'simontaxi_blockout_date_args', array(
		'hierarchical' => false,
		'labels'       => apply_filters( 'simontaxi_blockout_date_labels', $blockout_date_labels),
		'show_ui'      => true,
		'query_var'    => 'blockout_date',
		'rewrite'      => array( 'slug' => $slug . '/blockout_date', 'with_front' => false, 'hierarchical' => true ),
		'show_admin_column'=>true,
		/*
		 * @since 2.0.0
		*/
		'capabilities' => array(
			'manage_terms' 	=> 'read_manage_layoutdates',
			'edit_terms' 	=> 'edit_manage_layoutdates',
			'delete_terms' 	=> 'delete_manage_layoutdates',
			'assign_terms'	=> 'edit_manage_layoutdates',
		),
		'map_meta_cap' => true,
	)
);
register_taxonomy( 'blockout_date', array( 'vehicle' ), $blockout_date_args );
}

add_action( 'blockout_date_add_form_fields', 'simontaxi_blockout_date_add_form_fields', 10, 2 ); //{$taxonomy}_add_form_fields
add_action( 'blockout_date_edit_form_fields', 'simontaxi_blockout_date_add_form_fields', 10, 2 ); //{$taxonomy}_edit_form_fields

/**
 * Add additional form fields to Blockout Dates add / edit screen
 *
 * @since   1.0
 * @return string
 */
function simontaxi_blockout_date_add_form_fields( $term ) {

if ( is_object( $term ) ) {
$t_id = $term->term_id;
$term_meta['block_date'] = get_term_meta( $t_id, 'block_date', true );
$term_meta['block_date_end'] = get_term_meta( $t_id, 'block_date_end', true );
$vehicles = get_term_meta( $t_id, 'vehicles', true );
$term_meta['vehicles'] = ( empty ( $vehicles ) ) ? array() : ( array )json_decode( $vehicles );
}
?>
<table class="form-table">
<?php do_action('simontaxi_blockout_date_add_form_fields_additional_top'); ?>
<tr class="form-field">
	<th scope="row"><label for="term_meta[block_date]"><?php esc_html_e( 'From Date', 'simontaxi' ); ?></label></th>
	<td><input type="text" name="term_meta[block_date]" placeholder="<?php esc_html_e( 'From Date', 'simontaxi' ); ?>" value="<?php if ( isset( $term_meta['block_date'] ) ) echo $term_meta['block_date']; ?>" placeholder="<?php esc_html_e( 'From Date', 'simontaxi' ); ?>" class="datetimepicker" autocomplete="off" readonly /></td>
</tr>

<tr class="form-field">
<th scope="row"><label for="term_meta[block_date_end]"><?php esc_html_e( 'To Date', 'simontaxi' ); ?></label></th>
<td><input type="text" name="term_meta[block_date_end]" placeholder="<?php esc_html_e( 'To Date', 'simontaxi' ); ?>" value="<?php if ( isset( $term_meta['block_date_end'] ) ) echo $term_meta['block_date_end']; ?>" class="datetimepicker2" autocomplete="off" readonly />
</td></tr>

<tr>
<th scope="row"><label for="term_meta[vehicles]"><?php esc_html_e( simontaxi_get_default_title_plural(), 'simontaxi' ); ?></label></th>
<td>
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
	$value = isset( $term_meta['vehicles'][ $vehicle_id ] ) ? VARIABLE_PREFIX . $term_meta['vehicles'][ $vehicle_id ] : '';
?>
<input type="checkbox" name="term_meta[vehicles][<?php echo $vehicle_id;?>]" value="<?php echo $vehicle->ID; ?>" <?php if ( $value == $vehicle_id ) echo ' checked'; ?>/>&nbsp;&nbsp;<?php echo $vehicle->post_title;?><br>
<?php } ?>
</td></tr>
<?php do_action('simontaxi_blockout_date_add_form_fields_additional_bottom'); ?>
</table>
<script>

jQuery(document).ready( function( $ ) {
	$( '.datetimepicker' ).datepicker({
		timepicker:false,
		dateFormat:'<?php echo simontaxi_get_option( 'st_date_format_js', 'mm/dd/yy' );?>',
		minDate: new Date(<?php echo date( 'Y' );?>, <?php echo date( 'm' )-1;?>, <?php echo date( 'd' );?>)
	});

	$( '.datetimepicker2' ).click(function(){
		var fromdate = $( '.datetimepicker' ).val();
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();

		if (fromdate == '' ) {
			fromdate = yyyy+'-'+mm+'-'+dd;
		}
		else {
			fromdate = new Date(fromdate);
		}
		$( '.datetimepicker2' ).datepicker({
			timepicker:false,
			dateFormat:'<?php echo simontaxi_get_option( 'st_date_format_js', 'mm/dd/yy' );?>',
			minDate: fromdate
		});
	});
});
</script>
<?php
}

add_action( 'edited_blockout_date', 'simontaxi_save_taxonomy_custom_meta_blockout_date', 10, 2 ); //edited_{$taxonomy} hook
add_action( 'created_blockout_date', 'simontaxi_save_taxonomy_custom_meta_blockout_date', 10, 2 );
/**
 * save Blockout Dates form additional form fieilds
 *
 * @since   1.0
 */
function simontaxi_save_taxonomy_custom_meta_blockout_date( $term_id, $tt_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$cat_keys = array_keys( $_POST['term_meta'] );
		$cat_keys = apply_filters( 'simontaxi_save_taxonomy_custom_meta_blockout_date', $cat_keys);
		foreach ( $cat_keys as $key )
		{
			if ( isset ( $_POST['term_meta'][ $key] ) )
			{
			   $value = $_POST['term_meta'][ $key];
			   if ( $key == 'vehicles' )
				   $value = json_encode( $_POST['term_meta'][ $key] );
				update_term_meta( $term_id, $key, $value );
			}
			if (!isset( $_POST['term_meta']['vehicles'] ) )
			{
				update_term_meta( $term_id, 'vehicles', json_encode( array() ) );
			}
		}
	}
}

add_filter( 'manage_edit-blockout_date_columns', 'simontaxi_add_blockout_date_additional_columns' ); //manage_edit-{$taxonomy}_columns hook
function simontaxi_add_blockout_date_additional_columns( $columns ){
    $columns['block_date'] = esc_html__( 'From', 'simontaxi' );
	$columns['block_date_end'] = esc_html__( 'To', 'simontaxi' );
    return $columns;
}

add_filter( 'manage_blockout_date_custom_column', 'simontaxi_add_block_date_column_content', 10, 3 ); //manage_{$taxonomy}_custom_column hook.
function simontaxi_add_block_date_column_content( $content, $column_name, $term_id )
{
    if ( $column_name != 'block_date' ){
        return $content;
    }
    $term_id = absint( $term_id );
    $block_date = get_term_meta( $term_id, 'block_date', true );
    if ( !empty( $block_date ) ){
        $content .= esc_attr( $block_date );
    }
    return $content;
}

add_filter( 'manage_blockout_date_custom_column', 'simontaxi_add_block_date_end_column_content', 10, 3 ); //manage_{$taxonomy}_custom_column hook.
function simontaxi_add_block_date_end_column_content( $content, $column_name, $term_id )
{
    if ( $column_name != 'block_date_end' ){
        return $content;
    }
    $term_id = absint( $term_id );
    $block_date_end = get_term_meta( $term_id, 'block_date_end', true );
    if ( ! empty ( $block_date_end ) ){
        $content .= esc_attr( $block_date_end );
    }
    return $content;
}

add_filter( 'manage_edit-blockout_date_sortable_columns', 'simontaxi_add_blockout_date_additional_column_sortable' ); //manage_edit-{$taxonomy}_sortable_columns

function simontaxi_add_blockout_date_additional_column_sortable( $sortable ){
    $sortable[ 'block_date' ] = 'block_date';
	$sortable[ 'block_date_end' ] = 'block_date_end';
    return $sortable;
}