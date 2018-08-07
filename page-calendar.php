<?php

/* Template Name: Kalendarium */

/////////////////////////////////////////////////////////////////////////////////////////////
//* what category to show, also used in functions.php
$cat_id = ( ICL_LANGUAGE_CODE == 'en' )? 12 : 7;


/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields before content
add_action( 'genesis_entry_header', 'psu_calendar_custom_fields_header' );
function psu_calendar_custom_fields_header() {

	// get custom fields
	$cf_startdate = psu_get_date_field('startdate');
	$cf_type			= trim( genesis_get_custom_field('type') );

	// output date
	printf('<div class="entry-startdate date-color month%s" title="%s">', date('n', $cf_startdate), date('Y-m-d', $cf_startdate));
	printf('<span class="day">%s</span><br /><span class="month">%s</span>', date('j', $cf_startdate), psu_month_3l( date('n', $cf_startdate) ) );
	if ( date('Y') != date('Y', $cf_startdate) ) printf('<br /><span class="year">%s</span>', date('Y', $cf_startdate) );
	printf('</div>');

	// output type
	if ($cf_type != '') printf('<div class="kalendarium-typ">%s</div>', $cf_type );

}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields after content
add_action( 'genesis_entry_footer', 'psu_calendar_custom_fields_aftercontent' );
function psu_calendar_custom_fields_aftercontent() {

	// get custom fields
	// "praktisk information", minus "startdate" and "type" fetched in post header
	$cf_language 			= trim( genesis_get_custom_field('language') );
	$cf_tid 					= trim( genesis_get_custom_field('tid') );
	$cf_organizer 		= trim( genesis_get_custom_field('organizer') );
	$cf_webb 					= trim( genesis_get_custom_field('webb') );
	$cf_onlinemeeting = trim( genesis_get_custom_field('onlinemeeting') );

	// "lokal och adress"
	$cf_address_room 					= trim( genesis_get_custom_field('address_room') );
	$cf_address_building 			= trim( genesis_get_custom_field('address_building') );
	$cf_address_streetname 		= trim( genesis_get_custom_field('address_streetname') );
	$cf_address_streetnumber 	= trim( genesis_get_custom_field('address_streetnumber') );

	// parse cf_webb and cf_language
	if ( $cf_webb != '' ) {
		if ( strpos( $cf_webb, 'http' ) === false ) {
	    $cf_webb = 'http://'.$cf_webb;
	  }
	  $url = parse_url($cf_webb);
	  $domain = ltrim( $url['host'], 'www.' );
 	}
	$lang = ( $cf_language == 'sve' )? 'Swedish': 'English';

	// print the custom fields aka. meta data, after post
	printf('<div class="entry-details">');
		if ($cf_tid != '') 									printf('<div class="tid"><div class="label">%s</div>%s</div>', __('Time', 'magazine'), $cf_tid );
		if ($cf_address_room != '') 				printf('<div class="plats"><div class="label">%s</div>%s</div>', __('Venue', 'magazine'), $cf_address_room.', '.$cf_address_building );
		if ($cf_address_streetname != '') 	printf('<div class="adress"><div class="label">%s</div>%s</div>', __('Address', 'magazine'), $cf_address_streetname.' '.$cf_address_streetnumber );
		if ($cf_organizer != '') 						printf('<div class="arrangor"><div class="label">%s</div>%s</div>', __('Organizer', 'magazine'), $cf_organizer );
		if ($cf_language != '') 						printf('<div class="sprak"><div class="label">%s</div>%s</div>', __('Language', 'magazine'), __($lang, 'magazine') );
	printf('</div>');

	// "entry link" and "online" has it's own <div>
	printf ('<div class="entry-link">');
		if ($cf_onlinemeeting == 'yes')	printf('<div class="online">%s</div>', __('This meeting will be held online.', 'magazine') );
		if ($cf_webb != '') 						printf('<div class="more">%s <a href="%s">%s</a></div>', __('More information on', 'magazine'), $cf_webb, $domain );
	printf ('</div>');

}




/////////////////////////////////////////////////////////////////////////////////////////////
//* Replace the standard loop with our custom loop
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'psu_do_custom_loop' );
function psu_do_custom_loop() {

	global $paged; // current paginated page
	global $query_args; // grab the current wp_query() args
	global $cat_id;

	// http://codex.wordpress.org/Class_Reference/WP_Query#Custom_Field_Parameters
//	$now = new DateTime(current_time('mysql'));
	$args = array(
		'cat' => $cat_id, /* shows all posts and child posts from category id */
		'meta_query'				=> array( array(
			'key'							=> 'startdate',
			'value'						=> strtotime('yesterday 10pm')*1000,
			'compare'					=> '>=',
			'type'						=> 'NUMERIC',
		)),
    'orderby' 					=> 'meta_value_num',
		'order'         		=> 'ASC',
		'paged'         		=> $paged, // respect pagination
		'posts_per_page'		=> '12', // overrides posts per page in theme settings
	);

	genesis_custom_loop( wp_parse_args($query_args, $args) );

}


/////////////////////////////////////////////////////////////////////////////////////////////
//* Do the thing
genesis();
