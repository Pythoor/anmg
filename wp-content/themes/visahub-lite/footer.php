<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package visahub-lite
 */

?>

<?php  do_action( 'visahub_lite_footer' );  ?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer tiny-footer-dark tiny1">

		<div class="container">

			<div class="row">
				<div class="col-12">

					<div class="site-info">
						<a href="<?php echo esc_url( 'https://wordpress.org/' ); ?>">
						<?php printf( esc_html__( 'Proudly powered by %s', 'visahub-lite' ), 'WordPress' ); ?>
						</a>
						<span class="sep"> | </span>
						<?php printf( esc_html__('Copyright &copy; %1$s VisaHub Lite. All Rights Reserved.', 'visahub-lite' ), date( 'Y' ) ); ?>
					</div>

				</div>
			</div>

		</div>

	</footer>
</div>

<?php wp_footer(); ?>

</body>
</html>
