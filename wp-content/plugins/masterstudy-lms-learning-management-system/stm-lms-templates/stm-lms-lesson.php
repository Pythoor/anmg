<?php if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly ?>

<?php
/**
 * @var $lesson
 * @var $course
 */

$lesson = parse_url($lesson);
$lesson = $lesson['path'];

$post_ids = explode('-', $lesson);
$post_id = $post_ids[0];
$item_id = $post_ids[1];

$is_previewed = (!empty($is_previewed)) ? $is_previewed : false;

$content_type = (get_post_type($item_id) == 'stm-lessons') ? 'lesson' : 'quiz';

$lesson_type = '';
if ($content_type === 'lesson') {
	$lesson_type = get_post_meta($item_id, 'type', true);
	stm_lms_register_style('lesson_' . $lesson_type);
}

STM_LMS_Templates::show_lms_template(
	'lesson/header',
	compact('post_id', 'item_id', 'is_previewed', 'content_type', 'lesson_type')
);

$custom_css = get_post_meta($item_id, '_wpb_shortcodes_custom_css', true);

stm_lms_register_style('lesson', array(), $custom_css);

$has_access = STM_LMS_User::has_course_access($post_id);
$has_preview = STM_LMS_Lesson::lesson_has_preview($item_id);
$is_previewed = STM_LMS_Lesson::is_previewed($post_id, $item_id);


if ($has_access or $has_preview):
	stm_lms_update_user_current_lesson($post_id, $item_id); ?>

    <div class="stm-lms-course__overlay"></div>

    <div class="stm-lms-wrapper <?php echo esc_attr(get_post_type($item_id)); ?>">

        <div class="stm-lms-course__curriculum">
			<?php STM_LMS_Templates::show_lms_template('lesson/curriculum', array('post_id' => $post_id, 'item_id' => $item_id)); ?>
        </div>

	    <?php if (!$is_previewed): ?>
            <div class="stm-lms-course__sidebar_toggle">
                <i class="fa fa-question"></i>
            </div>
        <?php endif; ?>

        <div class="stm-lms-course__sidebar">
            <div class="stm-lesson_sidebar__close">
                <i class="lnr lnr-cross"></i>
            </div>
			<?php if (!$is_previewed): ?>
				<?php STM_LMS_Templates::show_lms_template('lesson/sidebar', compact('post_id', 'item_id', 'is_previewed')); ?>
			<?php endif; ?>
        </div>

        <div id="stm-lms-lessons">
            <div class="stm-lms-course__content">

				<?php STM_LMS_Templates::show_lms_template('lesson/content_top_wrapper_start', compact('lesson_type')); ?>
				    <?php STM_LMS_Templates::show_lms_template('lesson/content_top', compact('post_id', 'item_id')); ?>
				<?php STM_LMS_Templates::show_lms_template('lesson/content_top_wrapper_end', compact('lesson_type')); ?>

                <div class="stm-lms-course__content_wrapper">
                    <?php STM_LMS_Templates::show_lms_template('lesson/content_wrapper_start', compact('lesson_type')); ?>

                        <?php STM_LMS_Templates::show_lms_template(
                            'course/parts/' . $content_type,
                            compact('post_id', 'item_id', 'is_previewed')
                        ); ?>

                    <?php STM_LMS_Templates::show_lms_template('lesson/content_wrapper_end', compact('lesson_type')); ?>
                </div>
            </div>
        </div>

    </div>
<?php else: ?>
	<?php STM_LMS_User::js_redirect(get_the_permalink($post_id)); ?>
<?php endif; ?>

<?php if (!$is_previewed) STM_LMS_Templates::show_lms_template('lesson/navigation', compact('post_id', 'item_id')); ?>

<?php
STM_LMS_Templates::show_lms_template(
	'lesson/footer',
	compact('post_id', 'item_id', 'is_previewed')
); ?>