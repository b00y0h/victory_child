<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */

/********************* META BOX DEFINITIONS ***********************/

/**
 * Prefix of meta keys (optional)
 * Use underscore (_) at the beginning to make keys hidden
 * Alt.: You also can make prefix empty to disable it
 */
// Better has an underscore as last sign
$prefix = 'gg_';

global $meta_boxes;

// Get sidebars defined in theme options
$metabox_sidebars = of_get_option('sidebar_list');
// Verify if sidebars are created
if( !$metabox_sidebars )
$metabox_sidebars = array();
array_unshift( $metabox_sidebars, Array( 'id' => 'default_sidebar', 'name' => 'Default sidebar' ) );

if ($metabox_sidebars) {
	$metabox_sidebars_array = array();
	foreach ($metabox_sidebars as $metabox_sidebars_list ) {
		   $metabox_sidebars_array[$metabox_sidebars_list['id']] = $metabox_sidebars_list['name'];
	}
}

$meta_boxes = array();

$meta_boxes[] = array(
	'id' => 'info_general',
	'title' => 'Page informations',
	'pages' => array('page'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'  => 'Page image dimensions',
			'id'    => "{$prefix}page_info",
			'desc'  => 'The dimension of the header page image is: 1200x450px. The image is automatically resized, but you must constrain proportions.</br>To insert a header image please use the "Set featured image" link from the right panel.',
			'type'  => 'info'
		)
	)
);

$meta_boxes[] = array(
	'id' => 'general_sidebar',
	'title' => 'Choose your sidebar',
	'pages' => array( 'post', 'page' ),
	'context' => 'side',
	'priority' => 'low',
	'fields' => array(
		array(
			'name'     => 'Posts Widget Area',
			'id'       => "{$prefix}primary-widget-area",
			'type'     => 'select',
			'class'    => 'posts-widget-area',
			'options'  => $metabox_sidebars_array,
			'multiple' => false,
		),
		array(
			'name'     => 'Pages Widget Area',
			'id'       => "{$prefix}secondary-widget-area",
			'type'     => 'select',
			'class'    => 'pages-widget-area',
			'options'  => $metabox_sidebars_array,
			'multiple' => false,
		),
		array(
			'name'     => 'Portfolio Widget Area',
			'id'       => "{$prefix}portfolio-widget-area",
			'type'     => 'select',
			'class'    => 'portfolio-widget-area',
			'options'  => $metabox_sidebars_array,
			'multiple' => false,
		),
		array(
			'name'     => 'Contact Widget Area',
			'id'       => "{$prefix}contact-widget-area",
			'type'     => 'select',
			'class'    => 'contact-widget-area',
			'options'  => $metabox_sidebars_array,
			'multiple' => false,
		),
		array(
			'name'     => 'First Footer Widget Area',
			'id'       => "{$prefix}first-footer-widget-area",
			'type'     => 'select',
			'class'    => 'first-footer-widget-area',
			'options'  => $metabox_sidebars_array,
			'multiple' => false,
		),
		array(
			'name'     => 'Second Footer Widget Area',
			'id'       => "{$prefix}second-footer-widget-area",
			'type'     => 'select',
			'class'    => 'second-footer-widget-area',
			'options'  => $metabox_sidebars_array,
			'multiple' => false,
		),
		array(
			'name'     => 'Third Footer Widget Area',
			'id'       => "{$prefix}third-footer-widget-area",
			'type'     => 'select',
			'class'    => 'third-footer-widget-area',
			'options'  => $metabox_sidebars_array,
			'multiple' => false,
		),
		array(
			'name'     => 'Fourth Footer Widget Area',
			'id'       => "{$prefix}fourth-footer-widget-area",
			'after'     => 'You can create more custom sidebars <a href="themes.php?page=options-framework">here</a>.',
			'type'     => 'select',
			'class'    => 'fourth-footer-widget-area',
			'options'  => $metabox_sidebars_array,
			'multiple' => false,
			
		)
	)
);

$meta_boxes[] = array(
	'id' => 'general_page_meta',
	'title' => 'Page headline',
	'pages' => array('page'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'  => 'Page headline',
			'id'    => "{$prefix}page_headline",
			'desc'  => 'Enter page headline here.',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'Page title',
			'id'    => "{$prefix}page_title",
			'desc'  => 'Enable/Disable page title.',
			'std'   => 1,
			'type'  => 'checkbox',
			'clone' => false,
		),
		array(
			'name'  => 'Page breadcrumbs',
			'id'    => "{$prefix}page_breadcrumbs",
			'desc'  => 'Enable/Disable page breadcrumbs.',
			'std'   => 1,
			'type'  => 'checkbox',
			'clone' => false,
		)
	)
);

$meta_boxes[] = array(
	'id' => 'portfolio_page_meta',
	'title' => 'Portfolio page options',
	'pages' => array('page'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'    => 'Select categories to display',
			'id'      => "{$prefix}portfolio_page_categories",
			'type'    => 'taxonomy',
			'options' => array(
				'taxonomy' => 'portfolio_category',
				'type' => 'select_tree',
				'args' => array()
			),
			'default'    => 'All posts',
		),
		
		array(
			'name'     => 'Select portfolio page style',
			'id'       => "{$prefix}portfolio_page_style",
			'type'     => 'select',
			'options'  => array(
				'classic' => 'Classic',
				'sidebar' => 'With sidebar',
				'filterable' => 'Filterable',
			),
			'std'   => array( 'classic' ),
			'multiple' => false,
		),

		array(
			'name'     => 'Select portfolio page columns',
			'id'       => "{$prefix}portfolio_page_columns",
			'type'     => 'select',
			'options'  => array(
				'one-col' => 'One column',
				'two-col' => 'Two columns',
				'three-col' => 'Three columns',
				'four-col' => 'Four columns',
			),
			'std'   => array( 'three-col' ),
			'multiple' => false,
		),

		array(
			'name'  => 'Enter number of post to show',
			'id'    => "{$prefix}portfolio_page_nr_posts",
			'desc'  => 'Enter number of posts to show. Default: 10',
			'type'  => 'text',
			'std'   => '10',
			'clone' => false,
		)
	)
);

$meta_boxes[] = array(
	'id' => 'blog_page_meta',
	'title' => 'Blog page options',
	'pages' => array('page'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'  => 'Enter number of post to show',
			'id'    => "{$prefix}blog_page_nr_posts",
			'desc'  => 'Enter number of posts to show. Default: 5',
			'type'  => 'text',
			'std'   => '5',
			'clone' => false,
		)
	)
);

$meta_boxes[] = array(
	'id' => 'blogpost_post_meta_info',
	'title' => 'Blog post informations',
	'pages' => array('post'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'  => 'Image dimensions',
			'id'    => "{$prefix}blog_post_info",
			'desc'  => 'The images are automatically resized, but you must constrain proportions.</br>To insert an image please use the "Set featured image" link from the bottom right panel. </br>
			For perfect display please use the following size: 660x360px </br>
			',
			'type'  => 'info'
		)
	)
);

$meta_boxes[] = array(
	'id' => 'contact_page_meta',
	'title' => 'Contact page options',
	'pages' => array('page'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'  => 'Enter your email address',
			'id'    => "{$prefix}contact_page_email",
			'desc'  => 'Enter your email address.',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'Enter success text',
			'id'    => "{$prefix}contact_page_success_msg",
			'desc'  => 'Enter the success text',
			'type'  => 'text',
			'std'   => 'Your email was successfully sent.',
			'clone' => false,
		),
		array(
			'name'  => 'Enter error text',
			'id'    => "{$prefix}contact_page_error_msg",
			'desc'  => 'Enter the error text',
			'type'  => 'text',
			'std'   => 'There was an error submitting the form.',
			'clone' => false,
		),
		array(
			'name'  => 'Map',
			'id'    => "{$prefix}contact_map",
			'desc'  => 'Enable/disable map. Default: true',
			'std'  => 1,
			'type'  => 'checkbox',
			'clone' => false,
		),
		array(
			'name'  => 'Address: Latitude value',
			'id'    => "{$prefix}contact_map_latitude",
			'desc'  => 'Enter the latitude value of your location in this format <pre>51.13456</pre> Latitude value is the first string (before comma) of google map address. E.G.: <strong>51.13456</strong>, -1.34333',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'Address: Longitude value',
			'id'    => "{$prefix}contact_map_longitude",
			'desc'  => 'Enter the longitude value of your location in this format <pre>-1.34333</pre> Latitude value is the second string (after comma) of google map address. E.G.: 51.13456, <strong>-1.34333</strong>',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'Zoom',
			'id'    => "{$prefix}contact_zoom",
			'desc'  => 'Enter the zoom level. Defaul: 16',
			'type'  => 'text',
			'std'   => '16',
			'clone' => false,
		),
		array(
			'name'  => 'Map InfoWindow',
			'id'    => "{$prefix}contact_map_infowindow",
			'desc'  => 'Insert your address details. Will appear after you click on the marker icon. HTML supported.',
			'type'  => 'textarea',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'Map InfoWindow title',
			'id'    => "{$prefix}contact_map_infowindow_title",
			'desc'  => 'Insert the infoWindow title. Will appear only on marker hover.',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		)
	)
);

$meta_boxes[] = array(
	'id' => 'slideshow_post_meta_info',
	'title' => 'Slideshow post informations',
	'pages' => array('slideshow'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'  => 'Image dimensions',
			'id'    => "{$prefix}slideshow_post_info",
			'desc'  => 'The images are automatically resized, but you must constrain proportions.</br>To insert an image please use the "Set featured image" link from the bottom right panel. For perfect display please use the following image sizes.
			',
			'type'  => 'info'
		),
		array(
			'name'  => 'Flexslider Slideshow',
			'id'    => "{$prefix}slideshow_post_info_flexslider_slideshow",
			'desc'  => '1200x450px',
			'type'  => 'info'
		),
		array(
			'name'  => 'Sequence Slideshow',
			'id'    => "{$prefix}slideshow_post_info_sequence_slideshow",
			'desc'  => '266x568px. Use .png images.',
			'type'  => 'info'
		),
		array(
			'name'  => 'Elastic Slideshow',
			'id'    => "{$prefix}slideshow_post_info_elastic_slideshow",
			'desc'  => '1200x450px',
			'type'  => 'info'
		),
		array(
			'name'  => 'iView Slideshow',
			'id'    => "{$prefix}slideshow_post_info_iview_slideshow",
			'desc'  => '1200x450px',
			'type'  => 'info'
		),
		array(
			'name'  => 'Slit Slideshow',
			'id'    => "{$prefix}slideshow_post_info_slit_slideshow",
			'desc'  => '1200x450px',
			'type'  => 'info'
		),
		array(
			'name'  => 'JMpress Slideshow',
			'id'    => "{$prefix}slideshow_post_info_jmpress_slideshow",
			'desc'  => '266x450px. Use .png images.',
			'type'  => 'info'
		)
	)
);

$meta_boxes[] = array(
	'id' => 'slideshow_post_meta',
	'title' => 'Slideshow post options',
	'pages' => array('slideshow'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'  => 'Slide caption - Main title',
			'id'    => "{$prefix}slideshow_caption_title",
			'desc'  => 'Enter slide caption main title',
			'type'  => 'textarea',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name' => 'Slide caption - Main title - color',
			'id'   => "{$prefix}slideshow_caption_title_color",
			'desc'  => 'Default:#ffffff',
			'type' => 'color',
		),
		array(
			'name'  => 'Slide caption - Subtitle',
			'id'    => "{$prefix}slideshow_caption_subtitle",
			'desc'  => 'Enter slide caption subtitle. Default:#ffffff',
			'type'  => 'textarea',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name' => 'Slide caption - Subtitle - color',
			'id'   => "{$prefix}slideshow_caption_subtitle_color",
			'desc'  => 'Default:#ffffff',
			'std'   => '',
			'type' => 'color',
		),
		array(
			'name'  => 'External link URL',
			'id'    => "{$prefix}slideshow_external_link",
			'desc'  => 'Enter external link here',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'External link name',
			'id'    => "{$prefix}slideshow_external_link_name",
			'desc'  => 'Enter external link name here',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name' => 'Slide background color',
			'id'   => "{$prefix}slideshow_background_color",
			'desc'  => 'Only for JMpress Slideshow. </br> Default:#eeeeee',
			'std'   => '#eeeeee',
			'type' => 'color',
		),
		
	)
);

$meta_boxes[] = array(
	'id' => 'sponsors_page_meta',
	'title' => 'Sponsors page options',
	'pages' => array('page'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'     => 'Select sponsors page style',
			'id'       => "{$prefix}sponsors_page_style",
			'type'     => 'select',
			'options'  => array(
				'full_width' => 'Full width',
				'sidebar' => 'With sidebar'
			),
			'std'   => array( 'full_width' ),
			'multiple' => false,
		),
		array(
			'name'  => 'Enter number of post to show',
			'id'    => "{$prefix}sponsors_page_nr_posts",
			'desc'  => 'Enter number of posts to show. Default: 12',
			'type'  => 'text',
			'std'   => '12',
			'clone' => false,
		)
	)
);

$meta_boxes[] = array(
	'id' => 'sponsors_post_meta_info',
	'title' => 'Sponsors post informations',
	'pages' => array('sponsors_pt'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'  => 'Image dimensions',
			'id'    => "{$prefix}sponsors_post_info",
			'desc'  => 'The images are automatically resized, but you must constrain proportions.</br>To insert an image please use the "Set featured image" link from the bottom right panel. </br>
			For perfect display please use the following image size: 180x100px.',
			'type'  => 'info'
		)
	)
);

$meta_boxes[] = array(
	'id' => 'sponsors_post_meta',
	'title' => 'Sponsors post options',
	'pages' => array('sponsors_pt'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'  => 'External link',
			'id'    => "{$prefix}sponsors_external_link",
			'desc'  => 'Enter external link',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'Hide the title',
			'id'    => "{$prefix}sponsors_hide_title",
			'desc'  => 'Check this box if you want to hide the title from the sponsor box.',
			'type'  => 'checkbox',
			'clone' => false,
		)
	)
);

$meta_boxes[] = array(
	'id' => 'testimonials_page_meta',
	'title' => 'Testimonials page options',
	'pages' => array('page'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'     => 'Select testimonials page style',
			'id'       => "{$prefix}testimonials_page_style",
			'type'     => 'select',
			'options'  => array(
				'full_width' => 'Full width',
				'sidebar' => 'With sidebar'
			),
			'std'   => array( 'full_width' ),
			'multiple' => false,
		),
		array(
			'name'  => 'Enter number of post to show',
			'id'    => "{$prefix}testimonials_page_nr_posts",
			'desc'  => 'Enter number of posts to show. Default: 12',
			'type'  => 'text',
			'std'   => '12',
			'clone' => false,
		)
	)
);

$meta_boxes[] = array(
	'id' => 'testimonials_post_meta_info',
	'title' => 'Testimonials post informations',
	'pages' => array('testimonials_pt'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'  => 'Image dimensions',
			'id'    => "{$prefix}testimonials_post_info",
			'desc'  => 'The images are automatically resized, but you must constrain proportions.</br>To insert an image please use the "Set featured image" link from the bottom right panel. </br>
			For perfect display please use the following image size: 35x35px',
			'type'  => 'info'
		)
	)
);

$meta_boxes[] = array(
	'id' => 'testimonials_post_meta',
	'title' => 'Testimonials post options',
	'pages' => array('testimonials_pt'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'  => 'Author name',
			'id'    => "{$prefix}testimonials_author_name",
			'desc'  => 'Enter author name',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'Author website',
			'id'    => "{$prefix}testimonials_author_website",
			'desc'  => 'Enter author website',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'Testimonial content',
			'id'    => "{$prefix}testimonials_content",
			'desc'  => 'Enter testimonial content',
			'type'  => 'textarea',
			'std'   => '',
			'clone' => false,
		)
	)
);

$meta_boxes[] = array(
	'id' => 'team_page_meta',
	'title' => 'Team page options',
	'pages' => array('page'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'     => 'Select team page style',
			'id'       => "{$prefix}team_page_style",
			'type'     => 'select',
			'options'  => array(
				'full_width' => 'Full width',
				'sidebar' => 'With sidebar'
			),
			'std'   => array( 'full_width' ),
			'multiple' => false,
		),
		array(
			'name'  => 'Enter number of post to show',
			'id'    => "{$prefix}team_page_nr_posts",
			'desc'  => 'Enter number of posts to show. Default: 12',
			'type'  => 'text',
			'std'   => '12',
			'clone' => false,
		)
	)
);


$meta_boxes[] = array(
	'id' => 'team_post_meta_info',
	'title' => 'Team post informations',
	'pages' => array('team_pt'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'  => 'Image dimensions',
			'id'    => "{$prefix}team_post_info",
			'desc'  => 'The images are automatically resized, but you must constrain proportions.</br>To insert an image please use the "Set featured image" link from the bottom right panel.</br> 
			For perfect display please use the following image size: 180x180px',
			'type'  => 'info'
		)
	)
);

$meta_boxes[] = array(
	'id' => 'team_post_meta',
	'title' => 'Team post options',
	'pages' => array('team_pt'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'  => 'Team member position',
			'id'    => "{$prefix}team_member_position",
			'desc'  => 'Enter team member job',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'Team member short description',
			'id'    => "{$prefix}team_member_desc",
			'desc'  => 'Enter a short description about the team member',
			'type'  => 'textarea',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'Team member Twitter link',
			'id'    => "{$prefix}team_member_twitter",
			'desc'  => 'Enter the link to your Twitter account',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'Team member Facebook link',
			'id'    => "{$prefix}team_member_facebook",
			'desc'  => 'Enter the link to your Facebook account',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'Team member Flickr link',
			'id'    => "{$prefix}team_member_flickr",
			'desc'  => 'Enter the link to your Flickr account',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'Team member Linkedin link',
			'id'    => "{$prefix}team_member_linkedin",
			'desc'  => 'Enter the link to your Linkedin account',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'Team member Youtube link',
			'id'    => "{$prefix}team_member_youtube",
			'desc'  => 'Enter the link to your Youtube account',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'Team member Personal Website',
			'id'    => "{$prefix}team_member_website",
			'desc'  => 'Enter the link to your Personal Website',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		)
	)
);

$meta_boxes[] = array(
	'id' => 'ads_page_meta',
	'title' => 'Ads page options',
	'pages' => array('page'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'     => 'Select ads page style',
			'id'       => "{$prefix}ads_page_style",
			'type'     => 'select',
			'options'  => array(
				'full_width' => 'Full width',
				'sidebar' => 'With sidebar'
			),
			'std'   => array( 'full_width' ),
			'multiple' => false,
		),
		array(
			'name'  => 'Enter number of post to show',
			'id'    => "{$prefix}ads_page_nr_posts",
			'desc'  => 'Enter number of posts to show. Default: 12',
			'type'  => 'text',
			'std'   => '12',
			'clone' => false,
		)
	)
);

$meta_boxes[] = array(
	'id' => 'ads_post_meta_info',
	'title' => 'Ads post informations',
	'pages' => array('ads_pt'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'  => 'Image dimensions',
			'id'    => "{$prefix}ads_post_info",
			'desc'  => 'The images are automatically resized, but you must constrain proportions.</br>To insert an image please use the "Set featured image" link from the bottom right panel.</br> 
			For perfect display please use the following image size: 220x305px 
			',
			'type'  => 'info'
		)
	)
);

$meta_boxes[] = array(
	'id' => 'ads_post_meta',
	'title' => 'Ads post options',
	'pages' => array('ads_pt'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'  => 'Ad link',
			'id'    => "{$prefix}ads_link",
			'desc'  => 'Insert an external link for your ads',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'             => 'Upload your ads',
			'id'               => "{$prefix}ads_upload",
			'type'             => 'plupload_image',
			'max_file_uploads' => 2,
			'desc'  => 'The images are automatically resized, but you must constrain proportions. Recommended: 220x305px. </br>  You can upload only 2 images. Drag left/right to reorder.',
			'force_delete' => true,
		)
	)
);

$meta_boxes[] = array(
	'id' => 'faq_page_meta',
	'title' => 'Faq page options',
	'pages' => array('page'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'     => 'Select faq page style',
			'id'       => "{$prefix}faq_page_style",
			'type'     => 'select',
			'options'  => array(
				'full_width' => 'Full width',
				'sidebar' => 'With sidebar'
			),
			'std'   => array( 'full_width' ),
			'multiple' => false,
		),
		array(
			'name'  => 'Enter number of post to show',
			'id'    => "{$prefix}faq_page_nr_posts",
			'desc'  => 'Enter number of posts to show. Default: 12',
			'type'  => 'text',
			'std'   => '12',
			'clone' => false,
		)
	)
);

$meta_boxes[] = array(
	'id' => 'portfolio_post_meta_info',
	'title' => 'Portfolio post informations',
	'pages' => array('portfolio_pt'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'  => 'Image dimensions',
			'id'    => "{$prefix}portfolio_post_info",
			'desc'  => 'The images are automatically resized, but you must constrain proportions.</br>To insert an image please use the "Set featured image" link from the right panel. For perfect display please use the following image sizes.
			',
			'type'  => 'info'
		),
		array(
			'name'  => 'Classic &amp; Filterable layout',
			'id'    => "{$prefix}portfolio_post_info_classic_filterable",
			'desc'  => '
				One column - 900x900px </br>
				Two columns - 420x420px </br>
				Three columns - 260x260px </br>
				Four columns - 180x180px </br>
 			',
			'type'  => 'info'
		),
		array(
			'name'  => 'Sidebar layout',
			'id'    => "{$prefix}portfolio_post_info_sidebar",
			'desc'  => '
				One column - 660x660px </br>
				Two columns - 345x471px </br>
				Three columns - 180x180px </br>
				Four columns - 120x120px </br>
 			',
			'type'  => 'info'
		)
	)
);

$meta_boxes[] = array(
	'id' => 'portfolio_post_meta',
	'title' => 'Portfolio post options',
	'pages' => array('portfolio_pt'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'  => 'Project date',
			'id'    => "{$prefix}portfolio_project_date",
			'desc'  => 'Enter the project date',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'Project URL',
			'id'    => "{$prefix}portfolio_project_url",
			'desc'  => 'Enter the project URL. Please include "http://"',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'Project details',
			'id'    => "{$prefix}portfolio_project_details",
			'desc'  => 'Insert here details worth mentioning',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'Enter video link',
			'id'    => "{$prefix}portfolio_post_video_link",
			'desc'  => '
			
			Enter video URL (Vimeo, Youtube), Flash content URL(SWF), QuickTime Movies URL<br><br>

			For Youtube, Vimeo videos just insert the link, like this:<br>
			http://vimeo.com/17120260<br>
			http://www.youtube.com/watch?v=qqXi8WmQ_WM<br><br>
			
			For SWF and Quicktime you must specify the dimensions too, like this:<br>
			http://trailers.apple.com/movies/universal/despicableme/despicableme-tlr1_r640s.mov?width=640&height=360<br>
			http://www.adobe.com/jp/events/cs3_web_edition_tour/swfs/perform.swf?width=792&height=294 <br>
			
			',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name'  => 'Enter external link',
			'id'    => "{$prefix}portfolio_post_external_link",
			'desc'  => 'Enter an external link for your project',
			'type'  => 'text',
			'std'   => '',
			'clone' => false,
		),
		array(
			'name' => 'Upload lightbox image',
			'id'   => "{$prefix}portfolio_lightbox_image",
			'type' => 'thickbox_image',
			'desc'  => 'Upload the image used in lightbox. You can upload as many as you want, but only the first image will be active.',
			'force_delete' => true,
		),
		array(
			'name'             => 'Portfolio slideshow upload (only for portfolio single post)',
			'id'               => "{$prefix}portfolio_slideshow_upload",
			'type'             => 'plupload_image',
			'max_file_uploads' => 20,
			'desc'  => 'The images are automatically resized, but you must constrain proportions. </br> Recommended: 1200x450px',
			'force_delete' => true,
		),
	)
);


//********************* META BOX REGISTERING ***********************/

/**
 * Register meta boxes
 *
 * @return void
 */
function rw_register_meta_boxes()
{
	// Make sure there's no errors when the plugin is deactivated or during upgrade
	if ( !class_exists( 'RW_Meta_Box' ) )
		return;

	global $meta_boxes;
	foreach ( $meta_boxes as $meta_box )
	{
		new RW_Meta_Box( $meta_box );
	}
}
// Hook to 'admin_init' to make sure the meta box class is loaded before
// (in case using the meta box class in another plugin)
// This is also helpful for some conditionals like checking page template, categories, etc.
add_action( 'admin_init', 'rw_register_meta_boxes' );