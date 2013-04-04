<?php
@define( 'PARENT_DIR', get_stylesheet_directory() );


function remove_cumico_actions() {
    remove_action('login_head','namespace_login_style');
    remove_action('wp_enqueue_scripts', 'load_styles_and_scripts');

}
add_action('init','remove_cumico_actions');

function child_namespace_login_style() {
    if (of_get_option('use_wp_admin_logo')) {
    echo '<style>.login h1 a { background-image: url( '.of_get_option('wp_admin_logo').' ) !important; background-size:auto; }</style>';
    }
}

add_action( 'login_head', 'child_namespace_login_style' );


add_action('wp_enqueue_scripts', 'child_load_styles_and_scripts');
function child_load_styles_and_scripts() {

    //only on frontend
    if(is_admin()) return;
    $woocommerce_is_active = in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
    $theme  = wp_get_theme();
    $version = $theme['Version'];

    //Register styles
    wp_register_style('skeleton', get_stylesheet_directory_uri().'/styles/skeleton.css', false, $version, 'screen, projection');
    wp_register_style('theme', get_stylesheet_directory_uri().'/style.css', 'okthemes', $version, 'screen, projection');
    wp_register_style('prettyphoto', get_stylesheet_directory_uri().'/styles/prettyPhoto.css', 'okthemes', $version, 'screen, projection');
    wp_register_style('isotope', get_stylesheet_directory_uri().'/styles/isotope.css', 'okthemes', $version, 'screen, projection');
    wp_register_style('woocommercecustomstyle', get_stylesheet_directory_uri().'/styles/woocommerce.css', 'okthemes', $version, 'screen, projection');
    wp_register_style('cloudzoomstyle', get_stylesheet_directory_uri().'/styles/cloud-zoom.css', 'okthemes', $version, 'screen, projection');
    wp_register_style('flexslider', get_stylesheet_directory_uri().'/styles/flexslider.css', 'okthemes', $version, 'screen, projection');
    wp_register_style('eislider', get_stylesheet_directory_uri().'/styles/eislider.css', 'okthemes', $version, 'screen, projection');
    wp_register_style('catslider', get_stylesheet_directory_uri().'/styles/catslider.css', 'okthemes', $version, 'screen, projection');
    wp_register_style('sequence', get_stylesheet_directory_uri().'/styles/sequencejs-theme.modern-slide-in.css', 'okthemes', $version, 'screen, projection');
    wp_register_style('iview', get_stylesheet_directory_uri().'/styles/iview.css', 'okthemes', $version, 'screen, projection');
    wp_register_style('slit', get_stylesheet_directory_uri().'/styles/slit.css', 'okthemes', $version, 'screen, projection');
    wp_register_style('jmpress', get_stylesheet_directory_uri().'/styles/jmpress.css', 'okthemes', $version, 'screen, projection');
    wp_register_style('ie7-style', get_stylesheet_directory_uri() . '/styles/ie7.css', 'okthemes', $version, 'screen, projection');
    wp_register_style('responsive', get_stylesheet_directory_uri().'/styles/responsive.css', 'okthemes', $version, 'screen, projection');
    wp_register_style('phpstyle', get_stylesheet_directory_uri() . '/style.php', 'okthemes', $version, 'screen, projection');

    //Enqueue styles
    wp_enqueue_style( 'skeleton' );
    wp_enqueue_style( 'theme' );
    wp_enqueue_style( 'prettyphoto' );

    //Portfolio isotope
    if ( is_page_template('page-portfolio.php') )
    wp_enqueue_style( 'isotope' );

    wp_enqueue_style( 'woocommercecustomstyle' );

    //Product cloud zoom
    if (of_get_option('product_cloud_zoom'))
        wp_enqueue_style( 'cloudzoomstyle' );

    wp_enqueue_style( 'flexslider' );

    //Elastic slider
    if (of_get_option('slideshow_select') == "elastic")
        wp_enqueue_style( 'eislider' );

    //Category slider
    if (of_get_option('slideshow_select') == "multiitemslider")
        wp_enqueue_style( 'catslider' );

    //Sequence slider
    if (of_get_option('slideshow_select') == "sequence")
        wp_enqueue_style( 'sequence' );

    //Iview slider
    if (of_get_option('slideshow_select') == "iview")
        wp_enqueue_style( 'iview' );

    //Slit slider
    if (of_get_option('slideshow_select') == "slit")
        wp_enqueue_style( 'slit' );

    //Jmpress slider
    if (of_get_option('slideshow_select') == "jmpress")
        wp_enqueue_style( 'jmpress' );

    wp_enqueue_style( 'ie7-style' );

    //Responsiveness
    if (of_get_option('responsiveness'))
        wp_enqueue_style( 'responsive' );

    wp_enqueue_style( 'phpstyle' );

    //Register scripts
    wp_register_script('custom',get_stylesheet_directory_uri() ."/javascripts/app.js",array('jquery'),'1.2.3',true);
    wp_register_script('prettyphoto',get_stylesheet_directory_uri() ."/javascripts/jquery.prettyPhoto.js",array('jquery'),'1.2.3',true);
    wp_register_script('isotope',get_stylesheet_directory_uri() ."/javascripts/jquery.isotope.min.js",array('jquery'),'1.2.3',true);
    wp_register_script('mobilemenu',get_stylesheet_directory_uri() ."/javascripts/jquery.mobilemenu.js",array('jquery'),'1.2.3',true);
    wp_register_script('fitvids',get_stylesheet_directory_uri() ."/javascripts/jquery.fitvids.js",array('jquery'),'1.2.3',true);
    wp_register_script('cloud-zoom',get_stylesheet_directory_uri() ."/javascripts/cloud-zoom.1.0.2.min.js",array('jquery'),'1.2.3',true);
    wp_register_script('modernizr',get_stylesheet_directory_uri() ."/javascripts/modernizr.js",array('jquery'),'1.2.3',true);
    wp_register_script('easing',get_stylesheet_directory_uri() ."/javascripts/jquery.easing.1.3.js",array('jquery'),'1.2.3',true);
    wp_register_script('raphael',get_stylesheet_directory_uri() ."/javascripts/raphael-min.js",array('jquery'),'1.2.3',true);
    wp_register_script('flexslider',get_stylesheet_directory_uri() ."/javascripts/jquery.flexslider-min.js",array('jquery'),'1.2.3',true);
    wp_register_script('eislideshow',get_stylesheet_directory_uri() ."/javascripts/jquery.eislideshow.js",array('jquery'),'1.2.3',true);
    wp_register_script('catslider',get_stylesheet_directory_uri() ."/javascripts/jquery.catslider.js",array('jquery'),'1.2.3',true);
    wp_register_script('sequence',get_stylesheet_directory_uri() ."/javascripts/sequence.jquery-min.js",array('jquery'),'1.2.3',true);
    wp_register_script('iview',get_stylesheet_directory_uri() ."/javascripts/iview.min.js",array('jquery'),'1.2.3',true);
    wp_register_script('ba-slit',get_stylesheet_directory_uri() ."/javascripts/jquery.ba-cond.min.js",array('jquery'),'1.2.3',true);
    wp_register_script('slit',get_stylesheet_directory_uri() ."/javascripts/jquery.slitslider.js",array('jquery'),'1.2.3',true);
    wp_register_script('jmpress',get_stylesheet_directory_uri() ."/javascripts/jmpress.min.js",array('jquery'),'1.2.3',true);
    wp_register_script('jmslideshow',get_stylesheet_directory_uri() ."/javascripts/jquery.jmslideshow.js",array('jquery'),'1.2.3',true);
    wp_register_script('gmaps',get_stylesheet_directory_uri() ."/javascripts/gmaps.js",array('jquery'),'1.2.3',true);
    wp_register_script('google-map',"http://maps.google.com/maps/api/js?sensor=true");
    wp_register_script('jvalidate',get_stylesheet_directory_uri() ."/javascripts/jquery.validate.min.js",array('jquery'),'1.2.3',true);
    wp_register_script('jcookie',get_stylesheet_directory_uri() ."/javascripts/jquery.cookie.min.js",array('jquery'),'1.2.3',true);
    wp_register_script('grid-list',get_stylesheet_directory_uri() ."/javascripts/jquery.gridlistview.min.js",array('jquery'),'1.2.3',true);
    wp_register_script('scrollto',get_stylesheet_directory_uri() ."/javascripts/jquery.scrollTo-1.4.3.1-min.js",array('jquery'),'1.2.3',true);

    //Enqueue script
    wp_enqueue_script( 'custom' );
    wp_enqueue_script( 'prettyphoto' );

    //Portfolio isotope
    if ( is_page_template('page-portfolio.php') )
        wp_enqueue_script( 'isotope' );

    wp_enqueue_script( 'mobilemenu' );
    wp_enqueue_script( 'fitvids' );

    //Cloud zoom
    if (of_get_option('product_cloud_zoom'))
        wp_enqueue_script( 'cloud-zoom' );

    wp_enqueue_script( 'modernizr' );
    wp_enqueue_script( 'easing' );
    wp_enqueue_script( 'flexslider' );

    //Elastic slider
    if (of_get_option('slideshow_select') == "elastic")
        wp_enqueue_script( 'eislideshow' );

    //Category slider
    if (of_get_option('slideshow_select') == "multiitemslider")
        wp_enqueue_script( 'catslider' );

    //Sequence slider
    if (of_get_option('slideshow_select') == "sequence")
        wp_enqueue_script( 'sequence' );

    //Iview slider
    if (of_get_option('slideshow_select') == "iview") {
        wp_enqueue_script( 'raphael' );
        wp_enqueue_script( 'iview' );
    }

    //Slit slider
    if (of_get_option('slideshow_select') == "slit") {
        wp_enqueue_script( 'ba-slit' );
        wp_enqueue_script( 'slit' );
    }

    //Jmpress slider
    if (of_get_option('slideshow_select') == "jmpress") {
        wp_enqueue_script( 'jmpress' );
        wp_enqueue_script( 'jmslideshow' );
    }

    //Gmaps
    if ( is_page_template('page-contact.php') ) {
        wp_enqueue_script( 'gmaps' );
        wp_enqueue_script( 'google-map' );
    }

    wp_enqueue_script( 'jvalidate' );
    wp_enqueue_script( 'scrollto' );

    //Grid on shop pages
    if ($woocommerce_is_active) {
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        wp_enqueue_script( 'jcookie' );
        wp_enqueue_script( 'grid-list' );
    }}

    global $wp_styles;
    $wp_styles->add_data('ie7-style', 'conditional', 'lte IE 7');
}

require_once (PARENT_DIR . '/lib/shortcodes-ultimate/shortcodes-ultimate.php');

