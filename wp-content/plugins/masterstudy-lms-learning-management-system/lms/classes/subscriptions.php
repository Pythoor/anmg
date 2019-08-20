<?php

STM_LMS_Subscriptions::init();

class STM_LMS_Subscriptions
{

	public static function init()
	{
		add_action('pmpro_membership_level_after_other_settings', 'STM_LMS_Subscriptions::stm_lms_pmpro_settings');
		add_action('pmpro_save_membership_level', 'STM_LMS_Subscriptions::stm_lms_pmpro_save_settings');

		add_action('wp_ajax_stm_lms_load_modal', 'STM_LMS_Helpers::load_modal');
		add_action('wp_ajax_nopriv_stm_lms_load_modal', 'STM_LMS_Helpers::load_modal');

		add_action('wp_ajax_stm_lms_use_membership', 'STM_LMS_Subscriptions::use_membership');
		add_action('wp_ajax_nopriv_stm_lms_use_membership', 'STM_LMS_Subscriptions::use_membership');
	}

	public static function use_membership() {

	    /*Check if has course id*/
	    if(empty($_GET['course_id'])) die;
	    $course_id = intval($_GET['course_id']);

	    /*Check if logged in*/
	    $current_user = STM_LMS_User::get_current_user();
	    if(empty($current_user['id'])) die;
	    $user_id = $current_user['id'];

	    /*Check if user already has course*/
		$courses = stm_lms_get_user_course($user_id, $course_id, array('user_course_id'));
		if(!empty($courses)) die;

	    $r = array();
	    $subs = STM_LMS_Subscriptions::user_subscriptions();
	    if(!empty($subs->quotas_left)) {
	        $progress_percent = 0;
	        $current_lesson_id = 0;
	        $status = 'enrolled';
	        $subscription_id = $subs->subscription_id;
			$user_course = compact('user_id', 'course_id', 'current_lesson_id', 'status', 'progress_percent', 'subscription_id');
			$user_course['start_time'] = time();
			stm_lms_add_user_course($user_course);
            STM_LMS_Course::add_student($course_id);
			$r['url'] = get_the_permalink($course_id);
		}

	    wp_send_json($r);

    }

	public static function subscription_enabled()
	{
		return (defined('PMPRO_VERSION'));
	}

	public static function level_url() {
		if(!STM_LMS_Subscriptions::subscription_enabled()) return false;

		$membership_levels = pmpro_getOption("levels_page_id");
		return (get_the_permalink($membership_levels));
	}

	public static function user_subscriptions() {
		if(!STM_LMS_Subscriptions::subscription_enabled()) return false;

		$subs = object;

		if(is_user_logged_in() && function_exists('pmpro_hasMembershipLevel') && pmpro_hasMembershipLevel())
		{
			$user = STM_LMS_User::get_current_user();
			if(empty($user['id'])) return $subs;
			$user_id = $user['id'];
			$subs = pmpro_getMembershipLevelForUser($user_id);
			$subscriptions = (!empty($subs->ID)) ? count(stm_lms_get_user_courses_by_subscription($user_id, $subs->subscription_id, array('user_course_id'), 0)) : 0;

			$subs->course_number = (!empty($subs->ID)) ? STM_LMS_Subscriptions::get_course_number($subs->ID) : 0;
			$subs->used_quotas = $subscriptions;
			$subs->quotas_left = $subs->course_number - $subs->used_quotas;
		}

		return $subs;
	}

	public static function save_course_number($level_id) {
		if(!empty($_REQUEST['stm_lms_course_number'])) {
			update_option('stm_lms_course_number_' . $level_id, intval($_REQUEST['stm_lms_course_number']));
		}
    }

    public static function get_course_number($level_id) {
	    return get_option('stm_lms_course_number_' . $level_id, 0);
    }

	public static function stm_lms_pmpro_settings() {
	    $level_id = (!empty($_GET['edit'])) ? intval($_GET['edit']) : 0;
	    $course_number = STM_LMS_Subscriptions::get_course_number($level_id);
	    ?>
		<h3 class="topborder"><?php esc_html_e('STM LMS Settings', 'masterstudy-lms-learning-management-system' );?></h3>
		<table class="form-table">
			<tbody>
			<tr class="membership_categories">
				<th scope="row" valign="top"><label><?php esc_html_e('Number of available courses in subscription', 'masterstudy-lms-learning-management-system' );?>:</label></th>
				<td>
					<input name="stm_lms_course_number" type="text" size="10" value="<?php echo esc_attr($course_number); ?>" />
					<small><?php esc_html_e('User can enroll several courses, after subscription', 'masterstudy-lms-learning-management-system'); ?></small>
				</td>
			</tr>
			</tbody>
		</table>
	<?php }

	public static function stm_lms_pmpro_save_settings($level_id) {
		STM_LMS_Subscriptions::save_course_number($level_id);
		return $level_id;
	}

}