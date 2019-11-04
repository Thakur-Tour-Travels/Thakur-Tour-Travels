<?php
/**
 * This template is used to display the 'user_bookings' with [simontaxi_user_bookings]
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  simontaxi_user_bookings
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;	
}

do_action( 'simontaxi_user_bookings_start' );

$current_user = wp_get_current_user();
global $wpdb;
$bookings = $wpdb->prefix . 'st_bookings';
$payments = $wpdb->prefix . 'st_payments';

if ( isset( $_REQUEST['invoice_id'] ) ) {
	$ref = explode( '-', $_REQUEST['invoice_id']);
	$booking_id = $ref[0];
	$booking_ref = isset( $ref[1] ) ? $ref[1] : '';
	if ( is_user_logged_in() ) {
		$invoice = $wpdb->get_results( 'SELECT *, ' . $bookings. '.ID as booking_id, ' . $bookings. '.reference as booking_ref, ' . $payments . '.reference as payment_ref  FROM ' . $bookings . ' INNER JOIN ' . $payments. ' ON ' . $bookings . '.ID = ' . $payments . '.booking_id WHERE ' . $bookings . '.user_id='.get_current_user_id() . ' AND ' . $bookings . '.ID=' . $booking_id );
	} else {
		$invoice = $wpdb->get_results( 'SELECT *, ' . $bookings. '.ID as booking_id, ' . $bookings. '.reference as booking_ref, ' . $payments . '.reference as payment_ref  FROM ' . $bookings . ' INNER JOIN ' . $payments. ' ON ' . $bookings . '.ID = ' . $payments . '.booking_id WHERE ' . $bookings . '.ID=' . $booking_id );
	}
	if ( empty( $invoice ) ) {
		echo esc_html__( 'NO INVOICE FOUND', 'simontaxi' );
	} else {
		$invoice = $invoice[0];
		$user_det = simontaxi_filter_gk( ( array ) get_user_meta( $invoice->user_id ) );
		$fail_message='';
		/**
		 * @since 2.0.8
		 */
		if ( isset( $_REQUEST['action'] ) && 'cancel_order' == $_REQUEST['action'] ) {
			$template = 'booking/includes/pages/user-cancel-booking.php';
			if ( simontaxi_is_template_customized( $template ) ) {
				include_once( simontaxi_get_theme_template_dir_name() . $template );
			} else {			
				include_once( apply_filters( 'simontaxi_locate_user_cancel_booking', SIMONTAXI_PLUGIN_PATH . $template )  );
			}
		} elseif ( isset( $_REQUEST['action'] ) && 'download_pdf' == $_REQUEST['action'] ) {
			require_once SIMONTAXI_PLUGIN_PATH . 'booking/libraries/mpdf/autoload.php';
			
			$ref = explode( '-', $_REQUEST['invoice_id']);
			$booking_id = $ref[0];
			$booking_ref = $ref[1];
			
			global $wpdb;
			$bookings = $wpdb->prefix . 'st_bookings';
			$payments = $wpdb->prefix . 'st_payments';
			$invoice = $wpdb->get_row( 'SELECT *, ' . $bookings. '.reference as booking_ref, ' . $payments . '.reference as payment_ref  FROM ' . $bookings . ' INNER JOIN ' . $payments. ' ON ' . $bookings . '.ID = ' . $payments . '.booking_id WHERE ' . $bookings . '.ID=' . $booking_id );

			$file = "invoice-$booking_id.pdf";
			ob_start();
			$file_source = include_once( SIMONTAXI_PLUGIN_PATH . 'booking/includes/pages/purchase-invoice-content.php' );
			$file_content = ob_get_clean();

			$mpdf = new \Mpdf\Mpdf();
			$mpdf->WriteHTML( $file_content );
			// $mpdf->Output( "invoice-$booking_id.pdf", 'D' );
			$file_path = SIMONTAXI_PLUGIN_PATH . 'invoices/' . $file;
			$mpdf->Output( $file_path, \Mpdf\Output\Destination::FILE);
			// $mpdf->Output( "invoice-$booking_id.pdf", 'D' );
			
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header("Content-Type: application/force-download");
			header('Content-Disposition: attachment; filename=' . urlencode($file));
			// header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file_path));
			ob_clean();
			flush();
			readfile($file_path);
			exit; 
			
		} elseif( isset( $_REQUEST['action'] ) && 'download_invoice' == $_REQUEST['action'] ) {
			$template = 'booking/includes/pages/purchase_invoice.php';
			if ( simontaxi_is_template_customized( $template ) ) {
				include_once( simontaxi_get_theme_template_dir_name() . $template );
			} else {			
				include_once( apply_filters( 'simontaxi_locate_purchase_invoice', SIMONTAXI_PLUGIN_PATH . $template )  );
			}
		}
	}
} else {
	$per_page = 5;
	$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
	if ( $page > 1) {
		$offset = $page * $per_page - $per_page;
	} else {
		$offset = 0;
	}
	$query_all = 'SELECT b.*, b.ID as booking_id, p.amount_paid, p.amount_payable FROM ' . $bookings. ' b INNER JOIN ' . $payments . ' p ON b.ID = p.booking_id WHERE b.user_id = ' . get_current_user_id() . ' AND booking_contacts != "" ORDER BY b.ID DESC';
	$query = $query_all . ' LIMIT ' . $per_page. ' OFFSET ' . $offset;
$results = $wpdb->get_results( $query );
?>
<!-- Booking Form -->

<?php if ( ! empty( $wp_error->errors ) ) { ?>
<div class="alert alert-danger">
<ul><?php echo implode( '</li><li>', $wp_error->get_error_messages() ); ?></ul>
</div>
<?php }
/**
 * @since 2.0.8
 */
$template = 'booking/includes/pages/user_left.php';
if ( simontaxi_is_template_customized( $template ) ) {
	include_once( simontaxi_get_theme_template_dir_name() . $template );
} else {
	include_once( apply_filters( 'simontaxi_locate_user_left', SIMONTAXI_PLUGIN_PATH . $template ) );
}
$show_invoice_to_user = simontaxi_get_option( 'show_invoice_to_user', 'yes' );
$user_actions = simontaxi_user_actions();
if ( 'no' === $show_invoice_to_user ) {
	unset( $user_actions['invoice'] );
}
?>
<div class="st-booking-block1 st-admin-booking-block">

<div class="tab-content">

<!-- TAB-1 -->
<div id="st-booktab1" class="tab-pane fade in active">
	<div class="table-responsive">
		<table class="table table-hover st-table st-table-sm st-table-user-bookings">
			<thead>
			<tr>
			<th><?php echo apply_filters( 'simontaxi_flt_user_bookings_booking_id_title', esc_html__( 'Booking ID', 'simontaxi' ) ); ?></th>
			<th><?php echo simontaxi_get_pickuppoint_title(); ?></th>
			<th><?php echo simontaxi_get_dropoffpoint_title(); ?></th>
			<th><?php echo simontaxi_get_default_title(); ?></th>
			<th><?php echo apply_filters( 'simontaxi_flt_user_bookings_amount_paid_title', esc_html__( 'Paid / Payable', 'simontaxi' ) ); ?></th>
			<th><?php esc_html_e( 'Status', 'simontaxi' ); ?></th>
			<?php if( ! empty( $user_actions ) ) { ?>
			<th><?php esc_html_e( 'Actions', 'simontaxi' ); ?></th>
			<?php } ?>
			</tr>
			</thead>
			<tbody>
			<?php foreach ( $results as $row ) { ?>
			<tr>
			<td><?php 
			ob_start();
			echo esc_attr( $row->reference ); ?><br><?php echo esc_attr( simontaxi_date_format( $row->date ) );
			
			/**
			 * @since 2.0.0
			*/
			$vehicle_details = array();
			$session_details = json_decode( $booking['session_details'] );
			if ( is_object( $session_details ) ) {
				  $session_details = (array) $session_details;
			  }
			if ( ! empty( $session_details ) ) {
				
				foreach ( $session_details as $session ) {
					
					if ( isset( $session->vehicle_details ) ) {
						
						$vehicle_details = $session->vehicle_details;
						$vehicle_details->booking_id = $booking['booking_id'];
						if ( ! empty( $booking['driver_id'] ) ) {
							$vehicle_details->driver_id = $booking['driver_id'];
						} else {
							$vehicle_details->driver_id = 0;
						}
					}
				}
			}

			if ( ! empty( $vehicle_details ) ) {
				echo '<small class="small-gray">';
				esc_html_e( 'Vehicle Details : ' );
				if( ! empty( $vehicle_details->post_title ) ) {
					echo $vehicle_details->post_title;
				}
				echo '</small>';
			}
			echo apply_filters( 'simontaxi_flt_user_bookings_booking_id', ob_get_clean(), $row ) ;
			?></td>
			<td><?php
			ob_start();
			if( ctype_digit( $row->pickup_location ) ) {
				if ( in_array( $row->booking_type, apply_filters( 'simontaxi_airport_other_tabs', array( 'airport' ) ) ) ) {
					$details = get_term( $row->pickup_location, 'vehicle_locations' );
					if ( $details ) {
						$name = $details->name;
						$term_meta = get_term_meta( $row->pickup_location );
						$location_address = ( ! empty( $term_meta['location_address'] ) ) ? $term_meta['location_address'][0] : '';
						$name_value = ( '' !== $location_address ) ? $location_address : $name;
						echo esc_attr( $name_value );
					}
				}
			} else {
				echo esc_attr( $row->pickup_location );
			} ?><br> <?php echo esc_attr( simontaxi_date_format( $row->pickup_date ) ) . ' ' . esc_attr( $row->pickup_time ); 
			echo apply_filters( 'simontaxi_flt_user_bookings_pickup_location', ob_get_clean(), $row ) ;
			?></td>
			<td><?php
			ob_start(); 
			if( ctype_digit( $row->drop_location ) ) {
				if ( in_array( $row->booking_type, apply_filters( 'simontaxi_airport_other_tabs', array( 'airport' ) ) ) ) {
					$details = get_term( $row->drop_location, 'vehicle_locations' );
					if ( $details ) {
						$name = $details->name;
						$term_meta = get_term_meta( $row->drop_location );
						$location_address = ( ! empty( $term_meta['location_address'] ) ) ? $term_meta['location_address'][0] : '';
						$name_value = ( '' !== $location_address ) ? $location_address : $name;
						echo esc_attr( $name_value );
					}
				}
			} else {
				echo esc_attr( $row->drop_location );
			}
			echo apply_filters( 'simontaxi_flt_user_bookings_drop_location', ob_get_clean(), $row ) ;
			?></td>
			<td><?php echo esc_attr( $row->vehicle_name ); ?></td>
			<td><?php
			ob_start();
			echo esc_attr( $row->amount_paid ) . ' / ' . esc_attr( $row->amount_payable );
			echo '<p><span class="small-gray">' . $row->payment_status . '</span></p>';
			echo apply_filters( 'simontaxi_flt_user_bookings_amount_paid', ob_get_clean(), $row ) ;
			?>
			</td>
			<td>
			<?php
			if ( $row->status == 'new' ) {
				echo esc_html__( 'Pending', 'simontaxi' );
			} else {
				echo esc_html__( strtoupper( $row->status ), 'simontaxi' );
			}
			?></td>
			<?php if( ! empty( $user_actions ) ) { ?>
			<td>
				<div class="dropdown more">
				<a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fa fa-ellipsis-v fa-2x"></i>
				</a>
				<ul class="dropdown-menu simontaxi-dropdown-menu" aria-labelledby="dLabel">
				<?php
				foreach( $user_actions as $key => $user_action ) {
					if ( 'invoice' === $key && 'no' === $show_invoice_to_user ) {
						continue;
					}
					$title = ! empty( $user_action['title'] ) ? $user_action['title'] : esc_html__( 'Title', 'simontaxi' );
					$link = ! empty( $user_action['link'] ) ? $user_action['link'] : '';
					$params = array();
					if ( ! empty( $user_action['params'] ) ) {
						foreach( $user_action['params'] as $param ) {
							$value = $param['value'];
							if ( 'DB' === $param['type'] ) {
								if ( is_array( $value ) ) {
									$str = '';
									$i = 1;
									foreach( $value as $v ) {
										$str .= $row->{$v};
										if ( $i != count( $value ) ) {
											$str .= $param['separator'];
										}
											$i++;
									}
									$value = $str;
								} else {
									$value = $row->{$param['value']};
								}
							}
							$params[ $param['name'] ] = $value;
						}
					}

					if ( ! empty( $link ) && ! empty( $params ) ) {
						$link = add_query_arg( $params, $link );
					}
					?>
					<li><a target="_blank" href="<?php echo esc_url( $link ); ?>" class="<?php echo esc_attr( $user_action['class'] ); ?>"><?php echo esc_html( $title ); ?></a></li>
				<?php }
				?>
				</ul>
				</div>	
			</td>
			<?php } ?>
			</tr>
			<?php }

			$total = count( $wpdb->get_results( $query_all ) );
			if ( $total == 0 ) {
				?>
				<tr>
			<td colspan="7" class="st-center"><?php esc_html_e( 'No Records found', 'simontaxi' ); ?></td></tr>
				<?php
			}
			?>
			<?php if ( $total > $per_page ) { ?>
			<tr>
			<td colspan="7" class="st-center">
			<?php
			echo paginate_links(array(
				'base' => add_query_arg( 'cpage', '%#%' ),
				'format' => '',
				'prev_text' => esc_html__( '&laquo;', 'simontaxi' ),
				'next_text' => esc_html__( '&raquo;', 'simontaxi' ),
				'total' => ceil( $total / $per_page ),
				'current' => $page
			) );
			?>
			</td>
			</tr>
			<?php } ?>
			</tbody>

		</table>
	</div>
</div>

</div>
				</div>

<!-- /Booking Form -->
<?php } ?>