<?php if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly ?>

<?php get_header();
    stm_lms_register_style('user');
    $tpl = (is_user_logged_in() ? 'account/private/parts/wishlist' : 'account/private/not_logged/wishlist');
    $current_user = STM_LMS_User::get_current_user('', false, true);
    ?>

	<div class="stm-lms-wrapper stm-lms-wrapper-wishlist">
		<div class="container">
            <?php STM_LMS_Templates::show_lms_template($tpl, array('current_user' => $current_user)); ?>
		</div>
	</div>

<?php get_footer(); ?>