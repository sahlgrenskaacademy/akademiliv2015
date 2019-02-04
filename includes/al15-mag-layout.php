<?php



/////////////////////////////////
//* LAYOUT CHANGES
/////////////////////////////////


/////////////////////////////////
//* Register widget areas

genesis_register_sidebar( array(
	'id'          => 'home-featured',
	'name'        => __( 'Home - Featured', 'magazine' ),
	'description' => __( 'The two top-right boxes, intended for featured news.', 'magazine' ),
	'before_title'=> '<h2 class="widget-title widgettitle">',
	'after_title' => "</h2>\n",
) );
genesis_register_sidebar( array(
	'id'          => 'home-notices',
	'name'        => __( 'Home - Notices', 'magazine' ),
	'description' => __( 'The top-right box, intended for a list of notices.', 'magazine' ),
	'before_title'=> '<h2 class="widget-title widgettitle">',
	'after_title' => "</h2>\n",
) );
genesis_register_sidebar( array(
	'id'          => 'home-second-row',
	'name'        => __( 'Home - Second Row', 'magazine' ),
	'description' => __( 'The two bottom boxes, intended for three of the latest news (auto).', 'magazine' ),
	'before_title'=> '<h2 class="widget-title widgettitle">',
	'after_title' => "</h2>\n",
) );
genesis_register_sidebar( array(
	'id'          => 'home-latest',
	'name'        => __( 'Home - Latest', 'magazine' ),
	'description' => __( 'The third box, intended for a list with the latest news.', 'magazine' ),
	'before_title'=> '<h2 class="widget-title widgettitle">',
	'after_title' => "</h2>\n",
) );
genesis_register_sidebar( array(
	'id'          => 'home-middle',
	'name'        => __( 'Home - Middle', 'magazine' ),
	'description' => __( 'The middle section of the homepage, intended for list with "Kalendarium", "Utbildning" och "Bidrag"', 'magazine' ),
	'before_title'=> '<h2 class="widget-title widgettitle">',
	'after_title' => "</h2>\n",
) );
genesis_register_sidebar( array(
	'id'          => 'home-news',
	'name'        => __( 'Home - News Area', 'magazine' ),
	'description' => __( 'The bottom section of the homepage, intended for a list with "More news"', 'magazine' ),
	'before_title'=> '<h2 class="widget-title widgettitle">',
	'after_title' => "</h2>\n",
) );
genesis_register_sidebar( array(
	'id'          => 'news-area',
	'name'        => __( 'News Area', 'magazine' ),
	'description' => __( 'Bottom section of category pages and post pages, intended for a list with "News"', 'magazine' ),
	'before_title'=> '<h2 class="widget-title widgettitle">',
	'after_title' => "</h2>\n",
) );
genesis_register_sidebar( array(
	'id'         => 'logo-area',
	'name'       => __( 'Logo Area', 'magazine' ),
	'description'=> __( 'Area for the University of Gothenburg logo', 'magazine' ),
) );
genesis_register_sidebar( array(
	'id'         => 'al-sidebar',
	'name'       => __( 'AL Sidebar', 'magazine' ),
	'description'=> __( 'Area displaying Sidebar content', 'magazine' ),
	'before_title'=> '<h2 class="widget-title widgettitle">',
	'after_title' => "</h2>\n",
) );

//* Register widget areas
//genesis_register_sidebar( array(
//	'id'          => 'home-top',
//	'name'        => __( 'Home - Top', 'magazine' ),
//	'description' => __( 'This is the top section of the homepage.', 'magazine' ),
//) );
//genesis_register_sidebar( array(
//	'id'          => 'home-middle',
//	'name'        => __( 'Home - Middle', 'magazine' ),
//	'description' => __( 'This is the middle section of the homepage.', 'magazine' ),
//) );
//genesis_register_sidebar( array(
//	'id'          => 'home-bottom',
//	'name'        => __( 'Home - Bottom', 'magazine' ),
//	'description' => __( 'This is the bottom section of the homepage.', 'magazine' ),
//) );


/// Remove Genesis Layout Settings ///
remove_theme_support( 'genesis-inpost-layouts' );

/// Unregister primary and secondary sidebar ///
unregister_sidebar( 'sidebar' );
unregister_sidebar( 'sidebar-alt' );

/// Remove default sidebar text ///
remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
add_action( 'genesis_sidebar', 'psu_do_default_sidebar' );
function psu_do_default_sidebar() {
	if ( ! dynamic_sidebar( 'primary-sidebar' ) ) {
		echo '';
	}
}


/////////////////////////////////
//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

/////////////////////////////////
//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

/////////////////////////////////
//* Relocate after entry widget
remove_action( 'genesis_after_entry', 'genesis_after_entry_widget_area' );
add_action( 'genesis_entry_footer', 'genesis_after_entry_widget_area' );


/////////////////////////////////
//* Reposition the primary navigation menu
remove_action( 'genesis_before_header', 'genesis_do_nav' );
add_action( 'genesis_after_header', 'genesis_do_nav' );
//remove_action( 'genesis_after_header', 'genesis_do_nav' );
//add_action( 'genesis_before_header', 'genesis_do_nav' );


/////////////////////////////////
//* Customize the entire footer
remove_action( 'genesis_footer', 'genesis_do_footer' );
/*
add_action( 'genesis_footer', 'sp_custom_footer' );
function sp_custom_footer() {
	?>
	<p>&copy; Copyright 2012 <a href="http://mydomain.com/">My Domain</a> &middot; All Rights Reserved &middot; Powered by <a href="http://wordpress.org/">WordPress</a> &middot; <a href="http://mydomain.com/wp-admin">Admin</a></p>
	<?php
}*/


/////////////////////////////////
//* Remove entry meta in entry footer in all listings except single
add_action( 'genesis_before_entry', 'magazine_remove_entry_meta' );
function magazine_remove_entry_meta() {

	//* Remove if not single post
	if ( !is_single() ) {
		remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
		remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
		remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
	}

}

/////////////////////////////////
//* Add primary-nav class if primary navigation is used
add_filter( 'body_class', 'backcountry_no_nav_class' );
function backcountry_no_nav_class( $classes ) {

	$menu_locations = get_theme_mod( 'nav_menu_locations' );

	if ( ! empty( $menu_locations['primary'] ) ) {
		$classes[] = 'primary-nav';
	}
	return $classes;
}

/////////////////////////////////
//* Customize search form input box text
add_filter( 'genesis_search_text', 'magazine_search_text' );
function magazine_search_text( $text ) {
	return esc_attr( __( 'Search akademiliv.se', 'magazine' ) );
}

/////////////////////////////////
//* Filter menu items, appending either a search form or today's date.
add_filter( 'wp_nav_menu_items', 'psu_menu_extras', 10, 2 );
function psu_menu_extras( $menu, $args ) {
	//* Change 'primary' to 'secondary' to add extras to the secondary navigation menu
	if ( 'primary' !== $args->theme_location )
		return $menu;
	//* Uncomment this block to add a search form to the navigation menu

	ob_start();
	get_search_form();
	$search = ob_get_clean();
	$menu  .= '<li class="right search">' . $search . '</li>';

	return $menu;
}



/////////////////////////////////
//* Hooks after-entry widget area to single posts, category pages and the news archive page
add_action( 'genesis_after_content', 'psu_news_area_logic'  );
function psu_news_area_logic() {
	if ( is_akademiliv_category_page() || is_single() || is_akademiliv_default() ) {
	  genesis_widget_area( 'news-area', array(
			'before' => '<div class="news-area widget-area">',
			'after'  => '</div>',
	  ) );
	}
}

/////////////////////////////////
//* Default Category Title
add_filter( 'genesis_term_meta_headline', 'psu_default_category_title', 10, 2 );
function psu_default_category_title( $headline, $term ) {
	if( ( is_category() || is_tag() || is_tax() ) && empty($headline) )
		$headline = $term->name;

	return $headline;
}


/////////////////////////////////
//* Prints a category's title and description
remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
add_action( 'genesis_before_content', 'psu_output_category_info' );
function psu_output_category_info() {
	if ( is_category() || is_tag() || is_tax() ) {
		printf( '<div class="archive-pre-title">%s</div><h1 class="archive-title">%s</h1>', __('All news with tag:', 'magazine'), ucfirst( single_term_title('', false) ) );
//		echo term_description();
	}
}

/////////////////////////////////
//* Add the logo area sidebar
add_action( 'genesis_header', 'psu_left_header_widget', 11 );
function psu_left_header_widget() {
	if (is_active_sidebar( 'logo-area' ) ) {
	 	genesis_widget_area( 'logo-area', array(
       'before' => '<div class="logo-area widget-area">',
       'after'	 => '</div>',
		) );
  }
}

//add_filter( 'genesis_search_form_label', 'sp_search_form_label' );
//function sp_search_form_label ( $text ) {
//	return esc_attr( '' );
//}

/////////////////////////////////
//* Add site description
//add_action( 'genesis_seo_description', 'psu_custom_seo_site_description' );
function psu_custom_seo_site_description($description) {
	return sprintf('<div id="site-description" class="description">%s</div>', get_bloginfo('description', 'display') );
}






 ?>
