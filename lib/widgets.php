<?php
/*
Plugin Name: Simple Twitter Widget
Plugin URI: http://chipsandtv.com/
Description: A simple but powerful widget to display updates from a Twitter feed. Configurable and reliable.
Version: 1.04
Author: Matthias Siegel
Author URI: http://chipsandtv.com/


Copyright 2011  Matthias Siegel  (email : chipsandtv@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!class_exists('Twitter_Widget')) :

	class Twitter_Widget extends WP_Widget {

		function Twitter_Widget() {

			// Widget settings
			$widget_ops = array('classname' => 'twitter-widget', 'description' =>__('Display your latest tweets.', 'okthemes'));

			// Create the widget
			$this->WP_Widget('twitter-widget', 'Twitter Widget', $widget_ops);
		}


		function widget($args, $instance) {

			extract($args);

			global $interval;

			// User-selected settings
			$title = apply_filters('widget_title', $instance['title']);
			$username = $instance['username'];
			$posts = $instance['posts'];
			$interval = $instance['interval'];
			$date = $instance['date'];
			$datedisplay = $instance['datedisplay'];
			$datebreak = $instance['datebreak'];
			$clickable = $instance['clickable'];
			$hideerrors = $instance['hideerrors'];
			$encodespecial = $instance['encodespecial'];

			// Before widget (defined by themes)
			echo $before_widget;

			// Set internal Wordpress feed cache interval, by default it's 12 hours or so
			add_filter('wp_feed_cache_transient_lifetime', array(&$this, 'setInterval'));
			include_once(ABSPATH . WPINC . '/feed.php');

			// Get current upload directory
			$upload = wp_upload_dir();
			$cachefile = $upload['basedir'] . '/_twitter_' . $username . '.txt';

			// Title of widget (before and after defined by themes)
			if (!empty($title)) echo $before_title . $title . $after_title;

			// If cachefile doesn't exist or was updated more than $interval ago, create or update it, otherwise load from file
			if (!file_exists($cachefile) || (file_exists($cachefile) && (filemtime($cachefile) + $interval) < time())) :

				$feed = fetch_feed('http://api.twitter.com/1/statuses/user_timeline.rss?screen_name=' . $username);

				// This check prevents fatal errors — which can't be turned off in PHP — when feed updates fail
				if (method_exists($feed, 'get_items')) :

					$tweets = $feed->get_items(0, $posts);

					$result = '
						<ul>';

					foreach	($tweets as $t) :
						$result .= '
							<li>';

						// Get message
						$text = $t->get_description();

						// Get date/time and convert to Unix timestamp
						$time = strtotime($t->get_date());

						// If status update is newer than 1 day, print time as "... ago" instead of date stamp
						if ((abs(time() - $time)) < 86400) :
							$time = human_time_diff($time) . ' ago';
						else :
							$time = date(($date), $time);
						endif;

						// HTML encode special characters like ampersands
						if ($encodespecial) :
							$text = htmlspecialchars($text);
						endif;

						// Make links and Twitter names clickable
						if ($clickable) :
							// Match URLs
				    	$text = preg_replace('`\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))`', '<a href="$0">$0</a>', $text);

				    	// Match @name
				    	$text = preg_replace('/(@)([a-zA-Z0-9\_]+)/', '@<a href="https://twitter.com/$2">$2</a>', $text);

							// Match #hashtag
							$text = preg_replace('/(#)([a-zA-Z0-9\_]+)/', '#<a href="https://twitter.com/search/?q=$2">$2</a>', $text);
						endif;

			    	// Display date/time
						if ($datedisplay) $result .= '
								<span class="twitter-date"><a href="'. $t->get_permalink() .'">' . $time . '</a></span>' . ($datebreak ? '<br />' : '');

			    	// Display message without username prefix
						$prefixlen = strlen($username . ": ");
						$result .= '
								<span class="twitter-text">' . substr($text, $prefixlen, strlen($text) - $prefixlen) . '</span>';

						$result .= '
							</li>';
					endforeach;

					$result .= '
						</ul>
						';

					// Save updated feed to cache file
					@file_put_contents($cachefile, $result);

					// Display everything
					echo $result;


				// If loading from Twitter fails, try loading from the file instead
				else :
					if (file_exists($cachefile)) :
						$result = @file_get_contents($cachefile);
					endif;

					if (!empty($result)) :
						echo $result;

					// If loading from the file failed too, display error
					elseif (!$hideerrors) :
						echo '<p>Error while loading Twitter feed.</p>';
					endif;
				endif;


			// If cache file exists or if it was updated not long ago, load from file straight away
			else :
				$result = @file_get_contents($cachefile);

				if (!empty($result)) :
					echo $result;
				elseif (!$hideerrors) :
					echo '<p>Error while loading Twitter feed.</p>';			
				endif;
			endif;


			// After widget (defined by themes)
			echo $after_widget;
		}


		// Callback helper for the cache interval filter
		function setInterval() {

			global $interval;

			return $interval;
		}


		function update($new_instance, $old_instance) {

			$instance = $old_instance;

			$instance['title'] = $new_instance['title'];
			$instance['username'] = $new_instance['username'];
			$instance['posts'] = $new_instance['posts'];
			$instance['interval'] = $new_instance['interval'];
			$instance['date'] = $new_instance['date'];
			$instance['datedisplay'] = $new_instance['datedisplay'];
			$instance['datebreak'] = $new_instance['datebreak'];
			$instance['clickable'] = $new_instance['clickable'];
			$instance['hideerrors'] = $new_instance['hideerrors'];
			$instance['encodespecial'] = $new_instance['encodespecial'];

			// Delete the cache file when options were updated so the content gets refreshed on next page load
			$upload = wp_upload_dir();
			$cachefile = $upload['basedir'] . '/_twitter_' . $old_instance['username'] . '.txt';
			@unlink($cachefile);

			return $instance;
		}


		function form($instance) {

			// Set up some default widget settings
			$defaults = array('title' => __('Latest tweets', 'okthemes'), 'username' => '', 'posts' => 5, 'interval' => 1800, 'date' => 'j F Y', 'datedisplay' => true, 'datebreak' => true, 'clickable' => true, 'hideerrors' => true, 'encodespecial' => false);
			$instance = wp_parse_args((array) $instance, $defaults);
?>
				
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('username'); ?>">Your Twitter username:</label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" value="<?php echo $instance['username']; ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('posts'); ?>">Number of posts to display</label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" value="<?php echo $instance['posts']; ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('interval'); ?>">Update interval (in seconds):</label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('interval'); ?>" name="<?php echo $this->get_field_name('interval'); ?>" value="<?php echo $instance['interval']; ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('date'); ?>">Date format (see PHP <a href="http://php.net/manual/en/function.date.php">date</a>):</label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('date'); ?>" name="<?php echo $this->get_field_name('date'); ?>" value="<?php echo $instance['date']; ?>">
			</p>
								
			<p>
				<input class="checkbox" type="checkbox" <?php if ($instance['datedisplay']) echo 'checked="checked" '; ?>id="<?php echo $this->get_field_id('datedisplay'); ?>" name="<?php echo $this->get_field_name('datedisplay'); ?>">
				<label for="<?php echo $this->get_field_id('datedisplay'); ?>">Display date</label>
				
				<br>
				
				<input class="checkbox" type="checkbox" <?php if ($instance['datebreak']) echo 'checked="checked" '; ?>id="<?php echo $this->get_field_id('datebreak'); ?>" name="<?php echo $this->get_field_name('datebreak'); ?>">
				<label for="<?php echo $this->get_field_id('datebreak'); ?>">Add linebreak after date</label>
				
				<br>

				<input class="checkbox" type="checkbox" <?php if ($instance['clickable']) echo 'checked="checked" '; ?>id="<?php echo $this->get_field_id('clickable'); ?>" name="<?php echo $this->get_field_name('clickable'); ?>">
				<label for="<?php echo $this->get_field_id('clickable'); ?>">Clickable URLs, names &amp; hashtags</label>
				
				<br>

				<input class="checkbox" type="checkbox" <?php if ($instance['hideerrors']) echo 'checked="checked" '; ?>id="<?php echo $this->get_field_id('hideerrors'); ?>" name="<?php echo $this->get_field_name('hideerrors'); ?>">
				<label for="<?php echo $this->get_field_id('hideerrors'); ?>">Hide error message if update fails</label>

				<br>

				<input class="checkbox" type="checkbox" <?php if ($instance['encodespecial']) echo 'checked="checked" '; ?>id="<?php echo $this->get_field_id('encodespecial'); ?>" name="<?php echo $this->get_field_name('encodespecial'); ?>">
				<label for="<?php echo $this->get_field_id('encodespecial'); ?>">HTML-encode special characters</label>
			</p>
			
<?php
		}
	}	
endif;

// Register the plugin/widget
if (class_exists('Twitter_Widget')) :

	function loadTwitterWidget() {

		register_widget('Twitter_Widget');
	}

	add_action('widgets_init', 'loadTwitterWidget');

endif;


/*
Plugin Name: Guahan Web Flickr widget
Description: This plugin will load the most recent public pictures from any user's photostream
Author: Garth Henson
Version: 1.0
Author URI: http://www.guahanweb.com
*/

add_action('widgets_init', 'load_flickr');

function load_flickr()
{
	register_widget('flickr_Widget');
}

/**
 * Wordpress widget that will allow you to provide your Flickr API key, a Flickr username and a limit to 
 * retrieve the newest images from the user's Flickr photostream.
 *
 * @author Garth Henson <garth@guahanweb.com>
 * @package widgets
 * @version 1.0
 * @copyright Copyright 2010 Guahan Web
 */
class flickr_Widget extends WP_Widget
{
	/**
	 * Generate the widget
	 *
	 * @constructor
	 * @return GuahanFlickr_Widget
	 */
	function flickr_Widget()
	{
		$widget_ops = array('classname' => 'flickr', 'description' => 'Loads thumbnails from a specified user\'s Flickr photostream');
		$control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'flickr-widget');

		$this->WP_Widget('flickr-widget', 'Flickr Widget', $widget_ops, $control_ops);
	}

	/**
	 * The execution of the widget logic for display
	 *
	 * @param array $args The Wordpress arguments passed
	 * @param array $instance The current instance of this widget
	 */
	function widget($args, $instance)
	{
		extract($args);

		$title = apply_filters('widget_title', $instance['title']);
		$api   = '3efbb106c426c772090552a02403e7e0';
		$username = $instance['username'];
		$img_limit = !empty($instance['img_limit']) ? (int) $instance['img_limit'] : 6; // Default to 6 images

		echo $before_widget;
		if (!empty($title))
		{
			echo $before_title . $title . $after_title;
		}

		if (FALSE === ($photos = $this->flickrSearch($api, $username, $img_limit)))
		{
			echo "<p>A Flickr error occurred.</p>\n";
		}
		else
		{
			echo "<ul class=\"flickr\">\n";
			for ($i = 0; $i < $img_limit; $i++)
			{
					if (isset($photos[$i]))
					{
						$photo = $photos[$i];
						printf("<li><a href=\"%s\"><img src=\"%s\" class=\"rounded-img\" height=\"52\" width=\"52\" alt=\"%s\" /></a></li>", $photo['link'], $photo['src'], $photo['title']);
					}
					else {	echo "<li>&nbsp;</li>\n";	}
			}
			echo "</ul>\n";
		}

		echo $after_widget;
	}

	/**
	 * Update the content of the current widget instance
	 *
	 * @param array $new_instance The current version
	 * @param array $old_instance The previous version
	 * @return array
	 */
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['username'] = strip_tags($new_instance['username']);
		$instance['img_limit'] = preg_replace('|[^\d]|', '', $new_instance['img_limit']);

		return $instance;
	}

	/**
	 * Generate the admin form for this widget
	 *
	 * @param array $instance The current instance of this widget
	 */
	function form($instance)
	{
		$instance = wp_parse_args((array) $instance, array( 'title' => 'Flickr Photostream', 'username' => '', 'img_limit' => 5));
		$title = strip_tags($instance['title']);
		$username = strip_tags($instance['username']);
		if ( !isset($instance['img_limit']) || !$img_limit = (int) $instance['img_limit'] )
			$img_limit = 5;
?>
<p>
	<label for="<?php echo $this->get_field_id('title'); ?>">Title: </label><br />
	<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo htmlentities($instance['title'], ENT_QUOTES); ?>" style="width: 100%;" />
</p>
<p>
	<label for="<?php echo $this->get_field_id('username'); ?>">Flickr Username: </label><br />
	<input id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" value="<?php echo htmlentities($instance['username'], ENT_QUOTES); ?>" style="width: 100%;" />
</p>
<p>
	<label for="<?php echo $this->get_field_id('img_limit'); ?>">Number of pictures to display: </label><br />
	<input id="<?php echo $this->get_field_id('img_limit'); ?>" name="<?php echo $this->get_field_name('img_limit'); ?>" value="<?php echo $instance['img_limit']; ?>" />
</p>

<?php
	}

	/**
	 * Makes a call to the Flickr API to retrieve the user ID for the provided username
	 * 
	 * @param string $api The Flickr API Key from which to make the call
	 * @param string $username The Flickr username for which to search
	 * @return string
	 */
	function flickrFindByUsername($api, $username)
	{
		$url = sprintf("http://flickr.com/services/rest/?method=flickr.people.findByUsername&api_key=%s&username=%s", $api, $username);
		$xml = simplexml_load_file($url);
		return $xml->user['nsid'];
	}

	/**
	 * Makes a call to the Flickr API to retrieve the photos for the provided username
	 * 
	 * @param string $api The Flickr API Key
	 * @param string $username The Flickr username
	 * @param int $limit The number of images to return
	 * @return array|FALSE
	 */
	function flickrSearch($api, $username, $limit = 10)
	{
		$user_id = $this->flickrFindByUsername($api, $username);
		$url = sprintf("http://flickr.com/services/rest/?method=flickr.photos.search&api_key=%s&user_id=%s&per_page=%d", $api, $user_id, $limit);
		$xml = simplexml_load_file($url);

		if (isset($xml->photos))
		{
			$photos = array();
			$src_map = "http://farm1.static.flickr.com/%s/%s_%s_s.jpg";
			$lnk_map = "http://www.flickr.com/photos/%s/%s";
			foreach ($xml->photos->photo as $photo)
			{
				$photos[] = array(
					'src' => sprintf($src_map, $photo['server'], $photo['id'], $photo['secret']),
					'link' => sprintf($lnk_map, $user_id, $photo['id']),
					'title' => $photo['title']
				);
			}
	
			return $photos;
		}

		return FALSE;
	}
}

/**
 * Post type: Posts from blog
 * Displays recent posts including the post date.
 */
class posts_from_blog_widget extends WP_Widget {

	function posts_from_blog_widget() {
		$widget_ops = array('classname' => 'posts_from_blog_widget', 'description' => __( "Posts from blog with thumbnail", 'okthemes') );
		$this->WP_Widget('posts_from_blog_widget', __('Posts from blog', 'okthemes'), $widget_ops);
		$this->alt_option_name = 'posts_from_blog_widget';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('posts_from_blog_widget', 'widget');

		if ( !is_array($cache) ) $cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start(); extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Posts from blog', 'okthemes') : $instance['title']);
		if ( !$number = (int) $instance['number'] ) $number = 10;
		else if ( $number < 1 ) $number = 1;
		else if ( $number > 15 ) $number = 15;
		
		$carousel = ! empty( $instance['carousel'] ) ? '1' : '0';
		$rand_ID = rand();
		$r = new WP_Query(array('showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => 1));
		if ($r->have_posts()) : ?>
		<?php echo $before_widget; ?>
        
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
        <div class="clear"></div>
        <div class="custom-wrapper <?php if ($carousel) echo 'flexslider carousel uq'.$rand_ID; ?> blog-posts-wrapper widget-mode">
		<ul class="slides">
		<?php  while ($r->have_posts()) : $r->the_post(); ?>

		<li class="three">
		<?php the_post_thumbnail('blog-single'); ?>
        <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'okthemes' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
        <div class="entry-content">
			<p><?php echo cumico_custom_excerpt( get_the_content(), 20 ); ?></p>
        </div> 
        <div class="entry-utility">
			<?php echo get_the_date(); ?>
        </div>               
		</li>
		<?php endwhile; ?>
		</ul>
        </div>
        
        <?php  if ($carousel) { 
		echo '<script type="text/javascript">';
		echo 'jQuery(window).load(function(){jQuery(".custom-wrapper.flexslider.carousel.blog-posts-wrapper.widget-mode.uq'.$rand_ID.'").flexslider({';
		echo 'animation: "slide", controlNav: true, directionNav: false, itemWidth:220,slideshow: false';
		echo '});});</script>';
      	} ?>
        
        
		<?php echo $after_widget; ?>
		<?php wp_reset_query();  // Restore global post data stomped by the_post().
		endif;
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_add('posts_from_blog_widget', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'carousel' => 0, 'number' => '') );
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['carousel'] = $new_instance['carousel'] ? 1 : 0;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['posts_from_blog_widget']) )
			delete_option('posts_from_blog_widget');

		return $instance;
	}

	function flush_widget_cache() {wp_cache_delete('posts_from_blog_widget', 'widget');}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'carousel' => 0, 'number' => 5) );
		$title = strip_tags($instance['title']);
		if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
			$number = 5;
		$carousel = $instance['carousel'] ? 'checked="checked"' : '';	
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'okthemes'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', 'okthemes'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br />
		<small><?php _e('(at most 15)', 'okthemes'); ?></small></p>
        
        <p><label for="<?php echo $this->get_field_id('carousel'); ?>">Carousel mode</label>
        <input class="checkbox" type="checkbox" <?php if ($instance['carousel']) echo 'checked="checked" '; ?>id="<?php echo $this->get_field_id('carousel'); ?>" name="<?php echo $this->get_field_name('carousel'); ?>"><br />
        <small><?php _e('(Enable/Disable carousel mode)', 'okthemes'); ?></small></p>
		
<?php
	}
}

function posts_from_blog_widget_init() {register_widget('posts_from_blog_widget');}
add_action('widgets_init', 'posts_from_blog_widget_init');

/**
 * Post type: ADS
 * Displays recent posts including the post date.
 */
class ads_post_type_widget extends WP_Widget {

	function ads_post_type_widget() {
		$widget_ops = array('classname' => 'ads_post_type_widget', 'description' => __( "Ads post type posts", 'okthemes') );
		$this->WP_Widget('ads_post_type_widget', __('Post type: ADS', 'okthemes'), $widget_ops);
		$this->alt_option_name = 'ads_post_type_widget';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('ads_post_type_widget', 'widget');

		if ( !is_array($cache) ) $cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start(); extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Ads', 'okthemes') : $instance['title']);
		if ( !$number = (int) $instance['number'] ) $number = 10;
		else if ( $number < 1 ) $number = 1;
		else if ( $number > 15 ) $number = 15;
		
		$carousel = ! empty( $instance['carousel'] ) ? '1' : '0';
		$rand_ID = rand();
		$r = new WP_Query(array('showposts' => $number, 'post_type' => 'ads_pt', 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => 1));
		if ($r->have_posts()) : ?>
		<?php echo $before_widget; ?>
        
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
        <div class="clear"></div>
        <div class="custom-wrapper <?php if ($carousel) echo 'flexslider carousel uq'.$rand_ID; ?> ads-wrapper widget-mode">
		<ul class="slides">
		<?php  while ($r->have_posts()) : $r->the_post(); 
		$ads_upload = rwmb_meta( 'gg_ads_upload', 'type=plupload_image&size=full'); 
		$ads_link = rwmb_meta( 'gg_ads_link' ); 
		?>

		<li class="three">
		<div class="ads-holder">
		<?php 
			foreach ( $ads_upload as $ad_upload ) {
			echo '<div>';	
			if ($ads_link) { echo '<a href="'.$ads_link.'" title="'.$ad_upload["title"].'">'; }
			echo '<img src="'.$ad_upload["url"].'" width="'.$ad_upload["width"].'" height="'.$ad_upload["height"].'" alt="'.$ad_upload["alt"].'" />';
			if ($ads_link) { echo '</a>'; }
			echo '</div>';
			}
		?>
        </div>
		</li>
		<?php endwhile; ?>
		</ul>
        </div>
        
        <script type="text/javascript">
			jQuery(".ads-holder").hover( function() {jQuery(this).stop(true).delay(200).animate({left: -1*jQuery(".ads-holder div:first-child").width()}, 800);}, function(){jQuery(this).stop(true).animate({left: "0px"}, 800);});
        </script>
        
        <?php  if ($carousel) { 
		echo '<script type="text/javascript">';
		echo 'jQuery(window).load(function(){jQuery(".custom-wrapper.flexslider.carousel.widget-mode.uq'.$rand_ID.'").flexslider({';
		echo 'animation: "slide", controlNav: true, directionNav: false, itemWidth:220, slideshow: false';
		echo '});});</script>';
      	} ?>
        
		<?php echo $after_widget; ?>
		<?php wp_reset_query();  // Restore global post data stomped by the_post().
		endif;
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_add('ads_post_type_widget', $cache, 'widget');
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'carousel' => 0, 'number' => '') );
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['carousel'] = $new_instance['carousel'] ? 1 : 0;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['ads_post_type_widget']) )
			delete_option('ads_post_type_widget');

		return $instance;
	}

	function flush_widget_cache() {wp_cache_delete('ads_post_type_widget', 'widget');}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'carousel' => 0, 'number' => 5) );
		$title = strip_tags($instance['title']);
		if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
			$number = 5;
		$carousel = $instance['carousel'] ? 'checked="checked"' : '';	
			
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'okthemes'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', 'okthemes'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br />
		<small><?php _e('(at most 15)', 'okthemes'); ?></small></p>
        
        <p><label for="<?php echo $this->get_field_id('carousel'); ?>">Carousel mode</label>
        <input class="checkbox" type="checkbox" <?php if ($instance['carousel']) echo 'checked="checked" '; ?>id="<?php echo $this->get_field_id('carousel'); ?>" name="<?php echo $this->get_field_name('carousel'); ?>"><br />
        <small><?php _e('(Enable/Disable carousel mode)', 'okthemes'); ?></small></p>
		
<?php
	}
}

function ads_post_type_widget_init() {register_widget('ads_post_type_widget');}
add_action('widgets_init', 'ads_post_type_widget_init');

/**
 * Post type: Team
 * Displays recent posts including the post date.
 */
class team_post_type_widget extends WP_Widget {

	function team_post_type_widget() {
		$widget_ops = array('classname' => 'team_post_type_widget', 'description' => __( "Team post type posts", 'okthemes') );
		$this->WP_Widget('team_post_type_widget', __('Post type: Team', 'okthemes'), $widget_ops);
		$this->alt_option_name = 'team_post_type_widget';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('team_post_type_widget', 'widget');

		if ( !is_array($cache) ) $cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start(); extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Team', 'okthemes') : $instance['title']);
		if ( !$number = (int) $instance['number'] ) $number = 10;
		else if ( $number < 1 ) $number = 1;
		else if ( $number > 15 ) $number = 15;
		
		$carousel = ! empty( $instance['carousel'] ) ? '1' : '0';
		$rand_ID = rand();
		$r = new WP_Query(array('showposts' => $number, 'post_type' => 'team_pt', 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => 1));
		if ($r->have_posts()) : ?>
		<?php echo $before_widget; ?>
        
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
        <div class="clear"></div>
        <div class="custom-wrapper <?php if ($carousel) echo 'flexslider carousel uq'.$rand_ID; ?> team-wrapper widget-mode">
		<ul class="slides">
		<?php  while ($r->have_posts()) : $r->the_post(); 
		$team_member_position = rwmb_meta('gg_team_member_position');
		$team_member_desc = rwmb_meta('gg_team_member_desc');
		$team_member_twitter = rwmb_meta('gg_team_member_twitter');
		$team_member_facebook = rwmb_meta('gg_team_member_facebook');
		$team_member_flickr = rwmb_meta('gg_team_member_flickr');
		$team_member_linkedin = rwmb_meta('gg_team_member_linkedin');
		$team_member_youtube = rwmb_meta('gg_team_member_youtube');
		$team_member_website = rwmb_meta('gg_team_member_website');
		?>

		<li class="three">
		<?php 
			echo ' '.get_the_post_thumbnail( get_the_ID(), "team-thumbnail" ).' ';	
			echo '<div class="entry-summary">';
			echo '<h2 class="entry-title portfolio">'.get_the_title().'</h2>';
			
			if ($team_member_position) {
				echo '<p class="member-position">'.$team_member_position.'</p>';
			}
			if ($team_member_desc) {
				echo '<p>'.$team_member_desc.'</p>';
			}
			
			echo '<ul class="member-social">';
			if ($team_member_twitter) {
				echo '<li><a class="member-twitter" href="'.$team_member_twitter.'">Twitter</a></li>';
			}
			if ($team_member_facebook) {
				echo '<li><a class="member-facebook" href="'.$team_member_facebook.'">Facebook</a></li>';
			}
			if ($team_member_flickr) {
				echo '<li><a class="member-flickr" href="'.$team_member_flickr.'">Flickr</a></li>';
			}
			if ($team_member_linkedin) {
				echo '<li><a class="member-linkedin" href="'.$team_member_linkedin.'">Linkedin</a></li>';
			}
			if ($team_member_youtube) {
				echo '<li><a class="member-youtube" href="'.$team_member_youtube.'">Youtube</a></li>';
			}
			if ($team_member_website) {
				echo '<li><a class="member-website" href="'.$team_member_website.'">Personal website</a></li>';
			}
			echo '</ul>';
		?>
		</li>
		<?php endwhile; ?>
		</ul>
        </div>
        
        <?php  if ($carousel) { 
		echo '<script type="text/javascript">';
		echo 'jQuery(window).load(function(){jQuery(".custom-wrapper.flexslider.carousel.widget-mode.uq'.$rand_ID.'").flexslider({';
		echo 'animation: "slide", controlNav: true, directionNav: false, itemWidth:220,slideshow: false';
		echo '});});</script>';
      	} ?>
        
        
		<?php echo $after_widget; ?>
		<?php wp_reset_query();  // Restore global post data stomped by the_post().
		endif;
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_add('team_post_type_widget', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'carousel' => 0, 'number' => '') );
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['carousel'] = $new_instance['carousel'] ? 1 : 0;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['team_post_type_widget']) )
			delete_option('team_post_type_widget');

		return $instance;
	}

	function flush_widget_cache() {wp_cache_delete('team_post_type_widget', 'widget');}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'carousel' => 0, 'number' => 5) );
		$title = strip_tags($instance['title']);
		if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
			$number = 5;
		$carousel = $instance['carousel'] ? 'checked="checked"' : '';	
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'okthemes'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', 'okthemes'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br />
		<small><?php _e('(at most 15)', 'okthemes'); ?></small></p>
        
        <p><label for="<?php echo $this->get_field_id('carousel'); ?>">Carousel mode</label>
        <input class="checkbox" type="checkbox" <?php if ($instance['carousel']) echo 'checked="checked" '; ?>id="<?php echo $this->get_field_id('carousel'); ?>" name="<?php echo $this->get_field_name('carousel'); ?>"><br />
        <small><?php _e('(Enable/Disable carousel mode)', 'okthemes'); ?></small></p>
		
<?php
	}
}

function team_post_type_widget_init() {register_widget('team_post_type_widget');}
add_action('widgets_init', 'team_post_type_widget_init');

/**
 * Post type: testimonials
 * Displays recent posts including the post date.
 */
class testimonials_post_type_widget extends WP_Widget {

	function testimonials_post_type_widget() {
		$widget_ops = array('classname' => 'testimonials_post_type_widget', 'description' => __( "Testimonials post type posts", 'okthemes') );
		$this->WP_Widget('testimonials_post_type_widget', __('Post type: Testimonials', 'okthemes'), $widget_ops);
		$this->alt_option_name = 'testimonials_post_type_widget';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('testimonials_post_type_widget', 'widget');

		if ( !is_array($cache) ) $cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start(); extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('testimonials', 'okthemes') : $instance['title']);
		if ( !$number = (int) $instance['number'] ) $number = 10;
		else if ( $number < 1 ) $number = 1;
		else if ( $number > 15 ) $number = 15;
		
		$carousel = ! empty( $instance['carousel'] ) ? '1' : '0';
		$rand_ID = rand();
		$r = new WP_Query(array('showposts' => $number, 'post_type' => 'testimonials_pt', 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => 1));
		if ($r->have_posts()) : ?>
		<?php echo $before_widget; ?>
        
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
        <div class="clear"></div>
        <div class="custom-wrapper <?php if ($carousel) echo 'flexslider carousel uq'.$rand_ID; ?> testimonials-wrapper widget-mode">
		<ul class="slides">
		<?php  while ($r->have_posts()) : $r->the_post(); 
		$testimonials_author_name = rwmb_meta('gg_testimonials_author_name');
		$testimonials_author_website = rwmb_meta('gg_testimonials_author_website');
		$testimonials_content = rwmb_meta('gg_testimonials_content');
		?>

		<li class="three">
		<?php 
			echo '<blockquote>'.$testimonials_content.'</blockquote>';
			echo '<div class="entry-summary">';
			echo ' '.get_the_post_thumbnail( get_the_ID(), "testimonials-thumbnail", array('class' => 'tesimonial-author-img') ).' ';	
			if ($testimonials_author_name) {
				echo '<p class="author-name">'.$testimonials_author_name.'</p>';
			}
			if ($testimonials_author_name) {
				echo '<a class="author-website" href="'.$testimonials_author_website.'">Website</a>';
			}
	 
			echo '</div>';
		?>
		</li>
		<?php endwhile; ?>
		</ul>
        </div>
        
        <?php  if ($carousel) { 
		echo '<script type="text/javascript">';
		echo 'jQuery(window).load(function(){jQuery(".custom-wrapper.flexslider.carousel.uq'.$rand_ID.'").flexslider({';
		echo 'animation: "slide", controlNav: true, directionNav: false, itemWidth:220, slideshow: false';
		echo '});});</script>';
      	} ?>
        
        
		<?php echo $after_widget; ?>
		<?php wp_reset_query();  // Restore global post data stomped by the_post().
		endif;
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_add('testimonials_post_type_widget', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'carousel' => 0, 'number' => '') );
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['carousel'] = $new_instance['carousel'] ? 1 : 0;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['testimonials_post_type_widget']) )
			delete_option('testimonials_post_type_widget');

		return $instance;
	}

	function flush_widget_cache() {wp_cache_delete('testimonials_post_type_widget', 'widget');}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'carousel' => 0, 'number' => 5) );
		$title = strip_tags($instance['title']);
		if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
			$number = 5;
		$carousel = $instance['carousel'] ? 'checked="checked"' : '';	
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'okthemes'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', 'okthemes'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br />
		<small><?php _e('(at most 15)', 'okthemes'); ?></small></p>
        
        <p><label for="<?php echo $this->get_field_id('carousel'); ?>">Carousel mode</label>
        <input class="checkbox" type="checkbox" <?php if ($instance['carousel']) echo 'checked="checked" '; ?>id="<?php echo $this->get_field_id('carousel'); ?>" name="<?php echo $this->get_field_name('carousel'); ?>"><br />
        <small><?php _e('(Enable/Disable carousel mode)', 'okthemes'); ?></small></p>
		
<?php
	}
}

function testimonials_post_type_widget_init() {register_widget('testimonials_post_type_widget');}
add_action('widgets_init', 'testimonials_post_type_widget_init');

/**
 * Post type: sponsors
 * Displays recent posts including the post date.
 */
class sponsors_post_type_widget extends WP_Widget {

	function sponsors_post_type_widget() {
		$widget_ops = array('classname' => 'sponsors_post_type_widget', 'description' => __( "Sponsors post type posts", 'okthemes') );
		$this->WP_Widget('sponsors_post_type_widget', __('Post type: Sponsors', 'okthemes'), $widget_ops);
		$this->alt_option_name = 'sponsors_post_type_widget';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('sponsors_post_type_widget', 'widget');

		if ( !is_array($cache) ) $cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start(); extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('sponsors', 'okthemes') : $instance['title']);
		if ( !$number = (int) $instance['number'] ) $number = 10;
		else if ( $number < 1 ) $number = 1;
		else if ( $number > 15 ) $number = 15;
		
		$carousel = ! empty( $instance['carousel'] ) ? '1' : '0';
		$rand_ID = rand();
		$r = new WP_Query(array('showposts' => $number, 'post_type' => 'sponsors_pt', 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => 1));
		if ($r->have_posts()) : ?>
		<?php echo $before_widget; ?>
        
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
        <div class="clear"></div>
        <div class="custom-wrapper <?php if ($carousel) echo 'flexslider carousel uq'.$rand_ID; ?> sponsors-wrapper widget-mode">
		<ul class="slides">
		<?php  while ($r->have_posts()) : $r->the_post(); 
		$sponsors_external_link = rwmb_meta('gg_sponsors_external_link');
		$sponsors_hide_title = rwmb_meta('gg_sponsors_hide_title');
		?>

		<li class="three">
		<?php 
			if ($sponsors_external_link) { echo '<a href="'.$sponsors_external_link.'">';	}
			echo ' '.get_the_post_thumbnail( get_the_ID(), "sponsors-thumbnail" ).' ';
			if ($sponsors_external_link) { echo '</a>'; }
			
			if (!$sponsors_hide_title) {
			echo '<div class="entry-summary">';	
			echo '<h2 class="entry-title portfolio">';	
				if ($sponsors_external_link) { echo '<a href="'.$sponsors_external_link.'">'.the_title().'</a>';	}
			echo '</h2>';    
			echo '</div>';
			}
		?>
		</li>
		<?php endwhile; ?>
		</ul>
        </div>
        
        <?php  if ($carousel) { 
		echo '<script type="text/javascript">';
		echo 'jQuery(window).load(function(){jQuery(".custom-wrapper.flexslider.carousel.widget-mode.uq'.$rand_ID.'").flexslider({';
		echo 'animation: "slide", controlNav: true, directionNav: false, itemWidth:220, slideshow: false';
		echo '});});</script>';
      	} ?>
        
        
		<?php echo $after_widget; ?>
		<?php wp_reset_query();  // Restore global post data stomped by the_post().
		endif;
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_add('sponsors_post_type_widget', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'carousel' => 0, 'number' => '') );
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['carousel'] = $new_instance['carousel'] ? 1 : 0;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['sponsors_post_type_widget']) )
			delete_option('sponsors_post_type_widget');

		return $instance;
	}

	function flush_widget_cache() {wp_cache_delete('sponsors_post_type_widget', 'widget');}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'carousel' => 0, 'number' => 5) );
		$title = strip_tags($instance['title']);
		if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
			$number = 5;
		$carousel = $instance['carousel'] ? 'checked="checked"' : '';	
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'okthemes'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', 'okthemes'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br />
		<small><?php _e('(at most 15)', 'okthemes'); ?></small></p>
        
        <p><label for="<?php echo $this->get_field_id('carousel'); ?>">Carousel mode</label>
        <input class="checkbox" type="checkbox" <?php if ($instance['carousel']) echo 'checked="checked" '; ?>id="<?php echo $this->get_field_id('carousel'); ?>" name="<?php echo $this->get_field_name('carousel'); ?>"><br />
        <small><?php _e('(Enable/Disable carousel mode)', 'okthemes'); ?></small></p>
		
<?php
	}
}

function sponsors_post_type_widget_init() {register_widget('sponsors_post_type_widget');}
add_action('widgets_init', 'sponsors_post_type_widget_init');

/**
 * Post type: portfolio
 * Displays recent posts including the post date.
 */
class portfolio_post_type_widget extends WP_Widget {

	function portfolio_post_type_widget() {
		$widget_ops = array('classname' => 'portfolio_post_type_widget', 'description' => __( "portfolio post type posts", 'okthemes') );
		$this->WP_Widget('portfolio_post_type_widget', __('Post type: portfolio', 'okthemes'), $widget_ops);
		$this->alt_option_name = 'portfolio_post_type_widget';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('portfolio_post_type_widget', 'widget');

		if ( !is_array($cache) ) $cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start(); extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('portfolio', 'okthemes') : $instance['title']);
		if ( !$number = (int) $instance['number'] ) $number = 10;
		else if ( $number < 1 ) $number = 1;
		else if ( $number > 15 ) $number = 15;
		
		$carousel = ! empty( $instance['carousel'] ) ? '1' : '0';
		$rand_ID = rand();
		$r = new WP_Query(array('showposts' => $number, 'post_type' => 'portfolio_pt', 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => 1));
		if ($r->have_posts()) : ?>
		<?php echo $before_widget; ?>
        
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
        <div class="clear"></div>
        <div class="custom-wrapper <?php if ($carousel) echo 'flexslider carousel uq'.$rand_ID; ?> portfolio-wrapper widget-mode">
		<ul class="slides">
		<?php  while ($r->have_posts()) : $r->the_post(); 
		$portfolio_external_link = rwmb_meta('gg_portfolio_external_link');
		$portfolio_hide_title = rwmb_meta('gg_portfolio_hide_title');
		?>

		<li class="three">
		<?php 
			echo ' '.get_the_post_thumbnail( get_the_ID(), 'portfolio-thumbnail-3-col' ).' ';
			echo '<div class="entry-summary">';
			echo '<h2 class="entry-title portfolio"> <a href="' . get_permalink(get_the_ID()) . '" title="' . get_the_title() . '" class="post-title">' . get_the_title() . '</a></h2>';
			echo '<div class="short-description"> '.get_the_excerpt().' </div></div>';
			
			echo '<div class="entry-lightbox">';
			
			$portfolio_lightbox_images = rwmb_meta( 'gg_portfolio_lightbox_image', 'type=thickbox_image' );
			$portfolio_video_link = rwmb_meta( 'gg_portfolio_post_video_link' );
			$portfolio_external_link = rwmb_meta( 'gg_portfolio_post_external_link' );
			
			$videos = array( '.mp4', '.MP4', '.flv', '.FLV', '.swf', '.SWF', '.mov', '.MOV', 'youtube.com', 'vimeo.com' );
			$videos_found = false;
			
			foreach ($videos as $video_ext) {
			  if (strrpos($portfolio_video_link, $video_ext)) {
				$videos_found = true;
				break;
			  }
			}
			
			if (!empty($portfolio_lightbox_images)) { //check if array is empty
				  $i = 1; //display only the first image		
				  foreach ( $portfolio_lightbox_images as $portfolio_lightbox_image )
				  {
					  echo '<a class="image-lightbox" href="'.$portfolio_lightbox_image["full_url"].'" data-rel="prettyPhoto[mixed]">View image in lightbox</a>';
					  if($i == 1) break; //display only the first image
				  }
			} 
			if ( $portfolio_video_link) {
				
				  if ($videos_found) {
					  echo '<a class="video-lightbox" href="'.$portfolio_video_link.'" data-rel="prettyPhoto[mixed]">View video in lightbox</a>';
				  }
				  
			 } 
			 
			 if ( $portfolio_external_link) {
					  echo '<a class="external-link" href="'.$portfolio_external_link.'">Go to link</a>';
			 }
			
			echo '</div>';
		?>
		</li>
		<?php endwhile; ?>
		</ul>
        <div class="flex-control-nav-container"></div>
        </div>

        <?php  if ($carousel) { 
		echo '<script type="text/javascript">';
		echo 'jQuery(window).load(function(){jQuery(".custom-wrapper.flexslider.carousel.uq'.$rand_ID.'").flexslider({';
		echo 'animation: "slide", controlNav: true, directionNav: false, itemWidth:220, slideshow: false,fixedHeightMiddleAlign: true';
		echo '});});</script>';
      	} ?>
        
		<?php echo $after_widget; ?>
		<?php wp_reset_query();  // Restore global post data stomped by the_post().
		endif;
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_add('portfolio_post_type_widget', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'carousel' => 0, 'number' => '') );
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['carousel'] = $new_instance['carousel'] ? 1 : 0;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['portfolio_post_type_widget']) )
			delete_option('portfolio_post_type_widget');

		return $instance;
	}

	function flush_widget_cache() {wp_cache_delete('portfolio_post_type_widget', 'widget');}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'carousel' => 0, 'number' => 5) );
		$title = strip_tags($instance['title']);
		if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
			$number = 5;
		$carousel = $instance['carousel'] ? 'checked="checked"' : '';	
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'okthemes'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', 'okthemes'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br />
		<small><?php _e('(at most 15)', 'okthemes'); ?></small></p>
        
        <p><label for="<?php echo $this->get_field_id('carousel'); ?>">Carousel mode</label>
        <input class="checkbox" type="checkbox" <?php if ($instance['carousel']) echo 'checked="checked" '; ?>id="<?php echo $this->get_field_id('carousel'); ?>" name="<?php echo $this->get_field_name('carousel'); ?>"><br />
        <small><?php _e('(Enable/Disable carousel mode)', 'okthemes'); ?></small></p>
		
<?php
	}
}

function portfolio_post_type_widget_init() {register_widget('portfolio_post_type_widget');}
add_action('widgets_init', 'portfolio_post_type_widget_init');


/**
 * Social Icons Widget
 * 
 */

add_action('widgets_init', 'socialicons_load_widgets');

function socialicons_load_widgets()
{
	register_widget('Socialicons_Widget');
}

class Socialicons_Widget extends WP_Widget {
	
	function Socialicons_Widget()
	{
		$widget_ops = array('classname' => 'social-icons', 'description' => '');

		$control_ops = array('id_base' => 'social-icons-widget');

		$this->WP_Widget('social-icons-widget', 'Social Icons Widget', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);

		echo $before_widget;

		if($title) {
			echo  $before_title.$title.$after_title;
		} ?>
		<div class="social-icons-widget">
		<ul>
		<?php if(of_get_option('rss_link')): ?>
		<li><a class="social-rss" href="<?php echo of_get_option('rss_link'); ?>" target="_blank"></a></li>
		<?php endif; ?>
		<?php if(of_get_option('facebook_link')): ?>
		<li><a class="social-facebook" href="<?php echo of_get_option('facebook_link'); ?>" target="_blank"></a></li>
		<?php endif; ?>
		<?php if(of_get_option('twitter_link')): ?>
		<li><a class="social-twitter" href="<?php echo of_get_option('twitter_link'); ?>" target="_blank"></a></li>
		<?php endif; ?>
		<?php if(of_get_option('skype_link')): ?>
		<li><a class="social-skype" href="<?php echo of_get_option('skype_link'); ?>" target="_blank"></a></li>
		<?php endif; ?>
		<?php if(of_get_option('vimeo_link')): ?>
		<li><a class="social-vimeo" href="<?php echo of_get_option('vimeo_link'); ?>" target="_blank"></a></li>
		<?php endif; ?>
		<?php if(of_get_option('linkedin_link')): ?>
		<li><a class="social-linkedin" href="<?php echo of_get_option('linkedin_link'); ?>" target="_blank"></a></li>
		<?php endif; ?>
		<?php if(of_get_option('dribble_link')): ?>
		<li><a class="social-dribble" href="<?php echo of_get_option('dribble_link'); ?>" target="_blank"></a></li>
		<?php endif; ?>
		<?php if(of_get_option('forrst_link')): ?>
		<li><a class="social-forrst" href="<?php echo of_get_option('forrst_link'); ?>" target="_blank"></a></li>
		<?php endif; ?>
		<?php if(of_get_option('flickr_link')): ?>
		<li><a class="social-flickr" href="<?php echo of_get_option('flickr_link'); ?>" target="_blank"></a></li>
		<?php endif; ?>
		<?php if(of_get_option('google_link')): ?>
		<li><a class="social-google" href="<?php echo of_get_option('google_link'); ?>" target="_blank"></a></li>
		<?php endif; ?>
		<?php if(of_get_option('youtube_link')): ?>
		<li><a href="<?php echo of_get_option('youtube_link'); ?>" target="_blank" class="social-youtube"></a></li>
		<?php endif; ?>
        <?php if(of_get_option('tumblr_link')): ?>
		<li><a href="<?php echo of_get_option('tumblr_link'); ?>" target="_blank" class="social-tumblr"></a></li>
		<?php endif; ?>
        <?php if(of_get_option('behance_link')): ?>
		<li><a href="<?php echo of_get_option('behance_link'); ?>" target="_blank" class="social-behance"></a></li>
		<?php endif; ?>
        <?php if(of_get_option('personal_link')): ?>
		<li><a href="<?php echo of_get_option('personal_link'); ?>" target="_blank" class="social-personal"></a></li>
		<?php endif; ?>
		</ul>
		
		<div class="clearfix"></div>
		</div>
		<?php echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => 'Social Icons');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
	<?php
	}
}

/**
 * Plugin Name: CodeDecoded - Contact Widget
 * Description: A plugin containing a Contact us widget example by CodeDecoded.
 * Version: 0.1
 * Author: CodeDecoded
 * Author URI: http://www.facebook.com/pages/Code-Decoded/203711979669931
 *
 * 
 */

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'example_load_widgets' );

/**
 * Register our widget.
 *
 * @since 0.1
 */
function example_load_widgets() {
	register_widget( 'Contact_Widget' );
}

/**
 * Contact Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class Contact_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Contact_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'contact', 'description' => __('Contact us Widget', 'okthemes') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'contact-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'contact-widget', __('Contact Widget', 'okthemes'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$address = $instance['address'];
		$phone =  $instance['phone'];
		$fax = $instance['fax'];
		$email = $instance['email'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
			echo '<ul>';
		/* Display name from widget settings if one was input. */
		if ( $address )
			printf( '<li class="address"><span>Address</span>' . __('%1$s', 'okthemes') . '</li>', $address );
		if ( $phone )
			printf( '<li class="phone"><span>Phone</span>' . __('%1$s', 'okthemes') . '</li>', $phone );
		if ( $fax )
			printf( '<li class="fax"><span>Fax</span>' . __('%1$s', 'okthemes') . '</li>', $fax );
		if ( $email )
			printf( '<li class="email"><span>Email</span>' . __('%1$s', 'okthemes') . '</li>', $email );
		echo '</ul>';	
		
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['address'] = strip_tags( $new_instance['address'] );
		$instance['phone'] = strip_tags( $new_instance['phone'] );
		$instance['fax'] = strip_tags( $new_instance['fax'] );
		$instance['email'] = strip_tags( $new_instance['email'] );

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Contact Us', 'okthemes'), 'address' => __('New York, NY, Usa', 'okthemes'), 'phone' => __('(40) - 555 555 5555 ', 'okthemes'), 'fax' => __('(40) - 555 555 5556', 'okthemes'), 'email' => __('email@email.com', 'okthemes') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'okthemes'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<!-- Your Address: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'address' ); ?>"><?php _e('Your Address:', 'okthemes'); ?></label>
			<input id="<?php echo $this->get_field_id( 'address' ); ?>" name="<?php echo $this->get_field_name( 'address' ); ?>" value="<?php echo $instance['address']; ?>" style="width:100%;" />
		</p>
		<!-- Your Phone: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'phone' ); ?>"><?php _e('Your Phone:', 'okthemes'); ?></label>
			<input id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" value="<?php echo $instance['phone']; ?>" style="width:100%;" />
		</p>
		<!-- Your Fax: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'fax' ); ?>"><?php _e('Your Fax:', 'okthemes'); ?></label>
			<input id="<?php echo $this->get_field_id( 'fax' ); ?>" name="<?php echo $this->get_field_name( 'fax' ); ?>" value="<?php echo $instance['fax']; ?>" style="width:100%;" />
		</p>
		<!-- Your E-mail: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php _e('Your Email:', 'okthemes'); ?></label>
			<input id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" value="<?php echo $instance['email']; ?>" style="width:100%;" />
		</p>

	<?php
	}
}

?>