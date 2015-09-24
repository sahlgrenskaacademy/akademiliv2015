<?php

/* Template Name: Bidrag */
 
/////////////////////////////////////////////////////////////////////////////////////////////
//* What category to show, also used in functions.php
$cat_id = ( ICL_LANGUAGE_CODE == 'en' )? 14 : 13;

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields before content
add_action( 'genesis_entry_header', 'psu_custom_fields_header' );
function psu_custom_fields_header() {
	$cf_startdate = psu_get_date_field('startdate');
	$cf_lopande 	= genesis_get_custom_field('lopande');
	
	if ( 'ja' != $cf_lopande ) {
	
		printf('<div class="entry-startdate date-color month%s" title="%s">', date('n', $cf_startdate), date('Y-m-d', $cf_startdate));
		printf('<span class="day">%s</span><br /><span class="month">%s</span>', date('j', $cf_startdate), psu_month_3l( date('n', $cf_startdate) ) );
	
		if ( date('Y') != date('Y', $cf_startdate) )
			printf('<br /><span class="year">%s</span>', date('Y', $cf_startdate) );
	
		printf('</div>');
		
	}
	
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields after content
add_action( 'genesis_entry_footer', 'psu_custom_fields_aftercontent' );
function psu_custom_fields_aftercontent() {
	$cf_webb = trim( genesis_get_custom_field('webb')  );
	
	if ( $cf_webb != '' ) {
		if ( strpos( $cf_webb, 'http://' ) === false ) {
	    $cf_webb = 'http://'.$cf_webb;
	  }	  
	  $cf_url = parse_url($cf_webb);
	  $cf_domain = ltrim( $cf_url['host'], 'www.' );
 	}

	if ( $cf_webb != '')
		printf('<div class="entry-link"><a href="%s">%s %s</a></div>', $cf_webb, __('More information on', 'magazine'), $cf_domain );

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
	$now = new DateTime(current_time('mysql'));
	$args = array(
		'cat' => $cat_id, /* shows all posts and child posts from category id */
		'meta_query'				=> array( 
			'relation'					=> 'AND',
			array(
				'key'							=> 'startdate',
				'value'						=> (int)$now->getTimestamp()*1000,
				'compare'					=> '>=',
				'type'						=> 'NUMERIC',
			),
			array(
				'key'							=> 'lopande',
				'value'						=> 'ja',
				'compare'					=> '!=',
				'type'						=> 'CHAR',
			),			
		),
    'orderby' 					=> 'meta_value_num',
		'order'         		=> 'ASC',
		'paged'         		=> $paged, // respect pagination
		'posts_per_page'		=> '12', // overrides posts per page in theme settings
	);
	
	genesis_custom_loop( wp_parse_args($query_args, $args) ); 

}



/////////////////////////////////////////////////////////////////////////////////////////////
//* Second loop with ongoing content
add_action( 'genesis_loop', 'psu_do_custom_loop_lopande' );
function psu_do_custom_loop_lopande() {
	
	global $query_args; // grab the current wp_query() args
	global $cat_id;
	
	// http://codex.wordpress.org/Class_Reference/WP_Query#Custom_Field_Parameters
	$args = array(
		'cat' => $cat_id, /* shows all posts and child posts from category id */
		'meta_query'				=> array( array(
			'key'							=> 'lopande',
			'value'						=> 'ja',
			'compare'					=> '=',
			'type'						=> 'CHAR',
		)),
    'orderby' 					=> 'menu_order',
		'order'         		=> 'ASC',
		'posts_per_page'		=> '999', // overrides posts per page in theme settings
	);

	printf('<h2 class="category-title category-subtitle">%s</h2>', __('Ongoing grants', 'magazine'));
	
	genesis_custom_loop( wp_parse_args($query_args, $args) ); 

}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Wrap loops
add_action( 'loop_start', 'psu_extra_loop_wrapper_open'  ); 
add_action( 'loop_end', 'psu_extra_loop_wrapper_close'  ); 
function psu_extra_loop_wrapper_open() {
	static $loop_count = 1;
	echo '<div class="extra-loop-wrapper loop-'.$loop_count.'">';
	$loop_count++;
}
function psu_extra_loop_wrapper_close() {
	echo '</div><!-- end extra-loop-wrapper -->';
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Do the thing     
genesis();

