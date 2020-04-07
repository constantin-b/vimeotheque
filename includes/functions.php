<?php

namespace Vimeotheque;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**************************************************************************************
 * TEMPLATING
 **************************************************************************************/

/**
 * Outputs the tag list for custom post type video
 * 
 * @param string $before
 * @param string $sep
 * @param string $after
 * @return null
 */
function cvm_the_tags( $before = null, $sep, $after, $id = 0 ){
	if ( null === $before )
		$before = __('Tags: ', 'cvm_video');
	echo cvm_get_the_tag_list($before, $sep, $after, $id = 0);	
}

/**
 * Gets the tag list for custom post type video
 * 
 * @param string $before
 * @param string $sep
 * @param string $after
 * @param int $id
 * @return
 */
function cvm_get_the_tag_list($before, $sep, $after, $id = 0){
	return apply_filters(
		'cvm_the_tags',
		get_the_term_list(
			$id,
			Plugin::instance()->get_cpt()->get_tag_tax(),
			$before,
			$sep,
			$after
		),
		$before,
		$sep,
		$after,
		$id
	);
}

/**
 * Outputs default player data
 */
function cvm_output_player_data( $echo = true ){
	$player = get_player_settings();
	$attributes = cvm_data_attributes( $player, $echo );	
	return $attributes;
}

/**
 * Output video parameters as data-* attributes
 *
 * @param $attributes
 * @param bool $echo
 *
 * @return string
 */
function cvm_data_attributes( $attributes, $echo = false ){
	$result = [];
	// these variables are not needed by js and will be skipped
	$exclude = [ 'video_position', 'aspect_override' ];
	// loop attributes
	foreach( $attributes as $key=>$value ){
		// skip values from $exclude
		if( in_array( $key, $exclude ) ){
			continue;
		}		
		$result[] = sprintf( 'data-%s="%s"', $key, $value );
	}
	if( $echo ){
		echo implode(' ', $result);
	}else{
		return implode(' ', $result);
	}	
}

/**
 * Outputs the default player size
 *
 * @param string $before
 * @param string $after
 * @param bool $echo
 *
 * @return string
 */
function cvm_output_player_size( $before = ' style="', $after='"', $echo = true ){
	$player = get_player_settings();
	$height = cvm_player_height($player['aspect_ratio'], $player['width']);
	$output = 'width:'.$player['width'].'px; height:'.$height.'px;';
	if( $echo ){
		echo $before.$output.$after;
	}
	
	return $before.$output.$after;
}

/**
 * Output width according to player
 *
 * @param string $before
 * @param string $after
 * @param bool $echo
 *
 * @return string
 */
function cvm_output_width( $before = ' style="', $after='"', $echo = true ){
	$player = get_player_settings();
	if( $echo ){
		echo $before.'width: '.$player['width'].'px; '.$after;
	}
	return $before.'width: '.$player['width'].'px; '.$after;
}


/**
 * Outputs the HTML for embedding videos on single posts.
 *
 * @param bool $echo
 *
 * @return string
 */
function embed_html( $echo = true ){
	
	global $post;
	if( !$post ){
		return;
	}

	$e = get_video_embed_html( $post, $echo );
	return $e;
}

/**
 * Displayes or returns the HTML needed to embed the video player
 *
 * @param int/WP_Post $post - the post ( ID or WP_Post object ) that needs to display the player
 * @param bool $echo - output the result
 *
 * @return string|void
 */
function get_video_embed_html( $post, $echo = true ){
	$_post = Helper::get_video_post( $post );

	if( !$_post->video_id ){
		return;
	}

	/**
	 * Allow embed settings filtering that can change the embedding options
	 * when the post is displayed.
	 *
	 * @var array
	 *
	 * @param array $embed_settings - the post video embed settings
	 * @param object $post - the current post being displayed
	 * @param array $video - the video details as retrieved from Vimeo
	 */
	$settings = apply_filters(
		'cvm_video_embed_settings',
		$_post->get_embed_options( true ),
		get_post( $post ),
		$_post->get_video_data()
	);

	$settings['video_id'] = $_post->video_id;

	/**
	 * @deprecated - Use cvm_embed_width
	 */
	$width 	= apply_filters(
		'cvm-embed-width',
		$settings['width'],
		$_post->get_video_data(),
		'manual_embed'
	);

	/**
	 * Filter that can be used to modify the width of the embed
	 * @var int
	 */
	$width 	= apply_filters(
		'cvm_embed_width',
		$width,
		$_post->get_video_data(),
		'manual_embed'
	);

	$height = cvm_player_height( $settings['aspect_ratio'] , $width, $settings['size_ratio'] );

	/**
	 * @deprecated - Use cvm_video_embed_css_class
	 */
	$class = apply_filters( 'cvm_video_post_css_class', [], $post );

	/**
	 * Filter on video container CSS class to add extra CSS classes
	 *
	 * Name: cvm_video_post_css_class
	 * Params: 	- an empty array
	 * 			- the post object that will embed the video
	 *
	 * @var string
	 */
	$class = apply_filters(
		'cvm_video_embed_css_class',
		$class,
		$post
	);

	$extra_css = implode( ' ', (array) $class );

	$video_data_atts = cvm_data_attributes( $settings );

	// if js embedding not allowed, embed by placing the iframe dirctly into the post content
	$embed_html = '<!-- video container -->';
	$js_embed = Plugin::instance()->get_player_options()->get_option( 'js_embed' );
	if( !is_wp_error( $js_embed ) && !$js_embed ){
		$params = [
			'title' => $settings[ 'title' ],
			'byline' => $settings[ 'byline' ],
			'portrait' => $settings[ 'portrait' ],
			'loop' => $settings[ 'loop' ],
			'color' => $settings[ 'color' ],
			//'fullscreen' => $settings[ 'fullscreen' ]
		];
		$embed_url = 'https://player.vimeo.com/video/' . $_post->video_id . '?' . http_build_query( $params, '', '&' );
		$extra_css .= ' cvm_simple_embed';
		$embed_html = '<iframe src="' . $embed_url . '" width="100%" height="100%" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
	}else{
		// add player script
		cvm_enqueue_player();
	}

	$video_container = sprintf(
		'<!-- regular embed --><div class="cvm_single_video_player %s" %s style="width:%spx; height:%spx; max-width:100%%;">%s</div>',
		$extra_css,
		$video_data_atts,
		$width,
		$height,
		$embed_html
	);

	if( $echo ) {
		echo $video_container;
	}

	return $video_container;
}

/********************************************************************************
 * Utils
 ********************************************************************************/

/**
 * Returns video URL for a given video ID
 *
 * @param string $video_id
 *
 * @return string
 */
function cvm_video_url( $video_id ){
	return sprintf('https://vimeo.com/%s', $video_id);
}

/**
 * Returns embed code for a given video ID
 *
 * @param string $video_id
 *
 * @return string
 */
function cvm_video_embed( $video_id ){
	$options = get_player_settings();
	return sprintf( '<iframe src="https://player.vimeo.com/video/%s?title=%d&amp;byline=%d&amp;portrait=%d&amp;color=%s" width="%d" height="%d" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
		$video_id,
		$options['title'],
		$options['byline'],
		$options['portrait'],
		$options['color'],
		$options['width'],
		cvm_player_height($options['aspect_ratio'], $options['width'])
	);
}

/**
 * Query Vimeo for single video details
 *
 * @param string $video_id
 * @param bool $ondemand_id
 * @param string $source
 *
 * @return array|bool|string|\WP_Error
 */
function cvm_query_video( $video_id ){
	$vimeo = new Video_Import( 'video', $video_id );
	$result = $vimeo->get_feed();
	if( !$result ){
		$error = $vimeo->get_errors();
		if( is_wp_error( $error ) ){
			return $error;
		}
	}
	return $result;
}

/**
 * Utility function. Checks if a given or current post is video created by the plugin
 *
 * @param bool|int|\WP_Post $post
 *
 * @return bool
 */
function is_video( $post = false ){
	return Helper::get_video_post( $post )->is_video();
}

/**
 * Adds video player script to page
 *
 * @param bool $js_dependency
 * @param bool $css_dependency
 */
function cvm_enqueue_player( $js_dependency = false, $css_dependency = false ){
	$js_dependency = $js_dependency ? ['jquery', $js_dependency] : ['jquery'];
	wp_enqueue_script(
		'cvm-video-player',
		VIMEOTHEQUE_URL . 'assets/front-end/js/video-player' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '' ) . '.js',
		$js_dependency,
		'1.0'
	);

	$css_dependency = $css_dependency ? [ $css_dependency ] : false;
	wp_enqueue_style(
		'cvm-video-player',
		VIMEOTHEQUE_URL . 'assets/front-end/css/video-player.css',
		$css_dependency
	);
}

/**
 * Returns post video data from meta
 *
 * @param int|\WP_Post $post
 *
 * @return bool/array
 */
function cvm_get_post_video_data( $post ){
	$_post = Helper::get_video_post( $post );
	if( $_post ){
		return $_post->get_video_data();
	}

	return false;
}

/**
 * Calculate player height from given aspect ratio and width
 *
 * @param string $aspect_ratio
 * @param int $width
 * @param bool $ratio - a given ratio; will override aspect ratio if set
 *
 * @return float|int
 */
function cvm_player_height( $aspect_ratio, $width, $ratio =  false ){
	$width = absint($width);
	
	if( is_numeric($ratio) && $ratio > 0 ){
		return floor( $width / $ratio );
	}
	
	$height = 0;
	switch( $aspect_ratio ){
		case '4x3':
			$height = floor( ($width * 3) / 4 );
		break;
		case '16x9':
		default:	
			$height = floor( ($width * 9) / 16 );
		break;	
		case '2.35x1':
			$height = floor( $width / 2.35 );
		break;	
	}
	
	return $height;
}

/**
 * Available player themes
 */
function cvm_playlist_themes(){
	// @todo - create a filter that allows you to add new themes in this list
	return [
		'default' 	=> __('Default theme', 'cvm_video'),
		'carousel' 	=> __('Carousel navigation', 'cvm_video'),
		'wall'		=> __('Wall', 'cvm_video')
	];
}

/***********************************************************************************
 * General player settings (from Settings page)
 **********************************************************************************/

/**
 * Get general player settings
 */
function get_player_settings(){
	/**
	 * Options object
	 */
	$option 	= \Vimeotheque\Plugin::instance()->get_player_options()->get_options();

	// various player outputs may set their own player settings. Return those.
	global $CVM_PLAYER_SETTINGS;
	if( $CVM_PLAYER_SETTINGS ){
		foreach( $option as $k => $v ){
			if( isset( $CVM_PLAYER_SETTINGS[ $k ] ) ){
				$option[ $k ] = $CVM_PLAYER_SETTINGS[ $k ];
			}
		}
	}
	
	return $option;
}

/**************************************************************************************************
 * Post specific player settings
 **************************************************************************************************/

/**
 * Single post default settings
 */
function cvm_post_settings_defaults(){
	// general player settings
	$plugin_defaults = get_player_settings();
	return $plugin_defaults;
}

/**
 * Returns playback settings set on a video post
 */
function get_video_settings( $post_id = false, $output = false ){
	if( !$post_id ){
		global $post;
		if( !$post || !is_video($post) ){
			return false;
		}
		$post_id = $post->ID;		
	}else{
		$post = get_post( $post_id );
		if( !$post || !is_video($post) ){
			return false;
		}
	}
	
	// stores default value as set in plugin settings
	$plugin_settings = cvm_post_settings_defaults();

	// if override is on, return global embed settings
	$settings = get_player_settings();

	// get values from post
	$option = isset( $settings['allow_override'] ) && $settings['allow_override'] ?
		$settings :
		get_post_meta( $post_id, Plugin::instance()->get_cpt()->get_post_settings()->get_meta_embed_settings(), true );

	// in some cases, this might not be an array and will issue errors; let's prevent this
	if( !is_array( $option ) ){
		$option = [];
	}

	// the options that should be preserved from main plugin settings
	$get_from_main_settings = [ 'aspect_override' ];
	
	// overwrite defaults with post options
	foreach( $plugin_settings as $k => $v ){
		if( in_array( $k, $get_from_main_settings ) || !isset( $option[ $k ] ) ){
			$option[ $k ] = $v;
		}
	}
	
	if( $output ){
		foreach( $option as $k => $v ){
			if( is_bool( $v ) ){
				$option[$k] = absint( $v );
			}
		}
	}
	
	if( isset( $option['aspect_override'] ) && $option['aspect_override'] ){
		$post_meta = cvm_get_post_video_data($post_id);
		if( $post_meta && isset( $post_meta['size']['ratio'] ) ){
			$option['size_ratio'] = $post_meta['size']['ratio'];
		}else{
			$option['size_ratio'] = false;
		}		
	}else{
		$option['size_ratio'] = false;
	}
	
	return $option;	
}

/**
 * Update video playback options
 *
 * @param int $post_id
 * @param bool $values
 * @param bool $_use_defaults
 *
 * @return bool
 */
function cvm_update_video_settings( $post_id, $values = false, $_use_defaults = false ){
	
	if( !$post_id ){
		return false;
	}
	
	$post = get_post( $post_id );
	if( !$post || !is_video( $post ) ){
		return false;
	}
	
	$defaults = cvm_post_settings_defaults();
	if( $values ){
		$source = (array)$values;
	}else{
		$source = $_POST;
	}
	
	
	foreach( $defaults as $key => $val ){
		if( is_numeric( $val ) ){
			if( isset( $source[ $key ] ) ){
				$defaults[ $key ] = (int)$source[ $key ];
			}else{
				// if flagged to use the default values, just skip the setting and allow the default
				if( $_use_defaults ){
					continue;
				}
				
				// some defaults are numeric but can only have value 1 or 0
				// if so, the option is a checkbox that is unchecked, set it to 0
				if( 0 == $defaults[$key] || 1 == $defaults[$key] ){				
					$defaults[ $key ] = 0;
				}
			}
			continue;
		}
		if( is_bool( $val ) ){
			$defaults[ $key ] = isset( $source[ $key ] );
			continue;
		}
		
		if( isset( $source[ $key ] ) ){
			$defaults[ $key ] = $source[ $key ];
		}
	}	
	
	update_post_meta(
		$post_id,
		Plugin::instance()->get_cpt()
		                  ->get_post_settings()
		                  ->get_meta_embed_settings(),
		$defaults
	);
}

/**
 * Creates from a number of given seconds a readable duration ( HH:MM:SS )
 * @param int $seconds
 * @return string - formatted time
 */
function human_time( $seconds ){
	return Helper::human_time( $seconds );
}

/**
 * @param $path
 * @param string $medium
 *
 * @return string
 */
function cvm_link( $path, $medium = 'doc_link' ){
	$base = 'https://vimeotheque.com/';
	$vars = [
		'utm_source' => 'plugin',
		'utm_medium' => $medium,
		'utm_campaign' => 'vimeo-pro-plugin'
	];
	$q = http_build_query( $vars );
	return $base . trailingslashit( $path ) . '?' . $q;
}

/**
 * A simple debug function. Doesn't do anything special, only triggers an
 * action that passes the information along the way.
 * For actual debug messages, extra functions that process and hook to this action
 * are needed.
 */
function _cvm_debug_message( $message, $separator = "\n", $data = false ){
	/**
	 * Fires a debug message action
	 */
	do_action( 'cvm_debug_message', $message, $separator, $data );	
}