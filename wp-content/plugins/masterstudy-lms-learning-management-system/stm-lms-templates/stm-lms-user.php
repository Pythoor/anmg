<?php if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly ?>

<?php get_header();

$user = parse_url($user_id);
$user_id = $user['path'];
$current_user = STM_LMS_User::get_current_user('', false, true);
$tpl = '';

if(!empty($current_user) and $current_user['id'] == $user_id) {
    $tpl = 'account/private/main';
}

if(empty($tpl)) STM_LMS_User::js_redirect(STM_LMS_User::login_page_url());

stm_lms_register_style('user');

?>

	<div class="stm-lms-wrapper">
		<div class="container">
            <?php if(!empty($tpl)) STM_LMS_Templates::show_lms_template($tpl, compact('current_user')); ?>
		</div>
	</div>

<?php get_footer(); ?>