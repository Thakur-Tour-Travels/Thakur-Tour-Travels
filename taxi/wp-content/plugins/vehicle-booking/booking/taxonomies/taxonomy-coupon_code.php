<?php
/**
 * Register taxonomy - coupon_code
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

function simontaxi_coupon_code_taxonomy() {
$slug = (defined('SIMONTAXI_SLUG')) ? SIMONTAXI_SLUG : 'vehicle';
//Coupon Codes
$coupon_code_labels = array(
	'name'              => sprintf( _x( '%s Coupon Code', 'taxonomy general name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'singular_name'     => sprintf( _x( '%s Coupon Codes', 'taxonomy singular name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'search_items'      => sprintf( esc_html__( 'Search %s Coupon Codes', 'simontaxi' ), simontaxi_get_label_singular() ),
	'all_items'         => sprintf( esc_html__( 'All %s Coupon Codes', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item'       => sprintf( esc_html__( 'Parent %s Coupon Code', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item_colon' => sprintf( esc_html__( 'Parent %s Coupon Code:', 'simontaxi' ), simontaxi_get_label_singular() ),
	'edit_item'         => sprintf( esc_html__( 'Edit %s Coupon Code', 'simontaxi' ), simontaxi_get_label_singular() ),
	'update_item'       => sprintf( esc_html__( 'Update %s Coupon Code', 'simontaxi' ), simontaxi_get_label_singular() ),
	'add_new_item'      => sprintf( esc_html__( 'Add New %s Coupon Code', 'simontaxi' ), simontaxi_get_label_singular() ),
	'new_item_name'     => sprintf( esc_html__( 'New %s Coupon Code Name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'menu_name'         => esc_html__( 'Coupon Codes', 'simontaxi' ),
);
$coupon_code_args = apply_filters( 'simontaxi_coupon_code_args', array(
		'hierarchical' => false,
		'labels'       => apply_filters('simontaxi_coupon_code_labels', $coupon_code_labels),
		'show_ui'      => true,
		'query_var'    => 'coupon_code',
		'rewrite'      => array('slug' => $slug . '/coupon_code', 'with_front' => false, 'hierarchical' => true ),
		'show_admin_column'=>true,
		/*
		 * @since 2.0.0
		*/
		'capabilities' => array(
			'manage_terms' 	=> 'read_manage_coupon_codes',
			'edit_terms' 	=> 'edit_manage_coupon_codes',
			'delete_terms' 	=> 'delete_manage_coupon_codes',
			'assign_terms'	=> 'edit_manage_coupon_codes',
		),
		'map_meta_cap' => true,
	)
);
register_taxonomy( 'coupon_code', array('vehicle'), $coupon_code_args );
}

add_action( 'coupon_code_add_form_fields', 'simontaxi_coupon_code_add_form_fields', 10, 2 ); //{$taxonomy}_add_form_fields
add_action( 'coupon_code_edit_form_fields', 'simontaxi_coupon_code_add_form_fields', 10, 2 ); //{$taxonomy}_edit_form_fields

/**
 * Add additional form fields to Coupon Codes add / edit screen
 *
 * @since   1.0
 * @return string
 */
function simontaxi_coupon_code_add_form_fields( $term ) {

if(is_object( $term))
{
$t_id = $term->term_id;
$term_meta['coupon_code'] = get_term_meta( $t_id, 'coupon_code', true );
$term_meta['coupon_value'] = get_term_meta( $t_id, 'coupon_value', true );
$term_meta['coupon_value_type'] = get_term_meta( $t_id, 'coupon_value_type', true );
$term_meta['minimum_purchase'] = get_term_meta( $t_id, 'minimum_purchase', true );
$term_meta['coupon_code_start'] = get_term_meta( $t_id, 'coupon_code_start', true );
$term_meta['coupon_code_end'] = get_term_meta( $t_id, 'coupon_code_end', true );
/**
 * @since 2.0.8
 */
$term_meta['usage_count'] = get_term_meta( $t_id, 'usage_count', true );
$term_meta['login_required'] = get_term_meta( $t_id, 'login_required', true );
}
?>
<table>
<?php do_action('simontaxi_coupon_code_add_form_fields_additional_top'); ?>
<tr>
	<td colspan="2"><div class="form-field">
		<label for="term_meta[coupon_code]"><?php esc_html_e( 'Code', 'simontaxi' ); ?></label>
		<input type="text" name="term_meta[coupon_code]" placeholder="<?php esc_html_e( 'Code', 'simontaxi' ); ?>" value="<?php if(isset( $term_meta['coupon_code'])) echo $term_meta['coupon_code']; ?>" placeholder="<?php esc_html_e( 'Code', 'simontaxi' ); ?>"/>

	</div></td>
</tr>

<tr>
	<td colspan="2"><div class="form-field">
		<label for="term_meta[coupon_value]"><?php esc_html_e( 'Value', 'simontaxi' ); ?></label>
		<input type="number" name="term_meta[coupon_value]" placeholder="<?php esc_html_e( 'Value', 'simontaxi' ); ?>" value="<?php if(isset( $term_meta['coupon_value'])) echo $term_meta['coupon_value']; ?>" placeholder="<?php esc_html_e( 'Value', 'simontaxi' ); ?>" style="width:63%" min="0"/>&nbsp;<select name="term_meta[coupon_value_type]">
		<option value="value" <?php if(isset( $term_meta['coupon_value_type']) && $term_meta['coupon_value_type'] == 'value') echo 'selected';?>><?php esc_html_e('Value', 'simontaxi')?></option>
		<option value="percent" <?php if(isset( $term_meta['coupon_value_type']) && $term_meta['coupon_value_type'] == 'percent') echo 'selected';?>><?php esc_html_e('Percent %', 'simontaxi')?></option>
		</select>

	</div></td>
</tr>

<tr>
	<td colspan="2"><div class="form-field">
		<label for="term_meta[usage_count]"><?php echo esc_html__( 'Usage Count Per User ', 'simontaxi'); ?></label>
		<input type="number" name="term_meta[usage_count]" value="<?php if(isset( $term_meta['usage_count'])) echo $term_meta['usage_count']; ?>" placeholder="<?php echo esc_html__( 'Usage Count Per User ', 'simontaxi' ); ?>" min="0"/>
		<br><small><?php esc_html_e( '"0" means no limit' ); ?></small>
	</div></td>
</tr>

<tr>
	<td colspan="2"><div class="form-field">
		<label for="term_meta[login_required]"><?php echo esc_html__( 'Login Required? ', 'simontaxi'); ?></label>
		<select name="term_meta[login_required]" >
			<option value="no" <?php if ( isset( $term_meta['login_required'] ) && $term_meta['login_required'] == 'no' ) echo 'selected'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
			<option value="yes" <?php if ( isset( $term_meta['login_required'] ) && $term_meta['login_required'] == 'yes' ) echo 'selected'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
		</select>
	</div></td>
</tr>


<tr>
	<td colspan="2"><div class="form-field">
		<label for="term_meta[minimum_purchase]"><?php echo esc_html__( 'Minimum Purchase ', 'simontaxi') . simontaxi_get_currency(); ?></label>
		<input type="number" name="term_meta[minimum_purchase]" value="<?php if(isset( $term_meta['minimum_purchase'])) echo $term_meta['minimum_purchase']; ?>" placeholder="<?php echo esc_html__( 'Minimum Purchase ', 'simontaxi' ) . simontaxi_get_currency(); ?>" min="0"/>

	</div></td>
</tr>

<tr>
	<td><div class="form-field">
		<label for="term_meta[coupon_code_start]"><?php esc_html_e( 'From Date', 'simontaxi' ); ?></label>
		<input type="text" name="term_meta[coupon_code_start]" placeholder="<?php esc_html_e( 'From Date', 'simontaxi' ); ?>" value="<?php if(isset( $term_meta['coupon_code_start'])) echo $term_meta['coupon_code_start']; ?>" placeholder="<?php esc_html_e( 'From Date', 'simontaxi' ); ?>" class="datetimepicker" autocomplete="off" readonly />

	</div></td>

<td>
	<div class="form-field">
		<label for="term_meta[coupon_code_end]"><?php esc_html_e( 'To Date', 'simontaxi' ); ?></label>
		<input type="text" name="term_meta[coupon_code_end]" placeholder="<?php esc_html_e( 'To Date', 'simontaxi' ); ?>" value="<?php if(isset( $term_meta['coupon_code_end'])) echo $term_meta['coupon_code_end']; ?>" class="datetimepicker2" autocomplete="off" readonly />
	</div>
</td></tr>

<?php do_action('simontaxi_coupon_code_add_form_fields_additional_bottom'); ?>

</table>
<script>
jQuery(document).ready( function() {
	jQuery('.datetimepicker').datepicker({
		timepicker:false,
		dateFormat:'<?php echo simontaxi_get_option( 'st_date_format_js', 'mm/dd/yy' );?>'
	});

	jQuery('.datetimepicker2').click(function(){
		var fromdate = jQuery('.datetimepicker').val();
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();

		if(fromdate == '')
			fromdate = yyyy+'-'+mm+'-'+dd;
		else
			fromdate = new Date(fromdate);
		jQuery('.datetimepicker2').datepicker({
			timepicker:false,
			dateFormat:'<?php echo simontaxi_get_option( 'st_date_format_js', 'mm/dd/yy' );?>',
			minDate: fromdate
		});
	});
});
</script>
<?php
}

add_action( 'edited_coupon_code', 'simontaxi_save_taxonomy_custom_meta_coupon_code', 10, 2 ); //edited_{$taxonomy} hook
add_action( 'created_coupon_code', 'simontaxi_save_taxonomy_custom_meta_coupon_code', 10, 2 );
/**
 * save Coupon Codes form additional form fieilds
 *
 * @since   1.0
 */
function simontaxi_save_taxonomy_custom_meta_coupon_code( $term_id, $tt_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$cat_keys = array_keys( $_POST['term_meta'] );
		$cat_keys = apply_filters( 'simontaxi_save_taxonomy_custom_meta_coupon_code', $cat_keys);
		foreach ( $cat_keys as $key )
		{
			if ( isset ( $_POST['term_meta'][$key] ) )
			{
			   $value = $_POST['term_meta'][$key];
			   if( $key == 'vehicles')
				   $value = json_encode( $_POST['term_meta'][$key]);
				update_term_meta( $term_id, $key, $value );
			}
			if(!isset( $_POST['term_meta']['vehicles']))
			{
				update_term_meta( $term_id, 'vehicles', json_encode(array()) );
			}
		}
	}
}

add_filter('manage_edit-coupon_code_columns', 'simontaxi_add_coupon_code_additional_columns' ); //manage_edit-{$taxonomy}_columns hook
function simontaxi_add_coupon_code_additional_columns( $columns ){
	$columns['coupon_code'] = esc_html__( 'Code', 'simontaxi' );
    $columns['coupon_code_start'] = esc_html__( 'From', 'simontaxi' );
	$columns['coupon_code_end'] = esc_html__( 'To', 'simontaxi' );
    return $columns;
}

add_filter('manage_coupon_code_custom_column', 'simontaxi_add_coupon_code_column_content', 10, 3 ); //manage_{$taxonomy}_custom_column hook.
function simontaxi_add_coupon_code_column_content( $content, $column_name, $term_id )
{
    if( $column_name != 'coupon_code' ){
        return $content;
    }
    $term_id = absint( $term_id );
    $coupon_code = get_term_meta( $term_id, 'coupon_code', true );
	$coupon_value = get_term_meta( $term_id, 'coupon_value', true );
	$coupon_value_type = get_term_meta( $term_id, 'coupon_value_type', true );
	if( $coupon_value_type == 'percent')
	{
		$coupon_code = $coupon_code . ' ('.$coupon_value.' %)';
	}
	else
	{
		$coupon_code = $coupon_code . ' ('.$coupon_value.' '.simontaxi_get_currency().')';
	}
    if( !empty( $coupon_code ) ){
        $content .= esc_attr( $coupon_code );
    }
    return $content;
}

add_filter('manage_coupon_code_custom_column', 'simontaxi_add_coupon_code_start_column_content', 10, 3 ); //manage_{$taxonomy}_custom_column hook.
function simontaxi_add_coupon_code_start_column_content( $content, $column_name, $term_id )
{
    if( $column_name != 'coupon_code_start' ){
        return $content;
    }
    $term_id = absint( $term_id );
    $coupon_code_start = get_term_meta( $term_id, 'coupon_code_start', true );
	
	$coupon_code_start = str_replace('/', '-', $coupon_code_start);
	
    if( !empty( $coupon_code_start ) ){
        $content .= esc_attr( date(simontaxi_get_option( 'st_date_format', 'd-m-Y' ), strtotime( $coupon_code_start)) );
    }
    return $content;
}

add_filter('manage_coupon_code_custom_column', 'simontaxi_add_coupon_code_end_column_content', 10, 3 ); //manage_{$taxonomy}_custom_column hook.
function simontaxi_add_coupon_code_end_column_content( $content, $column_name, $term_id )
{
    if( $column_name != 'coupon_code_end' ){
        return $content;
    }
    $term_id = absint( $term_id );
    $coupon_code_end = get_term_meta( $term_id, 'coupon_code_end', true );
	
	$coupon_code_end = str_replace('/', '-', $coupon_code_end);
	
    if( !empty( $coupon_code_end ) ){
        $content .= esc_attr( date(simontaxi_get_option( 'st_date_format', 'd-m-Y' ), strtotime( $coupon_code_end)) );
    }
    return $content;
}

add_filter( 'manage_edit-coupon_code_sortable_columns', 'simontaxi_add_coupon_code_additional_column_sortable' ); //manage_edit-{$taxonomy}_sortable_columns

function simontaxi_add_coupon_code_additional_column_sortable( $sortable ){
    $sortable[ 'coupon_code' ] = 'coupon_code';
	$sortable[ 'coupon_code_end' ] = 'coupon_code_end';
    return $sortable;
}