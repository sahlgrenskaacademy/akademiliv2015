<?php

// ///////////////////////////////
## COMMENTS ##
// ///////////////////////////////


/// Modify the size of the Gravatar in the author box /////////////////////////////////////////
add_filter( 'genesis_author_box_gravatar_size', 'magazine_author_box_gravatar' );
function magazine_author_box_gravatar( $size ) {
	return 140;
}

/// Modify the size of the Gravatar in the entry comments /////////////////////////////////////////
add_filter( 'genesis_comment_list_args', 'magazine_comments_gravatar' );
function magazine_comments_gravatar( $args ) {
	$args['avatar_size'] = 100;
	return $args;
}

/// Remove comment form allowed tags /////////////////////////////////////////
add_filter( 'comment_form_defaults', 'magazine_remove_comment_form_allowed_tags' );
function magazine_remove_comment_form_allowed_tags( $defaults ) {
	$defaults['comment_notes_after'] = '';
	return $defaults;
}

/// Modify comments title text in comments /////////////////////////////////////////
add_filter( 'genesis_title_comments', 'psu_genesis_title_comments' );
function psu_genesis_title_comments() {
	$title = sprintf('<h2>%s</h2>', __('Comments', 'magazine') );
	return $title;
}

/// Modify the speak your mind title in comments /////////////////////////////////////////
add_filter( 'comment_form_defaults', 'psu_comment_form_defaults' );
function psu_comment_form_defaults( $defaults ) {
	$defaults['title_reply'] = __( 'Leave a Comment', 'magazine' );
	return $defaults;
}

/// Remove URL text from WordPress comment form /////////////////////////////////////////
add_filter('comment_form_default_fields','psu_disable_comment_url');
function psu_disable_comment_url($fields) {
	unset($fields['url']);
	return $fields;
}

/// Removes comment form from category posts /////////////////////////////////////////
add_action('genesis_before_loop', 'remove_comment_support', 100);
function remove_comment_support() {
	if ( is_akademiliv_single_cat() ) {
    remove_post_type_support( 'post', 'comments' );
  }
  remove_post_type_support( 'page', 'comments' );
}


?>
