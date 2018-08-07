<?php

// ///////////////////////////////////////////////////////////////////////////////////////////
## THEME SETUP  ##
// ///////////////////////////////////////////////////////////////////////////////////////////

/// Start the engine & Setup Theme ///////////////////////////////////////////////////////////////
include_once( get_template_directory() . '/lib/init.php' );
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

/// Set Localization (do not remove) ///////////////////////////////////////////////////////////////
load_child_theme_textdomain( 'magazine', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'magazine' ) );

/// Child theme (do not remove) ///////////////////////////////////////////////////////////////
define( 'CHILD_THEME_NAME', 'AL Mag 2015v18' );
define( 'CHILD_THEME_URL', 'http://www.akademiliv.se' );
define( 'CHILD_THEME_VERSION', '1.5' );


// ///////////////////////////////////////////////////////////////////////////////////////////
## INCLUDES  ##
// ///////////////////////////////////////////////////////////////////////////////////////////

/// Init
include_once( get_stylesheet_directory() . '/includes/al15-init-genesis.php' ); // modifications related to Genesis parent theme
include_once( get_stylesheet_directory() . '/includes/al15-init-helpers.php' ); // misc helper functions, date stuff etc.

/// Changes to Worpress and Genesis functionality
include_once( get_stylesheet_directory() . '/includes/al15-wp-shortcodes.php' ); // wordpress goes here shortcodes
include_once( get_stylesheet_directory() . '/includes/al15-wp-images.php'); // image functions
include_once( get_stylesheet_directory() . '/includes/al15-wp-comments.php'); // modification of comments

/// Theme specific layouts, category pages, archive page and single post page
include_once( get_stylesheet_directory() . '/includes/al15-mag-layout.php'); // layout changes mostly related to Genesis theme
include_once( get_stylesheet_directory() . '/includes/al15-mag-categories.php'); // category pages functions
include_once( get_stylesheet_directory() . '/includes/al15-mag-singlepost.php'); // single post page functions
include_once( get_stylesheet_directory() . '/includes/al15-mag-archive.php'); // archive page functions



// ///////////////////////////////////////////////////////////////////////////////////////////
## MISC MODS ##
// ///////////////////////////////////////////////////////////////////////////////////////////


/// Gravity Forms, change date format to unix timestamp "_2" = form ID, "_3" = field ID //////////////////////////
add_filter( 'gform_save_field_value_2_3', 'psu_save_field_value', 10, 4 );
function psu_save_field_value( $value, $entry, $field, $form ) {
  return strtotime( $value )*1000;
}
