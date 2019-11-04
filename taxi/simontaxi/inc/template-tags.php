<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Simontaxi
 */

if ( ! function_exists( 'simontaxi_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function simontaxi_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			esc_html_x( 'Posted on %s', 'post date', 'simontaxi' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		$byline = sprintf(
			esc_html_x( 'by %s', 'post author', 'simontaxi' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	}
endif;


/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function simontaxi_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'simontaxi_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'simontaxi_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so simontaxi_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so simontaxi_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in simontaxi_categorized_blog.
 */
function simontaxi_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'simontaxi_categories' );
}
add_action( 'edit_category', 'simontaxi_category_transient_flusher' );
add_action( 'save_post',     'simontaxi_category_transient_flusher' );

if ( ! function_exists( 'simontaxi_categories' ) ) :
/**
 * Prints HTML with category and tags for current post.
 *
 * Create your own simontaxi_categories() function to override in a child theme.
 *
 * @since Simontaxi 1.0
 */
function simontaxi_categories() {
	$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'simontaxi' ) );
	if ( $categories_list && simontaxi_categorized_blog() ) {
		printf( '<li><i class="fa fa-fw fa-folder"></i><span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span></li>',
			_x( 'Categories: ', 'Used before category names.', 'simontaxi' ),
			$categories_list
		);
	}
}
endif;

if ( ! function_exists( 'simontaxi_date' ) ) :
/**
 * Prints HTML with category and tags for current post.
 *
 * Create your own simontaxi_date() function to override in a child theme.
 *
 * @since Simontaxi 1.0
 */
function simontaxi_date() {
	if ( is_single() ) {
		printf( '<li><i class="fa fa-fw fa-clock-o"></i><span class="cat-links">%2$s</span></li>',
			_x( 'Date: ', 'Used before date display.', 'simontaxi' ),
			get_the_date()
		);
	} else {
	printf( '<li><i class="fa fa-fw fa-clock-o"></i><span class="cat-links"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span></li>',
			_x( 'Date: ', 'Used before date display.', 'simontaxi' ),
			esc_url( get_permalink() ),
			get_the_date()
		);
	}
}
endif;

if ( ! function_exists( 'simontaxi_tags' ) ) :
/**
 * Prints HTML with category and tags for current post.
 *
 * Create your own simontaxi_categories() function to override in a child theme.
 *
 * @since Simontaxi 1.0
 */
function simontaxi_tags() {
	$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'simontaxi' ) );
	if ( $tags_list ) {
		printf( '<div class="st-blog-tag-share"><i class="fa fa-fw fa-tags"></i> <span class="tags-links"><span class="screen-reader-text st-blog-tags">%1$s </span>%2$s</span></div>',
			_x( 'Tags: ', 'Used before tag names.', 'simontaxi' ),
			$tags_list
		);
	}
}
endif;

if ( ! function_exists( 'simontaxi_edit_link' ) ) :
/**
 * Returns an accessibility-friendly link to edit a post or page.
 *
 * This also gives us a little context about what exactly we're editing
 * (post or page?) so that users understand a bit more where they are in terms
 * of the template hierarchy and their content. Helpful when/if the single-page
 * layout with multiple posts/pages shown gets confusing.
 */
function simontaxi_edit_link() {

	$link = edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			wp_kses( __( '<li><i class="fa fa-fw fa-pencil-square-o"></i>Edit<span class="screen-reader-text"> "%s"</span></li>', 'simontaxi' ), array('li' => array(), 'i' => array('class' => 'fa fa-fw fa-pencil-square-o'), 'span' => array('class' => 'screen-reader-text'))),  get_the_title()
		),
		'<span class="edit-link">',
		'</span>'
	);

	return $link;
}
endif;
