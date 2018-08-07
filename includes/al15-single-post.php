<?php

// ///////////////////////////////////////////////////////////////////////////////////////////
## SINGLE POST LAYOUT aka. "Nyhet" ##
// ///////////////////////////////////////////////////////////////////////////////////////////


/// All category stuff inits here  ///////////////////////////////////////////////////////////////
add_action('genesis_before', 'psu_singlepost_customization');
function psu_singlepost_customization() {

	if ( is_akademiliv_default() || is_single() ) {

		//* Extra wrapper around category page loop
		add_action( 'genesis_before_loop', 'psu_loop_wrapper_single_open'  );
		add_action( 'genesis_after_loop', 'psu_loop_wrapper_close'  );

		//* Customize the entry meta in the entry footer
		add_filter( 'genesis_post_meta', 'psu_post_meta_filter' );

		//* Remove the entry header markup and entry title
		remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
		remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
		remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
		remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

		//* Add the entry header markup and entry title togheter with featured image and post info
		add_action( 'genesis_entry_header', 'psu_single_add_entry_header' );
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
		if (!is_akademiliv_single_cat() ) { psu_do_post_info(); } // XXX
		genesis_entry_header_markup_close();


	} else {
		genesis_do_post_title();
		if (!is_akademiliv_single_cat()) { psu_do_post_info(); }
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

  if ( in_category(12) || in_category(7) ){ // calenar: 12, 7
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


?>
