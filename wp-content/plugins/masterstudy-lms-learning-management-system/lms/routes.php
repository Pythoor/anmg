<?php

if (!defined('ABSPATH')) exit; //Exit if accessed directly

global $wp_router;

$wp_router = new WP_Router;

$wp_router->get( array(
	'as'   => 'simpleRoute',
	'uri'  => '/simple',
	'uses' => 'STM_LMS_WP_Router@my_function'
) );

class STM_LMS_WP_Router {

	public static function my_function() {
		return 'dedede';
	}
}