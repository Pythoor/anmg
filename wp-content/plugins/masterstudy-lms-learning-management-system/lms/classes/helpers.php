<?php

STM_LMS_Helpers::init();

class STM_LMS_Helpers
{

	public static function init()
	{
		add_action('wp_ajax_stm_lms_load_modal', 'STM_LMS_Helpers::load_modal');
		add_action('wp_ajax_nopriv_stm_lms_load_modal', 'STM_LMS_Helpers::load_modal');

		add_action('wp_ajax_stm_lms_load_content', 'STM_LMS_Helpers::load_content');
		add_action('wp_ajax_nopriv_stm_lms_load_content', 'STM_LMS_Helpers::load_content');

		add_action('wp_ajax_stm_lms_get_image_url', 'STM_LMS_Helpers::get_image_url');
	}

	public static function is_pro()
	{
		return defined('STM_LMS_PRO_PATH');
	}

	public static function load_modal()
	{
		if (empty($_GET['modal'])) die;
		$r = array();

		$modal = 'modals/' . sanitize_text_field($_GET['modal']);
		$params = (!empty($_GET['params'])) ? json_decode(stripslashes_deep($_GET['params']), true) : array();
		$r['modal'] = STM_LMS_Templates::load_lms_template($modal, $params);

		wp_send_json($r);

	}

	public static function sanitize_fields($value, $type = '')
	{
		switch ($type) {
			case 'email' :
				$r = (is_email($value)) ? sanitize_email($value) : false;
				break;
			default :
				$r = sanitize_text_field($value);
		}

		return $r;
	}

	public static function parse_meta_field($post_id)
	{

		$meta = get_post_meta($post_id);
		$meta_array = STM_LMS_Helpers::simplify_meta_array($meta);

		return $meta_array;
	}

	public static function simplify_meta_array($meta)
	{
		$meta_array = array();

		if (!empty($meta)) {
			foreach ($meta as $meta_name => $value) {
				if (!empty($value) and !empty($value[0])) {
					$meta_array[$meta_name] = is_serialized($value[0]) ? unserialize($value[0]) : $value[0];
				}
			}
		}

		return $meta_array;
	}

	public static function simplify_db_array($db_array)
	{
		$arr = array();
		if (empty($db_array)) return $arr;
		foreach ($db_array as $item) {
			if (!empty($item) and is_array($item)) {
				foreach ($item as $key => $value) {
					$arr[$key] = $value;
				}
			}
		}
		return $arr;
	}

	public static function in_array_r($needle, $haystack, $strict = false)
	{
		foreach ($haystack as $item) {
			if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && STM_LMS_Helpers::in_array_r($needle, $item, $strict))) {
				return true;
			}
		}

		return false;
	}

	public static function get_current_url()
	{
		return (is_ssl() ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	}

	public static function set_value_as_key($old_array, $key)
	{
		$new_array = array();

		if (empty($old_array)) return $new_array;

		foreach ($old_array as $old_key => $value) {
			$new_key = (!empty($value[$key])) ? $value[$key] : $key;
			$new_array[$new_key] = $value;
		}

		return $new_array;
	}

	public static function only_array_numbers($old_array)
	{
		$new_array = array();
		if (empty($old_array)) return $new_array;

		foreach ($old_array as $value) {
			if (is_numeric($value)) $new_array[] = $value;
		}

		return $new_array;
	}

	public static function display_price($price)
	{
		if (empty($price)) return '';
		$symbol = STM_LMS_Options::get_option('currency_symbol', '$');
		$position = STM_LMS_Options::get_option('currency_position', 'left');
		$currency_thousands = STM_LMS_Options::get_option('currency_thousands', ',');


		if(strpos($price, '.')) {
            $price = number_format($price, 2, '.', $currency_thousands);
        } else {
            $price = number_format($price, 0, '', $currency_thousands);
        }

		if ($position == 'left') {
			return $symbol . $price;
		} else {
			return $price . $symbol;
		}
	}

	public static function sort_query($sort)
	{
		switch ($sort) {
			case 'date_low' :
				$sorting = array(
					'orderby' => 'date',
					'order'   => 'ASC',
				);
				break;
			case 'free' :
				$sorting = array(
					'meta_query' => array(
						array(
							'relation' => 'AND',
							array(
								'key'     => 'price',
								'value'   => '',
								'compare' => '='
							),
							array(
								'key'     => 'sale_price',
								'value'   => '',
								'compare' => '='
							),
						),
					)
				);
				break;
			case 'rating' :
				$sorting = array(
					'meta_key' => 'course_mark_average',
					'orderby'  => 'meta_value_num',
					'order'    => 'DESC',
				);
				break;
			case 'popular' :
				$sorting = array(
					'meta_key' => 'views',
					'orderby'  => 'meta_value_num',
					'order'    => 'DESC',
				);
				break;
			default :
				$sorting = array();
		}

		return apply_filters('stm_lms_sorting_args', $sorting, $sort);
	}

	public static function load_content()
	{

		if (empty($_GET['template'])) die;
		$tpl = sanitize_text_field($_GET['template']);

		$args = (!empty($_GET['args'])) ? json_decode(stripslashes(sanitize_text_field($_GET['args'])), true) : array();

		$pp = (!empty($_GET['per_page'])) ? intval($_GET['per_page']) : get_option('posts_per_page');

		$args['posts_per_page'] = (!empty($args['posts_per_page'])) ? $args['posts_per_page'] : $pp;

		$args['offset'] = (!empty($_GET['offset'])) ? intval($_GET['offset']) : 0;

		$page = $args['offset'];

		$args['offset'] = $args['offset'] * $args['posts_per_page'];

		$args['isAjax'] = true;

		if (!empty($_GET['sort'])) {
			$args = array_merge($args, STM_LMS_Helpers::sort_query(sanitize_text_field($_GET['sort'])));
		}

		if (!empty($args['sort'])) {
			$args = array_merge($args, STM_LMS_Helpers::sort_query(sanitize_text_field($args['sort'])));
		}

		$link = STM_LMS_Course::courses_page_url();

		if (!empty($args['term'])) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'stm_lms_course_taxonomy',
					'field'    => 'term_id',
					'terms'    => intval($args['term']),
				),
			);

			$link = get_term_link(intval($args['term']), 'stm_lms_course_taxonomy');
		}

		$args['post_status'] = 'publish';

		$content = STM_LMS_Templates::load_lms_template($tpl, array('args' => $args));

		wp_send_json(array('content' => $content, 'page' => $page + 1, 'link' => $link));

	}

	public static function send_email($to, $subject, $message)
	{
		$to = (empty($to) or $to == 'admin') ? get_option('admin_email') : $to;

		add_filter('wp_mail_content_type', 'STM_LMS_Helpers::set_html_content_type');
		wp_mail($to, $subject, $message);
		remove_filter('wp_mail_content_type', 'STM_LMS_Helpers::set_html_content_type');
	}

	public static function g_recaptcha_enabled()
	{
		$recaptcha = STM_LMS_Helpers::g_recaptcha_keys();
		return (!empty($recaptcha['public']) and !empty($recaptcha['private']));
	}

	public static function g_recaptcha_keys()
	{
		$r = array();
		$r['public'] = STM_LMS_Options::get_option('recaptcha_site_key', '');
		$r['private'] = STM_LMS_Options::get_option('recaptcha_private_key', '');

		return $r;
	}

	public static function check_recaptcha($recaptcha_name = 'recaptcha')
	{

		$r = true;

		$recaptcha_enabled = STM_LMS_Helpers::g_recaptcha_enabled();

		if ($recaptcha_enabled) {

			$request_body = file_get_contents('php://input');
			$data = json_decode($request_body, true);
			$recaptcha = STM_LMS_Helpers::g_recaptcha_keys();

			$post_data = http_build_query(
				array(
					'secret'   => $recaptcha['private'],
					'response' => $data[$recaptcha_name],
					'remoteip' => $_SERVER['REMOTE_ADDR']
				)
			);
			$opts = array('http' =>
							  array(
								  'method'  => 'POST',
								  'header'  => 'Content-type: application/x-www-form-urlencoded',
								  'content' => $post_data
							  )
			);
			$context = stream_context_create($opts);
			$response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
			$result = json_decode($response);

			if (!$result->success) {
				$r = false;
			}
		}
		return $r;
	}

	public static function get_image_url()
	{
		if (empty($_GET['image_id'])) die;
		wp_send_json(wp_get_attachment_url(intval($_GET['image_id'])));
	}

	public static function get_client_ip()
	{
		$ip = getenv('HTTP_CLIENT_IP')?:
			getenv('HTTP_X_FORWARDED_FOR')?:
				getenv('HTTP_X_FORWARDED')?:
					getenv('HTTP_FORWARDED_FOR')?:
						getenv('HTTP_FORWARDED')?:
							getenv('REMOTE_ADDR');

		$ip = ($ip == '::1') ? '127.0.0.1' : $ip;

		return $ip;
	}

	public static function remove_non_numbers($string) {
		return preg_replace('/[^0-9]/', '', $string);
	}

	public static function current_screen() {
		$current_screen = get_queried_object();
		return (!empty($current_screen)) ? $current_screen->ID : '';
	}

}