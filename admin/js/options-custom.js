/**
 * Prints out the inline javascript needed for the colorpicker and choosing
 * the tabs in the panel.
 */

jQuery(document).ready(function($) {
	
	// Sorter (Layout Manager) 
	jQuery('.sorter').each( function() {
	var id = jQuery(this).attr('id');
		$('#'+ id).find('ul').sortable({
			items: 'li',
			placeholder: "placeholder",
			connectWith: '.sortlist_' + id,
			opacity: 0.6,
			update: function() {
				$(this).find('.position').each( function() {
				var listID = $(this).parent().attr('id');
				var parentID = $(this).parent().parent().attr('id');
				parentID = parentID.replace(id + '_', '');
				var optionID = $(this).parent().parent().parent().attr('id');
				var nameTheme = $(this).attr('class');
				nameTheme = nameTheme.replace('position ', '');
				$(this).prop("name", nameTheme + '[' + optionID + '][' + parentID + '][' + listID + ']');
			});
			}
		});
	});
	
	// Fade out the save message
	$('.fade').delay(1000).fadeOut(1000);
	
	$('.of-color').wpColorPicker();
	
	// Switches option sections
	$('.group').hide();
	var active_tab = '';
	if (typeof(localStorage) != 'undefined' ) {
		active_tab = localStorage.getItem("active_tab");
	}
	if (active_tab != '' && $(active_tab).length ) {
		$(active_tab).fadeIn();
	} else {
		$('.group:first').fadeIn();
	}
	$('.group .collapsed').each(function(){
		$(this).find('input:checked').parent().parent().parent().nextAll().each( 
			function(){
				if ($(this).hasClass('last')) {
					$(this).removeClass('hidden');
						return false;
					}
				$(this).filter('.hidden').removeClass('hidden');
			});
	});
	if (active_tab != '' && $(active_tab + '-tab').length ) {
		$(active_tab + '-tab').addClass('nav-tab-active');
	}
	else {
		$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
	}
	
	$('.nav-tab-wrapper a').click(function(evt) {
		$('.nav-tab-wrapper a').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active').blur();
		var clicked_group = $(this).attr('href');
		if (typeof(localStorage) != 'undefined' ) {
			localStorage.setItem("active_tab", $(this).attr('href'));
		}
		$('.group').hide();
		$(clicked_group).fadeIn();
		evt.preventDefault();
		
		// Editor Height (needs improvement)
		$('.wp-editor-wrap').each(function() {
			var editor_iframe = $(this).find('iframe');
			if ( editor_iframe.height() < 30 ) {
				editor_iframe.css({'height':'auto'});
			}
		});
	
	});
           					
	$('.group .collapsed input:checkbox').click(unhideHidden);
				
	function unhideHidden(){
		if ($(this).attr('checked')) {
			$(this).parent().parent().parent().nextAll().removeClass('hidden');
		}
		else {
			$(this).parent().parent().parent().nextAll().each( 
			function(){
				if ($(this).filter('.last').length) {
					$(this).addClass('hidden');
					return false;		
					}
				$(this).addClass('hidden');
			});
           					
		}
	}
	
	// Image Options
	$('.of-radio-img-img').click(function(){
		$(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
		$(this).addClass('of-radio-img-selected');		
	});
		
	$('.of-radio-img-label').hide();
	$('.of-radio-img-img').show();
	$('.of-radio-img-radio').hide();
	
	/* Toggle */
	$(".toggle-container .toggle-content").hide(); //Hide (Collapse) the toggle containers on load
	$(".toggle-container .toggle-sign").text('+'); //Add the + sign on load

	$(".toggle-container .toggle").toggle(function() {
		$(this).addClass('selected');
		$(this).find('.toggle-sign').text('-');
		$(this).next(".toggle-content").slideToggle();
	}, function() {
		$(this).removeClass('selected');
		$(this).find('.toggle-sign').text('+');
		$(this).next(".toggle-content").slideToggle();
	});
		 		
});

function options_font_preview(select_id, example_id, style) {
    var id = '#'+select_id;
    var ex = '#'+example_id;
    jQuery(ex).css( style, jQuery(id).val() );
}
  	