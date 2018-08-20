<?php


// ///////////////////////////////////////////////////////////////////////////////////////////
## CATEGORY PAGES LAYOUT ##
// ///////////////////////////////////////////////////////////////////////////////////////////


/// All category stuff inits here  ///////////////////////////////////////////////////////////////
add_action('genesis_before', 'psu_category_customization');
function psu_category_customization() {

	if ( is_akademiliv_category_page() ) {
		add_action( 'genesis_before_loop', 'psu_do_taxonomy_title_description', 15 );		//* Add genesis taxonomy title and description to category pages
		add_action( 'genesis_before_loop', 'psu_loop_wrapper_open'  );		//* Extra wrapper around category page loop
		add_action( 'genesis_after_loop', 'psu_loop_wrapper_close'  );
		add_filter( 'genesis_post_title_output', 'psu_custom_post_title' );		// * Remove Links from Post Titles and set heading to H2 in Genesis
		add_filter( 'genesis_post_info', 'psu_category_info_filter' );		//* Filter/remove post meta info
		remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );		//* Remove featured image
		add_filter( 'get_the_content_more_link', 'psu_child_read_more_link' );		//* Remove Read more link
	}

	// special handeling for notices
	if ( is_akademiliv_category_page('notices') ) {
		add_filter( 'genesis_post_info', 'psu_category_notices_info_filter' );
    add_action( 'genesis_before_loop', 'psu_notices_init_full_content' );
	}

  // not in the if bc apply to news archive page also (in the future: change news archive to category page)
  add_filter('excerpt_more', 'psu_excerpt_more');	//* Change dots
  add_filter( 'excerpt_length', 'psu_category_excerpt_length' );  //* Custom content archive limit

}


/// Init full article for notices
function psu_notices_init_full_content() {
	// remove_action( 'genesis_post_content', 'genesis_do_post_content' ); /* Pre-HTML5 */
	remove_action( 'genesis_entry_content', 'genesis_do_post_content' ); /* HTML5 */
	// add_action( 'genesis_post_content', 'sk_do_post_content' ); /* Pre-HTML5 */
	add_action( 'genesis_entry_content', 'psu_notices_entry_content' ); /* HTML5 */
}


/// Add genesis taxonomy title and description to category pages /////////////////////////////////
function psu_do_taxonomy_title_description() {
	global $cat_id;
	$term = get_term( $cat_id, 'category' );
	if ( !$term || !isset( $term->meta ) ) { return; }
	$headline = $intro_text = '';
	if ( $term->meta['headline'] ) { 		$headline = sprintf( '<h1 class="category-title">%s</h1>', strip_tags( $term->meta['headline'] ) ); }
	if ( $term->meta['intro_text'] ) {	$intro_text = sprintf( '<div class="category-intro-text">%s</div>', apply_filters( 'genesis_term_intro_text_output', $term->meta['intro_text'] ) ); }
	if ( $headline || $intro_text ) {		printf( $headline . $intro_text ); }
}


/// Extra wrapper around category page loop ///////////////////////////////////////////////////////////////
function psu_loop_wrapper_open() {
	global $cat_id;
	$term = get_term( $cat_id, 'category' );
	printf('<div class="category-wrapper category-wrapper-%s  cat-%s">', $term->slug, $cat_id);
}
function psu_loop_wrapper_close() {
	printf('</div>');
}


/// Remove Links from Post Titles and set heading to H2 in Genesis //////////////////////////////////////
function psu_custom_post_title( $title ) {
	if( get_post_type( get_the_ID() ) == 'post' ) {
		$post_title =	sprintf('<a href="%s" title="%s">%s</a>', get_permalink(), the_title_attribute('echo=0'), get_the_title()); // get_the_title( get_the_ID() );
		$title = '<h2 class="entry-title" itemprop="headline">' . $post_title . '</h2>';
	}
	return $title;
}


/// Filter/remove post meta info ///////////////////////////////////////////////////////////////
function psu_category_info_filter($post_info) {
//	$post_info = '[post_date] by [post_author_posts_link] [post_comments] [post_edit]';
	$post_info = '';
	return $post_info;
}


/// Special case for meta info on category notices /////////////////////////////////////////
function psu_category_notices_info_filter($post_info) {
//	$post_info = '[post_date] by [post_author_posts_link] [post_comments] [post_edit]';
	$post_info = '[post_date]';
	return $post_info;
}


/// Remove Read more link ///////////////////////////////////////////////////////////////
function psu_child_read_more_link() {
	return '';
}


/// Set archive excrept length based on category ///////////////////////////////////////////////////////////////
function psu_category_excerpt_length($length) {
  global $cat_id;
  switch ( $cat_id ) {
    // notices
    case 1021: //dev
    case 1022:
    case 1590: //stage
    case 1591:
    case 1852: //live
    case 1853:
      return 100;
    // grants
    case 13:
    case 14:
      return 60;
    // calendar
    case 7:
    case 12:
      return 50;
    // news
    default:
      return 70;
  }
}


/// Adjust excerpt dots ///////////////////////////////////////////////////////////////
function psu_excerpt_more( $more ) {
	return '…';
}


/// Full article for 'notices' ///////////////////////////////////////////////////////////////
//  https://gist.github.com/About2git/b5d78dd2bce46bc152a6
function psu_notices_entry_content() {
	global $post;
	the_content();
/*
	if ( is_singular() ) {
		the_content();
		if ( is_single() && 'open' === get_option( 'default_ping_status' ) && post_type_supports( $post->post_type, 'trackbacks' ) ) {
			echo '<!--';
			trackback_rdf();
			echo '-->' . "\n";
		}
		if ( is_page() && apply_filters( 'genesis_edit_post_link', true ) )
			edit_post_link( __( '(Edit)', 'genesis' ), '', '' );
	}
	elseif ( 'excerpts' === genesis_get_option( 'content_archive' ) && !is_category('notices') ) {
	echo "då";
		the_excerpt();
	}
	else {
		if ( genesis_get_option( 'content_archive_limit' ) )
			the_content_limit( (int) genesis_get_option( 'content_archive_limit' ), __( '[Read more...]', 'genesis' ) );
		else
			the_content( __( '[Read more...]', 'genesis' ) );
	}
	*/
}




?>
