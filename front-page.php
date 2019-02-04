<?php
/**
 * This file adds the Home Page to the Magazine Pro Child Theme.
 *
 * @author StudioPress
 * @package Magazine Pro
 * @subpackage Customizations
 */

add_action( 'genesis_meta', 'magazine_home_genesis_meta' );
/**
 * Add widget support for homepage. If no widgets active, display the default loop.
 *
 */
function magazine_home_genesis_meta() {

	if ( is_active_sidebar( 'home-featured' ) || is_active_sidebar( 'home-latest' ) || is_active_sidebar( 'home-second-row' ) || is_active_sidebar( 'home-middle' ) || is_active_sidebar( 'home-news' )   ) {

		// Force content-sidebar layout setting
		add_filter( 'genesis_site_layout', '__genesis_return_content_sidebar' );

		// Add magazine-home body class
		add_filter( 'body_class', 'magazine_body_class' );

		// Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		// Add homepage widgets
		add_action( 'genesis_loop', 'magazine_homepage_widgets' );

	}
}

function magazine_body_class( $classes ) {

	$classes[] = 'magazine-home';
	return $classes;

}

function magazine_homepage_widgets() {

//	genesis_widget_area( 'home-top', array(
//		'before' => '<div class="home-top widget-area">',
//		'after'  => '</div>',
//	) );
//
//	genesis_widget_area( 'home-middle', array(
//		'before' => '<div class="home-middle widget-area">',
//		'after'  => '</div>',
//	) );
//
//	genesis_widget_area( 'home-bottom', array(
//		'before' => '<div class="home-bottom widget-area">',
//		'after'  => '</div>',
//	) );

	genesis_widget_area( 'home-featured', array(
		'before' => '<div class="home-featured widget-area">',
		'after'  => '</div>',
	) );
	genesis_widget_area( 'home-notices', array(
		'before' => '<div class="home-notices widget-area">',
		'after'  => '</div>',
	) );
	genesis_widget_area( 'home-second-row', array(
		'before' => '<div class="home-second-row widget-area">',
		'after'  => '</div>',
	) );
	genesis_widget_area( 'home-latest', array(
		'before' => '<div class="home-latest widget-area">',
		'after'  => '</div>',
	) );
	genesis_widget_area( 'home-middle', array(
		'before' => '<div class="home-middle widget-area">',
		'after'  => '</div>',
	) );
	genesis_widget_area( 'home-news', array(
		'before' => '<div class="home-news widget-area">',
		'after'  => '</div>',
	) );

}

genesis();
