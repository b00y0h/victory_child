<?php
$get_root_path = __FILE__;
$path_to_wpcontent = explode( 'wp-content', $get_root_path );
$path_to_wpload = $path_to_wpcontent[0];
require_once( $path_to_wpload.'/wp-load.php' );

// Content type
header("Content-type: text/css"); 

// Main Body Styles
$typography = of_get_option('body_typography');
$body_background = of_get_option('body_background');
$pattern_background = of_get_option('pattern_background');
echo 'body {';
if ($typography) {
		echo 'color:'.$typography['color'].';';
		echo 'font-size:'.$typography['size'].';';
		echo 'font-family:'.$typography['face'].';';
		echo 'font-weight:'.$typography['style'].';';
		echo 'font-style:'.$typography['style'].';';
	}

// Verify if layout = boxed
$layout_style = of_get_option('layout_style');
	if ($layout_style == 'boxed') { 	
	// Custom Background
	if ($body_background) {
		if ($body_background['image']) {
		echo 'background:'.$body_background['color'].' url('.$body_background['image'].') '.$body_background['repeat'].' '.$body_background['position'].' '.$body_background['attachment'].'';
		} elseif ($body_background['color']) {
		echo 'background-color:'.$body_background['color'].';';
		}
	}
	// Pattern Background
	if ($pattern_background) {
		if ($pattern_background !== 'none') {
		echo 'background: url('.get_template_directory_uri().'/images/patterns/'.$pattern_background.'.png) repeat left top;';
		}
	}
}
// End Body Styles
echo '}';

if ((!$typography['color'] == '#575757'))  { 
echo '.testimonials-wrapper li blockquote:before, table td {color:inherit}';
}


// Main Menu Styles
$menu_typography = of_get_option('menu_typography');
if ($menu_typography) {
echo '#navigation ul li a {';
		echo 'color:'.$menu_typography['color'].' !important;';
		echo 'font-size:'.$menu_typography['size'].' !important;';
		echo 'font-family:'.$menu_typography['face'].';';
		echo 'font-weight:'.$menu_typography['style'].';';
		echo 'font-style:'.$menu_typography['style'].';';
echo '}';
if ($menu_typography['color'] !== '#3f3f3f')  {
echo '#header .header_mini_cart ul.mini-cart, #header .header_mini_cart ul li ul.cart_list li a {font-family: inherit;}';
}
}		

// Footer typography
$footer_typography = of_get_option('footer_typography');
if ($footer_typography) {
echo '#footer {';

		echo 'color:'.$footer_typography['color'].';';
		echo 'font-size:'.$footer_typography['size'].';';
		echo 'font-family:'.$footer_typography['face'].';';
		echo 'font-weight:'.$footer_typography['style'].';';
		echo 'font-style:'.$footer_typography['style'].';';
echo '}';

if ($footer_typography['color'] !== '#999999') {
echo '.widget-container.contact ul li span, #footer .contact ul li span {color:'.$footer_typography['color'].';}';
}

}

// Footer widget heading
$footer_widget_heading = of_get_option('footer_widget_heading');

echo '#footer h3.widget-title {';
if ($footer_widget_heading) {
		echo 'color:'.$footer_widget_heading['color'].';';
		echo 'font-size:'.$footer_widget_heading['size'].';';
		echo 'font-family:'.$footer_widget_heading['face'].';';
		echo 'font-weight:'.$footer_widget_heading['style'].';';
		echo 'font-style:'.$footer_widget_heading['style'].';';
	}
echo '}';

// Sidebar custom widget title
$sidebar_custom_widget_heading = of_get_option('sidebar_custom_widget_heading');

echo '#sidebar ul li.widget_product_categories h3, #sidebar ul li.widget_shopping_cart h3, ul.portfolio-meta li h4, #header .header_mini_cart ul.mini-cart li ul.cart_list li.cart-title h4 {';
if ($sidebar_custom_widget_heading) {
		echo 'color:'.$sidebar_custom_widget_heading['color'].';';
		echo 'font-size:'.$sidebar_custom_widget_heading['size'].';';
		echo 'font-family:'.$sidebar_custom_widget_heading['face'].';';
		echo 'font-weight:'.$sidebar_custom_widget_heading['style'].';';
		echo 'font-style:'.$sidebar_custom_widget_heading['style'].';';
		
		$sidebar_custom_widget_heading_shadow = su_hex_shift( $sidebar_custom_widget_heading['color'], 'lighter', 80 );
		echo 'text-shadow: 1px 1px 0 '.$sidebar_custom_widget_heading_shadow.';';
	}
echo '}';

// Page title
$page_title = of_get_option('page_title');

echo 'h1.entry-title {';
if ($page_title) {
		echo 'color:'.$page_title['color'].';';
		echo 'font-size:'.$page_title['size'].';';
		echo 'font-family:'.$page_title['face'].';';
		echo 'font-weight:'.$page_title['style'].';';
		echo 'font-style:'.$page_title['style'].';';
	}
echo '}';

// Page headline
$page_headline = of_get_option('page_headline');

echo '.page-headline-wrapper p {';
if ($page_headline) {
		echo 'color:'.$page_headline['color'].';';
		echo 'font-size:'.$page_headline['size'].';';
		echo 'font-family:'.$page_headline['face'].';';
		echo 'font-weight:'.$page_headline['style'].';';
		echo 'font-style:'.$page_headline['style'].';';
	}
echo '}';

// Sidebar widget heading
$sidebar_widget_heading = of_get_option('sidebar_widget_heading');

echo '.widget-title {';
if ($sidebar_widget_heading) {
		echo 'color:'.$sidebar_widget_heading['color'].';';
		echo 'font-size:'.$sidebar_widget_heading['size'].';';
		echo 'font-family:'.$sidebar_widget_heading['face'].';';
		echo 'font-weight:'.$sidebar_widget_heading['style'].';';
		echo 'font-style:'.$sidebar_widget_heading['style'].';';
	}
echo '}';

// Homepage widget heading
$homepage_widget_heading = of_get_option('homepage_widget_heading');

echo '#homepage-style-default h3.widget-title {';
if ($homepage_widget_heading) {
		echo 'color:'.$homepage_widget_heading['color'].';';
		echo 'font-size:'.$homepage_widget_heading['size'].';';
		echo 'font-family:'.$homepage_widget_heading['face'].';';
		echo 'font-weight:'.$homepage_widget_heading['style'].';';
		echo 'font-style:'.$homepage_widget_heading['style'].';';
	}
echo '}';

// Top header menu
$top_header_menu = of_get_option('top_header_menu');

echo '.top-menu-header ul li a {';
if ($top_header_menu) {
		echo 'color:'.$top_header_menu['color'].';';
		echo 'font-size:'.$top_header_menu['size'].';';
		echo 'font-family:'.$top_header_menu['face'].';';
		echo 'font-weight:'.$top_header_menu['style'].';';
		echo 'font-style:'.$top_header_menu['style'].';';
	}
echo '}';

// Modules title (product, portfolio, blog, etc.)
$modules_title = of_get_option('modules_title');

echo 'h2.entry-title.portfolio, h2.entry-title, h2.entry-title a, h2.entry-title.portfolio a, ul.products li.product h3 {';
if ($modules_title) {
		echo 'color:'.$modules_title['color'].';';
		echo 'font-size:'.$modules_title['size'].';';
		echo 'font-family:'.$modules_title['face'].';';
		echo 'font-weight:'.$modules_title['style'].';';
		echo 'font-style:'.$modules_title['style'].';';
	}
echo '}';

// Breadcrumbs
$breadcrumbs_typo = of_get_option('breadcrumbs_typo');

echo '#breadcrumb a, #breadcrumb {';
if ($breadcrumbs_typo) {
		echo 'color:'.$breadcrumbs_typo['color'].';';
		echo 'font-size:'.$breadcrumbs_typo['size'].';';
		echo 'font-family:'.$breadcrumbs_typo['face'].';';
		echo 'font-weight:'.$breadcrumbs_typo['style'].';';
		echo 'font-style:'.$breadcrumbs_typo['style'].';';
	}
echo '}';

// Product page title
$product_page_title = of_get_option('product_page_title');

echo '.div.product .product_title, #content div.product .product_title {';
if ($product_page_title) {
		echo 'color:'.$product_page_title['color'].';';
		echo 'font-size:'.$product_page_title['size'].';';
		echo 'font-family:'.$product_page_title['face'].';';
		echo 'font-weight:'.$product_page_title['style'].';';
		echo 'font-style:'.$product_page_title['style'].';';
	}
echo '}';

// Product page price
$product_page_price = of_get_option('product_page_price');
if ($product_page_price) {
echo 'ul.products li.product .price, .summary .price {';
		echo 'color:'.$product_page_price['color'].';';
		echo 'font-size:'.$product_page_price['size'].';';
		echo 'font-family:'.$product_page_price['face'].';';
		echo 'font-weight:'.$product_page_price['style'].';';
		echo 'font-style:'.$product_page_price['style'].';';
echo '}';

if (($product_page_price['color'] !== '#e07b7b')) {
echo 'span.amount, span.onsale,div.product p.stock, #content div.product p.stock, ul.products li.product .price del .amount, ul.products li.product .price del, .summary .price del, .product_list_widget li del {';
		echo 'color:'.$product_page_price['color'].';';
echo '}';

echo 'span.onsale, .woocommerce_tabs ul.tabs li.active a, .woocommerce-tabs ul.tabs li.active a, .woocommerce_tabs ul.tabs li a:hover, .woocommerce-tabs ul.tabs li a:hover {';
		echo 'color:'.$product_page_price['color'].';';
echo '}';
}
}

// H1 Settings
$h1 = of_get_option('h1_typography');

echo 'h1 {';
if ($h1) {
		echo 'color:'.$h1['color'].';';
		echo 'font-size:'.$h1['size'].';';
		echo 'font-family:'.$h1['face'].';';
		echo 'font-weight:'.$h1['style'].';';
		echo 'font-style:'.$h1['style'].';';
	}
	echo '}';
	
// H2 Settings
$h2 = of_get_option('h2_typography');

echo 'h2 {';
if ($h2) {
		echo 'color:'.$h2['color'].';';
		echo 'font-size:'.$h2['size'].';';
		echo 'font-family:'.$h2['face'].';';
		echo 'font-weight:'.$h2['style'].';';
		echo 'font-style:'.$h2['style'].';';
	}
	echo '}';

// H3 Settings
$h3 = of_get_option('h3_typography');

echo 'h3 {';
if ($h3) {
		echo 'color:'.$h3['color'].';';
		echo 'font-size:'.$h3['size'].';';
		echo 'font-family:'.$h3['face'].';';
		echo 'font-weight:'.$h3['style'].';';
		echo 'font-style:'.$h3['style'].';';
	}
	echo '}';
	
// H4 Settings
$h4 = of_get_option('h4_typography');

echo 'h4 {';
if ($h4) {
		echo 'color:'.$h4['color'].';';
		echo 'font-size:'.$h4['size'].';';
		echo 'font-family:'.$h4['face'].';';
		echo 'font-weight:'.$h4['style'].';';
		echo 'font-style:'.$h4['style'].';';
	}
	echo '}';

// h5 Settings
$h5 = of_get_option('h5_typography');

echo 'h5 {';
if ($h5) {
		echo 'color:'.$h5['color'].';';
		echo 'font-size:'.$h5['size'].';';
		echo 'font-family:'.$h5['face'].';';
		echo 'font-weight:'.$h5['style'].';';
		echo 'font-style:'.$h5['style'].';';
	}
	echo '}';
?>

<?php
	$sidebar_position = of_get_option('page_layout');
	$content_position = ($sidebar_position == "right" ? "left" : "right");
	$sidebar_margin = ($sidebar_position == "right" ? "left" : "right");
?>
.container #content {float:<?php echo $content_position; ?>;}
.container #sidebar {float:<?php echo $sidebar_position; ?>;}

<?php if (of_get_option('general_link_color')) { ?>
a, .addresses a.edit {
    color: <?php echo of_get_option('general_link_color'); ?>;
}
<?php } ?>

<?php if (of_get_option('general_link_hover_color')) { ?>
a:hover, #breadcrumb a:hover, h2.entry-title.portfolio a:hover, h2.entry-title a:hover,
#sidebar ul li.widget_product_categories ul.product-categories li.current-cat, #sidebar ul li.widget_product_categories ul.product-categories li.current-cat a, #sidebar ul li.widget_product_categories ul.product-categories li.current-cat li a:hover {
    color: <?php echo of_get_option('general_link_hover_color'); ?>;
}
.pagination a:hover, .pagination span.current, .nav-previous:hover, .nav-next:hover {
	color: <?php echo of_get_option('general_link_hover_color'); ?>;
}
<?php } ?>

<?php if (of_get_option('footer_link_color')) { ?>
#footer a {
    color: <?php echo of_get_option('footer_link_color'); ?>;
}
<?php } ?>

<?php if (of_get_option('footer_link_hover_color')) { ?>
#footer a:hover, #footer a:focus {
    color: <?php echo of_get_option('footer_link_hover_color'); ?>;
}
<?php } ?>


<?php if (of_get_option('store_catalog_mode')) { ?>
div.product form.cart, #content div.product form.cart {display:none;}
<?php } ?>

<?php if (of_get_option('catalog_grid_desc')) { ?>
ul.products.catalog-page li.product span.excerpt {display:inline;}
<?php } ?>

<?php if (of_get_option('shop_grid_desc')) { ?>
ul.products.shop-page li.product span.excerpt {display:inline;}
<?php } ?>

<?php if (of_get_option('footer_background_color')) { ?>
.footer-wrapper-wide {
	background : <?php echo of_get_option('footer_background_color'); ?>
}
<?php
if (of_get_option('footer_background_color') !== '#383838') { 
$footer_bottom_border = su_hex_shift( of_get_option('footer_background_color'), 'darker', 30 );
?>
#footer li {
	border-color: <?php echo $footer_bottom_border; ?>;
}
#footer #credits .hr-bullet,
#footer .footer-image .hr-bullet {
	background: <?php echo $footer_bottom_border; ?>;
}
<?php } ?>
<?php } ?>

<?php if (of_get_option('menu_background_color')) { ?>
#navigation ul li.active,
#navigation ul li:hover,
#navigation ul li.current-menu-item,
#navigation ul ul {
	background-color : <?php echo of_get_option('menu_background_color'); ?>
}
<?php } ?>

<?php if (of_get_option('submenu_background_color')) { ?>
#navigation ul ul li a {
	background : <?php echo of_get_option('submenu_background_color'); ?>
}
<?php 
if (of_get_option('submenu_background_color') !== '#f3f4f5') {
$submenu_inner_shadow = su_hex_shift( of_get_option('submenu_background_color'), 'lighter', 70 );
$submenu_border = su_hex_shift( of_get_option('submenu_background_color'), 'darker', 30 );
?>
#navigation ul ul li a {
	box-shadow: inset 0 0 0 1px <?php echo $submenu_inner_shadow; ?>;
	-webkit-box-shadow: inset 0 0 0 1px <?php echo $submenu_inner_shadow; ?>;
	-moz-box-shadow: inset 0 0 0 1px <?php echo $submenu_inner_shadow; ?>;
	-o-box-shadow: inset 0 0 0 1px <?php echo $submenu_inner_shadow; ?>;
}

#navigation ul ul li a {
	border-color: <?php echo $submenu_border; ?>;
}
#navigation ul ul{
	border-color: <?php echo $submenu_border; ?>;
    border-top: none;
}
<?php } ?>
<?php } ?>

<?php if (of_get_option('submenu_hover_background_color')) { ?>
#navigation ul ul li a:hover,
#navigation ul ul li.current-menu-item a {
	background-color : <?php echo of_get_option('submenu_hover_background_color'); ?>
}
<?php } ?>

<?php if (of_get_option('bullet_element_color')) { ?>
.hr-bullet:after,
.hr-bullet:before,
.su-divider:after,
.su-divider:before,
.top-menu-header ul li:before,
#navigation ul li:after,
#footer li:before, 
#sidebar li li:before, 
.entry-content .widget_recent_entries ul li:before,
#sidebar ul li.widget_product_categories ul.product-categories li li:before,
.masonry-navigation li:after,
ul.digital-downloads li:before {
	color : <?php echo of_get_option('bullet_element_color'); ?>;
}
<?php } ?>

<?php if (of_get_option('hr_element_color')) { ?>
.hr-bullet,
.su-divider {
	background : <?php echo of_get_option('hr_element_color'); ?>;
}
.mi-slider nav,
.mi-slider nav a.mi-selected:after {
	border-color: <?php echo of_get_option('hr_element_color'); ?>;
}
<?php } ?>

<?php if (of_get_option('navigation_elements_background')) { ?>
.flex-direction-nav a,
#homepage-style-default.with-sidebar .flex-direction-nav .flex-prev, 
#homepage-style-default.with-sidebar .flex-direction-nav .flex-next {
	background : <?php echo of_get_option('navigation_elements_background'); ?>
}
<?php  if (of_get_option('navigation_elements_background') !== '#dee7eb'){
$navigation_border = su_hex_shift( of_get_option('navigation_elements_background'), 'darker', 40 );
?>
.flex-direction-nav a,
#homepage-style-default.with-sidebar .flex-direction-nav .flex-prev, 
#homepage-style-default.with-sidebar .flex-direction-nav .flex-next {
	border-color: <?php echo $navigation_border; ?>
}
<?php } } ?>

<?php if (of_get_option('buttons_background')) { ?>
a.button, 
button.button, 
input.button, 
#respond input#submit, 
#content input.button,
button, 
input[type="reset"], 
input[type="submit"],
input[type="button"],
.quantity .plus, 
#content .quantity .plus, 
.quantity .minus, 
#content .quantity .minus,
.su-fancy-link, 
.more-link, 
a.jms-link,
#header .header_mini_cart ul.mini-cart li ul.cart_list li.buttons .button,
#sidebar ul li.widget_shopping_cart p.buttons .button,
.ei-slider-thumbs li.ei-slider-element {
	background-color : <?php echo of_get_option('buttons_background'); ?>
}
<?php

if (of_get_option('buttons_background') !== '#dee7eb') { 
$button_box_shadow = su_hex_shift( of_get_option('buttons_background'), 'lighter', 30 );
$button_border = su_hex_shift( of_get_option('buttons_background'), 'darker', 20 );
$button_border_hover = su_hex_shift( of_get_option('buttons_background'), 'darker', 60 );
?>

a.button, 
button.button, 
input.button, 
#respond input#submit, 
#content input.button,
button, 
input[type="reset"], 
input[type="submit"],
input[type="button"],
.quantity .plus, 
#content .quantity .plus, 
.quantity .minus, 
#content .quantity .minus,
.su-fancy-link, 
.more-link,
a.jms-link,
#header .header_mini_cart ul.mini-cart li ul.cart_list li.buttons .button,
#sidebar ul li.widget_shopping_cart p.buttons .button {
	-moz-box-shadow: inset 0 3px 0 <?php echo $button_box_shadow; ?>;
	-webkit-box-shadow: inset 0 3px 0 <?php echo $button_box_shadow; ?>;
	box-shadow: inset 0 3px 0 <?php echo $button_box_shadow; ?>;
    border-color: <?php echo $button_border; ?>;
    
    color: <?php echo $button_box_shadow; ?>;
    text-shadow: 1px 1px 0 <?php echo $button_border; ?>;
}

.single_add_to_cart_button:before {
	border-color: <?php echo $button_border; ?>;
}

a.button:hover, 
button.button:hover, 
input.button:hover, 
#respond input#submit:hover, 
#content input.button:hover,
button:hover, 
input[type="reset"]:hover, 
input[type="submit"]:hover,
input[type="button"]:hover,
.quantity .plus:hover, 
#content .quantity .plus:hover, 
.quantity .minus:hover, 
#content .quantity .minus:hover,
.su-fancy-link:hover, 
.more-link:hover,
a.jms-link:hover,
#header .header_mini_cart ul.mini-cart li ul.cart_list li.buttons .button,
#sidebar ul li.widget_shopping_cart p.buttons .button {
    border-color: <?php echo $button_border_hover; ?>;
    color: <?php echo $button_box_shadow; ?> !important;
    text-shadow: 1px 1px 0 <?php echo $button_border; ?>;
}
<?php } ?>
<?php } ?>


<?php if (of_get_option('header_search_input_background')) { ?>
#header .header_form .main-search:before {
	background-color : <?php echo of_get_option('header_search_input_background'); ?>
}
<?php } ?>

<?php if (of_get_option('header_minicart_background')) { ?>
#header .header_mini_cart ul.mini-cart li a.cart-parent:before,
#header .header_mini_cart ul li ul.cart_list {
	background-color : <?php echo of_get_option('header_minicart_background'); ?>
}
<?php } ?>

<?php if (of_get_option('header_minicart_list_background')) { ?>
#header .header_mini_cart ul.mini-cart li ul.cart_list li.cart_list_product,
#header .header_mini_cart ul.mini-cart li ul.cart_list li.buttons {
	background-color : <?php echo of_get_option('header_minicart_list_background'); ?>
}
<?php
if (of_get_option('header_minicart_list_background') !== '#f3f4f5') { 
$minicart_inner_shadow = su_hex_shift( of_get_option('header_minicart_list_background'), 'lighter', 70 );
$minicart_border = su_hex_shift( of_get_option('header_minicart_list_background'), 'darker', 30 );
?>
#header .header_mini_cart ul.mini-cart li ul.cart_list li.cart_list_product {
	box-shadow: inset 0 0 0 1px <?php echo $minicart_inner_shadow; ?>;
	-webkit-box-shadow: inset 0 0 0 1px <?php echo $minicart_inner_shadow; ?>;
	-moz-box-shadow: inset 0 0 0 1px <?php echo $minicart_inner_shadow; ?>;
	-o-box-shadow: inset 0 0 0 1px <?php echo $minicart_inner_shadow; ?>;
}
#header .header_mini_cart ul.mini-cart li ul.cart_list li {
	border-color: <?php echo $minicart_border; ?>;
}
#header .header_mini_cart ul li ul.cart_list {
	border-color: <?php echo $minicart_border; ?>;
    border-top: none;
}
ul.cart_list li img, ul.product_list_widget li img {
	border-color: <?php echo $minicart_border; ?>;
}
<?php } ?>
<?php } ?>

<?php 
if (of_get_option('general_border_color')) { 
if (of_get_option('general_border_color') !== '#ededed') { 
?>
.custom-wrapper li,
ul.products li.product,
div.product div.images, 
#content div.product div.images,
.custom-wrapper .entry-lightbox a,
.pagination, .navigation,
.post,
span.onsale,
.custom-headline {
	border-color: <?php echo of_get_option('general_border_color'); ?>;
}
.custom-headline h1 { background:none;}
.grid-switch a {color:<?php echo of_get_option('general_border_color'); ?>;}
<?php }} ?>

<?php 
if (of_get_option('general_hover_border_color')) { 
if (of_get_option('general_hover_border_color') !== '#cedbe1') { 
?>
.custom-wrapper li:hover,
ul.products li.product:hover,
.custom-wrapper .entry-lightbox a:hover,
div.pagination:hover,
.post:hover  {
	border-color: <?php echo of_get_option('general_hover_border_color'); ?>;
}
.grid-switch a:hover, .grid-switch a.active {
	color:<?php echo of_get_option('general_hover_border_color'); ?>;
}
<?php }} ?>

<?php 
if (of_get_option('sidebar_background_color')) {
if (of_get_option('sidebar_background_color') !== '#e0e8ed') {
$sidebar_count = su_hex_shift( of_get_option('sidebar_background_color'), 'darker', 30 );	
$filter_light = su_hex_shift( of_get_option('sidebar_background_color'), 'lighter', 50 );	
?>
#sidebar ul li.widget_product_categories, 
#sidebar ul li.widget_shopping_cart,
ul.portfolio-meta,
.widget_price_filter .ui-slider .ui-slider-range {
	background: <?php echo of_get_option('sidebar_background_color'); ?>;
}

#sidebar ul li.widget_product_categories ul.product-categories li .count {
	color: <?php echo $sidebar_count; ?>;
}
.widget_price_filter .price_slider_wrapper .ui-widget-content {
	background: <?php echo $filter_light; ?>;
}
<?php }} ?>

<?php if (of_get_option('sidebar_list_background_color')) { ?>
#sidebar ul li.widget_product_categories ul.product-categories li,
#sidebar ul li.widget_shopping_cart ul.cart_list li,
ul.portfolio-meta li,
ul.portfolio-meta li.project-date,
ul.portfolio-meta li.project-category,
ul.portfolio-meta li.project-url,
#questions,
.current-faq {
	background-color: <?php echo of_get_option('sidebar_list_background_color'); ?>;
}
<?php
if (of_get_option('sidebar_list_background_color') !== '#f3f4f5') { 
$sidebar_list_inner_shadow = su_hex_shift( of_get_option('sidebar_list_background_color'), 'lighter', 70 );
$sidebar_list_border = su_hex_shift( of_get_option('sidebar_list_background_color'), 'darker', 30 );
?>
#sidebar ul li.widget_product_categories ul.product-categories li, 
#sidebar ul li.widget_shopping_cart ul.cart_list li,
ul.portfolio-meta li,
ul.portfolio-meta li.project-date,
ul.portfolio-meta li.project-category,
ul.portfolio-meta li.project-url,
#questions,
.current-faq {
	box-shadow: inset 0 0 0 1px <?php echo $sidebar_list_inner_shadow; ?>;
	-webkit-box-shadow: inset 0 0 0 1px <?php echo $sidebar_list_inner_shadow; ?>;
	-moz-box-shadow: inset 0 0 0 1px <?php echo $sidebar_list_inner_shadow; ?>;
	-o-box-shadow: inset 0 0 0 1px <?php echo $sidebar_list_inner_shadow; ?>;
    border-top: 1px solid <?php echo $sidebar_list_border; ?>;
}

#sidebar ul li.widget_product_categories, 
#sidebar ul li.widget_shopping_cart,
ul.portfolio-meta,
#questions,
.current-faq {
	border-color: <?php echo $sidebar_list_border; ?>;
}
ul.portfolio-meta {border-top:0;}
<?php } ?>
<?php } ?>

<?php if (of_get_option('form_elements_border_color')) { ?>
textarea, 
select, 
input[type="date"], 
input[type="datetime"], 
input[type="datetime-local"], 
input[type="email"], 
input[type="month"], 
input[type="number"], 
input[type="password"], 
input[type="search"], 
input[type="tel"], 
input[type="text"], 
input[type="input"], 
input[type="time"], 
input[type="url"], 
input[type="week"],
#header .header_mini_cart ul.mini-cart li a.cart-parent,
.quantity input.qty, 
#content .quantity input.qty {
    border-color: <?php echo of_get_option('form_elements_border_color'); ?>;
}
<?php } ?>

<?php if (of_get_option('form_elements_border_color')) { ?>
#header .header_mini_cart ul.mini-cart li a.cart-parent:before,
#header .header_form .main-search:before {
	border-color: <?php echo of_get_option('form_elements_border_color'); ?>;
}
<?php } ?>

<?php if (of_get_option('product_tab_border_top')) { ?>
.woocommerce_tabs ul.tabs li.active:before {
    background: <?php echo of_get_option('product_tab_border_top'); ?>;
    border-color: <?php echo of_get_option('product_tab_border_top'); ?>;
}
<?php } ?>
