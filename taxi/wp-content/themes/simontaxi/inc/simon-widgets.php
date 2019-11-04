<?php
/**
 * Widget API: Simon_Widget_Social_Links class
 *
 * @package Simontaxi
 */

/**
 * Core class used to implement a Social Links widget.
 */
class Simon_Widget_Social_Links extends WP_Widget {

	/**
	 * Sets up a new Social Links widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'simontaxi_widget_social_links',
			'description' => esc_html__( 'Your site&#8217;s social links.', 'simontaxi' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'simontaxi-social-links', esc_html__( 'Simontaxi Social Links', 'simontaxi' ), $widget_ops );
		$this->alt_option_name = 'simontaxi_widget_social_links';
	}

	/**
	 * Outputs the content for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Posts widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}
		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Social Links', 'simontaxi' );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$facebook     = isset( $instance['facebook'] ) ? esc_url( $instance['facebook'] ) : '';
		$twitter    = isset( $instance['twitter'] ) ? esc_url( $instance['twitter'] ) : '';
		$linked_in = isset( $instance['linked_in'] ) ? esc_url( $instance['linked_in'] ) : '';
		$instagram = isset( $instance['instagram'] ) ? esc_url( $instance['instagram'] ) : '';
		?>
		<?php echo $args['before_widget'];
		echo $args['before_title'] . $title . $args['after_title'];
		?>
			<ul class="st-widget-socials">
				<?php if ( '' !== $facebook ) { ?>
				<li><a href="<?php echo esc_url( $facebook );?>" target="_blank" tabindex="0"><i class="fa fa-facebook"></i></a></li>
				<?php } ?>
				<?php if ( '' !== $twitter ) { ?>
				<li><a href="<?php echo esc_url( $twitter );?>" target="_blank" tabindex="0"><i class="fa fa-twitter"></i></a></li>
				<?php } ?>
				<?php if ( '' !== $linked_in ) { ?>
				<li><a href="<?php echo esc_url( $linked_in );?>" target="_blank" tabindex="0"><i class="fa fa-linkedin "></i></a></li>
				<?php } ?>
				<?php if ( '' !== $instagram ) { ?>
				<li><a href="<?php echo esc_url( $instagram );?>" target="_blank" tabindex="0"><i class="fa fa-instagram"></i></a></li>
				<?php } ?>
			</ul>	
<?php echo $args['after_widget']; ?>			
		<?php
	}

	/**
	 * Handles updating the settings for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['facebook'] = esc_url_raw( $new_instance['facebook'] );
		$instance['twitter'] = esc_url_raw( $new_instance['twitter'] );
		$instance['linked_in'] = esc_url_raw( $new_instance['linked_in'] );
		$instance['instagram'] = esc_url_raw( $new_instance['instagram'] );
		return $instance;
	}

	/**
	 * Outputs the settings form for the Recent Posts widget.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? $instance['title'] : '';
		$facebook     = isset( $instance['facebook'] ) ? $instance['facebook'] : '';
		$twitter    = isset( $instance['twitter'] ) ? $instance['twitter'] : '';
		$linked_in = isset( $instance['linked_in'] ) ? $instance['linked_in'] : '';
		$instagram = isset( $instance['instagram'] ) ? $instance['instagram'] : '';
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'simontaxi' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php esc_html_e( 'Facebook:', 'simontaxi' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" type="text" value="<?php echo esc_url( $facebook ); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php esc_html_e( 'Twitter:', 'simontaxi' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" type="text" value="<?php echo esc_url( $twitter ); ?>"/></p>

		<p><label for="<?php echo $this->get_field_id( 'linked_in' ); ?>"><?php esc_html_e( 'Linkedin', 'simontaxi' ); ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'linked_in' ); ?>" name="<?php echo $this->get_field_name( 'linked_in' ); ?>" value="<?php echo esc_url( $linked_in );?>"/>
		</p>
		
		<p><label for="<?php echo $this->get_field_id( 'instagram' ); ?>"><?php esc_html_e( 'Instagram', 'simontaxi' ); ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'instagram' ); ?>" name="<?php echo $this->get_field_name( 'instagram' ); ?>" value="<?php echo esc_url( $instagram );?>"/>
		</p>
<?php
	}
}


/**
 * Recent Posts Widget
 */
class Simon_Widget_Recent_Posts extends WP_Widget {

	/**
	 * Sets up a new Recent Posts widget instance.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'simontaxi_widget_recent_entries',
			'description' => esc_html__( 'Your site&#8217;s most recent Posts.', 'simontaxi' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'simontaxi-recent-posts', esc_html__( 'Simontaxi Fresh From Blog', 'simontaxi' ), $widget_ops );
		$this->alt_option_name = 'simontaxi_widget_recent_entries';
	}

	/**
	 * Outputs the content for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Posts widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Recent Posts', 'simontaxi' );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number ) {
			$number = 5;
		}

		$number = 3;
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
		) ) );

		if ( $r->have_posts() ) :
		?>
		<?php echo $args['before_widget']; ?>
		<?php echo $args['before_title'] . $title . $args['after_title']; ?>
		
		<?php while ( $r->have_posts() ) : $r->the_post(); ?>
		
		<?php
		$post_id = get_the_ID();
		?>
		<div class="st-widget-post">
			<?php if ( has_post_thumbnail( $post_id ) ) { ?>
			<div class="media-left">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
				<?php the_post_thumbnail( 'thumbnail' ); ?>
				</a>
			</div>
			<?php } ?>
			<div class="media-body">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><h4 class="st-title"><?php get_the_title() ? the_title() : the_ID(); ?></h4></a>
				<?php if ( $show_date ) { ?>
				<h4 class="st-date"><?php echo get_the_date();?></h4>
				<?php } ?>
			</div>
		</div>
		<?php endwhile;?>		
		<?php echo $args['after_widget']; ?>
		<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;
	}

	/**
	 * Handles updating the settings for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		return $instance;
	}

	/**
	 * Outputs the settings form for the Recent Posts widget.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? $instance['title'] : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'simontaxi' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php esc_html_e( 'Number of posts to show:', 'simontaxi' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php esc_html_e( 'Display post date?', 'simontaxi' ); ?></label></p>
<?php
	}
}

/**
 * Widget API: Simon_Widget_Logo class
 *
 * @package Simontaxi
 */
class Simon_Widget_Logo extends WP_Widget {

	/**
	 * Sets up a new Support widget instance.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'simontaxi_widget_logo',
			'description' => esc_html__( 'This widget will display Logo.', 'simontaxi' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'simontaxi-logo', esc_html__( 'Simontaxi Logo', 'simontaxi' ), $widget_ops );
		$this->alt_option_name = 'simontaxi_widget_logo';
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
		$image = $instance['image'];
		if( '' === $image ) {
			$image = get_template_directory_uri() . '/images/footer-logo.png';
		}
		echo $args['before_widget'];
		?>
		
			<div class="st-footer-logo">
				<?php if ( '' !== $image ) { ?>
				<img src="<?php echo esc_url( $image );?>" class="img-responsive" alt="">
				<?php } ?>
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
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'count' => 0, 'dropdown' => '' ) );
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
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
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'image' => '' ) );

		$title = isset( $instance['title'] ) ? sanitize_text_field( $instance['title'] ) : '';
		$image = isset($instance['image']) ? esc_url( $instance['image'] ) : '';
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_script( 'simontaxi-admin-js', get_template_directory_uri() . '/js/admin.js', array( 'jquery', 'thickbox', 'media-upload' ));
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'simontaxi' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php esc_html_e( 'Image', 'simontaxi' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'image' ); ?>" name="<?php echo $this->get_field_name( 'image' ); ?>" value="<?php echo esc_url( $image );?>" placeholder="<?php esc_html_e( '184px * 201px', 'simontaxi' );?>"/><input style="float: left;" type="button" class="button simontaxi_image_button" name="image_button" id="image_button" value="<?php esc_html_e( 'Browse', 'simontaxi' );?>" />
			<?php if ( '' !== $image ) {
				?>
				<img src="<?php echo esc_url( $image );?>" alt="<?php echo esc_attr( $title ); ?>" title="<?php echo esc_attr( $title ); ?>" width="50" height="50">
				<?php
			}?>
		</p>
		<?php
	}
}