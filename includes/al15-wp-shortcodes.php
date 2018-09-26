<?php


// ///////////////////////////////////////////////////////////////////////////////////////////
## SHORT CODES ##
// ///////////////////////////////////////////////////////////////////////////////////////////


/// This will ensure that the text content of widgets is parsed for shortcodes and those shortcodes are run
add_filter('widget_text', 'do_shortcode');


/// Add shortcode for GU logo
add_shortcode('logotype-gu', 'psu_logo_shortcode');
function psu_logo_shortcode(){

	$l = ICL_LANGUAGE_CODE == 'en'? 'en': 'sv';

	$str['sv']['url'] = 		'http://gu.se';
	$str['sv']['title'] = 	'Göteborgs universitet';
	$str['sv']['img'] = 		'http://gu.se/digitalAssets/1498/1498146_ny_logo_sv_normal.png';
	$str['sv']['alt'] = 		'Göteborgs universitets logotyp';
	$str['en']['url'] = 		'http://gu.se/english';
	$str['en']['title'] = 	'University of Gothenburg';
	$str['en']['img'] = 		'http://gu.se/digitalAssets/1498/1498144_ny_logo_en_normal.png';
	$str['en']['alt'] = 		'University of Gothenburg Logotype';

	printf('<a href="%s" title="%s"><img src="%s" alt="%s"></a>', $str[$l]['url'], $str[$l]['title'], $str[$l]['img'], $str[$l]['alt'] );
}

/// Add shortcode for other language(s)
add_shortcode('switch-language', 'psu_post_languages');
function psu_post_languages(){
	$separator = ' | ';
	$pre_lang = __('På ', 'magazine');
  $languages = icl_get_languages('skip_missing=1');

	if( count($languages) > 1 ) {
//    echo __('This post is also available in: ', 'magazine');
    foreach($languages as $l) {
      if( !$l['active'] ) {
      	$langs[] = sprintf('<a href="%s">%s%s</a>', $l['url'], $pre_lang, $l['native_name']); // or translated_name
//      	$langs[] = '<a href="'.$l['url'].'">'. $l['translated_name']. '</a>';
      }
		}
    printf('<section id="switch-language" class="widget widget_text"><div class="widget-wrap">%s</div></section>', join($separator, $langs) );
  }

}


/// Add shortcode for GAN form
add_shortcode('newsletter-form', 'psu_gan_newsletter_form');
function psu_gan_newsletter_form( $atts ) {
  return sprintf('
		<form class="newsletter-form" method="POST" action="http://gansub.com/s/5rMKyY/">
      <input type="hidden" name="gan_repeat_email" />
      <input type="email" id="email" name="email" required placeholder="%s" />
      <input type="submit" value=" %s " />
    </form>',
    __('Your e-mail', 'magazine'), // email placeholder
    __('Sign up', 'magazine') // submit text
  );
}


/// Add shortcode for calendar form query string
// https://docs.gravityforms.com/api-functions/
add_shortcode('event-url','psu_calendar_query_string');
function psu_calendar_query_string($atts=[]) {

	// check input params, requires gravity form confirmation page setup with
	// redirect query string field as follows: post_id={post_id}&entry_id={entry_id}&callback_id={referer}
	$entry_id 			= intval( $_GET['entry_id'] );
	$event_id 			= intval( $_GET['event_id'] );
	$callback_url 	= strtok( $_GET['callback_url'], '?' );
	if ( $entry_id == 0 || $event_id == 0 || $callback_url == '' ) return;

	$atts = array_change_key_case((array)$atts, CASE_LOWER);
	if ( $atts[0] == 'copy' ) {

		// fetch entry field values and create query string
		$entry = GFAPI::get_entry( $entry_id );
		for ($i=1; $i<100; $i++) { // don't know how to figure out field ids so loop through possible id 1 to 100
			$field_content = $entry[$i];
			if ( $field_content == '' ) continue;
			$field = GFAPI::get_field( $entry['form_id'], $i );
			$querystring[ $field['inputName'] ] = urlencode($field_content);
		}
		$url = $callback_url .'?'. http_build_query($querystring);
		$text = __('Skapa en kopia av ditt arrangemang', 'magazine');
		return sprintf('<a href="%s">%s</a>', $url, $text);

	} elseif ( $atts[0] == 'view' ) {

		$url = get_permalink($event_id);
		$text = __('Länk till ditt nya arrangemang', 'magazine');
		return sprintf('<a href="%s">%s</a>', $url, $text);

	}

}



?>
