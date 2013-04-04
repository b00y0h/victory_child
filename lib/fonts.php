<?php
 /**
 * Returns an array of system fonts
 */

function options_typography_get_os_fonts() {
	// OS Font Defaults
	$os_faces = array(
		'Arial, sans-serif' => 'Arial',
		'"Avant Garde", sans-serif' => 'Avant Garde',
		'Cambria, Georgia, serif' => 'Cambria',
		'Copse, sans-serif' => 'Copse',
		'Garamond, "Hoefler Text", Times New Roman, Times, serif' => 'Garamond',
		'Georgia, serif' => 'Georgia',
		'"Helvetica Neue", Helvetica, sans-serif' => 'Helvetica Neue',
		'Tahoma, Geneva, sans-serif' => 'Tahoma',
		'Lucida Sans Unicode, Lucida Grande, sans-serif' => 'Lucida'
	);
	return $os_faces;
}

/**
 * Returns a select list of Google fonts
 */

function options_typography_get_google_fonts() {
	// Google Font Defaults
	$google_faces = array(
		'Arvo, serif' => 'Arvo',
		'Copse, sans-serif' => 'Copse',
		'Droid Sans, sans-serif' => 'Droid Sans',
		'Droid Serif, serif' => 'Droid Serif',
		'Lobster, cursive' => 'Lobster',
		'Rock Salt, cursive' => 'Rock Salt',
		'Nobile, sans-serif' => 'Nobile',
		'Open Sans, sans-serif' => 'Open Sans',
		'Oswald, sans-serif' => 'Oswald',
		'Bitter, sans-serif' => 'Bitter',
		'Syncopate, sans-serif' => 'Syncopate',
		'Russo One, sans-serif' => 'Russo One',
		'Krona One, sans-serif' => 'Krona One',
		'Pacifico, cursive' => 'Pacifico',
		'Lovers Quarrel, cursive' => 'Lovers Quarrel',
		'Ewert, cursive' => 'Ewert',
		'Londrina Sketch, cursive' => 'Londrina Sketch',
		'Kaushan Script, cursive' => 'Kaushan Script',
		'Josefin Slab, serif' => 'Josefin Slab',
		'Marcellus, serif' => 'Marcellus',
		'Antic Slab, serif' => 'Antic Slab',
		'Old Standard TT, serif' => 'Old Standard TT',
		'Vollkorn, serif' => 'Vollkorn',
		'Oswald, sans-serif' => 'Oswald',
		'Oxygen, sans-serif' => 'Oxygen',
		'Julius Sans One, sans-serif' => 'Julius Sans One',
		'PT Sans, sans-serif' => 'PT Sans',
		'Lato, sans-serif' => 'Lato',
		'Economica, sans-serif' => 'Economica',
		'Quattrocento, serif' => 'Quattrocento',
		'Raleway, cursive' => 'Raleway',
		'Eagle Lake, cursive' => 'Eagle Lake',
		'Berkshire Swash, cursive' => 'Berkshire Swash',
		'Comfortaa, cursive' => 'Comfortaa',
		'Abril Fatface, cursive' => 'Abril Fatface',
		'Stalemate, cursive' => 'Stalemate',
		'Pompiere, cursive' => 'Pompiere',
		'Chelsea Market, cursive' => 'Chelsea Market',
		'Yesteryear, cursive' => 'Yesteryear',
		'Grand Hotel, cursive' => 'Grand Hotel',
		'Ubuntu, sans-serif' => 'Ubuntu',
		'Jockey One, sans-serif' => 'Jockey One',
		'Yanone Kaffeesatz, sans-serif' => 'Yanone Kaffeesatz'
	);
	return $google_faces;
}



/* 
 * Returns a typography option in a format that can be outputted as inline CSS
 */
 
function options_typography_font_styles($option, $selectors) {
		$output = $selectors . ' {';
		$output .= ' color:' . $option['color'] .'; ';
		$output .= 'font-family:' . $option['face'] . '; ';
		$output .= 'font-weight:' . $option['style'] . '; ';
		$output .= 'font-size:' . $option['size'] . '; ';
		$output .= '}';
		$output .= "\n";
		return $output;
}

/**
 * Checks font options to see if a Google font is selected.
 * If so, options_typography_enqueue_google_font is called to enqueue the font.
 * Ensures that each Google font is only enqueued once.
 */
 
if ( !function_exists( 'options_typography_google_fonts' ) ) {
	function options_typography_google_fonts() {
		$all_google_fonts = array_keys( options_typography_get_google_fonts() );
		// Define all the options that possibly have a unique Google font
		$body_typography = of_get_option('body_typography', 'Bitter, sans-serif');
		$footer_typography = of_get_option('footer_typography', 'Bitter, sans-serif');
		$menu_typography = of_get_option('menu_typography', 'Bitter, sans-serif');
		$h1_typography = of_get_option('h1_typography', 'Bitter, sans-serif');
		$h2_typography = of_get_option('h2_typography', 'Bitter, sans-serif');
		$h3_typography = of_get_option('h3_typography', 'Bitter, sans-serif');
		$h4_typography = of_get_option('h4_typography', 'Bitter, sans-serif');
		$h5_typography = of_get_option('h5_typography', 'Bitter, sans-serif');
		$footer_widget_heading = of_get_option('footer_widget_heading', 'Bitter, sans-serif');
		$homepage_widget_heading = of_get_option('homepage_widget_heading', 'Bitter, sans-serif');
		$top_header_menu = of_get_option('top_header_menu', 'Bitter, sans-serif');
		$modules_title = of_get_option('modules_title', 'Bitter, sans-serif');
		$breadcrumbs_typo = of_get_option('breadcrumbs_typo', 'Bitter, sans-serif');
		$product_page_title = of_get_option('product_page_title', 'Bitter, sans-serif');
		$product_page_price = of_get_option('product_page_price', 'Bitter, sans-serif');
		$sidebar_widget_heading = of_get_option('sidebar_widget_heading', 'Bitter, sans-serif');
		$page_headline = of_get_option('page_headline', 'Bitter, sans-serif');
		$page_title = of_get_option('page_title', 'Bitter, sans-serif');
		$sidebar_custom_widget_heading = of_get_option('sidebar_custom_widget_heading', 'Bitter, sans-serif');
		// Get the font face for each option and put it in an array
		$selected_fonts = array(
			$body_typography['face'],
			$footer_typography['face'],
			$menu_typography['face'],
			$h1_typography['face'],
			$h2_typography['face'],
			$h3_typography['face'],
			$h4_typography['face'],
			$h5_typography['face'],
			$footer_widget_heading['face'],
			$homepage_widget_heading['face'],
			$top_header_menu['face'],
			$modules_title['face'],
			$breadcrumbs_typo['face'],
			$product_page_title['face'],
			$product_page_price['face'],
			$sidebar_widget_heading['face'],
			$page_headline['face'],
			$page_title['face'],
			$sidebar_custom_widget_heading['face']
		);
		// Remove any duplicates in the list
		$selected_fonts = array_unique($selected_fonts);
		// Check each of the unique fonts against the defined Google fonts
		// If it is a Google font, go ahead and call the function to enqueue it
		foreach ( $selected_fonts as $font ) {
			if ( in_array( $font, $all_google_fonts ) ) {
				options_typography_enqueue_google_font($font);
			}
		}
	}
}

add_action( 'wp_enqueue_scripts', 'options_typography_google_fonts' );

/**
 * Enqueues the Google $font that is passed
 */
 
function options_typography_enqueue_google_font($font) {
	$font = explode(',', $font);
	$font = $font[0];
	
	$font = str_replace(" ", "+", $font);
	wp_enqueue_style( "options_typography_$font", "http://fonts.googleapis.com/css?family=$font", false, null, 'all' );
}