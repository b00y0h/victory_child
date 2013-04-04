<?php
// Disable WooCommerce styles 
define('WOOCOMMERCE_USE_CSS', false);

/*-----------------------------------------------------------------------------------*/
/* Hook in on activation */
/*-----------------------------------------------------------------------------------*/
 
global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) add_action('init', 'cumico_woocommerce_image_dimensions', 1);
 
/*-----------------------------------------------------------------------------------*/
/* Define image sizes / hard crop */
/*-----------------------------------------------------------------------------------*/
 
function cumico_woocommerce_image_dimensions() {
// Image sizes < 1.6
update_option( 'woocommerce_thumbnail_image_width', '90' ); // Image gallery thumbs
update_option( 'woocommerce_thumbnail_image_height', '90' );
update_option( 'woocommerce_single_image_width', '420' ); // Featured product image
update_option( 'woocommerce_single_image_height', '888' ); 
update_option( 'woocommerce_catalog_image_width', '180' ); // Product category thumbs
update_option( 'woocommerce_catalog_image_height', '888' );

// Hard Crop [0 = false, 1 = true] <1.6
update_option( 'woocommerce_thumbnail_image_crop', 1 );
update_option( 'woocommerce_single_image_crop', 0 ); 
update_option( 'woocommerce_catalog_image_crop', 0 );

// Image sizes > 1.6
update_option( 'shop_thumbnail_image_size', array('width' => '90', 'height' => '90', 'crop' => true) ); // Image gallery thumbs
update_option( 'shop_single_image_size', array('width' => '420', 'height' => '', 'crop' => true) ); // Featured product image
update_option( 'shop_catalog_image_size', array('width' => '180', 'height' => '', 'crop' => true) ); // Product category thumbs
}

// Adjust markup on all woocommerce pages
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action( 'woocommerce_before_main_content', 'woocommerce_wrapper', 10);
add_action( 'woocommerce_after_main_content', 'woocommerce_close_wrapper', 10); 

// Reset woocommerce wrapper
if (!function_exists('woocommerce_wrapper')) {function woocommerce_wrapper(){echo '';}}
if (!function_exists('woocommerce_close_wrapper')) {function woocommerce_close_wrapper(){echo '';}}

// List or grid View
if ( is_shop() || is_product_category() || is_product_tag() ) {
add_action( 'wp_footer', 'gridlist_set_default_view' );
function gridlist_set_default_view() {
	$default = of_get_option( 'shop_grid_list_default' );
	?>
		<script>
			jQuery(document).ready(function() {
				if (jQuery.cookie('gridcookie') == null) {
					jQuery('ul.products').addClass('<?php echo $default; ?>');
					jQuery('.gridlist-toggle #<?php echo $default; ?>').addClass('active');
				}
			});
		</script>
	<?php
}
}


// Handle cart in header fragment for ajax add to cart
add_filter('add_to_cart_fragments', 'woocommerceframework_header_add_to_cart_fragment');
function woocommerceframework_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	ob_start();
	?>

	<ul class="mini-cart">
		<li>
			<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>" class="cart-parent">
			<span> 
			<?php 
			echo sprintf(_n('<mark class="total-items">%d product</mark>', '<mark class="total-items">%d products</mark>', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);
			echo $woocommerce->cart->get_cart_total();
			?>
			</span>
			</a>
			<?php
	
	        echo '<ul class="cart_list">';
	        echo '<li class="cart-title"><h4>'.__('Your Cart Contents', 'okthemes').'</h4></li>';
	           if (sizeof($woocommerce->cart->cart_contents)>0) : foreach ($woocommerce->cart->cart_contents as $cart_item_key => $cart_item) :
		           $_product = $cart_item['data'];
		           if ($_product->exists() && $cart_item['quantity']>0) :
		               echo '<li class="cart_list_product"><a href="'.get_permalink($cart_item['product_id']).'">';
		               
		               if (has_post_thumbnail($cart_item['product_id'])) echo get_the_post_thumbnail($cart_item['product_id'], 'shop_thumbnail'); 
		               else echo '<img src="'.$woocommerce->plugin_url(). '/assets/images/placeholder.png" alt="Placeholder" width="'.$woocommerce->get_image_size('shop_thumbnail_image_width').'" height="'.$woocommerce->get_image_size('shop_thumbnail_image_height').'" />'; 
		               
		               echo apply_filters('woocommerce_cart_widget_product_title', $_product->get_title(), $_product).'</a>';
		               
		               if($_product instanceof woocommerce_product_variation && is_array($cart_item['variation'])) :
		                   echo woocommerce_get_formatted_variation( $cart_item['variation'] );
		                 endif;
		               
		               echo '<span class="quantity">' .$cart_item['quantity'].' &times; '.woocommerce_price($_product->get_price()).'</span></li>';
		           endif;
		       endforeach;
	
	        	else: echo '<li class="empty">'.__('No products in the cart.','woothemes').'</li>'; endif;
	        	if (sizeof($woocommerce->cart->cart_contents)>0) :
	            echo '<li class="total"><strong>';
	
	            if (get_option('js_prices_include_tax')=='yes') :
	                _e('Total', 'woothemes');
	            else :
	                _e('Subtotal', 'woothemes');
	            endif;

	            echo ':</strong>'.$woocommerce->cart->get_cart_total();'</li>';
	
	            echo '<li class="buttons"><a href="'.$woocommerce->cart->get_cart_url().'" class="button">'.__('View Cart &rarr;','woothemes').'</a> <a href="'.$woocommerce->cart->get_checkout_url().'" class="button checkout">'.__('Checkout &rarr;','woothemes').'</a></li>';
	        endif;
	        
	        echo '</ul>';
	
	    ?>
		</li>
	</ul>
	<?php
	$fragments['ul.mini-cart'] = ob_get_clean();
	return $fragments;
}

//Add custom pagination
remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
function woocommerce_pagination() {
		pagination(); 		
	}
add_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10);

//Add search fragment
function woocommerceframework_add_search_fragment ( $settings ) {
	$settings['add_fragment'] = '?post_type=product';
	return $settings;
} // End woocommerceframework_add_search_fragment()

// Move the product data tabs
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
add_action( 'woocommerce_after_single_product', 'woocommerce_output_product_data_tabs', 10);


// Add the excerpt to product archives
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_product_excerpt', 35, 2);
if (!function_exists('woocommerce_product_excerpt')) {
	function woocommerce_product_excerpt() {
		echo '<div class="excerpt">';
		the_excerpt();
		echo '</div>';
	}
}

// Products per page.
add_filter('loop_shop_per_page',  'show_products_per_page');
function show_products_per_page() {
	$product_per_page = of_get_option('product_per_page');
    return $product_per_page;
}

/**
 * Display min max price
 **/
add_filter('woocommerce_variable_price_html', 'custom_variation_price', 10, 2);
 
function custom_variation_price( $price, $product ) {
     
     $price = '';
 
     if ( !$product->min_variation_price || $product->min_variation_price !== $product->max_variation_price ) $price .= '<span class="from">' . _x('From', 'min_price', 'woocommerce') . ' </span>';
			
     $price .= woocommerce_price($product->get_price());
			
     if ( $product->max_variation_price && $product->max_variation_price !== $product->min_variation_price ) {
          $price .= '<span class="to"> ' . _x('to', 'max_price', 'woocommerce') . ' </span>';
 
          $price .= woocommerce_price($product->max_variation_price);
     }
 
     return $price;
}

/**
 * Catalog mode functions
 **/

if (of_get_option('store_catalog_mode')) {
	// Remove add to cart button from the product loop
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10, 2);
	 
	// Remove add to cart button from the product details page
	remove_action( 'woocommerce_before_add_to_cart_form', 'woocommerce_template_single_product_add_to_cart', 10, 2);
	 
	//disabled actions (add to cart, checkout and pay)
	remove_action( 'init', 'woocommerce_add_to_cart_action', 10);
	remove_action( 'init', 'woocommerce_checkout_action', 10 );
	remove_action( 'init', 'woocommerce_pay_action', 10 );
}

/**
 * Remove breadcrumbs
 **/
if (!of_get_option('store_breadcrumbs')) {
	remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);
}
if ( ! function_exists( 'woocommerce_breadcrumb' ) ) {

/**
 * Output the WooCommerce Breadcrumb
 *
 * @access public
 * @return void
 */
function woocommerce_breadcrumb( $args = array() ) {

    $defaults = array(
        'delimiter'  => ' &rsaquo; ',
        'wrap_before'  => '<div id="breadcrumb">',
        'wrap_after' => '</div>',
        'before'   => '',
        'after'   => '',
        'home'    => null
    );

    $args = wp_parse_args( $args, $defaults  );

    woocommerce_get_template( 'shop/breadcrumb.php', $args );
}
}

/**
 * Create product categories mega menu
 **/
 
add_filter( 'walker_nav_menu_start_el', 'woo_mega_dropdown', 10, 4 );
function woo_mega_dropdown( $item_output, $item, $depth, $args ) {
	// The mega dropdown options
	$mega_dropdown_ad = of_get_option('mega_dropdown_ad');
	$mega_dropdown_ad_link = of_get_option('mega_dropdown_ad_link');
	$mega_dropdown_ad_desc = of_get_option('mega_dropdown_ad_desc');
	
	// The mega dropdown only applies to the main navigation.
	if ( 'primary' !== $args->theme_location )
		return $item_output;

	// The mega dropdown needs to be added to one specific menu item.
	if ( in_array( 'mega-dropdown', $item->classes ) ) {
		
		//Begin code for product categories
		$taxonomy     = 'product_cat';
		$orderby      = 'name';
		$order	      = 'desc';
		$show_count   = 1;      // 1 for yes, 0 for no
		$pad_counts   = 1;      // 1 for yes, 0 for no
		$hierarchical = 1;      // 1 for yes, 0 for no
		$title        = '';
		$empty        = 0;

		$args = array(
		  'taxonomy'     => $taxonomy,
		  'orderby'      => $orderby,
		  'order'	     => $order,
		  'show_count'   => $show_count,
		  'pad_counts'   => $pad_counts,
		  'hierarchical' => $hierarchical,
		  'parent' => 0,
		  'title_li'     => $title,
		  'hide_empty'   => $empty
		);
		$all_categories = get_categories( $args );
		
		$item_output .= '<ul class="mega-dropdown submenu">';

		$cols = 4; // Change to columns needed.
		$catcount = count($all_categories);
		$catpercol = ceil($catcount / $cols);
		$c = 0;
		
		// Open the first li
		$item_output .= "<li class='menu-columns'><div class='master-column'>";

		foreach ($all_categories as $cat) { 
				$category_id = $cat->term_id;
				$args2 = array(
				  'taxonomy'     => $taxonomy,
				  //'child_of'     => $category_id,
				  'parent'       => $category_id,
				  'orderby'      => $orderby,
				  'order'	     => $order,
				  'show_count'   => $show_count,
				  'pad_counts'   => $pad_counts,
				  'hierarchical' => $hierarchical,
				  'title_li'     => $title,
				  'hide_empty'   => $empty
				);
				$sub_cats = get_categories( $args2 );

				if ( $c == $catpercol ) {
					$c = 0;
					$item_output .=  "</div></li><li class='menu-columns'><div class='master-column'>";
				  }
				
				if($sub_cats)
					$item_output .= '<div class="with-subcats"><a class="mega-list-title" href="'. get_term_link($cat->slug, 'product_cat') .'"><strong>'. $cat->name .'</strong></a>';
				else
					$item_output .= '<div class="no-subcats"><a class="mega-list-title" href="'. get_term_link($cat->slug, 'product_cat') .'"><strong>'. $cat->name .'</strong></a>';
								
					if($sub_cats) {
						$item_output .= '<div>';
						foreach($sub_cats as $sub_category) {
							$item_output .= '<span><a href="'. get_term_link($sub_category->slug, 'product_cat') .'">'. $sub_category->name .'</a></span>';
						}
						$item_output .= '</div>';
					}
				$item_output .= '</div>';
				$c++;		
		}
		//End code for product categories
		// Close the last li
		$item_output .= "</div></li>";
		
			if ($mega_dropdown_ad) {
				$item_output .= '<li class="mega-ad-placeholder">';
					if ($mega_dropdown_ad_link) $item_output .= '<a href=" '.$mega_dropdown_ad_link.' ">';
					$item_output .= '<img src=" '.$mega_dropdown_ad.' " alt=" '.$mega_dropdown_ad_desc.' " />';
					if ($mega_dropdown_ad_link) $item_output .= '</a>';
				$item_output .= '</li>';
			}
			
		
		$item_output .= '</ul>'; 
		
	}

	return $item_output;
}

/**
 * Minicart function
 **/
if (!function_exists('header_mini_cart')) { 
function header_mini_cart() { global $woocommerce; ?>
<div class="header_mini_cart">
<ul class="mini-cart">
    <li>
        <a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'okthemes'); ?>" class="cart-parent">
            <span> 
            <?php
			echo sprintf(_n('<mark class="total-items">%d product</mark>', '<mark class="total-items">%d products</mark>', $woocommerce->cart->cart_contents_count, 'okthemes'), $woocommerce->cart->cart_contents_count); 
            echo $woocommerce->cart->get_cart_total();
            ?>
            </span>
        </a>
        <?php
            
            echo '<ul class="cart_list">';
            echo '<li class="cart-title"><h4>'.__('Your Cart Contents', 'okthemes').'</h4></li>';
               if (sizeof($woocommerce->cart->cart_contents)>0) : foreach ($woocommerce->cart->cart_contents as $cart_item_key => $cart_item) :
                   $_product = $cart_item['data'];
                   if ($_product->exists() && $cart_item['quantity']>0) :
                       echo '<li class="cart_list_product"><a href="'.get_permalink($cart_item['product_id']).'">';
                       
                       if (has_post_thumbnail($cart_item['product_id'])) echo get_the_post_thumbnail($cart_item['product_id'], 'shop_thumbnail'); 
                       else echo '<img src="'.$woocommerce->plugin_url(). '/assets/images/placeholder.png" alt="Placeholder" width="'.$woocommerce->get_image_size('shop_thumbnail_image_width').'" height="'.$woocommerce->get_image_size('shop_thumbnail_image_height').'" />'; 
                       
                       echo apply_filters('woocommerce_cart_widget_product_title', $_product->get_title(), $_product).'</a>';
                       
                       if($_product instanceof woocommerce_product_variation && is_array($cart_item['variation'])) :
                           echo woocommerce_get_formatted_variation( $cart_item['variation'] );
                         endif;
                       
                       echo '<span class="quantity">' .$cart_item['quantity'].' &times; '.woocommerce_price($_product->get_price()).'</span></li>';
                   endif;
               endforeach;

                else: echo '<li class="empty">'.__('No products in the cart.','okthemes').'</li>'; endif;
                if (sizeof($woocommerce->cart->cart_contents)>0) :
                echo '<li class="total"><strong>';

                if (get_option('js_prices_include_tax')=='yes') :
                    _e('Total', 'okthemes');
                else :
                    _e('Subtotal', 'okthemes');
                endif;
                echo '</strong>'.$woocommerce->cart->get_cart_total();'</li>';

                echo '<li class="buttons"><a href="'.$woocommerce->cart->get_cart_url().'" class="button">'.__('View Cart &rarr;','okthemes').'</a> <a href="'.$woocommerce->cart->get_checkout_url().'" class="button checkout">'.__('Checkout &rarr;','okthemes').'</a></li>';
            endif;
            echo '</ul>';
        ?>
    </li>
</ul>
</div>

<?php } 

}

/**
 * Cloud zoom function
 **/
if (of_get_option('product_cloud_zoom')) {
add_action('wp_footer', 'cloud_zoom_feature',30);
function cloud_zoom_feature(){
    if(is_product()){
?>
        <script type="text/javascript">
        jQuery(document).ready(function($){
            $('a.zoom').unbind('click');
            $thumbnailsContainer = $('.product .thumbnails');
            $thumbnails = $('a', $thumbnailsContainer);
            $productImages = $('.product .images > a');
            addCloudZoom = function(onWhat){
                onWhat.addClass('cloud-zoom').attr('data', "zoomWidth:'auto',zoomHeight: 'auto',position:'right',adjustX:40,adjustY:0,tint:false,tintOpacity:0.5,lensOpacity:0.5,softFocus:false,smoothMove:3,showTitle:false,titleOpacity:0.5").CloudZoom();
            }
			
            if($thumbnails.length){
                $thumbnails.bind('click',function(){
                    $image = $(this).clone(false).fadeTo(1500, 1.0);
                    $image.insertAfter($productImages);
                    $productImages.remove();
                    $productImages = $image;
                    $('.mousetrap').remove();
                    addCloudZoom($productImages);
                    return false;

                })

            }
            addCloudZoom($productImages);
			
        });
        </script>



<?php
    }
}

}

function cloud_zoom_catalog_thumbnail(){
    $return = 'shop_single';
    return $return;
}
add_filter( 'single_product_small_thumbnail_size', 'cloud_zoom_catalog_thumbnail',10,2 ) ;


//Enable/Disable Sale Flash
if ( !of_get_option('store_sale_flash') ) {
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
}
else {
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
	add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 10);
}

//Enable/Disable Products price
if ( !of_get_option('store_products_price') ) {
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
}
else {
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
	add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 10);
}

//Enable/Disable Add to cart
if ( !of_get_option('store_add_to_cart') ) {
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart',10);
}
else {
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart',10);
	add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart',10);
}


if ( !of_get_option('product_sale_flash') )
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);

if ( !of_get_option('product_products_price') )
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);

if ( !of_get_option('product_products_excerpt') )
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);

if ( !of_get_option('product_add_to_cart') )
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);

if ( !of_get_option('product_products_meta') )
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

//Enable/Disable Related products
if ( !of_get_option('product_related_products') ) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products' );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
} else {
	// Change columns in related products output to 4 and move below the product summary 
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
	add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 20);
	if (!function_exists('woocommerce_output_related_products')) {
		function woocommerce_output_related_products() {
			woocommerce_related_products(4,4); 
		}
	}
}
//Enable/Disable Up Sells products
if ( !of_get_option('product_upsells_products') ) {
	remove_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display' );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	remove_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display', 20 );
} else {
	// Change columns in upsells output to 3 and move below the product summary
	remove_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display');
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	add_action( 'woocommerce_after_single_product', 'woocommerceframework_upsell_display', 20);
	
	if (!function_exists('woocommerceframework_upsell_display')) {
		function woocommerceframework_upsell_display() {
			woocommerce_upsell_display(4,4);
		}
	}
}
//Enable/Disable Cross Sells products
if ( !of_get_option('product_crosssells_products') ) {
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 20 );
}

//Enable/Disable Review tab
if ( !of_get_option('product_reviews_tab') ) {
remove_action( 'woocommerce_product_tabs', 'woocommerce_product_reviews_tab', 30);
remove_action( 'woocommerce_product_tab_panels', 'woocommerce_product_reviews_panel', 30);
}

//Enable/Disable Description tab
if ( !of_get_option('product_description_tab') ) {
remove_action( 'woocommerce_product_tabs', 'woocommerce_product_description_tab', 10);
remove_action( 'woocommerce_product_tab_panels', 'woocommerce_product_description_panel', 10);
}

//Enable/Disable Attributes tab
if ( !of_get_option('product_attributes_tab') ) {
remove_action( 'woocommerce_product_tabs', 'woocommerce_product_attributes_tab', 20);
remove_action( 'woocommerce_product_tab_panels', 'woocommerce_product_attributes_panel', 20);
}
?>