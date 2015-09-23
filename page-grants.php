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

	printf('<div class="entry-startdate date-color month%s" title="%s">', date('n', $cf_startdate), date('Y-m-d', $cf_startdate));
	printf('<span class="day">%s</span><br /><span class="month">%s</span>', date('j', $cf_startdate), psu_month_3l( date('n', $cf_startdate) ) );

	if ( date('Y') != date('Y', $cf_startdate) )
		printf('<br /><span class="year">%s</span>', date('Y', $cf_startdate) );

	printf('</div>');
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields after content
add_action( 'genesis_entry_footer', 'psu_custom_fields_aftercontent' );
function psu_custom_fields_aftercontent() {
	$cf_tid = trim( genesis_get_custom_field('tid') );
	$cf_plats = trim( rtrim( genesis_get_custom_field('plats'), '.' ) );
	$cf_webb = trim( genesis_get_custom_field('webb')  );
	
	if ( $cf_webb != '' ) {
		if ( strpos( $cf_webb, 'http://' ) === false ) {
	    $cf_webb = 'http://'.$cf_webb;
	  }	  
	  $cf_url = parse_url($cf_webb);
	  $cf_domain = ltrim( $cf_url['host'], 'www.' );
 	}
    
	printf('<div class="entry-details">');
		if ( $cf_tid != '')
			printf('<span class="tid">%s </span>', $cf_tid );
		if ( $cf_plats != '')
			printf('<span class="plats">@ %s</span>', $cf_plats );
	printf('</div>');

	if ( $cf_webb != '')
		printf('<div class="entry-link"><a href="%s">Mer information på %s</a></div>', $cf_webb, $cf_domain );

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
		'meta_query'				=> array( array(
			'key'							=> 'startdate',
			'value'						=> (int)$now->getTimestamp()*1000,
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

