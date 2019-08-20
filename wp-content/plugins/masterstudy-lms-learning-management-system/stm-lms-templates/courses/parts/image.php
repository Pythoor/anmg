<?php
/**
 * @var $id
 * @var $img_size
 */

$post_status = STM_LMS_Course::get_post_status($id);
$img_size = (!empty($img_size)) ? $img_size : '272x161';

?>

<div class="stm_lms_courses__single--image">

	<?php if (!empty($post_status)): ?>
		<div class="stm_lms_post_status heading_font <?php echo sanitize_text_field($post_status['status']); ?>">
			<?php echo sanitize_text_field($post_status['label']); ?>
		</div>
	<?php endif; ?>

	<a href="<?php the_permalink(); ?>">
		<div>
			<?php
			if (function_exists('stm_get_VC_img')) {
				echo stm_lms_lazyload_image(stm_get_VC_img(get_post_thumbnail_id(), $img_size));
			} else {
				the_post_thumbnail($img_size);
			}
			?>
		</div>
	</a>
</div>