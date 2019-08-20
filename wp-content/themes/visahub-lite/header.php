<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package visahub-lite
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php 

	if( function_exists( 'wp_body_open' ) ){

		 wp_body_open();
	} 
?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'visahub-lite' ); ?></a>


<?php /** @ref https://developer.wordpress.org/themes/functionality/custom-headers/#displaying-custom-header **/ ?>

<?php if ( get_header_image() ) : ?>
    <div id="site-header">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
            <img src="<?php esc_url( header_image() ); ?>" class="img-fluid" width="<?php echo absint( get_custom_header()->width ); ?>" height="<?php echo absint( get_custom_header()->height ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
        </a>
    </div>
<?php endif; ?>

<header id="masthead" class="site-header">
	<nav class="navbar navbar-expand-md navbar-light bg-faded">
	   <?php visahub_lite_brand(); ?>
	   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bs4navbar" aria-controls="bs4navbar" aria-expanded="false" aria-label="Toggle navigation">
	     <span class="navbar-toggler-icon"></span>
	   </button>
	   <?php

	   	if( has_nav_menu( 'primary-menu' ) ){

			wp_nav_menu([
			 'menu'            => 'primary-menu',
			 'theme_location'  => 'primary-menu',
			 'container'       => 'div',
			 'container_id'    => 'bs4navbar',
			 'container_class' => 'collapse navbar-collapse',
			 'menu_id'         => false,
			 'menu_class'      => 'navbar-nav mr-auto',
			 'depth'           => 4,
			 'fallback_cb'     => 'VisaHub_Lite_Nav_Menu::fallback',
			 'walker'          => new VisaHub_Lite_Nav_Menu()
			]);
	   	}

	   ?>
	</nav>
</header>
<div id="content" class="site-content">

	<?php  do_action( 'visahub_lite_header' );  ?>