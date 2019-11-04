<?php
/**
 * Template part for displaying vehicle details.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Simontaxi
 */

?>
<?php $meta = simontaxi_filter_gk( get_post_meta( get_the_ID() ) );?>
	<?php
	/**
	 * Let us display if the post has any thumbnail
	 */
	if ( has_post_thumbnail( get_the_ID() ) ) {
		if ( isset( $meta['vehicle_image_gallery'] ) ) {
			$meta['vehicle_image_gallery'] = $meta['vehicle_image_gallery'] . ',' . get_post_thumbnail_id();
		} else {
			$meta['vehicle_image_gallery'] = get_post_thumbnail_id();
		}
	}
	if ( isset( $meta['vehicle_image_gallery'] ) && '' !== $meta['vehicle_image_gallery'] ) {
		$attachments = array_filter( explode( ',', $meta['vehicle_image_gallery'] ) );
		if ( ! empty( $attachments ) ) {
	?>
	<!-- Slider For -->
	<div class="st-slider-for">
		<?php
		foreach ( $attachments as $attachment_id ) {
			$attachment = wp_get_attachment_image( $attachment_id, '750x441', '', array( 'class' => 'img-responsive' ) );

			if ( ! empty( $attachment ) ) {
				?>
				<!-- Item -->
				<div class="st-thumb-view">
					<?php echo $attachment;?>
				</div>
				<!-- /Item -->
				<?php
			}
		}
		?>
	</div>
	<!-- /Slider For -->
	
	<!-- Slider Nav -->
	<div class="st-slider-nav">
		<?php
		foreach ( $attachments as $attachment_id ) {
			$attachment = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
			if ( ! empty( $attachment ) ) {
				?>
				<!-- Item -->
				<div class="st-thumb">
				<img src="<?php echo esc_url( $attachment[0] );?>" class="img-responsive" alt="">
				</div>
				<!-- /Item -->
				<?php
			}
		}
		?>
	</div>
	<!-- /Slider Nav -->
		<?php } // End if().
	} // End if(). ?>

	<div class="clearfix"></div>
	<div class="row">
		<div class="col-sm-12">
			<div class="st-product-details">
				<h2><?php the_title();?></h2>
				
				<p><?php the_content();?></p>
			</div>
		</div>
		<?php $features = get_the_terms( get_the_ID(), 'vehicle_features' );
		if ( ! empty( $features ) ) {
		?>
		<div class="col-sm-6">
			<div class="st-product-features">
				<h3><?php esc_html_e( 'Features', 'simontaxi' );?></h3>
				<ul>
					<?php foreach ( $features as $feature ) { ?>
					<li><i class="fa fa-car"></i><?php echo esc_attr( $feature->name );?></li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<?php } ?>
		
		<div class="col-sm-6">
			<div class="st-product-features">
				<h3><?php esc_html_e( 'Details', 'simontaxi' );?></h3>
				<ul>
				<?php if ( isset( $meta['p2p_basic_distance'] ) ) { ?>
				<li><?php esc_html_e( 'Basic Distance : ', 'simontaxi' );?><?php echo esc_attr( $meta['p2p_basic_distance'] );?> <?php echo esc_attr( simontaxi_get_distance_units() );?></li>
				<?php } ?>
				<?php if ( isset( $meta['p2p_basic_price'] ) ) { ?>
				<li><?php esc_html_e( 'Basic Price : ', 'simontaxi' );?><?php echo esc_attr( simontaxi_get_currency( $meta['p2p_basic_price'] ) );?></li>
				<?php } ?>
				<?php if ( isset( $meta['p2p_unit_price'] ) ) { ?>
				<li><?php esc_html_e( 'Unit Distance : ', 'simontaxi' );?><?php echo esc_attr( simontaxi_get_currency( $meta['p2p_unit_price'] ) );?></li>
				<?php } ?>
				<?php if ( isset( $meta['seating_capacity'] ) ) { ?>
				<li><?php esc_html_e( 'Seating Capacity : ', 'simontaxi' );?><?php echo esc_attr( $meta['seating_capacity'] );?></li>
				<?php } ?>
				<?php if ( isset( $meta['luggage'] ) ) { ?>
				<li><?php esc_html_e( 'Luggage : ', 'simontaxi' );?><?php echo esc_attr( $meta['luggage'] );?> <?php
				if ( isset( $meta['luggage_type_symbol'] ) ) {
					echo esc_attr( $meta['luggage_type_symbol'] );
				} else {
					echo esc_attr( $meta['luggage_type'] );
				}?></li>
				<?php } ?>
				</ul>
				
			</div>
		</div>
		<div class="col-sm-12">
			<ul class="st-pair-btns">
				<li><a href="<?php echo esc_url( simontaxi_get_bookingsteps_urls( 'step1' ) );?>" class="btn btn-primary"><?php esc_html_e( 'Book Now', 'simontaxi' );?></a></li>
			</ul>
		</div>
	</div>
	<div class="clearfix"></div>
