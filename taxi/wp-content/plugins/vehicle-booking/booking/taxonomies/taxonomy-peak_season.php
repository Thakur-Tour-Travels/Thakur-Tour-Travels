<?php
/**
 * Register taxonomy - peak_season
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  taxonomy
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

function simontaxi_peak_season_taxonomy() {
$slug = (defined('SIMONTAXI_SLUG')) ? SIMONTAXI_SLUG : 'vehicle';
//Special fare Dates
$peak_season_labels = array(
	'name'              => sprintf( _x( '%s Special fare Date', 'taxonomy general name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'singular_name'     => sprintf( _x( '%s Special fare Dates', 'taxonomy singular name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'search_items'      => sprintf( esc_html__( 'Search %s Special fare Dates', 'simontaxi' ), simontaxi_get_label_singular() ),
	'all_items'         => sprintf( esc_html__( 'All %s Special fare Dates', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item'       => sprintf( esc_html__( 'Parent %s Special fare Date', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item_colon' => sprintf( esc_html__( 'Parent %s Special fare Date:', 'simontaxi' ), simontaxi_get_label_singular() ),
	'edit_item'         => sprintf( esc_html__( 'Edit %s Special fare Date', 'simontaxi' ), simontaxi_get_label_singular() ),
	'update_item'       => sprintf( esc_html__( 'Update %s Special fare Date', 'simontaxi' ), simontaxi_get_label_singular() ),
	'add_new_item'      => sprintf( esc_html__( 'Add New %s Special fare Date', 'simontaxi' ), simontaxi_get_label_singular() ),
	'new_item_name'     => sprintf( esc_html__( 'New %s Special fare Date Name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'menu_name'         => esc_html__( 'Special fare', 'simontaxi' ),
);
$peak_season_args = apply_filters( 'simontaxi_peak_season_args', array(
		'hierarchical' => false,
		'labels'       => apply_filters('simontaxi_peak_season_labels', $peak_season_labels),
		'show_ui'      => true,
		'query_var'    => 'peak_season',
		'rewrite'      => array('slug' => $slug . '/peak_season', 'with_front' => false, 'hierarchical' => true ),
		'show_admin_column'=>true,
		/*
		 * @since 2.0.0
		*/
		'capabilities' => array(
			'manage_terms' 	=> 'read_manage_special_fare',
			'edit_terms' 	=> 'edit_manage_special_fare',
			'delete_terms' 	=> 'delete_manage_special_fare',
			'assign_terms'	=> 'edit_manage_special_fare',
		),
		'map_meta_cap' => true,
	)
);
register_taxonomy( 'peak_season', array('vehicle'), $peak_season_args );
}

add_action( 'peak_season_add_form_fields', 'peak_season_add_form_fields', 10, 2 ); //{$taxonomy}_add_form_fields
add_action( 'peak_season_edit_form_fields', 'peak_season_add_form_fields', 10, 2 ); //{$taxonomy}_edit_form_fields

/**
 * Add additional form fields to Blockout Dates add / edit screen
 *
 * @since   1.0
 * @return string
 */
function peak_season_add_form_fields( $term ) {

if(is_object($term))
{
$t_id = $term->term_id;
$term_meta['peak_season'] = get_term_meta( $t_id, 'peak_season', true );
$term_meta['peak_season_end'] = get_term_meta( $t_id, 'peak_season_end', true );
$term_meta['surcharge'] = get_term_meta( $t_id, 'surcharge', true );
$term_meta['surcharge_type'] = get_term_meta( $t_id, 'surcharge_type', true );
}
?>
<table class="form-table">
<?php do_action('peak_season_add_form_fields_additional_top'); ?>
<tr class="form-field term-term-wrap">
	<th scope="row"><label for="term_meta[peak_season]"><?php esc_html_e( 'From Date', 'simontaxi' ); ?></label></th>
				<td><input name="term_meta[peak_season]" id="peak_season" type="text" value="<?php if(isset($term_meta['peak_season'])) echo $term_meta['peak_season']; ?>" placeholder="<?php esc_html_e( 'From Date', 'simontaxi' ); ?>" size="40" class="datetimepicker" autocomplete="off" readonly>
	</td>
</tr>

<tr class="form-field term-peak_season-wrap">
	<th scope="row"><label for="term_meta[peak_season_end]"><?php esc_html_e( 'To Date', 'simontaxi' ); ?></label></th>
				<td><input name="term_meta[peak_season_end]" id="peak_season_end" type="text" value="<?php if(isset($term_meta['peak_season_end'])) echo $term_meta['peak_season_end']; ?>" placeholder="<?php esc_html_e( 'To Date', 'simontaxi' ); ?>" size="40" class="datetimepicker2" autocomplete="off" readonly>
	</td>
</tr>

<?php $surcharge = 'value';
if(isset($term_meta['surcharge_type'])) {
	$surcharge = $term_meta['surcharge_type'];
}
?>
<tr class="form-field term-peak_season-wrap">
	<th scope="row"><label for="term_meta[surcharge]"><?php esc_html_e( 'Surcharge', 'simontaxi' ); ?></label></th>
				<td><input name="term_meta[surcharge]" type="number" value="<?php if(isset($term_meta['surcharge'])) echo $term_meta['surcharge']; ?>" placeholder="<?php esc_html_e( 'Surcharge', 'simontaxi' ); ?>" size="40" autocomplete="off" min="0">&nbsp;
				<select name="term_meta[surcharge_type]">
					<option value="percent" <?php if((isset($surcharge) && $surcharge == 'percent')) echo 'selected';?>><?php esc_html_e('Percent %', 'simontaxi')?></option>
					<option value="value" <?php if((isset($surcharge) && $surcharge == 'value')) echo 'selected';?>><?php esc_html_e('Value', 'simontaxi')?></option>
				</select>
	</td>
</tr>

<?php do_action('peak_season_add_form_fields_additional_bottom'); ?>

</table>
<script>
jQuery(document).ready( function( $ ) {
	$('#peak_season').datepicker({
		timepicker:false,
		dateFormat:'<?php echo simontaxi_get_option( 'st_date_format_js', 'mm/dd/yy' );?>',
		minDate: new Date(<?php echo date('Y');?>, <?php echo date('m')-1;?>, <?php echo date('d');?>)
	});

	$('#peak_season_end').click(function(){
		var fromdate = $('#peak_season').val();
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();

		if(fromdate == '')
			fromdate = yyyy+'-'+mm+'-'+dd;
		else
			fromdate = new Date(fromdate);
		$('#peak_season_end').datepicker({
			timepicker:false,
			dateFormat:'<?php echo simontaxi_get_option( 'st_date_format_js', 'mm/dd/yy' );?>',
			minDate: fromdate
		});
	});
});
</script>
<?php
}

add_action( 'edited_peak_season', 'save_taxonomy_custom_meta_peak_season', 10, 2 ); //edited_{$taxonomy} hook
add_action( 'created_peak_season', 'save_taxonomy_custom_meta_peak_season', 10, 2 );
/**
 * save Blockout Dates form additional form fieilds
 *
 * @since   1.0
 */
function save_taxonomy_custom_meta_peak_season( $term_id, $tt_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$cat_keys = array_keys( $_POST['term_meta'] );
		$cat_keys = apply_filters( 'save_taxonomy_custom_meta_peak_season', $cat_keys);
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

add_filter('manage_edit-peak_season_columns', 'add_peak_season_additional_columns' ); //manage_edit-{$taxonomy}_columns hook
function add_peak_season_additional_columns( $columns ){
    $columns['peak_season'] = esc_html__( 'From', 'simontaxi' );
	$columns['peak_season_end'] = esc_html__( 'To', 'simontaxi' );
	$columns['surcharge'] = esc_html__( 'Surcharge', 'simontaxi' );
    return $columns;
}

add_filter('manage_peak_season_custom_column', 'add_peak_season_column_content', 10, 3 ); //manage_{$taxonomy}_custom_column hook.
function add_peak_season_column_content( $content, $column_name, $term_id )
{
    if( $column_name != 'peak_season' ){
        return $content;
    }
    $term_id = absint( $term_id );
    $peak_season = get_term_meta( $term_id, 'peak_season', true );
    if( !empty( $peak_season ) ){
        $content .= esc_attr( $peak_season );
    }
    return $content;
}

add_filter('manage_peak_season_custom_column', 'add_peak_season_end_column_content', 10, 3 ); //manage_{$taxonomy}_custom_column hook.
function add_peak_season_end_column_content( $content, $column_name, $term_id )
{
    if( $column_name != 'peak_season_end' ){
        return $content;
    }
    $term_id = absint( $term_id );
    $peak_season_end = get_term_meta( $term_id, 'peak_season_end', true );
    if( !empty( $peak_season_end ) ){
        $content .= esc_attr( $peak_season_end );
    }
    return $content;
}

/**
 * @since 2.0.0
 */
add_filter('manage_peak_season_custom_column', 'add_surcharge_column_content', 10, 3 );
//manage_{$taxonomy}_custom_column hook.
function add_surcharge_column_content( $content, $column_name, $term_id )
{
    if( $column_name != 'surcharge' ){
        return $content;
    }
    $term_id = absint( $term_id );
    $surcharge = get_term_meta( $term_id, 'surcharge', true );
	
	$surcharge_type = get_term_meta( $term_id, 'surcharge_type', true );
    if( ! empty( $surcharge ) ){
        $content .= esc_attr( $surcharge ) . ' ('.$surcharge_type.')';
    }
    return $content;
}

add_filter( 'manage_edit-peak_season_sortable_columns', 'add_peak_season_additional_column_sortable' ); //manage_edit-{$taxonomy}_sortable_columns

function add_peak_season_additional_column_sortable( $sortable ){
    $sortable[ 'peak_season' ] = 'peak_season';
	$sortable[ 'peak_season_end' ] = 'peak_season_end';
    return $sortable;
}