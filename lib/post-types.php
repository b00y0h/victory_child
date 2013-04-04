<?php
if ( ! class_exists( 'Portfolio_Post_Type' ) ) :

class Portfolio_Post_Type {

	// Current plugin version
	var $version = 0.4;

	function __construct() {

		// Adds the portfolio post type and taxonomies
		add_action( 'init', array( &$this, 'portfolio_init' ) );

		// Thumbnail support for portfolio posts
		add_theme_support( 'post-thumbnails', array( 'portfolio' ) );

		// Adds columns in the admin view for thumbnail and taxonomies
		add_filter( 'manage_edit-portfolio_columns', array( &$this, 'portfolio_edit_columns' ) );
		add_action( 'manage_posts_custom_column', array( &$this, 'portfolio_column_display' ), 10, 2 );

		// Allows filtering of posts by taxonomy in the admin view
		add_action( 'restrict_manage_posts', array( &$this, 'portfolio_add_taxonomy_filters' ) );

		// Show portfolio post counts in the dashboard
		add_action( 'right_now_content_table_end', array( &$this, 'add_portfolio_counts' ) );
	}

	function portfolio_init() {

		/**
		 * Enable the Portfolio custom post type
		 * http://codex.wordpress.org/Function_Reference/register_post_type
		 */

		$labels = array(
			'name' => __( 'Portfolio', 'okthemes' ),
			'singular_name' => __( 'Portfolio Item', 'okthemes' ),
			'add_new' => __( 'Add New Item', 'okthemes' ),
			'add_new_item' => __( 'Add New Portfolio Item', 'okthemes' ),
			'edit_item' => __( 'Edit Portfolio Item', 'okthemes' ),
			'new_item' => __( 'Add New Portfolio Item', 'okthemes' ),
			'view_item' => __( 'View Item', 'okthemes' ),
			'search_items' => __( 'Search Portfolio', 'okthemes' ),
			'not_found' => __( 'No portfolio items found', 'okthemes' ),
			'not_found_in_trash' => __( 'No portfolio items found in trash', 'okthemes' )
		);

		$args = array(
	    	'labels' => $labels,
	    	'public' => true,
			'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'capability_type' => 'post',
			'rewrite' => array("slug" => "portfolio-item"), // Permalinks format
			'menu_position' => 5,
			'has_archive' => true
		);

		register_post_type( 'portfolio_pt', $args );

		/**
		 * Register a taxonomy for Portfolio Tags
		 * http://codex.wordpress.org/Function_Reference/register_taxonomy
		 */

		$taxonomy_portfolio_tag_labels = array(
			'name' => __( 'Portfolio Tags', 'okthemes' ),
			'singular_name' => __( 'Portfolio Tag', 'okthemes' ),
			'search_items' => __( 'Search Portfolio Tags', 'okthemes' ),
			'popular_items' => __( 'Popular Portfolio Tags', 'okthemes' ),
			'all_items' => __( 'All Portfolio Tags', 'okthemes' ),
			'parent_item' => __( 'Parent Portfolio Tag', 'okthemes' ),
			'parent_item_colon' => __( 'Parent Portfolio Tag:', 'okthemes' ),
			'edit_item' => __( 'Edit Portfolio Tag', 'okthemes' ),
			'update_item' => __( 'Update Portfolio Tag', 'okthemes' ),
			'add_new_item' => __( 'Add New Portfolio Tag', 'okthemes' ),
			'new_item_name' => __( 'New Portfolio Tag Name', 'okthemes' ),
			'separate_items_with_commas' => __( 'Separate portfolio tags with commas', 'okthemes' ),
			'add_or_remove_items' => __( 'Add or remove portfolio tags', 'okthemes' ),
			'choose_from_most_used' => __( 'Choose from the most used portfolio tags', 'okthemes' ),
			'menu_name' => __( 'Portfolio Tags', 'okthemes' )
		);

		$taxonomy_portfolio_tag_args = array(
			'labels' => $taxonomy_portfolio_tag_labels,
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => true,
			'hierarchical' => false,
			'rewrite' => array( 'slug' => 'portfolio_tag' ),
			'query_var' => true
		);

		register_taxonomy( 'portfolio_tag', array( 'portfolio_pt' ), $taxonomy_portfolio_tag_args );

		/**
		 * Register a taxonomy for Portfolio Categories
		 * http://codex.wordpress.org/Function_Reference/register_taxonomy
		 */

	    $taxonomy_portfolio_category_labels = array(
			'name' => __( 'Portfolio Categories', 'okthemes' ),
			'singular_name' => __( 'Portfolio Category', 'okthemes' ),
			'search_items' => __( 'Search Portfolio Categories', 'okthemes' ),
			'popular_items' => __( 'Popular Portfolio Categories', 'okthemes' ),
			'all_items' => __( 'All Portfolio Categories', 'okthemes' ),
			'parent_item' => __( 'Parent Portfolio Category', 'okthemes' ),
			'parent_item_colon' => __( 'Parent Portfolio Category:', 'okthemes' ),
			'edit_item' => __( 'Edit Portfolio Category', 'okthemes' ),
			'update_item' => __( 'Update Portfolio Category', 'okthemes' ),
			'add_new_item' => __( 'Add New Portfolio Category', 'okthemes' ),
			'new_item_name' => __( 'New Portfolio Category Name', 'okthemes' ),
			'separate_items_with_commas' => __( 'Separate portfolio categories with commas', 'okthemes' ),
			'add_or_remove_items' => __( 'Add or remove portfolio categories', 'okthemes' ),
			'choose_from_most_used' => __( 'Choose from the most used portfolio categories', 'okthemes' ),
			'menu_name' => __('Portfolio Categories', 'okthemes' ),
	    );

	    $taxonomy_portfolio_category_args = array(
			'labels' => $taxonomy_portfolio_category_labels,
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => true,
			'hierarchical' => true,
			'rewrite' => array( 'slug' => 'portfolio_category' ),
			'query_var' => true
	    );

	    register_taxonomy( 'portfolio_category', array( 'portfolio_pt' ), $taxonomy_portfolio_category_args );

	}

	/**
	 * Add Columns to Portfolio Edit Screen
	 * http://wptheming.com/2010/07/column-edit-pages/
	 */

	function portfolio_edit_columns( $portfolio_columns ) {
		$portfolio_columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"title" => __('Title', 'okthemes'),
			"portfolio_thumbnail" => __('Thumbnail', 'okthemes'),
			"portfolio_category" => __('Category', 'okthemes'),
			"portfolio_tag" => __('Tags', 'okthemes'),
			"author" => __('Author', 'okthemes'),
			"comments" => __('Comments', 'okthemes'),
			"date" => __('Date', 'okthemes'),
		);
		$portfolio_columns['comments'] = '<div class="vers"><img alt="Comments" src="' . esc_url( admin_url( 'images/comment-grey-bubble.png' ) ) . '" /></div>';
		return $portfolio_columns;
	}

	function portfolio_column_display( $portfolio_columns, $post_id ) {

		// Code from: http://wpengineer.com/display-post-thumbnail-post-page-overview

		switch ( $portfolio_columns ) {

			case 'portfolio_thumbnail':
				$width = (int) 50;
				$height = (int) 50;
				// Display the featured image in the column view if possible
				if ( has_post_thumbnail()) {
					the_post_thumbnail( array($width, $height) );
				} else {
					echo 'None';
				}
				break;

			// Display the portfolio tags in the column view
			case "portfolio_category":

				if ( $category_list = get_the_term_list( $post_id, 'portfolio_category', '', ', ', '' ) ) {
					echo $category_list;
				} else {
					echo __('None', 'okthemes');
				}
				break;

			// Display the portfolio tags in the column view
			case "portfolio_tag":

				if ( $tag_list = get_the_term_list( $post_id, 'portfolio_tag', '', ', ', '' ) ) {
					echo $tag_list;
				} else {
					echo __('None', 'okthemes');
				}
				break;
		}
	}

	/**
	 * Adds taxonomy filters to the portfolio admin page
	 * Code artfully lifed from http://pippinsplugins.com
	 */

	function portfolio_add_taxonomy_filters() {
		global $typenow;

		// An array of all the taxonomyies you want to display. Use the taxonomy name or slug
		$taxonomies = array( 'portfolio_category', 'portfolio_tag' );

		// must set this to the post type you want the filter(s) displayed on
		if ( $typenow == 'portfolio_pt' ) {

			foreach ( $taxonomies as $tax_slug ) {
				$current_tax_slug = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
				$tax_obj = get_taxonomy( $tax_slug );
				$tax_name = $tax_obj->labels->name;
				$terms = get_terms($tax_slug);
				if ( count( $terms ) > 0) {
					echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
					echo "<option value=''>$tax_name</option>";
					foreach ( $terms as $term ) {
						echo '<option value=' . $term->slug, $current_tax_slug == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
					}
					echo "</select>";
				}
			}
		}
	}

	/**
	 * Add Portfolio count to "Right Now" Dashboard Widget
	 */

	function add_portfolio_counts() {
	        if ( ! post_type_exists( 'portfolio_pt' ) ) {
	             return;
	        }

	        $num_posts = wp_count_posts( 'portfolio_pt' );
	        $num = number_format_i18n( $num_posts->publish );
	        $text = _n( 'Portfolio Item', 'Portfolio Items', intval($num_posts->publish) );
	        if ( current_user_can( 'edit_posts' ) ) {
	            $num = "<a href='edit.php?post_type=portfolio'>$num</a>";
	            $text = "<a href='edit.php?post_type=portfolio'>$text</a>";
	        }
	        echo '<td class="first b b-portfolio">' . $num . '</td>';
	        echo '<td class="t portfolio">' . $text . '</td>';
	        echo '</tr>';

	        if ($num_posts->pending > 0) {
	            $num = number_format_i18n( $num_posts->pending );
	            $text = _n( 'Portfolio Item Pending', 'Portfolio Items Pending', intval($num_posts->pending) );
	            if ( current_user_can( 'edit_posts' ) ) {
	                $num = "<a href='edit.php?post_status=pending&post_type=portfolio'>$num</a>";
	                $text = "<a href='edit.php?post_status=pending&post_type=portfolio'>$text</a>";
	            }
	            echo '<td class="first b b-portfolio">' . $num . '</td>';
	            echo '<td class="t portfolio">' . $text . '</td>';

	            echo '</tr>';
	        }
	}

}

// new Portfolio_Post_Type;

endif;

if ( ! class_exists( 'Slideshow_Post_Type' ) ) :

class Slideshow_Post_Type {

	// Current plugin version
	var $version = 0.4;

	function __construct() {


		// Adds the Slideshow post type and taxonomies
		add_action( 'init', array( &$this, 'slideshow_init' ) );

		// Thumbnail support for Slideshow posts
		add_theme_support( 'post-thumbnails', array( 'slideshow' ) );

		// Adds columns in the admin view for thumbnail and taxonomies
		add_filter( 'manage_edit-slideshow_columns', array( &$this, 'slideshow_edit_columns' ) );
		add_action( 'manage_posts_custom_column', array( &$this, 'slideshow_column_display' ), 10, 2 );

		// Show Slideshow post counts in the dashboard
		add_action( 'right_now_content_table_end', array( &$this, 'add_slideshow_counts' ) );
	}

	function slideshow_init() {

		/**
		 * Enable the Slideshow custom post type
		 * http://codex.wordpress.org/Function_Reference/register_post_type
		 */

		$labels = array(
			'name' => __( 'Slideshow', 'okthemes' ),
			'singular_name' => __( 'Slideshow Item', 'okthemes' ),
			'add_new' => __( 'Add New Item', 'okthemes' ),
			'add_new_item' => __( 'Add New Slideshow Item', 'okthemes' ),
			'edit_item' => __( 'Edit Slideshow Item', 'okthemes' ),
			'new_item' => __( 'Add New Slideshow Item', 'okthemes' ),
			'view_item' => __( 'View Item', 'okthemes' ),
			'search_items' => __( 'Search Slideshow', 'okthemes' ),
			'not_found' => __( 'No slideshow items found', 'okthemes' ),
			'not_found_in_trash' => __( 'No slideshow items found in trash', 'okthemes' )
		);

		$args = array(
	    	'labels' => $labels,
	    	'public' => true,
			'supports' => array( 'title', 'thumbnail' ),
			'capability_type' => 'post',
			'rewrite' => array("slug" => "slideshow-item"), // Permalinks format
			'menu_position' => 5,
			'has_archive' => true
		);

		register_post_type( 'slideshow', $args );

		/**
		 * Register a taxonomy for Slideshow Categories
		 * http://codex.wordpress.org/Function_Reference/register_taxonomy
		 */

	    $taxonomy_slideshow_category_labels = array(
			'name' => __( 'Slideshow Categories', 'okthemes' ),
			'singular_name' => __( 'Slideshow Category', 'okthemes' ),
			'search_items' => __( 'Search Slideshow Categories', 'okthemes' ),
			'popular_items' => __( 'Popular Slideshow Categories', 'okthemes' ),
			'all_items' => __( 'All Slideshow Categories', 'okthemes' ),
			'parent_item' => __( 'Parent Slideshow Category', 'okthemes' ),
			'parent_item_colon' => __( 'Parent Slideshow Category:', 'okthemes' ),
			'edit_item' => __( 'Edit Slideshow Category', 'okthemes' ),
			'update_item' => __( 'Update Slideshow Category', 'okthemes' ),
			'add_new_item' => __( 'Add New Slideshow Category', 'okthemes' ),
			'new_item_name' => __( 'New Slideshow Category Name', 'okthemes' ),
			'separate_items_with_commas' => __( 'Separate Slideshow categories with commas', 'okthemes' ),
			'add_or_remove_items' => __( 'Add or remove Slideshow categories', 'okthemes' ),
			'choose_from_most_used' => __( 'Choose from the most used Slideshow categories', 'okthemes' ),
			'menu_name' => __( 'Slideshow Categories', 'okthemes' ),
	    );

	    $taxonomy_slideshow_category_args = array(
			'labels' => $taxonomy_slideshow_category_labels,
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => true,
			'hierarchical' => true,
			'rewrite' => array( 'slug' => 'slideshow_category' ),
			'query_var' => true
	    );

	    register_taxonomy( 'slideshow_category', array( 'slideshow' ), $taxonomy_slideshow_category_args );
	}

	function slideshow_edit_columns( $slideshow_columns ) {
		$slideshow_columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"title" => __('Title', 'okthemes'),
			"slideshow_thumbnail" => __('Thumbnail', 'okthemes'),
			"slideshow_category" => __('Category', 'okthemes'),
			"date" => __('Date', 'okthemes'),
		);
		return $slideshow_columns;
	}

	function slideshow_column_display( $slideshow_columns, $post_id ) {

		// Code from: http://wpengineer.com/display-post-thumbnail-post-page-overview

		switch ( $slideshow_columns ) {

			case 'slideshow_thumbnail':
				$width = (int) 110;
				$height = (int) 50;
				// Display the featured image in the column view if possible
				if ( has_post_thumbnail()) {
					the_post_thumbnail( array($width, $height) );
				} else {
					echo 'None';
				}
				break;

			// Display the Slideshow categories in the column view
			case "slideshow_category":

			if ( $category_list = get_the_term_list( $post_id, 'slideshow_category', '', ', ', '' ) ) {
				echo $category_list;
			} else {
				echo __('None', 'okthemes');
			}
			break;
		}
	}

	/**
	 * Adds taxonomy filters to the Slideshow admin page
	 * Code artfully lifed from http://pippinsplugins.com
	 */

	function slideshow_add_taxonomy_filters() {
		global $typenow;

		// An array of all the taxonomyies you want to display. Use the taxonomy name or slug
		$taxonomies = array( 'slideshow_category');

		// must set this to the post type you want the filter(s) displayed on
		if ( $typenow == 'slideshow' ) {

			foreach ( $taxonomies as $tax_slug ) {
				$current_tax_slug = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
				$tax_obj = get_taxonomy( $tax_slug );
				$tax_name = $tax_obj->labels->name;
				$terms = get_terms($tax_slug);
				if ( count( $terms ) > 0) {
					echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
					echo "<option value=''>$tax_name</option>";
					foreach ( $terms as $term ) {
						echo '<option value=' . $term->slug, $current_tax_slug == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
					}
					echo "</select>";
				}
			}
		}
	}

	/**
	 * Add Slideshow count to "Right Now" Dashboard Widget
	 */

	function add_slideshow_counts() {
	        if ( ! post_type_exists( 'slideshow' ) ) {
	             return;
	        }

	        $num_posts = wp_count_posts( 'slideshow' );
	        $num = number_format_i18n( $num_posts->publish );
	        $text = _n( 'Slideshow Item', 'Slideshow Items', intval($num_posts->publish) );
	        if ( current_user_can( 'edit_posts' ) ) {
	            $num = "<a href='edit.php?post_type=slideshow'>$num</a>";
	            $text = "<a href='edit.php?post_type=slideshow'>$text</a>";
	        }
	        echo '<td class="first b b-slideshow">' . $num . '</td>';
	        echo '<td class="t slideshow">' . $text . '</td>';
	        echo '</tr>';

	        if ($num_posts->pending > 0) {
	            $num = number_format_i18n( $num_posts->pending );
	            $text = _n( 'Slideshow Item Pending', 'Slideshow Items Pending', intval($num_posts->pending) );
	            if ( current_user_can( 'edit_posts' ) ) {
	                $num = "<a href='edit.php?post_status=pending&post_type=slideshow'>$num</a>";
	                $text = "<a href='edit.php?post_status=pending&post_type=slideshow'>$text</a>";
	            }
	            echo '<td class="first b b-slideshow">' . $num . '</td>';
	            echo '<td class="t slideshow">' . $text . '</td>';

	            echo '</tr>';
	        }
	}

}

new Slideshow_Post_Type;

endif;

if ( ! class_exists( 'Sponsors_Post_Type' ) ) :

class Sponsors_Post_Type {

	// Current plugin version
	var $version = 0.4;

	function __construct() {


		// Adds the Sponsors post type and taxonomies
		add_action( 'init', array( &$this, 'sponsors_init' ) );

		// Thumbnail support for Sponsors posts
		add_theme_support( 'post-thumbnails', array( 'sponsors' ) );

		// Adds columns in the admin view for thumbnail and taxonomies
		add_filter( 'manage_edit-sponsors_columns', array( &$this, 'sponsors_edit_columns' ) );
		add_action( 'manage_posts_custom_column', array( &$this, 'sponsors_column_display' ), 10, 2 );

		// Show Sponsors post counts in the dashboard
		add_action( 'right_now_content_table_end', array( &$this, 'add_sponsors_counts' ) );
	}

	function sponsors_init() {

		/**
		 * Enable the Sponsors custom post type
		 * http://codex.wordpress.org/Function_Reference/register_post_type
		 */

		$labels = array(
			'name' => __( 'Sponsors', 'okthemes' ),
			'singular_name' => __( 'Sponsors Item', 'okthemes' ),
			'add_new' => __( 'Add New Item', 'okthemes' ),
			'add_new_item' => __( 'Add New Sponsors Item', 'okthemes' ),
			'edit_item' => __( 'Edit Sponsors Item', 'okthemes' ),
			'new_item' => __( 'Add New Sponsors Item', 'okthemes' ),
			'view_item' => __( 'View Item', 'okthemes' ),
			'search_items' => __( 'Search Sponsors', 'okthemes' ),
			'not_found' => __( 'No sponsors items found', 'okthemes' ),
			'not_found_in_trash' => __( 'No sponsors items found in trash', 'okthemes' )
		);

		$args = array(
	    	'labels' => $labels,
	    	'public' => true,
			'supports' => array( 'title', 'thumbnail' ),
			'capability_type' => 'post',
			'rewrite' => array("slug" => "sponsors-page"), // Permalinks format
			'menu_position' => 5,
			'has_archive' => true
		);

		register_post_type( 'sponsors_pt', $args );
	}

	/**
	 * Add Columns to Sponsors Edit Screen
	 * http://wptheming.com/2010/07/column-edit-pages/
	 */

	function sponsors_edit_columns( $sponsors_columns ) {
		$sponsors_columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"title" => __('Title', 'okthemes'),
			"sponsors_thumbnail" => __('Thumbnail', 'okthemes'),
			"author" => __('Author', 'okthemes'),
			"date" => __('Date', 'okthemes'),
		);
		return $sponsors_columns;
	}

	function sponsors_column_display( $sponsors_columns, $post_id ) {

		// Code from: http://wpengineer.com/display-post-thumbnail-post-page-overview

		switch ( $sponsors_columns ) {

			// Display the thumbnail in the column view
			case 'sponsors_thumbnail':
				$width = (int) 120;
				$height = (int) 60;
				// Display the featured image in the column view if possible
				if ( has_post_thumbnail()) {
					the_post_thumbnail( array($width, $height) );
				} else {
					echo 'None';
				}
				break;

		}
	}

	/**
	 * Add Sponsors count to "Right Now" Dashboard Widget
	 */

	function add_sponsors_counts() {
	        if ( ! post_type_exists( 'sponsors_pt' ) ) {
	             return;
	        }

	        $num_posts = wp_count_posts( 'sponsors_pt' );
	        $num = number_format_i18n( $num_posts->publish );
	        $text = _n( 'Sponsors Item', 'Sponsors Items', intval($num_posts->publish) );
	        if ( current_user_can( 'edit_posts' ) ) {
	            $num = "<a href='edit.php?post_type=sponsors_pt'>$num</a>";
	            $text = "<a href='edit.php?post_type=sponsors_pt'>$text</a>";
	        }
	        echo '<td class="first b b-sponsors">' . $num . '</td>';
	        echo '<td class="t sponsors">' . $text . '</td>';
	        echo '</tr>';

	        if ($num_posts->pending > 0) {
	            $num = number_format_i18n( $num_posts->pending );
	            $text = _n( 'Sponsors Item Pending', 'Sponsors Items Pending', intval($num_posts->pending) );
	            if ( current_user_can( 'edit_posts' ) ) {
	                $num = "<a href='edit.php?post_status=pending&post_type=sponsors'>$num</a>";
	                $text = "<a href='edit.php?post_status=pending&post_type=sponsors'>$text</a>";
	            }
	            echo '<td class="first b b-sponsors">' . $num . '</td>';
	            echo '<td class="t sponsors">' . $text . '</td>';

	            echo '</tr>';
	        }
	}

}

new Sponsors_Post_Type;

endif;

if ( ! class_exists( 'Testimonials_Post_Type' ) ) :

class Testimonials_Post_Type {

	// Current plugin version
	var $version = 0.4;

	function __construct() {


		// Adds the Sponsors post type and taxonomies
		add_action( 'init', array( &$this, 'testimonials_init' ) );

		// Thumbnail support for Sponsors posts
		add_theme_support( 'post-thumbnails', array( 'testimonials' ) );

		// Adds columns in the admin view for thumbnail and taxonomies
		add_filter( 'manage_edit-testimonials_columns', array( &$this, 'testimonials_edit_columns' ) );
		add_action( 'manage_posts_custom_column', array( &$this, 'testimonials_column_display' ), 10, 2 );

		// Show Testimonials post counts in the dashboard
		add_action( 'right_now_content_table_end', array( &$this, 'add_testimonials_counts' ) );
	}

	function testimonials_init() {

		/**
		 * Enable the Sponsors custom post type
		 * http://codex.wordpress.org/Function_Reference/register_post_type
		 */

		$labels = array(
			'name' => __( 'Testimonials', 'okthemes' ),
			'singular_name' => __( 'Testimonials Item', 'okthemes' ),
			'add_new' => __( 'Add New Item', 'okthemes' ),
			'add_new_item' => __( 'Add New Testimonials Item', 'okthemes' ),
			'edit_item' => __( 'Edit Testimonials Item', 'okthemes' ),
			'new_item' => __( 'Add New Testimonials Item', 'okthemes' ),
			'view_item' => __( 'View Item', 'okthemes' ),
			'search_items' => __( 'Search Testimonials', 'okthemes' ),
			'not_found' => __( 'No testimonials items found', 'okthemes' ),
			'not_found_in_trash' => __( 'No testimonials items found in trash', 'okthemes' )
		);

		$args = array(
	    	'labels' => $labels,
	    	'public' => true,
			'supports' => array( 'title', 'thumbnail' ),
			'capability_type' => 'post',
			'rewrite' => array("slug" => "testimonials-page"), // Permalinks format
			'menu_position' => 5,
			'has_archive' => true
		);

		register_post_type( 'testimonials_pt', $args );
	}

	/**
	 * Add Columns to Testimonials Edit Screen
	 * http://wptheming.com/2010/07/column-edit-pages/
	 */

	function testimonials_edit_columns( $testimonials_columns ) {
		$testimonials_columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"title" => __('Title', 'okthemes'),
			"testimonials_thumbnail" => __('Thumbnail', 'okthemes'),
			"author" => __('Author', 'okthemes'),
			"date" => __('Date', 'okthemes'),
		);
		return $testimonials_columns;
	}

	function testimonials_column_display( $testimonials_columns, $post_id ) {

		// Code from: http://wpengineer.com/display-post-thumbnail-post-page-overview

		switch ( $testimonials_columns ) {

			// Display the thumbnail in the column view
			case 'testimonials_thumbnail':
				$width = (int) 35;
				$height = (int) 35;
				// Display the featured image in the column view if possible
				if ( has_post_thumbnail()) {
					the_post_thumbnail( array($width, $height) );
				} else {
					echo 'None';
				}
				break;

		}
	}

	/**
	 * Add Testimonials count to "Right Now" Dashboard Widget
	 */

	function add_testimonials_counts() {
	        if ( ! post_type_exists( 'testimonials_pt' ) ) {
	             return;
	        }

	        $num_posts = wp_count_posts( 'testimonials_pt' );
	        $num = number_format_i18n( $num_posts->publish );
	        $text = _n( 'Testimonials Item', 'Testimonials Items', intval($num_posts->publish) );
	        if ( current_user_can( 'edit_posts' ) ) {
	            $num = "<a href='edit.php?post_type=testimonials_pt'>$num</a>";
	            $text = "<a href='edit.php?post_type=testimonials_pt'>$text</a>";
	        }
	        echo '<td class="first b b-testimonials">' . $num . '</td>';
	        echo '<td class="t testimonials">' . $text . '</td>';
	        echo '</tr>';

	        if ($num_posts->pending > 0) {
	            $num = number_format_i18n( $num_posts->pending );
	            $text = _n( 'Testimonials Item Pending', 'Testimonials Items Pending', intval($num_posts->pending) );
	            if ( current_user_can( 'edit_posts' ) ) {
	                $num = "<a href='edit.php?post_status=pending&post_type=testimonials_pt'>$num</a>";
	                $text = "<a href='edit.php?post_status=pending&post_type=testimonials_pt'>$text</a>";
	            }
	            echo '<td class="first b b-testimonials">' . $num . '</td>';
	            echo '<td class="t testimonials">' . $text . '</td>';

	            echo '</tr>';
	        }
	}

}

new Testimonials_Post_Type;

endif;

if ( ! class_exists( 'Team_Post_Type' ) ) :

class Team_Post_Type {

	// Current plugin version
	var $version = 0.4;

	function __construct() {


		// Adds the Sponsors post type and taxonomies
		add_action( 'init', array( &$this, 'team_init' ) );

		// Thumbnail support for Sponsors posts
		add_theme_support( 'post-thumbnails', array( 'team' ) );

		// Adds columns in the admin view for thumbnail and taxonomies
		add_filter( 'manage_edit-team_columns', array( &$this, 'team_edit_columns' ) );
		add_action( 'manage_posts_custom_column', array( &$this, 'team_column_display' ), 10, 2 );

		// Show Team post counts in the dashboard
		add_action( 'right_now_content_table_end', array( &$this, 'add_team_counts' ) );
	}


	function team_init() {

		/**
		 * Enable the Sponsors custom post type
		 * http://codex.wordpress.org/Function_Reference/register_post_type
		 */

		$labels = array(
			'name' => __( 'Team', 'okthemes' ),
			'singular_name' => __( 'Team Item', 'okthemes' ),
			'add_new' => __( 'Add New Item', 'okthemes' ),
			'add_new_item' => __( 'Add New Team Item', 'okthemes' ),
			'edit_item' => __( 'Edit Team Item', 'okthemes' ),
			'new_item' => __( 'Add New Team Item', 'okthemes' ),
			'view_item' => __( 'View Item', 'okthemes' ),
			'search_items' => __( 'Search Team', 'okthemes' ),
			'not_found' => __( 'No team items found', 'okthemes' ),
			'not_found_in_trash' => __( 'No team items found in trash', 'okthemes' )
		);

		$args = array(
	    	'labels' => $labels,
	    	'public' => true,
			'supports' => array( 'title', 'thumbnail' ),
			'capability_type' => 'post',
			'rewrite' => array("slug" => "team-page"), // Permalinks format
			'menu_position' => 5,
			'has_archive' => true
		);

		register_post_type( 'team_pt', $args );
	}

	/**
	 * Add Columns to Testimonials Edit Screen
	 * http://wptheming.com/2010/07/column-edit-pages/
	 */

	function team_edit_columns( $team_columns ) {
		$team_columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"title" => __('Title', 'okthemes'),
			"team_thumbnail" => __('Thumbnail', 'okthemes'),
			"author" => __('Author', 'okthemes'),
			"date" => __('Date', 'okthemes'),
		);
		return $team_columns;
	}

	function team_column_display( $team_columns, $post_id ) {

		// Code from: http://wpengineer.com/display-post-thumbnail-post-page-overview

		switch ( $team_columns ) {

			// Display the thumbnail in the column view
			case 'team_thumbnail':
				$width = (int) 35;
				$height = (int) 35;
				// Display the featured image in the column view if possible
				if ( has_post_thumbnail()) {
					the_post_thumbnail( array($width, $height) );
				} else {
					echo 'None';
				}
				break;

		}
	}

	/**
	 * Add Testimonials count to "Right Now" Dashboard Widget
	 */

	function add_team_counts() {
	        if ( ! post_type_exists( 'team_pt' ) ) {
	             return;
	        }

	        $num_posts = wp_count_posts( 'team_pt' );
	        $num = number_format_i18n( $num_posts->publish );
	        $text = _n( 'Team Item', 'Team Items', intval($num_posts->publish) );
	        if ( current_user_can( 'edit_posts' ) ) {
	            $num = "<a href='edit.php?post_type=team_pt'>$num</a>";
	            $text = "<a href='edit.php?post_type=team_pt'>$text</a>";
	        }
	        echo '<td class="first b b-team">' . $num . '</td>';
	        echo '<td class="t team">' . $text . '</td>';
	        echo '</tr>';

	        if ($num_posts->pending > 0) {
	            $num = number_format_i18n( $num_posts->pending );
	            $text = _n( 'Team Item Pending', 'Team Items Pending', intval($num_posts->pending) );
	            if ( current_user_can( 'edit_posts' ) ) {
	                $num = "<a href='edit.php?post_status=pending&post_type=team_pt'>$num</a>";
	                $text = "<a href='edit.php?post_status=pending&post_type=team_pt'>$text</a>";
	            }
	            echo '<td class="first b b-team">' . $num . '</td>';
	            echo '<td class="t team">' . $text . '</td>';

	            echo '</tr>';
	        }
	}

}

// new Team_Post_Type;

endif;

if ( ! class_exists( 'Ads_Post_Type' ) ) :

class Ads_Post_Type {

	// Current plugin version
	var $version = 0.4;

	function __construct() {


		// Adds the Sponsors post type and taxonomies
		add_action( 'init', array( &$this, 'ads_init' ) );

		// Thumbnail support for Ads posts
		add_theme_support( 'post-thumbnails', array( 'ads' ) );

		// Adds columns in the admin view for thumbnail and taxonomies
		add_filter( 'manage_edit-ads_columns', array( &$this, 'ads_edit_columns' ) );
		add_action( 'manage_posts_custom_column', array( &$this, 'ads_column_display' ), 10, 2 );

		// Show Team post counts in the dashboard
		add_action( 'right_now_content_table_end', array( &$this, 'add_ads_counts' ) );
}

	function ads_init() {

		/**
		 * Enable the Sponsors custom post type
		 * http://codex.wordpress.org/Function_Reference/register_post_type
		 */

		$labels = array(
			'name' => __( 'Ads', 'okthemes' ),
			'singular_name' => __( 'Ads Item', 'okthemes' ),
			'add_new' => __( 'Add New Item', 'okthemes' ),
			'add_new_item' => __( 'Add New Ads Item', 'okthemes' ),
			'edit_item' => __( 'Edit Ads Item', 'okthemes' ),
			'new_item' => __( 'Add New Ads Item', 'okthemes' ),
			'view_item' => __( 'View Item', 'okthemes' ),
			'search_items' => __( 'Search Ads', 'okthemes' ),
			'not_found' => __( 'No Ads items found', 'okthemes' ),
			'not_found_in_trash' => __( 'No Ads items found in trash', 'okthemes' )
		);

		$args = array(
	    	'labels' => $labels,
	    	'public' => true,
			'supports' => array( 'title'),
			'capability_type' => 'post',
			'rewrite' => array("slug" => "ads-page"), // Permalinks format
			'menu_position' => 5,
			'has_archive' => true
		);

		register_post_type( 'ads_pt', $args );
	}

	/**
	 * Add Columns to Ads Edit Screen
	 * http://wptheming.com/2010/07/column-edit-pages/
	 */

	function ads_edit_columns( $ads_columns ) {
		$ads_columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"title" => __('Title', 'okthemes'),
			"ads_thumbnail" => __('Thumbnail', 'okthemes'),
			"author" => __('Author', 'okthemes'),
			"date" => __('Date', 'okthemes'),
		);
		return $ads_columns;
	}

	function ads_column_display( $ads_columns, $post_id ) {

		// Code from: http://wpengineer.com/display-post-thumbnail-post-page-overview

		switch ( $ads_columns ) {

			// Display the thumbnail in the column view
			case 'ads_thumbnail':
				$width = (int) 35;
				$height = (int) 35;
				// Display the featured image in the column view if possible
				if ( has_post_thumbnail()) {
					the_post_thumbnail( array($width, $height) );
				} else {
					echo 'None';
				}
				break;

		}
	}

	/**
	 * Add Ads count to "Right Now" Dashboard Widget
	 */

	function add_ads_counts() {
	        if ( ! post_type_exists( 'ads_pt' ) ) {
	             return;
	        }

	        $num_posts = wp_count_posts( 'ads_pt' );
	        $num = number_format_i18n( $num_posts->publish );
	        $text = _n( 'Ads Item', 'Ads Items', intval($num_posts->publish) );
	        if ( current_user_can( 'edit_posts' ) ) {
	            $num = "<a href='edit.php?post_type=ads_pt'>$num</a>";
	            $text = "<a href='edit.php?post_type=ads_pt'>$text</a>";
	        }
	        echo '<td class="first b b-ads">' . $num . '</td>';
	        echo '<td class="t ads">' . $text . '</td>';
	        echo '</tr>';

	        if ($num_posts->pending > 0) {
	            $num = number_format_i18n( $num_posts->pending );
	            $text = _n( 'Ads Item Pending', 'Ads Items Pending', intval($num_posts->pending) );
	            if ( current_user_can( 'edit_posts' ) ) {
	                $num = "<a href='edit.php?post_status=pending&post_type=ads_pt'>$num</a>";
	                $text = "<a href='edit.php?post_status=pending&post_type=ads_pt'>$text</a>";
	            }
	            echo '<td class="first b b-ads">' . $num . '</td>';
	            echo '<td class="t ads">' . $text . '</td>';

	            echo '</tr>';
	        }
	}

}

new Ads_Post_Type;

endif;

if ( ! class_exists( 'Faq_Post_Type' ) ) :

class Faq_Post_Type {

	// Current plugin version
	var $version = 0.4;

	function __construct() {


		// Adds the Faq post type and taxonomies
		add_action( 'init', array( &$this, 'faq_init' ) );

		// Thumbnail support for Faq posts
		add_theme_support( 'post-thumbnails', array( 'faq' ) );

		// Adds columns in the admin view for thumbnail and taxonomies
		add_filter( 'manage_edit-faq_columns', array( &$this, 'faq_edit_columns' ) );
		add_action( 'manage_posts_custom_column', array( &$this, 'faq_column_display' ), 10, 2 );

		// Show Faq post counts in the dashboard
		add_action( 'right_now_content_table_end', array( &$this, 'add_faq_counts' ) );
	}


	function faq_init() {

		/**
		 * Enable the Faq custom post type
		 * http://codex.wordpress.org/Function_Reference/register_post_type
		 */

		$labels = array(
			'name' => __( 'Faq', 'okthemes' ),
			'singular_name' => __( 'Faq Item', 'okthemes' ),
			'add_new' => __( 'Add New Item', 'okthemes' ),
			'add_new_item' => __( 'Add New Faq Item', 'okthemes' ),
			'edit_item' => __( 'Edit Faq Item', 'okthemes' ),
			'new_item' => __( 'Add New Faq Item', 'okthemes' ),
			'view_item' => __( 'View Item', 'okthemes' ),
			'search_items' => __( 'Search Faq', 'okthemes' ),
			'not_found' => __( 'No Faq items found', 'okthemes' ),
			'not_found_in_trash' => __( 'No Faq items found in trash', 'okthemes' )
		);

		$args = array(
	    	'labels' => $labels,
	    	'public' => true,
			'supports' => array( 'title', 'editor'),
			'capability_type' => 'post',
			'rewrite' => array("slug" => "faq-page"), // Permalinks format
			'menu_position' => 5,
			'has_archive' => true
		);

		register_post_type( 'faq_pt', $args );
	}

	/**
	 * Add Columns to Faq Edit Screen
	 * http://wptheming.com/2010/07/column-edit-pages/
	 */

	function faq_edit_columns( $faq_columns ) {
		$faq_columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"title" => __('Title', 'okthemes'),
			"author" => __('Author', 'okthemes'),
			"date" => __('Date', 'okthemes'),
		);
		return $faq_columns;
	}

	function faq_column_display( $faq_columns, $post_id ) {

		// Code from: http://wpengineer.com/display-post-thumbnail-post-page-overview
	}

	/**
	 * Add Ads count to "Right Now" Dashboard Widget
	 */

	function add_faq_counts() {
	        if ( ! post_type_exists( 'faq_pt' ) ) {
	             return;
	        }

	        $num_posts = wp_count_posts( 'faq_pt' );
	        $num = number_format_i18n( $num_posts->publish );
	        $text = _n( 'Faq Item', 'Faq Items', intval($num_posts->publish) );
	        if ( current_user_can( 'edit_posts' ) ) {
	            $num = "<a href='edit.php?post_type=faq_pt'>$num</a>";
	            $text = "<a href='edit.php?post_type=faq_pt'>$text</a>";
	        }
	        echo '<td class="first b b-faq">' . $num . '</td>';
	        echo '<td class="t faq">' . $text . '</td>';
	        echo '</tr>';

	        if ($num_posts->pending > 0) {
	            $num = number_format_i18n( $num_posts->pending );
	            $text = _n( 'Faq Item Pending', 'Faq Items Pending', intval($num_posts->pending) );
	            if ( current_user_can( 'edit_posts' ) ) {
	                $num = "<a href='edit.php?post_status=pending&post_type=faq_pt'>$num</a>";
	                $text = "<a href='edit.php?post_status=pending&post_type=faq_pt'>$text</a>";
	            }
	            echo '<td class="first b b-faq">' . $num . '</td>';
	            echo '<td class="t faq">' . $text . '</td>';

	            echo '</tr>';
	        }
	}

}

new Faq_Post_Type;

endif;