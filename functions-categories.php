<?php

/////////////////////////////////////////////////////////////////////////////////////////////
//* PAGE-CALENDAR
/////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields before content
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
		if ( strpos( $cf_webb, 'http' ) === false ) {
	    $cf_webb = 'http://'.$cf_webb;
	  }
	  $cf_url = parse_url($cf_webb);
	  $cf_domain = ltrim( $cf_url['host'], 'www.' );
 	}

	if ( $cf_webb != '')
		printf('<div class="entry-link"><a href="%s">%s %s</a></div>', $cf_webb, __('More information on', 'magazine'), $cf_domain );

}





/////////////////////////////////////////////////////////////////////////////////////////////
//* PAGE-NOTICES
/////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields before content
function psu_notices_custom_fields_header() {


}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields after content
function psu_notices_custom_fields_aftercontent() {

}



?>
