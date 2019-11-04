<?php
/**
 * This template is used to display the 'bookings' for admin / executive
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  manage_bookings
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'simontaxi_theme_admin_menu_manage_countries' );
function simontaxi_theme_admin_menu_manage_countries() {
   		add_submenu_page( 'edit.php?post_type=vehicle', esc_html__( 'Manage Countries', 'simontaxi' ),esc_html__( 'Manage Countries', 'simontaxi' ),'manage_countries','manage_countries','manage_countries' );
}

function manage_countries() {
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
	table.booking-status-update {background: white;border:1px solid #e6e6e6;padding:10px;}
	table.booking-status-update th{text-align: left;padding:10px;padding-left: 0px; vertical-align: text-top;}
	table.booking-status-update td{ vertical-align: text-top;}

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



	if(isset( $_GET['status']))
	{
		global $wpdb;
		$status = (isset( $_GET['status']) ? $_GET['status'] : 'new' );
		$id = isset( $_GET['id']) ? $_GET['id'] : '0';
		if ( 'delete' === $status && $id > 0 ) {
			$wpdb->delete("{$wpdb->prefix}st_countries", array( 'id_countries' => $id ), array( '%d' ) );
			simontaxi_set_message( 'success', esc_html__( 'Currency Deleted successfully', 'simontaxi' ) );
		} elseif ( 'delete' === $status && $id == 0 ) {
			simontaxi_set_infomessage( 'success', esc_html__( 'Please select a currency to delete', 'simontaxi' ) );
		}

		if ( ! empty( $_POST['simontaxi_country_nonce'] ) && wp_verify_nonce( $_POST['simontaxi_country_nonce'], 'simontaxi-country-nonce' ) ) {
			if ( empty ( $_POST['name'] ) ) {
				simontaxi_set_error( 'name', esc_html__( 'Please enter country name', 'simontaxi' ) );
			}
			if ( empty ( $_POST['iso_alpha3'] ) ) {
				simontaxi_set_error( 'iso_alpha3', esc_html__( 'Please enter ISO3 Code', 'simontaxi' ) );
			}
			if ( empty ( $_POST['currency_code'] ) ) {
				simontaxi_set_error( 'currency_code', esc_html__( 'Please enter currency code', 'simontaxi' ) );
			}

			if ( empty ( $_POST['phonecode'] ) ) {
				simontaxi_set_error( 'phonecode', esc_html__( 'Please enter phone code', 'simontaxi' ) );
			}
			$errors = simontaxi_get_errors();

			if ( empty( $errors ) ) {
				$data = array(
					'name' => $_POST['name'],
					'iso_alpha2' => $_POST['iso_alpha2'],
					'iso_alpha3' => $_POST['iso_alpha3'],
					'currency_code' => $_POST['currency_code'],
					'currency_name' => $_POST['currency_name'],
					'currency_symbol' => $_POST['currency_symbol'],
					'phonecode' => $_POST['phonecode'],
				);

				if ( 'new' === $_GET['status'] ) {
					
					$sql = 'insert into ' . $wpdb->prefix . 'st_countries (' . implode( ',', array_keys( $data ) ) . ') values("'.implode( '","', array_values( $data ) ).'")';
					$wpdb->query( $sql );
					
					simontaxi_set_message( 'success', esc_html__( 'Currency Added successfully', 'simontaxi' ) );
				} else {
					$wpdb->update( $wpdb->prefix .'st_countries', $data, array( 'id_countries' => $_GET['id'] ));
					simontaxi_set_message( 'success', esc_html__( 'Currency Updated successfully', 'simontaxi' ) );
				}
				$redirect_to = admin_url( 'admin.php?page=manage_countries' );
				wp_safe_redirect( $redirect_to );
			}
		}
		$details = array();
		if ( 'edit' === $status && $id > 0 ) {
			$sql = "SELECT * FROM {$wpdb->prefix}st_countries WHERE id_countries = '" . $id . "'";
			$details = $wpdb->get_results( $sql );
			if ( ! empty( $details ) ) {
				$details = ( array ) $details[0];
			}
		}
		?>
			 <form id="select-payment" action="" method="POST">
			<div class="wrap">
				<div id="icon-users" class="icon32"></div>
				<a style="float:right;" href="<?php echo admin_url( 'admin.php?page=manage_countries' ); ?>"><?php esc_html_e( 'Back to Countries', 'simontaxi' ); ?></a>

					<div id="booking-div">
					<?php echo simontaxi_print_errors(); ?>

						<table class="booking-status-update">
							<tbody>
							<tr>
								<th><?php esc_html_e( 'Name', 'simontaxi' ); ?></th><td>:<input type="text" class="form-control" name="name" id="name" placeholder="<?php esc_html_e( 'Name', 'simontaxi' ); ?>" value="<?php echo ! empty( $details['name'] ) ? $details['name'] : ''; ?>"></td>
							</tr>
							<tr>
								<th ><?php esc_html_e( 'ISO2', 'simontaxi' ); ?></th><td>:<input type="text" class="form-control" name="iso_alpha2" id="iso_alpha2" placeholder="<?php esc_html_e( 'ISO2', 'simontaxi' ); ?>" value="<?php echo ! empty( $details['iso_alpha2'] ) ? $details['iso_alpha2'] : ''; ?>"></td>
							</tr>
							<tr>
								<th ><?php esc_html_e( 'ISO3', 'simontaxi' ); ?></th><td>:<input type="text" class="form-control" name="iso_alpha3" id="iso_alpha3" placeholder="<?php esc_html_e( 'ISO3', 'simontaxi' ); ?>" value="<?php echo ! empty( $details['iso_alpha3'] ) ? $details['iso_alpha3'] : ''; ?>"></td>
							</tr>
							<tr>
								<th ><?php esc_html_e( 'Currency Code', 'simontaxi' ); ?></th><td>:<input type="text" class="form-control" name="currency_code" id="currency_code" placeholder="<?php esc_html_e( 'Currency Code', 'simontaxi' ); ?>" value="<?php echo ! empty( $details['currency_code'] ) ? $details['currency_code'] : ''; ?>"></td>
							</tr>

							<tr>
								<th ><?php esc_html_e( 'Currency Name', 'simontaxi' ); ?></th><td>:<input type="text" class="form-control" name="currency_name" id="currency_name" placeholder="<?php esc_html_e( 'Currency Name', 'simontaxi' ); ?>" value="<?php echo ! empty( $details['currency_name'] ) ? $details['currency_name'] : ''; ?>"></td>
							</tr>

							<tr>
								<th ><?php esc_html_e( 'Currency Symbol', 'simontaxi' ); ?></th><td>:<input type="text" class="form-control" name="currency_symbol" id="currency_symbol" placeholder="<?php esc_html_e( 'Currency Symbol', 'simontaxi' ); ?>" value="<?php echo ! empty( $details['currency_symbol'] ) ? $details['currency_symbol'] : ''; ?>"></td>
							</tr>

							<tr>
								<th ><?php esc_html_e( 'Phone Code', 'simontaxi' ); ?></th><td>:<input type="text" class="form-control" name="phonecode" id="phonecode" placeholder="<?php esc_html_e( 'Phone Code', 'simontaxi' ); ?>" value="<?php echo ! empty( $details['phonecode'] ) ? $details['phonecode'] : ''; ?>"></td>
							</tr>

							<tr>
								<th> &nbsp;</th><td>:
								<input type="hidden" name="simontaxi_country_nonce" value="<?php echo wp_create_nonce( 'simontaxi-country-nonce' ); ?>"/>
								<input type="submit" class="form-control" name="Save" id="Save" placeholder="<?php esc_html_e( 'Save', 'simontaxi' ); ?>" value="<?php esc_html_e( 'Save', 'simontaxi' ); ?>"></td>
							</tr>
							</tbody>
						</table>

						<a style="float:right;" href="<?php echo admin_url( 'admin.php?page=manage_countries' ); ?>"><?php esc_html_e( 'Back to Countries', 'simontaxi' ); ?></a>
					</div>

				</div>
			</form>

<?php
	} else {
	if ( ! class_exists( 'WP_List_Table' ) ) {
	   require_once( ABSPATH . 'age_paymentswp-admin/includes/class-wp-list-table.php' );
	}
	class Bookings_List_Countries extends WP_List_Table
	{

		/** Class constructor */
		public function __construct() {

			parent::__construct( array(
				'singular' => esc_html__( 'Country', 'simontaxi' ), //singular name of the listed records
				'plural'   => esc_html__( 'Countries', 'simontaxi' ), //plural name of the listed records
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
		        "new" => "<a class='' href='".admin_url( 'admin.php?page=manage_countries&status=new' ) . "'>" . esc_html__( 'New', 'simontaxi' ) . "</a>",
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
		public static function get_bookings( $per_page = 20, $page_number = 1 ) {
			global $wpdb;
			$bookings = $wpdb->prefix . 'st_countries';
			$sql = "SELECT * FROM `" . $bookings . "` WHERE 1=1 ";

			$search = ( isset( $_REQUEST['s'] ) ) ? $_REQUEST['s'] : false;
			if( $search ) {
				$sql .= " AND (`" . $bookings . "`.`name` LIKE '%" . $search . "%' OR `" . $bookings . "`.iso_alpha2 LIKE '%" . $search . "%' OR `" . $bookings . "`.iso_alpha3 LIKE '%" . $search . "%' OR `" . $bookings . "`.currency_code LIKE '%" . $search . "%' OR `" . $bookings . "`.currency_name LIKE '%" . $search . "%' OR `" . $bookings . "`.currency_symbol LIKE '%" . $search . "%' OR `" . $bookings . "`.phonecode LIKE '%" . $search . "%' ) ";
			}

			if ( ! empty( $_REQUEST['orderby'] ) ) {
				$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
				$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
			} else {
				$sql .= ' ORDER BY ' . $bookings . ' .name ASC';
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
			$wpdb->delete("{$wpdb->prefix}st_countries", array( 'id_countries' => $id ) , array( '%d' ) );
		}

		/**
		 * Returns the count of records in the database.
		 *
		 * @return null|string
		 */
		public static function record_count( $status='' ) {
			global $wpdb;
			$bookings = $wpdb->prefix . 'st_countries';
			$sql = "SELECT COUNT(*) FROM `" . $bookings . "`";		  
		  return $wpdb->get_var( $sql );
		}
		/** Text displayed when no booking data is available */
		public function no_items() {
		  esc_html_e( 'No Counties avaliable . ', 'simontaxi' );
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
		  $delete_nonce = wp_create_nonce( 'sp_delete_booking' );

		  $title = '<strong>' . $item['reference'] . '</strong>';

		  $actions = array(
		    'delete' => sprintf( '<a href="?page=%s&action=%s&booking=%s&_wpnonce=%s">' .  esc_html__( 'Delete', 'simontaxi' ) . '</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['ID'] ), $delete_nonce )
		  );

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
		    'countryname'    => esc_html__( 'Name', 'simontaxi' ),
		    'iso'    => esc_html__( 'ISO2 / ISO3', 'simontaxi' ),
		    'currency'    => esc_html__( 'Currency (Code / Symbol / Name)', 'simontaxi' ),
		    'phone'    => esc_html__( 'Phone', 'simontaxi' ),
		    'change_status'    => esc_html__( 'Change Status', 'simontaxi' )
		  );

		  return $columns;
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
			      return $this->column_cb( $item );
			    case 'countryname':
					$str = ' <span class="small-gray"> ' . $item['name'] . '</span>';
			      return $str;
			    case 'iso':
				  $str = '<span class="small-gray">' . $item['iso_alpha2'] . ' / ' . $item['iso_alpha3'] . '</span>';
				  return $str;
			    case 'currency':
			    	$str = '<span class="small-gray">' . $item['currency_code'] . ' / ' . $item['currency_symbol'] . ' / ' . $item['currency_name'] . '</span>';
			    	return $str;
				case 'phone':
			    	$str = '<span class="small-gray">' . $item['phonecode'] . '</span>';
			    	return $str;
			    case 'change_status':
			    	$delete_link = ' | <a href="' .admin_url( 'admin.php?page=manage_countries&status=delete&id=' . $item['id_countries']) . '" onclick="return confirm(\'' . esc_html__( 'Are you sure?', 'simontaxi' ) . '\' )">' . esc_html__( 'Delete', 'simontaxi' ) . '</a>';
					$delete_link = '';
					$edit_link = '<a href="' .admin_url( 'admin.php?page=manage_countries&status=edit&id=' . $item['id_countries']) . '">' . esc_html__( 'Edit', 'simontaxi' ) . '</a>';
					return $edit_link . $delete_link;
			    default:
			      return ucfirst( $item[ $column_name ]); //Show the whole array for troubleshooting purposes
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
		 // return sprintf( '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID'] );
		}



		/**
		 * Columns to make sortable.
		 *
		 * @return array
		 */
		public function get_sortable_columns() {
		  $sortable_columns = array(
		    //'ID' => array( 'ID', true ),
		    'status_updated' => array( 'status_updated', false ),
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
		    //'bulk-delete' => 'Delete'
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

		  $per_page     = simontaxi_get_option( 'records_per_page', 20);
		  
		  $current_page = $this->get_pagenum();
		  $total_items  = self::record_count();

		  $this->set_pagination_args( array(
		    'total_items' => $total_items, //WE have to calculate the total number of items
		    'per_page'    => $per_page //WE have to determine how many items to show on a page
		  ) );
		  $this->items = self::get_bookings( $per_page, $current_page );
		}

		public function process_bulk_action() {

		  //Detect when a bulk action is being triggered...
		  if ( 'delete' === $this->current_action() ) {

			self::delete_booking( absint( $_GET['booking_id'] ) );

			wp_redirect( esc_url( add_query_arg() ) );

			exit;
		  }

		  // If the delete bulk action is triggered
		  if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		       || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		  ) {

		    $delete_ids = esc_sql( $_POST['bulk-delete'] );

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
					 <input type="search" id="' . $input_id . '" name="s" value="' .(isset( $_REQUEST['s']) ? $_REQUEST['s'] : '' ) . '" placeholder="' .esc_html__( 'Ex: Country Name, Currency Code, Currency Name etc', 'simontaxi' ) . '"/>
					    ' .submit_button( $text, 'button', '', false, array( 'id' => 'search-submit' ,'style'=>'float:right;', 'onClick'=>'location.search+=\'&s=\'+document.getElementById(\'' . $input_id . '\' ).value;' ) ) . '
					</p>
			';

	    }

	}

	echo '<div class="wrap">
			<div id="icon-users" class="icon32"></div>
			<h2>' .esc_html__( 'Countires', 'simontaxi' ) . '</h2>';
				$bookings = new Bookings_List_Countries();
				$bookings->views();
				$bookings->prepare_items();
				$bookings->search_box(esc_html__( 'Search Country', 'simontaxi' ),'countries' );
				$bookings->display();
	echo '	</div>
		  </div>';
}
}
?>