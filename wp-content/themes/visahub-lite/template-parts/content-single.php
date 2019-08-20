<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package visahub-lite
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php

		if ( 'post' === get_post_type() ) :
			?>

			<p class="meta mb-3">
				<?php
				visahub_lite_posted_on();
				visahub_lite_posted_by();
				?>
			</p>

		<?php endif; ?>
	</header>

	<?php visahub_lite_post_thumbnail(); ?>

	<div class="entry-content mb-3">

		<?php the_content(); ?>

	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
