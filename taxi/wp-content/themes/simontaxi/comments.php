<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Simontaxi
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
    return;
}
?>

<?php
// You can start editing here -- including this comment!
if ( have_comments() ) : ?>
    <h2 class="st-heading-xs">
        <?php
            printf( // WPCS: XSS OK.
                _nx( 'One Comment', 'Comments (%1$s)', get_comments_number(), 'comments title', 'simontaxi' ), number_format_i18n( get_comments_number() )
            );
        ?>
    </h2>

    <?php
    if ( ! comments_open() && '0' !== get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
    ?>
        <p class="no-comments"><?php echo esc_html__( 'Comments are closed.', 'simontaxi' ); ?></p>
    <?php endif; ?>

    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
    <nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
        <h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'simontaxi' ); ?></h2>
        <div class="nav-links">

            <div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'simontaxi' ) ); ?></div>
            <div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'simontaxi' ) ); ?></div>

        </div><!-- .nav-links -->
    </nav><!-- #comment-nav-above -->
    <?php endif; // Check for comment navigation. ?>

    <ul class="st-tree">
    <?php
    wp_list_comments( array(
            'style'      => 'ol',
            'short_ping' => true,
            'avatar_size' => 119,
            'callback'   => 'simontaxi_comments',
    ) );
        ?>
    </ul>

    <?php
    if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
    <nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
        <h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'simontaxi' ); ?></h2>
        <div class="nav-links">

            <div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'simontaxi' ) ); ?></div>
            <div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'simontaxi' ) ); ?></div>

        </div><!-- .nav-links -->
    </nav><!-- #comment-nav-above -->
    <?php endif; // Check for comment navigation.

endif; // Check for have_comments().
?>

<div class="row">
<div class="col-sm-12 ">
<?php
$commenter = wp_get_current_commenter();
$req = get_option( 'require_name_email' );
$aria_req = ( $req ? " aria-required='true'" : '' );

$fields = array(
    'author' => '<div class="st-half st-half-right">
                                <div class="input-group ">
                                    <input type="text" class="form-control" name="author" id="author" value="' . esc_attr( $commenter['comment_author'] ) . '" placeholder="' . esc_html__( 'Name', 'simontaxi' ) . '"' . $aria_req . '>
                                </div>
                            </div>',
    'email' => '<div class="st-half st-half-left ">
                                <div class="input-group ">
                                    <input type="email" class="form-control" name="email" id="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" placeholder="' . esc_html__( 'Email', 'simontaxi' ) . '"' . $aria_req . '>
                                </div>
                            </div>',
    'url' => ' <div class="input-group ">
                                    <input type="text" class="form-control" name="url" id="url" placeholder="' . esc_html__( 'Website Address', 'simontaxi' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) . '" required="">
                                </div> ',
);



$args = array(
    'id_form'           => 'commentform',
    'class_form'      => 'st-form-light',
    'id_submit'         => 'submit',
    'class_submit'      => 'btn btn-primary',
    'name_submit'       => 'submit',
    'title_reply_to'    => '<h2 class="st-heading-xs">' . esc_html__('Leave a Reply to %s', 'simontaxi') . '</h2>',
    'cancel_reply_link' => '<span class="st-comment-reply st-right">' . esc_html__('Cancel Reply', 'simontaxi') . '</span>',
    'label_submit'      => esc_html__( 'Submit Comment', 'simontaxi' ),
    'format'            => 'xhtml',
    'comment_field' => '<div class="input-group ">
                                    <textarea rows="5" id="comment" name="comment" placeholder="' . esc_html__( 'Your Comments', 'simontaxi' ) . '" aria-required="true"></textarea>
                                </div>
                            ',
    'must_log_in' => '<p class="must-log-in">' . sprintf( wp_kses( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'simontaxi' ), array('a' => array('href' => array())) ), wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) ) . '</p>',
    'comment_notes_before' => '<p class="comment-notes">' . esc_html__( 'Your email address will not be published.', 'simontaxi' ) . '</p>',
    'fields' => apply_filters( 'comment_form_default_fields', $fields ),
);
?>
<?php comment_form( $args ); ?>
</div>
</div>
