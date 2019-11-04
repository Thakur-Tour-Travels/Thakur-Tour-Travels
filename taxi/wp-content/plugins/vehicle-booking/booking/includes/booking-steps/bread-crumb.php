<?php
$booking_step1 = simontaxi_get_session( 'booking_step1', array() );
$current_step = ! empty( $booking_step1['current_step'] ) ? $booking_step1['current_step'] : 'step1';

$available_steps = simontaxi_booking_steps();

// Initialize variables

$bread_index = 0;
foreach( $available_steps as $bread_key => $bread_val ) {
	if ( 0 === $bread_index ) {
		$available_steps[ $bread_key ]['status'] = 'active';
	} else {
		$available_steps[ $bread_key ]['status'] = '';
	}
	$bread_index++;
}

// Disable completed steps
foreach( $available_steps as $bread_key => $bread_val ) {
	if ( $current_step === $bread_key ) {
		$available_steps[ $bread_key ]['status'] = 'active';
		break;
	} else {
		$available_steps[ $bread_key ]['status'] = 'done';
	}
}
?>
<ol class="st-breadcrumb">
	<?php foreach( $available_steps as $bread_key => $bread_val ) { ?>
	<li class="<?php echo esc_attr( $bread_val['status'] ); ?>">
		<?php if ( 'done' === $bread_val['status'] ) { ?>
		<a href="<?php echo $bread_val['url']; ?>">
		<?php } else { ?><a><?php } ?>
		<?php echo $bread_val['title']; ?>
		</a>
	</li>
	<?php } ?>
</ol>
