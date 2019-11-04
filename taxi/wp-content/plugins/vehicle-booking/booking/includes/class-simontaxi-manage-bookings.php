<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WP_List_Table' ) ) {
   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class Bookings_List extends WP_List_Table
{

	public $user_id = 0;
	/** Class constructor */
	public function __construct( $user_id = 0 ) {

		parent::__construct( array(
			'singular' => esc_html__( 'Booking', 'simontaxi' ), //singular name of the listed records
			'plural'   => esc_html__( 'Bookings', 'simontaxi' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?

		) );
		$this->user_id = $user_id;

	}

	/**
	*   get_views] : Frontend Filters
	*
	*
	*/

	protected function get_views() {
		$status_links = apply_filters( 'simontaxi_status_button_links', array(
			"new" => "<a class='' href='".admin_url( 'admin.php?page=manage_bookings&status=new' ) .  "'>" . esc_html__( 'New', 'simontaxi' ) . " <span class='status-count bg-danger'>" . $this->record_count( 'new' ) . "</span></a>",
			"confirmed"   => "<a class='' href='".admin_url( 'admin.php?page=manage_bookings&status=confirmed' ) . "'>" . esc_html__( 'Confirmed', 'simontaxi' ) . " <span class='status-count bg-purple'>" . $this->record_count( 'confirmed' ) . "</span></a>",
			"onride"   => "<a class='' href='".admin_url( 'admin.php?page=manage_bookings&status=onride' ) . "'>" . esc_html__( 'On Ride', 'simontaxi' ) . " <span class='status-count bg-warning'>" . $this->record_count( 'onride' ) . "</span></a>",		        
			"success"   => "<a class='' href='".admin_url( 'admin.php?page=manage_bookings&status=success' ) . "'>" . esc_html__( 'Completed', 'simontaxi' ) . "<span class='status-count bg-happygreen'>" . $this->record_count( 'success' ) . " </span></a>",
			"cancelled"   => "<a class='' href='".admin_url( 'admin.php?page=manage_bookings&status=cancelled' ) . "'>" . esc_html__( 'Cancelled', 'simontaxi' ) . " <span class='status-count bg-cancel'>" . $this->record_count( 'cancelled' ) . "</span></a>",
			"expired"       => "<a class='' href='".admin_url( 'admin.php?page=manage_bookings&status=expired' ) . "'>" . esc_html__( 'Expired', 'simontaxi' ) . " <span class='status-count bg-sky'>" . $this->record_count( 'expired' ) . "</span></a>",
			"all"       => "<a class='' href='".admin_url( 'admin.php?page=manage_bookings&status=all' ) . "'>" . esc_html__( 'All', 'simontaxi' ) . " <span class='status-count bg-sky'>" . $this->record_count( 'all' ) . "</span></a>",			
		) );
		
		$current_status_view = isset( $_REQUEST['status']) ? $_REQUEST['status'] : 'new';
		
		$status_links_temp = $status_links;
		if ( ! empty( $status_links_temp ) ) {
			$status_links = array();
			foreach( $status_links_temp as $key => $status_link ) {
				if ( $key == $current_status_view ) {
					$key = $key . ' active';
				}
				$status_links[ $key ] = $status_link;
			}
		}
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
	public function get_bookings( $args = array() ) {
		$per_page = ! empty( $args['per_page'] ) ? $args['per_page'] : 20;
		$page_number = ! empty( $args['page_number'] ) ? $args['page_number'] : 1;
		
		$status = ! empty( $_REQUEST['status'] ) ? $_REQUEST['status'] : '';
		$key = "simontaxi_bookings_page_$page_number";
		if ( ! empty( $status ) ) {
			$key = "simontaxi_bookings_page_{$status}_{$page_number}";
		}
		$result = get_option( $key, false );
		
		if ( false === $result ) {
			global $wpdb;
			$bookings = $wpdb->prefix . 'st_bookings';
			$payments = $wpdb->prefix . 'st_payments';
			$sql = "SELECT *, `" . $bookings . "`.`ID` AS booking_id, `" . $bookings . "`.`reference` AS booking_ref FROM `" . $bookings . "` INNER JOIN `" . $payments . "` ON `" . $payments . "`.`booking_id`=`" . $bookings . "`.`ID`";
			
			if ( ! simontaxi_is_admin_user() ) {
				$sql .= apply_filters( 'simontaxi_bookings_join_condition', $sql );
			}
			
			$sql .= " WHERE `" . $bookings . "`.booking_contacts!='' ";
			$today = date_i18n( 'Y-m-d' );
			if ( isset( $_REQUEST['status']) && ( $_REQUEST['status'] != 'all' ) ){
				if ( 'expired' == $_REQUEST['status'] ) {
					$sql .=" AND `" . $bookings . "`.`status`='new' AND pickup_date < '" . $today . "'";
				} elseif ( 'new' == $_REQUEST['status'] ) {
					$sql .=" AND `" . $bookings . "`.`status`='" . $_REQUEST['status'] . "' AND pickup_date >= '" . $today . "'";
				} else {
					$sql .=" AND `" . $bookings . "`.`status`='" . $_REQUEST['status'] . "'";
				}
			}
			if( ! isset( $_REQUEST['status'])) {
				$sql .=" AND `" . $bookings . "`.`status`='new' AND pickup_date >= '" . $today . "'";
			}
			
			if ( $this->user_id > 0 ) {
				$sql .=" AND $bookings.user_id=" . $this->user_id;
			}
			
			$search = ( isset( $_REQUEST['s'] ) ) ? $_REQUEST['s'] : false;
			if( $search ) {
				$sql .= " AND (`" . $bookings . "`.`reference` LIKE '%" . $search . "%' OR `" . $bookings . "`.booking_contacts LIKE '%" . $search . "%' OR `" . $bookings . "`.vehicle_name LIKE '%" . $search . "%' ) ";
			}
			
			if ( ! simontaxi_is_admin_user() ) {
				$sql .= apply_filters( 'simontaxi_bookings_where_condition', $sql );
			}
			
			/**
			 * Let us allow other plugins to change the query as per their requirements before records limit applied
			 *
			 * @since 2.0.9
			 */
			$sql = apply_filters( 'simontaxi_manage_bookings_query_before_offset', $sql );

			if ( ! empty( $_REQUEST['orderby'] ) ) {
				$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
				$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
			} else {
				$sql .= ' ORDER BY ' . $bookings . ' .ID DESC';
			}	
			
			$sql .= " LIMIT $per_page";
			
			$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
			/**
			 * Let us allow other plugins to change the query as per their requirements after records limit applied
			 *
			 * @since 2.0.9
			 */
			$sql = apply_filters( 'simontaxi_manage_bookings_query_after_limit', $sql );
			// echo $sql;
			$result = $wpdb->get_results( $sql, 'ARRAY_A' );
			
			update_option( $key, $result );
		}
		return $result;
	}

	/**
	 * Delete a Booking record.
	 *
	 * @param int $id booking ID
	 */
	public static function delete_booking( $id ) {
		global $wpdb;
		
		$bookings = $wpdb->prefix . 'st_bookings';
		$sql = "SELECT `status` FROM `" . $bookings . "` WHERE ID = " . $id;
		$status = $wpdb->get_var( $sql );
		
		$wpdb->delete("{$wpdb->prefix}st_payments", array( 'booking_id' => $id ), array( '%d' ) );

		$wpdb->delete("{$wpdb->prefix}st_bookings", array( 'ID' => $id ), array(  '%d' ) );
		
		simontaxi_update_count( $status, 'decrease' );
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public function record_count( $status='' ) {
		
		$key = 'simontaxi_bookings_total';
		if ( ! empty( $key ) ) {
			$key = 'simontaxi_bookings_' . $status;
		}
		$total = get_option( $key, false );
		
		if( false === $total ) {
			global $wpdb;

			$bookings = $wpdb->prefix . 'st_bookings';
			$payments = $wpdb->prefix . 'st_payments';

			$sql = "SELECT COUNT(*) FROM `" . $bookings . "` INNER JOIN `" . $payments . "` ON `" . $payments . "`.`booking_id`=`" . $bookings . "`.`ID`";
			if ( ! simontaxi_is_admin_user() ) {
				$sql .= apply_filters( 'simontaxi_bookings_join_condition', '' );
			}
			$sql .= " WHERE `" . $bookings . "`.booking_contacts!='' ";
			$today = date_i18n( 'Y-m-d' );
			if ( $status == '' ) {
				$sql .=" AND `" . $bookings . "`.`status`='new' AND pickup_date < '" . $today . "'";
			} elseif( $status != 'all' ) {
				if ( 'expired' == $status ) {
					$sql .= " AND status='new' AND pickup_date < '" . $today . "'";
				} elseif( 'new' == $status ) {
					$sql .= " AND status='" . $status . "' AND pickup_date >= '" . $today . "'";
				} else {
					$sql .= " AND status='" . $status . "'";
				}
			}
			if ( $this->user_id > 0 ) {
				$sql .=" AND $bookings.user_id=" . $this->user_id;
			}
			if ( ! simontaxi_is_admin_user() ) {
				$sql .= apply_filters( 'simontaxi_bookings_where_condition', '' );
			}
			$total = $wpdb->get_var( $sql );

			// Store the total for the first time
			update_option( $key, $total );
	}
	  return $total;
	}
	/** Text displayed when no booking data is available */
	public function no_items() {
	  esc_html_e( 'No Bookings avaliable . ', 'simontaxi' );
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
	  $columns = apply_filters( 'manage_bookings_columns', array(
		'cb'      => '<input type="checkbox" />',
		'ID'    => esc_html__( 'ID #Reference', 'simontaxi' ),
		'pickup_location'    => esc_html__( 'From - To', 'simontaxi' ),
		'booking_contacts'    => esc_html__( 'Customer', 'simontaxi' ),
		'status_updated'    => esc_html__( 'Status', 'simontaxi' ),
		'payment_id'    => esc_html__( 'Payment', 'simontaxi' ),
		'change_status'    => esc_html__( 'Change Status', 'simontaxi' )
	  ) );

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
			case 'ID':
			  $str = ' <span class="small-gray"> ' . $item['booking_id'] . '-' . $item['booking_ref'] . '</span><br><span class="small-gray">' . esc_html__( 'Booking Date : ', 'simontaxi' ) . simontaxi_date_format( $item['date'] ) . '</span><br> <a href="' .admin_url( 'admin.php?page=manage_bookings&view_status=' . $item['booking_id']) . '">' . esc_html__( 'View Details', 'simontaxi' ) . '</a>';
			  $str = apply_filters( 'simontaxi_flt_ID_table', $str, $item );
			  return $str;			  
			case 'pickup_location':
			  $vehicle_id = $item['selected_vehicle'];
			  $str = simontaxi_get_address( $item, 'pickup_location' ) . ' - ' . simontaxi_get_address( $item, 'drop_location' ) . '<br><span class="small-gray">' . simontaxi_date_format( $item['pickup_date'] ) . ' ' . simontaxi_get_time_display_format( $item['pickup_time'] ) . '</span>';
			  if ( in_array( $item['journey_type'], apply_filters( 'simontaxi_twoway_other_tabs_step1', array( 'two_way' ) ) ) ) {
				  $return_pickup_date = (isset( $item['pickup_date_return'])) ? simontaxi_date_format( $item['pickup_date_return'] ) : '';
				  $return_pickup_date .= (isset( $item['pickup_time_return'])) ? simontaxi_get_time_display_format( $item['pickup_time_return'] ) : '';
				  $str .= '<br>' . simontaxi_get_address( $item, 'drop_location' ) . ' - ' . simontaxi_get_address( $item, 'pickup_location' ) . '<br><span class="small-gray">' . esc_html__( 'Return : ', 'simontaxi' ) . $return_pickup_date . '</span>';
			  }
			  $str .= '&nbsp<span class="small-gray">' . strtoupper( esc_html( simontaxi_get_booking_type( $item['booking_type'] ) ) . ' - ' . $item['journey_type'] . ' - <a href="'. esc_url(get_permalink( $vehicle_id ) ) . '" target="_blank" class="vehicle_details" data-vehicle_id="' . esc_attr( $vehicle_id ) . '" data-booking_id="' . esc_attr( $item['booking_id'] ) . '">' . $item['vehicle_name']) . '</a></span>';
// dd( $item );
			  $session_details = json_decode( $item['session_details'] );
			  if ( is_object( $session_details ) ) {
				  $session_details = (array) $session_details;
			  }
			  if ( ! empty( $session_details ) ) {
					if ( ! empty( $session_details[0]->distance_text ) ) {
						$str .= '<br><span class="small-gray">Distance: ' . $session_details[0]->distance_text . '</span>';
					}
				}
			$str = apply_filters( 'simontaxi_pickup_location_table', $str, $item );
			return $str;
			case 'booking_contacts':
				$det = (array)json_decode( $item['booking_contacts']);
				$str = '';
				$mobile_countrycode = '';
				if ( ! empty( $det['mobile_countrycode'] ) ) {
					$parts = explode( '_', $det['mobile_countrycode'] );
					if ( ! empty( $parts[0] ) ) {
						$mobile_countrycode = '+' . $parts[0] . ' ';
					}
				}
				if ( ! empty( $det['full_name']) ) {
					$str = ucfirst( $det['full_name']) . '<br><span class="small-gray">';
					
					
					
					if ( ! empty( $det['mobile']) ) {
						$str .= $mobile_countrycode . $det['mobile'] . ' | ';
					}
					$str .= $det['email'] . '</span>';
				} elseif ( ! empty( $det['first_name']) ) {
					$str = $det['first_name'];
					if ( ! empty( $det['last_name'] ) ) {
						$str .= ' ' . $det['last_name'];
					}
					if ( ! empty( $det['mobile']) ) {
						$str .= '<br><span class="small-gray">' . $mobile_countrycode . $det['mobile'] . '</span>';
					}
				}else {
					$str = ucfirst( $det['email']);
					if ( ! empty( $det['mobile']) ) {
						$str .= '<br><span class="small-gray">' . $mobile_countrycode . $det['mobile'] . '</span>';
					}
				}
				
				/**
				 * @since 2.0.6
				 */
				
				if ( ! empty( $det['company_name'] ) ) {
					$str .= '<br><span class="small-gray">Company: ' . $det['company_name'] . '</span>';
				}
				if ( ! empty( $det['land_mark_pickupaddress'] ) ) {
					$str .= '<br><span class="small-gray">Landmark / Pickup address: ' . $det['land_mark_pickupaddress'] . '</span>';
				}
				if ( ! empty( $det['special_instructions'] ) ) {
					$str .= '<br><span class="small-gray">Special Instructions: ' . $det['special_instructions'] . '</span>';
				}
				
				$persons = '';
				$session_details = json_decode( $item['session_details'] );
				if ( is_object( $session_details ) ) {
				  $session_details = (array) $session_details;
			  }
				if ( ! empty( $session_details ) ) {
					if ( ! empty( $session_details[0]->number_of_persons ) ) {
						$persons .= '<br><span class="small-gray">' . esc_html__('No. of passengers:', 'simontaxi') .  $session_details[0]->number_of_persons . '</span>';
					}
				}					
				if ( $persons == '' && ! empty( $det['no_of_passengers'] ) ) {
					$persons .= '<br><span class="small-gray">'.esc_html__('No. of passengers:', 'simontaxi') . $det['no_of_passengers'] . '</span>';
				}
				
				if ( ! empty( $session_details ) ) {
					if ( ! empty( $session_details[0]->flight_no ) ) {
						$persons .= '<br><span class="small-gray">' . esc_html__('Flight No.:', 'simontaxi') . $session_details[0]->flight_no . '</span>';
					}
					if ( ! empty( $session_details[0]->flight_arrival_time ) ) {
						$persons .= '<br><span class="small-gray">' . esc_html__('Arrival Time: ', 'simontaxi') . $session_details[0]->flight_arrival_time . '</span>';
					}
				}
				
				$str .= $persons;
				$str = apply_filters( 'simontaxi_booking_contacts_table', $str, $item );
				return $str;
			case 'change_status':
				$delete_link = ' | <a href="' .admin_url( 'admin.php?page=manage_bookings&action=delete&booking_id=' . $item['booking_id']) . '" onclick="return confirm(\'' . esc_html__( 'Are you sure?', 'simontaxi' ) . '\' )">' . esc_html__( 'Delete', 'simontaxi' ) . '</a>';
				$all_links = '';
				if ( $item['status'] == 'new' ) {
					$all_links = '<a href="' .admin_url( 'admin.php?page=manage_bookings&change_status=confirmed&booking_id=' . $item['booking_id']) . '">' . esc_html__( 'Confirm', 'simontaxi' ) . '</a> | <a href="' .admin_url( 'admin.php?page=manage_bookings&change_status=cancelled&booking_id=' . $item['booking_id']) . '">' . esc_html__( 'Cancel', 'simontaxi' ) . '</a>' . $delete_link;
				} elseif ( $item['status'] == 'confirmed' ) {
					$all_links = '<a href="' .admin_url( 'admin.php?page=manage_bookings&change_status=onride&booking_id=' . $item['booking_id']) . '">' . esc_html__( 'Start Ride', 'simontaxi' ) . '</a> | <a href="' .admin_url( 'admin.php?page=manage_bookings&change_status=cancelled&booking_id=' . $item['booking_id']) . '">' . esc_html__( 'Cancel', 'simontaxi' ) . '</a>' . $delete_link;
				} elseif ( $item['status'] == 'onride' ) {
					$all_links = '<a href="' .admin_url( 'admin.php?page=manage_bookings&change_status=success&booking_id=' . $item['booking_id']) . '">' . esc_html__( 'Completed', 'simontaxi' ) . '</a> | <a href="' .admin_url( 'admin.php?page=manage_bookings&change_status=cancelled&booking_id=' . $item['booking_id']) . '">' . esc_html__( 'Cancel', 'simontaxi' ) . '</a>' . $delete_link;
				} else {
					$all_links = '<span class="small-gray">' . esc_html__( 'NO ACTIONS', 'simontaxi' ) . '</span>';
				}
				$all_links = apply_filters( 'simontaxi_status_links_table', $all_links, $item );
				/*ob_start(); ?>
				<div class="dropdown more">
					<a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="fa fa-ellipsis-v fa-2x"></i>
					</a>
				</div>
				<?php
				$all_links = ob_get_clean();
				*/
				
				return $all_links;
			case 'payment_id':
				$str = simontaxi_get_currency( apply_filters( 'simontaxi_amount_paid', $item['amount_paid'], $item ) )  . ' / ' . simontaxi_get_currency( apply_filters( 'simontaxi_amount_payable',  $item['amount_payable'], $item ) ) . ' <span class="small-gray">' . ucfirst( $item['payment_method'] ) . '</span><br>' . strtoupper( $item['payment_status'] ) . '<br><span class="small-gray">' . $item['datetime'] . '</span>';
				$str = apply_filters( 'simontaxi_flt_payment_id_table', $str, $item );
				return $str;
			case 'status_updated':
				$str = strtoupper( $item['status']) . '<br><span class="small-gray">' . simontaxi_date_format( $item['status_updated'], true ) . '</span>';
				$str = apply_filters( 'simontaxi_flt_status_updated_table', $str, $item );
				return $str;
			default:
			  return apply_filters( 'booking_column_value', $item, $column_name ); //Show the whole array for troubleshooting purposes
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
	  $status = ! empty( $_GET['status'] ) ? $_GET['status'] : 'new';
	  $total_items  = self::record_count( $status );
	  
	  $this->set_pagination_args( array(
		'total_items' => $total_items, //WE have to calculate the total number of items
		'per_page'    => $per_page //WE have to determine how many items to show on a page
	  ) );



	  $this->items = self::get_bookings( array( 'per_page' => $per_page, 'current_page' => $current_page ) );

	 // var_dump( $this->items );
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

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
		}
		if ( ! empty( $_REQUEST['order'] ) ) {
			echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
		}
		if ( ! empty( $_REQUEST['post_mime_type'] ) ) {
			echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( $_REQUEST['post_mime_type'] ) . '" />';
		}
		if ( ! empty( $_REQUEST['detached'] ) ) {
			echo '<input type="hidden" name="detached" value="' . esc_attr( $_REQUEST['detached'] ) . '" />';
		}
		$url = admin_url( 'admin.php?page=manage_bookings&change_status=reset_counts');
		echo '&nbsp;&nbsp;<a href="' . $url . '" class="button" style="margin-top:10px;">' . esc_html__( 'Reset Counts', 'simontaxi' ) . '</a><p class="search-box" style="margin-top:10px;">
					<input type="search" id="' . $input_id . '" name="s" value="' .(isset( $_REQUEST['s']) ? $_REQUEST['s'] : '' ) . '" placeholder="' .esc_html__( 'Ex: Booking Ref, Vehicle, Customer Name, Mobile', 'simontaxi' ) . '"/>
					' .submit_button( $text, 'button', '', false, array( 'id' => 'search-submit' ,'style'=>'float:right;margin-top:10px;', 'onClick'=>'location.search+=\'&s=\'+document.getElementById(\'' . $input_id . '\' ).value;' ) ) . '
				</p>';

	}
	
	/**
	 * Process content of CSV file
	 *
	 * @since 0.1
	 **/
	public function generate_csv( $args = array() ) {

			$defaults = array( 'content'    => 'all',
			                   'author'     => false,
			                   'category'   => false,
			                   'start_date' => false,
			                   'end_date'   => false,
			                   'status'     => false,
			);

			$user_args = array(
				'role'   => wp_kses_post( $_GET['role'] ),
				'fields' => 'all_with_meta',
			);

			$merge_args = array_merge( $defaults, $user_args );

			$args = wp_parse_args( $args, $merge_args );

			
			$users = get_users( $args );
			

			if ( ! $users ) {
				$referer = add_query_arg( 'error', 'empty', wp_get_referer() );
				wp_redirect( $referer );
				exit;
			}

			$sitename = sanitize_key( get_bloginfo( 'name' ) );
			if ( ! empty( $sitename ) ) {
				$sitename .= '.';
			}
			$filename = $sitename . 'users.' . date( 'Y-m-d-H-i-s' ) . '.csv';

			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			header( 'Content-Type: text/csv; charset=' . get_option( 'blog_charset' ), true );

			$exclude_data = apply_filters( 'pp_eu_exclude_data', array() );

			global $wpdb;

			$data_keys = array(
				'ID',
				'user_login',
				'user_pass',
				'user_nicename',
				'user_email',
				'user_url',
				'user_registered',
				'user_activation_key',
				'user_status',
				'display_name'
			);
			$meta_keys = $wpdb->get_results( "SELECT distinct(meta_key) FROM $wpdb->usermeta" );
			$meta_keys = wp_list_pluck( $meta_keys, 'meta_key' );
			$fields    = array_merge( $data_keys, $meta_keys );

			$headers = array();

			foreach ( $fields as $key => $field ) {
				if ( in_array( $field, $exclude_data ) ) {
					unset( $fields[ $key ] );
				} else {
					$headers[] = '"' . strtolower( $field ) . '"';
				}
			}

			echo implode( ',', $headers ) . "\n";

			foreach ( $users as $user ) {
				$data = array();
				foreach ( $fields as $field ) {
					$value  = isset( $user->{$field} ) ? $user->{$field} : '';
					$value  = is_array( $value ) ? serialize( $value ) : $value;
					$data[] = '"' . str_replace( '"', '""', $value ) . '"';
				}

				echo implode( ',', $data ) . "\n";
			}

			exit;
	}
	
	public function get_bookings_with_email( $user_email ) {
		global $wpdb;
		
		$bookings = $wpdb->prefix . 'st_bookings';
		$payments = $wpdb->prefix . 'st_payments';
		$sql = "SELECT $bookings.ID AS booking_id FROM $bookings INNER JOIN $payments ON $payments.booking_id= $bookings.ID WHERE $bookings.booking_contacts LIKE '%$user_email%' AND $bookings.user_id = 0";
		return $wpdb->get_results( $sql );
	}
	
	public function get_bookings_details( $booking_id ) {
		global $wpdb;
		
		$bookings = $wpdb->prefix . 'st_bookings';
		$payments = $wpdb->prefix . 'st_payments';
		$sql = "SELECT *, $bookings.ID AS booking_id, $bookings.reference AS booking_ref FROM $bookings INNER JOIN $payments ON $payments.booking_id= $bookings.ID WHERE $bookings.ID = $booking_id";
		return $wpdb->get_row( $sql );
	}
	
	public function set_customer_id( $booking_id, $customer_id ) {
		global $wpdb;
		$wpdb->update( array( 'user_id' => $customer_id ), $wpdb->prefix . 'st_bookings', array( 'ID' => $booking_id ) );
	}
}