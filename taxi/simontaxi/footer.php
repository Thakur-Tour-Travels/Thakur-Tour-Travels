<?php
/**
 * The template for displaying the footer.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Simontaxi
 */
 
?>
<!-- FOOTER -->
<div class="st-footer">
	<div class="container">
		<div class="row">
		<?php if ( is_active_sidebar( 'simontaxifooter' ) ) : ?>
		<?php dynamic_sidebar( 'simontaxifooter' ); ?>
		<?php endif; ?>
		</div>
	</div>
</div>
<!-- /FOOTER -->
<?php wp_footer(); ?>

</body>
</html>


