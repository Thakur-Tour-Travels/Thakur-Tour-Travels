<?php
/**
 * Register Custom post type (CPT) - vehicle
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  CPT
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function simontaxi_vehicle_post_type() {
    $vehicle_labels = apply_filters( 'simontaxi_vehicle_labels', array(
		'name'                  => _x( '%2$s', 'vehicle post type name', 'simontaxi' ),
		'singular_name'         => _x( '%1$s', 'singular vehicle post type name', 'simontaxi' ),
		'add_new'               => esc_html__( 'Add New', 'simontaxi' ),
		'add_new_item'          => esc_html__( 'Add New %1$s', 'simontaxi' ),
		'edit_item'             => esc_html__( 'Edit %1$s', 'simontaxi' ),
		'new_item'              => esc_html__( 'New %1$s', 'simontaxi' ),
		'all_items'             => esc_html__( 'All %2$s', 'simontaxi' ),
		'view_item'             => esc_html__( 'View %1$s', 'simontaxi' ),
		'search_items'          => esc_html__( 'Search %2$s', 'simontaxi' ),
		'not_found'             => esc_html__( 'No %2$s found', 'simontaxi' ),
		'not_found_in_trash'    => esc_html__( 'No %2$s found in Trash', 'simontaxi' ),
		'parent_item_colon'     => '',
		'menu_name'             => _x( '%2$s', 'vehicle post type menu name', 'simontaxi' ),
		'featured_image'        => esc_html__( '%1$s Image', 'simontaxi' ),
		'set_featured_image'    => esc_html__( 'Set %1$s Image', 'simontaxi' ),
		'remove_featured_image' => esc_html__( 'Remove %1$s Image', 'simontaxi' ),
		'use_featured_image'    => esc_html__( 'Use as %1$s Image', 'simontaxi' ),
		'filter_items_list'     => esc_html__( 'Filter %2$s list', 'simontaxi' ),
		'items_list_navigation' => esc_html__( '%2$s list navigation', 'simontaxi' ),
		'items_list'            => esc_html__( '%2$s list', 'simontaxi' ),
	) );
	foreach ( $vehicle_labels as $key => $value ) {
		$vehicle_labels[ $key ] = sprintf( $value, simontaxi_get_label_singular(), simontaxi_get_label_plural() );
	}
	$vehicle_args = array(
		'labels'             => $vehicle_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => SIMONTAXI_SLUG , 'with_front' => false),
		'capability_type'    => 'post',
		'capabilities' => get_capabilities( 'manage_vehicles' ),
		'map_meta_cap'       => true,
		'has_archive'        => false,
		'hierarchical'       => false,
		'supports'           => apply_filters( 'simontaxi_vehicle_supports', array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ) ),
		'menu_position' => 5,
        'menu_icon'           => SIMONTAXI_PLUGIN_URL . '/images/logo-16x16.png',
		'register_meta_box_cb' => 'add_vehicle_metaboxes',
		'taxonomies' => array( 'vehicle_features' ),
	);

	register_post_type( 'vehicle', apply_filters( 'simontaxi_vehicle_post_type_args', $vehicle_args ) );
}

function add_vehicle_metaboxes() {
	 $fixed_point_title = simontaxi_get_option( 'fixed_point_title', 'Airport' );
	 
	 add_meta_box( 'vehicle_p2p_settings', 'P2P Settings', 'p2p_settings', 'vehicle', 'normal', 'high' );
	 add_meta_box( 'vehicle_airport_settings', $fixed_point_title . ' Settings', 'airport_settings', 'vehicle', 'normal', 'high' );
	 add_meta_box( 'vehicle_feature_settings', 'Features', 'feature_settings', 'vehicle', 'normal', 'high' );
	 add_meta_box( 'vehicle_gallery', 'Gallery', 'vehicle_gallery', 'vehicle', 'normal', 'high' );
	 
	 /**
	  * @since 2.0.9
	  */
	 add_meta_box( 'vehicle_other_information', 'Other Information', 'vehicle_other_information', 'vehicle', 'normal', 'high' );
	 
	 /**
	  * To add metaboxes through extensions
	  *
	  * @since 2.0.0
	  */
	  do_action( 'simontaxi_vehicle_other_metaboxes' );
	  
}

function vehicle_other_information() {
	global $post;
	$other_information = get_post_meta( $post->ID, 'other_information', true );
	// noncename needed to verify where the data originated

	echo '<input type="hidden" name="vehiclemeta_noncename" id="vehiclemeta_noncename" value="' .
        wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	wp_editor( $other_information, 'other_information' );
}

function vehicle_gallery() {
	global $post;

	// noncename needed to verify where the data originated
    echo '<input type="hidden" name="vehiclemeta_noncename" id="vehiclemeta_noncename" value="' .
        wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	?>
	<div id="vehicle_images_container">
		<ul class="vehicle_images">
			<?php
				if ( metadata_exists( 'post', $post->ID, 'vehicle_image_gallery' ) ) {
					$vehicle_image_gallery = get_post_meta( $post->ID, 'vehicle_image_gallery', true );
				} else {
					// Backwards compat
					$attachment_ids = get_posts( 'post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&meta_key=_vehicle_exclude_image&meta_value=0' );
					$attachment_ids = array_diff( $attachment_ids, array( get_post_thumbnail_id() ) );
					$vehicle_image_gallery = implode( ',', $attachment_ids );
				}

				$attachments         = array_filter( explode( ',', $vehicle_image_gallery ) );
				$update_meta         = false;
				$updated_gallery_ids = array();

				if ( ! empty( $attachments ) ) {
					foreach ( $attachments as $attachment_id ) {
						$attachment = wp_get_attachment_image( $attachment_id, 'thumbnail' );

						// if attachment is empty skip
						if ( empty( $attachment ) ) {
							$update_meta = true;
							continue;
						}

						echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
							' . $attachment . '
							<ul class="actions">
								<li><a href="#" class="delete tips" data-tip="' . esc_attr__( 'Delete image', 'simontaxi' ) . '">' . esc_html__( 'Delete', 'simontaxi' ) . '</a></li>
							</ul>
						</li>';

						// rebuild ids to be saved
						$updated_gallery_ids[] = $attachment_id;
					}

					// need to update product meta to set new gallery ids
					if ( $update_meta ) {
						update_post_meta( $post->ID, 'vehicle_image_gallery', implode( ',', $updated_gallery_ids ) );
					}
				}
			?>
		</ul>

		<input type="hidden" id="vehicle_image_gallery" name="vehicle_image_gallery" value="<?php echo esc_attr( $vehicle_image_gallery ); ?>" />

	</div>
	<p class="add_vehicle_images hide-if-no-js">
		<a href="#" data-choose="<?php esc_attr_e( 'Add Images to Vehicle Gallery', 'simontaxi' ); ?>" data-update="<?php esc_attr_e( 'Add to gallery', 'simontaxi' ); ?>" data-delete="<?php esc_attr_e( 'Delete image', 'simontaxi' ); ?>" data-text="<?php esc_attr_e( 'Delete', 'simontaxi' ); ?>"><?php esc_html_e( 'Add vehicle gallery images', 'simontaxi' ); ?></a>
	</p>
	<script type="text/javascript">

jQuery( function( $ ) {
		// Product gallery file uploads.
		var vehicle_gallery_frame;
		var $image_gallery_ids = $( '#vehicle_image_gallery' );
		var $vehicle_images    = $( '#vehicle_images_container' ).find( 'ul.vehicle_images' );
		$( '.add_vehicle_images' ).on( 'click', 'a', function( event ) {
			var $el = $( this );

			event.preventDefault();

			// If the media frame already exists, reopen it.
			if ( vehicle_gallery_frame ) {
				vehicle_gallery_frame.open();
				return;
			}

			// Create the media frame.
			vehicle_gallery_frame = wp.media.frames.product_gallery = wp.media({
				// Set the title of the modal.
				title: $el.data( 'choose' ),
				button: {
					text: $el.data( 'update' )
				},
				states: [
					new wp.media.controller.Library({
						title: $el.data( 'choose' ),
						filterable: 'all',
						multiple: true
					})
				]
			});

			// When an image is selected, run a callback.
			vehicle_gallery_frame.on( 'select', function() {
				var selection = vehicle_gallery_frame.state().get( 'selection' );
				var attachment_ids = $image_gallery_ids.val();

				selection.map( function( attachment ) {
					attachment = attachment.toJSON();

					if ( attachment.id ) {
						attachment_ids   = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
						var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

						$vehicle_images.append( '<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" /><ul class="actions"><li><a href="#" class="delete" title="' + $el.data( 'delete' ) + '">' + $el.data( 'text' ) + '</a></li></ul></li>' );
					}
				});

				$image_gallery_ids.val( attachment_ids );
			});

			// Finally, open the modal.
			vehicle_gallery_frame.open();
		});

		// Image ordering.
		$vehicle_images.sortable({
			items: 'li.image',
			cursor: 'move',
			scrollSensitivity: 40,
			forcePlaceholderSize: true,
			forceHelperSize: false,
			helper: 'clone',
			opacity: 0.65,
			placeholder: 'wc-metabox-sortable-placeholder',
			start: function( event, ui ) {
				ui.item.css( 'background-color', '#f6f6f6' );
			},
			stop: function( event, ui ) {
				ui.item.removeAttr( 'style' );
			},
			update: function() {
				var attachment_ids = '';

				$( '#vehicle_images_container' ).find( 'ul li.image' ).css( 'cursor', 'default' ).each( function() {
					var attachment_id = $( this ).attr( 'data-attachment_id' );
					attachment_ids = attachment_ids + attachment_id + ',';
				});

				$image_gallery_ids.val( attachment_ids );
			}
		});

		// Remove images.
		$( '#vehicle_images_container' ).on( 'click', 'a.delete', function() {
			$( this ).closest( 'li.image' ).remove();

			var attachment_ids = '';

			$( '#vehicle_images_container' ).find( 'ul li.image' ).css( 'cursor', 'default' ).each( function() {
				var attachment_id = $( this ).attr( 'data-attachment_id' );
				attachment_ids = attachment_ids + attachment_id + ',';
			});

			$image_gallery_ids.val( attachment_ids );

			// Remove any lingering tooltips.
			$( '#tiptip_holder' ).removeAttr( 'style' );
			$( '#tiptip_arrow' ).removeAttr( 'style' );

			return false;
		});
});
	</script>
	<?php
}
function p2p_settings() {
	global $post;
	$p2p_basic_distance = get_post_meta( $post->ID, 'p2p_basic_distance', true );
	$p2p_basic_price = get_post_meta( $post->ID, 'p2p_basic_price', true );
	$p2p_unit_price = get_post_meta( $post->ID, 'p2p_unit_price', true );
	// noncename needed to verify where the data originated
	
	$vehicle_distance = simontaxi_get_option( 'vehicle_distance', 'km' );
	

	echo '<input type="hidden" name="vehiclemeta_noncename" id="vehiclemeta_noncename" value="' .
        wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
?>
<table class="st-table-vehicles">
	<?php do_action('p2p_settings_vehicle_post_type_top'); ?>
	<tr>
		<td>
			<div class="form-field">
				 <label for="p2p_basic_distance"><?php echo esc_html__( 'Basic Distance', 'simontaxi' ) . ' ' . $vehicle_distance; ?></label>
				 <input type="number"  name="p2p_basic_distance" id="p2p_basic_distance" value="<?php echo esc_attr( $p2p_basic_distance );?>" placeholder="<?php echo esc_attr__( 'Basic Distance', 'simontaxi' ) . ' ' . $vehicle_distance; ?>" step="0.1" min="0"/>
			</div>

		</td>
		<td>

			 <div class="form-field">
				 <label for="p2p_basic_price"><?php echo esc_html__( 'Basic Price', 'simontaxi' ) . ' ' . simontaxi_get_currency(); ?></label>
				 <input type="number" name="p2p_basic_price" id="p2p_basic_price" value="<?php echo esc_attr( $p2p_basic_price );?>" placeholder="<?php echo esc_attr__( 'Basic Price', 'simontaxi' ) . ' ' . simontaxi_get_currency(); ?>" step="0.1" min="0"/>
			</div>

		</td>

		<td>
			<div class="form-field">
				 <label for="p2p_unit_price"><?php echo esc_html__( 'Price per Standard Unit Distance', 'simontaxi' ) . ' ' . simontaxi_get_currency(); ?></label>
				 <input type="number" name="p2p_unit_price" id="p2p_unit_price" value="<?php echo esc_attr( $p2p_unit_price );?>" placeholder="<?php echo esc_attr__( 'Price per Standard Unit Distance', 'simontaxi' ) . ' ' . simontaxi_get_currency(); ?>" step="0.1" min="0"/>
			</div>

		</td>

	</tr>
	<?php do_action('p2p_settings_vehicle_post_type_bottom'); ?>
</table>
<?php
}

function airport_settings()
{
	global $post;
	$to_airport_basic_distance = get_post_meta( $post->ID, 'to_airport_basic_distance', true );
	$to_airport_basic_price = get_post_meta( $post->ID, 'to_airport_basic_price', true );
	$to_airport_unit_price = get_post_meta( $post->ID, 'to_airport_unit_price', true );

	$from_airport_basic_distance = get_post_meta( $post->ID, 'from_airport_basic_distance', true );
	$from_airport_basic_price = get_post_meta( $post->ID, 'from_airport_basic_price', true );
	$from_airport_unit_price = get_post_meta( $post->ID, 'from_airport_unit_price', true );
	// noncename needed to verify where the data originated
    echo '<input type="hidden" name="vehiclemeta_noncename" id="vehiclemeta_noncename" value="' .
        wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
		
	$fixed_point_title = simontaxi_get_option( 'fixed_point_title', 'Airport' );
	
	$vehicle_distance = simontaxi_get_option( 'vehicle_distance', 'km' );
?>
<table class="st-table-vehicles">
	<?php do_action('airport_settings_vehicle_post_type_top'); ?>
	<tr><td colspan="2"> <b><?php esc_html_e( 'Traveling To ' .  $fixed_point_title, 'simontaxi' );?></b> </td></tr>
	<tr>
		<td>
			<div class="form-field">
				 <label for="to_airport_basic_distance"><?php echo esc_html__( 'Basic Distance', 'simontaxi' ) . ' ' . $vehicle_distance; ?></label>
				 <input type="number" name="to_airport_basic_distance" id="to_airport_basic_distance" value="<?php echo esc_attr( $to_airport_basic_distance );?>" placeholder="<?php echo esc_attr__( 'Basic Distance', 'simontaxi' ) . ' ' . $vehicle_distance; ?>" step="0.1" min="0"/>
			</div>

		</td>
		<td>

			 <div class="form-field">
				 <label for="to_airport_basic_price"><?php echo esc_html__( 'Basic Price', 'simontaxi' ) . ' ' . simontaxi_get_currency(); ?></label>
				 <input type="number" name="to_airport_basic_price" id="to_airport_basic_price" value="<?php echo esc_attr( $to_airport_basic_price );?>" placeholder="<?php echo esc_attr__( 'Basic Price', 'simontaxi' ) . ' ' . simontaxi_get_currency(); ?>" step="0.1" min="0"/>
			</div>

		</td>

		<td>
			<div class="form-field">
				 <label for="to_airport_unit_price"><?php echo esc_html__( 'Price per Standard Unit Distance', 'simontaxi' ) . ' ' . simontaxi_get_currency(); ?></label>
				 <input type="number" name="to_airport_unit_price" id="to_airport_unit_price" value="<?php echo esc_attr( $to_airport_unit_price );?>" placeholder="<?php echo esc_attr__( 'Price per Standard Unit Distance', 'simontaxi' ) . ' ' . simontaxi_get_currency(); ?>" step="0.1" min="0"/>
			</div>

		</td>

	</tr>

	<tr><td colspan="2"> <b><?php esc_html_e( 'Traveling From ' . $fixed_point_title, 'simontaxi' );?></b> </td></tr>
	<tr>
		<td>
			<div class="form-field">
				 <label for="from_airport_basic_distance"><?php echo esc_html__( 'Basic Distance', 'simontaxi' ) . ' ' . $vehicle_distance; ?></label>
				 <input type="number" name="from_airport_basic_distance" id="from_airport_basic_distance" value="<?php echo esc_attr( $from_airport_basic_distance );?>" placeholder="<?php echo esc_attr__( 'Basic Distance', 'simontaxi' ) . ' ' . $vehicle_distance; ?>" step="0.1" min="0"/>
			</div>

		</td>
		<td>

			 <div class="form-field">
				 <label for="from_airport_basic_price"><?php echo esc_html__( 'Basic Price', 'simontaxi' ) . ' ' . simontaxi_get_currency(); ?></label>
				 <input type="number" name="from_airport_basic_price" id="from_airport_basic_price" value="<?php echo esc_attr( $from_airport_basic_price );?>" placeholder="<?php echo esc_attr__( 'Basic Price', 'simontaxi' ) . ' ' . simontaxi_get_currency(); ?>" step="0.1" min="0"/>
			</div>

		</td>

		<td>
			<div class="form-field">
				 <label for="from_airport_unit_price"><?php echo esc_html__( 'Price per Standard Unit Distance', 'simontaxi' ) . ' ' . simontaxi_get_currency(); ?></label>
				 <input type="number" name="from_airport_unit_price" id="from_airport_unit_price" value="<?php echo esc_attr( $from_airport_unit_price );?>" placeholder="<?php echo esc_attr__( 'Price per Standard Unit Distance', 'simontaxi' ). ' ' . simontaxi_get_currency(); ?>" step="0.1" min="0"/>
			</div>

		</td>

	</tr>
	
	<?php do_action('airport_settings_vehicle_post_type_bottom'); ?>

</table>
<?php
}

function feature_settings()
{
	global $post;
	$number_of_vehicles = get_post_meta( $post->ID, 'number_of_vehicles', true );
	$seating_capacity = get_post_meta( $post->ID, 'seating_capacity', true );
	$luggage = get_post_meta( $post->ID, 'luggage', true );
	$luggage_type = get_post_meta( $post->ID, 'luggage_type', true );
	$luggage_type_symbol = get_post_meta( $post->ID, 'luggage_type_symbol', true );
	
	$vehicle_no = get_post_meta( $post->ID, 'vehicle_no', true );
	
	$vehicle_distance = simontaxi_get_option( 'vehicle_distance', 'km' );
	// noncename needed to verify where the data originated
    echo '<input type="hidden" name="vehiclemeta_noncename" id="vehiclemeta_noncename" value="' .
        wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
?>
<table class="st-table-vehicles">
	<?php do_action('feature_settings_vehicle_post_type_top'); ?>
	<tr>
		<td>
			<div class="form-field">
				 <label for="number_of_vehicles"><?php esc_html_e( 'Number of Vehicles / Number of bookings accepted per day', 'simontaxi' ); ?></label>
				 <input type="number" min="0" name="number_of_vehicles" id="number_of_vehicles" value="<?php echo esc_attr( $number_of_vehicles );?>" placeholder="<?php esc_attr_e( 'Number of Vehicles / Number of bookings accepted per day', 'simontaxi' ); ?>"/>
				 <p><small><?php esc_html_e( '"0" means only on request', 'simontaxi' ); ?></small></p>
			</div>

		</td>
		<td>
			<div class="form-field">
				 <label for="seating_capacity"><?php esc_html_e( 'Seating Capacity', 'simontaxi' ); ?></label>
				 <input type="number" min="1" name="seating_capacity" id="seating_capacity" value="<?php echo esc_attr( $seating_capacity );?>" placeholder="<?php esc_attr_e( 'Seating Capacity', 'simontaxi' ); ?>"/>
			</div>
		</td>
		<td>
			<div class="form-field">
				 <label for="vehicle_no"><?php esc_html_e( 'License plate', 'simontaxi' ); ?></label>
				 <input type="text" name="vehicle_no" id="vehicle_no" value="<?php echo esc_attr( $vehicle_no );?>" placeholder="<?php esc_attr_e( 'License plate', 'simontaxi' ); ?>"/>
			</div>
		</td>
	</tr>
	
	<tr>
		<td colspan="3">
		
		<table><tr><td>
		<label for="luggage"><?php esc_html_e( 'Luggage', 'simontaxi' ); ?></label>
				 <input type="number" min="0" name="luggage" id="luggage" value="<?php echo esc_attr( $luggage );?>" placeholder="<?php esc_attr_e( 'Luggage', 'simontaxi' ); ?>" class="st-input-number"/>&nbsp;
				 <select name="luggage_type" id="luggage_type">
					<optgroup label="General">
						<option value="Small" <?php if( $luggage_type == 'Small' ) echo 'selected'?>><?php esc_html_e( 'Small', 'simontaxi' );?></option>
						<option value="Medium" <?php if( $luggage_type == 'Medium' ) echo 'selected'?>><?php esc_html_e( 'Medium', 'simontaxi' );?></option>
						<option value="Large" <?php if( $luggage_type == 'Large' ) echo 'selected'?>><?php esc_html_e( 'Large', 'simontaxi' );?></option>
					</optgroup>

					<optgroup label="Weights">
						<option value="Kilogram" <?php if( $luggage_type == 'Kilogram' ) echo 'selected'?>><?php esc_html_e( 'Kilogram', 'simontaxi' );?></option>
						<option value="Gram" <?php if( $luggage_type == 'Gram' ) echo 'selected'?>><?php esc_html_e( 'Gram', 'simontaxi' );?></option>
						<option value="Milligram" <?php if( $luggage_type == 'Milligram' ) echo 'selected'?>><?php esc_html_e( 'Milligram', 'simontaxi' );?></option>
						<option value="Microgram" <?php if( $luggage_type == 'Microgram' ) echo 'selected'?>><?php esc_html_e( 'Microgram', 'simontaxi' );?></option>
						<option value="Imperial ton" <?php if( $luggage_type == 'Imperial ton' ) echo 'selected'?>><?php esc_html_e( 'Imperial ton', 'simontaxi' );?></option>
						<option value="US ton" <?php if( $luggage_type == 'US ton' ) echo 'selected'?>><?php esc_html_e( 'US ton', 'simontaxi' );?></option>
						<option value="Stone" <?php if( $luggage_type == 'Stone' ) echo 'selected'?>><?php esc_html_e( 'Stone', 'simontaxi' );?></option>
						<option value="Pound" <?php if( $luggage_type == 'Pound' ) echo 'selected'?>><?php esc_html_e( 'Pound', 'simontaxi' );?></option>
						<option value="Ounce" <?php if( $luggage_type == 'Ounce' ) echo 'selected'?>><?php esc_html_e( 'Ounce', 'simontaxi' );?></option>
						<option value="Tonne" <?php if( $luggage_type == 'Tonne' ) echo 'selected'?>><?php esc_html_e( 'Tonne', 'simontaxi' );?></option>
					</optgroup>
				 </select>&nbsp;
				 <input type="text" name="luggage_type_symbol" id="luggage_type_symbol" value="<?php echo esc_attr( $luggage_type_symbol );?>" placeholder="<?php esc_attr_e( 'Luggage Type Symbol. Eg: KG', 'simontaxi' ); ?>" class="st-input-luggage_type_symbol" style="width:35%"/>
		</td><td>
		<?php
			$luggage2 = get_post_meta( $post->ID, 'luggage2', true );
			$luggage2_type = get_post_meta( $post->ID, 'luggage2_type', true );
			$luggage2_type_symbol = get_post_meta( $post->ID, 'luggage2_type_symbol', true );
			?>
			 
				 <label for="luggage2"><?php esc_html_e( 'Luggage-2', 'simontaxi' ); ?></label>
				 <input type="number" min="0" name="luggage2" id="luggage2" value="<?php echo esc_attr( $luggage2 );?>" placeholder="<?php esc_attr_e( 'Luggage-2', 'simontaxi' ); ?>" class="st-input-number"/>&nbsp;
				 <select name="luggage2_type" id="luggage2_type">
					<optgroup label="General">
						<option value="Small" <?php if( $luggage2_type == 'Small' ) echo 'selected'?>><?php esc_html_e( 'Small', 'simontaxi' );?></option>
						<option value="Medium" <?php if( $luggage2_type == 'Medium' ) echo 'selected'?>><?php esc_html_e( 'Medium', 'simontaxi' );?></option>
						<option value="Large" <?php if( $luggage2_type == 'Large' ) echo 'selected'?>><?php esc_html_e( 'Large', 'simontaxi' );?></option>
					</optgroup>

					<optgroup label="Weights">
						<option value="Kilogram" <?php if( $luggage2_type == 'Kilogram' ) echo 'selected'?>><?php esc_html_e( 'Kilogram', 'simontaxi' );?></option>
						<option value="Gram" <?php if( $luggage2_type == 'Gram' ) echo 'selected'?>><?php esc_html_e( 'Gram', 'simontaxi' );?></option>
						<option value="Milligram" <?php if( $luggage2_type == 'Milligram' ) echo 'selected'?>><?php esc_html_e( 'Milligram', 'simontaxi' );?></option>
						<option value="Microgram" <?php if( $luggage2_type == 'Microgram' ) echo 'selected'?>><?php esc_html_e( 'Microgram', 'simontaxi' );?></option>
						<option value="Imperial ton" <?php if( $luggage2_type == 'Imperial ton' ) echo 'selected'?>><?php esc_html_e( 'Imperial ton', 'simontaxi' );?></option>
						<option value="US ton" <?php if( $luggage2_type == 'US ton' ) echo 'selected'?>><?php esc_html_e( 'US ton', 'simontaxi' );?></option>
						<option value="Stone" <?php if( $luggage2_type == 'Stone' ) echo 'selected'?>><?php esc_html_e( 'Stone', 'simontaxi' );?></option>
						<option value="Pound" <?php if( $luggage2_type == 'Pound' ) echo 'selected'?>><?php esc_html_e( 'Pound', 'simontaxi' );?></option>
						<option value="Ounce" <?php if( $luggage2_type == 'Ounce' ) echo 'selected'?>><?php esc_html_e( 'Ounce', 'simontaxi' );?></option>
						<option value="Tonne" <?php if( $luggage2_type == 'Tonne' ) echo 'selected'?>><?php esc_html_e( 'Tonne', 'simontaxi' );?></option>
					</optgroup>
				 </select>&nbsp;
				 <input type="text" name="luggage2_type_symbol" id="luggage2_type_symbol" value="<?php echo esc_attr( $luggage2_type_symbol );?>" placeholder="<?php esc_attr_e( 'Luggage-2 Type Symbol. Eg: KG', 'simontaxi' ); ?>" class="st-input-luggage_type_symbol" style="width:35%"/>
			
		</td></tr>
		</table>
		
		</td>
	</tr>
	<?php
	/**
	 * Added time restriction optional feature for admin. Eg: If some one book particular vehicle on particular time, Let us say vehicle1 is booked for 12/04/2018 at 12pm, Other customer should not be able to book same vehicle at same time to avoid clashes
	 *
	 * @since 2.0.8
	 */
	?>
	<tr>
		<td colspan="3">
		
		<table><tr><td>
		<div class="form-field">
				 <label for="apply_time_restriction"><?php esc_html_e( 'Apply Time Restriction / Allow to book for different times in a day?', 'simontaxi' ); ?></label>
				 <?php
				 $apply_time_restriction = get_post_meta( $post->ID, 'apply_time_restriction', true );
				 ?>
				 <select name="apply_time_restriction" id="apply_time_restriction" title="<?php esc_html_e( 'If some one book particular vehicle on particular time, Let us say vehicle1 is booked for 12/04/2018 at 12pm, Other customer should not be able to book same vehicle at same time to avoid clashes.', 'simontaxi' ); ?>">
					<option value="no" <?php if ( 'no' === $apply_time_restriction ) echo 'selected'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
					<option value="yes" <?php if ( 'yes' === $apply_time_restriction ) echo 'selected'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
				 </select>
				 <?php echo simontaxi_get_help( 'If some one book particular vehicle on particular time, Let us say "vehicle1" is booked for 12/04/2018 at 12pm, Other customer should not be able to book same vehicle at same time to avoid clashes. Which means the vehicle is not available for till the current ride is completed. This will do with google provided time. Suppose if "vehicle1" booked at 12/04/2018 at 12pm and the google estimated time to finish ride is 2Hours the vehicle available for booking after 2 hours only on same day!.' ); ?>
				 
				 <?php
				$transition_time = get_post_meta( $post->ID, 'transition_time', true );
				$transition_time_type = get_post_meta( $post->ID, 'transition_time_type', true );
				?>
				 <input type="number" name="transition_time" id="transition_time" value="<?php echo esc_attr( $transition_time );?>" placeholder="<?php esc_attr_e( 'Transition Time', 'simontaxi' ); ?>" class="st-input-transition_time"/>
				 <?php echo simontaxi_get_help( 'Ideal time between booking to boking. It will valid if the vehicle allowed to book different time schedules in a day.' ); ?>
				 <select name="transition_time_type" id="transition_time_type" title="<?php esc_html_e( 'Transition Time Type', 'simontaxi' ); ?>">
					<option value="minutes" <?php if ( 'minutes' === $transition_time_type ) echo 'selected'; ?>><?php esc_html_e( 'Minutes', 'simontaxi' ); ?></option>
					<option value="hours" <?php if ( 'hours' === $transition_time_type ) echo 'selected'; ?>><?php esc_html_e( 'Hours', 'simontaxi' ); ?></option>
				 </select>
			</div>
		</td><td>
		
		 <label for="apply_seats_restriction"><?php esc_html_e( 'Apply Seats Restriction', 'simontaxi' ); ?></label>
		 <?php
		 $apply_seats_restriction = get_post_meta( $post->ID, 'apply_seats_restriction', true );
		 ?>
		 <select name="apply_seats_restriction" id="apply_seats_restriction" title="<?php esc_html_e( 'If this option set to "yes" and this vehicle has 4 seats, user is searching for a booking with 5 passengers, then this vehicle is not available for booking', 'simontaxi' ); ?>">
			<option value="no" <?php if ( 'no' === $apply_seats_restriction ) echo 'selected'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
			<option value="yes" <?php if ( 'yes' === $apply_seats_restriction ) echo 'selected'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
		 </select>
		 <?php echo simontaxi_get_help( 'If this option set to "yes" and this vehicle has 4 seats, user is searching for a booking with 5 passengers, then this vehicle is not available for booking' ); ?>
		
		</td></tr>
		</table>
			
		</td>
	</tr>
	
	<tr><td rowspan="2">
		<div class="form-field">
			 <label for="apply_peakseason_rates"><?php esc_html_e( 'Apply Peak Season Rates', 'simontaxi' ); ?></label>
			 <?php
			 $apply_peakseason_rates = get_post_meta( $post->ID, 'apply_peakseason_rates', true );
			 if ( empty( $apply_peakseason_rates ) ) {
				 $apply_peakseason_rates = 'no';
			 }
			 ?>
			 <select name="apply_peakseason_rates" id="apply_peakseason_rates" title="<?php esc_html_e( 'Apply Peak Season Rates', 'simontaxi' ); ?>">
				<option value="no" <?php if ( 'no' === $apply_peakseason_rates ) echo 'selected'; ?>><?php esc_html_e( 'No', 'simontaxi' ); ?></option>
				<option value="yes" <?php if ( 'yes' === $apply_peakseason_rates ) echo 'selected'; ?>><?php esc_html_e( 'Yes', 'simontaxi' ); ?></option>
			 </select>
		</div>
	</td>
	<td>&nbsp;</td>
	</tr>
	
	<tr><td rowspan="2">	
	<div class="form-field">
		 <label for="vehicle_available_for"><?php esc_html_e( 'Available for', 'simontaxi' ); ?></label>
		 
		 <?php 
		$booking_types = simontaxi_booking_types();
		$vehicle_available_for = array_keys( (array) json_decode( get_post_meta( $post->ID, 'vehicle_available_for', true ) ) );
		if ( empty( $vehicle_available_for ) ) {
			$vehicle_available_for = array_keys( $booking_types );
		}
		foreach( $booking_types as $key => $booking_type ) {
			?>
			<input type="checkbox" <?php if(is_array( $vehicle_available_for)) { if ( in_array( $key, $vehicle_available_for)) { ?>checked="checked"<?php }} ?> name="vehicle_available_for[<?php echo $key; ?>]" value="<?php esc_html_e( $booking_type, 'simontaxi' ); ?>"><?php echo esc_html( $booking_type ); ?> &nbsp;
			<?php
		}
		?>
	</div>	
	</td>
	<td>&nbsp;</td>
	</tr>
	
	<tr>
	<td>	
	<div class="form-field">
		 <label for="vehicle_base_location"><?php esc_html_e( 'Vehicle Base Location', 'simontaxi' ); ?></label>
		 <?php
		 $vehicle_base_location = get_post_meta( $post->ID, 'vehicle_base_location', true );
		 if ( empty( $vehicle_base_location ) ) {
			 $vehicle_base_location = simontaxi_get_option( 'garage_address', '' );
		 }
		 ?>
		 <input type="text" name="vehicle_base_location" id="vehicle_base_location" value="<?php echo esc_attr( $vehicle_base_location );?>" placeholder="<?php esc_attr_e( 'Vehicle Base Location', 'simontaxi' ); ?>" class="st-input-luggage_type_symbol" onClick="initialize(this.id);" onFocus="initialize(this.id);"/>
	</div>	
	</td>
	<td>	
	<div class="form-field">
		 <label for="minimum_distance_to_handle_booking"><?php echo esc_html__( 'Minimum Distance to handle booking', 'simontaxi' ) . ' ' . $vehicle_distance; ?></label>
		 <?php
		 $minimum_distance_to_handle_booking = get_post_meta( $post->ID, 'minimum_distance_to_handle_booking', true );
		 if ( empty( $minimum_distance_to_handle_booking ) ) {
			 $minimum_distance_to_handle_booking = 0;
		 }
		 ?>
		 <input type="number" name="minimum_distance_to_handle_booking" id="minimum_distance_to_handle_booking" value="<?php echo esc_attr( $minimum_distance_to_handle_booking );?>" placeholder="<?php echo esc_attr__( 'Minimum Distance to handle booking', 'simontaxi' ). ' ' . $vehicle_distance; ?>" class="st-input-luggage_type_symbol"/>
		 <small><?php esc_html_e('"0" means no limit.'); ?></small>
	</div>	
	</td>
	<td>&nbsp;</td>
	</tr>
	
	<tr valign="top">
		<td class="titledesc" scope="row">
			<label for="farecalculation_basedon"><?php esc_html_e( 'Fare Calculation Based On', 'simontaxi' ); ?></label>
		
			<?php
			/**
			 * Fare calculation can be set at vehicle level.
			 *
			 * @since 2.0.9
			 */
			$farecalculation_basedon = get_post_meta( $post->ID, 'minimum_distance_to_handle_booking', true );
			if ( empty( $farecalculation_basedon ) ) {
				$farecalculation_basedon = simontaxi_get_option( 'farecalculation_basedon', 'basicfare' );
			}
			?>
			<select id="farecalculation_basedon" name="farecalculation_basedon" title="<?php esc_html_e( 'Fare Calculation Based On', 'simontaxi' ); ?>" style="width: 25em;">
				<option value="basicfare" <?php if ( isset ( $farecalculation_basedon) && $farecalculation_basedon == 'basicfare' ) { echo 'selected'; }?>><?php esc_html_e( 'Basic Fare', 'simontaxi' ); ?></option>
				<option value="predefined" <?php if ( isset ( $farecalculation_basedon) && $farecalculation_basedon == 'predefined' ) { echo 'selected'; }?>><?php esc_html_e( 'Predefined Charges', 'simontaxi' ); ?></option>
			</select><?php echo simontaxi_get_help( 'Specifies the fare calculation based on which criteria for user in front end. This value will override the global value at "Settings -> General -> Fare Calculation Based On"' ); ?>
		</td>
		<td colspan="2">
		<?php if ( simontaxi_is_user( 'driver' ) ) {
			echo '<input type="hidden" name="vehicle_display_status" id="vehicle_display_status" value="nodisplay">&nbsp;';
		} else { ?>
		<div class="form-field">
			 <label for="vehicle_display_status"><?php echo esc_html__( 'Display Status', 'simontaxi' ); ?></label>
			 <?php
			 $vehicle_display_status = get_post_meta( $post->ID, 'vehicle_display_status', true );
			 if ( empty( $vehicle_display_status ) ) {
				 $vehicle_display_status = 'display';
			 }
			 ?>
			 <select id="vehicle_display_status" name="vehicle_display_status" title="<?php esc_html_e( 'Vehicle Display Status', 'simontaxi' ); ?>" style="width: 25em;">
				<option value="display" <?php if ( isset ( $vehicle_display_status) && $vehicle_display_status == 'display' ) { echo 'selected'; }?>><?php esc_html_e( 'Display', 'simontaxi' ); ?></option>
				<option value="nodisplay" <?php if ( isset ( $vehicle_display_status) && $vehicle_display_status == 'nodisplay' ) { echo 'selected'; }?>><?php esc_html_e( 'No Display', 'simontaxi' ); ?></option>
			</select>
		</div>	
		<?php } ?>
		</td>
	</tr>
	<tr><td colspan="3">
	<label for="minimum_fare"><?php esc_html_e( 'Minimum Fare', 'simontaxi' ); ?></label>
	<?php
	 $minimum_fare = get_post_meta( $post->ID, 'minimum_fare', true );
	 if ( empty( $minimum_fare ) ) {
		 $minimum_fare = 0;
	 }
	 ?>
	 <input type="number" name="minimum_fare" id="minimum_fare" value="<?php echo esc_attr( $minimum_fare );?>" placeholder="<?php echo esc_attr__( 'Minimum Fare', 'simontaxi' ). ' ' . simontaxi_get_currency(); ?>" class="st-input-luggage_type_symbol" step="0.01"/>
	 &nbsp;
	 <?php
	 /*
	 $minimum_fare_on = get_post_meta( $post->ID, 'minimum_fare_on', true );
	 if ( empty( $minimum_fare_on ) ) {
		 $minimum_fare_on = 0;
	 }
	 ?>
	 <select id="minimum_fare_on" name="minimum_fare_on" title="<?php esc_html_e( 'Minimum Fare On', 'simontaxi' ); ?>" style="width: 25em;">
		<option value="basicfare" <?php if ( $minimum_fare_on == 'basicfare' ) { echo 'selected'; }?>><?php esc_html_e( 'Basic Fare', 'simontaxi' ); ?></option>
		<option value="totalamount" <?php if ( $minimum_fare_on == 'totalamount' ) { echo 'selected'; }?>><?php esc_html_e( 'Total Amount (Includes all)', 'simontaxi' ); ?></option>
		<option value="totalamount_exclude_tax" <?php if ( $minimum_fare_on == 'totalamount_exclude_tax' ) { echo 'selected'; }?>><?php esc_html_e( 'Total Amount (Exclude Tax)', 'simontaxi' ); ?></option>
	</select>
	<?php */ ?>
	</td></tr>
	<?php do_action('feature_settings_vehicle_post_type_bottom'); ?>
</table>

<?php
$vehicle_country = simontaxi_get_option( 'vehicle_country', 'US' );
$vehicle_country_region_from = simontaxi_get_option( 'vehicle_country_region_from', '' );
$vehicle_country_region_to = simontaxi_get_option( 'vehicle_country_region_to', '' );

$vehicle_country_dropoff = simontaxi_get_option( 'vehicle_country_dropoff', 'US' );
$vehicle_places = simontaxi_get_option( 'vehicle_places', 'googleall' );

$vehicle_places = simontaxi_get_option( 'vehicle_places', 'googleall' );
$vehicle_country_dropoff_region_from = simontaxi_get_option( 'vehicle_country_dropoff_region_from', '' );
$vehicle_country_dropoff_region_to = simontaxi_get_option( 'vehicle_country_dropoff_region_to', '' );
$regionBounds_set = false;
$google_api = simontaxi_get_option( 'google_api', 'AIzaSyCqRV6HQ_BSw3MMjPen2bT2IwDnZgfjwu4' );
?>
<script src="//maps.googleapis.com/maps/api/js?libraries=places&key=<?php echo $google_api; ?>"></script>
<script>
function initialize(id) {
	var input = jQuery( '#' + id);

	var regionBounds_set = false;

	if ( id == 'drop_location' ) {
		var selected_country = '<?php echo $vehicle_country_dropoff; ?>';
		<?php if ( '' !== $vehicle_country_dropoff_region_from && '' !== $vehicle_country_dropoff_region_to ) : ?>
		<?php
		$vehicle_country_dropoff_region_from_parts = explode( ',', $vehicle_country_dropoff_region_from );
		$vehicle_country_dropoff_region_to_parts = explode( ',', $vehicle_country_dropoff_region_to );
		?>

		<?php if ( ! empty( $vehicle_country_dropoff_region_from_parts[1] ) 
			&& ! empty( $vehicle_country_dropoff_region_to_parts[1] )
		&& ! empty( $vehicle_country_dropoff_region_from_parts[0] ) 
		&& ! empty( $vehicle_country_dropoff_region_to_parts[0] ) ) { ?>
			var regionBounds = new google.maps.LatLngBounds(
			new google.maps.LatLng(<?php echo $vehicle_country_dropoff_region_from_parts[1]; ?>,<?php echo $vehicle_country_dropoff_region_to_parts[1]; ?>),
			new google.maps.LatLng(<?php echo $vehicle_country_dropoff_region_from_parts[0]; ?>,<?php echo $vehicle_country_dropoff_region_to_parts[0]; ?>) );

			regionBounds_set = true;
		<?php
		$regionBounds_set = true;
		} ?>
		<?php endif; ?>

	} else {
		var selected_country = '<?php echo $vehicle_country; ?>';
		<?php if ( '' !== $vehicle_country_region_from && '' !== $vehicle_country_region_to ) : ?>
		<?php
		$vehicle_country_region_from_parts = explode( ',', $vehicle_country_region_from );
		$vehicle_country_region_to_parts = explode( ',', $vehicle_country_region_to );
		?>
		<?php if ( ! empty( $vehicle_country_region_from_parts[1] ) 
			&& ! empty( $vehicle_country_region_to_parts[1] ) 
		&& ! empty( $vehicle_country_region_from_parts[0] ) 
		&& ! empty( $vehicle_country_region_to_parts[0] ) ) { ?>

		var regionBounds = new google.maps.LatLngBounds(
		new google.maps.LatLng(<?php echo (float) $vehicle_country_region_from_parts[1]; ?>,<?php echo (float) $vehicle_country_region_to_parts[1]; ?>),
		new google.maps.LatLng(<?php echo (float) $vehicle_country_region_from_parts[0]; ?>,<?php echo (float) $vehicle_country_region_to_parts[0]; ?>) );

		regionBounds_set = true;

		<?php
		$regionBounds_set = true;
		} ?>

		<?php endif; ?>
	}

	var options = {
		language: 'en-GB',
		<?php
		/**
		* If the admin impose restriction on places, then we are taking only regions (Important places). Reference : https://developers.google.com/places/supported_types
		* Regions: locality (Name)
			sublocality
			postal_code
			country
			administrative_area_level_1 (State)
			administrative_area_level_2 (District)
		* Cities: locality
			administrative_area_level_3
		*/
		if ( $vehicle_places == 'googleregions' ) {
		?>
		types: ['(regions)'],
		<?php }
		if ( $vehicle_places == 'googlecities' ) {
		?>
		types: ['(cities)'],
		<?php }
		?>
		<?php
		/**
		 * We have received many requests to restrict the region to book, so here is solution!
		 */
		if ( 'predefined' !== $vehicle_places && $regionBounds_set ) { ?>
		bounds: regionBounds,
		strictBounds: true,
		<?php } ?>
		componentRestrictions: {
			country: selected_country
		}
	};
    var autocomplete_my = new google.maps.places.Autocomplete(input[0], options);

	google.maps.event.addListener(autocomplete_my, 'place_changed', function () {
        place = autocomplete_my.getPlace();
		// console.log( place );
        jQuery( '#' + id + '_lat' ).val(place.geometry.location.lat() );
        jQuery( '#' + id + '_lng' ).val(place.geometry.location.lng() );

        if (place.address_components) {
            stateID = place.address_components[0] && place.address_components[0].long_name || '';
        }
        if ( place.name ) {
			stateID = place.name;
		} else {
			stateID = place.formatted_address;
		}
		stateID = place.formatted_address;
        input.blur();
        input.val(stateID);
    });
}
</script>
<?php
}

// Save the Metabox Data
function simontaxi_save_vehicle_meta( $post_id, $post ) {
	// verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( isset( $_POST['vehiclemeta_noncename'] ) ) {

		if ( ! wp_verify_nonce( $_POST['vehiclemeta_noncename'], plugin_basename( __FILE__ ) ) ) {
			return $post->ID;
        }

        // Is the user allowed to edit the post or page?
        if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return $post->ID;
		}
        // OK, we're authenticated: we need to find and save the data
        // We'll put it into an array to make it easier to loop though.


        $vehicle_meta['p2p_basic_distance'] = $_POST['p2p_basic_distance'];
		$vehicle_meta['p2p_basic_price'] = $_POST['p2p_basic_price'];
		$vehicle_meta['p2p_unit_price'] = $_POST['p2p_unit_price'];

		$vehicle_meta['to_airport_basic_distance'] = $_POST['to_airport_basic_distance'];
		$vehicle_meta['to_airport_basic_price'] = $_POST['to_airport_basic_price'];
		$vehicle_meta['to_airport_unit_price'] = $_POST['to_airport_unit_price'];

		$vehicle_meta['from_airport_basic_distance'] = $_POST['from_airport_basic_distance'];
		$vehicle_meta['from_airport_basic_price'] = $_POST['from_airport_basic_price'];
		$vehicle_meta['from_airport_unit_price'] = $_POST['from_airport_unit_price'];

		/**
		 * @since 2.0.0
		*/
		$vehicle_meta['number_of_vehicles'] = $_POST['number_of_vehicles'];
		
		$vehicle_meta['seating_capacity'] = $_POST['seating_capacity'];
		$vehicle_meta['vehicle_no'] = $_POST['vehicle_no'];
		
		$vehicle_meta['luggage'] = $_POST['luggage'];
		$vehicle_meta['luggage_type'] = $_POST['luggage_type'];
		$vehicle_meta['luggage_type_symbol'] = $_POST['luggage_type_symbol'];
		
		$vehicle_meta['luggage2'] = $_POST['luggage2'];
		$vehicle_meta['luggage2_type'] = $_POST['luggage2_type'];
		$vehicle_meta['luggage2_type_symbol'] = $_POST['luggage2_type_symbol'];

		$vehicle_meta['vehicle_image_gallery'] = $_POST['vehicle_image_gallery'];
		
		/**
		 * @since 2.0.8
		 */
		$vehicle_meta['apply_time_restriction'] = $_POST['apply_time_restriction'];
		$vehicle_meta['apply_seats_restriction'] = $_POST['apply_seats_restriction'];
		$vehicle_meta['apply_peakseason_rates'] = $_POST['apply_peakseason_rates'];
		$vehicle_meta['vehicle_available_for'] = json_encode( $_POST['vehicle_available_for'] );
		$vehicle_meta['minimum_distance_to_handle_booking'] = $_POST['minimum_distance_to_handle_booking'];
		$vehicle_meta['vehicle_base_location'] = $_POST['vehicle_base_location'];
		$vehicle_meta['farecalculation_basedon'] = $_POST['farecalculation_basedon'];

		/**
		 *  we can't only rent for one day Point to point drop off my take max 2 to 3 hour in my area So a schedule will help me to grow my business. With out any control a car might get several booking in the same time. 
		 *
		 * @since 2.0.9
		 */
		$vehicle_meta['transition_time'] = $_POST['transition_time'];
		$vehicle_meta['transition_time_type'] = $_POST['transition_time_type'];
		$vehicle_meta['other_information'] = $_POST['other_information'];
		
		$vehicle_meta['vehicle_display_status'] = $_POST['vehicle_display_status'];
		
		$vehicle_meta['minimum_fare'] = $_POST['minimum_fare'];
		//$vehicle_meta['minimum_fare_on'] = $_POST['minimum_fare_on'];
				
		$vehicle_meta = apply_filters('simontaxi_save_vehicle_meta', $vehicle_meta);

        // Add values of $cabs_meta as custom fields
        foreach ( $vehicle_meta as $key => $value ) { // Cycle through the $vehicle_meta array!
            if ( $post->post_type == 'revision' ) {
				return; // Don't store custom data twice	
			}
            $value = implode( ',', ( array ) $value ); // If $value is an array, make it a CSV (unlikely)
            if ( get_post_meta( $post->ID, $key, FALSE ) ) { // If the custom field already has a value
                update_post_meta( $post->ID, $key, $value );
            } else { // If the custom field doesn't have a value
                add_post_meta( $post->ID, $key, $value );
            }
            if ( ! $value ) {
				delete_post_meta( $post->ID, $key ); // Delete if blank
			}
        }
    }

}
add_action( 'save_post', 'simontaxi_save_vehicle_meta', 1, 2 ); // save the custom fields