<?php
/**
 * Widget API: Simon_Widget_Supportcontact class
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  widget
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class Simon_Widget_Supportcontact extends WP_Widget {

	/**
	 * Sets up a new Support widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'simon_widget_supportcontact',
			'description' => esc_html__( 'This widget will display support image and contact number.', 'simontaxi' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'simontaxi-support-contact', esc_html__( 'Simontaxi Support Contact', 'simontaxi' ), $widget_ops );
		$this->alt_option_name = 'simon_widget_supportcontact';
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
		$phone = $instance['phone'];
		$image = $instance['image'];
		if($image == '')
			$image = get_template_directory_uri() . '/images/help.png';
		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__( 'Do you have any questions', 'simontaxi' ) : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];
		?>
		<div class="st-widget-helpline text-center">
			<?php if($image != '') { ?>
			<img src="<?php echo $image;?>" alt="" class="img-responsive center-block" width="184px" height="201px">
			<?php } ?>
			<h4><?php echo $title;?></h4>
			<h2><i class="icon-call-out"></i> <?php echo $phone;?></h2>
		</div>
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
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'count' => 0, 'dropdown' => '') );
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['phone'] = sanitize_text_field( $new_instance['phone'] );
		$instance['image'] = sanitize_text_field( $new_instance['image'] );
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
		$phone = isset($instance['phone']) ? sanitize_text_field( $instance['phone'] ) : '';
		$image = isset($instance['image']) ? esc_url( $instance['image'] ) : '';
		wp_enqueue_script( 'simontaxi-admin-js', SIMONTAXI_PLUGIN_URL . '/js/admin.js', array( 'jquery', 'thickbox', 'media-upload' ));
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', 'simontaxi'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('phone'); ?>"><?php esc_html_e('Phone', 'simontaxi'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('phone'); ?>" name="<?php echo $this->get_field_name('phone'); ?>" value="<?php echo $phone;?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('image'); ?>"><?php esc_html_e('Image', 'simontaxi'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" value="<?php echo $image;?>" placeholder="184px * 201px"/><input style="float: left;" type="button" class="button simontaxi_image_button" name="image_button" id="image_button" value="Browse" />
			<?php if($image != '') {
				?>
				<img src="<?php echo $image;?>" alt="<?php echo esc_attr($title); ?>" title="<?php echo esc_attr($title); ?>" width="50" height="50">
				<?php
			}?>
		</p>
		<?php
	}

	/**
	 * Flushes the Simontaxi Support widget cache.
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