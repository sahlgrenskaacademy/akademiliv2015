<?php

// ///////////////////////////////////////////////////////////////////////////////////////////
## GENESIS MODIFICATIONS ##
// ///////////////////////////////////////////////////////////////////////////////////////////

/// Enqueue Google Fonts and JS script ///////////////////////////////////////////////////////////////
add_action( 'wp_enqueue_scripts', 'magazine_enqueue_scripts' );
function magazine_enqueue_scripts() {
	wp_enqueue_script( 'magazine-entry-date', get_bloginfo( 'stylesheet_directory' ) . '/js/entry-date.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_script( 'magazine-responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_style( 'dashicons' );
//	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Roboto:300,400|Raleway:400,500,900', array(), CHILD_THEME_VERSION );
}

/// Add HTML5 markup structure ///////////////////////////////////////////////////////////////
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

/// Add viewport meta tag for mobile browsers ///////////////////////////////////////////////////////////////
add_theme_support( 'genesis-responsive-viewport' );

/// Add support for custom header ///////////////////////////////////////////////////////////////
add_theme_support( 'custom-header', array(
	'default-text-color'     => '000000',
	'header-selector'        => '.site-title a',
	'header-text'            => false,
	'height'                 => 180,
	'width'                  => 760,
) );


/// Set post type to "post" for search page and show 20 posts on each page ////////////////////////////////
add_filter('pre_get_posts','psu_search_filter');
function psu_search_filter($query) {
	if ($query->is_search) {
		$query->set('post_type', 'post');
		$query->set('showposts', '20');
	}
	return $query;
}

/// Redirect category archives to pages ///////////////////////////////////////////////////////////////
add_action( 'template_redirect', 'psu_category_template_redirect' );
function psu_category_template_redirect( $url ) {
  if( is_category() ) {
  	$cat = get_category( get_query_var('cat') );
    wp_redirect( home_url( '/'.$cat->slug ) );
    exit;
  }
}

/// Modify title tag ///////////////////////////////////////////////////////////////
remove_filter( 'wp_title', 'genesis_default_title', 10, 3 ); //Default title
add_filter( 'wp_title', 'psu_title_tag', 10, 3 );
function psu_title_tag($title) {
	$standard = get_bloginfo('name') .', '. __( "Sahlgrenska Academy's news site", 'magazine' );
	if ( is_front_page() ) {
		return  $standard;
	} else {
		return get_the_title() .' - '. $standard;
	}
}

/// Remove Genesis in-post SEO Settings /////////////////////////////////////////
remove_action( 'admin_menu', 'genesis_add_inpost_seo_box' );

/// Remove Genesis Layout Settings /////////////////////////////////////////
remove_theme_support( 'genesis-inpost-layouts' );

?>
