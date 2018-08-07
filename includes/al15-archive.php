<?php



// ///////////////////////////////////////////////////////////////////////////////////////////
## ARCHIVE LAYOUT ##
// ///////////////////////////////////////////////////////////////////////////////////////////


/// All category stuff inits here  ///////////////////////////////////////////////////////////////
add_action('genesis_before', 'psu_archive_customization');
function psu_archive_customization() {

	if ( is_akademiliv_archive() ) {

		//* Extra wrapper around category page loop
		add_action( 'genesis_before_loop', 'psu_loop_wrapper_archive_open'  );
		add_action( 'genesis_after_loop', 'psu_loop_wrapper_close'  );

		// * Remove Links from Post Titles and set heading to H2 in Genesis
		add_filter( 'genesis_post_title_output', 'psu_post_title_h2' );

		//* Filter/remove post meta info
		add_filter( 'genesis_post_info', 'psu_post_info_filter' );

		//* Change order of title and image in list
		remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
		add_action( 'genesis_entry_header', 'genesis_do_post_image', 8 );

	}

}

/// Extra wrapper around page loop ///////////////////////////////////////////////////////////////
function psu_loop_wrapper_archive_open() {
	printf('<div class="archive-wrapper">');
}


// * Set heading to H2 in Genesis
function psu_post_title_h2( $title ) {
	if( get_post_type( get_the_ID() ) == 'post'  && !is_single() ) {
		$post_title = get_the_title( get_the_ID() );
		$post_link = get_permalink( get_the_ID() );
		$title = '<h2 class="entry-title" itemprop="headline"><a href="' . $post_link . '" rel="bookmark">' . $post_title . '</a></h2>';
	}
	return $title;
}


/// Filter/remove post meta info ///////////////////////////////////////////////////////////////
function psu_post_info_filter($post_info) {
//	$post_info = '[post_date] by [post_author_posts_link] [post_comments] [post_edit]';
	$post_info = '[post_date] [post_edit]';
	return $post_info;
}






?>
