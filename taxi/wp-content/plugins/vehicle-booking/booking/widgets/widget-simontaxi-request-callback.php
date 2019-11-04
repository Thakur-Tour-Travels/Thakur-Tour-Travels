<?php
/**
 * Widget API: Simon_Widget_Requestcall class
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  widget
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class Simon_Widget_Requestcall extends WP_Widget {

	/**
	 * Sets up a new Archives widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'simon_widget_requestcall',
			'description' => esc_html__( 'This widget will display request callback widget.', 'simontaxi' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'simontaxi-request-callback', esc_html__( 'Simontaxi Request callback', 'simontaxi' ), $widget_ops );
		$this->alt_option_name = 'widget_request_callback';
	}

	/**
	 * Outputs the content for the current Archives widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Archives widget instance.
	 */
	public function widget( $args, $instance ) {
		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__( 'Request A Callback', 'simontaxi' ) : $instance['title'], $instance, $this->id_base );

		$name_field = isset( $instance['name_field'] ) ? (bool) $instance['name_field'] : false;
		$email_field = isset( $instance['email_field'] ) ? (bool) $instance['email_field'] : false;
		$phone_field = isset( $instance['phone_field'] ) ? (bool) $instance['phone_field'] : false;
		$button_title = empty( $instance['button_title'] ) ? esc_html__( 'Submit', 'simontaxi' ) : $instance['button_title'];
		echo $args['before_widget'];
		echo $args['before_title'] . $title . $args['after_title'];
		?>
		<form class="" action="#" method="post">
			<?php if($name_field) { ?>
			<div class="input-group">
				<input type="text" class="form-control" name="name" id="name" placeholder="Name" required="">
			</div>
			<?php } ?>
			<?php if($email_field) { ?>
			<div class="input-group">
				<input type="text" class="form-control" name="email" id="email" placeholder="Email" required="">
			</div>
			<?php } ?>
			<?php if($phone_field) { ?>
			<div class="input-group">
				<input type="text" class="form-control" name="phone" id="phone" placeholder="Phone no" required="">
			</div>
			<?php } ?>
			<button type="button" class="btn btn-secondary st-widget-btn callbackbutton"><?php echo $button_title;?></button>
		</form>
		<script>
		jQuery('.callbackbutton').click(function(){
			var name,phone,email;
			name=phone=email = '';
			jQuery('#name').removeClass('error');
			jQuery('#email').removeClass('error');
			if(jQuery('#name').length > 0) {
				if(jQuery('#name').val() == '')
				{
					//alert('Please enter name')
					jQuery('#name').addClass('error');
					jQuery('#name').focus();
					return false;
				}
				name = jQuery('#name').val();
			}



			if(jQuery('#email').length > 0) {
				if(jQuery('#email').val() == '')
				{
					//alert('Please enter email address')
					jQuery('#email').addClass('error');
					jQuery('#email').focus();
					return false;
				}

    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;

				if(!pattern.test(jQuery('#email').val()))
				{
					alert('Please enter valid email address')
					jQuery('#email').focus();
					return false;
				}
				email = jQuery('#email').val();
			}

			if(jQuery('#phone').length > 0) {
				if(jQuery('#phone').val() == '')
				{
					alert('Please enter phone')
					jQuery('#phone').focus();
					return false;
				}
				phone = jQuery('#phone').val();
			}

			var data = {
				'action': 'save_request_callback',
				'name': name,
				'phone' : phone,
				'email' : email
			};
			jQuery.post('<?php echo admin_url( 'admin-ajax.php ' );?>', data
				, function (response) {
					var result = jQuery.parseJSON(response);
					alert(result['msg']);
					jQuery('#name').val('');
					jQuery('#email').val('');
					jQuery('#phone').val('');
				});
		});
		</script>
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Handles updating settings for the current Archives widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget_Archives::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'name_field' => true, 'phone_field' => false, 'email_field' => false, 'button_title' => 'Submit') );
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['name_field'] = isset( $new_instance['name_field'] ) ? (bool) $new_instance['name_field'] : false;
		$instance['phone_field'] = isset( $new_instance['phone_field'] ) ? (bool) $new_instance['phone_field'] : false;
		$instance['email_field'] = isset( $new_instance['email_field'] ) ? (bool) $new_instance['email_field'] : false;
		$instance['button_title'] = sanitize_text_field( $new_instance['button_title'] );
		return $instance;
	}

	/**
	 * Outputs the settings form for the Archives widget.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'phone' => '', 'image' => '') );

		$title = isset($instance['title']) ? sanitize_text_field( $instance['title'] ) : '';
		$name_field = isset( $instance['name_field'] ) ? $instance['name_field'] : false;
		$phone_field = isset( $instance['phone_field'] ) ? $instance['phone_field'] : false;
		$email_field = isset( $instance['email_field'] ) ? $instance['email_field'] : false;
		$button_title =  isset( $instance['button_title'] ) ? esc_attr($instance['button_title']) : esc_html__('Submit', 'simontaxi');
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', 'simontaxi'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'name_field' ); ?>"><?php esc_html_e( 'Display Name?', 'simontaxi' ); ?></label>
			<input class="checkbox" type="checkbox"<?php checked( $name_field ); ?> id="<?php echo $this->get_field_id( 'name_field' ); ?>" name="<?php echo $this->get_field_name( 'name_field' ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'phone_field' ); ?>"><?php esc_html_e( 'Display Phone?', 'simontaxi' ); ?></label>
			<input class="checkbox" type="checkbox"<?php checked( $phone_field ); ?> id="<?php echo $this->get_field_id( 'phone_field' ); ?>" name="<?php echo $this->get_field_name( 'phone_field' ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'email_field' ); ?>"><?php esc_html_e( 'Display Email?', 'simontaxi' ); ?></label>
			<input class="checkbox" type="checkbox"<?php checked( $email_field ); ?> id="<?php echo $this->get_field_id( 'email_field' ); ?>" name="<?php echo $this->get_field_name( 'email_field' ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('button_title'); ?>"><?php esc_html_e('Button Title', 'simontaxi'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('button_title'); ?>" name="<?php echo $this->get_field_name('button_title'); ?>" value="<?php echo $button_title;?>"/>
		</p>

		<?php
	}

	/**
	 * Flushes the Simontaxi Request call widget cache.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @deprecated 4.4.0 Fragment caching was removed in favor of split queries.
	 */
	public function flush_widget_cache() {
		_deprecated_function( __METHOD__, '4.4.0' );
	}
}