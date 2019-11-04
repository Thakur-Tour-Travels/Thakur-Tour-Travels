<?php
/**
 * This template is used to display the user account form with [simontaxi_user_support]
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  simontaxi_user_support
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @global wpdb  $wpdb  WordPress database abstraction object.
 */
global $wpdb;
$wp_error = new WP_Error();

if ( isset( $_POST['send_message'] ) ) {
	$data = $_POST;
	if ( empty( $data['subject'] ) ) {
		$wp_error->add( 'subject', esc_html__( 'Please enter subject', 'simontaxi' ) );
	}
	if ( empty( $data['message'] ) ) {
		$wp_error->add( 'message', esc_html__( 'Please enter message', 'simontaxi' ) );
	}

	$first_name = $data['first_name'];
	$last_name = $data['last_name'];
	if ( empty( $first_name) ) {
		$wp_error->add( 'first_name', esc_html__( 'Please enter first name', 'simontaxi' ) );
	}
	if ( empty( $last_name) ) {
		$wp_error->add( 'last_name', esc_html__( 'Please enter last name', 'simontaxi' ) );
	}

	$mobile_countrycode = $data['mobile_countrycode'];
	$mobile = $data['mobile'];
	if ( empty( $mobile_countrycode) ) {
		$wp_error->add( 'mobile_countrycode', esc_html__( 'Please select country code', 'simontaxi' ) );
	}
	if ( empty( $mobile) ) {
		$wp_error->add( 'mobile', esc_html__( 'Please enter mobile number', 'simontaxi' ) );
	}
	if ( empty( $wp_error->errors ) ) {
		$current_user = wp_get_current_user();
		$data_array = array(
			'user_id' => get_current_user_id(),
			'first_name' => sanitize_text_field( $data['first_name'] ),
			'last_name' => sanitize_text_field( $data['last_name'] ),
			'mobile_phonecode' => sanitize_text_field( $data['mobile_countrycode'] ),
			'mobile' => sanitize_text_field( $data['mobile'] ),
			'email' => $current_user->data->user_email,
			'subject' => sanitize_text_field( $data['subject'] ),
			'message' => sanitize_text_field( $data['message'] ),
			'date' => date( 'Y-m-d H:i:s' ),
		);
		$wpdb->insert( $wpdb->prefix.'st_user_support', $data_array);

		wp_safe_redirect( simontaxi_get_bookingsteps_urls( 'user_support' ) );
	}
}
$current_user = wp_get_current_user();
$user_meta = simontaxi_filter_gk( ( array ) get_user_meta(  $current_user->ID ) );
?>
<!-- Booking Form -->
<div class="row">
	<div class="col-md-12">
		<?php if ( ! empty( $wp_error->errors ) ) { ?>
		<div class="alert alert-danger">
		<ul><?php echo implode( '</li><li>', $wp_error->get_error_messages() ); ?></ul>
		</div>
		<?php } ?>
		<?php
		/**
		 * @since 2.0.8
		 */
		$template = 'booking/includes/pages/user_left.php';
		if ( simontaxi_is_template_customized( $template ) ) {
			include_once( simontaxi_get_theme_template_dir_name() . $template );
		} else {
			include_once( apply_filters( 'simontaxi_locate_user_left', SIMONTAXI_PLUGIN_PATH . $template ) );
		}
		?>
		<div class="st-booking-block st-admin-booking-block">



			<div class="tab-content">
				<?php
				$action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';

				if ( $action == 'list' ) {
					$st_user_support = $wpdb->prefix . 'st_user_support';
					$users = $wpdb->prefix.'users';
					$per_page = 5;
					$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
					if ( $page > 1) {
					$offset = $page * $per_page - $per_page;
					} else {
					$offset = 0;
					}
					$query_all = 'SELECT s.* FROM ' . $st_user_support . ' s
					INNER JOIN ' . $users . ' u ON s.user_id = u.ID WHERE s.user_id = ' . get_current_user_id() . ' ORDER BY s.ID DESC';
					$query = $query_all . ' LIMIT ' . $per_page . ' OFFSET ' . $offset;
					//echo $query;
					$results = $wpdb->get_results( $query );
				?>
				<div id="st-booktab1" class="tab-pane fade in active">
					<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'create' ),simontaxi_get_bookingsteps_urls( 'user_support' ) ) ) ?>" class="btn btn-primary btn-support"><?php esc_html_e( 'Create', 'simontaxi' ); ?></a>
					<div class="table-responsive">
						<table class="table table-hover st-table st-table-sm">
							<thead>
							<tr>
							<th><?php esc_html_e( 'Subject', 'simontaxi' ); ?></th>
							<th><?php esc_html_e( 'Created', 'simontaxi' ); ?></th>
							<th><?php esc_html_e( 'Updated', 'simontaxi' ); ?></th>
							<th><?php esc_html_e( 'Status', 'simontaxi' ); ?></th>
							<th><?php esc_html_e( 'Actions', 'simontaxi' ); ?></th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ( $results as $row )
							{ ?>
							<tr>
							<td><?php echo esc_attr( $row->subject ); ?></td>
							<td><?php echo esc_attr( $row->date ); ?></td>
							<td><?php if ( $row->last_updated != '0000-00-00 00:00:00' ) { echo esc_attr( simontaxi_date_format( date_i18n( 'Y-m-d H:i:s', strtotime( $row->last_updated ) ), true ) ); } else { echo '-'; } ?></td>
							<td><?php echo esc_attr( $row->status ); ?></td>
							<td><a href="<?php echo add_query_arg( array( 'id' => $row->ID, 'action' => 'view' ),simontaxi_get_bookingsteps_urls( 'user_support' ) ); ?>" class="btn btn-dark btn-sm"><?php esc_html_e( 'View', 'simontaxi' ); ?></a></td>
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
							echo paginate_links( array(
								'base' => add_query_arg( 'cpage', '%#%' ),
								'format' => '',
								'prev_text' => esc_html__( '&laquo;', 'simontaxi' ),
								'next_text' => esc_html__( '&raquo;', 'simontaxi' ),
								'total' => ceil( $total / $per_page),
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
				<?php } ?>

				<?php if ( $action == 'create' || $action == 'view' ) {
				$disabled = ( $action == 'view' ) ? ' disabled' : '';
				$row = array(
						'subject' => '',
						'message' => '',
						'first_name' => isset( $user_meta['first_name'] ) ? $user_meta['first_name'] : '',
						'last_name' => isset( $user_meta['last_name'] ) ? $user_meta['last_name'] : '',
						'mobile_phonecode' => ( isset( $user_meta['mobile_countrycode'] ) ) ? $user_meta['mobile_countrycode'] : '',
						'mobile' => isset( $user_meta['mobile'] ) ? $user_meta['mobile'] : '',
					);
				if ( $action == 'view' && ( isset( $_GET['id'] ) && $_GET['id'] > 0) ) {
					$st_user_support = $wpdb->prefix . 'st_user_support';
					$query = 'SELECT * FROM ' . $st_user_support . ' WHERE ID = ' . $_GET['id'];
					$row = (array)$wpdb->get_row( $query );
				}
				?>
				<!-- TAB-1 -->
				<div id="st-booktab1" class="tab-pane fade in active">
					<form class="st-booking-form row" id="update_user_account" method="POST" action="">

						<div class="form-group col-sm-12">
							<label for="subject"><?php esc_html_e( 'Subject', 'simontaxi' ); ?></label>
							<div class="inner-addon right-addon">
								<input type="text" class="form-control" name="subject" id="subject" placeholder="<?php esc_html_e( 'Subject', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $_POST, 'subject', $row['subject'] ); ?>" <?php echo $disabled; ?>>
							</div>
						</div>
						<div class="form-group col-sm-12">
							<label for="message"><?php esc_html_e( 'Message', 'simontaxi' ); ?></label>
							<div class="inner-addon right-addon">
								<textarea id="message" name="message" class="form-control" placeholder="<?php esc_html_e( 'Enter Message', 'simontaxi' ); ?>" rows="4" <?php echo $disabled; ?>><?php echo simontaxi_get_value( $_POST, 'message', $row['message'] ); ?></textarea>
							</div>
						</div>


						<div class="form-group col-sm-6">
							<label for="first_name"><?php esc_html_e( 'First Name', 'simontaxi' ); ?></label>
							<div class="inner-addon right-addon">
								<input type="text" class="form-control" name="first_name" id="first_name" placeholder="<?php esc_html_e( 'First Name', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $_POST, 'first_name', $row['first_name'] ); ?>" <?php echo $disabled; ?>>
							</div>
						</div>
						<div class="form-group col-sm-6">
							<label for="last_name"><?php esc_html_e( 'Last Name', 'simontaxi' ); ?></label>
							<div class="inner-addon right-addon">
								<input type="text" class="form-control" name="last_name" id="last_name" placeholder="<?php esc_html_e( 'Last Name', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $_POST, 'last_name', $row['last_name'] ); ?>" <?php echo $disabled; ?>>
							</div>
						</div>

						<div class="form-group col-sm-6">
							<label for="mobile_countrycode"><?php esc_html_e( 'Country code', 'simontaxi' ); ?></label>
							<div class="inner-addon right-addon">
							<?php
							$countryList = simontaxi_get_countries();
							?>
							<select id="mobile_countrycode" name="mobile_countrycode" title="<?php esc_html_e( 'Country code', 'simontaxi' ); ?>"class="selectpicker show-tick show-menu-arrow" <?php echo $disabled; ?>>
							<option value=""><?php esc_html_e( 'Country code', 'simontaxi' ); ?></option>
							<?php
							if ( $countryList) {
								$mobile_countrycode = simontaxi_get_value( $_POST, 'mobile_countrycode', $row['mobile_phonecode'] );
								foreach ( $countryList as $result) {
									$code = $result->phonecode . '_' . $result->id_countries;
									?>
									<option value="<?php echo $code; ?>" <?php if ( $mobile_countrycode == $code) echo 'selected="selected"'; ?>><?php echo $result->name . ' ( ' . $result->phonecode . ' )'; ?> </option>
									<?php
								}
							}
							?>
							</select>
							</div>
						</div>
						<div class="form-group col-sm-6">
							<label for="mobile"><?php esc_html_e( 'Mobile phone', 'simontaxi' ); ?></label>
							<div class="inner-addon right-addon">
								<input type="text" class="form-control" id="mobile" name="mobile" placeholder="<?php esc_html_e( 'Phone number to receive SMS', 'simontaxi' ); ?>" value="<?php echo simontaxi_get_value( $_POST, 'mobile', $row['mobile'] ); ?>" <?php echo $disabled; ?>>
							</div>
						</div>
						<?php if ( $action == 'view' ) {
							?>
							<div class="form-group col-sm-12">
							<label for="message"><?php esc_html_e( 'Admin Comments', 'simontaxi' ); ?></label>
							<div class="inner-addon right-addon">
								<textarea id="message" name="message" class="form-control" placeholder="<?php esc_html_e( 'Admin Comments', 'simontaxi' ); ?>" rows="4" <?php echo $disabled; ?>><?php echo $row['admin_comments']; ?></textarea>
							</div>
						</div>
							<?php
						}?>

						<div class="col-sm-12">
							<?php if ( $action !== 'view' ) { ?>
							<button type="submit" class="btn btn-primary" name="send_message" <?php echo $disabled; ?>><?php esc_html_e( 'Send', 'simontaxi' ); ?></button>
							<?php } ?>
							<a class="btn btn-dull btn-second" href="<?php echo esc_url(simontaxi_get_bookingsteps_urls( 'user_support' ) ); ?>"><?php esc_html_e( 'Cancel', 'simontaxi' ); ?></a>
						</div>

					</form>
				</div>
				<?php } ?>

			</div>
		</div>
	</div>
</div>
<!-- /Booking Form -->