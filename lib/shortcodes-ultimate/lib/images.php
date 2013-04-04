<?php

	/**
	 * Retrieve images and links for Nivo slider, jCarousel or custom gallery
	 *
	 * @param string $source Images source: post=POST_ID (post attachments), category=CATEGORY_ID (posts thumbnails)
	 * @param string $link Images links: none, image, permalink, meta, caption
	 * @param string $size Retrieved images size $width x $height
	 * @param int $limit Number of images to retrieve
	 */
	function su_build_gallery( $source = 'post', $link = 'none', $size = '150x150', $limit = 10, $options = array( ) ) {

		// Thumbnail dimensions
		$dims = explode( 'x', $size );
		$width = $dims[0];
		$height = $dims[1];

		### SOURCE: POST ###
		if ( strpos( $source, 'post' ) !== false ) {

			// Specified post
			if ( strpos( $source, 'post=' ) !== false ) {
				$source = explode( '=', $source );
				$post_id = $source[1];
			}

			// Current post
			else {
				$post_id = get_the_ID();
			}

			// Get attachments
			$attachments = get_posts( array(
				'post_type' => 'attachment',
				'numberposts' => $limit,
				'order' => 'ASC',
				'orderby' => 'menu_order',
				'post_status' => null,
				'post_parent' => $post_id
				) );

			foreach ( $attachments as $attachment ) {
				if ( $link == 'caption' )
					$linked = $attachment->post_excerpt;
				elseif ( $link == 'permalink' )
					$linked = get_permalink( $attachment->ID );
				elseif ( $link == 'image' )
					$linked = $attachment->guid;
				else
					$linked = false;
				$imgsrc = wp_get_attachment_image_src($attachment->ID, 'full', $icon = false);
				$return[] = array(
					'id' => $attachment->ID,
					'image' => $attachment->guid,
					'thumbnail' =>$imgsrc[0],
					'link' => $linked,
					'name' => $attachment->post_title,
					'description' => $attachment->post_content
				);
			}
		}

		### SOURCE: CATEGORY ###
		elseif ( strpos( $source, 'cat=' ) !== false ) {
			$source = explode( '=', $source );
			$category_id = $source[1];

			// Get posts with images
			$images = get_posts( array(
				'numberposts' => $limit,
				'category' => $category_id
				) );

			foreach ( $images as $image ) {
				if ( $link == 'meta' )
					$linked = get_post_meta( $image->ID, 'link', true );
				elseif ( $link == 'permalink' )
					$linked = get_permalink( $image->ID );
				elseif ( $link == 'image' )
					$linked = su_get_post_image( $image->ID );
				else
					$linked = false;

				$return[] = array(
					'id' => $image->ID,
					'image' => su_get_post_image( $image->ID ),
					'thumbnail' => su_get_post_image( $image->ID ),
					'link' => $linked,
					'name' => $image->post_title,
					'description' => $image->post_excerpt
				);
			}
		}

		return $return;
	}

	/**
	 * Get post image by post id
	 *
	 * @global mixed $post
	 * @param int $post_id
	 * @return string Post image src
	 */
	function su_get_post_image( $post_id = false ) {

		global $post;

		$id = ( $post_id ) ? $post_id : $post->ID;
		$default = su_plugin_url() . '/images/thumbnail.png';
		$meta = 'thumbnail';

		// Get post attachments
		$attachments = get_posts( array(
			'post_type' => 'attachment',
			'numberposts' => 1,
			'order' => 'ASC',
			'post_status' => null,
			'post_parent' => $id
			) );

		### Post thumbnail ###
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'full' , false);
			$src = $image[0];

		### Meta field ###
		if ( get_post_meta( $id, $meta, true ) ) {
			$src = get_post_meta( $id, $meta, true );
		}

		### First post attachment ###
		elseif ( $attachments ) {
			$vars = get_object_vars( $attachments[0] );
			$src = $vars['guid'];
		}

		### First post_content image ###
		else {
			ob_start();
			ob_end_clean();
			$output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );
			$src = $matches[1][0];
		}

		### Default image ###
		if ( empty( $src ) ) {
			$src = $default;
		}

		return $src;
	}

?>