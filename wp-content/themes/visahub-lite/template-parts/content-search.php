<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package visahub-lite
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'mb-5' ); ?>>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>

			<p class="meta mb-3">
				<?php
				visahub_lite_posted_on();
				visahub_lite_posted_by();
				?>
			</p>

		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php visahub_lite_post_thumbnail(); ?>

	<div class="entry-content mb-3">
		<?php
		the_excerpt();

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'visahub-lite' ),
			'after'  => '</div>',
		) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">

		<?php  // visahub_lite_entry_footer(); ?>

		<?php

			printf( '<div class="read-more"><a href="%1$s" class="btn btn-default btn-orange">%2$s</a></div>',

					// 1
					esc_url( get_the_permalink() ),

					// 2
					esc_html__( 'Read More', 'visahub-lite' )
			);

		?>

	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
