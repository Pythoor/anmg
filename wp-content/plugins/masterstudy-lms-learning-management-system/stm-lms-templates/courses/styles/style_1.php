<?php
/**
 * @var $has_sale_price
 * @var $id
 * @var $price
 * @var $sale_price
 * @var $author_id
 * @var $style
 */

$classes = array($has_sale_price, $style);
?>


<div class="stm_lms_courses__single stm_lms_courses__single_animation <?php echo implode(' ', $classes); ?>">

	<div class="stm_lms_courses__single__inner">

		<?php STM_LMS_Templates::show_lms_template('courses/parts/image', array('id' => $id)); ?>

		<div class="stm_lms_courses__single--inner">

			<?php STM_LMS_Templates::show_lms_template('courses/parts/terms', array('id' => $id)); ?>

			<?php STM_LMS_Templates::show_lms_template('courses/parts/title'); ?>

			<div class="stm_lms_courses__single--meta">

				<?php STM_LMS_Templates::show_lms_template('courses/parts/rating', array('id' => $id)); ?>

				<?php STM_LMS_Templates::show_lms_template('global/price', compact('price', 'sale_price')); ?>

			</div>


		</div>

		<?php STM_LMS_Templates::show_lms_template('courses/parts/course_info',
			array_merge(array(
				'post_id' => $id,
			), compact('post_status', 'sale_price', 'price', 'author_id', 'id'))
		); ?>
	</div>

</div>