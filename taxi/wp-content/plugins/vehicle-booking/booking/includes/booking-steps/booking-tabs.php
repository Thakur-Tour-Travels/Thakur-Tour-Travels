<!-- TabPills Navigation -->
<ul class="<?php echo esc_attr( $tabfills ); ?>">
	 <?php
	 $p2p_active = '';
	 $airport_active = '';
	 $hourly_active = '';
	
	 $tabs_keys = array();
	 if ( ! empty( $tabs ) && simontaxi_is_assoc_array( $tabs ) ) {
		 $tabs_keys = array_keys( $tabs );
	 } else {
		$tabs_keys =  $tabs;
	 }
	 if ( in_array( 'p2p', $tabs_keys ) ) {
		$p2p_active = 'active';
		$airport_active = '';
		$hourly_active = '';
	 } elseif ( in_array( 'airport', $tabs_keys ) ) {
		 $p2p_active = '';
		$airport_active = 'active';
		$hourly_active = '';
	 } elseif (in_array( 'hourly', $tabs_keys) ) {
		 $p2p_active = '';
		$airport_active = '';
		$hourly_active = 'active';
	 } else {
		$p2p_active = '';
		$airport_active = '';
		$hourly_active = '';
	 }
	 if ( $modify ) {
		 $tab = simontaxi_get_value( $booking_step1, 'booking_type' );
		 if ( $tab == 'p2p' ) {
			 $p2p_active = 'active';
			 $airport_active = '';
			 $hourly_active = '';
		 } elseif ( $tab == 'airport' ) {
			 $p2p_active = '';
			 $airport_active = 'active';
			 $hourly_active = '';
		 } elseif ( $tab == 'hourly' ) {
			 $p2p_active = '';
			 $airport_active = '';
			 $hourly_active = 'active';
		 } else {
			 $p2p_active = '';
			 $airport_active = '';
			 $hourly_active = '';
		 }
	 }
	 
	 /**
	  * @since 2.0.8
	  */
	 if ( ! empty( $_GET['booking_type'] ) ) {
		 $tab = $_GET['booking_type'];
		 if ( $tab == 'p2p' ) {
			 $p2p_active = 'active';
			 $airport_active = '';
			 $hourly_active = '';
		 } elseif ( $tab == 'airport' ) {
			 $p2p_active = '';
			 $airport_active = 'active';
			 $hourly_active = '';
		 } elseif ( $tab == 'hourly' ) {
			 $p2p_active = '';
			 $airport_active = '';
			 $hourly_active = 'active';
		 }
	 }
	 if ( empty( $booking_step1['booking_type'] ) && empty( $p2p_active ) && empty( $airport_active ) && empty( $hourly_active ) ) {
		 $p2p_active = 'active';
	 }
	 
	 $booking_type = simontaxi_get_option( 'default_active_tab', 'p2p' );
	 if ( ! empty( $booking_step1['booking_type'] ) ) {
		 $booking_type = $booking_step1['booking_type'];
	 }
	 if ( ! empty( $_POST['booking_type'] ) ) {
		 $booking_type = $_POST['booking_type'];
	 }
	 if ( ! in_array( $booking_type, array_keys( simontaxi_primary_booking_types() ) ) ) {
		$p2p_active = '';
		$airport_active = '';
		$hourly_active = '';
	 }
	 if ( in_array( 'p2p', $tabs_keys ) ) { ?>
	<li class="<?php echo esc_attr( $p2p_active ); ?>"><a data-toggle="pill" href="#st-p2p"><?php echo simontaxi_get_p2ptab_title(); ?></a></li>
	<?php } ?>
	<?php if ( in_array( 'airport', $tabs_keys ) ) { ?>
	<li class="<?php echo esc_attr( $airport_active ); ?>"><a data-toggle="pill" href="#st-airport"><?php echo simontaxi_get_airporttab_title(); ?></a></li>
	<?php } ?>
	<?php if (in_array( 'hourly', $tabs_keys) ) { ?>
	<li class="<?php echo esc_attr( $hourly_active ); ?>"><a data-toggle="pill" href="#st-hourly"><?php echo simontaxi_get_hourlytab_title(); ?></a></li>
	<?php } ?>
	<?php do_action('simontaxi_addtional_booking_tabs', $tabs, $booking_step1 ); ?>
</ul>
<!-- end TabPills Navigation -->