<?php

/* Template Name: Utbildning */
 
/////////////////////////////////////////////////////////////////////////////////////////////
//* What category to show, also used in functions.php
$cat_id = ( ICL_LANGUAGE_CODE == 'en' )? 10 : 9;

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields before content
add_action( 'genesis_entry_header', 'psu_custom_fields_header' );
function psu_custom_fields_header() {
	$cf_deadline = psu_get_date_field('deadline');

	printf('<div class="entry-deadline date-color month%s"><span class="label">Ansök senast:</span> <span class="day">%s</span> <span class="month">%s</span> <span class="year">%s</span></div>', date('n', $cf_deadline), date('j', $cf_deadline), psu_month_string( date('n', $cf_deadline) ), date('Y', $cf_deadline) );

}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields after content
add_action( 'genesis_entry_footer', 'psu_custom_fields_aftercontent' );
function psu_custom_fields_aftercontent() {
	$cf_tid = trim( genesis_get_custom_field('tid') );
	$cf_plats = trim( rtrim( genesis_get_custom_field('plats'), '.' ) );
	$cf_webb = trim( genesis_get_custom_field('webb')  );
	$cf_startdate = psu_get_date_field('startdate');
	
	if ( $cf_webb != '' ) {
		if ( strpos( $cf_webb, 'http://' ) === false ) {
	    $cf_webb = 'http://'.$cf_webb;
	  }	  
	$cf_url = parse_url($cf_webb);
	$cf_domain = ltrim( $cf_url['host'], 'www.' );
 	}
    
	printf('<div class="entry-details">');

		if ( $cf_tid != '')
			$time_str = sprintf('<span class="tid">%s %s</span>', __(', ', 'magazine'), $cf_tid );
		else
		  $time_str = '';

		printf('<div class="startdate"><div class="label">%s</div><span class="day">%s</span> <span class="month">%s</span> <span class="year">%s</span>%s</div>', __('Date & time', 'magazine'), date('j', $cf_startdate), psu_month_string( date('n', $cf_startdate) ), date('Y', $cf_startdate), $time_str);

		if ( $cf_plats != '')
			printf('<div class="plats"><div class="label">%s</div>%s</div>', __('Venue', 'magazine'), $cf_plats );
			
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
			'key'							=> 'deadline',
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

