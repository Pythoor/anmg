<?php
if (!empty($_GET['fill_content'])) {

	add_action('init', 'stm_lms_create_courses_content');

	function stm_lms_create_courses_content()
	{
		$original_post = $_GET['fill_content'];
		$original_post_content = get_post($original_post);

		$titles = ['Math', 'Bio', 'Course', 'Creating', 'With', 'Start', 'Generate', 'Lesson', 'Premium', 'And', 'Spark', 'Instructor', 'Enterprise'];
		$random_keys = array_rand($titles, 6);

		$title = '';
		foreach ($random_keys as $key) {
			$title .= $titles[$key] . ' ';
		}

		$content = $original_post_content->post_content;
		$excerpt = $original_post_content->post_excerpt;
		$image = get_post_thumbnail_id($original_post);
		$post_meta = get_post_meta($original_post);


		$post = array(
			'post_type'    => 'stm-courses',
			'post_title'   => $title,
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_excerpt' => $excerpt,
		);

		$post = wp_insert_post($post);

		/*Thumbnail*/
		set_post_thumbnail($post, $image);

		/*META*/
		$serialized = array('_vc_post_settings', 'course_marks');

//		$args = array(
//			'post_type' => 'stm-courses',
//			'post__not_in' => array($original_post),
//			'posts_per_page' => -1
//		);
//
//		$images = array(938, 933, 930, 927, 921, 919, 911, 909, 905, 900, 896, 895, 862, 858, 272, 271);
//
//		$q = new WP_Query($args);
//		if($q->have_posts()) {
//			while($q->have_posts()) {
//				$q->the_post();
//				$post = get_the_ID();
//
//				$image_id = $images[array_rand($images)];
//
//				$url = wp_get_attachment_image_url($image_id);
//
//				echo "<img src='{$url}' />";
//				set_post_thumbnail($post, $image_id);
//				update_post_meta( $post, '_thumbnail_id', $image_id );
//
//				echo $images[array_rand($images)] . ' - ' . $post . ' ' . get_the_title() . '<br/>';
//
//
//				foreach ($post_meta as $meta_key => $meta_value) {
//					if($meta_key === '_thumbnail_id') continue;
//					if (!empty($meta_value[0])) {
//						$value = (in_array($meta_key, $serialized)) ? unserialize($meta_value[0]) : $meta_value[0];
//
//						if($meta_key === 'price') {
//							if(rand(0, 1)) {
//								update_post_meta($post, $meta_key, rand(60, 80));
//							}
//						} elseif($meta_key === 'sale_price') {
//							if(rand(0, 1)) {
//								update_post_meta($post, $meta_key, rand(20, 50));
//							}
//						} else {
//							update_post_meta($post, $meta_key, $value);
//						}
//					}
//				}
//			}
//		}

		foreach ($post_meta as $meta_key => $meta_value) {
			if (!empty($meta_value[0])) {
				$value = (in_array($meta_key, $serialized)) ? unserialize($meta_value[0]) : $meta_value[0];

				if ($meta_key === 'price') {
					if (rand(0, 1)) {
						update_post_meta($post, $meta_key, rand(60, 80));
					}
				} elseif ($meta_key === 'sale_price') {
					if (rand(0, 1)) {
						update_post_meta($post, $meta_key, rand(20, 50));
					}
				} else {
					update_post_meta($post, $meta_key, $value);
				}
			}
		}

		/*TERMS*/
		$terms = wp_list_pluck(get_terms('stm_lms_course_taxonomy', array(
			'hide_empty' => false,
		)), 'term_id');

		if (!empty($terms)) {

			$random_keys = array_rand($terms, 2);
			$rand_terms = array($terms[$random_keys[0]], $terms[$random_keys[1]]);
			wp_set_object_terms($post, $rand_terms, 'stm_lms_course_taxonomy');
		}


		die;
	}

	function stm_lms_create_courses_lesson()
	{
		$lesson_id = 19;

		//stm_pa(get_post_meta($lesson_id));
	}
}

if (!empty($_GET['fill_instructors'])) {

	add_action('init', function () {

		$bio = "John studied Software Development at UC Berkeley and has more than 15 years of experience in software quality assurance. He's been building software and tooling, managing software engineer team many years. When he's not reading about the latest trends in computing he spends his time with his wife, snowboarding, or running..";

		$users = array(
			array(
				'first_name' => 'George',
				'last_name'  => 'Clinton',
				'position'   => 'Photograher, Travel Bloger',
			),
			array(
				'first_name' => 'Robert',
				'last_name'  => 'Richards',
				'position'   => 'Professional skaters association',
			),
			array(
				'first_name' => 'Zuck',
				'last_name'  => 'Elmado',
				'position'   => 'Musical Producer from Chicago',
			),
			array(
				'first_name' => 'Jane',
				'last_name'  => 'Doe',
				'position'   => 'Personal Life Coach Fitness Trainer',
			),
			array(
				'first_name' => 'George',
				'last_name'  => 'Richards',
				'position'   => 'Professional skaters association',
			),
			array(
				'first_name' => 'Robert',
				'last_name'  => 'Doe',
				'position'   => 'Professor of Business Administration',
			),
			array(
				'first_name' => 'Namrata',
				'last_name'  => 'Parmar',
				'position'   => 'Marketing Consultants form India',
			),
		);

		foreach ($users as $user) {
			$name = sanitize_title($user['first_name'] . ' ' . $user['last_name']);
			$email = sanitize_title($user['first_name'] . ' ' . $user['last_name']) . '@gmail.com';
			$user_id = wp_create_user($name, 1, $email);
			if (is_wp_error($user_id)) {
				$user_by = get_user_by('email', $email);
				$user_id = $user_by->ID;
			}

			$sum_rating = rand(5,80);
			$total_reviews = intval($sum_rating / rand(2,4));
			$average = round($sum_rating / $total_reviews, 2);

			if($average > 5) $average = 5;

			$sum_rating_key = 'sum_rating';
			$total_reviews_key = 'total_reviews';
			$average_key = 'average_rating';

			update_user_meta($user_id, $sum_rating_key, $sum_rating);
			update_user_meta($user_id, $total_reviews_key, $total_reviews);
			update_user_meta($user_id, $average_key, $average);

			foreach ($user as $field_key => $field) {
				update_user_meta($user_id, $field_key, $field);
			}

			update_user_meta($user_id, 'description', $bio);

			$u = new WP_User($user_id);
			$u->remove_role('subscriber');
			$u->add_role('editor');
			$u->add_role(STM_LMS_Instructor::role());
		}

		die;
	});
}