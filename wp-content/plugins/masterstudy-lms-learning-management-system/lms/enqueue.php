<?php

if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly


function stm_lms_wp_head()
{
	?>
	<script type="text/javascript">
        var stm_lms_ajaxurl = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
	</script>
	<?php
}

add_action('wp_head', 'stm_lms_wp_head');
add_action('admin_head', 'stm_lms_wp_head');


function stm_lms_enqueue_ss() {
	$v = time();
	$assets = STM_LMS_URL . 'assets';
	$base = STM_LMS_URL . '/post_type/metaboxes/assets/';

	wp_enqueue_style('linear', $assets . '/linearicons/linear.css', array(), $v);
	wp_enqueue_style('linear-icons', $base . 'css/linear-icons.css', NULL, $v);
	wp_enqueue_style('stm_lms_icons', $assets . '/icons/style.css', NULL, $v);
	wp_enqueue_style('font-awesome-min', $assets . '/vendors/font-awesome.min.css', NULL, $v, 'all');
	wp_enqueue_style('font-icomoon', $assets . '/vendors/icomoon.fonts.css', NULL, $v, 'all');
	wp_enqueue_style( 'boostrap', $assets . '/vendors/bootstrap.min.css', NULL, $v, 'all' );

	wp_enqueue_script('jquery');

	wp_enqueue_script('stripe.js', 'https://js.stripe.com/v3/', array(), false, false);
	wp_register_script('vue.js', $base . 'js/vue.min.js', array('jquery'), $v);
	wp_register_script('vue-resource.js', $base . 'js/vue-resource.min.js', array('vue.js'), $v);
	wp_register_script('vue2-editor.js', $base . 'js/vue2-editor.min.js', array('vue.js'), $v);
	wp_register_script('stm_grecaptcha', 'https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit', array('jquery'), $v, true);
	wp_register_script('vue-recaptcha', STM_LMS_URL . '/assets/js/vue-recaptcha.js', array('stm_grecaptcha'), $v, true);
	wp_register_script( 'jquery.cookie',   $assets . '/vendors/jquery.cookie.js', array('jquery'), $v, TRUE );
	wp_register_script( 'sticky-sidebar',   $assets . '/vendors/sticky-sidebar.min.js', array('jquery'), $v, TRUE );
	wp_enqueue_script( 'bootstrap',   $assets . '/vendors/bootstrap.min.js', array('jquery'), $v, TRUE );
	wp_enqueue_script('vue2-datepicker', $base . 'js/vue2-datepicker.min.js', array('vue.js'), $v);

	if(stm_lms_has_custom_colors()) {
		wp_enqueue_style('masterstudy-lms-learning-management-system', stm_lms_custom_styles_url(true) . '/stm_lms.css', array(), stm_lms_custom_styles_v());
	} else {
		wp_enqueue_style('masterstudy-lms-learning-management-system', $assets . '/css/stm_lms.css');
	}

	if(current_user_can('edit_posts')) {
		wp_enqueue_style( 'stm_lms_logged_in', $assets . '/css/stm_lms_logged_in.css', NULL, $v, 'all' );
    }

	stm_lms_register_script('lms');

	if(STM_LMS_Subscriptions::subscription_enabled()) stm_lms_register_style('pmpro');
}

add_action( 'wp_enqueue_scripts', 'stm_lms_enqueue_ss' );