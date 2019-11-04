<?php
/**
 * Add admin menu for vehicle support request
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  support requests
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'vehicle_theme_admin_menu_support_request' );
function vehicle_theme_admin_menu_support_request() {
   		add_submenu_page( 'edit.php?post_type=vehicle', esc_html__( 'Support Request', 'simontaxi' ),esc_html__( 'Support Request', 'simontaxi' ),'manage_support_request','view_support_request','view_support_request' );
}
function view_support_request()
{
			?>
			<script>
			function printContent(el){
			var restorepage = document.body.innerHTML;
			var printcontent = document.getElementById(el).innerHTML;
			document.body.innerHTML = printcontent;
			window.print();
			document.body.innerHTML = restorepage;
		}
		</script>

		<style type="text/css">
		table{font-family: arial; width: 100%; }
		table.support-request-status-update {background: white;border:1px solid #e6e6e6;padding:10px;}
		table.support-request-status-update th{text-align: left;padding:10px;padding-left: 0px;}
		table.support-request-status-update td{vertical-align: top;}
		.small-gray{color:gray;font-size:11px;}
		.status-count{font-size: 11px;background:transparent;color:white;font-weight:bold;padding:0px 7px;border-radius:100%;display: inline-block;border:1px solid white;}
		.bg-happygreen{background:#5cc550;}
		.bg-cancel{background:gray;}
		.bg-success{background:#44923b;}
		.bg-warning{background:#eab107;}
		.bg-danger{background:#ce4141;}
		.bg-sky{background:#00a0d2;}
		.bg-purple{background:#b35e89;}
		.font-white{color:white !important;min-width:100px;display: inline-block;text-indent:3px;border-radius: 2px;font-family: arial;font-size:13px;}

		</style>
	<?php

	if ( isset( $_GET['view_status'] ) )
	{
		global $wpdb;
		if ( isset( $_POST['admin_comments'] ) ) {
			$data = array(
				'status' => $_POST['status'],
				'admin_comments' => $_POST['admin_comments'],
				'last_updated' => date( 'Y-m-d H:i:s' ),
			);
			$id = $_POST['id'];
			$wpdb->update( $wpdb->prefix . 'st_user_support', $data , array( 'ID' => $id ) );
			$redirect_to = simontaxi_get_bookingsteps_urls( 'manage_support' );
			wp_safe_redirect( $redirect_to );
		}
		$st_user_support = $wpdb->prefix. 'st_user_support';
	    $result = $wpdb->get_results( "SELECT * FROM $st_user_support WHERE ID=" . $_GET['view_status'] );

	    if ( ! empty ( $result ) )
		{
			$request=( array ) $result[0];
		?>
			<div class="wrap">
				<div id="icon-users" class="icon32"></div>
					<a style="float:right;" href="<?php echo admin_url( 'admin.php?page=view_support_request' );?>"><?php esc_html_e( 'Back to Support Request', 'simontaxi' );?></a>
						<h3><?php esc_html_e( 'Support Request Details', 'simontaxi' );?> <button onclick="printContent( 'support-request-div' )"><?php esc_html_e( 'Print', 'simontaxi' );?></button></h3></h3>
						<div id="support-request-div">
							<form action="" method="post" id="frmchangestatus">
							<table class="support-request-status-update">
								<tbody>
									<tr>
										<th ><?php esc_html_e( 'ID', 'simontaxi' );?></th><td>:<?php echo $request['ID'];?></td>
									</tr>
									<tr>
										<th ><?php esc_html_e( 'Name', 'simontaxi' );?></th><td>:<?php echo ucfirst( $request['first_name'] . ' ' . $request['last_name'] ) ;?></td>
									</tr>
									<tr>
										<th ><?php esc_html_e( 'Subject', 'simontaxi' );?></th><td>:<?php echo ucfirst( $request['subject'] ) ;?></td>
									</tr>
									<tr>
										<th ><?php esc_html_e( 'Message', 'simontaxi' );?></th><td>:<?php echo ucfirst( $request['message'] ) ;?></td>
									</tr>
									<tr>
										<th ><?php esc_html_e( 'Status', 'simontaxi' );?></th><td>:<?php echo ucfirst( $request['status'] ) ;?></td>
									</tr>
									<tr>
										<th ><?php esc_html_e( 'Change Status', 'simontaxi' );?></th><td>:
										<select name="status" id="status">
											<option value=""><?php esc_html_e( 'Please select', 'simontaxi' );?></option>
											<option value="solved"><?php esc_html_e( 'Solved', 'simontaxi' );?></option>
											<option value="cancelled"><?php esc_html_e( 'Cancelled', 'simontaxi' );?></option>
										</select>
										</td>
									</tr>
									<tr>
										<th ><?php esc_html_e( 'Comments', 'simontaxi' );?></th><td>:
										<textarea name="admin_comments" id="admin_comments"></textarea>
										<input type="hidden" name="id" value="<?php echo $_GET['view_status'];?>">
										</td>
									</tr>
									<tr>
										<th >&nbsp;</th><td><button type="submit" class="btn btn-primary" name="send_message"><?php esc_html_e( 'Send', 'simontaxi' );?></button>
										</td>
									</tr>
								</tbody>
							</table>
							</form>
							<script type="text/javascript">
							jQuery( document ).ready(function( $ ) {
								$( '#frmchangestatus' ).submit(function(event){
									var admin_comments = $( '#admin_comments' ).val();
									var status = $( '#status' ).val();
									var error = 0;;
									if (status == '' ) {
										$( '#status' ).after( '<span class="error"> <?php esc_html_e( 'Please select status', 'simontaxi' );?></span>' );
										error++;
									}
									if (admin_comments == '' ) {
										$( '#admin_comments' ).after( '<span class="error"> <?php esc_html_e( 'Please enter comments', 'simontaxi' );?></span>' );
										error++;
									}
									if (error > 0) event.preventDefault();
								});
							});
							</script>
							<a style="float:right;" href="<?php echo admin_url( 'admin.php?page=view_support_request' );?>"><?php esc_html_e( 'Back to Support Request', 'simontaxi' );?></a>
						</div>

			</div>
						<?php

		}
		exit;
	}

	elseif ( isset( $_GET['change_status'] ) && isset( $_GET['rc_id'] ) )
	{

		global $wpdb;
		$st_supprt_request = $wpdb->prefix. 'st_user_support';
		$id =  $_GET['rc_id'];

		$data = array(
			'status' => $_GET['change_status'],
			'last_updated' => date( 'Y-m-d H:i:s' ),
		);
		$x=$wpdb->update( $wpdb->prefix . 'st_user_support', $data, array( 'ID'=>$id) );

			if ( $x)
			{
				 echo '<div id="lc-plugin-activated" class="notice updated lc-plugin-activated is-dismissible">
				                <p>
				                    <b>Status Updated !</b>
				                </p>
				           </div>';
			}

	}

	if (!class_exists( 'WP_List_Table' ) ){
	   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

	class Sr_List extends WP_List_Table
	{

		/** Class constructor */
		public function __construct() {

			parent::__construct( array(
				'singular' => esc_html__( 'Support Request', 'simontaxi' ), //singular name of the listed records
				'plural'   => esc_html__( 'Support Request', 'simontaxi' ), //plural name of the listed records
				'ajax'     => false //should this table support ajax?

			) );

		}

		/**
		*   get_views] : Frontend Filters
		*
		*
		*/

		protected function get_views() {
		    $status_links = array(
		        "new" => "<a class='' href='" .esc_url(admin_url( 'admin.php?page=view_support_request&status=new' ) ) . "'> " .esc_html__( 'New','simontaxi' ) . " <span class='status-count bg-danger'>" . $this->record_count( 'new' ) . "</span></a>",
		        "solved" => "<a class='' href='" .esc_url(admin_url( 'admin.php?page=view_support_request&status=solved' ) ) . "'> " .esc_html__( 'Solved','simontaxi' ) . " <span class='status-count bg-success'>" . $this->record_count( 'solved' ) . "</span></a>",

		         "all"   => "<a class='' href='" .esc_url(admin_url( 'admin.php?page=view_support_request&status=all' ) ) . "'> " .esc_html__( 'All','simontaxi' ) . " <span class='status-count bg-sky'>" . $this->record_count( 'all' ) . "</span></a>"
		    );
		    return $status_links;
		}

		/**
		 * Retrieve Booking data from the database
		 *
		 * @param int $per_page
		 * @param int $page_number
		 *
		 * @return mixed
		 */
		public static function get_support_request( $per_page = 5, $page_number = 1 ) {

			global $wpdb;
			$st_user_support = $wpdb->prefix. 'st_user_support';
			
			$sql = "SELECT * FROM `" . $st_user_support . "` WHERE `ID`!=0 ";

			if ( isset( $_REQUEST['status'] ) && ( $_REQUEST['status']!='all' ) ) {
				$sql .=" AND `" . $st_user_support . "`.`status`='" . $_REQUEST['status'] . "'";
			}
			
			if ( ! isset( $_REQUEST['status'] ) ) {
				$sql .=" AND `" . $st_user_support . "`.`status`='new'";
			}
			
			if ( ! empty( $_REQUEST['orderby'] ) ) {
				$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
				$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
			}

			$search = ( isset( $_REQUEST['s'] ) ) ? $_REQUEST['s'] : false;
			if ( $search ) {
				$sql .= " AND `" . $st_user_support . "`.`rc_name` LIKE '%%%" . $search . "%%' ";
			}
			$sql .= " LIMIT $per_page";
			$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
			$result = $wpdb->get_results( $sql, 'ARRAY_A' );
			return $result;
		}

		/**
		 * Delete a Booking record.
		 *
		 * @param int $id booking ID
		 */


		public static function delete_booking( $id ) {
		  global $wpdb;
		  $wpdb->delete("{$wpdb->prefix}st_user_support", array( 'ID' => $id ), array(  '%d' ) );
		}

		/**
		 * Returns the count of records in the database.
		 *
		 * @return null|string
		 */
		public static function record_count( $status='' ) {
		  global $wpdb;

		  if (!isset( $_REQUEST['status'] )&& $status=='' ) {
			  $status='new';  
		  }
		  if ( isset( $_REQUEST['status'] ) && $status=='' &&  $_REQUEST['status']!='' )  {
			$status = $_REQUEST['status'];  
		  }
		  $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}st_user_support WHERE ID!=0 ";
		  if ( $status !== 'all' ) {
		  	$sql.=" AND status='" . $status . "'";
		  }


		  return $wpdb->get_var( $sql );
		}
		/** Text displayed when no booking data is available */
		public function no_items() {
		  esc_html_e( 'No Support Requests avaliable. ', 'simontaxi' );
		}



		/**
		 * Method for name column
		 *
		 * @param array $item an array of DB data
		 *
		 * @return string
		 */
		function column_name( $item ) {

		  // create a nonce
		  $delete_nonce = wp_create_nonce( 'sp_delete_request_callback' );

		  $title = '<strong>' . $item['first_name'] . ' ' . $item['last_name'] . '</strong>';

		  $actions = array();

		  return $title . $this->row_actions( $actions );
		}

		/**
		 *  Associative array of columns
		 *
		 * @return array
		 */
		function get_columns() {
		  $columns = array(
		    'cb'      => '<input type="checkbox" />',
		    'ID'    => esc_html__( 'ID ', 'simontaxi' ),
		    'name'    => esc_html__( 'Name', 'simontaxi' ),
		    'subject'    => esc_html__( 'Subject', 'simontaxi' ),
		    'date'    => esc_html__( 'Date', 'simontaxi' ),
		    'status'    => esc_html__( 'Status', 'simontaxi' ),
		  );

		  return $columns;
		  //var_dump( $columns);
		}

		/**
		 * Render a column when no column specific method exists.
		 *
		 * @param array $item
		 * @param string $column_name
		 *
		 * @return mixed
		 */
		public function column_default( $item, $column_name ) {

		     switch ( $column_name ) {
			    case 'cb':
			      return $this->column_cb( $item);
			    case 'ID':
			      return ' <span> ' . $item['ID']. '</span> - ' . '<a href="' .admin_url( 'admin.php?page=view_support_request&view_status=' . $item['ID'] ) . '">View Details</a>';
			    case 'name':
			      return $item['first_name'] . ' ' . $item['last_name'];
			    case 'subject':
			      return $item['subject'];
			    case 'message':
			      return $item['message'];
			    case 'date':
			    	return $item['date'];
			    case 'status':
			    	return ( $item['status']=='new' ? '<a href="' .admin_url( 'admin.php?page=view_support_request&change_status=solved&rc_id=' . $item['ID'] ) . '">' . __( 'new', 'simontaxi' ) . '</a> ' : ucfirst( $item['status'] ) );
			    default:
			      return ucfirst( $item[ $column_name ] ); //Show the whole array for troubleshooting purposes
			  }

		}

		/**
		 * Render the bulk edit checkbox
		 *
		 * @param array $item
		 *
		 * @return string
		 */
		function column_cb( $item ) {
		  return sprintf( '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID'] );
		}



		/**
		 * Columns to make sortable.
		 *
		 * @return array
		 */
		public function c() {
		  $sortable_columns = array(
		    'date' => array( 'date', true ),
		  );

		  return $sortable_columns;
		}

		/**
		 * Returns an associative array containing the bulk action
		 *
		 * @return array
		 */

		public function get_bulk_actions() {
		  $actions = array(
		    'bulk-delete' => 'Delete'
		  );
		  return $actions;
		}

		/**
		 * Handles data query and filter, sorting, and pagination.
		 */
		public function prepare_items() {

			$columns = $this->get_columns();
			$hidden = array();
			$sortable = $this->get_sortable_columns();
			$this->_column_headers = array( $columns, $hidden, $sortable);

		  /** Process bulk action */
		  	$this->process_bulk_action();

		  $per_page     = $this->get_items_per_page( 'support_request_per_page', 4);
		  $current_page = $this->get_pagenum();
		  $total_items  = self::record_count();

		  $this->set_pagination_args( array(
		    'total_items' => $total_items, //WE have to calculate the total number of items
		    'per_page'    => $per_page //WE have to determine how many items to show on a page
		  ) );
		  
		  $this->items = self::get_support_request( $per_page, $current_page );
		}

		public function process_bulk_action() {

		  //Detect when a bulk action is being triggered...
		  if ( 'delete' === $this->current_action() ) {

		    // In our file that handles the request, verify the nonce.
		    $nonce = esc_attr( $_REQUEST['_wpnonce'] );

		    if ( ! wp_verify_nonce( $nonce, 'sp_delete_support_request' ) ) {
		      die( 'Go get a life script kiddies' );
		    }
		    else {
		      self::delete_conntact_us( absint( $_GET['support_request'] ) );

		      wp_redirect( esc_url( add_query_arg() ) );
		      exit;
		    }

		  }

		  // If the delete bulk action is triggered
		  if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		       || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		  ) {

		    $delete_ids = esc_sql( $_POST['bulk-delete'] );
			var_dump( $delete_ids);

		    // loop over the array of record IDs and delete them
		    foreach ( $delete_ids as $id ) {
		      self::delete_booking( $id );

		    }

		    wp_redirect( esc_url( add_query_arg() ) );
		    exit;
		  }
		}

		public function search_box( $text, $input_id ) {

	        $input_id = $input_id . '-search-input';

	        if ( ! empty( $_REQUEST['orderby'] ) )
	            echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
	        if ( ! empty( $_REQUEST['order'] ) )
	            echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
	        if ( ! empty( $_REQUEST['post_mime_type'] ) )
	            echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( $_REQUEST['post_mime_type'] ) . '" />';
	        if ( ! empty( $_REQUEST['detached'] ) )
	            echo '<input type="hidden" name="detached" value="' . esc_attr( $_REQUEST['detached'] ) . '" />';
			echo '<p class="search-box">
					    <input type="search" id="' . $input_id. '" name="s" value="' .( isset( $_REQUEST['s'] ) ? $_REQUEST['s'] : '' ) . '" />
					    ' .submit_button( $text, 'button', '', false, array( 'id' => 'search-submit' ,'style'=>'float:right;', 'onClick'=>'location.search+=\'&s=\'+document.getElementById(\'' . $input_id. '\' ).value;' ) ) . '
					</p>
			';
	    }
	}
	echo '<div class="wrap">
			<div id="icon-users" class="icon32"></div>
			<h2>Support Requests <span style="color:gray"></span></h2>';
				$bookings = new Sr_List();
				$bookings->views();
				$bookings->prepare_items();
				$bookings->search_box( 'Search Name','contact_us' );
				$bookings->display();
	echo '	</div>
		  </div>';
}
?>