<?php

// ///////////////////////////////////////////////////////////////////////////////////////////
## CALENDAR AND GRANTS FUNCTIONS ##
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
	global $post;
	$custom_conent = '';
	echo '<header class="entry-header category-details-box"><div class="box-inner">';

  if ( in_category(12) || in_category(7) ){ // calendar: 12, 7
		psu_calendar_custom_fields_header();
		psu_calendar_custom_fields_aftercontent();
		if (has_excerpt()) $custom_content = sprintf( '<p class="calendar-excerpt">%s</p>', get_the_excerpt() );
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

// ///////////////////////////////////////////////////////////////////////////////////////////
/// CALENDAR
// ///////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields before CALENDAR content
function psu_calendar_custom_fields_header() {

	// get custom fields
	$cf_startdate = psu_get_date_field('startdate');

	// output date
	printf('<div class="entry-startdate date-color month%s" title="%s">', date('n', $cf_startdate), date('Y-m-d', $cf_startdate));
	printf('<span class="day">%s</span><br /><span class="month">%s</span>', date('j', $cf_startdate), psu_month_3l( date('n', $cf_startdate) ) );
	if ( date('Y') != date('Y', $cf_startdate) ) printf('<br /><span class="year">%s</span>', date('Y', $cf_startdate) );
	printf('</div>');

}

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields after CALENDAR content
function psu_calendar_custom_fields_aftercontent() {

	// get custom fields
	// "praktisk information", minus "startdate" and "type" fetched in post header
	$cf_language 			= trim( genesis_get_custom_field('language') );
	$cf_tid 					= trim( genesis_get_custom_field('tid') );
	$cf_organizer 		= trim( genesis_get_custom_field('organizer') );
	$cf_webb 					= trim( genesis_get_custom_field('webb') );
	$cf_email 				= trim( genesis_get_custom_field('email') );
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
	$cf_address_room = $cf_address_building!=''? $cf_address_room.',': $cf_address_room;
	$venue = $cf_address_room.' '.$cf_address_building.', '.$cf_address_streetname.' '.$cf_address_streetnumber;

	// output

	if ($cf_onlinemeeting == 'yes')	{
		printf('<div class="entry-online">%s</div>', __('This is an online meeting.', 'magazine') );
		$venue = __('Online', 'magazine');
	}

	printf('<div class="entry-contact">');
	if ($cf_organizer != '')	printf('<div class="arrangor"><div class="label">%s</div>%s</div>', __('Organizer', 'magazine'), $cf_organizer );
	if ($cf_email != '') 			printf('<div class="epost"><a href="mailto:%s">%s</a></div>', $cf_email, $cf_email );
	if ($cf_webb != '')				printf('<div class="webb"><a href="%s">%s %s</a></div>', $cf_webb, __('Visit', 'magazine'), $domain );
	printf ('</div>');

	printf('<div class="entry-details">');
	if ($cf_tid != '' || $venue != '')	printf('<div class="tid"><div class="label">%s</div>%s<br />%s</div>', __('When and where?', 'magazine'), $cf_tid, $venue );
	if ($cf_language != '')							printf('<div class="sprak"><div class="label">%s</div>%s</div>', __('Language', 'magazine'), __($lang, 'magazine') );
	printf('</div>');

}


/// Change date format to unix timestamp in gravity forms field "_2"=form_id, "_3"=field_id
//add_filter( 'gform_save_field_value_2_3', 'psu_save_field_value', 10, 4 );
function psu_save_field_value( $value, $entry, $field, $form ) {
  return strtotime( $value )*1000;
}

/// Add English fields in gravity forms to English translation of post. Requires WPML "_2"=form_id
// https://wpml.org/wpml-hook/wpml_admin_make_post_duplicates
// https://docs.gravityforms.com/gform_after_create_post

add_action( 'gform_after_create_post_2', 'psu_form_post_create' );
add_action( 'gform_after_create_post_3', 'psu_form_post_create' );
function psu_form_post_create( $post_id, $entry, $form ) {

	// fetch custom fields
	$cf_startdate 			= genesis_get_custom_field('startdate', $post_id);
	$cf_title_english 	= genesis_get_custom_field('title_english', $post_id);
	$cf_excerpt_english = genesis_get_custom_field('excerpt_english', $post_id);
	$cf_desc_english 		= genesis_get_custom_field('desc_english', $post_id);

	// Change date format to unix timestamp
	update_field('startdate', strtotime($cf_startdate)*1000, $post_id);

	// Create duplicate in English and fetch new post id
	$language_to = 'en';
	do_action('wpml_admin_make_post_duplicates', $post_id);
	$tr_post_id = apply_filters( 'wpml_object_id', $post_id, 'post', false, $language_to );

	// set post content and category
	wp_update_post(array(
	  'ID'           => $tr_post_id,
	  'post_title'   => $cf_title_english,
		'post_excerpt' => $cf_excerpt_english,
	  'post_content' => $cf_desc_english,
  ));
	wp_set_post_categories( $tr_post_id, array(12) ); // "Calendar" (English category) have tag_ID=12

}

/// Excerpt mandatory on calendar post
// https://www.wp-tweaks.com/display-custom-error-messages-in-wordpress-admin/
add_action('post_updated_messages', 'psu_excerpt_error_message');
function psu_excerpt_error_message($messages) {
	global $post;
	$cat = get_the_category();
	$term_id = $cat[0]->term_id;
	$is_calendar = ($term_id == 7 || $term_id == 12)? true: false; // 7 and 12 are calendar category ids

	if (
		$is_calendar &&
		$post->post_excerpt == '' &&
		$post->post_status == 'publish'
	) {
    add_settings_error(
			'exerpt_missing_error',
			'',
			'Post not published. Please enter a short description in the excerpt box.',
			'error'
		);
    settings_errors('exerpt_missing_error');
    $post->post_status = 'draft';
    wp_update_post($post);
    return;
	} else {
		return $messages;
	}

}



// ///////////////////////////////////////////////////////////////////////////////////////////
/// GRANTS
// ///////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////
//* Add custom fields before GRANTS content
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
