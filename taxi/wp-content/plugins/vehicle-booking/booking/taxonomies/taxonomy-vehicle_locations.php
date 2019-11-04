<?php
/**
 * Register taxonomy - vehicle_locations
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  taxonomy
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

function simontaxi_vehicle_locations_taxonomy() {
$slug = (defined('SIMONTAXI_SLUG')) ? SIMONTAXI_SLUG : 'vehicle';
//Locations
$location_labels = array(
	'name'              => sprintf( _x( '%s Location', 'taxonomy general name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'singular_name'     => sprintf( _x( '%s Locations', 'taxonomy singular name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'search_items'      => sprintf( esc_html__( 'Search %s Locations', 'simontaxi' ), simontaxi_get_label_singular() ),
	'all_items'         => sprintf( esc_html__( 'All %s Locations', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item'       => sprintf( esc_html__( 'Parent %s Location', 'simontaxi' ), simontaxi_get_label_singular() ),
	'parent_item_colon' => sprintf( esc_html__( 'Parent %s Location:', 'simontaxi' ), simontaxi_get_label_singular() ),
	'edit_item'         => sprintf( esc_html__( 'Edit %s Location', 'simontaxi' ), simontaxi_get_label_singular() ),
	'update_item'       => sprintf( esc_html__( 'Update %s Location', 'simontaxi' ), simontaxi_get_label_singular() ),
	'add_new_item'      => sprintf( esc_html__( 'Add New %s Location', 'simontaxi' ), simontaxi_get_label_singular() ),
	'new_item_name'     => sprintf( esc_html__( 'New %s Location Name', 'simontaxi' ), simontaxi_get_label_singular() ),
	'menu_name'         => esc_html__( 'Locations', 'simontaxi' ),
);
$location_args = apply_filters( 'simontaxi_vehicle_features_args', array(
		'hierarchical' => false,
		'labels'       => apply_filters('simontaxi_vehicle_locations_labels', $location_labels),
		'show_ui'      => true,
		'query_var'    => 'vehicle_locations',
		'rewrite'      => array('slug' => $slug . '/vehicle_locations', 'with_front' => false, 'hierarchical' => true ),
		'show_admin_column'=>true,
		/*
		 * @since 2.0.0
		*/
		'capabilities' => array(
			'manage_terms' 	=> 'read_manage_locations',
			'edit_terms' 	=> 'edit_manage_locations',
			'delete_terms' 	=> 'delete_manage_locations',
			'assign_terms'	=> 'edit_manage_locations',
		),
		'map_meta_cap' => true,
	)
);
register_taxonomy( 'vehicle_locations', array('vehicle'), $location_args );
}

add_action( 'vehicle_locations_add_form_fields', 'vehicle_locations_add_form_fields', 10, 2 ); //{$taxonomy}_add_form_fields
add_action( 'vehicle_locations_edit_form_fields', 'vehicle_locations_add_form_fields', 10, 2 ); //{$taxonomy}_edit_form_fields

/**
 * Add additional form fields to vehicle locations add / edit screen
 *
 * @since   1.0
 */
function vehicle_locations_add_form_fields( $term )
{
	  $name = '';
	  if(is_object($term))
	  {
		$t_id = $term->term_id;
		$term_meta['location_type'] = get_term_meta( $t_id, 'location_type', true );
		$term_meta['display_type'] = get_term_meta( $t_id, 'display_type', true );
		$name = $term->name;
		$term_meta['location_address'] = get_term_meta( $t_id, 'location_address', true );

		$term_meta['distances'] = get_term_meta( $t_id, 'distances', true );
		$term_meta['times'] = get_term_meta( $t_id, 'times', true );

		$term_meta['distances'] = (array)json_decode($term_meta['distances']);
		$term_meta['times'] = (array)json_decode($term_meta['times']);
		//print_r($term_meta);
	  }
	  
	  $display_types = apply_filters( 'simontaxi_location_display_types', 
		array(
			'pickup_location' => esc_html__( 'Pick up point', 'simontaxi' ),
			'drop_location' => esc_html__( 'Drop off point', 'simontaxi' ),
			'both' => esc_html__( 'Both', 'simontaxi' ),
	  ) );
	  
	  $location_types = apply_filters( 'simontaxi_location_types', 
		array(
			'normal' => esc_html__( 'Normal', 'simontaxi' ),
			'airport' => simontaxi_get_fixed_point_title(),
	  ) );
	  echo '<table class="form-table">';
	?>
	<?php do_action('vehicle_locations_add_form_fields_additional_top'); ?>
	<tr>
			<td><label for="term_meta[location_type]"><?php esc_html_e( 'Location Type', 'simontaxi' ); ?></label></td>
			<td>
			<select  name="term_meta[location_type]" id="term_meta[location_type]">
				<?php
				if ( ! empty( $location_types ) ) {
					foreach( $location_types as $key => $val ) {
						?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php if(isset($term_meta['location_type']) && $term_meta['location_type']==$key ) echo 'selected'?>><?php echo esc_html( $val ); ?></option>
						<?php
					}
				}
				?>
			</select>
			</td>
		</tr>

		<tr>
			<td><label for="term_meta[display_type]"><?php esc_html_e( 'Display Type', 'simontaxi' ); ?></label></td>
			<td>
			<select  name="term_meta[display_type]" id="term_meta[display_type]">
				<?php
				if ( ! empty( $display_types ) ) {
					foreach( $display_types as $key => $val ) {
						?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php if(isset($term_meta['display_type']) && $term_meta['display_type']==$key ) echo 'selected'?>><?php echo esc_html( $val ); ?></option>
						<?php
					}
				}
				?>
			</select>
			</td>
		</tr>

		<tr>
			<td><label for="term_meta[location_address]"><?php esc_html_e( 'Address', 'simontaxi' ); ?></label></td>
			<td>
			<textarea  name="term_meta[location_address]" id="term_meta[location_address]" placeholder="<?php esc_html_e('It should match the Google map address, so that we can calculate distance using google map feature. More useful for airports and predefined locations. Ref : https://www.google.co.in/maps', 'simontaxi');?>" rows="5" cols="40"><?php if ( isset( $term_meta['location_address'] ) ) { echo $term_meta['location_address']; }?></textarea>
			</td>
		</tr>


	<?php
	global $wpdb;
	
	$args = array(
		'taxonomy' => 'vehicle_locations',
		'hide_empty' => false,
	);
	$locations = get_terms( $args );
	if ( ! empty( $locations ) && ! is_wp_error( $locations ) ) {
		echo '<tr><td colspan="2"><h3>' . esc_html__( 'Distance & Journey Duration From', 'simontaxi' ) . ' <span id="lc-input-location">' . esc_html__( 'New Location', 'simontaxi' ) . '</span> </h3></td></tr>';
		
		foreach ( $locations as $row ) {
			$row = (array)$row;
			if($name != $row['name'] ){
				//echo $term_meta['distances'][$row['name']].'##'.$row['name'];
			$distance = '';
			if ( ! empty( $term_meta[ 'distances' ][ 'dt_' . $row['term_id' ] ] ) ) {
				$distance = $term_meta[ 'distances' ][ 'dt_' . $row['term_id' ] ];
			} elseif ( ! empty( $term_meta[ 'distances' ][ $row['name' ] ] ) ) {
				$distance = $term_meta[ 'distances' ][ $row['name'] ];
			}
			
			$time = '';
			if ( ! empty( $term_meta[ 'times' ][ 'tm_' . $row['term_id' ] ] ) ) {
				$time = $term_meta[ 'times' ][ 'tm_' . $row['term_id' ] ];
			} elseif ( ! empty( $term_meta[ 'times' ][ $row['name' ] ] ) ) {
				$time = $term_meta[ 'times' ][ $row['name'] ];
			}
			?>
			<tr>
				<td><label ><?php echo $row['name']; ?></label>&nbsp;<input  name="term_meta[distances][dt_<?php echo esc_html( $row['term_id'] ); ?>]" type="number" placeholder="<?php esc_html_e('Distance', 'simontaxi'); 
				echo ' ' . ucfirst( simontaxi_get_distance_units() );?>" value="<?php echo esc_attr( $distance ); ?>" step="0.01"></td>
				<td><label >&nbsp;</label>&nbsp;<input type="text" name="term_meta[times][tm_<?php echo esc_html($row['term_id']); ?>]" placeholder="<?php esc_html_e('HH:MM', 'simontaxi');?>" value="<?php echo esc_attr( $time ); ?>"></td>
			</tr>
			<?php
		}
	}
	}
	/*
	$con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$sql = "SELECT t.* from $wpdb->terms AS t
	INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
	WHERE tt.taxonomy IN('vehicle_locations') GROUP BY t.term_id";
	if ($result = mysqli_query($con, $sql)) {

		if (mysqli_num_rows($result) > 0)
		{
			echo '<tr><td colspan="2"><h3>' . esc_html__( 'Distance & Journey Duration From', 'simontaxi' ) . ' <span id="lc-input-location">' . esc_html__( 'New Location', 'simontaxi' ) . '</span> </h3></td></tr>';

			while ($row = mysqli_fetch_array($result))
			{
				if($name != $row['name'] ){
					//echo $term_meta['distances'][$row['name']].'##'.$row['name'];
				$distance = '';
				if ( ! empty( $term_meta[ 'distances' ][ 'dt_' . $row['term_id' ] ] ) ) {
					$distance = $term_meta[ 'distances' ][ 'dt_' . $row['term_id' ] ];
				} elseif ( ! empty( $term_meta[ 'distances' ][ $row['name' ] ] ) ) {
					$distance = $term_meta[ 'distances' ][ $row['name'] ];
				}
				
				$time = '';
				if ( ! empty( $term_meta[ 'times' ][ 'tm_' . $row['term_id' ] ] ) ) {
					$time = $term_meta[ 'times' ][ 'tm_' . $row['term_id' ] ];
				} elseif ( ! empty( $term_meta[ 'times' ][ $row['name' ] ] ) ) {
					$time = $term_meta[ 'times' ][ $row['name'] ];
				}
				?>
				<tr>
					<td><label ><?php echo esc_html($row['name'], 'simontaxi'); ?></label>&nbsp;<input  name="term_meta[distances][dt_<?php echo esc_html( $row['term_id'] ); ?>]" type="number" placeholder="<?php esc_html_e('Distance', 'simontaxi'); 
					echo ' ' . ucfirst( simontaxi_get_distance_units() );?>" value="<?php echo esc_attr( $distance ); ?>" step="0.01"></td>
					<td><input type="text" name="term_meta[times][tm_<?php echo esc_html($row['term_id']); ?>]" placeholder="<?php esc_html_e('HH:MM', 'simontaxi');?>" value="<?php echo esc_attr( $time ); ?>"></td>
				</tr>
				<?php
				}
			}
		}
	}
	*/
	do_action('vehicle_locations_add_form_fields_additional_bottom');
	echo '</table>';
}

add_action( 'edited_vehicle_locations', 'save_taxonomy_custom_meta_vehicle_locations', 10, 2 ); //edited_{$taxonomy} hook
add_action( 'created_vehicle_locations', 'save_taxonomy_custom_meta_vehicle_locations', 10, 2 ); //created_{$taxonomy} hook

/**
 * save vehicle locations form additional form fieilds
 *
 * @since   1.0
 */
function save_taxonomy_custom_meta_vehicle_locations( $term_id, $tt_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$cat_keys = array_keys( $_POST['term_meta'] );
		$cat_keys = apply_filters( 'save_taxonomy_custom_meta_vehicle_locations', $cat_keys);
		foreach ( $cat_keys as $key )
		{
			if ( isset ( $_POST['term_meta'][$key] ) )
			{
			   $value = $_POST['term_meta'][$key];
			   if($key == 'distances' || $key == 'times')
				$value = json_encode($_POST['term_meta'][$key]);
				update_term_meta( $term_id, $key, $value );
			}
		}
	}
}
add_filter('manage_edit-vehicle_locations_columns', 'add_location_columns' ); //manage_edit-{$taxonomy}_columns hook
function add_location_columns( $columns ){
    $columns['location_type'] = esc_html__( 'Type', 'simontaxi' );
    return $columns;
}

add_filter('manage_vehicle_locations_custom_column', 'add_location_column_content', 10, 3 ); //manage_{$taxonomy}_custom_column hook.
function add_location_column_content( $content, $column_name, $term_id )
{
    if( $column_name !== 'location_type' ){
        return $content;
    }

    $term_id = absint( $term_id );
    $location_type = get_term_meta( $term_id, 'location_type', true );

    if( !empty( $location_type ) ){
        $content .= esc_attr( $location_type );
    }
    return $content;
}

add_filter( 'manage_edit-vehicle_locations_sortable_columns', 'add_location_column_sortable' ); //manage_edit-{$taxonomy}_sortable_columns

function add_location_column_sortable( $sortable ){
    $sortable[ 'location_type' ] = 'location_type';
    return $sortable;
}