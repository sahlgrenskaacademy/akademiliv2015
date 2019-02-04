<?php

// //////////////////////////////
## HELPER FUNCTIONS ##
// //////////////////////////////

/// Return month as text ///
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


/// Get the translated equivalent of an array of ids ////////////////////////////////////////////
function psu_lang_object_ids($ids_array, $type) {
 if(function_exists('icl_object_id')) {
  $res = array();
  foreach ($ids_array as $id) {
   $xlat = icl_object_id($id,$type,false);
   if(!is_null($xlat)) { $res[] = $xlat; }
  }
  return $res;
 } else {
  return $ids_array;
 }
}


/// Get ids and translated ids from a list of categories /////////////////////////////
function psu_get_cat_ids( $cat_names ) {
  global $sitepress;
  // get current and default language to be able to switch between the two
  $current_language = $sitepress->get_current_language();
  $default_language = $sitepress->get_default_language();
  $sitepress->switch_lang($default_language);
  // get ids for categories
  $cat_ids = array();
  foreach ($cat_names as $value) {
    $cat_ids[] = get_cat_ID($value);
  }
  // switch back
  $sitepress->switch_lang($current_language);
  // append with the other language id
  $cat_ids = array_merge( $cat_ids, psu_lang_object_ids( $cat_ids, 'category' ) );
  return $cat_ids;
}


/// Returns true if is Akademiliv category template page ///////////////////////////////////////////////
function is_akademiliv_category_page($page = '') {
	global $wp_query;
	if ( $page == '' ) { // if page is not set, try every page template
  	if ( isset($wp_query) && is_page_template('page-calendar.php') || is_page_template('page-grants.php') || is_page_template('page-notices.php') ) {
  		return true;
  	}
	} else { // if $page is set try that page template
  	if ( isset($wp_query) && is_page_template('page-'.$page.'.php') ) {
  		return true;
  	}
	}
	return false;
}
function is_akademiliv_archive() {
	global $wp_query;
	if ( isset($wp_query) ) {
  	if ( is_page_template('page_blog.php') ) {  // page_blog.php defined in Genesis theme
			return true;
		}
		if ( is_archive() || is_search() ) { // auto pages for tags or categories
			return true;
		}
	}
	return false;
}
function is_akademiliv_default() {
	global $wp_query;
	if ( isset($wp_query) ) {
		if ( is_page() && !( is_akademiliv_category_page() || is_akademiliv_archive() ) ) {
			return true;
		}
	}
	return false;
}
function is_akademiliv_single_cat() { // exclude "notices" to present it more like news (comments, metadata)
  $cat_names = array(
    'calendar',
    'grants',
//    'notices'
  );
  $cat_ids = psu_get_cat_ids( $cat_names );
  foreach ($cat_ids as $value) {
    if ( in_category( $value ) ) {
      return true;
    }
  }
  return false;
}


/// Get and reformat date field value ///
function psu_get_date_field( $field ) {
	$date = genesis_get_custom_field( $field )/1000;
	$date += 2 * 60*60; // compensate for timezone since jQuery datepicker seems to be unaware of that
	return $date;
}

?>
