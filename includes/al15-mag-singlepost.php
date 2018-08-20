<?php

// ///////////////////////////////////////////////////////////////////////////////////////////
## SINGLE POST LAYOUT ##
// ///////////////////////////////////////////////////////////////////////////////////////////


/// All category stuff inits here  ///////////////////////////////////////////////////////////////
add_action('genesis_before', 'psu_singlepost_customization');
function psu_singlepost_customization() {
	if ( is_akademiliv_default() || is_single() ) {
		add_action( 'genesis_before_loop', 'psu_loop_wrapper_single_open'  );		//* Extra wrapper around category page loop
		add_action( 'genesis_after_loop', 'psu_loop_wrapper_close'  );
		add_filter( 'genesis_post_meta', 'psu_post_meta_filter' );		//* Customize the entry meta in the entry footer
		remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );		//* Remove the entry header markup and entry title
		remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
		remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
		remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
		add_action( 'genesis_entry_header', 'psu_single_add_entry_header' );		//* Add the entry header markup and entry title togheter with featured image and post info
	}
}


/// Extra wrapper around page loop ///////////////////////////////////////////////////////////////
function psu_loop_wrapper_single_open() {
	printf('<div class="single-wrapper">');
}

/// Customize the entry meta in the entry footer ///////////////////////////////////////////////////////////////
function psu_post_meta_filter($post_meta) {
//	$post_meta = '[post_categories] [post_tags]';
	$post_meta = sprintf('<div class="author-wrapper">%s [post_author_posts_link]</div>[post_tags]', __('By:', 'magazine') );
	return $post_meta;
}


/// Add the entry header markup and entry title togheter with featured image /////////////////////////////////////
function psu_single_add_entry_header() {
	genesis_entry_header_markup_open();
	$thumb = psu_get_thumbnail_max_size();
	if ( $thumb['size'] == 'post-full' ) {
		psu_output_single_post_featured_image();
		genesis_do_post_title();
		if ( !is_akademiliv_single_cat() ) { psu_do_post_info(); }
		genesis_entry_header_markup_close();
	} else {
		genesis_do_post_title();
		if ( !is_akademiliv_single_cat() ) { psu_do_post_info(); }
		genesis_entry_header_markup_close();
		add_action( 'genesis_entry_content', 'psu_output_single_post_featured_image', 3);
	}
}


/// Add features image on single post ///////////////////////////////////////////////////////////////
// http://designsbynickthegeek.com/tutorials/genesis-explained-image-functions
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


/// Custom post info function (since it was removed and then manually outputed again //////////////////
function psu_do_post_info() {
	if ( !is_page() ) {
		$post_info = '[post_date] [post_comments] [post_edit]';
		printf('<p class="entry-meta">%s</p>', do_shortcode($post_info) );
	}
}


/// Return array with size, width, height and crop for thumbnail /////////////////////
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


// ///////////////////////////////////////////////////////////////////////////////////////////
/// SINGLE CATEGORY PAGE LAYOUT aka. "Kalendarium" eller "Bidrag"
// ///////////////////////////////////////////////////////////////////////////////////////////

/// All single page category stuff inits here  ///////////////////////////////////////////////////////////////
add_action('genesis_before', 'psu_single_cat_customization');
function psu_single_cat_customization() {
	if ( is_akademiliv_single_cat() ) {
		//* Add custom fields to the end of every post page.
		add_filter( 'the_content', 'psu_single_custom_fields', 20 );
		//* Customize the entry meta in the entry footer
		add_filter( 'genesis_post_meta', 'psu_cat_post_meta_filter' );
	}
}

/// Add custom fields to the end of every post page. //////////////////////////////////////
function psu_single_custom_fields( $content ) {
	echo '<header class="entry-header category-details-box"><div class="box-inner">';

  if ( in_category(12) || in_category(7) ){ // calendar: 12, 7
		psu_calendar_custom_fields_header();
		psu_calendar_custom_fields_aftercontent();
  } elseif ( in_category(14) || in_category(13) ) { // grants: 14, 13
		psu_grants_custom_fields_header();
		psu_grants_custom_fields_aftercontent();
  }

	echo '<div style="clear:both;"></div></div></header>';

  return $custom_content . $content;
}

/// Customize the entry meta in the entry footer ///////////////////////////////////////////////////////////////
function psu_cat_post_meta_filter($post_meta) {
	return '';
}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields before CALENDAR content
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
//* Add custom fields after CALENDAR content
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
//* Add custom fields before GRANTS content
add_action( 'genesis_entry_header', 'psu_grants_custom_fields_header' );
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
//* Add custom fields after GRANTS content
add_action( 'genesis_entry_footer', 'psu_grants_custom_fields_aftercontent' );
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

?>
