jQuery(document).ready(function() {
	//verify if checkbox is checked
	if(jQuery('#gg_portfolio_all_categories').is(':checked')) {
        jQuery('.rwmb-taxonomy-wrapper').fadeToggle(400);
    }
	//on click hide/show checkbox
	jQuery('#gg_portfolio_all_categories').click(function() {
		jQuery('.rwmb-taxonomy-wrapper').fadeToggle(400);
	});
	
	if (jQuery('#gg_portfolio_all_categories:checked').val() !== undefined) {
		jQuery('.rwmb-taxonomy-wrapper').show();
	}
	
	//verify if select is selected
  	if (!jQuery("#gg_portfolio_page_style option:selected").length)
    jQuery("#gg_portfolio_page_style option[value='filterable']").attr('selected', 'selected');
	jQuery('.rwmb-taxonomy-wrapper, .rwmb-checkbox-wrapper').fadeToggle(400);
	
	
	//on select show/hide element
	jQuery("#gg_portfolio_page_style").change(function(){ 
 
        if (jQuery(this).val() == "filterable" ) { 
 
            jQuery('.rwmb-taxonomy-wrapper, .rwmb-checkbox-wrapper').fadeToggle(200);
 
        } else {
 
            jQuery('.rwmb-taxonomy-wrapper, .rwmb-checkbox-wrapper').show();
 
        }
    });
	
});