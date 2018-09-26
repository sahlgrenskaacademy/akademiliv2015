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




?>
