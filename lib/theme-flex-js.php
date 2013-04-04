<?php
add_action( 'wp_footer', 'flexslider_scripts' );

function flexslider_scripts() {

	?>
		<script type="text/javascript">
			jQuery(window).load(function() {
				<?php if (is_home() && of_get_option('slideshow_select') == 'flexslider') { ?>
				//Main Slider
				jQuery('.flexslider.slideshow').flexslider({
					directionNav: <?php echo of_get_option('flexslider_navigation'); ?>,
					controlNav: false,
					animation: "<?php echo of_get_option('flexslider_slide_effect'); ?>", //fade, slide
					slideshow: <?php echo of_get_option('flexslider_auto_animate'); ?>, //Boolean: Animate slider automatically
					slideshowSpeed: <?php echo of_get_option('flexslider_auto_animate_speed'); ?>,
					start: function(slider) {
							slider.removeClass('loading');
					}
				});
				
				//Featured Products Slider
				<?php if (of_get_option('featured_products_carousel')== 'yes') { ?>
				jQuery(".products-wrapper.flexslider.carousel.uniq-featured-products").flexslider({
					animation: "slide",
					move:1, 
					selector: ".products > li", 
					itemWidth: 220,
					itemMargin: 20,
					controlNav: false,
					slideshow: <?php echo of_get_option('featured_products_carousel_autoplay'); ?>
				});
				<?php } ?>
				
				//Recent Products Slider
				<?php if (of_get_option('recent_products_carousel') == 'yes') { ?>
				jQuery(".products-wrapper.flexslider.carousel.uniq-recent-products").flexslider({
					animation: "slide",
					move:1, 
					selector: ".products > li", 
					itemWidth: 220,
					itemMargin: 20,
					controlNav: false,
					slideshow: <?php echo of_get_option('recent_products_carousel_autoplay'); ?>
				});
				<?php } ?>
				
				//Products by ID Slider
				<?php if (of_get_option('products_by_id_carousel') == 'yes') { ?>
				jQuery(".products-wrapper.flexslider.carousel.uniq-products-by-id").flexslider({
					animation: "slide",
					move:1, 
					selector: ".products > li", 
					itemWidth: 220,
					itemMargin: 20,
					controlNav: false,
					slideshow: <?php echo of_get_option('products_by_id_carousel_autoplay'); ?>
				});
				<?php } ?>
				
				//Products category Slider
				<?php if (of_get_option('products_category_carousel' )== 'yes') { ?>
				jQuery(".products-wrapper.flexslider.carousel.uniq-products-category").flexslider({
					animation: "slide",
					move:1, 
					selector: ".products > li", 
					itemWidth: 220,
					itemMargin: 20,
					controlNav: false,
					slideshow: <?php echo of_get_option('products_category_carousel_autoplay'); ?>
				});
				<?php } ?>
				
				//Products by category slug Slider
				<?php if (of_get_option('products_by_category_slug_carousel') == 'yes') { ?>
				jQuery(".products-wrapper.flexslider.carousel.uniq-products-by-cat-slug").flexslider({
					animation: "slide",
					move:1, 
					selector: ".products > li", 
					itemWidth: 220,
					itemMargin: 20,
					controlNav: false,
					slideshow: <?php echo of_get_option('products_by_category_slug_carousel_autoplay'); ?>
				});
				<?php } ?>

				<?php } ?>
			});
		</script>
	<?php
}

?>