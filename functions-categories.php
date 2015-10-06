<?php

/////////////////////////////////////////////////////////////////////////////////////////////
//* PAGE-SEMINARS
/////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields before content
function psu_seminars_custom_fields_header() {
	$cf_startdate = psu_get_date_field('startdate');

	printf('<div class="entry-startdate date-color month%s" title="%s">', date('n', $cf_startdate), date('Y-m-d', $cf_startdate));
	printf('<span class="day">%s</span><br /><span class="month">%s</span>', date('j', $cf_startdate), psu_month_3l( date('n', $cf_startdate) ) );

	if ( date('Y') != date('Y', $cf_startdate) )
		printf('<br /><span class="year">%s</span>', date('Y', $cf_startdate) );

	printf('</div>');
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields after content
function psu_seminars_custom_fields_aftercontent() {
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
			printf('<div class="label">%s</div><div class="tid">%s</div>', __('Time', 'magazine'), $cf_tid );
		if ( $cf_plats != '')
			printf('<div class="plats"><div class="label">%s</div>%s</div>', __('Venue', 'magazine'), $cf_plats );
	printf('</div>');

	if ( $cf_webb != '')
		printf('<div class="entry-link"><a href="%s">%s %s</a></div>', $cf_webb, __('More information on', 'magazine'), $cf_domain );	

}





/////////////////////////////////////////////////////////////////////////////////////////////
//* PAGE-GRANTS
/////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields before content
function psu_grants_custom_fields_header() {
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
function psu_grants_custom_fields_aftercontent() {
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
//* PAGE-EDUCATION
/////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields before content
function psu_education_custom_fields_header() {
	$cf_deadline = psu_get_date_field('deadline');

	printf('<div class="entry-deadline date-color month%s"><span class="label">Ansök senast:</span> <span class="day">%s</span> <span class="month">%s</span> <span class="year">%s</span></div>', date('n', $cf_deadline), date('j', $cf_deadline), psu_month_string( date('n', $cf_deadline) ), date('Y', $cf_deadline) );

}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields after content
function psu_education_custom_fields_aftercontent() {
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
		printf('<div class="entry-link"><a href="%s">%s %s</a></div>', $cf_webb, __('More information on', 'magazine'), $cf_domain );

}



?>