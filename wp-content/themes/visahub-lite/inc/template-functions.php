<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package visahub-lite
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function visahub_lite_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'visahub_lite_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function visahub_lite_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'visahub_lite_pingback_header' );


if( ! function_exists( 'visahub_lite_the_post_navigation' ) ){

	function visahub_lite_the_post_navigation(){

		global $post, $wp_query;

		$p = get_adjacent_post(false, '', true);  $n = get_adjacent_post(false, '', false);

		if( !empty($p) || !empty($n) ) {

			print '<div class="next-prev-post"><div class="row">';

	        if(!empty($p)){

				printf('<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
							<div class="prev-post blog-prev-next">
								<div class="prev-post-a mb-3">
									<a href="%1$s" class="btn-link" title="%4$s">%3$s</a>
								</div>
								<h3 class="prev-link-title"><a href="%1$s" class="heading-title">%2$s</a></h3>
							</div>
						</div>',

						// 1
						esc_url( get_permalink($p->ID) ),

						// 2
						esc_html( $p->post_title ),

						// 3
						esc_html__('Previous Post','visahub-lite'),

						//4
						the_title_attribute( 'echo=0' )
				);
	        }
			
			if(!empty($n)){

				printf('<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 "><div class="nav-next text-right">
							 <div class="next-post blog-prev-next">
								<div class="next-post-a mb-3">
								   <a href="%1$s" class="btn-link" title="%4$s">%3$s</a>
								</div>
								<h3 class="next-link-title"><a href="%1$s" class="heading-title">%2$s</a></h3>
							</div>
						</div>
					</div>',

					// 1
					esc_url( get_permalink($n->ID) ),

					// 2
					esc_html( $n->post_title ),

					// 3
					esc_html__('Next Post','visahub-lite'),

					//4
					the_title_attribute( 'echo=0' )
				); 
			}
			print '</div></div>';
		}
	}
}