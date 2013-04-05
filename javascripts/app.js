/* 
* cumico V1.0.3
* Copyright 2011, Dave Gamache
* www.getcumico.com
* Free to use under the MIT license.
* http://www.opensource.org/licenses/mit-license.php
* 7/17/2011
*/	

jQuery(document).ready(function($) {
	
			/* MyAccount page - make div 100% for Login/Register
	================================================== */
	
	if( !$.trim( $('.woocommerce-account #sidebar ul').html() ).length ) {
		$('.woocommerce-account .container #sidebar').hide();
		$('.woocommerce-account .container #content').css({'width' : '100%'});
	}

		/* Dropdown Menu
	================================================== */
	$( '#navigation ul, .header_mini_cart ul' ).on( 'mouseenter', ' > li', function(e) {
		$( this ).children('ul').hide().stop( true, true ).slideDown( { easing: 'easeOutExpo', duration: 'fast' } );
		e.stopPropagation();
	}).on( 'mouseleave', ' > li', function(e) {
		$( this ).children('ul').stop( true, true ).slideUp( { easing: 'easeOutExpo', duration: 'fast' } );
	});
	
		/* Style sorting select input
	================================================== */
	
	if (!$.browser.opera) {

        $('.woocommerce-ordering select').each(function(){
            var title = $(this).attr('title');
            if( $('option:selected', this).val() != ''  ) title = $('option:selected',this).text();
            $(this)
                .css({'z-index':10,'opacity':0,'-khtml-appearance':'none'})
                .after('<span class="select">' + title + '</span>')
                .change(function(){
                    val = $('option:selected',this).text();
                    $(this).next().text(val);
                    })
        });

    };
	
		/* Images hover effect
	================================================== */
	
	$('.portfolio-wrapper li img, .products li img').hover(
	function(){
		$(this).stop().fadeTo('slow',0.4);
	},
	function(){
		$(this).stop().fadeTo('slow',1);
	});
	
		/* FitVids
	================================================== */
	$(".container").fitVids();
	
		/* Clear search input
	================================================== */
	// Save the initial values of the inputs as placeholder text
	$('#header .header_form .main-search input#s').attr("data-placeholdertext", function() {
	return this.value;
	});
	
	// Hook up a handler to delete the placeholder text on focus,
	// and put it back on blur
	$('#header .header_form .main-search')
	.delegate('input', 'focus', function() {
	  if (this.value === $(this).attr("data-placeholdertext")) {
		this.value = '';
	  }
	})
	.delegate('input', 'blur', function() {
	  if (this.value.length == 0) {
		this.value = $(this).attr("data-placeholdertext");
	  }
	});
	
		/* prettyPhoto
	================================================== */
	$("a[data-rel^='prettyPhoto']").prettyPhoto({
			hook: 'data-rel',
			theme: 'pp_default', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
			social_tools: '<div class="pp_social"><div class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div><div class="facebook"><iframe src="http://www.facebook.com/plugins/like.php?locale=en_US&href='+location.href+'&amp;layout=button_count&amp;show_faces=true&amp;width=500&amp;action=like&amp;font&amp;colorscheme=light&amp;height=25" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:500px; height:25px;" allowTransparency="true"></iframe></div></div>'
		});
	
		/* Responsive menu
	================================================== */
		
	$('.menu-header .menu').mobileMenu({
		defaultText: 'Navigate to...',
		className: 'select-menu',
		subMenuDash: '&ndash;'
	});	 
	
	// Style Tags
	$(function(){ // run after page loads
		$('p.tags a')
		.wrap('<span class="st_tag" />');
	});
	
	// valid XHTML method of target_blank
	$(function(){ // run after page loads
		$('a[rel*=external]').click( function() {
			window.open(this.href);
			return false;
		});
	});
});