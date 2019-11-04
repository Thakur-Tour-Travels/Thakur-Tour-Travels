<?php
/**
 * This template is used to display the 'purchase_invoice'
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  purchase_invoice
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;	
}

do_action( 'simontaxi_purchase_invoice_start' );

$currency_code = simontaxi_get_currency_code(); ?>

<!-- <html>
<body> -->
<?php
/**
 * @since 2.0.8
 */
$template = '/booking/includes/pages/user_left.php';
if ( simontaxi_is_template_customized( $template ) ) {
	include_once( simontaxi_get_theme_template_dir_name() . $template );
} else {
	include_once( SIMONTAXI_PLUGIN_PATH . $template );
}
?>
<div class='content-wrapper'>
<div class="content">

<?php if( $fail_message=='' ) { ?>
	<div  bgcolor="#f6f6f6" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; -webkit-font-smoothing: antialiased; height: 100%; -webkit-text-size-adjust: none; width: 100% !important; margin: 0; padding: 0;margin-top:10px;">

	<!-- body -->
	<table class="body-wrap" bgcolor="#f6f6f6" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; width: 100%; margin: 0; padding: 5px;">
		<tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0;">
	<!--------------------------------------------------------------- P1 TD 1 ------------------------------------------------------------>
			<td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0;">

			</td>
	<!--------------------------------------------------------------- P1 TD 2 ------------------------------------------------------------>
			<td  bgcolor="#FFFFFF" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; clear: both !important; display: block !important; max-width: 800px !important; margin: 0 auto; padding: 0px; border: 1px solid #f0f0f0;"><!-- content -->

			<p class='button-bar'><?php esc_html_e( 'Invoice', 'simontaxi' ); ?>
				<?php
				$links = apply_filters( 'simontaxi_purchase_invoice_links', array(
					'print' => '<span class="pull-right badge " onClick="printItem( \'invoice-print-div\' )"><i class="fa fa-print"></i></span>',
				) );
				$total_links = count( $links );
				if ( ! empty( $links ) ) { 
					$i = 1;
					foreach( $links as $key => $link ) {
						echo $link;
						
						if ( $i < $total_links ) {
							echo '<span class="pull-right">&nbsp;|&nbsp;</span>';
						}
						$i++;
					}
				?>
				
				<?php }
				?>
				
				
			</p>
			<hr />

	<?php if( $invoice->payment_status == 'pending' ) { ?>
	  	<div style="padding:20px;">
	  	<center>
	  	<h4 style="color:orange"><?php esc_html_e( 'Payment is Pending', 'simontaxi' ); ?> </h4>
	  	<h5 style="font-size:12px" class="text-info"><?php esc_html_e( 'For any queries please mail us to', 'simontaxi' ); ?>  <b><?php echo simontaxi_get_option( 'vehicle_payment_queries' ); ?></b></h5>
	  	</center>
	  	</div>
	<?php } ?>

	<?php if( $invoice->payment_status == 'failed' ) { ?>
	<div style="padding:20px;">
  	<center>
  	<h4 class="text-danger"><?php esc_html_e( 'Payment Failed', 'simontaxi' ); ?> </h4>
  	<h5 class="text-info"><?php esc_html_e( 'For any queries please mail us to', 'simontaxi' ); ?>  <b><?php echo simontaxi_get_option( 'vehicle_payment_queries' ); ?></b></h5>
  	</center>
  	</div>
   <?php } ?>

	<?php if( $invoice->payment_status =='cancelled' ) { ?>
		<div style="padding:20px;">
		<center>
		<h4 class="text-danger"><?php esc_html_e( 'Payment cancelled', 'simontaxi' ); ?> </h4>
		<h5 class="text-info"><?php esc_html_e( 'For any queries please mail us to', 'simontaxi' ); ?>  <b><?php echo simontaxi_get_option( 'vehicle_payment_queries' ); ?></b></h5>
		</center>
		</div>
	<?php } ?>
	<?php
	$template = 'booking/includes/pages/purchase-invoice-content.php';
	if ( simontaxi_is_template_customized( $template ) ) {
		require simontaxi_get_theme_template_dir_name() . $template;
	} else {
		require apply_filters( 'simontaxi_locate_purchase_invoice_content', SIMONTAXI_PLUGIN_PATH . $template );
	}
	?>

	<!-- /content --></td>
	<td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0;"></td>
	</tr>

	</table>
	<!-- /body -->
	<!-- footer -->

     		    </div>

      		</td>

    <!--------------------------------------------------------------- P1 TD 3 ------------------------------------------------------------>
    		<td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; margin: 0; padding: 0;"></td>
  		</tr>
	</table>
<?php } ?>
</div>
</div>
<script type="text/javascript">
function printItem( elem ) {
	var mywindow = window.open('', 'PRINT', 'height=400,width=600' );
	mywindow.document.write('<html><head><title>' + document.title  + '</title>' );
	mywindow.document.write('</head><body >' );
	mywindow.document.write('<h1>' + document.title  + '</h1>' );
	mywindow.document.write(document.getElementById(elem).innerHTML);
	mywindow.document.write('</body></html>' );

	mywindow.document.close(); // necessary for IE >= 10
	mywindow.focus(); // necessary for IE >= 10*/

	mywindow.print();
	mywindow.close();

	return true;
}
</script>