<?php

/* Template Name: Kalendarium */

/////////////////////////////////////////////////////////////////////////////////////////////
//* what category to show, also used in functions.php
$cat_id = ( ICL_LANGUAGE_CODE == 'en' )? 12 : 7;


/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields before and after content
add_action( 'genesis_entry_header', 'psu_calendar_custom_fields_header' );
add_action( 'genesis_entry_footer', 'psu_calendar_custom_fields_aftercontent' );


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
