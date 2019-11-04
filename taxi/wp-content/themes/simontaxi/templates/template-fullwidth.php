<?php
/**
 * Template Name: Booking Template (Full width)
 *
 * @package Simontaxi
 */

get_header(); ?>
<!-- Booking Form -->
<?php
while ( have_posts() ) : the_post();
	the_content();
endwhile;
?>
<!-- /Booking Form -->
<?php
get_footer();
