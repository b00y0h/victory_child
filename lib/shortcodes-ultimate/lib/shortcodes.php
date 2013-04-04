<?php

	/**
	 * Shortcode: Headline
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_headline_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'style' => 1,
				'title' => '',
				'link' => '',
				'border_bottom' => 'yes',
				'border_top' => 'yes'
				), $atts ) );
		
		$return = '<div class="clear"></div>';
		if ($border_top == 'yes') $return .= '<div class="hr-bullet"></div>';
		$return .= '<div class="custom-headline">';
		
		if ($title && $link) $return .= '<h1><a href="'.$link.'">'.$title.'</a></h1>';
		else $return .= '<h1>'.$title.'</h1>'; 
		
		if ($content) $return .= '<p>'.$content.'</p>'; 
		
		$return .= '</div>';
		if ($border_bottom == 'yes') $return .= '<div class="hr-bullet"></div>';	
		return $return;
	}
	
	/**
	 * Shortcode: Contact form
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_contact_form_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'email_address' => ''
				), $atts ) );
		
		$return = '<div class="clear"></div>';
		$randID = rand();
		//If the form is submitted
		if(isset($_POST['submitted'])) {
		
			//Check to see if the honeypot captcha field was filled in
			if(trim($_POST['checking']) !== '') {
				$captchaError = true;
			} else {
			
				//Check to make sure that the name field is not empty
				if(trim($_POST['contactName']) === '') {
					$nameError = 'You forgot to enter your name.';
					$hasError = true;
				} else {
					$name = trim($_POST['contactName']);
				}
				
				//Check to make sure sure that a valid email address is submitted
				if(trim($_POST['email']) === '')  {
					$emailError = 'You forgot to enter your email address.';
					$hasError = true;
				} else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['email']))) {
					$emailError = 'You entered an invalid email address.';
					$hasError = true;
				} else {
					$email = trim($_POST['email']);
				}
					
				//Check to make sure comments were entered	
				if(trim($_POST['comments']) === '') {
					$commentError = 'You forgot to enter your comments.';
					$hasError = true;
				} else {
					if(function_exists('stripslashes')) {
						$comments = stripslashes(trim($_POST['comments']));
					} else {
						$comments = trim($_POST['comments']);
					}
				}
					
				//If there is no error, send the email
				if(!isset($hasError)) {
					$emailTo = $email_address;
					if (!isset($emailTo) || ($emailTo == '') ){
						$emailTo = get_option('admin_email');
					}
					$subject = 'From '.$name;
					$body = "Name: $name \n\nEmail: $email \n\nComments: $comments";
					$headers = 'From: My Site <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;
					
					wp_mail($emailTo, $subject, $body, $headers);
					$emailSent = true;
		
				}
			}
		}
		$return .= '<script type="text/javascript">var $j = jQuery.noConflict();$j(document).ready(function(){$j("#contactFormMini-'.$randID.'").validate();});</script>';
		
		if(isset($emailSent) && $emailSent == true) {
			$form_finished = 'form-finished';
            $return .= '<div class="thanks">';
                $return .= '<h3>Thank you, '.$name.' !</h3>';
                $return .= '<p>'.$contact_page_success.'</p>';
            $return .= '</div>';
        }
            
        if(isset($hasError) || isset($captchaError)) {
            $return .= '<p class="error">'.$contact_page_error.'</p>';
        }
		
		if(isset($_POST['contactName'])) $contact_name_ech = $_POST['contactName']; 
		if(isset($_POST['email']))  $contact_email_ech = $_POST['email'];
		if(isset($_POST['comments'])) { 
			if(function_exists('stripslashes')) { 
				$contact_comments_ech = stripslashes($_POST['comments']); 
			} else { 
				$contact_comments_ech = $_POST['comments']; 
			}
		}
		if(isset($_POST['checking']))  $contact_checking_ech = $_POST['checking'];
		
		$return .= '<form action="'.get_permalink().'" id="contactFormMini-'.$randID.'" method="post">';
			$return .= '<ul class="contact-form '.$form_finished.' mini">';
				$return .= '<li>';
					$return .='<label for="contactName">Name</label>';
					$return .='<input type="text" name="contactName" id="contactName" value="'.$contact_name_ech.'" class="required" />';
				$return .= '</li>';    
				$return .= '<li>';   
					$return .='<label for="email">Email</label>';
					$return .='<input type="text" name="email" id="email" value="'.$contact_email_ech.'" class="required email" />';
				$return .= '</li>';
				$return .= '<li class="textarea">';
					$return .= '<label for="commentsText">Comments</label>';
					$return .= '<textarea name="comments" id="commentsText" rows="20" cols="30" class="required">'.$contact_comments_ech.'</textarea>';
				$return .= '</li>';
				$return .= '<li class="screenReader">';
					$return .= '<label for="checking" class="screenReader">If you want to submit this form, do not enter anything in this field</label>';
					$return .= '<input type="text" name="checking" id="checking" class="screenReader" value="'.$contact_comments_ech.'" />';
				$return .= '</li>';
				$return .= '<li class="buttons">';
					$return .= '<input type="hidden" name="submitted" id="submitted" value="true" />';
					$return .= '<button type="submit">Send email</button>';
				$return .= '</li>';
			$return .= '</ul>';
		$return .= '</form>';

		return $return;
	}

	/**
	 * Shortcode: heading
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_heading_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'style' => 1
				), $atts ) );
		if ($style == '5') {
		return '<div class="su-heading su-heading-style-' . $style . '"><div class="su-heading-shell"><span>' . $content . '</span></div></div>';
		} else {
		return '<div class="su-heading su-heading-style-' . $style . '"><div class="su-heading-shell">' . $content . '</div></div>';
		}
	}

	/**
	 * Shortcode: frame
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_frame_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'align' => 'none'
				), $atts ) );

		return '<div class="su-frame su-frame-align-' . $align . '"><div class="su-frame-shell">' . do_shortcode( $content ) . '</div></div>';
	}

	/**
	 * Shortcode: tabs
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_tabs_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
				'style' => 1
				), $atts ) );

		$GLOBALS['tab_count'] = 0;

		do_shortcode( $content );

		if ( is_array( $GLOBALS['tabs'] ) ) {
			foreach ( $GLOBALS['tabs'] as $tab ) {
				$tabs[] = '<span>' . $tab['title'] . '</span>';
				$panes[] = '<div class="su-tabs-pane">' . $tab['content'] . '</div>';
			}
			$return = '<div class="su-tabs su-tabs-style-' . $style . '"><div class="su-tabs-nav">' . implode( '', $tabs ) . '</div><div class="su-tabs-panes">' . implode( "\n", $panes ) . '</div><div class="su-spacer"></div></div>';
		}

		// Unset globals
		unset( $GLOBALS['tabs'], $GLOBALS['tab_count'] );

		return $return;
	}

	/**
	 * Shortcode: tab
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_tab_shortcode( $atts, $content ) {
		extract( shortcode_atts( array( 'title' => 'Tab %d' ), $atts ) );
		$x = $GLOBALS['tab_count'];
		$GLOBALS['tabs'][$x] = array( 'title' => sprintf( $title, $GLOBALS['tab_count'] ), 'content' => do_shortcode( $content ) );
		$GLOBALS['tab_count']++;
	}

	/**
	 * Shortcode: spoiler
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_spoiler_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'title' => __( 'Spoiler title', 'shortcodes-ultimate' ),
				'open' => false,
				'style' => 1
				), $atts ) );

		$open_class = ( $open ) ? ' su-spoiler-open' : '';
		$open_display = ( $open ) ? ' style="display:block"' : '';

		return '<div class="su-spoiler su-spoiler-style-' . $style . $open_class . '"><div class="su-spoiler-title">' . $title . '</div><div class="su-spoiler-content"' . $open_display . '>' . su_do_shortcode( $content, 's' ) . '</div></div>';
	}
	
	/**
	 * Shortcode: skills
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_skills_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'title' => __( 'Skills title', 'shortcodes-ultimate' )
				), $atts ) );
		$randid = rand();
		return '		
		<dt>' . $title . '</dt>
		<dd><span id="data-'.$randid.'">' . su_do_shortcode( $content, 's' ) . '</span></dd>
		<style type="text/css">
		#data-'.$randid.'{width:' . su_do_shortcode( $content, 's' ) . '; -webkit-animation-name:bar-'.$randid.';}
		#data-'.$randid.'{-webkit-animation-duration:0.5s;-webkit-animation-iteration-count:1;-webkit-animation-timing-function:ease-out;}
		@-webkit-keyframes bar-'.$randid.'{0%{width:0%;}100%{width:' . su_do_shortcode( $content, 's' ) . ';}}
		</style>
		';
	}
	
	/**
	 * Shortcode: skills wrapper
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_skills_wrapper_shortcode( $atts = null, $content = null ) {

		return '<dl class="chart">' . su_do_shortcode( $content, 'a' ) . '</dl>';
	}

	/**
	 * Shortcode: accordion
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_accordion_shortcode( $atts = null, $content = null ) {

		return '<div class="su-accordion">' . su_do_shortcode( $content, 'a' ) . '</div>';
	}

	/**
	 * Shortcode: divider
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_divider_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'top' => false
				), $atts ) );

		return ( $top ) ? '<div class="su-divider"><a href="#">' . __( 'Top', 'shortcodes-ultimate' ) . '</a></div>' : '<div class="su-divider"></div>';
	}
	
	/**
	 * Shortcode: sharebox
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_sharebox_shortcode( $atts, $content = null ) {

		return '<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script><div class="sharebox"><div class="twittme"><a href="https://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a></div><div class="shareface"><a name="fb_share"></a></div></div><div class="clear"></div>';
	}

	/**
	 * Shortcode: spacer
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_spacer_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'size' => 0
				), $atts ) );

		return '<div class="su-spacer" style="height:' . $size . 'px"></div>';
	}

	/**
	 * Shortcode: highlight
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_highlight_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'bg' => '#df9',
				'color' => '#000'
				), $atts ) );

		return '<span class="su-highlight" style="background:' . $bg . ';color:' . $color . '">&nbsp;' . $content . '&nbsp;</span>';
	}

	/**
	 * Shortcode: label
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_label_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'style' => 'default'
				), $atts ) );

		return '<span class="su-label su-label-style-' . $style . '">' . $content . '</span>';
	}

	/**
	 * Shortcode: dropcap
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_dropcap_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'style' => 1,
				'size' => 3
				), $atts ) );

		$em = $size * 0.5 . 'em';

		return '<span class="su-dropcap su-dropcap-style-' . $style . '" style="font-size:' . $em . '">' . $content . '</span>';
	}

	/**
	 * Shortcode: column
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_column_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'size' => '1-2',
				'last' => false,
				'style' => 0
				), $atts ) );

		return ( $last ) ? '<div class="su-column su-column-' . $size . ' su-column-last su-column-style-' . $style . '">' . su_do_shortcode( $content, 'c' ) . '</div><div class="su-spacer"></div>' : '<div class="su-column su-column-' . $size . ' su-column-style-' . $style . '">' . su_do_shortcode( $content, 'c' ) . '</div>';
	}

	/**
	 * Shortcode: list
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_list_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'style' => 'star'
				), $atts ) );

		return '<div class="su-list su-list-style-' . $style . '">' . do_shortcode( $content ) . '</div>';
	}

	/**
	 * Shortcode: quote
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_quote_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'style' => 1
				), $atts ) );

		return '<div class="su-quote su-quote-style-' . $style . '"><div class="su-quote-shell">' . do_shortcode( $content ) . '</div></div>';
	}

	/**
	 * Shortcode: pullquote
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_pullquote_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'style' => 1,
				'align' => 'left'
				), $atts ) );

		return '<div class="su-pullquote su-pullquote-style-' . $style . ' su-pullquote-align-' . $align . '">' . do_shortcode( $content ) . '</div>';
	}

	/**
	 * Shortcode: button
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_button_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'link' => '#',
				'color' => '#aaa',
				'dark' => false,
				'radius' => 'auto',
				'square' => false,
				'style' => 1,
				'size' => 3,
				'icon' => false,
				'class' => 'su-button-class',
				'target' => false
				), $atts ) );

		// Old parameter compatibility, square
		$radius = ( $square ) ? 0 : $radius;
		
		$styles = '';
		$styles = array(
			'border_radius' => ( $radius == 'auto' ) ? round( $size + 2 ) : intval( $radius ),
			'dark_color' => su_hex_shift( $color, 'darker', 20 ),
			'light_color' => su_hex_shift( $color, 'lighter', 70 ),
			'size' => round( ( $size + 9 ) * 1.3 ),
			'text_color' => ( $dark ) ? su_hex_shift( $color, 'darker', 70 ) : su_hex_shift( $color, 'lighter', 90 ),
			'text_shadow' => ( $dark ) ? su_hex_shift( $color, 'lighter', 50 ) : su_hex_shift( $color, 'darker', 20 ),
			'text_shadow_position' => ( $dark ) ? '1px 1px' : '-1px -1px',
			'padding_v' => round( ( $size * 2 ) + 2 ),
			'padding_h' => round( ( ( $size * 3 ) + 10 ) )
		);
		
		$link_styles = '';
		$link_styles = array(
			'background-color' => $color,
			'border' => '1px solid ' . $styles['dark_color'],
			'border-radius' => $styles['border_radius'] . 'px',
			'-moz-border-radius' => $styles['border_radius'] . 'px',
			'-webkit-border-radius' => $styles['border_radius'] . 'px'
		);
		
		$span_styles = '';
		$span_styles = array(
			'color' => $styles['text_color'],
			'padding' => $styles['padding_v'] . 'px ' . $styles['padding_h'] . 'px',
			'font-size' => $styles['size'] . 'px',
			'height' => $styles['size'] . 'px',
			'line-height' => $styles['size'] . 'px',
			'border-top' => '1px solid ' . $styles['light_color'],
			'border-radius' => $styles['border_radius'] . 'px',
			'text-shadow' => $styles['text_shadow_position'] . ' 0 ' . $styles['text_shadow'],
			'-moz-border-radius' => $styles['border_radius'] . 'px',
			'-moz-text-shadow' => $styles['text_shadow_position'] . ' 0 ' . $styles['text_shadow'],
			'-webkit-border-radius' => $styles['border_radius'] . 'px',
			'-webkit-text-shadow' => $styles['text_shadow_position'] . ' 0 ' . $styles['text_shadow']
		);
		
		$img_styles = '';
		$img_styles = array(
			'margin' => '0 ' . round( $size * 0.7 ) . 'px -' . round( ( $size * 0.3 ) + 4 ) . 'px -' . round( $size * 0.8 ) . 'px',
			'height' => ( $styles['size'] + 4 ) . 'px'
		);

		foreach ( $link_styles as $link_rule => $link_value ) {
			$link_style .= $link_rule . ':' . $link_value . ';';
		}

		foreach ( $span_styles as $span_rule => $span_value ) {
			$span_style .= $span_rule . ':' . $span_value . ';';
		}

		foreach ( $img_styles as $img_rule => $img_value ) {
			$img_style .= $img_rule . ':' . $img_value . ';';
		}

		$icon_image = ( $icon ) ? '<img src="' . $icon . '" alt="' . htmlspecialchars( $content ) . '" style="' . $img_style . '" /> ' : '';

		$target = ( $target ) ? ' target="_' . $target . '"' : '';

		return '<a href="' . $link . '" class="su-button su-button-style-' . $style . ' ' . $class . '" style="' . $link_style . '"' . $target . '><span style="' . $span_style . '">' . $icon_image . $content . '</span></a>';
	}

	/**
	 * Shortcode: fancy-link
	 *
	 * @param string $content
	 * @return string Output html
	 */
	function su_fancy_link_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'link' => '#',
				'color' => 'black'
				), $atts ) );

		return '<a class="su-fancy-link" href="' . $link . '">' . $content . ' <span class="meta-nav">&#187;</span></a>';
	}

	/**
	 * Shortcode: service
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_service_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'title' => __( 'Service name', 'shortcodes-ultimate' ),
				'icon' => su_plugin_url() . '/images/service.png',
				'size' => 32
				), $atts ) );

		return '<div class="su-service"><div class="su-service-title" style="padding:' . round( ( $size - 16 ) / 2 ) . 'px 0 ' . round( ( $size - 16 ) / 2 ) . 'px ' . ( $size + 15 ) . 'px"><img src="' . $icon . '" width="' . $size . '" height="' . $size . '" alt="' . $title . '" /> ' . $title . '</div><div class="su-service-content" style="padding:0 0 0 ' . ( $size + 15 ) . 'px">' . do_shortcode( $content ) . '</div></div>';
	}

	/**
	 * Shortcode: box
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_box_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'color' => '#333',
				'title' => __( 'This is box title', 'shortcodes-ultimate' )
				), $atts ) );

		$styles = array(
			'dark_color' => su_hex_shift( $color, 'darker', 20 ),
			'light_color' => su_hex_shift( $color, 'lighter', 60 ),
			'text_shadow' => su_hex_shift( $color, 'darker', 70 ),
		);

		return '<div class="su-box" style="border:1px solid ' . $styles['dark_color'] . '"><div class="su-box-title" style="background-color:' . $color . ';border-top:1px solid ' . $styles['light_color'] . ';">' . $title . '</div><div class="su-box-content">' . su_do_shortcode( $content, 'b' ) . '</div></div>';
	}

	/**
	 * Shortcode: note
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_note_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'color' => '#fc0'
				), $atts ) );

		$styles = array(
			'dark_color' => su_hex_shift( $color, 'darker', 10 ),
			'light_color' => su_hex_shift( $color, 'lighter', 20 ),
			'extra_light_color' => su_hex_shift( $color, 'lighter', 80 ),
			'text_color' => su_hex_shift( $color, 'darker', 70 )
		);

		return '<div class="su-note" style="background-color:' . $styles['light_color'] . ';border:1px solid ' . $styles['dark_color'] . '"><div class="su-note-shell" style="border:1px solid ' . $styles['extra_light_color'] . ';color:' . $styles['text_color'] . '">' . su_do_shortcode( $content, 'n' ) . '</div></div>';
	}

	/**
	 * Shortcode: private
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_private_shortcode( $atts = null, $content = null ) {

		if ( current_user_can( 'publish_posts' ) )
			return '<div class="su-private"><div class="su-private-shell">' . do_shortcode( $content ) . '</div></div>';
	}

	/**
	 * Shortcode: media
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_media_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'url' => '',
				'width' => 600,
				'height' => 400,
				'jwplayer' => false
				), $atts ) );

		if ( $jwplayer ) {
			$jwplayer = str_replace( '#038;', '&', $jwplayer );
			parse_str( $jwplayer, $jwplayer_options );
		}

		$return = '<div class="su-media">';
		$return .= ( $url ) ? su_get_media( $url, $width, $height, $jwplayer_options ) : __( 'Please specify media url', 'shortcodes-ultimate' );
		$return .= '</div>';

		return $return;
	}

	/**
	 * Shortcode: table
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_table_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'style' => 1,
				'file' => false
				), $atts ) );

		$return = '<div class="su-table su-table-style-' . $style . '">';
		$return .= ( $file ) ? su_parse_csv( $file ) : do_shortcode( $content );
		$return .= '</div>';

		return $return;
	}

	/**
	 * Shortcode: pricing
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_pricing_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'style' => 1
				), $atts ) );

		$return = '<div class="su-pricing su-pricing-style-' . $style . '">';
		$return .= do_shortcode( $content );
		$return .= '<div class="su-spacer"></div></div>';

		return $return;
	}

	/**
	 * Shortcode: plan
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_plan_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'name' => '&hellip;',
				'price' => '$100',
				'per' => 'per month',
				'width' => 150,
				'primary' => false,
				'class' => false
				), $atts ) );

		$custom_classes = ( $primary ) ? ' su-plan-primary' : '';
		$custom_classes .= ( $class ) ? ' ' . $class : '';

		$return = '<div class="su-plan' . $custom_classes . '" style="width:' . $width . 'px">';
		$return .= '<div class="su-plan-name">' . $name . '</div>';
		$return .= '<div class="su-plan-price">' . $price . '<span class="su-sep">/</span><span class="su-per">' . $per . '</span></div>';
		$return .= '<div class="su-plan-content">';
		$return .= do_shortcode( $content );
		$return .= '</div><div class="su-plan-footer"></div></div>';

		return $return;
	}

	/**
	 * Shortcode: flexslider
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_flexslider_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'source' => 'post',
				'link' => 'image',
				'size' => '940x450',
				'limit' => 10,
				'effect' => 'slide'
				), $atts ) );

		// Get dimensions
		$dimensions = explode( 'x', strtolower( $size ) );
		$width = $dimensions[0];
		$height = $dimensions[1];

		// Define unique slider ID
		$slider_id = uniqid( 'su-flexslider_' );

		// Get slides
		$slides = su_build_gallery( $source, $link, $size, $limit );

		// If slides exists
		if ( count( $slides ) > 1 ) {

			$return = '<script type="text/javascript">jQuery(window).load(function(){jQuery(".' . $slider_id . '.flexslider.slideshow").flexslider({animation:"' . $effect . '", directionNav: true, controlNav: false,start: function(slider) {slider.removeClass("loading");}});});</script>';

			$return .= '<div class="flexslider-wrapper"><div class="' . $slider_id . ' flexslider slideshow loading" style="width:' . $width . 'px;height:' . $height . 'px"><div class="slideshow-top-shadow" style="width:' . $width . 'px;"></div><ul class="slides">';
			
			foreach ( $slides as $slide ) {
				$return .= '<li><a href="' . $slide['link'] . '" title="' . $slide['name'] . '"><img src="' . $slide['thumbnail'] . '" data-thumb="' . $slide['thumbnail'] . '" alt="' . $slide['name'] . '" width="' . $width . '" height="' . $height . '" /></a></li>';
			}
			$return .= '</ul></div></div><div class="clearfix"></div>';
		}

		// No slides
		else {
			$return = '<p class="su-error"><strong>Flexslider:</strong> ' . __( 'no attached images, or only one attached image', 'shortcodes-ultimate' ) . '&hellip;</p>';
		}

		return $return;
	}

	/**
	 * Shortcode: flexcarousel
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_flexcarousel_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'source' => 'post',
				'link' => 'image',
				'size' => '150x150',
				'limit' => 10,
				'items' => 3,
				'speed' => 400,
				'margin' => 20
				), $atts ) );

		// Get dimensions
		$dimensions = explode( 'x', strtolower( $size ) );
		$width = $dimensions[0];
		$height = $dimensions[1];

		$widthli = $dimensions[0]+8;
		$heightli = $dimensions[1]+8;

		// Calculate width
		$container_width = round( ( ( $widthli + $margin ) * $items ) - $margin );

		// Define unique carousel ID
		$carousel_id = uniqid( 'su-flexcarousel_' );

		// Get slides
		$slides = su_build_gallery( $source, $link, $size, $limit );

		// If has attachments
		if ( count( $slides ) > 1 ) {

			$return = '<script type="text/javascript">';
			$return .= 'jQuery(document).ready(function(){jQuery(".custom-wrapper.flexslider.carousel.' . $carousel_id . '").flexslider({';
			$return .= 'animation: "slide", move:1, itemWidth: '.$widthli.',itemMargin: '.$margin.',controlNav: false,fixedHeightMiddleAlign: true';
			$return .= '});});</script>';

			$return .= '<div class="custom-wrapper flexslider carousel ' . $carousel_id . '" ><ul class="slides">';

			foreach ( $slides as $slide ) {
				$return .= '<li style="width:' . $widthli . 'px;;margin-right:' . $margin . 'px"><a href="' . $slide['link'] . '" title="' . $slide['name'] . '"><img src="' . $slide['thumbnail'] . '" alt="' . $slide['name'] . '" width="' . $width . '" height="' . $height . '" /></a></li>';
			}
			$return .= '</ul></div>';
		}

		// No attachments
		else {
			$return = '<p class="su-error"><strong>flexcarousel:</strong> ' . __( 'no attached images, or only one attached image', 'shortcodes-ultimate' ) . '&hellip;</p>';
		}

		return $return;
	}

	/**
	 * Shortcode: custom_gallery
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_custom_gallery_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'style' => 1,
				'source' => 'post',
				'link' => 'lightbox',
				'description' => false,
				'size' => '200x200',
				'limit' => 10
				), $atts ) );

		// Get dimensions
		$dims = explode( 'x', strtolower( $size ) );
		$width = $dims[0];
		$height = $dims[1];

		// Define unique gallery ID
		$gallery_id = uniqid( 'su-custom-gallery_' );

		// Get slides
		$slides = su_build_gallery( $source, $link, $size, $limit );

		// If slides exists
		if ( count( $slides ) > 1 ) {

			$return = '<div id="' . $gallery_id . '" class="su-custom-gallery su-custom-gallery-style-' . $style . '">';
			foreach ( $slides as $slide ) {

				// Description
				$desc = ( $description ) ? '<span>' . $slide['description'] . '</span>' : false;
				$return .= '<a class="su-custom-gallery-item" style="width:' . $width . 'px;" href="' . $slide['link'] . '" title="' . $slide['name'] . '"><img src="' . $slide['thumbnail'] . '" alt="' . $slide['name'] . '" width=" ' . $width . ' " height="' . $height . '" />' . $desc . '</a>';
			}
			$return .= '<div class="su-spacer"></div></div>';
		}

		// No slides
		else {
			$return = '<p class="su-error"><strong>Custom gallery:</strong> ' . __( 'no attached images, or only one attached image', 'shortcodes-ultimate' ) . '&hellip;</p>';
		}

		return $return;
	}

	/**
	 * Shortcode: permalink
	 *
	 * @param string $content
	 * @return string Output html
	 */
	function su_permalink_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'p' => 1,
				'target' => ''
				), $atts ) );

		$text = ( $content ) ? $content : get_the_title( $p );
		$tgt = ( $target ) ? ' target="_' . $target . '"' : '';

		return '<a href="' . get_permalink( $p ) . '" title="' . $text . '"' . $tgt . '>' . $text . '</a>';
	}

	/**
	 * Shortcode: bloginfo
	 *
	 * @param string $content
	 * @return string Output html
	 */
	function su_bloginfo_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'option' => 'name'
				), $atts ) );

		return get_bloginfo( $option );
	}

	/**
	 * Shortcode: subpages
	 *
	 * @param string $content
	 * @return string Output html
	 */
	function su_subpages_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'depth' => 1,
				'p' => false
				), $atts ) );

		global $post;

		$child_of = ( $p ) ? $p : get_the_ID();

		$return = wp_list_pages( array(
			'title_li' => '',
			'echo' => 0,
			'child_of' => $child_of,
			'depth' => $depth
			) );

		return ( $return ) ? '<ul class="su-subpages">' . $return . '</ul>' : false;
	}

	/**
	 * Shortcode: siblings pages
	 *
	 * @param string $content
	 * @return string Output html
	 */
	function su_siblings_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'depth' => 1
				), $atts ) );

		global $post;

		$return = wp_list_pages( array(
			'title_li' => '',
			'echo' => 0,
			'child_of' => $post->post_parent,
			'depth' => $depth,
			'exclude' => $post->ID
			) );

		return ( $return ) ? '<ul class="su-siblings">' . $return . '</ul>' : false;
	}

	/**
	 * Shortcode: menu
	 *
	 * @param string $content
	 * @return string Output html
	 */
	function su_menu_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'name' => 1
				), $atts ) );

		$return = wp_nav_menu( array(
			'echo' => false,
			'menu' => $name,
			'container' => false,
			'fallback_cb' => 'su_menu_shortcode_fb_cb'
			) );

		return ( $name ) ? $return : false;
	}

	/**
	 * Fallback callback function for menu shortcode
	 *
	 * @return string Text message
	 */
	function su_menu_shortcode_fb_cb() {
		return __( 'This menu doesn\'t exists, or has no elements', 'shortcodes-ultimate' );
	}

	/**
	 * Shortcode: document
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_document_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'width' => 600,
				'height' => 400,
				'file' => ''
				), $atts ) );

		return '<iframe src="http://docs.google.com/viewer?embedded=true&url=' . $file . '" width="' . $width . '" height="' . $height . '" class="su-document"></iframe>';
	}

	/**
	 * Shortcode: gmap
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_gmap_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'width' => 600,
				'height' => 400,
				'address' => 'Russia, Moscow'
				), $atts ) );

		return '<iframe width="' . $width . '" height="' . $height . '" src="http://maps.google.com/maps?q=' . urlencode( $address ) . '&amp;output=embed" class="su-gmap"></iframe>';
	}

	/**
	 * Shortcode: members
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_members_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'style' => 1,
				'login' => 1
				), $atts ) );

		// Logged user
		if ( is_user_logged_in() && !is_null( $content ) && !is_feed() ) {
			return do_shortcode( $content );
		}

		// Not logged user, show login message
		elseif ( $login == 1 ) {
			return '<div class="su-members su-members-style-' . $style . '"><span class="su-members-shell">' . __( 'This content is for members only.', 'shortcodes-ultimate' ) . ' <a href="' . wp_login_url( get_permalink( get_the_ID() ) ) . '">' . __( 'Please login', 'shortcodes-ultimate' ) . '</a>.' . '</span></div>';
		}
	}

	/**
	 * Shortcode: guests
	 *
	 * @param string $content
	 * @return string Output html
	 */
	function su_guests_shortcode( $atts = null, $content = null ) {

		// Logged user
		if ( !is_user_logged_in() && !is_null( $content ) ) {
			return '<div class="su-guests">' . do_shortcode( $content ) . '</div>';
		}
	}

	/**
	 * Shortcode: feed
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_feed_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'url' => get_bloginfo_rss( 'rss2_url' ),
				'limit' => 3
				), $atts ) );

		include_once( ABSPATH . WPINC . '/rss.php' );

		return '<div class="su-feed">' . wp_rss( $url, $limit ) . '</div>';
	}

	/**
	 * Shortcode: tweets
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string Output html
	 */
	function su_tweets_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'username' => 'twitter',
				'limit' => 3,
				'style' => 1,
				'show_time' => 1
				), $atts ) );

		$return = '<div class="su-tweets su-tweets-style-' . $style . '">';
		$return .= su_get_tweets( $username, $limit, $show_time );
		$return .= '</div>';

		return $return;
	}
	
/**
 * Recent Posts Shortcode
 */
function su_recent_posts_shortcode($atts) {
	extract(shortcode_atts(array(		
		"title" => "",
		"limit" => "5", //number of posts to show
		"cats" => "", //show posts only from the follwing categories
		"wordscount" => "20" //excerpt limit words
	), $atts));
	
	if( $cats != '' )
		$cats = explode( ',', $cats );

	$recent = new WP_Query( array( 'posts_per_page' => $limit, 'category__in' => $cats ) );
	
	$return = '<div class="widget_recent_entries">';
	
	if( $title != "" )
		$return .= '<h3 class="widget-title">' . $title . '</h3>';
	else
		$return .= '';
	
	$return .= '<ul>';

	while ($recent->have_posts()) : $recent->the_post();

		$return .= '<li>';
		$return .= '<a href="' . get_permalink(get_the_ID()) . '" title="' . get_the_title() . '" class="post-title">' . get_the_title() . '</a>';
		if( intval( $wordscount) > 0 )
			$return .= '<p class="excerpt">' . cumico_custom_excerpt( get_the_content(), $wordscount ) . '</p>';
		$return .= '</li>';

	endwhile;
	
	$return .= '</ul>';
	$return .= '</div>';
	
	wp_reset_query();
				
	return $return;
}

/**
 * Popular Posts Shortcode
 */
function su_popular_posts_shortcode($atts) {
	extract(shortcode_atts(array(		
		"title" => "",
		"limit" => "5", //number of posts to show
		"cats" => "", //show posts only from the follwing categories
		"wordscount" => "20" //excerpt limit words
	), $atts));
	
	if( $cats != '' )
		$cats = explode( ',', $cats );

	$popular = new WP_Query( array( 'orderby' => 'comment_count', 'posts_per_page' => $limit, 'category__in' => $cats ) );
	
	$return = '<div class="widget_recent_entries">';
	
	if( $title != "" )
		$return .= '<h3 class="widget-title">' . $title . '</h3>';
	else
		$return .= '';
	
	$return .= '<ul>';

	while ($popular->have_posts()) : $popular->the_post();

		$return .= '<li>';
		$return .= '<a href="' . get_permalink(get_the_ID()) . '" title="' . get_the_title() . '" class="post-title">' . get_the_title() . '</a>';
		if( intval( $wordscount) > 0 )
			$return .= '<p class="excerpt">' . cumico_custom_excerpt( get_the_content(), $wordscount ) . '</p>';
		$return .= '</li>';

	endwhile;
	
	$return .= '</ul>';
	$return .= '</div>';
	
	wp_reset_query();
				
	return $return;
}

/**
 * Portfolio Recent Posts Shortcode
 */
function su_portfolio_recent_posts_shortcode($atts) {
	extract(shortcode_atts(array(		
		"title" => "",
		"limit" => "5", //number of posts to show
		"cats" => "", //show posts only from the follwing categories
		"wordscount" => "20" //excerpt limit words
	), $atts));
	
	if( $cats != '' )
		$cats = explode( ',', $cats );

	$portfolio_posts_to_query = get_objects_in_term( $cats, 'portfolio_category');
	$recent = new WP_Query( array( 'post_type' => 'portfolio_pt', 'posts_per_page' => $limit, 'post__in' => $portfolio_posts_to_query ) );
	
	$return = '<div class="widget_recent_entries">';
	
	if( $title != "" )
		$return .= '<h3 class="widget-title">' . $title . '</h3>';
	else
		$return .= '';
	
	$return .= '<ul>';

	while ($recent->have_posts()) : $recent->the_post();

		$return .= '<li>';
		$return .= '<a href="' . get_permalink(get_the_ID()) . '" title="' . get_the_title() . '" class="post-title">' . get_the_title() . '</a>';
		if( intval( $wordscount) > 0 )
			$return .= '<p class="excerpt">' . cumico_custom_excerpt( get_the_content(), $wordscount ) . '</p>';
		$return .= '</li>';

	endwhile;
	
	$return .= '</ul>';
	$return .= '</div>';
	
	wp_reset_query();
				
	return $return;
}

/**
 * Portfolio Popular Posts Shortcode
 */
function su_portfolio_popular_posts_shortcode($atts) {
	extract(shortcode_atts(array(		
		"title" => "",
		"limit" => "5", //number of posts to show
		"cats" => "", //show posts only from the follwing categories
		"wordscount" => "20" //excerpt limit words
	), $atts));
	
	if( $cats != '' )
		$cats = explode( ',', $cats );

	$portfolio_posts_to_query = get_objects_in_term( $cats, 'portfolio_category');
	$popular = new WP_Query( array( 'orderby' => 'comment_count', 'post_type' => 'portfolio_pt', 'posts_per_page' => $limit, 'post__in' => $portfolio_posts_to_query ) );
	
	$return = '<div class="widget_recent_entries">';
	
	if( $title != "" )
		$return .= '<h3 class="widget-title">' . $title . '</h3>';
	else
		$return .= '';
	
	$return .= '<ul>';

	while ($popular->have_posts()) : $popular->the_post();

		$return .= '<li>';
		$return .= '<a href="' . get_permalink(get_the_ID()) . '" title="' . get_the_title() . '" class="post-title">' . get_the_title() . '</a>';
		if( intval( $wordscount) > 0 )
			$return .= '<p class="excerpt">' . cumico_custom_excerpt( get_the_content(), $wordscount ) . '</p>';
		$return .= '</li>';

	endwhile;
	
	$return .= '</ul>';
	$return .= '</div>';
	
	wp_reset_query();
				
	return $return;
}

/**
 * Portfolio Posts by category Shortcode
 */
function su_portfolio_posts_shortcode($atts) {
	extract(shortcode_atts(array(		
		"title" => "",
		"carousel_mode" => "yes", //true, false
		"columns" => "4", //3,4,5
		"limit" => "5", //number of posts to show
		"cats" => "", //show posts only from the follwing categories
		'orderby' => 'date',
		'order' => 'DESC',
		'slideshow' => 'false'
	), $atts));
	
	$layout_width = of_get_option('layout_width');
	$portfolio_col_class="three";
	$portfolio_img_dim = "portfolio-thumbnail-3-col";
	$portfolio_col_no = "4";
	
	if ( $columns != '' ) { 
		if ($columns == '3') $portfolio_col_no = "3";
		elseif ($columns == '4') $portfolio_col_no = "4";
		elseif ($columns == '5') $portfolio_col_no = "5";
	} else { $portfolio_col_no = "4";}
	
	if( $cats != '' )
		$cats = explode( ',', $cats );

	$portfolio_posts_to_query = get_objects_in_term( $cats, 'portfolio_category');
	$rand_ID = rand();
	
	$portfolio = new WP_Query( array( 'post_type' => 'portfolio_pt', 'posts_per_page' => $limit, 'post__in' => $portfolio_posts_to_query, 'orderby' => $orderby, 'order' => $order ) );
	
	if ($carousel_mode == "yes")
		$return = '<div class="custom-wrapper flexslider carousel uq'.$rand_ID.'">';
	else
	$return = '<div class="custom-wrapper">';
	
	
	if( $title != "" )
		$return .= '<h3 class="widget-title">' . $title . '</h3>';
	else
		$return .= '';
	
	$return .= '<ul class="slides">';
	$iterate = 0;
	$margins = '';
	while ($portfolio->have_posts()) : $portfolio->the_post(); $iterate++;
		
		if ($carousel_mode !== "yes") {
			if ( $iterate % $portfolio_col_no == 0 ) $margins = ' last';
			elseif ( ( $iterate - 1 ) % $portfolio_col_no == 0 ) $margins = ' first';
			else $margins = '';
		}

		$return .= '<li class=" '.$portfolio_col_class.' '.$margins.' ">';
		
		$return .= ' '.get_the_post_thumbnail( get_the_ID(), $portfolio_img_dim ).' ';
        $return .= '<div class="entry-summary">';
        $return .= '<h2 class="entry-title portfolio"> <a href="' . get_permalink(get_the_ID()) . '" title="' . get_the_title() . '" class="post-title">' . get_the_title() . '</a></h2>';
        $return .= '<div class="short-description"> '.get_the_excerpt().' </div></div>';
		
		$return .= '<div class="entry-lightbox">';
		
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
				  $return .= '<a class="image-lightbox" href="'.$portfolio_lightbox_image["full_url"].'" data-rel="prettyPhoto[mixed]">View image in lightbox</a>';
				  if($i == 1) break; //display only the first image
			  }
		} 
		if ( $portfolio_video_link) {
			
			  if ($videos_found) {
				  $portfolio_video_link = htmlspecialchars($portfolio_video_link, ENT_QUOTES);
				  $return .= '<a class="video-lightbox" href="'.$portfolio_video_link.'" data-rel="prettyPhoto[mixed]">View video in lightbox</a>';
			  }
			  
		 } 
		 
		 if ( $portfolio_external_link) {
				  $return .= '<a class="external-link" href="'.$portfolio_external_link.'">Go to link</a>';
		 }
		
		$return .= '</div>';
		
		
		$return .= '</li>';

	endwhile;
	
	$return .= '</ul>';
	$return .= '</div>';
	
	if ($carousel_mode == 'yes') {
	if ($slideshow == '')
		$slideshow = 'false';

	$return .= '<script type="text/javascript">';
	$return .= 'jQuery(window).load(function(){jQuery(".custom-wrapper.flexslider.carousel.uq'.$rand_ID.'").flexslider({';
	$return .= 'animation: "slide", move:1, slideshow:'.$slideshow.', itemWidth: 220,itemMargin: 20, controlNav: false,fixedHeightMiddleAlign: true';
	$return .= '});});</script>';
	} else { $return .= ''; }
	
	wp_reset_query();
				
	return $return;
}

/**
 * Sponsors Posts Shortcode
 */
function su_sponsors_posts_shortcode($atts) {
	extract(shortcode_atts(array(		
		"title" => "",
		"limit" => "5", //number of posts to show
		"carousel_mode" => "yes", //true, false
		"columns" => "4", //3,4
		'orderby' => 'date',
		'order' => 'DESC',
		'slideshow' => 'false'
	), $atts));
	
	$layout_width = of_get_option('layout_width');
	if ( $columns != '' ) { 
		if ($columns == '3') $sponsors_col_no = "3";
		elseif ($columns == '4') $sponsors_col_no = "4";
		elseif ($columns == '5') $sponsors_col_no = "5";
	} else { $sponsors_col_no = "4";}
	
	$rand_ID = rand();
	$sponsors = new WP_Query( array( 'post_type' => 'sponsors_pt', 'posts_per_page' => $limit, 'orderby' => $orderby, 'order' => $order ) );
	
	if ($carousel_mode == "yes") $return = '<div class="custom-wrapper flexslider carousel uq'.$rand_ID.'">';
	else $return = '<div class="custom-wrapper">';
	
	if( $title != "" )
		$return .= '<h3 class="widget-title">' . $title . '</h3>';
	else
		$return .= '';
	
	$return .= '<ul class="slides">';
	$iterate = 0;
	$margins = '';
	while ($sponsors->have_posts()) : $sponsors->the_post(); $iterate++;
	
		$sponsors_external_link = rwmb_meta('gg_sponsors_external_link');
		$sponsors_hide_title = rwmb_meta('gg_sponsors_hide_title');
		
		if ($carousel_mode !== "yes") {
			if ( $iterate % $sponsors_col_no == 0 ) $margins = ' last';
			elseif ( ( $iterate - 1 ) % $sponsors_col_no == 0 ) $margins = ' first';
			else $margins = '';
		}
		
		$return .= '<li class="three '.$margins.' ">';
		
		if ($sponsors_external_link) { $return .= '<a href="'.$sponsors_external_link.'">';	}
		$return .= ' '.get_the_post_thumbnail( get_the_ID(), "sponsors-thumbnail" ).' ';
		if ($sponsors_external_link) { $return .= '</a>'; }
        
        if (!$sponsors_hide_title) {
		$return .= '<div class="entry-summary">';	
        $return .= '<h2 class="entry-title portfolio">';	
		
		if ($sponsors_external_link) { $return .= '<a href="'.$sponsors_external_link.'">'.the_title().'</a>';	}
        $return .= '</h2>';    
        $return .= '</div>';
		}
		
		$return .= '</li>';

	endwhile;
	
	$return .= '</ul>';
	$return .= '</div>';
	
	if ($carousel_mode == 'yes') {
	$layout_width = of_get_option('layout_width');
	if ($slideshow == '')
		$slideshow = 'false';	
	$return .= '<script type="text/javascript">';
	$return .= 'jQuery(window).load(function(){jQuery(".custom-wrapper.flexslider.carousel.uq'.$rand_ID.'").flexslider({';
	$return .= 'animation: "slide", move:1, slideshow: '.$slideshow.', itemWidth: 220,itemMargin: 20,controlNav: false,fixedHeightMiddleAlign: true';
	$return .= '});});</script>';
	} else { $return .= ''; }
	
	wp_reset_query();
				
	return $return;
}

/**
 * Testimonials Posts Shortcode
 */
function su_testimonials_posts_shortcode($atts) {
	extract(shortcode_atts(array(		
		"title" => "",
		"limit" => "5", //number of posts to show
		"carousel_mode" => "yes", //true, false
		"columns" => "4", //3,4
		'orderby' => 'date',
		'order' => 'DESC',
		'slideshow' => 'false'
	), $atts));
	if ( $columns != '' ) { 
		if ($columns == '3') $testimonials_col_no = "3";
		elseif ($columns == '4') $testimonials_col_no = "4";
		elseif ($columns == '5') $testimonials_col_no = "5";
	} else { $testimonials_col_no = "4";}
	$rand_ID = rand();
	$testimonials = new WP_Query( array( 'post_type' => 'testimonials_pt', 'posts_per_page' => $limit, 'orderby' => $orderby, 'order' => $order ) );
	
	if ($carousel_mode == "yes") $return = '<div class="custom-wrapper flexslider carousel testimonials-wrapper uq'.$rand_ID.'">';
	else $return = '<div class="custom-wrapper testimonials-wrapper">';
	
	if( $title != "" )
		$return .= '<h3 class="widget-title">' . $title . '</h3>';
	else
		$return .= '';
	
	$return .= '<ul class="slides">';
	$iterate = 0;
	$margins = '';
	while ($testimonials->have_posts()) : $testimonials->the_post(); $iterate++;
	
		$testimonials_author_name = rwmb_meta('gg_testimonials_author_name');
		$testimonials_author_website = rwmb_meta('gg_testimonials_author_website');
		$testimonials_content = rwmb_meta('gg_testimonials_content');
		
		if ($carousel_mode !== "yes") {
			if ( $iterate % $testimonials_col_no == 0 ) $margins = ' last';
			elseif ( ( $iterate - 1 ) % $testimonials_col_no == 0 ) $margins = ' first';
			else $margins = '';
		}

		$return .= '<li class="three '.$margins.'">';
		$return .= '<blockquote>'.$testimonials_content.'</blockquote>';
		$return .= '<div class="entry-summary">';
		$return .= ' '.get_the_post_thumbnail( get_the_ID(), "testimonials-thumbnail", array('class' => 'tesimonial-author-img') ).' ';	
		if ($testimonials_author_name) {
			$return .= '<p class="author-name">'.$testimonials_author_name.'</p>';
		}
		if ($testimonials_author_name) {
			$return .= '<a class="author-website" href="'.$testimonials_author_website.'">Website</a>';
		}
 
        $return .= '</div>';
		$return .= '</li>';

	endwhile;
	
	$return .= '</ul>';
	$return .= '</div>';
	
	if ($carousel_mode == 'yes') {
	if ($slideshow == '')
		$slideshow = 'false';	
	$return .= '<script type="text/javascript">';
	$return .= 'jQuery(window).load(function(){jQuery(".custom-wrapper.flexslider.carousel.uq'.$rand_ID.'").flexslider({';
	$return .= 'animation: "slide", move:1, slideshow: '.$slideshow.', itemWidth: 220,itemMargin: 20,controlNav: false,fixedHeightMiddleAlign: true';
	$return .= '});});</script>';
	} else { $return .= ''; }
	
	wp_reset_query();
				
	return $return;
}

/**
 * Team Posts Shortcode
 */
function su_team_posts_shortcode($atts) {
	extract(shortcode_atts(array(		
		"title" => "",
		"limit" => "5", //number of posts to show
		"carousel_mode" => "yes", //yes, no
		"columns" => "4", //3,4
		'orderby' => 'date',
		'order' => 'DESC',
		'slideshow' => 'false'
	), $atts));
	if ( $columns != '' ) { 
		if ($columns == '3') $team_col_no = "3";
		elseif ($columns == '4') $team_col_no = "4";
		elseif ($columns == '5') $team_col_no = "5";
	} else { $team_col_no = "4";}
	
	$rand_ID = rand();
	$team = new WP_Query( array( 'post_type' => 'team_pt', 'posts_per_page' => $limit, 'orderby' => $orderby, 'order' => $order ) );
	
	if ($carousel_mode == "yes") $return = '<div class="custom-wrapper flexslider carousel team-wrapper uq'.$rand_ID.'">';
	else $return = '<div class="custom-wrapper team-wrapper">';
	
	if( $title != "" )
		$return .= '<h3 class="widget-title">' . $title . '</h3>';
	else
		$return .= '';
	
	$return .= '<ul class="slides">';
	$iterate = 0;
	$margins = '';
	while ($team->have_posts()) : $team->the_post(); $iterate++;
	
		$team_member_position = rwmb_meta('gg_team_member_position');
		$team_member_desc = rwmb_meta('gg_team_member_desc');
		$team_member_twitter = rwmb_meta('gg_team_member_twitter');
		$team_member_facebook = rwmb_meta('gg_team_member_facebook');
		$team_member_flickr = rwmb_meta('gg_team_member_flickr');
		$team_member_linkedin = rwmb_meta('gg_team_member_linkedin');
		$team_member_youtube = rwmb_meta('gg_team_member_youtube');
		$team_member_website = rwmb_meta('gg_team_member_website');
		
		if ($carousel_mode !== "yes") {
			if ( $iterate % $team_col_no == 0 ) $margins = ' last';
			elseif ( ( $iterate - 1 ) % $team_col_no == 0 ) $margins = ' first';
			else $margins = '';
		}

		$return .= '<li class="three '.$margins.'">';
		$return .= ' '.get_the_post_thumbnail( get_the_ID(), "team-thumbnail" ).' ';	
		$return .= '<div class="entry-summary">';
		$return .= '<h2 class="entry-title portfolio">'.get_the_title().'</h2>';
		
		if ($team_member_position) {
			$return .= '<p class="member-position">'.$team_member_position.'</p>';
		}
		if ($team_member_desc) {
			$return .= '<p>'.$team_member_desc.'</p>';
		}
 		
		$return .= '<ul class="member-social">';
		if ($team_member_twitter) {
			$return .= '<li><a class="member-twitter" href="'.$team_member_twitter.'">Twitter</a></li>';
		}
		if ($team_member_facebook) {
			$return .= '<li><a class="member-facebook" href="'.$team_member_facebook.'">Facebook</a></li>';
		}
		if ($team_member_flickr) {
			$return .= '<li><a class="member-flickr" href="'.$team_member_flickr.'">Flickr</a></li>';
		}
		if ($team_member_linkedin) {
			$return .= '<li><a class="member-linkedin" href="'.$team_member_linkedin.'">Linkedin</a></li>';
		}
		if ($team_member_youtube) {
			$return .= '<li><a class="member-youtube" href="'.$team_member_youtube.'">Youtube</a></li>';
		}
		if ($team_member_website) {
			$return .= '<li><a class="member-website" href="'.$team_member_website.'">Personal website</a></li>';
		}
		$return .= '</ul>';
		
        $return .= '</div>';
		$return .= '</li>';

	endwhile;
	
	$return .= '</ul>';
	$return .= '</div>';
	
	if ($carousel_mode == 'yes') {
	if ($slideshow == '')
		$slideshow = 'false';	
	$return .= '<script type="text/javascript">';
	$return .= 'jQuery(window).load(function(){jQuery(".custom-wrapper.flexslider.carousel.uq'.$rand_ID.'").flexslider({';
	$return .= 'animation: "slide", move:1, slideshow: '.$slideshow.', itemWidth: 220,itemMargin: 20,controlNav: false,fixedHeightMiddleAlign: true';
	$return .= '});});</script>';
	} else { $return .= ''; }
	
	wp_reset_query();
				
	return $return;
}

/**
 * Ads Posts Shortcode
 */
function su_ads_posts_shortcode($atts) {
	extract(shortcode_atts(array(		
		"title" => "",
		'orderby' => 'date',
		'order' => 'DESC',
		"limit" => "5", //number of posts to show
		"carousel_mode" => "yes", //yes, no
		"columns" => "4", //3,4
		'slideshow' => 'false'
	), $atts));
	if ( $columns != '' ) { 
		if ($columns == '3') $ads_col_no = "3";
		elseif ($columns == '4') $ads_col_no = "4";
		elseif ($columns == '5') $ads_col_no = "5";
	} else { $ads_col_no = "4";}
	
	$rand_ID = rand();
	$ads = new WP_Query( array( 'post_type' => 'ads_pt', 'posts_per_page' => $limit, 'orderby' => $orderby, 'order' => $order ) );
	
	if ($carousel_mode == "yes") $return = '<div class="custom-wrapper flexslider carousel ads-wrapper uq'.$rand_ID.'">';
	else $return = '<div class="custom-wrapper ads-wrapper">';
	
	if( $title != "" )
		$return .= '<h3 class="widget-title">' . $title . '</h3>';
	else
		$return .= '';
	
	$return .= '<ul class="slides">';
	$iterate = 0;
	$margins = '';
	while ($ads->have_posts()) : $ads->the_post(); $iterate++;
	
		$ads_upload = rwmb_meta( 'gg_ads_upload', 'type=plupload_image&size=full'); 
		$ads_link = rwmb_meta( 'gg_ads_link' ); 

		if ($carousel_mode !== "yes") {
			if ( $iterate % $ads_col_no == 0 ) $margins = ' last';
			elseif ( ( $iterate - 1 ) % $ads_col_no == 0 ) $margins = ' first';
			else $margins = '';
		}
		
		$return .= '<li class="three '.$margins.'">';
		if ($ads_upload) {
		$return .= '<div class="ads-holder">';
		
		foreach ( $ads_upload as $ad_upload ) {
			$return .= '<div>';
			if ($ads_link) { $return .= '<a href="'.$ads_link.'" title="'.$ad_upload["title"].'">'; }
			$return .= '<img src="'.$ad_upload["url"].'" width="'.$ad_upload["width"].'" height="'.$ad_upload["height"].'" alt="'.$ad_upload["alt"].'" />';
			if ($ads_link) { $return .= '</a>'; }
			$return .= '</div>';
		}
 		
        $return .= '</div>';
		}
		$return .= '</li>';

	endwhile;
	
	$return .= '</ul>';
	$return .= '<script type="text/javascript">jQuery(".ads-holder").hover( function() {jQuery(this).stop(true).delay(200).animate({left: -1*jQuery(".ads-holder div:first-child").width()}, 800);}, function(){jQuery(this).stop(true).animate({left: "0px"}, 800);});</script> '; 
	$return .= '</div>';
	
	if ($carousel_mode == 'yes') {
	$layout_width = of_get_option('layout_width');
	if ($slideshow == '')
		$slideshow = 'false';	
	$return .= '<script type="text/javascript">';
	$return .= 'jQuery(window).load(function(){jQuery(".custom-wrapper.flexslider.carousel.uq'.$rand_ID.'").flexslider({';
	$return .= 'animation: "slide", move:1, slideshow: '.$slideshow.', itemWidth: 220,itemMargin: 20,controlNav: false,fixedHeightMiddleAlign: true';
	$return .= '});});</script>';
	} 	
	wp_reset_query();
				
	return $return;
}

/**
 * Best selling products Shortcode
 */
function su_best_selling_products_shortcode($atts) {
	global $woocommerce_loop;

	    extract( shortcode_atts( array(
			"title" 		=> "",
	        'per_page'      => '12',
	        'columns'       => '4', //3,4
			"carousel_mode" => "yes", //yes, no
			'slideshow' => 'false'
	        ), $atts ) );

	    $args = array(
	        'post_type' => 'product',
	        'post_status' => 'publish',
	        'ignore_sticky_posts'   => 1,
	        'posts_per_page' => $per_page,
	        'meta_key' 		 => 'total_sales',
	    	'orderby' 		 => 'meta_value',
	        'meta_query' => array(
	            array(
	                'key' => '_visibility',
	                'value' => array( 'catalog', 'visible' ),
	                'compare' => 'IN'
	            )
	        )
	    );

	  	ob_start();

		$products = new WP_Query( $args );

		$woocommerce_loop['columns'] = $columns;
		
		if ($carousel_mode == "yes") echo '<div class="flexslider carousel products-wrapper">';
		else echo '<div class="products-wrapper">';

		if ( $products->have_posts() ) : 

		if( $title != "" )
			echo '<h3 class="widget-title">' . $title . '</h3>';
		else
			echo'';
		
		?>
        
		<ul class="products">

			<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php woocommerce_get_template_part( 'content', 'product' ); ?>

			<?php endwhile; // end of the loop. ?>

		</ul>
        

	<?php endif; echo '</div>';
	
	if ($carousel_mode == 'yes') {
	$layout_width = of_get_option('layout_width');
	if ($slideshow == '')
		$slideshow = 'false';	
	echo '<script type="text/javascript">';
	echo 'jQuery(window).load(function(){jQuery(".products-wrapper.flexslider.carousel").flexslider({';
	echo 'animation: "slide", move:1, selector: ".products > li", slideshow: '.$slideshow.', itemWidth: 220,itemMargin: 20,controlNav: false';
	echo '});});</script>';
	} else { echo ''; }

	wp_reset_query();

	return ob_get_clean();
}

/**
 * Sale products Shortcode
 */
function su_sale_products_shortcode($atts) {
	global $woocommerce_loop;

	    extract( shortcode_atts( array(
			"title" 		=> "",
	        'per_page'      => '12',
			'orderby'       => 'date',
			'order'     	=> 'ASC',
	        'columns'       => '4',
			"carousel_mode" => "yes", //yes, no
			'slideshow' => 'false'
	        ), $atts ) );

	    $args = array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'ignore_sticky_posts'   => 1,
			'posts_per_page' => $per_page,
			'orderby' => $orderby,
			'order' => $order,
			'meta_query' => array(
				array(
					'key' => '_visibility',
					'value' => array('catalog', 'visible'),
					'compare' => 'IN'
				),
				array(
					'key' => '_sale_price',
					'value' =>  0,
					'compare'   => '>',
					'type'      => 'NUMERIC'
				)
			)
		);

	  	ob_start();

		$products = new WP_Query( $args );

		$woocommerce_loop['columns'] = $columns;
		
		if ($carousel_mode == "yes") echo '<div class="flexslider carousel products-wrapper uniq-sale-products">';
		else echo '<div class="products-wrapper">';

		if ( $products->have_posts() ) : 

		if( $title != "" )
			echo '<h3 class="widget-title">' . $title . '</h3>';
		else
			echo'';
		
		?>

		<ul class="products">

			<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php woocommerce_get_template_part( 'content', 'product' ); ?>

			<?php endwhile; // end of the loop. ?>

		</ul>

	<?php endif; echo '</div>';
	
	if ($carousel_mode == 'yes') {
	$layout_width = of_get_option('layout_width');
	if ($slideshow == '')
		$slideshow = 'false';
	echo '<script type="text/javascript">';
	echo 'jQuery(window).load(function(){jQuery(".products-wrapper.flexslider.carousel.uniq-sale-products").flexslider({';
	echo 'animation: "slide", move:1, selector: ".products > li", slideshow: '.$slideshow.', itemWidth: 220,itemMargin: 20,controlNav: false,fixedHeightMiddleAlign: true';
	echo '});});</script>';
	} else { echo ''; }

	wp_reset_query();

	return ob_get_clean();
}


?>