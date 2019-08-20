<?php

define( 'VISAHUB_VER', wp_get_theme()->get( 'Version' ) );

define( 'VISAHUB_PATH', get_template_directory_uri() . '/inc/visahub-lite/' );

define( 'VISAHUB_LIB', VISAHUB_PATH . 'assets/' );

/**
 *  Theme Menu
 */
require get_template_directory() . '/inc/visahub-lite/nav-menu-walker.php';

require get_template_directory() . '/inc/visahub-lite/page-header-banner.php';

/**
 *  Bootstap Included
 */

if( ! function_exists( 'visahub_lite_bootstap' )  ){

	function visahub_lite_bootstap() {

        wp_enqueue_style( 'jquery-bootstrap', VISAHUB_LIB . 'bootstrap/bootstrap.css', array(), VISAHUB_VER, 'all' );

    	wp_enqueue_script( 'jquery-bootstrap', VISAHUB_LIB . 'bootstrap/bootstrap.js', array('jquery'), VISAHUB_VER, true );
	}

	add_action( 'wp_enqueue_scripts', 'visahub_lite_bootstap' );
}


/**
 *  VisaHub Style Included
 */

if( ! function_exists( 'visahub_lite_stylesheet' )  ){

	function visahub_lite_stylesheet() {

        wp_enqueue_style( 'visahub-lite', VISAHUB_LIB . 'style.css', array(), VISAHUB_VER, 'all' );

    	wp_enqueue_script( 'visahub-lite', VISAHUB_LIB . 'script.js', array('jquery'), VISAHUB_VER, true );
	}

	add_action( 'wp_enqueue_scripts', 'visahub_lite_stylesheet' );
}


/**
 *  Fontawesome
 */

if( ! function_exists( 'visahub_lite_font_awesome' )  ){

	function visahub_lite_font_awesome() {

        wp_enqueue_style( 'visahub-fontawesome', VISAHUB_LIB . 'font-awesome/css/font-awesome.min.css', array(), VISAHUB_VER, 'all' );
	}

	add_action( 'wp_enqueue_scripts', 'visahub_lite_font_awesome' );
}

/**
 *  VisaHub Body
 */
if( ! function_exists( 'visahub_lite_header' ) ){

	add_action( 'visahub_lite_header', 'visahub_lite_header_markup' );

	function visahub_lite_header_markup(){

		do_action( 'visahub_lite_page_banner' );

		?>
			<div class="container">
				<div class="row">


				<?php
				if( ! is_404() ){
				
					print '<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12 primary-section">';

				} else {  

					print '<div class="col-12 primary-section">';
				}
	}
}

if( ! function_exists( 'visahub_lite_footer' ) ){

	add_action( 'visahub_lite_footer', 'visahub_lite_footer_markup' );

	function visahub_lite_footer_markup(){

				if( ! is_404() ){

						print '</div>';

					print '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">';

						get_sidebar();

					print '</div>';

				} else {  

					print '</div>';
				}
		?>

		</div></div><?php
	}
}


/**
 *   Pagination.
 */
if(!function_exists( 'visahub_lite_pagination' ) ){

	function visahub_lite_pagination( $numpages = '', $pagerange = '', $paged='' ){

	  	global $paged, $wp_query;

	  	if( $numpages >= absint( '2' ) ){

			  if ( empty($pagerange) ){
			  	   $pagerange = absint('2');
			  }

			  if ( empty($paged) ){
			       $paged = absint('1');
			  }

			  if ($numpages == ''){  
			  	  $numpages = $wp_query->max_num_pages;
			      if(!$numpages) $numpages = absint('1');
			  }

			  $big = absint('999999999'); // need an unlikely integer

			  $pagination_args = array(
			    'base'            => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			    'format'          => '&paged=%#%',
			    'total'           => $numpages,
			    'current'         => $paged,
			    'show_all'        => false,
			    'end_size'        => 1,
			    'mid_size'        => $pagerange,
			    'prev_next'       => true,
			    'type'            => 'array',
			    'add_args'        => false,
			    'add_fragment'    => '',
			    'prev_text'       => esc_html__('&laquo; Previous','visahub-lite'),
				'next_text'       => esc_html__('Next &raquo;','visahub-lite'),
			  );

			  $pages = paginate_links($pagination_args);

			  if( is_array( $pages ) ) {  $paged = ( get_query_var('paged') == absint('0') ) ? absint('1') : get_query_var('paged');

		          ?><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center post-pagination"><ul class="pagination"><?php

		        	foreach ( $pages as $page ){

		        		printf( '<li class="page-item">%1$s</li>', $page );
		        	}

		          ?></ul></div></div><?php
		     }
		}

	}
}
