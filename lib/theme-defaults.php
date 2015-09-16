<?php

//* Magazine Theme Setting Defaults
add_filter( 'genesis_theme_settings_defaults', 'magazine_theme_defaults' );
function magazine_theme_defaults( $defaults ) {

	$defaults['blog_cat_num']              = 5;
	$defaults['content_archive']           = 'full';
	$defaults['content_archive_limit']     = 380;
	$defaults['content_archive_thumbnail'] = 1;
	$defaults['image_alignment']           = 'alignleft';
	$defaults['image_size']                = 'thumbnail';
	$defaults['posts_nav']                 = 'prev-next';
	$defaults['site_layout']               = 'content-sidebar';

	return $defaults;

}

//* Magazine Theme Setup
add_action( 'after_switch_theme', 'magazine_theme_setting_defaults' );
function magazine_theme_setting_defaults() {

	if( function_exists( 'genesis_update_settings' ) ) {

		genesis_update_settings( array(
			'blog_cat_num'              => 5,	
			'content_archive'           => 'full',
			'content_archive_limit'     => 380,
			'content_archive_thumbnail' => 1,
			'image_alignment'           => 'alignleft',
			'image_size'                => 'thumbnail',
			'posts_nav'                 => 'prev-next',
			'site_layout'               => 'content-sidebar',
		) );
		
		genesis_update_settings( array(
			'location_horizontal'             => 'left',
			'location_vertical'               => 'bottom',
			'posts_num'                       => '3',
			'slideshow_excerpt_content_limit' => '100',
			'slideshow_excerpt_content'       => 'full',
			'slideshow_excerpt_show'          => 0,
			'slideshow_excerpt_width'         => '100',
			'slideshow_height'                => '420',
			'slideshow_hide_mobile'           => 1,
			'slideshow_more_text'             => __( 'Continue Reading', 'outreach' ),
			'slideshow_title_show'            => 1,
			'slideshow_width'                 => '750',
		), GENESIS_RESPONSIVE_SLIDER_SETTINGS_FIELD );
		
	} else {
		
		_genesis_update_settings( array(
			'blog_cat_num'              => 5,	
			'content_archive'           => 'full',
			'content_archive_limit'     => 380,
			'content_archive_thumbnail' => 1,
			'image_alignment'           => 'alignleft',
			'image_size'                => 'thumbnail',
			'posts_nav'                 => 'prev-next',
			'site_layout'               => 'content-sidebar',
		) );
		
		_genesis_update_settings( array(
			'location_horizontal'             => 'left',
			'location_vertical'               => 'bottom',
			'posts_num'                       => '3',
			'slideshow_excerpt_content_limit' => '100',
			'slideshow_excerpt_content'       => 'full',
			'slideshow_excerpt_show'          => 0,
			'slideshow_excerpt_width'         => '100',
			'slideshow_height'                => '420',
			'slideshow_hide_mobile'           => 1,
			'slideshow_more_text'             => __( 'Continue Reading', 'outreach' ),
			'slideshow_title_show'            => 1,
			'slideshow_width'                 => '750',
		), GENESIS_RESPONSIVE_SLIDER_SETTINGS_FIELD );
		
	}

	update_option( 'posts_per_page', 5 );

}

//* Set Genesis Responsive Slider defaults
add_filter( 'genesis_responsive_slider_settings_defaults', 'magazine_responsive_slider_defaults' );
function magazine_responsive_slider_defaults( $defaults ) {

	$args = array(
		'location_horizontal'             => 'left',
		'location_vertical'               => 'bottom',
		'posts_num'                       => '3',
		'slideshow_excerpt_content_limit' => '100',
		'slideshow_excerpt_content'       => 'full',
		'slideshow_excerpt_show'          => 0,
		'slideshow_excerpt_width'         => '100',
		'slideshow_height'                => '420',
		'slideshow_hide_mobile'           => 1,
		'slideshow_more_text'             => __( 'Continue Reading', 'outreach' ),
		'slideshow_title_show'            => 1,
		'slideshow_width'                 => '750',
	);

	$args = wp_parse_args( $args, $defaults );
	
	return $args;
}

//* Simple Social Icon Defaults
add_filter( 'simple_social_default_styles', 'magazine_social_default_styles' );
function magazine_social_default_styles( $defaults ) {

	$args = array(
		'alignment'              => 'aligncenter',
		'background_color'       => '#eeeeee',
		'background_color_hover' => '#222222',
		'border_radius'          => 0,
		'icon_color'             => '#222222',
		'icon_color_hover'       => '#ffffff',
		'size'                   => 56,
	);
		
	$args = wp_parse_args( $args, $defaults );
	
	return $args;
	
}
