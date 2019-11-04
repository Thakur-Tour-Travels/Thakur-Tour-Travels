<?php
/**
 * Simontaxi functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package simontaxi
 */

if ( ! function_exists( 'simontaxi_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function simontaxi_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on simontaxi, use a find and replace
		 * to change 'simontaxi' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'simontaxi', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for custom logo.
		 *
		 * @since Simontaxi 1.0
		 * @link https://make.wordpress.org/core/2016/03/10/custom-logo/
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 39,
			'width'       => 225,
			'flex-height' => true,
			'header-text' => array( 'site-title', 'site-description' ),
		) );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/**
		 * Add image sizes.
		 *
		 * @link https://developer.wordpress.org/reference/functions/add_image_size/
		 */
		add_image_size( 'simontaxi-featured', 748, 420, true );
		add_image_size( 'simontaxi-grid-image', 358, 240, true );

		/**
		 * This theme support 'vehicle' custome post type it require to display the image in 750x441 let us register it here!
		*/
		add_image_size( 'simontaxi-vehicle-image', 750, 441, true );
		add_image_size( 'simontaxi-vehicle-grid-image', 370, 280, true );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary Menu', 'simontaxi' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
		
		/*
		 * This theme styles the visual editor to resemble the theme style,
		 * specifically font, colors, icons, and column width.
		 */
		add_editor_style( array( 'css/editor-style.css', simontaxi_fonts_url() ) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'simontaxi_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );
	}
endif;
add_action( 'after_setup_theme', 'simontaxi_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function simontaxi_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'simontaxi_content_width', 640 );
}
add_action( 'after_setup_theme', 'simontaxi_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function simontaxi_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'simontaxi' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'simontaxi' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'footerSidebar', 'simontaxi' ),
		'id'            => 'simontaxifooter',
		'description'   => '',
		'before_widget' => '<div class="col-sm-4 col-xs-12 st-border-right">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="st-widget-heading">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'simontaxi_widgets_init' );

if ( ! function_exists( 'simontaxi_fonts_url' ) ) :
/**
 * Register Google fonts for Simontaxi.
 *
 * Create your own simontaxi_fonts_url() function to override in a child theme.
 *
 * @since Simontaxi 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function simontaxi_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Merriweather font: on or off', 'simontaxi' ) ) {
		$fonts[] = 'Merriweather:400,700,900,400italic,700italic,900italic';
	}

	/* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'simontaxi' ) ) {
		$fonts[] = 'Montserrat:400,700';
	}

	/* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'simontaxi' ) ) {
		$fonts[] = 'Inconsolata:400';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * Header Logo
 */
function simontaxi_get_header_logo() {
	if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
		the_custom_logo();
	} else { ?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand st-navbar-brand"><div class="site-title"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></div></a>
	<?php
	}
}

if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
	add_filter( 'get_custom_logo', 'simontaxi_custom_logo_output', 10 );

	/**
	 * Filters the custom logo output.
	 *
	 * @param string $html output logo.
	 * @return string
	 */
	function simontaxi_custom_logo_output( $html ) {
		$html = str_replace( 'custom-logo-link', 'navbar-brand st-navbar-brand', $html );
		$html = str_replace( 'custom-logo', 'img-responsive', $html );
		return $html;
	}
}
/**
 * Enqueue scripts and styles.
 */
function simontaxi_scripts() {
	// Add Bootstrap default CSS.
	wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/css/bootstrap.min.css' );
	
	// Google Fonts
	wp_enqueue_style( 'wpb-google-fonts', 'https://fonts.googleapis.com/css?family=Montserrat:400,500|Raleway:400,500', false );

	// Add Bootstrap Offcanvas CSS.
	wp_enqueue_style( 'bootstrap-offcanvas', get_template_directory_uri() . '/css/bootstrap.offcanvas.css' );

	// Add Slick theme CSS.
	wp_enqueue_style( 'slick-theme', get_template_directory_uri() . '/js/slick-slider/slick-theme.css' );

	// Add Slick CSS.
	wp_enqueue_style( 'slick', get_template_directory_uri() . '/js/slick-slider/slick.css' );

	// Add Simontaxi CSS.
	wp_enqueue_style( 'simontaxi-other-styles', get_template_directory_uri() . '/css/other-style.css' );

	// Add Simontaxi Default CSS.
	wp_enqueue_style( 'simontaxi-style-main', get_stylesheet_uri() );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	// Add Font-awesome
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css' );

	// Scripts.
	wp_enqueue_script( 'bootstrap-offcanvas', get_template_directory_uri() . '/js/bootstrap.offcanvas.js', array( 'jquery' ) );
	wp_enqueue_script( 'slick-min', get_template_directory_uri() . '/js/slick-slider/slick.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'flex-grid', get_template_directory_uri() . '/js/flexgrid.js', array( 'jquery' ) );
	wp_enqueue_script( 'simontaxi-main', get_template_directory_uri() . '/js/main.js', array( 'jquery' ) );
	
	if( basename( get_page_template() ) == 'template-grid.php' ) 
	{
		/* Flex-grid Boxes -Masonry JavaScript grid layout library */
		wp_add_inline_script( 'flex-grid', 'jQuery(document).ready(function( $ ){ $(".grid").masonry({
				itemSelector: ".grid-item"
			}); });' );
	}
}
add_action( 'wp_enqueue_scripts', 'simontaxi_scripts', 2 );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

include get_template_directory() . '/inc/top-editor.php'; // Customizer.
require get_template_directory() . '/inc/simon-widgets.php';

/**
 * Register theme widgets.
 */
function simontaxi_register_widgets() {
	register_widget( 'Simon_Widget_Social_Links' );
	register_widget( 'Simon_Widget_Recent_Posts' );
	register_widget( 'Simon_Widget_Logo' );
}
add_action( 'widgets_init', 'simontaxi_register_widgets' );

/**
 * Simontaxi comments display.
 *
 * @param Object  $comment Comment to display.
 * @param array   $args output logo.
 * @param integer $depth dept of the comment.
 */
function simontaxi_comments( $comment, $args, $depth ) {
	if ( 'div' === $args['style'] ) {
		$tag       = 'div';
		$add_below = 'comment';
	} else {
		$tag       = 'li';
		$add_below = 'div-comment';
	}
	?>
		<li class="st-child">
			<!-- Single comment -->
			<div class="media st-comment">
				<?php if ( 0 !== $args['avatar_size'] ) : ?>
				<div class="media-left">				
					<a href="<?php echo esc_url( comment_author_url() ); ?>"><?php echo get_avatar( $comment, $args['avatar_size'], '',false,array( 'class' => 'st-comment-profile img-circle' ) );?></a>
				</div>
				<?php endif;?>    

				<div class="media-body">
					<h4 class="st-user"> <?php echo get_comment_author_link();?>
					
					<?php
					$myclass = 'st-comment-reply st-right';
					echo preg_replace( '/comment-reply-link/', 'comment-reply-link ' . $myclass,
						get_comment_reply_link(
							array_merge( $args, array(
								'add_below' => $add_below,
								'depth' => $depth,
								'max_depth' => $args['max_depth'],
								)
							)
						),
					1 );
					?>
					</h4>
					<p class="st-post-date"><?php echo get_comment_date();?></p>
					<p class="st-comment-text"><?php comment_text(); ?></p>
				</div>
			</div>
			<!-- Ends single comment -->
		</li>
	<?php
}

/**
 * Function to add 'Call Us' link to the primary menu.
 *
 * @param string $items output links.
 * @param array  $args array of arguments.
 * @return string
 */
function simontaxi_theme_menu( $items, $args ) {
	if ( 'primary' !== $args->theme_location ) {
		return $items;
	}

	$callus_phone = get_theme_mod( 'simontaxi_call_us' );
	$link = '';
	if ( false !== $callus_phone && '' !== $callus_phone ) {
		$link .= '<li class="hidden-xs st-nav-help"><a href="tel:' . $callus_phone . '"><i class="fa  fa-phone-square"></i><h4><span>' . esc_html__( 'Call Us', 'simontaxi' ) . '</span><br>' . $callus_phone . '</h4></a></li>';
	}
	return $items . $link;
}
add_filter( 'wp_nav_menu_items', 'simontaxi_theme_menu', 10, 2 );

/**
 * Function to add 'Read more' link to the escerpt.
 *
 * @param string $more excerpt.
 * @return string
 */
function simontaxi_excerpt_more( $more ) {
	return sprintf( ' <a class="st-blog-fullread" href="%s">' . esc_html__( 'Read more', 'simontaxi' ) . '</a>', get_permalink() );
}
add_filter( 'excerpt_more', 'simontaxi_excerpt_more' );

/**
 * Filter the excerpt length to 20 characters.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function simontaxi_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'simontaxi_excerpt_length');

require_once get_template_directory() . '/theme_plugin/plugin-activate-config.php';
require_once( get_template_directory() . '/admin/theme_filters.php' );
