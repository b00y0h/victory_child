<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */

function optionsframework_option_name() {
	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = function_exists( 'wp_get_theme' ) ? wp_get_theme() : get_current_theme();
	$themename = $themename['Name'];
	$themename = preg_replace("/\W/", "", strtolower($themename) );

	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);
}

function optionsframework_options() {

// Sorter
	$homepage_sorter = array(
		"enabled" => array (
							"placebo"    					=> "placebo", //REQUIRED!
							"headline_area"   				=> "Homepage headline",
							"featured_products"   			=> "Featured products",
							"sale_products"   				=> "Sale products",
							"recent_products" 				=> "Recent products",

							),
		"disabled" => array (
							"placebo"    					=> "placebo", //REQUIRED!
							"best_selling_products" 		=> "Best selling products",
							"products_by_ids"				=> "Products by ids",
							"product_categories" 			=> "Product categories",
							"products_by_category_slug" 	=> "Products by category slug",
							"ads" 							=> "Ads",
							"portfolio" 					=> "Portfolio",
							"testimonials"					=> "Testimonials",
							"sponsors" 						=> "Sponsors",
							"team" 							=> "Team",
							),

		);

	// If using image radio buttons, define a directory path
	$imagepath =  get_stylesheet_directory_uri() . '/images/';
	$imgpatternspath =  get_stylesheet_directory_uri() . '/images/patterns/admin/';

	// Get sidebars defined in theme options (To be modified !!!!!!!!!!)
	$metabox_sidebars = of_get_option('sidebar_list');
	$metabox_sidebars_array = array();
	//list registered sidebars
	foreach ( $GLOBALS['wp_registered_sidebars'] as $registered_sidebar ) {
		$metabox_sidebars_array[$registered_sidebar['id']] = $registered_sidebar['name'];
	}
	if ($metabox_sidebars) {
	//list custom created sidebars
	foreach ($metabox_sidebars as $metabox_sidebars_list ) {
		   $metabox_sidebars_array[$metabox_sidebars_list['id']] = $metabox_sidebars_list['name'];
	}
	}

	// Pull all the portfolio categories into an array
	$options_portfolio_categories = array();
	$options_portfolio_categories_obj = get_terms("portfolio_category");
	$options_portfolio_categories[''] = 'All posts';
	foreach ($options_portfolio_categories_obj as $portfolio_category) {
		$options_portfolio_categories[$portfolio_category->name] = $portfolio_category->name;
	}

	// Pull all the products categories into an array
	$woocommerce_is_active = in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
	$options_product_categories = array();
	if ($woocommerce_is_active) {
	$options_product_categories_obj = get_terms("product_cat");
		foreach ($options_product_categories_obj as $product_category) {
			$options_product_categories[$product_category->term_id] = $product_category->name;
		}
	}

	// Pull all the slideshow categories into an array
	$options_slideshow_categories = array();
	$options_slideshow_categories_obj = get_terms("slideshow_category");
	$options_slideshow_categories[''] = 'All posts';
	foreach ($options_slideshow_categories_obj as $slideshow_category) {
		$options_slideshow_categories[$slideshow_category->name] = $slideshow_category->name;
	}



	// Slideshow data
	$slideshow_array = array(
		'flexslider' => __('Flexslider', 'okthemes'),
		'sequence' => __('Sequence slider', 'okthemes'),
		'elastic' => __('Elastic slider', 'okthemes'),
		'iview' => __('iView slider', 'okthemes'),
		'slit' => __('Slit slider', 'okthemes'),
		'jmpress' => __('jmpress slider', 'okthemes'),
		'multiitemslider' => __('Multi Item Slider', 'okthemes'),
		'slideshow_plugin' => __('Slideshow from plugin', 'okthemes'),
		'none' => __('None', 'okthemes')
	);

	// Layout style
	$layout_style_array = array(
		'full' => __('Full width', 'okthemes'),
		'boxed' => __('Boxed', 'okthemes')
	);

	// Layout style
	$layout_width_array = array(
		'layout_width_960' => __('960px wide', 'okthemes'),
		'layout_width_1140' => __('1200px wide', 'okthemes')
	);

	// Background Defaults
	$body_background_defaults = array(
		'color' => '#ffffff',
		'image' => '',
		'repeat' => '',
		'position' => '',
		'attachment'=>''
	);

	$g_fonts = options_typography_get_google_fonts();
	foreach ( $g_fonts as $font ) {
		options_typography_enqueue_google_font($font);
	}

	$typography_mixed_fonts = array_merge( options_typography_get_os_fonts() , options_typography_get_google_fonts() );
	asort($typography_mixed_fonts);

	$options = array();

	// Style options
	$options[] = array( "name" => __('Layout', 'okthemes'),
						"type" => "heading");

	$options[] = array(	'name' => __('Select layout width', 'okthemes'),
						'desc' => __('Select between 960px or 1140px wide layout', 'okthemes'),
						'id' => 'layout_width',
						'std' => 'layout_width_960',
						'type' => 'select',
						'options' => $layout_width_array);

	$options[] = array(	'name' => __('Select layout style', 'okthemes'),
						'desc' => __('Select between full width and boxed layout', 'okthemes'),
						'id' => 'layout_style',
						'std' => 'full',
						'type' => 'select',
						'options' => $layout_style_array);

	$options[] = array( "name" => __('Body Background', 'okthemes'),
						"desc" => __('Upload a background image here.<br> If you select to display a bacground image/color, set the patterns to "none."', 'okthemes'),
						"id" => "body_background",
						"class" => "hidden subpanel",
						"std" => $body_background_defaults,
						"type" => "background");

	$options[] = array( "name" => __('Select pattern', 'okthemes'),
						"desc" => __('Select a pattern for your boxed layout', 'okthemes'),
						"id" => "pattern_background",
						"class" => "hidden subpanel",
						"std" => "pat09",
						"type" => "images",
						"options" => array(
							'pat01' => $imgpatternspath . 'pat01.png',
							'pat02' => $imgpatternspath . 'pat02.png',
							'pat03' => $imgpatternspath . 'pat03.png',
							'pat04' => $imgpatternspath . 'pat04.png',
							'pat05' => $imgpatternspath . 'pat05.png',
							'pat06' => $imgpatternspath . 'pat06.png',
							'pat07' => $imgpatternspath . 'pat07.png',
							'pat08' => $imgpatternspath . 'pat08.png',
							'pat09' => $imgpatternspath . 'pat09.png',
							'pat10' => $imgpatternspath . 'pat10.png',
							'none' => $imgpatternspath . 'none.png'
							)
						);

	$options[] = array( "name" => __('Sidebar Position', 'okthemes'),
						"desc" => __('Select a sidebar layout position (left or right). You can also select a wide page layout on a per-page basis.', 'okthemes'),
						"id" => "page_layout",
						"std" => "right",
						"type" => "images",
						"options" => array(
							'left' => $imagepath . '2cl.png',
							'right' => $imagepath . '2cr.png')
						);

	$options[] = array(	'name' => __('Responsiveness', 'okthemes'),
						'desc' => __('Enable/Disable responsive mode. Defaults to true.', 'okthemes'),
						'id' => 'responsiveness',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array( "name" => __('Logo Style', 'okthemes'),
						"desc" => __('Display a custom image/logo image in place of title header.', 'okthemes'),
						"id" => "use_logo_image",
						"type" => "checkbox");


	$options[] = array( "name" => __('Header Logo', 'okthemes'),
						"desc" => __('If you prefer to show a graphic logo in place of the header, you can upload or paste the URL here. Set the width and height below. <strong>Your logo should be resized prior to uploading</strong>', 'okthemes'),
						"id" => "header_logo",
						"class" => "hidden subpanel",
						"type" => "upload");

	$options[] = array( "name" => __('Logo Width', 'okthemes'),
						"desc" => __('Width (in px) of your logo.', 'okthemes'),
						"id" => "logo_width",
						"std" => "300",
						"class" => "mini hidden subpanel",
						"type" => "text");

	$options[] = array( "name" => __('Logo Height', 'okthemes'),
						"desc" => __('Height (in px) of your logo.', 'okthemes'),
						"id" => "logo_height",
						"std" => "80",
						"class" => "mini hidden subpanel",
						"type" => "text");

	$options[] = array( "name" => __('Site tagline', 'okthemes'),
						"desc" => __('Display site tagline?', 'okthemes'),
						"id" => "display_site_tagline",
						"type" => "checkbox");

	$options[] = array( "name" => __('Favicon Style', 'okthemes'),
						"desc" => __('Display a custom image/logo favicon.', 'okthemes'),
						"id" => "use_favicon",
						"type" => "checkbox");

	$options[] = array( "name" => __('Favicon Logo', 'okthemes'),
						"desc" => __('Upload or paste the URL here. <strong>Your logo should be resized prior to uploading</strong>', 'okthemes'),
						"id" => "favicon_logo",
						"class" => "hidden subpanel",
						"type" => "upload");

	$options[] = array( "name" => __('WP admin logo style', 'okthemes'),
						"desc" => __('Display a custom image/logo when you wp login page.', 'okthemes'),
						"id" => "use_wp_admin_logo",
						"type" => "checkbox");

	$options[] = array( "name" => __('WP admin logo', 'okthemes'),
						"desc" => __('Upload or paste the URL here. <strong>Your logo should be resized prior to uploading</strong>', 'okthemes'),
						"id" => "wp_admin_logo",
						"class" => "hidden subpanel",
						"type" => "upload");



	// Typography options
	$options[] = array( "name" => __('Typography', 'okthemes'),
						"type" => "heading");

	$options[] = array(	'name' => __('Link color', 'okthemes'),
						'desc' => __('Default: #5c5c5c ', 'okthemes'),
						'id' => 'general_link_color',
						'std' => '#5c5c5c',
						'type' => 'color' );

	$options[] = array(	'name' => __('Link hover color', 'okthemes'),
						'desc' => __('Default: #e07b7b ', 'okthemes'),
						'id' => 'general_link_hover_color',
						'std' => '#e07b7b',
						'type' => 'color' );

	$options[] = array(	'name' => __('Footer Link color', 'okthemes'),
						'desc' => __('Default: #DEDEDE ', 'okthemes'),
						'id' => 'footer_link_color',
						'std' => '#DEDEDE',
						'type' => 'color' );

	$options[] = array(	'name' => __('Footer Link hover color', 'okthemes'),
						'desc' => __('Default: #e07b7b ', 'okthemes'),
						'id' => 'footer_link_hover_color',
						'std' => '#e07b7b',
						'type' => 'color' );

    $options[] = array( "name" => __('Main Body typography', 'okthemes'),
						"desc" => __('Defaults: Size:13, Face: Bitter, Style: normal, Color:575757 ', 'okthemes'),
						"id" => "body_typography",
						"std" => array('size' => '13px','face' => 'Bitter, sans-serif','style' => 'normal','color' => '#575757'),
						'type' => 'typography',
						'options' => array('faces' => $typography_mixed_fonts));

	$options[] = array( "name" => __('Main Menu typography', 'okthemes'),
						"desc" => __('Defaults: Size:11, Face: Bitter, Style: normal, Color:3f3f3f', 'okthemes'),
						"id" => "menu_typography",
						"std" => array('size' => '11px','face' => 'Bitter, sans-serif','style' => 'normal','color' => '#3f3f3f'),
						'type' => 'typography',
						'options' => array('faces' => $typography_mixed_fonts));

	 $options[] = array( "name" => __('Footer typography', 'okthemes'),
						"desc" => __('Defaults: Size:13, Face: Bitter, Style: normal, Color:999999 ', 'okthemes'),
						"id" => "footer_typography",
						"std" => array('size' => '13px','face' => 'Bitter, sans-serif','style' => 'normal','color' => '#999999'),
						'type' => 'typography',
						'options' => array('faces' => $typography_mixed_fonts));

	$options[] = array( "name" => __('(H1) Heading', 'okthemes'),
						"desc" => __('Defaults: Size:30, Face: Bitter, Style: normal, Color:E07B7B', 'okthemes'),
						"id" => "h1_typography",
						"std" => array('size' => '30px','face' => 'Bitter, sans-serif','style' => 'normal','color' => '#E07B7B'),
						'type' => 'typography',
						'options' => array('faces' => $typography_mixed_fonts));

  	$options[] = array( "name" => __('(H2) Heading', 'okthemes'),
						"desc" => __('Defaults: Size:24, Face: Bitter, Style: normal, Color:E07B7B', 'okthemes'),
						"id" => "h2_typography",
						"std" => array('size' => '24px','face' => 'Bitter, sans-serif','style' => 'normal','color' => '#E07B7B'),
						'type' => 'typography',
						'options' => array('faces' => $typography_mixed_fonts));


  	$options[] = array( "name" => __('(H3) Heading', 'okthemes'),
						"desc" => __('Defaults: Size:18, Face: Bitter, Style: normal, Color:E07B7B', 'okthemes'),
						"id" => "h3_typography",
						"std" => array('size' => '18px','face' => 'Bitter, sans-serif','style' => 'normal','color' => '#E07B7B'),
						'type' => 'typography',
						'options' => array('faces' => $typography_mixed_fonts));

	$options[] = array( "name" => __('(H4) Heading', 'okthemes'),
						"desc" => __('Defaults: Size:14, Face: Bitter, Style: normal, Color:E07B7B', 'okthemes'),
						"id" => "h4_typography",
						"std" => array('size' => '14px','face' => 'Bitter, sans-serif','style' => 'bold','color' => '#E07B7B'),
						'type' => 'typography',
						'options' => array('faces' => $typography_mixed_fonts));

 	$options[] = array( "name" => __('(H5) Heading', 'okthemes'),
						"desc" => __('Defaults: Size:12, Face: Bitter, Style: normal, Color:E07B7B', 'okthemes'),
						"id" => "h5_typography",
						"std" => array('size' => '12px','face' => 'Bitter, sans-serif','style' => 'bold','color' => '#E07B7B'),
						'type' => 'typography',
						'options' => array('faces' => $typography_mixed_fonts));

	$options[] = array( "name" => __('Page title', 'okthemes'),
						"desc" => __('Defaults: Size:30, Face: Bitter, Style: normal, Color:E07B7B', 'okthemes'),
						"id" => "page_title",
						"std" => array('size' => '30px','face' => 'Bitter, sans-serif','style' => 'normal','color' => '#E07B7B'),
						'type' => 'typography',
						'options' => array('faces' => $typography_mixed_fonts));

	$options[] = array( "name" => __('Page headline', 'okthemes'),
						"desc" => __('Defaults: Size:36, Face: Bitter, Style: normal, Color:DFE8EC', 'okthemes'),
						"id" => "page_headline",
						"std" => array('size' => '36px','face' => 'Bitter, sans-serif','style' => 'normal','color' => '#DFE8EC'),
						'type' => 'typography',
						'options' => array('faces' => $typography_mixed_fonts));

	$options[] = array( "name" => __('Sidebar widget title', 'okthemes'),
						"desc" => __('Defaults: Size:13, Face: Bitter, Style: bold, Color:E07B7B', 'okthemes'),
						"id" => "sidebar_widget_heading",
						"std" => array('size' => '13px','face' => 'Bitter, sans-serif','style' => 'bold','color' => '#E07B7B'),
						'type' => 'typography',
						'options' => array('faces' => $typography_mixed_fonts));

	$options[] = array( "name" => __('Sidebar custom widget title', 'okthemes'),
						"desc" => __('Defaults: Size:13, Face: Bitter, Style: bold, Color:B1C7D3', 'okthemes'),
						"id" => "sidebar_custom_widget_heading",
						"std" => array('size' => '13px','face' => 'Bitter, sans-serif','style' => 'bold','color' => '#B1C7D3'),
						'type' => 'typography',
						'options' => array('faces' => $typography_mixed_fonts));

	$options[] = array( "name" => __('Footer widget heading', 'okthemes'),
						"desc" => __('Defaults: Size:13, Face: Bitter, Style: bold, Color:ffffff', 'okthemes'),
						"id" => "footer_widget_heading",
						"std" => array('size' => '13px','face' => 'Bitter, sans-serif','style' => 'bold','color' => '#ffffff'),
						'type' => 'typography',
						'options' => array('faces' => $typography_mixed_fonts));

	$options[] = array( "name" => __('Homepage widget heading', 'okthemes'),
						"desc" => __('Defaults: Size:13, Face: Bitter, Style: bold, Color:282727', 'okthemes'),
						"id" => "homepage_widget_heading",
						"std" => array('size' => '13px','face' => 'Bitter, sans-serif','style' => 'bold','color' => '#282727'),
						'type' => 'typography',
						'options' => array('faces' => $typography_mixed_fonts));

	$options[] = array( "name" => __('Top header menu', 'okthemes'),
						"desc" => __('Defaults: Size:10, Face: Bitter, Style: normal, Color:999999', 'okthemes'),
						"id" => "top_header_menu",
						"std" => array('size' => '10px','face' => 'Bitter, sans-serif','style' => 'normal','color' => '#999999'),
						'type' => 'typography',
						'options' => array('faces' => $typography_mixed_fonts));

	$options[] = array( "name" => __('Modules title (product, portfolio, blog, etc.)', 'okthemes'),
						"desc" => __('Defaults: Size:18, Face: Bitter, Style: normal, Color:282727', 'okthemes'),
						"id" => "modules_title",
						"std" => array('size' => '18px','face' => 'Bitter, sans-serif','style' => 'normal','color' => '#282727'),
						'type' => 'typography',
						'options' => array('faces' => $typography_mixed_fonts));

	$options[] = array( "name" => __('Product page: Title', 'okthemes'),
						"desc" => __('Defaults: Size:30, Face: Bitter, Style: normal, Color:282727', 'okthemes'),
						"id" => "product_page_title",
						"std" => array('size' => '30px','face' => 'Bitter, sans-serif','style' => 'normal','color' => '#282727'),
						'type' => 'typography',
						'options' => array('faces' => $typography_mixed_fonts));

	$options[] = array( "name" => __('Product page: Price, On sale, Stock', 'okthemes'),
						"desc" => __('Defaults: Size:18, Face: Bitter, Style: normal, Color:E07B7B', 'okthemes'),
						"id" => "product_page_price",
						"std" => array('size' => '18px','face' => 'Bitter, sans-serif','style' => 'normal','color' => '#E07B7B'),
						'type' => 'typography',
						'options' => array('faces' => $typography_mixed_fonts));

	$options[] = array( "name" => __('Breadcrumbs', 'okthemes'),
						"desc" => __('Defaults: Size:10, Face: Bitter, Style: normal, Color:999999', 'okthemes'),
						"id" => "breadcrumbs_typo",
						"std" => array('size' => '10px','face' => 'Bitter, sans-serif','style' => 'normal','color' => '#999999'),
						'type' => 'typography',
						'options' => array('faces' => $typography_mixed_fonts));


	// Design options
	$options[] = array( "name" => __('Design', 'okthemes'),
						"type" => "heading");

	$options[] = array(	'name' => __('General border color', 'okthemes'),
						'desc' => __('Default: #ededed ', 'okthemes'),
						'id' => 'general_border_color',
						'std' => '#ededed',
						'type' => 'color' );

	$options[] = array(	'name' => __('General hover border color', 'okthemes'),
						'desc' => __('Default: #CEDBE1 ', 'okthemes'),
						'id' => 'general_hover_border_color',
						'std' => '#CEDBE1',
						'type' => 'color' );

	$options[] = array(	'name' => __('Bullet element color', 'okthemes'),
						'desc' => __('Default: #00A8FF ', 'okthemes'),
						'id' => 'bullet_element_color',
						'std' => '#00A8FF',
						'type' => 'color' );

	$options[] = array(	'name' => __('General horizontal line color', 'okthemes'),
						'desc' => __('Default: #EDEDED ', 'okthemes'),
						'id' => 'hr_element_color',
						'std' => '#EDEDED',
						'type' => 'color' );

	$options[] = array(	'name' => __('Footer background', 'okthemes'),
						'desc' => __('Default: #383838 ', 'okthemes'),
						'id' => 'footer_background_color',
						'std' => '#383838',
						'type' => 'color' );

	$options[] = array(	'name' => __('Custom sidebar background color. (Product categories and portfolio single sidebar)', 'okthemes'),
						'desc' => __('Default: #E0E8ED ', 'okthemes'),
						'id' => 'sidebar_background_color',
						'std' => '#E0E8ED',
						'type' => 'color' );

	$options[] = array(	'name' => __('Custom sidebar list background color. (Product categories and portfolio single sidebar)', 'okthemes'),
						'desc' => __('Default: #F3F4F5 ', 'okthemes'),
						'id' => 'sidebar_list_background_color',
						'std' => '#F3F4F5',
						'type' => 'color' );

	$options[] = array(	'name' => __('Navigation elements background color', 'okthemes'),
						'desc' => __('Default: #DEE7EB ', 'okthemes'),
						'id' => 'navigation_elements_background',
						'std' => '#DEE7EB',
						'type' => 'color' );

	$options[] = array(	'name' => __('Buttons background color', 'okthemes'),
						'desc' => __('Default: #DEE7EB ', 'okthemes'),
						'id' => 'buttons_background',
						'std' => '#DEE7EB',
						'type' => 'color' );

	$options[] = array(	'name' => __('Form elements border color', 'okthemes'),
						'desc' => __('Default: #D9D9D9 ', 'okthemes'),
						'id' => 'form_elements_border_color',
						'std' => '#D9D9D9',
						'type' => 'color' );

	$options[] = array(	'name' => __('Header search input background color', 'okthemes'),
						'desc' => __('Default: #E0E8ED ', 'okthemes'),
						'id' => 'header_search_input_background',
						'std' => '#E0E8ED',
						'type' => 'color' );

	$options[] = array(	'name' => __('Header minicart background color', 'okthemes'),
						'desc' => __('Default: #E0E8ED ', 'okthemes'),
						'id' => 'header_minicart_background',
						'std' => '#E0E8ED',
						'type' => 'color' );

	$options[] = array(	'name' => __('Header minicart list background color', 'okthemes'),
						'desc' => __('Default: #F3F4F5 ', 'okthemes'),
						'id' => 'header_minicart_list_background',
						'std' => '#F3F4F5',
						'type' => 'color' );

	$options[] = array(	'name' => __('Main menu background', 'okthemes'),
						'desc' => __('Default: #E0E8ED ', 'okthemes'),
						'id' => 'menu_background_color',
						'std' => '#E0E8ED',
						'type' => 'color' );

	$options[] = array(	'name' => __('Submenu list background', 'okthemes'),
						'desc' => __('Default: #F3F4F5 ', 'okthemes'),
						'id' => 'submenu_background_color',
						'std' => '#F3F4F5',
						'type' => 'color' );

	$options[] = array(	'name' => __('Submenu hover&amp;current list background', 'okthemes'),
						'desc' => __('Default: #E5EAED ', 'okthemes'),
						'id' => 'submenu_hover_background_color',
						'std' => '#E5EAED',
						'type' => 'color' );

	$options[] = array(	'name' => __('Product page tab border top active color', 'okthemes'),
						'desc' => __('Default: #98C0D6 ', 'okthemes'),
						'id' => 'product_tab_border_top',
						'std' => '#98C0D6',
						'type' => 'color' );

	//Homepage options
	$options[] = array( "name" => __('Homepage', 'okthemes'),
						"type" => "heading");

	$options[] = array( "name" => __('Homepage layout', 'okthemes'),
						"desc" => __('Select the homepage page layout: with or without sidebar. Default: with sidebar', 'okthemes'),
						"id" => "homepage_layout",
						"std" => "without_sidebar",
						"type" => "select",
						"options" => array(
							'with_sidebar' => 'With sidebar',
							'without_sidebar' => 'Without sidebar')
						);
	$options[] = array(	'name' => __('Homepage sidebar', 'okthemes'),
						'desc' => __('Select a sidebar to display on the Homepage. Default: "Pages widget area". To create custom sidebars please go to "Sidebars" tab.', 'okthemes'),
						'id' => 'homepage_sidebar_select',
						'std' => 'secondary-widget-area',
						'type' => 'select',
						'options' =>$metabox_sidebars_array
						);

	$options[] = array( "desc" => __('Configure the homepage modules here. To enable the ones you need please head over to "Homepage sorter"', 'okthemes'),
						"type" => "info");

	$options[] = array( "name" => __('Headline options ', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array( "name" => __('Tile', 'okthemes'),
						"desc" => __('Insert main title here. Will be wrapped in a H1 tag.', 'okthemes'),
						"class" => "subpanel",
						"id" => "headline_title",
						"std" => "Main headline goes here",
						"type" => "text");

	$options[] = array( "name" => __('Short description', 'okthemes'),
						"desc" => __('Insert short description here. Will be wrapped in a P tag, under the title.', 'okthemes'),
						"class" => "subpanel",
						"id" => "headline_desc",
						"std" => "Short description goes here",
						"type" => "textarea");

	$options[] = array( "name" => __('Tile link', 'okthemes'),
						"desc" => __('Insert main title link here. Insert "http://" also.', 'okthemes'),
						"class" => "subpanel",
						"id" => "headline_title_link",
						"std" => "",
						"type" => "text");

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Featured products options ', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array( "name" => __('Title', 'okthemes'),
						"desc" => __('Insert the title for featured products area', 'okthemes'),
						"class" => "subpanel",
						"id" => "featured_products_title",
						"std" => "Featured products",
						"type" => "text");

	$options[] = array( "name" => __('No. of posts', 'okthemes'),
						"desc" => __('Insert the number of posts to display. Default:12', 'okthemes'),
						"class" => "subpanel",
						"id" => "featured_products_posts",
						"std" => "12",
						"type" => "text");

	$options[] = array( "name" => __('Order posts by', 'okthemes'),
						"desc" => __('Sort retrieved posts by parameter. Default: date', 'okthemes'),
						"id" => "featured_products_orderby",
						"std" => "date",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'none'  => 'None',
							'title' => 'Title',
							'name'  => 'Name (Post slug)',
							'date'  => 'Date',
							'rand'  => 'Random')
						);

	$options[] = array( "name" => __('Order posts', 'okthemes'),
						"desc" => __('Designate the ascending or descending order of posts. Default: DESC', 'okthemes'),
						"id" => "featured_products_order",
						"std" => "DESC",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'DESC'  => 'DESC',
							'ASC' => 'ASC')
						);

	$options[] = array( "name" => __('Carousel mode', 'okthemes'),
						"desc" => __('Select true to enable carousel, false to show default', 'okthemes'),
						"id" => "featured_products_carousel",
						"class" => "subpanel",
						"type" => "select",
						"std" => "yes",
						"options" => array(
							'yes'  => 'yes',
							'no' => 'no')
						);

	$options[] = array( "name" => __('Carousel autoplay', 'okthemes'),
						"desc" => __('Select true to enable carousel autoplay, false otherwise. Default:false', 'okthemes'),
						"id" => "featured_products_carousel_autoplay",
						"class" => "subpanel",
						"type" => "select",
						"std" => "false",
						"options" => array(
							'true'  => 'true',
							'false' => 'false')
						);

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Sale products options', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array( "name" => __('Title', 'okthemes'),
						"desc" => __('Insert the title for sale products area', 'okthemes'),
						"class" => "subpanel",
						"id" => "sale_products_title",
						"std" => "Sale products",
						"type" => "text");

	$options[] = array( "name" => __('No. of posts', 'okthemes'),
						"desc" => __('Insert the number of posts to display. Default:12', 'okthemes'),
						"class" => "subpanel",
						"id" => "sale_products_posts",
						"std" => "12",
						"type" => "text");

	$options[] = array( "name" => __('Order posts by', 'okthemes'),
						"desc" => __('Sort retrieved posts by parameter. Default: date', 'okthemes'),
						"id" => "sale_products_orderby",
						"std" => "date",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'none'  => 'None',
							'title' => 'Title',
							'name'  => 'Name (Post slug)',
							'date'  => 'Date',
							'rand'  => 'Random')
						);

	$options[] = array( "name" => __('Order posts', 'okthemes'),
						"desc" => __('Designate the ascending or descending order of posts. Default: DESC', 'okthemes'),
						"id" => "sale_products_order",
						"std" => "DESC",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'DESC'  => 'DESC',
							'ASC' => 'ASC')
						);

	$options[] = array( "name" => __('Carousel mode', 'okthemes'),
						"desc" => __('Select true to enable carousel, false to show default', 'okthemes'),
						"id" => "sale_products_carousel",
						"class" => "subpanel",
						"type" => "select",
						"std" => "yes",
						"options" => array(
							'yes'  => 'yes',
							'no' => 'no')
						);

	$options[] = array( "name" => __('Carousel autoplay', 'okthemes'),
						"desc" => __('Select true to enable carousel autoplay, false otherwise. Default:false', 'okthemes'),
						"id" => "sale_products_carousel_autoplay",
						"class" => "subpanel",
						"type" => "select",
						"std" => "false",
						"options" => array(
							'true'  => 'true',
							'false' => 'false')
						);

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Recent products options', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array( "name" => __('Title', 'okthemes'),
						"desc" => __('Insert the title for recent products area', 'okthemes'),
						"class" => "subpanel",
						"id" => "recent_products_title",
						"std" => "Recent products",
						"type" => "text");

	$options[] = array( "name" => __('No. of posts', 'okthemes'),
						"desc" => __('Insert the number of posts to display. Default:12', 'okthemes'),
						"class" => "subpanel",
						"id" => "recent_products_posts",
						"std" => "12",
						"type" => "text");

	$options[] = array( "name" => __('Order posts by', 'okthemes'),
						"desc" => __('Sort retrieved posts by parameter. Default: date', 'okthemes'),
						"id" => "recent_products_orderby",
						"std" => "date",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'none'  => 'None',
							'title' => 'Title',
							'name'  => 'Name (Post slug)',
							'date'  => 'Date',
							'rand'  => 'Random')
						);

	$options[] = array( "name" => __('Order posts', 'okthemes'),
						"desc" => __('Designate the ascending or descending order of posts. Default: DESC', 'okthemes'),
						"id" => "recent_products_order",
						"std" => "DESC",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'DESC'  => 'DESC',
							'ASC' => 'ASC')
						);

	$options[] = array( "name" => __('Carousel mode', 'okthemes'),
						"desc" => __('Select true to enable carousel, false to show default', 'okthemes'),
						"id" => "recent_products_carousel",
						"class" => "subpanel",
						"type" => "select",
						"std" => "yes",
						"options" => array(
							'yes'  => 'yes',
							'no' => 'no')
						);

	$options[] = array( "name" => __('Carousel autoplay', 'okthemes'),
						"desc" => __('Select true to enable carousel autoplay, false otherwise. Default:false', 'okthemes'),
						"id" => "recent_products_carousel_autoplay",
						"class" => "subpanel",
						"type" => "select",
						"std" => "false",
						"options" => array(
							'true'  => 'true',
							'false' => 'false')
						);

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Best selling products options', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array( "name" => __('Title', 'okthemes'),
						"desc" => __('Insert the title for best selling products area', 'okthemes'),
						"class" => "subpanel",
						"id" => "best_selling_products_title",
						"std" => "Best selling products",
						"type" => "text");

	$options[] = array( "name" => __('No. of posts', 'okthemes'),
						"desc" => __('Insert the number of posts to display. Default:12', 'okthemes'),
						"class" => "subpanel",
						"id" => "best_selling_products_posts",
						"std" => "12",
						"type" => "text");

	$options[] = array( "name" => __('Order posts by', 'okthemes'),
						"desc" => __('Sort retrieved posts by parameter. Default: date', 'okthemes'),
						"id" => "best_selling_products_orderby",
						"std" => "date",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'none'  => 'None',
							'title' => 'Title',
							'name'  => 'Name (Post slug)',
							'date'  => 'Date',
							'rand'  => 'Random')
						);

	$options[] = array( "name" => __('Order posts', 'okthemes'),
						"desc" => __('Designate the ascending or descending order of posts. Default: DESC', 'okthemes'),
						"id" => "best_selling_products_order",
						"std" => "DESC",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'DESC'  => 'DESC',
							'ASC' => 'ASC')
						);

	$options[] = array( "name" => __('Carousel mode', 'okthemes'),
						"desc" => __('Select true to enable carousel, false to show default', 'okthemes'),
						"id" => "best_selling_products_carousel",
						"class" => "subpanel",
						"type" => "select",
						"std" => "yes",
						"options" => array(
							'yes'  => 'yes',
							'no' => 'no')
						);

	$options[] = array( "name" => __('Carousel autoplay', 'okthemes'),
						"desc" => __('Select true to enable carousel autoplay, false otherwise. Default:false', 'okthemes'),
						"id" => "best_selling_products_carousel_autoplay",
						"class" => "subpanel",
						"type" => "select",
						"std" => "false",
						"options" => array(
							'true'  => 'true',
							'false' => 'false')
						);

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Products by id options', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array( "name" => __('Title', 'okthemes'),
						"desc" => __('Insert the title products by id area', 'okthemes'),
						"class" => "subpanel",
						"id" => "products_by_id_title",
						"std" => "Products by id",
						"type" => "text");

	$options[] = array( "name" => __('Product ids', 'okthemes'),
						"desc" => __('Insert the product ids separated by commas. E.g. 1154, 1254, 1256 ', 'okthemes'),
						"class" => "subpanel",
						"id" => "products_ids",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => __('Order posts by', 'okthemes'),
						"desc" => __('Sort retrieved posts by parameter. Default: date', 'okthemes'),
						"id" => "products_by_id_orderby",
						"std" => "date",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'none'  => 'None',
							'title' => 'Title',
							'name'  => 'Name (Post slug)',
							'date'  => 'Date',
							'rand'  => 'Random')
						);

	$options[] = array( "name" => __('Order posts', 'okthemes'),
						"desc" => __('Designate the ascending or descending order of posts. Default: DESC', 'okthemes'),
						"id" => "products_by_id_order",
						"std" => "DESC",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'DESC'  => 'DESC',
							'ASC' => 'ASC')
						);

	$options[] = array( "name" => __('Carousel mode', 'okthemes'),
						"desc" => __('Select true to enable carousel, false to show default', 'okthemes'),
						"id" => "products_by_id_carousel",
						"class" => "subpanel",
						"type" => "select",
						"std" => "yes",
						"options" => array(
							'yes'  => 'yes',
							'no' => 'no')
						);

	$options[] = array( "name" => __('Carousel autoplay', 'okthemes'),
						"desc" => __('Select true to enable carousel autoplay, false otherwise. Default:false', 'okthemes'),
						"id" => "products_by_id_carousel_autoplay",
						"class" => "subpanel",
						"type" => "select",
						"std" => "false",
						"options" => array(
							'true'  => 'true',
							'false' => 'false')
						);

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Product categories', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array( "name" => __('Title', 'okthemes'),
						"desc" => __('Insert the product categories title', 'okthemes'),
						"class" => "subpanel",
						"id" => "products_category_title",
						"std" => "Product categories",
						"type" => "text");

	$options[] = array( "name" => __('No. of posts', 'okthemes'),
						"desc" => __('Insert the number of posts to display. Default:12', 'okthemes'),
						"class" => "subpanel",
						"id" => "products_category_posts",
						"std" => "12",
						"type" => "text");


	$options[] = array( "name" => __('Order posts by', 'okthemes'),
						"desc" => __('Sort retrieved posts by parameter. Default: date', 'okthemes'),
						"id" => "products_category_orderby",
						"std" => "date",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'none'  => 'None',
							'title' => 'Title',
							'name'  => 'Name (Post slug)',
							'date'  => 'Date',
							'rand'  => 'Random')
						);

	$options[] = array( "name" => __('Order posts', 'okthemes'),
						"desc" => __('Designate the ascending or descending order of posts. Default: DESC', 'okthemes'),
						"id" => "products_category_order",
						"std" => "DESC",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'DESC'  => 'DESC',
							'ASC' => 'ASC')
						);

	$options[] = array( "name" => __('Carousel mode', 'okthemes'),
						"desc" => __('Select true to enable carousel, false to show default', 'okthemes'),
						"id" => "products_category_carousel",
						"class" => "subpanel",
						"type" => "select",
						"std" => "yes",
						"options" => array(
							'yes'  => 'yes',
							'no' => 'no')
						);

	$options[] = array( "name" => __('Carousel autoplay', 'okthemes'),
						"desc" => __('Select true to enable carousel autoplay, false otherwise. Default:false', 'okthemes'),
						"id" => "products_category_carousel_autoplay",
						"class" => "subpanel",
						"type" => "select",
						"std" => "false",
						"options" => array(
							'true'  => 'true',
							'false' => 'false')
						);

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Products by category slug', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array( "name" => __('Title', 'okthemes'),
						"desc" => __('Insert the products by category slug title', 'okthemes'),
						"class" => "subpanel",
						"id" => "products_by_category_slug_title",
						"std" => "Products by category slug",
						"type" => "text");

	$options[] = array( "name" => __('Category slug', 'okthemes'),
						"desc" => __('Insert the category slug name', 'okthemes'),
						"class" => "subpanel",
						"id" => "products_by_category_slug_name",
						"std" => "",
						"type" => "text");


	$options[] = array( "name" => __('No. of posts', 'okthemes'),
						"desc" => __('Insert the number of posts to display. Default:12', 'okthemes'),
						"class" => "subpanel",
						"id" => "products_by_category_slug_posts",
						"std" => "12",
						"type" => "text");


	$options[] = array( "name" => __('Order posts by', 'okthemes'),
						"desc" => __('Sort retrieved posts by parameter. Default: date', 'okthemes'),
						"id" => "products_by_category_slug_orderby",
						"std" => "date",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'none'  => 'None',
							'title' => 'Title',
							'name'  => 'Name (Post slug)',
							'date'  => 'Date',
							'rand'  => 'Random')
						);

	$options[] = array( "name" => __('Order posts', 'okthemes'),
						"desc" => __('Designate the ascending or descending order of posts. Default: DESC', 'okthemes'),
						"id" => "products_by_category_slug_order",
						"std" => "DESC",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'DESC'  => 'DESC',
							'ASC' => 'ASC')
						);

	$options[] = array( "name" => __('Carousel mode', 'okthemes'),
						"desc" => __('Select true to enable carousel, false to show default', 'okthemes'),
						"id" => "products_by_category_slug_carousel",
						"class" => "subpanel",
						"type" => "select",
						"std" => "yes",
						"options" => array(
							'yes'  => 'yes',
							'no' => 'no')
						);

	$options[] = array( "name" => __('Carousel autoplay', 'okthemes'),
						"desc" => __('Select true to enable carousel autoplay, false otherwise. Default:false', 'okthemes'),
						"id" => "products_by_category_slug_carousel_autoplay",
						"class" => "subpanel",
						"type" => "select",
						"std" => "false",
						"options" => array(
							'true'  => 'true',
							'false' => 'false')
						);

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Ads', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array( "name" => __('Title', 'okthemes'),
						"desc" => __('Insert the ads title', 'okthemes'),
						"class" => "subpanel",
						"id" => "ads_title",
						"std" => "Ads title",
						"type" => "text");

	$options[] = array( "name" => __('No. of posts', 'okthemes'),
						"desc" => __('Insert the number of posts to display. Default:12', 'okthemes'),
						"class" => "subpanel",
						"id" => "ads_posts",
						"std" => "12",
						"type" => "text");


	$options[] = array( "name" => __('Order posts by', 'okthemes'),
						"desc" => __('Sort retrieved posts by parameter. Default: date', 'okthemes'),
						"id" => "ads_orderby",
						"std" => "date",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'none'  => 'None',
							'title' => 'Title',
							'name'  => 'Name (Post slug)',
							'date'  => 'Date',
							'rand'  => 'Random')
						);

	$options[] = array( "name" => __('Order posts', 'okthemes'),
						"desc" => __('Designate the ascending or descending order of posts. Default: DESC', 'okthemes'),
						"id" => "ads_order",
						"std" => "DESC",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'DESC'  => 'DESC',
							'ASC' => 'ASC')
						);

	$options[] = array( "name" => __('Carousel mode', 'okthemes'),
						"desc" => __('Select true to enable carousel, false to show default', 'okthemes'),
						"id" => "ads_carousel",
						"class" => "subpanel",
						"type" => "select",
						"std" => "yes",
						"options" => array(
							'yes'  => 'yes',
							'no' => 'no')
						);

	$options[] = array( "name" => __('Carousel autoplay', 'okthemes'),
						"desc" => __('Select true to enable carousel autoplay, false otherwise. Default:false', 'okthemes'),
						"id" => "ads_carousel_autoplay",
						"class" => "subpanel",
						"type" => "select",
						"std" => "false",
						"options" => array(
							'true'  => 'true',
							'false' => 'false')
						);

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Portfolio', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array( "name" => __('Title', 'okthemes'),
						"desc" => __('Insert the portfolio title', 'okthemes'),
						"class" => "subpanel",
						"id" => "portfolio_title",
						"std" => "Portfolio title",
						"type" => "text");

	$options[] = array( "name" => __('No. of posts', 'okthemes'),
						"desc" => __('Insert the number of posts to display. Default:12', 'okthemes'),
						"class" => "subpanel",
						"id" => "portfolio_posts",
						"std" => "12",
						"type" => "text");

	$options[] = array( "name" => __('Categories', 'okthemes'),
						"desc" => __('Insert the categories from where the post should appear, separated by commas(","). Leave empty to display from all categories.', 'okthemes'),
						"class" => "subpanel",
						"id" => "portfolio_categories",
						"std" => "",
						"type" => "text");


	$options[] = array( "name" => __('Order posts by', 'okthemes'),
						"desc" => __('Sort retrieved posts by parameter. Default: date', 'okthemes'),
						"id" => "portfolio_orderby",
						"std" => "date",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'none'  => 'None',
							'title' => 'Title',
							'name'  => 'Name (Post slug)',
							'date'  => 'Date',
							'rand'  => 'Random')
						);

	$options[] = array( "name" => __('Order posts', 'okthemes'),
						"desc" => __('Designate the ascending or descending order of posts. Default: DESC', 'okthemes'),
						"id" => "portfolio_order",
						"std" => "DESC",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'DESC'  => 'DESC',
							'ASC' => 'ASC')
						);

	$options[] = array( "name" => __('Carousel mode', 'okthemes'),
						"desc" => __('Select true to enable carousel, false to show default', 'okthemes'),
						"id" => "portfolio_carousel",
						"class" => "subpanel",
						"type" => "select",
						"std" => "yes",
						"options" => array(
							'yes'  => 'yes',
							'no' => 'no')
						);

	$options[] = array( "name" => __('Carousel autoplay', 'okthemes'),
						"desc" => __('Select true to enable carousel autoplay, false otherwise. Default:false', 'okthemes'),
						"id" => "portfolio_carousel_autoplay",
						"class" => "subpanel",
						"type" => "select",
						"std" => "false",
						"options" => array(
							'true'  => 'true',
							'false' => 'false')
						);

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Testimonials', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array( "name" => __('Title', 'okthemes'),
						"desc" => __('Insert the testimonials title', 'okthemes'),
						"class" => "subpanel",
						"id" => "testimonials_title",
						"std" => "Testimonials title",
						"type" => "text");

	$options[] = array( "name" => __('No. of posts', 'okthemes'),
						"desc" => __('Insert the number of posts to display. Default:12', 'okthemes'),
						"class" => "subpanel",
						"id" => "testimonials_posts",
						"std" => "12",
						"type" => "text");


	$options[] = array( "name" => __('Order posts by', 'okthemes'),
						"desc" => __('Sort retrieved posts by parameter. Default: date', 'okthemes'),
						"id" => "testimonials_orderby",
						"std" => "date",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'none'  => 'None',
							'title' => 'Title',
							'name'  => 'Name (Post slug)',
							'date'  => 'Date',
							'rand'  => 'Random')
						);

	$options[] = array( "name" => __('Order posts', 'okthemes'),
						"desc" => __('Designate the ascending or descending order of posts. Default: DESC', 'okthemes'),
						"id" => "testimonials_order",
						"std" => "DESC",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'DESC'  => 'DESC',
							'ASC' => 'ASC')
						);

	$options[] = array( "name" => __('Carousel mode', 'okthemes'),
						"desc" => __('Select true to enable carousel, false to show default', 'okthemes'),
						"id" => "testimonials_carousel",
						"class" => "subpanel",
						"type" => "select",
						"std" => "yes",
						"options" => array(
							'yes'  => 'yes',
							'no' => 'no')
						);

	$options[] = array( "name" => __('Carousel autoplay', 'okthemes'),
						"desc" => __('Select true to enable carousel autoplay, false otherwise. Default:false', 'okthemes'),
						"id" => "testimonials_carousel_autoplay",
						"class" => "subpanel",
						"type" => "select",
						"std" => "false",
						"options" => array(
							'true'  => 'true',
							'false' => 'false')
						);

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Sponsors', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array( "name" => __('Title', 'okthemes'),
						"desc" => __('Insert the sponsors title', 'okthemes'),
						"class" => "subpanel",
						"id" => "sponsors_title",
						"std" => "Sponsors title",
						"type" => "text");

	$options[] = array( "name" => __('No. of posts', 'okthemes'),
						"desc" => __('Insert the number of posts to display. Default:12', 'okthemes'),
						"class" => "subpanel",
						"id" => "sponsors_posts",
						"std" => "12",
						"type" => "text");


	$options[] = array( "name" => __('Order posts by', 'okthemes'),
						"desc" => __('Sort retrieved posts by parameter. Default: date', 'okthemes'),
						"id" => "sponsors_orderby",
						"std" => "date",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'none'  => 'None',
							'title' => 'Title',
							'name'  => 'Name (Post slug)',
							'date'  => 'Date',
							'rand'  => 'Random')
						);

	$options[] = array( "name" => __('Order posts', 'okthemes'),
						"desc" => __('Designate the ascending or descending order of posts. Default: DESC', 'okthemes'),
						"id" => "sponsors_order",
						"std" => "DESC",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'DESC'  => 'DESC',
							'ASC' => 'ASC')
						);

	$options[] = array( "name" => __('Carousel mode', 'okthemes'),
						"desc" => __('Select true to enable carousel, false to show default', 'okthemes'),
						"id" => "sponsors_carousel",
						"class" => "subpanel",
						"type" => "select",
						"std" => "yes",
						"options" => array(
							'yes'  => 'yes',
							'no' => 'no')
						);

	$options[] = array( "name" => __('Carousel autoplay', 'okthemes'),
						"desc" => __('Select true to enable carousel autoplay, false otherwise. Default:false', 'okthemes'),
						"id" => "sponsors_carousel_autoplay",
						"class" => "subpanel",
						"type" => "select",
						"std" => "false",
						"options" => array(
							'true'  => 'true',
							'false' => 'false')
						);

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Team', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array( "name" => __('Title', 'okthemes'),
						"desc" => __('Insert the team title', 'okthemes'),
						"class" => "subpanel",
						"id" => "team_title",
						"std" => "Team title",
						"type" => "text");

	$options[] = array( "name" => __('No. of posts', 'okthemes'),
						"desc" => __('Insert the number of posts to display. Default:12', 'okthemes'),
						"class" => "subpanel",
						"id" => "team_posts",
						"std" => "12",
						"type" => "text");


	$options[] = array( "name" => __('Order posts by', 'okthemes'),
						"desc" => __('Sort retrieved posts by parameter. Default: date', 'okthemes'),
						"id" => "team_orderby",
						"std" => "date",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'none'  => 'None',
							'title' => 'Title',
							'name'  => 'Name (Post slug)',
							'date'  => 'Date',
							'rand'  => 'Random')
						);

	$options[] = array( "name" => __('Order posts', 'okthemes'),
						"desc" => __('Designate the ascending or descending order of posts. Default: DESC', 'okthemes'),
						"id" => "team_order",
						"std" => "DESC",
						"class" => "subpanel",
						"type" => "select",
						"options" => array(
							'DESC'  => 'DESC',
							'ASC' => 'ASC')
						);

	$options[] = array( "name" => __('Carousel mode', 'okthemes'),
						"desc" => __('Select true to enable carousel, false to show default', 'okthemes'),
						"id" => "team_carousel",
						"class" => "subpanel",
						"type" => "select",
						"std" => "yes",
						"options" => array(
							'yes'  => 'yes',
							'no' => 'no')
						);

	$options[] = array( "name" => __('Carousel autoplay', 'okthemes'),
						"desc" => __('Select true to enable carousel autoplay, false otherwise. Default:false', 'okthemes'),
						"id" => "team_carousel_autoplay",
						"class" => "subpanel",
						"type" => "select",
						"std" => "false",
						"options" => array(
							'true'  => 'true',
							'false' => 'false')
						);

	$options[] = array( "type" => "close-toggle");


	//Homepage sorter
	$options[] = array( "name" => __('Homepage sorter', 'okthemes'),
						"type" => "heading");

	$options[] = array( "name" => "",
						"desc" => "Organize how you want the layout to appear on the homepage",
						"id" => "homepage_sorter",
						"std" => $homepage_sorter,
						"type" => "sorter" );

	//Slideshow Options
	$options[] = array( "name" => __('Slideshow/Slider', 'okthemes'),
						"type" => "heading");

	$options[] = array(	'name' => __('Select slideshow', 'okthemes'),
						'desc' => __('Select your slideshow', 'okthemes'),
						'id' => 'slideshow_select',
						'std' => 'none',
						'type' => 'select',
						'options' => $slideshow_array);

	$options[] = array(	'name' => __('Select a Category', 'okthemes'),
						'desc' => __('Select a slideshow category to display', 'okthemes'),
						'id' => 'slideshow_select_categories',
						'type' => 'select',
						'options' =>$options_slideshow_categories);

	$options[] = array( "name" => __('Number of posts to show', 'okthemes'),
						"desc" => __('Insert number of sponsors posts to show', 'okthemes'),
						"id" => "slideshow_nr_posts",
						"std" => "5",
						"type" => "text");

	$options[] = array( "name" => __('Flexslider options', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array(	'name' => __('Animate automatically?', 'okthemes'),
						'desc' => __('Select true/false if you want the slideshow to animate automatically. Default: false', 'okthemes'),
						'id' => 'flexslider_auto_animate',
						'std' => 'false',
						'type' => 'select',
						'options' => array(
									'false' => __('False', 'okthemes'),
									'true' => __('True', 'okthemes')
									)
						);

	$options[] = array( "name" => __('Animation speed', 'okthemes'),
						"desc" => __('Insert the animtion speed in ms. Default:7000', 'okthemes'),
						"id" => "flexslider_auto_animate_speed",
						"std" => "7000",
						"type" => "text");

	$options[] = array(	'name' => __('Display navigation?', 'okthemes'),
						'desc' => __('Select true/false if you want to display navigation. Default:true', 'okthemes'),
						'id' => 'flexslider_navigation',
						'std' => 'true',
						'type' => 'select',
						'options' => array(
									'true' => __('True', 'okthemes'),
									'false' => __('False', 'okthemes')
									)
						);

	$options[] = array(	'name' => __('Slide effect', 'okthemes'),
						'desc' => __('Select the slide effect. Default: fade', 'okthemes'),
						'id' => 'flexslider_slide_effect',
						'std' => 'fade',
						'type' => 'select',
						'options' => array(
									'fade' => __('Fade', 'okthemes'),
									'slide' => __('Slide', 'okthemes')
									)
						);
	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Sequence slider option', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array(	'name' => __('Animate automatically?', 'okthemes'),
						'desc' => __('Select true/false if you want the slideshow to animate automatically. Default: false', 'okthemes'),
						'id' => 'sequence_auto_animate',
						'std' => 'false',
						'type' => 'select',
						'options' => array(
									'false' => __('False', 'okthemes'),
									'true' => __('True', 'okthemes')
									)
						);

	$options[] = array( "name" => __('Animation speed', 'okthemes'),
						"desc" => __('Insert the animtion speed in ms. Default:3000', 'okthemes'),
						"id" => "sequence_auto_animate_speed",
						"std" => "3000",
						"type" => "text");

	$options[] = array(	'name' => __('Animate starting frame?', 'okthemes'),
						'desc' => __('Select true/false if you want the slideshow to animate the first slide. Default: true', 'okthemes'),
						'id' => 'sequence_starting_frame',
						'std' => 'true',
						'type' => 'select',
						'options' => array(
									'false' => __('False', 'okthemes'),
									'true' => __('True', 'okthemes')
									)
						);

	$options[] = array(	'name' => __('Pause on hover', 'okthemes'),
						'desc' => __('Select true/false if you want the slideshow to pause on hover. Default: false', 'okthemes'),
						'id' => 'sequence_pause_hover',
						'std' => 'false',
						'type' => 'select',
						'options' => array(
									'false' => __('False', 'okthemes'),
									'true' => __('True', 'okthemes')
									)
						);

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Elastic slider option', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array(	'name' => __('Animate automatically?', 'okthemes'),
						'desc' => __('Select true/false if you want the slideshow to animate automatically. Default: false', 'okthemes'),
						'id' => 'elastic_auto_animate',
						'std' => 'false',
						'type' => 'select',
						'options' => array(
									'false' => __('False', 'okthemes'),
									'true' => __('True', 'okthemes')
									)
						);

	$options[] = array( "name" => __('Animation speed', 'okthemes'),
						"desc" => __('Insert the animtion speed in ms. Default:3000', 'okthemes'),
						"id" => "elastic_auto_animate_speed",
						"std" => "3000",
						"type" => "text");

	$options[] = array( "name" => __('Easing speed', 'okthemes'),
						"desc" => __('Insert the easing speed in ms. Default:800', 'okthemes'),
						"id" => "elastic_easing_speed",
						"std" => "800",
						"type" => "text");

	$options[] = array( "name" => __('Title speed', 'okthemes'),
						"desc" => __('Insert the title speed in ms. Default:1200', 'okthemes'),
						"id" => "elastic_title_speed",
						"std" => "1200",
						"type" => "text");

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Iview slider option', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array(	'name' => __('Slide effect', 'okthemes'),
						'desc' => __('Select the slide effect. Default: random', 'okthemes'),
						'id' => 'iview_slide_effect',
						'std' => 'random',
						'type' => 'select',
						'options' => array(
									'random' 			=> __('Random', 'okthemes'),
									'left-curtain' 		=> __('Left Curtain', 'okthemes'),
									'fade' 				=> __('Fade', 'okthemes'),
									'zigzag-top'		=> __('Zigzag Top', 'okthemes'),
									'strip-left-fade' 	=> __('Strip Left Fade', 'okthemes')
									)
						);

	$options[] = array( "name" => __('Animation speed', 'okthemes'),
						"desc" => __('Insert the animtion speed in ms. Default:500', 'okthemes'),
						"id" => "iview_auto_animate_speed",
						"std" => "500",
						"type" => "text");

	$options[] = array( "name" => __('Caption speed', 'okthemes'),
						"desc" => __('Insert the caption speed in ms. Default:500', 'okthemes'),
						"id" => "iview_caption_speed",
						"std" => "500",
						"type" => "text");

	$options[] = array( "name" => __('Pause time', 'okthemes'),
						"desc" => __('Insert the pause time of the slide before animating to the next one. Default:5000', 'okthemes'),
						"id" => "iview_pause_time",
						"std" => "5000",
						"type" => "text");

	$options[] = array(	'name' => __('Pause on hover', 'okthemes'),
						'desc' => __('Select true/false if you want the slideshow to pause on hover. Default: false', 'okthemes'),
						'id' => 'iview_pause_hover',
						'std' => 'false',
						'type' => 'select',
						'options' => array(
									'false' => __('False', 'okthemes'),
									'true' => __('True', 'okthemes')
									)
						);

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Slit slider option', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array(	'name' => __('Animate automatically?', 'okthemes'),
						'desc' => __('Select true/false if you want the slideshow to animate automatically. Default: false', 'okthemes'),
						'id' => 'slit_auto_animate',
						'std' => 'false',
						'type' => 'select',
						'options' => array(
									'false' => __('False', 'okthemes'),
									'true' => __('True', 'okthemes')
									)
						);

	$options[] = array( "name" => __('Animation speed', 'okthemes'),
						"desc" => __('Insert the animation speed in ms. Default:4000', 'okthemes'),
						"id" => "slit_auto_animate_speed",
						"std" => "4000",
						"type" => "text");

	$options[] = array( "name" => __('Transition speed', 'okthemes'),
						"desc" => __('Insert the transition speed in ms. Default:800', 'okthemes'),
						"id" => "slit_transition_speed",
						"std" => "800",
						"type" => "text");

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('JMpress slider option', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array(	'name' => __('Animate automatically?', 'okthemes'),
						'desc' => __('Select true/false if you want the slideshow to animate automatically. Default: false', 'okthemes'),
						'id' => 'jmpress_auto_animate',
						'std' => 'false',
						'type' => 'select',
						'options' => array(
									'false' => __('False', 'okthemes'),
									'true' => __('True', 'okthemes')
									)
						);

	$options[] = array( "name" => __('Animation speed', 'okthemes'),
						"desc" => __('Insert the animation speed in ms. Default:3500', 'okthemes'),
						"id" => "jmpress_auto_animate_speed",
						"std" => "3500",
						"type" => "text");

	$options[] = array(	'name' => __('Arrows animation', 'okthemes'),
						'desc' => __('Select true/false if you want to display arrows animations. Default: false', 'okthemes'),
						'id' => 'jmpress_arrows_animation',
						'std' => 'false',
						'type' => 'select',
						'options' => array(
									'false' => __('False', 'okthemes'),
									'true' => __('True', 'okthemes')
									)
						);

	$options[] = array(	'name' => __('Bullet animation', 'okthemes'),
						'desc' => __('Select true/false if you want to display bullet animations. Default: true', 'okthemes'),
						'id' => 'jmpress_bullet_animation',
						'std' => 'true',
						'type' => 'select',
						'options' => array(
									'false' => __('False', 'okthemes'),
									'true' => __('True', 'okthemes')
									)
						);

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Product Categories Slider', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array(	'name' => __('Select product categories', 'okthemes'),
						'desc' => __('Select the product categories you want to display. Each category can display a maximum of 4 products.', 'okthemes'),
						'id' => 'product_cats',
						'type' => 'multicheck',
						'options' => $options_product_categories);

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Slideshow from plugin', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array( "name" => __('Slideshow shortcode', 'okthemes'),
						"desc" => __('Insert here the shortcode for your slideshow plugin', 'okthemes'),
						"id" => "slideshow_plugin_shortcode",
						"std" => "",
						"type" => "textarea");

	$options[] = array( "type" => "close-toggle");

	//Store Options
	$options[] = array( "name" => __('Store', 'okthemes'),
						"type" => "heading");

	$options[] = array(	'name' => __('Catalog mode', 'okthemes'),
						'desc' => __('Enable/Disable catalog mode. This will disable: add to cart, checkout and buy functions. Defaults to false.', 'okthemes'),
						'id' => 'store_catalog_mode',
						'std' => '0',
						'type' => 'checkbox');

	$options[] = array(	'name' => __('Cloud zoom feature', 'okthemes'),
						'desc' => __('Enable/Disable the Cloud zoom feature in product page. This will be reverted to the original woocommerce lightbox. Defaults to true.', 'okthemes'),
						'id' => 'product_cloud_zoom',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array( "name" => __('Mega dropdown options', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array(	'desc' => __('Mega dropdown is activated if you insert "mega-dropdown" in the "CSS classes" field from "Appearance >> Menus >> Menu item".</br></br> Image size: 480x150px (the height can be as much as you want). </br></br>Note: Only applies to the main navigation. ', 'okthemes'),
						'type' => 'info');

	$options[] = array( "name" => __('Ad image', 'okthemes'),
						"desc" => __('Upload your ad image here.', 'okthemes'),
						"id" => "mega_dropdown_ad",
						"type" => "upload");

	$options[] = array( "name" => __('Ad link', 'okthemes'),
						"desc" => __('Insert the ad image link here.', 'okthemes'),
						"id" => "mega_dropdown_ad_link",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => __('Ad description', 'okthemes'),
						"desc" => __('Description applies to image "alt" tag.', 'okthemes'),
						"id" => "mega_dropdown_ad_desc",
						"std" => "",
						"type" => "text");

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('General options', 'okthemes'),
						"type" => "open-toggle");


	$options[] = array( "name" => __('Number of products per page', 'okthemes'),
						"desc" => __('Default:12', 'okthemes'),
						"id" => "product_per_page",
						"std" => "12",
						"type" => "text"
						);

	$options[] = array(	'name' => __('Header cart', 'okthemes'),
						'desc' => __('Enable/Disable header cart, defaults to true.', 'okthemes'),
						'id' => 'store_header_cart',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array(	'name' => __('Header products search', 'okthemes'),
						'desc' => __('Enable/Disable header products search, defaults to true.', 'okthemes'),
						'id' => 'store_header_search',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array(	'name' => __('Breadcrumbs', 'okthemes'),
						'desc' => __('Enable/Disable store breadcrumbs, defaults to true.', 'okthemes'),
						'id' => 'store_breadcrumbs',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array( "name" => __('Grid/list default view', 'okthemes'),
						"desc" => __('Select the display mode of the products. Default: Grid view', 'okthemes'),
						"id" => "shop_grid_list_default",
						"std" => "grid",
						"type" => "select",
						"options" => array(
							'grid' => 'Grid view',
							'list' => 'List view')
						);

	$options[] = array(	'name' => __('Sale flash', 'okthemes'),
						'desc' => __('Enable/Disable sale flash on products, defaults to true.', 'okthemes'),
						'id' => 'store_sale_flash',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array(	'name' => __('Products price', 'okthemes'),
						'desc' => __('Enable/Disable products price, defaults to true.', 'okthemes'),
						'id' => 'store_products_price',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array(	'name' => __('Add to cart', 'okthemes'),
						'desc' => __('Enable/Disable add to cart button, defaults to true.', 'okthemes'),
						'id' => 'store_add_to_cart',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Shop options', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array( "name" => __('Shop layout', 'okthemes'),
						"desc" => __('Select the Shop page layout: with or without sidebar', 'okthemes'),
						"id" => "shop_layout",
						"std" => "with_sidebar",
						"type" => "select",
						"options" => array(
							'with_sidebar' => 'With sidebar',
							'without_sidebar' => 'Without sidebar')
						);

	$options[] = array(	'name' => __('Shop sidebar', 'okthemes'),
						'desc' => __('Select a sidebar to display on the Shop page. Default: "Pages widget area". To create custom sidebars please go to "Sidebars" tab.', 'okthemes'),
						'id' => 'shop_sidebar_select',
						'std' => 'secondary-widget-area',
						'type' => 'select',
						'options' =>$metabox_sidebars_array
						);

	$options[] = array(	'name' => __('Grid/list view', 'okthemes'),
						'desc' => __('Enable/Disable grid/list view, defaults to true.', 'okthemes'),
						'id' => 'shop_grid_list_view',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array(	'name' => __('Grid view short description', 'okthemes'),
						'desc' => __('Enable/Disable grid view short description, defaults to false.', 'okthemes'),
						'id' => 'shop_grid_desc',
						'std' => '0',
						'type' => 'checkbox');

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Catalog options', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array( "name" => __('Catalog layout', 'okthemes'),
						"desc" => __('Select the Catalog page layout: with or without sidebar', 'okthemes'),
						"id" => "catalog_layout",
						"std" => "with_sidebar",
						"type" => "select",
						"options" => array(
							'with_sidebar' => 'With sidebar',
							'without_sidebar' => 'Without sidebar')
						);

	$options[] = array(	'name' => __('Catalog sidebar', 'okthemes'),
						'desc' => __('Select a sidebar to display on the Catalog page. Default: "Pages widget area". To create custom sidebars please go to "Sidebars" tab.', 'okthemes'),
						'id' => 'catalog_sidebar_select',
						'std' => 'secondary-widget-area',
						'type' => 'select',
						'options' =>$metabox_sidebars_array
						);

	$options[] = array(	'name' => __('Grid/list view', 'okthemes'),
						'desc' => __('Enable/Disable grid/list view, defaults to true.', 'okthemes'),
						'id' => 'catalog_grid_list_view',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array(	'name' => __('Grid view short description', 'okthemes'),
						'desc' => __('Enable/Disable grid view short description, defaults to false.', 'okthemes'),
						'id' => 'catalog_grid_desc',
						'std' => '0',
						'type' => 'checkbox');

	$options[] = array(	'name' => __('Category image', 'okthemes'),
						'desc' => __('Enable/Disable category image in product category, defaults to true.', 'okthemes'),
						'id' => 'catalog_category_img',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array(	'name' => __('Category description', 'okthemes'),
						'desc' => __('Enable/Disable category image in product category, defaults to true.', 'okthemes'),
						'id' => 'catalog_category_desc',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Product options', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array(	'name' => __('Sale flash', 'okthemes'),
						'desc' => __('Enable/Disable sale flash on products, defaults to true.', 'okthemes'),
						'id' => 'product_sale_flash',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array(	'name' => __('Product price', 'okthemes'),
						'desc' => __('Enable/Disable product price, defaults to true.', 'okthemes'),
						'id' => 'product_products_price',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array(	'name' => __('Product excerpt', 'okthemes'),
						'desc' => __('Enable/Disable product excerpt(short description), defaults to true.', 'okthemes'),
						'id' => 'product_products_excerpt',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array(	'name' => __('Product meta', 'okthemes'),
						'desc' => __('Enable/Disable product meta(sku, category, tag), defaults to true.', 'okthemes'),
						'id' => 'product_products_meta',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array(	'name' => __('Add to cart', 'okthemes'),
						'desc' => __('Enable/Disable add to cart button, defaults to true.', 'okthemes'),
						'id' => 'product_add_to_cart',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array(	'name' => __('Related products', 'okthemes'),
						'desc' => __('Enable/Disable Related products. Defaults to true. <a target="_blank" href="http://wcdocs.woothemes.com/user-guide/related-products-up-sells-and-cross-sells/">How does this work?</a>', 'okthemes'),
						'id' => 'product_related_products',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array(	'name' => __('Up sells products', 'okthemes'),
						'desc' => __('Enable/Disable Up Sells products. Defaults to true. <a target="_blank" href="http://wcdocs.woothemes.com/user-guide/related-products-up-sells-and-cross-sells/">How does this work?</a>', 'okthemes'),
						'id' => 'product_upsells_products',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array(	'name' => __('Product Reviews tab', 'okthemes'),
						'desc' => __('Enable/Disable reviews tab. Defaults to true.', 'okthemes'),
						'id' => 'product_reviews_tab',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array(	'name' => __('Product Description tab', 'okthemes'),
						'desc' => __('Enable/Disable description tab, defaults to true.', 'okthemes'),
						'id' => 'product_description_tab',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array(	'name' => __('Product Attributes tab', 'okthemes'),
						'desc' => __('Enable/Disable attributes tab, defaults to true.', 'okthemes'),
						'id' => 'product_attributes_tab',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array( "type" => "close-toggle");


	$options[] = array( "name" => __('Cart options', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array(	'name' => __('Cross Sells products', 'okthemes'),
						'desc' => __('Enable/Disable Cross Sells products. Defaults to true. <a target="_blank" href="http://wcdocs.woothemes.com/user-guide/related-products-up-sells-and-cross-sells/">How does this work?</a>', 'okthemes'),
						'id' => 'product_crosssells_products',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array( "type" => "close-toggle");

	//Page templates Options
	$options[] = array( "name" => __('Page templates', 'okthemes'),
						"type" => "heading");

	$options[] = array( "name" => __('Portfolio templates', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array(	'name' => __('Related posts', 'okthemes'),
						'desc' => __('Enable/Disable related posts on portfolio pages, defaults to true.', 'okthemes'),
						'id' => 'portfolio_related_posts',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array( "name" => __('Related posts title', 'okthemes'),
						"desc" => __('Insert the title of "Related posts" module', 'okthemes'),
						"id" => "portfolio_related_posts_title",
						"std" => "Related posts",
						"type" => "text");

	$options[] = array(	'name' => __('Project details', 'okthemes'),
						'desc' => __('Enable/Disable project details box, defaults to true.', 'okthemes'),
						'id' => 'portfolio_project_details',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array( "name" => __('Project details title', 'okthemes'),
						"desc" => __('Insert the title of "Project details" box', 'okthemes'),
						"id" => "portfolio_project_details_title",
						"std" => "Project details",
						"type" => "text");

	$options[] = array( "type" => "close-toggle");

	$options[] = array( "name" => __('Blog template', 'okthemes'),
						"type" => "open-toggle");

	$options[] = array(	'name' => __('Blog inner image', 'okthemes'),
						'desc' => __('Enable/Disable blog inner page, defaults to true.', 'okthemes'),
						'id' => 'blog_inner_image',
						'std' => '1',
						'type' => 'checkbox');

	$options[] = array( "type" => "close-toggle");

	//Social Media Options
	$options[] = array( "name" => __('Social Media', 'okthemes'),
						"type" => "heading");

	$options[] = array( "name" => __('RSS Link (Widget)', 'okthemes'),
						"desc" => __('Social Icon will display/hide automatically in the social icon widget.', 'okthemes'),
						"id" => "rss_link",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => __('Facebook Link (Widget)', 'okthemes'),
						"desc" => __('Social Icon will display/hide automatically in the social icon widget.', 'okthemes'),
						"id" => "facebook_link",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => __('Twitter Link (Widget)', 'okthemes'),
						"desc" => __('Social Icon will display/hide automatically in the social icon widget.', 'okthemes'),
						"id" => "twitter_link",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => __('Skype Link (Widget)', 'okthemes'),
						"desc" => __('Social Icon will display/hide automatically in the social icon widget.', 'okthemes'),
						"id" => "skype_link",
						"std" => "",
						"type" => "text");


	$options[] = array( "name" => __('Vimeo Link (Widget)', 'okthemes'),
						"desc" => __('Social Icon will display/hide automatically in the social icon widget.', 'okthemes'),
						"id" => "vimeo_link",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => __('LinkedIn Link (Widget)', 'okthemes'),
						"desc" => __('Social Icon will display/hide automatically in the social icon widget.', 'okthemes'),
						"id" => "linkedin_link",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => __('Dribble Link (Widget)', 'okthemes'),
						"desc" => __('Social Icon will display/hide automatically in the social icon widget.', 'okthemes'),
						"id" => "dribble_link",
						"std" => "",
						"type" => "text");


	$options[] = array( "name" => __('Forrst Link (Widget)', 'okthemes'),
						"desc" => __('Social Icon will display/hide automatically in the social icon widget.', 'okthemes'),
						"id" => "forrst_link",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => __('Flickr Link (Widget)', 'okthemes'),
						"desc" => __('Social Icon will display/hide automatically in the social icon widget.', 'okthemes'),
						"id" => "flickr_link",
						"std" => "",
						"type" => "text");


	$options[] = array( "name" => __('Google Link (Widget)', 'okthemes'),
						"desc" => __('Social Icon will display/hide automatically in the social icon widget.', 'okthemes'),
						"id" => "google_link",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => __('Youtube Link (Widget)', 'okthemes'),
						"desc" => __('Social Icon will display/hide automatically in the social icon widget.', 'okthemes'),
						"id" => "youtube_link",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => __('Tumblr Link (Widget)', 'okthemes'),
						"desc" => __('Social Icon will display/hide automatically in the social icon widget.', 'okthemes'),
						"id" => "tumblr_link",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => __('Behance Link (Widget)', 'okthemes'),
						"desc" => __('Social Icon will display/hide automatically in the social icon widget.', 'okthemes'),
						"id" => "behance_link",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => __('Personal Website Link (Widget)', 'okthemes'),
						"desc" => __('Social Icon will display/hide automatically in the social icon widget.', 'okthemes'),
						"id" => "personal_link",
						"std" => "",
						"type" => "text");

	//SEO Options
	$options[] = array( "name" => __('SEO', 'okthemes'),
						"type" => "heading");

	$options[] = array( "name" => __('Meta Description', 'okthemes'),
						"desc" => __('Enter a brief description for your site. This is what gets displayed on a Search Engine results page.', 'okthemes'),
						"id" => "seo_meta_desc",
						"std" => "",
						"type" => "textarea");

	$options[] = array( "name" => __('Meta Keywords', 'okthemes'),
						"desc" => __('Enter a comma separated list of keywords for your site (E.g.: keyword1, keyword2, keyword3)', 'okthemes'),
						"id" => "seo_meta_keywords",
						"std" => "",
						"type" => "textarea");

	//Misc Options
	$options[] = array( "name" => __('Sidebars', 'okthemes'),
						"type" => "heading");

	$options[] = array( "name" => "Create new custom sidebar:",
						"desc" => "",
						"id" => "sidebar_create",
						"type" => "sidebar_create",
						"std" => "");

	$options[] = array( "name" => "Available custom sidebars:",
						"desc" => "",
						"id" => "sidebar_list",
						"type" => "sidebar_list",
						"std" => "");

	//Misc Options
	$options[] = array( "name" => __('Footer', 'okthemes'),
						"type" => "heading");

	$options[] = array( "name" => __('Footer image', 'okthemes'),
						"desc" => __('Upload or paste the URL here. <strong>This image will be inserted at the bottom of the site, centered.</strong>', 'okthemes'),
						"id" => "footer_image",
						"type" => "upload");

	$options[] = array( "name" => __('Footer image link', 'okthemes'),
						"desc" => __('Insert a link to wrap your image', 'okthemes'),
						"id" => "footer_image_link",
						"type" => "text");

	$options[] = array( "name" => __('Footer copyright title', 'okthemes'),
						"desc" => __('Enter your copyright informations here', 'okthemes'),
						"id" => "footer_copyright",
						"std" => __('Copyright 2012 - All rights reserved', 'okthemes'),
						"type" => "text");

	$options[] = array( "name" => __('Footer Scripts', 'okthemes'),
						"desc" => __('Add custom footer scripts such as Google Analytics. Do not include the &lt;script&gt; tag. This is already done for you.', 'okthemes'),
						"id" => "footer_scripts",
						"std" => "",
						"type" => "textarea");

	return $options;
}