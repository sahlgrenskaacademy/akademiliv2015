<?php


/////////////////////////////////////////////////////////////////////////////////////////////
//* THEME 
/////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////
//* Start the engine & Setup Theme
include_once( get_template_directory() . '/lib/init.php' );
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

/////////////////////////////////////////////////////////////////////////////////////////////
//* Set Localization (do not remove)
load_child_theme_textdomain( 'magazine', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'magazine' ) );

/////////////////////////////////////////////////////////////////////////////////////////////
//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'AL Mag 2015' );
define( 'CHILD_THEME_URL', 'http://www.akademiliv.se' );
define( 'CHILD_THEME_VERSION', '0.1' );

/////////////////////////////////////////////////////////////////////////////////////////////
//* Enqueue Google Fonts and JS script
add_action( 'wp_enqueue_scripts', 'magazine_enqueue_scripts' );
function magazine_enqueue_scripts() {
	wp_enqueue_script( 'magazine-entry-date', get_bloginfo( 'stylesheet_directory' ) . '/js/entry-date.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_script( 'magazine-responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_style( 'dashicons' );
//	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Roboto:300,400|Raleway:400,500,900', array(), CHILD_THEME_VERSION );
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for additional color styles
//add_theme_support( 'genesis-style-selector', array(
//	'magazine-pro-blue'   => __( 'Magazine Pro Blue', 'magazine' ),
//	'magazine-pro-green'  => __( 'Magazine Pro Green', 'magazine' ),
//	'magazine-pro-orange' => __( 'Magazine Pro Orange', 'magazine' ),
//) );

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add support for custom header
add_theme_support( 'custom-header', array(
	'default-text-color'     => '000000',
	'header-selector'        => '.site-title a',
	'header-text'            => false,
	'height'                 => 90,
	'width'                  => 380,
) );


/////////////////////////////////////////////////////////////////////////////////////////////
//* Remove Genesis Layout Settings
remove_theme_support( 'genesis-inpost-layouts' );

/////////////////////////////////////////////////////////////////////////////////////////////
//* Unregister primary and secondary sidebar
unregister_sidebar( 'sidebar' );
unregister_sidebar( 'sidebar-alt' );


/////////////////////////////////////////////////////////////////////////////////////////////
//* Set post type to "post" for search page and show 20 posts on each page
add_filter('pre_get_posts','psu_search_filter');
function psu_search_filter($query) {
	if ($query->is_search) {
		$query->set('post_type', 'post');
		$query->set('showposts', '20');
	}
	return $query;
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Redirect category arhcives to pages
add_action( 'template_redirect', 'psu_category_template_redirect' );
function psu_category_template_redirect( $url ) {
  if( is_category() ) {
  	$cat = get_category( get_query_var('cat') );
    wp_redirect( home_url( '/'.$cat->slug ) );
    exit;
  }
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Remove default sidebar text
remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
add_action( 'genesis_sidebar', 'psu_do_default_sidebar' );
function psu_do_default_sidebar() {
	if ( ! dynamic_sidebar( 'primary-sidebar' ) ) {
		echo '';
	}
}



/////////////////////////////////////////////////////////////////////////////////////////////
//* HELPER FUNCTIONS
/////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////
//* return month as text
function psu_month_3l($m_n) {
	$m_t = array(
		'jan',
		'feb',
		'mars',
		'april',
		'maj',
		'juni',
		'juli',
		'aug',
		'sep',
		'okt',
		'nov',
		'dec',
	);
	return $m_t[ $m_n-1 ];
}
function psu_month_string($m_n) {
	$m_t = array(
		'januari',
		'februari',
		'mars',
		'april',
		'maj',
		'juni',
		'juli',
		'augusti',
		'september',
		'oktober',
		'november',
		'december',
	);
	return $m_t[ $m_n-1 ];
}


/////////////////////////////////////////////////////////////////////////////////////////////
//* Returns true if is Akademiliv category template page
function is_akademiliv_category_page() {
	global $wp_query;
	if ( isset($wp_query) && is_page_template('page-seminars.php') || is_page_template('page-grants.php') || is_page_template('page-education.php') ) {
		return true;
	}
	return false;
}
function is_akademiliv_archive() {
	global $wp_query;
	if ( isset($wp_query) ) {
  	if ( is_page_template('page_blog.php') )  // page_blog.php defined in Genesis theme
			return true;
		if ( is_archive() || is_search() ) // auto pages for tags or categories
			return true;  
	}
	return false;
}
function is_akademiliv_default() {
	global $wp_query;
	if ( isset($wp_query) ) {
		if ( is_page() && !( is_akademiliv_category_page() || is_akademiliv_archive() ) )
			return true;
	}
	return false;
}


function psu_get_date_field( $field ) {
	$date = genesis_get_custom_field( $field )/1000;
	$date += 2 * 60*60; // compensate for timezone since jQuery datepicker seems to be aware of that
	return $date;
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* LAYOUT CHANGES
/////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////
//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'home-latest',
	'name'        => __( 'Home - Latest', 'magazine' ),
	'description' => __( 'The top-left box, intended for a list with the latest news.', 'magazine' ),
	'before_title'=> '<h2 class="widget-title widgettitle">',
	'after_title' => "</h2>\n",
) );
genesis_register_sidebar( array(
	'id'          => 'home-featured',
	'name'        => __( 'Home - Featured', 'magazine' ),
	'description' => __( 'The two top-right boxes, intended for featured news.', 'magazine' ),
	'before_title'=> '<h2 class="widget-title widgettitle">',
	'after_title' => "</h2>\n",
) );
genesis_register_sidebar( array(
	'id'          => 'home-second-row',
	'name'        => __( 'Home - Second Row', 'magazine' ),
	'description' => __( 'The three bottom boxes, intended for three of the latest news (auto).', 'magazine' ),
	'before_title'=> '<h2 class="widget-title widgettitle">',
	'after_title' => "</h2>\n",
) );
genesis_register_sidebar( array(
	'id'          => 'home-middle',
	'name'        => __( 'Home - Middle', 'magazine' ),
	'description' => __( 'The middle section of the homepage, intended for list with "Seminarier", "Utbildning" och "Bidrag"', 'magazine' ),
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



/////////////////////////////////////////////////////////////////////////////////////////////
//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

/////////////////////////////////////////////////////////////////////////////////////////////
//* Relocate after entry widget
remove_action( 'genesis_after_entry', 'genesis_after_entry_widget_area' );
add_action( 'genesis_entry_footer', 'genesis_after_entry_widget_area' );


/////////////////////////////////////////////////////////////////////////////////////////////
//* Reposition the primary navigation menu
remove_action( 'genesis_before_header', 'genesis_do_nav' );
add_action( 'genesis_after_header', 'genesis_do_nav' );
//remove_action( 'genesis_after_header', 'genesis_do_nav' );
//add_action( 'genesis_before_header', 'genesis_do_nav' );


/////////////////////////////////////////////////////////////////////////////////////////////
//* Customize the entire footer
remove_action( 'genesis_footer', 'genesis_do_footer' );
/*
add_action( 'genesis_footer', 'sp_custom_footer' );
function sp_custom_footer() {
	?>
	<p>&copy; Copyright 2012 <a href="http://mydomain.com/">My Domain</a> &middot; All Rights Reserved &middot; Powered by <a href="http://wordpress.org/">WordPress</a> &middot; <a href="http://mydomain.com/wp-admin">Admin</a></p>
	<?php
}*/


/////////////////////////////////////////////////////////////////////////////////////////////
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

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add primary-nav class if primary navigation is used
add_filter( 'body_class', 'backcountry_no_nav_class' );
function backcountry_no_nav_class( $classes ) {

	$menu_locations = get_theme_mod( 'nav_menu_locations' );

	if ( ! empty( $menu_locations['primary'] ) ) {
		$classes[] = 'primary-nav';
	}
	return $classes;
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Customize search form input box text
add_filter( 'genesis_search_text', 'magazine_search_text' );
function magazine_search_text( $text ) {
	return esc_attr( __( 'Search akademiliv.se', 'magazine' ) );
}

/////////////////////////////////////////////////////////////////////////////////////////////
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



/////////////////////////////////////////////////////////////////////////////////////////////
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

/////////////////////////////////////////////////////////////////////////////////////////////
//* Default Category Title
add_filter( 'genesis_term_meta_headline', 'psu_default_category_title', 10, 2 );
function psu_default_category_title( $headline, $term ) {
	if( ( is_category() || is_tag() || is_tax() ) && empty($headline) )
		$headline = $term->name;

	return $headline;
}


/////////////////////////////////////////////////////////////////////////////////////////////
//* Prints a category's title and description
remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
add_action( 'genesis_before_content', 'psu_output_category_info' );
function psu_output_category_info() {
	if ( is_category() || is_tag() || is_tax() ) {
		printf( '<div class="archive-pre-title">%s</div><h1 class="archive-title">%s</h1>', __('All news with tag:', 'magazine'), ucfirst( single_term_title('', false) ) );
//		echo term_description();
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////
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

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add site description
//add_action( 'genesis_seo_description', 'psu_custom_seo_site_description' );
function psu_custom_seo_site_description($description) {
	return sprintf('<div id="site-description" class="description">%s</div>', get_bloginfo('description', 'display') );
}







/////////////////////////////////////////////////////////////////////////////////////////////
//* SHORT CODES
/////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////
//* This will ensure that the text content of widgets is parsed for shortcodes and those shortcodes are ran.
add_filter('widget_text', 'do_shortcode');

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add shortcode for GU logo
add_shortcode('logotype-gu', 'psu_logo_shortcode');
function psu_logo_shortcode(){

	$l = ICL_LANGUAGE_CODE == 'en'? 'en': 'sv';
	
	$str['sv']['url'] = 		'http://gu.se';
	$str['sv']['title'] = 	'Göteborgs universitet';
	$str['sv']['img'] = 		'http://gu.se/digitalAssets/1498/1498146_ny_logo_sv_normal.png';
	$str['sv']['alt'] = 		'Göteborgs universitets logotyp';
	$str['en']['url'] = 		'http://gu.se/english';
	$str['en']['title'] = 	'University of Gothenburg';
	$str['en']['img'] = 		'http://gu.se/digitalAssets/1498/1498144_ny_logo_en_normal.png';
	$str['en']['alt'] = 		'University of Gothenburg Logotype';

	printf('<a href="%s" title="%s"><img src="%s" alt="%s"></a>', $str[$l]['url'], $str[$l]['title'], $str[$l]['img'], $str[$l]['alt'] );
}



/////////////////////////////////////////////////////////////////////////////////////////////
//* Add shortcode for other language(s)
add_shortcode('switch-language', 'psu_post_languages');
function psu_post_languages(){
	$separator = ' | ';
	$pre_lang = __('På ', 'magazine');
  $languages = icl_get_languages('skip_missing=1');

	if( count($languages) > 1 ){
//    echo __('This post is also available in: ', 'magazine');
    foreach($languages as $l){
      if( !$l['active'] ) 
      	$langs[] = sprintf('<a href="%s">%s%s</a>', $l['url'], $pre_lang, $l['native_name']); // or translated_name
//      	$langs[] = '<a href="'.$l['url'].'">'. $l['translated_name']. '</a>';
		}
    printf('<section id="switch-language" class="widget widget_text"><div class="widget-wrap">%s</div></section>', join($separator, $langs) );
  }

}




/////////////////////////////////////////////////////////////////////////////////////////////
//* CATEGORY PAGES LAYOUT
/////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////
//* All category stuff inits here 
add_action('genesis_before', 'psu_category_customization');
function psu_category_customization() {
		
	if ( is_akademiliv_category_page() ) { 
	
		//* Add genesis taxonomy title and description to category pages
		add_action( 'genesis_before_loop', 'psu_do_taxonomy_title_description', 15 );
	
		//* Extra wrapper around category page loop
		add_action( 'genesis_before_loop', 'psu_loop_wrapper_open'  ); 
		add_action( 'genesis_after_loop', 'psu_loop_wrapper_close'  ); 
	
		// * Remove Links from Post Titles and set heading to H2 in Genesis
		add_filter( 'genesis_post_title_output', 'psu_custom_post_title' );
	
		//* Filter/remove post meta info
		add_filter( 'genesis_post_info', 'psu_category_info_filter' );
	
		//* Remove featured image
		remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );

		//* Remove Read more link
		add_filter( 'get_the_content_more_link', 'psu_child_read_more_link' ); 

	}
	
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add genesis taxonomy title and description to category pages
function psu_do_taxonomy_title_description() {

	global $cat_id;

	$term = get_term( $cat_id, 'category' );
	if ( !$term || !isset( $term->meta ) )
		return;

	$headline = $intro_text = '';

	if ( $term->meta['headline'] )
		$headline = sprintf( '<h1 class="category-title">%s</h1>', strip_tags( $term->meta['headline'] ) );
	if ( $term->meta['intro_text'] )
		$intro_text = sprintf( '<div class="category-intro-text">%s</div>', apply_filters( 'genesis_term_intro_text_output', $term->meta['intro_text'] ) );

	if ( $headline || $intro_text )
		printf( $headline . $intro_text );

}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Extra wrapper around category page loop
function psu_loop_wrapper_open() {
	global $cat_id;
	$term = get_term( $cat_id, 'category' );
	printf('<div class="category-wrapper category-wrapper-%s  cat-%s">', $term->slug, $cat_id);
}
function psu_loop_wrapper_close() {
	printf('</div>');
}

/////////////////////////////////////////////////////////////////////////////////////////////
// * Remove Links from Post Titles and set heading to H2 in Genesis
function psu_custom_post_title( $title ) {
	if( get_post_type( get_the_ID() ) == 'post' ) {
		$post_title = get_the_title( get_the_ID() );
		$title = '<h2 class="entry-title" itemprop="headline">' . $post_title . '</h2>';
	}
	return $title;
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Filter/remove post meta info
function psu_category_info_filter($post_info) {
//	$post_info = '[post_date] by [post_author_posts_link] [post_comments] [post_edit]';
	$post_info = '';
	return $post_info;
}
 
/////////////////////////////////////////////////////////////////////////////////////////////
//* Remove Read more link
function psu_child_read_more_link() { 
	return ''; 
}





/////////////////////////////////////////////////////////////////////////////////////////////
//* ARCHIVE LAYOUT
/////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////
//* All category stuff inits here 
add_action('genesis_before', 'psu_archive_customization');
function psu_archive_customization() {
		
	if ( is_akademiliv_archive() ) { 
	
		//* Extra wrapper around category page loop
		add_action( 'genesis_before_loop', 'psu_loop_wrapper_archive_open'  ); 
		add_action( 'genesis_after_loop', 'psu_loop_wrapper_close'  ); 
	
		// * Remove Links from Post Titles and set heading to H2 in Genesis
		add_filter( 'genesis_post_title_output', 'psu_post_title_h2' );		
		
		//* Filter/remove post meta info
		add_filter( 'genesis_post_info', 'psu_post_info_filter' );		

		//* Change order of title and image in list
		remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
		add_action( 'genesis_entry_header', 'genesis_do_post_image', 8 );		
	
	}
	
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Extra wrapper around page loop
function psu_loop_wrapper_archive_open() {
	printf('<div class="archive-wrapper">');
}


/////////////////////////////////////////////////////////////////////////////////////////////
// * Set heading to H2 in Genesis
function psu_post_title_h2( $title ) {
	if( get_post_type( get_the_ID() ) == 'post'  && !is_single() ) {
		$post_title = get_the_title( get_the_ID() );
		$post_link = get_permalink( get_the_ID() );
		$title = '<h2 class="entry-title" itemprop="headline"><a href="' . $post_link . '" rel="bookmark">' . $post_title . '</a></h2>';
	}
	return $title;
}


/////////////////////////////////////////////////////////////////////////////////////////////
//* Filter/remove post meta info
function psu_post_info_filter($post_info) {
//	$post_info = '[post_date] by [post_author_posts_link] [post_comments] [post_edit]';
	$post_info = '[post_date] [post_edit]';
	return $post_info;
}





/////////////////////////////////////////////////////////////////////////////////////////////
//* SINGLE PAGE LAYOUT
/////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////
//* All category stuff inits here 
add_action('genesis_before', 'psu_singlepage_customization');
function psu_singlepage_customization() {
		
	if ( is_akademiliv_default() || is_single() ) { 

		//* Extra wrapper around category page loop
		add_action( 'genesis_before_loop', 'psu_loop_wrapper_single_open'  ); 
		add_action( 'genesis_after_loop', 'psu_loop_wrapper_close'  ); 
	
		//* Customize the entry meta in the entry footer
		add_filter( 'genesis_post_meta', 'psu_post_meta_filter' );	
		
		//* Remove the entry header markup and entry title
		remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
		remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );		
		remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
		remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
		//* Add the entry header markup and entry title togheter with featured image and post info
		add_action( 'genesis_entry_header', 'psu_single_add_entry_header' );
	}
	
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Extra wrapper around page loop
function psu_loop_wrapper_single_open() {
	printf('<div class="single-wrapper">');
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Customize the entry meta in the entry footer
function psu_post_meta_filter($post_meta) {
//	$post_meta = '[post_categories] [post_tags]';
	$post_meta = sprintf('<div class="author-wrapper">%s [post_author_posts_link]</div>[post_tags]', __('By:', 'magazine') );
	return $post_meta;
}


/////////////////////////////////////////////////////////////////////////////////////////////
//* Add the entry header markup and entry title togheter with featured image
function psu_single_add_entry_header() {

	genesis_entry_header_markup_open();

	$thumb = psu_get_thumbnail_max_size();		
	if ( $thumb['size'] == 'post-full' ) {

		psu_output_single_post_featured_image();
		genesis_do_post_title();
		psu_do_post_info();
		genesis_entry_header_markup_close();


	} else {
		genesis_do_post_title();
		psu_do_post_info();
		genesis_entry_header_markup_close();
		add_action( 'genesis_entry_content', 'psu_output_single_post_featured_image', 3);
	}

}


/////////////////////////////////////////////////////////////////////////////////////////////
//* Add features image on single post
//* http://designsbynickthegeek.com/tutorials/genesis-explained-image-functions
function psu_output_single_post_featured_image() {

	$thumb = psu_get_thumbnail_max_size();	
	if ( $thumb['size'] !== false && $thumb['size'] != 'post-medium' ) {

		$img_id = get_post_thumbnail_id();
		$img = genesis_get_image( array( 
			'format' => 'html', 
			'size' => $thumb['size'], 
			'attr' => array( 'class' => 'featured-image size-'. $thumb['size'] ) 
		));
		
		$caption = get_post($img_id)->post_excerpt;
		if ( $caption != '' ) {
			$figcaption = sprintf('<figcaption class="wp-caption-text">%s</figcaption>', $caption);
		} else {
			$figcaption = '';
		}
		
		$img_size = psu_get_defined_sizes( $thumb['size'] );
		printf( '<figure id="attachment_%s" style="width: %spx;" class="wp-caption featured-figure">%s%s</figure>', $img_id, $img_size['width'], $img, $figcaption );
	
	}
	
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Custom post info function (since it was removed and then manually outputed again
function psu_do_post_info() {
	if ( !is_page() ) {
		$post_info = '[post_date] [post_comments] [post_edit]';
		printf('<p class="entry-meta">%s</p>', do_shortcode($post_info) );
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Return array with size, width, height and crop for thumbnail
function psu_get_thumbnail_max_size() {
	$img_width 	  = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); // full = original
	$img_width		= $img_width[1];
	$full 				= psu_get_defined_sizes( 'post-full' );
	$center 			= psu_get_defined_sizes( 'post-center' );
	$medium 			= psu_get_defined_sizes( 'post-medium' );
	if ( $img_width >= $full['width']) {
		$r = $full;
		$r['size'] = 'post-full';
	} elseif ( $img_width >= $center['width']) {
		$r = $center;
		$r['size'] = 'post-center';	
	} elseif ( $img_width >= $medium['width']) {
		$r = $medium;
		$r['size'] = 'post-medium';	
	} else {
		$r = array('size' => false);
	}
	return $r;	
}


 

/////////////////////////////////////////////////////////////////////////////////////////////
//* IMAGE & VIDEO FUNCTIONS
/////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////
//* Add new image sizes: width, height, crop(x_crop,y_crop)  x_crop: ‘left’ ‘center’, ‘right’ -- y_crop: ‘top’, ‘center’, ‘bottom’.
add_image_size( 'home-box', 				300, 200, array('center','center') );
add_image_size( 'home-box-dubble', 	600, 200, array('center','center') );
add_image_size( 'news-listing', 		200, 133, array('center','center') );
add_image_size( 'post-full', 				920, 350, array('center','center') );
add_image_size( 'post-center', 	   	720, 405, array('center','center') );
add_image_size( 'post-medium',   		360, 360, false );
add_image_size( 'post-small',   		180, 180, false );
add_image_size( 'al-sidebar', 	   	286, 286, array('center','center') );
add_image_size( 'admin-col', 				100, 67, array('center','center') );

//add_image_size( 'sidebar-thumbnail', 100, 100, true );
//add_image_size( 'home-middle', 360, 200, true );
//add_image_size( 'home-top', 750, 420, true );
//add_image_size( 'sidebar-thumbnail', 100, 100, true );


/////////////////////////////////////////////////////////////////////////////////////////////
//* Default link images to 'none' (instead of 'file')
update_option('image_default_link_type','none');

/////////////////////////////////////////////////////////////////////////////////////////////
//* Register the three useful image sizes for use in Add Media modal
add_filter( 'image_size_names_choose', 'psu_custom_sizes_admin' );
function psu_custom_sizes_admin( $sizes ) {
	unset( $sizes['thumbnail'] );
	unset( $sizes['full'] );
	unset( $sizes['medium'] );
	unset( $sizes['large'] );
  return array_merge( $sizes, array(
    'post-center' => __( 'Entire Width', 'magazine' ),
    'post-medium' => __( 'Medium', 'magazine' ),
    'post-small' 	=> __( 'Small', 'magazine' ),
  ) );
}



/////////////////////////////////////////////////////////////////////////////////////////////
//* If no post thumb nail, get the old one from custom fields "thumbs"
add_filter ( 'genesis_pre_get_image', 'pontus_try_custom_thumb', 10, 3 );
function pontus_try_custom_thumb( $output, $args, $post ) {
	
	$post_thumb = get_post_custom_values( 'thumbs', $post->ID );
	if ( has_post_thumbnail( $post->ID ) || !is_array($post_thumb) ) {
		//* return false = countiue genesis_get_image as usual
		return false;
	}

	$url = $post_thumb[0];
	//* Source path, relative to the root
	$src = str_replace( home_url(), '', $url );
	
	//* Create the html output
	$args['attr']['class'] = $args['attr']['class'] . ' old-thumb';
	$attr = array_map( 'esc_attr', $args['attr'] );
	$html = '<img src="' .$url.'"';
	foreach ( $attr as $name => $value ) {
		$html .= " $name=" . '"' . $value . '"';
	}
	$sizes = psu_get_defined_sizes( $args['size'] );
	$html .= ' style="width: '. $sizes['width'] .'px; height: '. $sizes['height'] .'px;"';
	$html .= ' />';
	
	//* Determine output
	if ( 'html' === mb_strtolower( $args['format'] ) )
		$output = $html;
	elseif ( 'url' === mb_strtolower( $args['format'] ) )
		$output = $url;
	else
		$output = $src;

	return $output;
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Get sizes of custom image size definitions
function psu_get_defined_sizes( $_size ) {
	global $_wp_additional_image_sizes;
	$sizes = array();
	if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
	  $sizes['width'] = get_option( $_size . '_size_w' );
	  $sizes['height'] = get_option( $_size . '_size_h' );
	  $sizes['crop'] = (bool) get_option( $_size . '_crop' );
	} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
	  $sizes = array( 
		  'width' => $_wp_additional_image_sizes[ $_size ]['width'],
		  'height' => $_wp_additional_image_sizes[ $_size ]['height'],
		  'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
		);
	}	
	return $sizes;
}


/////////////////////////////////////////////////////////////////////////////////////////////
//* Changes the default embed sizes based on site layout
add_filter( 'embed_defaults', 'psu_embed_defaults' );
function psu_embed_defaults( $defaults ) {  

	if ( is_single() )
		return array( 'width'  => 720, 'height' => 405 );
	else
		return $defaults;

}







/////////////////////////////////////////////////////////////////////////////////////////////
//* COMMENTS
/////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////
//* Modify the size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'magazine_author_box_gravatar' );
function magazine_author_box_gravatar( $size ) {
	return 140;
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Modify the size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'magazine_comments_gravatar' );
function magazine_comments_gravatar( $args ) {
	$args['avatar_size'] = 100;
	return $args;
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Remove comment form allowed tags
add_filter( 'comment_form_defaults', 'magazine_remove_comment_form_allowed_tags' );
function magazine_remove_comment_form_allowed_tags( $defaults ) {
	$defaults['comment_notes_after'] = '';
	return $defaults;
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Modify comments title text in comments
add_filter( 'genesis_title_comments', 'psu_genesis_title_comments' );
function psu_genesis_title_comments() {
	$title = sprintf('<h2>%s</h2>', __('Comments', 'magazine') );
	return $title;
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Modify the speak your mind title in comments
add_filter( 'comment_form_defaults', 'psu_comment_form_defaults' );
function psu_comment_form_defaults( $defaults ) { 
	$defaults['title_reply'] = __( 'Leave a Comment', 'magazine' );
	return $defaults;
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Remove URL text from WordPress comment form
add_filter('comment_form_default_fields','psu_disable_comment_url');
function psu_disable_comment_url($fields) { 
	unset($fields['url']);
	return $fields;
}


/////////////////////////////////////////////////////////////////////////////////////////////
//* AL_SIDBAR CUSTOM POST TYPE
/////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////
//* Define Custom Post Type "al_sidebar_post"
add_action( 'init', 'psu_create_al_sidebar_post_type' );
function psu_create_al_sidebar_post_type() {
	$labels = array( 
	  'name' => __( 'Sidebars', 'magazine' ),
	  'singular_name' => __( 'Sidebar', 'magazine' ),
	  'add_new' => __( 'New Sidebar', 'magazine' ),
	  'add_new_item' => __( 'Add New Sidebar', 'magazine' ),
	  'edit_item' => __( 'Edit Sidebar', 'magazine' ),
	  'new_item' => __( 'New Sidebar', 'magazine' ),
	  'view_item' => __( 'View Sidebar', 'magazine' ),
	  'search_items' => __( 'Search Sidebars', 'magazine' ),
	  'not_found' =>  __( 'No Sidebars Found', 'magazine' ),
	  'not_found_in_trash' => __( 'No Sidebars found in Trash', 'magazine' ),
	);
	$args = array(
	  'labels' => $labels,
	  'has_archive' => false,
	  'public' => true,
	  'hierarchical' => false,
	  'supports' => array(
	    'title', 
	    'editor', 
//	    'excerpt', 
	    'custom-fields', 
	    'thumbnail',
	    'page-attributes'
	  ),
	);
	register_post_type( 'al_sidebar_post', $args );
} 

/////////////////////////////////////////////////////////////////////////////////////////////
//* Output sidebar posts loop
//* http://code.tutsplus.com/tutorials/use-a-custom-post-type-for-your-sidebar-content--cms-23830
function psu_al_sidebar() {
     
	$args = array( 
		'post_type' => 'al_sidebar_post',
		'orderby'		=> 'menu_order',
		'order'			=> 'ASC',
	);
	 
	$query = new WP_query ( $args );
	if ( $query->have_posts() ) {

		/* start the loop */
		while ( $query->have_posts() ) : $query->the_post(); 
		
			$cf_webb = trim( genesis_get_custom_field('webb')  );
			if ( $cf_webb != '' ) {
				if ( strpos( $cf_webb, 'http://' ) === false )
			    $cf_webb = 'http://'.$cf_webb;
			} else {
		  	$cf_webb = false;
		  }
	
			printf('<aside id="post-%s"', get_the_ID());
				post_class( 'al_sidebar_post' );
			printf('>');		

			if ($cf_webb !== false) {
				$link_start 	= sprintf('<a href="%s">', $cf_webb);
				$link_end 		= '</a>';
			} else {
				$link_start 	= '';
				$link_end 		= '';			
			}

			if ( has_post_thumbnail() ) {
			
				echo $link_start;
				the_post_thumbnail( 'al-sidebar', array(
          'class' => 'aligncenter',
          'alt'   => trim(strip_tags( $wp_postmeta->_wp_attachment_image_alt ))
	      ) );				
	      echo $link_end;

			} // end thumbnail
				
			printf('<div class="entry-wrapper"><h2 class="sidebar-title">');
			echo $link_start;
			the_title();
      echo $link_end;

			printf('</h2><section class="sidebar-content">');
			the_content();		    
			printf('</section></div></aside>');
	
		endwhile; /* end the loop*/
	  wp_reset_postdata();
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add the logo area sidebar
add_action( 'genesis_sidebar', 'psu_al_sidebar_logic' );
function psu_al_sidebar_logic() {
	genesis_structural_wrap( 'sidebar' );
	do_action( 'genesis_before_sidebar_widget_area' );
	if (is_active_sidebar( 'al-sidebar' ) )
	 	genesis_widget_area( 'al-sidebar' ); 
	psu_al_sidebar();
	do_action( 'genesis_after_sidebar_widget_area' );
	genesis_structural_wrap( 'sidebar', 'close' );
}







/////////////////////////////////////////////////////////////////////////////////////////////
//* ADMIN CHANGES
/////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////
//* Remove Genesis Page Templates
add_filter( 'theme_page_templates', 'psu_remove_genesis_page_templates' );
function psu_remove_genesis_page_templates( $page_templates ) {
//	unset( $page_templates['page_archive.php'] );
//	unset( $page_templates['page_blog.php'] );
	unset( $page_templates['page_landing.php'] );
	return $page_templates;
}


/////////////////////////////////////////////////////////////////////////////////////////////
//* Remove Genesis in-post SEO Settings
remove_action( 'admin_menu', 'genesis_add_inpost_seo_box' );

/////////////////////////////////////////////////////////////////////////////////////////////
//* Remove Genesis Layout Settings
remove_theme_support( 'genesis-inpost-layouts' );

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add admin css
add_action('admin_enqueue_scripts', 'pontus_admin_theme_style');
function pontus_admin_theme_style() {
  wp_enqueue_style('admin-customization', get_bloginfo( 'stylesheet_directory' )  . '/admin-customization.css');
}


/////////////////////////////////////////////////////////////////////////////////////////////
//* Modifying TinyMCE editor to remove unused items.
//* https://codex.wordpress.org/TinyMCE
add_filter( 'tiny_mce_before_init', 'psu_customize_tinymce' );
function psu_customize_tinymce( $in ) {
//	$in['remove_linebreaks'] = false;
//	$in['gecko_spellcheck'] = false;
//	$in['keep_styles'] = true;
//	$in['accessibility_focus'] = true;
//	$in['tabfocus_elements'] = 'major-publishing-actions';
//	$in['media_strict'] = false;
//	$in['paste_remove_styles'] = false;
//	$in['paste_remove_spans'] = false;
//	$in['paste_strip_class_attributes'] = 'none';
//	$in['paste_text_use_dialog'] = true;
//	$in['wpeditimage_disable_captions'] = true;
//	$in['plugins'] = 'tabfocus,paste,media,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,wpfullscreen';
//	$in['content_css'] = get_template_directory_uri() . "/editor-style.css";
//	$in['wpautop'] = true;
//	$in['apply_source_formatting'] = false;
  $in['block_formats'] = "Paragraph=p; Heading 2=h2; Heading 3=h3";
//	$in['toolbar1'] = 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,wp_fullscreen,wp_adv ';
	$in['toolbar1'] = 'bold,italic,strikethrough,bullist,numlist,blockquote,link,unlink,wp_fullscreen,wp_adv';
//	$in['toolbar2'] = 'formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help ';
	$in['toolbar2'] = 'formatselect,pastetext,removeformat,undo,redo';
//	$in['toolbar3'] = '';
//	$in['toolbar4'] = '';
	return $in;
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Remove meta boxes
add_action('admin_menu','remove_my_post_metaboxes');
function remove_my_post_metaboxes() {
//	remove_meta_box( 'authordiv','post','normal' ); // Author Metabox
	remove_meta_box( 'formatdiv','post','normal' ); // Formats
	remove_meta_box( 'postcustom','post','normal' ); // Custom Fields Metabox
	remove_meta_box( 'postexcerpt','post','normal' ); // Excerpt Metabox
	remove_meta_box( 'revisionsdiv','post','normal' ); // Revisions Metabox
	remove_meta_box( 'slugdiv','post','normal' ); // Slug Metabox
	remove_meta_box( 'trackbacksdiv','post','normal' ); // Trackback Metabox
}
