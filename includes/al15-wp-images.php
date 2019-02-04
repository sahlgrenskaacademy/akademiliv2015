<?php

// ///////////////////////////////
## IMAGE FUNCTIONS ##
// ///////////////////////////////


/// Add new image sizes: width, height, crop(x_crop,y_crop)  x_crop: ‘left’ ‘center’, ‘right’ -- y_crop: ‘top’, ‘center’, ‘bottom’.
add_image_size( 'home-box', 				300, 200, array('center','center') );
add_image_size( 'home-box-dubble', 	600, 200, array('center','center') );
add_image_size( 'news-listing', 		200, 133, array('center','center') );
add_image_size( 'post-full', 				920, 400, array('center','center') );
add_image_size( 'post-center', 	   	720, 405, array('center','center') );
add_image_size( 'post-medium',   		360, 360, false );
add_image_size( 'post-small',   		180, 180, false );
add_image_size( 'al-sidebar', 	   	286, 286, array('center','center') );
add_image_size( 'admin-col', 				100, 67, array('center','center') );

//add_image_size( 'sidebar-thumbnail', 100, 100, true );
//add_image_size( 'home-middle', 360, 200, true );
//add_image_size( 'home-top', 750, 420, true );
//add_image_size( 'sidebar-thumbnail', 100, 100, true );


/// Default link images to 'none' (instead of 'file') ///////////////////////////////////////////////
update_option('image_default_link_type','none');


/// Register the three useful image sizes for use in Add Media modal /////////////////////////////
add_filter( 'image_size_names_choose', 'psu_custom_sizes_admin' );
function psu_custom_sizes_admin( $sizes ) {
	unset( $sizes['thumbnail'] );
//	unset( $sizes['full'] );
	unset( $sizes['medium'] );
	unset( $sizes['large'] );
  return array_merge( $sizes, array(
    'post-center' => __( 'Entire Width', 'magazine' ),
    'post-medium' => __( 'Medium', 'magazine' ),
    'post-small' 	=> __( 'Small', 'magazine' ),
  ) );
}


/// If no post thumbnail, get the old one from custom fields "thumbs"  ///////////////////////////
add_filter ( 'genesis_pre_get_image', 'pontus_try_custom_thumb', 10, 3 );
function pontus_try_custom_thumb( $output, $args, $post ) {

	$post_thumb = get_post_custom_values( 'thumbs', $post->ID );
	if ( has_post_thumbnail( $post->ID ) || !is_array($post_thumb) ) {
		//* return false = countiue genesis_get_image as usual
		return false;
	}

	$url = $post_thumb[0];
	//* Source path, relative to the root
	$src = str_replace( home_url(), '', $url );

	//* Create the html output
	$args['attr']['class'] = $args['attr']['class'] . ' old-thumb';
	$attr = array_map( 'esc_attr', $args['attr'] );
	$html = '<img src="' .$url.'"';
	foreach ( $attr as $name => $value ) {
		$html .= " $name=" . '"' . $value . '"';
	}
	$sizes = psu_get_defined_sizes( $args['size'] );
	$html .= ' style="width: '. $sizes['width'] .'px; height: '. $sizes['height'] .'px;"';
	$html .= ' />';

	//* Determine output
	if ( 'html' === mb_strtolower( $args['format'] ) ) {
		$output = $html;
	} elseif ( 'url' === mb_strtolower( $args['format'] ) ) {
		$output = $url;
	} else {
		$output = $src;
  }
	return $output;
}

/// Get sizes of custom image size definitions ///
function psu_get_defined_sizes( $_size ) {
	global $_wp_additional_image_sizes;
	$sizes = array();
	if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
	  $sizes['width'] = get_option( $_size . '_size_w' );
	  $sizes['height'] = get_option( $_size . '_size_h' );
	  $sizes['crop'] = (bool) get_option( $_size . '_crop' );
	} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
	  $sizes = array(
		  'width' => $_wp_additional_image_sizes[ $_size ]['width'],
		  'height' => $_wp_additional_image_sizes[ $_size ]['height'],
		  'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
		);
	}
	return $sizes;
}


/// Changes the default embed sizes based on site layout /////////////////////////////////////////////////
add_filter( 'embed_defaults', 'psu_embed_defaults' );
function psu_embed_defaults( $defaults ) {

	if ( is_single() ) {
		return array( 'width'  => 720, 'height' => 405 );
	} else {
		return $defaults;
  }
}


?>
