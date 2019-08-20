<?php

if (!defined('ABSPATH')) exit; //Exit if accessed directly


$routes = array(
	'lms-login'                   => 'stm_lms_login',
	'courses/{course}/{lesson}/'  => 'stm_lms_lesson',
	'lms-user/{user_id}/'         => 'stm_lms_user',
	'lms-user_profile/{user_id}/' => 'stm_lms_user_public',
	'lms-chats'                   => 'stm_lms_user_chats',
	'lms-wishlist'                => 'stm_lms_user_wishlist',
	'lms-checkout'                => 'stm_lms_cart',
	'lms-manage-course'           => 'stm_lms_manage_course',
);

if (is_admin()) $routes = array();

$uri = array_filter(explode('/', $_SERVER['REQUEST_URI']));
foreach ($routes as $route => $callable) {
	$route_parts = explode('/', $route);

	$has_intersects = array_intersect($route_parts, $uri);

	if ($has_intersects and (in_array($route, $uri) or strpos($route, '{') !== false)) {
		$route = site_url($route, 'relative');
		WP_Route::get($route, $callable);
	}

	echo ICL_LANGUAGE_CODE;

	die;
}

function stm_lms_login()
{
	require_once STM_LMS_PATH . '/stm-lms-templates/stm-lms-login.php';
	die;
}

function stm_lms_lesson($course, $lesson)
{
	add_action('wp_loaded', function () use ($course, $lesson) {
		if (class_exists('WPBMap')) {
			WPBMap::addAllMappedShortcodes();
		}
		require_once STM_LMS_PATH . '/stm-lms-templates/stm-lms-lesson.php';
		die;
	});
}

function stm_lms_user($user_id)
{
	add_action('wp_loaded', function () use ($user_id) {
		require_once STM_LMS_PATH . '/stm-lms-templates/stm-lms-user.php';
		die;
	});
}

function stm_lms_user_public($user_id)
{
	add_action('wp_loaded', function () use ($user_id) {
		require_once STM_LMS_PATH . '/stm-lms-templates/stm-lms-user-public.php';
		die;
	});
}

function stm_lms_user_chats()
{
	add_action('wp_loaded', function () {
		require_once STM_LMS_PATH . '/stm-lms-templates/stm-lms-user-chats.php';
		die;
	});
}

function stm_lms_user_wishlist()
{
	add_action('wp_loaded', function () {
		require_once STM_LMS_PATH . '/stm-lms-templates/stm-lms-wishlist.php';
		die;
	});
}

function stm_lms_cart()
{
	add_action('wp_loaded', function () {
		require_once STM_LMS_PATH . '/stm-lms-templates/stm-lms-checkout.php';
		die;
	});
}

//function stm_lms_manage_course()
//{
//	add_action('wp_loaded', function () {
//		require_once STM_LMS_PATH . '/stm-lms-templates/stm-lms-manage-course.php';
//		die;
//	});
//}